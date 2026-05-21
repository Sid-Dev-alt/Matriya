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
		 
		 $category1 = ltrim($data->catname);
		 $catdescription = $data->catdescription;
		 $category = rtrim($category1);
		//  $KgArrPkId = json_decode($_REQUEST['KgArrPkId']);
		// $name = json_decode($_REQUEST['name']);
		// //print_r($name);
		//  $Type = json_decode($_REQUEST['Type']);
		//  $price = json_decode($_REQUEST['price']);
		//  $status = json_decode($_REQUEST['status']);


		if($category!="")
		{
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
			$SAId = $_SESSION['EmpId'];
			$DATEIME = GetDateTime();	
			$delete_status=1;
			$check = 0;
			$Errors .= "";
			$query1 = $dbConnection->prepare("SELECT CategoryName FROM Category WHERE CategoryName=? AND  DeleteStatus=?");
			$query1->execute(array($category,$delete_status));
			if($query1->rowCount()>0)
			{
				$check = 1;
				$Errors .= "Category Name already exists"."\n";
			}

			if($check==1){
			echo $Errors;
			}
			else 
			{

				$query = $dbConnection->prepare("INSERT INTO Category (CategoryName,CreatedTime) VALUES (?,?)");
				$query->execute(array($category,$DATEIME));
				$PkId = $dbConnection->lastInsertId();

				$Post = "Created Category:$category as on $DATEIME by $SAId";
				$Type="Category";		
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
	else
	{
		echo "<script language=\"javascript\">window.location=\"product-types.php\";</script>";
	}
}	 
?>
