<?php

header("Content-Type: application/json");

require_once "../../confi/database.php";
include "../../../userAccess.php";

try{

    $data = json_decode(file_get_contents("php://input"), true);

    if(!isset($user_id)){
        throw new Exception("Unauthorized");
    }

    $quiz_id = $data['quiz_id'] ?? 0;

    if(!$quiz_id){
        throw new Exception("Quiz ID missing");
    }

    /* FETCH QUIZ */

    $stmt = $conn->prepare("
        SELECT * FROM quizzes WHERE quiz_id = ?
    ");

    $stmt->bind_param("i", $quiz_id);

    if(!$stmt->execute()){
        throw new Exception($stmt->error);
    }

    $result = $stmt->get_result();

    if($result->num_rows == 0){
        throw new Exception("Quiz not found");
    }

    $quiz = $result->fetch_assoc();

    echo json_encode([
        "status" => "success",
        "quiz" => $quiz
    ]);

}catch(Exception $e){

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);

}