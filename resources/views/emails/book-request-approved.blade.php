<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Request Approved - {{ $appName }}</title>
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
            border-bottom: 2px solid #28a745;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #28a745;
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
            background-color: #d4edda;
            padding: 15px;
            border-left: 4px solid #28a745;
            margin: 20px 0;
        }
        .info-box h3 {
            color: #155724;
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
            background-color: #28a745;
            color: #ffffff;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
        }
        .due-date {
            background-color: #fff3cd;
            padding: 10px;
            border-left: 4px solid #ffc107;
            margin: 15px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Book Request Approved!</h1>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $userName }}</strong>,</p>
            
            <p>Great news! Your book request has been approved by our administrator. You can now pick up your book from the library.</p>
            
            <div class="info-box">
                <h3>Request Details:</h3>
                <p><strong>Book Title:</strong> {{ $bookTitle }}</p>
                <p><strong>Author:</strong> {{ $bookAuthor }}</p>
                <p><strong>Request ID:</strong> #{{ $requestId }}</p>
                <p><strong>Borrowing ID:</strong> #{{ $borrowingId }}</p>
                <p><strong>Requested at:</strong> {{ $requestedAt }}</p>
                <p><strong>Approved at:</strong> {{ $approvedAt }}</p>
                <p><strong>Approved by:</strong> {{ $approvedByName }}</p>
                <p><strong>Status:</strong> <span class="status-badge">Approved</span></p>
            </div>
            
            <div class="due-date">
                <h3>⚠️ Important - Return Date</h3>
                <p><strong>Due Date:</strong> {{ $dueDate }}</p>
                <p><strong>Borrowed at:</strong> {{ $borrowedAt }}</p>
                <p>Please return the book by the due date to avoid any late fees. You can renew the book if needed, subject to availability.</p>
            </div>
            
            <h3>What happens next?</h3>
            <ul>
                <li><strong>Visit the library:</strong> Pick up your book from the circulation desk</li>
                <li><strong>Bring your ID:</strong> You may need to show your student/teacher ID</li>
                <li><strong>Check due date:</strong> Make note of your return date</li>
                <li><strong>Track your borrowings:</strong> View your current and past borrowings online</li>
            </ul>
            
            <p style="text-align: center;">
                <a href="{{ $myBorrowingsUrl }}" class="btn">View My Borrowings</a>
            </p>
            
            <p>If you have any questions about your borrowing or need to make changes, please contact the library staff.</p>
            
            <p>Happy reading!</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message from {{ $appName }}. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
