<?php
session_start();
require_once "db.php";
require_once "audit_helper.php";

$error = "";

if (isset($_SESSION["user_id"]) && $_SESSION["role"] === "admin") {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        $stmt = $conn->prepare("SELECT user_id, username, name, password, role FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user["password"])) {
            session_regenerate_id(true);
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["role"] = $user["role"];

            logAudit($conn, $user["user_id"], $user["username"], "USER_LOGIN", "Successful login to StudentHub");

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid username/email or password.";
            logAudit($conn, null, $username, "FAILED_LOGIN", "Failed login attempt for user: " . $username);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Practical 14</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>StudentHub Portal</h1>
        <p>Practical 14: Admin Dashboard with RBAC & Audit Trail</p>
    </div>

    <div class="container">
        <div class="card">
            <h2>Administrative Sign In</h2>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <input type="text" id="username" name="username" placeholder="Enter administrator username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter password" required>
                </div>

                <button type="submit" class="btn btn-primary">Login to Dashboard</button>
            </form>

            <div class="demo-credentials">
                <h4>Default Admin Access:</h4>
                <p>Username: <code>admin</code></p>
                <p>Password: <code>password123</code></p>
            </div>
        </div>
    </div>
</body>
</html>