<?php
require_once "db.php";

$search = trim($_GET["search"] ?? "");
$courseFilter = trim($_GET["course"] ?? "");

$sql = "SELECT * FROM students WHERE 1=1";
$params = [];
$types = "";

if (!empty($search)) {
    $sql .= " AND (name LIKE ? OR email LIKE ? OR student_code LIKE ?)";
    $likeSearch = "%" . $search . "%";
    $params[] = $likeSearch;
    $params[] = $likeSearch;
    $params[] = $likeSearch;
    $types .= "sss";
}

if (!empty($courseFilter)) {
    $sql .= " AND course = ?";
    $params[] = $courseFilter;
    $types .= "s";
}

$sql .= " ORDER BY student_id DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$message = $_GET["message"] ?? "";
$error = $_GET["error"] ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management - Practical 11</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>StudentHub Portal</h1>
        <p>Practical 11: Student Management CRUD with Search & Filter</p>
    </div>

    <div class="container container-wide">
        <div class="card">
            <div class="table-header">
                <h2>Student Records</h2>
                <a href="add_student.php" class="btn btn-primary">+ Add New Student</a>
            </div>

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

            <form action="index.php" method="GET" class="filter-form">
                <input type="text" name="search" placeholder="Search by name, email, or code..." value="<?php echo htmlspecialchars($search); ?>">
                <select name="course">
                    <option value="">All Courses</option>
                    <option value="Information Technology" <?php echo $courseFilter === "Information Technology" ? "selected" : ""; ?>>Information Technology</option>
                    <option value="Computer Engineering" <?php echo $courseFilter === "Computer Engineering" ? "selected" : ""; ?>>Computer Engineering</option>
                    <option value="Computer Science" <?php echo $courseFilter === "Computer Science" ? "selected" : ""; ?>>Computer Science</option>
                    <option value="Artificial Intelligence" <?php echo $courseFilter === "Artificial Intelligence" ? "selected" : ""; ?>>Artificial Intelligence</option>
                </select>
                <button type="submit" class="btn btn-search">Filter</button>
                <?php if (!empty($search) || !empty($courseFilter)): ?>
                    <a href="index.php" class="btn btn-reset">Reset</a>
                <?php endif; ?>
            </form>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student Code</th>
                            <th>Full Name</th>
                            <th>Email Address</th>
                            <th>Mobile</th>
                            <th>Course</th>
                            <th>Year</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row["student_id"]; ?></td>
                                    <td><strong><?php echo htmlspecialchars($row["student_code"]); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row["name"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["email"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["mobile"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["course"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["year"]); ?></td>
                                    <td class="action-cell">
                                        <a href="edit_student.php?id=<?php echo $row["student_id"]; ?>" class="btn-sm btn-edit">Edit</a>
                                        <a href="delete_student.php?id=<?php echo $row["student_id"]; ?>" class="btn-sm btn-delete" onclick="return confirm('Are you sure you want to delete this student record?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" style="text-align: center;">No matching student records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>