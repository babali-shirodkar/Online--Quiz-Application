<?php 

header("Content-Type: application/json");

require_once "../../confi/database.php";

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';


if (empty($email) || empty($password)) {
    echo json_encode([
        "status" => "error",
        "message" => "Email and password are required"
    ]);
    exit;
}


$stmt = $conn->prepare("SELECT user_id, name, email, password, role, status FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();


if (!$user || !password_verify($password, $user['password'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email or password"
    ]);
    exit;
}


if ($user['status'] !== 'active') {
    echo json_encode([
        "status" => "error",
        "message" => "Your account is inactive. Please contact admin."
    ]);
    exit;
}




session_start();

$_SESSION['user_id'] = $user['user_id'];
$_SESSION['role'] = $user['role'];
$_SESSION['name'] = $user['name'];
$_SESSION['email'] = $user['email'];



unset($user['password']);

echo json_encode([
    "status" => "success",
    "message" => "Login successful",
    "data" => [
        "user_details" => $user
    ],
    "redirect" => "/quiz/pages/admin/index.php"
]);

exit;
?>