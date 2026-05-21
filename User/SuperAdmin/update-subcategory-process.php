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
	$FormPkId  = $data->FormPkId;
	 $category = $data->catname;
	 $subcatname = $data->subcatname;

	if($FormPkId!="" && $category!="" && $subcatname!="")
	{
		
		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";
		$SAId = $_SESSION['UserId'];
		$DATEIME = GetDateTime();	
		$delete_status=1;	

			$query = $dbConnection->prepare("UPDATE SubCategoryMaster SET PkId_CategoryMaster=?,SubCategoryName=? WHERE PkId=? AND DeleteStatus=?");
			$query->execute(array($category,$subcatname,$FormPkId,$delete_status));
	
			$Post = "$SAId UPDATED  SubCategory (SubCategoryName: $subcatname) as on $DATEIME";
			$Type="SubCategory";		
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
?>
