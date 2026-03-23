<?php

header("Content-Type: application/json");

require_once "../../confi/database.php";
include "../../../userAccess.php";

try{

    /* GET INPUT */

    $quiz_id = $_GET['quiz_id'] ?? 0;

    if(!$quiz_id){
        throw new Exception("Quiz ID missing");
    }

    /* FETCH QUIZ */

    $stmt = $conn->prepare("
        SELECT 
            title,
            duration,
            total_questions,
            total_marks
        FROM quizzes
        WHERE quiz_id = ? AND status = 'published'
    ");

    $stmt->bind_param("i",$quiz_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows === 0){
        throw new Exception("Quiz not found or not published");
    }

    $row = $result->fetch_assoc();

    echo json_encode([
        "status" => "success",
        "data" => $row
    ]);

}catch(Exception $e){

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);

}