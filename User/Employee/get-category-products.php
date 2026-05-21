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
	$mainarray = array();
	$data0 = array();
	$data1 = array();
	$data2 = array();
	include_once "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";

	$data = json_decode(file_get_contents("php://input"));
	$JobPkId = $data->JobPkId;
	$rawcategory = $data->CatPkId;
	$entrydate = date('Y-m-d');
	
		$query1 = $dbConnection->prepare("SELECT ProductId,ProductName FROM ProductMaster WHERE PkId_Category=? AND DeleteStatus=?");
		$query1->execute(array($rawcategory,$delete_status));
		while($row1 = $query1->fetch())
		{
			$Print_ProductId = $row1['ProductId'];
			$ProductName = $row1['ProductName'];

			$query7 = $dbConnection->prepare("SELECT PkId,PrintOutput FROM Printing WHERE PkId_JobMaster=? AND DeleteStatus=?");
			$query7->execute(array($JobPkId,$delete_status));
			while($row7 = $query7->fetch())
			{
				$data2[] = array('PkId' =>$row7['PkId'],'PrintOutput' =>$row7['PrintOutput']);	
			}

			$data1[] = array("ProductId"=>$Print_ProductId,"ProductName"=>$ProductName,"data2"=>$data2);
			//unset($data2);
		}
		echo json_encode($data1);
}
?>