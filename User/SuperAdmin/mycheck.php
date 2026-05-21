<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{		
	$data = json_decode(file_get_contents("php://input"));
	$barcodeInput = $data->barcodeInput;
	$delete_status = 1;	
	$data2 = array();
	$data3 = array();
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";

	$query3 = $dbConnection->prepare("SELECT PkId_InventoryMaster FROM InvoiceDetails WHERE InvoiceId_Invoices='INV248'");
	$query3->execute(array());
	if($query3->rowCount()>0)
	{
		while($row2 = $query3->fetch())
		{

			echo $PkId_InventoryMaster = $row2['PkId_InventoryMaster']."<br>";
		}

	}
	else
	{
		echo "NoData";
	}
}
?>