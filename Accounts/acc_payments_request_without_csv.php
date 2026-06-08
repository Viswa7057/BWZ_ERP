<?php
include_once('../check_login.php');
// Check if the user is not logged in, redirect to index.php
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}
require_once("db_connect.php");
session_start();

$loggedInuserName  = $_SESSION['username'];
$loggedInUserLevel = $_SESSION['user_level'];

$stmt_rs = "SELECT user_name,email from users where email='$loggedInuserName';";
$rslt_rs = mysqli_query($conn, $stmt_rs);
$row_rs = mysqli_fetch_row($rslt_rs);
$usename = $row_rs[0];
$sale_email = $row_rs[1];

// Handle Vendor Payment Accept
if ($_POST['accept_neworder']) {

  $id = $_REQUEST["acceptID"];
  $invoice_value = $_REQUEST["invoice_value"];
  $company_name = $_REQUEST["txt_vendorname"];
  $sales = $_REQUEST["txt_salename"];

  $payment_type = $_REQUEST["payment_type"];


  $targetFile_VD = basename($_FILES["invoice"]["name"]);
  $allowedFileTypes_VD = array("pdf");
  $fileExtension_VD = strtolower(pathinfo($targetFile_VD, PATHINFO_EXTENSION));

  if (!in_array($fileExtension_VD, $allowedFileTypes_VD)) {

    echo '<script>alert("Sorry, Only PDF files are allowed.")</script>';
  } else {

    $file_name_VD = $_FILES['invoice']['name'];
    $upload_dir_VD = 'Invoice/'; // Directory to store uploaded files
    $file_path_VD = $upload_dir_VD . $file_name_VD;
    move_uploaded_file($_FILES['invoice']['tmp_name'], $file_path_VD);

    $stmt_update = "UPDATE payment_request SET PI_invoice='$file_name_VD',payment_types='$payment_type',acc_status='Accepted',status='CEO' WHERE id='$id'";
    //echo $stmt_update; exit;
    $rslt_update = mysqli_query($conn, $stmt_update);

    $stmt_ops = "SELECT email,user_name from users where user_level='CEO';";
    $rslt_ops = mysqli_query($conn, $stmt_ops);
    $row_ops = mysqli_fetch_row($rslt_ops);
    $ops_email = $row_ops[0];
    $ops_user = $row_ops[1];

    // After submit the form Send mail to RM
    require "../Mail/phpmailer/PHPMailerAutoload.php";
    $mail = new PHPMailer;

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';

    $mail->Username = 'erp@brandonwheelz.com'; // Replace with your email
    $mail->Password = 'bytj wubr exys ggad'; // Replace with your email password

    $mail->setFrom('erp@brandonwheelz.com', 'ERP');
    $adminEmail = "$ops_email"; // Replace with the desired email address
    $mail->addAddress($adminEmail);

    $mail->isHTML(true);
    $mail->Subject = "Invoice Approve Request";
    $mail->Body = "<p>Dear $ops_user, </p> <h3>Kindly check the invoice doc for the $company_name & Please approve.<br></h3>
                     Click the below link for login<br>
					 http://brandonwheelz.in/index.php<br>
                     <p></p>
                     <b></b>";

    $mail->send();
  }
}

// Handle Vendor Payment Voucher Upload
if ($_POST['accept_voucher']) {

  $id = $_REQUEST["payment_id"];
  $company_name = $_REQUEST["txt_vendor_name"];


  $targetFile_VD = basename($_FILES["voucher"]["name"]);
  $allowedFileTypes_VD = array("pdf");
  $fileExtension_VD = strtolower(pathinfo($targetFile_VD, PATHINFO_EXTENSION));

  if (!in_array($fileExtension_VD, $allowedFileTypes_VD)) {

    echo '<script>alert("Sorry, Only PDF files are allowed.")</script>';
  } else {

    $file_name_VD = $_FILES['voucher']['name'];
    $upload_dir_VD = 'Voucher/'; // Directory to store uploaded files
    $file_path_VD = $upload_dir_VD . $file_name_VD;
    move_uploaded_file($_FILES['voucher']['tmp_name'], $file_path_VD);

    $stmt_update = "UPDATE payment_request SET voucher='$file_name_VD',status='Waiting For Voucher Approval',ceo_update='Voucher Uploaded',acc_status='Accepted' WHERE id='$id'";
    //echo $stmt_update; exit;
    $rslt_update = mysqli_query($conn, $stmt_update);

    $stmt_ops = "SELECT email,user_name from users where user_level='Director';";
    $rslt_ops = mysqli_query($conn, $stmt_ops);
    $row_ops = mysqli_fetch_row($rslt_ops);
    $ops_email = $row_ops[0];
    $ops_user = $row_ops[1];

    // After submit the form Send mail to RM
    require "../Mail/phpmailer/PHPMailerAutoload.php";
    $mail = new PHPMailer;

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';

    $mail->Username = 'erp@brandonwheelz.com'; // Replace with your email
    $mail->Password = 'bytj wubr exys ggad'; // Replace with your email password

    $mail->setFrom('erp@brandonwheelz.com', 'ERP');
    $adminEmail = "$ops_email"; // Replace with the desired email address
    $mail->addAddress($adminEmail);

    $mail->isHTML(true);
    $mail->Subject = "Voucher Approve Request";
    $mail->Body = "<p>Dear $ops_user, </p> <h3>Kindly check the voucher for the payment & Please approve.<br></h3>
                     Click the below link for login<br>
					 http://brandonwheelz.in/index.php<br>
                     <p></p>
                     <b></b>";

    $mail->send();
  }
}

// Handle Vendor Payment Reject
if ($_POST['reject_neworder']) {

  $txt_id = $_POST["deleteIngroupID"];
  $txt_reason = $_POST["txt_reason"];
  $txt_name = $_POST["txt_name"];
  $van_email = $_POST["ven_email"];

$stmt_update = "UPDATE payment_request SET acc_status='Rejected',comments='$txt_reason' WHERE id='$txt_id'";
$rslt_update = mysqli_query($conn, $stmt_update); 
  
   $stmt_sale = "SELECT email,user_name from users where user_level='Operations';";
  $rslt_sale = mysqli_query($conn, $stmt_sale);
  $row_sale = mysqli_fetch_row($rslt_sale);
  $sale_email = $row_sale[0];
  $op_name = $row_sale[1];
  
  // After reject the sales Send mail to sales person
  require "../Mail/phpmailer/PHPMailerAutoload.php";
  $mail = new PHPMailer;

  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->Port = 587;
  $mail->SMTPAuth = true;
  $mail->SMTPSecure = 'tls';

  $mail->Username = 'erp@brandonwheelz.com'; // Replace with your email
  $mail->Password = 'bytj wubr exys ggad'; // Replace with your email password

  $mail->setFrom('erp@brandonwheelz.com', 'ERP');
  $adminEmail = "$sale_email"; // Replace with the desired email address
  $mail->addAddress($adminEmail);

  $mail->isHTML(true);
  $mail->Subject = "Payment Request Rejected";
  $mail->Body = "<p>Dear $op_name, </p> <h3>Your Payment Request has been rejected by Accounts team, because of $txt_reason.<br></h3>
                     <p></p>
                     <b></b>";

  $mail->send();
}

// Handle Internal Payment Accept
if ($_POST['accept_internal_payment']) {

  $id = $_REQUEST["acceptID_internal"];
  $employee_name = $_REQUEST["txt_employee_name_internal"];
  $payment_type = $_REQUEST["payment_type_internal"];

  $targetFile_VD = basename($_FILES["invoice_internal"]["name"]);
  $allowedFileTypes_VD = array("pdf");
  $fileExtension_VD = strtolower(pathinfo($targetFile_VD, PATHINFO_EXTENSION));

  if (!in_array($fileExtension_VD, $allowedFileTypes_VD)) {
    echo '<script>alert("Sorry, Only PDF files are allowed.")</script>';
  } else {

    $file_name_VD = $_FILES['invoice_internal']['name'];
    $upload_dir_VD = 'Internal_Invoice/';
    
    // Create directory if it doesn't exist
    if (!file_exists($upload_dir_VD)) {
      mkdir($upload_dir_VD, 0777, true);
    }
    
    $file_path_VD = $upload_dir_VD . $file_name_VD;
    move_uploaded_file($_FILES['invoice_internal']['tmp_name'], $file_path_VD);

    $stmt_update = "UPDATE internal_payment_request SET internal_invoice='$file_name_VD',payment_types='$payment_type',acc_status='Accepted',status='CEO' WHERE id='$id'";
    $rslt_update = mysqli_query($conn, $stmt_update);

    $stmt_ops = "SELECT email,user_name from users where user_level='CEO';";
    $rslt_ops = mysqli_query($conn, $stmt_ops);
    $row_ops = mysqli_fetch_row($rslt_ops);
    $ops_email = $row_ops[0];
    $ops_user = $row_ops[1];

    // Send mail to CEO
    require "../Mail/phpmailer/PHPMailerAutoload.php";
    $mail = new PHPMailer;

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';

    $mail->Username = 'erp@brandonwheelz.com';
    $mail->Password = 'bytj wubr exys ggad';

    $mail->setFrom('erp@brandonwheelz.com', 'ERP');
    $adminEmail = "$ops_email";
    $mail->addAddress($adminEmail);

    $mail->isHTML(true);
    $mail->Subject = "Internal Payment Invoice Approve Request";
    $mail->Body = "<p>Dear $ops_user, </p> <h3>Kindly check the invoice for internal payment of $employee_name & Please approve.<br></h3>
                     Click the below link for login<br>
					 http://brandonwheelz.in/index.php<br>
                     <p></p>
                     <b></b>";

    $mail->send();
  }
}

// Handle Internal Payment Voucher Upload
if ($_POST['accept_internal_voucher']) {

  $id = $_REQUEST["payment_id_internal"];
  $employee_name = $_REQUEST["txt_employee_name_voucher"];

  $targetFile_VD = basename($_FILES["voucher_internal"]["name"]);
  $allowedFileTypes_VD = array("pdf");
  $fileExtension_VD = strtolower(pathinfo($targetFile_VD, PATHINFO_EXTENSION));

  if (!in_array($fileExtension_VD, $allowedFileTypes_VD)) {
    echo '<script>alert("Sorry, Only PDF files are allowed.")</script>';
  } else {

    $file_name_VD = $_FILES['voucher_internal']['name'];
    $upload_dir_VD = 'Internal_Voucher/';
    
    // Create directory if it doesn't exist
    if (!file_exists($upload_dir_VD)) {
      mkdir($upload_dir_VD, 0777, true);
    }
    
    $file_path_VD = $upload_dir_VD . $file_name_VD;
    move_uploaded_file($_FILES['voucher_internal']['tmp_name'], $file_path_VD);

    $stmt_update = "UPDATE internal_payment_request SET voucher='$file_name_VD',status='Waiting For Voucher Approval',acc_status='Accepted' WHERE id='$id'";
    $rslt_update = mysqli_query($conn, $stmt_update);

    $stmt_ops = "SELECT email,user_name from users where user_level='Director';";
    $rslt_ops = mysqli_query($conn, $stmt_ops);
    $row_ops = mysqli_fetch_row($rslt_ops);
    $ops_email = $row_ops[0];
    $ops_user = $row_ops[1];

    // Send mail to Director
    require "../Mail/phpmailer/PHPMailerAutoload.php";
    $mail = new PHPMailer;

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';

    $mail->Username = 'erp@brandonwheelz.com';
    $mail->Password = 'bytj wubr exys ggad';

    $mail->setFrom('erp@brandonwheelz.com', 'ERP');
    $adminEmail = "$ops_email";
    $mail->addAddress($adminEmail);

    $mail->isHTML(true);
    $mail->Subject = "Internal Payment Voucher Approve Request";
    $mail->Body = "<p>Dear $ops_user, </p> <h3>Kindly check the voucher for internal payment & Please approve.<br></h3>
                     Click the below link for login<br>
					 http://brandonwheelz.in/index.php<br>
                     <p></p>
                     <b></b>";

    $mail->send();
  }
}

// Handle Internal Payment Reject
if ($_POST['reject_internal_payment']) {

  $txt_id = $_POST["deleteIngroupID_internal"];
  $txt_reason = $_POST["txt_reason_internal"];
  $txt_name = $_POST["txt_name_internal"];

  $stmt_update = "UPDATE internal_payment_request SET acc_status='Rejected',acc_comments='$txt_reason' WHERE id='$txt_id'";
  $rslt_update = mysqli_query($conn, $stmt_update); 
  
  $stmt_sale = "SELECT email,user_name from users where user_level='Operations' order by id desc limit 1;";
  $rslt_sale = mysqli_query($conn, $stmt_sale);
  $row_sale = mysqli_fetch_row($rslt_sale);
  $sale_email = $row_sale[0];
  $op_name = $row_sale[1];
  
  // Send rejection mail
  require "../Mail/phpmailer/PHPMailerAutoload.php";
  $mail = new PHPMailer;

  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->Port = 587;
  $mail->SMTPAuth = true;
  $mail->SMTPSecure = 'tls';

  $mail->Username = 'erp@brandonwheelz.com';
  $mail->Password = 'bytj wubr exys ggad';

  $mail->setFrom('erp@brandonwheelz.com', 'ERP');
  $adminEmail = "$sale_email";
  $mail->addAddress($adminEmail);

  $mail->isHTML(true);
  $mail->Subject = "Internal Payment Request Rejected";
  $mail->Body = "<p>Dear $op_name, </p> <h3>Your Internal Payment Request has been rejected by Accounts team, because of $txt_reason.<br></h3>
                     <p></p>
                     <b></b>";

  $mail->send();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
  <meta name="viewport" content="width=1500">
  <title>Payment Request</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
  <!-- BS Stepper -->
  <link rel="stylesheet" href="plugins/bs-stepper/css/bs-stepper.min.css">
  <!-- dropzonejs -->
  <link rel="stylesheet" href="plugins/dropzone/min/dropzone.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <style>
    body {
      overflow-y: scroll;
      overflow-x: scroll;
    }

    thead th,
    table.dataTable thead td {
      padding: 15px 18px !important;
      border-bottom: 1px solid #dee2e6 !important;
    }

    table.dataTable.no-footer {
      border-bottom: 1px solid #dee2e6 !important;
    }

    .dataTables_wrapper .dataTables_info {
      clear: both;
      float: left;
      padding: 10px !important;
      font-size: smaller !important;
    }

    .dataTables_wrapper .dataTables_paginate {
      float: right;
      text-align: right;
      padding: 10px !important;
      font-size: smaller !important;
    }

    .dataTables_wrapper .dataTables_length {
      float: left;
      padding: 10px !important;
      font-size: smaller !important;
    }

    .dataTables_wrapper .dataTables_filter {
      float: right;
      text-align: right;
      padding: 10px !important;
      font-size: smaller !important;
    }

    .dataTables_wrapper .dataTables_filter input {
      border: 1px solid #dee2e6 !important;
    }

    .dt-button-collection {
      margin-top: 2.5px !important;
      margin-bottom: 5px !important;
    }

    footer {
      text-align: center;
      padding: 3px;
      background-color: #fff;
      color: #3a403a;
      font-size: 20px;

    }

    ul,
    li,
    a {
      color: #fff;
      text-decoration: none;
      list-style-type: none;
      margin-left: 5px;
      font-size: 20px;
    }

    a:hover {
      color: #fff;
    }

    .logo {
      max-width: 198px;
      height: 59px;
      display: block;
      margin: 0 auto;
      margin-top: 13px;
      margin-left: 14px;
    }

    #adduserButton {
      background-color: #36b9cc;
      color: #fff;
      padding: 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 10px;
      margin-left: 20px;
    }

    label {
      font-weight: normal;
    }

    .form-group-a label {
      font-weight: normal !important;
      /* Resetting font-weight to normal */
    }

    .form-group-a input[type="checkbox"]+label {
      font-weight: normal !important;
      /* Resetting font-weight to normal specifically for labels next to checkboxes */
    }

    #imageViewer {
      max-width: 100%;
      max-height: 500px;
    }

    #imageModal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.9);
      /* Semi-transparent white background */
      justify-content: center;
      align-items: center;
    }

    #modalContent {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.7);
      max-width: 90%;
      /* Adjust as needed */
      max-height: 90%;
      /* Adjust as needed */
      overflow: auto;
      position: relative;
      width: 700px;
    }

    #imageViewer img {
      max-width: 100%;
      /* Set maximum width for the image */
      max-height: 500px;
      /* Set maximum height for the image */
    }

    #closeBtn {
      position: absolute;
      top: -4px;
      right: 3px;
      cursor: pointer;
      font-size: 30px;
      color: #f00;
    }

    /* #downloadLink {
            display: block;
            margin-top: 10px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
        } */
    #downloadLink {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    #downloadLink:hover {
      background-color: #45a049;
    }

    #popup {
      display: none;
      position: absolute;
      background: white;
      border-radius: 10px;
      box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.7);
      /* Adding box shadow */
      padding: 20px;

    }

    #pdfViewer {
      width: 100%;
      height: 500px;
    }

    #pdfModal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      /*background: rgba(255, 255, 255, 0.9);*/
      /* Semi-transparent white background */
      justify-content: center;
      align-items: center;
      margin-top: 70px;
    }

    #modalContent {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.7);
      max-width: 90%;
      /* Adjust as needed */
      max-height: 90%;
      /* Adjust as needed */
      overflow: auto;
      position: relative;
      width: 700px;
    }

    #closeBtn {
      position: absolute;
      top: -4px;
      right: 3px;
      cursor: pointer;
      font-size: 30px;
      color: #f00;
    }

    .sidebar .menu-link {
      position: relative;
      display: block;
      padding: 8px 16px;
      color: #fff;
    }

    .sidebar .menu-link:hover::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 255, 255, 0.5);
      /* Transparent white */
      z-index: -1;
    }

    .purchase-ops {
      background-color: #b52f34;
      border-color: #b52f34;
    }

    .purchase-ops:hover {
      background-color: darkred;
      border-color: darkred;
    }
    
    .nav-tabs .nav-link {
      color: #495057;
      background-color: #f8f9fa;
      border: 1px solid #dee2e6;
    }
    
    .nav-tabs .nav-link.active {
      color: #fff;
      background-color: #b52f34;
      border-color: #b52f34;
    }
  </style>
  <script>
    $(document).ready(function() {
      sampleDiv_zoom.style.zoom = '80%';
      var scale = 'scale(1)';
      document.body.style.webkitTransform = scale; // Chrome, Opera, Safari
      document.body.style.msTransform = scale; // IE 9
      document.body.style.transform = scale; // General
    });
  </script>
  <!-- Include Flatpickr CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  <!-- Include jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <!-- Include Flatpickr JS -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body class="hold-transition sidebar-mini" id="sampleDiv_zoom">
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="height:90px;">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item d-none d-sm-inline-block">
          <h2 style="margin-left:50px;color: #b52f34;"><b><i class="fa fa-check-square" aria-hidden="true"></i> Payment Request</b></h2>
        </li>

      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item" style="display:none;">
          <a class="nav-link" data-widget="navbar-search" href="#" role="button">
            <i class="fas fa-search"></i>
          </a>
          <div class="navbar-search-block">
            <form class="form-inline">
              <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                  <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                  <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </li>

        <!-- Messages Dropdown Menu -->

        <!-- Notifications Dropdown Menu -->

        <li class="nav-item" style="color:#b52f34; font-size:25px;">
          <i class="fa fa fa-user-circle" aria-hidden="true" style="color:#b52f34;"></i>&nbsp;<b><?php echo $usename; ?></b>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php" title="Signout" role="button"><i class="fas fa-sign-out-alt" style="color:red;"></i></a>
        </li>
        <li class="nav-item" style="display:none;">
          <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
            <i class="fas fa-th-large"></i>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar" style=" background: linear-gradient(327deg, #ff0000a3, #9cb3d7  ); color: #FFFFFF;">
      <!-- Brand Logo -->
      <img src="../images/bwz1.png" alt="Admin Logo" class="logo">
      <!-- Sidebar -->
      <div class="sidebar">

        <!-- SidebarSearch Form -->
        <div class="form-inline" style="display:none;">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <hr style="border: 0.5px solid #b52f34;border-radius: 2px;"><br>
          <!-- Add sidebar content as needed -->
          <li><a href="accounts_dashboard.php" class="menu-link"><i class="fa fa-th-large " aria-hidden="true"></i> Dashboard</a></li><br><br>
          <!-- <li><a href="ops_details.php" class="menu-link"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Sales Details</a></li><br><br> -->
          <li><a href="acc_invoice_request.php" class="menu-link"><i class="fa fa-check-circle" aria-hidden="true"></i> Invoice Request</a></li><br><br>
          <li><a href="acc_po_request.php" class="menu-link"><i class="fa fa-check-circle" aria-hidden="true"></i> Vendor PO Request</a></li><br><br>
          <li><a href="acc_payments_request.php" class="menu-link"><i class="fa fa-check-circle" aria-hidden="true"></i> Payments Request</a></li><br><br>
          <li style="font-weight:bold;color: #ffff;">Reports</li><br><br>
          <li><a href="po_report.php" class="menu-link"><i class="fa fa-align-center" aria-hidden="true"></i> PO Report</a></li><br><br>
          <li><a href="payment_report.php" class="menu-link"><i class="fa fa-align-center" aria-hidden="true"></i> Payement Request Report</a></li><br><br>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <!-- <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">

            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
              </ol>
            </div>
          </div>
        </div>
      </section> -->

      <!-- Main content -->
      <section class="content" style="background: linear-gradient(327deg, #ff0000a3, #FFFFFF), url('../images/bwz_erp_bg.jpg'); background-size: cover; background-position: center center;">
        <div class="container-fluid">


          <!-- SELECT2 EXAMPLE -->
          <div class="card card-default">
            <div class="card-header">

              <div class="card-tools">
                <!-- btn -->

                <!-- btn end -->
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" style="display:none;" class="btn btn-tool" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

              <!-- Tabs -->
              <ul class="nav nav-tabs" id="paymentTabs" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="vendor-tab" data-toggle="tab" href="#vendorPayments" role="tab">Vendor Payments</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="internal-tab" data-toggle="tab" href="#internalPayments" role="tab">Internal Payments</a>
                </li>
              </ul>

              <div class="tab-content" id="paymentTabsContent">
                <!-- Vendor Payments Tab -->
                <div class="tab-pane fade show active" id="vendorPayments" role="tabpanel">
                  <div class="row" style="overflow-x: auto; margin-top: 20px;">
                    <div class="col-md-12">
                      <div class="card-body p-0">
                        <table class="table table-striped projects table-bordered" id="table_camp">
                          <thead>
                            <tr>
                              <th style="width: 2%">Sr.No.</th>
                              <th style="width: 2%">Date/Time</th>
                              <th style="width:20%">Campaign Code</th>
                               <th style="width:20%">Vendor Id</th>
                              <th style="width:20%">Vendor Name</th>
                              <th style="width:20%">Type Of Vendor</th>
                              <th style="width:20%">Invoice Value</th>
                              <th style="width:30%">Payment Type</th>
                              <th style="width:20%">Status</th>
                              <th style="width:30%">Payment Status</th>
                              <th style="width:50%">Op's Comments</th>
                              <th style="width:50%">Acc Status</th>
                              <th style="width:50%">Ceo Status</th>
                              <th style="width:50%">Director Status</th>
                              <th style="width:50%">Comments</th>
                              <th style="width:20%">View Invoice</th>
                              <th style="width:20%">Voucher</th>
                              <th style="width:20%">View DC</th>
                              <th style="width:20%">View Vehicle Details</th>
                              <th style="width:20%">View Vehicle Images</th>
                              <th style="width:20%">Action</th>
                            </tr>
                          </thead>
                          <tbody>

                            <?php
                            $stmt_select = "SELECT * from payment_request WHERE graph_status='Accepted' order by id desc";
                            $rslt_rs = mysqli_query($conn, $stmt_select);

                            $x = 1;
                            while ($row = mysqli_fetch_assoc($rslt_rs)) {

                              $Invoice_file = "Invoice/" . $row["PI_invoice"];
                              $voucher_file = "Voucher/" . $row["voucher"];
                              $DC_file = "../Operations/DC/" . $row["dc_report"];
                              $VD_file = "../Operations/VD/" . $row["vehicle_details"];
                              $PO_file = "../Operations/PO/" . $row["upload_po"];

                         $vendor_name = $row["vendor_name"];
$stmt_vn = "SELECT vendor_id from vendor_reg where vendor_name='$vendor_name' order by id desc limit 1;";
$rslt_vn = mysqli_query($conn, $stmt_vn);
$row_vn = mysqli_fetch_row($rslt_vn);
$vendor_id = $row_vn[0];

                            ?>
                              <tr>
                                <td><?php echo $x; ?></td>
                                <td>
                                 <?php
                                  echo ($row["date_time"] !== "0000-00-00 00:00:00")
                                      ? $row["date_time"]
                                      : "";
                                  ?>
                                </td>
                                <td><?php echo $row["campaign_code"]; ?></td>
                                 <td><?php echo $vendor_id; ?></td>
                                <td><?php echo $row["vendor_name"]; ?></td>
                                <td><?php echo $row["type_of_vendor"]; ?></td>
                                <td><?php echo $row["invoice"]; ?></td>
                                <td><?php echo $row["payment_type"]; ?></td>
                                <td><?php echo $row["Status"]; ?></td>
                                <td><?php echo $row["ceo_update"]; ?></td>
                                <td><?php echo $row["OP_comments"]; ?></td>
                                <td><?php echo $row["acc_status"]; ?></td>
                                <td><?php echo $row["ceo_status"]; ?></td>
                                <td><?php echo $row["Director_status"]; ?></td>
                                <td><?php echo $row["comments"]; ?></td>

                                <td>
                                  <?php
                                  if ($row["PI_invoice"] != "") {
                                  ?>
                                    <button class="btn btn-primary" onclick="viewInvoice('<?php echo $Invoice_file; ?>')"><i class="fa fa-eye"></i> View</button><br><br>

                                  <?php
                                   if ($row["upload_po"] != "") { ?>
                                    <button class="btn btn-primary" onclick="viewPO('<?php echo $PO_file; ?>')"><i class="fa fa-eye"></i> PO</button>
                                  <?php } 
                                  } else {
                                  }
                                  ?>
                                </td>
                                <td>
                                  <?php
                                  if ($row["voucher"] != "") {
                                  ?>
                                    <button class="btn btn-primary" onclick="viewVoucher('<?php echo $voucher_file; ?>')"><i class="fa fa-eye"></i> View</button>

                                  <?php
                                  } else {
                                  }
                                  ?>
                                </td>
                                <td>
                                  <button class="btn btn-primary" onclick="viewDC('<?php echo $DC_file; ?>')"><i class="fa fa-eye"></i> DC</button>
                                </td>
                                <td>
                                  <button class="btn btn-primary" onclick="viewVD('<?php echo $VD_file; ?>')"><i class="fa fa-eye"></i> View</button>
                                </td>
                                <td>
                                  <?php if ($row["vehicle_images"] == "") { ?>
                                  <?php } else { ?>
                                    <button class="btn btn-success"><a href="download_zip.php?file_id=<?php echo $row["id"]; ?>">Download</a></button>
                                  <?php
                                  }
                                  ?>
                                </td>
                                <td>
                                 <?php if ($row["ceo_status"] == "Accepted" && $row["Director_status"] == "Accepted") { }else{?>
                                    <button class="btn btn-success" style="width: 91px;" onclick="upload_voucher('<?php echo $row['id']; ?>','<?php echo $row['vendor_name']; ?>')">Upload Voucher</button>
                                  <?php  } ?>
                                  <br><br>
                                  <?php if ($row["acc_status"] == "") {?>
                                  <button class="btn btn-danger btn-sm" style="width: 91px;" onclick="rejectInstallation('<?php echo $row['id']; ?>','<?php echo $row['vendor_name']; ?>')">Reject</button>
                                  <?php  }else{} ?>
                                </td>

                              </tr>

                            <?php $x++;
                            } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Internal Payments Tab -->
                <div class="tab-pane fade" id="internalPayments" role="tabpanel">
                  <div class="row" style="overflow-x: auto; margin-top: 20px;">
                    <div class="col-md-12">
                      <div class="card-body p-0">
                        <table class="table table-striped projects table-bordered" id="table_internal">
                          <thead>
                            <tr>
                              <th style="width: 2%">Sr.No.</th>
                              <th style="width: 2%">Date/Time</th>
                              <th style="width:20%">Employee Name</th>
                              <th style="width:20%">Total Amount</th>
                              <th style="width:30%">Payment Type</th>
                              <th style="width:20%">Status</th>
                              <th style="width:50%">OP's Comments</th>
                              <th style="width:50%">Acc Status</th>
                              <th style="width:50%">Ceo Status</th>
                              <th style="width:50%">Director Status</th>
                              <th style="width:50%">Comments</th>
                              
                              <th style="width:20%">View Details</th>
                              <th style="width:20%">View Invoice</th>
                              <th style="width:20%">Voucher</th>
                              <th style="width:20%">Action</th>
                            </tr>
                          </thead>
                          <tbody>

                            <?php
                            $stmt_select_internal = "SELECT * from internal_payment_request order by id desc";
                            $rslt_internal = mysqli_query($conn, $stmt_select_internal);

                            $y = 1;
                            while ($row_internal = mysqli_fetch_assoc($rslt_internal)) {
                              $details_file = "../Operations/Internal_Payment_Details/" . $row_internal["upload_details"];
                              $internal_invoice_file = "Internal_Invoice/" . $row_internal["internal_invoice"];
                              $internal_voucher_file = "Internal_Voucher/" . $row_internal["voucher"];
                            ?>
                              <tr>
                                <td><?php echo $y; ?></td>
                                <td>
                                 <?php
                                  echo ($row_internal["date_time"] !== "0000-00-00 00:00:00")
                                      ? $row_internal["date_time"]
                                      : "";
                                  ?>
                                </td>
                                <td><?php echo $row_internal["employee_name"]; ?></td>
                                <td><?php echo $row_internal["total_amount"]; ?></td>
                                <td><?php echo $row_internal["payment_type"]; ?></td>
                                <td><?php echo $row_internal["status"]; ?></td>
                                <td><?php echo $row_internal["comments"]; ?></td>
                                <td><?php echo $row_internal["acc_status"]; ?></td>
                                <td><?php echo $row_internal["ceo_status"]; ?></td>
                                <td><?php echo $row_internal["director_status"]; ?></td>
                                <td><?php echo $row_internal["acc_comments"]; ?> <?php echo $row_internal["ceo_comments"]; ?> <?php echo $row_internal["director_comments"]; ?></td>
                               
                                <td>
                                    <?php
                                  if ($row_internal["upload_details"] != "") {
                                  ?>
                                  <button class="btn btn-primary" onclick="viewDetails('<?php echo $details_file; ?>')"><i class="fa fa-eye"></i> View</button>
                                   <?php
                                  } else {
                                  }
                                  ?>
                                </td>
                                <td>
                                  <?php
                                  if ($row_internal["internal_invoice"] != "") {
                                  ?>
                                    <button class="btn btn-primary" onclick="viewInternalInvoice('<?php echo $internal_invoice_file; ?>')"><i class="fa fa-eye"></i> View</button>
                                  <?php
                                  } else {
                                  }
                                  ?>
                                </td>
                                <td>
                                  <?php
                                  if ($row_internal["voucher"] != "") {
                                  ?>
                                    <button class="btn btn-primary" onclick="viewInternalVoucher('<?php echo $internal_voucher_file; ?>')"><i class="fa fa-eye"></i> View</button>
                                  <?php
                                  } else {
                                  }
                                  ?>
                                </td>
                                <td>
                                 <?php if ($row_internal["ceo_status"] == "Accepted" && $row_internal["director_status"] == "Accepted") { }else{?>
                                    <button class="btn btn-success" style="width: 91px;" onclick="upload_internal_voucher('<?php echo $row_internal['id']; ?>','<?php echo $row_internal['employee_name']; ?>')">Upload Voucher</button>
                                  <?php  } ?>
                                  <br><br>
                                  <?php if ($row_internal["acc_status"] == "") {?>
                                  <button class="btn btn-danger btn-sm" style="width: 91px;" onclick="rejectInternalPayment('<?php echo $row_internal['id']; ?>','<?php echo $row_internal['employee_name']; ?>')">Reject</button>
                                  <?php  }else{} ?>
                                </td>
                              </tr>
                            <?php $y++; } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- /.row -->
            </div>
            <!-- /.card-body -->
            <!-- btn -->
            <!-- Modal -->

          </div>
          <!-- btn end -->
        </div>
        <!-- /.card -->

  <!-- Vendor Payment Invoice Upload Modal -->
  <div class="modal fade" id="myModal_accept" style="opacity: 3;top : 104px !important">
    <div class="modal-dialog">
      <div class="modal-content" style="top:40px;height:500px;overflow-y:auto;">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Invoice/ PI</h4>
          <button type="button" class="close24" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
            <div class="card-body">
              <div class="form-group">
                <label for="UserID">Company Name</label>
                <input type="text" class="form-control" title="Please enter only characters" id="txt_vendorname" name="txt_vendorname" readonly>
              </div>

              <div class="form-group" style="width:300px;">
                <label for="UserID">Payment Types<span style="color:red;">*</span></label>
                <select class="form-control select2" style="width: 100%;" id="payment_type" name="payment_type" required>
                  <option value="Weekly">Weekly</option>
                  <option value="Adoc">Adoc</option>
                  <option value="24hours">24hours</option>
                  <option value="4hours"> Priority 4hours</option>
                </select>
              </div>
              <div class="form-group">
                <label for="IngroupName">Upload Invoice/ PI<span style="color:red;">*</span></label>
                <input type="file" class="form-control" id="invoice" accept=".pdf" name="invoice" required><span style="font-size: 10px;font-family: Open Sans;"><b>Only PDF</b></span>
              </div>

            </div>
            <input type="hidden" value="" id="txt_salename" name="txt_salename">
            <input type="hidden" value="" id="acceptID" name="acceptID">
            <!-- /.card-body -->

            <div class="card-footer">
              <input type="submit" class="btn btn-success" name="accept_neworder" value="Upload">
            </div>
          </form>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
        </div>

      </div>
    </div>
  </div>

  <!-- Vendor Payment Voucher Upload Modal -->
  <div class="modal fade" id="myModal_voucher" style="opacity: 3;top : 104px !important">
    <div class="modal-dialog">
      <div class="modal-content" style="top:40px;height:500px;overflow-y:auto;">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Upload Voucher</h4>
          <button type="button" class="close25" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
            <div class="card-body">
              <div class="form-group">
                <label for="UserID">Company Name</label>
                <input type="text" class="form-control" title="Please enter only characters" id="txt_vendor_name" name="txt_vendor_name" readonly>
              </div>

              <div class="form-group">
                <label for="IngroupName">Upload Voucher<span style="color:red;">*</span></label>
                <input type="file" class="form-control" id="voucher" accept=".pdf" name="voucher" required><span style="font-size: 10px;font-family: Open Sans;"><b>Only PDF</b></span>
              </div>

            </div>
            <input type="hidden" value="" id="payment_id" name="payment_id">
            <!-- /.card-body -->

            <div class="card-footer">
              <input type="submit" class="btn btn-success" name="accept_voucher" value="Submit">
            </div>
          </form>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
        </div>

      </div>
    </div>
  </div>

  <!-- Vendor Payment Reject Modal -->
  <div class="modal fade" id="myModal_delete" style="opacity: 3;top : 104px !important">
    <div class="modal-dialog">
      <div class="modal-content" style="top:40px;">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Reject Vendor Payment</h4>
          <button type="button" class="close23" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="card-body">
              <div class="form-group">
                <label for="UserID">Vendor Name</label>
                <input type="text" class="form-control" title="Please enter only numbers and characters" id="txt_name" name="txt_name" readonly>
              </div>
              <div class="form-group">
                <label for="IngroupName">Reason For Reject<span style="color:red;">*</span></label>
                <textarea class="form-control" id="txt_reason" name="txt_reason" placeholder="Enter reason for reject" required></textarea>
              </div>
            </div>
            <input type="hidden" value="" id="deleteIngroupID" name="deleteIngroupID">
            <input type="hidden" value="" id="ven_email" name="ven_email">
            <!-- /.card-body -->

            <div class="card-footer">
              <input type="submit" class="btn btn-success" name="reject_neworder" value="Reject">
            </div>
          </form>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
        </div>

      </div>
    </div>
  </div>

  <!-- Internal Payment Invoice Upload Modal -->
  <div class="modal fade" id="myModal_accept_internal" style="opacity: 3;top : 104px !important">
    <div class="modal-dialog">
      <div class="modal-content" style="top:40px;height:500px;overflow-y:auto;">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Internal Payment Invoice</h4>
          <button type="button" class="close26" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
            <div class="card-body">
              <div class="form-group">
                <label for="UserID">Employee Name</label>
                <input type="text" class="form-control" id="txt_employee_name_internal" name="txt_employee_name_internal" readonly>
              </div>

              <div class="form-group" style="width:300px;">
                <label for="UserID">Payment Types<span style="color:red;">*</span></label>
                <select class="form-control select2" style="width: 100%;" id="payment_type_internal" name="payment_type_internal" required>
                  <option value="Weekly">Weekly</option>
                  <option value="Adoc">Adoc</option>
                  <option value="24hours">24hours</option>
                  <option value="4hours"> Priority 4hours</option>
                </select>
              </div>
              <div class="form-group">
                <label for="IngroupName">Upload Invoice<span style="color:red;">*</span></label>
                <input type="file" class="form-control" id="invoice_internal" accept=".pdf" name="invoice_internal" required><span style="font-size: 10px;font-family: Open Sans;"><b>Only PDF</b></span>
              </div>

            </div>
            <input type="hidden" value="" id="acceptID_internal" name="acceptID_internal">
            <!-- /.card-body -->

            <div class="card-footer">
              <input type="submit" class="btn btn-success" name="accept_internal_payment" value="Upload">
            </div>
          </form>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
        </div>

      </div>
    </div>
  </div>

  <!-- Internal Payment Voucher Upload Modal -->
  <div class="modal fade" id="myModal_voucher_internal" style="opacity: 3;top : 104px !important">
    <div class="modal-dialog">
      <div class="modal-content" style="top:40px;height:500px;overflow-y:auto;">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Upload Internal Payment Voucher</h4>
          <button type="button" class="close27" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
            <div class="card-body">
              <div class="form-group">
                <label for="UserID">Employee Name</label>
                <input type="text" class="form-control" id="txt_employee_name_voucher" name="txt_employee_name_voucher" readonly>
              </div>

              <div class="form-group">
                <label for="IngroupName">Upload Voucher<span style="color:red;">*</span></label>
                <input type="file" class="form-control" id="voucher_internal" accept=".pdf" name="voucher_internal" required><span style="font-size: 10px;font-family: Open Sans;"><b>Only PDF</b></span>
              </div>

            </div>
            <input type="hidden" value="" id="payment_id_internal" name="payment_id_internal">
            <!-- /.card-body -->

            <div class="card-footer">
              <input type="submit" class="btn btn-success" name="accept_internal_voucher" value="Submit">
            </div>
          </form>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
        </div>

      </div>
    </div>
  </div>

  <!-- Internal Payment Reject Modal -->
  <div class="modal fade" id="myModal_delete_internal" style="opacity: 3;top : 104px !important">
    <div class="modal-dialog">
      <div class="modal-content" style="top:40px;">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Reject Internal Payment</h4>
          <button type="button" class="close28" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="card-body">
              <div class="form-group">
                <label for="UserID">Employee Name</label>
                <input type="text" class="form-control" id="txt_name_internal" name="txt_name_internal" readonly>
              </div>
              <div class="form-group">
                <label for="IngroupName">Reason For Reject<span style="color:red;">*</span></label>
                <textarea class="form-control" id="txt_reason_internal" name="txt_reason_internal" placeholder="Enter reason for reject" required></textarea>
              </div>
            </div>
            <input type="hidden" value="" id="deleteIngroupID_internal" name="deleteIngroupID_internal">
            <!-- /.card-body -->

            <div class="card-footer">
              <input type="submit" class="btn btn-success" name="reject_internal_payment" value="Reject">
            </div>
          </form>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
        </div>

      </div>
    </div>
  </div>

  <!-- /.row -->

  </div>
  <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer>
    <p>© 2024. All rights reserved by Brand On Wheelz</p>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Select2 -->
  <script src="plugins/select2/js/select2.full.min.js"></script>
  <!-- Bootstrap4 Duallistbox -->
  <script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
  <!-- InputMask -->
  <script src="plugins/moment/moment.min.js"></script>
  <script src="plugins/inputmask/jquery.inputmask.min.js"></script>
  <!-- date-range-picker -->
  <script src="plugins/daterangepicker/daterangepicker.js"></script>
  <!-- bootstrap color picker -->
  <script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- Bootstrap Switch -->
  <script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
  <!-- BS-Stepper -->
  <script src="plugins/bs-stepper/js/bs-stepper.min.js"></script>
  <!-- dropzonejs -->
  <script src="plugins/dropzone/min/dropzone.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <!--<script src="dist/js/demo.js"></script> -->
  <!-- Page specific script -->
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
  <div id="popup" class="popup" style="display: none;margin-top:-450px;margin-left:800px;">
    <span id="closeBtn" onclick="closeModal()"><b>&times;</b></span>
    <button class="btn btn-success" id="viewInvoiceButton"><i class="fa fa-eye"></i>PO</button>
    <button class="btn btn-success" id="viewCheckButton"><i class="fa fa-eye"></i> Check List</button>
  </div>

  <div id="imageModal" onclick="closeImageModal()" style="margin-top:30px;">
    <div id="modalContent">
      <span id="closeBtn" onclick="closeImageModal()"><b>&times;</b></span>
      <div id="imageViewer"></div>
      <a id="downloadLink" href="#" download>Download Image</a>
    </div>
  </div>

  <div id="pdfModal" onclick="closePDFModal()">
    <div id="modalContent">
      <h2><b>Report</b></h2>
      <span id="closeBtn" onclick="closePDFModal()"><b>&times;</b></span>
      <div id="pdfViewer"></div>
    </div>
  </div>

  <script>
    function togglePopup(PO, Check) {
      //alert(SOW);
      var popup = document.getElementById("popup");
      if (popup.style.display === "none") {
        popup.style.display = "block";
        setupViewInvoiceButton(PO);
        setupViewCheckButton(Check); // Setup the button to view payment when the popup is shown
      } else {
        popup.style.display = "none";
      }
    }

    function setupViewInvoiceButton(PO) {
      var viewInvoiceButton = document.getElementById("viewInvoiceButton");
      // Ensure we don't attach multiple event listeners to the same button
      viewInvoiceButton.onclick = null; // Remove existing onclick to avoid multiple triggers
      viewInvoiceButton.onclick = function() {
        viewInvoice(PO);
      };
    }

    function setupViewCheckButton(Check) {
      var viewCheckButton = document.getElementById("viewCheckButton");
      // Ensure we don't attach multiple event listeners to the same button
      viewCheckButton.onclick = null; // Remove existing onclick to avoid multiple triggers
      viewCheckButton.onclick = function() {
        viewCheck(Check);
      };
    }

    function viewPayment(imagePath) {
      var imageElement = '<img src="' + imagePath + '" alt="Image">';
      var downloadLinkElement = document.getElementById('downloadLink');
      downloadLinkElement.setAttribute('href', imagePath);

      // Set the image and display the modal
      document.getElementById('imageViewer').innerHTML = imageElement;
      document.getElementById('imageModal').style.display = 'flex';
    }


    function viewDC(DC) {
      document.getElementById('pdfViewer').innerHTML = '<object data="' + DC + '" type="application/pdf" width="100%" height="100%"></object>';
      document.getElementById('pdfModal').style.display = 'flex';
    }

    function viewVD(VD) {
      document.getElementById('pdfViewer').innerHTML = '<object data="' + VD + '" type="application/pdf" width="100%" height="100%"></object>';
      document.getElementById('pdfModal').style.display = 'flex';
    }

    function viewInvoice(INV) {
      document.getElementById('pdfViewer').innerHTML = '<object data="' + INV + '" type="application/pdf" width="100%" height="100%"></object>';
      document.getElementById('pdfModal').style.display = 'flex';
    }
    
    function viewPO(PO) {
      document.getElementById('pdfViewer').innerHTML = '<object data="' + PO + '" type="application/pdf" width="100%" height="100%"></object>';
      document.getElementById('pdfModal').style.display = 'flex';
    }
    
    function viewVoucher(Vouch) {
      document.getElementById('pdfViewer').innerHTML = '<object data="' + Vouch + '" type="application/pdf" width="100%" height="100%"></object>';
      document.getElementById('pdfModal').style.display = 'flex';
    }
    
    function viewDetails(details) {
      document.getElementById('pdfViewer').innerHTML = '<object data="' + details + '" type="application/pdf" width="100%" height="100%"></object>';
      document.getElementById('pdfModal').style.display = 'flex';
    }
    
    function viewInternalInvoice(INV) {
      document.getElementById('pdfViewer').innerHTML = '<object data="' + INV + '" type="application/pdf" width="100%" height="100%"></object>';
      document.getElementById('pdfModal').style.display = 'flex';
    }
    
    function viewInternalVoucher(Vouch) {
      document.getElementById('pdfViewer').innerHTML = '<object data="' + Vouch + '" type="application/pdf" width="100%" height="100%"></object>';
      document.getElementById('pdfModal').style.display = 'flex';
    }

    function closeImageModal() {
      document.getElementById('imageModal').style.display = 'none';
      // Clear the content when closing the modal
      document.getElementById('imageViewer').innerHTML = '';
    }

    function closeModal() {
      document.getElementById('popup').style.display = 'none';
      // Clear the content when closing the modal
    }

    function closePDFModal() {
      document.getElementById('pdfModal').style.display = 'none';
      // Clear the content when closing the modal
      document.getElementById('pdfViewer').innerHTML = '';
    }
  </script>
  <script>
    // JavaScript to handle button click and toggle form visibility
    document.getElementById('openFormBtn').addEventListener('click', function() {
      document.getElementById('myForm').style.display = 'block';
    });
  </script>

  <script>
    $(document).ready(function() {
      // Initially hide the text input
      $('#otherInput').hide();

      // Listen for changes on the dropdown
      $('#prdct').change(function() {
        // Check if the 'Other' option is selected
        if ($(this).val() == "Other") {
          // Show the text input
          $('#otherInput').show();
        } else {
          // Hide the text input if 'Other' is not selected
          $('#otherInput').hide();
        }
      });
    });

    $(document).ready(function() {
      // Initially hide the text input
      $('#txt_otherInput').hide();

      // Listen for changes on the dropdown
      $('#txt_prdct').change(function() {
        // Check if the 'Other' option is selected
        if ($(this).val() == "Other") {
          // Show the text input
          $('#txt_otherInput').show();
        } else {
          // Hide the text input if 'Other' is not selected
          $('#txt_otherInput').hide();
        }
      });
    });

    $(document).ready(function() {
      // Listen for changes on the payment select dropdown
      $('#payment').change(function() {
        // Check if the selected value is "Yes"
        if ($(this).val() == "Yes") {
          // If yes, show the div for uploading payment screenshot
          $('div.form-group').has('#pay_attachment').show();
        } else {
          // If no or empty, hide the div
          $('div.form-group').has('#pay_attachment').hide();
        }
      });
    });
  </script>

  <script>
    // validation script edit form
    function validateForm() {
      var custName = document.getElementById('txt_cust_name').value;
      var location = document.getElementById('txt_loc').value;
      var product = document.getElementById('txt_prdct').value;
      var type = document.getElementById('txt_type').value;
      var pocDate = document.getElementById('txt_poc_date').value;
      var trunk = document.getElementById('txt_trunk').value;
      var customization = document.getElementById('txt_customization').value;
      var ifCustomization = document.getElementById('txt_if_customization').value;
      var customizationDays = document.getElementById('txt_customization_days').value;
      var contPerMobNo = document.getElementById('txt_cont_per_mobno').value;

      // Validate Customer Name (Alphabets only)
      if (!/^[a-zA-Z\s]+$/.test(custName)) {
        alert('Customer Name should contain only alphabets.');
        return false;
      }

      var mobNoPattern = /^\d{10}$/;
      if (!mobNoPattern.test(contPerMobNo)) {
        alert('Contact Person Mob no should be a 10-digit number.');
        return false;
      }


      // Add validations for other fields...

      return true; // If all validations pass, allow the form to be submitted
      // Attach the validateForm function to the form's onsubmit event
      document.getElementById('yourFormId').onsubmit = validateForm;

    }

    document.addEventListener("DOMContentLoaded", function() {
      flatpickr("#datepicker", {
        defaultDate: new Date()
      });
    });
    document.addEventListener("DOMContentLoaded", function() {
      flatpickr("#txt_datepicker", {
        defaultDate: new Date()
      });
    });
    document.addEventListener("DOMContentLoaded", function() {
      flatpickr("#sla_period", {
        defaultDate: new Date()
      });
    });
    document.addEventListener("DOMContentLoaded", function() {
      flatpickr("#txt_sla_period", {
        defaultDate: new Date()
      });
    });
    $(function() {

      $('#table_camp').dataTable();
      $('#table_internal').dataTable();
      //Initialize Select2 Elements
      $('.select2').select2()

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })

      //Datemask dd/mm/yyyy
      $('#datemask').inputmask('dd/mm/yyyy', {
        'placeholder': 'dd/mm/yyyy'
      })
      //Datemask2 mm/dd/yyyy
      $('#datemask2').inputmask('mm/dd/yyyy', {
        'placeholder': 'mm/dd/yyyy'
      })
      //Money Euro
      $('[data-mask]').inputmask()

      //Date picker
      $('#reservationdate').datetimepicker({
        format: 'L'
      });

      //Date and time picker
      $('#reservationdatetime').datetimepicker({
        icons: {
          time: 'far fa-clock'
        }
      });

      //Date range picker
      $('#reservation').daterangepicker()
      //Date range picker with time picker
      $('#reservationtime').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
          format: 'MM/DD/YYYY hh:mm A'
        }
      })
      //Date range as a button
      $('#daterange-btn').daterangepicker({
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function(start, end) {
          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        }
      )

      //Timepicker
      $('#timepicker').datetimepicker({
        format: 'LT'
      })

      //Bootstrap Duallistbox
      $('.duallistbox').bootstrapDualListbox()

      //Colorpicker
      $('.my-colorpicker1').colorpicker()
      //color picker with addon
      $('.my-colorpicker2').colorpicker()

      $('.my-colorpicker2').on('colorpickerChange', function(event) {
        $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
      })

      $("input[data-bootstrap-switch]").each(function() {
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
      })

    })
    // BS-Stepper Init
    document.addEventListener('DOMContentLoaded', function() {
      window.stepper = new Stepper(document.querySelector('.bs-stepper'))
    })

    // DropzoneJS Demo Code Start
    Dropzone.autoDiscover = false

    // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
    var previewNode = document.querySelector("#template")
    previewNode.id = ""
    var previewTemplate = previewNode.parentNode.innerHTML
    previewNode.parentNode.removeChild(previewNode)

    var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
      url: "/target-url", // Set the url
      thumbnailWidth: 80,
      thumbnailHeight: 80,
      parallelUploads: 20,
      previewTemplate: previewTemplate,
      autoQueue: false, // Make sure the files aren't queued until manually added
      previewsContainer: "#previews", // Define the container to display the previews
      clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
    })

    myDropzone.on("addedfile", function(file) {
      // Hookup the start button
      file.previewElement.querySelector(".start").onclick = function() {
        myDropzone.enqueueFile(file)
      }
    })

    // Update the total progress bar
    myDropzone.on("totaluploadprogress", function(progress) {
      document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
    })

    myDropzone.on("sending", function(file) {
      // Show the total progress bar when upload starts
      document.querySelector("#total-progress").style.opacity = "1"
      // And disable the start button
      file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
    })

    // Hide the total progress bar when nothing's uploading anymore
    myDropzone.on("queuecomplete", function(progress) {
      document.querySelector("#total-progress").style.opacity = "0"
    })

    // Setup the buttons for all transfers
    // The "add files" button doesn't need to be setup because the config
    // clickable has already been specified.
    document.querySelector("#actions .start").onclick = function() {
      myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
    }
    document.querySelector("#actions .cancel").onclick = function() {
      myDropzone.removeAllFiles(true)
    }
    // DropzoneJS Demo Code End


    function ingroupEditFun(val) {
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          //alert(this.responseText);
          var val = this.responseText;
          var res = val.split("*");

          //alert(res[17]);
          document.getElementById('txt_vendor_Name').value = res[0];
          document.getElementById('txt_vendor_type').value = res[1];
          document.getElementById('txt_Campaing_Code').value = res[2];
          document.getElementById('txt_total_prints').value = res[3];
          document.getElementById('txt_per_unit').value = res[4];
          document.getElementById('txt_id').value = res[5];

        }
      };
      xhttp.open("GET", "edit_po_request.php?id=" + val, true);
      xhttp.send();
      // Get the modal
      var modal1 = document.getElementById('myModal_edit');
      // Get the button that opens the modal
      var btn1 = document.getElementById("myBtngrpedit");
      // Get the <span> element that closes the modal
      var span1 = document.getElementsByClassName("close123")[0];
      // When the user clicks the edit button, open the modal 
      modal1.style.display = "block";
      // When the user clicks on <span> (x), close the modal
      span1.onclick = function() {

        modal1.style.display = "none";
      }
      // When the user clicks anywhere outside of the modal, close it
      window.onclick = function(event) {
        if (event.target == modal1) {
          modal1.style.display = "none";
        }
      }

    }

    function acceptInstallation(Aval_id, Acust_name, type, sales) {
      //alert(cust_name);
      document.getElementById('acceptID').value = Aval_id;
      document.getElementById('txt_vendorname').value = Acust_name;

      // Get the modal
      var modal3 = document.getElementById('myModal_accept');

      // Get the button that opens the modal
      var btn3 = document.getElementById("myBtn2");

      // Get the <span> element that closes the modal
      var span3 = document.getElementsByClassName("close24")[0];

      // When the user clicks the edit button, open the modal 
      modal3.style.display = "block";

      // When the user clicks on <span> (x), close the modal
      span3.onclick = function() {
        modal3.style.display = "none";
      }
      // When the user clicks anywhere outside of the modal, close it
      window.onclick = function(event) {
        if (event.target == modal3) {
          modal3.style.display = "none";
        }
      }
    }

    function upload_voucher(Aval_id, Acust_name, type, sales) {
      //alert(cust_name);
      document.getElementById('payment_id').value = Aval_id;
      document.getElementById('txt_vendor_name').value = Acust_name;

      // Get the modal
      var modal4 = document.getElementById('myModal_voucher');

      // Get the button that opens the modal
      var btn4 = document.getElementById("myBtn3");

      // Get the <span> element that closes the modal
      var span4 = document.getElementsByClassName("close25")[0];

      // When the user clicks the edit button, open the modal 
      modal4.style.display = "block";

      // When the user clicks on <span> (x), close the modal
      span4.onclick = function() {
        modal4.style.display = "none";
      }
      // When the user clicks anywhere outside of the modal, close it
      window.onclick = function(event) {
        if (event.target == modal4) {
          modal4.style.display = "none";
        }
      }
    }

    function rejectInstallation(val_id, cust_name) {
      //alert(cust_name);
      document.getElementById('deleteIngroupID').value = val_id;
      document.getElementById('txt_name').value = cust_name;
      // Get the modal
      var modal2 = document.getElementById('myModal_delete');

      // Get the button that opens the modal
      var btn2 = document.getElementById("myBtn2");

      // Get the <span> element that closes the modal
      var span2 = document.getElementsByClassName("close23")[0];

      // When the user clicks the edit button, open the modal 
      modal2.style.display = "block";

      // When the user clicks on <span> (x), close the modal
      span2.onclick = function() {
        modal2.style.display = "none";
      }
      // When the user clicks anywhere outside of the modal, close it
      window.onclick = function(event) {
        if (event.target == modal2) {
          modal2.style.display = "none";
        }
      }
    }
    
    // Internal Payment Functions
    function acceptInternalPayment(Aval_id, Aemp_name) {
      document.getElementById('acceptID_internal').value = Aval_id;
      document.getElementById('txt_employee_name_internal').value = Aemp_name;

      // Get the modal
      var modal5 = document.getElementById('myModal_accept_internal');

      // Get the <span> element that closes the modal
      var span5 = document.getElementsByClassName("close26")[0];

      // When the user clicks the edit button, open the modal 
      modal5.style.display = "block";

      // When the user clicks on <span> (x), close the modal
      span5.onclick = function() {
        modal5.style.display = "none";
      }
      // When the user clicks anywhere outside of the modal, close it
      window.onclick = function(event) {
        if (event.target == modal5) {
          modal5.style.display = "none";
        }
      }
    }

    function upload_internal_voucher(Aval_id, Aemp_name) {
      document.getElementById('payment_id_internal').value = Aval_id;
      document.getElementById('txt_employee_name_voucher').value = Aemp_name;

      // Get the modal
      var modal6 = document.getElementById('myModal_voucher_internal');

      // Get the <span> element that closes the modal
      var span6 = document.getElementsByClassName("close27")[0];

      // When the user clicks the edit button, open the modal 
      modal6.style.display = "block";

      // When the user clicks on <span> (x), close the modal
      span6.onclick = function() {
        modal6.style.display = "none";
      }
      // When the user clicks anywhere outside of the modal, close it
      window.onclick = function(event) {
        if (event.target == modal6) {
          modal6.style.display = "none";
        }
      }
    }

    function rejectInternalPayment(val_id, emp_name) {
      document.getElementById('deleteIngroupID_internal').value = val_id;
      document.getElementById('txt_name_internal').value = emp_name;
      
      // Get the modal
      var modal7 = document.getElementById('myModal_delete_internal');

      // Get the <span> element that closes the modal
      var span7 = document.getElementsByClassName("close28")[0];

      // When the user clicks the edit button, open the modal 
      modal7.style.display = "block";

      // When the user clicks on <span> (x), close the modal
      span7.onclick = function() {
        modal7.style.display = "none";
      }
      // When the user clicks anywhere outside of the modal, close it
      window.onclick = function(event) {
        if (event.target == modal7) {
          modal7.style.display = "none";
        }
      }
    }


    function showadd_user() {

      document.getElementById('myuser').style.display = 'block'
      // Get the modal
      var modal2 = document.getElementById('myuser');

      // Get the button that opens the modal
      var btn2 = document.getElementById("myBtn2");

      // Get the <span> element that closes the modal
      var span2 = document.getElementsByClassName("close")[0];

      // When the user clicks the edit button, open the modal 
      modal2.style.display = "block";

      // When the user clicks on <span> (x), close the modal
      span2.onclick = function() {
        modal2.style.display = "none";
      }
      // When the user clicks anywhere outside of the modal, close it
      window.onclick = function(event) {
        if (event.target == modal2) {
          modal2.style.display = "none";
        }
      }


    }


    function checkLevel() {
      var type = document.getElementById("type").value;

      if (type == "Poc") {
        document.getElementById("po_no").required = false;
        document.getElementById("amount").required = false;
        document.getElementById("payment").required = false;
        document.getElementById("SOW_attachment").required = false;
        document.getElementById("MSA_attachment").required = false;
      } else {
        document.getElementById("po_no").required = true;
        document.getElementById("amount").required = true;
        document.getElementById("payment").required = true;
        document.getElementById("SOW_attachment").required = true;
        document.getElementById("MSA_attachment").required = true;
      }
    }

    function txt_checkLevel() {
      var txt_type = document.getElementById("txt_type").value;

      if (txt_type == "Poc") {
        document.getElementById("txt_po_no").required = false;
        document.getElementById("txt_amount").required = false;
        document.getElementById("txt_payment").required = false;
        document.getElementById("txt_SOW_attachment").required = false;
        document.getElementById("txt_MSA_attachment").required = false;
      } else {
        document.getElementById("txt_po_no").required = true;
        document.getElementById("txt_amount").required = true;
        document.getElementById("txt_payment").required = true;
        document.getElementById("txt_SOW_attachment").required = true;
        document.getElementById("txt_MSA_attachment").required = true;
      }
    }
  </script>
</body>

</html>