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


if ($_POST['addInfo_list']) {

  $Company_Name = $_POST['Company_Name'];
$Contact_Person_Name = $_POST['Contact_Person_Name'];
$Contact_Person_Mob_No = $_POST['Contact_Person_Mob_No'];
$Email_ID = $_POST['Email_ID'];
$Campaing_Details = $_POST['Campaing_Details'];
$Start_date = $_POST['Start_date'];
$Spl_Request = $_POST['Spl_Request'];
$Media_Type = $_POST['Media_Type'];
$No_of_Vehicles = $_POST['No_of_Vehicles'];
$GST = $_POST['GST'];

  $targetFile_PO = basename($_FILES["PO"]["name"]);
  $allowedFileTypes_PO = array("pdf");
  $fileExtension_PO = strtolower(pathinfo($targetFile_PO, PATHINFO_EXTENSION));

  $targetFile_checklist = basename($_FILES["checklist"]["name"]);
  $allowedFileTypes_checklist = array("pdf");
  $fileExtension_checklist = strtolower(pathinfo($targetFile_checklist, PATHINFO_EXTENSION));


    if ((!in_array($fileExtension_PO, $allowedFileTypes_PO)) && (!in_array($fileExtension_checklist, $allowedFileTypes_checklist))) {


      echo '<script>alert("Sorry, Only PDF files are allowed.")</script>';
    } else {

      $file_name_PO = $_FILES['PO']['name'];
      $upload_dir_PO = 'PO/'; // Directory to store uploaded files
      $file_path_PO = $upload_dir_PO . $file_name_PO;
      move_uploaded_file($_FILES['PO']['tmp_name'], $file_path_PO);

      $file_name_checklist = $_FILES['checklist']['name'];
      $upload_dir_checklist = 'checklist/'; // Directory to store uploaded files
      $file_path_checklist = $upload_dir_checklist . $file_name_checklist;
      move_uploaded_file($_FILES['checklist']['tmp_name'], $file_path_checklist);

      

      $date = date("Y-m-d H:i:s");
      $stmt_insert = "INSERT INTO order_details(created_date, company_name, contact_person_name, contact_person_mobile, email, Campaing_Details, Expected_Start_date, Any_Spl_Request, Media_Type, Total_No_of_Vehicles, Total_Value_Without_GST, PO, Checklist, sales_person, status) 
	  VALUES ('$date','$Company_Name','$Contact_Person_Name','$Contact_Person_Mob_No','$Email_ID','$Campaing_Details','$Start_date','$Spl_Request','$Media_Type','$No_of_Vehicles','$GST','$file_name_PO','$file_name_checklist','$usename','NEW');";

      $rslt_insert = mysqli_query($conn, $stmt_insert);
	  /*
      //require 'send_mail_rm.php';
      // After submit the form Send mail to RM
      require "../Mail/phpmailer/PHPMailerAutoload.php";
      $mail = new PHPMailer;

      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->Port = 587;
      $mail->SMTPAuth = true;
      $mail->SMTPSecure = 'tls';

      $mail->Username = 'erp@haloocom.com'; // Replace with your email
      $mail->Password = 'suef rgqd zlzf cjci'; // Replace with your email password

      $mail->setFrom('erp@haloocom.com', 'ERP');
      $adminEmail = "$rm_email"; // Replace with the desired email address
      $mail->addAddress($adminEmail);

      $mail->isHTML(true);
      $mail->Subject = "New Installation Info";
      $mail->Body = "<p>Dear user, </p> <h3>New Installation Info has been created kindly check the payment details in dash board<br></h3>
                     <br>Click the below link for login<br>
					 http://192.168.3.15/ERP_Haloocom/index.php<br>
                     <p></p>
                     <b></b>";

      $mail->send(); */
    }
  
}

if ($_POST['editInfolist']) {

   $txt_Company_Name = $_POST['txt_Company_Name'];
$txt_Contact_Person_Name = $_POST['txt_Contact_Person_Name'];
$txt_Contact_Person_Mob_No = $_POST['txt_Contact_Person_Mob_No'];
$txt_Email_ID = $_POST['txt_Email_ID'];
$txt_Campaing_Details = $_POST['txt_Campaing_Details'];
$txt_Start_date = $_POST['txt_Start_date'];
$txt_Spl_Request = $_POST['txt_Spl_Request'];
$txt_Media_Type = $_POST['txt_Media_Type'];
$txt_No_of_Vehicles = $_POST['txt_No_of_Vehicles'];
$txt_GST = $_POST['txt_GST'];
$txt_id = $_POST['txt_id'];

  $txt_targetFile_PO = basename($_FILES["txt_PO"]["name"]);
  if ($txt_targetFile_PO == "") {
    $txt_targetFile_PO = basename($_POST["txt_po_id"]);
  } else {
    $txt_targetFile_PO = basename($_FILES["txt_PO"]["name"]);
  }
  $txt_allowedFileTypes_PO = array("pdf");
  $txt_fileExtension_PO = strtolower(pathinfo($txt_targetFile_PO, PATHINFO_EXTENSION));

  $txt_targetFile_check = basename($_FILES["txt_checklist"]["name"]);
  if ($txt_targetFile_check == "") {
    $txt_targetFile_check = basename($_POST["txt_check_id"]);
  } else {
    $txt_targetFile_check = basename($_FILES["txt_checklist"]["name"]);
  }
  $txt_allowedFileTypes_check = array("pdf");
  $txt_fileExtension_check = strtolower(pathinfo($txt_targetFile_check, PATHINFO_EXTENSION));

    if ((!in_array($txt_fileExtension_PO, $txt_allowedFileTypes_PO)) && (!in_array($txt_fileExtension_check, $txt_allowedFileTypes_check))) {


      echo '<script>alert("Sorry, Only PDF files are allowed.")</script>';
    } else {

      $txt_file_name_PO = $_FILES['txt_PO']['name'];
      if ($txt_file_name_PO == "") {
        $txt_file_name_PO = $_POST['txt_po_id'];
      } else {
        $txt_file_name_PO = $_FILES['txt_PO']['name'];
      }
      $txt_upload_dir_PO = 'sow/'; // Directory to store uploaded files
      $txt_file_path_PO = $txt_upload_dir_PO . $txt_file_name_PO;
      move_uploaded_file($_FILES['txt_PO']['tmp_name'], $txt_file_path_PO);


      $txt_file_name_check = $_FILES['txt_checklist']['name'];
      if ($txt_file_name_check == "") {
        $txt_file_name_check = $_POST['txt_check_id'];
      } else {
        $txt_file_name_check = $_FILES['txt_checklist']['name'];
      }
      $txt_upload_dir_check = 'check_list/'; // Directory to store uploaded files
      $txt_file_path_check = $txt_upload_dir_check . $txt_file_name_check;
      move_uploaded_file($_FILES['txt_checklist']['tmp_name'], $txt_file_path_check);

      
        $stmt_update = "UPDATE order_details SET company_name='$txt_Company_Name',contact_person_name='$txt_Contact_Person_Name',
contact_person_mobile='$txt_Contact_Person_Mob_No',email='$txt_Email_ID',Campaing_Details='$txt_Campaing_Details',Expected_Start_date='$txt_Start_date',
Any_Spl_Request='$txt_Spl_Request',Media_Type='$txt_Media_Type',Total_No_of_Vehicles='$txt_No_of_Vehicles',Total_Value_Without_GST='$txt_GST',
PO='$txt_file_name_PO',Checklist='$txt_file_name_check' WHERE id='$txt_id'";

        $rslt_update = mysqli_query($conn, $stmt_update);
      
    }
  
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
  <meta name="viewport" content="width=1500">
  <title>Order Details Dashboard</title>

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
  background-color: rgba(255, 255, 255, 0.5); /* Transparent white */
  z-index: -1;
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
          <h2 style="margin-left:50px;color: #b52f34;"><b><i class="fa fa-align-center" aria-hidden="true"></i> Accounts Report</b></h2>
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
    <aside class="main-sidebar"  style=" background: linear-gradient(327deg, #ff0000a3, #9cb3d7  ); color: #FFFFFF;">
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
		  <li><a href="ops_details.php" class="menu-link"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Sales Details</a></li><br><br>
		 <li><a href="acc_invoice_request.php" class="menu-link"><i class="fa fa-check-circle" aria-hidden="true"></i> Invoice Request</a></li><br><br>
          <li><a href="acc_po_request.php" class="menu-link"><i class="fa fa-check-circle" aria-hidden="true"></i> Vendor PO Request</a></li><br><br>
		  <li><a href="acc_payments_request.php" class="menu-link"><i class="fa fa-check-circle" aria-hidden="true"></i> Vendor Payments Request</a></li><br><br>
          <li style="font-weight:bold;color: #ffff;">Reports</li><br><br>
          <li><a href="accounts_report.php" class="menu-link"><i class="fa fa-align-center " aria-hidden="true"></i> Accounts</a></li><br><br>

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

      <section class="content">
        <div class="container-fluid">
          <div class="card-footer">
 <form action = "<?php echo $_SERVER['PHP_SELF']; ?>" method = "POST">
            
		  <table class="table table-striped projects table-bordered" id="">
				<tr>
			
					<td><label>Start Date</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="date" id="startDate" name="startDate" >
					</td>
					<td><label>End Date</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="date" id="endDate" name="endDate" >
					</td>
				<td>
						<label for="cars">Status</label>
						  <select name='status[]' multiple class='formselect' id="status">
			<option value = "New" >New</option>
			<option value = "Rejected" >Rejected</option>
			<option value = "Completed" >Completed</option>
			</select>
					</td>
					<td>
						<input type = "submit" name="btn_submit" class = "btn btn-success">
					</td>
					 
				
				</tr>
          </table>
		  </form>
		  
          </div>

          <!-- SELECT2 EXAMPLE -->
        <div class="card card-default">
          <div class="card-header">
           <br>
			  <?php
				if (isset($_POST["btn_submit"]) == "btn_submit") {
											
							  $start_date = $_POST['startDate']; 
					          $end_date = $_POST['endDate']; 
							
							
				?>
					<label style="font-size: medium;font-weight: 400;">Date Range :&nbsp; &nbsp; <?php echo "$start_date 00:00:00"; ?>&nbsp; &nbsp; to  &nbsp; &nbsp;<?php echo "$end_date 23:59:59"; ?></label>
					
				<?php			
				}	
				?>
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
             <table class="table m-0  tableFixHead" id="table_camp" cellspacing="0" style="border: 1px solid #eee;">
              <thead>
                  <tr>
                     
					  <th class="col-xs-2" style="text-align: center;">
						Date & Time
                      </th>
                    
					  <th class="col-xs-2" style="text-align: center;">
                        Customer Name
                      </th> 
					   <th class="col-xs-2" style="text-align: center;">
                        Location
                      </th> 
					  
                      <th class="col-xs-2" style="text-align: center;">
                          Product
                      </th>
					  
					   <th class="col-xs-2" style="text-align: center;">
                          Buy Type
                      </th>
					   <th class="col-xs-2" style="text-align: center;">
                          Amount
                      </th>
					   <th class="col-xs-2" style="text-align: center;">
                          Installation Date
                      </th>
					  <th class="col-xs-2" style="text-align: center;">
                          Installation End Date
                      </th>
					  <th class="col-xs-1" style="text-align: center;">
                          Sales Person
                      </th>
					  <th class="col-xs-1" style="text-align: center;">
                          Status
                      </th>
					  <th class="col-xs-2" style="text-align: center;">
                            Finance Status
                          </th>
					<th class="col-xs-2" style="text-align: center;">
                        RM Status
                      </th>
					  <th class="col-xs-2" style="text-align: center;">
                        IMP Status
                      </th>
					  <th class="col-xs-2" style="text-align: center;">
                        OP Status
                      </th>
					  <th class="col-xs-2" style="text-align: center;">
                        CEO Status
                      </th>
					  <th class="col-xs-2" style="text-align: center;">
                        Comments
                      </th>
					 
                  </tr>
              </thead>
             <tbody>
			  <?php 	
			  
			  
			require_once("db_connect.php");
						   if (isset($_POST["btn_submit"]) == "btn_submit") {
							   
							   $start_date = $_POST['startDate']; 
							   $end_date = $_POST['endDate']; 
							 if ($start_date !="" && $end_date !="") {
								 
							 foreach ($_POST['status'] as $select_status) {								 
                            
							 if($select_status=="New"){
								 $statusSQL ="RM_status='' AND status='NEW'";
							 }else if($select_status=="Rejected"){
								 $statusSQL ="RM_status='Rejected'";
							 }else if($select_status=="Completed"){
								 $statusSQL ="status IN('Completed','Implementation','Operation')";
							}

							$stmt_login="SELECT * from installation_info where $statusSQL AND salesPerson_name='$usename' AND created_date >='$start_date 00:00:00' AND created_date <='$end_date 23:59:59' ORDER BY DATE(created_date) ASC;";
                         //   echo $stmt_login;
	                           $rslt_login= mysqli_query($conn,$stmt_login);
	                         
							  $x =1;
							  while($row = mysqli_fetch_assoc($rslt_login)) {
						
						$ins_id = $row["id"];

                          $stmt_rs = "SELECT product_type,payment,installation_date,OP_status,CEO_status,installation_end_date from pre_installation_checklist where installation_id= '$ins_id';";
                          //echo $stmt_rs;
                          $rslt_rs_imp = mysqli_query($conn, $stmt_rs);
                          $row_rs = mysqli_fetch_row($rslt_rs_imp);
                          $ptype = $row_rs[0];
                          $pymt = $row_rs[1];
						  $insta_date = $row_rs[2];
                          $op_status = $row_rs[3];
						  $ceo_status = $row_rs[4];
						  $insta_enddate = $row_rs[5];
												?>
											<tr>
											 
											  <td class="col-xs-2" style="text-align: center;"><?php echo $row["created_date"]; ?></td>
											 
											  <td class="col-xs-2" style="text-align: center;"><?php echo $row["customer_name"]; ?></td>
											  
											  <td class="col-xs-2" style="text-align: center;"><?php echo $row["location"]; ?></td>
											  <td class="col-xs-2" style="text-align: center;"><?php echo $row["product"]; ?></td>
											  <td class="col-xs-2" style="text-align: center;"><?php echo $row["buying_type"]; ?></td>
											  <td class="col-xs-2" style="text-align: center;"><?php echo $row["amount"]; ?></td>
											  <td class="col-xs-2" style="text-align: center;"><?php echo $row["installation_date"]; ?></td>
											  <td class="col-xs-2" style="text-align: center;"><?php echo $insta_enddate; ?></td>
											   <td class="col-xs-2" style="text-align: center;"><?php echo $row["salesPerson_name"]; ?></td>
											    <td class="col-xs-2" style="text-align: center;"><?php echo $row["status"]; ?></td>
												<td class="col-xs-2" style="text-align: center;"><?php echo $row["finance_status"]; ?></td>
												<td class="col-xs-2" style="text-align: center;"><?php echo $row["RM_status"]; ?></td>
												<td class="col-xs-2" style="text-align: center;"><?php echo $row["imp_status"]; ?></td>
												<td class="col-xs-2" style="text-align: center;"><?php echo $op_status; ?></td>
												<td class="col-xs-2" style="text-align: center;"><?php echo $ceo_status; ?></td>
												<td class="col-xs-2" style="text-align: center;"><?php echo $row["comments"]; ?></td>
											 </tr>							
												
												<?php
							  	$x++;}
							  }
			                                 ?>
										 
											<?php  
											}else { ?> <label style="color:red;font-size:medium;font-weight:700;text-align:center;">Please Select Date Range And Status..</label> <br>
									 
											<?php }
							 }?>
								 
                 
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
        
        </div>
        <!-- /.card -->
		
		

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

  <script src = "https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src = "https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
	<script src = "https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
	<script src = "https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src = "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src = "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src = "https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
		
	<link rel = "stylesheet" href = "https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

  <script>
    $(function() {
		  
var date = "<?php echo $TodayDate; ?>";
   var today = new Date();
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
	var yyyy = today.getFullYear();

	today = dd + '-' + mm + '-' + yyyy;
	
    $('#table_camp').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"dom": "1Bfrtip",
		 buttons: [
		 'pageLength',

            {
                extend: 'csv',
				title:'Sales_Report_'+date
                
            }

		 
        ] 
    } );

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


    
  </script>
  </body>
</html>