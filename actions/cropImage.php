<?php
include(dirname(__FILE__) . "/../config/settings.php");
include(dirname(__FILE__) . "/../config/dbConn.php");
include(dirname(__FILE__) . "/../config/Classes/users.php");
include(dirname(__FILE__) . "/../config/Classes/authenticator.php");
include(dirname(__FILE__) . "/../config/Classes/dbot_db.php");
include(dirname(__FILE__) . "/../config/Classes/dropImageUploads.php");
$auth = new Authenticator();
$auth->Required_Admin("?target=index.php");

// $dbot_db = new Dbot_db();
// $dbot_diu = new DropImageUploads();


$ImageUrl = $_GET["ImageUrl"] ?? "";
// $permissionRole = $_POST["permissionRole"];
// if(isset($_GET["ReturnParams"]))
// {
// 	$explodedParams = explode("-", $_GET["ReturnParams"]);
// 	$template = $explodedParams[0];
// 	$permissionRole = $explodedParams[1];
// }
// $thisUrlWithParams = "/actions/dbot_generateNewDrops.php?ReturnParams=".$template."-".$permissionRole;
// $uniqueUsers = $dbot_diu->GetAllUniqueUsersWithPhotoInPermGroup($permissionRole);
// $uniqueUsersInactive = $dbot_diu->GetAllUniqueUsersWithPhotoInPermGroup($permissionRole, 0);
// $uniqueUploads = $dbot_diu->GetAllUniqueUploadsInPermGroup($permissionRole);

// switch ($template) {
// 	case "4by2_Basic":
// 		$templateSize = 8;
// 		break;
	
// 	default:
// 		$templateSize = 0;
// 		break;
// }

// $suggestedDropsCount = round(count($uniqueUsers) / $templateSize);

?>

<!DOCTYPE html>
<html lang="cs">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="/libs/cropper/dist/cropper.css" rel="stylesheet">
	<script type="module" src="/libs/cropper/dist/cropper.js"></script>
	<script src="https://unpkg.com/feather-icons"></script>
	<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>

<body class="bg-light" style="max-width: 95%;max-height: 90% !important">
	<div class="position-fixed top-25 start-50" style="z-index: 100">
		<div class="btn-group">
          <button id="zoomIn" type="button" class="btn btn-primary" title="Zoom In">
              <i data-feather="zoom-in"></i>
          </button>
          <button id="zoomOut" type="button" class="btn btn-primary" title="Zoom Out">
              <i data-feather="zoom-out"></i>
          </button>
        </div>
	</div>
	<div class="position-absolute top-0 end-0"></div>
	<div class="position-absolute top-50 start-50"></div>
	<div class="position-absolute bottom-50 end-50"></div>
	<div class="position-absolute bottom-0 start-0"></div>
	<div class="position-absolute bottom-0 end-0"></div>

	<img id="image" src="<?php echo $ImageUrl; ?>">


	<style type="text/css">
		img {
			display: block;
			max-height: 100%;
		}
		.cropper-container{
			max-height: 80% !important;
		}
	</style>
	<script type="text/javascript">
		feather.replace();
	</script>
	<script type="module">
import {} from '/libs/cropper/dist/cropper.js';

const image = document.getElementById('image');

const cropper = new Cropper(image, {
  aspectRatio: 8 / 9,
  cropBoxResizable: false,
  dragMode: 'move',
  zoomable: true,
});
cropper.setCropBoxData({
  width: 800,
  height: 900,
});


	</script>
<script src="/libs/cropper/dist/jquery-cropper.js"></script>
<script type="text/javascript">

const ZIBtn = document.getElementById('zoomIn');
const ZOBtn = document.getElementById('zoomOut');
var image = document.getElementById('image');


$( "#zoomIn" ).click(function() {
	image.cropper.zoom(0.1);
});
$( "#zoomOut" ).click(function() {
	image.cropper.zoom(-0.1);
});
</script>

</body>