<?php
include 'db.php';

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

$site_mode = get_site_mode($conn);

if ($site_mode == 'maintenance' && basename($_SERVER['PHP_SELF']) != 'maintenance.php') {
    header("Location: maintenance.php");
    exit();
}
?>