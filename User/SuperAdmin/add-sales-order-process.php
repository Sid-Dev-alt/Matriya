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
		 
		  $OrderId = $_REQUEST['OrderId'];
		  $customerid = $_REQUEST['customerid'];
		  $customername = ucfirst($_REQUEST['customername']);
		  $referencenum = $_REQUEST['referencenum'];
		  $entrydate = $_REQUEST['entrydate'];
		  $shipdate = $_REQUEST['shipdate'];
		  

		  $paymentterms = $_REQUEST['paymentterms'];
		  $deliverymethod = $_REQUEST['deliverymethod'];
		  $salesperson = $_REQUEST['salesperson'];
		  $additionalcharges = $_REQUEST['additionalcharges'];
		  $sum = $_REQUEST['sum'];
		  $disctype = $_REQUEST['disctype'];
		  $discvalue = $_REQUEST['discvalue'];
		  $disctotal = $_REQUEST['disctotal'];
		  $totalamount = $_REQUEST['totalamount'];
		  $cnotes = $_REQUEST['cnotes'];
		  $terms = $_REQUEST['terms'];

		  $status = $_REQUEST['status'];

		 $ProductId = json_decode($_REQUEST['ProductId']);
		 $product = json_decode($_REQUEST['product']);
		 $quantity = json_decode($_REQUEST['quantity']);
		 $price = json_decode($_REQUEST['price']);
		 
		 
		
    $Ordrchk=array();	 
  	foreach ($ProductId as $key => $value) {
	 	# code...
	 	if($ProductId[$key]!="")
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
		if($OrderId!="" && $customerid!="" && $sum!="" && $totalamount!="" && $status!="")
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
			$AppURL = $_SESSION['AppURL'];

			$Datechk=array();
			  if($shipdate!="")
			  {
			  	$entrydate = date('Y-m-d',strtotime($entrydate));
				$shipdate = date('Y-m-d', strtotime($shipdate));

			  	//echo $shipdate."".$entrydate;
			  	if($shipdate<$entrydate)
			  	{
			  		$Datechk[] = 0;
			  	}
			  	else
			  	{
			  		$Datechk[] = 1;
			  	}
			  }

			$entrydate = date('Y-m-d',strtotime($entrydate));
			if($shipdate=="")
			{
				$shipdate = "";
			}
			else
			{
				$shipdate = date('Y-m-d', strtotime($shipdate));	
			}
			

			$SAId = $_SESSION['UserId'];
			$DATEIME = GetDateTime();
			if(in_array('0', $Datechk))
			{
				$check = 1;
				$Errors .= "Shipment Date Should be greater than Selected Date"."\n";
			}
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
			

			$upload_dir = "../OrderImages/";
			//$validextensions = array("jpeg", "jpg", "png", "gif");
			 $temporary = explode(".", $_FILES["file"]["name"]);
			 $file_extension = end($temporary);

			$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable

			if($_FILES['file']['name']=="")
			{
				$filename1="";	
			}else
			{
				$filename1 = $OrderId."-".str_replace(' ', '', $_FILES['file']['name']);	
			}
			
			$targetPath = $upload_dir.$filename1; // Target path where file is to be stored


			move_uploaded_file($sourcePath,$targetPath); // Moving Uploaded file

			$query = $dbConnection->prepare("INSERT INTO SalesOrders (OrderId,CustomerId_CustomerMaster,OrderDate,Reference,ShipmentDate,PaymentTerms,DeliveryMethod,Salesperson,CustomerNotes,TermsCondition,FileName,SubTotal,AdditionalCharges,DiscType,DiscountVal,DiscountAmount,OrderTotal,OrderStatus,CreatedTime) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$query->execute(array($OrderId,$customerid,$entrydate,$referencenum,$shipdate,$paymentterms,$deliverymethod,$salesperson,$cnotes,$terms,$filename1,$sum,$additionalcharges,$disctype,$discvalue,$disctotal,$totalamount,$status,$DATEIME));
			$PkId = $dbConnection->lastInsertId();

			foreach ($ProductId as $key => $value) {
				# code...
				$query2 = $dbConnection->prepare("SELECT SUM(Quantity) AS PrvsQty FROM SalesOrderDetails WHERE OrderId_SalesOrders=? AND ProductId_ProductMaster=? AND DeleteStatus=?");
				$query2->execute(array($OrderId,$ProductId[$key],$delete_status));
				$row2 = $query2->fetch();
				$PrvsQty = $row2['PrvsQty'];
				if($PrvsQty!="")
				{
					//echo "hai";
					$TQty = $PrvsQty+$quantity[$key];	
					$query1 = $dbConnection->prepare("UPDATE SalesOrderDetails SET Quantity=? WHERE OrderId_SalesOrders=? AND ProductId_ProductMaster=? AND DeleteStatus=?");
					$query1->execute(array($TQty,$OrderId,$ProductId[$key],$delete_status));
				}
				else
				{
					//echo "Bai";
					$PrvsQty=0;	
					$Ttotal = $quantity[$key]*$price[$key];

					$query1 = $dbConnection->prepare("INSERT INTO SalesOrderDetails (OrderId_SalesOrders,ProductId_ProductMaster,Quantity,Price,Amount,CreatedTime) VALUES (?,?,?,?,?,?)");
					$query1->execute(array($OrderId,$ProductId[$key],$quantity[$key],$price[$key],$Ttotal,$DATEIME));
				}
			}

			$maildata1 = "";
			$maildata1.= "<html></head></head><body>
			<table  id='insidetable' border='1' cellpadding='10' cellspacing='0' class='table'>
			<th>Product</th>
			<th>Quantity</th>
			<th>Price</th>
			<th>Amount</th>
			";
			$query1 = $dbConnection->prepare("SELECT * FROM SalesOrderDetails WHERE OrderId_SalesOrders=? AND DeleteStatus=?");
			$query1->execute(array($OrderId,$delete_status));
			while($row1 = $query1->fetch())
			{
				$Quantity =  $row1['Quantity'];
				$Price =  $row1['Price'];
				$Amount =  $row1['Amount'];
				$query2 = $dbConnection->prepare("SELECT ProductName FROM ProductMaster 
					WHERE ProductId=?");
				$query2->execute(array($row1['ProductId_ProductMaster']));
				$row2 = $query2->fetch();
				$ProductName = $row2['ProductName'];

			$maildata1.="<tr><td>$ProductName</td><td>$Quantity</td><td>$Price</td><td>$Amount</td></tr>";
			}

			$maildata1.= "<tr><td colspan='3'>Sub Total</td><td>$sum</td></tr><tr><td colspan='3'>Additional Charges</td><td>$additionalcharges</td></tr><tr><td colspan='3'>Discount Amount</td><td>$disctotal</td></tr><tr><td colspan='3'>Total</td><td>$totalamount</td></tr></table></body></html>";

			$query3 = $dbConnection->prepare("SELECT DisplayName,EmailId FROM CustomerMaster WHERE CustomerId=?");
			$query3->execute(array($customerid));
			$row3 = $query3->fetch();
			$DisplayName = ucfirst($row3['DisplayName']);
			$CEmailId = $row3['EmailId'];

			$query4 = $dbConnection->prepare("SELECT LogoFilename FROM CompanyInfo WHERE DeleteStatus=?");
			$query4->execute(array($delete_status));
			$row4 = $query4->fetch();
			$LogoFilename = $row4['LogoFilename'];

			//echo "$AppURL/User/CompanyLogo/$LogoFilename";

			/*Sent Email start */
			if($status==2)
			{
				$subject = "Sales Order generated";
				$mailinfo ="<!DOCTYPE html> <html> <head> <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'> <meta name='viewport' content='width=device-width, initial-scale=1.0'> <title>FieldTrack Email</title> <style type='text/css'> @media(max-width:480px){table[class=main_table],table[class=layout_table]{width:300px !important;}table[class=layout_table] tbody tr td.header_image img{width:300px !important;height:auto !important;}}a{color:#37aadc}table p{font-family:Arial, Helvetica, sans-serif;font-size:16px;line-height:22px;text-align:justify;}</style> </head> <body> <table border='0' cellpadding='0' cellspacing='0' width='100%' style='background:#F7F7F7;padding: 25px;'> <tbody> <tr><tr>&nbsp;</tr></tr><tr> <td align='center' valign='top'> <table border='0' cellpadding='0' cellspacing='0' class='main_table' width='650' style='background: #fff;border-radius:10px;'> <tbody> <tr> <td> <table border='0' cellpadding='0' cellspacing='0' class='layout_table' style='border-radius: 5px;border:1px solid #CCCCCC;background: #ffffff;' width='100%' > <tbody> <tr><td style='font-size:13px;line-height:13px;margin:0;padding:0;'>&nbsp;</td></tr><tr> <td align='center' valign='top'> <table align='center' border='0' cellpadding='0' cellspacing='0' width='85%'> <tbody><tr><td style='text-align:center'><img src='$AppURL/User/CompanyLogo/$LogoFilename'></td></tr> <tr> <td align='left'> <p>Dear $DisplayName,</p><p>Order generated by $From. OrderId is: $OrderId<br>Below are the details</p><p>$maildata1</p></td></tr></tbody> </table> </td></tr><tr><td>&nbsp;</td></tr></tbody> </table> </td></tr></tbody> </table> </td></tr></tbody> </table> </body> </html>";
				$message = "$mailinfo\n\n<p>Regards,<br>Support Team,<br>$From</p>";	
				$headers = "Reply-To: $From <$FromId>\r\n";
				$headers .= "Return-Path: $From <$FromId>\r\n";
				$headers .= "From: $From <$FromId>\r\n";
				$headers .= "Organization: $From\r\n";
				$headers .= "Content-Type: text/html\r\n";
				$headers .= "X-Sender: <$FromId>\n";
				$headers .= "X-Mailer: PHP\n"; // mailer
				$headers .= "X-Priority: 1\n"; //1 Urgent Message, 3 Normal
				//$headers .= "Bcc:$BCC"."\n";
				$headers .= "Cc:$Cc\n";
				
				mail($CEmailId,$subject,$message,$headers);
			}
			/*Sent Email end */

			$Post = "Created Order as on $DATEIME";
			$Type="Order";		
			$query2 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
			$query2->execute(array($SAId,$Post,$Type,$OrderId,$DATEIME));

			echo $result= "Success";
		}
					
	}
	else
	{
		echo $Errors = "Enter Mandatory Fields";
		
	}
}	 
?>