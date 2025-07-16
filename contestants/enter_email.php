<?php
session_start();
include '../includes/db.php';
include '../includes/mailer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    if (!$email) {
        die('❌ Invalid email address.');
    }

    // ✅ Check if email already exists
    $stmt = $conn->prepare("SELECT id, verified FROM contestants WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // 🎯 Existing email
        $stmt->bind_result($id, $verified);
        $stmt->fetch();

        if ($verified == 1) {
            // ✅ Already verified - redirect to registration
            $_SESSION['verified_email'] = $email;
            header("Location: register.php");
            exit();
        } else {
            // 🔁 Not verified - resend new token
            $token = bin2hex(random_bytes(32)); // 64-char token
            $update = $conn->prepare("UPDATE contestants SET token=?, token_expiry=DATE_ADD(NOW(), INTERVAL 1 DAY) WHERE id=?");
            $update->bind_param('si', $token, $id);
            if ($update->execute()) {
                sendVerificationEmail($email, 'Contestant', $token); // ✅ Pass 3 arguments
                echo "📧 A new verification link has been sent to your email.";
            } else {
                echo "❌ Failed to update verification token. Try again.";
            }
        }
    } else {
        // 🆕 New email - insert into database
        $token = bin2hex(random_bytes(32)); // 64-char token
        $insert = $conn->prepare("INSERT INTO contestants (email, token, token_expiry) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 DAY))");
        $insert->bind_param('ss', $email, $token);
        if ($insert->execute()) {
            sendVerificationEmail($email, 'Contestant', $token); // ✅ Pass 3 arguments
            echo "📧 A verification link has been sent to your email.";
        } else {
            echo "❌ Failed to send verification link. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enter Your Email - Miss South East Nigeria</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            padding: 40px;
        }
        form {
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            max-width: 400px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        input, button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            background: #4CAF50;
            color: #fff;
            border: none;
        }
        button:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <form method="post">
        <h2>Enter Your Email</h2>
        <p>Please enter your email to start your registration.</p>
        <input type="email" name="email" placeholder="you@example.com" required>
        <button type="submit">Send Verification Link</button>
    </form>
</body>
</html>
