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
	$data2 = array();
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";

	$TotalOutstanding = 0;
	$todaty = date("Y-m-d");
	$query12 = $dbConnection->prepare("SELECT CustomerId_CustomerMaster,InvoiceId,InvoiceDate,InvoiceTotal,DueDate FROM Invoices WHERE DeleteStatus=? AND InvoiceStatus=? AND DueDate IS NOT NULL AND DueDate<?");
	$query12->execute(array($delete_status,'2',$todaty));
	if($query12->rowCount()>0)
	{
	while($rows12 = $query12->fetch())
	{
		$InvoiceId = $rows12['InvoiceId'];
		$CustomerId_CustomerMaster = $rows12['CustomerId_CustomerMaster'];
		$InvoiceTotal = $rows12['InvoiceTotal'];
		$InvoiceDate = $rows12['InvoiceDate'];
		$DueDate = $rows12['DueDate'];

		$query13 = $dbConnection->prepare("SELECT SUM(ReceivedAmount) AS received FROM Payments WHERE DeleteStatus=? AND InvoiceId_Invoices=?");
		$query13->execute(array($delete_status,$InvoiceId));
		$rows13 = $query13->fetch();
		$received = $rows13['received'];

		$TotalOutstanding = $InvoiceTotal-$received;

		$query3 = $dbConnection->prepare("SELECT DisplayName FROM CustomerMaster WHERE CustomerId=? AND DeleteStatus=?");
		$query3->execute(array($CustomerId_CustomerMaster,$delete_status));
		$row3 = $query3->fetch();
		$DisplayName = $row3['DisplayName'];


			$data1[] = array(
				'CName' => $DisplayName,
				'InvoiceId' => $InvoiceId,
				'InvoiceTotal' => $InvoiceTotal,
				'received' => $received,
				'TotalOutstanding' => $TotalOutstanding,
				'DueDate' => $DueDate,
				'InvoiceDate' => $InvoiceDate,
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