<?php

header("Content-Type: application/json");
require_once "../../confi/database.php";

$attempt_id = $_POST['attempt_id'] ?? '';

if(!$attempt_id){
    echo json_encode(["status"=>"error","message"=>"Missing attempt"]);
    exit;
}

/* CALCULATE SCORE */

$correct = 0;
$wrong = 0;

$q = $conn->prepare("
SELECT DISTINCT question_id FROM user_answers
WHERE attempt_id=?
");
$q->bind_param("i",$attempt_id);
$q->execute();
$qres = $q->get_result();

$total_questions = $qres->num_rows;

while($row = $qres->fetch_assoc()){

    $qid = $row['question_id'];

    $correctOpt = [];
    $userOpt = [];

    $c = $conn->query("
    SELECT id FROM options 
    WHERE question_id=$qid AND is_correct=1
    ");

    while($r = $c->fetch_assoc()){
        $correctOpt[] = $r['id'];
    }

    $u = $conn->query("
    SELECT option_id FROM user_answers
    WHERE attempt_id=$attempt_id AND question_id=$qid
    ");

    while($r = $u->fetch_assoc()){
        $userOpt[] = $r['option_id'];
    }

    sort($correctOpt);
    sort($userOpt);

    if($correctOpt === $userOpt){
        $correct++;
    }else{
        $wrong++;
    }
}

$percentage = ($total_questions > 0)
? ($correct/$total_questions)*100
: 0;

/* COMPLETE ATTEMPT */

$conn->query("
UPDATE quiz_attempts
SET status='completed', end_time=NOW(), score=$correct
WHERE id=$attempt_id
");

/* SAVE RESULT */

$conn->query("
INSERT INTO results
(attempt_id,total_questions,correct_answers,wrong_answers,percentage)
VALUES ($attempt_id,$total_questions,$correct,$wrong,$percentage)
");

echo json_encode([
"status"=>"success",
"attempt_id"=>$attempt_id
]);