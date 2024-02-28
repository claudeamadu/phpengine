# Project Documentation

## Introduction

This project documentation provides an overview of the various classes, methods, and functionalities implemented within the system. The project includes functionalities related to databases, authentication, cloud services, HTTP requests, user management, file handling, messaging, payment processing, and more.

## Classes and Functionalities

### Database

- **connect()**: Establishes a connection to the database.
- **query(sql)**: Executes a SQL query.
- **fetch(sql)**: Fetches a single row from the result of the SQL query.
- **fetchAll(sql)**: Fetches all rows from the result of the SQL query.
- **insert(table, data)**: Inserts data into a specified table.
- **update(table, data, conditions, condition)**: Updates data in a specified table based on conditions.
- **select(table, columns, condition)**: Selects data from a specified table based on conditions.
- **delete(table, conditions, condition)**: Deletes data from a specified table based on conditions.
- **real_escape_string(string)**: Escapes special characters in a string for use in an SQL statement.
- **disconnect()**: Closes the database connection.

### FirebaseAuth

- **__construct(apiKey)**: Initializes FirebaseAuth with the provided API key.
- **setSessionItem(key, value)**: Sets an item in the user's session.
- **getSessionItem(key)**: Retrieves an item from the user's session.
- **refreshToken(refreshToken)**: Refreshes the authentication token.
- **isTokenExpired()**: Checks if the authentication token is expired.
- **isUserSignedIn()**: Checks if a user is signed in.
- **getAccessToken()**: Retrieves the access token.
- **getRefreshToken()**: Retrieves the refresh token.
- **currentUser()**: Retrieves information about the current user.
- **signUp(email, password)**: Registers a new user with the provided email and password.
- **signIn(email, password)**: Signs in a user with the provided email and password.
- **signOut()**: Signs out the current user.
- **sendEmailVerification(idToken)**: Sends an email verification to the user.
- **signInAnonymously()**: Signs in a user anonymously.
- **sendPasswordResetEmail(email)**: Sends a password reset email to the user.
- **confirmPasswordReset(oobCode, newPassword)**: Confirms a password reset.
- **changeEmail(idToken, newEmail)**: Changes the user's email address.
- **changePassword(idToken, newPassword)**: Changes the user's password.
- **confirmEmailVerification(oobCode)**: Confirms email verification.
- **deleteAccount(idToken)**: Deletes the user's account.

### FirebaseFirestore

- **__construct(Token, Database, ProjectID)**: Initializes FirebaseFirestore with the provided token, database URL, and project ID.

### Query

- **__construct(baseUrl, token)**: Initializes a query object with the base URL and authentication token.
- **run()**: Executes the query.
- **addField(field)**: Adds a field to the query.
- **addFields(fields)**: Adds multiple fields to the query.
- **selectFields()**: Selects fields to include in the query result.
- **orderBy(field, direction)**: Specifies the order of the query results.
- **from(collectionPath)**: Specifies the collection to query.
- **startAt(values)**: Sets the start point for the query.
- **endAt(values)**: Sets the end point for the query.
- **offset(position)**: Sets the offset for the query.
- **limit(limitBy)**: Limits the number of results returned by the query.
- **where(compositeFilter)**: Adds a composite filter to the query.
- **where2(fieldFilter)**: Adds a field filter to the query.
- **where3(unaryFilter)**: Adds a unary filter to the query.
- **complete()**: Completes the query construction.

### FieldFilter

- **isIn(field, value)**: Adds an "in" filter to the query.
- **notIn(field, value)**: Adds a "not in" filter to the query.
- **arrayContainsAny(field, value)**: Adds an "array contains any" filter to the query.
- **arrayContains(field, value)**: Adds an "array contains" filter to the query.
- **notEqual(field, value)**: Adds a "not equal" filter to the query.
- **equalTo(field, value)**: Adds an "equal to" filter to the query.
- **lessThan(field, value)**: Adds a "less than" filter to the query.
- **greaterThan(field, value)**: Adds a "greater than" filter to the query.
- **greaterThanOrEqualTo(field, value)**: Adds a "greater than or equal to" filter to the query.
- **lessThanOrEqualTo(field, value)**: Adds a "less than or equal to" filter to the query.
- **complete()**: Completes the filter construction.

### CompositeFilter

- **__construct()**: Initializes a composite filter object.
- **filters(filter, operator)**: Adds filters to the composite filter.
- **complete()**: Completes the composite filter construction.

### UnaryFilter

- **__construct()**: Initializes a unary filter object.
- **setOperator(operator)**: Sets the operator for the unary filter.
- **setField(field)**: Sets the field for the unary filter.
- **complete()**: Completes the unary filter construction.

### PathBuilder

- **__construct()**: Initializes a path builder object.
- **append(path)**: Appends a path to the builder.
- **collection(path)**: Specifies a collection path.
- **document(path)**: Specifies a document path.
- **complete()**: Completes the path construction.

### FirebaseCloudMessaging

- **__construct(credentialsPath)**: Initializes FirebaseCloudMessaging with the provided credentials path.
- **createJWT(header, payload, privateKey)**: Creates a JSON Web Token (JWT) for authentication.
- **getAccessToken()**: Retrieves the access token.
- **sendNotificationToTopic(accessToken, topics, title, body, data)**: Sends a notification to a topic.
- **sendNotificationToAndroid(accessToken, topics, title, body, data)**: Sends a notification to Android devices.
- **sendFCMNotificationToIOS(accessToken, topic, title, body, data)**: Sends a notification to iOS devices.

### Http

- **sendRequest(url, method, data, headers)**: Sends an HTTP request with the specified URL, method, data, and headers.

### Route

- **__construct(routePath)**: Initializes a route object with the specified path.
- **api(routePath)**: Defines an API route.
- **define(routePath)**: Defines a route.
- **display()**: Displays the route.
- **head(routePath)**: Defines a HEAD route.

### View

- **__construct(viewPath)**: Initializes a view object with the specified path.
- **make(viewPath)**: Creates a view.
- **error(code)**: Displays an error page.
- **layout(code)**: Sets the layout for the view.
- **render(data)**: Renders the view with the provided data.

### ResponseHandler

- **deliver(status, data)**: Delivers a response with the specified status code and data.
- **getStatusMessage(statusCode)**: Retrieves the status message for the specified status code.

### Session



- **startSession()**: Starts a session.
- **set(key, value)**: Sets a session variable.
- **unset(key)**: Unsets a session variable.
- **get(key)**: Retrieves a session variable.
- **isset(key)**: Checks if a session variable is set.

### SessionMessage

- **startSession()**: Starts a session for messages.
- **setMessage(message, title, type)**: Sets a message in the session.
- **unsetMessage()**: Unsets a message from the session.
- **showMessage()**: Displays a message from the session.

### ContactImporter

- **__construct(filePath)**: Initializes a contact importer with the specified file path.
- **importContacts()**: Imports contacts from the specified file.
- **importFromExcel()**: Imports contacts from an Excel file.
- **importFromCSVInBatches()**: Imports contacts from a CSV file in batches.
- **processBatch(contacts)**: Processes a batch of contacts.
- **importFromCSV()**: Imports contacts from a CSV file.
- **processData(data)**: Processes contact data.

### CryptoHelper

- **__construct(key)**: Initializes a crypto helper with the specified key.
- **encrypt(data)**: Encrypts data.
- **decrypt(encryptedData)**: Decrypts encrypted data.

### DirectoryManager

- **__construct(baseDirectory)**: Initializes a directory manager with the specified base directory.
- **createDirectory(directoryName)**: Creates a directory.
  
### FileManager

- **__construct(basePath)**: Initializes a file manager with the specified base path.
- **deleteFile(fileName)**: Deletes a file.
- **copyFile(sourceFileName, destinationFileName)**: Copies a file.
- **moveFile(sourceFileName, destinationFileName)**: Moves a file.

### FileUploader

- **__construct(uploadDirectory, allowedExtensions, maxFileSize)**: Initializes a file uploader with the specified upload directory, allowed extensions, and maximum file size.
- **uploadFile(file)**: Uploads a file.
- **validateFile(file)**: Validates a file.
- **generateUniqueFileName(originalFileName)**: Generates a unique file name.

### UserInfo

- **get_user_agent()**: Retrieves the user agent.
- **get_ip()**: Retrieves the IP address.
- **get_os()**: Retrieves the operating system.
- **get_browser()**: Retrieves the browser.
- **get_device()**: Retrieves the device.

### Auth

- **handle(request)**: Handles authentication.
- **isAuthenticated()**: Checks if the user is authenticated.

### Mailer

- **__construct(host, username, password, port, template, smtpSecure)**: Initializes a mailer with the specified host, username, password, port, template, and SMTP secure mode.
- **secure(type)**: Sets the security type for the mailer.
- **setFrom(address, name)**: Sets the sender address and name.
- **addRecipient(address, name)**: Adds a recipient.
- **addReplyTo(address, name)**: Adds a reply-to address.
- **addCC(address)**: Adds a CC recipient.
- **addBCC(address)**: Adds a BCC recipient.
- **addAttachment(path, name)**: Adds an attachment.
- **setHTMLContent(subject, body, altBody)**: Sets HTML content for the email.
- **send()**: Sends the email.

### Paystack

- **__construct(api_key)**: Initializes Paystack with the provided API key.
- **setHttp(method, url, params)**: Sets the HTTP parameters for Paystack requests.
- **detectNetwork(phoneNumber)**: Detects the network of a phone number.
- **createSubAccount(name, bank, number, email)**: Creates a sub-account.
- **createTransferReceipient(name, bank, number, email)**: Creates a transfer recipient.
- **updateTransferReceipient(name, code, email)**: Updates a transfer recipient.
- **fetchTransferReceipient(code)**: Fetches a transfer recipient.
- **deleteTransferReceipient(code)**: Deletes a transfer recipient.
- **initiateTransfer(source, reason, amount, recipient)**: Initiates a transfer.
- **finalizeTransfer(transfer_code, otp)**: Finalizes a transfer.
- **fetchTransfer(transfer_code)**: Fetches a transfer.
- **verifyTransfer(reference)**: Verifies a transfer.
- **charge(email, amount, network, phone_number)**: Charges a user.
- **submitOTP(otp, reference)**: Submits an OTP.
- **submitPIN(pin, reference)**: Submits a PIN.
- **pendingCharge(reference)**: Checks the status of a pending charge.
- **verifyAccount(account_number, bank_code)**: Verifies a bank account.
- **InitializeTransaction(email, amount, callback_url, channels)**: Initializes a transaction.
- **verifyTransaction(reference)**: Verifies a transaction.
- **ChargeAuthorization(email, amount, authorization_code, reference, channels, queue)**: Charges an authorization.

### User

- **authenticate()**: Authenticates the user.
- **create(data)**: Creates a new user.
- **get()**: Retrieves user data.
- **requestdata(key)**: Retrieves data from the request.
- **password(password)**: Generates a hashed password.
- **generateapikey(length)**: Generates an API key.
- **badgestatus(status)**: Retrieves badge status.
- **badgestatusmessage(status)**: Retrieves badge status message.
- **adddaystodate(originalDate, daysToAdd)**: Adds days to a date.
- **countcharacters(message)**: Counts characters in a message.
- **getdatetime(dateString)**: Retrieves a formatted date and time.
- **issubscriptionvalid(startDate, endDate)**: Checks if a subscription is valid.
- **issubscriptionvalid2(startDate, endDate)**: Checks if a subscription is valid.
- **getfileextensions(fileType)**: Retrieves file extensions.
- **sendmail(email, name, subject, message)**: Sends an email.
- **sendmailnoadmin(email, name, subject, message)**: Sends an email without admin permission.
- **removehtmltags(input)**: Removes HTML tags from input.
- **parsedatestring(dateString, inputFormat, outputFormat)**: Parses a date string.
- **formatphonenumber(phoneNumber)**: Formats a phone number.
- **sendsms(apiKey, recipientNumber, message, senderId)**: Sends an SMS.
- **durationfromdigits(digits, ly)**: Retrieves duration from digits.
- **ip_info(ip, purpose, deep_detect)**: Retrieves IP information.
- **convertfirestorejson(firestoreData)**: Converts Firestore data to JSON.
- **convertfieldstojson(fields)**: Converts fields to JSON.
- **converttofirestorevalue(value)**: Converts values to Firestore format.
- **convertfieldstofirestorejson(fields)**: Converts fields to Firestore JSON format.

## Usage

To utilize the functionalities provided by each class, instantiate the respective class and call its methods as needed within your application.

```php
<?php
// Example of using the Database class
$result = Database::query("SELECT * FROM users");
$data = Database::fetchAll($result);
print_r($data);
Database::disconnect();

// Example of using the FirebaseAuth class
$auth = new FirebaseAuth($apiKey);
$auth->signIn($email, $password);
$userInfo = $auth->currentUser();
echo "User ID: " . $userInfo['userId'];

// Example of using the Query class
$query = new Query($baseUrl, $token);
$query->from('users')->where($filter)->complete();
$queryResult = $query->run();

// Example of using the FieldFilter class
$filter = new FieldFilter();
$filter->equalTo('age', 30);
$filter->notEqual('status', 'inactive');

// Example of using the CompositeFilter class
$compositeFilter = new CompositeFilter();
$compositeFilter->filters([$filter1, $filter2], 'AND');

// Example of using the UnaryFilter class
$unaryFilter = new UnaryFilter();
$unaryFilter->setOperator('NOT');
$unaryFilter->setField('verified');

// Example of using the PathBuilder class
$pathBuilder = new PathBuilder();
$pathBuilder->append('users')->document('userID')->complete();
$documentPath = $pathBuilder->getPath();

// Example of using the FirebaseCloudMessaging class
$fcm = new FirebaseCloudMessaging($credentialsPath);
$accessToken = $fcm->getAccessToken();
$fcm->sendNotificationToTopic($accessToken, 'news', 'New article published', 'Check out our latest article!', []);

// Example of using the Http class
$response = Http::sendRequest($url, 'GET', [], []);
echo $response;

// Example of using the Route class
Route::define($routePath);
Route::api($routePath);
Route::head($routePath);

// Example of using the View class
View::make($viewPath);
View::layout($viewPath);

// Example of using the ResponseHandler class
ResponseHandler::deliver(200, ['message' => 'Success']);

// Example of using the Session class
Session::startSession();
Session::set('user_id', 123);
echo Session::get('user_id');

// Example of using the SessionMessage class
SessionMessage::setMessage('Success', 'Login', 'info');
SessionMessage::showMessage();

// Example of using the ContactImporter class
$contactImporter = new ContactImporter($filePath);
$contactImporter->importContacts();

// Example of using the CryptoHelper class
$cryptoHelper = new CryptoHelper($key);
$encryptedData = $cryptoHelper->encrypt($data);
$decryptedData = $cryptoHelper->decrypt($encryptedData);

// Example of using the DirectoryManager class
$directoryManager = new DirectoryManager($baseDirectory);
$directoryManager->createDirectory('uploads');

// Example of using the FileManager class
$fileManager = new FileManager($basePath);
$fileManager->deleteFile('example.txt');

// Example of using the FileUploader class
$fileUploader = new FileUploader('uploads', ['jpg', 'png'], 5000000);
$fileUploader->uploadFile($_FILES['file']);

// Example of using the UserInfo class
$userInfo = new UserInfo();
echo $userInfo->get_ip();

// Example of using the Auth class
$auth = new Auth();
if ($auth->isAuthenticated()) {
    echo "User is authenticated";
}

// Example of using the Mailer class
$mailer = new Mailer($host, $username, $password, $port, $template, $smtpSecure);
$mailer->setFrom($fromAddress, $fromName);
$mailer->addRecipient($recipientAddress, $recipientName);
$mailer->setHTMLContent($subject, $htmlBody, $altBody);
$mailer->send();

// Example of using the Paystack class
$paystack = new Paystack($api_key);
$response = $paystack->createSubAccount($name, $bank, $number, $email);
echo $response;

// Example of using the User class
User::create($userData);
$userInfo = User::get();
```

## Creator

- [Claude Amadu](https://github.com/claudeamadu)

## License

GNU GENERAL PUBLIC LICENSE