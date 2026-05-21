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
			$catname = $data->catname;
			$subcatname = $data->subcatname;
			$lvl2subcatname	 = $data->lvl2subcatname;
			$delete_status = 1;
				
			if($FormPkId=="")	
			{
				$query = $dbConnection->prepare("SELECT Level2SCName FROM Level2SubCategoryMaster WHERE Level2SCName=? AND PkId_CategoryMaster=? AND PkId_SubCategoryMaster=? AND DeleteStatus=?");
				$query->execute(array($lvl2subcatname,$catname,$subcatname,$delete_status));
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
				$query = $dbConnection->prepare("SELECT Level2SCName FROM Level2SubCategoryMaster WHERE Level2SCName=? AND PkId_CategoryMaster=? AND PkId_SubCategoryMaster=? AND DeleteStatus=? AND PkId!=?");
				$query->execute(array($lvl2subcatname,$catname,$subcatname,$delete_status,$FormPkId));
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