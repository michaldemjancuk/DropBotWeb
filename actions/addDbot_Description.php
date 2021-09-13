<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include(dirname(__FILE__) . "/../config/dbConn.php");
include(dirname(__FILE__) . "/../config/Classes/dbot_Descriptions.php");
include(dirname(__FILE__) . "/../config/Classes/users.php");
include(dirname(__FILE__) . "/../config/settings.php");
include(dirname(__FILE__) . "/../config/Classes/authenticator.php");
$auth = new Authenticator();
$auth->Required_Admin("?target=index.php");

$PreUsernameText = utf8_encode($_POST["PreUsernameText"]);
$EmojiUsing = utf8_encode($_POST["EmojiUsing"]);
$Free = 0;
if (isset($_POST["Free"])) 
	{ $Free = 1; }
else
	{ $Free = 0;}

$descClass = new Dbot_Descriptions();

$descClass
	->Add($PreUsernameText, $EmojiUsing, $Free);

header('Location: /dbot_descriptions.php'); 
exit();
?>