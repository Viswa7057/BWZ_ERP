<?php
include('db_connect.php'); // Ensure this file connects to your database

header('Content-Type: application/json');

if (isset($_POST['vendor_name'])) {
    $vendor_name = mysqli_real_escape_string($conn, $_POST['vendor_name']);
    $van_id = mysqli_real_escape_string($conn, $_POST['id']);

    // Fetch type_of_vendor based on vendor_name
    $query_type = "SELECT DISTINCT type_of_vendor FROM purchase WHERE vendor_name='$vendor_name' AND id ='$van_id' AND status='Received' LIMIT 1";
    $result_type = mysqli_query($conn, $query_type);
    $type_of_vendor = '';
    if ($row = mysqli_fetch_assoc($result_type)) {
        $type_of_vendor = $row['type_of_vendor'];
    }

    // Fetch campaign_code based on vendor_name
    $query_campaign = "SELECT DISTINCT campaign_code FROM purchase WHERE vendor_name='$vendor_name' AND id ='$van_id' AND status='Received' LIMIT 1";
    $result_campaign = mysqli_query($conn, $query_campaign);
    $campaign_code = '';
    if ($row = mysqli_fetch_assoc($result_campaign)) {
        $campaign_code = $row['campaign_code'];
    }

    $response = array(
        'type_of_vendor' => $type_of_vendor,
        'campaign_code' => $campaign_code
    );

    echo json_encode($response);
} else {
    echo json_encode(array('error' => 'No vendor name provided.'));
}
?>
