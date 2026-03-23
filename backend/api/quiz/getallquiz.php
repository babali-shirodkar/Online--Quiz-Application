<?php

header("Content-Type: application/json");

require_once "../../confi/database.php";
include "../../../userAccess.php";

try{

    if(!isset($user_id)){
        throw new Exception("Unauthorized");
    }

    $data = [];

    /* ADMIN → ALL QUIZZES */
    if($role === "admin"){

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
        ORDER BY q.quiz_id DESC";

        $result = $conn->query($sql);

    }

    /* INSTRUCTOR → OWN QUIZZES */
    else if($role === "instructor"){

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
        WHERE q.created_by = ?
        ORDER BY q.quiz_id DESC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();

    }else{
        throw new Exception("Invalid role");
    }

    while($row = $result->fetch_assoc()){
        $data[] = $row;
    }

    echo json_encode([
        "status" => "success",
        "data" => $data
    ]);

}catch(Exception $e){

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);

}