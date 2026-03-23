<?php
header("Content-Type: application/json");

require_once "../../confi/database.php";

try{

    $data = json_decode(file_get_contents("php://input"), true);

    $attempt_id = $data['attempt_id'] ?? '';
    $question_id = $data['question_id'] ?? '';
    $options = $data['options'] ?? [];

    if(!$attempt_id || !$question_id){
        throw new Exception("Invalid request data");
    }

    /* START TRANSACTION */
    $conn->begin_transaction();

    /* DELETE OLD ANSWERS */

    $del = $conn->prepare("
        DELETE FROM user_answers
        WHERE attempt_id=? AND question_id=?
    ");
    $del->bind_param("ii", $attempt_id, $question_id);
    $del->execute();

    /* INSERT NEW ANSWERS */

    if(!empty($options)){

        $ins = $conn->prepare("
            INSERT INTO user_answers (attempt_id, question_id, option_id)
            VALUES (?, ?, ?)
        ");

        foreach($options as $op){

            $ins->bind_param("iii", $attempt_id, $question_id, $op);
            $ins->execute();

        }
    }

    $conn->commit();

    echo json_encode([
        "status" => "success"
    ]);

}catch(Exception $e){

    $conn->rollback();

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);

}
?>