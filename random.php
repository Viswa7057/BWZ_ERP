<?php
require_once("db_connect.php");
function generateVendorID() {
    $prefix = 'BWZ';

    $number = rand(1000, 99999);

    $vendorID = $prefix . '-' . $number;

    return $vendorID;
}

$vendorID = generateVendorID();
echo $vendorID;

$stmt_ops = "SELECT email,user_name from users where user_level='Operations';";
  $rslt_ops = mysqli_query($conn, $stmt_ops);
  $row_ops = mysqli_fetch_row($rslt_ops);
  $ops_email = $row_ops[0];
  $ops_user = $row_ops[1];

  echo $ops_email;
  echo $ops_user;




$current_timestamp = time();

$date = date('d/m/Y', $current_timestamp);

$time = date('H:i', $current_timestamp);

$timestamp = $date . ' ' . $time;



?>
