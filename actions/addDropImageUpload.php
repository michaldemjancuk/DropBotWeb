<?php
error_reporting(E_ALL);
include(dirname(__FILE__) . "/../config/settings.php");
include(dirname(__FILE__) . "/../config/dbConn.php");
include(dirname(__FILE__) . "/../config/Classes/users.php");
include(dirname(__FILE__) . "/../config/Classes/authenticator.php");
include(dirname(__FILE__) . "/../config/Classes/dropImageUploads.php");
$auth = new Authenticator();
$auth->Required_User("?target=index.php");


$newName = bin2hex(openssl_random_pseudo_bytes(16)) . ".png";
$nameWithFolder =  "dropImageUploads/" . $newName;
$nameForSaving = dirname(__FILE__) . "/../" . $nameWithFolder;

$data = $_POST['image'];
$UserId = $_POST["profileId"];

$image_array_1 = explode(";", $data);
$image_array_2 = explode(",", $image_array_1[1]);
$data = base64_decode($image_array_2[1]);

file_put_contents($nameForSaving, $data);


$DIUpload = new DropImageUploads();

$DIUpload
	->Add($nameWithFolder, $UserId);

//header('Location: /myDrops.php'); 
exit();
?>
