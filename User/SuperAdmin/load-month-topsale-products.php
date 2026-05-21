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

	$data = json_decode(file_get_contents("php://input"));
	$SellingTitle = $data->SellingTitle;

	$data1 =array();
	if($SellingTitle=="This Month")
	{
		$From = date("Y-m-01 00:00:00");
		$To = date("Y-m-t 00:00:00");
	}
	if($SellingTitle=="Last Month")
	{
		$From = date('Y-m-d 00:00:00', strtotime('first day of last month')); // Output: 2011-02-01
		$To = date('Y-m-d 00:00:00', strtotime('last day of last month')); // Output: 2011-02-01
	}


	$query6 = $dbConnection->prepare("SELECT ProductId_ProductMaster, SUM(Quantity) AS TotalQuantity
FROM InvoiceDetails WHERE  (CreatedTime Between ? AND ?) GROUP BY ProductId_ProductMaster ORDER BY SUM(Quantity) DESC LIMIT 0,5");
	$query6->execute(array($From,$To));
	while($rows6 = $query6->fetch())
	{
		$ProductId_ProductMaster = $rows6['ProductId_ProductMaster'];

		$query7 = $dbConnection->prepare("SELECT FileName,ProductName FROM ProductMaster WHERE ProductId=?");
		$query7->execute(array($ProductId_ProductMaster));
		$rows7 = $query7->fetch();
		$ProductName = $rows7['ProductName'];
		$FileName = $rows7['FileName'];

		$data1[] = array("ProductName"=>$ProductName,"FileName"=>$FileName);
	}

	
	echo (json_encode($data1));
	
}
?>
