<?php

include("config/dbConn.php");
include("config/settings.php");
include("config/Classes/users.php");
include("config/Classes/authenticator.php");
$auth = new Authenticator();
$users = new Users();
$idToLoad = (isset($_GET['id']) && $auth->IsAdmin()) ? 
	$_GET['id'] : 
	$auth->GetUserId();

$auth->Required_User();
$row = $users->GetUserData($idToLoad);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
	<title>Port&aacute;l &uacute;dr&#382;by</title>
	<meta charset="utf-8" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script>
		function confirmReset(id, url){
			if(confirm("Opravdu si přejete uživateli " + id + " vymazat Password?")){
				window.location.href = url;
			}
		}
		function confirmAdmin(id, url){
			if(confirm("Opravdu si přejete uživateli " + id + " nastavit administrátorská práva? Odebrat je může pouze správce webu!")){
				window.location.href = url;
			}
		}
	</script>
	<style type="text/css">
		.width-40{
			width: 40%;
		}
		.width-30{
			width: 30%;
		}
	</style>
</head>

<body class="bg-light">
	<table class="table">
		<thead class="thead-dark">
			<tr>
				<th class="width-30">Column name</th>
				<th class="width-30">Actual value</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th scope="row">Id</th>
				<td><?php echo $row['Id'] ?></td>
				<td></td>
			</tr>
			<tr>
				<th scope="row">First name</th>
				<td><?php echo $row['FirstName'] ?></td>
			</tr>
			<tr>
				<th scope="row">Last name</th>
				<td><?php echo $row['LastName'] ?></td>
				<td>
				</td>
			</tr>
			<tr>
				<th scope="row">Password</th>
				<td><b>******</b></td>
			</tr>
			<!--tr>
				<th scope="row">Sm&#283;na</th>
				<td><?php echo $row['Shift'] ?></td>
				<td>
<?php
if($auth->IsAdmin())
{
	echo '
					<form action="actions/editUserShift.php?id=' . $row['Id'] . '" class="form-inline justify-content-between" method="post" name="shift">
						<input type="text" name="shift" maxlength="1" style="text-transform: uppercase" class="form-control mb-4 col-md-6" placeholder="Zadejte jedno písmeno (směna)" >
						<button class="btn btn-success col-md-2">Save</button>
					</form>';
}
else
{
	echo "[<span data-feather=\"award\"></span>] Contact administrator";
}
?>
				</td>
			</tr-->
		</tbody>
	</table>
<?php
if($auth->IsAdmin())
{
echo '
	<a href="/users.php" class="btn btn-warning">&laquo; Seznam u&#382;ivatel&#367;</a>';
}
?>
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace()
    </script>
</body>
</html>

<?php
if(isset($_GET['e']))
{
	echo "<script>alert('" . $_GET['e'] . "');</script>";
}
?>