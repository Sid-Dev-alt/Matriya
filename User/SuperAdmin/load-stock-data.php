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
	include_once "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";

	$data = json_decode(file_get_contents("php://input"));
    $data1 = array();
    $data2 = array();
    $AvlQty = 0;
	$query = $dbConnection->prepare("SELECT PM.*,cat.CategoryName FROM ProductMaster AS PM INNER JOIN Category AS cat ON cat.PkId=PM.PkId_Category WHERE PM.DeleteStatus=?");
	$query->execute(array($delete_status));
	$num_rows = $query->rowCount();
	$a = "1";
	if($num_rows>0)
	{	
		while($rows = $query->fetch())
		{
			$PkId = $rows['PkId'];
			$ProductId = $rows['ProductId'];
			$ProductName = $rows['ProductName'];
			$Micron = $rows['Micron'];
			$Unit = $rows['Unit'];
			$PkId_Category = $rows['PkId_Category'];
			$InventoryType = $rows['InventoryType'];
			$PkId_SubCategoryMaster = $rows['PkId_SubCategoryMaster'];
			$PkId_Level2SubCategoryMaster = $rows['PkId_Level2SubCategoryMaster'];
			$PkId_Level3SubCategoryMaster = $rows['PkId_Level3SubCategoryMaster'];
			$CategoryName = $rows['CategoryName'];
			$FileName = $rows['FileName'];
			if($rows['Status']==1)
			{
				$displaystatus = "Active";
			}
			else
			{
				$displaystatus = "Inactive";
			}

			$query1 = $dbConnection->prepare("SELECT COALESCE(SUM(Quantity),0) AS AvlQty FROM InventoryMaster WHERE ProductId_ProductMaster=? AND IsSplitQty=? AND Status=? AND DeleteStatus=?");
			$query1->execute(array($ProductId,'0',$delete_status,$delete_status));
			$rows1 = $query1->fetch();
			$AvlQty = $rows1['AvlQty'];

			$query2 = $dbConnection->prepare("SELECT COUNT(*) AS TotalRolls FROM InventoryMaster WHERE ProductId_ProductMaster=? AND Quantity>0 AND IsSplitQty=? AND Status=? AND DeleteStatus=?");
			$query2->execute(array($ProductId,'0',$delete_status,$delete_status));
			$rows2 = $query2->fetch();
			$TotalRolls = $rows2['TotalRolls'];
			
			$AvlSizeQty = 0;
			$query3 = $dbConnection->prepare("SELECT * FROM InventoryMaster WHERE ProductId_ProductMaster=? AND Quantity>0 AND IsSplitQty=? AND Status=? AND DeleteStatus=? ORDER BY CAST(ProductSize AS unsigned)");
			$query3->execute(array($ProductId,'0',$delete_status,$delete_status));
			while($rows3 = $query3->fetch())
			{
			    $InvPkId = $rows3['PkId'];
			    $UniqueRollNo = $rows3['UniqueRollNo'];
			    $Quantity = $rows3['Quantity'];
			    $ProductSize = $rows3['ProductSize'];
			    /*if($rows3['Remarks']!="" || $rows3['Remarks']!=NULL || $rows3['Remarks']!=null)
			    {
			        $Remarks = $rows3['Remarks'];
			    }
			    else
			    {
			        $query4 = $dbConnection->prepare("SELECT * FROM SlittingRolls WHERE RollNo=? AND DeleteStatus=?");
    				$query4->execute(array($UniqueRollNo,$delete_status));
    				$rows4 = $query4->fetch();
    				$Remarks = $rows4['Remarks'];
			    }*/
			    
				$data2[] = array("InvPkId"=>$InvPkId,"UniqueRollNo"=>$UniqueRollNo,"Quantity"=>$Quantity,"ProductSize"=>$ProductSize,"TotalProductName"=>$TotalProductName);
			}
			
			if($AvlQty>0)
			{
    			$data1[] = array("PkId"=>$PkId,"ProductId"=>$ProductId,"PkId_Category"=>$PkId_Category,"CategoryName"=>$CategoryName,"ProductName"=>$ProductName,"AvlQty"=>$AvlQty,"Micron"=>$Micron,"Unit"=>$Unit,"TotalRolls"=>$TotalRolls,"data2"=>$data2);
    			unset($data2);
			}
		}
		echo (json_encode($data1));
	}
	else
	{
		echo "NoData";
	}
}
?>
