<?php
session_start();
include_once('../check_login.php');

if (isset($_POST['deleteIngroupID'])) {
    echo $deleteId = $_POST['deleteIngroupID'];

    // Prepare the delete statement
    $stmt_delete = "DELETE FROM purchase WHERE id='$deleteId'";
       echo $stmt_delete; exit;
    if (mysqli_query($conn, $stmt_delete)) {
        // Optionally, you can set a success message
        $_SESSION['message'] = "Record deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting record: " . mysqli_error($conn);
    }
}

header("Location: po_request_arvind.php"); 
exit();
?>
