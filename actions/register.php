<?php

include(dirname(__FILE__) . "/../config/dbConn.php");
include(dirname(__FILE__) . "/../config/settings.php");
include(dirname(__FILE__) . "/../config/Classes/users.php");

$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];
$birthdate = $_POST["birth"];
$PageUrl = $_POST['pageUrl'];
$verificationCode = 1234;
$permissionLevel = 90; //


// 0 - Guest
// 10 - Registered user
// 11 - Registered user - Pro
// 20 - Registered model
// 90 - Admin
// 99 - Majki


$hash = password_hash($password, PASSWORD_DEFAULT);

echo 
$username."<br>".
$email."<br>".
$password." - ".$hash."<br>".
$birthdate."<br>".
$PageUrl."<br>".
$verificationCode."<br>".
$permissionLevel; 
//$shift = strtoupper($_POST["shift"]);

$usersClass = new Users();

$usersClass
	->Register($username, $email, $hash, $birthdate, $PageUrl, $verificationCode, $permissionLevel);

header('Location: /login.php'); 
exit();
?>