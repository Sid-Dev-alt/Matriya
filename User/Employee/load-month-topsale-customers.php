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
	
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";

	$data = json_decode(file_get_contents("php://input"));
	$CustomerTitle = $data->CustomerTitle;

	$data1 =array();
	if($CustomerTitle=="This Month")
	{
		$From = date("Y-m-01");
		$To = date("Y-m-t");
	}
	if($CustomerTitle=="Last Month")
	{
		$From = date('Y-m-d', strtotime('first day of last month')); // Output: 2011-02-01
		$To = date('Y-m-d', strtotime('last day of last month')); // Output: 2011-02-01
	}


	$query6 = $dbConnection->prepare("SELECT CustomerId_CustomerMaster, SUM(InvoiceTotal) AS TotalQuantity
FROM Invoices WHERE  (InvoiceDate Between ? AND ?) GROUP BY CustomerId_CustomerMaster ORDER BY SUM(InvoiceTotal) DESC LIMIT 0,3");
	$query6->execute(array($From,$To));
	while($rows6 = $query6->fetch())
	{
		$CustomerId = $rows6['CustomerId_CustomerMaster'];
		$InvoiceTotal = $rows6['TotalQuantity'];

		$query7 = $dbConnection->prepare("SELECT DisplayName FROM CustomerMaster WHERE CustomerId=?");
		$query7->execute(array($CustomerId));
		$rows7 = $query7->fetch();
		$DisplayName = $rows7['DisplayName'];

		$data1[] = array("CustomerName"=>$DisplayName,"InvoiceTotal"=>$InvoiceTotal);
	}

	
	echo (json_encode($data1));
	
}
?>
