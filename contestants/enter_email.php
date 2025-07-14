<?php
include '../includes/db.php';
include '../includes/mailer.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $token = bin2hex(random_bytes(16));

    $sql = "INSERT INTO email_verifications (email, token) VALUES ('$email', '$token') 
            ON DUPLICATE KEY UPDATE token='$token', is_verified=0";

    if ($conn->query($sql) === TRUE) {
        if (sendVerificationEmail($email, $token)) {
            echo "Verification email sent! Check your inbox.";
        } else {
            echo "Failed to send verification email.";
        }
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<form method="post">
    <h2>Enter Your Email</h2>
    <input type="email" name="email" required>
    <button type="submit">Send Verification Link</button>
</form>