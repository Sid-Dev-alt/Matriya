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
	$invtype = $data->invtype;
	$data1 = array();
	$query = $dbConnection->prepare("SELECT * FROM ProductMaster WHERE DeleteStatus=? AND InventoryType=?");
	$query->execute(array($delete_status,$invtype));
	$num_rows = $query->rowCount();
	$a = "1";
	if($num_rows>0)
	{	
		while($rows = $query->fetch())
		{
			$PkId = $rows['PkId'];
			$ProductId = $rows['ProductId'];
			$ProductName = $rows['ProductName'];
			$PkId_Category = $rows['PkId_Category'];
			
			$query1 = $dbConnection->prepare("SELECT SUM(Quantity) AS AvlQty FROM InventoryMaster WHERE ProductId_ProductMaster=? AND DeleteStatus=?");
			$query1->execute(array($ProductId,$delete_status));
			$row = $query1->fetch();
			$AvlQty = $row['AvlQty'];

			// $query1 = $dbConnection->prepare("SELECT SUM(Weight) AS WeightofRaw FROM Productions WHERE ProductId_ProductMaster=? AND DeleteStatus=?");
			// $query1->execute(array($ProductId,$delete_status));
			// $row = $query1->fetch();
			// $WeightofRaw = $row['WeightofRaw'];



			$data1[] = array("PkId"=>$PkId,"ProductId"=>$ProductId,
				"ProductName"=>$ProductName,
				"AvlQty"=>$AvlQty,
				"Status"=>$rows['Status'],"displaystatus"=>$displaystatus);
		}

		echo (json_encode($data1));
	}
	else
	{
		echo "NoData";
	}
}
?>