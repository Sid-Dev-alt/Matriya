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
	$data3 = array();
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	$query = $dbConnection->prepare("SELECT ProductName,SUM(Quantity) AS Dispatched FROM InvoiceDetails WHERE DeleteStatus=? GROUP BY ProductId_ProductMaster");
	$query->execute(array($delete_status));
	$num_rows = $query->rowCount();
	$a = "1";
	if($num_rows>0)
	{	
		while($row = $query->fetch())
		{
			$ProductName = $row['ProductName'];
			$Dispatched = $row['Dispatched'];

			

			$data1[] = array(
				// 'FormPkId' => $FormPkId,
				// 'ProductionId' => $row['ProductionId'],
				// 'ProductId_ProductMaster' => $row['ProductId_ProductMaster'],
				'ProductName' => $ProductName,
				'Dispatched' => $Dispatched,
				// 'Quantity'=>$row['Quantity'],
				// 'Weight'=>$row['Weight'],
				// 'SCRAP'=>$row['SCRAP'],
				// 'Dripper'=>$row['Dripper'],
				// 'Total'=>$row['Total'],
				// 'Notes'=>$row['Notes'],
				// 'data2'=>$data2,
				// 'data3'=>$data3,
			);
		//	unset($data2);
		//	unset($data3);
		}

		echo (json_encode($data1));
	}
	else
	{
		echo "NoData";
	}
}
?>