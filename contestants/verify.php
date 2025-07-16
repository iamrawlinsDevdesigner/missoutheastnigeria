<?php
session_start();
include '../includes/db.php';

// ✅ Check if token is provided
if (!isset($_GET['token'])) {
    echo "❌ No token provided.";
    exit();
}

$token = $conn->real_escape_string($_GET['token']);

// ✅ Check if token exists, not expired, and not already used
$sql = "SELECT email, token_expiry, verified FROM contestants WHERE token='$token'";
$result = $conn->query($sql);

if ($result && $result->num_rows === 1) {
    $row = $result->fetch_assoc();

    // ✅ Check if already verified
    if ($row['verified'] == 1) {
        echo "✅ Email already verified. Redirecting...";
        $_SESSION['verified_email'] = $row['email'];
        header("Refresh: 2; URL=register.php");
        exit();
    }

    // ✅ Check if token expired
    if (strtotime($row['token_expiry']) < time()) {
        echo "❌ Token expired. Please request a new verification link.";
        exit();
    }

    // ✅ Mark email as verified
    $update = "UPDATE contestants SET verified=1, token=NULL, token_expiry=NULL WHERE token='$token'";
    if ($conn->query($update)) {
        // Store email in session for registration
        $_SESSION['verified_email'] = $row['email'];
        echo "🎉 Email verified successfully! Redirecting to registration...";
        header("Refresh: 2; URL=register.php");
        exit();
    } else {
        echo "❌ Failed to verify email. Please try again.";
        exit();
    }
} else {
    echo "❌ Invalid or expired token.";
    exit();
}
?>
