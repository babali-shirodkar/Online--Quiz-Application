<?php
include "../../confi/database.php";

$id=$_POST['id'];

$conn->query("UPDATE users SET status='inactive' WHERE user_id=$id");

echo json_encode(["status"=>"success"]);
?>