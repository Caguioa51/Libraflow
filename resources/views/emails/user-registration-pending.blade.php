<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Received - {{ $appName }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #ffc107;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #ffc107;
            margin: 0;
            font-size: 28px;
        }
        .content {
            margin-bottom: 30px;
        }
        .footer {
            border-top: 1px solid #ddd;
            padding-top: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .info-box {
            background-color: #fff3cd;
            padding: 15px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
        }
        .info-box h3 {
            color: #856404;
            margin-top: 0;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            background-color: #ffc107;
            color: #000;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⏳ Registration Received</h1>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $userName }}</strong>,</p>
            
            <p>Thank you for your interest in <strong>{{ $appName }}</strong>! We have successfully received your registration request.</p>
            
            <div class="info-box">
                <h3>Registration Details:</h3>
                <p><strong>Name:</strong> {{ $userName }}</p>
                <p><strong>Email:</strong> {{ $userEmail }}</p>
                <p><strong>Role:</strong> {{ ucfirst($userRole) }}</p>
                <p><strong>Registered at:</strong> {{ $registeredAt }}</p>
                <p><strong>Status:</strong> <span class="status-badge">Pending Approval</span></p>
            </div>
            
            <p>Your registration is currently being reviewed by our administration team. This process typically takes 1-2 business days.</p>
            
            <h3>What happens next?</h3>
            <ul>
                <li>Our team will review your registration information</li>
                <li>You will receive an email notification once a decision has been made</li>
                <li>If approved, you'll be able to login and access your account</li>
                <li>If additional information is needed, we'll contact you directly</li>
            </ul>
            
            <p>You don't need to take any action at this time. We'll notify you as soon as there's an update on your registration status.</p>
            
            <p style="text-align: center;">
                <a href="{{ $loginUrl }}" style="color: #007bff; text-decoration: none;">Visit Login Page</a>
            </p>
            
            <p>If you have any questions or need to update your registration information, please don't hesitate to contact our support team.</p>
            
            <p>We appreciate your patience and look forward to potentially welcoming you to {{ $appName }}!</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message from {{ $appName }}. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
