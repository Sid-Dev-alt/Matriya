<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{	
	
	if($_SERVER["REQUEST_METHOD"]==="POST")
	{

		$data = json_decode(file_get_contents("php://input")); 
		 $size = $data->size;

		if($size!="")
		{
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
			$SAId = $_SESSION['UserId'];
			$DATEIME = GetDateTime();		

			$query = $dbConnection->prepare("INSERT INTO SizeMaster (Size,CreatedTime) VALUES (?,?)");
			$query->execute(array($size,$DATEIME));
			$PkId = $dbConnection->lastInsertId();

				$Post = "$SAId Created Size (Size: $size) as on $DATEIME";
				$Type="SizeMaster";		
				$query2 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
				$query2->execute(array($SAId,$Post,$Type,$PkId,$DATEIME));

				$result= "Success";
				echo $result;
					
		}
		else
		{
			$result= "Enter Mandatory Fields";
			echo $result;
		}
	}
	else
	{
		echo "<script language=\"javascript\">window.location=\"list-sizes.php\";</script>";
	}
}	 
?>