<?php

header("Content-Type: application/json");

require_once "../../confi/database.php";
include "../../../userAccess.php";

try{

    /* READ JSON INPUT */
    $data = json_decode(file_get_contents("php://input"), true);

    $id = $data['id'] ?? 0;

    if(!$id){
        throw new Exception("User ID missing");
    }

    if(!isset($user_id)){
        throw new Exception("Unauthorized access");
    }

    /* PREVENT SELF CHANGE */
    if($id == $user_id){
        throw new Exception("You cannot change your own status");
    }

    /* CHECK USER */

    $check = $conn->prepare("SELECT status FROM users WHERE user_id=?");
    $check->bind_param("i",$id);
    $check->execute();

    $res = $check->get_result();

    if($res->num_rows == 0){
        throw new Exception("User not found");
    }

    $row = $res->fetch_assoc();

    $new_status = ($row['status'] === 'active') ? 'inactive' : 'active';

    /* UPDATE */

    $stmt = $conn->prepare("
        UPDATE users 
        SET status=? 
        WHERE user_id=?
    ");

    $stmt->bind_param("si",$new_status,$id);

    if(!$stmt->execute()){
        throw new Exception("Failed to update status");
    }

    echo json_encode([
        "status"=>"success",
        "message"=>"Status updated successfully",
        "new_status"=>$new_status
    ]);

}catch(Exception $e){

    echo json_encode([
        "status"=>"error",
        "message"=>$e->getMessage()
    ]);

}
?>