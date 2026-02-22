<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Application Status Update</title>
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
            background-color: #e74c3c;
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
            background-color: #e74c3c;
            color: white;
            border-radius: 3px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Application Status Update</h1>
        </div>
        
        <div class="content">
            <p>Dear {{ $user->name }},</p>
            
            <p>We regret to inform you that your application for <strong>{{ $application->category->name }}</strong> has not been approved at this time.</p>
            
            <div class="status-badge">Status: REJECTED</div>
            
            <h3>Application Details:</h3>
            <ul>
                <li><strong>Position:</strong> {{ $application->category->name }}</li>
                <li><strong>Decision Date:</strong> {{ $application->reviewed_at->format('M d, Y H:i') }}</li>
                <li><strong>Reviewed By:</strong> {{ $application->reviewer->name }}</li>
            </ul>
            
            <p>Thank you for your interest in the position. We encourage you to apply for other positions or try again in the next election cycle. For more information, please contact the student affairs office.</p>
            
            <p>Best regards,<br>
            <strong>IUEA GuildVote Admin Team</strong></p>
        </div>
        
        <div class="footer">
            <p>&copy; 2026 International University of East Africa. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
