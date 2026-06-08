<?php
$servername = "localhost";
$username = "branfedi_ERP_BWZ";
$password = "Zpl;blpDV0T7";
$dbname = "branfedi_BWZ_ERP";

// Create connection
$conn = mysqli_connect("localhost","branfedi_ERP_BWZ","Zpl;blpDV0T7","branfedi_BWZ_ERP");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT SUM(invoice) AS total_payment FROM payment_request WHERE status = 'Payment Done'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo $row['total_payment'];
} else {
    echo "0";
}

$conn->close();
?>
