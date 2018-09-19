<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once "vendor/autoload.php";

function sendEmail($to,$name,$subject,$message){
    // To send HTML mail, the Content-type header must be set
    $headers =  array();
    
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';
    $headers[] = 'From: Reward Admin <admin@peivandi.ca>';
    $headers[] = 'Reply-To: Reward Admin <admin@peivandi.ca>';
    
    // Mail it
    mail($to, $subject, $message, implode("\r\n", $headers));
}

function sendSMTPEmail($to,$name,$subject,$message){
    
    $mail = new PHPMailer();
    
    //Enable SMTP debugging.
    // $mail->SMTPDebug = 3;
    //Set PHPMailer to use SMTP.
    $mail->isSMTP();
    //Set SMTP host name
    $mail->Host = "smtp.gmail.com";
    //Set this to true if SMTP host requires authentication to send email
    $mail->SMTPAuth = true;
    //Provide username and password
    $mail->Username = "bsmarterdotca@gmail.com";
    $mail->Password = "lOefp17KBSADMIN";
    //If SMTP requires TLS encryption then set it
    $mail->SMTPSecure = "tls";
    //Set TCP port to connect to
    $mail->Port = 587;
    
    $mail->From = "admin@bsmarter.ca";
    $mail->FromName = "BSmarter Admin";
    
    $mail->addAddress($to, $name);
    
    $mail->isHTML(true);
    
    $mail->Subject = $subject;
    $mail->Body = $message;
    
    return $mail->send();
}

function sendChangeConfirmationEmail($to,$name) {
    $subject = 'Reward membership email change confirmation';
    $message = '
<html>
<head>
  <title>Reward Membership email change Confirmation</title>
</head>
<body>
  <p>Hello ' . $name . '</p><p>Please note your email is changed and going forward you need to use the new email address to login into your account.
 </p>
  <p>If you have not requested this change please contact us by sending an email to admin@bsmarter.ca</p>
  <hr>
  <B>Thank you for your business</B>
</body>
</html>
';
    
    sendSMTPEmail($to,$name,$subject,$message);
}

function sendConfirmationEmail($to,$name,$token) {
    $subject = 'Reward membership confirmation';
    $message = '
<html>
<head>
  <title>Reward Membership Confirmation</title>
</head>
<body>
  <p>Hello ' . $name . '</p><p>Welcome to Rewards program where you always get rewarded for your daily shoppings at our participating locaitons.</p>
  <p>Please click <a href="http://peivandi.ca/Rewards/PHP/emailConfirmed.php?token=' . $token .'">here</a> to confirm your email </p>
  <p>If you have not registered for our site please ignore this email.</p>
  <hr>
  <B>Thank you for your business</B>
</body>
</html>
';
    
    sendSMTPEmail($to,$name,$subject,$message);
}

function getGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }
    else {
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
        .substr($charid, 0, 8).$hyphen
        .substr($charid, 8, 4).$hyphen
        .substr($charid,12, 4).$hyphen
        .substr($charid,16, 4).$hyphen
        .substr($charid,20,12)
        .chr(125);// "}"
        return $uuid;
    }
}

?>