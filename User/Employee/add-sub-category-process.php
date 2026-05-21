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
		 
		 $category = $data->catname;
		 $subcatname = ltrim($data->subcatname);
		 $subcatname = rtrim($subcatname);

		//  $KgArrPkId = json_decode($_REQUEST['KgArrPkId']);
		// $name = json_decode($_REQUEST['name']);
		// //print_r($name);
		//  $Type = json_decode($_REQUEST['Type']);
		//  $price = json_decode($_REQUEST['price']);
		//  $status = json_decode($_REQUEST['status']);


		if($category!="" && $subcatname!="")
		{
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
			$SAId = $_SESSION['EmpId'];
			$DATEIME = GetDateTime();
			$delete_status=1;
			$check = 0;
			$Errors .= "";
			$query1 = $dbConnection->prepare("SELECT SubCategoryName FROM SubCategoryMaster WHERE SubCategoryName=? AND PkId_CategoryMaster=? AND DeleteStatus=?");
			$query1->execute(array($subcatname,$category,$delete_status));
			if($query1->rowCount()>0)
			{
				$check = 1;
				$Errors .= "SubCategory Name already exists"."\n";
			}

			if($check==1){
			echo $Errors;
			}
			else 
			{
				

				$query = $dbConnection->prepare("INSERT INTO SubCategoryMaster (PkId_CategoryMaster,SubCategoryName,CreatedTime) VALUES (?,?,?)");
				$query->execute(array($category,$subcatname,$DATEIME));
				$PkId = $dbConnection->lastInsertId();

				$Post = "$SAId Created SubCategoryMaster (SubCategoryName: $subcatname) as on $DATEIME";
				$Type="SubCategory";		
				$query2 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
				$query2->execute(array($SAId,$Post,$Type,$PkId,$DATEIME));

				$result= "Success";
				echo $result;
			}
				
		}
		else
		{
			$result= "Enter Mandatory Fields";
			echo $result;
		}
}	 
?>