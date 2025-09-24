<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - Santa Fe Water</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .header {
            background-color: #0056b3;
            padding: 20px;
            text-align: center;
        }
        .logo {
            max-width: 180px;
            height: auto;
        }
        .content {
            padding: 30px;
        }
        .verification-box {
            background-color: #f0f7ff;
            border-left: 4px solid #0056b3;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 0 4px 4px 0;
        }
        .code {
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #0056b3;
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            background: #e6f0ff;
            border-radius: 4px;
        }
        .footer {
            background-color: #f2f2f2;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        .warning {
            color: #ff6600;
            font-weight: bold;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #0056b3;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 15px 0;
            font-weight: bold;
        }
        @media only screen and (max-width: 600px) {
            .content {
                padding: 20px;
            }
            .code {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        
        <div class="content">
            <h2>Email Verification Required</h2>
            <p>Hello,</p>
            <p>Thank you for using the Santa Fe Water Billing System. To complete your verification, please use the following code:</p>
            
            <div class="verification-box">
                <p>Your verification code is:</p>
                <div class="code">{{ $code }}</div>
                <p class="warning">This code will expire in 1 minute for security reasons.</p>
            </div>
            
            <p>If you did not request this verification, please ignore this email or contact our support team if you have concerns.</p>
            
            <p>For your security, never share this code with anyone.</p>
        </div>
        
        <div class="footer">
            <p>Thank you,<br>The Santa Fe Water Billing System Team</p>
            <p>© 2025 Santa Fe Water Billing System. All rights reserved.</p>
            <p><a href="#" style="color: #0056b3;">Privacy Policy</a> | <a href="#" style="color: #0056b3;">Contact Support</a></p>
        </div>
    </div>
</body>
</html>