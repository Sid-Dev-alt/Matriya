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
		 
		$FormPkId = $data->FormPkId;
		$InvoiceId = $data->InvoiceId;
		$InvoiceDate = $data->InvoiceDate;
		$delete_status = 1;			
		if($FormPkId=="")
		{
			$check = 1;
			$Errors .= "Unable to Submit"."\n";
		}
		if($InvoiceId=="")
		{
			$check = 1;
			$Errors .= "InvoiceId is required"."\n";
		}
		if($InvoiceDate=="")
		{
			$check = 1;
			$Errors .= "Invoice Date is required"."\n";
		}
		
	    if($check==1){
		echo $Errors;
		}
		if($check==0)
		{
			$check = 0;
			$Errors .= "";
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
			$SAId = $_SESSION['UserId'];
			$DATEIME = GetDateTime();
			$todaydt = GetTDate();

			//set Invoice delete
			$query = $dbConnection->prepare("UPDATE Invoices SET DeleteStatus=? WHERE InvoiceId=?");
			$query->execute(array('0',$InvoiceId));

			$query1 = $dbConnection->prepare("SELECT * FROM InvoiceDetails WHERE InvoiceId_Invoices=? AND DeleteStatus=?");
			$query1->execute(array($InvoiceId,$delete_status));
			while($row1 = $query1->fetch())
			{
				$DetailId = $row1['PkId'];
				$ProductId_ProductMaster = $row1['ProductId_ProductMaster'];
				$quantity = $row1['Quantity'];

				//get present value of product
				$query9 = $dbConnection->prepare("SELECT Quantity FROM InventoryMaster WHERE ProductId_ProductMaster=?");
				$query9->execute(array($ProductId_ProductMaster));
				$row9 = $query9->fetch();
				$OpenQuantity = $row9['Quantity'];
				$FinalQty = $OpenQuantity+$quantity;				

				//update fresh quantity
				$query11 = $dbConnection->prepare("UPDATE InventoryMaster SET Quantity=? WHERE ProductId_ProductMaster=?");
				$query11->execute(array($FinalQty,$ProductId_ProductMaster));

				//set Invoice_Details delete
				$query4 = $dbConnection->prepare("UPDATE InvoiceDetails SET DeleteStatus=? WHERE PkId=?");
				$query4->execute(array('0',$DetailId));
			}

			$Post = "Deleted Invoice as on $DATEIME";
			$Type="Invoice";		
			$query14 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
			$query14->execute(array($SAId,$Post,$Type,$InvoiceId,$DATEIME));

			echo $result= "Success";
		}
}	 
?>