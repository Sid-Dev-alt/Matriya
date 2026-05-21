<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{	
	
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
					
			$UserId = $_SESSION['EmpId'];		

			$data = json_decode(file_get_contents("php://input"));


			$delete_status = 1;
			$data1 = array();  
				
			$query = $dbConnection->prepare("SELECT * FROM States WHERE DeleteStatus=?");
			$query->execute(array($delete_status));
			$num_rows = $query->rowCount();
			if($num_rows>0)
			{	
				while($rows = $query->fetch())
				{
					$PkId = $rows['PkId'];
					$StateName = $rows['StateName'];			

					$data1[] = $StateName;
					$a++;
				}
				echo (json_encode($data1));
			}	
			else
			{
				echo "Invalid Data";
				echo $result;
			}
}
?>