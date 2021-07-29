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

	if($users->CheckAccountExists($_POST['id']))
	{
		if (!$users->CheckPasswordExists($_POST['id']))
		{
			echo '<script>alert("Účet s tímto ID (' . $_POST['id'] . ') ještě nemá nastaveno heslo, přihlaste se prosím skrze formulář: "První přihlášení" !");</script>';
		}
		else
		{
			$usersPass = $users->GetPasswordHash($_POST['id']);
			$notEnc = $_POST['password'];
			$passwordHash = password_hash($notEnc, PASSWORD_DEFAULT);
			if(password_verify($notEnc, $usersPass)){
				$auth = new Authenticator();
				$auth->LogIn($_POST['id'], $passwordHash);
			} else {
				echo "<script>alert('Nesprávné heslo!');</script>";
			}
		}
	}
	else
	{
		echo '<script>alert("Účet s tímto ID (' . $_POST['id'] . ') neexistuje !");</script>';
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
<html lang="cs">
<head>
	<title>Port&aacute;l &uacute;dr&#382;by</title>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap/bootstrap-grid.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap/bootstrap-reboot.css">
	<link rel="stylesheet" type="text/css" href="/css/custom.css">
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/jquery-3.3.1.js"></script>
    <script src="js/modernizr-2.8.3.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>
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

<body>
    <div class="container">
        <div class="row">
			<div class="col-md-5 mx-auto jumbotron">
				<div class="form">
					<div class="logo mb-3">
						<div class="col-md-12 text-center">
							<h1>P&#345;ihl&aacute;&#353;en&iacute;</h1>
						</div>
					</div>
					<hr />
					<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="login">
						<div class="form-group">
						  <label>&#268;&iacute;slo zam&#283;stnance</label>
						  <input type="number" name="id"  class="form-control" id="login_id" placeholder="Osobní číslo zaměstnance" min="10000" max="99999" value="10000" required>
						</div>
						<div class="form-group">
							<label>Heslo</label>
							<input type="password" name="password" id="login_password"  class="form-control" placeholder="Heslo" required>
						</div>
						<input type="text" name="type" value="login" hidden>
						<button type="submit" class="form-group btn btn-primary">P&#345;ihl&aacute;sit</button>
                    </form>
				</div>
			</div>
			<div class="col-md-1">
				
			</div>
			<div class="col-md-5 mx-auto jumbotron">
				<div class="form">
					<div class="logo mb-3">
						<div class="col-md-12 text-center">
							<h1>Prvn&iacute; p&#345;ihl&aacute;&#353;en&iacute; (Registrace)</h1>
							<!-- <hr />
							 <h1>(&Uacute;&#269;et bez hesla)</h1> -->
						</div>
					</div>
					<hr />
					<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="login">
						<div class="form-group">
						  <label>&#268;&iacute;slo zam&#283;stnance</label>
						  <input type="number" name="id"  class="form-control" id="register_id" placeholder="Osobní číslo zaměstnance" min="10000" max="99999" value="10000" required>
						</div>
						<div class="form-group">
						  <label>Jm&eacute;no zam&#283;stnance</label>
						  <input type="text" name="fName"  class="form-control" id="register_fname" placeholder="Jm&eacute;no" maxlength="50" required>
						</div>
						<div class="form-group">
						  <label>P&#345;&iacute;jmen&iacute; zam&#283;stnance</label>
						  <input type="text" name="lName"  class="form-control" id="register_lname" placeholder="P&#345;&iacute;jmen&iacute;" maxlength="50" required>
						</div>
						<div class="form-group">
							<label>2x Heslo (Pro ov&#283;&#345;en&iacute; spr&aacute;vnosti zad&aacute;n&iacute;)</label>
							<input type="password" name="password" id="register_password"  class="form-control" placeholder="Heslo">
							<input type="password" name="password2" id="register_password2"  class="form-control" placeholder="Heslo">
						</div>
						<input type="text" name="type" value="reset" hidden>
                    	<button type="submit" onclick="return validate()" class="form-group btn btn-info">Nastavit heslo</button>
                    </form>
				</div>
			</div>
		</div>
	</div>  
</body>
</html>