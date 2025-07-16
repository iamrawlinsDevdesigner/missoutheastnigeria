<?php
session_start();
include '../includes/db.php';
include '../includes/mailer.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(403);
    echo "Unauthorized.";
    exit();
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM contestants WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $c = $result->fetch_assoc();
    echo "<h2>{$c['fullname']}</h2>";
    echo "<p>Email: {$c['email']}</p>";
    echo "<p>Status: <strong>{$c['status']}</strong></p>";
    echo "<p>Phone: {$c['phone']}</p>";
    echo "<p>City: {$c['city']}</p>";
    echo "<p>Nationality: {$c['nationality']}</p>";
    echo "<p>Photo:<br><img src='../uploads/{$c['photo']}' width='200'></p>";

    if ($c['status'] === 'declined') {
        echo "<p><strong>Decline Reason:</strong> {$c['decline_reason']}</p>";
    }

    if ($c['status'] !== 'approved') {
        echo "<button onclick=\"approveContestant($id)\">✅ Approve</button>
              <button onclick=\"showDeclineForm($id)\">❌ Decline</button>";
    }
    echo "<div id='declineForm$id' style='display:none; margin-top:10px;'>
            <label>Reason for Decline:</label>
            <select id='declineReason$id'>
                <option value='Photos did not meet requirements'>Photos did not meet requirements</option>
                <option value='Not qualified'>Not qualified</option>
                <option value='Other'>Other</option>
            </select>
            <textarea id='declineOther$id' placeholder='Custom reason (if Other selected)'></textarea>
            <button onclick=\"submitDecline($id)\">Send Decline</button>
          </div>";
} else {
    echo "Contestant not found.";
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function approveContestant(id) {
    Swal.fire({
        title: 'Approve Contestant?',
        text: "Are you sure you want to approve this contestant?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Approve!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}&action=approve`
            }).then(res => res.json())
              .then(data => {
                  Swal.fire(data.status, data.message, data.status);
                  setTimeout(() => location.reload(), 2000);
              });
        }
    });
}

function showDeclineForm(id) {
    document.getElementById('declineForm' + id).style.display = 'block';
}

function submitDecline(id) {
    let reason = document.getElementById('declineReason' + id).value;
    if (reason === 'Other') {
        reason = document.getElementById('declineOther' + id).value;
    }

    Swal.fire({
        title: 'Decline Contestant?',
        text: "Are you sure you want to decline this contestant?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Decline',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}&action=decline&reason=${encodeURIComponent(reason)}`
            }).then(res => res.json())
              .then(data => {
                  Swal.fire(data.status, data.message, data.status);
                  setTimeout(() => location.reload(), 2000);
              });
        }
    });
}
</script>
