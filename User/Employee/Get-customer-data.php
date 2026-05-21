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
	$CustomerId = $data->CustomerId;

	$delete_status = 1;
	$wh=2; //Store
	$data1 = array();  
	$query = $dbConnection->prepare("SELECT Mobile FROM CustomerMaster WHERE CustomerId=? AND DeleteStatus=?");
	$query->execute(array($CustomerId,$delete_status));
	$num_rows = $query->rowCount();
	if($num_rows>0)
	{
		$row = $query->fetch();
		$mobile = $row['Mobile'];
		$data1 = array('mobile' =>$mobile);
		echo json_encode($data1);
	}
	else
	{
		echo "NoData";
	}
	
}
?>