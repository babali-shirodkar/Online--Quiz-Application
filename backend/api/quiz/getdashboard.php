<?php
header("Content-Type: application/json");

require_once "../../confi/database.php";
include "../../../userAccess.php";

try{

    if(!isset($user_id)){
        throw new Exception("Unauthorized");
    }

    $data = [];

    /*  PARTICIPANT  */
    if($role === "participant"){

        // Total quizzes (only published)
        $q1 = $conn->query("
            SELECT COUNT(*) as total 
            FROM quizzes 
            WHERE status='published'
        ")->fetch_assoc();

        // Attempted quizzes
        $q2 = $conn->prepare("
            SELECT COUNT(DISTINCT quiz_id) as attempted
            FROM quiz_attempts
            WHERE user_id=?
        ");
        $q2->bind_param("i",$user_id);
        $q2->execute();
        $attempted = $q2->get_result()->fetch_assoc();

        $data = [
            "total_quizzes" => $q1['total'],
            "attempted_quizzes" => $attempted['attempted']
        ];
    }

    /* INSTRUCTOR  */
    else if($role === "instructor"){

        $q = $conn->prepare("
            SELECT 
                COUNT(*) as total,
                SUM(status='draft') as draft,
                SUM(status='published') as published
            FROM quizzes
            WHERE created_by=?
        ");
        $q->bind_param("i",$user_id);
        $q->execute();
        $res = $q->get_result()->fetch_assoc();

        $data = $res;
    }

    /*  ADMIN */
    else if($role === "admin"){

        $q1 = $conn->query("
            SELECT COUNT(*) as total_instructors 
            FROM users 
            WHERE role='instructor' AND status= 1
        ")->fetch_assoc();

        $q2 = $conn->query("
            SELECT COUNT(*) as total_participants 
            FROM users 
            WHERE role='participant' AND status= 1
        ")->fetch_assoc();

        $q3 = $conn->query("
            SELECT 
                COUNT(*) as total_quizzes,
                SUM(status='published') as published,
                SUM(status='draft') as draft
            FROM quizzes
            WHERE status!='deleted'
        ")->fetch_assoc();

        $data = [
            "total_instructors" => $q1['total_instructors'],
            "total_participants" => $q2['total_participants'],
            "total_quizzes" => $q3['total_quizzes'],
            "published" => $q3['published'],
            "draft" => $q3['draft']
        ];
    }

    echo json_encode([
        "status"=>"success",
        "role"=>$role,
        "data"=>$data
    ]);

}catch(Exception $e){
    echo json_encode([
        "status"=>"error",
        "message"=>$e->getMessage()
    ]);
}