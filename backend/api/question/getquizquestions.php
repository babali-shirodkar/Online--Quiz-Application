<?php

header("Content-Type: application/json");

require_once "../../confi/database.php";
include "../../../userAccess.php";

$quiz_id = $_GET['quiz_id'] ?? '';

if(!$quiz_id){
    echo json_encode([
        "status"=>"error",
        "message"=>"Quiz ID missing"
    ]);
    exit;
}

$quizQuery = $conn->prepare("
    SELECT title, duration, total_questions
    FROM quizzes
    WHERE quiz_id=?
");

$quizQuery->bind_param("i",$quiz_id);
$quizQuery->execute();
$quizResult = $quizQuery->get_result()->fetch_assoc();

$quiz_name = $quizResult['title'] ?? '';
$duration = $quizResult['duration'] ?? 0;
$total_limit = $quizResult['total_questions'] ?? 0;

$qidQuery = $conn->prepare("
    SELECT id 
    FROM questions
    WHERE quiz_id=?
    ORDER BY RAND()
    LIMIT ?
");

$qidQuery->bind_param("ii", $quiz_id, $total_limit);
$qidQuery->execute();

$qidResult = $qidQuery->get_result();

$question_ids = [];

while($row = $qidResult->fetch_assoc()){
    $question_ids[] = $row['id'];
}

if(empty($question_ids)){
    echo json_encode([
        "status"=>"error",
        "message"=>"No questions found"
    ]);
    exit;
}

$idList = implode(",", $question_ids);

$sql = "
SELECT 
q.id as question_id,
q.question_text,
q.question_type,
q.marks,
o.id as option_id,
o.option_text
FROM questions q
LEFT JOIN options o ON o.question_id = q.id
WHERE q.id IN ($idList)
";

$result = $conn->query($sql);

$questions = [];

while($row = $result->fetch_assoc()){

    $qid = $row['question_id'];

    if(!isset($questions[$qid])){

        $questions[$qid] = [
            "id"=>$qid,
            "question_text"=>$row['question_text'],
            "question_type"=>$row['question_type'],
            "marks"=>$row['marks'],
            "options"=>[]
        ];
    }

    if($row['option_id']){
        $questions[$qid]['options'][] = [
            "id"=>$row['option_id'],
            "option_text"=>$row['option_text']
        ];
    }
}

$questions = array_values($questions);



foreach($questions as &$q){
    shuffle($q['options']);
}

echo json_encode([
    "status"=>"success",
    "quiz_name"=>$quiz_name,
    "duration"=>$duration,
    "total_questions"=>count($questions),
    "questions"=>$questions
]);

?>