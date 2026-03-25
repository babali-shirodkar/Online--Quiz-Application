<?php

header("Content-Type: application/json");

require_once "../../confi/database.php";
include "../../../userAccess.php";

try{

    /* READ JSON INPUT */
    $data = json_decode(file_get_contents("php://input"), true);

    $name = trim($data['name'] ?? '');
    $email = trim($data['email'] ?? '');
    $password_raw = $data['password'] ?? '';

    /* VALIDATION */

    if(!$name || !$email || !$password_raw){
        throw new Exception("All fields are required");
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        throw new Exception("Invalid email");
    }

    if(strlen($password_raw) < 5){
        throw new Exception("Password must be at least 5 characters");
    }

    /* CHECK EMAIL EXISTS */

    $check = $conn->prepare("SELECT user_id FROM users WHERE email=?");
    $check->bind_param("s",$email);
    $check->execute();

    if($check->get_result()->num_rows > 0){
        throw new Exception("Email already exists");
    }

    /* HASH PASSWORD */

    $password = password_hash($password_raw, PASSWORD_DEFAULT);

    /* INSERT */

    $stmt = $conn->prepare("
        INSERT INTO users (name,email,password,role)
        VALUES (?,?,?,'instructor')
    ");

    $stmt->bind_param("sss",$name,$email,$password);

    if(!$stmt->execute()){
        throw new Exception("Failed to add instructor");
    }

    echo json_encode([
        "status"=>"success",
        "message"=>"Instructor added successfully"
    ]);

}catch(Exception $e){

    echo json_encode([
        "status"=>"error",
        "message"=>$e->getMessage()
    ]);

}
?>