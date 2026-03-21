<?php
include "../../confi/database.php";

$res = $conn->query("SELECT * FROM users WHERE role='participant'");

$data = [];

while($row = $res->fetch_assoc()){
    $data[] = $row;
}

echo json_encode(["status"=>"success","data"=>$data]);
?>