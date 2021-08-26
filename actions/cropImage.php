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


<html>
	<head>
		<title></title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>        
		<link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />
		<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet"/>
		<script src="https://unpkg.com/dropzone"></script>
		<script src="https://unpkg.com/cropperjs"></script>
		
		<style>

		.image_area {
		  position: relative;
		}

		.preview {
  			overflow: hidden;
  			width: 160px; 
  			height: 160px;
  			margin: 10px;
  			border: 1px solid red;
		}

		.modal-lg{
  			max-width: 1000px !important;
		}

		.overlay {
		  position: absolute;
		  bottom: 10px;
		  left: 0;
		  right: 0;
		  background-color: rgba(255, 255, 255, 0.5);
		  overflow: hidden;
		  height: 0;
		  transition: .5s ease;
		  width: 100%;
		}

		.text {
		  color: #333;
		  font-size: 20px;
		  position: absolute;
		  top: 50%;
		  left: 50%;
		  -webkit-transform: translate(-50%, -50%);
		  -ms-transform: translate(-50%, -50%);
		  transform: translate(-50%, -50%);
		  text-align: center;
		}
		
		</style>
	</head>
	<body>
		<form method="post">
			<label for="upload_image">
				<div class="overlay">
					<div class="text bg-dark">Click here for upload</div>
				</div>
				Click for upload and crop image
				<input type="file" name="image" class="image" id="upload_image" style="display:none" />
			</label>
		</form>
		<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
		  	<div class="modal-dialog modal-lg" role="document">
		    	<div class="modal-content">
		      		<div class="modal-header">
		        		<h5 class="modal-title">Crop Image Before Upload</h5>
		        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          			<span aria-hidden="true">×</span>
		        		</button>
		      		</div>
		      		<div class="modal-body">
		        		<div class="img-container">
		            		<div class="row">
		                		<div class="col-md-8">
		                    		<img src="<?php echo $ImageUrl; ?>" id="sample_image" />
		                		</div>
		                		<div class="col-md-4">
		                    		<div class="preview"></div>
		                		</div>
		            		</div>
		        		</div>
		      		</div>
		      		<div class="modal-footer">
		      			<button type="button" id="crop" class="btn btn-primary">Crop & upload image</button>
		        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
		      		</div>
		    	</div>
		  	</div>
		</div>
	</body>
</html>

<script>

$(document).ready(function(){

	var $modal = $('#modal');

	var image = document.getElementById('sample_image');

	var cropper;

	$('#upload_image').change(function(event){
		var files = event.target.files;

		var done = function(url){
			image.src = url;
			$modal.modal('show');
		};

		if(files && files.length > 0)
		{
			reader = new FileReader();
			reader.onload = function(event)
			{
				done(reader.result);
			};
			reader.readAsDataURL(files[0]);
		}
	});

	$modal.on('shown.bs.modal', function() {
		cropper = new Cropper(image, {
			aspectRatio: 1,
			viewMode: 3,
			preview:'.preview'
		});
	}).on('hidden.bs.modal', function(){
		cropper.destroy();
   		cropper = null;
	});

	$('#crop').click(function(){
		canvas = cropper.getCroppedCanvas({
			width:400,
			height:400
		});

		canvas.toBlob(function(blob){
			url = URL.createObjectURL(blob);
			var reader = new FileReader();
			reader.readAsDataURL(blob);
			reader.onloadend = function(){
				var base64data = reader.result;
				$.ajax({
					url:'cropUpload.php',
					method:'POST',
					data:{image:base64data},
					success:function(data)
					{
						$modal.modal('hide');
						$('#uploaded_image').attr('src', data);
					}
				});
			};
		});
	});
	
});
</script>


<?php exit(); ?>





<!--DOCTYPE html>
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
		<div class="btn-group">
          <button id="cropBtn" type="button" class="btn btn-primary" title="Crop image">
              <i data-feather="scissors"></i>
          </button>
          <button id="saveBtn" type="button" class="btn btn-success" title="Save image">
              <i data-feather="save"></i>
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


$(document).ready(function () {

	var image = document.getElementById('image');

	$( "#zoomIn" ).click(function() {
		image.cropper.zoom(0.1);
	});
	$( "#zoomOut" ).click(function() {
		image.cropper.zoom(-0.1);
	});
	$( "#saveBtn" ).click(function() {
        downloadCanvas(this);
	});


    
    document.getElementById('zoomOut').addEventListener('click', function () {
        downloadCanvas(this);
    }, false);
}); // ready

function downloadCanvas(link) {
	var image = document.getElementById('image');
    link.href = image.cropper.getCroppedCanvas().toDataURL();
    link.download = 'CroppedImage.png';
}

</script>

</body> -->