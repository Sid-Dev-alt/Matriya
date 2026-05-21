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
		$FormPkId  = $data->FormPkId;

		 $category = $data->catname;
		 $catdescription = $data->catdescription;


		if($FormPkId!="" && $category!="")
		{
			
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
			$SAId = $_SESSION['EmpId'];
			$DATEIME = GetDateTime();	
			$delete_status=1;	

				$query = $dbConnection->prepare("UPDATE Category SET CategoryName=? WHERE PkId=? AND DeleteStatus=?");
				$query->execute(array($category,$FormPkId,$delete_status));
		
				$Post = "$SAId UPDATED  Category (CategoryName: $category) as on $DATEIME";
				$Type="Category";		
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
		echo "<script language=\"javascript\">window.location=\"product-types.php\";</script>";		
	}
}
?>
