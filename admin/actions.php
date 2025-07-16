<?php
session_start();
include '../includes/db.php';
include '../includes/mailer.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

$id = intval($_POST['id']);
$action = $_POST['action'];
$reason = $_POST['reason'] ?? '';

$sql = "SELECT email, fullname FROM contestants WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo json_encode(['status' => 'error', 'message' => 'Contestant not found.']);
    exit();
}

$c = $result->fetch_assoc();
$email = $c['email'];
$name = $c['fullname'];

if ($action === 'approve') {
    $update = $conn->prepare("UPDATE contestants SET status='approved' WHERE id=?");
    $update->bind_param('i', $id);
    if ($update->execute()) {
        sendApprovalEmail($email, $name);
        echo json_encode(['status' => 'success', 'message' => 'Contestant approved and email sent.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to approve contestant.']);
    }
} elseif ($action === 'decline') {
    $update = $conn->prepare("UPDATE contestants SET status='declined', decline_reason=? WHERE id=?");
    $update->bind_param('si', $reason, $id);
    if ($update->execute()) {
        sendDeclineEmail($email, $name, $reason);
        echo json_encode(['status' => 'success', 'message' => 'Contestant declined and email sent.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to decline contestant.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
}
?>
