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

	$data = json_decode(file_get_contents("php://input"));
	$Micron = $data->Micron;
	$ProductName = $data->ProductName;
	//$ProductSize = $data->ProductSize;

	 $itemsPerPage = $data->itemsPerPage;
    $pagenumber = $data->pagenumber;
    $first = ($pagenumber-1)*$itemsPerPage;
    
$data1 = array();
$data2 = array();

	$query = $dbConnection->prepare("SELECT PM.*,cat.CategoryName FROM ProductMaster AS PM INNER JOIN Category AS cat ON cat.PkId=PM.PkId_Category WHERE PM.DeleteStatus=? AND PM.Micron=? AND PM.ProductName=?");
	$query->execute(array($delete_status,$Micron,$ProductName));
	$num_rows = $query->rowCount();
	$a = "1";
	if($num_rows>0)
	{	
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


			$query8 = $dbConnection->prepare("SELECT PkId,ProductSize,UniqueRollNo,Quantity,ProductId_ProductMaster,Remarks FROM InventoryMaster WHERE ProductId_ProductMaster=? AND DeleteStatus=? AND Quantity>0 ORDER BY PkId DESC LIMIT $first,$itemsPerPage");
			$query8->execute(array($ProductId,$delete_status));
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

				$data2[] = array(
				"InvPkId"=>$row8['PkId'],
				"ProductSize"=>$row8['ProductSize'],
				"UniqueRollNo"=>$row8['UniqueRollNo'],
				"Remarks" => $row8['Remarks'],
				"Quantity"=>$row8['Quantity'],
				"ProductName"=>$ProductName,
				"TotalProductName"=>$TotalProductName,
				"Micron"=>$Micron,
				"Unit"=>$Unit,
			);
			}

			$sql = $dbConnection->prepare("SELECT COALESCE(SUM(Quantity), 0) AS TotalQty, COUNT(PkId) AS Total FROM InventoryMaster WHERE ProductId_ProductMaster=? AND DeleteStatus=? AND Quantity>0");
	    	$sql->execute(array($ProductId,$delete_status));
	        $row4 = $sql->fetch();
	        $Total = $row4['Total'];
	        $TotalQty = $row4['TotalQty'];
	    	$data3 = array("Total"=>$Total,"TotalQty"=>$TotalQty,"data1"=>$data2);
	    	echo json_encode($data3);
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

	}
	else
	{
		echo "NoData";
	}
}
?>