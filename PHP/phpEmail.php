<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once "vendor/autoload.php";

$mail = new PHPMailer();

//Enable SMTP debugging.
$mail->SMTPDebug = 3;
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

$mail->From = "amir.peivandi@gmail.com";
$mail->FromName = "Amir Peivandi";

$mail->addAddress("apeivandi@bsmarter.ca", "Amir Peivandi (BSmarter)");

$mail->isHTML(true);

$mail->Subject = "Subject Text";
$mail->Body = "<i>Mail body in HTML</i>";
$mail->AltBody = "This is the plain text version of the email content";

if(!$mail->send())
{
    echo "Mailer Error: " . $mail->ErrorInfo;
}
else
{
    echo "Message has been sent successfully";
}