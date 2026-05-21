<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{		
	$data = json_decode(file_get_contents("php://input"));
	$delete_status = 1;	

	$itemsPerPage = $data->itemsPerPage;
	$pagenumber = $data->pagenumber;
	$first = ($pagenumber-1)*$itemsPerPage;
	
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	$data1 = array();
	$query = $dbConnection->prepare("SELECT * FROM VendorMaster WHERE DeleteStatus=? ORDER BY PkId DESC LIMIT $first,$itemsPerPage");
	$query->execute(array($delete_status));
		$data1 = $query->fetchall();

	$sql = $dbConnection->prepare("SELECT COUNT(PkId) AS Total FROM VendorMaster WHERE DeleteStatus=?");
	$sql->execute(array($delete_status));
	$row4 = $sql->fetch();
	$Total = $row4['Total'];
	$data3 = array("Total"=>$Total,"data1"=>$data1);
	echo json_encode($data3);

		//echo json_encode($data1);	
}
?>