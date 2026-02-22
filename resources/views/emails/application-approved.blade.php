<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Application Approved</title>
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
            background-color: #27ae60;
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
            background-color: #27ae60;
            color: white;
            border-radius: 3px;
            margin: 10px 0;
        }
        .congratulations {
            font-size: 24px;
            text-align: center;
            margin: 20px 0;
            color: #27ae60;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Congratulations! 🎉</h1>
        </div>
        
        <div class="content">
            <p>Dear {{ $user->name }},</p>
            
            <div class="congratulations">
                ✅ Your Application Has Been Approved!
            </div>
            
            <p>We are pleased to inform you that your application for <strong>{{ $application->category->name }}</strong> has been <strong>APPROVED</strong>!</p>
            
            <div class="status-badge">Status: APPROVED</div>
            
            <h3>Application Details:</h3>
            <ul>
                <li><strong>Position:</strong> {{ $application->category->name }}</li>
                <li><strong>Approved:</strong> {{ $application->reviewed_at->format('M d, Y H:i') }}</li>
                <li><strong>Approved By:</strong> {{ $application->reviewer->name }}</li>
            </ul>
            
            <p>You are now officially a registered candidate for this position. You can now engage in campaigns and prepare for the elections.</p>
            
            <p>Log in to your dashboard to view more details and manage your candidacy.</p>
            
            <p>Thank you for your interest in student leadership!</p>
            
            <p>Best regards,<br>
            <strong>IUEA GuildVote Admin Team</strong></p>
        </div>
        
        <div class="footer">
            <p>&copy; 2026 International University of East Africa. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
