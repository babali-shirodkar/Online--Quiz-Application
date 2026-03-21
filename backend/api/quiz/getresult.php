<?php 

header("Content-Type: application/json");

require_once "../../confi/database.php";
include "../../../userAccess.php";

$attempt_id = $_GET['attempt_id'] ?? '';

if(!$attempt_id){
    echo json_encode([
        "status"=>"error",
        "message"=>"Attempt ID missing"
    ]);
    exit;
}

/* ================= VALIDATE ATTEMPT ================= */

$attempt = $conn->prepare("
SELECT quiz_id 
FROM quiz_attempts 
WHERE id=?
");

$attempt->bind_param("i",$attempt_id);
$attempt->execute();

$attemptData = $attempt->get_result()->fetch_assoc();

if(!$attemptData){
    echo json_encode([
        "status"=>"error",
        "message"=>"Invalid attempt"
    ]);
    exit;
}


/* ================= GET SUMMARY ================= */

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
$summary->execute();

$summaryData = $summary->get_result()->fetch_assoc();


/* ================= GET QUESTIONS FROM ATTEMPT ================= */

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
$q->execute();

$qres = $q->get_result();

while($row = $qres->fetch_assoc()){

    $question_id = $row['question_id'];

    /* ================= USER SELECTED ================= */

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
        $user_selected[] = $r['option_id'];
    }


    /* ================= CORRECT OPTIONS ================= */

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
        $correct_options[] = $c['id'];
    }


    /* ================= OPTIONS IN SAME ORDER ================= */

    $option_ids = json_decode($row['options_order'], true);

    $optList = implode(",", $option_ids);

    $options = [];

    $optQuery = $conn->query("
    SELECT id, option_text, is_correct
    FROM options
    WHERE id IN ($optList)
    ORDER BY FIELD(id,$optList)
    ");

    while($opt = $optQuery->fetch_assoc()){
        $options[] = $opt;
    }


    /* ================= STATUS ================= */

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
        "question_id"=>$question_id,
        "question_order"=>$row['question_order'],
        "question_text"=>$row['question_text'],
        "user_selected"=>$user_selected,
        "correct_options"=>$correct_options,
        "status"=>$status,
        "options"=>$options
    ];
}




echo json_encode([
    "status"=>"success",
    "summary"=>$summaryData,
    "questions"=>$questions
]);