<?php
error_reporting(E_ALL);
include(dirname(__FILE__) . "/../config/settings.php");
include(dirname(__FILE__) . "/../config/dbConn.php");
include(dirname(__FILE__) . "/../config/Classes/users.php");
include(dirname(__FILE__) . "/../config/Classes/authenticator.php");
include(dirname(__FILE__) . "/../config/Classes/dropImageUploads.php");
$auth = new Authenticator();
$auth->Required_User("?target=index.php");

$filename = basename($_FILES["uploadDropImage"]["name"]);
$tempname = $_FILES["uploadDropImage"]["tmp_name"];    
$UserId = $_POST["profileId"];
$newName = bin2hex(openssl_random_pseudo_bytes(16)) . ".png";
$msg = "";

$dirnameThis = dirname(__FILE__);
$root = substr($dirnameThis, 0, strrpos( $dirnameThis, '/'));
$target_dir = $root . "/dropImageUploads/";
$target_file = $target_dir . $newName;
$nameWithFolder =  "dropImageUploads/" . $newName;
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
  $check = getimagesize($_FILES["uploadDropImage"]["tmp_name"]);
  if($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }
// Check if file already exists
if (file_exists($target_file)) {
  echo "Sorry, file already exists.";
  $uploadOk = 0;
}

// Check file size
if ($_FILES["uploadDropImage"]["size"] > 50000000) {
  echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["uploadDropImage"]["tmp_name"], $target_file)) {
    echo "The file ". htmlspecialchars( basename( $_FILES["uploadDropImage"]["name"])). " has been uploaded and moved from" . $_FILES["uploadDropImage"]["tmp_name"];
  } else {
    echo "Sorry, there was an error uploading your file.<br>";
  }
}

$DIUpload = new DropImageUploads();

$DIUpload
	->Add($nameWithFolder, $UserId);

echo $msg;

header('Location: /myDrops.php'); 
exit();
?>
