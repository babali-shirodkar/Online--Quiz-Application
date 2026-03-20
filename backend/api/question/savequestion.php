<?php

header("Content-Type: application/json");

require_once "../../confi/database.php";

/* ================================
   GET JSON INPUT
================================ */

$data = json_decode(file_get_contents("php://input"), true);

$questions = $data['questions'] ?? [];

/* ================================
   VALIDATION
================================ */

if(empty($questions)){
    echo json_encode([
        "status" => "error",
        "message" => "No questions received"
    ]);
    exit;
}

/* ================================
   LOOP ALL QUESTIONS
================================ */

foreach($questions as $q){

    $quiz_id = $q['quiz_id'] ?? null;
    $question_id = $q['question_id'] ?? null;
    $question_text = $q['question_text'] ?? '';
    $marks = $q['marks'] ?? 1;
    $type = $q['question_type'] ?? 'mcq';
    $options = $q['options'] ?? [];

    if(empty($quiz_id) || $question_text == ""){
        continue;
    }

    /* ================================
       INSERT OR UPDATE QUESTION
    ================================= */

    if(!empty($question_id)){

        /* UPDATE QUESTION */

        $stmt = $conn->prepare("
            UPDATE questions
            SET question_text = ?, question_type = ?, marks = ?
            WHERE id = ? AND quiz_id = ?
        ");

        $stmt->bind_param("ssiii", $question_text, $type, $marks, $question_id, $quiz_id);

        if(!$stmt->execute()){
            echo json_encode([
                "status"=>"error",
                "message"=>"Question update failed"
            ]);
            exit;
        }

    } else {

        /* INSERT QUESTION */

        $stmt = $conn->prepare("
            INSERT INTO questions
            (quiz_id, question_text, question_type, marks)
            VALUES (?,?,?,?)
        ");

        $stmt->bind_param("issi", $quiz_id, $question_text, $type, $marks);

        if(!$stmt->execute()){
            echo json_encode([
                "status"=>"error",
                "message"=>"Question insert failed"
            ]);
            exit;
        }

        $question_id = $conn->insert_id;
    }

    /* ================================
       RESET OPTIONS
    ================================= */

    $deleteOpt = $conn->prepare("
        DELETE FROM options WHERE question_id = ?
    ");

    $deleteOpt->bind_param("i", $question_id);
    $deleteOpt->execute();


    /* ================================
       INSERT OPTIONS
    ================================= */

    if(!empty($options)){

        foreach($options as $opt){

            $text = $opt['option_text'] ?? '';
            $correct = $opt['is_correct'] ?? 0;

            if(trim($text) == "") continue;

            $optstmt = $conn->prepare("
                INSERT INTO options
                (question_id, option_text, is_correct)
                VALUES (?,?,?)
            ");

            $optstmt->bind_param("isi", $question_id, $text, $correct);
            $optstmt->execute();
        }
    }

}

/* ================================
   FINAL RESPONSE
================================ */

echo json_encode([
    "status"=>"success",
    "message"=>"All questions saved successfully"
]);

?>