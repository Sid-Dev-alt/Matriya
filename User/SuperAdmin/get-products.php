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
	$data1 = array();
	$query = $dbConnection->prepare("SELECT * FROM ProductMaster WHERE DeleteStatus=? AND InventoryType=? ORDER BY PkId DESC");
	$query->execute(array($delete_status,'Finished'));
	$num_rows = $query->rowCount();
	if($num_rows>0)
	{	
		$data1 = $query->fetchall();
		echo json_encode($data1);	
	}
	else
	{
		echo "NoData";
	}
}
?>