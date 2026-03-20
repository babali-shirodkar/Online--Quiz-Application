<?php
header("Content-Type: application/json");

require_once "../../confi/database.php";
include "../../../userAccess.php";

$attempt_id = $_GET['attempt_id'] ?? '';

if(!$attempt_id){
    echo json_encode(["status"=>"error","message"=>"Attempt ID missing"]);
    exit;
}

/* ================= GET QUIZ DETAILS ================= */

$qz = $conn->prepare("
SELECT q.title, q.duration
FROM quizzes q
JOIN quiz_attempts qa ON qa.quiz_id = q.quiz_id
WHERE qa.id=?
");

$qz->bind_param("i",$attempt_id);
$qz->execute();

$qz_res = $qz->get_result()->fetch_assoc();

/* ================= GET QUESTIONS ================= */

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
$stmt->bind_param("i",$attempt_id);
$stmt->execute();

$res = $stmt->get_result();

$questions = [];

while($row = $res->fetch_assoc()){

    $qid = $row['question_id'];

    $option_ids = json_decode($row['options_order'], true);
    $optList = implode(",", $option_ids);

    $optQuery = $conn->query("
    SELECT id, option_text
    FROM options
    WHERE id IN ($optList)
    ORDER BY FIELD(id,$optList)
    ");

    $options = [];

    while($o = $optQuery->fetch_assoc()){
        $options[] = $o;
    }

    /* LOAD SAVED ANSWERS */
    $ansQ = $conn->prepare("
    SELECT option_id FROM user_answers
    WHERE attempt_id=? AND question_id=?
    ");

    $ansQ->bind_param("ii",$attempt_id,$qid);
    $ansQ->execute();

    $ansRes = $ansQ->get_result();

    $saved = [];

    while($a = $ansRes->fetch_assoc()){
        $saved[] = (string)$a['option_id'];
    }

    $questions[] = [
        "id"=>$qid,
        "question_text"=>$row['question_text'],
        "question_type"=>$row['question_type'],
        "marks"=>$row['marks'],
        "options"=>$options,
        "saved_answers"=>$saved
    ];
}

echo json_encode([
    "status"=>"success",
    "quiz_name"=>$qz_res['title'],
    "duration"=>$qz_res['duration'],
    "questions"=>$questions
]);