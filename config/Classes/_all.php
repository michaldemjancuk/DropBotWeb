<?php

/**
	>>> DEPENDENCIES <<<

	include("config/dbConn.php");
	include("config/users.php");
 */
class EF_Procesor
{
	
	function __construct()
	{
		# code...
	}

	public function ProcessUsers($data, $ef_reference)
	{
		$Id = 0;
		$FirstName = -1;
		$LastName = -1;
		$IsAdmin = -1;
		$Shift = -1;
		$DepartmentId = -1;		

		for ($col=0; $col < count($data[0]); $col++) { 
			$header = $data[0][$col];

			if($header == "Id")
			{
				$Id = $col;
			}
			else if($header == "FirstName")
			{
				$FirstName = $col;
			}
			else if($header == "LastName")
			{
				$LastName = $col;
			}
			else if($header == "IsAdmin")
			{
				$IsAdmin = $col;
			}
			else if($header == "Shift")
			{
				$Shift = $col;
			}
			else if($header == "DepartmentId")
			{
				$DepartmentId = $col;
			}
			else
				echo "<p><b>Trying to pass invalid parameter! ('" . $header . "')</b></p>";
		}

		$dataInQuery = array();

		for ($i=1; $i < count($data); $i++) { 
			$dataId = $data[$i][$Id];
			$dataFName = $data[$i][$FirstName];
			$dataLName = $data[$i][$LastName];
			$dataIsAdmin = ($data[$i][$IsAdmin] != -1) ? $data[$i][$IsAdmin] : "0";
			$dataHash = password_hash($dataId, PASSWORD_DEFAULT);
			$dataDepartmentId = $data[$i][$DepartmentId];

			$record = "($dataId, '$dataFName', '$dataLName', b'$dataIsAdmin', '$dataHash', $dataDepartmentId)";

			array_push($dataInQuery, $record);
		}
		$dataInQuery = implode(', ', $dataInQuery);
		$ef_reference->AddMultipleUsers($dataInQuery);
	}

}
?>