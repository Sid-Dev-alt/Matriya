<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{		
	$delete_status = 1;	
	$mainarray = array();
	$data1 = array();
	$data2 = array();
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";

	$data = json_decode(file_get_contents("php://input"));
	$ProductId = $data->ProductId;
	$entrydate = date('Y-m-d');
	
	$query1 = $dbConnection->prepare("SELECT CreatedTime,SUM(Quantity) AS total FROM InvoiceDetails WHERE DeleteStatus=? AND ProductId_ProductMaster=?");
	$query1->execute(array($delete_status,$ProductId));
	while($row1 = $query1->fetch())
	{
		$CreatedDate = $row1['CreatedTime'];
		$totalinvoice = $row1['total'];
		$totalproduce=0;


	$data1[] = array(
		"CreatedDate"=>$CreatedDate,
		"totalproduce"=>$totalproduce,
		"totalinvoice"=>$totalinvoice);
	}

	$query2 = $dbConnection->prepare("SELECT ProductionDate,SUM(Quantity) AS totalproduce FROM Productions WHERE ProductId_ProductMaster=? AND DeleteStatus=? group by ProductionDate");
	$query2->execute(array($ProductId,$delete_status));
	while($row2 = $query2->fetch())
	{
		$CreatedDate = $row2['ProductionDate'];
		$totalproduce = $row2['totalproduce'];
		$totalinvoice =0;

	$data2[] = array(
		"CreatedDate"=>$CreatedDate,
		"totalproduce"=>$totalproduce,
		"totalinvoice"=>$totalinvoice);
	}
	// echo (json_encode($data1));
	// echo (json_encode($data2));
	// $mainarray = array_merge ($data1, $data2);
	// echo (json_encode($mainarray));
	$sums = array();
    foreach (array_keys($data1 + $data2) as $currency) {

    	if($data1[$currency]['CreatedDate']!="" || $data2[$currency]['CreatedDate']!="")
    	{

	    	if($data1[$currency]['CreatedDate']==$data2[$currency]['CreatedDate'])
	    	{
	    		 $sums[$currency]['CreatedDate'] = $data1[$currency]['CreatedDate'];
	    		 $sums[$currency]['totalproduce'] = $data1[$currency]['totalproduce'] + $data2[$currency]['totalproduce'];
	    		 $sums[$currency]['totalinvoice'] = $data1[$currency]['totalinvoice'] + $data2[$currency]['totalinvoice'];
	    	}
	    	else
	    	{
	    		if($data1[$currency]['CreatedDate']=="")
	    		{
	    			$sums[$currency]['CreatedDate'] =  $data2[$currency]['CreatedDate'];
	    			$sums[$currency]['totalproduce'] =  $data2[$currency]['totalproduce'];
	    			$sums[$currency]['totalinvoice'] =  $data2[$currency]['totalinvoice'];
	    		}
	    		if($data2[$currency]['CreatedDate']=="")
	    		{
	    			$sums[$currency]['CreatedDate'] =  $data1[$currency]['CreatedDate'];
	    			$sums[$currency]['totalproduce'] =  $data1[$currency]['totalproduce'];
	    			$sums[$currency]['totalinvoice'] =  $data1[$currency]['totalinvoice'];
	    		}
	    	}
	    }
    }
	echo (json_encode($sums));
}
?>