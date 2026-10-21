<?php
require_once "db.php";

$mysqliStatus = false;
$mysqliMessage = "";
if ($conn && !$conn->connect_error) {
    $mysqliStatus = true;
    $mysqliMessage = "Connected successfully via MySQLi to database: " . $dbname;
} else {
    $mysqliMessage = "MySQLi connection error: " . ($conn ? $conn->connect_error : "Server unavailable");
}

$pdoStatus = false;
$pdoMessage = "";
if ($pdo !== null) {
    $pdoStatus = true;
    $pdoMessage = "Connected successfully via PDO to database: " . $dbname;
} else {
    $pdoMessage = "PDO connection error: Server unavailable or database does not exist.";
}

$studentsCount = 0;
$eventsCount = 0;
$registrationsCount = 0;

if ($mysqliStatus) {
    $r1 = $conn->query("SELECT COUNT(*) AS total FROM students");
    if ($r1) {
        $studentsCount = $r1->fetch_assoc()["total"];
    }
    $r2 = $conn->query("SELECT COUNT(*) AS total FROM events");
    if ($r2) {
        $eventsCount = $r2->fetch_assoc()["total"];
    }
    $r3 = $conn->query("SELECT COUNT(*) AS total FROM registrations");
    if ($r3) {
        $registrationsCount = $r3->fetch_assoc()["total"];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Test - Practical 8</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>StudentHub Portal</h1>
        <p>Practical 8: MySQL Schema & Database Connectivity Test</p>
    </div>

    <div class="container">
        <div class="card">
            <h2>Connection Diagnostics</h2>

            <div class="status-block <?php echo $mysqliStatus ? 'status-ok' : 'status-fail'; ?>">
                <h3>MySQLi Connection Status</h3>
                <p><?php echo $mysqliMessage; ?></p>
            </div>

            <div class="status-block <?php echo $pdoStatus ? 'status-ok' : 'status-fail'; ?>">
                <h3>PDO Connection Status</h3>
                <p><?php echo $pdoMessage; ?></p>
            </div>

            <?php if ($mysqliStatus): ?>
                <h3>Database Record Summary</h3>
                <div class="stats-grid">
                    <div class="stat-card">
                        <h4>Students</h4>
                        <div class="stat-val"><?php echo $studentsCount; ?></div>
                    </div>
                    <div class="stat-card">
                        <h4>Events</h4>
                        <div class="stat-val"><?php echo $eventsCount; ?></div>
                    </div>
                    <div class="stat-card">
                        <h4>Registrations</h4>
                        <div class="stat-val"><?php echo $registrationsCount; ?></div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">
                    <p>To initialize the database, open phpMyAdmin or MySQL console and import <strong>schema.sql</strong> located in this folder.</p>
                </div>
            <?php endif; ?>

            <div class="action-buttons">
                <a href="index.php" class="btn btn-secondary">Back to Overview</a>
                <a href="test_connection.php" class="btn btn-primary">Refresh Test</a>
            </div>
        </div>
    </div>
</body>
</html>