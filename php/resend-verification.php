<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './PHPMailer/src/Exception.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

    $conn = mysqli_connect("localhost:3306", "root", "", "flowershop");

    $update = mysqli_query($conn, "UPDATE users SET verification_code = '$verification_code' WHERE email = '$email'");

    if ($update) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'sunnybloom0812@gmail.com'; // your Gmail
            $mail->Password = 'uxcosnbdmcbawvhc'; // your actual app password (remove spaces)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('sunnybloom0812@gmail.com', 'Sunny Bloom');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Email Verification Code';
            $mail->Body    = "<h3>Your Verification Code</h3><p>$verification_code</p>";

            $mail->send();

            header("Location: email-verification.php?email=" . urlencode($email));
            exit();
        } catch (Exception $e) {
            echo "<script>alert('Mailer Error: {$mail->ErrorInfo}'); window.history.back();</script>";
            exit();
        }
    } else {
        echo "<script>alert('Failed to generate verification code.'); window.history.back();</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
    exit();
}
?>