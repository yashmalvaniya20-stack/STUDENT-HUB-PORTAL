<?php
require_once "db.php";

$id = intval($_GET["id"] ?? 0);

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: index.php?message=" . urlencode("Student record deleted successfully!"));
        exit();
    } else {
        $stmt->close();
        header("Location: index.php?error=" . urlencode("Could not delete record: " . $conn->error));
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>