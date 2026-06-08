<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Thank You | Driver KYC Submitted</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: linear-gradient(135deg, #edf2f8, #f8faff);
      padding: 20px;
    }
    .container {
      text-align: center;
      background: white;
      border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
      max-width: 500px;
      width: 100%;
      padding: 30px;
    }
    .container img {
      max-width: 100%;
      height: auto;
      margin-bottom: 20px;
    }
    h1 {
      color: #0a2540;
      font-size: 28px;
      margin-bottom: 10px;
    }
    p {
      color: #4a4a4a;
      font-size: 16px;
      margin-bottom: 25px;
    }
    .btn {
      display: inline-block;
      padding: 12px 24px;
      background-color: #0a84ff;
      color: white;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: background 0.3s ease;
    }
    .btn:hover {
      background-color: #006edc;
    }

    @media (max-width: 600px) {
      h1 {
        font-size: 22px;
      }
      p {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <img src="https://cdn.openai.com/chat-assets/ai-driver-thanks.png" alt="Driver AI Illustration" />
    <h1>Thank You for Submitting Your Details!</h1>
    <p>Your KYC & onboarding information has been received successfully.<br>
    Our team will verify and get back to you shortly.</p>
    <a href="/" class="btn">Return to Home</a>
  </div>
</body>
</html>
