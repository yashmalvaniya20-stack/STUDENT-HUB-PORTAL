<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

$fullName = trim($_POST["fullName"] ?? "");
$email = trim($_POST["email"] ?? "");
$mobile = trim($_POST["mobile"] ?? "");
$course = trim($_POST["course"] ?? "");
$year = trim($_POST["year"] ?? "");
$gender = trim($_POST["gender"] ?? "");
$message = trim($_POST["message"] ?? "");

$fullName = htmlspecialchars($fullName, ENT_QUOTES, "UTF-8");
$email = htmlspecialchars($email, ENT_QUOTES, "UTF-8");
$mobile = htmlspecialchars($mobile, ENT_QUOTES, "UTF-8");
$course = htmlspecialchars($course, ENT_QUOTES, "UTF-8");
$year = htmlspecialchars($year, ENT_QUOTES, "UTF-8");
$gender = htmlspecialchars($gender, ENT_QUOTES, "UTF-8");
$message = htmlspecialchars($message, ENT_QUOTES, "UTF-8");

$errors = [];

if (empty($fullName)) {
    $errors[] = "Full name is required.";
} elseif (strlen($fullName) < 3) {
    $errors[] = "Full name must be at least 3 characters long.";
}

if (empty($email)) {
    $errors[] = "Email address is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please provide a valid email address.";
}

if (empty($mobile)) {
    $errors[] = "Mobile number is required.";
} elseif (!preg_match("/^[0-9]{10}$/", $mobile)) {
    $errors[] = "Mobile number must be exactly 10 digits.";
}

if (empty($course)) {
    $errors[] = "Course selection is required.";
}

if (empty($year)) {
    $errors[] = "Academic year selection is required.";
}

if (empty($gender)) {
    $errors[] = "Gender selection is required.";
}

$success = false;

if (empty($errors)) {
    $csvFile = "registrations.csv";
    $jsonFile = "registrations.json";
    $submittedAt = date("Y-m-d H:i:s");

    $fileNeedsHeader = !file_exists($csvFile) || filesize($csvFile) === 0;
    $fp = fopen($csvFile, "a");
    if ($fp) {
        if ($fileNeedsHeader) {
            fputcsv($fp, ["Full Name", "Email", "Mobile", "Course", "Year", "Gender", "Message", "Submitted At"]);
        }
        fputcsv($fp, [$fullName, $email, $mobile, $course, $year, $gender, $message, $submittedAt]);
        fclose($fp);
    }

    $existingData = [];
    if (file_exists($jsonFile) && filesize($jsonFile) > 0) {
        $jsonContent = file_get_contents($jsonFile);
        $decoded = json_decode($jsonContent, true);
        if (is_array($decoded)) {
            $existingData = $decoded;
        }
    }

    $newRecord = [
        "fullName" => $fullName,
        "email" => $email,
        "mobile" => $mobile,
        "course" => $course,
        "year" => $year,
        "gender" => $gender,
        "message" => $message,
        "submittedAt" => $submittedAt
    ];
    $existingData[] = $newRecord;
    file_put_contents($jsonFile, json_encode($existingData, JSON_PRETTY_PRINT));

    $success = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Status - Practical 7</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>StudentHub Portal</h1>
        <p>Practical 7: Form Processing Result</p>
    </div>

    <div class="container">
        <div class="card">
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <h3>Registration Successful!</h3>
                    <p>Your details have been validated and saved to both CSV and JSON files.</p>
                </div>

                <div class="summary-box">
                    <p><strong>Name:</strong> <?php echo $fullName; ?></p>
                    <p><strong>Email:</strong> <?php echo $email; ?></p>
                    <p><strong>Mobile:</strong> <?php echo $mobile; ?></p>
                    <p><strong>Course:</strong> <?php echo $course; ?></p>
                    <p><strong>Year:</strong> <?php echo $year; ?></p>
                    <p><strong>Gender:</strong> <?php echo $gender; ?></p>
                    <p><strong>Message:</strong> <?php echo $message ? $message : "N/A"; ?></p>
                    <p><strong>Timestamp:</strong> <?php echo $submittedAt; ?></p>
                </div>

                <div class="action-buttons">
                    <a href="index.php" class="btn btn-primary">Register Another Student</a>
                    <a href="records.php" class="btn btn-secondary">View All Stored Records</a>
                </div>

            <?php else: ?>
                <div class="alert alert-danger">
                    <h3>Form Submission Failed</h3>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="action-buttons">
                    <a href="javascript:history.back()" class="btn btn-primary">Go Back & Correct Errors</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>