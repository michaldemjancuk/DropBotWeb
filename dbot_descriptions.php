<?php

include("config/dbConn.php");
include("config/settings.php");
include("config/Classes/users.php");
include("config/Classes/authenticator.php");
include("config/Classes/dbot_Descriptions.php");

$users = new Users();
$auth = new Authenticator();
$auth->Required_Admin("?target=actions/logout.php");

$descriptionsClass = new Dbot_Descriptions();
$descriptions = $descriptionsClass->GetAll();
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


<body class="bg-light">
		<button type="button" class="btn btn-success rounded-pill" data-bs-toggle="modal" data-bs-target="#CreateDescription">
			<img src="/_icons/user-plus.svg">&nbsp;
			Add description
		</button>
        <a class="btn btn-primary rounded-pill" href="javascript:window.location.href=window.location.href">
            <img src="/_icons/refresh-cw.svg">&nbsp;
			Reload
        </a>

		<hr class="my-4">
		<ul class="list-group">
			<li class="list-group-item bg-dark text-white">
				<div class="row text-center">
					<div class="col-md-6"><b>Prologue text</b></div>
					<div class="col-md-1"><b>Emoji</b></div>
					<div class="col-md-1"><b>Is Free?</b></div>
					<div class="col-md-4 text-center"><b>Actions</b></div>
				</div>
			</li>
			<?php for ($i = 0; $i < sizeof($descriptions); ++$i) {  ?>
			<li class="list-group-item bg-light">
				<div class="row text-center">
					<div class="col-md-6"><b><?php echo utf8_decode($descriptions[$i]['PreUsernameText']); ?></b></div>
					<div class="col-md-1"><b><?php echo utf8_decode($descriptions[$i]['EmojiUsing']); ?></b></div>
					<div class="col-md-1"><b>
						<?php if($descriptions[$i]['Free'] == b'0') { ?>
							<img class="btn-sm btn-warning" src="/_icons/x.svg">
						<?php } else { ?>
							<img class="btn-sm btn-success" src="/_icons/check.svg">
						<?php } ?>
					</b></div>
					<div class="col-md-4 text-center"><b>
						<a class="btn btn-sm btn-warning disabled" href="/actions/resetPassword.php?Username=<?php echo $usersData[$i]['Id']; ?>" disabled>Edit</a>
						<a class="btn btn-sm btn-danger" onclick="confirmDelete('<?php echo $usersData[$i]["Username"]; ?>', '/actions/deleteDbot_Description.php?Id=<?php echo $descriptions[$i]['Id']; ?>')">Delete description</a>
					</b></div>
				</div>
			</li>
		<?php } ?>
		</ul>

	<script>
		function confirmDelete(id, url){
			if(confirm("Are you sure you want to delete description with id '" + id + "' forever (quite a long time)?")){
				window.location.href = url;
			}
		}
		function goToPage(url){
			window.location.href = url;
		}
	</script>
<div class="modal fade" id="CreateDescription" role="dialog" tabindex="-1" aria-labelledby="CreateDescriptionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="CreateDescriptionModalLabel">Create description</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<form action="actions/addDbot_Description.php" method="post" class="text-center border border-light p-5">
		    <div class="input-group mb-4">
	    		<textarea name="PreUsernameText" class="form-control" rows="3" placeholder="Prologue" minlength="5" maxlength="510" required></textarea> 
			</div>
		    <div class="input-group mb-4">
	    		<input type="text" name="EmojiUsing" class="form-control" placeholder="Emoji" minlength="1" maxlength="16" required>
	    	</div>
			<div class="form-check">
				<input type="checkbox" class="form-check-input" name="Free" id="FreeCheckbox">
				<label class="form-check-label" for="FreeCheckbox">Free = checked | VIP = NOT</label>
			</div>
			<br>
		    <button type="submit" class="btn btn-block btn-success">Create description</button>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Exit without saving</button>
      </div>
    </div>
  </div>
</div>
</body>
</html>