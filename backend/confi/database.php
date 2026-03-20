<?php

$host = "localhost";
$db = "online_quiz_app";
$user = "root";
$pass = "";

$conn = new mysqli($host,$user,$pass,$db);

if($conn->connect_error){
    die("Database connection failed");
}

$api_url = 'http://localhost/quiz/backend/api/';
$site_url = 'http://localhost/quiz/';
?>