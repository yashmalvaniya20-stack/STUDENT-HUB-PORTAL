<?php
require_once "db.php";

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
        $check = $conn->prepare("SELECT student_id FROM students WHERE student_code = ? OR email = ?");
        $check->bind_param("ss", $student_code, $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $errors[] = "Student code or Email already exists.";
        }
        $check->close();
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO students (student_code, name, email, mobile, course, year) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $student_code, $name, $email, $mobile, $course, $year);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: index.php?message=" . urlencode("Student record added successfully!"));
            exit();
        } else {
            $errors[] = "Database insert error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - Practical 11</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>StudentHub Portal</h1>
        <p>Practical 11: Add New Student Record</p>
    </div>

    <div class="container">
        <div class="card">
            <h2>New Student Registration</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $err): ?>
                            <li><?php echo $err; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="add_student.php" method="POST">
                <div class="form-group">
                    <label for="student_code">Student Code / ID</label>
                    <input type="text" id="student_code" name="student_code" placeholder="e.g. 23IT025" required>
                </div>

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="Student's full name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="student@charusat.edu.in" required>
                </div>

                <div class="form-group">
                    <label for="mobile">Mobile Number</label>
                    <input type="tel" id="mobile" name="mobile" placeholder="10-digit phone number" maxlength="10" required>
                </div>

                <div class="form-group">
                    <label for="course">Course</label>
                    <select id="course" name="course" required>
                        <option value="">-- Select Course --</option>
                        <option value="Information Technology">Information Technology</option>
                        <option value="Computer Engineering">Computer Engineering</option>
                        <option value="Computer Science">Computer Science</option>
                        <option value="Artificial Intelligence">Artificial Intelligence</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="year">Year of Study</label>
                    <select id="year" name="year" required>
                        <option value="">-- Select Year --</option>
                        <option value="1st Year">1st Year</option>
                        <option value="2nd Year">2nd Year</option>
                        <option value="3rd Year">3rd Year</option>
                        <option value="4th Year">4th Year</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Save Student Record</button>
            </form>

            <div class="footer-links">
                <a href="index.php" class="btn btn-secondary">Cancel & Back to List</a>
            </div>
        </div>
    </div>
</body>
</html>