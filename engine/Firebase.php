<?php
/**
 * Firebase PHP Library
 *
 * This PHP library provides an interface to interact with the Paystack API,
 * enabling developers to handle payment transactions, transfers, verification,
 * and other financial operations easily.
 *
 * @version 1.0
 * @license GNU
 * @author Claude Amadu
 * @link https://github.com/claudeamadu/firebase
 * @link https://firebase.google.com/docs
 */

/**
 * Firebase Authentication
 */
class FirebaseAuth
{
    private $apiKey;
    private $baseUrl;
    private $sessionKeys;

    /**
     * Initialize Firebase Authentication
     *
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = 'https://identitytoolkit.googleapis.com/v1/accounts';
        $this->sessionKeys = [
            'accessToken' => 'firebase_access_token',
            'expiresIn' => 'firebase_token_expires',
            'refreshToken' => 'firebase_refresh_token',
            'signedIn' => 'firebase_signed_in',
            'user' => 'firebase_user',
        ];
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set session item
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    private function setSessionItem(string $key, string $value): void
    {
        $_SESSION[$this->sessionKeys[$key]] = $value;
    }

    private function getSessionItem($key)
    {
        return $_SESSION[$this->sessionKeys[$key]] ?? null;
    }

    /**
     * Refresh token
     *
     * @param string $refreshToken
     * @return mixed
     */
    public function refreshToken(string $refreshToken): mixed
    {
        $url = "https://securetoken.googleapis.com/v1/token?key={$this->apiKey}";
        $requestData = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];

        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($requestData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);

            if ($data) {
                $this->setSessionItem('accessToken', $data['access_token']);
                $this->setSessionItem('expiresIn', $data['expires_in']);
                $this->setSessionItem('refreshToken', $data['refresh_token']);
                return $data;
            } else {
                throw new Exception('Error refreshing token');
            }
        } catch (Exception $error) {
            echo 'Error refreshing token: ' . $error->getMessage();
            throw $error;
        }
    }
    /**
     * Check if token is expired
     *
     * @return boolean
     */
    public function isTokenExpired()
    {
        $token = $this->getSessionItem('expiresIn');
        if (!$token) {
            return true; // Token or expiration time not provided, consider it expired
        }

        $currentTime = time(); // Current time in seconds
        return $token < $currentTime;
    }

    /**
     * Check if user is signed in
     *
     * @return boolean
     */
    public function isUserSignedIn()
    {
        if ($this->isTokenExpired()) {
            return false;
        }
        return $this->getSessionItem('signedIn');
    }
    /**
     * Get access token
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->getSessionItem('accessToken');
    }

    /**
     * Retrieves the refresh token.
     *
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->getSessionItem('refreshToken');
    }

    /**
     * Retrieves the current user from the session and returns a new FirebaseUser object.
     *
     * @return FirebaseUser
     */
    public function currentUser()
    {
        $user = json_decode($this->getSessionItem('user'), true);
        return new FirebaseUser($user); // You need to define FirebaseUser class accordingly
    }


    /**
     * Sign up
     *
     * @param string $email
     * @param string $password
     * @return mixed
     */
    public function signUp($email, $password)
    {
        $signUpUrl = "{$this->baseUrl}:signUp?key={$this->apiKey}";
        $requestData = [
            'email' => $email,
            'password' => $password,
            'returnSecureToken' => true,
        ];

        try {
            $ch = curl_init($signUpUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);
            return $data;
        } catch (Exception $error) {
            echo 'Error signing up: ' . $error->getMessage();
            throw $error;
        }
    }


    /**
     * Sign in
     *
     * @param datatype $email
     * @param datatype $password
     * @return mixed
     */
    public function signIn($email, $password)
    {
        $signInUrl = "{$this->baseUrl}:signInWithPassword?key={$this->apiKey}";
        $requestData = [
            'email' => $email,
            'password' => $password,
            'returnSecureToken' => true,
        ];

        try {
            $ch = curl_init($signInUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);

            if ($data) {
                $this->setSessionItem('signedIn', true);
                $this->setSessionItem('accessToken', $data['idToken']);
                $this->setSessionItem('refreshToken', $data['refreshToken']);
                $user = [
                    'displayName' => $data['displayName'],
                    'profilePicture' => $data['profilePicture'],
                    'email' => $data['email']
                ];
                $this->setSessionItem('user', json_encode($user));
                return $data;
            } else {
                throw new Exception('Error signing in');
            }
        } catch (Exception $error) {
            echo 'Error signing in: ' . $error->getMessage();
            throw $error;
        }
    }

    /**
     * Sign out
     *
     * @return void
     */
    public function signOut()
    {
        unset($_SESSION[$this->sessionKeys['signedIn']]);
        unset($_SESSION[$this->sessionKeys['accessToken']]);
        unset($_SESSION[$this->sessionKeys['refreshToken']]);
        unset($_SESSION[$this->sessionKeys['expiresIn']]);
        echo 'Signed Out: true';
    }
    /**
     * Send email verification
     *
     * @param string $idToken
     * @return void
     */
    public function sendEmailVerification($idToken)
    {
        $url = "{$this->baseUrl}:sendOobCode?key={$this->apiKey}";
        $requestData = [
            'requestType' => 'VERIFY_EMAIL',
            'idToken' => $idToken,
        ];

        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);
            return $data;
        } catch (Exception $error) {
            echo 'Error sending email verification: ' . $error->getMessage();
            throw $error;
        }
    }
    /**
     * Sign in anonymously
     *
     * @return void
     */
    public function signInAnonymously()
    {
        $url = "{$this->baseUrl}:signUp?key={$this->apiKey}";
        $requestData = [
            'returnSecureToken' => true,
        ];

        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);
            if ($data) {
                $this->setSessionItem('signedIn', true);
                $this->setSessionItem('accessToken', $data['idToken']);
                $this->setSessionItem('expiresIn', $data['expiresIn']);
                $this->setSessionItem('refreshToken', $data['refreshToken']);
                return true;
            } else {
                throw new Exception('Error signing in anonymously');
            }
        } catch (Exception $error) {
            echo 'Error signing in anonymously: ' . $error->getMessage();
            throw $error;
        }
    }

    /**
     * Send password reset email
     *
     * @param string $email
     * @return void
     */
    public function sendPasswordResetEmail($email)
    {
        $url = "{$this->baseUrl}:sendOobCode?key={$this->apiKey}";
        $requestData = [
            'email' => $email,
            'requestType' => 'PASSWORD_RESET',
        ];

        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);
            return $data;
        } catch (Exception $error) {
            echo 'Error sending password reset email: ' . $error->getMessage();
            throw $error;
        }
    }
    /**
     * Confirm password reset
     *
     * @param string $oobCode 
     * @param string $newPassword 
     * @return array
     */
    public function confirmPasswordReset($oobCode, $newPassword)
    {
        $url = "{$this->baseUrl}:resetPassword?key={$this->apiKey}";
        $requestData = [
            'oobCode' => $oobCode,
            'newPassword' => $newPassword,
        ];

        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);
            return $data;
        } catch (Exception $error) {
            echo 'Error confirming password reset: ' . $error->getMessage();
            throw $error;
        }
    }

    /**
     * Change email
     *
     * @param string $idToken
     * @param string $newEmail
     * @return void
     */
    public function changeEmail(string $idToken, string $newEmail)
    {
        $url = "{$this->baseUrl}:update?key={$this->apiKey}";
        $requestData = [
            'idToken' => $idToken,
            'email' => $newEmail,
        ];

        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);
            return $data;
        } catch (Exception $error) {
            echo 'Error changing email: ' . $error->getMessage();
            throw $error;
        }
    }

    /**
     * Change password
     *
     * @param string $idToken
     * @param string $newPassword
     * @return void
     */
    public function changePassword($idToken, $newPassword)
    {
        $url = "{$this->baseUrl}:update?key={$this->apiKey}";
        $requestData = [
            'idToken' => $idToken,
            'password' => $newPassword,
            'returnSecureToken' => true,
        ];

        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);
            return $data;
        } catch (Exception $error) {
            echo 'Error changing password: ' . $error->getMessage();
            throw $error;
        }
    }

    /**
     * Confirm email verification
     *
     * @param string $oobCode
     * @return void
     */
    public function confirmEmailVerification($oobCode)
    {
        $url = "{$this->baseUrl}:resetPassword?key={$this->apiKey}";
        $requestData = [
            'oobCode' => $oobCode,
        ];

        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);
            return $data;
        } catch (Exception $error) {
            echo 'Error confirming email verification: ' . $error->getMessage();
            throw $error;
        }
    }

    /**
     * Delete account
     *
     * @param string $idToken
     * @return void
     */
    public function deleteAccount($idToken)
    {
        $url = "{$this->baseUrl}:delete?key={$this->apiKey}";
        $requestData = [
            'idToken' => $idToken,
        ];

        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);
            return $data;
        } catch (Exception $error) {
            echo 'Error deleting account: ' . $error->getMessage();
            throw $error;
        }
    }
}

/**
 * FirebaseUser
 */
class FirebaseUser
{
    public $user;
    public $displayName;
    public $email;
    public $profilePicture;

    /**
     * Initilize FirebaseUser
     *
     * @param array $user
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->displayName = $user['displayName'] ?? null;
        $this->email = $user['email'] ?? null;
        $this->profilePicture = $user['profilePicture'] ?? null;
    }
}

class FirebaseFirestore
{
    public $Token;
    public $Database;
    public $ProjectID;
    public $baseUrl;
    public $db;

    /**
     * Initilize FirebaseFirestore
     *
     * @param string $Token
     * @param string $Database
     * @param string $ProjectID
     */
    public function __construct($Token, $Database, $ProjectID)
    {
        $this->Token = $Token;
        $this->Database = $Database;
        $this->ProjectID = $ProjectID;
        $this->baseUrl = "https://firestore.googleapis.com/v1/projects/{$this->ProjectID}/databases/{$this->Database}";
        $this->db = new FirestoreDB($this->baseUrl, $this->Token);
    }
}

class FirestoreDB
{
    public $baseUrl;
    public $token;
    public $query;

    /**
     * Initilize FirestoreDB
     *
     * @param string $baseUrl
     * @param string $token
     */
    public function __construct($baseUrl, $token)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
        $this->query = new Query($baseUrl, $token);
    }
    /**
     * Execute request
     *
     * @param string $url
     * @param array $options
     * @return string
     */
    private function executeRequest($url, $options)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $options['method'],
            CURLOPT_HTTPHEADER => $options['headers'],
        ]);

        if (isset($options['body'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $options['body']);
        }

        $response = curl_exec($ch);
        if ($response === false) {
            throw new Exception(curl_error($ch), curl_errno($ch));
        }

        curl_close($ch);

        return json_encode(convertFirestoreJSON(json_decode($response, true)));
    }

    /**
     * Get document
     *
     * @param string $collectionPath
     * @param string $documentPath
     * @return string
     */
    public function getDocument($collectionPath, $documentPath)
    {
        $url = "{$this->baseUrl}/documents/{$collectionPath}/{$documentPath}";
        $requestOptions = [
            'method' => 'GET',
            'headers' => [
                'Authorization: Bearer ' . $this->token,
            ],
        ];

        try {
            $response = $this->executeRequest($url, $requestOptions);
            return $response;
        } catch (Exception $error) {
            echo 'Error getting document: ' . $error->getMessage();
            throw $error;
        }
    }
    /**
     * Get collection
     *
     * @param string $collectionPath
     * @return string
     */
    public function getCollection($collectionPath)
    {
        $url = "{$this->baseUrl}/documents/{$collectionPath}";
        $requestOptions = [
            'method' => 'GET',
            'headers' => [
                'Authorization: Bearer ' . $this->token,
            ],
        ];

        try {
            $response = $this->executeRequest($url, $requestOptions);
            return $response;
        } catch (Exception $error) {
            echo 'Error getting collection: ' . $error->getMessage();
            throw $error;
        }
    }
    /**
     * Create document
     *
     * @param string $collection
     * @param string $document
     * @param array $data
     * @return string
     */
    public function createDocument($collection, $document, $data)
    {
        $url = "{$this->baseUrl}/documents/{$collection}/{$document}";
        $requestOptions = [
            'method' => 'POST',
            'headers' => [
                'Authorization: Bearer ' . $this->token,
                'Content-Type: application/json',
            ],
            'body' => json_encode(['fields' => convertFieldsToFirestoreJSON($data)]),
        ];

        try {
            return $this->executeRequest($url, $requestOptions);
        } catch (Exception $error) {
            echo 'Error creating document: ' . $error->getMessage();
            throw $error;
        }
    }

    /**
     * Update document
     *
     * @param string $collection
     * @param string $document
     * @param array $fieldsToUpdate
     * @return string
     */
    public function updateDocument($collection, $document, $fieldsToUpdate)
    {
        $fieldPaths = array_map(function ($field) {
            return 'updateMask.fieldPaths=' . $field;
        }, array_keys($fieldsToUpdate));

        $fieldPathsQuery = implode('&', $fieldPaths);
        $url = "{$this->baseUrl}/documents/{$collection}/{$document}?currentDocument.exists=true&{$fieldPathsQuery}&alt=json";

        $requestOptions = [
            'method' => 'PATCH',
            'headers' => [
                'Authorization: Bearer ' . $this->token,
                'Content-Type: application/json',
            ],
            'body' => json_encode(['fields' => convertFieldsToFirestoreJSON($fieldsToUpdate)]),
        ];

        try {
            $response = $this->executeRequest($url, $requestOptions);
            return $response;
        } catch (Exception $error) {
            echo 'Error updating document: ' . $error->getMessage();
            throw $error;
        }
    }
    /**
     * Delete document
     *
     * @param string $collection
     * @param string $document
     * @return string
     */
    public function deleteDocument($collection, $document)
    {
        $url = "{$this->baseUrl}/documents/{$collection}/{$document}";
        $requestOptions = [
            'method' => 'DELETE',
            'headers' => [
                'Authorization: Bearer ' . $this->token,
            ],
        ];

        try {
            return $this->executeRequest($url, $requestOptions);
        } catch (Exception $error) {
            echo 'Error deleting document: ' . $error->getMessage();
            throw $error;
        }
    }
    /**
     * Delete collection
     *
     * @param string $collection
     * @return string
     */
    public function deleteCollection($collection)
    {
        $url = "{$this->baseUrl}/documents/{$collection}";
        $requestOptions = [
            'method' => 'DELETE',
            'headers' => [
                'Authorization: Bearer ' . $this->token,
            ],
        ];

        try {
            return $this->executeRequest($url, $requestOptions);
        } catch (Exception $error) {
            echo 'Error deleting collection: ' . $error->getMessage();
            throw $error;
        }
    }

    /**
     * Delete document fields
     *
     * @param string $collection
     * @param string $document
     * @param array $fieldsToDelete
     * @return string
     */
    public function deleteDocumentFields($collection, $document, $fieldsToDelete)
    {
        $url = "{$this->baseUrl}/documents/{$collection}/{$document}";
        $requestOptions = [
            'method' => 'PATCH',
            'headers' => [
                'Authorization: Bearer ' . $this->token,
                'Content-Type: application/json',
            ],
            'body' => json_encode(['fields' => convertFieldsToFirestoreJSON($fieldsToDelete)]),
        ];

        try {
            return $this->executeRequest($url, $requestOptions);
        } catch (Exception $error) {
            echo 'Error deleting document fields: ' . $error->getMessage();
            throw $error;
        }
    }
}

class Query
{
    private $baseUrl;
    private $token;
    private $queryMap;
    private $fieldsList;
    private $orderList;
    private $subCollection;

    /**
     * @param string $baseUrl
     * @param string $token
     */
    public function __construct($baseUrl, $token)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
        $this->queryMap = [];
        $this->fieldsList = [];
        $this->orderList = [];
        $this->subCollection = null;
    }

    /**
     * Run query
     *
     * @return array
     */
    public function run()
    {
        $url = "{$this->baseUrl}/documents/:runQuery";
        $requestOptions = [
            'method' => 'POST',
            'headers' => [
                'Authorization: Bearer ' . $this->token,
                'Content-Type: application/json',
            ],
            'body' => json_encode($this->complete()),
        ];

        // Initialize cURL session
        $curl = curl_init($url);

        // Set cURL options
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $requestOptions['headers']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $requestOptions['body']);

        // Execute cURL request
        $response = curl_exec($curl);

        // Check for errors and handle response
        if ($response === false) {
            $error = curl_error($curl);
            curl_close($curl);
            throw new Exception('Error running query: ' . $error);
        }

        // Close cURL session
        curl_close($curl);

        // Handle the response data accordingly        
        return json_encode(convertFirestoreJSON(json_decode($response, true)));
    }

    /**
     * Add single field
     *
     * @param string $field
     * @return array
     */
    public function addField($field)
    {
        $this->fieldsList[] = ['fieldPath' => $field];
        return $this;
    }

    /**
     * Add multiple fields
     *
     * @param array $fields
     * @return Query
     */
    public function addFields($fields)
    {
        foreach ($fields as $field) {
            $this->fieldsList[] = ['fieldPath' => $field];
        }
        return $this;
    }

    /**
     * Select fields
     *
     * @return Query
     */
    public function selectFields()
    {
        $selectMap = [];
        if (count($this->fieldsList) > 0) {
            $selectMap['fields'] = $this->fieldsList;
        }
        if (count($this->orderList) > 0) {
            $this->queryMap['orderBy'] = $this->orderList;
        }
        if (!empty($selectMap)) {
            $this->queryMap['select'] = $selectMap;
        }
        return $this;
    }

    /**
     * Order by
     *
     * @param string $field
     * @param string $direction
     * @return Query
     */
    public function orderBy($field, $direction)
    {
        $this->orderList[] = [
            'field' => ['fieldPath' => $field],
            'direction' => $direction
        ];
        return $this;
    }

    /**
     * From
     *
     * @param string $collectionPath
     * @return Query
     */
    public function from($collectionPath)
    {
        $this->selectFields();
        $fromList = [['collectionId' => $collectionPath]];
        $this->queryMap['from'] = $fromList;
        return $this;
    }

    /**
     * Start at
     *
     * @param array $values
     * @return Query
     */
    public function startAt($values)
    {
        $start = [
            'values' => array_map([$this, 'convertToFirestoreValue'], $values)
        ];
        $this->queryMap['startAt'] = $start;
        return $this;
    }

    /**
     * End at
     *
     * @param array $values
     * @return Query
     */
    public function endAt($values)
    {
        $end = [
            'values' => array_map([$this, 'convertToFirestoreValue'], $values)
        ];
        $this->queryMap['endAt'] = $end;
        return $this;
    }

    /**
     * Offset
     *
     * @param int $position
     * @return Query
     */
    public function offset($position)
    {
        $this->queryMap['offset'] = $position;
        return $this;
    }

    /**
     * Limit data
     *
     * @param int $limit
     * @return Query
     */
    public function limit($limitBy)
    {
        $this->queryMap['limit'] = $limitBy;
        return $this;
    }

    /**
     * Adds a filter
     *
     * @param CompositeFilter $compositeFilter
     * @return Query
     */
    public function where(CompositeFilter $compositeFilter): Query
    {
        $this->queryMap['where'] = ['compositeFilter' => $compositeFilter->complete()];
        return $this;
    }

    /**
     * Adds a filter
     *
     * @param FieldFilter $fieldFilter
     * @return Query
     */
    public function where2(FieldFilter $fieldFilter): Query
    {
        $this->queryMap['where'] = $fieldFilter->complete();
        return $this;
    }

    /**
     * Adds a filter
     *
     * @param UnaryFilter $unaryFilter
     * @return Query
     */
    public function where3(UnaryFilter $unaryFilter): Query
    {
        $this->queryMap['where'] = ['unaryFilter' => $unaryFilter->complete()];
        return $this;
    }

    /**
     * Returns query
     *
     * @return array
     */
    public function complete()
    {
        if (!empty($this->queryMap)) {
            return ['structuredQuery' => $this->queryMap];
        } else {
            return [];
        }
    }
}

class FieldFilter
{
    private array $filters = [];
    // Operators
    private string $LESS_THAN = 'LESS_THAN';
    private string $LESS_THAN_OR_EQUAL = 'LESS_THAN_OR_EQUAL';
    private string $GREATER_THAN = 'GREATER_THAN';
    private string $GREATER_THAN_OR_EQUAL = 'GREATER_THAN_OR_EQUAL';
    private string $EQUAL = 'EQUAL';
    private string $NOT_EQUAL = 'NOT_EQUAL';
    private string $ARRAY_CONTAINS = 'ARRAY_CONTAINS';
    private string $ARRAY_CONTAINS_ANY = 'ARRAY_CONTAINS_ANY';
    private string $NOT_IN = 'NOT_IN';
    private string $IN = 'IN';

    /**
     * Field is included in the query
     *
     * @param string $field
     * @param mixed $value
     * @return FieldFilter
     */
    public function isIn($field, $value)
    {
        $filter = [
            'field' => ['fieldPath' => $field],
            'op' => $this->IN,
            'value' => convertToFirestoreValue($value)
        ];
        $this->filters[] = ['fieldFilter' => $filter];
        return $this;
    }

    /**
     * Field is not included in the query
     *
     * @param string $field
     * @param mixed $value
     * @return FieldFilter
     */
    public function notIn($field, $value)
    {
        $filter = [
            'field' => ['fieldPath' => $field],
            'op' => $this->NOT_IN,
            'value' => convertToFirestoreValue($value)
        ];
        $this->filters[] = ['fieldFilter' => $filter];
        return $this;
    }

    /**
     * Field contains any of the values
     *
     * @param string $field
     * @param mixed $value
     * @return FieldFilter
     */
    public function arrayContainsAny($field, $value)
    {
        $filter = [
            'field' => ['fieldPath' => $field],
            'op' => $this->ARRAY_CONTAINS_ANY,
            'value' => convertToFirestoreValue($value)
        ];
        $this->filters[] = ['fieldFilter' => $filter];
        return $this;
    }

    /**
     * Field contains all of the values
     *
     * @param string $field
     * @param mixed $value
     * @return FieldFilter
     */
    public function arrayContains($field, $value)
    {
        $filter = [
            'field' => ['fieldPath' => $field],
            'op' => $this->ARRAY_CONTAINS,
            'value' => convertToFirestoreValue($value)
        ];
        $this->filters[] = ['fieldFilter' => $filter];
        return $this;
    }

    /**
     * Field is not equal to the value
     *
     * @param string $field
     * @param mixed $value
     * @return FieldFilter
     */
    public function notEqual($field, $value)
    {
        $filter = [
            'field' => ['fieldPath' => $field],
            'op' => $this->NOT_EQUAL,
            'value' => convertToFirestoreValue($value)
        ];
        $this->filters[] = ['fieldFilter' => $filter];
        return $this;
    }

    /**
     * Field is equal to the value
     *
     * @param string $field
     * @param mixed $value
     * @return FieldFilter
     */
    public function equalTo($field, $value)
    {
        $filter = [
            'field' => ['fieldPath' => $field],
            'op' => $this->EQUAL,
            'value' => convertToFirestoreValue($value)
        ];
        $this->filters[] = ['fieldFilter' => $filter];
        return $this;
    }

    /**
     * Field is less than the value
     *
     * @param string $field
     * @param mixed $value
     * @return FieldFilter
     */
    public function lessThan($field, $value)
    {
        $filter = [
            'field' => ['fieldPath' => $field],
            'op' => $this->LESS_THAN,
            'value' => convertToFirestoreValue($value)
        ];
        $this->filters[] = ['fieldFilter' => $filter];
        return $this;
    }

    /**
     * Field is greater than the value
     *
     * @param string $field
     * @param mixed $value
     * @return FieldFilter
     */
    public function greaterThan($field, $value)
    {
        $filter = [
            'field' => ['fieldPath' => $field],
            'op' => $this->GREATER_THAN,
            'value' => convertToFirestoreValue($value)
        ];
        $this->filters[] = ['fieldFilter' => $filter];
        return $this;
    }

    /**
     * Field is less than or equal to the value
     *
     * @param string $field
     * @param mixed $value
     * @return FieldFilter
     */
    public function greaterThanOrEqualTo($field, $value)
    {
        $filter = [
            'field' => ['fieldPath' => $field],
            'op' => $this->GREATER_THAN_OR_EQUAL,
            'value' => convertToFirestoreValue($value)
        ];
        $this->filters[] = ['fieldFilter' => $filter];
        return $this;
    }

    /**
     * Field is greater than or equal to the value
     *
     * @param string $field
     * @param mixed $value
     * @return FieldFilter
     */
    public function lessThanOrEqualTo($field, $value)
    {
        $filter = [
            'field' => ['fieldPath' => $field],
            'op' => $this->LESS_THAN_OR_EQUAL,
            'value' => convertToFirestoreValue($value)
        ];
        $this->filters[] = ['fieldFilter' => $filter];
        return $this;
    }

    /**
     * Get the complete filter
     *\
     * @return array
     */
    public function complete()
    {
        if (count($this->filters) >= 2) {
            return $this->filters;
        } else {
            return $this->filters[0];
        }
    }
}

class CompositeFilter
{
    private array $compositeMap = [];
    /**
     * Creates a new composite filter
     *
     */
    public function __construct()
    {
        $this->compositeMap = [];
    }


    /**
     * Add a filter
     *
     * @param FieldFilter $filter
     * @param string $operator
     * @return CompositeFilter
     */
    public function filters($filter, $operator = null)
    {
        if (count($this->compositeMap) > 0) {
            echo 'CompositeFilter already set';
        } else {
            $this->compositeMap['op'] = $operator ?: 'AND';
            $this->compositeMap['filters'] = $filter->complete();
        }
        return $this;
    }

    /**
     * Get the complete filter
     *
     * @return array
     */
    public function complete()
    {
        if (count($this->compositeMap) > 0) {
            return $this->compositeMap;
        } else {
            return [];
        }
    }
}

class UnaryFilter
{
    private $filter;
    public $IN;
    public $CONTAINS;
    public $IS_NOT_NAN;
    public $IS_NOT_NULL;

    /**
     * Creates a new unary filter
     *
     */
    public function __construct()
    {
        $this->filter = [];
        $this->IN = 'IN';
        $this->CONTAINS = 'CONTAINS';
        $this->IS_NOT_NAN = 'IS_NOT_NAN';
        $this->IS_NOT_NULL = 'IS_NOT_NULL';
    }

    /**
     * Set the operator
     *
     * @param string $operator
     * @return UnaryFilter
     */
    public function setOperator($operator)
    {
        $this->filter['op'] = $operator;
        return $this;
    }

    /**
     * Set the field
     *
     * @param string $field
     * @return UnaryFilter
     */
    public function setField($field)
    {
        $this->filter['field'] = ['fieldPath' => $field];
        return $this;
    }

    /**
     * Get the complete filter
     *
     * @return array
     */
    public function complete()
    {
        return $this->filter;
    }
}

class PathBuilder
{
    private $pathUrl;

    /**
     * Creates a new path
     *
     */
    public function __construct()
    {
        $this->pathUrl = '';
    }
    
    /**
     * Add a path
     *
     * @param string $path
     * @return PathBuilder
     */
    public function append($path)
    {
        if ($this->pathUrl !== '') {
            $this->pathUrl = "{$this->pathUrl}/{$path}";
        } else {
            $this->pathUrl = $path;
        }
        return $this;
    }

    /**
     * Add a path
     *
     * @param string $path
     * @return PathBuilder
     */
    public function collection($path)
    {
        if ($this->pathUrl !== '') {
            $this->pathUrl = "{$this->pathUrl}/{$path}";
        } else {
            $this->pathUrl = $path;
        }
        return $this;
    }

    /**
     * Add a path
     *
     * @param string $path
     * @return PathBuilder
     */
    public function document($path)
    {
        if ($this->pathUrl !== '') {
            $this->pathUrl = "{$this->pathUrl}/{$path}";
        } else {
            $this->pathUrl = $path;
        }
        return $this;
    }

    /**
     * Get the complete path
     *
     * @return string
     */
    public function complete()
    {
        return $this->pathUrl;
    }
}

/**
 * Firebase Cloud Messaging
 */
class FirebaseCloudMessaging
{
    private $credentialsPath;
    private $projectId;

    /**
     * Initialize Firebase Cloud Messaging
     *
     * @param string $credentialsPath
     */
    public function __construct($credentialsPath = 'serviceAccountCredentials.json')
    {
        $this->credentialsPath = $credentialsPath;
    }
    /**
     * Create a JWT
     *
     * @param array $header
     * @param array $payload
     * @param string $privateKey
     * @return string
     */
    function createJWT($header, $payload, $privateKey)
    {
        $headerEncoded = base64_encode(json_encode($header));
        $payloadEncoded = base64_encode(json_encode($payload));

        $dataToSign = $headerEncoded . '.' . $payloadEncoded;

        // Load the private key
        $privateKey = openssl_pkey_get_private($privateKey);

        // Create the signature using the private key and RS256 algorithm
        openssl_sign($dataToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        $signatureEncoded = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $headerEncoded . '.' . $payloadEncoded . '.' . $signatureEncoded;
    }

    /**
     * Get access token
     *
     * @return string
     */
    function getAccessToken()
    {
        $tokenFile = 'token.json';

        if (file_exists($tokenFile)) {
            $tokenData = json_decode(file_get_contents($tokenFile), true);
            $this->projectId = $tokenData['project_id'];
            // Check if the token exists and is not expired
            if ($tokenData && isset($tokenData['access_token']) && isset($tokenData['expires_at']) && $tokenData['expires_at'] > time()) {
                return $tokenData['access_token']; // Return the existing token
            }
        }

        // Load the service account credentials JSON file
        $serviceAccount = json_decode(file_get_contents($this->credentialsPath), true);

        // Get the OAuth 2.0 client ID and private key from the service account credentials
        $clientEmail = $serviceAccount['client_email'];
        $privateKey = $serviceAccount['private_key'];

        // Create the JWT payload
        $jwtPayload = [
            'iss' => $clientEmail,
            'sub' => $clientEmail,
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => time(),
            'exp' => time() + 3600,
            'scope' => 'https://www.googleapis.com/auth/cloud-platform'
        ];

        // Create the JWT token
        $header = [
            'alg' => 'RS256', // Algorithm used for hashing (you can use 'RS256' for RSA)
            'typ' => 'JWT'    // Type of token
        ];

        $jwt = $this->createJWT($header, $jwtPayload, $privateKey);

        // Make a POST request to the Google OAuth 2.0 token endpoint to exchange the JWT for an access token
        $tokenEndpoint = 'https://oauth2.googleapis.com/token';
        $postData = http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tokenEndpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $response = curl_exec($ch);

        if ($response === false) {
            die('Curl failed: ' . curl_error($ch));
        }

        curl_close($ch);

        $accessTokenData = json_decode($response, true);

        // Save the access token to token.json
        $accessTokenData['expires_at'] = time() + $accessTokenData['expires_in']; // Calculate token expiration time
        $accessTokenData['project_id'] = $serviceAccount['project_id'];
        file_put_contents($tokenFile, json_encode($accessTokenData));

        $this->projectId = $accessTokenData['project_id'];
        return $accessTokenData['access_token'];
    }


    /**
     * Send notification
     *
     * @param string $accessToken
     * @param string $topic
     * @param string $title
     * @param string $body
     * @param array $data
     * @return mixed
     */
    function sendNotificationToTopic($accessToken, $topics, $title, $body, $data = null)
    {
        $url = 'https://fcm.googleapis.com/v1/projects/' . $this->projectId . '/messages:send';

        $message = [
            'message' => [
                'topic' => $topics,
                'notification' => [
                    'title' => $title,
                    'body' => $body
                ],
                'android' => [
                    'notification' => [
                        'title' => $title,
                        'body' => $body
                    ]
                ],
                'apns' => [
                    'headers' => ['apns-priority' => '5'],
                    'payload' => [
                        'aps' => [
                            'alert' => [
                                'title' => $title,
                                'body' => $body
                            ]
                        ]
                    ]
                ],
                'webpush' => [
                    'headers' => ['TTL' => '86400']
                ]
            ]
        ];

        if ($data !== null && !empty($data)) {
            $message['message']['data'] = $data;
            $message['message']['data']['title'] = $title;
            $message['message']['data']['body'] = $body;
        } else {
            $message['message']['data'] = [
                'title' => $title,
                'body' => $body
            ];
        }

        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Should be set to true in production
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

        $result = curl_exec($ch);

        if ($result === false) {
            die('Curl failed: ' . curl_error($ch));
        }

        curl_close($ch);

        return $result;
    }

    /**
     * Send notification to Android
     *
     * @param string $accessToken
     * @param string $topic
     * @param string $title
     * @param string $body
     * @param array $data
     * @return mixed
     */
    public function sendNotificationToAndroid($accessToken, $topics, $title, $body, $data = null)
    {
        $url = 'https://fcm.googleapis.com/v1/projects/' . $this->projectId . '/messages:send';

        $message = [
            'message' => [
                'topic' => $topics,
                'notification' => [
                    'title' => $title,
                    'body' => $body
                ],
                'android' => [
                    'notification' => [
                        'title' => $title,
                        'body' => $body
                    ]
                ]
            ]
        ];

        if ($data !== null && !empty($data)) {
            $message['message']['data'] = $data;
            $message['message']['data']['title'] = $title;
            $message['message']['data']['body'] = $body;
        } else {
            $message['message']['data'] = [
                'title' => $title,
                'body' => $body
            ];
        }

        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Should be set to true in production
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

        $result = curl_exec($ch);

        if ($result === false) {
            die('Curl failed: ' . curl_error($ch));
        }

        curl_close($ch);

        return $result;
    }

    /**
     * Send notification to iOS
     *
     * @param string $accessToken
     * @param string $topic
     * @param string $title
     * @param string $body
     * @param array $data
     * @return mixed
     */
    public function sendFCMNotificationToIOS($accessToken, $topic, $title, $body, $data = null)
    {
        $url = 'https://fcm.googleapis.com/v1/projects/' . $this->projectId . '/messages:send';

        /*$message = [
            'message' => [
                'topic' => 'ios_' . $topic,
                'notification' => [
                    'title' => $title,
                    'body' => $body
                ],
                'apns' => [
                    'headers' => ['apns-priority' => '5'],
                    'payload' => [
                        'aps' => [
                            'alert' => [
                                'title' => $title,
                                'body' => $body
                            ]
                        ]
                    ]
                ]
            ]
        ];*/

        $message = [
            'message' => [
                'topic' => 'ios_' . $topic,
                'notification' => [
                    'title' => $title,
                    'body' => $body
                ],
                'apns' => [
                    'headers' => [
                        'apns-priority' => '5'
                    ],
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                            'badge' => 0
                        ]
                    ]
                ]
            ]
        ];


        /*if ($data !== null && !empty($data)) {
            $message['message']['data'] = $data;
            $message['message']['data']['title'] = $title;
            $message['message']['data']['body'] = $body;
        } else {
            $message['message']['data'] = [
                'title' => $title,
                'body' => $body
            ];
        }*/

        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Should be set to true in production
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

        $result = curl_exec($ch);

        if ($result === false) {
            die('Curl failed: ' . curl_error($ch));
        }

        curl_close($ch);

        return $result;
    }
}

// Helper functions
/**
 * Convert Firestore JSON to PHP array
 *
 * @param array $firestoreData
 * @return array
 */
function convertFirestoreJSON($firestoreData)
{
    if (isset($firestoreData['documents'])) {
        // If it's a collection
        $documents = array_map(function ($doc) {
            $nameParts = explode('/', $doc['name']);
            $docId = end($nameParts); // Extract document ID
            $fields = convertFieldsToJSON($doc['fields']); // Convert fields to JSON
            $fields['id'] = $docId;
            $fields['createTime'] = $doc['createTime'];
            $fields['updateTime'] = $doc['updateTime'];
            return $fields;
        }, $firestoreData['documents']);

        return ['documents' => $documents];
    } elseif (isset($firestoreData['name']) && isset($firestoreData['fields'])) {
        // If it's a single document
        $nameParts = explode('/', $firestoreData['name']);
        $docId = end($nameParts); // Extract document ID
        $fields = convertFieldsToJSON($firestoreData['fields']); // Convert fields to JSON
        $fields['id'] = $docId;
        $fields['createTime'] = $firestoreData['createTime'];
        $fields['updateTime'] = $firestoreData['updateTime'];
        return $fields;
    } elseif (is_array($firestoreData)) {
        $documents = array_map(function ($element) {
            $doc = $element['document'];
            $nameParts = explode('/', $doc['name']);
            $docId = end($nameParts); // Extract document ID
            $fields = convertFieldsToJSON($doc['fields']); // Convert fields to JSON
            $fields['id'] = $docId;
            $fields['createTime'] = $doc['createTime'];
            $fields['updateTime'] = $doc['updateTime'];
            return $fields;
        }, $firestoreData);

        return ['documents' => $documents];
    }

    return null;
}
/**
 * Convert Firestore fields to PHP array
 *
 * @param array $fields
 * @return array
 */
function convertFieldsToJSON($fields)
{
    $result = [];
    foreach ($fields as $key => $value) {
        if (isset($value['stringValue'])) {
            $result[$key] = $value['stringValue'];
        } elseif (isset($value['integerValue'])) {
            $result[$key] = intval($value['integerValue']);
        } elseif (isset($value['mapValue']['fields'])) {
            $result[$key] = convertFieldsToJSON($value['mapValue']['fields']);
        } elseif (isset($value['arrayValue']['values'])) {
            $result[$key] = array_map(function ($value) {
                if (isset ($value['stringValue'])) {
                    return $value['stringValue'];
                } elseif (isset ($value['integerValue'])) {
                    return intval($value['integerValue']);
                }
                return null; // Handle other value types as needed
            }, $value['arrayValue']['values']);
        }
    }
    return $result;
}
/**
 * Convert PHP array to Firestore JSON
 *
 * @param array $data
 * @return array
 */
function convertToFirestoreValue($value)
{
    if (is_string($value)) {
        return ['stringValue' => $value];
    } elseif (is_numeric($value)) {
        return ['integerValue' => strval($value)];
    } elseif (is_array($value)) {
        $values = array_map('convertToFirestoreValue', $value);
        return ['arrayValue' => ['values' => $values]];
    } elseif (is_object($value)) {
        $mapValue = [];
        foreach ($value as $key => $innerValue) {
            $mapValue[$key] = convertToFirestoreValue($innerValue);
        }
        return ['mapValue' => ['fields' => $mapValue]];
    }
    // Handle other data types or undefined/null values as needed
    return null;
}

/**
 * Convert PHP array to Firestore JSON
 *
 * @param array $data
 * @return array
 */
function convertFieldsToFirestoreJSON($fields)
{
    $result = [];
    foreach ($fields as $key => $value) {
        $result[$key] = convertToFirestoreValue($value);
    }
    return $result;
}