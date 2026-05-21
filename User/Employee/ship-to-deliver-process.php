<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{		
	$data = json_decode(file_get_contents("php://input")); 		 
	$PkId = $data->PkId;
	$ShipId = $data->ShipId;
	$InvoiceId = $data->InvoiceId;
	// $customername = ucfirst($_REQUEST['customername']);
	// $referencenum = $_REQUEST['referencenum'];
	 //$entrydate = date('Y-m-d',strtotime($data->entrydate));
	// $duedate = date('Y-m-d', strtotime($_REQUEST['duedate']));

	$PackageId = $data->PackageId;
		 		
		 $delete_status = 1;
		if($PkId!="" && $ShipId!=""  && $PackageId!="")
		{
			$check = 0;
			$Errors .= "";
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";

			$From = $_SESSION['AppName'];
			$FromId = $_SESSION['FromEmailId'];
			$PrimaryEmailId = $_SESSION['PrimaryEmailId'];
			$Cc = $_SESSION['Cc'];
			$Bcc = $_SESSION['Bcc'];

			$SAId = $_SESSION['EmpId'];
			$DATEIME = GetDateTime();

			//delivered =2;
			$query = $dbConnection->prepare("UPDATE Shipping SET ShipStatus=? WHERE PkId=?");
			$query->execute(array('2',$PkId));

			//delivered =3;
			$query1 = $dbConnection->prepare("UPDATE Packages SET PackageStatus=? WHERE PackageId=?");
			$query1->execute(array('3',$PackageId));

			$query3 = $dbConnection->prepare("UPDATE PackageDetails SET IsShipped=? WHERE PackageId_Packages=?");
			$query3->execute(array('3',$PackageId));
				
			$query2 = $dbConnection->prepare("SELECT PackageStatus FROM Invoices WHERE InvoiceId=?");
			$query2->execute(array($InvoiceId));
			$row2 = $query2->fetch();
			$PackageStatus = $row2['PackageStatus'];			

			$query22 = $dbConnection->prepare("SELECT PkId FROM Packages WHERE InvoiceId=? AND PackageStatus<?");
			$query22->execute(array($InvoiceId,'3'));
			$scount =$query22->rowCount();
			if($PackageStatus=="2" && $scount=="0")
			{
				$query2 = $dbConnection->prepare("UPDATE Invoices SET ShipmentStatus=? WHERE InvoiceId=?");
				$query2->execute(array('3',$InvoiceId));	
			}
			
			/*Sent Email start */
			// $subject = "Order Sent by ";
			// $message = "Dear Admin,<p>Order received from $CustomerName.</p><p>OrderId : $orderid</p><p>Order Details:</p> $maildata\n\nTo Login with your password, visit the following address:\n\n[ $AppURL ]<p>Regards,<br>Support Team,<br>$From</p>";	
			// $headers = "Reply-To: $From <$FromId>\r\n";
			// $headers .= "Return-Path: $From <$FromId>\r\n";
			// $headers .= "From: $From <$FromId>\r\n";
			// $headers .= "Organization: $From\r\n";
			// $headers .= "Content-Type: text/html\r\n";
			// $headers .= "X-Sender: <$FromId>\n";
			// $headers .= "X-Mailer: PHP\n"; // mailer
			// $headers .= "X-Priority: 1\n"; //1 Urgent Message, 3 Normal
			// //$headers .= "Bcc:$BCC"."\n";
			// $headers .= "Cc:$Cc\n";
			
			// mail($PrimaryEmailId,$subject,$message,$headers);
			/*Sent Email end */

			$Post = "Shipping to Deliverd as on $DATEIME";
			$Type="Shipping Deliverd";		
			$query2 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
			$query2->execute(array($SAId,$Post,$Type,$ShipId,$DATEIME));

			echo $result= "Success";
					
	}
	else
	{
		echo $Errors = "Enter Mandatory Fields";
		
	}
}	 
?>