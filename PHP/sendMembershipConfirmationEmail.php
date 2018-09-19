<?php

include_once 'util.php';

$to = $_GET["email"];
$name = $_GET["fullname"];
$token = $_GET["token"];

sendConfirmationEmail($to, $name, $token);

?>