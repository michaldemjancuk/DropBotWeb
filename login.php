<?php

include("config/dbConn.php");
include("config/settings.php");
include("config/Classes/users.php");
include("config/Classes/authenticator.php");

$authenticatorSettings = AuthenticatorSettings();
$auth = new Authenticator();

if($auth->IsLoggedIn())
{
	header('Location: index.php', TRUE, 302);
}

if(!empty($_GET['result']))
{
		echo '<script>alert("Message: ' . $_GET['result'] . '");</script>';
}

if(!empty($_POST['type']))
{
	if ($_POST["type"] == "login") 
	{
		ClassicLogin();
	} 
	else if ($_POST["type"] == "reset") 
	{
		FirstLogin();
	} 
	else 
	{ 
		echo '<script>alert("Dotaz na server byl zablokován! ' . $_POST['type'] . '");</script>';
	}
}

function ClassicLogin()
{
	$users = new Users();

	if($users->CheckAccountExists($_POST['username']))
	{
		$usersPass = $users->GetPasswordHash($_POST['username']);
		$notEnc = $_POST['password'];
		$passwordHash = password_hash($notEnc, PASSWORD_DEFAULT);
		if(password_verify($notEnc, $usersPass)){
			$auth = new Authenticator();
			$auth->LogIn($_POST['username'], $passwordHash);
		} else {
			echo "<script>alert('Wrong password!');</script>";
		}
	}
	else
	{
		echo '<script>alert("Account with username (' . $_POST['id'] . ') does not exist !");</script>';
	}
}

function FirstLogin()
{
	$users = new Users();
	if($users->CheckAccountExists($_POST['id']))
	{
		if (!$users->CheckPasswordExists($_POST['id'])) {
			if($users->ValidateAndSetAccountPass(
				$_POST['id'], 
				$_POST['fName'],
				$_POST['lName'],
				$_POST['password']))
			{
				echo '<script>alert("Uživateli s ID (' . $_POST['id'] . ') bylo nastaveno heslo !");</script>';
			}
			else
			{
				echo '<script>alert("Uživateli s ID (' . $_POST['id'] . ') NEBYLO nastaveno heslo, zkuste překontrolovat Vámi zadané údaje, pokud jste si jisti, že NEJDE o Vaši chybu, kontaktujte prosím administrátora !!!");</script>';
			}
		}
		else
		{
			echo '<script>alert("Účet s tímto ID (' . $_POST['id'] . ') již má nastaveno heslo !");</script>';
		}
	}
	else
	{
		echo '<script>alert("Účet s tímto ID (' . $_POST['id'] . ') neexistuje !");</script>';
	}
}

?>

<!DOCTYPE html>
<html lang="cs" style="height: 100%">
<head>
	<title>ShotBot</title>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/css/custom.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="js/custom.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
    	function validate(){
			if ($('#register_password').val() != $('#register_password2').val()
			){
				alert("Zadaná hesla se musí shodovat !");
				return false;
			}
			else if (($('#register_password').val()).length < <?php echo LoginSettings()['minPassword'] ?>) 
			{
				alert("Heslo musí být nejméně 6 znaků dlouhé");
				return false;
			}
			else
			{
				return true;
			}
		}
    </script>
</head>

<body class="bg-secondary" style="height: 100% !important">
	<div class="mx-auto col-md-6 position-absolute top-50 start-50 translate-middle">
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item" role="presentation">
				<button class="nav-link active bg-white" id="login-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Login</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link bg-white" id="registration-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Registration</button>
			</li>
		</ul>
		<div class="tab-content bg-white rounded" id="myTabContent">
			<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="login-tab">
				<div class="container">
					<div class="row">
						
						<span class="spacer-25"></span>

						<form method="post" action="" class="mb-3">
							<div class="row mb-3">
								<label for="username" class="col-sm-2 col-form-label">Username</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="username">
								</div>
							</div>
							<div class="row mb-3">
								<label for="password" class="col-sm-2 col-form-label">Password</label>
								<div class="col-sm-10">
									<input type="password" class="form-control" name="password">
								</div>
							</div>
							<button type="submit" class="btn btn-primary">Sign in</button>
						</form>

					</div>
				</div>
			</div>

			<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="registration-tab">
				<div class="container">
					<div class="row">
						
						<span class="spacer-25"></span>

						<form method="post" action="actions/register.php" class="mb-3">
							<div class="row mb-3">
								<label for="username" class="col-sm-2 col-form-label">Username</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="username" name="username" minlength="5" required>
								</div>
							</div>
							<div class="row mb-3">
								<label for="email" class="col-sm-2 col-form-label">Email</label>
								<div class="col-sm-10">
									<input type="email" class="form-control" name="email" required>
								</div>
							</div>
							<div class="row mb-3">
								<label for="password" class="col-sm-2 col-form-label">Password</label>
								<div class="col-sm-10">
									<input type="password" class="form-control" name="password"
						                data-bv-identical="true"
						                data-bv-identical-field="confirmPassword"
						                data-bv-identical-message="The password and its confirm are not the same"
						                minlength="5" 
						                required />
								</div>
							</div>
							<div class="row mb-3">
								<label for="confirmPassword" class="col-sm-2 col-form-label">Confirm password</label>
								<div class="col-sm-10">
						            <input type="password" class="form-control" name="confirmPassword"
						                data-bv-identical="true"
						                data-bv-identical-field="password"
						                data-bv-identical-message="The password and its confirm are not the same" 
						                required />
								</div>
							</div>
							<div class="row mb-3">
								<label for="birth" class="col-sm-2 col-form-label">Birth date</label>
								<div class="col-sm-10">
									<input type="date"  class="form-control" name="birth" required>
								</div>
							</div>
							<div class="row mb-3">
								<label for="pageUrl" class="col-sm-2 col-form-label">Publication page URL:</label>
								<div class="col-sm-10">
									<input type="url"  class="form-control" name="pageUrl">
								</div>
							</div>
							<button type="submit" class="btn btn-primary">Register account</button>
						</form>

					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>