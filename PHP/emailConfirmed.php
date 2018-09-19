<?php
include_once 'dbinfo.php';
// Create connection
$conn = new mysqli($servername, $username, $password , $db);

// Check connection
if (mysqli_connect_errno()) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM profiles WHERE token = '" . $_GET["token"] . "'";
$result = $conn->query($sql);

if (!$result){
?>
<h1>Your token is incorrect. Please register again.</h1>
<?php
}else{
    $sql = "UPDATE profiles SET activated = 1 WHERE token='" . $_GET["token"] . "'" ;
    $conn->query($sql);   
?>
<h1>Thanks for confirming your email. <a href="/Reward">Click here</a> to go to login page.</h1>
<?php
}
$conn->close();
?>