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
		if(isset($_GET))
		{
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
					
			$SAId = $_SESSION['EmpId'];			
			$data = json_decode(file_get_contents("php://input"));
			$FormPkId = $data->FormPkId;
			$category = $data->catname;
			$protype = $data->protype;
			$delete_status = 1;
				
			if($FormPkId=="")	
			{
				$query = $dbConnection->prepare("SELECT CategoryName FROM Category WHERE CategoryName=? AND DeleteStatus=?");
				$query->execute(array($category,$delete_status));
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
				$query = $dbConnection->prepare("SELECT CategoryName FROM Category WHERE CategoryName=? AND DeleteStatus=? AND PkId!=?");
				$query->execute(array($category,$delete_status,$FormPkId));
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
	else
	{
		echo "<script language=\"javascript\">window.location=\"product-types.php\";</script>";
	}
}
?>