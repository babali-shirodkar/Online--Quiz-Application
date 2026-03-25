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

    /* VALIDATE ATTEMPT */

    $attempt = $conn->prepare("
        SELECT quiz_id 
        FROM quiz_attempts 
        WHERE id=?
    ");

    $attempt->bind_param("i",$attempt_id);

    if(!$attempt->execute()){
        throw new Exception($attempt->error);
    }

    $attemptData = $attempt->get_result()->fetch_assoc();

    if(!$attemptData){
        throw new Exception("Invalid attempt");
    }

    /* GET SUMMARY */

    $summary = $conn->prepare("
        SELECT r.total_questions,
               r.correct_answers,
               r.wrong_answers,
               r.percentage,
               qa.score
        FROM results r
        JOIN quiz_attempts qa ON qa.id = r.attempt_id
        WHERE r.attempt_id=?
    ");

    $summary->bind_param("i",$attempt_id);

    if(!$summary->execute()){
        throw new Exception($summary->error);
    }

    $summaryData = $summary->get_result()->fetch_assoc();

    /* GET QUESTIONS */

    $questions = [];

    $q = $conn->prepare("
        SELECT 
            aq.question_id,
            aq.question_order,
            q.question_text,
            aq.options_order
        FROM attempt_questions aq
        JOIN questions q ON q.id = aq.question_id
        WHERE aq.attempt_id=?
        ORDER BY aq.question_order ASC
    ");

    $q->bind_param("i",$attempt_id);

    if(!$q->execute()){
        throw new Exception($q->error);
    }

    $qres = $q->get_result();

    while($row = $qres->fetch_assoc()){

        $question_id = $row['question_id'];

        /* USER SELECTED */

        $user_selected = [];

        $ua = $conn->prepare("
            SELECT option_id 
            FROM user_answers 
            WHERE attempt_id=? AND question_id=?
        ");

        $ua->bind_param("ii",$attempt_id,$question_id);
        $ua->execute();

        $uaRes = $ua->get_result();

        while($r = $uaRes->fetch_assoc()){
            $user_selected[] = (int)$r['option_id'];
        }

        /* CORRECT OPTIONS */

        $correct_options = [];

        $co = $conn->prepare("
            SELECT id 
            FROM options 
            WHERE question_id=? AND is_correct=1
        ");

        $co->bind_param("i",$question_id);
        $co->execute();

        $coRes = $co->get_result();

        while($c = $coRes->fetch_assoc()){
            $correct_options[] = (int)$c['id'];
        }

        /* OPTIONS ORDER SAFE */

        $option_ids = json_decode($row['options_order'], true);

        if(!is_array($option_ids)) $option_ids = [];

        $options = [];

        if(!empty($option_ids)){

            $placeholders = implode(',', array_fill(0, count($option_ids), '?'));
            $types = str_repeat('i', count($option_ids));

            $optStmt = $conn->prepare("
                SELECT id, option_text, is_correct
                FROM options
                WHERE id IN ($placeholders)
            ");

            $optStmt->bind_param($types, ...$option_ids);
            $optStmt->execute();

            $optRes = $optStmt->get_result();

            $temp = [];

            while($opt = $optRes->fetch_assoc()){
                $temp[$opt['id']] = $opt;
            }

            /* maintain order */
            foreach($option_ids as $oid){
                if(isset($temp[$oid])){
                    $options[] = $temp[$oid];
                }
            }
        }

        /* STATUS */

        sort($user_selected);
        sort($correct_options);

        if(empty($user_selected)){
            $status = "skipped";
        }
        else if($user_selected === $correct_options){
            $status = "correct";
        }
        else{
            $status = "wrong";
        }

        $questions[] = [
            "question_id" => $question_id,
            "question_order" => $row['question_order'],
            "question_text" => $row['question_text'],
            "user_selected" => $user_selected,
            "correct_options" => $correct_options,
            "status" => $status,
            "options" => $options
        ];
    }

    echo json_encode([
        "status" => "success",
        "summary" => $summaryData,
        "questions" => $questions
    ]);

}catch(Exception $e){

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}