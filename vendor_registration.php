<?php

require_once("db_connect.php");

if ($_POST['adduser']) {

  $date = $_POST['date'];
  $vendor_name = $_POST['vendor_name'];
  $phone_number = $_POST['phone_number'];
  $gst_no = $_POST['gst_no'];
  $pan_no = $_POST['pan_no'];
  $tally_name = $_POST['tally_name'];
  $business_type = $_POST['business_type'];
  $credit_period = $_POST['credit_period'];
  $credit_limit = $_POST['credit_limit'];
  // $purchase_type = $_POST['purchase_type'];
  $primary_name = $_POST['primary_name'];
  $vendor_type = $_POST['vendor_type'];
  $address = $_POST['address'];
  $city = $_POST['city'];
  $state = $_POST['state'];
  $country = $_POST['country'];
  $zip = $_POST['zip'];
  $contact_name = $_POST['contact_name'];
  $contact_email = $_POST['contact_email'];
  $contact_phone = $_POST['contact_phone'];
  $contact_mobile = $_POST['contact_mobile'];
  // $contact_fax = $_POST['contact_fax'];

  $targetFile_msme_form = basename($_FILES["msme_form"]["name"]);
  $allowedFileTypes_msme_form = array("pdf");
  $fileExtension_msme_form = strtolower(pathinfo($targetFile_msme_form, PATHINFO_EXTENSION));

  $targetFile_cancel_chq = basename($_FILES["cancel_chq"]["name"]);
  $allowedFileTypes_cancel_chq = array("pdf");
  $fileExtension_cancel_chq = strtolower(pathinfo($targetFile_cancel_chq, PATHINFO_EXTENSION));

  $targetFile_agreement_form = basename($_FILES["agreement_form"]["name"]);
  $allowedFileTypes_agreement_form = array("pdf");
  $fileExtension_agreement_form = strtolower(pathinfo($targetFile_agreement_form, PATHINFO_EXTENSION));

  if ((!in_array($fileExtension_msme_form, $allowedFileTypes_msme_form)) && (!in_array($fileExtension_cancel_chq, $allowedFileTypes_cancel_chq)) && (!in_array($fileExtension_agreement_form, $allowedFileTypes_agreement_form))) {


    echo '<script>alert("Sorry, Only PDF files are allowed.")</script>';
  } else {

    $file_name_msme_form = $_FILES['msme_form']['name'];
    $upload_dir_msme_form = 'MSME/'; // Directory to store uploaded files
    $file_path_msme_form = $upload_dir_msme_form . $file_name_msme_form;
    move_uploaded_file($_FILES['msme_form']['tmp_name'], $file_path_msme_form);

    $file_name_cancel_chq = $_FILES['cancel_chq']['name'];
    $upload_dir_cancel_chq = 'CHQ/'; // Directory to store uploaded files
    $file_path_cancel_chq = $upload_dir_cancel_chq . $file_name_cancel_chq;
    move_uploaded_file($_FILES['cancel_chq']['tmp_name'], $file_path_cancel_chq);

    $file_name_agreement_form = $_FILES['agreement_form']['name'];
    $upload_dir_agreement_form = 'AGRM/'; // Directory to store uploaded files
    $file_path_agreement_form = $upload_dir_agreement_form . $file_name_agreement_form;
    move_uploaded_file($_FILES['agreement_form']['tmp_name'], $file_path_agreement_form);

    function generateVendorID()
    {
      $prefix = 'BWZ';

      $number = rand(1000, 99999);

      $vendorID = $prefix . '-' . $number;

      return $vendorID;
    }

    $current_timestamp = time();

    $date = date('d/m/Y', $current_timestamp);

    $time = date('H:i', $current_timestamp);

    $timestamp = $date . ' ' . $time;

    $vendorID = generateVendorID();


    $stmt_insert = "INSERT INTO vendor_reg(date, vendor_name,vendor_id,vendor_form_sub_date, phone_no, GST_no, pan_no, name_used_tally, type_of_business, Credit_Period, Credit_Limit, Primary_Name, MSME_Form, Cancel_Chq, Agreement, Type_of_Vendor, Address, City, State, Country, Zip, Contact_Name, E_mail, Phone, Mobile, Fax) 
VALUES ('$date','$vendor_name','$vendorID','$timestamp','$phone_number','$gst_no','$pan_no','$tally_name','$business_type','$credit_period','$credit_limit','$primary_name','$file_name_msme_form','$file_name_cancel_chq','$file_name_agreement_form','$vendor_type','$address','$city','$state','$country','$zip','$contact_name','$contact_email','$contact_phone','$contact_mobile','$contact_fax');";
    $rslt_insert = mysqli_query($conn, $stmt_insert);

    // echo '<script>alert("Vendor Registration Successfully Completed.")</script>';

    // After submit the form Send mail to RM
    require "Mail/phpmailer/PHPMailerAutoload.php";
    $mail = new PHPMailer;

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';

    $mail->Username = 'erp@brandonwheelz.com'; // Replace with your email
    $mail->Password = 'bytj wubr exys ggad'; // Replace with your email password

    $mail->setFrom('erp@brandonwheelz.com', 'ERP');
    $adminEmail = "$contact_email"; // Replace with the desired email address
    $mail->addAddress($adminEmail);

    $mail->isHTML(true);
    $mail->Subject = "Vendor Registration Details";
    $mail->Body = "<p>Dear $vendor_name Team,</p>
               <p>Thank you for choosing BWZ for advertising your brand.</p>
               <p>This is To acknowledge that your vendor code <b> $vendorID </b>has been generated .</p>
               <p> Please Contact Your Relationship Manager and Quote this vendor code in Future.Welcome   to Brand On Wheelz Family.</p>
               <p>Best regards,<br>BWZ Team</p>";

    $mail->send();



    /** mail vendor details to ops team */


    $stmt_ops = "SELECT email,user_name from users where user_level='Operations';";
    $rslt_ops = mysqli_query($conn, $stmt_ops);
    $row_ops = mysqli_fetch_row($rslt_ops);
    $ops_email = $row_ops[0];
    $ops_user = $row_ops[1];

    $mail1 = new PHPMailer;

    $mail1->isSMTP();
    $mail1->Host = 'smtp.gmail.com';
    $mail1->Port = 587;
    $mail1->SMTPAuth = true;
    $mail1->SMTPSecure = 'tls';

    $mail1->Username = 'erp@brandonwheelz.com';
    $mail1->Password = 'bytj wubr exys ggad';

    $mail1->setFrom('erp@brandonwheelz.com', 'ERP');
    $adminEmail = "$ops_email";
    $mail1->addAddress($adminEmail);

    $mail1->isHTML(true);
    $mail1->Subject = "New Order PO Request";
    $mail1->Body = "<p>Dear $ops_user, </p> <h3>new vendor has been Onboarded Successfully link below link to know more.<br></h3>
                     Click the below link for login<br>
					 http://brandonwheelz.in/index.php<br>
                     <p></p>
                     <b></b>";

    $mail1->send();

    header("Location: http://brandonwheelz.in/thank_you.html");
  }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
  <meta name="viewport" content="width=1500">
  <title>Vendor Registration</title>

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
      /* background-color: #fff; */
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
<!-- images/bwz_erp_bg.jpg -->
<body class="hold-transition sidebar-mini" id="sampleDiv_zoom" style="background: linear-gradient(327deg, #ff0000a3, #FFFFFF), url(''); background-size: cover; background-position: center center;">
  <div class="wrapper">
    <!-- Navbar -->
    <!-- Image and text -->
    <nav class="navbar navbar-light" style="background-color:white;">
      <a class="navbar-brand" href="#">
        <img src="images/bwz1.png" width="180" height="70" class="d-inline-block align-top" alt="" style="width: 243px;">

      </a>
    </nav>
    <!-- /.navbar -->
    <hr>
    <div>
      <div style="text-align:center;font-family:Roboto, Sans-serif;text-decoration: underline;">
        <h3 class="modal-title" style="color:#B52F32;font-weight: bold;">VENDOR REGISTRATION FORM</h3>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data"style="text-align: -webkit-center;">
          <div class="modal-header" style="text-align:center;font-family:Roboto, Sans-serif;justify-content: center;">
            <h4 class="modal-title" style="color:#B52F32;">Vendor Authorization/Change Form</h4>
          </div>
          <table>
            <tr>
              <th>
                <div class="form-group" style="width:300px;">
                  <label for="UserID">Date<span style="color:red;">*</span></label>
                  <input type="date" class="form-control" id="date" name="date" required>
                </div>
              </th>
              <th>
                <div class="form-group" style="width:300px;">
                  <label for="UserID">Company Name<span style="color:red;">*</span></label>
                  <input type="text" class="form-control" id="vendor_name" name="vendor_name" placeholder="Enter Vendor Name" required>
                </div>
              </th>
              <th>
                <div class="form-group" style="width:300px;">
                  <label for="UserID">Company Phone Number<span style="color:red;">*</span></label>
                  <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Enter Vendor Phone Number" required>
                </div>
              </th>
              <th>
                <!-- <div class="form-group" style="width:300px;">
                  <label for="UserID">GST No<span style="color:red;">*</span></label>
                  <input type="text" class="form-control" id="gst_no" name="gst_no" placeholder="Enter GST Number" required>
                </div> -->
                <div class="form-group" style="width:300px;">
                  <label for="gst_no">GST No</label>
                  <input type="text" class="form-control" id="gst_no" name="gst_no" placeholder="Enter GST Number">
                </div>

              </th>
              <th>
                <!-- <div class="form-group" style="width:300px;">
                  <label for="UserID">PAN No<span style="color:red;">*</span></label>
                  <input type="text" class="form-control" id="pan_no" name="pan_no" placeholder="Enter PAN Number" required>
                </div> -->
                <div class="form-group" style="width:300px;">
                  <label for="pan_no">PAN No<span style="color:red;">*</span></label>
                  <input type="text" class="form-control" id="pan_no" name="pan_no" placeholder="Enter PAN Number" required>
                </div>
              </th>
            </tr>

            <tr>
              <th style="display:none;">
                <div class="form-group" style="width:300px;">
                  <label for="UserID">Name used by Tally<span style="color:red;">*</span></label>
                  <input type="text" class="form-control" id="tally_name" name="tally_name" placeholder="Enter Name Used For Tally">
                </div>
              </th>
              <th>
                <div class="form-group" style="width:300px;">
                  <label for="UserID">Type of Business<span style="color:red;">*</span></label>
                  <select class="form-control select2" style="width: 100%;" id="business_type" name="business_type" onchange="checkLevel()" required>
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
                <div class="form-group" style="width:300px;">
                  <label for="UserID">Credit Period<span style="color:red;">*</span></label>
                  <input type="text" class="form-control" id="credit_period" name="credit_period" placeholder="Enter Credit Period" required>
                </div>
              </th>
              <th>
                <div class="form-group" style="width:300px;">
                  <label for="UserID">Credit Limit<span style="color:red;">*</span></label>
                  <input type="text" class="form-control" id="credit_limit" name="credit_limit" placeholder="Enter Credit Limit" required>
                </div>
              </th>
              <!-- <th>
                <div class="form-group" style="width:300px;">
                  <label for="UserID">Type of Purchase/Payment<span style="color:red;">*</span></label>
                  <select class="form-control select2" style="width: 100%;" id="purchase_type" name="purchase_type" required>
                    <option value="">Select</option>
                    <option value="Goods">Goods</option>
                    <option value="Consultant">Consultant</option>
                    <option value="Transporter">Transporter</option>
                    <option value="Services">Services</option>
                    <option value="Auditor">Auditor</option>
                    <option value="Other">Others</option>

                  </select>
                </div>
              </th> -->
            </tr>

          </table>
          <div class="modal-header" style="text-align:center;font-family:Roboto, Sans-serif;justify-content: center;">
            <h4 class="modal-title" style="color:#B52F32;">Branding Work</h4>
          </div>
          <table>
            <tr>
              <th style="display:none;">
                <div class="form-group" style="width:300px;">
                  <label for="UserID">Primary Name <span style="color:red;">*</span></label>
                  <!-- <input type="text" class="form-control" id="primary_name" name="primary_name" placeholder="Enter Primary Name" required> -->
                  <!-- <input type="text" id="primary_name" name="primary_name" inputmode="text" required> -->
                  <input type="text" class="form-control" id="primary_name" name="primary_name" inputmode="text" placeholder="Enter Country Name">

                  <!-- <input type="text" class="form-control" id="country" name="country" placeholder="Enter Country Name" required> -->

                </div>
              </th>
              <th>
                <div class="form-group" style="width:300px;">
                  <label for="UserID">MSME Certificate Form(PDF)<span style="color:red;"></span></label>
                  <input type="file" class="form-control" id="msme_form" name="msme_form">
                </div>
              </th>
              <th>
                <div class="form-group" style="width:300px;">
                  <label for="UserID">Cancel Chq(PDF)<span style="color:red;">*</span></label>
                  <input type="file" class="form-control" id="cancel_chq" name="cancel_chq" required>
                </div>
              </th>
              <th>
                <div class="form-group" style="width:300px;">
                  <label for="UserID">Agreement(PDF)<span style="color:red;">*</span></label>
                  <input type="file" class="form-control" id="agreement_form" name="agreement_form" required>
                </div>
              </th>
              <th>
                <div class="form-group" style="width:300px;">
                  <label for="UserID">Type of Vendor<span style="color:red;">*</span></label>
                  <select class="form-control select2" style="width: 100%;" id="vendor_type" name="vendor_type" required>
                    <option value="">Select</option>
                    <option value="Fleet">Fleet</option>
                    <option value="Mounting">Mounting</option>
                    <option value="Printing">Printing</option>
                    <option value="Auto Hood Manufaturer">Auto Hood Manufaturer</option>
                    <option value="Auto Hood Installer">Auto Hood Installer</option>
                    <option value="Other">Others</option>

                  </select>
                </div>
              </th>
              <th>
                <div class="form-group" style="width:300px;">
                  <label for="UserID">Address<span style="color:red;">*</span></label>
                  <textarea class="form-control" id="address" name="address" placeholder="Enter Full Address" required></textarea>
                </div>
              </th>
            </tr>
            <tr>

              <th>
                <div class="form-group" style="width:300px;">
                  <label for="UserID">City<span style="color:red;">*</span></label>
                  <input type="text" class="form-control" id="city" name="city" placeholder="Enter City Name" required>
                </div>
              </th>
              <th>
                <div class="form-group" style="width:300px;">
                  <label for="UserID">State<span style="color:red;">*</span></label>
                  <input type="text" class="form-control" id="state" name="state" placeholder="Enter State Name" required>
                </div>
              </th>
              <th>
                <div class="form-group" style="width:300px;">
                  <label for="UserID">Country<span style="color:red;">*</span></label>
                  <input type="text" class="form-control" id="country" name="country" placeholder="Enter Country Name" required>
                </div>
              </th>
              <th>
                <div class="form-group" style="width:300px;">
                  <label for="UserID">Zip<span style="color:red;">*</span></label>
                  <input type="text" class="form-control" id="zip" name="zip" placeholder="Enter Zip Code" required>
                </div>
              </th>
              <th>
                <div class="form-group" style="width:300px;">
                  <label for="UserID">Contact Name<span style="color:red;">*</span></label>
                  <!-- <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Enter Contact Person Name" required> -->
                  <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Enter Name" required>
                </div>
              </th>
            </tr>

            <tr>

              <th>
                <div class="form-group" style="width:300px;">
                  <label for="UserID">E-mail<span style="color:red;">*</span></label>
                  <input type="email" class="form-control" id="contact_email" name="contact_email" placeholder="Enter Contact Person Email Id" required>
                </div>
              </th>
              <th>
                <div class="form-group" style="width:300px;">
                  <label for="UserID">Primary Mobile<span style="color:red;">*</span></label>
                  <input type="text" class="form-control" id="contact_phone" name="contact_phone" placeholder="Enter Contact Person Phone Number" required>
                </div>
              </th>
              <th>
                <div class="form-group" style="width:300px;">
                  <label for="UserID">Alt Mobile</label>
                  <input type="text" class="form-control" id="contact_mobile" name="contact_mobile" placeholder="Enter Mobile Number">
                </div>
              </th>
              <!--<th>-->
              <!--  <div class="form-group" style="width:300px;">-->
              <!--    <label for="UserID">Digital Signature<span style="color:red;">*</span></label>-->
              <!--    <input type="file" class="form-control" id="digi_sig" name="digi_sig" required>-->
              <!--  </div>-->
              <!--</th>-->
              <!-- <th>
                <div class="form-group" style="width:300px;">
                  <label for="UserID">Fax<span style="color:red;">*</span></label>
                  <input type="text" class="form-control" id="contact_fax" name="contact_fax" placeholder="Enter Fax Number" required>
                </div>
              </th> -->
            </tr>

          </table>
          <div class="card-footer">
            <input type="submit" class="btn btn-success" name="adduser" value="Submit" style="color:white;height:50px;width:150px;">
            <input type="reset" class="btn btn-warning" name="reset" value="Reset" style="color:white;height:50px;width:150px;">
          </div>
        </form>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
      </div>


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
  <script>
    document.getElementById('gst_no').addEventListener('input', function(event) {
      var gstPattern = /^[0-9]{0,2}$|^[0-9]{2}[A-Z]{0,5}$|^[0-9]{2}[A-Z]{5}[0-9]{0,4}$|^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{0,1}$|^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{0,1}$|^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z$|^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{0,1}$/;
      var gstNumber = event.target.value;

      if (!gstPattern.test(gstNumber)) {
        event.target.value = gstNumber.slice(0, -1);
        document.getElementById('error').innerText = 'Invalid character entered for GST Number.';
      } else {
        document.getElementById('error').innerText = '';
      }
    });

    document.getElementById('pan_no').addEventListener('input', function(event) {
      var panPattern = /^[A-Z]{0,5}$|^[A-Z]{5}[0-9]{0,4}$|^[A-Z]{5}[0-9]{4}[A-Z]{0,1}$/;
      var panNumber = event.target.value;

      if (!panPattern.test(panNumber)) {
        event.target.value = panNumber.slice(0, -1);
        document.getElementById('error').innerText = 'Invalid character entered for PAN Number.';
      } else {
        document.getElementById('error').innerText = '';
      }
    });

    document.getElementById('numberForm').addEventListener('submit', function(event) {
      var gstNumber = document.getElementById('gst_no').value;
      var panNumber = document.getElementById('pan_no').value;
      var gstPattern = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;
      var panPattern = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;

      if (!gstPattern.test(gstNumber)) {
        document.getElementById('error').innerText = 'Invalid GST Number format.';
        event.preventDefault(); // Prevent form submission
      } else if (!panPattern.test(panNumber)) {
        document.getElementById('error').innerText = 'Invalid PAN Number format.';
        event.preventDefault(); // Prevent form submission
      } else {
        document.getElementById('error').innerText = ''; // Clear error message
      }
    });
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const gstField = document.getElementById('gst_no');
      const panField = document.getElementById('pan_no');

      gstField.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
      });

      panField.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
      });
    });
    // const nameInput = document.getElementById("name");

    // nameInput.addEventListener("keydown", function(event) {
    //   const key = event.key;
    //   const regex = /[0-9]/;
    //   if (regex.test(key) && key !== "Backspace" && key !== "Delete") {
    //     event.preventDefault();
    //   }
    // });
    function checkLevel() {
      var type = document.getElementById("business_type").value;

      if (type === "Corporation(Company)" ||
        type === "Partnership" ||
        type === "Limited Liability Company" ||
        type === "Government Entity" ||
        type === "Non Profit/501(c) Entity" ||
        type === "Employee") {
        document.getElementById("gst_no").required = true;
        document.getElementById("pan_no").required = true;
      } else {
        document.getElementById("gst_no").required = false;
        document.getElementById("pan_no").required = false;
      }
    }
    // Additionally, handle pasting events (optional)
    // nameInput.addEventListener("paste", function(event) {
    //   const pastedText = event.clipboardData.getData("text");
    //   const regex = /[0-9]/;
    //   if (regex.test(pastedText)) {
    //     event.preventDefault();
    //   }
    // });
    const inputIds = ['contact_name', 'country', 'vendor_name', 'tally_name', 'primary_name', 'city', 'state'];

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
    const numericInputIds = ['contact_fax', 'contact_mobile', 'contact_phone', 'zip', 'phone_number'];

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