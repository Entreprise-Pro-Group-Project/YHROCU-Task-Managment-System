<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Request</title>
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>YHROCU</h1>
            <p>WORKFLOW MANAGEMENT SYSTEM</p>
        </div>
        
        <div class="content">
            <h2>Hello!</h2>
            
            <p>A password reset has been requested for: {{ $user->first_name }} {{ $user->last_name }}</p>
            
            <p><strong>Username:</strong> {{ $user->username }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            
            <p>Please reset the password for this user in the admin panel.</p>
            
            <a href="{{ $resetUrl }}" class="button">Reset Password</a>
            
            <p>Please reset the password for this user in the admin panel.</p>
            
            <p>Regards,<br>YHROCU-Task-Management</p>
        </div>
        
        <div class="footer">
            <p>If you did not request this password reset, please ignore this email.</p>
        </div>
    </div>
</body>
</html> 