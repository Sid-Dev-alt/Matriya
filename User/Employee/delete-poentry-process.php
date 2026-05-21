<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{	
	
		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";
		$DATEIME = GetDateTime();		
		$UserId = $_SESSION['EmpId'];	

		$data = json_decode(file_get_contents("php://input"));
		$EntryPkId = $data->EntryPkId;
		$Micron = $data->Micron;
		$ProductName = $data->ProductName;
		$ProductSize = $data->ProductSize;

		$delete_status = 1;
		$data1 = array(); 
		$trueqty = 1;

		if($EntryPkId!="")	
		{
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
			// $entrydate = date('Y-m-d',strtotime($entrydate));
		 	//  $shipdate = date('Y-m-d', strtotime($shipdate));

			$sql3 = $dbConnection->prepare("SELECT * FROM RawPurchaseMasterDetails WHERE IsSplitQty=? AND PkId=?");
			$sql3->execute(array($delete_status,$EntryPkId));
			//echo $sql3->rowCount();
			if($sql3->rowCount()>0)
			{
				echo "Item has already been slit. You can't delete now";
			}
			else
			{
				$query12 = $dbConnection->prepare("SELECT PkId FROM InventoryMaster WHERE PkId_RawPurchaseMasterDetails=?");
				$query12->execute(array($EntryPkId));
				$row12 = $query12->fetch();
				$InvPkId = $row12['PkId'];

				$query12 = $dbConnection->prepare("SELECT PkId FROM InvoiceDetails WHERE PkId_InventoryMaster=?");
				$query12->execute(array($InvPkId));
				if($query12->rowCount()>0)
				{
					echo "Item has already dispatched. You can't delete now";
				}
				else
				{
					$sql4 = $dbConnection->prepare("UPDATE RawPurchaseMasterDetails SET DeleteStatus=? WHERE PkId=?");
					$sql4->execute(array('0',$EntryPkId));

					$sql5 = $dbConnection->prepare("UPDATE InventoryMaster SET DeleteStatus=? WHERE PkId_RawPurchaseMasterDetails=?");
					$sql5->execute(array('0',$EntryPkId));

					$Post = "Deleted PO Entry as on $DATEIME";

					$Type="Delete PO Entry";		
					$query2 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
					$query2->execute(array($SAId,$Post,$Type,$EntryPkId,$DATEIME));
					echo "Success";
				}
			}
			
				
		}	
		else
		{
		echo "Invalid";
		}
}
?>