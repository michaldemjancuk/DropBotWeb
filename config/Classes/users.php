<?php

/**
	>>> DEPENDENCIES <<<

	include("config/dbConn.php");
 */
class Users
{
	
	function __construct()
	{
		# code...
	}

	public function Add($username, $email, $birthdate, $PageUrl, $verificationCode, $permissionLevel, $profileImageUrl = '')
	{
		$dbConn = new DbConn();
		$sql = "INSERT INTO `users`(Username, Email, BirthDate, PageUrl, VerificationCode, PermissionLevel, ProfileImageUrl) VALUES (?, ?, ?, ?, ?, ?, ?)";
		$pdo = $dbConn->GetConnection();
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$username, $email, $birthdate, $PageUrl, $verificationCode, $permissionLevel, $profileImageUrl]);
	}

	public function Register($username, $email, $hash, $birthdate, $PageUrl, $verificationCode, $permissionLevel)
	{
		$dbConn = new DbConn();
		$sql = "INSERT INTO `users`(Username, Email, Password, BirthDate, PageUrl, VerificationCode, PermissionLevel) VALUES (?, ?, ?, ?, ?, ?, ?)";
		$pdo = $dbConn->GetConnection();
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$username, $email, $hash, $birthdate, $PageUrl, $verificationCode, $permissionLevel]);
		$this->SendVerificationCode($email, $username, $verificationCode);
	}

	public function SendVerificationCode($email, $username, $verificationCode)
	{
		$to = $email;
		$message = 
'<body>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<div class="card" style="width: 32rem;">
		<div class="card-header">
			Thank you for your registration. Your login details are:
		</div>
		<ul class="card-body bg-light list-group list-group-flush">
			<li class="list-group-item bg-warning text-dark"><b>Username:</b> '. $username . '</li>
			<li class="list-group-item bg-warning text-dark"><b>Password:</b> <i>not sent for security reasons</i></li>
			<li class="list-group-item bg-warning text-dark"><b>Email:</b> '. $email . '</li>
			<li class="list-group-item bg-warning text-dark"><b>Verification code:</b> <input type="text" value="'. $verificationCode . '" readonly></li>
		</ul>
		<div class="card-footer">
			Sincerely yours, <a href="#">xxx</a>.
		</div>
	</div>
</body>';

		$subject = "DropBot - Verification code";

		$header = "From:Neodpovidej@dropbot.com \r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-type: text/html\r\n";

		$retval = mail($to,$subject,$message,$header);


		if( $retval == true ) {
			echo "<script>console.log('Email with verification code successfully sent');</script>";
		}else {
			echo "<script>console.log('Email with verification code cannot be sent, contact admin!');</script>";
		}
	}

	public function GetUserData($username)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT * FROM users WHERE Username = '" . $username . "'")->fetchAll();
		return $data[0];
	}

	public function GetAll()
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT * FROM users order by Username")->fetchAll();
		return $data;
	}

	public function GetOccurancesForUser($Username)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT Occurances FROM users WHERE Username = '" . $Username . "'")->fetchAll();
		return $data[0][0];
	}

	public function GetOccurancesForUserWithPermissionLevel($Username, $PermissionLevel)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT Occurances FROM users WHERE Username = '" . $Username . "' AND PermissionLevel = '" . $PermissionLevel . "'")->fetchAll();
		return $data[0][0];
	}

	public function SetOccuranceForUser($Username, $Occurance, $PermissionLevel = null)
	{
		$dbConn = new DbConn();
		$pdo = $dbConn->GetConnection();
		if (isset($PermissionLevel)) {
			$sql = "UPDATE users SET Occurances = ? Where Username = ? AND PermissionLevel = ?";
			$stmt= $pdo->prepare($sql);
			$stmt->execute([$Occurance, $Username, $PermissionLevel]);
		} else {
			$sql = "UPDATE users SET Occurances = ? Where Username = ?";
			$stmt= $pdo->prepare($sql);
			$stmt->execute([$Occurance, $Username]);
		}
	}

	public function UpdateEdgeStatus($Username, $EdgeStatus, $PermissionLevel = null)
	{
		$dbConn = new DbConn();
		$pdo = $dbConn->GetConnection();
		if (isset($PermissionLevel)) {
			$sql = "UPDATE users SET OnTheEdge = ? Where Username = ? AND PermissionLevel = ?";
			$stmt= $pdo->prepare($sql);
			$stmt->execute([$EdgeStatus, $Username, $PermissionLevel]);
		}
		else{
			$sql = "UPDATE users SET OnTheEdge = ? Where Username = ?";
			$stmt= $pdo->prepare($sql);
			$stmt->execute([$EdgeStatus, $Username]);
		}
	}

	public function UpdateNotGroupStatus($Username, $NotGroup, $PermissionLevel = null)
	{
		$dbConn = new DbConn();
		$pdo = $dbConn->GetConnection();
		if(isset($PermissionLevel)){
			$sql = "UPDATE users SET NotGroup = ? Where Username = ? AND $PermissionLevel = ?";
			$stmt= $pdo->prepare($sql);
			$stmt->execute([$NotGroup, $Username, $PermissionLevel]);
		}else{
			$sql = "UPDATE users SET NotGroup = ? Where Username = ?";
			$stmt= $pdo->prepare($sql);
			$stmt->execute([$NotGroup, $Username]);
		}
	}

	public function GetAllOccurancesSelect($KeySelected = '')
	{
		$data = $this->GetOccurancesList();
		for($i = 0; $i < sizeof($data); $i++){
			if($data[$i]['Key'] != $KeySelected)
				echo "<option value='" . $data[$i]['Key'] . "'>" . $data[$i]['Description'] . " - [Key:" . $data[$i]['Key'] . "]</option>";
			else
				echo "<option value='" . $data[$i]['Key'] . "' selected>" . $data[$i]['Description'] . " - [Key:" . $data[$i]['Key'] . "]</option>";

		}
	}

	function GetOccurancesList()
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT * FROM `dbot_occurances_list`")->fetchAll();
		return $data;
	}

	public function GetDropUsersUploadList()
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT Username, Id FROM users ORDER BY Username ASC")->fetchAll();
		return $data;
	}

	public function GenerateUsersSelect()
	{
		$data = $this->GetDropUsersUploadList();
		for($i = 0; $i < sizeof($data); $i++){
			echo "<option value='" . $data[$i]['Username'] . "'>@" . $data[$i]['Username'] . " - [Id:" . $data[$i]['Id'] . "]</option>";
		}
	}

	public function Delete($Id)
	{
		$dbConn = new DbConn();
		$pdo = $dbConn->GetConnection();
		$sql = "DELETE FROM users Where Id = ?";
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$Id]);
	}

	public function Enable($Username)
	{
		$dbConn = new DbConn();
		$pdo = $dbConn->GetConnection();
		$sql = "UPDATE users SET Active = 1 Where Username = ?";
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$Username]);
	}

	public function EnableWithPerm($Username, $PermissionLevel)
	{
		$dbConn = new DbConn();
		$pdo = $dbConn->GetConnection();
		$sql = "UPDATE users SET Active = 1 Where Username = ? AND PermissionLevel = ?";
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$Username, $PermissionLevel]);
	}

	public function Disable($Username)
	{
		$dbConn = new DbConn();
		$pdo = $dbConn->GetConnection();
		$sql = "UPDATE users SET Active = 0 Where Username = ?";
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$Username]);
	}

	public function DisableWithPerm($Username, $PermissionLevel)
	{
		$dbConn = new DbConn();
		$pdo = $dbConn->GetConnection();
		$sql = "UPDATE users SET Active = 0 Where Username = ? AND PermissionLevel = ?";
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$Username, $PermissionLevel]);
	}

	public function VerifiedLogin($username, $Hash)
	{
		$CalcHash = password_hash($username, PASSWORD_DEFAULT);
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT * FROM users WHERE username = '" . $username . "'")->fetchAll();
		$DbHash = $data[0]['Password'];
		return 	password_verify($Hash, $DbHash) != '1' || 
				password_verify($Hash, $CalcHash) != '1';
	}

	public function GetPasswordHash($username)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT * FROM users WHERE username = '" . $username . "'")->fetchAll();
		return $data[0]['Password'];
	}

	public function GetRightsPermissionGroup($username)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT PermissionLevel FROM users WHERE username = '" . $username . "'")->fetchAll();
		return $data[0][0];
	}

	public function GetPermissionLevelsFromTable()
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT * FROM users_accesses")->fetchAll();
		return $data;
	}

	public function SetRightsPermissionGroup($Id, $PermissionLevel)
	{
		$dbConn = new DbConn();
		$pdo = $dbConn->GetConnection();
		$sql = "UPDATE users SET PermissionLevel = ? Where Id = ?";
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$PermissionLevel, $Id]);
	}

	public function GetProfileImageUrlByUsername($username)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT ProfileImageUrl FROM users WHERE username = '" . $username . "'")->fetchAll();
		return $data[0][0];
	}

	public function CheckAccountExists($username)
	{
		$dbConn = new DbConn(); 
		$pdo = $dbConn->GetConnection();
		$data = $pdo->query("SELECT Id FROM users WHERE username = '" . $username . "'")->fetchAll();
		return count($data) > 0;
	}
}



?>