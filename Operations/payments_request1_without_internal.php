<?php
include_once('../check_login.php');
// Check if the user is not logged in, redirect to index.php
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

require_once("db_connect.php");
session_start();

$loggedInuserName  = $_SESSION['username'];
$loggedInUserLevel = $_SESSION['user_level'];

$stmt_rs = "SELECT user_name,email from users where email='$loggedInuserName';";
$rslt_rs = mysqli_query($conn, $stmt_rs);
$row_rs = mysqli_fetch_row($rslt_rs);
$usename = $row_rs[0];
$sale_email = $row_rs[1];

if ($_POST['addInfo_list']) {

  $vendor_Name = $_POST['vendor_Name'];
  $vendor_type = $_POST['vendor_type'];
  $Campaing_Code = $_POST['Campaing_Code'];
  $invoice = $_POST['invoice_val'];
  $total_prints = $_POST['total_prints'];
  $per_unit = $_POST['per_unit'];
  $comments= $_POST['txt_comments'];
  $payment_type = $_POST['payment_type'];
  $employee_name = '';

  $targetFile_VD = basename($_FILES["vehicle_details"]["name"]);
  $allowedFileTypes_VD = array("pdf");
  $fileExtension_VD = strtolower(pathinfo($targetFile_VD, PATHINFO_EXTENSION));

  $targetFile_DC = basename($_FILES["dc_report"]["name"]);
  $allowedFileTypes_DC = array("pdf");
  $fileExtension_DC = strtolower(pathinfo($targetFile_DC, PATHINFO_EXTENSION));

  $targetFile_IN = basename($_FILES["invoice"]["name"]);
  $allowedFileTypes_IN = array("pdf");
  $fileExtension_IN = strtolower(pathinfo($targetFile_IN, PATHINFO_EXTENSION));

  $targetFile_PO = basename($_FILES["upload_po"]["name"]);
  $allowedFileTypes_PO = array("pdf");
  $fileExtension_PO = strtolower(pathinfo($targetFile_PO, PATHINFO_EXTENSION));

  $uploadDir = 'vehicle_images/';
  $zipFilePath = $uploadDir . basename($_FILES['vehicle_images']['name']);
  // Check if the file is a ZIP file
  if (isset($_FILES['vehicle_images']) && is_uploaded_file($_FILES['vehicle_images']['tmp_name'])) {
    $fileType = mime_content_type($_FILES['vehicle_images']['tmp_name']);
} else {
    $fileType = ''; // or set to null, or any default value
}

  if ((!in_array($fileExtension_VD, $allowedFileTypes_VD)) && (!in_array($fileExtension_DC, $allowedFileTypes_DC)) && (!in_array($fileExtension_IN, $allowedFileTypes_IN)) && (!in_array($fileExtension_PO, $allowedFileTypes_PO))) {

    echo '<script>alert("Sorry, Only PDF files are allowed.")</script>';
  } elseif ($fileType != 'application/zip' && $fileType != 'application/x-zip-compressed' && $fileType !='') {

    echo '<script>alert("Sorry, Invalid file type. Only ZIP files are allowed.")</script>';
  } else {

    $file_name_VD = $_FILES['vehicle_details']['name'];
    $upload_dir_VD = 'VD/'; // Directory to store uploaded files
    $file_path_VD = $upload_dir_VD . $file_name_VD;
    move_uploaded_file($_FILES['vehicle_details']['tmp_name'], $file_path_VD);

    $file_name_DC = $_FILES['dc_report']['name'];
    $upload_dir_DC = 'DC/'; // Directory to store uploaded files
    $file_path_DC = $upload_dir_DC . $file_name_DC;
    move_uploaded_file($_FILES['dc_report']['tmp_name'], $file_path_DC);

    $file_name_IN = $_FILES['invoice']['name'];
    $upload_dir_IN = '../Accounts/Invoice/'; // Directory to store uploaded files
    $file_path_IN = $upload_dir_IN . $file_name_IN;
    move_uploaded_file($_FILES['invoice']['tmp_name'], $file_path_IN);

    $file_name_PO = $_FILES['upload_po']['name'];
    $upload_dir_PO = 'PO/'; // Directory to store uploaded files
    $file_path_PO = $upload_dir_PO . $file_name_PO;
    move_uploaded_file($_FILES['upload_po']['tmp_name'], $file_path_PO);

    move_uploaded_file($_FILES['vehicle_images']['tmp_name'], $zipFilePath);

    $stmt_update = "INSERT INTO payment_request(vendor_name, type_of_vendor, campaign_code, invoice, dc_report, vehicle_details, vehicle_images, Status, PI_invoice, ceo_update, total_prints, per_unit, OP_comments, payment_type, upload_po, employee_name) VALUES ('$vendor_Name','$vendor_type','$Campaing_Code','$invoice','$file_name_DC','$file_name_VD','$zipFilePath','Requested','$file_name_IN','Please Upload The Voucher','$total_prints','$per_unit','$comments','$payment_type','$file_name_PO','$employee_name')";
//echo $stmt_update; exit;
    $rslt_update = mysqli_query($conn, $stmt_update);

    $stmt_ops = "SELECT email,user_name from users where user_level='Graphics';";
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
    $mail->Subject = "New Vendor Details";
    $mail->Body = "<p>Dear $ops_user, </p> <h3>New Vendor Request has been added. Kindly check the dash board for more details.<br></h3>
                     Click the below link for login<br>
					 http://brandonwheelz.in/index.php<br>
                     <p></p>
                     <b></b>";

    $mail->send();
  }
}

if ($_POST['updateInfo_list']) {

  $txt_id = $_POST["txt_id"];
  $txt_vendor_Name = $_POST['txt_vendor_Name'];
  $txt_vendor_type = $_POST['txt_vendor_type'];
  $txt_Campaing_Code = $_POST['txt_Campaing_Code'];
  $txt_total_prints = $_POST['txt_total_prints'];
  $txt_per_unit = $_POST['txt_per_unit'];
  $txt_payment_type = $_POST['txt_payment_type'];
  $txt_employee_name = $_POST['txt_employee_name'];

  // Initialize file name variables
  $file_name_PO = '';
  $file_name_VD = '';
  $file_name_DC = '';
  $file_name_IN = '';
  $zipFilePath = '';
  
  $updateFields = array();
  
  // Handle PO file upload if a new file is uploaded
  if(isset($_FILES['txt_upload_po']) && $_FILES['txt_upload_po']['error'] == 0) {
    $file_name_PO = $_FILES['txt_upload_po']['name'];
    $upload_dir_PO = 'PO/';
    $file_path_PO = $upload_dir_PO . $file_name_PO;
    move_uploaded_file($_FILES['txt_upload_po']['tmp_name'], $file_path_PO);
    $updateFields[] = "upload_po='$file_name_PO'";
  }
  
  // Handle Vehicle Details file upload
  if(isset($_FILES['txt_vehicle_details']) && $_FILES['txt_vehicle_details']['error'] == 0) {
    $file_name_VD = $_FILES['txt_vehicle_details']['name'];
    $upload_dir_VD = 'VD/';
    $file_path_VD = $upload_dir_VD . $file_name_VD;
    move_uploaded_file($_FILES['txt_vehicle_details']['tmp_name'], $file_path_VD);
    $updateFields[] = "vehicle_details='$file_name_VD'";
  }
  
  // Handle Vehicle Images ZIP upload
  if(isset($_FILES['txt_vehicle_images']) && $_FILES['txt_vehicle_images']['error'] == 0) {
    $uploadDir = 'vehicle_images/';
    $zipFilePath = $uploadDir . basename($_FILES['txt_vehicle_images']['name']);
    move_uploaded_file($_FILES['txt_vehicle_images']['tmp_name'], $zipFilePath);
    $updateFields[] = "vehicle_images='$zipFilePath'";
  }
  
  // Handle DC Report file upload
  if(isset($_FILES['txt_dc_report']) && $_FILES['txt_dc_report']['error'] == 0) {
    $file_name_DC = $_FILES['txt_dc_report']['name'];
    $upload_dir_DC = 'DC/';
    $file_path_DC = $upload_dir_DC . $file_name_DC;
    move_uploaded_file($_FILES['txt_dc_report']['tmp_name'], $file_path_DC);
    $updateFields[] = "dc_report='$file_name_DC'";
  }
  
  // Handle Invoice file upload
  if(isset($_FILES['txt_invoice']) && $_FILES['txt_invoice']['error'] == 0) {
    $file_name_IN = $_FILES['txt_invoice']['name'];
    $upload_dir_IN = '../Accounts/Invoice/';
    $file_path_IN = $upload_dir_IN . $file_name_IN;
    move_uploaded_file($_FILES['txt_invoice']['tmp_name'], $file_path_IN);
    $updateFields[] = "PI_invoice='$file_name_IN'";
  }

  // Base update query
  $stmt_update = "UPDATE payment_request SET 
                  vendor_name='$txt_vendor_Name', 
                  type_of_vendor='$txt_vendor_type', 
                  campaign_code='$txt_Campaing_Code', 
                  total_prints='$txt_total_prints', 
                  per_unit='$txt_per_unit', 
                  payment_type='$txt_payment_type', 
                  employee_name='$txt_employee_name'";
  
  // Add file update fields if any files were uploaded
  if(!empty($updateFields)) {
    $stmt_update .= ", " . implode(", ", $updateFields);
  }
  
  $stmt_update .= " WHERE id='$txt_id'";

  $rslt_update = mysqli_query($conn, $stmt_update);
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
          <h2 style="margin-left:50px;color: #b52f34;"><b><i class="fa fa-check-square" aria-hidden="true"></i> Vendor Payment Request</b></h2>
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
          <li><a href="ops_dashboard.php" class="menu-link"><i class="fa fa-th-large " aria-hidden="true"></i> Dashboard</a></li><br><br>
          <li><a href="ops_details.php" class="menu-link"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Sales Details</a></li><br><br>
          <li><a href="ops_vendor_details.php" class="menu-link"><i class="fa fa-check-circle" aria-hidden="true"></i> New Vendor Info</a></li><br><br>
          <li><a href="po_request.php" class="menu-link"><i class="fa fa-check-circle" aria-hidden="true"></i> Vendor PO Request</a></li><br><br>
          <li><a href="payments_request.php" class="menu-link"><i class="fa fa-check-circle" aria-hidden="true"></i> Payments Request</a></li><br><br>
          <li style="font-weight:bold;color: #ffff;">Reports</li><br><br>
          <li><a href="po_report.php" class="menu-link"><i class="fa fa-align-center" aria-hidden="true"></i> PO Report</a></li><br><br>
          <li><a href="payrequest_report.php" class="menu-link"><i class="fa fa-align-center" aria-hidden="true"></i> Payement Request Report</a></li><br><br>        </nav>
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
          <div class="card-footer">

            <button type="submit" id="adduserButton" onclick="showadd_user()" ;><i class="fa fa-user-plus" aria-hidden="true"></i> Add Payment Request</button>

          </div>

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

              <div class="row" style="overflow-x: auto;">
                <div class="col-md-12">
                  <div class="card-body p-0">
                    <table class="table table-striped projects table-bordered" id="table_camp">
                      <thead>
                        <tr>
                          <th style="width: 2%">
                            Sr.No.
                          </th>
                          <th style="width: 5%">
                            Date & Time
                          </th>
                          <th style="width:20%">
                            Campaign Code
                          </th>
                          <th style="width:20%">
                            Vendor Name
                          </th>
                          <th style="width:20%">
                            Type Of Vendor
                          </th>
                          <th style="width:20%">
                            Invoice Value
                          </th>
                          <th style="width:20%">
                            Payment Type
                          </th>
                          
                          <th style="width:20%">
                            Status
                          </th>
                          <th style="width:20%">
                            Accounts Status
                          </th>
                          <th style="width:20%">
                            CEO Status
                          </th>
                          <th style="width:20%">
                            Director Status
                          </th>
                          <th style="width:70%">
                            Op's Comments
                          </th>
                          <th style="width:20%">
                            Comments
                          </th>
                          <th style="width:20%">
                            View DC
                          </th>
                          <th style="width:20%">
                            View Vehicle Details
                          </th>
                          <th style="width:20%">
                            View Vehicle Images
                          </th>
                          <th style="width:20%">
                            View Invoice
                          </th>
                          <th style="width:20%">
                            View PO
                          </th>
                          <th style="width:20%">
                            Action
                          </th>

                        </tr>
                      </thead>
                      <tbody>

                        <?php
                        $stmt_select = "SELECT * from payment_request order by id desc";
                        $rslt_rs = mysqli_query($conn, $stmt_select);

                        $x = 1;
                        while ($row = mysqli_fetch_assoc($rslt_rs)) {

                          $DC_file = "DC/" . $row["dc_report"];
                          $VD_file = "VD/" . $row["vehicle_details"];
                          $Invoice_file = "../Accounts/Invoice/" . $row["PI_invoice"];
                          $PO_file = "PO/" . $row["upload_po"];

                        ?>
                          <tr>
                            <td>
                              <?php echo $x; ?>
                            </td>
                            <td>
                              <?php echo $row["date_time"]; ?>
                            </td>
                            <td>
                              <?php echo $row["campaign_code"]; ?>
                            </td>
                            <td>
                              <?php echo $row["vendor_name"]; ?>
                            </td>
                            <td>
                              <?php echo $row["type_of_vendor"]; ?>
                            </td>
                            <td>
                              <?php echo $row["invoice"]; ?>
                            </td>
                            <td>
                              <?php echo $row["payment_type"]; ?>
                            </td>
                            
                            <td>
                              <?php echo $row["Status"]; ?>
                            </td>
                            
                            <td>
                              <?php echo $row["acc_status"]; ?>
                            </td>
                            
                            <td>
                              <?php echo $row["ceo_status"]; ?>
                            </td>
                            
                            <td>
                              <?php echo $row["Director_status"]; ?>
                            </td>
                            <td>
                              <?php echo $row["OP_comments"]; ?>
                            </td>
                            <td>
                              <?php echo $row["comments"]; ?>
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
                              
                                <button class="btn btn-primary" onclick="viewInvoice('<?php echo $Invoice_file; ?>')"><i class="fa fa-eye"></i> View</button>
                             
                            </td>
                            <td>
                              <?php if ($row["upload_po"] != "") { ?>
                                <button class="btn btn-primary" onclick="viewPO('<?php echo $PO_file; ?>')"><i class="fa fa-eye"></i> View</button>
                              <?php } ?>
                            </td>
                            <td>
                              <button class="btn btn-info btn-sm" onclick="ingroupEditFun('<?php echo $row["id"]; ?>')">
                                <i class="fas fa-pencil-alt"></i> Edit
                              </button>
                            </td>

                          </tr>

                        <?php $x++;
                        } ?>
                      </tbody>
                    </table>
                  </div>
                  <!-- /.form-group -->
                </div>
                <!-- /.col -->

                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- /.row -->
            </div>
            <!-- /.card-body -->
            <!-- btn -->
            <!-- Modal -->

          </div>
          <!-- btn end -->
        </div>
        <!-- /.card -->

        <!-- The Modal Edit -->
        <div class="modal fade" id="myModal_edit" style="opacity: 3;top : 104px !important; overflow-y:scroll;">
          <div class="modal-dialog">
            <div class="modal-content" style="top:40px;width: 800px; margin-left: -100px;">


              <!-- Modal Header -->
              <div class="modal-header">
                <h4 class="modal-title">Update Vendor Payment Request</h4>
                <button type="button" class="close123" data-dismiss="modal">&times;</button>
              </div>

              <!--Main Form Modal body -->
              <div class="modal-body">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                  <table>
                    <tr>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Vendor Name<span style="color:red;">*</span></label>
                          <select class="form-control select2" style="width: 100%;" name="txt_vendor_Name" id="txt_vendor_Name" required>
                            <option value="">Select Vendor Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>

                            <?php
                            $stmt_select = "SELECT * from vendor_reg where ceo_status='Accepted'";
                            $rslt_rs = mysqli_query($conn, $stmt_select);

                            $x = 1;
                            while ($row = mysqli_fetch_assoc($rslt_rs)) {
                            ?>

                              <option value="<?php echo $row["vendor_name"]; ?>"><?php echo $row["vendor_name"]; ?></option>
                            <?php } ?>
                          </select>

                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Type of vendor<span style="color:red;">*</span></label>
                          <select class="form-control" id="txt_vendor_type" name="txt_vendor_type">
                            <option value="">Select Vendor Type</option>
                            <option value="Printing">Printing</option>
                            <option value="Fleet">Fleet</option>
                            <option value="Mounting">Mounting</option>
                            <option value="Auto Hood Manufaturer">Auto Hood Manufaturer</option>
                            <option value="Auto Hood Installer">Auto Hood Installer</option>
                            <!-- Add more options as needed -->
                          </select>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Campaign Code<span style="color:red;">*</span></label>
                          <select class="form-control select2" style="width: 100%;" name="txt_Campaing_Code" id="txt_Campaing_Code" required>
                            <option value="">Select Campaign Code &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>

                            <?php
                            $stmt_select = "SELECT * from order_details where ceo_status='Accepted'";
                            $rslt_rs = mysqli_query($conn, $stmt_select);

                            $x = 1;
                            while ($row = mysqli_fetch_assoc($rslt_rs)) {
                            ?>

                              <option value="<?php echo $row["code"]; ?>"><?php echo $row["code"]; ?> - <?php echo $row["company_name"]; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </th>
                    </tr>
                    <tr>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Total No of Prints/ Fleet/ Autos<span style="color:red;">*</span></label>
                          <input type="text" class="form-control" pattern="^[0-9\s]*$" title="Please enter only numbers" id="txt_total_prints" name="txt_total_prints" placeholder="Enter Total No of Prints/ Fleet/ Autos" required>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Per Unit Cost<span style="color:red;">*</span></label>
                          <input type="text" class="form-control" pattern="^[0-9\s]*$" title="Please enter Per unit" id="txt_per_unit" name="txt_per_unit" placeholder="Enter Per unit cost" required>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Payment Type<span style="color:red;">*</span></label>
                          <select class="form-control" id="txt_payment_type" name="txt_payment_type" required>
                            <option value="">Select Payment Type</option>
                            <option value="Priority Payment">Priority Payment</option>
                            
                            <option value="Balance Payment">Balance Payment</option>
                            <option value="AD Hoc">AD Hoc</option>
                            <option value="Weekly Payment">Weekly Payment</option>
                          </select>
                        </div>
                      </th>
                    </tr>
                    <tr>
                      
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Upload PO</label>
                          <input type="file" class="form-control" accept=".pdf" id="txt_upload_po" name="txt_upload_po">
                          <span style="font-size: 10px;font-family: Open Sans;"><b>Only PDF (Leave empty if no change)</b></span>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Upload Vehicle Details</label>
                          <input type="file" class="form-control" accept=".pdf" id="txt_vehicle_details" name="txt_vehicle_details">
                          <span style="font-size: 10px;font-family: Open Sans;"><b>Only PDF (Leave empty if no change)</b></span>
                        </div>
                      </th>
                    
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Upload Vehicle Images</label>
                          <input type="file" class="form-control" accept=".zip" id="txt_vehicle_images" name="txt_vehicle_images">
                          <span style="font-size: 10px;font-family: Open Sans;"><b>Only ZIP (Leave empty if no change)</b></span>
                        </div>
                      </th>
                      </tr>
                    <tr>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Upload DC Report</label>
                          <input type="file" class="form-control" accept=".pdf" id="txt_dc_report" name="txt_dc_report">
                          <span style="font-size: 10px;font-family: Open Sans;"><b>Only PDF (Leave empty if no change)</b></span>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Upload Invoice</label>
                          <input type="file" class="form-control" accept=".pdf" id="txt_invoice" name="txt_invoice">
                          <span style="font-size: 10px;font-family: Open Sans;"><b>Only PDF (Leave empty if no change)</b></span>
                        </div>
                      </th>
                    </tr>
                  </table>
                  <div class="card-footer">
                    <input type="hidden" value="" id="txt_id" name="txt_id">
                    <input type="submit" class="btn btn-success" name="updateInfo_list" value="Update">
                  </div>
                </form>
              </div>

              <!-- Modal footer -->


            </div>
          </div>
        </div>

        <!-- /.row -->

        <!-- The Modal Add -->
        <div class="modal fade" id="myuser" style="opacity: 3;top : 104px !important; overflow-y:scroll;">
          <div class="modal-dialog">
            <div class="modal-content" style="top:40px;width: 800px; margin-left: -100px;">
              <!-- Modal Header -->
              <button type="button" class="close" data-dismiss="modal"style="margin-left: 96%;">&times;</button>
              <div class="modal-body">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                  <table>
                    <tr>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="vendor_Name">Vendor Name<span style="color:red;">*</span></label>
                          <select class="form-control select2" style="width: 100%;" name="vendor_Name" id="vendor_Name" required>
                            <option value="">Select Vendor Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>

                            <?php
                            $stmt_select = "SELECT * from vendor_reg where ceo_status='Accepted'";
                            $rslt_rs = mysqli_query($conn, $stmt_select);

                            $x = 1;
                            while ($row = mysqli_fetch_assoc($rslt_rs)) {
                            ?>

                              <option value="<?php echo $row["vendor_name"]; ?>"><?php echo $row["vendor_name"]; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="txt_vendor_type">Type of Vendor<span style="color:red;">*</span></label>
                          <select class="form-control" id="vendor_type" name="vendor_type" onchange="checkLevel()" required>
                            <option value="">Select Vendor Type</option>
                            <option value="Printing">Printing</option>
                            <option value="Fleet">Fleet</option>
                            <option value="Mounting">Mounting</option>
                            <option value="Auto Hood Manufaturer">Auto Hood Manufaturer</option>
                            <option value="Auto Hood Installer">Auto Hood Installer</option>
                          </select>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="Campaing_Code">Campaign Code<span style="color:red;">*</span></label>
                          <select class="form-control select2" style="width: 100%;" name="Campaing_Code" id="Campaing_Code" required>
                            <option value="">Select Campaign Code &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>

                            <?php
                            $stmt_select = "SELECT * from order_details where ceo_status='Accepted'";
                            $rslt_rs = mysqli_query($conn, $stmt_select);

                            $x = 1;
                            while ($row = mysqli_fetch_assoc($rslt_rs)) {
                            ?>

                              <option value="<?php echo $row["code"]; ?>"><?php echo $row["code"]; ?> - <?php echo $row["company_name"]; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </th>
                    </tr>
                    <tr>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Invoice Value<span style="color:red;">*</span></label>
                          <input type="text" class="form-control" pattern="^[0-9\s]*$" title="Please enter only numbers" id="invoice_val" name="invoice_val" placeholder="Enter Invoice Value" required>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Total No of Prints/ Fleet/ Autos<span style="color:red;">*</span></label>
                          <input type="text" class="form-control" pattern="^[0-9\s]*$" title="Please enter only numbers" id="txt_total_prints" name="total_prints" placeholder="Enter Total No of Prints/ Fleet/ Autos" required>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Per Unit Cost<span style="color:red;">*</span></label>
                          <input type="text" class="form-control" pattern="^[0-9\s]*$" title="Please enter Per unit" id="txt_per_unit" name="per_unit" placeholder="Enter Per unit cost" required>
                        </div>
                      </th>
                    </tr>
                    <tr>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Payment Type<span style="color:red;">*</span></label>
                          <select class="form-control" id="payment_type" name="payment_type" required>
                            <option value="">Select Payment Type</option>
                            <option value="Priority Payment">Priority Payment</option>
                            
                            <option value="Balance Payment">Balance Payment</option>
                            <option value="AD Hoc">AD Hoc</option>
                            <option value="Weekly Payment">Weekly Payment</option>
                          </select>
                        </div>
                      </th>
                      
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Upload PO</label>
                          <input type="file" class="form-control" accept=".pdf" id="upload_po" name="upload_po">
                          <span style="font-size: 10px;font-family: Open Sans;"><b>Only PDF</b></span>
                        </div>
                      </th>
                    
                      <th>
                        <div class="form-group">
                          <label for="IngroupName">Upload Vehicle Details(Only PDF)</label>
                          <input type="file" class="form-control" accept=".pdf" id="vehicle_details" name="vehicle_details" required>
                        </div>
                      </th>
                      </tr>
                    <tr>
                      <th>
                        <div class="form-group">
                          <label for="IngroupName">Upload Vehicle Images(Only ZIP)</label>
                          <input type="file" class="form-control" id="vehicle_images" name="vehicle_images" accept=".zip" required>
                        </div>
                      </th>
                      <th>
                        <div class="form-group">
                          <label for="IngroupName">Upload DC Report(Only PDF)</label>
                          <input type="file" class="form-control" accept=".pdf" id="dc_report" name="dc_report">
                        </div>
                      </th>
                   
                      <th>
                        <div class="form-group">
                          <label for="IngroupName">Upload Invoice<span style="color:red;">*</span></label>
                          <input type="file" class="form-control" id="invoice" accept=".pdf" name="invoice" required><span style="font-size: 10px;font-family: Open Sans;"><b>Only PDF</b></span>
                        </div>
                      </th>
                       </tr>
                    <tr>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Comments</label>
                          <textarea class="form-control" title="Please enter Comments" id="txt_comments" name="txt_comments" placeholder="Enter Comments"></textarea>
                        </div>
                      </th>
                    </tr>
                  </table>
                  <div class="card-footer">
                    <input type="submit" class="btn btn-success" name="addInfo_list" value="Submit">
                  </div>
                </form>
              </div>

              <!-- Modal footer -->
              <div class="modal-footer">
              </div>

            </div>
          </div>
        </div> <!-- /.row -->


        <!-- The Modal Delete-->
        <div class="modal fade" id="myModal_delete" style="opacity: 3;top : 104px !important">
          <div class="modal-dialog">
            <div class="modal-content" style="top:40px;">

              <!-- Modal Header -->
              <div class="modal-header">
                <h4 class="modal-title">Reject New Order</h4>
                <button type="button" class="close23" data-dismiss="modal">&times;</button>
              </div>

              <!-- Modal body -->
              <div class="modal-body">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                  <div class="card-body">
                    <div class="form-group">
                      <label for="UserID">Customer Name</label>
                      <input type="text" class="form-control" title="Please enter only numbers and characters" id="txt_name" name="txt_name" readonly>
                    </div>
                    <div class="form-group">
                      <label for="IngroupName">Reason For Reject<span style="color:red;">*</span></label>
                      <textarea class="form-control" id="txt_reason" name="txt_reason" placeholder="Enter reason for reject the new installation" required></textarea>
                    </div>
                  </div>
                  <input type="hidden" value="" id="deleteIngroupID" name="deleteIngroupID">
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


    function viewDC(D1C) {
      document.getElementById('pdfViewer').innerHTML = '<object data="' + D1C + '" type="application/pdf" width="100%" height="100%"></object>';
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
          document.getElementById('txt_payment_type').value = res[6];
          document.getElementById('txt_employee_name').value = res[7];

        }
      };
      xhttp.open("GET", "edit_payment_request.php?id=" + val, true);
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
      var type = document.getElementById("vendor_type").value;
    // alert(type);
      if (type == "Printing") {
        document.getElementById("dc_report").required = true;
        document.getElementById("vehicle_details").required = false;
        document.getElementById("vehicle_images").required = false;
      } else {
        document.getElementById("dc_report").required = false;
        document.getElementById("vehicle_details").required = true;
        document.getElementById("vehicle_images").required = true;
       
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
  <script>
    function fetchVendorDetails() {

      var selectedOption = $('#vendor_Name option:selected');
      var vendorName = selectedOption.val();
      var id = selectedOption.data('id');
      //alert(id);
      //alert(vendorName);
      if (vendorName !== '') {

        $.ajax({
          url: 'fetch_vendor_details.php', // Ensure this path is correct
          method: 'POST',
          data: {
            vendor_name: vendorName,
            id: id
          },
          dataType: 'json',
          success: function(data) {
            console.log(data); // Log the response for debugging
            if (data.error) {
              console.error("Server Error: " + data.error);
            } else {
              $('#vendor_type').val(data.type_of_vendor);
              $('#Campaing_Code').val(data.campaign_code);
            }
          },
          error: function(xhr, status, error) {
            console.error("AJAX Error: " + status + " " + error);
            console.log("Response Text: " + xhr.responseText); // Log the response text for debugging
          }
        });
        document.getElementById("van_id").value = "";
      } else {
        $('#txt_vendor_type').val('');
        $('#Campaing_Code').val('');
        document.getElementById("van_id").value = "";
      }
    }

    $(document).ready(function() {
      // Initialize Select2
      $('#vendor_Name').select2();
      $('#employee_name').select2();
      $('#txt_employee_name').select2();
    });
  </script>
</body>

</html>