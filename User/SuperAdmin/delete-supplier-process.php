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
		$SAId = $_SESSION['UserId'];	


		$data = json_decode(file_get_contents("php://input"));
		$PkId = $data->PkId;

		$delete_status = 0;
		$data1 = array(); 
		$trueqty = 1;

		if($PkId!="")	
		{

			$sql2 = $dbConnection->prepare("UPDATE VendorMaster SET DeleteStatus=? WHERE PkId=?");
			$sql2->execute(array($delete_status,$PkId));
			
			$Post = "Deleted Vendor with (PkId: $PkId) as on $DATEIME by $SAId";
				$Type="Vendor";		
				$query2 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
				$query2->execute(array($SAId,$Post,$Type,$PkId,$DATEIME));

			echo "Success";
		}	
		else
		{
		echo "Invalid";
		}
}
?>
