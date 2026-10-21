<?php
session_start();

$timeoutDuration = 600;

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION["role"] !== "admin") {
    header("Location: student_dashboard.php");
    exit();
}

if (isset($_SESSION["last_activity"]) && (time() - $_SESSION["last_activity"] > $timeoutDuration)) {
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit();
}

$_SESSION["last_activity"] = time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Practical 10</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>StudentHub Portal</h1>
        <p>Practical 10: Administrator Panel (RBAC Restricted)</p>
    </div>

    <div class="container container-wide">
        <div class="card">
            <div class="table-header">
                <h2>Admin Control Center - Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>!</h2>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>

            <div class="user-info-card">
                <p><strong>Administrator:</strong> <?php echo htmlspecialchars($_SESSION["username"]); ?></p>
                <p><strong>Privilege Level:</strong> <span class="badge badge-admin"><?php echo htmlspecialchars($_SESSION["role"]); ?></span></p>
                <p><strong>Session ID:</strong> <code><?php echo session_id(); ?></code></p>
                <p><strong>Session Security:</strong> Regenerated on login with active inactivity timeout.</p>
            </div>

            <div class="module-grid">
                <div class="module-item">
                    <h3>User & Student Administration</h3>
                    <p>Manage registered student profiles, roles, and credentials.</p>
                </div>
                <div class="module-item">
                    <h3>Event Publishing & Moderation</h3>
                    <p>Create new campus events, review poster uploads, manage capacity.</p>
                </div>
                <div class="module-item">
                    <h3>System Logs & Audit Trail</h3>
                    <p>Track administrator modifications, logins, and permission changes.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>