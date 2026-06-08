<?php

require_once("db_connect.php");
session_start();

$loggedInuserName  = $_SESSION['username'];
$loggedInUserLevel = $_SESSION['user_level'];

$stmt_rs = "SELECT user_name from users where email='$loggedInuserName';";
$rslt_rs = mysqli_query($conn, $stmt_rs);
$row_rs = mysqli_fetch_row($rslt_rs);
$usename = $row_rs[0];


if ($_POST['adduser']) {

  $user_name = $_POST['user_name'];
  $user_email = $_POST['user_email'];
  $User_level = $_POST['User_level'];

  $stmt_rs = "SELECT id from users where email='$user_email';";
 // echo $stmt_rs;
  $rslt_rs = mysqli_query($conn, $stmt_rs);
 $row_rs = mysqli_num_rows($rslt_rs);

  if ($row_rs ==0 || $row_rs =="") {

    $date = date("Y-m-d H:i:s");
    $stmt_insert = "INSERT INTO users(created_date,user_name,user_level,email) values('$date','$user_name','$User_level','$user_email')";
  //  echo $stmt_insert; exit;
    $rslt_insert = mysqli_query($conn, $stmt_insert);
	
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
  $adminEmail = "$user_email"; // Replace with the desired email address
  $mail->addAddress($adminEmail);

  $mail->isHTML(true);
  $mail->Subject = "Welcome To BrandOnWheelz Mini ERP!";
  $mail->Body = "<p>Dear User,<br> Welcome to BrandOnWheelz Mini ERP!</p> <h3> The user has been created with the email id $user_email.</h3>
                     <br>Please Click on the below link for login<br>
					https:https://brandonwheelz.in/index.php<br>
                     <p></p>
                     <b></b>";

  $mail->send();
  
  } else {
    echo "<script>alert('User already exist with the same email id.');</script>";
  }
}

if ($_POST['edituser']) {

  $txt_user_name = $_POST['txt_user_name'];
  $txt_user_email = $_POST['txt_user_email'];
  $txt_User_level = $_POST['txt_User_level'];
  $txt_id = $_POST['txt_id'];


  $stmt_update = "update users set user_name='$txt_user_name',user_level='$txt_User_level',email='$txt_user_email' where id='$txt_id'";
  $rslt_update = mysqli_query($conn, $stmt_update);
}

if ($_POST['deleteuser']) {
  $deleteIngroupID = $_POST['deleteIngroupID'];
  $stmt_delete = "delete from users where id='$deleteIngroupID'";

  $rslt_delete = mysqli_query($conn, $stmt_delete);
}

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
      max-width: 177px;
    height: 60px;
    display: block;
    margin: 0 auto;
    margin-top: 10px;
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
</head>

<body class="hold-transition sidebar-mini" id="sampleDiv_zoom">
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="height:90px;">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item d-none d-sm-inline-block">
          <h2 style="margin-left:50px;color: #b52f34;"><b><i class="fa fa-users" aria-hidden="true"></i> Users</b></h2>
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
    <!-- Sidebar content -->
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
      <section class="content">
        <div class="container-fluid">
          <div class="card-footer">

            <button type="submit" id="adduserButton" onclick="showadd_user()" ;><i class="fa fa-user-plus" aria-hidden="true"></i> Add Users</button>

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
                          <th style="width: 20%">
                            User Name
                          </th>
                          <th style="width:20%">
                            User Type
                          </th>

                          <th style="width:20%">
                            Email Id
                          </th>

                          <th style="width:30%">
                            ACTION
                          </th>
                        </tr>
                      </thead>
                      <tbody>

                        <?php
                        $stmt_select = "SELECT * from users";
                        $rslt_rs = mysqli_query($conn, $stmt_select);

                        $x = 1;
                        while ($row = mysqli_fetch_assoc($rslt_rs)) {

                        ?>
                          <tr>
                            <td>
                              <?php echo $x; ?>
                            </td>
                            <td>
                              <a>
                                <?php echo $row["user_name"]; ?>
                              </a>
                            </td>
                            <td>
                              <?php echo $row["user_level"]; ?>
                            </td>

                            <td>
                              <?php echo $row["email"]; ?>
                            </td>


                            <td class="project-actions text-right">
                              <span class="btn btn-warning" onclick="document.getElementById('myModal_edit').style.display='block'; ingroupEditFun('<?php echo $row["id"]; ?>')" style="color:white;">
                                <i class="fas fa-pencil-alt">
                                </i>
                                Edit
                              </span>

                              <span class="btn btn-danger btn-sm" onclick="document.getElementById('myModal_delete').style.display='block'; ingroupDeleteFun('<?php echo $row["id"]; ?>','<?php echo $row["user_name"]; ?>','<?php echo $row["email"]; ?>')">
                                <i class="fas fa-trash">
                                </i>
                                Delete
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
          <div class="modal fade" id="myModal_edit" style="opacity: 3;top : 104px !important">
            <div class="modal-dialog">
              <div class="modal-content" style="top:40px;width: 600px;">

                <!-- Modal Header -->
                <div class="modal-header">
                  <h4 class="modal-title">EDIT USER</h4>
                  <button type="button" class="close1" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <table>
                      <tr>
                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="UserID">Name<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" id="txt_user_name" name="txt_user_name" placeholder="Enter User Name" required>
                          </div>
                        </th>
                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="UserID">Email ID<span style="color:red;">*</span></label>
                            <input type="email" class="form-control" id="txt_user_email" name="txt_user_email" placeholder="Enter User Email" required>
                          </div>
                        </th>
                      </tr>

                      <tr>

                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="UserID">User Type<span style="color:red;">*</span></label>
                            <select class="form-control select2" style="width: 100%;" id="txt_User_level" name="txt_User_level" required>
                              <option value="">Select</option>
                              <option value="Admin">Admin</option>
                              <option value="Director">Director</option>
                              <option value="Sales">Sales</option>
                              <option value="HOD_Sales">Sales HOD</option>

                              <option value="CEO">CEO</option>
                              <option value="Graphics">Graphics</option>
							  <option value="Accounts">Accounts</option>
                              <option value="Operations">Operations</option>

                            </select>
                          </div>
                        </th>
                      </tr>

                    </table>
                    <div class="card-footer">
                      <input type="hidden" value="" id="txt_id" name="txt_id">
                      <input type="submit" class="btn btn-success" name="edituser" value="Update">
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
          <div class="modal fade" id="myuser" style="opacity: 3;top : 104px !important;">
            <div class="modal-dialog">
              <div class="modal-content" style="top:40px; width: 600px; margin: left 20px;">

                <!-- Modal Header -->
                <div class="modal-header">
                  <h4 class="modal-title">ADD USER</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <table>
                      <tr>
                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="UserID">Name<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Enter User Name" required>
                          </div>
                        </th>
                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="UserID">Email ID<span style="color:red;">*</span></label>
                            <input type="email" class="form-control" id="user_email" name="user_email" placeholder="Enter User Email" required>
                          </div>
                        </th>

                        </th>

                      </tr>

                      <tr>

                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="UserID">User Type<span style="color:red;">*</span></label>
                            <select class="form-control select2" style="width: 100%;" id="User_level" name="User_level" required>
                              <option value="">Select</option>
                              <option value="Admin">Admin</option>
                              <option value="Director">Director</option>
                              <option value="Sales">Sales</option>
                              <option value="HOD_Sales">Sales HOD</option>
                              <option value="CEO">CEO</option>
                              <option value="Graphics">Graphics</option>
							  <option value="Accounts">Accounts</option>
                              <option value="Operations">Operations</option>

                            </select>
                          </div>
                        </th>
                      </tr>

                    </table>
                    <div class="card-footer">

                      <input type="submit" class="btn btn-success" name="adduser" value="Submit">
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
                  <h4 class="modal-title">DELETE USER</h4>
                  <button type="button" class="close23" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                  <form action="" method="POST">
                    <table>
                      <tr>
                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="UserID">Name<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" id="delete_user_name" name="delete_user_name" placeholder="Enter User Name" readonly>
                          </div>
                        </th>
                      </tr>
                      <tr>
                        <th>
                          <div class="form-group" style="width:250px;">
                            <label for="UserID">Email ID<span style="color:red;">*</span></label>
                            <input type="email" class="form-control" id="delete_user_email" name="delete_user_email" placeholder="Enter User Email" readonly>
                          </div>
                        </th>
                      </tr>
                    </table>

                    <!-- /.card-body -->
                    <div class="card-footer">
                      <input type="hidden" value="" id="deleteIngroupID" name="deleteIngroupID">
                      <input type="submit" class="btn btn-success" name="deleteuser" value="Delete">
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

  <script>
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

          //alert(res[3]);
          document.getElementById('txt_user_name').value = res[0];
          document.getElementById('txt_user_email').value = res[1];
          document.getElementById('txt_User_level').value = res[2];
          document.getElementById('txt_id').value = res[3];

        }
      };
      xhttp.open("GET", "useredit_data.php?id=" + val, true);
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


    function ingroupDeleteFun(val_grpd, name, email) {
      document.getElementById('deleteIngroupID').value = val_grpd;
      document.getElementById('delete_user_name').value = name;
      document.getElementById('delete_user_email').value = email;

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
      var modal3 = document.getElementById('myuser');

      // Get the <span> element that closes the modal
      var span3 = document.getElementsByClassName("close")[0];

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