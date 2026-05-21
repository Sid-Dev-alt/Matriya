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
	$data3 = array();

	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	$query = $dbConnection->prepare("SELECT * FROM RawPurchaseMaster WHERE DeleteStatus=? ORDER BY PkId DESC LIMIT $first,$itemsPerPage");
	$query->execute(array($delete_status));
	$num_rows = $query->rowCount();
	$a = "1";
	if($num_rows>0)
	{	
		while($row = $query->fetch())
		{
			$FormPkId = $row['PkId'];
			$RawPurchaseId = $row['RawPurchaseId'];
			$RawPODate = $row['RawPODate'];
			$VendorId_VenodrMaster = $row['VendorId_VendorMaster'];

			$PkId_GoDownMaster = $row['PkId_GoDownMaster'];

			$query1 = $dbConnection->prepare("SELECT DisplayName FROM VendorMaster WHERE VendorId=?");
			$query1->execute(array($VendorId_VenodrMaster));
			$row1 = $query1->fetch();
			$VendorName = $row1['DisplayName'];


			$query2 = $dbConnection->prepare("SELECT GoDownName FROM GoDownMaster WHERE PkId=?");
			$query2->execute(array($PkId_GoDownMaster));
			$row2 = $query2->fetch();
			$GoDownName = $row2['GoDownName'];
			
			$TotalQty = 0;
			$query3 = $dbConnection->prepare("SELECT * FROM RawPurchaseMasterDetails WHERE RawPurchaseId_RawPurchaseMaster=? AND DeleteStatus=?");
			$query3->execute(array($RawPurchaseId,$delete_status));
			while($row3 = $query3->fetch())
			{
				$EntryPkId = $row3['PkId'];
				$ProductId_ProductMaster = $row3['ProductId_ProductMaster'];
				$PurchaseQty = $row3['PurchaseQty'];	
				$RollNo = $row3['RollNo'];
				$ProductSize = $row3['ProductSize'];
				$IsSplitQty = $row3['IsSplitQty'];
				$DeleteStatus = $row3['DeleteStatus'];
				$DetailRemarks = $row3['Remarks'];

				$query5 = $dbConnection->prepare("SELECT InvoiceDetails.* FROM InventoryMaster INNER JOIN InvoiceDetails ON InventoryMaster.PkId=InvoiceDetails.PkId_InventoryMaster WHERE InventoryMaster.PkId_RawPurchaseMasterDetails=? AND InvoiceDetails.DeleteStatus=?");
				$query5->execute(array($EntryPkId,$delete_status));
				if($query5->rowCount()>0)
				{
					$Isinvoiced = 1;
				}
				else
				{
					$Isinvoiced = 0;
				}


				$query4 = $dbConnection->prepare("SELECT ProductName,Micron,Unit FROM ProductMaster WHERE ProductId=?");
				$query4->execute(array($ProductId_ProductMaster));
				$row4 = $query4->fetch();
				$ProductName = $row4['ProductName'];
				$Micron = $row4['Micron'];
				$Unit = $row4['Unit'];

				if($ProductSize=="" || $ProductSize=="NA")
				{
					$TotalProductName = "$Micron $ProductName";	
				}
				else
				{
					$TotalProductName = "$Micron $ProductName $ProductSize MM";
				}

				$TotalQty += $PurchaseQty;

				$data2[] = array(
				'EntryPkId' => $EntryPkId,
				'ProductId_ProductMaster' => $ProductId_ProductMaster,
				'ProductName' => $ProductName,
				'TotalProductName' => $TotalProductName,
				'ProductSize' => $ProductSize,
				'Micron' => $Micron,
				'PurchaseQty' => $PurchaseQty,
				'Remarks' => $DetailRemarks,
				'RollNo' => $RollNo,
				'Unit' => $Unit,
				'IsSplitQty' => $IsSplitQty,
				'Isinvoiced' => $Isinvoiced,
				'DeleteStatus' => $DeleteStatus,
				);
			}

			$data1[] = array(
				'FormPkId' => $FormPkId,
				'RawPurchaseId' => $RawPurchaseId,
				'VendorId_VenodrMaster' => $VendorId_VenodrMaster,
				'VendorName' => $VendorName,
				'RawPODate' => $RawPODate,
				'PkId_GoDownMaster'=>$PkId_GoDownMaster,
				'GoDownName'=>$GoDownName,
				'Comments'=>$row['Comments'],
				'data2'=>$data2,
				'TotalQty'=>$TotalQty,
			);
			unset($data2);
		}

		$sql = $dbConnection->prepare("SELECT COUNT(PkId) AS Total FROM RawPurchaseMaster WHERE DeleteStatus=?");
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