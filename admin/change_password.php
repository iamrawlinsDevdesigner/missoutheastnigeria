<?php
include '../includes/db.php';

$new_password = 'superadmin100%';
$hash = password_hash($new_password, PASSWORD_BCRYPT);

$sql = "UPDATE admins SET password='$hash' WHERE id=1";
if ($conn->query($sql) === TRUE) {
    echo "Admin password updated successfully!";
} else {
    echo "Error updating password: " . $conn->error;
}
?>
