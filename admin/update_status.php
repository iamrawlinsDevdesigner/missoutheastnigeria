<?php
session_start();
include '../includes/db.php';
include '../includes/mailer.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(403);
    exit('Unauthorized');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $action = $_POST['action'];

    $stmt = $conn->prepare("SELECT email, fullname FROM contestants WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows != 1) {
        exit('Contestant not found.');
    }
    $row = $result->fetch_assoc();
    $email = $row['email'];
    $name = $row['fullname'];

    if ($action == 'approve') {
        $conn->query("UPDATE contestants SET status='approved' WHERE id=$id");
        sendApprovalEmail($email, $name);
        echo "✅ Contestant approved.";
    } elseif ($action == 'decline') {
        $reason = $conn->real_escape_string($_POST['reason']);
        $conn->query("UPDATE contestants SET status='declined', decline_reason='$reason' WHERE id=$id");
        sendDeclineEmail($email, $name, $reason);
        echo "❌ Contestant declined.";
    } else {
        echo "Invalid action.";
    }
}
