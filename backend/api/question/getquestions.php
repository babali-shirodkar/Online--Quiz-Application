<?php

header("Content-Type: application/json");

require_once "../../confi/database.php";

$quiz_id = $_GET['quiz_id'] ?? '';

$questions = [];

$q = $conn->prepare("SELECT * FROM questions WHERE quiz_id=?");
$q->bind_param("i",$quiz_id);
$q->execute();

$result = $q->get_result();

while($row = $result->fetch_assoc()){

$question_id = $row['id'];

$options = [];

$o = $conn->prepare("SELECT * FROM options WHERE question_id=?");
$o->bind_param("i",$question_id);
$o->execute();

$optres = $o->get_result();

while($opt = $optres->fetch_assoc()){
$options[] = $opt;
}

$row['options'] = $options;

$questions[] = $row;

}

echo json_encode([
"status"=>"success",
"questions"=>$questions
]);