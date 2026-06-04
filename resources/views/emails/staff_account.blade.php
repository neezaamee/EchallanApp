<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Account Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #0d6efd;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
            color: #333333;
            line-height: 1.6;
        }
        .details-box {
            background-color: #f8f9fa;
            border-left: 4px solid #0d6efd;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            background-color: #f4f4f4;
            color: #777777;
            text-align: center;
            padding: 15px;
            font-size: 12px;
            border-top: 1px solid #dddddd;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #0d6efd;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>City Traffic Police Faisalabad</h1>
        </div>
        <div class="content">
            <p>Respected {{ $user->name }},</p>
            
            <p>Your account has been {{ $action == 'created' ? 'created' : 'updated' }} by the administrator. Below are your account credentials:</p>
            
            <div class="details-box">
                <p><strong>Login Email:</strong> {{ $user->email }}</p>
                <p><strong>Password:</strong> {{ $password }}</p>
            </div>
            
            <p>Please keep this information secure and do not share it with anyone. You can log in using the button below:</p>
            
            <a href="{{ url('/login') }}" class="button">Log In to WCMS Application</a>
            
            <p>If you have any questions, please contact IT Branch, CTP Faisalabad.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} City Traffic Police Faisalabad. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
