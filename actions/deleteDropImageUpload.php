<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include(dirname(__FILE__) . "/../config/settings.php");
include(dirname(__FILE__) . "/../config/dbConn.php");
include(dirname(__FILE__) . "/../config/Classes/users.php");
include(dirname(__FILE__) . "/../config/Classes/authenticator.php");
include(dirname(__FILE__) . "/../config/Classes/dropImageUploads.php");
$auth = new Authenticator();
$auth->Required_User("?target=index.php");

$imageId = $_GET["imageId"];

//BEFORE DETITION USER ANONYMIZE REPAIRS


$DIUpload = new DropImageUploads();

$ImageData = $DIUpload->GetById($imageId);
if (file_exists(dirname(__FILE__) . "/../" . $ImageData["LocalUrl"])) {
   unlink(dirname(__FILE__) . "/../" . $ImageData["LocalUrl"]);
}

$DIUpload
  ->Delete($imageId);

header('Location: /myDrops.php'); 
exit();
?>
