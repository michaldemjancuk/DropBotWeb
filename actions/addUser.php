<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

echo "Username: " . $Username . "<br>";
echo "Email: " . $Email . "<br>";
echo "BirthDate: " . $BirthDate . "<br>";
echo "OnlyFans: " . $PageUrl . "<br>";
echo "PermissionLevel: " . $PermissionLevel . "<br>";
echo "VerificationCode: " . $VerificationCode . "<br>";

$usersClass = new Users();

$usersClass
	->Add($Username, $Email, $BirthDate, $PageUrl, $VerificationCode, $PermissionLevel);

//header('Location: /users.php'); 
exit();
?>