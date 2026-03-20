<?php

header("Content-Type: application/json");
require_once "../../confi/database.php";
include "../../../userAccess.php";

$data = json_decode(file_get_contents("php://input"), true);
$quiz_id = $data['quiz_id'] ?? 0;

if(!$quiz_id){

echo json_encode([
"status"=>"error",
"message"=>"Quiz ID missing"
]);
exit;

}

/* GET QUIZ */

$stmt=$conn->prepare("SELECT total_questions FROM quizzes WHERE quiz_id=?");
$stmt->bind_param("i",$quiz_id);
$stmt->execute();
$res=$stmt->get_result();

if($res->num_rows==0){

echo json_encode([
"status"=>"error",
"message"=>"Quiz not found"
]);
exit;

}

$row=$res->fetch_assoc();
$required=$row['total_questions'];


/* COUNT QUESTIONS */

$stmt=$conn->prepare("SELECT id FROM questions WHERE quiz_id=?");
$stmt->bind_param("i",$quiz_id);
$stmt->execute();
$qres=$stmt->get_result();

$current=$qres->num_rows;

if($current < $required){

echo json_encode([
"status"=>"error",
"message"=>"You must add ".$required." questions before publishing"
]);
exit;

}


/* VALIDATE OPTIONS */

while($q=$qres->fetch_assoc()){

$qid=$q['id'];

$opt=$conn->prepare("SELECT option_text,is_correct FROM options WHERE question_id=?");
$opt->bind_param("i",$qid);
$opt->execute();
$ores=$opt->get_result();

if($ores->num_rows < 2){

echo json_encode([
"status"=>"error",
"message"=>"Each question must have minimum 2 options"
]);
exit;

}

$correct=0;

while($o=$ores->fetch_assoc()){
if($o['is_correct']==1) $correct++;
}

if($correct==0){

echo json_encode([
"status"=>"error",
"message"=>"Each question must have a correct answer"
]);
exit;

}

}


/* PUBLISH QUIZ */

$stmt=$conn->prepare("UPDATE quizzes SET status='published' WHERE quiz_id=?");
$stmt->bind_param("i",$quiz_id);

if($stmt->execute()){

echo json_encode([
"status"=>"success",
"message"=>"Quiz published successfully"
]);

}else{

echo json_encode([
"status"=>"error",
"message"=>"Failed to publish quiz"
]);

}

?>