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
		 
		 $category = $data->catname;
		 $subcatname = $data->subcatname;
		 $lvl2subcatname = $data->lvl2subcatname;
		//  $KgArrPkId = json_decode($_REQUEST['KgArrPkId']);
		// $name = json_decode($_REQUEST['name']);
		// //print_r($name);
		//  $Type = json_decode($_REQUEST['Type']);
		//  $price = json_decode($_REQUEST['price']);
		//  $status = json_decode($_REQUEST['status']);


		if($category!="" && $subcatname!="" && $lvl2subcatname!="")
		{
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
			$SAId = $_SESSION['UserId'];
			$DATEIME = GetDateTime();	
			

			$query = $dbConnection->prepare("INSERT INTO Level2SubCategoryMaster (PkId_CategoryMaster,PkId_SubCategoryMaster,Level2SCName,CreatedTime) VALUES (?,?,?,?)");
			$query->execute(array($category,$subcatname,$lvl2subcatname,$DATEIME));
			$PkId = $dbConnection->lastInsertId();

			$Post = "$SAId Created Level2SubCategoryMaster (Level-2 SubCategoryName: $lvl2subcatname) as on $DATEIME";
			$Type="Level-2 SubCategory";		
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
?>