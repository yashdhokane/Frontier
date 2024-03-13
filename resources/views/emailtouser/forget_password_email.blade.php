<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Update Notification</title>
    <style>
        /* Add your custom styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        p {
            color: #666;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Password Update Notification</h1>
        <p>Dear user,</p>
        <p>Your password for your {{ $companyName }} account has been successfully updated.</p>
        <p>Your new password is: <strong>{{ $newPassword }}</strong></p>
        <p>We recommend that you keep this password secure and do not share it with anyone.</p>
        <p>Thank you,</p>
        <p>The {{ $companyName }} Team</p>
    </div>
</body>
</html>