<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{	
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
			
	$UserId = $_SESSION['EmpId'];		

	$data = json_decode(file_get_contents("php://input"));
	 

	$delete_status = 1;
	$wh=1; //Warehouse
	$data1 = array(); 
	$data2 = array();  
	$query = $dbConnection->prepare("SELECT * FROM LocationMaster WHERE PkId_LocationType=? AND DeleteStatus=?");
	$query->execute(array($wh,$delete_status));
	while($row = $query->fetch())
	{
		$LocationPkId = $row['PkId'];
		$LocationName = $row['LocationName'];

		$query5 = $dbConnection->prepare("SELECT ivm.PkId,ivm.PkId_LocationMaster,ivm.ProductId_ProductMaster,ivm.Size,ivm.Brand,ivm.BuyPrice,ivm.SalesPrice,ivm.SKU,ivm.BatchNo,ivm.ManfactureDate,ivm.ExpireDate,ivm.AvailableQty,ivm.Status,pm.PkId_Category,pm.PkId_SubCategoryMaster,pm.PkId_Level2SubCategoryMaster,pm.PkId_Level3SubCategoryMaster,pm.ProductName FROM InventoryMaster AS ivm INNER JOIN ProductMaster AS pm ON pm.ProductId = ivm.ProductId_ProductMaster WHERE ivm.PkId_LocationMaster=? AND ivm.AvailableQty>'0' AND ivm.Status=? AND ivm.DeleteStatus=?");
		$query5->execute(array($LocationPkId,$delete_status,$delete_status));
		while($rows = $query5->fetch())
		{
			$PkId = $rows['PkId'];
			$PkId_Category = $rows['PkId_Category'];
			$PkId_SubCategoryMaster = $rows['PkId_SubCategoryMaster'];
			$PkId_Level2SubCategoryMaster = $rows['PkId_Level2SubCategoryMaster'];
			$PkId_Level3SubCategoryMaster = $rows['PkId_Level3SubCategoryMaster'];

			$ProductId_ProductMaster = $rows['ProductId_ProductMaster'];
			$ProductName = $rows['ProductName'];
			$BatchNo = $rows['BatchNo'];
			$Size = $rows['Size'];
			$Brand = $rows['Brand'];
			$ManfactureDate = $rows['ManfactureDate'];
			$ExpireDate = $rows['ExpireDate'];
			$BuyPrice = $rows['BuyPrice'];
			$SalesPrice = $rows['SalesPrice'];
			$SKU = $rows['SKU'];
			$AvailableQty = $rows['AvailableQty'];
			$Status = $rows['Status'];

			$query = $dbConnection->prepare("SELECT CategoryName FROM Category WHERE  PkId=? AND DeleteStatus=?");
			$query->execute(array($PkId_Category,$delete_status));
			$row = $query->fetch();
			$CategoryName = $row['CategoryName'];

			$query1 = $dbConnection->prepare("SELECT SubCategoryName FROM SubCategoryMaster WHERE  PkId=? AND DeleteStatus=?");
			$query1->execute(array($PkId_SubCategoryMaster,$delete_status));
			$rows1 = $query1->fetch();
			$SubCategoryName = $rows1['SubCategoryName'];

			$query2 = $dbConnection->prepare("SELECT Level2SCName FROM Level2SubCategoryMaster WHERE  PkId=? AND DeleteStatus=?");
			$query2->execute(array($PkId_Level2SubCategoryMaster,$delete_status));
			$rows2 = $query2->fetch();
			$Level2SCName = $rows2['Level2SCName'];

			$query3 = $dbConnection->prepare("SELECT Level3SCName FROM Level3SubCategoryMaster WHERE  PkId=? AND DeleteStatus=?");
			$query3->execute(array($PkId_Level3SubCategoryMaster,$delete_status));
			$rows3 = $query3->fetch();
			$Level3SCName = $rows3['Level3SCName'];


			$data2[] = array("PkId"=>$PkId,
				"ProductId_ProductMaster"=>$ProductId_ProductMaster,
				"CategoryName"=>$CategoryName,
				"SubCategoryName"=>$SubCategoryName,
				"Level2SCName"=>$Level2SCName,
				"Level3SCName"=>$Level3SCName,
				"ProductName"=>$ProductName,
				"Size"=>$Size,
				"Brand"=>$Brand,
				"BatchNo"=>$BatchNo,
				"ManfactureDate"=>$ManfactureDate,
				"ExpireDate"=>$ExpireDate,
				"BuyPrice"=>$BuyPrice,
				"SalesPrice"=>$SalesPrice,
				"SKU"=>$SKU,
				"AvailableQty"=>$AvailableQty,
				"Status"=>$Status);
			
		}
		$data1[] = array("LocationPkId"=>$LocationPkId,"LocationName"=>$LocationName,"data2"=>$data2);
		unset($data2);
	}

	echo json_encode($data1);
}
?>