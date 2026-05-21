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
	$data1 = array();
	$data2 = array();
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	$data = json_decode(file_get_contents("php://input"));
	$ProductPkId = $data->ProductPkId;

	$query = $dbConnection->prepare("SELECT * FROM ProductMaster WHERE DeleteStatus=? AND PkId=?");
	$query->execute(array($delete_status,$ProductPkId));
	$num_rows = $query->rowCount();
	if($num_rows>0)
	{	
		$rows= $query->fetch();
		
			$PkId = $rows['PkId'];
			$ProductId = $rows['ProductId'];

			$query2 = $dbConnection->prepare("SELECT DisplayName FROM VendorMaster WHERE VendorId=?");
			$query2->execute(array($rows['VendorId_VendorMaster']));
			$rows2= $query2->fetch();
			$VendorName = $rows2['DisplayName'];
			
			$serailnumber = "";
			$query1 = $dbConnection->prepare("SELECT * FROM InventoryMaster WHERE OpeningQuantity!=? AND ProductId_ProductMaster=? AND DeleteStatus=?");
			$query1->execute(array('0',$ProductId,$delete_status));
			while($rows1= $query1->fetch())
			{
				$data2[] = array(
					"EntryPkId"=>$rows1['PkId'],
					"BatchNoORSrNo"=>$rows1['BatchNoORSrNo'],
					"SKU"=>$rows1['SKU'],
					"BatchManufacturer"=>$rows1['BatchManufacturer'],
					"ManfactureDate"=>$rows1['ManfactureDate'],
					"ExpireDate"=>$rows1['ExpireDate'],
					"OpeningQuantity"=>$rows1['OpeningQuantity'],
					"Quantity"=>$rows1['Quantity'],
				);
				$serailnumber .= $rows1['BatchNoORSrNo'].",";
			}

		$data1 = array("PkId"=>$PkId,
			"ProductId"=>$ProductId,
			"Type"=>$rows['Type'],
			"PkId_Category"=>$rows['PkId_Category'],
			"PkId_SubCategoryMaster"=>$rows['PkId_SubCategoryMaster'],
			"PkId_Level2SubCategoryMaster"=>$rows['PkId_Level2SubCategoryMaster'],
			"PkId_Level3SubCategoryMaster"=>$rows['PkId_Level3SubCategoryMaster'],
			"ProductName"=>$rows['ProductName'],
			"SKU"=>$rows['SKU'],
			"Unit"=>$rows['Unit'],
			"FileName"=>$rows['FileName'],
			"Size"=>$rows['Size'],
			"Colour"=>$rows['Colour'],
			"InventoryType"=>$rows['InventoryType'],
			"IsItReturnable"=>$rows['IsItReturnable'],
			"Dimensions"=>$rows['Dimensions'],
			"Weight"=>$rows['Weight'],
			"Manufacturer"=>$rows['Manufacturer'],
			"Brand"=>$rows['Brand'],
			"UPC"=>$rows['UPC'],
			"MPN"=>$rows['MPN'],
			"EAN"=>$rows['EAN'],
			"ISBN"=>$rows['ISBN'],
			"SalesPrice"=>$rows['SalesPrice'],
			"PurchasePrice"=>$rows['PurchasePrice'],
			"OpeningStock"=>$rows['OpeningStock'],
			"OpeningStockRateperUnit"=>$rows['OpeningStockRateperUnit'],
			"ReorderPoint"=>$rows['ReorderPoint'],
			"TrackingMode"=>$rows['TrackingMode'],
			"VendorId_VendorMaster"=>$rows['VendorId_VendorMaster'],
			"VendorName"=>$VendorName,
			"Status"=>$rows['Status'],
			"serailnumber"=>rtrim($serailnumber,','),
			"data2"=>$data2,
			);
		

		echo (json_encode($data1));
	}
	else
	{
		echo "NoData";
	}
}
?>