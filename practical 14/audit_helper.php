<?php
function logAudit($conn, $userId, $username, $action, $details) {
    $ip = $_SERVER["REMOTE_ADDR"] ?? "127.0.0.1";
    $stmt = $conn->prepare("INSERT INTO audit_logs (user_id, username, action, details, ip_address) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("issss", $userId, $username, $action, $details, $ip);
        $stmt->execute();
        $stmt->close();
    }
}
?>