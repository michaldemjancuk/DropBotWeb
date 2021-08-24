<?php

/**
	>>> DEPENDENCIES <<<
//20 - Free drop
//23 - Paid drop less than 3 Three times (Velký třikrát)
//24 - Paid drop less than 3 ONCE (Velký jednou)
//25 - Paid drop less than 3 (Velký) 
//26 - Paid drop more than 3 (Malý)
//29 - drop admin with 25 and 24


	
	include("config/dbConn.php");
	include("config/settings.php");
	include("config/Classes/users.php");
 */
class Authenticator
{
	
	function __construct()
	{
	}

	public function Required_Admin($queryString = '')
	{
		$this->Required_User();
		if (!$this->IsAdmin()) 
		{
			header('Location: /403.php' . $queryString);
			exit();
		}
	}

	public function Required_User()
	{
		if (!$this->IsLoggedIn()) 
		{
			header('Location: /403.php?target=login.php');
			exit();
		}
	}

	public function IsLoggedIn()
	{
		try{
			if(!$this->CookiesAreSet())
				return false;
			$authenticatorSettings = AuthenticatorSettings();
			$users = new Users();
			$userId = $_COOKIE[$authenticatorSettings['idCookieName']];
			$hash = $_COOKIE[$authenticatorSettings['hashCookieName']];
			return $users->VerifiedLogin($userId, $hash);
		}catch (Exception $e){
			echo $e->getMessage();
			return false;
		}
	}

	public function IsRegisteredUser($username = null)
	{
		return $this->ComaprePermissionGroupByUsernameOrCookie(10, $username);
	}

	public function IsModel($username = null)
	{
		return 
			$this->ComaprePermissionGroupByUsernameOrCookie(20, $username) || 
			$this->ComaprePermissionGroupByUsernameOrCookie(21, $username) || 
			$this->ComaprePermissionGroupByUsernameOrCookie(99, $username);
	}

	public function IsAdmin($username = null)
	{
		return 
			$this->ComaprePermissionGroupByUsernameOrCookie(90, $username) || 
			$this->ComaprePermissionGroupByUsernameOrCookie(99, $username);
	}

	public function IsSuperAdmin($username = null)
	{
		return $this->ComaprePermissionGroupByUsernameOrCookie(99, $username);
	}

	public function ComaprePermissionGroupByUsernameOrCookie($value, $username)
	{
		if(!isset($username))
		{
			if(!$this->CookiesAreSet())
				return false;
			$authenticatorSettings = AuthenticatorSettings();
			$users = new Users();
			$userId = $_COOKIE[$authenticatorSettings['idCookieName']];
			return $users->GetRightsPermissionGroup($userId) == $value;
		}
		else
		{
			return $users->GetRightsPermissionGroup($username) == $value;
		}
	}

	public function GetUserId()
	{
		if(!$this->CookiesAreSet())
			return '';
		$authenticatorSettings = AuthenticatorSettings();
		return $_COOKIE[$authenticatorSettings['idCookieName']];
	}

	public function CookiesAreSet()
	{
		$authenticatorSettings = AuthenticatorSettings();
		return 
			isset($_COOKIE[$authenticatorSettings['hashCookieName']]) &&
			isset($_COOKIE[$authenticatorSettings['idCookieName']]);
	}

	public function LogIn($username, $hash)
	{
		$authenticatorSettings = AuthenticatorSettings();
		$users = new Users();
		if($users->VerifiedLogin($username, $hash))
		{
			$cookie_value = $users->GetPasswordHash($username);
			setcookie($authenticatorSettings['idCookieName'], $username, time() + $authenticatorSettings['sessionLength'], "/");
			setcookie($authenticatorSettings['hashCookieName'], $cookie_value, time() + $authenticatorSettings['sessionLength'], "/");
			header('Location: /login.php');
			exit();
		}
	}

	public function LogOut()
	{
		$authenticatorSettings = AuthenticatorSettings();
		setcookie(
			$authenticatorSettings['idCookieName'],
			$_COOKIE[$authenticatorSettings['idCookieName']], 
			time() - 3600,
			"/"
		);
		setcookie(
			$authenticatorSettings['hashCookieName'], 
			$_COOKIE[$authenticatorSettings['hashCookieName']], 
			time() - 3600,
			"/"
		);
		header('Location: /login.php');
		exit();
	}
}

?>