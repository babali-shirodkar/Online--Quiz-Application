<?php

header("Content-Type: application/json");

require_once "../../confi/database.php";
include "../../../userAccess.php";

try{

    /* AUTH CHECK */
    if(!isset($user_id)){
        throw new Exception("Unauthorized access");
    }

    /* FETCH PARTICIPANTS */

    $stmt = $conn->prepare("
        SELECT user_id, name, email, status, is_deleted
        FROM users 
        WHERE role = 'participant' AND is_deleted = 0
        ORDER BY user_id DESC
    ");

    $stmt->execute();

    $result = $stmt->get_result();

    $data = [];

    while($row = $result->fetch_assoc()){
        $data[] = $row;
    }

    echo json_encode([
        "status"=>"success",
        "data"=>$data
    ]);

}catch(Exception $e){

    echo json_encode([
        "status"=>"error",
        "message"=>$e->getMessage()
    ]);

}
?>