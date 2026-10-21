<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$userRole = $_SESSION["role"] ?? "student";
$userName = $_SESSION["name"] ?? "User";

$countStudents = 0;
$countEvents = 0;
$countRegistrations = 0;
$countUsers = 0;

$r1 = $conn->query("SELECT COUNT(*) AS total FROM students");
if ($r1) $countStudents = $r1->fetch_assoc()["total"];

$r2 = $conn->query("SELECT COUNT(*) AS total FROM events");
if ($r2) $countEvents = $r2->fetch_assoc()["total"];

$r3 = $conn->query("SELECT COUNT(*) AS total FROM registrations");
if ($r3) $countRegistrations = $r3->fetch_assoc()["total"];

$r4 = $conn->query("SELECT COUNT(*) AS total FROM users");
if ($r4) $countUsers = $r4->fetch_assoc()["total"];

$regQuery = "
    SELECT r.registration_id, r.registration_date, r.status, s.name AS student_name, s.student_code, e.title AS event_title
    FROM registrations r
    JOIN students s ON r.student_id = s.student_id
    JOIN events e ON r.event_id = e.event_id
    ORDER BY r.registration_id DESC
    LIMIT 5
";
$latestRegs = $conn->query($regQuery);

$auditQuery = "SELECT * FROM audit_logs ORDER BY log_id DESC LIMIT 8";
$latestAudits = $conn->query($auditQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Practical 14</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>StudentHub Portal</h1>
        <p>Practical 14: Dynamic Admin Dashboard & Audit Trail</p>
    </div>

    <nav class="navbar">
        <div class="nav-brand">StudentHub Control Panel</div>
        <div class="nav-menu">
            <?php if ($userRole === "admin"): ?>
                <a href="dashboard.php" class="active">Overview</a>
                <a href="#stats">Statistics</a>
                <a href="#registrations">Registrations</a>
                <a href="#audits">Audit Trail</a>
                <a href="../practical11/index.php" target="_blank">Students CRUD</a>
                <a href="../practical12/index.php" target="_blank">Events CRUD</a>
            <?php else: ?>
                <a href="dashboard.php" class="active">My Overview</a>
                <a href="#events">Campus Events</a>
            <?php endif; ?>
            <a href="logout.php" class="nav-logout">Logout (<?php echo htmlspecialchars($userName); ?>)</a>
        </div>
    </nav>

    <div class="container container-wide">
        <?php if ($userRole !== "admin"): ?>
            <div class="alert alert-info">
                <h3>Student View (Non-Admin Restricted)</h3>
                <p>You are logged in as a student. Administrative metrics and audit logs are accessible only to administrators.</p>
            </div>
        <?php else: ?>
            <div id="stats" class="stats-grid">
                <div class="stat-card">
                    <h4>Total Students</h4>
                    <div class="stat-val"><?php echo $countStudents; ?></div>
                    <span class="stat-sub">Enrolled Learners</span>
                </div>
                <div class="stat-card">
                    <h4>Total Events</h4>
                    <div class="stat-val"><?php echo $countEvents; ?></div>
                    <span class="stat-sub">Campus Activities</span>
                </div>
                <div class="stat-card">
                    <h4>Registrations</h4>
                    <div class="stat-val"><?php echo $countRegistrations; ?></div>
                    <span class="stat-sub">Event Submissions</span>
                </div>
                <div class="stat-card">
                    <h4>System Accounts</h4>
                    <div class="stat-val"><?php echo $countUsers; ?></div>
                    <span class="stat-sub">Admins & Students</span>
                </div>
            </div>

            <div id="registrations" class="card" style="margin-bottom: 25px;">
                <div class="table-header">
                    <h2>Recent Event Registrations</h2>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student Code</th>
                                <th>Student Name</th>
                                <th>Event Title</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($latestRegs && $latestRegs->num_rows > 0): ?>
                                <?php while ($reg = $latestRegs->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $reg["registration_id"]; ?></td>
                                        <td><strong><?php echo htmlspecialchars($reg["student_code"]); ?></strong></td>
                                        <td><?php echo htmlspecialchars($reg["student_name"]); ?></td>
                                        <td><?php echo htmlspecialchars($reg["event_title"]); ?></td>
                                        <td><?php echo $reg["registration_date"]; ?></td>
                                        <td><span class="badge badge-success"><?php echo htmlspecialchars($reg["status"]); ?></span></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align:center;">No recent registrations found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="audits" class="card">
                <div class="table-header">
                    <h2>System Audit Logs (Action Traceability)</h2>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Log ID</th>
                                <th>Username</th>
                                <th>Action Performed</th>
                                <th>Details / Payload</th>
                                <th>IP Address</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($latestAudits && $latestAudits->num_rows > 0): ?>
                                <?php while ($log = $latestAudits->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $log["log_id"]; ?></td>
                                        <td><strong><?php echo htmlspecialchars($log["username"]); ?></strong></td>
                                        <td><span class="badge badge-log"><?php echo htmlspecialchars($log["action"]); ?></span></td>
                                        <td><?php echo htmlspecialchars($log["details"]); ?></td>
                                        <td><code><?php echo htmlspecialchars($log["ip_address"]); ?></code></td>
                                        <td><?php echo $log["created_at"]; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align:center;">No audit log records recorded yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>