<?php

/**
 * 
 */
class DbConn
{

	function __construct() { }

	public function GetConnection()
	{
		$DbConnString = "mysql:host=a043um.forpsi.com;dbname=f146227";
		$DbUsername = "f146227";
		$DbPassword = "kokosak1399";
		//return new PDO($DbConnString, $DbUsername, $DbPassword);
		try{
			// create a PDO connection with the configuration data
			$conn = new PDO($DbConnString, $DbUsername, $DbPassword);
			 
			// display a message if connected to database successfully
			if($conn){
				return $conn;
	        }
		}catch (PDOException $e){
			// report error message
			echo $e->getMessage();
		}
	}
}

?>