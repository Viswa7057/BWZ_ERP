<?php
// submit_kyc.php
session_start();

// Database connection
// Make sure to replace these with your actual database credentials
$host = "192.168.56.56"; 
$user = "homestead";
$pass = "secret";
$dbname = "aws";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

// Function to upload files
function uploadFile($fileInput, $uploadDir = "uploads/") {
    if (!isset($_FILES[$fileInput]) || $_FILES[$fileInput]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . "_" . basename($_FILES[$fileInput]['name']);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES[$fileInput]['tmp_name'], $targetFile)) {
        return $targetFile;
    }
    return null;
}

// Collect form data
$fullname   = $_POST['fullname'] ?? '';
// FIX: Prioritize getting the mobile number from the hidden field 'mobile_verified'
$mobile     = $_POST['mobile_verified'] ?? $_POST['mobile'] ?? '';
$email      = $_POST['email'] ?? '';
$city       = $_POST['city'] ?? '';
$aadhaar    = $_POST['aadhaar'] ?? '';
$area       = $_POST['area'] ?? '';
$pincode    = $_POST['pincode'] ?? '';
$car_model  = $_POST['car_model'] ?? '';
$year       = $_POST['year'] ?? '';
$platform   = $_POST['platform'] ?? '';
$shift      = $_POST['shift'] ?? '';
$fleet_type = $_POST['fleet_type'] ?? '';

// Upload files
$selfie      = uploadFile('selfie');
$car_rc      = uploadFile('car_rc');
$front_view  = uploadFile('front_view');
$back_view   = uploadFile('back_view');
$left_side   = uploadFile('left_side');
$right_view  = uploadFile('right_view');
$license_img = uploadFile('license_image');

// Handle multiple car photos
$car_photos = [];
if (isset($_FILES['car_photos'])) {
    foreach ($_FILES['car_photos']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['car_photos']['error'][$key] === UPLOAD_ERR_OK) {
            $fileName = time() . "_" . basename($_FILES['car_photos']['name'][$key]);
            $targetFile = "uploads/" . $fileName;
            if (move_uploaded_file($tmp_name, $targetFile)) {
                $car_photos[] = $targetFile;
            }
        }
    }
}
$car_photos_str = implode(",", $car_photos);

// Insert into DB
$stmt = $conn->prepare("INSERT INTO drivers 
    (fullname, mobile, email, city, selfie, aadhaar, area, pincode, car_model, year, car_rc, car_photos, front_view, back_view, left_side, right_view, license_img, platform, shift, fleet_type, created_at) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");


$stmt->bind_param("ssssssssssssssssssss",
    $fullname, $mobile, $email, $city, $selfie, $aadhaar, $area, $pincode, 
    $car_model, $year, $car_rc, $car_photos_str, $front_view, $back_view, 
    $left_side, $right_view, $license_img, $platform, $shift, $fleet_type
);


if ($stmt->execute()) {
    echo "<script>window.location.href='thank-you.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>