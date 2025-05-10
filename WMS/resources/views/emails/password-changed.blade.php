<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Password Has Been Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #f8f9fa;
        }
        .header {
            background-color: #0284c7;
            padding: 20px;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            font-size: 16px;
        }
        .content {
            padding: 30px;
            background-color: white;
        }
        .content h2 {
            margin-top: 0;
            font-size: 20px;
            color: #333;
        }
        .button {
            display: inline-block;
            background-color: #0284c7;
            color: white !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
        }
        .footer {
            padding: 20px 30px;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #eee;
        }
        .credentials {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
            border: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>YHROCU</h1>
            <p>WORKFLOW MANAGEMENT SYSTEM</p>
        </div>
        
        <div class="content">
            <h2>Hello {{ $user->first_name }},</h2>
            
            <p>Your password has been reset by an administrator.</p>
            
            <div class="credentials">
                <p><strong>Username:</strong> {{ $user->username }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Password:</strong> {{ $newPassword }}</p>
            </div>
            
            <p>For security reasons, we recommend changing your password after logging in.</p>
            
            <a href="{{ $loginUrl }}" class="button">Login to Your Account</a>
            
            <p>If you did not request this password reset, please contact the system administrator immediately.</p>
            
            <p>Regards,<br>YHROCU-Task-Management</p>
        </div>
        
        <div class="footer">
            <p>Thank you for using the YHROCU Workflow Management System!</p>
        </div>
    </div>
</body>
</html> 