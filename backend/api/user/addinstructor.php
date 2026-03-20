<?php
include "../../confi/database.php";

$name=$_POST['name'];
$email=$_POST['email'];
$password=password_hash($_POST['password'],PASSWORD_DEFAULT);

$q=$conn->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,'instructor')");
$q->bind_param("sss",$name,$email,$password);

if($q->execute()){
echo json_encode(["status"=>"success"]);
}else{
echo json_encode(["status"=>"error","message"=>"Email exists"]);
}
?>