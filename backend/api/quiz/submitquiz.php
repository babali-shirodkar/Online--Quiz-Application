<?php

header("Content-Type: application/json");

require_once "../../confi/database.php";
include "../../../userAccess.php";

$conn->begin_transaction();

try{

$attempt_id = $_POST['attempt_id'] ?? '';

if(!$attempt_id){
    throw new Exception("Attempt ID missing");
}

if(!isset($user_id)){
    throw new Exception("User not authenticated");
}

/* =========================
   GET QUIZ ID FROM ATTEMPT
========================= */

$att = $conn->prepare("
SELECT quiz_id FROM quiz_attempts WHERE id=? AND user_id=?
");

$att->bind_param("ii",$attempt_id,$user_id);
$att->execute();

$attRes = $att->get_result()->fetch_assoc();

if(!$attRes){
    throw new Exception("Invalid attempt");
}

$quiz_id = $attRes['quiz_id'];

/* =========================
   GET ATTEMPT QUESTIONS ONLY
========================= */

$q = $conn->prepare("
SELECT question_id FROM attempt_questions
WHERE attempt_id=?
");

$q->bind_param("i",$attempt_id);
$q->execute();

$qres = $q->get_result();

$total_questions = $qres->num_rows;

$correct = 0;
$wrong = 0;
$skipped = 0;

/* =========================
   LOOP EACH QUESTION
========================= */

while($ques = $qres->fetch_assoc()){

    $question_id = $ques['question_id'];

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

    /* USER OPTIONS */
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

    sort($correctOpt);
    sort($userOpt);

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

/* =========================
   CALCULATE PERCENTAGE
========================= */

$percentage = ($total_questions > 0)
    ? ($correct / $total_questions) * 100
    : 0;

/* =========================
   UPDATE ATTEMPT
========================= */

$update = $conn->prepare("
UPDATE quiz_attempts
SET end_time=NOW(),
    score=?,
    status='completed'
WHERE id=?
");

$update->bind_param("ii",$correct,$attempt_id);
$update->execute();

/* =========================
   INSERT RESULT
========================= */

$resultInsert = $conn->prepare("
INSERT INTO results
(attempt_id, total_questions, correct_answers, wrong_answers, percentage)
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

$resultInsert->execute();

/* =========================
   COMMIT
========================= */

$conn->commit();

/* =========================
   RESPONSE
========================= */

echo json_encode([
    "status"=>"success",
    "attempt_id"=>$attempt_id,
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