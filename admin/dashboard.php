<?php
session_start();
include '../includes/db.php';




// Redirect if not logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

function get_site_mode($conn) {
    $sql = "SELECT site_mode FROM settings WHERE id = 1 LIMIT 1";
    $result = $conn->query($sql);
    return $result->num_rows > 0 ? $result->fetch_assoc()['site_mode'] : 'live';
}

$currentMode = get_site_mode($conn);

if (isset($_POST['toggle'])) {
    $newMode = $currentMode === 'live' ? 'maintenance' : 'live';
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
    <title>Admin Dashboard - Miss South East Nigeria</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header>
        <h1>Miss South East Nigeria - Admin Dashboard</h1>
        <div class="admin-info">
            ✅ Logged in as Admin | <a href="logout.php" class="logout">Logout</a>
        </div>
    </header>

    <section class="site-mode">
        <p>Current Site Mode: 
            <span class="badge <?php echo $currentMode == 'live' ? 'live' : 'maintenance'; ?>">
                <?php echo strtoupper($currentMode); ?>
            </span>
        </p>
        <form method="post">
            <button type="submit" name="toggle" class="toggle-btn">
                Switch to <?php echo $currentMode == 'live' ? 'Maintenance' : 'Live'; ?> Mode
            </button>
        </form>
    </section>

    <nav class="tabs">
        <button class="tab-btn active" data-status="all">All Submissions</button>
        <button class="tab-btn" data-status="approved">Approved</button>
        <button class="tab-btn" data-status="declined">Declined</button>
    </nav>

    <div id="contestantTable" class="table-container">
        <div class="loader">Loading contestants...</div>
    </div>

    <!-- Popup Modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <div id="modalBody">Loading...</div>
        </div>
    </div>

    <script src="../assets/js/dashboard.js"></script>
</body>
</html>
