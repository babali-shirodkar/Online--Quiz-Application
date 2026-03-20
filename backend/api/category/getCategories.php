<?php

header("Content-Type: application/json");
require_once "../../confi/database.php";

$sql = "SELECT id, category_name FROM categories ORDER BY category_name ASC";

$result = $conn->query($sql);

$data = [];

if($result){
    while($row = $result->fetch_assoc()){
        $data[] = $row;
    }
}

echo json_encode([
    "status"=>"success",
    "data"=>$data
]);

?>