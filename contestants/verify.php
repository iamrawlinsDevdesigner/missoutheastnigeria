<?php
include '../includes/db.php';

if (isset($_GET['token'])) {
    $token = $conn->real_escape_string($_GET['token']);
    $sql = "SELECT * FROM email_verifications WHERE token='$token' AND is_verified=0";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $conn->query("UPDATE email_verifications SET is_verified=1 WHERE token='$token'");
        header("Location: register.php?token=$token");
        exit();
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}
?>