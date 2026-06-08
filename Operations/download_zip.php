<?php
if (isset($_GET['file_id'])) {
    $file_id = intval($_GET['file_id']);
   require_once("db_connect.php");
    // Retrieve file information from database
    $sql = "SELECT * FROM payment_request WHERE id = $file_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $file = $result->fetch_assoc();
        $filepath = $file['vehicle_images'];

        // Check if file exists
        if (file_exists($filepath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            flush(); // Flush system output buffer
            readfile($filepath);
            exit;
        } else {
            echo "File does not exist.";
        }
    } else {
        echo "No file found with the given ID.";
    }

    $conn->close();
} else {
    echo "No file ID provided.";
}
?>
