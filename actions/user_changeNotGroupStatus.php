<?php

include(dirname(__FILE__) . "/../config/dbConn.php");
include(dirname(__FILE__) . "/../config/Classes/users.php");
include(dirname(__FILE__) . "/../config/settings.php");
include(dirname(__FILE__) . "/../config/Classes/authenticator.php");
$auth = new Authenticator();
$auth->Required_Admin("?target=index.php");

$Username = $_POST["Username"] ?? $_GET["Username"];
$NotGroup = $_POST["NotGroup"] ?? $_GET["NotGroup"];
$PermissionLevel = $_POST["PermissionLevel"];

$usersClass = new Users();

if(!isset($PermissionLevel))
$usersClass
	->UpdateNotGroupStatus($Username, $EdgeStatus);
else
$usersClass
	->UpdateNotGroupStatus($Username, $EdgeStatus, $PermissionLevel);

exit();
?>