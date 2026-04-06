<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

defined('BASEPATH') OR exit('No direct script access allowed');

// ✅ Load Composer autoload
$autoloadPaths = [
    APPPATH . '../vendor/autoload.php',
    APPPATH . 'third_party/vendor/autoload.php',
];

$autoloadFound = false;
foreach ($autoloadPaths as $path) {
    if (file_exists($path)) {
        require_once $path;
        $autoloadFound = true;
        break;
    }
}

if (!$autoloadFound) {
    die('❌ Composer autoload file not found.');
}


use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;

class Firebase {

    private $projectId = 'blindtex-easy-quote'; 
    private $keyPath;

    public function __construct() {
       
        $this->keyPath = APPPATH . 'config/firebase.json';
    }


  public function sendNotification($deviceToken, $title, $body)
{
    $keyData = json_decode(file_get_contents($this->keyPath), true);
    if (!$keyData) {
        die('❌ Invalid Firebase key JSON file.');
    }

    $credentials = new ServiceAccountCredentials(
        ['https://www.googleapis.com/auth/firebase.messaging'],
        $this->keyPath
    );

    $httpHandler = HttpHandlerFactory::build();
    $tokenData = $credentials->fetchAuthToken($httpHandler);

    if (!isset($tokenData['access_token'])) {
        echo "❌ Failed to fetch access token.<br>";
        print_r($tokenData);
        exit;
    }

    $accessToken = $tokenData['access_token'];

    // 🔹 Use FCM v1 API URL
    $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

    // 🔹 Improved message payload
    $message = [
        'message' => [
            'token' => $deviceToken,
            'notification' => [
                'title' => $title,
                'body'  => $body,
            ],
            'android' => [
                'priority' => 'high',
                'notification' => [
                    'sound' => 'default',
                    'notification_priority' => 'PRIORITY_MAX',
                    'visibility' => 'PUBLIC', // ensures it shows on lock screen
                    'default_light_settings' => true,
                    'default_sound' => true,
                    'default_vibrate_timings' => true,
                ],
            ],
            'apns' => [
                'payload' => [
                    'aps' => [
                        'alert' => [
                            'title' => $title,
                            'body' => $body,
                        ],
                        'sound' => 'default',
                    ],
                ],
            ],
            'data' => [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'title' => $title,
                'body'  => $body,
            ],
        ],
    ];

    $headers = [
        "Authorization: Bearer $accessToken",
        "Content-Type: application/json",
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

    $result = curl_exec($ch);
    $error  = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return "❌ CURL Error: $error";
    }

    return $result;
}


    public function checkGoogleAuth() {
        return class_exists('Google\Auth\Credentials\ServiceAccountCredentials')
            ? "✅ Google Auth library loaded successfully!"
            : "❌ Google Auth library NOT found.";
    }
}
