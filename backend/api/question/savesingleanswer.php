<?php
header("Content-Type: application/json");

require_once "../../confi/database.php";

$attempt_id = $_POST['attempt_id'];
$question_id = $_POST['question_id'];
$options = $_POST['options'];

$conn->query("
DELETE FROM user_answers
WHERE attempt_id=$attempt_id AND question_id=$question_id
");

foreach($options as $op){

$conn->query("
INSERT INTO user_answers (attempt_id,question_id,option_id)
VALUES ($attempt_id,$question_id,$op)
");

}

echo json_encode(["status"=>"success"]);