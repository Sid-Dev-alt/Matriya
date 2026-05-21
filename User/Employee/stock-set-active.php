<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{	
	if($_SERVER["REQUEST_METHOD"]==="POST")
	{
		$data = json_decode(file_get_contents("php://input"));	
		$FormPkId  = $data->PkId;	
		if(isset($FormPkId))
		{
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
			$SAId = $_SESSION['EmpId'];
			$DATEIME = GetDateTime();	
			$delete_status=1;	

			$query = $dbConnection->prepare("UPDATE ProductTypes SET VisibleStatus=? WHERE PkId=? AND DeleteStatus=?");
			$query->execute(array($delete_status,$FormPkId,$delete_status));

			$result= "Success";
			echo $result;
		}
		else
		{
			$result= "Invalid Data";
			echo $result;
		}
	}
	else
	{
		echo "<script language=\"javascript\">window.location=\"stock-list.php\";</script>";		
	}
}
?>