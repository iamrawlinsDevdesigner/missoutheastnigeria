<?php
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';
require_once __DIR__ . '/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendVerificationEmail($toEmail, $token) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rawcoaster@gmail.com'; // Your Gmail address
        $mail->Password   = 'wlfwcbezyrbblmab'; // Gmail App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('youremail@gmail.com', 'Miss South East Nigeria');
        $mail->addAddress($toEmail);
        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Email - Miss South East Nigeria';
        $mail->Body    = 'Click the link to verify your email: <a href="http://localhost/miss-south-east-complete/contestants/verify.php?token=' . $token . '">Verify Email</a>';

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>