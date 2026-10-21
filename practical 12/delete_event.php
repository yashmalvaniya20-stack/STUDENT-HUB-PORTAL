<?php
require_once "db.php";

$id = intval($_GET["id"] ?? 0);

if ($id > 0) {
    $stmt = $conn->prepare("SELECT poster FROM events WHERE event_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($event = $res->fetch_assoc()) {
        if (!empty($event["poster"]) && file_exists("uploads/" . $event["poster"])) {
            @unlink("uploads/" . $event["poster"]);
        }
    }
    $stmt->close();

    $delStmt = $conn->prepare("DELETE FROM events WHERE event_id = ?");
    $delStmt->bind_param("i", $id);
    if ($delStmt->execute()) {
        $delStmt->close();
        header("Location: index.php?message=" . urlencode("Event deleted successfully!"));
        exit();
    } else {
        $delStmt->close();
        header("Location: index.php?error=" . urlencode("Could not delete event: " . $conn->error));
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>