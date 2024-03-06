<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Auto-Ecole App!</title>
</head>
<body>
    <h2>Welcome to Auto-Ecole App, {{ $user->name }}!</h2>
    <!-- Instructions for password reset -->
    <p>To reset your password, please follow these steps:</p>
    <ol>
        <li>Copy the code above    {{ $code }}.</li>
        <li>Go to the password reset page on our website.</li>
        <li>Paste the copied code into the appropriate field.</li>
        <li>Follow the instructions to reset your password.</li>
    </ol>
    <p>If you have any questions or need further assistance, please contact our support team.</p>

    <p>Thank you for joining Auto-Ecole App!</p>
</body>
</html>
