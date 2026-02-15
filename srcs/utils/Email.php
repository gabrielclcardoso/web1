<?php

function sendActivationEmail($email, $token) {
    $subject = "Confirm your camagru account";
    
    $activation_link = "http://localhost:8080/activate.php?email=" . urlencode($email) . "&token=" . urlencode($token);

    $message = "
    <html>
    <head>
        <title>Welcome to Camagru</title>
    </head>
    <body>
        <h2>Thank you for registering!</h2>
        <p>To start using the website please activate your account</p>
        <p>
            <a href='{$activation_link}' style='background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>
                Activate my account
            </a>
        </p>
        <p><small>If the link above doesn't work, copy this link: {$activation_link}</small></p>
    </body>
    </html>
    ";

    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: no-reply@camagru.com" . "\r\n";

    return mail($email, $subject, $message, $headers);
}

function sendResetEmail($email, $token) {
    $subject = "Password Recovery - Camagru";
    
    $reset_link = "http://localhost:8080/reset_password.php?token=" . urlencode($token);

    $message = "
    <html>
    <head>
        <title>Password Recovery</title>
        <style>
            .button { background-color: #dc3545; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold; }
        </style>
    </head>
    <body>
        <h2>You have solicited a password reset link</h2>
        <p>Click on the button below to reset your password. This link is valid for 1 hour.</p>
        <p>
            <a href='{$reset_link}' class='button'>Reset my password</a>
        </p>
        <p>If it wasn't you who solicited the link you can safely ignore this email</p>
        <hr>
        <p><small>If the button doesn't work, copy this link: {$reset_link}</small></p>
    </body>
    </html>
    ";

    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Camagru Support <no-reply@camagru.com>" . "\r\n";

    return mail($email, $subject, $message, $headers);
}
?>
