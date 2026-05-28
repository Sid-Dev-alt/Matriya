<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{		
	$delete_status = 1;	
	$data1 = array();
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	
	$query = $dbConnection->prepare("SELECT * FROM orders WHERE DeleteStatus=? ORDER BY CreatedTime DESC");
	$query->execute(array($delete_status));
	$num_rows = $query->rowCount();
	
	if($num_rows>0)
	{	
		while($row = $query->fetch())
		{
			$data1[] = array(
				'PkId' => $row['PkId'],
				'OrderId' => $row['OrderId'],
				'CustomerName' => $row['CustomerName'],
				'OrderDate' => $row['OrderDate'],
				'TotalAmount' => $row['TotalAmount'],
				'CreatedTime' => $row['CreatedTime']
			);
		}
		echo (json_encode($data1));
	}
	else
	{
		echo "NoData";
	}
}
?>
