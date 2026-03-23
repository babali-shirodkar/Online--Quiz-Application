<?php

header("Content-Type: application/json");

require_once "../../confi/database.php";
include "../../../userAccess.php";

try{

    /* GET JSON INPUT */
    $data = json_decode(file_get_contents("php://input"), true);

    $quiz_id = $data['quiz_id'] ?? 0;

    if(!$quiz_id){
        throw new Exception("Quiz ID missing");
    }

    if(!isset($user_id)){
        throw new Exception("Unauthorized access");
    }

    /* CHECK QUIZ STATUS */

    $check = $conn->prepare("
        SELECT status FROM quizzes WHERE quiz_id=?
    ");

    $check->bind_param("i", $quiz_id);
    $check->execute();

    $result = $check->get_result();

    if($result->num_rows == 0){
        throw new Exception("Quiz not found");
    }

    $row = $result->fetch_assoc();

    if($row['status'] !== 'published'){
        throw new Exception("Only published quiz can be unpublished");
    }

    /* UPDATE STATUS */

    $stmt = $conn->prepare("
        UPDATE quizzes 
        SET status='draft' 
        WHERE quiz_id=?
    ");

    $stmt->bind_param("i", $quiz_id);

    if(!$stmt->execute()){
        throw new Exception($stmt->error);
    }

    if($stmt->affected_rows == 0){
        throw new Exception("Quiz already in draft");
    }

    echo json_encode([
        "status"=>"success",
        "message"=>"Quiz unpublished successfully"
    ]);

}catch(Exception $e){

    echo json_encode([
        "status"=>"error",
        "message"=>$e->getMessage()
    ]);

}
?>