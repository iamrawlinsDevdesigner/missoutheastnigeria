<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(403);
    echo "Unauthorized.";
    exit();
}

$status = $_GET['status'] ?? 'all';

$where = '';
if ($status === 'approved') {
    $where = "WHERE status='approved'";
} elseif ($status === 'declined') {
    $where = "WHERE status='declined'";
}

$sql = "SELECT id, fullname, email, verified, status FROM contestants $where ORDER BY id DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<table>';
    echo '<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Status</th><th>Action</th></tr></thead>';
    echo '<tbody>';
    while ($row = $result->fetch_assoc()) {
        $badgeClass = '';
        $badgeText = '';

        if ($row['status'] === 'approved') {
            $badgeClass = 'registered';
            $badgeText = 'Approved';
        } elseif ($row['status'] === 'declined') {
            $badgeClass = 'declined';
            $badgeText = 'Declined';
        } elseif ($row['verified'] == 1) {
            $badgeClass = 'verified';
            $badgeText = 'Email Verified Only';
        } else {
            $badgeClass = 'pending';
            $badgeText = 'Pending Verification';
        }

        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['fullname']}</td>
                <td>{$row['email']}</td>
                <td><span class='status-badge $badgeClass'>$badgeText</span></td>
                <td><button class='view-btn' data-id='{$row['id']}'>👁 View</button></td>
              </tr>";
    }
    echo '</tbody></table>';
} else {
    echo "<p>No contestants found.</p>";
}
?>
