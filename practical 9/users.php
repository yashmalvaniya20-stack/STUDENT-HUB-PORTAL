<?php
require_once "db.php";

$result = $conn->query("SELECT user_id, username, name, email, password, role, created_at FROM users ORDER BY user_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List - Practical 9</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>StudentHub Portal</h1>
        <p>Practical 9: Registered Users Database View</p>
    </div>

    <div class="container container-wide">
        <div class="card">
            <div class="table-header">
                <h2>Registered Accounts</h2>
                <a href="register.php" class="btn btn-primary">+ Register Another User</a>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Hashed Password (BCrypt)</th>
                            <th>Role</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row["user_id"]; ?></td>
                                    <td><strong><?php echo htmlspecialchars($row["username"]); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row["name"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["email"]); ?></td>
                                    <td><code class="hash-text"><?php echo htmlspecialchars(substr($row["password"], 0, 30)) . "..."; ?></code></td>
                                    <td><span class="badge badge-<?php echo $row["role"]; ?>"><?php echo htmlspecialchars($row["role"]); ?></span></td>
                                    <td><?php echo $row["created_at"]; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align:center;">No users registered yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="footer-links">
                <a href="register.php" class="btn btn-secondary">Back to Registration</a>
            </div>
        </div>
    </div>
</body>
</html>