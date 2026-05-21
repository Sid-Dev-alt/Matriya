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
	$VPId = $_SESSION['UserId'];
	$data = json_decode(file_get_contents("php://input"));
	$location = $data->location;
	
	
	$data1 = array();
	$data2 = array();
	$query1 = $dbConnection->prepare("SELECT PkId FROM InventoryMaster WHERE PkId_LocationMaster=? AND Status=? AND DeleteStatus=?");
	$query1->execute(array($location,$delete_status,$delete_status));
	while($rows1 = $query1->fetch())
		{
			$PkIdArr = $rows1['PkId'];

			# code...
			$query2 = $dbConnection->prepare("SELECT * FROM StockDetails WHERE ReferenceId=? AND DeleteStatus=? ORDER BY PkId DESC");
			$query2->execute(array($PkIdArr,$delete_status));
			while($row2 = $query2->fetch())
			{

				$PkId = $row2['PkId'];
				$TransactionType = $row2['TransactionType'];
				$SKU_InventoryMaster = $row2['SKU_InventoryMaster'];
				$CreatedTime = $row2['CreatedTime'];


				$query = $dbConnection->prepare("SELECT ivm.PkId,ivm.PkId_LocationMaster,ivm.ProductId_ProductMaster,ivm.Size,ivm.Brand,ivm.BuyPrice,ivm.SalesPrice,ivm.SKU,ivm.BatchNo,ivm.ManfactureDate,ivm.ExpireDate,ivm.AvailableQty,ivm.Status,pm.ProductName FROM InventoryMaster AS ivm INNER JOIN ProductMaster AS pm ON pm.ProductId = ivm.ProductId_ProductMaster WHERE ivm.SKU=? AND ivm.Status=? AND ivm.DeleteStatus=?");
				$query->execute(array($SKU_InventoryMaster,$delete_status,$delete_status));
		
				$rows = $query->fetch();
				$ProductId_ProductMaster = $rows['ProductId_ProductMaster'];
				$ProductName = $rows['ProductName'];
				// $BatchNo = $rows['BatchNo'];
				 $Size = $rows['Size'];
				 $Brand = $rows['Brand'];
				// $ManfactureDate = $rows['ManfactureDate'];
				// $ExpireDate = $rows['ExpireDate'];
				// $BuyPrice = $rows['BuyPrice'];
				// $SalesPrice = $rows['SalesPrice'];
				// $SKU = $rows['SKU'];
				// $AvailableQty = $rows['AvailableQty'];
				// $Status = $rows['Status'];
				if($TransactionType==1)
				{
					$action = "You added a Product Quantity";
				}
				else if($TransactionType==2)
				{
					$action =  "You adjusted a Product Quantity";
				}
				else if($TransactionType==3)
				{
					$action =  "You transferred Products";
				}
				else if($TransactionType==4)
				{
					$action =  "You received Products";
				}

			$data1[] = array(
				"PkId"=>$PkId,
				"ProductId_ProductMaster"=>$ProductId_ProductMaster,"ProductName"=>$ProductName,
				"Size"=>$Size,
				 "Brand"=>$Brand,
				 "action"=>$action,
				// "BatchNo"=>$BatchNo,
				// "ManfactureDate"=>$ManfactureDate,
				// "ExpireDate"=>$ExpireDate,
				// "BuyPrice"=>$BuyPrice,
				// "SalesPrice"=>$SalesPrice,
				// "SKU"=>$SKU,
				// "AvailableQty"=>$AvailableQty,
				// "Status"=>$Status,
				 "CreatedTime"=>$CreatedTime 
			);
		}
		
	}	
	

		$data2[]	 = array("data1"=>$data1);
		unset($data1);
		echo json_encode($data2);
		
}
?>