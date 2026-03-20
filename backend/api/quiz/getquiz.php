<?php

header("Content-Type: application/json");

require_once "../../confi/database.php";
include "../../../userAccess.php";

$quiz_id = $_GET['quiz_id'];

$sql = "SELECT * FROM quizzes WHERE quiz_id='$quiz_id'";
$result = $conn->query($sql);

if($result->num_rows > 0){

$quiz = $result->fetch_assoc();

echo json_encode([
"status"=>"success",
"quiz"=>$quiz
]);

}else{

echo json_encode([
"status"=>"error",
"message"=>"Quiz not found"
]);

}