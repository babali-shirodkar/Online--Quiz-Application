<?php
header("Content-Type: application/json");

// INCLUDE FILES
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$message = $_POST['message'] ?? '';

if(!$name || !$email || !$message){
    echo json_encode(["status"=>"error","message"=>"All fields required"]);
    exit;
}

$mail = new PHPMailer(true);

try{

    // DEBUG (remove later)
    // $mail->SMTPDebug = 2;

    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'babalishirodakar28@gmail.com';
    $mail->Password   = 'dypkqcntbtxyeyid';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->SMTPOptions = [
    'ssl' => [
        'verify_peer'       => false,
        'verify_peer_name'  => false,
        'allow_self_signed' => true
    ]
];

    // FIXED SENDER
    $mail->setFrom('babalishirodakar28@gmail.com', 'QuizApp');

    // USER EMAIL AS REPLY
    $mail->addReplyTo($email, $name);

    // RECEIVER
    $mail->addAddress('babalishirodakar28@gmail.com', 'Admin');

    $mail->isHTML(true);
    $mail->Subject = "New Contact Message";

    $mail->Body = "
        <h3>New Contact Message</h3>
        <p><b>Name:</b> $name</p>
        <p><b>Email:</b> $email</p>
        <p><b>Message:</b><br>$message</p>
    ";

    $mail->send();

    echo json_encode(["status"=>"success"]);

}catch(Exception $e){

    echo json_encode([
        "status"=>"error",
        "message"=>$mail->ErrorInfo
    ]);
}