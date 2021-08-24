<?php

include(dirname(__FILE__) . "/../config/dbConn.php");
include(dirname(__FILE__) . "/../config/Classes/users.php");
include(dirname(__FILE__) . "/../config/settings.php");
include(dirname(__FILE__) . "/../config/Classes/authenticator.php");
$auth = new Authenticator();
$auth->Required_Admin("?target=index.php");

$Username = $_GET["Username"];
$BackUrl = $_GET["BackUrl"];

$usersClass = new Users();

$usersClass
	->Disable($Username);

echo $BackUrl;

if(!isset($BackUrl))
{
	header('Location: /createDrop.php'); 
}
else
{
	header('Location: ' . $BackUrl);
}
exit();
?>