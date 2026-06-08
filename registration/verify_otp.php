<?php
session_start();
header("Content-Type: application/json");

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
    exit;
}

// Get JSON body
$input  = json_decode(file_get_contents("php://input"), true);
$mobile = preg_replace('/\D/', '', $input['mobile'] ?? '');
$otp    = trim($input['otp'] ?? '');

// Validate inputs
if (!$mobile || !$otp) {
    echo json_encode(["success" => false, "error" => "Mobile number or OTP missing"]);
    exit;
}

// Fetch stored OTP
$storedData = $_SESSION['otp_' . $mobile] ?? null;

if (!$storedData) {
    echo json_encode(["success" => false, "error" => "OTP not found. Please send OTP first."]);
    exit;
}

$storedOtp = $storedData['otp'];
$expires   = $storedData['expires'];

// Check expiry
if (time() > $expires) {
    unset($_SESSION['otp_' . $mobile]); // clear expired OTP
    echo json_encode(["success" => false, "error" => "OTP expired. Please resend."]);
    exit;
}

// Check match
if ($otp == $storedOtp) {
    unset($_SESSION['otp_' . $mobile]); // clear after success
    echo json_encode(["success" => true, "message" => "OTP verified successfully"]);
} else {
    echo json_encode(["success" => false, "error" => "Invalid OTP"]);
}
