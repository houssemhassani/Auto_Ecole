<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to MyApp</title>
</head>
<body>
    <h2>Welcome to Auto-Ecole App, {{ $user->name }}!</h2>
    <p>Your account has been successfully created.</p>
    <p>Here are your login details:</p>
    <ul>
        <li><strong>Email:</strong> {{ $user->email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>
    <p>Please keep this information secure and do not share it with anyone.</p>
    <p>Thank you for joining MyApp!</p>
</body>
</html>
