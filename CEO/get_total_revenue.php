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

// $sql = "SELECT SUM(Total_Value_Without_GST) AS total_revenue FROM order_details";
$sql = "SELECT SUM(Total_Value_Without_GST) AS total_revenue FROM order_details WHERE ceo_status = 'Accepted'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo $row['total_revenue'];
} else {
    echo "0";
}

$conn->close();
?>
