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
	$data1 = array();
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";

	$query8 = $dbConnection->prepare("SELECT ProductId,ProductName,ReorderPoint,SalesPrice FROM ProductMaster WHERE DeleteStatus=? AND Status=?");
	$query8->execute(array($delete_status,$delete_status));
	if($query8->rowCount()>0)
	{
	while($rows8 = $query8->fetch())
	{
		$ProductId = $rows8['ProductId'];
		$ProductName = $rows8['ProductName'];
		$ReorderPoint = $rows8['ReorderPoint'];

		if($rows8['ReorderPoint']!="")
		{
			$ReorderPoint = $rows8['ReorderPoint'];
		
			$query9 = $dbConnection->prepare("SELECT SUM(Quantity) AS AvlQty FROM InventoryMaster WHERE DeleteStatus=? AND ProductId_ProductMaster=?");
			$query9->execute(array($delete_status,$ProductId));
			$rows9 = $query9->fetch();
			 $AvlQty = $rows9['AvlQty'];
			 // echo $AvlQty."<br>";
			 // echo $ReorderPoint."<br>";
			if($AvlQty<=$ReorderPoint)
			{

				$data1[] = array(
					'PkId' => $ProductId,
					'ProductName' => $ProductName,
					'AvlQty' => $AvlQty,
					'ReorderPoint' => $ReorderPoint
				);
			}

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