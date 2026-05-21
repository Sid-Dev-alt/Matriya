<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{		

	$data = json_decode(file_get_contents("php://input"));
	$delete_status = 1;	

	$itemsPerPage = $data->itemsPerPage;
	$pagenumber = $data->pagenumber;
	$first = ($pagenumber-1)*$itemsPerPage;

	$data1 = array();
	$data2 = array();
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	$query = $dbConnection->prepare("SELECT * FROM Slitting WHERE DeleteStatus=? ORDER BY PkId DESC LIMIT $first,$itemsPerPage");
	$query->execute(array($delete_status));
	$num_rows = $query->rowCount();
	if($num_rows>0)
	{	
		while($row = $query->fetch())
		{
			$FormPkId = $row['PkId'];
			$SlitId = $row['SlitId'];
			$SlitDate = $row['SlitDate'];
			$PkId_InventoryMaster = $row['PkId_InventoryMaster'];
			$Remarks = $row['Remarks'];

			$query2 = $dbConnection->prepare("SELECT PkId_RawPurchaseMasterDetails,ProductId_ProductMaster,UniqueRollNo,ProductSize,Quantity,Remarks,IsSplitQty FROM InventoryMaster WHERE PkId=?");
			$query2->execute(array($PkId_InventoryMaster));
			$row2 = $query2->fetch();
			$UniqueRollNo = $row2['UniqueRollNo'];
			$ProductId_ProductMaster = $row2['ProductId_ProductMaster'];
			$ProductSize = $row2['ProductSize'];
			$PkId_RawPurchaseMasterDetails=$row2['PkId_RawPurchaseMasterDetails'];
			$Quantity=$row2['Quantity'];
			$DetailRemarks = $row2['Remarks'];
			$IsSplitQty = $row2['IsSplitQty'];

			$query1 = $dbConnection->prepare("SELECT * FROM RawPurchaseMasterDetails WHERE PkId=?");
			$query1->execute(array($PkId_RawPurchaseMasterDetails));
			$row1 = $query1->fetch();
			$RawPurchaseId_RawPurchaseMaster = $row1['RawPurchaseId_RawPurchaseMaster'];
			$PurchaseQty = $row1['PurchaseQty'];
			
			$query22 = $dbConnection->prepare("SELECT Name FROM Users WHERE UserId=?");
			$query22->execute(array($row['UserId_Users']));
			$row22 = $query22->fetch();
			$UserName = $row22['Name'];

			/*
			if ( str_contains ($UniqueRollNo, 'S') ) {
				$query11 = $dbConnection->prepare("SELECT SlitQty FROM SlittingRolls WHERE RollNo=?");
				$query11->execute(array($UniqueRollNo));
				$row11 = $query11->fetch();
				$ParentSlitQty = $row11['SlitQty'];
				$ParentSlit="1";
			}
			else
			{
				$ParentSlit="0";
			}
			*/
			if (strpos($UniqueRollNo, 'S') !== false) {//true
				$query11 = $dbConnection->prepare("SELECT SlitQty,Remarks FROM SlittingRolls WHERE RollNo=?");
				$query11->execute(array($UniqueRollNo));
				$row11 = $query11->fetch();
				$ParentSlitQty = $row11['SlitQty'];
				$ParentSlit="1";
            }
			else
			{
				$ParentSlit="0";
			}
			
			$query33 = $dbConnection->prepare("SELECT * FROM SlittingRolls INNER JOIN InventoryMaster ON SlittingRolls.RollNo=InventoryMaster.UniqueRollNo INNER JOIN InvoiceDetails ON InventoryMaster.PkId=InvoiceDetails.PkId_InventoryMaster WHERE InvoiceDetails.DeleteStatus=? AND SlittingRolls.SlitId_Slitting=?");
			$query33->execute(array($delete_status,$SlitId));	
			$delbtn = $query33->rowcount();

			// $query4 = $dbConnection->prepare("SELECT PkId_GoDownMaster,Comments FROM RawPurchaseMaster WHERE RawPurchaseId=?");
			// $query4->execute(array($RawPurchaseId_RawPurchaseMaster));
			// $row4 = $query4->fetch();
			// $PkId_GoDownMaster = $row4['PkId_GoDownMaster'];
			// $Comments = $row4['Comments'];

			// $query5 = $dbConnection->prepare("SELECT GoDownName FROM GoDownMaster WHERE PkId=?");
			// $query5->execute(array($PkId_GoDownMaster));
			// $row5 = $query5->fetch();
			// $GoDownName = $row5['GoDownName'];

			$query6 = $dbConnection->prepare("SELECT ProductName,Micron,Unit FROM ProductMaster WHERE ProductId=?");
			$query6->execute(array($ProductId_ProductMaster));
			$row6 = $query6->fetch();
			$ProductName = $row6['ProductName'];
			$Micron = $row6['Micron'];
			$Unit = $row6['Unit'];
			$TotalName= "$Micron $ProductName";	

			$query7 = $dbConnection->prepare("SELECT SUM(SlitQty) AS TotalSlitQty FROM SlittingRolls WHERE SlitId_Slitting=? AND DeleteStatus=?");
			$query7->execute(array($SlitId,$delete_status));
			$row7 = $query7->fetch();
			$TotalSlitQty = $row7['TotalSlitQty'];

			
			$query9 = $dbConnection->prepare("SELECT DISTINCT SetId FROM SlittingRolls WHERE SlitId_Slitting=? AND DeleteStatus=?");
			$query9->execute(array($SlitId,$delete_status));
			while($row9 = $query9->fetch())
			{
				$SetId = $row9['SetId'];

				$TotalStepQty = 0;
				$query3 = $dbConnection->prepare("SELECT * FROM SlittingRolls WHERE SlitId_Slitting=? AND SetId=? AND DeleteStatus=?");
				$query3->execute(array($SlitId,$SetId,$delete_status));
				while($row3 = $query3->fetch())
				{
					$EntryPkId = $row3['PkId'];
					$SplitSize = $row3['SplitSize'];
					$RollNo = $row3['RollNo'];
					$SlitQty = $row3['SlitQty'];
					$SlitRemarks = $row3['Remarks'];

					$TotalStepQty += $SlitQty;

					$data3[] = array(
					'EntryPkId' => $EntryPkId,
					//'NewProductId_ProductMaster' => $NewProductId_ProductMaster,
					'NewProductName' => $ProductName,
					'NewProductSize' => $SplitSize,
					'NewMicron' => $Micron,
					'NewUnit' => $Unit,
					'splitsize' => $SplitSize,
					'RollNo' => $RollNo,
					'quantity' => $SlitQty,
					"remarks" => $SlitRemarks,
					"delflag" => $row3['DeleteStatus'],
					);
				}
				$data2[] = array('SetId' => $SetId,'data3' => $data3,"TotalStepQty" => $TotalStepQty);
				unset($data3);
			}

			$data1[] = array(
				'FormPkId' => $FormPkId,
				'SlitId' => $SlitId,
				'PkId_InventoryMaster' => $PkId_InventoryMaster,
				'PkId_RawPurchaseMasterDetails' => $PkId_RawPurchaseMasterDetails,
				'ProductId_ProductMaster' => $ProductId_ProductMaster,
				'ProductName' => $ProductName,
				'ProductSize' => $ProductSize,
				'Micron' => $Micron,
				'Unit' => $Unit,
				'SlitDate' => $SlitDate,
				'TotalName' => $TotalName,
				'UniqueRollNo' => $UniqueRollNo,
				'Remarks'=>$Remarks,
				"GoDownName"=>$GoDownName,

				"ParentSlit"=>$ParentSlit,
				"ParentSlitQty"=>$ParentSlitQty,
				"UserName"=>$UserName,
				"Quantity"=>$Quantity,
				"PurchaseQty"=>$PurchaseQty,
				"TotalSlitQty"=>$TotalSlitQty,
				'data2'=>$data2,
				'delbtn'=>$delbtn,
			);
			unset($data2);
		}
		$sql = $dbConnection->prepare("SELECT COUNT(PkId) AS Total FROM Slitting WHERE DeleteStatus=?");
		$sql->execute(array($delete_status));
		$row4 = $sql->fetch();
		$Total = $row4['Total'];
		$data3 = array("Total"=>$Total,"data1"=>$data1);

		echo json_encode($data3);
	}
	else
	{
		echo "NoData";
	}
}
?>
