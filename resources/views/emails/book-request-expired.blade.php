<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Request Expired - {{ $appName }}</title>
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
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            background-color: #dc3545;
            color: #ffffff;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⏰ Book Request Expired</h1>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $userName }}</strong>,</p>
            
            <p>We're writing to inform you that your book request has expired due to the time limit for approval.</p>
            
            <div class="info-box">
                <h3>Request Details:</h3>
                <p><strong>Book Title:</strong> {{ $bookTitle }}</p>
                <p><strong>Author:</strong> {{ $bookAuthor }}</p>
                <p><strong>Request ID:</strong> #{{ $requestId }}</p>
                <p><strong>Requested at:</strong> {{ $requestedAt }}</p>
                <p><strong>Expired at:</strong> {{ $expiredAt }}</p>
                <p><strong>Status:</strong> <span class="status-badge">Expired</span></p>
            </div>
            
            <h3>What happened?</h3>
            <p>Book requests in {{ $appName }} are automatically expired after 2 hours if not approved by an administrator. This ensures that books remain available for other users when requests are not processed in a timely manner.</p>
            
            <h3>What can you do now?</h3>
            <ul>
                <li><strong>Request the book again:</strong> You can submit a new request for the same book if it's still available</li>
                <li><strong>Check availability:</strong> Visit your borrowing requests page to see current status</li>
                <li><strong>Contact support:</strong> If you believe this was an error, please contact our library staff</li>
            </ul>
            
            <p style="text-align: center;">
                <a href="{{ $bookRequestUrl }}" class="btn">View My Requests</a>
            </p>
            
            <p>The book's availability has been restored and may be requested by other users or borrowed immediately if available.</p>
            
            <p>We apologize for any inconvenience this may cause. Thank you for your understanding.</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message from {{ $appName }}. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
