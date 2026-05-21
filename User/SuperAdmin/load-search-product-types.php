<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{
	include_once "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";

	$data = json_decode(file_get_contents("php://input"));
	$delete_status = 1;	

	$itemsPerPage = $data->itemsPerPage;
	$pagenumber = $data->pagenumber;
	$first = ($pagenumber-1)*$itemsPerPage;
	$searchmicron = $data->searchmicron;
	$itemname = $data->itemname;
	
	$final = "";
	if($searchmicron!="")
	{
		$final .= "PM.Micron='$searchmicron' AND ";
	}
	if($itemname!="")
	{
		$final .= "PM.ProductId='$itemname' AND ";
	}
	
	if($final!="")
	{
		$qry .= "" .substr($final, 0, -4);
	}
	else
	{
		$qry = "";
	}
	
	// echo $qry;

	$query = $dbConnection->prepare("SELECT PM.*,cat.CategoryName FROM ProductMaster AS PM LEFT JOIN Category AS cat ON cat.PkId=PM.PkId_Category WHERE PM.DeleteStatus=? AND $qry GROUP BY PM.ProductId ORDER BY PM.PkId DESC LIMIT $first,$itemsPerPage");
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
			$ProductSize = $rows['Size'];
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

			$query11 = $dbConnection->prepare("SELECT Name FROM Users WHERE UserId=?");
			$query11->execute(array($rows['UserId_Users']));
			$row11 = $query11->fetch();
			$UserName = $row11['Name'];

			$query1 = $dbConnection->prepare("SELECT SUM(Quantity) AS AvlQty FROM InventoryMaster WHERE ProductId_ProductMaster=? AND DeleteStatus=?");
			$query1->execute(array($ProductId,$delete_status));
			$num_rows1 = $query1->rowCount();
			if($num_rows1>0)
	        {
    			$row = $query1->fetch();
    			$AvlQty = $row['AvlQty'];
    			if($ProductSize=="" || $ProductSize=="NA")
    			{
    				$TotalProductName= "$Micron $ProductName";	
    			}
    			else
    			{
    				$TotalProductName= "$Micron $ProductName $ProductSize MM";
    			}
	        }
	        else
	        {
    			$AvlQty = "";
    			$TotalProductName= "";
	        }
			

			$query2 = $dbConnection->prepare("SELECT PkId,UniqueRollNo,Quantity,ProductId_ProductMaster FROM InventoryMaster WHERE ProductId_ProductMaster=? AND DeleteStatus=? AND Quantity>0");
			$query2->execute(array($ProductId,$delete_status));
			$num_rows2 = $query2->rowCount();
			if($num_rows2>0)
	        {
    			while($row2 = $query2->fetch())
    			{
    				$data2[] = array("InvPkId"=>$row2['PkId'],"UniqueRollNo"=>$row2['UniqueRollNo'],
    				"Quantity"=>$row2['Quantity']);
    			}
		    }
	        else
	        {
    			$data2[] = "";
	        }

			$data1[] = array("PkId"=>$PkId,"ProductId"=>$ProductId,
				"PkId_Category"=>$PkId_Category,
				"CategoryName"=>$CategoryName,
				"PkId_SubCategoryMaster"=>$PkId_SubCategoryMaster,
				"PkId_Level2SubCategoryMaster"=>$PkId_Level2SubCategoryMaster,
				"PkId_Level3SubCategoryMaster"=>$PkId_Level3SubCategoryMaster,
				"InventoryType"=>$InventoryType,
				"FileName"=>$FileName,
				"ProductName"=>$ProductName,
				"TotalProductName"=>$TotalProductName,
				"AvlQty"=>$AvlQty,
				"Micron"=>$Micron,
				"ProductSize"=>$ProductSize,
				"UserName"=>$UserName,
				"Unit"=>$Unit,
				"Status"=>$rows['Status'],"displaystatus"=>$displaystatus,
				"data2"=>$data2
			);
			unset($data2);
		}
		if($searchmicron!="")
		{
			$sql = $dbConnection->prepare("SELECT PM.*,cat.CategoryName FROM ProductMaster AS PM LEFT JOIN Category AS cat ON cat.PkId=PM.PkId_Category WHERE PM.DeleteStatus=? AND $qry GROUP BY PM.ProductId ORDER BY PM.PkId DESC");
			$sql->execute(array($delete_status));
			$count_rows = $sql->rowCount();
			$Total = $count_rows;
		}
		if($itemname!="")
		{
			$sql = $dbConnection->prepare("SELECT PM.*,cat.CategoryName FROM ProductMaster AS PM LEFT JOIN Category AS cat ON cat.PkId=PM.PkId_Category WHERE PM.DeleteStatus=? AND $qry GROUP BY PM.ProductId ORDER BY PM.PkId DESC");
			$sql->execute(array($delete_status));
			$count_rows = $sql->rowCount();
			$Total = $count_rows;
		}
		
		$data3 = array("Total"=>$Total,"data1"=>$data1);

		echo json_encode($data3);

	}
	else
	{
		echo "NoData";
	}
}
?>
