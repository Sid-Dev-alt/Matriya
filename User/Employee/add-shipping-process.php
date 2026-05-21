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
	$PackageId = $_REQUEST['PackageId'];
	$ShipId = $_REQUEST['ShipId'];
	// $customerid = $data->customerid;
	// $customername = ucfirst($_REQUEST['customername']);
	 $InvoiceId = $_REQUEST['InvoiceId'];
	 $entrydate = date('Y-m-d',strtotime($_REQUEST['entrydate']));
	// $duedate = date('Y-m-d', strtotime($_REQUEST['duedate']));

	$transporttype = $_REQUEST['transporttype'];
	$carrier = $_REQUEST['carrier'];
	$transportinvoiceno = $_REQUEST['transportinvoiceno'];
	$shipcharge = $_REQUEST['shipcharge'];
	$internalnotes = $_REQUEST['internalnotes'];
		 		
		 $delete_status = 1;
		if($PackageId!="" && $ShipId!="")
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

			$upload_dir = "../ShippingImages/";
			//$validextensions = array("jpeg", "jpg", "png", "gif");
			 $temporary = explode(".", $_FILES["file"]["name"]);
			 $file_extension = end($temporary);

			$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable

			if($_FILES['file']['name']=="")
			{
				$filename1="";	
			}else
			{
				$filename1 = $ShipId."-".str_replace(' ', '', $_FILES['file']['name']);	
			}
			
			$targetPath = $upload_dir.$filename1; // Target path where file is to be stored

			move_uploaded_file($sourcePath,$targetPath); // Moving Uploaded file

			$query = $dbConnection->prepare("INSERT INTO Shipping (ShipId,PackageId_Packages,ShipDate,ModeOfTransport,Carrier,TransportNo,ShipCharges,Notes,FileName,CreatedTime) VALUES (?,?,?,?,?,?,?,?,?,?)");
			$query->execute(array($ShipId,$PackageId,$entrydate,$transporttype,$carrier,$transportinvoiceno,$shipcharge,$internalnotes,$filename1,$DATEIME));

			//shipped =2;
			$query1 = $dbConnection->prepare("UPDATE Packages SET PackageStatus=? WHERE PackageId=?");
			$query1->execute(array('2',$PackageId));

			//shipped =2;
			$query1 = $dbConnection->prepare("UPDATE PackageDetails SET IsShipped=? WHERE PackageId_Packages=?");
			$query1->execute(array('2',$PackageId));

			//partially shipped =2;
			$query2 = $dbConnection->prepare("UPDATE Invoices SET ShipmentStatus=? WHERE InvoiceId=?");
			$query2->execute(array('2',$InvoiceId));				

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

			$Post = "Created Shipping as on $DATEIME";
			$Type="Shipping";		
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