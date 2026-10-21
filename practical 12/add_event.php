<?php
require_once "db.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $event_date = trim($_POST["event_date"] ?? "");
    $venue = trim($_POST["venue"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $status = trim($_POST["status"] ?? "upcoming");

    if (empty($title)) {
        $errors[] = "Event title is required.";
    }
    if (empty($event_date)) {
        $errors[] = "Event date is required.";
    }
    if (empty($venue)) {
        $errors[] = "Venue is required.";
    }
    if (empty($category)) {
        $errors[] = "Category is required.";
    }

    $posterName = null;

    if (isset($_FILES["poster"]) && $_FILES["poster"]["error"] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES["poster"];
        $allowedExtensions = ["jpg", "jpeg", "png", "webp"];
        $maxSize = 2 * 1024 * 1024;

        if ($file["error"] !== UPLOAD_ERR_OK) {
            $errors[] = "File upload failed with error code: " . $file["error"];
        } else {
            $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExtensions)) {
                $errors[] = "Invalid file type. Only JPG, JPEG, PNG, and WEBP images are allowed.";
            }

            if ($file["size"] > $maxSize) {
                $errors[] = "File size exceeds the 2MB limit.";
            }

            if (empty($errors)) {
                if (!is_dir("uploads")) {
                    mkdir("uploads", 0777, true);
                }
                $posterName = "event_" . time() . "_" . mt_rand(1000, 9999) . "." . $ext;
                $destination = "uploads/" . $posterName;

                if (!move_uploaded_file($file["tmp_name"], $destination)) {
                    $errors[] = "Failed to save the uploaded poster.";
                    $posterName = null;
                }
            }
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO events (title, description, event_date, venue, category, status, poster) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $title, $description, $event_date, $venue, $category, $status, $posterName);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: index.php?message=" . urlencode("Event created successfully!"));
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
    <title>Add Event - Practical 12</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>StudentHub Portal</h1>
        <p>Practical 12: Create Event with Validated Poster Upload</p>
    </div>

    <div class="container">
        <div class="card">
            <h2>Add New Campus Event</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $err): ?>
                            <li><?php echo $err; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="add_event.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Event Title</label>
                    <input type="text" id="title" name="title" placeholder="e.g. Web Development Workshop" required>
                </div>

                <div class="form-group">
                    <label for="description">Event Description</label>
                    <textarea id="description" name="description" rows="3" placeholder="Brief summary of event schedule and agenda"></textarea>
                </div>

                <div class="form-group">
                    <label for="event_date">Event Date</label>
                    <input type="date" id="event_date" name="event_date" required>
                </div>

                <div class="form-group">
                    <label for="venue">Venue / Location</label>
                    <input type="text" id="venue" name="venue" placeholder="e.g. Seminar Hall 1 or Lab 301" required>
                </div>

                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <option value="">-- Select Category --</option>
                        <option value="Technical">Technical</option>
                        <option value="Coding">Coding</option>
                        <option value="Workshop">Workshop</option>
                        <option value="Cultural">Cultural</option>
                        <option value="Sports">Sports</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Event Status</label>
                    <select id="status" name="status" required>
                        <option value="upcoming">Upcoming</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="poster">Event Poster Image (Max 2MB: JPG, PNG, WEBP)</label>
                    <input type="file" id="poster" name="poster" accept=".jpg,.jpeg,.png,.webp">
                </div>

                <button type="submit" class="btn btn-primary">Create Event</button>
            </form>

            <div class="footer-links">
                <a href="index.php" class="btn btn-secondary">Cancel & Back to Events</a>
            </div>
        </div>
    </div>
</body>
</html>