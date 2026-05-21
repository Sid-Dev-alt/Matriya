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
	$data2 = array();
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	$VPId = $_SESSION['EmpId'];
	$data = json_decode(file_get_contents("php://input"));
 	$entrydate = date("Y-m-d", strtotime($data->entrydate));
	$paymentterms = $data->paymentterms;
	if($entrydate!="" && $paymentterms!="")
	{
		$query3 = $dbConnection->prepare("SELECT * FROM PaymentTerms WHERE DeleteStatus=? AND TermName=?");
		$query3->execute(array($delete_status,$paymentterms));
		$row3 = $query3->fetch();
		
			$NoofDays=$row3['NoofDays'];
			$TermName=$row3['TermName'];
			if($TermName=="Due end of the month")
			{
				$duedate = date("Y-m-t", strtotime($entrydate));
			}
			else if($TermName=="Due end of the next month")
			{
				$duedate = date("Y-m-t", strtotime("+1 month"));
			}
			else if($TermName=="Due on Receipt")
			{
				$duedate = $entrydate;
			}
			else
			{
				$duedate = date("Y-m-d", strtotime("+$NoofDays Days "));
			}
			

			echo $duedate;
	}
	else
	{
		echo "NoData";
	}
}
?>