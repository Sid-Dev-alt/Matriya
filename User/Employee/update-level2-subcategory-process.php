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
	$FormPkId  = $data->FormPkId;
	 $category = $data->catname;
	 $subcatname = $data->subcatname;
	 $lvl2subcatname = $data->lvl2subcatname;

	if($FormPkId!="" && $category!="" && $subcatname!="" && $lvl2subcatname!="")
	{
		
		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";
		$SAId = $_SESSION['EmpId'];
		$DATEIME = GetDateTime();	
		$delete_status=1;	

			$query = $dbConnection->prepare("UPDATE Level2SubCategoryMaster SET PkId_CategoryMaster=?,PkId_SubCategoryMaster=?,Level2SCName=? WHERE PkId=? AND DeleteStatus=?");
			$query->execute(array($category,$subcatname,$lvl2subcatname,$FormPkId,$delete_status));
	
			$Post = "$SAId UPDATED  SubCategory (SubCategoryName: $lvl2subcatname) as on $DATEIME";
			$Type="Level-2 SubCategory";		
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