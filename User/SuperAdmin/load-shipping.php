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
	$query = $dbConnection->prepare("SELECT * FROM Shipping WHERE DeleteStatus=?");
	$query->execute(array($delete_status));
	$num_rows = $query->rowCount();
	$a = "1";
	if($num_rows>0)
	{	
		while($row = $query->fetch())
		{
			$ShipId = $row['ShipId'];
			$PackageId_Packages = $row['PackageId_Packages'];
			$ShipDate = $row['ShipDate'];
			$Carrier = $row['Carrier'];
			$Tracking = $row['Tracking'];
			$ShipCharges = $row['ShipCharges'];
			$Notes = $row['Notes'];

			$query3 = $dbConnection->prepare("SELECT * FROM Packages WHERE PackageId=? AND DeleteStatus=?");
			$query3->execute(array($PackageId_Packages,$delete_status));
			$row3 = $query3->fetch();
			$InvoiceId = $row3['InvoiceId'];

			$query11 = $dbConnection->prepare("SELECT DisplayName FROM CustomerMaster AS cm INNER JOIN Invoices AS so ON so.CustomerId_CustomerMaster=cm.CustomerId WHERE so.InvoiceId=? AND so.DeleteStatus=?");
			$query11->execute(array($InvoiceId,$delete_status));
			$row11 = $query11->fetch();
			$CustomerName = $row11['DisplayName'];


			

			$data1[] = array(
				'PkId' => $row['PkId'],
				'ShipId' => $ShipId,
				'InvoiceId' => $InvoiceId,
				'CustomerName' => $CustomerName,
				'PackageId' => $PackageId_Packages,
				'ShipDate' => $ShipDate,
				'Carrier' => $Carrier,
				'Tracking' => $Tracking,
				'ShipCharges' => $ShipCharges,
				'Notes' => $Notes,
				// 'PkgDate' => $row['PkgDate'],
				// 'OrderDate' => $row['OrderDate'],
				// 'Reference' => $row['Reference'],
				// 'ShipmentDate'=>$row['ShipmentDate'],
				// 'PaymentTerms'=>$row['PaymentTerms'],
				// 'DeliveryMethod'=>$row['DeliveryMethod'],
				// 'Salesperson'=>$row['Salesperson'],
				// 'CustomerNotes'=>$row['CustomerNotes'],
				// 'TermsCondition'=>$row['TermsCondition'],
				// 'FileName'=>$row['FileName'],
				// 'SubTotal'=>$row['SubTotal'],
				// 'DiscType'=>$row['DiscType'],
				// 'DiscountVal'=>$row['DiscountVal'],
				// 'DiscountAmount'=>$row['DiscountAmount'],
				// 'OrderTotal'=>$row['OrderTotal'],
				// 'OrderStatus'=>$row['OrderStatus'],
				// 'InvoiceStatus'=>$row['InvoiceStatus'],
				// 'PackageStatus'=>$row['PackageStatus'],
				 'ShipStatus'=>$row['ShipStatus'],
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