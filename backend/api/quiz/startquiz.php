<?php
header("Content-Type: application/json");

require_once "../../confi/database.php";
include "../../../userAccess.php";

$conn->begin_transaction();

try{

    
    $data = json_decode(file_get_contents("php://input"), true);

    $quiz_id = $data['quiz_id'] ?? '';

    if(!$quiz_id){
        throw new Exception("Quiz ID missing");
    }

    if(!isset($user_id)){
        throw new Exception("User not logged in");
    }

    /* CREATE ATTEMPT */

    $attempt = $conn->prepare("
        INSERT INTO quiz_attempts (user_id, quiz_id, start_time, status)
        VALUES (?, ?, NOW(), 'in_progress')
    ");

    $attempt->bind_param("ii",$user_id,$quiz_id);
    $attempt->execute();

    $attempt_id = $conn->insert_id;

    if(!$attempt_id){
        throw new Exception("Attempt not created");
    }

    /* GET TOTAL QUESTIONS */

    $qz = $conn->prepare("
        SELECT total_questions FROM quizzes WHERE quiz_id=?
    ");
    $qz->bind_param("i",$quiz_id);
    $qz->execute();

    $qz_res = $qz->get_result()->fetch_assoc();
    $total_limit = $qz_res['total_questions'] ?? 0;

    /* GET RANDOM QUESTIONS */

    $q = $conn->prepare("
        SELECT id FROM questions
        WHERE quiz_id=?
        ORDER BY RAND()
        LIMIT ?
    ");

    $q->bind_param("ii",$quiz_id,$total_limit);
    $q->execute();

    $res = $q->get_result();

    $order = 1;

    while($row = $res->fetch_assoc()){

        $qid = $row['id'];

        /* GET OPTIONS */
        $optQ = $conn->prepare("
            SELECT id FROM options WHERE question_id=?
        ");
        $optQ->bind_param("i",$qid);
        $optQ->execute();

        $optRes = $optQ->get_result();

        $option_ids = [];

        while($o = $optRes->fetch_assoc()){
            $option_ids[] = $o['id'];
        }

        shuffle($option_ids);

        $opt_json = json_encode($option_ids);

        /* SAVE INTO attempt_questions */
        $ins = $conn->prepare("
            INSERT INTO attempt_questions
            (attempt_id, question_id, question_order, options_order)
            VALUES (?,?,?,?)
        ");

        $ins->bind_param("iiis",$attempt_id,$qid,$order,$opt_json);
        $ins->execute();

        $order++;
    }

    $conn->commit();

    echo json_encode([
        "status"=>"success",
        "attempt_id"=>$attempt_id
    ]);

}catch(Exception $e){

    $conn->rollback();

    echo json_encode([
        "status"=>"error",
        "message"=>$e->getMessage()
    ]);

}
?>