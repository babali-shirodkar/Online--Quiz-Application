<?php
include "../../confi/database.php";

$res = $conn->query("SELECT * FROM users WHERE role='instructor'");

$data = [];

while($row = $res->fetch_assoc()){
    $data[] = $row;
}

echo json_encode(["status"=>"success","data"=>$data]);
?>