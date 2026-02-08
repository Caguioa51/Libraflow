<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Update - {{ $appName }}</title>
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
            border-bottom: 2px solid #dc3545;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #dc3545;
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
            background-color: #f8d7da;
            padding: 15px;
            border-left: 4px solid #dc3545;
            margin: 20px 0;
        }
        .info-box h3 {
            color: #721c24;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Registration Update</h1>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $userName }}</strong>,</p>
            
            <p>We regret to inform you that your registration request for <strong>{{ $appName }}</strong> has been reviewed and could not be approved at this time.</p>
            
            <div class="info-box">
                <h3>Registration Details:</h3>
                <p><strong>Name:</strong> {{ $userName }}</p>
                <p><strong>Email:</strong> {{ $userEmail }}</p>
                <p><strong>Status:</strong> Not Approved</p>
                <p><strong>Processed at:</strong> {{ $rejectedAt }}</p>
            </div>
            
            <div class="info-box">
                <h3>Reason:</h3>
                <p>{{ $rejectionReason }}</p>
            </div>
            
            <p>If you believe this was made in error or would like to inquire further about this decision, please contact our administration team for clarification.</p>
            
            <p>Thank you for your interest in {{ $appName }}.</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message from {{ $appName }}. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
