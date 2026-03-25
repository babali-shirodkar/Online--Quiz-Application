<?php

header("Content-Type: application/json");

require_once "../../confi/database.php";
include "../../../userAccess.php";

if(!isset($user_id)){
    echo json_encode([
        "status"=>"error",
        "message"=>"User not logged in"
    ]);
    exit;
}

/* GET ATTEMPTS */

$sql = $conn->prepare("
SELECT 
qa.id as attempt_id,
q.title as quiz_name,
qa.score,
qa.status,
qa.end_time as completed_at,
r.total_questions
FROM quiz_attempts qa
JOIN quizzes q ON q.quiz_id = qa.quiz_id
LEFT JOIN results r ON r.attempt_id = qa.id
WHERE qa.user_id=?
ORDER BY qa.id DESC
");

$sql->bind_param("i",$user_id);
$sql->execute();

$res = $sql->get_result();

$data = [];

while($row = $res->fetch_assoc()){
    $data[] = $row;
}

echo json_encode([
    "status"=>"success",
    "data"=>$data
]);