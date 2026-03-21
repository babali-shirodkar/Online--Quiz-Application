<?php
header("Content-Type: application/json");
require_once "../../confi/database.php";
include "../../../userAccess.php";


$quiz_id = $_GET['quiz_id'] ?? 0;

if(!$quiz_id){
    echo json_encode([
        "status" => "error",
        "message" => "Invalid quiz id"
    ]);
    exit;
}

$sql = "SELECT 
title,
duration,
total_questions,
total_marks
FROM quizzes
WHERE quiz_id = ? AND status='published'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$quiz_id);
$stmt->execute();
$result = $stmt->get_result();

if($row = $result->fetch_assoc()){

    echo json_encode([
        "status" => "success",
        "data" => $row
    ]);

}else{

    echo json_encode([
        "status" => "error",
        "message" => "Quiz not found"
    ]);
}