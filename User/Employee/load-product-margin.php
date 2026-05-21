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
	$data2 = array();
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";

	$query8 = $dbConnection->prepare("SELECT ProductId,ProductName,ReorderPoint,SalesPrice,PurchasePrice FROM ProductMaster WHERE DeleteStatus=? AND Status=? ORDER BY SalesPrice DESC");
	$query8->execute(array($delete_status,$delete_status));
	if($query8->rowCount()>0)
	{
	while($rows8 = $query8->fetch())
	{
		$ProductId = $rows8['ProductId'];
		$ProductName = $rows8['ProductName'];
		$SalesPrice = $rows8['SalesPrice'];
		$PurchasePrice = $rows8['PurchasePrice'];
		$Margin = $SalesPrice-$PurchasePrice;


		$query11 = $dbConnection->prepare("SELECT SUM(Quantity) AS AvlQty FROM InventoryMaster WHERE DeleteStatus=? AND ProductId_ProductMaster=?");
		$query11->execute(array($delete_status,$ProductId));
		$rows11 = $query11->fetch();
		$AvlQty = $rows11['AvlQty'];


			$data1[] = array(
				'ProductName' => $ProductName,
				'SalesPrice' => $SalesPrice,
				'PurchasePrice' => $PurchasePrice,
				'Margin' => $Margin,
				'AvlQty' => $AvlQty,
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