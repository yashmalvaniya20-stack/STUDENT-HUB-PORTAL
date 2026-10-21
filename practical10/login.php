<?php
session_start();
require_once "db.php";

if (isset($_SESSION["user_id"])) {
    if ($_SESSION["role"] === "admin") {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        header("Location: student_dashboard.php");
        exit();
    }
}

if (!isset($_SESSION["user_id"]) && isset($_COOKIE["remember_user"])) {
    $tokenUser = $_COOKIE["remember_user"];
    $stmt = $conn->prepare("SELECT user_id, username, name, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $tokenUser);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($user = $res->fetch_assoc()) {
        session_regenerate_id(true);
        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["name"] = $user["name"];
        $_SESSION["role"] = $user["role"];
        $_SESSION["last_activity"] = time();

        if ($user["role"] === "admin") {
            header("Location: admin_dashboard.php");
            exit();
        } else {
            header("Location: student_dashboard.php");
            exit();
        }
    }
    $stmt->close();
}

$error = "";
$message = "";

if (isset($_GET["timeout"])) {
    $error = "Your session expired due to inactivity. Please log in again.";
}
if (isset($_GET["logged_out"])) {
    $message = "You have been logged out successfully.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $remember = isset($_POST["remember"]);

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        $stmt = $conn->prepare("SELECT user_id, username, name, password, role FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($user = $res->fetch_assoc()) {
            if (password_verify($password, $user["password"])) {
                session_regenerate_id(true);
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["name"] = $user["name"];
                $_SESSION["role"] = $user["role"];
                $_SESSION["last_activity"] = time();

                if ($remember) {
                    setcookie("remember_user", $user["username"], time() + (86400 * 30), "/");
                }

                if ($user["role"] === "admin") {
                    header("Location: admin_dashboard.php");
                    exit();
                } else {
                    header("Location: student_dashboard.php");
                    exit();
                }
            } else {
                $error = "Invalid password. Please check and try again.";
            }
        } else {
            $error = "Account not found for that username/email.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Login - Practical 10</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>StudentHub Portal</h1>
        <p>Practical 10: Secure Login, Sessions, RBAC & Timeout</p>
    </div>

    <div class="container">
        <div class="card">
            <h2>Sign In</h2>

            <?php if (!empty($message)): ?>
                <div class="alert alert-success">
                    <p><?php echo htmlspecialchars($message); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <input type="text" id="username" name="username" placeholder="Enter username or email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>

                <div class="form-check">
                    <label><input type="checkbox" name="remember" value="1"> Remember me</label>
                </div>

                <button type="submit" class="btn btn-primary">Login to Account</button>
            </form>

            <div class="demo-credentials">
                <h4>Demo Credentials:</h4>
                <p><strong>Admin:</strong> Username: <code>admin</code> | Password: <code>password123</code></p>
                <p><strong>Student:</strong> Username: <code>student</code> | Password: <code>password123</code></p>
            </div>
        </div>
    </div>
</body>
</html>