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
	
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	$query = $dbConnection->prepare("SELECT PkId,BrandName,DeleteStatus FROM BrandMaster WHERE DeleteStatus=?  ORDER BY PkId DESC");
	$query->execute(array($delete_status));
	$num_rows = $query->rowCount();
	$a = "1";
	if($num_rows>0)
	{	
		while($rows = $query->fetch())
		{
			$PkId = $rows['PkId'];
			$BrandName = $rows['BrandName'];
			
			$data[] = array("PkId"=>$PkId,"BrandName"=>$BrandName);
			$a++;
		}

		echo (json_encode($data));
	}
	else
	{
		echo "NoData";
	}
}
?>