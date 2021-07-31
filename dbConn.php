<?php

/**
 * 
 */
class DbConn
{

	function __construct() { }

	public function GetConnection()
	{
		$DbConnString = "mysql:host=185.129.138.43;dbname=f145750";
		$DbUsername = "f145750";
		$DbPassword = "QAMtUK5L";
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