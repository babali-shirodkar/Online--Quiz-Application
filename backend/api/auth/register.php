<?php

header("Content-Type: application/json");

require_once "../../confi/database.php";

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

$fullname = $data['fullname'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';
$confirm_password = $data['confirm_password'] ?? '';

if (empty($fullname) || empty($email) || empty($password) || empty($confirm_password)) {
    echo json_encode([
        "status" => "error",
        "message" => "All fields are required"
    ]);
    exit;
}

if ($password !== $confirm_password) {
    echo json_encode([
        "status" => "error",
        "message" => "Passwords do not match"
    ]);
    exit;
}

$check = $conn->prepare("SELECT user_id FROM users WHERE email=?");
$check->bind_param("s", $email);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Email already registered"
    ]);
    exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users(name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $fullname, $email, $hashed);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Account created successfully"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $stmt->error
    ]);
}

?>