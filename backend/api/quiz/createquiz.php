<?php
header("Content-Type: application/json");
require_once "../../confi/database.php";
include "../../../userAccess.php";

$json_data = file_get_contents("php://input");
$data = json_decode($json_data, true);

$title = $data['title'] ?? '';
$category_id = $data['category_id'] ?? '';
$duration = $data['duration'] ?? '';
$total_marks = $data['total_marks'] ?? '';
$total_questions = $data['total_questions'] ?? '';
$created_by = $_SESSION['user_id'] ?? 0;

if(empty($title) || empty($category_id) || empty($duration)){
    echo json_encode([
        "status"=>"error",
        "message"=>"Required fields missing"
    ]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO quizzes 
(category_id,title,duration,total_marks,total_questions,created_by)
VALUES (?,?,?,?,?,?)");

$stmt->bind_param(
    "isiiii",
    $category_id,
    $title,
    $duration,
    $total_marks,
    $total_questions,
    $created_by
);

if($stmt->execute()){

    $quiz_id = $conn->insert_id; 

    echo json_encode([
        "status"=>"success",
        "message"=>"Quiz created successfully",
        "quiz_id"=>$quiz_id  
    ]);

}else{

    echo json_encode([
        "status"=>"error",
        "message"=>"Failed to create quiz"
    ]);

}

?>