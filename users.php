<?php

include("config/dbConn.php");
include("config/settings.php");
include("config/Classes/users.php");
include("config/Classes/authenticator.php");

$users = new Users();
$auth = new Authenticator();
$auth->Required_Admin("?target=actions/logout.php");

$usersData = $users->GetAll();
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
		<button type="button" class="btn btn-success rounded-pill" data-bs-toggle="modal" data-bs-target="#CreateAccount">
			<img src="/_icons/user-plus.svg">&nbsp;
			Add account
		</button>
        <a class="btn btn-primary rounded-pill" href="javascript:window.location.href=window.location.href">
            <img src="/_icons/refresh-cw.svg">&nbsp;
			Reload
        </a>

		<hr class="my-4">
		<ul class="list-group">
			<li class="list-group-item bg-dark text-white">
				<div class="row text-center">
					<div class="col-md-2"><b>Username</b></div>
					<div class="col-md-2"><b>Email</b></div>
					<div class="col-md-1"><b>Password</b></div>
					<div class="col-md-1"><b>Birthdate</b></div>
					<div class="col-md-1"><b>Verified</b></div>
					<div class="col-md-1"><b>Access group</b></div>
					<div class="col-md-4 text-center"><b>Actions</b></div>
				</div>
			</li>
			<?php for ($i = 0; $i < sizeof($usersData); ++$i) {  ?>
			<li class="list-group-item bg-light">
				<div class="row text-center">
					<div class="col-md-2"><b><?php echo $usersData[$i]['Username']; ?></b></div>
					<div class="col-md-2"><b><?php echo $usersData[$i]['Email']; ?></b></div>
					<div class="col-md-1"><b><button class="btn btn-sm btn-warning disabled">Reset</button></b></div>
					<div class="col-md-1"><b><?php echo $usersData[$i]['BirthDate']; ?></b></div>
					<div class="col-md-1"><b>
						<?php if(isset($usersData[$i]['VerificationCode'])) { ?>
							<img class="btn-sm btn-warning" src="/_icons/x.svg">
						<?php } else { ?>
							<img class="btn-sm btn-success" src="/_icons/check.svg">
						<?php } ?>
					</b></div>
					<div class="col-md-1"><b><?php echo $usersData[$i]['PermissionLevel']; ?></b></div>
					<div class="col-md-4 text-center"><b>
						<a class="btn btn-sm btn-warning disabled" href="/actions/resetPassword.php?Username=<?php echo $usersData[$i]['Id']; ?>">Reset password</a>
						<a class="btn btn-sm btn-danger" onclick="confirmDelete('<?php echo $usersData[$i]["Username"]; ?>', '/actions/deleteUser.php?Id=<?php echo $usersData[$i]["Id"]; ?>')">Delete account</a>
					</b></div>
				</div>
			</li>
		<?php } ?>
		</ul>

	<script>
		function confirmReset(id, url){
			if(confirm("Opravdu si přejete uživateli " + id + " vymazat heslo?")){
				window.location.href = url;
			}
		}
		function confirmDelete(id, url){
			if(confirm("Are you sure you want to delete user '" + id + "' forever (quite a long time)?")){
				window.location.href = url;
			}
		}
		function goToPage(url){
			window.location.href = url;
		}
	</script>
<div class="modal fade" id="CreateAccount" role="dialog" tabindex="-1" aria-labelledby="CreateAccountModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="CreateAccountModalLabel">Create account</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<form action="actions/addUser.php" method="post" class="text-center border border-light p-5">
		    <div class="input-group mb-4">
				<div class="input-group-text">@</div>
	    		<input type="text" name="Username" class="form-control" placeholder="Username" minlength="5" required>
			</div>
		    <div class="input-group mb-4">
	    		<input type="email" name="Email" class="form-control" placeholder="Email" minlength="5" required>
	    	</div>
		    <div class="input-group mb-4">
				<div class="input-group-text">Birth date:</div>
	    		<input type="date" name="BirthDate" class="form-control" placeholder="Birth date" min="1920-01-01" max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>" required>
			</div>
		    <div class="input-group">
				<div class="input-group-text"><img src="/_icons/link.svg"></div>
	    		<input type="url" name="OnlyFans" class="form-control" placeholder="OnlyFans link" minlength="10" required>
			</div>
		    <br>
		    <div class="row">
		    	<div class="col">
					<input class="form-control" name="PermissionLevel" list="datalistOptions" id="exampleDataList" placeholder="Permission level">
					<datalist id="datalistOptions">
						<option value="10">Basic user (follower)</option>
						<option value="20">Model</option>
						<option value="90">Admin</option>
					</datalist>
			    </div>
		    </div>
			<br>
		    <button type="submit" class="btn btn-block btn-success">Create account</button>
		    <hr>
		    <em>Password will be set up during email verification in first login attempt - there will be user required to consent with GOP and GDPR orelse data would be wiped.</em>
		    </p>
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