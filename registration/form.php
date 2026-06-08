<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Driver KYC Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .kyc-form {
            max-width: 700px;
            margin: 100px auto 40px;
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .kyc-form h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }
        .kyc-form label {
            font-weight: 500;
        }
        .form-check a {
            text-decoration: underline;
        }
        .logo-top {
            position: absolute;
            top: 20px;
            left: 20px;
            height: 60px;
        }
        .verified-badge {
            color: #28a745;
            font-weight: bold;
        }
        .countdown-timer {
            color: #dc3545;
            font-size: 0.875rem;
        }
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
    </style>
</head>
<body>
<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 1030; background-color: rgba(255, 255, 255, 1); padding: 10px; box-shadow: 0 0 8px rgba(0,0,0,0.1);">
    <div class="container">
        <img src="https://brandonwheelz.com/wp-content/uploads/2025/09/BrandOnWheelzLogo-scaled.png" alt="Logo" style="height: 60px;">
    </div>
</div>

<section class="kyc-form">
    <h2>Driver KYC & Onboarding</h2>
    <form action="submit_kyc.php" method="POST" enctype="multipart/form-data" name="kycForm">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Full Name</label>
                <input type="text" name="fullname" class="form-control" data-required="true" />
            </div>
            <div class="col-md-6">
                <label class="form-label">Mobile Number</label>
                <div class="input-group">
                    <input type="tel" name="mobile" id="mobile" class="form-control" data-required="true" maxlength="10" />
                    <button type="button" class="btn btn-outline-primary" id="sendOtpBtn">Send OTP</button>
                </div>
                <div id="otpSection" class="mt-2" style="display:none;">
                    <input type="text" id="otpInput" class="form-control mb-2" placeholder="Enter 6-digit OTP" maxlength="6" />
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <button type="button" class="btn btn-success" id="verifyOtpBtn">Verify OTP</button>
                        <button type="button" class="btn btn-link btn-sm" id="resendOtpBtn" style="display:none;">Resend OTP</button>
                    </div>
                    <div id="otpTimer" class="countdown-timer" style="display:none;"></div>
                    <div id="otpStatus" class="mt-1"></div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email ID</label>
                <input type="email" name="email" class="form-control" data-required="true" />
            </div>
            <div class="col-md-6">
                <label class="form-label">City</label>
                <input type="text" name="city" class="form-control" data-required="true" />
            </div>
            <div class="col-md-6">
                <label class="form-label">Upload Selfie <span class="text-danger">*</span></label>
                <input type="file" name="selfie" accept="image/*" class="form-control" data-required="true" />
            </div>
            <div class="col-md-6">
                <label class="form-label">Aadhaar Card Number</label>
                <input type="text" name="aadhaar" class="form-control" data-required="true" />
            </div>
        </div>

        <div class="form-section">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Area</label>
                    <input type="text" name="area" class="form-control" data-required="true" />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pincode</label>
                    <input type="text" name="pincode" class="form-control" data-required="true" />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Car Model</label>
                    <input type="text" name="car_model" class="form-control" data-required="true"/>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Manufacturing Year</label>
                    <input type="number" name="year" min="1900" max="2025" class="form-control" data-required="true"/>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Upload Vehicle RC <span class="text-danger">*</span></label>
                    <input type="file" name="car_rc" class="form-control" data-required="true" />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Upload Vehicle Photos<span class="text-danger">*</span></label>
                    <input type="file" name="car_photos[]" multiple class="form-control" data-required="true"/>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Vehicle Front View<span class="text-danger">*</span></label>
                    <input type="file" name="front_view" class="form-control" data-required="true" />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Vehicle Back View<span class="text-danger">*</span></label>
                    <input type="file" name="back_view" class="form-control" data-required="true" />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Vehicle Left Side View<span class="text-danger">*</span></label>
                    <input type="file" name="left_side" class="form-control" data-required="true" />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Vehicle Right Side View<span class="text-danger">*</span></label>
                    <input type="file" name="right_view" class="form-control" data-required="true" />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Driving License Image <span class="text-danger">*</span></label>
                    <input type="file" name="license_image" class="form-control" data-required="true" />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Affiliated Platform</label>
                    <select class="form-select" name="platform" data-required="true">
                        <option value="" selected disabled>Select a platform</option>
                        <option>OLA</option>
                        <option>Uber</option>
                        <option>Namma Yatri</option>
                        <option>Rapido</option>
                        <option>Quick Ride</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Shift Preference</label>
                    <select class="form-select" name="shift">
                        <option value="" selected disabled>Select Shift Preference</option>
                        <option>Morning</option>
                        <option>Night</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Fleet Type</label>
                    <select class="form-select" name="fleet_type">
                        <option value="" selected disabled>Select Fleet Type</option>
                        <option>Cab</option>
                        <option>Auto</option>
                        <option>Truck</option>
                        <option>Bike</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-check my-4">
            <input class="form-check-input" type="checkbox" data-required="true" />
            <label class="form-check-label">
                I accept the <a href="#">Digital Agreement</a>
            </label>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">Register</button>
        </div>
    </form>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // OTP Configuration
    const OTP_CONFIG = {
        API_KEY: '10A51-56AEF', 
        USERNAME: 'haloocom',
        SENDER: 'Halcom', 
        ROUTE: 'ServiceImplicit',
        TEMPLATE_ID: '1707175740149131639',
        SEND_OTP_URL: 'send_otp.php', // Use your own backend script
        VERIFY_OTP_URL: 'verify_otp.php', // Use your own backend script
        OTP_LENGTH: 6,
        OTP_VALIDITY_MINUTES: 5,
        RESEND_TIMER_SECONDS: 30
    };

    let otpTimer;
    let resendTimer;
    let otpVerified = false;

    document.addEventListener('DOMContentLoaded', function() {
        initializeOTPHandlers();
    });

    function initializeOTPHandlers() {
        const sendOtpBtn = document.getElementById('sendOtpBtn');
        const verifyOtpBtn = document.getElementById('verifyOtpBtn');
        const resendOtpBtn = document.getElementById('resendOtpBtn');
        const mobileInput = document.getElementById('mobile');
        const otpInput = document.getElementById('otpInput');

        sendOtpBtn.addEventListener('click', handleSendOTP);
        verifyOtpBtn.addEventListener('click', handleVerifyOTP);
        resendOtpBtn.addEventListener('click', handleResendOTP);

        mobileInput.addEventListener('input', function() {
            if (otpVerified) resetOTPVerification();
            this.value = this.value.replace(/\D/g, '').substring(0, 10);
        });

        otpInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').substring(0, OTP_CONFIG.OTP_LENGTH);
        });
    }

    // Handle Send OTP button click
    async function handleSendOTP() {
        const mobileNumber = document.getElementById('mobile').value.trim();

        if (!validateMobileNumber(mobileNumber)) {
            showOTPStatus('Please enter a valid 10-digit mobile number', 'error');
            return;
        }

        setLoadingState('sendOtpBtn', true, 'Sending...');

        try {
            // Make a POST request to your backend script
            const response = await fetch(OTP_CONFIG.SEND_OTP_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ mobile: mobileNumber })
            });

            const data = await response.json();
            console.log("Backend Response:", data);

            if (data.success) {
                showOTPSection();
                showOTPStatus('OTP sent successfully to +91-' + mobileNumber, 'success');
                startOTPTimer();
            } else {
                showOTPStatus(data.error || 'Failed to send OTP. Please try again.', 'error');
            }
        } catch (error) {
            console.error("Error sending OTP:", error);
            showOTPStatus('Failed to send OTP. Please try again.', 'error');
        } finally {
            setLoadingState('sendOtpBtn', false, 'Send OTP');
        }
    }

    // Handle Verify OTP button click
  async function handleVerifyOTP() {
      const otp = document.getElementById('otpInput').value.trim();
      const mobileNumber = document.getElementById('mobile').value.trim();

      if (!otp || otp.length !== OTP_CONFIG.OTP_LENGTH) {
          showOTPStatus('Please enter a valid 6-digit OTP', 'error');
          return;
      }

      setLoadingState('verifyOtpBtn', true, 'Verifying...');

      try {
          const response = await fetch(OTP_CONFIG.VERIFY_OTP_URL, {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ mobile: mobileNumber, otp: otp })
          });

          const data = await response.json();

          if (data.success) {
              otpVerified = true;
              showOTPStatus('✓ Mobile number verified successfully', 'success');
              markMobileAsVerified();
              clearOTPTimer();

              // ✅ Remove "Please verify your mobile number with OTP" error if it exists
              const mobileInput = document.getElementById("mobile");
              const otpError = mobileInput.parentNode.querySelector(".text-danger");
              if (otpError && otpError.innerText.includes("Please verify your mobile number with OTP")) {
                  otpError.remove();
              }
          } else {
              showOTPStatus(data.error || 'Invalid OTP. Please try again.', 'error');
          }
      } catch (error) {
          console.error("Verify OTP Error:", error);
          showOTPStatus('Failed to verify OTP. Please try again.', 'error');
      } finally {
          setLoadingState('verifyOtpBtn', false, 'Verify OTP');
      }
  }

    // Handle Resend OTP
    async function handleResendOTP() {
        const mobileNumber = document.getElementById('mobile').value.trim();
        setLoadingState('resendOtpBtn', true, 'Resending...');

        try {
            const response = await fetch(OTP_CONFIG.SEND_OTP_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ mobile: mobileNumber })
            });

            const data = await response.json();

            if (data.success) {
                showOTPStatus('OTP resent successfully', 'success');
                startOTPTimer();
                document.getElementById('resendOtpBtn').style.display = 'none';
            } else {
                showOTPStatus(data.error || 'Failed to resend OTP. Please try again.', 'error');
            }
        } catch (error) {
            console.error("Resend OTP Error:", error);
            showOTPStatus('Failed to resend OTP. Please try again.', 'error');
        } finally {
            setLoadingState('resendOtpBtn', false, 'Resend OTP');
        }
    }

    // Utility Functions (Unchanged)
    function validateMobileNumber(mobile) {
        return /^[6-9]\d{9}$/.test(mobile);
    }
    function showOTPSection() {
        document.getElementById('otpSection').style.display = 'block';
        document.getElementById('otpInput').focus();
    }
    function showOTPStatus(message, type) {
        const statusDiv = document.getElementById('otpStatus');
        statusDiv.className = `mt-1 ${type === 'success' ? 'text-success' : 'text-danger'}`;
        statusDiv.textContent = message;
    }
    function startOTPTimer() {
        clearOTPTimer();
        let timeLeft = OTP_CONFIG.OTP_VALIDITY_MINUTES * 60;
        const timerDiv = document.getElementById('otpTimer');
        const resendBtn = document.getElementById('resendOtpBtn');
        timerDiv.style.display = 'block';
        resendBtn.style.display = 'none';
        otpTimer = setInterval(() => {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerDiv.textContent = `OTP expires in ${minutes}:${seconds.toString().padStart(2, '0')}`;
            timeLeft--;
            if (timeLeft < 0) {
                clearInterval(otpTimer);
                timerDiv.textContent = 'OTP expired';
                resendBtn.style.display = 'inline-block';
                startResendTimer();
            }
        }, 1000);
    }
    function startResendTimer() {
        const resendBtn = document.getElementById('resendOtpBtn');
        let resendTimeLeft = OTP_CONFIG.RESEND_TIMER_SECONDS;
        resendBtn.disabled = true;
        resendTimer = setInterval(() => {
            resendBtn.textContent = `Resend OTP (${resendTimeLeft}s)`;
            resendTimeLeft--;
            if (resendTimeLeft < 0) {
                clearInterval(resendTimer);
                resendBtn.disabled = false;
                resendBtn.textContent = 'Resend OTP';
            }
        }, 1000);
    }
    function clearOTPTimer() {
        if (otpTimer) clearInterval(otpTimer);
        if (resendTimer) clearInterval(resendTimer);
        document.getElementById('otpTimer').style.display = 'none';
    }
function markMobileAsVerified() {
    const mobileInput = document.getElementById('mobile');
    const sendOtpBtn = document.getElementById('sendOtpBtn');

    mobileInput.classList.add('is-valid');
    mobileInput.readOnly = true; // <-- instead of disabled

    sendOtpBtn.textContent = '✓ Verified';
    sendOtpBtn.className = 'btn btn-success';
    sendOtpBtn.disabled = true;

    // add hidden field to ensure submission
    let hiddenMobile = document.querySelector("input[name='mobile_verified']");
    if (!hiddenMobile) {
        hiddenMobile = document.createElement("input");
        hiddenMobile.type = "hidden";
        hiddenMobile.name = "mobile_verified";
        hiddenMobile.value = mobileInput.value.trim();
        mobileInput.form.appendChild(hiddenMobile);
    }

    setTimeout(() => {
        document.getElementById('otpSection').style.display = 'none';
    }, 2000);
}

    function resetOTPVerification() {
        otpVerified = false;
        const mobileInput = document.getElementById('mobile');
        const sendOtpBtn = document.getElementById('sendOtpBtn');
        mobileInput.classList.remove('is-valid');
        // FIX: Set readOnly to false so the user can edit again
        mobileInput.readOnly = false; 
        sendOtpBtn.textContent = 'Send OTP';
        sendOtpBtn.className = 'btn btn-outline-primary';
        sendOtpBtn.disabled = false;
        document.getElementById('otpSection').style.display = 'none';
        document.getElementById('otpInput').value = '';
        document.getElementById('otpStatus').textContent = '';
        clearOTPTimer();
    }
    function setLoadingState(buttonId, loading, text) {
        const button = document.getElementById(buttonId);
        button.disabled = loading;
        button.textContent = text;
    }
// Form validation code with OTP verification check
document.querySelector("form[name='kycForm']").addEventListener("submit", function (e) {
    let form = e.target;
    let valid = true;
    form.querySelectorAll(".text-danger").forEach(el => el.remove());

    function showError(input, message) {
        const error = document.createElement("div");
        error.className = "text-danger mt-1";
        error.innerText = message;
        input.parentNode.appendChild(error);
        valid = false;
    }

    const name = form.fullname;
    if (!name.value.trim()) showError(name, "Full name is required");

    const mobile = form.mobile;
    const mobileVal = mobile.value.trim();

    if (!/^\d{10}$/.test(mobileVal)) {
        showError(mobile, "Enter valid 10-digit mobile number");
    } else if (!otpVerified) {
        showError(mobile, "Please verify your mobile number with OTP");
    }

    const email = form.email;
    if (!/^\S+@\S+\.\S+$/.test(email.value.trim())) showError(email, "Enter a valid email address");

    const aadhaar = form.aadhaar;
    if (!/^\d{12}$/.test(aadhaar.value.trim())) showError(aadhaar, "Enter valid 12-digit Aadhaar number");

    const city = form.city;
    if (!city.value.trim()) showError(city, "City is required");

    const area = form.area;
    if (!area.value.trim()) showError(area, "Area is required");

    const pincode = form.pincode;
    if (!/^\d{6}$/.test(pincode.value.trim())) showError(pincode, "Enter valid 6-digit pincode");

    const carModel = form.car_model;
    if (!carModel.value.trim()) showError(carModel, "Car model is required");

    const year = form.year;
    const yearValue = parseInt(year.value.trim(), 10);
    const currentYear = new Date().getFullYear();
    if (!year.value.trim()) {
        showError(year, "Manufacturing year is required");
    } else if (yearValue < 1900 || yearValue > currentYear) {
        showError(year, `Enter a valid year between 1900 and ${currentYear}`);
    }

    const platform = form.platform;
    if (!platform.value) showError(platform, "Please select a platform");

    const shift = form.shift;
    if (!shift.value) showError(shift, "Please select a shift preference");

    const fleetType = form.fleet_type;
    if (!fleetType.value) showError(fleetType, "Please select a fleet type");

    const checkbox = form.querySelector("input[type='checkbox']");
    if (!checkbox.checked) {
        const label = checkbox.parentNode;
        showError(label, "You must accept the Digital Agreement");
    }

    form.querySelectorAll("input[type='file'][data-required='true']").forEach(input => {
        const parent = input.closest(".col-md-6, .form-group");
        if (!input.files || input.files.length === 0) {
            const label = parent.querySelector("label");
            const fieldName = label ? label.innerText : "this file";
            const error = document.createElement("div");
            error.className = "text-danger mt-1";
            error.innerText = `Please upload ${fieldName.toLowerCase()}`;
            parent.appendChild(error);
            valid = false;
        }
    });

    if (!valid) {
        e.preventDefault();
        window.scrollTo({ top: form.offsetTop, behavior: 'smooth' });
    }
});

// ✅ Auto-remove error when user types or changes input
document.querySelectorAll("form[name='kycForm'] input, form[name='kycForm'] select, form[name='kycForm'] textarea").forEach(input => {
    input.addEventListener("input", () => {
        const error = input.parentNode.querySelector(".text-danger");
        if (error) error.remove();
    });
    input.addEventListener("change", () => {
        const error = input.parentNode.querySelector(".text-danger");
        if (error) error.remove();
    });
});


    // File validation code (unchanged)
    const MAX_FILE_SIZE_MB = 10;
    const ALLOWED_TYPES = ["image/jpeg", "image/png", "application/pdf"];

    function validateFiles(input) {
        const files = input.files;
        const parent = input.closest(".col-md-6, .form-group");
        parent.querySelectorAll(".text-danger").forEach(el => el.remove());
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (!ALLOWED_TYPES.includes(file.type)) {
                const error = document.createElement("div");
                error.className = "text-danger mt-1";
                error.innerText = `Invalid file type: ${file.name}`;
                parent.appendChild(error);
                input.value = "";
                return false;
            }
            if (file.size > MAX_FILE_SIZE_MB * 1024 * 1024) {
                const error = document.createElement("div");
                error.className = "text-danger mt-1";
                error.innerText = `File too large: ${file.name} (Max ${MAX_FILE_SIZE_MB} MB)`;
                parent.appendChild(error);
                input.value = "";
                return false;
            }
        }
        return true;
    }
    document.querySelectorAll("input[type='file']").forEach(input => {
        input.addEventListener("change", function () {
            const parent = input.closest(".col-md-6, .form-group");
            parent.querySelectorAll(".text-danger").forEach(el => el.remove());
            const existingRemove = parent.querySelector(".remove-wrapper");
            if (existingRemove) existingRemove.remove();
            if (validateFiles(input) && input.files.length > 0) {
                const wrapper = document.createElement("div");
                wrapper.className = "remove-wrapper mt-2 d-flex align-items-center gap-2";
                const fileLabel = document.createElement("small");
                fileLabel.className = "text-muted";
                fileLabel.innerText = input.files[0].name;
                const removeBtn = document.createElement("button");
                removeBtn.type = "button";
                removeBtn.className = "btn btn-sm btn-outline-danger";
                removeBtn.innerText = "✖";
                removeBtn.addEventListener("click", () => {
                    input.value = "";
                    wrapper.remove();
                });
                wrapper.appendChild(fileLabel);
                wrapper.appendChild(removeBtn);
                input.insertAdjacentElement("afterend", wrapper);
            }
        });
    });
    // Form reset handler (unchanged)
    document.querySelector("form[name='kycForm']").addEventListener("submit", function (e) {
        const form = e.target;
        if (!e.defaultPrevented) {
            setTimeout(() => {
                form.reset();
                form.querySelectorAll(".remove-wrapper").forEach(el => el.remove());
            }, 100); 
        }
    });
</script>
</body>
</html>