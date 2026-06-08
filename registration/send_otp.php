<?php
session_start();
header("Content-Type: application/json");

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
    exit;
}

// Get JSON body
$input = json_decode(file_get_contents("php://input"), true);
$mobile = preg_replace('/\D/', '', $input['mobile'] ?? '');

// Validate mobile number (must be 10 digits, starting with 6–9)
if (!$mobile || !preg_match('/^[6-9]\d{9}$/', $mobile)) {
    echo json_encode(["success" => false, "error" => "Invalid mobile number"]);
    exit;
}

// Generate a 6-digit OTP
$otp = rand(100000, 999999);

// Save OTP in session (5 minutes expiry)
$_SESSION['otp_' . $mobile] = [
    'otp' => $otp,
    'expires' => time() + (5 * 60)
];

// SMS API credentials (replace with your own real values)
$username   = 'haloocom';
$apikey     = '10A51-56AEF';
$sender     = 'Halcom';
$route      = 'ServiceImplicit';
$templateId = '1707175740149131639';

$url = "https://www.k3digitalmedia.co.in/websms/api/http/index.php";
$params = [
    'username'   => $username,
    'apikey'     => $apikey,
    'apirequest' => 'Template',
    'sender'     => $sender,
    'route'      => $route,
    'TemplateID' => $templateId,
    'mobile'     => $mobile,
    'Values'     => $otp
];

$finalUrl = $url . '?' . http_build_query($params);

// Send OTP request using cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $finalUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // disable SSL verify if needed

$response = curl_exec($ch);

if (curl_errno($ch)) {
    $error_msg = curl_error($ch);
    curl_close($ch);
    echo json_encode(["success" => false, "error" => "cURL error: $error_msg"]);
    exit;
}

curl_close($ch);

// Log API response for debugging
error_log("K3DigitalMedia API Response: " . $response);

// Return response to frontend
if (!$response) {
    echo json_encode(["success" => false, "error" => "No response from SMS API"]);
} else {
    echo json_encode([
        "success" => true,
        "message" => "OTP sent successfully",
        "api_response" => $response  // helpful for debugging
    ]);
}
