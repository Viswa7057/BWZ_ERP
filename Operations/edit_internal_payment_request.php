<?php
require_once("db_connect.php");

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $stmt = "SELECT employee_name, total_amount, id FROM internal_payment_request WHERE id='$id'";
    $result = mysqli_query($conn, $stmt);
    
    if($row = mysqli_fetch_assoc($result)) {
        echo $row['employee_name'] . "*" . $row['total_amount'] . "*" . $row['id'];
    }
}
?>
