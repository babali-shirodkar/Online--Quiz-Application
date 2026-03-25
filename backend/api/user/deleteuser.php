<?php

header("Content-Type: application/json");

require_once "../../confi/database.php";
include "../../../userAccess.php";

try{

    /* GET JSON INPUT */
    $data = json_decode(file_get_contents("php://input"), true);

    $id = $data['id'] ?? 0;

    if(!$id){
        throw new Exception("User ID missing");
    }

    if(!isset($user_id)){
        throw new Exception("Unauthorized access");
    }

    if($id == $user_id){
        throw new Exception("You cannot delete yourself");
    }

    /* CHECK USER EXISTS */

    $check = $conn->prepare("SELECT user_id FROM users WHERE user_id=?");
    $check->bind_param("i",$id);
    $check->execute();

    $res = $check->get_result();

    if($res->num_rows === 0){
        throw new Exception("User not found");
    }

    /* SOFT DELETE (inactive) */

   $stmt = $conn->prepare("
        UPDATE users 
        SET is_deleted = 1 
        WHERE user_id=?
    ");

    $stmt->bind_param("i",$id);

    if(!$stmt->execute()){
        throw new Exception("Failed to delete user");
    }

    echo json_encode([
        "status"=>"success",
        "message"=>"User deactivated successfully"
    ]);

}catch(Exception $e){

    echo json_encode([
        "status"=>"error",
        "message"=>$e->getMessage()
    ]);

}
?>