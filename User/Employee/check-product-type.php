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
		
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
					
			$SAId = $_SESSION['EmpId'];			
			$data = json_decode(file_get_contents("php://input"));
			$FormPkId = $data->FormPkId;
			 $category = $data->category;
			 $subcategory = $data->subcategory;
			 $lvl2subcat = $data->lvl2subcat;
			 $lvl3subcat = $data->lvl3subcat;
			$protype = $data->protype;
			$delete_status = 1;
				
			if($FormPkId=="")	
			{
				$query = $dbConnection->prepare("SELECT ProductName FROM ProductMaster WHERE PkId_Category=? AND PkId_SubCategoryMaster=? AND PkId_Level2SubCategoryMaster=? AND PkId_Level3SubCategoryMaster=? AND ProductName=? AND DeleteStatus=?");
				$query->execute(array($category,$subcategory,$lvl2subcat,$lvl3subcat,$protype,$delete_status));
				//echo $query->rowCount();
				if($query->rowCount()>0)
				{			
					$result= "Already exists";
					echo $result;		
				}
				else
				{
					 
					$result= "OK";
					echo $result;		
				}
			}
			else
			{
				$query = $dbConnection->prepare("SELECT ProductName FROM ProductMaster WHERE PkId_Category=? AND PkId_SubCategoryMaster=?  AND PkId_Level2SubCategoryMaster=? AND PkId_Level3SubCategoryMaster=? AND ProductName=? AND DeleteStatus=? AND PkId!=?");
				$query->execute(array($category,$subcategory,$lvl2subcat,$lvl3subcat,$protype,$delete_status,$FormPkId));
				if($query->rowCount()>0)
				{			
					$result= "Already exists";
					echo $result;
				}
				else
				{
					$result= "OK";
					echo $result;	
				}
			}
			
		}
		else
		{
			echo "Invalid Data";
			echo $result;
		}
}
?>