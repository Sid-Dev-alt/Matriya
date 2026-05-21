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
	$query = $dbConnection->prepare("SELECT * FROM SalesOrders WHERE DeleteStatus=?");
	$query->execute(array($delete_status));
	$num_rows = $query->rowCount();
	$a = "1";
	if($num_rows>0)
	{	
		while($row = $query->fetch())
		{
			$OrderId = $row['OrderId'];

			$query11 = $dbConnection->prepare("SELECT DisplayName FROM CustomerMaster WHERE CustomerId=?");
			$query11->execute(array($row['CustomerId_CustomerMaster']));
			$row11 = $query11->fetch();
			$CustomerName = $row11['DisplayName'];

			$query1 = $dbConnection->prepare("SELECT * FROM SalesOrderDetails WHERE OrderId_SalesOrders=? AND DeleteStatus=?");
			$query1->execute(array($OrderId,$delete_status));
			while($row1 = $query1->fetch())
			{

				$query2 = $dbConnection->prepare("SELECT ProductName,SKU FROM ProductMaster 
					WHERE ProductId=?");
				$query2->execute(array($row1['ProductId_ProductMaster']));
				$row2 = $query2->fetch();
				$ProductName = $row2['ProductName'];
				$SKU = $row2['SKU'];

				$query3 = $dbConnection->prepare("SELECT SUM(Quantity) AS Total FROM InventoryMaster 
					WHERE ProductId_ProductMaster=?");
				$query3->execute(array($row1['ProductId_ProductMaster']));
				$row3 = $query3->fetch();
				$AvlQty = $row3['Total'];


				$data2[] = array(
				'EntryPkId' => $row1['PkId'],
				'ProductId'=>$row1['ProductId_ProductMaster'],				
				'product'=>$ProductName,
				'quantity' => $row1['Quantity'],
				'price' => $row1['Price'],
				'OrderId_SalesOrders' => $row1['OrderId_SalesOrders'],
				'FileName'=>$row1['FileName'],
				'Amount'=>$row1['Amount'],
				'AvlQty'=>$AvlQty,
				'SKU'=>$SKU,
				);

			}

			$data1[] = array(
				'PkId' => $row['PkId'],
				'CustomerId_CustomerMaster' => $row['CustomerId_CustomerMaster'],
				'CustomerName' => $CustomerName,
				'OrderId' => $row['OrderId'],
				'OrderDate' => $row['OrderDate'],
				'Reference' => $row['Reference'],
				'ShipmentDate'=>$row['ShipmentDate'],
				'PaymentTerms'=>$row['PaymentTerms'],
				'DeliveryMethod'=>$row['DeliveryMethod'],
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
				'OrderStatus'=>$row['OrderStatus'],
				'InvoiceStatus'=>intval($row['InvoiceStatus']),
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