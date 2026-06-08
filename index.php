<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
session_start();
include('db_connect.php');

$emailform = true;
$otpform = false;

// Ensure PHPMailer is available
require "Mail/phpmailer/PHPMailerAutoload.php";

// Email form submission
if (isset($_POST["email"])) {
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $Director = isset($_POST["user_level_Director"]) ? htmlspecialchars(trim($_POST["user_level_Director"])) : '';

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Invalid email format");</script>';
    } else {
        // Prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['mail'] = $email;
            $_SESSION['Director'] = $Director;

            $mail = new PHPMailer;

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';

            $mail->Username = 'erp@brandonwheelz.com';
            $mail->Password = 'bytj wubr exys ggad'; // Consider moving this to a secure .env file

            $mail->setFrom('erp@brandonwheelz.com', 'OTP Verification');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "Your verification code";
            $mail->Body = "<p>Dear user, </p> <h3>Your verification OTP code is $otp <br></h3><br><br>";

            if (!$mail->send()) {
                echo '<script>alert("Failed to send OTP, please try again.");</script>';
            } else {
                echo '<script>alert("OTP sent to ' . htmlspecialchars($email) . '");</script>';
                $emailform = false;
                $otpform = true;
            }
        } else {
            echo '<script>alert("Email not registered.");</script>';
        }

        $stmt->close();
    }
}

// OTP verification
if (isset($_POST["verify"])) {
    if (!isset($_SESSION['otp']) || !isset($_SESSION['mail'])) {
        header("Location: index.php");
        exit();
    }

    $email = $_SESSION['mail'];
    $otp = $_SESSION['otp'];
    $input_otp = trim($_POST['otp_code']);
    $Director = $_SESSION['Director'];

    if ($otp != $input_otp) {
        echo '<script>alert("Invalid OTP Code");</script>';
        $otpform = true;
        $emailform = false;
    } else {
        // Get user_level from DB securely
        $stmt = $conn->prepare("SELECT user_level FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($user_level);
        $stmt->fetch();
        $stmt->close();

        if (!empty($user_level)) {
            $level_to_use = $Director !== "" ? $Director : $user_level;
            $_SESSION['user_level'] = $level_to_use;
            $_SESSION['username'] = $email;

            $dashboard_urls = [
                'Admin' => 'http://brandonwheelz.in/Admin/admin_dashboard.php',
                'Director' => 'http://brandonwheelz.in/Admin/admin_dashboard.php',
                'Sales' => 'http://brandonwheelz.in/Sales/sales_dashboard.php',
                'CEO' => 'http://brandonwheelz.in/CEO/ceo_dashboard.php',
                'Graphics' => 'http://brandonwheelz.in/Graphics/graph_dashboard.php',
                'Operations' => 'http://brandonwheelz.in/Operations/ops_dashboard.php',
                'Accounts' => 'http://brandonwheelz.in/Accounts/accounts_dashboard.php',
                'HOD_Sales' => 'http://brandonwheelz.in/sales_hod/hod_dashboard.php'
            ];

            if (array_key_exists($level_to_use, $dashboard_urls)) {
                header("Location: " . $dashboard_urls[$level_to_use]);
                exit();
            } else {
                echo '<script>alert("Invalid user level");</script>';
            }
        } else {
            echo '<script>alert("User not found.");</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <style>
        .left-column {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .right-column {
            /* padding: 200px 50px 50px 50px; */
        }

        .navbar-laravel {
            background: white;
            color: #FFFFFF;
        }

        .navbar-brand {
            color: #007bff;
            font-weight: bold;
        }

        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 500px;
        }

        .card-header {
            background-color: #B52F32;
            color: #ffffff;
            font-weight: bold;
        }

        .form-control {
            border-radius: 0;
        }

        .card-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"] {
            border: 1px solid navy;
        }

        input[type="submit"] {
            background-color: #B52F32;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #b0e0e6;
        }

        .mr-auto {
            margin-right: auto !important;
        }

        .bwz_logo {
            width: 218px;
            max-width: 100%;
            height: auto;
        }

        footer {
            text-align: center;
            padding: 3px;
            background-color: #fff;
            color: #3a403a;
            font-size: 15px;
        }

        input[type=text] {
            width: 270px;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        @media (max-width: 992px) {
            .right-column {
                padding: 50px 20px;
            }

            .login_img {
                height: 300px;
                margin-top: 20px;
            }
        }

        @media (max-width: 768px) {
            .left-column,
            .right-column {
                padding: 20px;
            }

            .left-column img {
                margin-top: 0;
            }

            .right-column {
                margin-top: 20px;
            }
        }

        @media (max-width: 576px) {
            .card {
                width: 100%;
            }

            .right-column {
                padding: 20px 10px;
            }
        }
    </style>
    <title>Login Page</title>
</head>

<body style="background: linear-gradient(327deg, #ff0000a3, #FFFFFF), url('images/bwz_erp_bg.jpg'); background-position: center center;">
    <nav class="navbar navbar-expand-lg navbar-light navbar-laravel">
        <div class="container d-flex align-items-center">
            <a class="navbar-brand mr-auto" href="#">
                <img class="bwz_logo" src="images/bwz1.png" alt="BWZ Logo">
            </a>
        </div>
    </nav>
    <hr>
    <div class="container-fluid">
        <div class="row">
            <!-- Left Column with Image -->
            <div class="col-lg-6 left-column">
                <!-- Add an image or any content here if needed -->
            </div>
            <!-- Right Column with Form -->
            <div class="col-lg-6 right-column" style="margin-bottom: -18%;">
                <main class="login-form" id="email-form" <?php if (!$emailform) echo 'style="display:none"'; ?>>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">ERP Login</div>
                                    <div class="card-body">
                                        <form action="#" method="POST" name="register">
                                            <div class="form-group row">
                                                <label for="email_address" class="col-md-4 col-form-label text-md-right" style="color: #B52F32;margin-top:15px;">E-Mail Address</label>
                                                <div class="col-md-6">
                                                    <input type="text" id="email_address" class="form-control" name="email" placeholder="Enter your email id" onkeyup="checkLogin()" required>
                                                </div>
                                            </div>
											  <div class="form-group row" id="user_Director" style="display:none;">
                                                <label for="email_address" class="col-md-4 col-form-label text-md-right" style="color: #b52f32;margin-top:15px;">User Level</label>
                                                <div class="col-md-6">
                                                    <select class="form-control" name="user_level_Director" id="user_level_Director" style="margin-top: -32px;margin-left: 162px;">
                                                        <option value="">Select</option>
                                                        <option value="Director">Director</option>
                                                        <option value="Accounts">Accounts</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 offset-md-4">
                                                <input type="submit" value="Send OTP" name="register">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <main class="login-form" id="otp-form" <?php if (!$otpform) echo 'style="display:none"'; ?> style="position:absolute;margin-top:160px;right:10%;">
                <div class="container" id="verify">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header bar verification-header">OTP Verification</div>
                                <div class="card-body">
                                    <form action="#" method="POST">
                                        <div class="form-group row">
                                            <label for="otp" class="col-md-4 col-form-label text-md-right" style="color: #B52F32;margin-top:15px;">OTP Code</label>
                                            <div class="col-md-6">
                                                <input type="text" id="otp" class="form-control" name="otp_code" placeholder="Enter OTP" required autofocus>
                                            </div>
                                        </div>
                                        <div class="col-md-6 offset-md-4 ">
                                            <input type="submit" value="Verify" name="verify">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <footer style="margin-top: 568px;">
        <p>© 2024. All rights reserved by Brand On Wheelz</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
	<script>
	 function checkLogin() {
         //   alert();
            var email_address = document.getElementById("email_address").value;

            if (email_address == "levis@brandonwheelz.com") {
                document.getElementById("user_Director").style.display = "block";
                document.getElementById("user_level_Director").required = true;

			}else {
                document.getElementById("user_Director").style.display = "none";
                document.getElementById("user_level_Director").required = false;
     
            }
        }
	</script>
</body>

</html>
