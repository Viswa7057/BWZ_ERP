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

 $stmt_rs = "SELECT user_name from users where email='$loggedInuserName';";
$rslt_rs = mysqli_query($conn, $stmt_rs);
 $row_rs = mysqli_fetch_row($rslt_rs);
 $usename = $row_rs[0];
 ?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
  <meta name="viewport" content="width=1500">
  <title>Admin Dashboard</title>

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
    #sidebar {
      width: 170px;
      background-color: #97c33a;
      color: #fff;
      padding: 20px;
      display: block;
        }

    main {
      flex: 1;
    }

    .subheading {
      text-align: left;
      margin-top: 20px;
    }

    /* .dashboard {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      margin: 20px;
    } */

    .dashboard {
      display: grid;
      grid-template-columns: repeat(3, calc(25% - 20px));
      /* 25% width with 20px gap */
      gap: 20px;
      margin: 20px;
      margin-left: 50px
        /* Move the entire dashboard towards the right by 20px */
    }

    .subheading {
      margin-left: 50px;
      /* Move the subheading towards the right by 20px */
    }

    /* .tile {
      border: 1px solid #ddd;
      text-align: center;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      cursor: pointer;
    } */



    .tile {
      border: 1px solid #ddd;
      text-align: center;
      background-color: #fff;
      /* Default background color */
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      cursor: pointer;
      position: relative;
    }


    .tile::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      height: 100%;
      width: 4px;
      /* Adjust the width of the left edge as needed */
      border-radius: 8px 0 0 8px;
      /* text-align: center; */
      /* Rounded left corners */
    }

    /* Red left edge for left tiles */
    .dashboard .tile:nth-child(3n+1)::before {
      background-color: #36b9cc;
      /* Red color */
    }

    /* Yellow left edge for middle tiles */
    .dashboard .tile:nth-child(3n+2)::before {
      background-color: rgba(45, 45, 220, 0.815);
      /* Yellow color */
    }

    /* Blue left edge for right tiles */
    .dashboard .tile:nth-child(3n)::before {
      background-color: #e21717;
      /* Blue color */
    }


    .tile h5 {
      color: #333;
      text-align: left;
      /* Align text to the left */
      margin: 10px 0 10px 10px;
      /* Add some vertical margin and left margin for better spacing */

    }

    .tile span {
      margin-right: 190px;
      /* Adjust the left margin as needed */
    }

    .finimg{
      width: 39px;
    height: 38px;
    }



    #graph {
      border: 1px solid #ddd;
      height: 300px;
      margin: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    #redirectButton {
      display: block;
      margin: 20px;
      padding: 10px;
      font-size: 16px;
      background-color: #333;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    form {
      margin: 20px;
      padding: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      display: none;
      /* Initially hide the form */
    }

    form label {
      display: block;
      margin-bottom: 10px;
    }

    form input {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }

    form button {
      background-color: #333;
      color: #fff;
      padding: 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    #addProductButton {
      background-color: #333;
      color: #fff;
      padding: 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 10px;
      display: none;
      /* Initially hide the button */
    }

    .totimg {
      height: 100px;
      width: 100px;
      margin-left: 200px;
      /* Adjust the value according to your needs */
      margin-top: -80px;
    }

    .logo {
    max-width: 184px;
    height: 53px;
    display: block;
    margin: 0 auto;
    margin-top: 19px;
    margin-left: 14px;
}
    @media screen and (max-width: 768px) {
      .dashboard {
        grid-template-columns: repeat(auto-fill, minmax(100%, 1fr));
        gap: 10px;
        margin: 10px;
      }

      .subheading {
        margin-left: 10px;

      }

    }

    header {
      background-color: white;
      padding: 10px;
      color: #b52f34;
    }

    h3 {
      color: #b52f34;
    }

    ul,
    li,
    a {
      color: #fff;
      text-decoration: none;
      list-style-type: none;
    }

    footer {
      text-align: center;
      padding: 3px;
      background-color: #fff;
      color: #3a403a;
      font-size: 15px;
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

    .content {
      margin-top: -70px;
      /* Adjust this value to move the section up */
    }
  
     /* Ensure modal body scrolls independently */
     .modal-body {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }

    /* Ensure entire page is scrollable when modal is open */
    body.modal-open {
        overflow: auto !important;
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
          <h2 style="margin-left:50px;color: #b52f34;"><b><i class="fa fa-th-large" aria-hidden="true"></i> Admin Dashboard</b></h2>
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
          <hr style="border: 0.5px solid #fff;border-radius: 2px;"><br>
          <!-- Add sidebar content as needed -->
          <li><a href="admin_dashboard.php"><i class="fa fa-th-large" aria-hidden="true"></i> Dashboard</a></li><br><br>
          <li><a href="user.php"><i class="fa fa-users" aria-hidden="true"></i> User</a></li><br><br>
         <?php
          if ($loggedInUserLevel == "Director") {
          ?>
		  
		  <li><a href="ops_details.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i> New Sales Info</a></li><br><br>
          <li><a href="ops_vendor_details.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i> New Vendor Info</a></li><br><br>
          <li><a href="po_request.php"><i class="fa fa-check-circle" aria-hidden="true"></i> PO Request</a></li><br><br>
		  <li><a href="director_payment_request.php"><i class="fa fa-check-circle" aria-hidden="true"></i> Payments Request</a></li><br><br>
		   <?php
          } else {
          } ?>
         
         <li style="font-weight:bold;color: #ffff;"> Sales Reports</li><br><br>
          <li><a href="sales_report.php" class="menu-link"><i class="fa fa-align-center " aria-hidden="true"></i> Sales</a></li><br><br>
          <li style="font-weight:bold;color: #ffff;"> Operation Reports</li><br><br>
          <li><a href="po_report.php" class="menu-link"><i class="fa fa-align-center" aria-hidden="true"></i> PO Report</a></li><br><br>
          <li><a href="payment_report.php" class="menu-link"><i class="fa fa-align-center" aria-hidden="true"></i> Payment Request Report</a></li><br><br>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">

            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content" style="background: linear-gradient(327deg, #ff0000a3, #FFFFFF), url('../images/bwz_erp_bg.jpg'); background-size: cover; background-position: center center;">
 
       <!-- <div class="date_report" style="margin-left: 50px;">
          <div class="form-group txtbx" style="display: inline-block;width: 20%;vertical-align: top;margin-right:7px;margin-left:5px;">
            <label for="UserID">From Date:</label><input type="date" class="form-control" id="outboundfromdate" name="outboundfromdate">
          </div>
          <div class="form-group txtbx" style="display: inline-block;width: 20%;vertical-align: top;margin-right:7px;">
            <label for="UserID">To Date:</label><input type="date" class="form-control" id="outboundtodate" name="outboundtodate">
          </div>
          <div class="form-group" style="display: inline-block;width: 20%;vertical-align: top;margin-right:7px;margin-top:36px;">
            <button type="button" class="btn btn-sm" onclick="selectDate()" style="background-color: #3CB371;color: #ffff;">Submit</button>
          </div>
        </div> -->
<br><br>
        <div class="subheading">
          <h3><b><i class="fa fa-align-center" aria-hidden="true"></i> Sales</b></h3>
        </div>

        <div class="dashboard">
          
          <div class="tile" id="CompletedSaleTile" data-toggle="modal" data-target="#myModal">
            <h5 style="color:#36b9cc;"><b>Completed Sales</b></h5><br>
            <span style="cursor:pointer;"><span id="CompletedSale" style="margin-left:20px;">0</span></span>
            <img class="totimg" src="images\totsales.jpg" alt="Completed Sale Image">
          </div>

          <div class="tile" id="pendingSaleTile" data-toggle="modal" data-target="#myModal_pending">
            <h5 style="color:rgba(45, 45, 220, 0.815);"><b>Pending Sales</b></h5><br>
            <span style="cursor:pointer"><span id="pendingSale" style="margin-left:20px;">0</span></span>
            <img class="totimg" src="images\totimpl.jpg" alt="Completed Sale Image">
          </div>

          <div class="tile" id="rejectedSaleTile" data-toggle="modal" data-target="#myModal_rejected">
            <h5 style="color:#e21717;"><b>Rejected Sales</b></h5><br>
            <span style="cursor:pointer"><span id="rejectedSale" style="margin-left:20px;">0</span></span>
            <img class="totimg" src="images\rejsale.jpg" alt="Completed Sale Image">
          </div>
        </div>

        
        <!-- <hr style="margin: 20px 0;"> -->

         <!-- Operation Section -->
        

        <div class="subheading">
          <h3><b><i class="fa fa-align-center" aria-hidden="true"></i> Invoice Request</b></h3>
        </div>

        <div class="dashboard">
          <div class="tile" id="CompletedSaleTile" data-toggle="modal" data-target="#myModal_invoice">
            <h5 style="color:#36b9cc;"><b>Completed Invoice</b></h5>
            <span style="cursor:pointer"><span id="Completed_invoice" style="margin-left:20px;">0</span></span>
            <img class="totimg" src="images/totsales.jpg" alt="Completed Sale Image">
          </div>

          <div class="tile" id="pendingSaleTile" data-toggle="modal" data-target="#myModal_pending_invoice">
            <h5 style="color:rgba(45, 45, 220, 0.815);"><b>Pending Invoice</b></h5>
            <span style="cursor:pointer"><span id="pending_invoice" style="margin-left:20px;">0</span></span>
            <img class="totimg" src="images\totimpl.jpg" alt="Completed Sale Image">
          </div>
		  
        </div>
		
		<!-- ##############################################################################   -->
		
		
		<div class="subheading">
          <h3><b><i class="fa fa-align-center" aria-hidden="true"></i> Vendor PO Request</b></h3>
        </div>

        <div class="dashboard">
          <div class="tile" id="CompletedSaleTile" data-toggle="modal" data-target="#myModal_po">
            <h5 style="color:#36b9cc;"><b>Completed PO Request</b></h5>
            <span style="cursor:pointer"><span id="Completed_po" style="margin-left:20px;">0</span></span>
            <img class="totimg" src="images/totsales.jpg" alt="Completed Sale Image">
          </div>

          <div class="tile" id="pendingSaleTile" data-toggle="modal" data-target="#myModal_pending_po">
            <h5 style="color:rgba(45, 45, 220, 0.815);"><b>Pending PO Request</b></h5>
            <span style="cursor:pointer"><span id="pending_po" style="margin-left:20px;">0</span></span>
            <img class="totimg" src="images\totimpl.jpg" alt="Completed Sale Image">
          </div>
		  
        </div>
		
		<!-- ##############################################################################   -->
		
        <div class="subheading">
          <h3><b><i class="fa fa-align-center" aria-hidden="true"></i> Payment Request</b></h3>
        </div>

        <div class="dashboard">
          <div class="tile" id="CompletedSaleTile" data-toggle="modal" data-target="#myModal_completed_fin">
            <h5 style="color:#36b9cc;"><b>Completed Payments</b></h5>
            <span style="cursor:pointer"><span id="CompletedFinance" style="margin-left:20px;">0</span></span>
            <img class="totimg" src="images/totsales.jpg" alt="Completed Sale Image">
          </div>

          <div class="tile" id="pendingSaleTile" data-toggle="modal" data-target="#myModal_pending_fin">
            <h5 style="color:rgba(45, 45, 220, 0.815);"><b>Pending Payments</b></h5>
            <span style="cursor:pointer"><span id="pendingFinance" style="margin-left:20px;">0</span></span>
            <img class="totimg" src="images\totimpl.jpg" alt="Completed Sale Image">
          </div>

          <div class="tile" id="rejectedSaleTile" data-toggle="modal" data-target="#myModal_rejected_fin">
            <h5 style="color:#e21717;"><b>Rejected Payments</b></h5>
            <span style="cursor:pointer"><span id="rejectedFinance" style="margin-left:20px;">0</span></span>
            <img class="totimg" src="images\rejsale.jpg" alt="Completed Sale Image">
          </div>
        </div>
		
		<!-- ###########################################################################  -->
        
        <!-- <hr style="margin: 20px 0;"> -->
        <!-- Graph Section -->

        <div id="graph">
          <canvas id="myChart"></canvas> <!-- Add canvas element for the graph -->
        </div>
      </section>
      <!-- /.content -->
    </div>

    <div class="modal fade" id="myModal" role="dialog" style="margin-left: -13%;">
      <div class="modal-dialog" style="overflow-y: initial !important">

        <!-- Modal content-->
        <div class="modal-content" style="width: 220%;">
          <div class="modal-header">
            <!--    <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">COMPLETED SALES</h4>
          </div>
          <div class="modal-body" style="height: 500px; overflow-y: auto;" id="CompletedSaleData">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>


    <div class="modal fade" id="myModal_pending" role="dialog" style="margin-left: -13%;">
      <div class="modal-dialog" style="overflow-y: initial !important">

        <!-- Modal content-->
        <div class="modal-content" style="width: 220%;">
          <div class="modal-header">
            <!--    <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">PENDING SALES</h4>
          </div>
          <div class="modal-body" style="height: 500px; overflow-y: auto;" id="pendingSaleData">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>


    <div class="modal fade" id="myModal_rejected" role="dialog" style="margin-left: -13%;">
      <div class="modal-dialog" style="overflow-y: initial !important">

        <!-- Modal content-->
        <div class="modal-content" style="width: 220%;">
          <div class="modal-header">
            <!--    <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">REJECTED SALES</h4>
          </div>
          <div class="modal-body" style="height: 500px; overflow-y: auto;" id="rejectedSaleData">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>

    <div class="modal fade" id="myModal_imp" role="dialog" style="margin-left: -13%;">
      <div class="modal-dialog" style="overflow-y: initial !important">

        <!-- Modal content-->
        <div class="modal-content" style="width: 205%;">
          <div class="modal-header">
            <!--    <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">COMPLETED IMPLEMENTATIONS</h4>
          </div>
          <div class="modal-body" style="height: 500px; overflow-y: auto;" id="CompletedImplementationsData">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>


    <div class="modal fade" id="myModal_pending_imp" role="dialog" style="margin-left: -13%;">
      <div class="modal-dialog" style="overflow-y: initial !important">

        <!-- Modal content-->
        <div class="modal-content" style="width: 205%;">
          <div class="modal-header">
            <!--    <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">PENDING IMPLEMENTATIONS</h4>
          </div>
          <div class="modal-body" style="height: 500px; overflow-y: auto;" id="pendingImplementationsData">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>


    <div class="modal fade" id="myModal_rejected_imp" role="dialog" style="margin-left: -13%;">
      <div class="modal-dialog" style="overflow-y: initial !important">

        <!-- Modal content-->
        <div class="modal-content" style="width: 220%;">
          <div class="modal-header">
            <!--    <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">REJECTED IMPLEMENTATIONS</h4>
          </div>
          <div class="modal-body" style="height: 500px; overflow-y: auto;" id="rejectedImplementationsData">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>


    <div class="modal fade" id="myModal_op" role="dialog" style="margin-left: -13%;">
      <div class="modal-dialog" style="overflow-y: initial !important">

        <!-- Modal content-->
        <div class="modal-content" style="width: 205%;">
          <div class="modal-header">
            <!--    <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">COMPLETED OPERATIONS</h4>
          </div>
          <div class="modal-body" style="height: 500px; overflow-y: auto;" id="CompletedOperationsData">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>


    <div class="modal fade" id="myModal_pending_op" role="dialog" style="margin-left: -13%;">
      <div class="modal-dialog" style="overflow-y: initial !important">

        <!-- Modal content-->
        <div class="modal-content" style="width: 205%;">
          <div class="modal-header">
            <!--    <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">PENDING OPERATIONS</h4>
          </div>
          <div class="modal-body" style="height: 500px; overflow-y: auto;" id="pendingOperationsData">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>


    <div class="modal fade" id="myModal_rejected_op" role="dialog" style="margin-left: -13%;">
      <div class="modal-dialog" style="overflow-y: initial !important">

        <!-- Modal content-->
        <div class="modal-content" style="width: 210%;">
          <div class="modal-header">
            <!--    <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">REJECTED OPERATIONS</h4>
          </div>
          <div class="modal-body" style="height: 500px; overflow-y: auto;" id="rejectedOperationsData">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>

<!-- graph of finannce -->
    <div class="modal fade" id="myModal_completed_fin" role="dialog" style="margin-left: -13%;">
      <div class="modal-dialog" style="overflow-y: initial !important">

        <!-- Modal content-->
        <div class="modal-content" style="width: 210%;">
          <div class="modal-header">
            <!--    <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">COMPLETED PAYMENTS </h4>
          </div>
          <div class="modal-body" style="height: 500px; overflow-y: auto;" id="completedFinanceData">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>
    <div class="modal fade" id="myModal_pending_fin" role="dialog" style="margin-left: -13%;">
      <div class="modal-dialog" style="overflow-y: initial !important">

        <!-- Modal content-->
        <div class="modal-content" style="width: 210%;">
          <div class="modal-header">
            <!--    <button type="button" class="close" data-dismiss="modal">&times;</button> -->
           <h4 class="modal-title">PENDING PAYMENTS </h4>
          </div>
          <div class="modal-body" style="height: 500px; overflow-y: auto;" id="pendingFinanceData">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>
	
	<div class="modal fade" id="myModal_rejected_fin" role="dialog" style="margin-left: -13%;">
      <div class="modal-dialog" style="overflow-y: initial !important">

        <!-- Modal content-->
        <div class="modal-content" style="width: 210%;">
          <div class="modal-header">
            <!--    <button type="button" class="close" data-dismiss="modal">&times;</button> -->
           <h4 class="modal-title">REJECTED PAYMENTS </h4>
          </div>
          <div class="modal-body" style="height: 500px; overflow-y: auto;" id="rejectedFinanceData">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>
	
	 <!-- ########################################################################################## -->
	
	<div class="modal fade" id="myModal_invoice" role="dialog" style="margin-left: -13%;">
      <div class="modal-dialog" style="overflow-y: initial !important">

        <!-- Modal content-->
        <div class="modal-content" style="width: 205%;">
          <div class="modal-header">
            <!--    <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">COMPLETED INVOICE</h4>
          </div>
          <div class="modal-body" style="height: 500px; overflow-y: auto;" id="Completed_Invoice_Data">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>


    <div class="modal fade" id="myModal_pending_invoice" role="dialog" style="margin-left: -13%;">
      <div class="modal-dialog" style="overflow-y: initial !important">

        <!-- Modal content-->
        <div class="modal-content" style="width: 205%;">
          <div class="modal-header">
            <!--    <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">PENDING INVOICE</h4>
          </div>
          <div class="modal-body" style="height: 500px; overflow-y: auto;" id="pending_Invoice_Data">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>
	
	<!-- ########################################################################################## -->
	
	<div class="modal fade" id="myModal_po" role="dialog" style="margin-left: -13%;">
      <div class="modal-dialog" style="overflow-y: initial !important">

        <!-- Modal content-->
        <div class="modal-content" style="width: 205%;">
          <div class="modal-header">
            <!--    <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">COMPLETED PO</h4>
          </div>
          <div class="modal-body" style="height: 500px; overflow-y: auto;" id="Completed_PO_Data">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>


    <div class="modal fade" id="myModal_pending_po" role="dialog" style="margin-left: -13%;">
      <div class="modal-dialog" style="overflow-y: initial !important">

        <!-- Modal content-->
        <div class="modal-content" style="width: 205%;">
          <div class="modal-header">
            <!--    <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">PENDING PO</h4>
          </div>
          <div class="modal-body" style="height: 500px; overflow-y: auto;" id="pending_PO_Data">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>
	
	<div class="modal fade" id="myModal_total_billing" role="dialog" style="margin-left: -13%;">
      <div class="modal-dialog" style="overflow-y: initial !important">

        <!-- Modal content-->
        <div class="modal-content" style="width: 210%;height:3450px;">
         
              <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          
          </div>
          <div class="modal-body" style="overflow-y: visible;" id="Total_billing_info">

          </div>
          
        </div>

      </div>
    </div>
    <!-- /.content-wrapper -->
    <footer>
      <p><b>© 2024. All rights reserved by Brand On Wheelz</b></p>
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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    var loginUser = '<?php echo $usename; ?>';

    function agentLiveData() {
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          var result1 = this.responseText;
          //alert(result1);
          console.log(result1);
          var result = result1.split("**");
          $('#CompletedSale').html(result[0]);
          $('#pendingSale').html(result[1]);
          $('#rejectedSale').html(result[2]);
	
		  $('#CompletedFinance').html(result[3]);
          $('#pendingFinance').html(result[4]);
		  $('#rejectedFinance').html(result[5]);
		  
		  $('#Completed_invoice').html(result[6]);
          $('#pending_invoice').html(result[7]);
		  
		  $('#Completed_po').html(result[8]);
          $('#pending_po').html(result[9]);

          $('#CompletedSaleData').html(result[10]);
          $('#pendingSaleData').html(result[11]);
          $('#rejectedSaleData').html(result[12]);
		  
		  $('#completedFinanceData').html(result[13]);
          $('#pendingFinanceData').html(result[14]);
		  $('#rejectedFinanceData').html(result[15]);
		  
		  $('#Completed_Invoice_Data').html(result[16]);
          $('#pending_Invoice_Data').html(result[17]);
		  
		  $('#Completed_PO_Data').html(result[18]);
          $('#pending_PO_Data').html(result[19]);

          $('#completed_sales').dataTable();
          $('#pending_sales').dataTable();
          $('#rejected_sales').dataTable();

		  
		  $('#completed_finance').dataTable();
          $('#pending_finance').dataTable();
		  $('#rejected_finance').dataTable();
		  
		  $('#completed_invoice_data').dataTable();
          $('#pending_invoice_data').dataTable();
		  
		   $('#completed_PO_data').dataTable();
          $('#pending_PO_data').dataTable();
		 

          var ctx = document.getElementById('myChart').getContext('2d');
          var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: ['Completed Sale', 'Pending Sale', 'Rejected Sale', 'Completed Payments', 'Pending Peyments', 'Rejected Payments', 'Completed Invoice', 'Pending Invoice', 'Completed PO', 'Pending PO'],
              datasets: [{
                label: 'Data',
                data: [
                  result[0], result[1], result[2], result[3], result[4], result[5], result[6], result[7], result[8], result[9]
                ],
                backgroundColor: [
                  'rgba(255, 99, 132, 0.5)',
                  'rgba(54, 162, 235, 0.5)',
                  'rgba(255, 206, 86, 0.5)',
                  'rgba(75, 192, 192, 0.5)',
                  'rgba(153, 102, 255, 0.5)',
                  'rgba(255, 159, 64, 0.5)',
                  'rgba(129, 199, 132, 0.5)',
                  'rgba(199, 129, 132, 0.5)',
                  'rgba(132, 199, 129, 0.5)',
                ],
                borderColor: [
                  'rgba(255, 99, 132, 1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)',
                  'rgba(153, 102, 255, 1)',
                  'rgba(255, 159, 64, 1)',
                  'rgba(129, 199, 132, 1)',
                  'rgba(199, 129, 132, 1)',
                  'rgba(132, 199, 129, 1)',
                ],
                borderWidth: 1,
              }, ],
            },
            options: {
              scales: {
                y: {
                  beginAtZero: true,
                },
              },
            },
          });


        }
      };
      xhttp.open("GET", "admin_sales_data.php?loginUser=" + loginUser, true);
      xhttp.send();

    }
    agentLiveData();
    setInterval(function() {
      agentLiveData()
    }, 60000);

    // Function to update the chart
    function updateChart(labels, data) {
      myChart.data.labels = labels;
      myChart.data.datasets[0].data = data;

      myChart.update();
    }
    updateChart();
    setInterval(function() {
      updateChart()
    }, 60000);


    function selectDate() {
      var outfromdate = document.getElementById("outboundfromdate").value;
      var outtodate = document.getElementById("outboundtodate").value;

      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          var result1 = this.responseText;
          //alert(result1);
          console.log(result1);
          var result = result1.split("**");
           $('#CompletedSale').html(result[0]);
          $('#pendingSale').html(result[1]);
          $('#rejectedSale').html(result[2]);

          $('#CompletedImplementation').html(result[3]);
          $('#pendingImplementation').html(result[4]);
          $('#rejectedImplementation').html(result[5]);

          $('#CompletedOperations').html(result[6]);
          $('#pendingOperations').html(result[7]);
          $('#rejectedOperations').html(result[8]);
		  
		  $('#CompletedFinance').html(result[9]);
          $('#pendingFinance').html(result[10]);

          $('#CompletedSaleData').html(result[11]);
          $('#pendingSaleData').html(result[12]);
          $('#rejectedSaleData').html(result[13]);

          $('#CompletedImplementationsData').html(result[14]);
          $('#pendingImplementationsData').html(result[15]);
          $('#rejectedImplementationsData').html(result[16]);

          $('#CompletedOperationsData').html(result[17]);
          $('#pendingOperationsData').html(result[18]);
          $('#rejectedOperationsData').html(result[19]);
		  
		  $('#completedFinanceData').html(result[20]);
          $('#pendingFinanceData').html(result[21]);

          $('#completed_sales').dataTable();
          $('#pending_sales').dataTable();
          $('#rejected_sales').dataTable();

          $('#completed_Implementations').dataTable();
          $('#pending_Implementations').dataTable();
          $('#rejected_Implementations').dataTable();

          $('#completed_Operations').dataTable();
          $('#pending_Operations').dataTable();
          $('#rejected_Operations').dataTable();
		  
		  $('#completed_finance').dataTable();
          $('#pending_finance').dataTable();

          var ctx = document.getElementById('myChart').getContext('2d');
          var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: ['Completed Sale', 'Pending Sale', 'Rejected Sale', 'Completed Implementation', 'Pending Implementation', 'Rejected Implementation', 'Completed Operation', 'Pending Operation', 'Rejected Operation', 'Completed Finance', 'Pending Finance'],
              datasets: [{
                label: 'Data',
                data: [
                  result[0], result[1], result[2], result[3], result[4], result[5], result[6], result[7], result[8], result[9], result[10], result[0], result[1], result[2], result[3], result[4], result[5], result[6], result[7], result[8], result[9], result[10], result[0], result[1], result[2], result[3], result[4], result[5], result[6], result[7], result[8], result[9], result[10]
                ],
                backgroundColor: [
                  'rgba(255, 99, 132, 0.5)',
                  'rgba(54, 162, 235, 0.5)',
                  'rgba(255, 206, 86, 0.5)',
                  'rgba(75, 192, 192, 0.5)',
                  'rgba(153, 102, 255, 0.5)',
                  'rgba(255, 159, 64, 0.5)',
                  'rgba(129, 199, 132, 0.5)',
                  'rgba(199, 129, 132, 0.5)',
                  'rgba(132, 199, 129, 0.5)',
                ],
                borderColor: [
                  'rgba(255, 99, 132, 1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)',
                  'rgba(153, 102, 255, 1)',
                  'rgba(255, 159, 64, 1)',
                  'rgba(129, 199, 132, 1)',
                  'rgba(199, 129, 132, 1)',
                  'rgba(132, 199, 129, 1)',
                ],
                borderWidth: 1,
              }, ],
            },
            options: {
              scales: {
                y: {
                  beginAtZero: true,
                },
              },
            },
          });

        }
      };
      xhttp.open("GET", "admin_date_filter.php?loginUser=" + loginUser + "&outfromdate=" + outfromdate + "&outtodate=" + outtodate, true);
      xhttp.send();

      function updateChart(labels, data) {
        myChart.data.labels = labels;
        myChart.data.datasets[0].data = data;

        myChart.update();
      }
      updateChart();

    }
  </script>

  <script>
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

          // alert(res[10]);
          document.getElementById('txt_cmpny_name').value = res[0];
          document.getElementById('txt_cust_name').value = res[1];
          document.getElementById('txt_desg').value = res[2];
          document.getElementById('txt_mbl_no').value = res[3];
          document.getElementById('txt_email').value = res[4];
          document.getElementById('txt_type').value = res[5];
          document.getElementById('txt_po_no').value = res[6];
          document.getElementById('txt_payment').value = res[7];
          document.getElementById('txt_office_add').value = res[8];
          document.getElementById('txt_Office_no').value = res[9];
          document.getElementById('txt_datepicker').value = res[10];
          document.getElementById('txt_rack_space').value = res[11];
          document.getElementById('txt_power_supply').value = res[12];
          document.getElementById('txt_power_bk').value = res[13];
          document.getElementById('txt_lan_net').value = res[14];
          document.getElementById('txt_agent_pc').value = res[15];
          document.getElementById('txt_internet').value = res[16];
          document.getElementById('txt_fire_wall').value = res[17];
          document.getElementById('txt_public_ip').value = res[18];
          document.getElementById('txt_customization').value = res[19];
          document.getElementById('txt_customization_com').value = res[20];
          document.getElementById('txt_pri').value = res[21];
          document.getElementById('txt_sip').value = res[22];
          document.getElementById('txt_gsm').value = res[23];
          document.getElementById('txt_voip').value = res[24];
          document.getElementById('txt_analog').value = res[25];
          document.getElementById('txt_special_req').value = res[26];
          document.getElementById('txt_sow').value = res[27];
          document.getElementById('txt_id').value = res[28];
          //document.getElementById('txt_product_type').value =res[29];

        }
      };
      xhttp.open("GET", "edit_pre_installation.php?id=" + val, true);
      xhttp.send();
      // Get the modal
      var modal1 = document.getElementById('myModal_edit');
      // Get the button that opens the modal
      var btn1 = document.getElementById("myBtngrpedit");
      // Get the <span> element that closes the modal
      var span1 = document.getElementsByClassName("close1")[0];
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


    function ingroupDeleteFun(val_grpd, ingroupIDVal, ingroupNameVal) {
      document.getElementById('deleteIngroupID').value = val_grpd;
      document.getElementById('txt_deleteingroup_id').value = ingroupIDVal;
      document.getElementById('txt_deleteingroup_name').value = ingroupNameVal;

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
  </script>
</body>

</html>