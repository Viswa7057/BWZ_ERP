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


if ($_POST['accept_neworder']) {

  $id = $_REQUEST["acc_id"];
  $current_timestamp = time();

  $date = date('d/m/Y', $current_timestamp);

  $time = date('H:i', $current_timestamp);

  $timestamp = $date . ' ' . $time;

  $stmt_update = "UPDATE vendor_reg SET ceo_status='Accepted',ops_status='Accepted',status='OPS', ops_action_date ='$timestamp' WHERE id='$id'";

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
      $mail->Subject = "New Vendor Details";
      $mail->Body = "<p>Dear $ops_user, </p> <h3>New Vendor Request has been added kindly check the dash board for more details.<br></h3>
                     Click the below link for login<br>
					 http://brandonwheelz.in/index.php<br>
                     <p></p>
                     <b></b>";

      $mail->send(); 
}

if ($_POST['reject_neworder']) {

  $txt_id = $_POST["deleteIngroupID"];
  $txt_reason = $_POST["txt_reason"];
  $txt_name = $_POST["txt_name"];
  $van_email = $_POST["ven_email"];

$stmt_update = "UPDATE vendor_reg SET ops_status='Rejected',status='Rejected' WHERE id='$txt_id'";
$rslt_update = mysqli_query($conn, $stmt_update); 
  
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
  $adminEmail = "$van_email"; // Replace with the desired email address
  $mail->addAddress($adminEmail);

  $mail->isHTML(true);
  $mail->Subject = "Vendor Registration Rejected";
  $mail->Body = "<p>Dear $txt_name team, </p> <h3>Your vendor registration has been rejected by BWZ team, because of $txt_reason.<br></h3>
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
  <title>Vendor Details</title>

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
          <h2 style="margin-left:50px;color: #b52f34;"><b><i class="fa fa-check-square" aria-hidden="true"></i> Vendor Details</b></h2>
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
          <li><a href="payrequest_report.php" class="menu-link"><i class="fa fa-align-center" aria-hidden="true"></i> Payement Request Report</a></li><br><br>
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
          <div class="card-footer">

          </div>

          <!-- SELECT2 EXAMPLE -->
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
            <!-- /.card-header -->
            <div class="card-body">

              <div class="row" style="overflow-x: auto;">
                <div class="col-md-12">
                  <div class="card-body p-0">
                    <table class="table table-striped projects table-bordered" id="table_camp" style="overflow-x: auto;position: sticky;">
                      <thead>
                        <tr>
                          <th style="width: 2%">
                            Sr.No.
                          </th>
                          <th style="width:20%">
                            Vendor Name
                          </th>
                          <th style="width:20%">
                            Vendor Id
                          </th>
                          <th style="width:20%">
                            Name used for advertise
                          </th>
                          <th style="width:20%">
                            Type of business
                          </th>
                          <th style="width:20%">
                            Type of vendor
                          </th>
                          <th style="width:20%">
                            Contact person name
                          </th>
                          <th style="width: 20%">
                            Phone number
                          </th>
                          <th style="width:20%">
                            Email
                          </th>
                          <th style="width:20%">
                            Address
                          </th>
                          <th style="width:20%">
                            Vendor Form Submitted 
                          </th>
                          <th style="width:20%">
                            Ops Action Date
                          </th>
                          
                          <th style="width:20%">
                            View
                          </th>

                          <th style="width:30%">
                            Action
                          </th>

                        </tr>
                      </thead>
                      <tbody>

                        <?php
                        $stmt_select = "SELECT * from vendor_reg order by id desc;";
                        $rslt_rs = mysqli_query($conn, $stmt_select);

                        $x = 1;
                        while ($row = mysqli_fetch_assoc($rslt_rs)) {

                          $VRF_file = "../VRF/" . $row["VRF_Form"];
                          $cheq_file = "../CHQ/" . $row["Cancel_Chq"];
                          $aggrement_file = "../AGRM/" . $row["Agreement"];
                          $Po_invoice_file = "../Accounts/PO/" . $row["PO_invoice"];

                        ?>
                          <tr>
                            <td>
                              <?php echo $x; ?>
                            </td>
                            <td>
                              <?php echo $row["vendor_name"]; ?>
                            </td>
                            <td>
                              <?php echo $row["vendor_id"]; ?>
                            </td>
                            <td>
                              <?php echo $row["name_used_tally"]; ?>
                            </td>
                            <td>
                              <?php echo $row["type_of_business"]; ?>
                            </td>
                            <td>
                              <?php echo $row["Type_of_Vendor"]; ?>
                            </td>
                            <td>
                              <?php echo $row["Contact_Name"]; ?>
                            </td>
                            <td>
                              <?php echo $row["Phone"]; ?>
                            </td>
                            <td>
                              <?php echo $row["E_mail"]; ?>
                            </td>
                            <td>
                              <?php echo $row["Address"]; ?>
                            </td>
                            <td>
                              <?php echo $row["vendor_form_sub_date"]; ?>
                            </td>
                            <td>
                              <?php echo $row["ops_action_date"]; ?>
                            </td>
							
                            <td>
                              <button class="btn btn-primary" onclick="togglePopup('<?php echo $VRF_file; ?>','<?php echo $cheq_file; ?>','<?php echo $aggrement_file; ?>')"><i class="fa fa-eye"></i> DOC</button><br><br>

                              <span class="btn btn-warning" onclick="document.getElementById('myModal_edit').style.display='block'; ingroupEditFun('<?php echo $row["id"]; ?>')">
                                <i class="fa fa-eye">
                                </i>
                                Details
                              </span>


                            </td>
                           <td>
                                <?php if ($row["ops_status"] == "Accepted") { ?>
                                <?php } else { ?>
                                  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

                                    <input type="hidden" id="acc_id" name="acc_id" value='<?php echo $row['id']; ?>'>

                                    <input type="submit" class="btn btn-success" name="accept_neworder" value="Accept" style="width: 91px;">

                                  </form>
                                  <br>
                                  <button class="btn btn-danger btn-sm" style="width: 91px;" onclick="rejectInstallation('<?php echo $row['id']; ?>','<?php echo $row['vendor_name']; ?>','<?php echo $row['E_mail']; ?>')">Reject</button>
                                <?php
                                }
                                ?>
								
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
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content" style="width: 800px;margin-left: -112px;">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Payment Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <!-- Form inside the modal -->
                    <form>
                      <table>
                        <tr>

                          <th>
                            <div class="form-group" style="width:250px;">
                              <label for="UserID">Vendor Name Drop down</label>
                              <select class="form-control" id="vendor_Name" name="vendor_Name">
                                <option value="">Select Vendor</option>
                                <option value="Vendor1">Vendor 1</option>
                                <option value="Vendor2">Vendor 2</option>
                                <!-- Add more options as needed -->
                              </select>
                            </div>
                          </th>
                          <th>
                            <div class="form-group" style="width:250px;">
                              <label for="UserID">VRF Form(PDF Only) <span style="color:red;">*</span></label>
                              <input type="file" class="form-control-file" id="VRF_Form" name="VRF_Form" accept=".pdf" required>
                            </div>
                          </th>
                          <th>
                            <div class="form-group" style="width:250px;">
                              <label for="UserID">Cancel Chq<span style="color:red;">*</span></label>
                              <input type="file" class="form-control-file" id="Cancel_Chq" name="Cancel_Chq[]" accept="image/*" multiple required>
                            </div>
                          </th>
                        <tr>
                          <th>
                            <div class="form-group" style="width:250px;">
                              <label for="UserID">Agreement (PDF Only) <span style="color:red;">*</span></label>
                              <input type="file" class="form-control-file" id="Agreement" name="Agreement" accept=".pdf" required>
                            </div>
                          </th>
                          <th>
                            <div class="form-group" style="width:250px;">
                              <label for="UserID">Type of vendor</label>
                              <select class="form-control" id="vendor_type" name="vendor_type">
                                <option value="">Select Vendor Type</option>
                                <option value="Vendor1">Fleet</option>
                                <option value="Vendor2">Mounting</option>
                                <option value="Vendor1">Printing</option>
                                <option value="Vendor2">Others</option>
                                <!-- Add more options as needed -->
                              </select>
                            </div>
                          </th>

                          <th>
                            <div class="form-group" style="width:250px;">
                              <label for="UserID">Price<span style="color:red;">*</span></label>
                              <input type="text" class="form-control" pattern="^[a-zA-Z\s]*$" title="Please enter Price" id="Price" name="Price" placeholder="Enter Price" required>
                            </div>
                          </th>
                        </tr>
                        <tr>
                          <th>
                            <div class="form-group" style="width:250px;">
                              <label for="UserID">City<span style="color:red;">*</span></label>
                              <input type="text" class="form-control" pattern="^[a-zA-Z\s]*$" title="Please enter City" id="City" name="City" placeholder="Enter City" required>
                            </div>
                          </th>
                        </tr>
                  </div>
                  </th>
                  </tr>
                  </table>
                  <button type="submit" class="btn btn-primary">Submit</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card -->

        <!-- The Modal -->
        <div class="modal fade" id="myModal_edit" style="opacity: 3;top : 104px !important; overflow-y:scroll;">
          <div class="modal-dialog">
            <div class="modal-content" style="top:40px;width: 1300px; margin-left: -250px;">


              <!-- Modal Header -->
              <div class="modal-header">
                <h4 class="modal-title"><b>Vendor Details</b></h4>
                <button type="button" class="close123" data-dismiss="modal">&times;</button>
              </div>

              <!--Main Form Modal body -->
              <div class="modal-body">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                  <div class="modal-header" style="text-align:center;font-family:Roboto, Sans-serif;">
                    <h4 class="modal-title" style="color:#B52F32;">Vendor Authorization/Change Form</h4>
                  </div>
                  <table>
                    <tr>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Date</label>
                          <input type="date" class="form-control" id="date" name="date" readonly>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Vendor Name</label>
                          <input type="text" class="form-control" id="vendor_name" name="vendor_name" placeholder="Enter Vendor Name" readonly>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Phone Number</label>
                          <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Enter Vendor Phone Number" readonly>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">GST No</label>
                          <input type="text" class="form-control" id="gst_no" name="gst_no" placeholder="Enter GST Number" readonly>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">PAN No</label>
                          <input type="text" class="form-control" id="pan_no" name="pan_no" placeholder="Enter PAN Number" readonly>
                        </div>
                      </th>
                    </tr>

                    <tr>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Name used by Tally</label>
                          <input type="text" class="form-control" id="tally_name" name="tally_name" placeholder="Enter Name Used For Tally" readonly>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Type of Business</label>
                          <select class="form-control select2" style="width: 100%;" id="business_type" name="business_type" readonly>
                            <option value="">Select</option>
                            <option value="Corporation(Company)">Corporation(Company)</option>
                            <option value="Partnership">Partnership</option>
                            <option value="Limited Liability Company">Limited Liability Company</option>
                            <option value="Government Entity">Government Entity</option>
                            <option value="Individual">Individual</option>
                            <option value="Non Profit/501(c) Entity">Non Profit/501(c) Entity</option>
                            <option value="Employee">Employee</option>
                            <option value="Other">Others</option>

                          </select>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Credit Period</label>
                          <input type="text" class="form-control" id="credit_period" name="credit_period" placeholder="Enter Credit Period" readonly>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Credit Limit</label>
                          <input type="text" class="form-control" id="credit_limit" name="credit_limit" placeholder="Enter Credit Limit" readonly>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Type of Purchase/Payment</label>
                          <select class="form-control select2" style="width: 100%;" id="purchase_type" name="purchase_type" readonly>
                            <option value="">Select</option>
                            <option value="Goods">Goods</option>
                            <option value="Consultant">Consultant</option>
                            <option value="Transporter">Transporter</option>
                            <option value="Services">Services</option>
                            <option value="Auditor">Auditor</option>
                            <option value="Other">Others</option>

                          </select>
                        </div>
                      </th>
                    </tr>

                  </table>
                  <div class="modal-header" style="text-align:center;font-family:Roboto, Sans-serif;">
                    <h4 class="modal-title" style="color:#B52F32;">Branding Work</h4>
                  </div>
                  <table>
                    <tr>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Primary Name </label>
                          <input type="text" class="form-control" id="primary_name" name="primary_name" placeholder="Enter Primary Name" readonly>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Type of Vendor</label>
                          <select class="form-control select2" style="width: 100%;" id="vendor_type" name="vendor_type" readonly>
                            <option value="">Select</option>
                            <option value="Fleet">Fleet</option>
                            <option value="Mounting">Mounting</option>
                            <option value="Printing">Printing</option>
                            <option value="Other">Others</option>

                          </select>
                        </div>
                      </th>

                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Address</label>
                          <textarea class="form-control" id="address" name="address" placeholder="Enter Full Address" readonly></textarea>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">City</label>
                          <input type="text" class="form-control" id="city" name="city" placeholder="Enter City Name" readonly>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">State</label>
                          <input type="text" class="form-control" id="state" name="state" placeholder="Enter State Name" readonly>
                        </div>
                      </th>
                    </tr>
                    <tr>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Country</label>
                          <input type="text" class="form-control" id="country" name="country" placeholder="Enter Country Name" readonly>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Zip</label>
                          <input type="text" class="form-control" id="zip" name="zip" placeholder="Enter Zip Code" readonly>
                        </div>
                      </th>

                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Contact Name</label>
                          <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Enter Contact Person Name" readonly>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">E-mail</label>
                          <input type="email" class="form-control" id="contact_email" name="contact_email" placeholder="Enter Contact Person Email Id" readonly>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Phone</label>
                          <input type="text" class="form-control" id="contact_phone" name="contact_phone" placeholder="Enter Contact Person Phone Number" readonly>
                        </div>
                      </th>
                    </tr>

                    <tr>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Mobile</label>
                          <input type="text" class="form-control" id="contact_mobile" name="contact_mobile" placeholder="Enter Mobile Number" readonly>
                        </div>
                      </th>
                      <th>
                        <div class="form-group" style="width:250px;">
                          <label for="UserID">Fax</label>
                          <input type="text" class="form-control" id="contact_fax" name="contact_fax" placeholder="Enter Fax Number" readonly>
                        </div>
                      </th>
                    </tr>

                  </table>

                </form>
              </div>

              <!-- Modal footer -->
              <div class="modal-footer">
              </div>

            </div>
          </div>
        </div>

        <!-- The Modal Delete-->
        <div class="modal fade" id="myModal_delete" style="opacity: 3;top : 104px !important">
            <div class="modal-dialog">
              <div class="modal-content" style="top:40px;">

                <!-- Modal Header -->
                <div class="modal-header">
                  <h4 class="modal-title">Reject New Vendor Registration</h4>
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
		  
		  
		  <div class="modal fade" id="myModal_ceo" style="opacity: 3;top : 104px !important">
            <div class="modal-dialog">
              <div class="modal-content" style="top:40px;">

                <!-- Modal Header -->
                <div class="modal-header">
                  <h4 class="modal-title">Reject New Vendor Registration</h4>
                  <button type="button" class="close24" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <div class="card-body">
                      <div class="form-group">
                        <label for="UserID">Customer Name</label>
                        <input type="text" class="form-control" title="Please enter only numbers and characters" id="order_name" name="order_name" readonly>
                      </div>
                      <div class="form-group">
                        <label for="IngroupName">Reason For Reject<span style="color:red;">*</span></label>
                        <textarea class="form-control" id="order_reason" name="order_reason" placeholder="Enter reason for reject the new installation" required></textarea>
                      </div>
                    </div>
                    <input type="hidden" value="" id="delete_orderID" name="delete_orderID">
                    <!-- /.card-body -->

                    <div class="card-footer">
                      <input type="submit" class="btn btn-success" name="reject_order" value="Reject">
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
    <!--<button class="btn btn-success" id="viewInvoiceButton"><i class="fa fa-eye"></i>VRF</button>-->
    <button class="btn btn-success" id="viewCheckButton"><i class="fa fa-eye"></i> Cancel Chq</button>
    <button class="btn btn-success" id="viewAggreButton"><i class="fa fa-eye"></i> Agreement</button>
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
    function togglePopup(PO, Check, Aggre) {
      //alert(SOW);
      var popup = document.getElementById("popup");
      if (popup.style.display === "none") {
        popup.style.display = "block";
        setupViewInvoiceButton(PO);
        setupViewCheckButton(Check);
        setupViewAggreButton(Aggre); // Setup the button to view payment when the popup is shown
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

    function setupViewAggreButton(Aggre) {
      var viewAggreButton = document.getElementById("viewAggreButton");
      // Ensure we don't attach multiple event listeners to the same button
      viewAggreButton.onclick = null; // Remove existing onclick to avoid multiple triggers
      viewAggreButton.onclick = function() {
        viewAggre(Aggre);
      };
    }

function viewPI(PI) {
      document.getElementById('pdfViewer').innerHTML = '<object data="' + PI + '" type="application/pdf" width="100%" height="100%"></object>';
      document.getElementById('pdfModal').style.display = 'flex';
    }
	
    function viewPayment(imagePath) {
      var imageElement = '<img src="' + imagePath + '" alt="Image">';
      var downloadLinkElement = document.getElementById('downloadLink');
      downloadLinkElement.setAttribute('href', imagePath);

      // Set the image and display the modal
      document.getElementById('imageViewer').innerHTML = imageElement;
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

    function viewAggre(Aggre) {
      document.getElementById('pdfViewer').innerHTML = '<object data="' + Aggre + '" type="application/pdf" width="100%" height="100%"></object>';
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
    // `clickable` has already been specified.
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
          document.getElementById('date').value = res[0];
          document.getElementById('vendor_name').value = res[1];
          document.getElementById('phone_number').value = res[2];
          document.getElementById('gst_no').value = res[3];
          document.getElementById('pan_no').value = res[4];
          document.getElementById('tally_name').value = res[5];
          document.getElementById('business_type').value = res[6];
          document.getElementById('credit_period').value = res[7];
          document.getElementById('credit_limit').value = res[8];
          document.getElementById('purchase_type').value = res[9];
          document.getElementById('primary_name').value = res[10];
          document.getElementById('vendor_type').value = res[11];
          document.getElementById('address').value = res[12];
          document.getElementById('city').value = res[13];
          document.getElementById('state').value = res[14];
          document.getElementById('country').value = res[15];
          document.getElementById('zip').value = res[16];
          document.getElementById('contact_name').value = res[17];
          document.getElementById('contact_email').value = res[18];
          document.getElementById('contact_phone').value = res[19];
          document.getElementById('contact_mobile').value = res[20];
          document.getElementById('contact_fax').value = res[21];


        }
      };
      xhttp.open("GET", "view_vendorInfo.php?id=" + val, true);
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


    function rejectInstallation(val_id,cust_name,email) {
      //alert(cust_name);
      document.getElementById('deleteIngroupID').value = val_id;
      document.getElementById('txt_name').value = cust_name;
	  document.getElementById('ven_email').value = email;
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


    function rejectceo(ord_id, ord_name){
	//alert(cust_name);
      document.getElementById('delete_orderID').value = ord_id;
      document.getElementById('order_name').value = ord_name;
      // Get the modal
      var modal3 = document.getElementById('myModal_ceo');

      // Get the button that opens the modal
      var btn3 = document.getElementById("myBtn3");

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
  </script>
</body>

</html>