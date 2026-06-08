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

// CSV Export Handler
if (isset($_GET['export_csv'])) {
  $start_date = $_GET['csv_start_date'] ?? '';
  $end_date = $_GET['csv_end_date'] ?? '';

  $csv_query = "SELECT p.*, v.vendor_id FROM purchase p
                LEFT JOIN vendor_reg v ON v.vendor_name = p.vendor_name AND v.id = (
                  SELECT MAX(id) FROM vendor_reg WHERE vendor_name = p.vendor_name
                )";

  if (!empty($start_date) && !empty($end_date)) {
    $start_date_safe = mysqli_real_escape_string($conn, $start_date);
    $end_date_safe   = mysqli_real_escape_string($conn, $end_date);
    $csv_query .= " WHERE DATE(p.created_date) BETWEEN '$start_date_safe' AND '$end_date_safe'";
  }

  $csv_query .= " ORDER BY p.id DESC";
  $csv_result = mysqli_query($conn, $csv_query);

  header('Content-Type: text/csv');
  header('Content-Disposition: attachment; filename="po_request_' . date('Y-m-d') . '.csv"');
  $output = fopen('php://output', 'w');

  fputcsv($output, ['Sr.No.', 'Date/Time', 'Campaign Code', 'Vendor Id', 'Vendor Name', 'Type Of Vendor', 'Total No.Of Prints', 'Per Unit Cost', 'Status', "OP's Comments", 'ACC Status', 'CEO Status', 'Director Status', 'Comments']);

  $sr = 1;
  while ($row = mysqli_fetch_assoc($csv_result)) {
    $date_val = ($row['created_date'] !== '0000-00-00 00:00:00') ? $row['created_date'] : '';
    $comments = trim($row['acc_comments'] . ' ' . $row['ceo_comments'] . ' ' . $row['director_comments']);
    fputcsv($output, [
      $sr,
      $date_val,
      $row['campaign_code'],
      $row['vendor_id'] ?? '',
      $row['vendor_name'],
      $row['Type_of_Vendor'],
      $row['total_prints'],
      $row['per_unitcost'],
      $row['status'],
      $row['comments'],
      $row['acc_status'],
      $row['ceo_status'],
      $row['director_status'],
      $comments
    ]);
    $sr++;
  }

  fclose($output);
  exit();
}

if ($_POST['accept_neworder']) {

  $id = $_REQUEST["acceptID"];
  $company_name = $_REQUEST["txt_vendorname"];
  
$targetFile_VD = basename($_FILES["invoice"]["name"]);
  $allowedFileTypes_VD = array("pdf");
  $fileExtension_VD = strtolower(pathinfo($targetFile_VD, PATHINFO_EXTENSION));
  
   if (!in_array($fileExtension_VD, $allowedFileTypes_VD)){

      echo '<script>alert("Sorry, Only PDF files are allowed.")</script>';
	  
    }else{
		
		$file_name_VD = $_FILES['invoice']['name'];
      $upload_dir_VD = 'PO/'; // Directory to store uploaded files
      $file_path_VD = $upload_dir_VD . $file_name_VD;
      move_uploaded_file($_FILES['invoice']['tmp_name'], $file_path_VD);
	  
  //$stmt_update = "UPDATE purchase SET PO_invoice='$file_name_VD',status='Received',acc_status='Accepted' WHERE id='$id'";
  $stmt_update = "UPDATE purchase SET PO_invoice='$file_name_VD',status='Received',acc_status='Accepted',ceo_status='Accepted' WHERE id='$id'";
  
  $rslt_update = mysqli_query($conn, $stmt_update);

	  $stmt_sale = "SELECT email,user_name from users where user_level='Operations';";
  $rslt_sale = mysqli_query($conn, $stmt_sale);
  $row_sale = mysqli_fetch_row($rslt_sale);
  $sale_email = $row_sale[0];
  $op_name = $row_sale[1];
                        
  // After submit the form Send mail to RM
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
      $mail->Subject = "$company_name - Vedor PO";
      $mail->Body = "<p>Dear $op_name, </p> <h3>Kindly check the attached vendor PO doc for the $company_name.<br></h3>
                     Click the below link for login to see more details<br>
					 http://brandonwheelz.in/index.php<br>
                     <p></p>
                     <b></b>";
	  $filePath = "PO/" .$file_name_VD;
      $mail->addAttachment($filePath);

      $mail->send(); 
	  
	}
}

if ($_POST['reject_neworder']) {

  $txt_id = $_POST["deleteIngroupID"];
  $txt_reason = $_POST["txt_reason"];
  $txt_name = $_POST["txt_name"];
  $van_email = $_POST["ven_email"];

$stmt_update = "UPDATE purchase SET acc_status='Rejected',acc_comments='$txt_reason' WHERE id='$txt_id'";
$rslt_update = mysqli_query($conn, $stmt_update); 
  
   $stmt_sale = "SELECT email,user_name from users where user_level='Operations';";
  $rslt_sale = mysqli_query($conn, $stmt_sale);
  $row_sale = mysqli_fetch_row($rslt_sale);
  $sale_email = $row_sale[0];
  $op_name = $row_sale[1];
  
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
  $mail->Subject = "PO Request Rejected";
  $mail->Body = "<p>Dear $op_name, </p> <h3>Your PO Request has been rejected by Accounts team, because of $txt_reason.<br></h3>
                     <p></p>
                     <b></b>";

  $mail->send();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=1500">
  <title>PO Request</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
  <link rel="stylesheet" href="plugins/bs-stepper/css/bs-stepper.min.css">
  <link rel="stylesheet" href="plugins/dropzone/min/dropzone.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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

    ul, li, a {
      color: #fff;
      text-decoration: none;
      list-style-type: none;
      margin-left: 5px;
      font-size: 20px;
    }

    a:hover { color: #fff; }

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

    label { font-weight: normal; }

    .form-group-a label { font-weight: normal !important; }

    .form-group-a input[type="checkbox"]+label { font-weight: normal !important; }

    #imageViewer { max-width: 100%; max-height: 500px; }

    #imageModal {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(255, 255, 255, 0.9);
      justify-content: center;
      align-items: center;
    }

    #modalContent {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.7);
      max-width: 90%;
      max-height: 90%;
      overflow: auto;
      position: relative;
      width: 700px;
    }

    #imageViewer img { max-width: 100%; max-height: 500px; }

    #closeBtn {
      position: absolute;
      top: -4px; right: 3px;
      cursor: pointer;
      font-size: 30px;
      color: #f00;
    }

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

    #downloadLink:hover { background-color: #45a049; }

    #popup {
      display: none;
      position: absolute;
      background: white;
      border-radius: 10px;
      box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.7);
      padding: 20px;
    }

    #pdfViewer { width: 100%; height: 700px; }

    #pdfModal {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      justify-content: center;
      align-items: center;
      margin-top: 60px;
    }

    #modalContent {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.7);
      max-width: 100%;
      max-height: 100%;
      overflow: auto;
      position: relative;
      width: 1000px;
    }

    #closeBtn {
      position: absolute;
      top: -4px; right: 3px;
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
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-color: rgba(255, 255, 255, 0.5);
      z-index: -1;
    }

    .purchase-ops { background-color: #b52f34; border-color: #b52f34; }
    .purchase-ops:hover { background-color: darkred; border-color: darkred; }

    /* Date filter bar */
    .date-filter-bar {
      background: #fff;
      border: 1px solid #dee2e6;
      border-radius: 6px;
      padding: 12px 18px;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 12px;
    }

    .date-filter-bar label {
      font-weight: 600 !important;
      color: #333;
      margin-bottom: 0;
      white-space: nowrap;
    }

    .date-filter-bar input[type="date"] {
      border: 1px solid #ced4da;
      border-radius: 4px;
      padding: 5px 10px;
      font-size: 14px;
      color: #333;
      height: 34px;
    }

    .btn-csv-export {
      background-color: #28a745;
      color: #fff;
      border: none;
      border-radius: 4px;
      padding: 6px 18px;
      font-size: 14px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      text-decoration: none;
    }

    .btn-csv-export:hover {
      background-color: #218838;
      color: #fff;
      text-decoration: none;
    }

    .btn-filter-reset {
      background-color: #6c757d;
      color: #fff;
      border: none;
      border-radius: 4px;
      padding: 6px 14px;
      font-size: 14px;
      cursor: pointer;
    }

    .btn-filter-reset:hover { background-color: #5a6268; color: #fff; }

    .filter-error {
      color: #dc3545;
      font-size: 13px;
      display: none;
    }
  </style>

  <script>
    $(document).ready(function() {
      sampleDiv_zoom.style.zoom = '80%';
      var scale = 'scale(1)';
      document.body.style.webkitTransform = scale;
      document.body.style.msTransform = scale;
      document.body.style.transform = scale;
    });
  </script>
</head>

<body class="hold-transition sidebar-mini" id="sampleDiv_zoom">
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="height:90px;">
      <ul class="navbar-nav">
        <li class="nav-item d-none d-sm-inline-block">
          <h2 style="margin-left:50px;color: #b52f34;"><b><i class="fa fa-check-square" aria-hidden="true"></i> Vendor PO Request</b></h2>
        </li>
      </ul>

      <ul class="navbar-nav ml-auto">
        <li class="nav-item" style="display:none;">
          <a class="nav-link" data-widget="navbar-search" href="#" role="button">
            <i class="fas fa-search"></i>
          </a>
          <div class="navbar-search-block">
            <form class="form-inline">
              <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                  <button class="btn btn-navbar" type="submit"><i class="fas fa-search"></i></button>
                  <button class="btn btn-navbar" type="button" data-widget="navbar-search"><i class="fas fa-times"></i></button>
                </div>
              </div>
            </form>
          </div>
        </li>

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

    <!-- Main Sidebar -->
    <aside class="main-sidebar" style="background: linear-gradient(327deg, #ff0000a3, #9cb3d7); color: #FFFFFF;">
      <img src="../images/bwz1.png" alt="Admin Logo" class="logo">
      <div class="sidebar">
        <div class="form-inline" style="display:none;">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-sidebar"><i class="fas fa-search fa-fw"></i></button>
            </div>
          </div>
        </div>

        <nav class="mt-2">
          <hr style="border: 0.5px solid #b52f34;border-radius: 2px;"><br>
          <li><a href="accounts_dashboard.php" class="menu-link"><i class="fa fa-th-large" aria-hidden="true"></i> Dashboard</a></li><br><br>
          <li><a href="acc_invoice_request.php" class="menu-link"><i class="fa fa-check-circle" aria-hidden="true"></i> Invoice Request</a></li><br><br>
          <li><a href="acc_po_request.php" class="menu-link"><i class="fa fa-check-circle" aria-hidden="true"></i> Vendor PO Request</a></li><br><br>
          <li><a href="acc_payments_request.php" class="menu-link"><i class="fa fa-check-circle" aria-hidden="true"></i> Vendor Payments Request</a></li><br><br>
          <li style="font-weight:bold;color: #ffff;">Reports</li><br><br>
          <li><a href="po_report.php" class="menu-link"><i class="fa fa-align-center" aria-hidden="true"></i> PO Report</a></li><br><br>
          <li><a href="payment_report.php" class="menu-link"><i class="fa fa-align-center" aria-hidden="true"></i> Payement Request Report</a></li><br><br>
        </nav>
      </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
      <section class="content" style="background: linear-gradient(327deg, #ff0000a3, #FFFFFF), url('../images/bwz_erp_bg.jpg'); background-size: cover; background-position: center center;">
        <div class="container-fluid">

          <div class="card card-default">
            <div class="card-header">
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" style="display:none;" class="btn btn-tool" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>

            <div class="card-body">

              <!-- ===== DATE FILTER BAR ===== -->
              <div class="date-filter-bar">
                <label><i class="fa fa-calendar" aria-hidden="true"></i> Filter by Date:</label>

                <label for="filter_start_date" style="font-weight:500 !important;">Start Date</label>
                <input type="date" id="filter_start_date" name="filter_start_date">

                <label for="filter_end_date" style="font-weight:500 !important;">End Date</label>
                <input type="date" id="filter_end_date" name="filter_end_date">

                <button class="btn-csv-export" id="exportCsvBtn" onclick="exportCSV()">
                  <i class="fa fa-file-csv"></i> Export CSV
                </button>

                <button class="btn-filter-reset" onclick="resetFilter()">
                  <i class="fa fa-times"></i> Reset
                </button>

                <span class="filter-error" id="filterError">Please select both Start Date and End Date.</span>
              </div>
              <!-- ===== END DATE FILTER BAR ===== -->

              <div class="row">
                <div class="col-md-12">
                  <div class="card-body p-0">
                    <table class="table table-striped projects table-bordered" id="table_camp">
                      <thead>
                        <tr>
                          <th style="width: 2%">Sr.No.</th>
                          <th style="width: 2%">Date/Time</th>
                          <th style="width:10%">Campaign Code</th>
                          <th style="width:10%">Vendor Id</th>
                          <th style="width:10%">Vendor Name</th>
                          <th style="width:10%">Type Of Vendor</th>
                          <th style="width:10%">Total No.Of Prints</th>
                          <th style="width:10%">Per Unit Cost</th>
                          <th style="width:10%">Status</th>
                          <th style="width:80%">OP's Comments</th>
                          <th style="width:50%">ACC status</th>
                          <!--<th style="width:50%">Ceo status</th>-->
                          <th style="width:50%">Director status</th>
                          <th style="width:50%">Comments</th>
                          <th style="width:10%">View</th>
                          <th style="width:20%">Action</th>
                        </tr>
                      </thead>
                      <tbody>

                        <?php
                        $stmt_select = "SELECT * from purchase order by id desc";
                        $rslt_rs = mysqli_query($conn, $stmt_select);

                        $x = 1;
                        while ($row = mysqli_fetch_assoc($rslt_rs)) {

                          $Po_file = "PO/" . $row["PO_invoice"];
                          $vendor_name = $row["vendor_name"];

                          $stmt_vn = "SELECT vendor_id from vendor_reg where vendor_name='$vendor_name' order by id desc limit 1;";
                          $rslt_vn = mysqli_query($conn, $stmt_vn);
                          $row_vn = mysqli_fetch_row($rslt_vn);
                          $vendor_id = $row_vn[0];

                          // Store created_date as data attribute for JS filtering
                          $created_date_val = ($row["created_date"] !== "0000-00-00 00:00:00") ? $row["created_date"] : "";
                          $date_only = $created_date_val ? date('Y-m-d', strtotime($created_date_val)) : "";
                        ?>
                          <tr data-date="<?php echo $date_only; ?>">
                            <td><?php echo $x; ?></td>
                            <td><?php echo $created_date_val; ?></td>
                            <td><?php echo $row["campaign_code"]; ?></td>
                            <td><?php echo $vendor_id; ?></td>
                            <td><?php echo $row["vendor_name"]; ?></td>
                            <td><?php echo $row["Type_of_Vendor"]; ?></td>
                            <td><?php echo $row["total_prints"]; ?></td>
                            <td><?php echo $row["per_unitcost"]; ?></td>
                            <td><?php echo $row["status"]; ?></td>
                            <td><?php echo $row["comments"]; ?></td>
                            <td><?php echo $row["acc_status"]; ?></td>
                            <!--<td><?php echo $row["ceo_status"]; ?></td>-->
                            <td><?php echo $row["director_status"]; ?></td>
                            <td><?php echo $row["acc_comments"] . "  " . $row["ceo_comments"] . "  " . $row["director_comments"]; ?></td>
                            <td>
                              <?php if ($row["PO_invoice"] != "") { ?>
                                <button class="btn btn-primary" onclick="viewCheck('<?php echo $Po_file; ?>')"><i class="fa fa-eye"></i> PO</button>
                              <?php } ?>
                            </td>
                            <td>
                              <?php if ($row["PO_invoice"] != "" || $row["acc_status"] != "") { ?>
                              <?php } else { ?>
                                <button class="btn btn-success" style="width: 91px;" onclick="acceptInstallation('<?php echo $row['id']; ?>','<?php echo $row['vendor_name']; ?>')">Upload PO</button>
                                <br><br>
                                <button class="btn btn-danger btn-sm" style="width: 91px;" onclick="rejectInstallation('<?php echo $row['id']; ?>','<?php echo $row['vendor_name']; ?>')">Reject</button>
                                <br>
                              <?php } ?>
                            </td>
                          </tr>
                        <?php $x++; } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

            </div>
          </div>

        </div>

        <!-- Modal Accept -->
        <div class="modal fade" id="myModal_accept" style="opacity: 3;top : 104px !important">
          <div class="modal-dialog">
            <div class="modal-content" style="top:40px;height:450px;overflow-y:auto;">
              <div class="modal-header">
                <h4 class="modal-title">Vendor PO</h4>
                <button type="button" class="close24" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                  <div class="card-body">
                    <div class="form-group">
                      <label for="UserID">Vendor Name</label>
                      <input type="text" class="form-control" id="txt_vendorname" name="txt_vendorname" readonly>
                    </div>
                    <div class="form-group">
                      <label for="IngroupName">Upload PO<span style="color:red;">*</span></label>
                      <input type="file" class="form-control" id="invoice" accept=".pdf" name="invoice" required>
                      <span style="font-size: 10px;font-family: Open Sans;"><b>Only PDF</b></span>
                    </div>
                  </div>
                  <input type="hidden" value="" id="acceptID" name="acceptID">
                  <div class="card-footer">
                    <input type="submit" class="btn btn-success" name="accept_neworder" value="Upload">
                  </div>
                </form>
              </div>
              <div class="modal-footer"></div>
            </div>
          </div>
        </div>

        <!-- Modal Reject -->
        <div class="modal fade" id="myModal_delete" style="opacity: 3;top : 104px !important">
          <div class="modal-dialog">
            <div class="modal-content" style="top:40px;">
              <div class="modal-header">
                <h4 class="modal-title">Reject New Order</h4>
                <button type="button" class="close23" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                  <div class="card-body">
                    <div class="form-group">
                      <label for="UserID">Customer Name</label>
                      <input type="text" class="form-control" id="txt_name" name="txt_name" readonly>
                    </div>
                    <div class="form-group">
                      <label for="IngroupName">Reason For Reject<span style="color:red;">*</span></label>
                      <textarea class="form-control" id="txt_reason" name="txt_reason" placeholder="Enter reason for reject the new installation" required></textarea>
                    </div>
                  </div>
                  <input type="hidden" value="" id="deleteIngroupID" name="deleteIngroupID">
                  <div class="card-footer">
                    <input type="submit" class="btn btn-success" name="reject_neworder" value="Reject">
                  </div>
                </form>
              </div>
              <div class="modal-footer"></div>
            </div>
          </div>
        </div>

        <!-- Modal Edit -->
        <div class="modal fade" id="myModal_edit" style="opacity: 3;top : 104px !important; overflow-y:scroll;">
          <div class="modal-dialog">
            <div class="modal-content" style="top:40px;width: 800px; margin-left: -100px;">
              <div class="modal-header">
                <h4 class="modal-title">Update Vendor PO Request</h4>
                <button type="button" class="close123" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                  <table>
                    <tr>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label>Vendor Name<span style="color:red;">*</span></label>
                          <select class="form-control select2" style="width: 100%;" name="txt_vendor_Name" id="txt_vendor_Name" required>
                            <option value="">Select Vendor Name</option>
                            <?php
                            $stmt_select = "SELECT * from vendor_reg where ceo_status='Accepted'";
                            $rslt_rs = mysqli_query($conn, $stmt_select);
                            while ($row = mysqli_fetch_assoc($rslt_rs)) { ?>
                              <option value="<?php echo $row["vendor_name"]; ?>"><?php echo $row["vendor_name"]; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label>Type of vendor<span style="color:red;">*</span></label>
                          <select class="form-control" id="txt_vendor_type" name="txt_vendor_type">
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
                          <label>Campaign Code<span style="color:red;">*</span></label>
                          <select class="form-control select2" style="width: 100%;" name="txt_Campaing_Code" id="txt_Campaing_Code" required>
                            <option value="">Select Campaign Code</option>
                            <?php
                            $stmt_select = "SELECT * from order_details where ceo_status='Accepted'";
                            $rslt_rs = mysqli_query($conn, $stmt_select);
                            while ($row = mysqli_fetch_assoc($rslt_rs)) { ?>
                              <option value="<?php echo $row["code"]; ?>"><?php echo $row["code"]; ?> - <?php echo $row["company_name"]; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </th>
                    </tr>
                    <tr>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label>Total No of Prints/ Fleet/ Autos<span style="color:red;">*</span></label>
                          <input type="text" class="form-control" pattern="^[0-9\s]*$" id="txt_total_prints" name="txt_total_prints" placeholder="Enter Total No of Prints/ Fleet/ Autos" required>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label>Per Unit Cost<span style="color:red;">*</span></label>
                          <input type="text" class="form-control" pattern="^[0-9\s]*$" id="txt_per_unit" name="txt_per_unit" placeholder="Enter Per unit cost" required>
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
            </div>
          </div>
        </div>

        <!-- Modal Add -->
        <div class="modal fade" id="myuser" style="opacity: 3;top : 104px !important; overflow-y:scroll;">
          <div class="modal-dialog">
            <div class="modal-content" style="top:40px;width: 800px; margin-left: -100px;">
              <div class="modal-header">
                <h4 class="modal-title">Add Vendor PO Request</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                  <table>
                    <tr>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label>Vendor Name<span style="color:red;">*</span></label>
                          <select class="form-control select2" style="width: 100%;" name="vendor_Name" id="vendor_Name" required>
                            <option value="">Select Vendor Name</option>
                            <?php
                            $stmt_select = "SELECT * from vendor_reg where ceo_status='Accepted'";
                            $rslt_rs = mysqli_query($conn, $stmt_select);
                            while ($row = mysqli_fetch_assoc($rslt_rs)) { ?>
                              <option value="<?php echo $row["vendor_name"]; ?>"><?php echo $row["vendor_name"]; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label>Type of vendor<span style="color:red;">*</span></label>
                          <select class="form-control" id="vendor_type" name="vendor_type">
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
                          <label>Campaign Code<span style="color:red;">*</span></label>
                          <select class="form-control select2" style="width: 100%;" name="Campaing_Code" id="Campaing_Code" required>
                            <option value="">Select Campaign Code</option>
                            <?php
                            $stmt_select = "SELECT * from order_details where ceo_status='Accepted'";
                            $rslt_rs = mysqli_query($conn, $stmt_select);
                            while ($row = mysqli_fetch_assoc($rslt_rs)) { ?>
                              <option value="<?php echo $row["code"]; ?>"><?php echo $row["code"]; ?> - <?php echo $row["company_name"]; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </th>
                    </tr>
                    <tr>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label>Total No of Prints/ Fleet/ Autos<span style="color:red;">*</span></label>
                          <input type="text" class="form-control" pattern="^[0-9\s]*$" id="total_prints" name="total_prints" placeholder="Enter Total No of Prints/ Fleet/ Autos" required>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label>Per Unit Cost<span style="color:red;">*</span></label>
                          <input type="text" class="form-control" pattern="^[0-9\s]*$" id="per_unit" name="per_unit" placeholder="Enter Per unit cost" required>
                        </div>
                      </th>
                    </tr>
                  </table>
                  <div class="card-footer">
                    <input type="submit" class="btn btn-success" name="addInfo_list" value="Submit">
                  </div>
                </form>
              </div>
              <div class="modal-footer"></div>
            </div>
          </div>
        </div>

      </section>
    </div>

    <footer>
      <p>© 2024. All rights reserved by Brand On Wheelz</p>
    </footer>

    <aside class="control-sidebar control-sidebar-dark"></aside>
  </div>

  <!-- Scripts -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="plugins/select2/js/select2.full.min.js"></script>
  <script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
  <script src="plugins/moment/moment.min.js"></script>
  <script src="plugins/inputmask/jquery.inputmask.min.js"></script>
  <script src="plugins/daterangepicker/daterangepicker.js"></script>
  <script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
  <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
  <script src="plugins/bs-stepper/js/bs-stepper.min.js"></script>
  <script src="plugins/dropzone/min/dropzone.min.js"></script>
  <script src="dist/js/adminlte.min.js"></script>
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
    // ===== DATE FILTER & CSV EXPORT =====
    function exportCSV() {
      var startDate = document.getElementById('filter_start_date').value;
      var endDate   = document.getElementById('filter_end_date').value;
      var errorEl   = document.getElementById('filterError');

      if (!startDate || !endDate) {
        errorEl.style.display = 'inline';
        return;
      }
      if (startDate > endDate) {
        errorEl.textContent = 'Start Date cannot be after End Date.';
        errorEl.style.display = 'inline';
        return;
      }
      errorEl.style.display = 'none';
      errorEl.textContent   = 'Please select both Start Date and End Date.';

      // Redirect to same page with export params — PHP handles download
      var url = window.location.pathname
        + '?export_csv=1'
        + '&csv_start_date=' + encodeURIComponent(startDate)
        + '&csv_end_date='   + encodeURIComponent(endDate);
      window.location.href = url;
    }

    function resetFilter() {
      document.getElementById('filter_start_date').value = '';
      document.getElementById('filter_end_date').value   = '';
      document.getElementById('filterError').style.display = 'none';
    }
    // ===== END DATE FILTER & CSV EXPORT =====

    function togglePopup(PO, Check) {
      var popup = document.getElementById("popup");
      if (popup.style.display === "none") {
        popup.style.display = "block";
        setupViewInvoiceButton(PO);
        setupViewCheckButton(Check);
      } else {
        popup.style.display = "none";
      }
    }

    function setupViewInvoiceButton(PO) {
      var btn = document.getElementById("viewInvoiceButton");
      btn.onclick = null;
      btn.onclick = function() { viewInvoice(PO); };
    }

    function setupViewCheckButton(Check) {
      var btn = document.getElementById("viewCheckButton");
      btn.onclick = null;
      btn.onclick = function() { viewCheck(Check); };
    }

    function viewPayment(imagePath) {
      document.getElementById('imageViewer').innerHTML = '<img src="' + imagePath + '" alt="Image">';
      document.getElementById('downloadLink').setAttribute('href', imagePath);
      document.getElementById('imageModal').style.display = 'flex';
    }

    function viewInvoice(PO) {
      document.getElementById('pdfViewer').innerHTML = '<object data="' + PO + '" type="application/pdf" width="100%" height="100%"></object>';
      document.getElementById('pdfModal').style.display = 'flex';
    }

    function viewCheck(Check) {
      document.getElementById('pdfViewer').innerHTML = '<object data="' + Check + '" type="application/pdf" width="100%" height="100%"></object>';
      document.getElementById('pdfModal').style.display = 'flex';
    }

    function closeImageModal() {
      document.getElementById('imageModal').style.display = 'none';
      document.getElementById('imageViewer').innerHTML = '';
    }

    function closeModal() {
      document.getElementById('popup').style.display = 'none';
    }

    function closePDFModal() {
      document.getElementById('pdfModal').style.display = 'none';
      document.getElementById('pdfViewer').innerHTML = '';
    }

    function acceptInstallation(Aval_id, Acust_name) {
      document.getElementById('acceptID').value = Aval_id;
      document.getElementById('txt_vendorname').value = Acust_name;
      var modal3 = document.getElementById('myModal_accept');
      var span3  = document.getElementsByClassName("close24")[0];
      modal3.style.display = "block";
      span3.onclick = function() { modal3.style.display = "none"; }
      window.onclick = function(event) {
        if (event.target == modal3) modal3.style.display = "none";
      }
    }

    function rejectInstallation(val_id, cust_name) {
      document.getElementById('deleteIngroupID').value = val_id;
      document.getElementById('txt_name').value = cust_name;
      var modal2 = document.getElementById('myModal_delete');
      var span2  = document.getElementsByClassName("close23")[0];
      modal2.style.display = "block";
      span2.onclick = function() { modal2.style.display = "none"; }
      window.onclick = function(event) {
        if (event.target == modal2) modal2.style.display = "none";
      }
    }

    function ingroupEditFun(val) {
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          var res = this.responseText.split("*");
          document.getElementById('txt_vendor_Name').value   = res[0];
          document.getElementById('txt_vendor_type').value   = res[1];
          document.getElementById('txt_Campaing_Code').value = res[2];
          document.getElementById('txt_total_prints').value  = res[3];
          document.getElementById('txt_per_unit').value      = res[4];
          document.getElementById('txt_id').value            = res[5];
        }
      };
      xhttp.open("GET", "edit_po_request.php?id=" + val, true);
      xhttp.send();
      var modal1 = document.getElementById('myModal_edit');
      var span1  = document.getElementsByClassName("close123")[0];
      modal1.style.display = "block";
      span1.onclick = function() { modal1.style.display = "none"; }
      window.onclick = function(event) {
        if (event.target == modal1) modal1.style.display = "none";
      }
    }

    function showadd_user() {
      var modal2 = document.getElementById('myuser');
      var span2  = document.getElementsByClassName("close")[0];
      modal2.style.display = "block";
      span2.onclick = function() { modal2.style.display = "none"; }
      window.onclick = function(event) {
        if (event.target == modal2) modal2.style.display = "none";
      }
    }

    $(function() {
      $('#table_camp').dataTable();
      $('.select2').select2();
      $('.select2bs4').select2({ theme: 'bootstrap4' });
      $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
      $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' });
      $('[data-mask]').inputmask();
      $('#reservationdate').datetimepicker({ format: 'L' });
      $('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });
      $('#reservation').daterangepicker();
      $('#reservationtime').daterangepicker({
        timePicker: true, timePickerIncrement: 30,
        locale: { format: 'MM/DD/YYYY hh:mm A' }
      });
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
      }, function(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
      });
      $('#timepicker').datetimepicker({ format: 'LT' });
      $('.duallistbox').bootstrapDualListbox();
      $('.my-colorpicker1').colorpicker();
      $('.my-colorpicker2').colorpicker();
      $('.my-colorpicker2').on('colorpickerChange', function(event) {
        $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
      });
      $("input[data-bootstrap-switch]").each(function() {
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
      });
    });

    document.addEventListener('DOMContentLoaded', function() {
      window.stepper = new Stepper(document.querySelector('.bs-stepper'));
    });

    Dropzone.autoDiscover = false;
    var previewNode = document.querySelector("#template");
    if (previewNode) {
      previewNode.id = "";
      var previewTemplate = previewNode.parentNode.innerHTML;
      previewNode.parentNode.removeChild(previewNode);
      var myDropzone = new Dropzone(document.body, {
        url: "/target-url",
        thumbnailWidth: 80, thumbnailHeight: 80, parallelUploads: 20,
        previewTemplate: previewTemplate, autoQueue: false,
        previewsContainer: "#previews", clickable: ".fileinput-button"
      });
      myDropzone.on("addedfile", function(file) {
        file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file); };
      });
      myDropzone.on("totaluploadprogress", function(progress) {
        document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
      });
      myDropzone.on("sending", function(file) {
        document.querySelector("#total-progress").style.opacity = "1";
        file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
      });
      myDropzone.on("queuecomplete", function() {
        document.querySelector("#total-progress").style.opacity = "0";
      });
    }
  </script>
</body>
</html>