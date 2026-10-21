<?php
require_once "db.php";

$message = $_GET["message"] ?? "";
$error = $_GET["error"] ?? "";

$sql = "SELECT * FROM events ORDER BY event_date ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management - Practical 12</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>StudentHub Portal</h1>
        <p>Practical 12: Event Management CRUD with Poster Upload & File Validation</p>
    </div>

    <div class="container container-wide">
        <div class="card">
            <div class="table-header">
                <h2>Campus Events List</h2>
                <a href="add_event.php" class="btn btn-primary">+ Add New Event</a>
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

            <div class="events-grid">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="event-card">
                            <div class="event-poster">
                                <?php if (!empty($row["poster"]) && file_exists("uploads/" . $row["poster"])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($row["poster"]); ?>" alt="Event Poster">
                                <?php else: ?>
                                    <div class="no-poster">No Poster Available</div>
                                <?php endif; ?>
                            </div>
                            <div class="event-body">
                                <div class="event-meta">
                                    <span class="badge badge-category"><?php echo htmlspecialchars($row["category"]); ?></span>
                                    <span class="badge badge-<?php echo htmlspecialchars($row["status"]); ?>"><?php echo htmlspecialchars($row["status"]); ?></span>
                                </div>
                                <h3><?php echo htmlspecialchars($row["title"]); ?></h3>
                                <p class="event-desc"><?php echo htmlspecialchars($row["description"]); ?></p>
                                <p class="event-detail"><strong>Date:</strong> <?php echo htmlspecialchars($row["event_date"]); ?></p>
                                <p class="event-detail"><strong>Venue:</strong> <?php echo htmlspecialchars($row["venue"]); ?></p>
                                <div class="event-actions">
                                    <a href="edit_event.php?id=<?php echo $row["event_id"]; ?>" class="btn-sm btn-edit">Edit</a>
                                    <a href="delete_event.php?id=<?php echo $row["event_id"]; ?>" class="btn-sm btn-delete" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="alert alert-info" style="grid-column: 1 / -1;">
                        <p>No events found. Click "+ Add New Event" above to create one.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>