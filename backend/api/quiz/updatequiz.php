<?php

header("Content-Type: application/json");

require_once "../../confi/database.php";
include "../../../userAccess.php";

$data = json_decode(file_get_contents("php://input"), true);

$quiz_id        = $data['quiz_id'] ?? '';
$title          = $data['title'] ?? '';
$category_id    = $data['category_id'] ?? '';
$duration       = $data['duration'] ?? '';
$total_marks    = $data['total_marks'] ?? '';
$total_questions= $data['total_questions'] ?? '';

if(!$quiz_id){
    echo json_encode([
        "status"=>"error",
        "message"=>"Quiz ID missing"
    ]);
    exit;
}

/* UPDATE QUERY */
if($category_id === '' || $category_id === null){
    $query = "SELECT category_id FROM quizzes WHERE quiz_id=?";
    $stmt2 = $conn->prepare($query);
    $stmt2->bind_param("i", $quiz_id);
    $stmt2->execute();
    $stmt2->bind_result($category_id);
    $stmt2->fetch();
    $stmt2->close();
}

$sql = "UPDATE quizzes 
        SET title=?,
            category_id=?,
            duration=?,
            total_marks=?,
            total_questions=?,
        WHERE quiz_id=?";

$stmt = $conn->prepare($sql);

/* CHECK PREPARE ERROR */

if(!$stmt){
    echo json_encode([
        "status"=>"error",
        "message"=>"SQL Prepare Failed",
        "error"=>$conn->error
    ]);
    exit;
}

/* BIND PARAM */

$stmt->bind_param(
    "siiiii",
    $title,
    $category_id,
    $duration,
    $total_marks,
    $total_questions,
    $quiz_id
);

/* EXECUTE */

if($stmt->execute()){

    echo json_encode([
        "status"=>"success",
        "message"=>"Quiz updated successfully"
    ]);

}else{

    echo json_encode([
        "status"=>"error",
        "message"=>"Update failed",
        "error"=>$stmt->error
    ]);

}

$stmt->close();
$conn->close();