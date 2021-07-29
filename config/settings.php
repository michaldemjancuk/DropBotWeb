<?php

function IndexSettings()
{
	return array(
		// >> DO NOT MODIFY <<
	    "Version" => "1.0.1_a_08",
		// >> DO NOT MODIFY <<
		// * Report bug url adress *
	    "ReportBugUrl" => "../bugs/",
		// >> DO NOT MODIFY <<
		// * Report bug fail text *
	    "ErrOccuredBugFile" => "<h2 style='text-align: center'>File content was for unknown reason not passed!</h2>"
	);
}

function AllSettings()
{
	return array(
	);
}

function AuthenticatorSettings()
{
	return array(
		// * Name for cookie with user hash stored in *
	    "hashCookieName" => "hash",	
		// * Name for cookie with user ID stored in *
	    "idCookieName" => "userId",
	    // DEFAULT: 3600 * 24 * 7 (7 DAY LOGGED IN)
	    // * Length of session in seconds *
	    "sessionLength" => 3600 * 24 * 7 		
	);
}

function LoginSettings()
{
	return array(
		// >> DO NOT MODIFY <<
		// * Minimal length of password *
	    "minPassword" => 5
	);
}

function ConvertDateToDMY($originalDateString)
{
	$timestamp = strtotime($originalDateString);
	return date("d.m.Y", $timestamp);
}

function ConvertDateTimeToDMY($originalDateString)
{
	$timestamp = strtotime($originalDateString);
	return date("d.m.Y h:i:s", $timestamp);
}

?>