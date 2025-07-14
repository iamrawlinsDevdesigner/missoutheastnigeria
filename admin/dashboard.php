<?php
include '../includes/db.php';

function get_site_mode($conn) {
    $sql = "SELECT site_mode FROM settings WHERE id = 1 LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['site_mode'];
    } else {
        return 'live';
    }
}

$currentMode = get_site_mode($conn);

if (isset($_POST['toggle'])) {
    $newMode = $currentMode == 'live' ? 'maintenance' : 'live';
    $sql = "UPDATE settings SET site_mode='$newMode' WHERE id=1";
    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Current Site Mode: <strong><?php echo strtoupper($currentMode); ?></strong></p>
    <form method="post">
        <button type="submit" name="toggle">
            Switch to <?php echo $currentMode == 'live' ? 'Maintenance' : 'Live'; ?> Mode
        </button>
    </form>
</body>
</html>