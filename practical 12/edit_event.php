<?php
require_once "db.php";

$id = intval($_GET["id"] ?? 0);
if ($id <= 0) {
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM events WHERE event_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$event) {
    header("Location: index.php?error=" . urlencode("Event not found."));
    exit();
}

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

    $posterName = $event["poster"];

    if (isset($_FILES["poster"]) && $_FILES["poster"]["error"] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES["poster"];
        $allowedExtensions = ["jpg", "jpeg", "png", "webp"];
        $maxSize = 2 * 1024 * 1024;

        if ($file["error"] !== UPLOAD_ERR_OK) {
            $errors[] = "File upload failed with error: " . $file["error"];
        } else {
            $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExtensions)) {
                $errors[] = "Only JPG, JPEG, PNG, and WEBP formats are allowed.";
            }

            if ($file["size"] > $maxSize) {
                $errors[] = "File size exceeds 2MB limit.";
            }

            if (empty($errors)) {
                if (!is_dir("uploads")) {
                    mkdir("uploads", 0777, true);
                }
                $newPosterName = "event_" . time() . "_" . mt_rand(1000, 9999) . "." . $ext;
                $destination = "uploads/" . $newPosterName;

                if (move_uploaded_file($file["tmp_name"], $destination)) {
                    if (!empty($event["poster"]) && file_exists("uploads/" . $event["poster"])) {
                        @unlink("uploads/" . $event["poster"]);
                    }
                    $posterName = $newPosterName;
                } else {
                    $errors[] = "Failed to upload new poster.";
                }
            }
        }
    }

    if (empty($errors)) {
        $updateStmt = $conn->prepare("UPDATE events SET title = ?, description = ?, event_date = ?, venue = ?, category = ?, status = ?, poster = ? WHERE event_id = ?");
        $updateStmt->bind_param("sssssssi", $title, $description, $event_date, $venue, $category, $status, $posterName, $id);

        if ($updateStmt->execute()) {
            $updateStmt->close();
            header("Location: index.php?message=" . urlencode("Event updated successfully!"));
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
    <title>Edit Event - Practical 12</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>StudentHub Portal</h1>
        <p>Practical 12: Modify Event & Poster</p>
    </div>

    <div class="container">
        <div class="card">
            <h2>Update Event #<?php echo $id; ?></h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $err): ?>
                            <li><?php echo $err; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="edit_event.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Event Title</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($event["title"]); ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">Event Description</label>
                    <textarea id="description" name="description" rows="3"><?php echo htmlspecialchars($event["description"]); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="event_date">Event Date</label>
                    <input type="date" id="event_date" name="event_date" value="<?php echo htmlspecialchars($event["event_date"]); ?>" required>
                </div>

                <div class="form-group">
                    <label for="venue">Venue / Location</label>
                    <input type="text" id="venue" name="venue" value="<?php echo htmlspecialchars($event["venue"]); ?>" required>
                </div>

                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <option value="Technical" <?php echo $event["category"] === "Technical" ? "selected" : ""; ?>>Technical</option>
                        <option value="Coding" <?php echo $event["category"] === "Coding" ? "selected" : ""; ?>>Coding</option>
                        <option value="Workshop" <?php echo $event["category"] === "Workshop" ? "selected" : ""; ?>>Workshop</option>
                        <option value="Cultural" <?php echo $event["category"] === "Cultural" ? "selected" : ""; ?>>Cultural</option>
                        <option value="Sports" <?php echo $event["category"] === "Sports" ? "selected" : ""; ?>>Sports</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Event Status</label>
                    <select id="status" name="status" required>
                        <option value="upcoming" <?php echo $event["status"] === "upcoming" ? "selected" : ""; ?>>Upcoming</option>
                        <option value="completed" <?php echo $event["status"] === "completed" ? "selected" : ""; ?>>Completed</option>
                        <option value="cancelled" <?php echo $event["status"] === "cancelled" ? "selected" : ""; ?>>Cancelled</option>
                    </select>
                </div>

                <?php if (!empty($event["poster"]) && file_exists("uploads/" . $event["poster"])): ?>
                    <div class="form-group">
                        <label>Current Poster Preview:</label>
                        <img src="uploads/<?php echo htmlspecialchars($event["poster"]); ?>" alt="Current Poster" style="max-width: 150px; border-radius: 6px; display: block; margin-top: 6px;">
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="poster">Change Poster (Leave empty to keep existing)</label>
                    <input type="file" id="poster" name="poster" accept=".jpg,.jpeg,.png,.webp">
                </div>

                <button type="submit" class="btn btn-primary">Update Event</button>
            </form>

            <div class="footer-links">
                <a href="index.php" class="btn btn-secondary">Cancel & Back to Events</a>
            </div>
        </div>
    </div>
</body>
</html>