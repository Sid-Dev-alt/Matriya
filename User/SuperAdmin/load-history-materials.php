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
	$data2 = array();
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";

	$data = json_decode(file_get_contents("php://input"));
	$invtype = $data->invtype;
	$entrydate = date('Y-m-d',strtotime($data->entrydate));
	$productid = $data->productid;
	$productname = $data->productname;
	$maindata = array();
	if($invtype=="Finished")
	{
			$data1 = array();
			$query = $dbConnection->prepare("SELECT OpeningQty,Quantity,CreatedTime FROM Productions WHERE ProductId_ProductMaster=? AND DeleteStatus=? AND ProductionDate=?");
			$query->execute(array($productid,$delete_status,$entrydate));
			$num_rows = $query->rowCount();
			while($rows = $query->fetch())
			{
				$OpeningQty = $rows['OpeningQty'];
				$Quantity = $rows['Quantity'];
				$CreatedTime = date('d-m-Y H:i:s', strtotime($rows['CreatedTime']));
			
				$data1[] = array("productname"=>$productname,"OpeningQty"=>$OpeningQty,
					"Quantity"=>$Quantity,"CreatedTime"=>$CreatedTime
				);
			}

			// $entrydatetime1 = date('Y-m-d 00:00:00',strtotime($data->entrydate));
			// $entrydatetime2 = date('Y-m-d 23:59:59',strtotime($data->entrydate));
				$data2 = array();
			//	echo "SELECT * FROM InvoiceDetails WHERE CreatedTime LIKE '%$entrydate%' AND DeleteStatus='$delete_status' AND ProductId_ProductMaster='$productid'";
			$query1 = $dbConnection->prepare("SELECT * FROM InvoiceDetails WHERE CreatedTime LIKE ? AND DeleteStatus=? AND ProductId_ProductMaster=?");
			$query1->execute(array("%$entrydate%",$delete_status,$productid));
			while($row1 = $query1->fetch())
			{
				$InvoiceId_Invoices = $row1['InvoiceId_Invoices'];
				$Quantity = $row1['Quantity'];

				$query2 = $dbConnection->prepare("SELECT CustomerId_CustomerMaster FROM Invoices WHERE InvoiceId=?");
				$query2->execute(array($InvoiceId_Invoices));
				$row2 = $query2->fetch();
				$CustomerId_CustomerMaster = $row2['CustomerId_CustomerMaster'];

				$query3 = $dbConnection->prepare("SELECT DisplayName FROM CustomerMaster WHERE CustomerId=?");
				$query3->execute(array($CustomerId_CustomerMaster));
				$row3 = $query3->fetch();
				$DisplayName = $row3['DisplayName'];

				$data2[] = array(
					"InvoiceId_Invoices"=>$InvoiceId_Invoices,
					"DisplayName"=>$DisplayName,
					"Quantity"=>$Quantity,
				);
			}
		$maindata[] = array(
					"data1"=>$data1,
					"data2"=>$data2
				);
	}
	if($invtype=="Raw")
	{
		$data3 = array();
		$query1 = $dbConnection->prepare("SELECT ProductionId_Productions,OpeningQuantity,DeductQuantity FROM ProductionRawMaterials WHERE CreatedTime LIKE ? AND DeleteStatus=? AND ProductId_ProductMaster=?");
			$query1->execute(array("%$entrydate%",$delete_status,$productid));
			while($row1 = $query1->fetch())
			{
				$ProductionId_Productions = $row1['ProductionId_Productions'];
				$OpeningQuantity = $row1['OpeningQuantity'];
				$DeductQuantity = $row1['DeductQuantity'];
				

				$query2 = $dbConnection->prepare("SELECT ProductId_ProductMaster,ProductionDate FROM Productions WHERE ProductionId=?");
				$query2->execute(array($ProductionId_Productions));
				$row2 = $query2->fetch();
				$ProductId_ProductMaster = $row2['ProductId_ProductMaster'];

				$query2 = $dbConnection->prepare("SELECT ProductName FROM ProductMaster WHERE ProductId=?");
				$query2->execute(array($ProductId_ProductMaster));
				$row2 = $query2->fetch();
				$FinishedProductName = $row2['ProductName'];

				$data3[] = array(
					"FinishedProductName"=>$FinishedProductName,
					"rawproductname"=>$productname,
					"OpeningQuantity"=>$OpeningQuantity,
					"DeductQuantity"=>$DeductQuantity,
				);
			}
				
				$maindata[] = array(
					"data3"=>$data3
				);
	}
		
	echo (json_encode($maindata));
}
?>