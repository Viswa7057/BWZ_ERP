<?php
require_once("db_connect.php");

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $stmt = "SELECT * FROM payment_request WHERE id='$id'";
    $result = mysqli_query($conn, $stmt);
    
    if($row = mysqli_fetch_assoc($result)) {
        // Return data separated by asterisk (*)
        echo $row['vendor_name'] . "*" . 
             $row['type_of_vendor'] . "*" . 
             $row['campaign_code'] . "*" . 
             $row['total_prints'] . "*" . 
             $row['per_unit'] . "*" . 
             $row['id'] . "*" . 
             $row['payment_type'] . "*" . 
             $row['employee_name'] . "*" .
             $row['invoice'];
    }
}
?>
