<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Application Submitted</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: white;
            padding: 20px;
            border-bottom: 1px solid #ddd;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 12px;
            background-color: #f39c12;
            color: white;
            border-radius: 3px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Your Application Has Been Received</h1>
        </div>
        
        <div class="content">
            <p>Dear {{ $user->name }},</p>
            
            <p>Thank you for applying for the position of <strong>{{ $application->category->name }}</strong>. We have received your application and will review it shortly.</p>
            
            <div class="status-badge">Status: {{ strtoupper($application->status) }}</div>
            
            <h3>Application Details:</h3>
            <ul>
                <li><strong>Position:</strong> {{ $application->category->name }}</li>
                <li><strong>Submitted:</strong> {{ $application->created_at->format('M d, Y H:i') }}</li>
                <li><strong>Your Statement:</strong> {{ substr($application->statement, 0, 100) }}...</li>
            </ul>
            
            <p>We will notify you by email as soon as your application has been reviewed. You can also check the status of your application in your dashboard.</p>
            
            <p>If you have any questions, please contact the student affairs office.</p>
            
            <p>Best regards,<br>
            <strong>IUEA GuildVote Admin Team</strong></p>
        </div>
        
        <div class="footer">
            <p>&copy; 2026 International University of East Africa. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
