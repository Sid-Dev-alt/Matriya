<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{		

	$data = json_decode(file_get_contents("php://input")); 
	$entrydate = $data->entrydate;
	$slitid = $data->slitid;
	$InvPkId = $data->InvPkId;
	$PkId_RawPurchaseMasterDetails = $data->PkId_RawPurchaseMasterDetails;
	$ProductId = $data->ProductId;
	$ProductName = $data->ProductName;
	$GoDownName = $data->GoDownName;
	$Micron = $data->Micron;
	$Size = $data->Size;
	$UniqueRollNo = $data->UniqueRollNo;
	$Avlqty = $data->Avlqty;
	// $splitsize = $data->splitsize;
	// $subquantity = $data->subquantity;
	$remarks = $data->remarks;
	//$sumbasictotal = $data->sumbasictotal;
	$Verions = $data->Verions;
	//print_r($Verions);


	$delete_status = 1;
	$check = 0;
	$Errors .= "";

	if($entrydate=="")
	{
		$check = 1;
		$Errors .= "Date is required"."\n";
	}
	if($slitid=="")
	{
		$check = 1;
		$Errors .= "Slitting id is required"."\n";
	}
	if($InvPkId=="")
	{
		$check = 1;
		$Errors .= "Please select roll"."\n";
	}
	/*if($Size!=$sumbasictotal)
	{
		$check = 1;
		$Errors .= "Sum of Sizes should match with the Actual Size"."\n";
	}*/
	
    if($check==1){
	echo $Errors;
	}
	if($check==0)
	{
		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";
		$entrydate = date("Y-m-d", strtotime($entrydate));
		$Dt = date("d", strtotime($entrydate));
		$Month = date("m", strtotime($entrydate));
		$DtMonth = $Dt."/".$Month;
		$SAId = $_SESSION['UserId'];
		$DATEIME = GetDateTime();
		$todaydt = GetTDate();

		$query10 = $dbConnection->prepare("SELECT * FROM ProductMaster WHERE ProductId=?");
		$query10->execute(array($ProductId));
		$row10 = $query10->fetch();
		$PkId_Category = $row10['PkId_Category'];
		$ProductUnit = $row10['Unit'];

		$query8 = $dbConnection->prepare("INSERT INTO Slitting (SlitId,SlitDate,PkId_InventoryMaster,Remarks,UserId_Users,CreatedTime) VALUES (?,?,?,?,?,?)");
		$query8->execute(array($slitid,$entrydate,$InvPkId,$remarks,$SAId,$DATEIME));
		$FormPkId = $dbConnection->lastInsertId();
		foreach ($Verions as $index => $value1) {
			# code...
			//print_r($Verions[$index]);
			$setid = $index+1;
			foreach ($Verions[$index]->BatchArr as $key => $value) {
			# code...
				$subquantity = $Verions[$index]->BatchArr[$key]->quantity;
				$remarks = $Verions[$index]->BatchArr[$key]->remarks;
				$splitsize = $Verions[$index]->BatchArr[$key]->splitsize;
				
				$query2 = $dbConnection->prepare("SELECT RollId FROM SlittingRolls WHERE TDate=? AND DeleteStatus=?  ORDER BY PkId DESC LIMIT 0,1");
					$query2->execute(array($entrydate,$delete_status));
					if($query2->rowCount()>0)
					{
						$row2 = $query2->fetch();
						$variable = $row2['RollId'] + 1;
						$length = strlen($variable);
						if(strlen($variable)<3)
						{
							switch($length)
							{
								case 2:
								$variable = "0".$variable;
								break;
								case 1:
								$variable = "00".$variable;
								break;
							}
						}
						$RollId = $variable;
					}
					else
					{
						$RollId = "001";
					}
					$str2 = substr($slitid, 3); 
					$totalroll = $DtMonth."|"."S".$str2."|".$RollId;


				$query222 = $dbConnection->prepare("INSERT INTO SlittingRolls (SlitId_Slitting,TDate,ProductId_ProductMaster,SetId,SplitSize,RollId,RollNo,SlitQty,Remarks,CreatedTime) VALUES (?,?,?,?,?,?,?,?,?,?)");
				$query222->execute(array($slitid,$entrydate,$ProductId,$setid,$splitsize,$RollId,$totalroll,$subquantity,$remarks,$DATEIME));

				$query101 = $dbConnection->prepare("INSERT INTO InventoryMaster (PkId_RawPurchaseMasterDetails,ProductId_ProductMaster,ProductSize,UniqueRollNo,Quantity,CreatedTime) VALUES (?,?,?,?,?,?)");
				$query101->execute(array($PkId_RawPurchaseMasterDetails,$ProductId,$splitsize,$totalroll,$subquantity,$DATEIME));
				//}
				
				$Post = "Created Slitting with (Slitting(SlitId: $slitid,SlitDate: $entrydate,PkId_InventoryMaster: $InvPkId,Remarks: $remarks)) and (SlittingRolls(SlitId_Slitting: $slitid,TDate: $entrydate,ProductId_ProductMaster: $ProductId,SetId: $setid,SplitSize: $splitsize,RollId: $RollId,RollNo: $totalroll,SlitQty: $subquantity,Remarks: $remarks)) and (InventoryMaster(PkId_RawPurchaseMasterDetails: $PkId_RawPurchaseMasterDetails)) as on $DATEIME by $SAId";
				$Type="Slitting";		
				$query25 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
				$query25->execute(array($SAId,$Post,$Type,$FormPkId,$DATEIME));
			}
		}

		$setzero = "0.000";
		$query9 = $dbConnection->prepare("UPDATE InventoryMaster SET Quantity=?,IsSplitQty=? WHERE PkId=?");
		$query9->execute(array($setzero,$delete_status,$InvPkId));
		
		$query13 = $dbConnection->prepare("UPDATE RawPurchaseMasterDetails SET IsSplitQty=? WHERE RollNo=?");
		$query13->execute(array($delete_status,$UniqueRollNo));
		
		

		echo $result= "Success";
	}
}	 
?>
