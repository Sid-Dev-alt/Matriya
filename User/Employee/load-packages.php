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
	$query = $dbConnection->prepare("SELECT * FROM Packages WHERE DeleteStatus=? AND PackageStatus=?");
	$query->execute(array($delete_status,$delete_status));
	$num_rows = $query->rowCount();
	$a = "1";
	if($num_rows>0)
	{	
		while($row = $query->fetch())
		{
			$PackageId = $row['PackageId'];
			$OrderId = $row['OrderId'];

			$query11 = $dbConnection->prepare("SELECT DisplayName FROM CustomerMaster AS cm INNER JOIN SalesOrders AS so ON so.CustomerId_CustomerMaster=cm.CustomerId WHERE so.OrderId=? AND so.DeleteStatus=?");
			$query11->execute(array($OrderId,$delete_status));
			$row11 = $query11->fetch();
			$CustomerName = $row11['DisplayName'];


			$query3 = $dbConnection->prepare("SELECT *,SUM(Packed) AS Totalpkg FROM PackageDetails WHERE PackageId_Packages=? AND DeleteStatus=?");
			$query3->execute(array($PackageId,$delete_status));
			while($row3 = $query3->fetch())
			{

				$Totalpkg = $row3['Totalpkg'];
				$query1 = $dbConnection->prepare("SELECT * FROM SalesOrderDetails WHERE OrderId_SalesOrders=? AND DeleteStatus=?");
				$query1->execute(array($row3['PkId_SalesOrderDetails'],$delete_status));
				$row1 = $query1->fetch();
				$PkId_InventoryMaster = $row1['PkId_InventoryMaster'];

				$query2 = $dbConnection->prepare("SELECT im.ProductId_ProductMaster,im.BatchNoORSrNo,im.BatchManufacturer,im.ManfactureDate,im.ExpireDate,pm.ProductName,pm.SKU FROM InventoryMaster AS im INNER JOIN ProductMaster AS pm ON im.ProductId_ProductMaster=pm.ProductId
					WHERE im.PkId=? AND im.DeleteStatus=?");
				$query2->execute(array($PkId_InventoryMaster,$delete_status));
				$row2 = $query2->fetch();
				$ProductId_ProductMaster = $row2['ProductId_ProductMaster'];
				$ProductName = $row2['ProductName'];
				$SKU = $row2['SKU'];


				$data2[] = array(
				'OrderId_SalesOrders' => $row1['OrderId_SalesOrders'],
				'PkId_InventoryMaster' => $row1['PkId_InventoryMaster'],
				'Quantity' => $row1['Quantity'],
				'Price' => $row1['Price'],
				'Amount'=>$row1['Amount'],
				'ProductId_ProductMaster'=>$ProductId_ProductMaster,
				'ProductName'=>$ProductName,
				'SKU'=>$SKU,
				);

			}

			$data1[] = array(
				'PkId' => $row['PkId'],
				'CustomerId_CustomerMaster' => $row['CustomerId_CustomerMaster'],
				'CustomerName' => $CustomerName,
				'PackageId' => $row['PackageId'],
				'OrderId' => $row['OrderId'],
				'PkgDate' => $row['PkgDate'],
				'OrderDate' => $row['OrderDate'],
				'Reference' => $row['Reference'],
				'ShipmentDate'=>$row['ShipmentDate'],
				'PaymentTerms'=>$row['PaymentTerms'],
				'DeliveryMethod'=>$row['DeliveryMethod'],
				'Salesperson'=>$row['Salesperson'],
				'CustomerNotes'=>$row['CustomerNotes'],
				'TermsCondition'=>$row['TermsCondition'],
				'FileName'=>$row['FileName'],
				'SubTotal'=>$row['SubTotal'],
				'DiscType'=>$row['DiscType'],
				'DiscountVal'=>$row['DiscountVal'],
				'DiscountAmount'=>$row['DiscountAmount'],
				'OrderTotal'=>$row['OrderTotal'],
				'OrderStatus'=>$row['OrderStatus'],
				'InvoiceStatus'=>$row['InvoiceStatus'],
				'PackageStatus'=>$row['PackageStatus'],

				'Totalpkg'=>$Totalpkg,
				//'data2'=>$data2,
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