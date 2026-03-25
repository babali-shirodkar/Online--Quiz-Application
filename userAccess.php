<?php
session_start();

if (!isset($_SESSION['user_id'])) {

    $current_url = $_SERVER['REQUEST_URI'];

    header("Location: /quiz/login.php?redirect_url=" . urlencode($current_url));
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$name = $_SESSION['name'];
$email = $_SESSION['email'];
?>