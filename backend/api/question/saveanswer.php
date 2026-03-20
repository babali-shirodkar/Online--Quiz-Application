<?php

header("Content-Type: application/json");

require_once "../../confi/database.php";
include "../../../userAccess.php";

$conn->begin_transaction();

try{

$quiz_id = $_POST['quiz_id'] ?? '';
$answers_json = $_POST['answers'] ?? '';

if(!$quiz_id || !$answers_json){
    throw new Exception("Missing quiz data");
}

$answers = json_decode($answers_json,true);

if(!is_array($answers)){
    throw new Exception("Invalid answers format");
}

/* CHECK USER */
if(!isset($user_id)){
    throw new Exception("User not authenticated");
}

/* ================= CREATE ATTEMPT ================= */

$attempt = $conn->prepare("
INSERT INTO quiz_attempts (user_id,quiz_id,start_time,status)
VALUES (?,?,NOW(),'in_progress')
");

$attempt->bind_param("ii",$user_id,$quiz_id);

if(!$attempt->execute()){
    throw new Exception($attempt->error);
}

$attempt_id = $conn->insert_id;

if(!$attempt_id){
    throw new Exception("Attempt creation failed");
}

/* ================= SAVE USER ANSWERS ================= */

foreach($answers as $question_id => $option_ids){

    // ensure array (important for single select)
    if(!is_array($option_ids)){
        $option_ids = [$option_ids];
    }

    foreach($option_ids as $option_id){

        $is_correct = 0;
        $final_option_id = NULL;

        /* TRUE / FALSE SUPPORT */
        if($option_id == "true" || $option_id == "false"){

            $check = $conn->prepare("
            SELECT id,is_correct
            FROM options
            WHERE question_id=? AND option_text=?
            ");

            $check->bind_param("is",$question_id,$option_id);
            $check->execute();

            $res = $check->get_result()->fetch_assoc();

            if($res){
                $final_option_id = $res['id'];
                $is_correct = $res['is_correct'];
            }

        }else{

            $final_option_id = intval($option_id);

            $check = $conn->prepare("
            SELECT is_correct
            FROM options
            WHERE id=?
            ");

            $check->bind_param("i",$final_option_id);
            $check->execute();

            $res = $check->get_result()->fetch_assoc();

            if($res){
                $is_correct = $res['is_correct'];
            }
        }

        /* INSERT ANSWER */
        $insert = $conn->prepare("
        INSERT INTO user_answers
        (attempt_id,question_id,option_id,is_correct)
        VALUES (?,?,?,?)
        ");

        $insert->bind_param(
            "iiii",
            $attempt_id,
            $question_id,
            $final_option_id,
            $is_correct
        );

        if(!$insert->execute()){
            throw new Exception($insert->error);
        }

    }
}

/* ================= CORRECT SCORING ================= */

$correct = 0;
$wrong = 0;
$skipped = 0;

/* GET ALL QUESTIONS */

$q = $conn->prepare("
SELECT id FROM questions WHERE quiz_id=?
");

$q->bind_param("i",$quiz_id);
$q->execute();
$qres = $q->get_result();

$total_questions = $qres->num_rows;

while($ques = $qres->fetch_assoc()){

    $question_id = $ques['id'];

    /* CORRECT OPTIONS */
    $correctOpt = [];

    $c = $conn->prepare("
    SELECT id FROM options 
    WHERE question_id=? AND is_correct=1
    ");

    $c->bind_param("i",$question_id);
    $c->execute();
    $cres = $c->get_result();

    while($row = $cres->fetch_assoc()){
        $correctOpt[] = $row['id'];
    }

    /* USER SELECTED */
    $userOpt = [];

    $u = $conn->prepare("
    SELECT option_id FROM user_answers 
    WHERE attempt_id=? AND question_id=?
    ");

    $u->bind_param("ii",$attempt_id,$question_id);
    $u->execute();
    $ures = $u->get_result();

    while($row = $ures->fetch_assoc()){
        $userOpt[] = $row['option_id'];
    }

    /* SORT FOR EXACT MATCH */
    sort($correctOpt);
    sort($userOpt);

    /* EVALUATION */

    if(empty($userOpt)){
        $skipped++;
        continue;
    }

    if($correctOpt === $userOpt){
        $correct++;
    }else{
        $wrong++;
    }

}

/* PERCENTAGE */

$percentage = ($total_questions > 0)
    ? ($correct/$total_questions)*100
    : 0;

/* ================= UPDATE ATTEMPT ================= */

$update = $conn->prepare("
UPDATE quiz_attempts
SET end_time=NOW(),
score=?,
status='completed'
WHERE id=?
");

$update->bind_param("ii",$correct,$attempt_id);

if(!$update->execute()){
    throw new Exception($update->error);
}

/* ================= INSERT RESULT ================= */

$resultInsert = $conn->prepare("
INSERT INTO results
(attempt_id,total_questions,correct_answers,wrong_answers,percentage)
VALUES (?,?,?,?,?)
");

$resultInsert->bind_param(
    "iiiid",
    $attempt_id,
    $total_questions,
    $correct,
    $wrong,
    $percentage
);

if(!$resultInsert->execute()){
    throw new Exception($resultInsert->error);
}

/* ================= COMMIT ================= */

$conn->commit();

echo json_encode([
    "status"=>"success",
    "attempt_id"=>$attempt_id,
    "score"=>$correct,
    "total_questions"=>$total_questions,
    "correct_answers"=>$correct,
    "wrong_answers"=>$wrong,
    "skipped"=>$skipped,
    "percentage"=>round($percentage,2)
]);

}catch(Exception $e){

$conn->rollback();

echo json_encode([
    "status"=>"error",
    "message"=>$e->getMessage()
]);

}
?>