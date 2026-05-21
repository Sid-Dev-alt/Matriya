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
	$query = $dbConnection->prepare("SELECT pt.PkId,pt.PkId_Category,pt.ProTypeName,pt.SalesIn,pt.AvlStock,pt.VisibleStatus,pt.DeleteStatus,cat.CategoryName FROM ProductTypes AS pt INNER JOIN Category AS cat ON pt.PkId_Category=cat.PkId WHERE pt.DeleteStatus=? AND pt.VisibleStatus=?");
	$query->execute(array($delete_status,$delete_status));
	$num_rows = $query->rowCount();
	$a = "1";
	if($num_rows>0)
	{	
		while($rows = $query->fetch())
		{
			$PkId = $rows['PkId'];
			$ProTypeName = $rows['ProTypeName'];
			$PkId_Category = $rows['PkId_Category'];
			$CategoryName = $rows['CategoryName'];
			$SalesIn = $rows['SalesIn'];
			$AvlStock = $rows['AvlStock'];
			$VisibleStatus = $rows['VisibleStatus'];
			// if($rows['CreatedTime']=="0000-00-00" || $rows['CreatedTime']=="")
			// {
			// 	$ct = "";
			// }
			// else
			// {
			// 	$ct = date("d-m-Y", strtotime($rows['CreatedTime']));
			// }
			$query1 = $dbConnection->prepare("SELECT * FROM ProductTypeDetails WHERE PkId_ProductTypes=? AND DeleteStatus=? AND Status=?");
			$query1->execute(array($PkId,$delete_status,$delete_status));
			while($rows1 = $query1->fetch())
			{
				$data2[] = array("ArrPkId"=>$rows1['PkId'],"KgorQuantity"=>$rows1['KgorQuantity'],"Type"=>$rows1['Type'],"Price"=>$rows1['Price'],"Status"=>$rows1['Status']);
			}


			$data[] = array("PkId"=>$PkId,"PkId_Category"=>$PkId_Category,"CategoryName"=>$CategoryName,"AvlStock"=>$AvlStock,"SalesIn"=>$SalesIn,"VisibleStatus"=>$VisibleStatus,"ProTypeName"=>$ProTypeName,"data2"=>$data2);
			unset($data2);
			$a++;
		}

		echo (json_encode($data));
	}
	else
	{
		echo "NoData";
	}
}
?>