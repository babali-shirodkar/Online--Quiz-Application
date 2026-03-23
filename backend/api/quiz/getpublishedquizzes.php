<?php

header("Content-Type: application/json");
require_once "../../confi/database.php";

$sql = "SELECT 
        q.quiz_id,
        q.title,
        q.total_questions,
        q.duration,
        q.total_marks,
        q.category_id,
        c.category_name
        FROM quizzes q
        JOIN categories c ON c.id = q.category_id
        WHERE q.status='published'
        ORDER BY q.quiz_id DESC";

$result = $conn->query($sql);

$data=[];

while($row=$result->fetch_assoc()){
$data[]=$row;
}

echo json_encode([
"status"=>"success",
"data"=>$data
]);

?>