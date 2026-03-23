<?php

header("Content-Type: application/json");
require_once "../../confi/database.php";

try{

    /* GET JSON INPUT */
    $data = json_decode(file_get_contents("php://input"), true);

    $quiz_id = $data['quiz_id'] ?? 0;

    if(!$quiz_id){
        throw new Exception("Quiz ID missing");
    }

    $questions = [];

    /* GET QUESTIONS */

    $q = $conn->prepare("SELECT * FROM questions WHERE quiz_id=?");
    $q->bind_param("i", $quiz_id);

    if(!$q->execute()){
        throw new Exception($q->error);
    }

    $result = $q->get_result();

    while($row = $result->fetch_assoc()){

        $question_id = $row['id'];

        $options = [];

        /* GET OPTIONS */

        $o = $conn->prepare("SELECT * FROM options WHERE question_id=?");
        $o->bind_param("i", $question_id);

        if(!$o->execute()){
            throw new Exception($o->error);
        }

        $optres = $o->get_result();

        while($opt = $optres->fetch_assoc()){
            $options[] = $opt;
        }

        $row['options'] = $options;

        $questions[] = $row;

    }

    echo json_encode([
        "status" => "success",
        "questions" => $questions
    ]);

}catch(Exception $e){

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);

}