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
          <h2 style="margin-left:50px;color: #b52f34;"><b><i class="fa fa-align-center" aria-hidden="true"></i> Payment Report</b></h2>
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
          <li><a href="admin_dashboard.php"><i class="fa fa-th-large" aria-hidden="true"></i> Dashboard</a></li><br><br>
          <li><a href="user.php"><i class="fa fa-users" aria-hidden="true"></i> User</a></li><br><br>
         <?php
          if ($loggedInUserLevel == "Director") {
          ?>
		  
		  <li><a href="ops_details.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i> New Sales Info</a></li><br><br>
          <li><a href="ops_vendor_details.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i> New Vendor Info</a></li><br><br>
		  <li><a href="director_payment_request.php"><i class="fa fa-check-circle" aria-hidden="true"></i> Payments Request</a></li><br><br>
		   <?php
          } else {
          } ?>
         
         <li style="font-weight:bold;color: #ffff;"> Sales Reports</li><br><br>
          <li><a href="sales_report.php" class="menu-link"><i class="fa fa-align-center " aria-hidden="true"></i> Sales</a></li><br><br>
          <li style="font-weight:bold;color: #ffff;"> Operation Reports</li><br><br>
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
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

              <table class="table table-striped projects table-bordered" id="">
                <tr>

                  <td><label>Start Date</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="date" id="startDate" name="startDate">
                  </td>
                  <td><label>End Date</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="date" id="endDate" name="endDate">
                  </td>
                  <td>
                    <label for="cars">Status</label>
                    <select name='status[]' multiple class='formselect' id="status">

                      <option value="Requested">Requested</option>
                      <option value="Received">Received</option>
                    </select>
                  </td>
                  <td>
                    <input type="submit" name="btn_submit" class="btn btn-success">
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
                <label style="font-size: medium;font-weight: 400;">Date Range :&nbsp; &nbsp; <?php echo "$start_date 00:00:00"; ?>&nbsp; &nbsp; to &nbsp; &nbsp;<?php echo "$end_date 23:59:59"; ?></label>

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
                           Date Time
                          </th>
                          <th class="col-xs-2" style="text-align: center;">
                            Vendor name
                          </th>
                          <th class="col-xs-2" style="text-align: center;">
                            Type of Vendor
                          </th>

                          <th class="col-xs-2" style="text-align: center;">
                            Campaign code
                          </th>
                          <th class="col-xs-2" style="text-align: center;">
                            Invoice
                          </th>
							<th class="col-xs-2" style="text-align: center;">
                           Status
                          </th>
						

                        </tr>
                      </thead>
                      <tbody>
                        <?php


                        require_once("db_connect.php");
                        if (isset($_POST["btn_submit"]) == "btn_submit") {

                          $start_date = $_POST['startDate'];
                          $end_date = $_POST['endDate'];
                          if ($start_date != "" && $end_date != "") {

                            foreach ($_POST['status'] as $select_status) {

                              if ($select_status == "Received") {
                                $statusSQL = "status='Received'";
                              } else if ($select_status == "Requested") {
                                $statusSQL = "status='Requested'";
                              }
                              ///////////////////////////////////////////////////
                              $stmt_login = "SELECT * from payment_request where $statusSQL  AND date_time >='$start_date 00:00:00' AND date_time <='$end_date 23:59:59' ORDER BY DATE(date_time) ASC;";
                              //echo $stmt_login;
                              $rslt_login = mysqli_query($conn, $stmt_login);

                              $x = 1;
                              while ($row = mysqli_fetch_assoc($rslt_login)) {

                        ?>
                                <tr>
									<td class="col-xs-2" style="text-align: center;"><?php echo $row["date_time"]; ?></td>
                                  <td class="col-xs-2" style="text-align: center;"><?php echo $row["vendor_name"]; ?></td>
                                  <td class="col-xs-2" style="text-align: center;"><?php echo $row["type_of_vendor"]; ?></td>
								<td class="col-xs-2" style="text-align: center;"><?php echo $row["campaign_code"]; ?></td>
                                  <td class="col-xs-2" style="text-align: center;"><?php echo $row["invoice"]; ?></td>
                                  <td class="col-xs-2" style="text-align: center;"><?php echo $row["Status"]; ?></td>
							

                              <?php
                                $x++;
                              }
                            }
                              ?>

                            <?php
                          } else { ?> <label style="color:red;font-size:medium;font-weight:700;text-align:center;">Please Select Date Range And Status..</label> <br>

                          <?php }
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

  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>

  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

  <script>
    $(function() {

      var date = "<?php echo $TodayDate; ?>";
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();

      today = dd + '-' + mm + '-' + yyyy;

      $('#table_camp').DataTable({
        "lengthMenu": [
          [10, 25, 50, -1],
          [10, 25, 50, "All"]
        ],
        "dom": "1Bfrtip",
        buttons: [
          'pageLength',

          {
            extend: 'csv',
            title: 'PO_Report_' + date

          }


        ]
      });

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