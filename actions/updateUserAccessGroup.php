<?php

include(dirname(__FILE__) . "/../config/dbConn.php");
include(dirname(__FILE__) . "/../config/Classes/users.php");
include(dirname(__FILE__) . "/../config/settings.php");
include(dirname(__FILE__) . "/../config/Classes/authenticator.php");
$auth = new Authenticator();
$auth->Required_Admin("?target=index.php");

$Id = $_POST["Id"];
$PermissionLevel = $_POST["PermissionLevel"];

$usersClass = new Users();

$usersClass
	->SetRightsPermissionGroup($Id, $PermissionLevel);
	
header('Location: /users.php'); 
exit();
?>