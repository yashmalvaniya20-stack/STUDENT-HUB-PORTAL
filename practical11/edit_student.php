<?php
require_once "db.php";

$id = intval($_GET["id"] ?? 0);
if ($id <= 0) {
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$student) {
    header("Location: index.php?error=" . urlencode("Student record not found."));
    exit();
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_code = trim($_POST["student_code"] ?? "");
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $mobile = trim($_POST["mobile"] ?? "");
    $course = trim($_POST["course"] ?? "");
    $year = trim($_POST["year"] ?? "");

    if (empty($student_code)) {
        $errors[] = "Student code is required.";
    }
    if (empty($name)) {
        $errors[] = "Student name is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }
    if (empty($mobile) || !preg_match("/^[0-9]{10}$/", $mobile)) {
        $errors[] = "10-digit mobile number is required.";
    }
    if (empty($course)) {
        $errors[] = "Course selection is required.";
    }
    if (empty($year)) {
        $errors[] = "Academic year is required.";
    }

    if (empty($errors)) {
        $check = $conn->prepare("SELECT student_id FROM students WHERE (student_code = ? OR email = ?) AND student_id != ?");
        $check->bind_param("ssi", $student_code, $email, $id);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $errors[] = "Another student with this code or email already exists.";
        }
        $check->close();
    }

    if (empty($errors)) {
        $updateStmt = $conn->prepare("UPDATE students SET student_code = ?, name = ?, email = ?, mobile = ?, course = ?, year = ? WHERE student_id = ?");
        $updateStmt->bind_param("ssssssi", $student_code, $name, $email, $mobile, $course, $year, $id);

        if ($updateStmt->execute()) {
            $updateStmt->close();
            header("Location: index.php?message=" . urlencode("Student record updated successfully!"));
            exit();
        } else {
            $errors[] = "Update failed: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Practical 11</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>StudentHub Portal</h1>
        <p>Practical 11: Edit Student Record</p>
    </div>

    <div class="container">
        <div class="card">
            <h2>Update Student #<?php echo $id; ?></h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $err): ?>
                            <li><?php echo $err; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="edit_student.php?id=<?php echo $id; ?>" method="POST">
                <div class="form-group">
                    <label for="student_code">Student Code / ID</label>
                    <input type="text" id="student_code" name="student_code" value="<?php echo htmlspecialchars($student["student_code"]); ?>" required>
                </div>

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($student["name"]); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student["email"]); ?>" required>
                </div>

                <div class="form-group">
                    <label for="mobile">Mobile Number</label>
                    <input type="tel" id="mobile" name="mobile" value="<?php echo htmlspecialchars($student["mobile"]); ?>" maxlength="10" required>
                </div>

                <div class="form-group">
                    <label for="course">Course</label>
                    <select id="course" name="course" required>
                        <option value="Information Technology" <?php echo $student["course"] === "Information Technology" ? "selected" : ""; ?>>Information Technology</option>
                        <option value="Computer Engineering" <?php echo $student["course"] === "Computer Engineering" ? "selected" : ""; ?>>Computer Engineering</option>
                        <option value="Computer Science" <?php echo $student["course"] === "Computer Science" ? "selected" : ""; ?>>Computer Science</option>
                        <option value="Artificial Intelligence" <?php echo $student["course"] === "Artificial Intelligence" ? "selected" : ""; ?>>Artificial Intelligence</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="year">Year of Study</label>
                    <select id="year" name="year" required>
                        <option value="1st Year" <?php echo $student["year"] === "1st Year" ? "selected" : ""; ?>>1st Year</option>
                        <option value="2nd Year" <?php echo $student["year"] === "2nd Year" ? "selected" : ""; ?>>2nd Year</option>
                        <option value="3rd Year" <?php echo $student["year"] === "3rd Year" ? "selected" : ""; ?>>3rd Year</option>
                        <option value="4th Year" <?php echo $student["year"] === "4th Year" ? "selected" : ""; ?>>4th Year</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update Changes</button>
            </form>

            <div class="footer-links">
                <a href="index.php" class="btn btn-secondary">Cancel & Back to List</a>
            </div>
        </div>
    </div>
</body>
</html>