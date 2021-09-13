<?php

include(dirname(__FILE__) . "/../config/dbConn.php");
include(dirname(__FILE__) . "/../config/settings.php");
include(dirname(__FILE__) . "/../config/Classes/users.php");
include(dirname(__FILE__) . "/../config/Classes/authenticator.php");
include(dirname(__FILE__) . "/../config/Classes/dbot_Descriptions.php");

$users = new Users();
$auth = new Authenticator();
$auth->Required_Admin("?target=actions/logout.php");

$Id = $_POST["Id"] ?? $_GET["Id"];
$PolicySelected = $_POST["PolicySelected"] ?? $_GET["PolicySelected"];

$descriptionsClass = new Dbot_Descriptions();

$descriptionsClass
	->UpdatePolicy($Id, $PolicySelected);
	
exit();
header('Location: /dbot_descriptions.php'); 
?>