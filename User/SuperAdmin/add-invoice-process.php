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
		 
		 $InvoiceId = $_REQUEST['InvoiceId'];
		  $OrderId = $_REQUEST['OrderId'];
		  $customerid = $_REQUEST['customerid'];
		  $customername = ucfirst($_REQUEST['customername']);
		  $referencenum = $_REQUEST['referencenum'];
		  $entrydate = $_REQUEST['entrydate'];
		  $duedate = $_REQUEST['duedate'];

		  $paymentterms = $_REQUEST['paymentterms'];
		  $deliverymethod = $_REQUEST['deliverymethod'];
		  $salesperson = $_REQUEST['salesperson'];
		  
		  $sum = $_REQUEST['sum'];
		  $additionalcharges = $_REQUEST['additionalcharges'];

		$gstperc = $_REQUEST['gstperc'];
		$gstpercamt = $_REQUEST['gstpercamt'];

		  $disctype = $_REQUEST['disctype'];
		  $discvalue = $_REQUEST['discvalue'];
		  $disctotal = $_REQUEST['disctotal'];
		  $totalamount = $_REQUEST['totalamount'];
		  $cnotes = $_REQUEST['cnotes'];
		  $terms = $_REQUEST['terms'];

		 $ProductId = json_decode($_REQUEST['ProductId']);
		 $product = json_decode($_REQUEST['product']);
		 $OrderQuantity = json_decode($_REQUEST['OrderQuantity']);
		 $InvoicedQty = json_decode($_REQUEST['InvoicedQty']);
		 $pickquantity = json_decode($_REQUEST['pickquantity']);
		 $AvlQty = json_decode($_REQUEST['AvlQty']);
		 $quantity = json_decode($_REQUEST['quantity']);
		 $price = json_decode($_REQUEST['price']);
		 $TrackingMode = json_decode($_REQUEST['TrackingMode']);
		 $InvArr = json_decode($_REQUEST['InvArr']);
		 //$InvArr = json_decode( json_decode($_REQUEST['InvArr']), true);
		 $Type = $_REQUEST['Type'];
		
    $Ordrchk=array();
    $Qtychk=array();
	$Insrtchk=array();
	$SameQtychk=array();	
	$Avlchk=array();
	$SaveProduct="";
	$AvlSaveProduct="";
	$BatchSaveProduct="";
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
    	if($quantity[$key]<0 || $quantity[$key]==0)
    	{
    		$Qtychk[]=0;	
    	}
    	else
    	{
    		$Qtychk[]=1;
    	}
    	$RemainQty[$key] = $OrderQuantity[$key]-$InvoicedQty[$key];

    	if($quantity[$key]>$RemainQty[$key])
    	{
    		$Insrtchk[]=0;	//true
    		$SaveProduct .= "".'"'.$product[$key]."".'"'." Qty is more than the Qty to be Invoice\n";
    	}
    	else
    	{
    		$Insrtchk[]=1; //false
    		$SaveProduct .= "";
    	}

    	if($quantity[$key]>$AvlQty[$key])
    	{
    		$Avlchk[]=0;	//true
    		$AvlSaveProduct .= "".'"'.$product[$key]."".'"'." Qty is more than the actual stock\n";
    	}
    	else
    	{
    		$Avlchk[]=1; //false
    		$AvlSaveProduct .= "";
    	}

	    if(($TrackingMode[$key]=="Serial" || $TrackingMode[$key]=="Batch") && $quantity[$key]==$pickquantity[$key])
	    {	
    		$SameQtychk[]=0;	//true
    		$BatchSaveProduct .= "";
    		
    	}
    	else if($TrackingMode[$key]=="None")
    	{
    		$SameQtychk[]=0;	//true
    		$BatchSaveProduct .= "";
    	}
    	else
    	{
    		$SameQtychk[]=1; //false
    		$BatchSaveProduct .= "".'"'.$product[$key]."".'"'." Qty should match with Picked (Batch) Quantity\n";
    	}
    	
	 }
	 //print_r($Qtychk);
	 
		 $delete_status = 1;
		if($OrderId!="" && $customerid!="" && $sum!="" && $totalamount!="")
		{
			$check = 0;
			$Errors .= "";
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
			$entrydate = date('Y-m-d',strtotime($entrydate));
		  	$duedate = date('Y-m-d', strtotime($duedate));
			$From = $_SESSION['AppName'];
			$FromId = $_SESSION['FromEmailId'];
			$PrimaryEmailId = $_SESSION['PrimaryEmailId'];
			$Cc = $_SESSION['Cc'];
			$Bcc = $_SESSION['Bcc'];

			$SAId = $_SESSION['UserId'];
			$DATEIME = GetDateTime();
			$todaydt = GetTDate();
			if($sum=="0" || $sum=="0.00")
			{
				$check = 1;
				$Errors .= "Quantity atlest 1 required"."\n";
			}

			if(in_array('0', $Ordrchk))
			{
				$check = 1;
				$Errors .= "Please Select Item Name"."\n";
			}
			// if(in_array('0', $Qtychk))
			// {
			// 	$check = 1;
			// 	$Errors .= "Quantity atlest 1 required"."\n";
			// }
			if(in_array('0', $Avlchk))
			{
				$check = 1;
				$Errors .= "$AvlSaveProduct\n";
			}
			else if(in_array('0', $Insrtchk))
			{
				$check = 1;
				$Errors .= "$SaveProduct\n";
			}
			else if(in_array('1', $SameQtychk))
			{
				$check = 1;
				$Errors .= "$BatchSaveProduct\n";
			}
			else if($totalamount<="0")
			{
				$check = 1;
				$Errors .= "Total amount required atlest 1"."\n";
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

			$upload_dir = "../InvoiceImages/";
			//$validextensions = array("jpeg", "jpg", "png", "gif");
			 $temporary = explode(".", $_FILES["file"]["name"]);
			 $file_extension = end($temporary);

			$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable

			if($_FILES['file']['name']=="")
			{
				$filename1="";	
			}else
			{
				$filename1 = $InvoiceId."-".str_replace(' ', '', $_FILES['file']['name']);	
			}
			
			$targetPath = $upload_dir.$filename1; // Target path where file is to be stored


			move_uploaded_file($sourcePath,$targetPath); // Moving Uploaded file

			$query = $dbConnection->prepare("INSERT INTO Invoices (InvoiceId,CustomerId_CustomerMaster,OrderId,Reference,InvoiceDate,PaymentTerms,DueDate,SalesPerson,CustomerNotes,TermsCondition,FileName,SubTotal,GST,GSTAmount,AdditionalCharges,DiscType,DiscountVal,DiscountAmount,InvoiceTotal,InvoiceStatus,CreatedTime) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$query->execute(array($InvoiceId,$customerid,$OrderId,$referencenum,$entrydate,$paymentterms,$duedate,$salesperson,$cnotes,$terms,$filename1,$sum,$gstperc,$gstpercamt,$additionalcharges,$disctype,$discvalue,$disctotal,$totalamount,'2',$DATEIME));

			foreach ($ProductId as $key => $value) {
			if($quantity[$key]>0)
			{
				# code...
				$total1 =$quantity[$key]*$price[$key];

				$FreshInvoice1 = $InvoicedQty[$key]+$quantity[$key];

				$remainblc1 = $AvlQty[$key]-$quantity[$key];

				$query11 = $dbConnection->prepare("SELECT PkId_Category,PkId_SubCategoryMaster,PkId_Level2SubCategoryMaster,PkId_Level3SubCategoryMaster,ProductName,SKU,Unit,Size,Colour,IsItReturnable FROM ProductMaster WHERE ProductId=?");
				$query11->execute(array($ProductId[$key]));
				$row11 = $query11->fetch();

				$query1 = $dbConnection->prepare("INSERT INTO InvoiceDetails (InvoiceId_Invoices,InvDate,ProductId_ProductMaster,PkId_Category,PkId_SubCategoryMaster,PkId_Level2SubCategoryMaster,PkId_Level3SubCategoryMaster,ProductName,SKU,Unit,Size,Colour,IsItReturnable,Quantity,Price,Amount,CreatedTime) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
				$query1->execute(array($InvoiceId,$entrydate,$ProductId[$key],$row11['PkId_Category'],$row11['PkId_SubCategoryMaster'],$row11['PkId_Level2SubCategoryMaster'],
				$row11['PkId_Level3SubCategoryMaster'],$row11['ProductName'],$row11['SKU'],$row11['Unit'],$row11['Size'],$row11['Colour'],$row11['IsItReturnable'],$quantity[$key],$price[$key],$total1,$DATEIME));
				$DetailId = $dbConnection->lastInsertId();

				
						foreach ($InvArr[$key] as $index => $value1) {
					# code...e
						$InvPkId1 = $InvArr[$key][$index]->InvPkId;
						$batchno1 =  $InvArr[$key][$index]->batchno;
						$InvQuantity1 =  $InvArr[$key][$index]->InvQuantity;
						$chooseqty1 =  $InvArr[$key][$index]->chooseqty;
						$UpdateQty1 = $InvQuantity1-$chooseqty1;

						if($TrackingMode[$key]!="None")
						{

							if($chooseqty1!="" && $chooseqty1!="0")
							{
								$query2 = $dbConnection->prepare("INSERT INTO InvoiceBatchDetails (PkId_InvoiceDetails,PkId_InventoryMaster,Quantity,CreatedTime) VALUES (?,?,?,?)");
								$query2->execute(array($DetailId,$InvPkId1,$chooseqty1,$DATEIME));

								$query3 = $dbConnection->prepare("UPDATE InventoryMaster SET Quantity=? WHERE PkId=?");
								$query3->execute(array($UpdateQty1,$InvPkId1));
							}
						}
						else
						{

								$UpdateQty1 = $AvlQty[$key]-$quantity[$key];
								$query2 = $dbConnection->prepare("INSERT INTO InvoiceBatchDetails (PkId_InvoiceDetails,PkId_InventoryMaster,Quantity,CreatedTime) VALUES (?,?,?,?)");
								$query2->execute(array($DetailId,$InvPkId1,$quantity[$key],$DATEIME));

								$query3 = $dbConnection->prepare("UPDATE InventoryMaster SET Quantity=? WHERE PkId=?");
								$query3->execute(array($UpdateQty1,$InvPkId1));
						}
					}
				

				//InvoiceStatus	=2 //partially
				//OrderStatus	=3 //confirmed
			
				$query3 = $dbConnection->prepare("UPDATE SalesOrderDetails SET InvoicedQty=? WHERE OrderId_SalesOrders=? AND ProductId_ProductMaster=?");
				$query3->execute(array($FreshInvoice1,$OrderId,$ProductId[$key]));

				$query2 = $dbConnection->prepare("UPDATE SalesOrders SET OrderStatus=?,InvoiceStatus=? WHERE OrderId=?");
				$query2->execute(array('3','2',$OrderId));


				$query11 = $dbConnection->prepare("SELECT SUM(Quantity) AS tordqty, SUM(InvoicedQty) AS tinvqty FROM SalesOrderDetails WHERE OrderId_SalesOrders=? AND DeleteStatus=?");
				$query11->execute(array($OrderId,$delete_status));
				$row11 = $query11->fetch();
				$tordqty = $row11['tordqty'];
				$tinvqty = $row11['tinvqty'];
				if($tordqty==$tinvqty)
				{
					//inoived = 1
					$query2 = $dbConnection->prepare("UPDATE SalesOrders SET OrderStatus=?,InvoiceStatus=? WHERE OrderId=?");
					$query2->execute(array('3','1',$OrderId));
				}
				

				$maildata1.="<tr><td>$product[$key]</td><td>$quantity[$key]</td><td>$price[$key]</td></tr>";
			}
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

			// $Post = "Created Invoice as on $DATEIME";
			// $Type="Invoice";		
			// $query2 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
			// $query2->execute(array($SAId,$Post,$Type,$InvoiceId,$DATEIME));

			 echo $result= "Success";

		}
					
	}
	else
	{
		echo $Errors = "Enter Mandatory Fields";
		
	}
}	 
?>