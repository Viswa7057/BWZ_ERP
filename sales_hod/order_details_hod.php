<?php
// include_once('../check_login.php');
// // Check if the user is not logged in, redirect to index.php
// if (!isset($_SESSION['username'])) {
//   header("Location: ../index.php");
//   exit();
// }
require_once("db_connect.php");
session_start();

$loggedInuserName  = $_SESSION['username'];
$loggedInUserLevel = $_SESSION['user_level'];

$stmt_rs = "SELECT user_name,email from users where email='$loggedInuserName';";
$rslt_rs = mysqli_query($conn, $stmt_rs);
$row_rs = mysqli_fetch_row($rslt_rs);
$usename = $row_rs[0];
$sale_email = $row_rs[1];

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$stmt_hod = "SELECT user_name,email from users where user_level='HOD_Sales';";
$rslt_hod = mysqli_query($conn, $stmt_hod);
$row_hod = mysqli_fetch_row($rslt_hod);
$hod_name = $row_hod[0];
$hod_email = $row_hod[1];


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

  $code = rand(100, 999);
  $letter_company = substr($Company_Name, 0, 3);
  $unique_id = "BWZ-" . $letter_company . "-" . $code;


  $targetFile_checklist = basename($_FILES["checklist"]["name"]);
  $allowedFileTypes_checklist = array("pdf");
  $fileExtension_checklist = strtolower(pathinfo($targetFile_checklist, PATHINFO_EXTENSION));


  if ((!in_array($fileExtension_checklist, $allowedFileTypes_checklist))) {


    echo '<script>alert("Sorry, Only PDF files are allowed.")</script>';
  } else {
$uploadDirectory = '../Sales/PO/';
     foreach ($_FILES['PO']['tmp_name'] as $key => $tmp_name) {
        $fileName = basename($_FILES['PO']['name'][$key]);
        $uploadPath = $uploadDirectory . $fileName;
		    $fileTmpName = $_FILES['PO']['tmp_name'][$key];

        if (move_uploaded_file($fileTmpName, $uploadPath)) {
            $fileNames[] = $fileName;
            $filePaths[] = $uploadPath;
        } else {
           // echo "Error uploading file: $fileName<br>";
        }
    }

    $file_name_checklist = $_FILES['checklist']['name'];
    $upload_dir_checklist = '../Sales/checklist/'; // Directory to store uploaded files
    $file_path_checklist = $upload_dir_checklist . $file_name_checklist;
    move_uploaded_file($_FILES['checklist']['tmp_name'], $file_path_checklist);

 $fileNamesStr = implode('|', $fileNames);


    $date = date("Y-m-d H:i:s");
    $stmt_insert = "INSERT INTO order_details(created_date, company_name, contact_person_name, contact_person_mobile, email, Campaing_Details, Expected_Start_date, Any_Spl_Request, Media_Type, Total_No_of_Vehicles, Total_Value_Without_GST, PO, Checklist, sales_person, status,code,HOD_Status) 
	  VALUES ('$date','$Company_Name','$Contact_Person_Name','$Contact_Person_Mob_No','$Email_ID','$Campaing_Details','$Start_date','$Spl_Request','$Media_Type','$No_of_Vehicles','$GST','$fileNamesStr','$file_name_checklist','$usename','NEW','$unique_id','Accepted');";

    $rslt_insert = mysqli_query($conn, $stmt_insert);

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
    $adminEmail = "$hod_email"; // Replace with the desired email address
    $mail->addAddress($adminEmail);

    $mail->isHTML(true);
    $mail->Subject = "New Order Info";
    $mail->Body = "<p>Dear $hod_name, </p> <h3>New Order Info has been added kindly check the dash board for more details.<br></h3>
                     Click the below link for login<br>
					 http://brandonwheelz.in/index.php<br>
                     <b></b>";

    $mail->send();
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

  

  $txt_targetFile_check_ad = basename($_FILES["txt_checklist"]["name"]);
  if ($txt_targetFile_check_ad == "") {
    $txt_targetFile_check = basename($_POST["txt_check_id"]);
  } else {
    $txt_targetFile_check = basename($_FILES["txt_checklist"]["name"]);
  }

  $txt_allowedFileTypes_check = array("pdf");
  $txt_fileExtension_check = strtolower(pathinfo($txt_targetFile_check, PATHINFO_EXTENSION));

  if ((!in_array($txt_fileExtension_check, $txt_allowedFileTypes_check))) {


    echo '<script>alert("Sorry, Only PDF files are allowed.")</script>';
  } else {
$uploadDirectory = '../sales_hod/PO/';
     foreach ($_FILES['txt_PO']['tmp_name'] as $key => $tmp_name) {
        $fileName = basename($_FILES['txt_PO']['name'][$key]);
        $uploadPath = $uploadDirectory . $fileName;
		    $fileTmpName = $_FILES['txt_PO']['tmp_name'][$key];

        if (move_uploaded_file($fileTmpName, $uploadPath)) {
            $fileNames[] = $fileName;
            $filePaths[] = $uploadPath;
        } else {
           // echo "Error uploading file: $fileName<br>";
        }
    }
    

    $txt_file_name_check = $_FILES['txt_checklist']['name'];
    if ($txt_file_name_check == "") {
      $txt_file_name_check = $_POST['txt_check_id'];
    } else {
      $txt_file_name_check = $_FILES['txt_checklist']['name'];
    }
    $txt_upload_dir_check = '../sales_hod/checklist/'; // Directory to store uploaded files
    $txt_file_path_check = $txt_upload_dir_check . $txt_file_name_check;
    move_uploaded_file($_FILES['txt_checklist']['tmp_name'], $txt_file_path_check);
 $fileNamesStr = implode('|', $fileNames);
    $stmt_update = "UPDATE order_details SET company_name='$txt_Company_Name',contact_person_name='$txt_Contact_Person_Name',
contact_person_mobile='$txt_Contact_Person_Mob_No',email='$txt_Email_ID',Campaing_Details='$txt_Campaing_Details',Expected_Start_date='$txt_Start_date',
Any_Spl_Request='$txt_Spl_Request',Media_Type='$txt_Media_Type',Total_No_of_Vehicles='$txt_No_of_Vehicles',Total_Value_Without_GST='$txt_GST',
PO='$fileNamesStr',Checklist='$txt_file_name_check',status='NEW',HOD_Status='',ceo_status='' WHERE id='$txt_id'";

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
      background-color: rgba(255, 255, 255, 0.5);
      /* Transparent white */
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
          <h2 style="margin-left:50px;color: #b52f34;"><b><i class="fa fa-check-square" aria-hidden="true"></i> New Sales INFO</b></h2>
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
          <li><a href="hod_dashboard.php" class="menu-link"><i class="fa fa-th-large " aria-hidden="true"></i> Dashboard</a></li><br><br>
          <li><a href="order_details.php" class="menu-link"><i class="fa fa-shopping-cart" aria-hidden="true"></i> New Sales Info</a></li><br><br>
		    <li><a href="order_details_hod.php" class="menu-link"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Add New Sale </a></li><br><br>
          <li style="font-weight:bold;">Reports</li><br><br>
          <li><a href="sales_report.php" class="menu-link"><i class="fa fa-align-center" aria-hidden="true"></i> Sales</a></li><br><br>

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

            <button type="submit" id="adduserButton" onclick="showadd_user()" ;><i class="fa fa-check-square" aria-hidden="true"></i> Add </button>

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

              <div class="row">
                <div class="col-md-12">
                  <div class="card-body p-0">
                    <table class="table table-striped projects table-bordered" id="table_camp">
                      <thead>
                        <tr>
                          <th style="width: 2%">
                            Sr.No.
                          </th>
                          <th style="width:20%">
                            Code
                          </th>
                          <th style="width:20%">
                            Company Name
                          </th>
                          <th style="width:20%">
                            Contact Person Name
                          </th>
                          <th style="width:20%">
                            Contact Person Email ID
                          </th>
                          <th style="width:20%">
                            Campaing Details
                          </th>
                          <th style="width: 20%">
                            Expected Start date
                          </th>
                          <th style="width:20%">
                            Media Type
                          </th>
                          <th style="width:20%">
                            Total No of Vehicles
                          </th>
                          <th style="width:20%">
                            Status
                          </th>
                           <th style="width:20%">
                            PO
                          </th>
                          <th style="width:20%">
                            Check
                          </th>

                          <th style="width:30%">
                            ACTION
                          </th>

                        </tr>
                      </thead>
                      <tbody>

                        <?php
                        $stmt_select = "SELECT * from order_details where sales_person='$usename'";
                        //echo $stmt_select;
                        $rslt_rs = mysqli_query($conn, $stmt_select);

						$x = 1;
						while ($row = mysqli_fetch_assoc($rslt_rs)) {
						$Po_files = explode('|', $row["PO"]); // assuming "file_name" is the column storing PO file names
						$check_file = "checklist/" . $row["Checklist"];


                        ?>
                          <tr>
                            <td>
                              <?php echo $x; ?>
                            </td>
                            <td>
                              <?php echo $row["code"]; ?>
                            </td>
                            <td>
                              <?php echo $row["company_name"]; ?>
                            </td>
                            <td>
                              <?php echo $row["contact_person_name"]; ?>
                            </td>
                            <td>
                              <?php echo $row["email"]; ?>
                            </td>
                            <td>
                              <?php echo $row["Campaing_Details"]; ?>
                            </td>
                            <td>
                              <?php echo $row["Expected_Start_date"]; ?>
                            </td>
                            <td>
                              <?php echo $row["Media_Type"]; ?>
                            </td>
                            <td>
                              <?php echo $row["Total_No_of_Vehicles"]; ?>
                            </td>
                            <td>
                              <?php echo $row["status"]; ?>
                            </td>
                            <td>
                              <!--<button class="btn btn-primary" onclick="viewInvoice('<?php echo $Po_file; ?>')"><i class="fa fa-eye"></i> PO</button>-->
                            <?php 
                                foreach ($Po_files as $index => $Po_file): ?>
								<button class="btn btn-primary" onclick="viewPO('<?php echo 'PO/' . htmlspecialchars($Po_file); ?>', <?php echo $index + 1; ?>)">
								<i class="fa fa-eye"></i> PO <?php echo $index + 1;
							?>
									</button><br>
								<?php endforeach; ?>
                            </td>
                            <td>
                              <button class="btn btn-primary" onclick="viewCheck('<?php echo $check_file; ?>')"><i class="fa fa-eye"></i> Check</button>
                            </td>

                            <td class="project-actions text-right">
                              <span class="btn btn-warning" onclick="document.getElementById('myModal_edit').style.display='block'; ingroupEditFun('<?php echo $row["id"]; ?>')">
                                <i class="fas fa-pencil-alt">
                                </i>
                                Edit
                              </span>


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

          </div>
          <!-- /.card -->

          <!-- The Modal -->
          <div class="modal fade" id="myModal_edit" style="opacity: 3;top : 104px !important; overflow-y:scroll;">
            <div class="modal-dialog">
              <div class="modal-content" style="top:40px;width: 1300px; margin-left: -250px;">


                <!-- Modal Header -->
                <div class="modal-header">
                  <h4 class="modal-title">Edit Sales Info</h4>
                  <button type="button" class="close123" data-dismiss="modal">&times;</button>
                </div>

                <!--Main Form Modal body -->
                <div class="modal-body">
                  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                    <table>
                      <tr>

                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="UserID">Company Name<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" pattern="^[a-zA-Z\s]*$" title="Please enter alphabet only" id="txt_Company_Name" name="txt_Company_Name" placeholder="Enter Company Name" required>
                          </div>
                        </th>
                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="ProductID">Contact Person Name<span style="color:red;">*</span></label>

                            <input type="text" class="form-control" pattern="^[a-zA-Z\s]*$" title="Please enter alphabet only" id="txt_Contact_Person_Name" name="txt_Contact_Person_Name" placeholder="Enter Contact Person Name" required>
                          </div>
                        </th>
                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="ProductID">Contact Person Mob No<span style="color:red;">*</span></label>

                            <input type="text" class="form-control" pattern="^[0-9\s]*$" title="Please enter Number only" id="txt_Contact_Person_Mob_No" name="txt_Contact_Person_Mob_No" placeholder="Enter Contact Person Mob No" required>
                          </div>
                        </th>
                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="ProductID">Contact Person Email ID<span style="color:red;">*</span></label>
                            <input type="text" class="form-control"   id="txt_Email_ID" name="txt_Email_ID" placeholder="Enter Contact Person Email ID" required>

						  </div>
                        </th>

                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="UserID">Campaing Details <span style="color:red;"></span>*</label>
                            <textarea class="form-control" pattern=".*" title="Please enter only alphabets and numbers" id="txt_Campaing_Details" name="txt_Campaing_Details" placeholder="Enter Campaing Details " required></textarea>
                          </div>
                        </th>
                      </tr>

                      <tr>
                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="UserID">Expected Start date<span style="color:red;">*</span></label>
                            <input type="date" class="form-control" id="txt_Start_date" name="txt_Start_date" required>
                          </div>
                        </th>


                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="UserID">Any Spl Request</Address><span style="color:red;"></span></label>
                            <textarea class="form-control" pattern=".*" title="Please enter only alphabets and numbers" id="txt_Spl_Request" name="txt_Spl_Request" placeholder="Enter Any Spl Request"></textarea>
                          </div>
                        </th>
                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="UserID">Media Type<span style="color:red;">*</span></label>
                            <select class="form-control select2" style="width: 100%;" id="txt_Media_Type" name="txt_Media_Type" required>
                              <option value="">Select</option>
                              <option value="Cabs-Door Wrap">Cabs-Door Wrap</option>
                              <option value="Cabs-Full Wrap">Cabs-Full Wrap</option>
                              <option value="Cabs-Boot Wrap">Cabs-Boot Wrap</option>
                              <option value="Auto Hood">Auto Hood</option>
                              <option value="Blr Namma Metro">Blr Namma Metro</option>
                              <option value="ADZ Basket">ADZ Basket</option>
                              <option value="Others">Others</option>
                            </select>
                          </div>
                        </th>

                        <th>


                          <div class="form-group" style="width:250px;">
                            <label for="UserID">Total No of Vehicles<span style="color:red;"></span></label>
                            <input type="text" class="form-control" pattern="^[0-9\s]*$" title="Please enter Number only" id="txt_No_of_Vehicles" name="txt_No_of_Vehicles" placeholder="Enter Total No of Vehicles">
                          </div>

                        </th>


                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="UserID">Total Value Without GST<span style="color:red;"></span></label>
                            <input type="text" class="form-control" pattern="^[0-9\s]*$" title="Please enter Number only" id="txt_GST" name="txt_GST" placeholder="Enter Total Value Without GST">
                          </div>
                        </th>
                      </tr>
                      <tr>
                        <th>
                          <!--<div class="form-group">-->
                          <!--  <label for="IngroupName">PO(PDF)</label>-->
                          <!--  <input type="file" class="form-control" id="txt_PO" name="txt_PO">-->
                          <!--</div>-->
                           <div class="container mt-3">
                              <form action="upload_po.php" method="post" enctype="multipart/form-data">
                                 <div class="form-group">
                                         <label for="txt_PO">PO(PDF)</label>
                                        <input type="file" class="form-control" id="txt_PO" name="txt_PO[]">
                                 </div>
                                     <div id="txt_additionalFields"></div>
                                             <button type="button" class="btn btn-primary" id="txt_addMoreBtn">Add More</button>
                                                                <!-- <button type="submit" class="btn btn-success">Upload</button> -->
                                                </form>
                                     </div>
                        </th>
                        <th>
                          <div class="form-group">
                            <label for="IngroupName">Checklist(PDF)</label>
                            <input type="file" class="form-control" id="txt_checklist" name="txt_checklist">
                          </div>
                        </th>


                      </tr>
                    </table>
                    <div class="card-footer">
                      <input type="hidden" class="form-control" id="txt_id" name="txt_id">
                      <input type="hidden" class="form-control" id="txt_po_id" name="txt_po_id">
                      <input type="hidden" class="form-control" id="txt_check_id" name="txt_check_id">
                      <input type="submit" class="btn btn-success" name="editInfolist" value="Re-Submit">
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

          <!-- The Modal Edit -->
          <div class="modal fade" id="myuser" style="opacity: 3;top : 104px !important; overflow-y:scroll;">
            <div class="modal-dialog">
              <div class="modal-content" style="top:40px;width: 1300px; margin-left: -250px;">

                <!-- Modal Header -->
                <div class="modal-header">
                  <h4 class="modal-title">Sales Info</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!--Main Form Modal body -->
                <div class="modal-body">
                  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                    <table>
                      <tr>

                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="UserID">Company Name<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" pattern="^[a-zA-Z\s]*$" title="Please enter alphabet only" id="Company_Name" name="Company_Name" placeholder="Enter Company Name" required>
                          </div>
                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="ProductID">Contact Person Name<span style="color:red;">*</span></label>

                            <input type="text" class="form-control" pattern="^[a-zA-Z\s]*$" title="Please enter alphabet only" id="Contact_Person_Name" name="Contact_Person_Name" placeholder="Enter Contact Person Name" required>
                          </div>
                        </th>
                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="ProductID">Contact Person Mob No<span style="color:red;">*</span></label>

                            <input type="text" class="form-control" title="Please enter alphabet only" id="Contact_Person_Mob_No" name="Contact_Person_Mob_No" placeholder="Enter Contact Person Mob No" required>
                          </div>
                        </th>
                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="ProductID">Contact Person Email ID<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" title="Please enter alphabet only" id="Email_ID" name="Email_ID" placeholder="Enter Contact Person Email ID" required>
                          </div>
                        </th>

                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="UserID">Campaing Details <span style="color:red;">*</span></label>
                            <textarea class="form-control" pattern=".*" title="Please enter only alphabets and numbers" id="Campaing_Details" name="Campaing_Details" placeholder="Enter Campaing Details " required></textarea>
                          </div>
                        </th>

                      </tr>
                      <tr>


                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="UserID">Expected Start date<span style="color:red;">*</span></label>
                            <input type="date" class="form-control" id="Start_date" name="Start_date" required>
                          </div>
                        </th>

                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="UserID">Any Spl Request</Address><span style="color:red;"></span></label>
                            <textarea class="form-control" pattern=".*" title="Please enter only alphabets and numbers" id="Spl_Request" name="Spl_Request" placeholder="Enter Any Spl Request"></textarea>
                          </div>
                        </th>
                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="UserID">Media Type<span style="color:red;">*</span></label>
                            <select class="form-control select2" style="width: 100%;" id="Media_Type" name="Media_Type" required>
                              <option value="">Select</option>
                              <option value="Cabs-Door Wrap">Cabs-Door Wrap</option>
                              <option value="Cabs-Full Wrap">Cabs-Full Wrap</option>
                              <option value="Cabs-Boot Wrap">Cabs-Boot Wrap</option>
                              <option value="Auto Hood">Auto Hood</option>
                              <option value="Blr Namma Metro">Blr Namma Metro</option>
                              <option value="ADZ Basket">ADZ Basket</option>
                              <option value="Others">Others</option>
                            </select>
                          </div>
                        </th>

                        <th>


                          <div class="form-group" style="width:250px;">
                            <label for="UserID">Total No of Vehicles<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" pattern="^[0-9\s]*$" title="Please enter Number only" id="No_of_Vehicles" name="No_of_Vehicles" placeholder="Enter Total No of Vehicles" required>
                          </div>

                        </th>

                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="UserID">Total Value Without GST<span style="color:red;"></span></label>
                            <input type="text" class="form-control" pattern="^[0-9\s]*$" title="Please enter Number only" id="GST" name="GST" placeholder="Enter Total Value Without GST">
                          </div>
                        </th>

                      </tr>
                      <tr>
                        <th>
                          <!--<div class="form-group">-->
                          <!--  <label for="IngroupName">PO(PDF)<span style="color:red;">*</span></label>-->
                          <!--  <input type="file" class="form-control" id="PO" name="PO">-->
                          <!--</div>-->
                            <div class="container mt-3">
        <form action="upload_po.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="txt_PO">PO(PDF)</label>
                <input type="file" class="form-control" id="PO" name="PO[]">
            </div>
            <div id="additionalFields"></div>
            <button type="button" class="btn btn-primary" id="addMoreBtn">Add More</button>
           <!-- <button type="submit" class="btn btn-success">Upload</button> -->
        </form>
    </div>
                        </th>
                        <th>
                          <div class="form-group">
                            <label for="IngroupName">Checklist(PDF)<span style="color:red;">*</span></label>
                            <input type="file" class="form-control" id="checklist" name="checklist">
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
                  <h4 class="modal-title">DELETE INGROUP</h4>
                  <button type="button" class="close23" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                  <form action="" method="POST">
                    <div class="card-body">
                      <div class="form-group">
                        <label for="UserID">Ingroup Id</label>
                        <input type="text" class="form-control" title="Please enter only numbers and characters" id="txt_deleteingroup_id" name="txt_deleteingroup_id" readonly>
                      </div>
                      <div class="form-group">
                        <label for="IngroupName">Ingroup Name</label>
                        <input type="text" class="form-control" pattern="^[a-zA-Z0-9\s]*$" title="Please enter only numbers and characters" id="txt_deleteingroup_name" name="txt_deleteingroup_name">
                      </div>
                    </div>
                    <input type="hidden" value="" id="deleteIngroupID" name="deleteIngroupID">
                    <!-- /.card-body -->

                    <div class="card-footer">
                      <input type="submit" class="btn btn-success" name="deletelist" value="Delete">
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
<script>
   document.addEventListener('DOMContentLoaded', (event) => {
            const addMoreBtn = document.getElementById('addMoreBtn');
            const additionalFields = document.getElementById('additionalFields');
            let fileInputCount = 1;

            addMoreBtn.addEventListener('click', () => {
                if (fileInputCount < 5) {
                    fileInputCount++;
                    const div = document.createElement('div');
                    div.className = 'form-group';
                    div.innerHTML = `
                        <label for="PO${fileInputCount}">PO(PDF) ${fileInputCount}</label>
                        <input type="file" class="form-control" id="PO${fileInputCount}" name="PO[]">
                    `;
                    additionalFields.appendChild(div);
                } else {
                    alert('You can only add up to 5 files.');
                }
            });
        });
  </script>
  <script>
  function viewPO(filePath, index) {
	 // alert(filePath);
    //$('#poModalBody').html('<a href="' + filePath + '" target="_blank">PO ' + index + '</a>');
  //  $('#poModal').modal('show');
	
	document.getElementById('pdfViewer').innerHTML = '<object data="' + filePath + '" type="application/pdf" width="100%" height="100%"></object>';
      document.getElementById('pdfModal').style.display = 'flex';
  }

  function viewCheck(filePath) {
    window.open(filePath, '_blank');
  }
</script>
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
  <div id="popup" class="popup" style="display: none;margin-top:-735px;margin-left:1050px;">
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
    const inputIds = ['Contact_Person_Name', 'txt_Contact_Person_Name'];

    inputIds.forEach(function(id) {
      const input = document.getElementById(id);

      if (input) { // Check if the element exists
        input.addEventListener("keydown", function(event) {
          const key = event.key;
          const regex = /[a-zA-Z]/;
          if (!regex.test(key) && key !== "Backspace" && key !== "Delete") {
            event.preventDefault();
          }
        });
      } else {
        console.error(`Element with ID '${id}' not found.`);
      }
    });


    const numericInputIds = ['txt_Contact_Person_Mob_No', 'txt_No_of_Vehicles', 'txt_GST', 'Contact_Person_Mob_No', 'No_of_Vehicles', 'GST'];

    numericInputIds.forEach(function(id) {
      const input = document.getElementById(id);

      if (input) { // Check if the element exists
        input.addEventListener("keydown", function(event) {
          const key = event.key;
          const regex = /[0-9]/;
          if (!regex.test(key) && key !== "Backspace" && key !== "Delete") {
            event.preventDefault();
          }
        });
      } else {
        console.error(`Element with ID '${id}' not found.`);
      }
    });
  </script>
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
          document.getElementById('txt_Company_Name').value = res[0];
          document.getElementById('txt_Contact_Person_Name').value = res[1];
          document.getElementById('txt_Contact_Person_Mob_No').value = res[2];
          document.getElementById('txt_Email_ID').value = res[3];
          document.getElementById('txt_Campaing_Details').value = res[4];
          document.getElementById('txt_Start_date').value = res[5];
          document.getElementById('txt_Spl_Request').value = res[6];
          document.getElementById('txt_Media_Type').value = res[7];
          document.getElementById('txt_No_of_Vehicles').value = res[8];
          document.getElementById('txt_GST').value = res[9];

          document.getElementById('txt_po_id').value = res[10];

          document.getElementById('txt_check_id').value = res[11];

          document.getElementById('txt_id').value = res[12];

        }
      };
      xhttp.open("GET", "edit_installationInfo.php?id=" + val, true);
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