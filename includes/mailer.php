<?php
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';
require_once __DIR__ . '/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendVerificationEmail($toEmail, $name, $token) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rawcoaster@gmail.com'; // Your Gmail
        $mail->Password   = 'wlfwcbezyrbblmab';      // App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('rawcoaster@gmail.com', 'Miss South East Nigeria');
        $mail->addAddress($toEmail, $name);
        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Email - Miss South East Nigeria';

        $verifyLink = "http://localhost/missoutheastnigeria/contestants/verify.php?token=$token";
        $mail->Body = "
            <p>Hello $name,</p>
            <p>Please click the link below to verify your email address:</p>
            <p><a href='$verifyLink'>$verifyLink</a></p>
            <p>This link will expire in 24 hours.</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error (Verification): {$mail->ErrorInfo}");
        return false;
    }
}

function sendApprovalEmail($to, $name) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rawcoaster@gmail.com';
        $mail->Password   = 'wlfwcbezyrbblmab';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('rawcoaster@gmail.com', 'Miss South East Nigeria');
        $mail->addAddress($to, $name);
        $mail->isHTML(true);
        $mail->Subject = 'Congratulations! You have been approved';
        $mail->Body = "<p>Dear $name,</p>
        <p>Congratulations! You have been approved as a contestant for Miss South East Nigeria.</p>
        <p>We look forward to your participation!</p>";

        $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error (Approval): {$mail->ErrorInfo}");
    }
}

function sendDeclineEmail($to, $name, $reason) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rawcoaster@gmail.com';
        $mail->Password   = 'wlfwcbezyrbblmab';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('rawcoaster@gmail.com', 'Miss South East Nigeria');
        $mail->addAddress($to, $name);
        $mail->isHTML(true);
        $mail->Subject = 'Your Application Status - Declined';
        $mail->Body = "<p>Dear $name,</p>
        <p>We regret to inform you that your application for Miss South East Nigeria has been declined.</p>
        <p><strong>Reason:</strong> $reason</p>
        <p>Thank you for your interest.</p>";

        $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error (Decline): {$mail->ErrorInfo}");
    }
}
?>
