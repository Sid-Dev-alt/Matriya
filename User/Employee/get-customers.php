<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{		
	$delete_status = 1;	
	
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	$data1 = array();
	$query = $dbConnection->prepare("SELECT * FROM CustomerMaster WHERE DeleteStatus=? ORDER BY PkId DESC");
	$query->execute(array($delete_status));
	$num_rows = $query->rowCount();
	if($num_rows>0)
	{	
		while($row = $query->fetch())
		{
			$CustomerId = $row['CustomerId'];
			$DisplayName = $row['DisplayName'];
			$CompanyName = $row['CompanyName'];
			$CoName = $CompanyName." ( ". $DisplayName ." )";
			$data1[] = array('CustomerId' =>$CustomerId,'DisplayName' =>$DisplayName,'CompanyName' =>$CompanyName,'CoName' =>$CoName);
		}
		echo json_encode($data1);	
	}
	else
	{
		echo "NoData";
	}
}
?>