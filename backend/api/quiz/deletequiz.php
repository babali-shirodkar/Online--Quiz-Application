<?php

header("Content-Type: application/json");

require_once "../../confi/database.php";
include "../../../userAccess.php";

try{

    $data = json_decode(file_get_contents("php://input"), true);

    $quiz_id = $data['quiz_id'] ?? '';

    if(!$quiz_id){
        throw new Exception("Quiz ID missing");
    }
    
    if(!isset($user_id)){
        throw new Exception("Unauthorized access");
    }

    $stmt = $conn->prepare("
        UPDATE quizzes 
        SET status='deleted' 
        WHERE quiz_id=?
    ");

    $stmt->bind_param("i",$quiz_id);

    if(!$stmt->execute()){
        throw new Exception($stmt->error);
    }

    if($stmt->affected_rows == 0){
        throw new Exception("Quiz not found or already deleted");
    }

    echo json_encode([
        "status"=>"success",
        "message"=>"Quiz deleted successfully"
    ]);

}catch(Exception $e){

    echo json_encode([
        "status"=>"error",
        "message"=>$e->getMessage()
    ]);

}
?>