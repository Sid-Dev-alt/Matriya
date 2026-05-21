<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{	
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	$DATEIME = GetDateTime();		
	$UserId = $_SESSION['UserId'];	


	$data = json_decode(file_get_contents("php://input"));
	$EntryPkId = $data->EntryPkId;
	$ProductId = $data->ProductId;
	$InvPkId = $data->InvPkId;
	$UniqueRollNo = $data->UniqueRollNo;

	$delete_status = 0;
	$data1 = array(); 
	$trueqty = 1;

	if($EntryPkId!="" && $ProductId!="" && $InvPkId!="" && $UniqueRollNo!="")	
	{
		//$sql = $dbConnection->prepare("SELECT Quantity FROM InvoiceDetails WHERE DeleteStatus=? AND PkId=? AND PkId_InventoryMaster=? AND ProductId_ProductMaster=?");
//		$sql->execute(array('1',$EntryPkId,$InvPkId,$ProductId));
//		$row = $sql->fetch();
		//$Qty = $row['Quantity'];
		
		$sql = $dbConnection->prepare("SELECT Quantity FROM InvoiceDetails WHERE DeleteStatus=? AND PkId=?");
		$sql->execute(array('1',$EntryPkId));
		$row = $sql->fetch();
		$Qty = $row['Quantity'];
		
		$sql1 = $dbConnection->prepare("UPDATE InventoryMaster SET Quantity=?,Status=? WHERE ProductId_ProductMaster=? AND UniqueRollNo=? AND PkId=?");
		$sql1->execute(array($Qty,'1',$ProductId,$UniqueRollNo,$InvPkId));
		
		$sql2 = $dbConnection->prepare("UPDATE InvoiceDetails SET DeleteStatus=? WHERE PkId=?");
		$sql2->execute(array($delete_status,$EntryPkId));
		
		/*$Post = "Deleted Vendor as on $DATEIME";
			$Type="Vendor";		
			$query2 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
			$query2->execute(array($SAId,$Post,$Type,$PkId,$DATEIME));*/

		echo "Success";
	}
}
?>
