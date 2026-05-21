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
	$searchpartyid = $data->searchpartyid;
	$partyname = $data->partyname;
	
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	$data1 = array();
	
	$final = "";
	if($searchpartyid!="")
	{
		$final .= "VendorId='$searchpartyid' AND ";
	}
	if($partyname!="")
	{
		$final .= "DisplayName='$partyname' AND ";
	}
	
	if($final!="")
	{
		$qry .= "" .substr($final, 0, -4);
	}
	else
	{
		$qry = "";
	}
	
	$query = $dbConnection->prepare("SELECT * FROM VendorMaster WHERE DeleteStatus=? AND $qry GROUP BY VendorId ORDER BY PkId DESC LIMIT $first,$itemsPerPage");
	$query->execute(array($delete_status));
	$data1 = $query->fetchall();
	
	if($searchpartyid!="")
	{
		$sql = $dbConnection->prepare("SELECT COUNT(PkId) AS Total FROM VendorMaster WHERE DeleteStatus=? AND VendorId='$searchpartyid' GROUP BY VendorId ORDER BY PkId DESC");
		$sql->execute(array($delete_status));
		$row4 = $sql->fetch();
		$Total = $row4['Total'];
	}
	if($partyname!="")
	{
		$sql = $dbConnection->prepare("SELECT * FROM VendorMaster WHERE DeleteStatus=? AND $qry GROUP BY VendorId ORDER BY PkId DESC");
		$sql->execute(array($delete_status));
		$count_rows = $sql->rowCount();
		$Total = $count_rows;
	}
	
	$data3 = array("Total"=>$Total,"data1"=>$data1);
	echo json_encode($data3);
		
}
?>
