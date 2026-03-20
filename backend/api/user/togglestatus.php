<?php
include "../../confi/database.php";

$id=$_POST['id'];

$q=$conn->query("UPDATE users 
SET status = IF(status='active','inactive','active') 
WHERE user_id=$id");

echo json_encode(["status"=>"success"]);
?>