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
	$VPId = $_SESSION['UserId'];
	$data = json_decode(file_get_contents("php://input"));
	$data1 = array();
	$data2 = array();

	$query = $dbConnection->prepare("SELECT * FROM InventoryAdjustments	 WHERE DeleteStatus=?");
	$query->execute(array($delete_status));
	$num_rows = $query->rowCount();
	if($num_rows>0)
	{	
		while($rows = $query->fetch())
		{
			$PkId = $rows['PkId'];
			$EntryDate = $rows['EntryDate'];
			$Reference = $rows['Reference'];
			$Reason = $rows['Reason'];	
			$Description = $rows['Description'];
			$ModeofAdjust = $rows['ModeofAdjust'];

			

			$query3 = $dbConnection->prepare("SELECT * FROM InventoryAdjustmentDetails WHERE  PkId_InventoryAdjustments=?");
			$query3->execute(array($PkId));
			while($row3 = $query3->fetch())
			{
				$query4 = $dbConnection->prepare("SELECT ProductName,SKU,TrackingMode FROM ProductMaster WHERE  ProductId=? AND DeleteStatus=?");
				$query4->execute(array($row3['ProductId_ProductMaster'],$delete_status));
				$row4 = $query4->fetch();
				 $ProductName = $row4['ProductName'];
				$SKU = $row4['SKU'];
				$TrackingMode = $row4['TrackingMode'];

				$query5 = $dbConnection->prepare("SELECT BatchNoORSrNo FROM InventoryMaster WHERE  PkId=? AND DeleteStatus=?");
				$query5->execute(array($row3['PkId_InventoryMaster'],$delete_status));
				$row5 = $query5->fetch();
				 $BatchNoORSrNo = $row5['BatchNoORSrNo'];

				$data2[] = array("InvPkId"=>$row3['PkId'],
					"ProductId_ProductMaster"=>$row3['ProductId_ProductMaster'],
					"PkId_InventoryMaster"=>$row3['PkId_InventoryMaster'],
					"Quantity"=>$row3['Quantity'],
					"ProductName"=>$ProductName,
					"SKU"=>$SKU,
					"BatchNoORSrNo"=>$BatchNoORSrNo,
					"TrackingMode"=>$TrackingMode,
			);
			}


			$data1[] = array("PkId"=>$PkId,
				"EntryDate"=>$EntryDate,
				"Reference"=>$Reference,
				"Reason"=>$Reason,
				"Description"=>$Description,
				"ModeofAdjust"=>$ModeofAdjust,
				"data2"=>$data2
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