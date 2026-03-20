<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: logout.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$name = $_SESSION['name'];
$email = $_SESSION['email'];
?>