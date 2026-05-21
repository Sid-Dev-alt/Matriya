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

	$data = json_decode(file_get_contents("php://input"));
	$Micron = $data->Micron;
	$ProductName = $data->ProductName;
	//$ProductSize = $data->ProductSize;

	$itemsPerPage = $data->itemsPerPage;
    $pagenumber = $data->pagenumber;
    $first = ($pagenumber-1)*$itemsPerPage;
    $fromdate = $data->fromdate;
    $todate = $data->todate;
    $FromDate = date("Y-m-d",strtotime($fromdate));
	$ToDate = date("Y-m-d",strtotime($todate));

$data1 = array();
$data2 = array();

	$query = $dbConnection->prepare("SELECT PM.PkId,PM.ProductId,PM.ProductName,PM.Micron,PM.Unit,PM.PkId_Category,PM.InventoryType,cat.CategoryName FROM ProductMaster AS PM INNER JOIN Category AS cat ON cat.PkId=PM.PkId_Category WHERE PM.DeleteStatus=? AND PM.Micron=? AND PM.ProductName=?");
	$query->execute(array($delete_status,$Micron,$ProductName));
	$num_rows = $query->rowCount();
		$rows = $query->fetch();
		//{
			$PkId = $rows['PkId'];
			$ProductId = $rows['ProductId'];
			$ProductName = $rows['ProductName'];
			$Micron = $rows['Micron'];
			$Unit = $rows['Unit'];
			$PkId_Category = $rows['PkId_Category'];
			$InventoryType = $rows['InventoryType'];


			// $PkId_SubCategoryMaster = $rows['PkId_SubCategoryMaster'];
			// $PkId_Level2SubCategoryMaster = $rows['PkId_Level2SubCategoryMaster'];
			// $PkId_Level3SubCategoryMaster = $rows['PkId_Level3SubCategoryMaster'];
			// $CategoryName = $rows['CategoryName'];
			// $FileName = $rows['FileName'];
			// if($rows['Status']==1)
			// {
			// 	$displaystatus = "Active";
			// }
			// else
			// {
			// 	$displaystatus = "Inactive";
			// }

			// $query1 = $dbConnection->prepare("SELECT SubCategoryName FROM SubCategoryMaster WHERE  PkId=? AND DeleteStatus=?");
			// $query1->execute(array($PkId_SubCategoryMaster,$delete_status));
			// $rows1 = $query1->fetch();
			// $SubCategoryName = $rows1['SubCategoryName'];

			// $query2 = $dbConnection->prepare("SELECT Level2SCName FROM Level2SubCategoryMaster WHERE  PkId=? AND DeleteStatus=?");
			// $query2->execute(array($PkId_Level2SubCategoryMaster,$delete_status));
			// $rows2 = $query2->fetch();
			// $Level2SCName = $rows2['Level2SCName'];

			// $query3 = $dbConnection->prepare("SELECT Level3SCName FROM Level3SubCategoryMaster WHERE  PkId=? AND DeleteStatus=?");
			// $query3->execute(array($PkId_Level3SubCategoryMaster,$delete_status));
			// $rows3 = $query3->fetch();
			// $Level3SCName = $rows3['Level3SCName'];


			$query8 = $dbConnection->prepare("SELECT * FROM RawPurchaseMasterDetails WHERE ProductId_ProductMaster=? AND DeleteStatus=? AND IsSplitQty=? AND TDate  BETWEEN '$FromDate' AND '$ToDate' ORDER BY PkId DESC LIMIT $first,$itemsPerPage");
			$query8->execute(array($ProductId,$delete_status,$delete_status));
			while($row8 = $query8->fetch())
			{
				$ProductSize = $row8['ProductSize'];
				if($ProductSize=="" || $ProductSize=="NA")
				{
					$TotalProductName= "$Micron $ProductName";	
				}
				else
				{
					$TotalProductName= "$Micron $ProductName";
				}
                $RollNo=$row8['RollNo'];
                $DetailRemarks = $row8['Remarks'];
                //05/06|P101|001
                $PurchaseQty=$row8['PurchaseQty'];

                $query3 = $dbConnection->prepare("SELECT * FROM InventoryMaster WHERE UniqueRollNo=?");
                $query3->execute(array($RollNo));
                $row3 = $query3->fetch();
                $InvPkId = $row3['PkId'];
                
                $query4 = $dbConnection->prepare("SELECT SUM(SlittingRolls.SlitQty) AS TotalSlitQty FROM SlittingRolls INNER JOIN Slitting  ON SlittingRolls.SlitId_Slitting= Slitting.SlitId WHERE PkId_InventoryMaster=? AND SlittingRolls.DeleteStatus=? AND SlittingRolls.TDate BETWEEN '$FromDate' AND '$ToDate'");
                $query4->execute(array($InvPkId,$delete_status));
                $row4 = $query4->fetch();
                //$TotalSlitQty = $row4['TotalSlitQty'];
                $TotalSlitQty = number_format((float)$row4['TotalSlitQty'], 3, '.', '');
                
				$data2[] = array(
				"InvPkId"=>$row8['PkId'],
				"ProductSize"=>$row8['ProductSize'],
				"UniqueRollNo"=>$row8['RollNo'],
				"Remarks" => $DetailRemarks,
				"PurchaseQty"=>$PurchaseQty,
                "TotalSlitQty"=>$TotalSlitQty,
				"ProductName"=>$ProductName,
				"TotalProductName"=>$TotalProductName,
				"Micron"=>$Micron,
				"Unit"=>$Unit,);
			}

			$sql = $dbConnection->prepare("SELECT COUNT(PkId) AS Total FROM RawPurchaseMasterDetails WHERE ProductId_ProductMaster=? AND DeleteStatus=? AND IsSplitQty=? AND TDate  BETWEEN '$FromDate' AND '$ToDate'");
	    	$sql->execute(array($ProductId,$delete_status,$delete_status));
	        $row4 = $sql->fetch();
	        $Total = $row4['Total'];
	    	$data3 = array("Total"=>$Total,"data1"=>$data2);

			// $data1[] = array("PkId"=>$PkId,"ProductId"=>$ProductId,
			// 	"PkId_Category"=>$PkId_Category,
			// 	"CategoryName"=>$CategoryName,
			// 	// "PkId_SubCategoryMaster"=>$PkId_SubCategoryMaster,
			// 	// "SubCategoryName"=>$SubCategoryName,
			// 	// "PkId_Level2SubCategoryMaster"=>$PkId_Level2SubCategoryMaster,
			// 	// "Level2SCName"=>$Level2SCName,
			// 	// "PkId_Level3SubCategoryMaster"=>$PkId_Level3SubCategoryMaster,
			// 	// "Level3SCName"=>$Level3SCName,
			// 	// "InventoryType"=>$InventoryType,
			// 	// "FileName"=>$FileName,
			// 	"ProductName"=>$ProductName,
			// 	"TotalProductName"=>$TotalProductName,
			// 	//"AvlQty"=>$AvlQty,
			// 	"Micron"=>$Micron,
			// 	"ProductSize"=>$ProductSize,
			// 	"Unit"=>$Unit,
			// 	"Status"=>$rows['Status'],
			// 	//"displaystatus"=>$displaystatus,
			// 	"data2"=>$data2
			// );
			//unset($data2);
//		}

		echo json_encode($data3);
}
?>