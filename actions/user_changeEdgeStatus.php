<?php

include(dirname(__FILE__) . "/../config/dbConn.php");
include(dirname(__FILE__) . "/../config/Classes/users.php");
include(dirname(__FILE__) . "/../config/settings.php");
include(dirname(__FILE__) . "/../config/Classes/authenticator.php");
$auth = new Authenticator();
$auth->Required_Admin("?target=index.php");

$Username = $_POST["Username"];
$EdgeStatus = $_POST["EdgeStatus"];
$PermissionLevel = $_POST["PermissionLevel"];

$usersClass = new Users();

if(!isset($PermissionLevel))
$usersClass
	->UpdateEdgeStatus($Username, $EdgeStatus);
else
$usersClass
	->UpdateEdgeStatus($Username, $EdgeStatus, $PermissionLevel);

exit();
?>