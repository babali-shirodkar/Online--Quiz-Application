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

$quiz_id = $attemptData['quiz_id'];



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

$questions = [];

$q = $conn->prepare("
SELECT id, question_text 
FROM questions 
WHERE quiz_id=?
");

$q->bind_param("i",$quiz_id);
$q->execute();
$qres = $q->get_result();

while($row = $qres->fetch_assoc()){

    $question_id = $row['id'];

  

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

   

    $options = [];

    $o = $conn->prepare("
    SELECT id, option_text, is_correct 
    FROM options 
    WHERE question_id=?
    ");

    $o->bind_param("i",$question_id);
    $o->execute();
    $ores = $o->get_result();

    while($opt = $ores->fetch_assoc()){
        $options[] = $opt;
    }

    

    sort($user_selected);
    sort($correct_options);

    $is_correct_flag = 0;

    if(empty($user_selected)){
        $status = "skipped";
    }
    else if($user_selected === $correct_options){
        $status = "correct";
        $is_correct_flag = 1;
    }
    else{
        $status = "wrong";
    }

    $row['user_selected'] = $user_selected;   
    $row['correct_options'] = $correct_options; 
    $row['correct'] = $is_correct_flag; 
    $row['status'] = $status; 
    $row['options'] = $options;

    $questions[] = $row;
}



echo json_encode([
    "status"=>"success",
    "summary"=>$summaryData,
    "questions"=>$questions
]);