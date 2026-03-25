<?php

header("Content-Type: application/json");
require_once "../../confi/database.php";

try {

    $sql = "SELECT id, category_name FROM categories ORDER BY category_name ASC";
    $result = $conn->query($sql);

    if(!$result){
        throw new Exception("Failed to fetch categories");
    }

    $data = [];

    while($row = $result->fetch_assoc()){
        $data[] = $row;
    }

    echo json_encode([
        "status" => "success",
        "data" => $data
    ]);

} catch(Exception $e){

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);

}
?>