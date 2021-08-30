<?php
include(dirname(__FILE__) . "/../config/settings.php");
include(dirname(__FILE__) . "/../config/dbConn.php");
include(dirname(__FILE__) . "/../config/Classes/users.php");
include(dirname(__FILE__) . "/../config/Classes/authenticator.php");
include(dirname(__FILE__) . "/../config/Classes/dbot_db.php");
include(dirname(__FILE__) . "/../config/Classes/dropImageUploads.php");
$auth = new Authenticator();
$auth->Required_Admin("?target=index.php");

$dbot_db = new Dbot_db();
$users = new Users();
$dbot_diu = new DropImageUploads();

$template = $_POST["template"];
$permissionRole = $_POST["permissionRole"];
if(isset($_GET["ReturnParams"]))
{
	$explodedParams = explode("-", $_GET["ReturnParams"]);
	$template = $explodedParams[0];
	$permissionRole = $explodedParams[1];
}

$thisUrlWithParams = "/actions/dbot_generateNewDrops.php?ReturnParams=".$template."-".$permissionRole;
$uniqueUsers = $dbot_diu->GetAllUniqueUsersWithPhotoInPermGroup($permissionRole);
$uniqueUsersInactive = $dbot_diu->GetAllUniqueUsersWithPhotoInPermGroup($permissionRole, 0);
$uniqueUploads = $dbot_diu->GetAllUniqueUploadsInPermGroup($permissionRole);

switch ($template) {
	case "4by2_Basic":
		$templateSize = 8;
		break;
	
	default:
		$templateSize = 0;
		break;
}

$suggestedDropsCount = round(count($uniqueUsers) / $templateSize);

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
</head>

<body class="bg-light" style="width: 99%">
	<div class="row">
		<div class="col">
			<ul class="list-group">
				<li class="list-group-item bg-dark text-white">
					<div class="row text-center">
						<div class="col-md-4"><b>Username</b></div>
						<div class="col-md-4"><b>Drop occurances</b></div>
						<div class="col-md-4 text-center"><b>Actions</b></div>
					</div>
				</li>
				<?php for ($i = 0; $i < sizeof($uniqueUsers); ++$i) {  ?>
				<li class="list-group-item bg-light">
					<div class="row text-center">
						<div class="col-md-4">
							<b><?php echo $uniqueUsers[$i]['Username']; ?></b>
						</div>
						<div class="col-md-4">
							<select 
								class="form-select GetAllOccurancesSelect" 
								id="<?php echo $uniqueUsers[$i]['Username']; ?>" 
								aria-label="User occurances count" 
								required>
								<?php $users->GetAllOccurancesSelect($users->GetOccurancesForUser($uniqueUsers[$i]['Username'])); ?>
							</select>
						</div>
						<div class="col-md-4 text-center">
							<b>
								<a class="btn btn-sm btn-warning" href="/actions/disableAccount.php?Username=<?php echo $uniqueUsers[$i]['Username']; ?>&BackUrl=<?php echo $thisUrlWithParams; ?>">Disable account</a>
							</b>
						</div>
					</div>
				</li>
			<?php } ?>
				<?php for ($i = 0; $i < sizeof($uniqueUsersInactive); ++$i) {  ?>
				<li class="list-group-item bg-warning">
					<div class="row text-center">
						<div class="col-md-4"><b><?php echo $uniqueUsersInactive[$i]['Username']; ?></b></div>
						<div class="col-md-8 text-center"><b>
							<a class="btn btn-sm btn-success" href="/actions/enableAccount.php?Username=<?php echo $uniqueUsersInactive[$i]['Username']; ?>&BackUrl=<?php echo $thisUrlWithParams; ?>">Enable account</a>
						</b></div>
					</div>
				</li>
			<?php } ?>
			</ul>
		</div>
		<div class="col">
			<ul class="list-group">
				<li class="list-group-item bg-dark text-white">
					<div class="row text-center">
						<div class="col-md-8"><b>Configuration</b></div>
						<div class="col-md-4 text-center"><b>Value</b></div>
					</div>
				</li>
				<li class="list-group-item bg-light">
					<div class="row text-center">
						<div class="col-md-8"><b>Unique users for this type of drop:</b></div>
						<div class="col-md-4 text-center"><b>
							<?php echo sizeof($uniqueUsers); ?>
						</b></div>
					</div>
				</li>
				<li class="list-group-item bg-light">
					<div class="row text-center">
						<div class="col-md-8"><b>Photos for this type of drop:</b></div>
						<div class="col-md-4 text-center"><b>
							<?php echo sizeof($uniqueUploads); ?>
						</b></div>
					</div>
				</li>
				<li class="list-group-item bg-light">
					<div class="row text-center">
						<div class="col-md-8"><b>Unique drops suggested:</b></div>
						<div class="col-md-4 text-center"><b>
							<?php echo $suggestedDropsCount; ?>
						</b></div>
					</div>
				</li>
				<li class="list-group-item bg-light">
					<div class="row text-center">
						<div class="col-md-8"><b>Template:</b></div>
						<div class="col-md-4 text-center"><b>
							<?php echo $template; ?>
						</b></div>
					</div>
				</li>
				<li class="list-group-item bg-light">
					<form method="post" class="row" action="/actions/dbot_processImage.php">
						<div class="col-md-8"></div>
						<input type="text" name="template" style="display: none;" value="<?php echo $template; ?>">
						<input type="number" name="dropsCount" style="display: none;" value="<?php echo $suggestedDropsCount; ?>">
						<input type="number" name="permissionRole" style="display: none;" value="<?php echo $permissionRole; ?>">
						<div class="col-md-8"></div>
						<button type="submit" class="btn btn-sm btn-success col-md-4">Generate</button>
					</form>
				</li>
			</ul>
		</div>
	</div>
</body>

<script type="text/javascript">

	function updateUserOccurances(username, occuranceKey) {
		$.post("/actions/updateUserOccurances.php",
		{
			Username:username,
			Occurances:occuranceKey
		},
		function(data,status){
			alert("Update status: " + status);
		});
	}

	$(".GetAllOccurancesSelect").change(function(){
	    var username = $(this).attr('id');
	    var occurances = $(this).val();
	    updateUserOccurances(username, occurances);
	});
</script>

<?php

function GetSelectForValue()
{
	# code...
}

?>