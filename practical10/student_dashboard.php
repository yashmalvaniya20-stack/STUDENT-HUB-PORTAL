<?php
session_start();

$timeoutDuration = 600;

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
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
    <title>Student Dashboard - Practical 10</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>StudentHub Portal</h1>
        <p>Practical 10: Student Area (Role-Based Access Control)</p>
    </div>

    <div class="container container-wide">
        <div class="card">
            <div class="table-header">
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>!</h2>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>

            <div class="user-info-card">
                <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION["username"]); ?></p>
                <p><strong>Role:</strong> <span class="badge badge-student"><?php echo htmlspecialchars($_SESSION["role"]); ?></span></p>
                <p><strong>Session ID:</strong> <code><?php echo session_id(); ?></code></p>
                <p><strong>Session Timeout:</strong> Active (10 minutes idle limit)</p>
            </div>

            <div class="module-grid">
                <div class="module-item">
                    <h3>My Enrolled Courses</h3>
                    <p>Web Development Frameworks, Database Systems, Computer Networks.</p>
                </div>
                <div class="module-item">
                    <h3>Upcoming Events</h3>
                    <p>Tech Euphoria 2026, Web Craft Hackathon.</p>
                </div>
                <div class="module-item">
                    <h3>Academic Notices</h3>
                    <p>Internal examination schedule posted on notice board.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>