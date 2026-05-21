<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{	
	if($_SERVER["REQUEST_METHOD"]==="POST")
	{
		$data = json_decode(file_get_contents("php://input"));	
		$FormPkId  = $data->FormPkId ;	
		$size = $data->size;	
		if(isset($FormPkId) && $size!="")
		{
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
			$SAId = $_SESSION['EmpId'];
			$DATEIME = GetDateTime();	
			$delete_status=1;	

			$query = $dbConnection->prepare("UPDATE SizeMaster SET Size=? WHERE PkId=? AND DeleteStatus=?");
			$query->execute(array($size,$FormPkId,$delete_status));

				$Post = "$SAId UPDATED  SizeMaster (Size: $size) as on $DATEIME";
				$Type="SizeMaster";		
				$query2 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
				$query2->execute(array($SAId,$Post,$Type,$FormPkId,$DATEIME));

				$result= "Success";
				echo $result;
		}
		else
		{
			$result= "Invalid Data";
			echo $result;
		}
	}
	else
	{
		echo "<script language=\"javascript\">window.location=\"list-brands.php\";</script>";		
	}
}
?>