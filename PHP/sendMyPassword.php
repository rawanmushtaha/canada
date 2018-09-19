<?php
include_once 'dbinfo.php';

// Create connection
$conn = new mysqli($servername, $username, $password , $db);

// Check connection
if (mysqli_connect_errno()) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM profiles WHERE email = '" . $_GET["email"] . "'";
$result = $conn->query($sql);

if (!$result){
}else{
    
    $row = $result->fetch_array(MYSQLI_ASSOC);
    
    $to = $_GET["email"];
    
    $subject = 'Reward password request';
    
    // Message
    $message = '
<html>
<head>
  <title>Reward Membership Confirmation</title>
</head>
<body>
  <p>Hello ' . $row["fullname"] . '</p><p>Someone requested the password for this account, your password is [' . $row["password"] . ']</p>
  <p>If you have not requested a password recovery please ignore this email.</p>
  <hr>
  <B>Thank you for your business</B>
</body>
</html>
';
    
    error_log($message,0);
    
    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';
    $headers[] = 'From: Reward Admin <admin@peivandi.ca>';
    $headers[] = 'Reply-To: Reward Admin <admin@peivandi.ca>';
    
    // Mail it
    mail($to, $subject, $message, implode("\r\n", $headers));
}
$conn->close();
?>