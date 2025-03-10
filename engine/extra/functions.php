<?php
/**
 * Gets data from request
 *
 * @param string $key
 * @return mixed
 */
function requestData($key)
{
    // Check if the key is present in $_REQUEST first
    if (isset($_REQUEST[$key])) {
        return $_REQUEST[$key];
    }
    
    // If not in $_REQUEST, check if data exists in php://input
    $input = json_decode(file_get_contents("php://input"), true);
    return isset($input[$key]) ? $input[$key] : null;
}

/**
 * Hashes password
 *
 * @param string $password
 * @return string
 */
function password($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}
/**
 * Generates API key
 *
 * @param int $length
 * @return string
 */
function generateApiKey($length = 32)
{
    // Define characters allowed in the API key
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    // Get the total number of characters
    $charLength = strlen($characters);

    // Initialize the API key variable
    $apiKey = '';

    // Generate a random character for each position in the API key
    for ($i = 0; $i < $length; $i++) {
        $apiKey .= $characters[random_int(0, $charLength - 1)];
    }

    return $apiKey;
}

/**
 * Generates badge
 *
 * @param int $length
 */
function badgeStatus($status)
{
    switch ($status) {
        case 0:
            echo '<span class="badge badge-warning">Pending</span>';
            break;
        case 1:
            echo '<span class="badge badge-success">Approved</span>';
            break;
        case 3:
            echo '<span class="badge badge-danger">Regected</span>';
            break;
        default:
            echo '<span class="badge badge-danger">Regected</span>';
            break;
    }
}
/**
 * Generates badge
 *
 * @param int $length
 */
function badgeStatusMessage($status)
{
    switch ($status) {
        case 0:
            echo '<span class="badge badge-warning">Failed</span>';
            break;
        case 1:
            echo '<span class="badge badge-success">Sent</span>';
            break;
        case 3:
            echo '<span class="badge badge-danger">Regected</span>';
            break;
        default:
            echo '<span class="badge badge-danger">Failed</span>';
            break;
    }
}

/**
 * Add days to date
 *
 * @param string $originalDate
 * @param int $daysToAdd
 * @return string
 */
function addDaysToDate($originalDate, $daysToAdd)
{
    // Create a DateTime object from the original date
    $date = new DateTime($originalDate);

    // Add the specified number of days
    $date->add(new DateInterval('P' . $daysToAdd . 'D'));

    // Return the new date
    return $date->format('Y-m-d');
}

/**
 * Count characters
 *
 * @param string $message
 * @return array
 */
function countCharacters($message)
{
    $maxLength = 160;
    $messageLength = strlen($message);

    $messages = ceil($messageLength / $maxLength);
    $remainingCharacters = $maxLength - ($messageLength % $maxLength);

    return [
        'characterCount' => $messageLength,
        'totalCharacters' => $messages * $maxLength,
        'messageCount' => $messages,
        'remainingCharacters' => $remainingCharacters
    ];
}

/**
 * Gets date
 *
 * @param string $date
 * @return string
 */
function getDateTime($dateString)
{

    // Create a DateTime object from the string
    $date = new DateTime($dateString);

    // Format the date as a readable string
    return $readableDate = $date->format('F j, Y h:i:s A');
}

/**
 * Check if subscription is valid
 *
 * @param string $startDate
 * @param string $endDate
 * @return bool
 */
function isSubscriptionValid($startDate, $endDate)
{
    $currentDate = date('Y-m-d');

    // Check if the current date is within the subscription period
    return($currentDate >= $startDate && $currentDate <= $endDate);
}
/**
 * Check if subscription is valid
 *
 * @param string $startDate
 * @param string $endDate
 * @return bool
 */
function isSubscriptionValid2($startDate, $endDate)
{
    $currentDate = date('Y-m-d');

    // Check if the current date is within the subscription period
    if ($currentDate >= $startDate && $currentDate <= $endDate) {
        // Calculate the number of days left
        $daysLeft = floor((strtotime($endDate) - strtotime($currentDate)) / (60 * 60 * 24));
        return "Subscription is valid. {$daysLeft} days left.";
    } else {
        return "Not Subscribed";
    }
}

/**
 * Get file extensions
 *
 * @param string $fileType
 * @return array
 */
function getFileExtensions($fileType)
{
    switch ($fileType) {
        case 'image':
            return ['png', 'jpg', 'jpeg', 'gif', 'svg'];

        case 'document':
            return ['doc', 'docx', 'pdf', 'txt', 'svg'];

        case 'audio':
            return ['mp3', 'wav', 'ogg', 'aac'];

        case 'video':
            return ['mp4', 'avi', 'mkv', 'mov'];

        default:
            return [];
    }
}

/**
 * Send email
 *
 * @param string $email
 * @param string $name
 * @param string $subject
 * @param string $message
 * @return bool
 */
function sendMail($email, $name, $subject, $message)
{
    $host = MAIL_HOST;
    $username = MAIL_USER;
    $password = MAIL_PASS;
    $port = MAIL_PORT;

    $mailer = new Mailer($host, $username, $password, $port);
    $mailer->setFrom('', '');
    $mailer->addRecipient($email, $name);
    $mailer->addReplyTo('', '');
    $mailer->setHTMLContent($subject, $message, removeHtmlTags($message));
    return $mailer->send();
}

/**
 * Send email
 *
 * @param string $email
 * @param string $name
 * @param string $subject
 * @param string $message
 * @return bool
 */
function sendMailNoAdmin($email, $name, $subject, $message)
{
    $host = MAIL_HOST;
    $username = MAIL_USER;
    $password = MAIL_PASS;
    $port = MAIL_PORT;

    $mailer = new Mailer($host, $username, $password, $port);
    $mailer->setFrom('', '');
    $mailer->addRecipient($email, $name);
    $mailer->addReplyTo('', '');
    $mailer->setHTMLContent($subject, $message, removeHtmlTags($message));
    return $mailer->send();
}

/**
 * Remove HTML tags
 *
 * @param string $input
 * @return string
 */
function removeHtmlTags($input)
{
    // Remove all HTML tags except <br> and <br/>
    $cleanedString = strip_tags($input, '<br><br/>');

    // Replace <br> and <br/> with a new line
    $cleanedString = str_ireplace(['<br>', '<br/>'], "\n", $cleanedString);

    return $cleanedString;
}

/**
 * Parse date
 *
 * @param string $date
 * @param string $inputFormat
 * @param string $outputFormat
 * @return string
 */
function parseDateString($dateString, $inputFormat = "m/d/Y", $outputFormat = "Y-m-d")
{
    $date = DateTime::createFromFormat($inputFormat, $dateString);

    if ($date !== false) {
        return $date->format($outputFormat);
    } else {
        return "Failed to parse date.";
    }
}

/**
 * Format phone number
 *
 * @param string $phoneNumber
 * @return string
 */
function formatPhoneNumber($phoneNumber)
{
    // Check if the string length is 12 and starts with 233
    if (strlen($phoneNumber) === 12 && strpos($phoneNumber, '233') === 0) {
        // If already formatted, return as is
        return $phoneNumber;
    }

    // Check if the string length is 10 or less
    if (strlen($phoneNumber) <= 10) {
        // Check if it starts with 0
        if (strpos($phoneNumber, '0') === 0) {
            // Remove 0 and replace with 233
            $phoneNumber = '233' . substr($phoneNumber, 1);
        } else {
            // If starting with other digits and less than 10, add 233
            $phoneNumber = '233' . $phoneNumber;
        }
    }

    return $phoneNumber;
}

/**
 * Send SMS
 *
 * @param string $apiKey
 * @param string $recipientNumber
 * @param string $message
 * @param string $senderId
 * @return bool
 */
function sendSMS($apiKey, $recipientNumber, $message, $senderId)
{
    // Set the API endpoint
    $url = 'https://sms.smsnotifygh.com/smsapi';
    
    $headers = [
        "Content-Type: application/json",
    ];

    // Prepare the data to be sent
    $postData = [
        'key' => $apiKey,
        'to' => $recipientNumber,
        'msg' => $message,
        'sender_id' => $senderId,
    ];

    // Initialize cURL session
    $curl = curl_init();

    // Set cURL options
    curl_setopt_array($curl, [
        CURLOPT_URL => $url . '?' . http_build_query($postData),
        CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => http_build_query($postData),
        CURLOPT_RETURNTRANSFER => true
    ]);

    // Execute cURL session
    $response = curl_exec($curl);

    // Check for cURL errors
    if (curl_errno($curl)) {
        echo 'cURL error: ' . curl_error($curl);
    }

    // Close cURL session
    curl_close($curl);

    // Output the response from the server
    return json_decode($response);
}

/**
 * Duration from digits
 *
 * @param int $digits
 * @param bool $ly
 * @return string
 */
function durationFromDigits($digits = 30, $ly = false)
{
    $duration = '';
    switch ($digits) {
        case 30:
            if ($ly) {
                $duration = "Monthly";
            } else {
                $duration = "month";
            }
            break;
        case 365:
            if ($ly) {
                $duration = "Yearly";
            } else {
                $duration = "year";
            }
            break;
    }
    return $duration;
}

/**
 * IP info
 *
 * @param string $ip
 * @param string $purpose
 * @param bool $deep_detect
 * @return array
 */
function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE)
{
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose = str_replace(array("name", "\n", "\t", " ", "-", "_"), '', strtolower(trim($purpose)));
    $support = array("country", "countrycode", "state", "region", "city", "location", "address", "currency", "currencysymbol");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city" => @$ipdat->geoplugin_city,
                        "state" => @$ipdat->geoplugin_regionName,
                        "country" => @$ipdat->geoplugin_countryName,
                        "country_code" => @$ipdat->geoplugin_countryCode,
                        "currency" => @$ipdat->geoplugin_currencyCode,
                        "currency_symbol" => @$ipdat->geoplugin_currencySymbol,
                        "continent" => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
                case "currency":
                    $output = @$ipdat->geoplugin_currencyCode;
                    break;
                case "currencysymbol":
                    $output = @$ipdat->geoplugin_currencySymbol;
                    break;
            }
        }
    }
    return $output;
}

function displayClassMethods($className)
{
    $class = new ReflectionClass($className);
    $methods = $class->getMethods();

    echo "<hr/>";
    echo "<h2>{$className}</h2>";
    echo "<ul>";
    foreach ($methods as $method) {
        echo "<li>{$method->getName()}(";
        $params = $method->getParameters();
        $paramStrings = [];
        foreach ($params as $param) {
            $paramStrings[] = $param->getName();
        }
        echo implode(', ', $paramStrings);
        echo ")</li>";
    }
    echo "</ul>";
}

function displayClassMethodsFromFile($filePath)
{
    // Include the file to make classes available
    require_once($filePath);

    // Get all declared classes in the included file
    $declaredClasses = get_declared_classes();

    foreach ($declaredClasses as $className) {
        // Check if the class is declared in the included file
        $class = new ReflectionClass($className);
        if ($class->getFileName() === realpath($filePath)) {
            // If yes, display class name as heading and list its methods
            echo "<hr/>";
            echo "<h2>{$className}</h2>";
            echo "<ul>";
            $methods = $class->getMethods();
            foreach ($methods as $method) {
                echo "<li>{$method->getName()}(";
                $params = $method->getParameters();
                $paramStrings = [];
                foreach ($params as $param) {
                    $paramStrings[] = $param->getName();
                }
                echo implode(', ', $paramStrings);
                echo ")</li>";
            }
            echo "</ul>";
        }
    }
}

function displayFunctionsFromFile($filePath)
{
    // Include the file to make functions available
    require_once($filePath);

    // Get all defined functions in the included file
    $definedFunctions = get_defined_functions();

    echo "<hr/>";
    // Loop through each function
    foreach ($definedFunctions['user'] as $functionName) {
        // Get reflection for the function
        $reflectionFunction = new ReflectionFunction($functionName);

        // Get function parameters
        $parameters = $reflectionFunction->getParameters();

        // Display function name
        echo "<h3>{$functionName}(";

        // Display function parameters
        $paramStrings = [];
        foreach ($parameters as $param) {
            $paramStrings[] = $param->getName();
        }
        echo implode(', ', $paramStrings);

        echo ")</h3>";
    }
}

function generateOTP($type, $length) {
    $otp = '';
    switch($type) {
        case 'Alphabets':
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;
        case 'Numbers':
            $characters = '0123456789';
            break;
        case 'Alphanumeric':
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            break;
        default:
            return 'Invalid OTP type';
    }
    
    $charactersLength = strlen($characters);
    for ($i = 0; $i < $length; $i++) {
        $otp .= $characters[rand(0, $charactersLength - 1)];
    }
    return $otp;
}

function addMinutesToDate($originalDate, $minutesToAdd)
{
    // Create a DateTime object from the original date
    $date = new DateTime($originalDate);

    // Add the specified number of minutes
    $date->add(new DateInterval('PT' . $minutesToAdd . 'M'));

    // Return the new date
    return $date->format('Y-m-d H:i:s');
}

function isOTPValid($start, $end)
{
    $currentDateTime = new DateTime(); // Current date and time

    // Convert start and end timestamps to DateTime objects
    $startDate = new DateTime($start);
    $endDate = new DateTime($end);

    // Check if the current date and time is within the OTP validity period
    return ($currentDateTime >= $startDate && $currentDateTime <= $endDate);
}


/**
 * Generates a 10-digit index number based on a date string and an ID number.
 *
 * @param string $dateString The date string in the format "YYYY-MM-DD HH:MM:SS".
 * @param int $idNumber The ID number to be included in the index number.
 * @return string The formatted 10-digit index number.
 */
function generateIndexNumber($dateString, $idNumber) {
    // Extract the year from the date string
    $year = substr($dateString, 0, 4);

    // Pad the ID number with leading zeros to ensure it is 6 digits long
    $paddedIdNumber = str_pad($idNumber, 6, "0", STR_PAD_LEFT);

    // Concatenate the year and the padded ID number
    $indexNumber = $year . $paddedIdNumber;

    return $indexNumber;
}

function sanitizeString($input) {
    // Convert the string to lowercase
    $input = strtolower($input);

    // Replace spaces with underscores
    $input = str_replace(' ', '_', $input);

    // Remove all characters that are not letters, numbers, or underscores
    $input = preg_replace('/[^a-z0-9_]/', '', $input);

    return $input;
}

function base64ToImage($base64String, $outputDir, $originalFileName) {
    // Extract the file extension from the original file name
    $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);

    // Generate a unique filename
    $uniqueFileName = uniqid('file_') . '_' . time() . '.' . $extension;

    // Ensure the output directory ends with a slash
    if (substr($outputDir, -1) !== '/') {
        $outputDir .= '/';
    }

    // Combine the directory and unique filename to get the full output file path
    $outputFile = $outputDir . $uniqueFileName;

    // Split the base64 string to remove the data URI scheme (if present)
    $data = explode(',', $base64String);

    // Decode the base64 string into binary data
    $imageData = base64_decode($data[1]);

    // Create the directory if it doesn't exist
    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0777, true);
    }

    // Write the binary data to a file
    file_put_contents($outputFile, $imageData);

    // Return the path of the saved image file
    return $outputFile;
}

/**
 * Fetches and processes JSON data from a given URL.
 *
 * @param string $url The URL to fetch JSON data from.
 * @return array|null Returns the processed data if successful, otherwise null.
 */
function fetchAndProcessJsonData($url) {
    // Fetch the JSON data from the URL
    $json = file_get_contents($url);

    // Check if the request was successful
    if ($json === FALSE) {
        return null;
    }

    // Decode the JSON data
    $data = json_decode($json, true);

    // Check if decoding was successful
    if ($data === NULL) {
        return null;
    }

    // Check the status and message
    if ($data['status'] == 200 && $data['message'] == 'OK') {
        // Return the data
        return $data['data'];
    } else {
        return null;
    }
}

function addPrefixSuffix($inputString, $prefix, $suffix) {
    // Extract the first 7 characters from the input string
    $firstSevenChars = substr($inputString, 0, 7);
    
    // Concatenate the prefix, first 7 characters, and suffix
    $result = $prefix . $firstSevenChars . $suffix;
    
    return $result;
}

function replaceAlphabetsWithIndex($inputString) {
    $outputString = '';
    
    // Loop through each character in the string
    for ($i = 0; $i < strlen($inputString); $i++) {
        // Check if the character is an alphabet (A-Z or a-z)
        if (ctype_alpha($inputString[$i])) {
            // Append the 1-based index to the output string
            $outputString .= ($i + 1);
        } else {
            // Append the original character to the output string
            $outputString .= $inputString[$i];
        }
    }
    
    return $outputString;
}


function sidebarActive($menu, $class): string
{
    // Get the current URL path (relative to the base URL)
    $currentUrl = trim($_SERVER['REQUEST_URI'], '/');

    // Split the current URL into parts by '/'
    $urlParts = explode('/', $currentUrl);

    // Normalize the menu (remove leading/trailing slashes)
    $menu = trim($menu, '/');

    // Check if the $menu matches any part of the URL
    if (in_array($menu, $urlParts)) {
        return $class;
    }

    // If no match and $menu is '/' (home), return the class
    if (count($urlParts) == 1 && ($menu === '' || $menu === '/')) {
        return $class;
    }

    return '';
}



function generateStars(float $rating, int $maxStars = 5): string
{
    $fullStars = floor($rating); // Number of full stars
    $halfStar = ($rating - $fullStars) >= 0.5; // Check if there's a half star
    $emptyStars = $maxStars - $fullStars - ($halfStar ? 1 : 0); // Calculate empty stars

    $starsHtml = '';

    // Add full stars
    for ($i = 0; $i < $fullStars; $i++) {
        $starsHtml .= '<span class="text-warning">★</span>';
    }

    // Add half star
    if ($halfStar) {
        $starsHtml .= '<span class="text-warning">☆</span>';
    }

    // Add empty stars
    for ($i = 0; $i < $emptyStars; $i++) {
        $starsHtml .= '<span class="text-muted">☆</span>';
    }

    return $starsHtml;
}
