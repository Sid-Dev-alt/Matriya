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
			$state = $data->state;

			$delete_status = 1;
			$data1 = array();  
				
			$query = $dbConnection->prepare("SELECT * FROM Districts WHERE DeleteStatus=? AND StateName=?");
			$query->execute(array($delete_status,$state));
			$num_rows = $query->rowCount();
			if($num_rows>0)
			{	
				while($rows = $query->fetch())
				{
					$PkId = $rows['PkId'];
					$DistName = $rows['DistName'];			

					$data1[] = $DistName;
				}
				echo json_encode($data1);
			}	
			else
			{
				echo "NoData";
			}
}
?>