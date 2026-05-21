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
	//$category = $data->category;
$data1 = array();
$data2 = array();
	$query = $dbConnection->prepare("SELECT PkId,ProductId,Micron,ProductName,Unit FROM ProductMaster WHERE DeleteStatus=? GROUP BY Micron,ProductName");
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
			//$ProductSize = $rows['Size'];
			$Micron = $rows['Micron'];
			$Unit = $rows['Unit'];


			if($Micron=="" || $Micron=="NA")
			{
				$TotalProductName= "$ProductName";	
			}
			else
			{
				$TotalProductName= "$Micron $ProductName";
			}
			

			$query2 = $dbConnection->prepare("SELECT ProductSize FROM InventoryMaster WHERE DeleteStatus=? AND ProductId_ProductMaster=? GROUP BY ProductSize");
			$query2->execute(array($delete_status,$ProductId));
			if($query2->rowCount()>0)
			{
				while($rows2 = $query2->fetch())
				{
					$data2[] = array("ProductSize"=>$rows2['ProductSize']);
				}
			}
			$data1[] = array("PkId"=>$PkId,"ProductId"=>$ProductId,
				"Micron"=>$Micron,
				"ProductName"=>$ProductName,
				"Unit"=>$Unit,
				"data2"=>$data2,"TotalProductName"=>$TotalProductName
			);
			unset($data2);
		}

		echo json_encode($data1);
	}
	else
	{
		echo "NoData";
	}
}
?>