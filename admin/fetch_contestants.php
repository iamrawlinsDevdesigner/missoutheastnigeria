<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(403);
    exit('Unauthorized');
}

$status = isset($_GET['status']) ? $_GET['status'] : 'all';

// Build query based on tab
$where = "";
if ($status === "approved") {
    $where = "WHERE status='approved'";
} elseif ($status === "declined") {
    $where = "WHERE status='declined'";
}

$sql = "SELECT id, fullname, email, verified, status, decline_reason 
        FROM contestants $where ORDER BY id DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Fullname</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): 
            $badge = "";
            if ($row['status'] === 'approved') {
                $badge = "<span class='badge green'>Approved</span>";
            } elseif ($row['status'] === 'declined') {
                $badge = "<span class='badge red'>Declined</span>";
            } elseif ($row['verified'] == 1 && $row['fullname']) {
                $badge = "<span class='badge green'>Registered</span>";
            } elseif ($row['verified'] == 1) {
                $badge = "<span class='badge yellow'>Email Verified Only</span>";
            } else {
                $badge = "<span class='badge gray'>Pending Verification</span>";
            }
        ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= htmlspecialchars($row['fullname'] ?: 'N/A'); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td><?= $badge; ?></td>
                <td>
                    <button class="view-btn" data-id="<?= $row['id']; ?>">View</button>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No contestants found for this tab.</p>
<?php endif; ?>
