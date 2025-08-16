<?php
// Show errors for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Redirect if not POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');  // Change this to your contact page
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Sanitize inputs
$name = htmlspecialchars(strip_tags($_POST['name']));
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$message = htmlspecialchars(strip_tags($_POST['message']));

// Validate
if (!$name || !$email || !$message || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Please fill in all fields correctly.";
    exit;
}

// Mail setup
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtpout.secureserver.net';  // ✅ for GoDaddy/Hostinger
    $mail->SMTPAuth = true;
    $mail->Username = 'info@rangvet.com';      // ✅ RANGVet email
    $mail->Password = '$N8.0F8sF4bk';  // 🔐 Replace with real password
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';

    // Optional - avoid SSL issues
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ],
    ];

    // Sender and recipient
    $mail->setFrom('info@rangvet.com', 'RANGVet Website');
    $mail->addAddress('info@rangvet.com');

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'New Contact Message from RANGVet Website';
    $mail->Body = "
        <h3>Contact Message</h3>
        <p><strong>Name:</strong> {$name}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Message:</strong><br>{$message}</p>
    ";

    // Send the email
    $mail->send();
    echo 'Message sent successfully!';
} catch (Exception $e) {
    echo "Message could not be sent. Error: {$mail->ErrorInfo}";
}
