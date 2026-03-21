<?php

header("Content-Type: application/json");

require_once "../../confi/database.php";
include "../../../userAccess.php";

if(!isset($user_id)){
    echo json_encode([
        "status"=>"error",
        "message"=>"Unauthorized"
    ]);
    exit;
}

$where = "";
if($role === "admin"){
    $where = "";
}
else if($role === "instructor"){
    $where = "WHERE q.created_by = ?";
}

$sql = "SELECT 
q.quiz_id,
q.title,
c.category_name AS category,
q.duration,
q.total_questions,
q.total_marks,
q.status,
u.name AS instructor_name,
q.created_by
FROM quizzes q
LEFT JOIN categories c ON c.id = q.category_id
LEFT JOIN users u ON u.user_id = q.created_by
$where
ORDER BY q.quiz_id DESC";



if($where){
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$user_id);
    $stmt->execute();
    $result = $stmt->get_result();
}else{
    $result = $conn->query($sql);
}

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