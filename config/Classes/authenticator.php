<?php

/**
	>>> DEPENDENCIES <<<

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

	public function IsAdmin($Id = null)
	{
		if(!isset($Id))
		{
			if(!$this->CookiesAreSet())
				return false;
			$authenticatorSettings = AuthenticatorSettings();
			$users = new Users();
			$userId = $_COOKIE[$authenticatorSettings['idCookieName']];
			return $users->CheckAccountHasAdminRights($userId);
		}
		else
		{
			return $users->CheckAccountHasAdminRights($Id);
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

	public function LogIn($userId, $hash)
	{
		$authenticatorSettings = AuthenticatorSettings();
		$users = new Users();
		if($users->VerifiedLogin($userId, $hash))
		{
			$cookie_value = $users->GetPasswordHash($userId);
			setcookie($authenticatorSettings['idCookieName'], $userId, time() + $authenticatorSettings['sessionLength'], "/");
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