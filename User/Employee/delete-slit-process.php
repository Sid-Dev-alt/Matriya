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
		 
		$FormPkId = $data->FormPkId;
		$SlitId = $data->SlitId;
		$PkId_RawPurchaseMasterDetails = $data->PkId_RawPurchaseMasterDetails;
		$PkId_InventoryMaster = $data->PkId_InventoryMaster;
		$UniqueRollNo = $data->UniqueRollNo;
		$PurchaseQty = $data->PurchaseQty;
		
		
		$delete_status = 1;			
		
	    if($check==1){
		echo $Errors;
		}
		if($check==0)
		{
			$check = 0;
			$Errors .= "";
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
			$SAId = $_SESSION['EmpId'];
			$DATEIME = GetDateTime();
			$todaydt = GetTDate();

			//set Invoice delete
			$query = $dbConnection->prepare("UPDATE Slitting SET DeleteStatus=? WHERE SlitId=?");
			$query->execute(array('0',$SlitId));

			$query1 = $dbConnection->prepare("UPDATE  SlittingRolls SET DeleteStatus=? WHERE SlitId_Slitting=?");
			$query1->execute(array('0',$SlitId));	

				//update fresh quantity
				$query11 = $dbConnection->prepare("UPDATE InventoryMaster SET DeleteStatus=?,Quantity=?,IsSplitQty=? WHERE PkId=?");
				$query11->execute(array('0',$PurchaseQty,'0',$PkId_InventoryMaster));

				//set Invoice_Details delete
				$query4 = $dbConnection->prepare("UPDATE RawPurchaseMasterDetails SET IsSplitQty=? WHERE RollNo=?");
				$query4->execute(array('0',$UniqueRollNo));
			}

			$Post = "Deleted Slitting as on $DATEIME";
			$Type="Slitting";		
			$query14 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
			$query14->execute(array($SAId,$Post,$Type,$SlitId,$DATEIME));

			echo $result= "Success";
}	 
?>