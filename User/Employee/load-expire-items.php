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

	$todaty = date("Y-m-d");
	$query9 = $dbConnection->prepare("SELECT * FROM InventoryMaster WHERE DeleteStatus=? AND Status=? AND   ExpireDate!='' AND ExpireDate<? AND Quantity>?");
	$query9->execute(array($delete_status,$delete_status,$todaty,'0'));
	if($query9->rowCount()>0)
	{	
		while($row = $query9->fetch())
		{
			$PkId = $row['PkId'];
			$ProductId_ProductMaster = $row['ProductId_ProductMaster'];
			$BatchNoORSrNo = $row['BatchNoORSrNo'];
			$BatchManufacturer = $row['BatchManufacturer'];
			$ManfactureDate = $row['ManfactureDate'];
			$ExpireDate = $row['ExpireDate'];
			$Quantity = $row['Quantity'];

			$query3 = $dbConnection->prepare("SELECT ProductName FROM ProductMaster WHERE ProductId=? AND DeleteStatus=?");
			$query3->execute(array($ProductId_ProductMaster,$delete_status));
			$row3 = $query3->fetch();
			$ProductName = $row3['ProductName'];


			$data1[] = array(
				'PkId' => $row['PkId'],
				'ProductName' => $ProductName,
				'ProductId_ProductMaster' => $ProductId_ProductMaster,
				'BatchNoORSrNo' => $BatchNoORSrNo,
				'BatchManufacturer' => $BatchManufacturer,
				'ManfactureDate' => $ManfactureDate,
				'ExpireDate' => $ExpireDate,
				'Quantity' => $Quantity
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