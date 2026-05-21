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
	$OrderTitle = $data->OrderTitle;

	$data1 =array();
	if($OrderTitle=="This Month")
	{
		$From = date("Y-m-01");
		$To = date("Y-m-t");
	}
	if($OrderTitle=="Last Month")
	{
		$From = date('Y-m-d', strtotime('first day of last month')); // Output: 2011-02-01
		$To = date('Y-m-d', strtotime('last day of last month')); // Output: 2011-02-01
	}


	$query2 = $dbConnection->prepare("SELECT COUNT(*) AS draftcount FROM SalesOrders WHERE DeleteStatus=? AND OrderStatus=? AND (OrderDate Between ? AND ?)");
	$query2->execute(array($delete_status,$delete_status,$From,$To));
	$rows2 = $query2->fetch();
	$draftcount = $rows2['draftcount'];

	$query3 = $dbConnection->prepare("SELECT COUNT(*) AS confirmcount FROM SalesOrders WHERE DeleteStatus=? AND OrderStatus=? AND (OrderDate Between ? AND ?)");
	$query3->execute(array($delete_status,'3',$From,$To));
	$rows3 = $query3->fetch();
	$confirmcount = $rows3['confirmcount'];

	$query4 = $dbConnection->prepare("SELECT COUNT(*) AS packedcount FROM SalesOrders WHERE DeleteStatus=? AND PackageStatus=? AND (OrderDate Between ? AND ?)");
	$query4->execute(array($delete_status,'2',$From,$To));
	$rows4 = $query4->fetch();
	$packedcount = $rows4['packedcount'];

	$query5 = $dbConnection->prepare("SELECT COUNT(*) AS shipcount FROM SalesOrders WHERE DeleteStatus=? AND ShipmentStatus=? AND (OrderDate Between ? AND ?)");
	$query5->execute(array($delete_status,'3',$From,$To));
	$rows5 = $query5->fetch();
	$shipcount = $rows5['shipcount'];

	$query6 = $dbConnection->prepare("SELECT COUNT(*) AS invoicecount FROM SalesOrders WHERE DeleteStatus=? AND InvoiceStatus=? AND (OrderDate Between ? AND ?)");
	$query6->execute(array($delete_status,$delete_status,$From,$To));
	$rows6 = $query6->fetch();
	$invoicecount = $rows6['invoicecount'];

	$query7 = $dbConnection->prepare("SELECT SUM(InvoiceTotal) AS invoicetotal FROM Invoices WHERE DeleteStatus=? AND InvoiceStatus=? AND (InvoiceDate Between ? AND ?)");
	$query7->execute(array($delete_status,'2',$From,$To));
	$rows7 = $query7->fetch();
	$invoicetotal = $rows7['invoicetotal'];


	$data1 = array("draftcount"=>$draftcount,"confirmcount"=>$confirmcount,"packedcount"=>$packedcount,"shipcount"=>$shipcount,"invoicecount"=>$invoicecount,"invoicetotal"=>$invoicetotal);
	echo (json_encode($data1));
	
}
?>