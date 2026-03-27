<?php

header("Content-Type: application/json");

require_once "../../confi/database.php";
include "../../../userAccess.php";

try{

    
    $data = json_decode(file_get_contents("php://input"), true);

    $attempt_id = $data['attempt_id'] ?? 0;

    if(!$attempt_id){
        throw new Exception("Attempt ID missing");
    }

    /*  GET QUIZ DETAILS  */

    $qz = $conn->prepare("
        SELECT q.title, q.duration
        FROM quizzes q
        JOIN quiz_attempts qa ON qa.quiz_id = q.quiz_id
        WHERE qa.id=?
    ");

    $qz->bind_param("i", $attempt_id);

    if(!$qz->execute()){
        throw new Exception($qz->error);
    }

    $qz_res = $qz->get_result()->fetch_assoc();

    if(!$qz_res){
        throw new Exception("Quiz not found");
    }

    /*  GET QUESTIONS  */

    $sql = "
        SELECT 
            aq.question_order,
            q.id as question_id,
            q.question_text,
            q.question_type,
            q.marks,
            aq.options_order
        FROM attempt_questions aq
        JOIN questions q ON q.id = aq.question_id
        WHERE aq.attempt_id=?
        ORDER BY aq.question_order ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $attempt_id);

    if(!$stmt->execute()){
        throw new Exception($stmt->error);
    }

    $res = $stmt->get_result();

    $questions = [];

    while($row = $res->fetch_assoc()){

        $qid = $row['question_id'];

        /* SAFE OPTION ORDER */
        $option_ids = json_decode($row['options_order'], true);

        if(!is_array($option_ids) || empty($option_ids)){
            $option_ids = [];
        }

        $options = [];

        if(!empty($option_ids)){

            $placeholders = implode(',', array_fill(0, count($option_ids), '?'));
            $types = str_repeat('i', count($option_ids));

            $optStmt = $conn->prepare("
                SELECT id, option_text
                FROM options
                WHERE id IN ($placeholders)
            ");

            $optStmt->bind_param($types, ...$option_ids);

            if(!$optStmt->execute()){
                throw new Exception($optStmt->error);
            }

            $optRes = $optStmt->get_result();

            $tempOptions = [];

            while($o = $optRes->fetch_assoc()){
                $tempOptions[$o['id']] = $o;
            }

            /* Maintain order */
            foreach($option_ids as $oid){
                if(isset($tempOptions[$oid])){
                    $options[] = $tempOptions[$oid];
                }
            }
        }

        /*  LOAD SAVED ANSWERS */

        $ansQ = $conn->prepare("
            SELECT option_id FROM user_answers
            WHERE attempt_id=? AND question_id=?
        ");

        $ansQ->bind_param("ii", $attempt_id, $qid);

        if(!$ansQ->execute()){
            throw new Exception($ansQ->error);
        }

        $ansRes = $ansQ->get_result();

        $saved = [];

        while($a = $ansRes->fetch_assoc()){
            $saved[] = (string)$a['option_id'];
        }

        $questions[] = [
            "id" => $qid,
            "question_text" => $row['question_text'],
            "question_type" => $row['question_type'],
            "marks" => $row['marks'],
            "options" => $options,
            "saved_answers" => $saved
        ];

    }

    echo json_encode([
        "status" => "success",
        "quiz_name" => $qz_res['title'],
        "duration" => $qz_res['duration'],
        "questions" => $questions
    ]);

}catch(Exception $e){

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);

}