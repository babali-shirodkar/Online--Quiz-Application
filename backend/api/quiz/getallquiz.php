<?php

header("Content-Type: application/json");
require_once "../../confi/database.php";
include "../../../userAccess.php";

$sql = "SELECT 
q.quiz_id,
q.title,
c.category_name AS category,
q.duration,
q.total_questions,
q.total_marks,
q.status
FROM quizzes q
LEFT JOIN categories c 
ON c.id = q.category_id
ORDER BY q.quiz_id DESC";

$result = $conn->query($sql);

$data = [];

if($result){

    while($row = $result->fetch_assoc()){
        $data[] = $row;
    }

}

echo json_encode([
"status" => "success",
"data" => $data
]);

?>