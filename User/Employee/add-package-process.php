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
		  $OrderId = $_REQUEST['OrderId'];
		  $customerid = $_REQUEST['customerid'];
		  $customername = ucfirst($_REQUEST['customername']);
		  $referencenum = $_REQUEST['referencenum'];
		  $entrydate = date('Y-m-d',strtotime($_REQUEST['entrydate']));
		  $duedate = date('Y-m-d', strtotime($_REQUEST['duedate']));

		  $paymentterms = $_REQUEST['paymentterms'];
		  $deliverymethod = $_REQUEST['deliverymethod'];
		  $salesperson = $_REQUEST['salesperson'];
		  
		  $ordersum = $_REQUEST['ordersum'];
		  $packedsum = $_REQUEST['packedsum'];
		  $sum = $_REQUEST['sum'];
		  $disctype = $_REQUEST['disctype'];
		  $discvalue = $_REQUEST['discvalue'];
		  $disctotal = $_REQUEST['disctotal'];
		  $totalamount = $_REQUEST['totalamount'];
		  $cnotes = $_REQUEST['cnotes'];
		  $terms = $_REQUEST['terms'];
		  $internalnotes = $_REQUEST['internalnotes'];


		 $SalePkId = json_decode($_REQUEST['SalePkId']);
		 $quantity = json_decode($_REQUEST['quantity']);
		 $PackedQty = json_decode($_REQUEST['PackedQty']);
		 $qtytopack = json_decode($_REQUEST['qtytopack']);
		 
		
    $Ordrchk=array();	 
    $Qtychk=array();	 
  	foreach ($SalePkId as $key => $value) {
	 	# code...
	 	if($SalePkId[$key]!="")
    	{
    		$Ordrchk[]=1;	
    	}
    	else
    	{
    		$Ordrchk[]=0;
    	}
    	$remainqty1 = $quantity[$key]-$PackedQty[$key];
    	if($qtytopack[$key]>$remainqty1)
    	{
    		$Qtychk[]=0; //true
    	}
    	else
    	{
    		$Qtychk[]=1; //fakse
    	}
	 }
	 
		 $delete_status = 1;
		if($OrderId!="" && $customerid!="" && $sum!="")
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
				$Errors .= "You cannot enter more than the Quantity to Pack"."\n";
			}
			if($sum<=0)
			{
				$check = 1;
				$Errors .= "Please select the quantity"."\n";
			}
			

	    if($check==1){
		echo $Errors;
		}
		else 
		{

			$query = $dbConnection->prepare("INSERT INTO Packages (PackageId,OrderId,PkgDate,InternalNotes,CreatedTime) VALUES (?,?,?,?,?)");
			$query->execute(array($PackageId,$OrderId,$entrydate,$internalnotes,$DATEIME));
			$PkId = $dbConnection->lastInsertId();

			foreach ($SalePkId as $key => $value) {
				# code...
				echo $SalePkId[$key];
				$quantity1 =$quantity[$key];
				$PackedQty1 =$PackedQty[$key];
				$qtytopack1 =$qtytopack[$key];
				$NewQty1 = $PackedQty1+$qtytopack1;

				if($qtytopack1>0)
				{

					$query1 = $dbConnection->prepare("INSERT INTO PackageDetails (PackageId_Packages,PkId_SalesOrderDetails,Packed,CreatedTime) VALUES (?,?,?,?)");
					$query1->execute(array($PackageId,$SalePkId[$key],$qtytopack[$key],$DATEIME));

					$query2 = $dbConnection->prepare("UPDATE SalesOrderDetails SET PackedQty=? WHERE PkId=? AND OrderId_SalesOrders=?");
					$query2->execute(array($NewQty1,$SalePkId[$key],$OrderId));					
				}
			}

			if($ordersum==$sum)
			{
				//$OrderStatus=3//confirmed
				//PackageStatus=2 //Full
				$query2 = $dbConnection->prepare("UPDATE SalesOrders SET OrderStatus=?,PackageStatus=? WHERE OrderId=?");
				$query2->execute(array('3','2',$OrderId));
			}
			else
			{
				//PackageStatus=1 //partial
				$query2 = $dbConnection->prepare("UPDATE SalesOrders SET OrderStatus=?,PackageStatus=? WHERE OrderId=?");
				$query2->execute(array('3','1',$OrderId));
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

			$Post = "Created Package as on $DATEIME";
			$Type="Package";		
			$query2 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
			$query2->execute(array($SAId,$Post,$Type,$PackageId,$DATEIME));

			echo $result= "Success";
		}
					
	}
	else
	{
		echo $Errors = "Enter Mandatory Fields";
		
	}
}	 
?>