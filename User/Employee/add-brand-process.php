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
		 $brand = $data->brand;

		if($brand!="")
		{
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
			$SAId = $_SESSION['EmpId'];
			$DATEIME = GetDateTime();		

			$query = $dbConnection->prepare("INSERT INTO BrandMaster (BrandName,CreatedTime) VALUES (?,?)");
			$query->execute(array($brand,$DATEIME));
			$PkId = $dbConnection->lastInsertId();

				$Post = "$SAId Created BrandName (BrandName: $protype) as on $DATEIME";
				$Type="Brand";		
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
		echo "<script language=\"javascript\">window.location=\"product-types.php\";</script>";
	}
}	 
?>