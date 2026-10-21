<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "studenthub";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    header("Content-Type: application/json");
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}
?>