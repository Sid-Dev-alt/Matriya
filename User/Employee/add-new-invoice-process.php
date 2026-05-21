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
		$FormPkId = $data->FormPkId;		 
		 $InvoiceId = $data->InvoiceId;
		//$OrderId = $data->OrderId;
		$IsSaveDraft = $data->IsSaveDraft;
		$CustomerId = $data->CustomerId;
		 $customername = ucfirst($data->CustomerName);
		 $customermobileno = $data->customermobileno;
		 $customerplace = $data->customerplace;
		 $referencenum = $data->referencenum;
		$entrydate = $data->entrydate;
		$EntryType = $data->EntryType;
		// $duedate = $data->duedate'];
		// $paymentterms = $data->paymentterms'];
		// $deliverymethod = $data->deliverymethod'];
		// $salesperson = $data->salesperson'];		  
		//  $sum = $data->sum;
		//  $additionalcharges = $data->additionalcharges;		  
		// $gstperc = $data->gstperc;
		// $gstpercamt = $data->gstpercamt;	

		//  $disctype = $data->disctype;
		//  $discvalue = $data->discvalue;
		//  $disctotal = $data->disctotal;
		//  $totalamount = $data->totalamount;
 $cnotes = $data->cnotes;
		// $terms = $data->terms;
		$EntryPkId = $data->EntryPkId;
		$InvPkId = $data->InvPkId;
		$ProductId = $data->ProductId;
		$product = $data->product;
		$UniqueRollNo = $data->UniqueRollNo;
		//$HSN = $data->HSN;
		$Size = $data->Size;
		
		$AvlQty = $data->AvlQty;
		$quantity = $data->quantity;
		// $price = $data->price;
		// $basicamount = $data->basicamount;		
		// $gstrate = $data->gstrate;
		// $gstamount = $data->gstamount;

		// $returnid = $data->returnid;
		// $returnamt = $data->returnamt;

		// $finaltotal = $data->finaltotal;
		
		//  $paycash = $data->paycash;
		//  $paycard = $data->paycard;
		//  $payothers = $data->payothers;
		//  $totalpaid = $data->totalpaid;
		//$InvArr = json_decode( $data->InvArr']), true);
		
    $Ordrchk=array();
    $Qtychk=array();
	$Insrtchk=array();
	$SameQtychk=array();	
	$Avlchk=array();	
	$SaveProduct="";
	$AvlSaveProduct="";
  	foreach ($InvPkId as $key => $value) {
	 	# code...
	 	//echo $ProductId[$key];
	 	if($ProductId[$key]=="")
    	{
    		$Ordrchk[]=1;	//true
    	}
    	else
    	{
    		$Ordrchk[]=0; //false
    	}
    	// if($quantity[$key]<0 || $quantity[$key]==0)
    	// {
    	// 	$Qtychk[]=1; //true	
    	// }
    	// else
    	// {
    	// 	$Qtychk[]=0; //false
    	// }
    	// if($quantity[$key]>$AvlQty[$key])
    	// {
    	// 	$Avlchk[]=1;	//true
    	// 	$AvlSaveProduct .= "".'"'.$product[$key]."".'"'." Qty is more than the Avl Qty\n";
    	// }
    	// else
    	// {
    	// 	$Avlchk[]=0; //false
    	// 	$AvlSaveProduct .= "";
    	// }    	
	 }
	 //print_r($Qtychk);	 
		$delete_status = 1;
		$check = 0;
		$Errors .= "";
		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";
		$entrydate = date('Y-m-d',strtotime($entrydate));
		//$duedate = date('Y-m-d', strtotime($duedate));
		$From = $_SESSION['AppName'];
		$FromId = $_SESSION['FromEmailId'];
		$PrimaryEmailId = $_SESSION['PrimaryEmailId'];
		$Cc = $_SESSION['Cc'];
		$Bcc = $_SESSION['Bcc'];
		$SAId = $_SESSION['EmpId'];
		$DATEIME = GetDateTime();
		$todaydt = GetTDate();
		// if($customermobileno=="")
		// {
		// 	$check = 1;
		// 	$Errors .= "Customer Mobile No is required"."\n";
		// }
		if($CustomerId=="")
		{
			$check = 1;
			$Errors .= "Customer Name is required"."\n";
		}
		if(in_array('1', $Ordrchk) || empty($InvPkId))
		{
			$check = 1;
			$Errors .= "Please Select Item Name"."\n";
		}
		// else if(in_array('1', $Qtychk))
		// {
		// 	$check = 1;
		// 	$Errors .= "Quantity atlest 1 required"."\n";
		// }

		// else if(in_array('1', $Avlchk))
		// {
		// 	$check = 1;
		// 	$Errors .= "$AvlSaveProduct\n";
		// }
		//print_r($Ordrchk);
		// else if($totalamount<="0")
		// {
		// 	$check = 1;
		// 	$Errors .= "Total amount required atlest 1"."\n";
		// }			

	    if($check==1){
		echo $Errors;
		}
		else 
		{			
			// $maildata1 = "";
			// $maildata1.= "<html></head></head><body>
			// <h1>Dear $customername, OrderId generated: $OrderId</h1>
			// <p>please confirm the order details</p>
			// <table  id='insidetable' border='1' cellpadding='10' cellspacing='0' class='table'>
			// <th>Product</th>
			// <th>Quantity</th>
			// <th>Price</th>			// ";
			// $upload_dir = "../InvoiceImages/";
			// //$validextensions = array("jpeg", "jpg", "png", "gif");
			//  $temporary = explode(".", $_FILES["file"]["name"]);
			//  $file_extension = end($temporary);
			// $sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
			// if($_FILES['file']['name']=="")
			// {			// 	$filename1="";				// }else			// {
			// 	$filename1 = $InvoiceId."-".str_replace(' ', '', $_FILES['file']['name']);	
			// }			
			// $targetPath = $upload_dir.$filename1; // Target path where file is to be stored
			// move_uploaded_file($sourcePath,$targetPath); // Moving Uploaded file

			if($EntryType=="Update")
			{
				$query = $dbConnection->prepare("UPDATE Invoices SET CustomerId_CustomerMaster=?,CustomerName=?,CustomerMobile=?,CustomerPlace=?,Reference=?,InvoiceDate=?,CustomerNotes=? WHERE PkId=?");
				$query->execute(array($CustomerId,$customername,$customermobileno,$customerplace,$referencenum,$entrydate,$cnotes,$FormPkId));

				// $query9 = $dbConnection->prepare("DELETE FROM DraftInvoiceDetails WHERE InvoiceId_Invoices=?");
				// $query9->execute(array($InvoiceId));

				$Post = "Updated Invoice with (Invoices (CustomerId_CustomerMaster: $CustomerId,CustomerName: $customername,CustomerMobile: $customermobileno,CustomerPlace: $customerplace,Reference: $referencenum,InvoiceDate: $entrydate,CustomerNotes: $cnotes)) as on $DATEIME by $SAId";
				$Type="Invoice";		
				$query2 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
				$query2->execute(array($SAId,$Post,$Type,$InvoiceId,$DATEIME));
			}
			else
			{

				$sql = $dbConnection->prepare("SELECT InvoiceId FROM Invoices ORDER BY PkId DESC LIMIT 0,1") ;
				$sql->execute();
				$rowCount = $sql->rowCount();
				if($rowCount>0)
				{
					$row = $sql->fetch();	
				 	$value = substr($row['InvoiceId'],3);
					$variable = $value + 1;
					$length = strlen($variable);
					if(strlen($variable)<3)
					{
						switch($length)
						{
							case 2:
							$variable = "0".$variable;
							break;
							case 1:
							$variable = "00".$variable;
							break;
						}
						$InvoiceId = substr($row['InvoiceId'],0,3).$variable;
					}
					else
					{
					 $InvoiceId = substr( $row['InvoiceId'],0,3).$variable;
					}
				}
				else
				{
					 $InvoiceId = "INV001";
				}

				$query12 = $dbConnection->prepare("INSERT INTO Invoices (UserId_Users,InvoiceId,CustomerId_CustomerMaster,CustomerName,CustomerMobile,CustomerPlace,Reference,InvoiceDate,CustomerNotes,InvoiceStatus,CreatedTime) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
				$query12->execute(array($SAId,$InvoiceId,$CustomerId,$customername,$customermobileno,$customerplace,$referencenum,$entrydate,$cnotes,'2',$DATEIME));

				$FormPkId = $dbConnection->lastInsertId();

			}
			
			foreach ($EntryPkId as $key => $value) {				# code...

				//$FreshInvoice1 = $InvoicedQty[$key]+$quantity[$key];
				// $remainblc = $AvlQty[$key]-$quantity[$key];
				// $remainblc1 = number_format((float)$remainblc, 3, '.', '');
				//echo $TrackingMode[$key];
				if($EntryPkId[$key]=="")
				{
					$query11 = $dbConnection->prepare("SELECT PkId_Category,ProductName FROM ProductMaster WHERE ProductId=?");
					$query11->execute(array($ProductId[$key]));
					$row11 = $query11->fetch();
					$PkId_Category = $row11['PkId_Category'];

					$query13 = $dbConnection->prepare("INSERT INTO InvoiceDetails (InvoiceId_Invoices,InvDate,PkId_Category,ProductId_ProductMaster,ProductName,PkId_InventoryMaster,Quantity,CreatedTime) VALUES (?,?,?,?,?,?,?,?)");
					$query13->execute(array($InvoiceId,$entrydate,$PkId_Category,$ProductId[$key],$product[$key],$InvPkId[$key],$AvlQty[$key],$DATEIME));
					//$DetailId = $dbConnection->lastInsertId();
					// if($AvlQty[$key]!=0 || $AvlQty[$key]!="0.000")
					// {
						// if($remainblc1=="0.000" || $remainblc1=="0.00" || $remainblc1==0 || $remainblc1<0)
						// {
						$query7 = $dbConnection->prepare("UPDATE InventoryMaster SET Quantity=?,Status=? WHERE PkId=?");
						$query7->execute(array('0.000','0',$InvPkId[$key]));
					//}
				}
				
				//$Post = "Created Invoice with (Invoices (InvoiceId: $InvoiceId,CustomerId_CustomerMaster: $CustomerId,CustomerName: $customername,CustomerMobile: $customermobileno,CustomerPlace: $customerplace,Reference: $referencenum,InvoiceDate: $entrydate,CustomerNotes: $cnotes,InvoiceStatus: '2')) and (InvoiceDetails(InvoiceId_Invoices: $InvoiceId,InvDate: $entrydate,PkId_Category: $row11['PkId_Category'],ProductId_ProductMaster: $ProductId[$key],ProductName: $product[$key],PkId_InventoryMaster: $InvPkId[$key],Quantity: $AvlQty[$key])) as on $DATEIME by $SAId";
				$Post = "Created Invoice with (Invoices (InvoiceId: $InvoiceId,CustomerId_CustomerMaster: $CustomerId,CustomerName: $customername,CustomerMobile: $customermobileno,CustomerPlace: $customerplace,Reference: $referencenum,InvoiceDate: $entrydate,CustomerNotes: $cnotes,InvoiceStatus: '2')) and (InvoiceDetails(InvoiceId_Invoices: $InvoiceId,InvDate: $entrydate,PkId_Category: $PkId_Category,ProductId_ProductMaster: $ProductId[$key],ProductName: $product[$key],PkId_InventoryMaster: $InvPkId[$key],Quantity: $AvlQty[$key])) as on $DATEIME by $SAId";
				$Type="Invoice";		
				$query123 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
				$query123->execute(array($SAId,$Post,$Type,$InvoiceId,$DATEIME));
			}

				//$maildata1.="<tr><td>$product[$key]</td><td>$quantity[$key]</td><td>$price[$key]</td></tr>";
			//}
			//$maildata1.= "</table></body></html>";

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

			
			$data4 = array('result'=>'Success','InvoiceId'=>$InvoiceId,'FormPkId'=>$FormPkId);
			echo json_encode($data4);
			 //echo $result= "Success";
		}
}	 
?>
