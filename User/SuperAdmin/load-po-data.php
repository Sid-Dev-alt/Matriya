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
	$data1 = array();
	$data2 = array();	
	$query = $dbConnection->prepare("SELECT po.PkId,po.POrderId,po.PkId_SupplierMaster,po.PODate,po.Total,po.POStatus,po.RequiredBy,po.Comments,sm.PersonName,sm.ShopName FROM POMaster AS po INNER JOIN SupplierMaster AS sm ON po.PkId_SupplierMaster=sm.PkId WHERE po.DeleteStatus=? ORDER BY po.PkId DESC");
	$query->execute(array($delete_status));
	$num_rows = $query->rowCount();
	if($num_rows>0)
	{	
		while($rows = $query->fetch())
		{
			$PkId = $rows['PkId'];
			$POrderId = $rows['POrderId'];
			$PODate = $rows['PODate'];
			$PkId_SupplierMaster = $rows['PkId_SupplierMaster'];
			$Total = $rows['Total'];
			$PersonName = $rows['PersonName'];
			$ShopName = $rows['ShopName'];
			$POrderStatus = $rows['POStatus'];
			if($POrderStatus=="0"){
				$postatus = "Pending / Not Received";
			}else{
				$postatus = "Received";
			}
			$RequiredBy = $rows['RequiredBy'];
			$Comments = $rows['Comments'];
			


			$query1 = $dbConnection->prepare("SELECT pod.*,pt.ProTypeName,bm.BrandName FROM PODetails AS pod INNER JOIN ProductTypes  AS pt ON pt.PkId=pod.PkId_ProductTypes INNER JOIN BrandMaster AS bm ON bm.PkId=pod.PkId_BrandMaster  WHERE pod.DeleteStatus=? AND pod.POrderId_POMaster=?");
			$query1->execute(array($delete_status,$POrderId));
			while($row1 = $query1->fetch()){
				$data2[] = array("ArrId"=>$row1['PkId'],
					"product"=>$row1['PkId_ProductTypes'],
					"ProTypeName"=>$row1['ProTypeName'],
					"brand"=>$row1['PkId_BrandMaster'],
					"BrandName"=>$row1['BrandName'],
					"prosize"=>$row1['Size'],
					"procolour"=>$row1['Colour'],
					"price"=>$row1['Price'],
					"quantity"=>$row1['Quantity']);
			}
			// if($rows['CreatedTime']=="0000-00-00" || $rows['CreatedTime']=="")
			// {
			// 	$ct = "";
			// }
			// else
			// {
			// 	$ct = date("d-m-Y", strtotime($rows['CreatedTime']));
			// }
			
			$data1[] = array("PkId"=>$PkId,"POrderId"=>$POrderId,"PODate"=>$PODate,"PkId_SupplierMaster"=>$PkId_SupplierMaster,"PersonName"=>$PersonName,"ShopName"=>$ShopName,"POrderStatus"=>$POrderStatus,"postatus"=>$postatus,"Total"=>$Total,"RequiredBy"=>$RequiredBy,"Comments"=>$Comments,"data2"=>$data2);
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