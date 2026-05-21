<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{		
	$delete_status = 1;	
	// $data = json_decode(file_get_contents("php://input"));
	// echo $location = $data->location;
	// if($location=="All")
	// {
	// 	$st = "(Status='0' || Status='1')";
	// }
	// else
	// {
	// 	$st =  "Status='$location'";
	// }

	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	$query = $dbConnection->prepare("SELECT PkId,CategoryName,DeleteStatus FROM Category WHERE DeleteStatus=?");
	$query->execute(array($delete_status));
	$num_rows = $query->rowCount();
	$a = "1";
	if($num_rows>0)
	{	
		while($rows = $query->fetch())
		{
			$PkId = $rows['PkId'];
			$CategoryName = $rows['CategoryName'];
			$data1[] = array("PkId"=>$PkId,"CategoryName"=>$CategoryName);
		}

		echo (json_encode($data1));
	}
	else
	{
		echo "NoData";
	}
}
?>