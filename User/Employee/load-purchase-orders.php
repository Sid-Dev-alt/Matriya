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
	$query = $dbConnection->prepare("SELECT * FROM PurchaseOrders WHERE DeleteStatus=?");
	$query->execute(array($delete_status));
	$num_rows = $query->rowCount();
	$a = "1";
	if($num_rows>0)
	{	
		while($row = $query->fetch())
		{
			$POrderId = $row['POrderId'];

			$query11 = $dbConnection->prepare("SELECT DisplayName FROM VendorMaster WHERE VendorId=?");
			$query11->execute(array($row['VendorId_VendorMaster']));
			$row11 = $query11->fetch();
			$VendorName = $row11['DisplayName'];

			$query1 = $dbConnection->prepare("SELECT * FROM PurchaseOrderDetails WHERE POrderId_PurchaseOrders=? AND DeleteStatus=?");
			$query1->execute(array($POrderId,$delete_status));
			while($row1 = $query1->fetch())
			{

				$query2 = $dbConnection->prepare("SELECT ProductName,SKU,InventoryType FROM ProductMaster 
					WHERE ProductId=?");
				$query2->execute(array($row1['ProductId_ProductMaster']));
				$row2 = $query2->fetch();
				$ProductName = $row2['ProductName'];
				$SKU = $row2['SKU'];
				$InventoryType = $row2['InventoryType'];

				$query3 = $dbConnection->prepare("SELECT SUM(Quantity) AS Total FROM InventoryMaster 
					WHERE ProductId_ProductMaster=?");
				$query3->execute(array($row1['ProductId_ProductMaster']));
				$row3 = $query3->fetch();
				$AvlQty = $row3['Total'];


				$data2[] = array(
				'EntryPkId' => $row1['PkId'],
				'OldProductId'=>$row1['ProductId_ProductMaster'],				
				'product'=>$ProductName,
				'Oldquantity' => $row1['Quantity'],
				'quantity' => $row1['Quantity'],
				'price' => $row1['Price'],
				'POrderId_PurchaseOrders' => $row1['POrderId_PurchaseOrders'],
				'FileName'=>$row1['FileName'],
				'Amount'=>$row1['Amount'],
				'DeleteStatus'=>$row1['DeleteStatus'],
				'AvlQty'=>$AvlQty,
				'SKU'=>$SKU,
				'InventoryType'=>$InventoryType,
				);

			}

			$data1[] = array(
				'PkId' => $row['PkId'],
				'VendorId_VendorMaster' => $row['VendorId_VendorMaster'],
				'VendorName' => $VendorName,
				'POrderId' => $row['POrderId'],
				'POrderDate' => $row['POrderDate'],
				'Reference' => $row['Reference'],
				'DeliveryDate'=>$row['DeliveryDate'],
				'PaymentTerms'=>$row['PaymentTerms'],
				'ShipmentReference'=>$row['ShipmentReference'],
				'Salesperson'=>$row['Salesperson'],
				'CustomerNotes'=>$row['CustomerNotes'],
				'TermsCondition'=>$row['TermsCondition'],
				'AdditionalCharges'=>$row['AdditionalCharges'],
				'FileName'=>$row['FileName'],
				'SubTotal'=>$row['SubTotal'],
				'DiscType'=>$row['DiscType'],
				'DiscountVal'=>$row['DiscountVal'],
				'DiscountAmount'=>$row['DiscountAmount'],
				'OrderTotal'=>$row['OrderTotal'],
				'POStatus'=>$row['POStatus'],
				'data2'=>$data2,
			);
			unset($data2);
		}

		echo (json_encode($data1));
	}
	else
	{
		echo "NoData";
	}
}
?>