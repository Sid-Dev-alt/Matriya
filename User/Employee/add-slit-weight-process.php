<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{		
	$data = json_decode(file_get_contents("php://input")); 
	$EntryPkId = $data->EntryPkId;
	$slitid = $data->SlitId;
	$quantity = $data->quantity;
	$RollNo = $data->RollNo;
	$PurchaseQty = $data->PurchaseQty;
	// 	$PkId_RawPurchaseMasterDetails = $data->PkId_RawPurchaseMasterDetails;
	// 	$ProductId = $data->ProductId;
	// 	$ProductName = $data->ProductName;
	// 	$GoDownName = $data->GoDownName;
	// 	$Micron = $data->Micron;
	// 	$Size = $data->Size;
	// 	$UniqueRollNo = $data->UniqueRollNo;
	// 	$Avlqty = $data->Avlqty;
	// 	$splitsize = $data->splitsize;
	// 	$subquantity = $data->subquantity;
	// 	$remarks = $data->remarks;
	//  $sumbasictotal = $data->sumbasictotal;
	$delete_status = 1;
	$check = 0;
	$Errors .= "";
	if($EntryPkId=="")
	{
		$check = 1;
		$Errors .= "Invalid Entry"."\n";
	}
	if($slitid=="")
	{
		$check = 1;
		$Errors .= "Slitting id is required"."\n";
	}
	if($quantity=="0.000" || $quantity=="0.00" || $quantity=="0")
	{
		$check = 1;
		$Errors .= "quantity is required"."\n";
	}
	
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	// $entrydate = date("Y-m-d", strtotime($entrydate));
	// $Dt = date("d", strtotime($entrydate));
	// $Month = date("m", strtotime($entrydate));
	// $DtMonth = $Dt."/".$Month;
	$SAId = $_SESSION['EmpId'];
	$DATEIME = GetDateTime();
	$todaydt = GetTDate();

	$query4 = $dbConnection->prepare("SELECT SUM(SlitQty) AS TotalSlitQty FROM SlittingRolls WHERE SlitId_Slitting=? AND DeleteStatus=?");
	$query4->execute(array($slitid,$delete_status));
	$row4 = $query4->fetch();
	$TotalSlitQty = $row4['TotalSlitQty'];
	if($PurchaseQty==$quantity)
	{
		$check = 1;
		$Errors .= "quantity should be less than purchase qty "."\n";
	}

	$query5 = $dbConnection->prepare("SELECT SUM(SlitQty) AS ExpTotalSlitQty FROM SlittingRolls WHERE  SlitId_Slitting=? AND DeleteStatus=? AND PkId!=?");
	$query5->execute(array($slitid,$delete_status,$EntryPkId));
	$row5 = $query5->fetch();
	//echo $row5['ExpTotalSlitQty'];
	$FreshTotal = $row5['ExpTotalSlitQty']+$quantity;
	// if($FreshTotal>$PurchaseQty)
	// {
	// 	$check = 1;
	// 	$Errors .= "You cannot add more than left over qty to the actual qty"."\n";
	// }
	// $query3 = $dbConnection->prepare("SELECT SlitQty FROM SlittingRolls WHERE PkId=?");
	// $query3->execute(array($EntryPkId));
	// $row3 = $query3->fetch();
	// $OldSlitQty = $row3['SlitQty'];
	// if($quantity>$OldSlitQty)
	// {
	// 	$fresh = $OldSlitQty-$quantity;
	// }
	// else if($quantity<$OldSlitQty)
	// {
	// 	$fresh = $OldSlitQty-$quantity;
	// }

    if($check==1){
	echo $Errors;
	}
	if($check==0)
	{
		
		$query2 = $dbConnection->prepare("UPDATE SlittingRolls SET SlitQty=? WHERE PkId=?");
		$query2->execute(array($quantity,$EntryPkId));

		$query9 = $dbConnection->prepare("UPDATE InventoryMaster SET Quantity=? WHERE UniqueRollNo=?");
		$query9->execute(array($quantity,$RollNo));

		$Post = "Added Slit Weight with (SlittingRolls (SlitQty: $quantity)) as on $DATEIME by $SAId";
		$Type="SlitWeight";		
		$query25 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
		$query25->execute(array($SAId,$Post,$Type,$EntryPkId,$DATEIME));

		echo $result= "Success";
	}
}	 
?>
