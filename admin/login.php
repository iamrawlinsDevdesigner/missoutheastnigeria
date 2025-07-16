<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_logged_in'] = true;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Admin account not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Miss South East Nigeria</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- PWA Meta -->
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#3498db">
<link rel="icon" sizes="192x192" href="/assets/icons/icon-192.png">

</head>
<body>
    <h2>Admin Login</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
        <input type="password" name="password" placeholder="Enter Admin Password" required><br><br>
        <button type="submit" class="loading-btn">Login</button>
    </form>
</body>
</html>
