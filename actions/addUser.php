<?php

include(dirname(__FILE__) . "/../config/dbConn.php");
include(dirname(__FILE__) . "/../config/Classes/users.php");
include(dirname(__FILE__) . "/../config/settings.php");
include(dirname(__FILE__) . "/../config/Classes/authenticator.php");
$auth = new Authenticator();
$auth->Required_Admin("?target=index.php");

$Username = $_POST["Username"];
$Email = $_POST["Email"];
$BirthDate = $_POST["BirthDate"];
$PageUrl = $_POST["OnlyFans"];
$PermissionLevel = $_POST['PermissionLevel'];
$VerificationCode = str_pad(rand(0, pow(10, 4)-1), 4, '0', STR_PAD_LEFT);

$usersClass = new Users();

$usersClass
	->Add($Username, $Email, $BirthDate, $PageUrl, $VerificationCode, $PermissionLevel);

header('Location: /users.php'); 
exit();
?>