<?php
header("Content-Type: application/json; charset=UTF-8");
require_once "db.php";

$action = $_GET["action"] ?? $_POST["action"] ?? "";

if ($action === "list") {
    $result = $conn->query("SELECT * FROM students ORDER BY student_id DESC");
    $students = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    }
    echo json_encode(["status" => "success", "data" => $students]);
    exit();
}

if ($action === "get") {
    $id = intval($_GET["id"] ?? 0);
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($data) {
        echo json_encode(["status" => "success", "data" => $data]);
    } else {
        echo json_encode(["status" => "error", "message" => "Student not found"]);
    }
    exit();
}

if ($action === "add") {
    $student_code = trim($_POST["student_code"] ?? "");
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $mobile = trim($_POST["mobile"] ?? "");
    $course = trim($_POST["course"] ?? "");
    $year = trim($_POST["year"] ?? "");

    if (empty($student_code) || empty($name) || empty($email) || empty($mobile) || empty($course) || empty($year)) {
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email format"]);
        exit();
    }

    $check = $conn->prepare("SELECT student_id FROM students WHERE student_code = ? OR email = ?");
    $check->bind_param("ss", $student_code, $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Student Code or Email already exists"]);
        $check->close();
        exit();
    }
    $check->close();

    $stmt = $conn->prepare("INSERT INTO students (student_code, name, email, mobile, course, year) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $student_code, $name, $email, $mobile, $course, $year);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Student added successfully", "id" => $stmt->insert_id]);
    } else {
        echo json_encode(["status" => "error", "message" => "Insert failed: " . $conn->error]);
    }
    $stmt->close();
    exit();
}

if ($action === "edit") {
    $id = intval($_POST["student_id"] ?? 0);
    $student_code = trim($_POST["student_code"] ?? "");
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $mobile = trim($_POST["mobile"] ?? "");
    $course = trim($_POST["course"] ?? "");
    $year = trim($_POST["year"] ?? "");

    if ($id <= 0 || empty($student_code) || empty($name) || empty($email) || empty($mobile) || empty($course) || empty($year)) {
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        exit();
    }

    $check = $conn->prepare("SELECT student_id FROM students WHERE (student_code = ? OR email = ?) AND student_id != ?");
    $check->bind_param("ssi", $student_code, $email, $id);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Duplicate Student Code or Email"]);
        $check->close();
        exit();
    }
    $check->close();

    $stmt = $conn->prepare("UPDATE students SET student_code = ?, name = ?, email = ?, mobile = ?, course = ?, year = ? WHERE student_id = ?");
    $stmt->bind_param("ssssssi", $student_code, $name, $email, $mobile, $course, $year, $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Student updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Update failed: " . $conn->error]);
    }
    $stmt->close();
    exit();
}

if ($action === "delete") {
    $id = intval($_POST["student_id"] ?? 0);
    if ($id <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid student ID"]);
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Student deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Delete failed: " . $conn->error]);
    }
    $stmt->close();
    exit();
}

echo json_encode(["status" => "error", "message" => "Invalid API action"]);
exit();
?>