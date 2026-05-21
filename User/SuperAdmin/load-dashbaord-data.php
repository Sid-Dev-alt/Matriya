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
	$currentdate = date('Y-m-d');

	$data1 =array();

	$query = $dbConnection->prepare("SELECT SUM(Quantity) AS sumqty,SUM(PackedQty) AS pckqty FROM InvoiceDetails WHERE DeleteStatus=?");
	$query->execute(array($delete_status));
	$rows = $query->fetch();
	$sumqty = $rows['sumqty'];
	$pckqty = $rows['pckqty'];
	$remainpkdqty = $sumqty-$pckqty;

	$query1 = $dbConnection->prepare("SELECT COUNT(*) AS packed FROM Packages WHERE DeleteStatus=?");
	$query1->execute(array($delete_status));
	$rows1 = $query1->fetch();
	$packed = $rows1['packed'];

	$query3 = $dbConnection->prepare("SELECT COUNT(*) AS shipcount FROM Shipping WHERE DeleteStatus=?");
	$query3->execute(array($delete_status));
	$rows3 = $query3->fetch();
	$shipcount = $rows3['shipcount'];
	$TobeShippd = $packed-$shipcount;

	$query4 = $dbConnection->prepare("SELECT COUNT(*) AS delivercount FROM Shipping WHERE DeleteStatus=? AND ShipStatus<?");
	$query4->execute(array($delete_status,'2'));
	$rows4 = $query4->fetch();
	$TobeDeliver = $rows4['delivercount'];

	$query5 = $dbConnection->prepare("SELECT COUNT(*) AS invoicecount FROM SalesOrders WHERE DeleteStatus=? AND InvoiceStatus=?");
	$query5->execute(array($delete_status,'0'));
	$rows5 = $query5->fetch();
	$TobeInvoice = $rows5['invoicecount'];//not Invoiced

	$query5 = $dbConnection->prepare("SELECT COUNT(*) AS invoicecount FROM SalesOrders WHERE DeleteStatus=? AND InvoiceStatus=?");
	$query5->execute(array($delete_status,'2'));
	$rows5 = $query5->fetch();
	$PartialInvoice = $rows5['invoicecount'];//partial invoiced

	$query5 = $dbConnection->prepare("SELECT COUNT(*) AS invoicecount FROM Invoices WHERE InvoiceDate=? AND DeleteStatus=? AND InvoiceStatus=? AND IsSaveDraft=?");
	$query5->execute(array($currentdate,$delete_status,'2','No'));
	$rows5 = $query5->fetch();
	$Invoiced = $rows5['invoicecount'];//invoiced

	$query6 = $dbConnection->prepare("SELECT COUNT(*) AS procount FROM ProductMaster WHERE DeleteStatus=? AND Status=?");
	$query6->execute(array($delete_status,$delete_status));
	$rows6 = $query6->fetch();
	$procount = $rows6['procount'];

	$query7 = $dbConnection->prepare("SELECT COUNT(*) AS Catcount FROM Category WHERE DeleteStatus=? AND Status=?");
	$query7->execute(array($delete_status,$delete_status));
	$rows7 = $query7->fetch();
	$Catcount = $rows7['Catcount'];

	$query11 = $dbConnection->prepare("SELECT PkId FROM CustomerMaster WHERE DeleteStatus=?");
	$query11->execute(array($delete_status));
	$CustomerCount = $query11->rowCount();

	$query12 = $dbConnection->prepare("SELECT PkId FROM VendorMaster WHERE DeleteStatus=?");
	$query12->execute(array($delete_status));
	$VendorCount = $query12->rowCount();

	$lowitemcount = 0;
	$TotalStockVal = 0;
	$TotalMarginVal = 0;
	$query8 = $dbConnection->prepare("SELECT ProductId,ReorderPoint,SalesPrice,PurchasePrice FROM ProductMaster WHERE DeleteStatus=? AND Status=?");
	$query8->execute(array($delete_status,$delete_status));
	while($rows8 = $query8->fetch())
	{
		$ProductId = $rows8['ProductId'];
		$SalesPrice = $rows8['SalesPrice'];
		$PurchasePrice = $rows8['PurchasePrice'];

			if($rows8['ReorderPoint']!="")
		{
			$ReorderPoint = $rows8['ReorderPoint'];
		
			$query9 = $dbConnection->prepare("SELECT SUM(Quantity) AS AvlQty FROM InventoryMaster WHERE DeleteStatus=? AND ProductId_ProductMaster=?");
			$query9->execute(array($delete_status,$ProductId));
			$rows9 = $query9->fetch();
			 $AvlQty = $rows9['AvlQty'];
			 // echo $AvlQty."<br>";
			 // echo $ReorderPoint."<br>";
			if($AvlQty<=$ReorderPoint)
			{
				$lowitemcount++;
			}
		}

		$query11 = $dbConnection->prepare("SELECT Quantity FROM InventoryMaster WHERE DeleteStatus=? AND ProductId_ProductMaster=?");
		$query11->execute(array($delete_status,$ProductId));
		while($rows11 = $query11->fetch())
		{
			$TotalStockVal += $rows11['Quantity']*$SalesPrice;
		}
		$TotalMarginVal += $SalesPrice-$PurchasePrice;
	}


	$todaty = date("Y-m-d");
	$query9 = $dbConnection->prepare("SELECT * FROM InventoryMaster WHERE DeleteStatus=? AND Status=? AND ExpireDate!='' AND ExpireDate<? AND Quantity>?");
	$query9->execute(array($delete_status,$delete_status,$todaty,'0'));
	$ExpiredCount = $query9->rowCount();

	$query10 = $dbConnection->prepare("SELECT SUM(Quantity) AS TotalQty FROM InventoryMaster WHERE DeleteStatus=? AND Status=?");
	$query10->execute(array($delete_status,$delete_status));
	$rows10 = $query10->fetch();
	$TotalQty = $rows10['TotalQty'];

	$TotalOutstanding = 0;
	$query12 = $dbConnection->prepare("SELECT InvoiceId,InvoiceTotal FROM Invoices WHERE DeleteStatus=? AND InvoiceStatus=? AND DueDate IS NOT NULL AND DueDate<?");
	$query12->execute(array($delete_status,'2',$todaty));
	while($rows12 = $query12->fetch())
	{
		$InvoiceId = $rows12['InvoiceId'];
		$InvoiceTotal = $rows12['InvoiceTotal'];

		$query13 = $dbConnection->prepare("SELECT SUM(ReceivedAmount) AS received FROM Payments WHERE DeleteStatus=? AND InvoiceId_Invoices=?");
		$query13->execute(array($delete_status,$InvoiceId));
		$rows13 = $query13->fetch();
		$received = $rows13['received'];

		$TotalOutstanding += $InvoiceTotal-$received;
	}
		

	// $LocationArr = array();
	// $query = $dbConnection->prepare("SELECT scm.PkId,scm.PkId_LocationType,scm.LocationName,cat.Name FROM LocationMaster AS scm INNER JOIN LocationType AS cat ON cat.PkId=scm.PkId_LocationType WHERE scm.DeleteStatus=?");
	// $query->execute(array($delete_status));
	// $num_rows = $query->rowCount();
	// 	while($rows = $query->fetch())
	// 	{
	// 		$PkId = $rows['PkId'];
	// 		$PkId_LocationType = $rows['PkId_LocationType'];
	// 		$LocationName = $rows['LocationName'];
	// 		$TypeName = $rows['Name'];


	// 		$LocationArr[] = array("PkId"=>$PkId,"PkId_LocationType"=>$PkId_LocationType,"TypeName"=>$TypeName,"LocationName"=>$LocationName);
	// 	}

	$data1 = array("remainpkdqty"=>$remainpkdqty,
		"TobeShippd"=>$TobeShippd,
		"TobeDeliver"=>$TobeDeliver,
		"TobeInvoice"=>$TobeInvoice,
		"PartialInvoice"=>$PartialInvoice,
		"Invoiced"=>$Invoiced,
		"procount"=>$procount,
		"Catcount"=>$Catcount,
		"lowitemcount"=>$lowitemcount,
		"ExpiredCount"=>$ExpiredCount,
		"TotalQty"=>$TotalQty,
		"TotalStockVal"=>$TotalStockVal,
		"TotalOutstanding"=>$TotalOutstanding,
		"TotalMarginVal"=>$TotalMarginVal,
		"CustomerCount"=>$CustomerCount,
		"VendorCount"=>$VendorCount,
	);

	echo (json_encode($data1));
	
}
?>