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
		 
		  $DcId = $_REQUEST['DcId'];
		  $customerid = $_REQUEST['customerid'];
		  $customername = ucfirst($_REQUEST['customername']);
		  $referencenum = $_REQUEST['referencenum'];
		  $entrydate = date('Y-m-d',strtotime($_REQUEST['entrydate']));
		  $shipdate = date('Y-m-d', strtotime($_REQUEST['shipdate']));

		  $paymentterms = $_REQUEST['paymentterms'];
		  $deliverymethod = $_REQUEST['deliverymethod'];
		  $salesperson = $_REQUEST['salesperson'];
		  
		  $sum = $_REQUEST['sum'];
		  $disctype = $_REQUEST['disctype'];
		  $discvalue = $_REQUEST['discvalue'];
		  $disctotal = $_REQUEST['disctotal'];
		  $totalamount = $_REQUEST['totalamount'];
		  $cnotes = $_REQUEST['cnotes'];
		  $terms = $_REQUEST['terms'];

		  $status = $_REQUEST['status'];

		 $InvPkId = json_decode($_REQUEST['InvPkId']);
		 $product = json_decode($_REQUEST['product']);
		 $quantity = json_decode($_REQUEST['quantity']);
		 $price = json_decode($_REQUEST['price']);
		 
		
    $Ordrchk=array();	 
  	foreach ($InvPkId as $key => $value) {
	 	# code...
	 	if($InvPkId[$key]!="")
    	{
    		$Ordrchk[]=1;	
    	}
    	else
    	{
    		$Ordrchk[]=0;
    	}
	 }

	  $Qtychk=array();	 
  	foreach ($quantity as $key => $value) {
	 	# code...
	 	if($quantity[$key]<0 || $quantity[$key]==0)
    	{
    		$Qtychk[]=0;	
    	}
    	else
    	{
    		$Qtychk[]=1;
    	}
	 }
	 
		 $delete_status = 1;
		if($DcId!="" && $customerid!="" && $sum!="" && $totalamount!="" && $status!="")
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

			if(in_array('0', $Ordrchk))
			{
				$check = 1;
				$Errors .= "Please Select Item Name"."\n";
			}
			if(in_array('0', $Qtychk))
			{
				$check = 1;
				$Errors .= "Quantity atlest 1 required"."\n";
			}

			if($totalamount<="0")
			{
				$check = 1;
				$Errors .= "Please make sure that total amount required atlest 1"."\n";
			}
			

	    if($check==1){
		echo $Errors;
		}
		else 
		{
			$maildata1 = "";
			$maildata1.= "<html></head></head><body>
			<h1>Dear $customername, OrderId generated: $OrderId</h1>
			<p>please confirm the order details</p>
			<table  id='insidetable' border='1' cellpadding='10' cellspacing='0' class='table'>
			<th>Product</th>
			<th>Quantity</th>
			<th>Price</th>
			";

			$upload_dir = "../DeliverImages/";
			//$validextensions = array("jpeg", "jpg", "png", "gif");
			 $temporary = explode(".", $_FILES["file"]["name"]);
			 $file_extension = end($temporary);

			$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable

			if($_FILES['file']['name']=="")
			{
				$filename1="";	
			}else
			{
				$filename1 = $DcId."-".str_replace(' ', '', $_FILES['file']['name']);	
			}
			
			$targetPath = $upload_dir.$filename1; // Target path where file is to be stored


			move_uploaded_file($sourcePath,$targetPath); // Moving Uploaded file

			$query = $dbConnection->prepare("INSERT INTO DeliveryChallans (DcId,CustomerId_CustomerMaster,DcDate,Reference,CustomerNotes,TermsCondition,FileName,SubTotal,DiscType,DiscountVal,DiscountAmount,OrderTotal,DCStatus,CreatedTime) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$query->execute(array($DcId,$customerid,$entrydate,$referencenum,$cnotes,$terms,$filename1,$sum,$disctype,$discvalue,$disctotal,$totalamount,$status,$DATEIME));
			$PkId = $dbConnection->lastInsertId();

			foreach ($InvPkId as $key => $value) {
				# code...
				$total1 =$quantity[$key]*$price[$key];
				$query1 = $dbConnection->prepare("INSERT INTO DeliveryChallanDetails (DcId_DeliveryChallans,PkId_InventoryMaster,Quantity,Price,Amount,CreatedTime) VALUES (?,?,?,?,?,?)");
				$query1->execute(array($DcId,$InvPkId[$key],$quantity[$key],$price[$key],$total1,$DATEIME));

				$maildata1.="<tr><td>$product[$key]</td><td>$quantity[$key]</td><td>$price[$key]</td></tr>";
			}
			$maildata1.= "</table></body></html>";

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

			$Post = "Created Delivery as on $DATEIME";
			$Type="Delivery Challan";		
			$query2 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
			$query2->execute(array($SAId,$Post,$Type,$DcId,$DATEIME));

			echo $result= "Success";
		}
					
	}
	else
	{
		echo $Errors = "Enter Mandatory Fields";
		
	}
}	 
?>