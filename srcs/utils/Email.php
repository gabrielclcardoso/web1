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
?>
