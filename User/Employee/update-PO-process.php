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
		$FormPkId = $_REQUEST['FormPkId'];
		$POrderId = $_REQUEST['POrderId'];
		$vendorid = $_REQUEST['vendorid'];
		$vendorname = ucfirst($_REQUEST['vendorname']);
		$referencenum = $_REQUEST['referencenum'];

		$entrydate = $_REQUEST['entrydate'];
			$shipdate = $_REQUEST['shipdate'];

		$paymentterms = $_REQUEST['paymentterms'];
		$deliverymethod = $_REQUEST['deliverymethod'];
		$salesperson = $_REQUEST['salesperson'];
		$additionalcharges = $_REQUEST['additionalcharges'];
		$FileName = $_REQUEST['FileName'];
		$sum = $_REQUEST['sum'];
		$disctype = $_REQUEST['disctype'];
		$discvalue = $_REQUEST['discvalue'];
		$disctotal = $_REQUEST['disctotal'];
		$totalamount = $_REQUEST['totalamount'];
		$cnotes = $_REQUEST['cnotes'];
		$terms = $_REQUEST['terms'];
		$status = $_REQUEST['status'];

		$EntryPkId = json_decode($_REQUEST['EntryPkId']);
		$OldProductId = json_decode($_REQUEST['OldProductId']);
		$ProductId = json_decode($_REQUEST['ProductId']);
		$product = json_decode($_REQUEST['product']);
		$Oldquantity = json_decode($_REQUEST['Oldquantity']);
		$quantity = json_decode($_REQUEST['quantity']);
		$price = json_decode($_REQUEST['price']);


    $Ordrchk=array();
  	foreach ($OldProductId as $key => $value) {
	 	# code...
	 	if($OldProductId[$key]!="")
    	{
    		$Ordrchk[]=1;
    	}
    	else
    	{
    		$Ordrchk[]=0;
    	}
	 }

	  $Qtychk=array();
  	foreach ($Oldquantity as $key => $value) {
	 	# code...
	 	if($Oldquantity[$key]<0 || $Oldquantity[$key]==0 || $Oldquantity[$key]=="0.000")
    	{
    		$Qtychk[]=0;
    	}
    	else
    	{
    		$Qtychk[]=1;
    	}
	 }

		 $delete_status = 1;
		if($FormPkId!="" && $POrderId!="" && $vendorid!="" && $sum!="" && $totalamount!="" && $status!="")
		{
			$check = 0;
			$Errors .= "";

			if(in_array('0', $Ordrchk))
			{
				$check = 1;
				$Errors .= "Please Select Item Name"."\n";
			}
			if($vendorid=="")
			{
				$check = 1;
				$Errors .= "Please select vendor"."\n";
			}
			if($sum=="" || $sum=="0" || $sum=="0.00")
			{
				$check = 1;
				$Errors .= "Sub Total is required atleast 1"."\n";
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
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
			$entrydate = date('Y-m-d',strtotime($entrydate));
			$shipdate = date('Y-m-d', strtotime($shipdate));

			$From = $_SESSION['AppName'];
			$FromId = $_SESSION['FromEmailId'];
			$PrimaryEmailId = $_SESSION['PrimaryEmailId'];
			$Cc = $_SESSION['Cc'];
			$Bcc = $_SESSION['Bcc'];
			$AppURL = $_SESSION['AppURL'];

			$SAId = $_SESSION['EmpId'];
			$AppName = $_SESSION['AppName'];
			$DATEIME = GetDateTime();

			$upload_dir = "../POImages/";
			//$validextensions = array("jpeg", "jpg", "png", "gif");
			 $temporary = explode(".", $_FILES["file"]["name"]);
			 $file_extension = end($temporary);

			$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable

			if($_FILES['file']['name']=="")
			{
				$filename1="$FileName";
			}else
			{
				unlink("../POImages/".$FileName);
				$filename1 = $POrderId."-".str_replace(' ', '', $_FILES['file']['name']);
			}

			$targetPath = $upload_dir.$filename1; // Target path where file is to be stored
			move_uploaded_file($sourcePath,$targetPath); // Moving Uploaded file

			$query = $dbConnection->prepare("UPDATE PurchaseOrders SET VendorId_VendorMaster=?,POrderDate=?,Reference=?,DeliveryDate=?,PaymentTerms=?,ShipmentReference=?,CustomerNotes=?,TermsCondition=?,FileName=?,SubTotal=?,AdditionalCharges=?,DiscType=?,DiscountVal=?,DiscountAmount=?,OrderTotal=?,POStatus=? WHERE PkId=?");
			$query->execute(array($vendorid,$entrydate,$referencenum,$shipdate,$paymentterms,$deliverymethod,$cnotes,$terms,$filename1,$sum,$additionalcharges,$disctype,$discvalue,$disctotal,$totalamount,$status,$FormPkId));

			foreach ($ProductId as $key => $value) {
				# code...
				if($EntryPkId[$key]=="")
				{
					//echo "insert new entry";

					$query9 = $dbConnection->prepare("SELECT Quantity FROM InventoryMaster WHERE ProductId_ProductMaster=?");
					$query9->execute(array($ProductId[$key]));
					$row9 = $query9->fetch();
					$AvlQuantity = $row9['Quantity'];

					$FreshQty = $AvlQuantity+$quantity[$key];

					$Ttotal = $quantity[$key]*$price[$key];
					$query1 = $dbConnection->prepare("INSERT INTO PurchaseOrderDetails (POrderId_PurchaseOrders,ProductId_ProductMaster,Quantity,Price,Amount,TDate,CreatedTime) VALUES (?,?,?,?,?,?,?)");
					$query1->execute(array($POrderId,$ProductId[$key],$quantity[$key],$price[$key],$Ttotal,$entrydate,$DATEIME));

					

					$query5 = $dbConnection->prepare("UPDATE InventoryMaster SET Quantity=? WHERE ProductId_ProductMaster=?");
					$query5->execute(array($FreshQty,$ProductId[$key]));

					// $query2 = $dbConnection->prepare("SELECT SUM(Quantity) AS PrvsQty FROM PurchaseOrderDetails WHERE POrderId_PurchaseOrders=? AND ProductId_ProductMaster=? AND DeleteStatus=?");
					// $query2->execute(array($POrderId,$ProductId[$key],$delete_status));
					// $row2 = $query2->fetch();
					// $PrvsQty = $row2['PrvsQty'];
					// if($PrvsQty!="")
					// {
					// 	//echo "hai";
					// 	$TQty = $PrvsQty+$quantity[$key];
					// 	$query1 = $dbConnection->prepare("UPDATE PurchaseOrderDetails SET Quantity=? WHERE POrderId_PurchaseOrders=? AND ProductId_ProductMaster=? AND DeleteStatus=?");
					// 	$query1->execute(array($TQty,$POrderId,$ProductId[$key],$delete_status));
					// }
					// else
					// {
					// 	//echo "Bai";
					// 	$PrvsQty=0;
					// 	$Ttotal = $quantity[$key]*$price[$key];

					// 	$query1 = $dbConnection->prepare("INSERT INTO PurchaseOrderDetails (POrderId_PurchaseOrders,ProductId_ProductMaster,Quantity,Price,Amount,CreatedTime) VALUES (?,?,?,?,?,?)");
					// 	$query1->execute(array($POrderId,$ProductId[$key],$quantity[$key],$price[$key],$Ttotal,$DATEIME));
					// }
				}
				else
				{
					//echo "update with new product";
					if($ProductId[$key]!="" && ($OldProductId[$key]!=$ProductId[$key]))
					{
							$Ttotal = $quantity[$key]*$price[$key];
							$query22 = $dbConnection->prepare("UPDATE PurchaseOrderDetails SET ProductId_ProductMaster=?,Quantity=?,Price=?,Amount=?,TDate=? WHERE POrderId_PurchaseOrders=? AND DeleteStatus=? AND PkId=?");
							$query22->execute(array($ProductId[$key],$quantity[$key],$price[$key],$Ttotal,$entrydate,$POrderId,$delete_status,$EntryPkId[$key]));

							//get present quantity of old product
							$query23 = $dbConnection->prepare("SELECT Quantity FROM InventoryMaster WHERE ProductId_ProductMaster=?");
							$query23->execute(array($OldProductId[$key]));
							$row23 = $query23->fetch();
							$OldOpenQuantity = $row23['Quantity'];
							$FreshOldProQty = $OldOpenQuantity-$Oldquantity[$key];

							//get present quantity of new product
							$query24 = $dbConnection->prepare("SELECT Quantity FROM InventoryMaster WHERE ProductId_ProductMaster=?");
							$query24->execute(array($ProductId[$key]));
							$row24 = $query24->fetch();
							$FreshQty = $row24['Quantity']+$quantity[$key];

							//minusing purchase qty to actual qty
							$query25 = $dbConnection->prepare("UPDATE InventoryMaster SET Quantity=? WHERE ProductId_ProductMaster=?");
							$query25->execute(array($FreshOldProQty,$OldProductId[$key]));
							
							$query5 = $dbConnection->prepare("UPDATE InventoryMaster SET Quantity=? WHERE ProductId_ProductMaster=?");
							$query5->execute(array($FreshQty,$ProductId[$key]));
					}
					else
					{
						//get present quantity of old product

						$Ttotal = $quantity[$key]*$price[$key];
						$query31 = $dbConnection->prepare("UPDATE PurchaseOrderDetails SET Quantity=?,Price=?,Amount=?,TDate=? WHERE POrderId_PurchaseOrders=? AND DeleteStatus=? AND PkId=?");
						$query31->execute(array($quantity[$key],$price[$key],$Ttotal,$entrydate,$POrderId,$delete_status,$EntryPkId[$key]));

						if($Oldquantity[$key]!=$quantity[$key])
						{

							$query30 = $dbConnection->prepare("SELECT Quantity FROM InventoryMaster WHERE ProductId_ProductMaster=?");
							$query30->execute(array($OldProductId[$key]));
							$row30 = $query30->fetch();
							$OpenQuantity30 = $row30['Quantity'];
							if($quantity[$key]<$Oldquantity[$key])
							{
								$MainNewQty =  $Oldquantity[$key]-$quantity[$key];
								$FinalQty = $OpenQuantity30-$MainNewQty;
							}
							else if($quantity[$key]>$Oldquantity[$key])
							{
								$MainNewQty = $quantity[$key]-$Oldquantity[$key];
								$FinalQty = $OpenQuantity30+$MainNewQty;
							}
							$query37 = $dbConnection->prepare("UPDATE InventoryMaster SET Quantity=? WHERE ProductId_ProductMaster=?");
							$query37->execute(array($FinalQty,$OldProductId[$key]));
						}

					}
				}
			}//foreach

			// $maildata1 = "";
			// $maildata1.= "<html></head></head><body>
			// <table  id='insidetable' border='1' cellpadding='10' cellspacing='0' class='table'>
			// <th>Product</th>
			// <th>Quantity</th>
			// <th>Price</th>
			// <th>Amount</th>
			// ";
			// $query1 = $dbConnection->prepare("SELECT * FROM PurchaseOrderDetails WHERE POrderId_PurchaseOrders=? AND DeleteStatus=?");
			// $query1->execute(array($POrderId,$delete_status));
			// while($row1 = $query1->fetch())
			// {
			// 	$Quantity =  $row1['Quantity'];
			// 	$Price =  $row1['Price'];
			// 	$Amount =  $row1['Amount'];
			// 	$query2 = $dbConnection->prepare("SELECT ProductName FROM ProductMaster
			// 		WHERE ProductId=?");
			// 	$query2->execute(array($row1['ProductId_ProductMaster']));
			// 	$row2 = $query2->fetch();
			// 	$ProductName = $row2['ProductName'];

			// $maildata1.="<tr><td>$ProductName</td><td>$Quantity</td><td>$Price</td><td>$Amount</td></tr>";
			// }

			// $maildata1.= "<tr><td colspan='3'>Sub Total</td><td>$sum</td></tr><tr><td colspan='3'>Additional Charges</td><td>$additionalcharges</td></tr><tr><td colspan='3'>Discount Amount</td><td>$disctotal</td></tr><tr><td colspan='3'>Total</td><td>$totalamount</td></tr></table></body></html>";

			// $query3 = $dbConnection->prepare("SELECT DisplayName,EmailId FROM VendorMaster WHERE VendorId=?");
			// $query3->execute(array($customerid));
			// $row3 = $query3->fetch();
			// $DisplayName = ucfirst($row3['DisplayName']);
			// $CEmailId = $row3['EmailId'];

			// $query4 = $dbConnection->prepare("SELECT LogoFilename FROM CompanyInfo WHERE DeleteStatus=?");
			// $query4->execute(array($delete_status));
			// $row4 = $query4->fetch();
			// $LogoFilename = $row4['LogoFilename'];
			// //echo "$AppURL/User/CompanyLogo/$LogoFilename";
			// /*Sent Email start */
			// if($status==2)
			// {
			// 	$subject = "Purchase Order revised";
			// 	$mailinfo ="<!DOCTYPE html> <html> <head> <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'> <meta name='viewport' content='width=device-width, initial-scale=1.0'> <title>FieldTrack Email</title> <style type='text/css'> @media(max-width:480px){table[class=main_table],table[class=layout_table]{width:300px !important;}table[class=layout_table] tbody tr td.header_image img{width:300px !important;height:auto !important;}}a{color:#37aadc}table p{font-family:Arial, Helvetica, sans-serif;font-size:16px;line-height:22px;text-align:justify;}</style> </head> <body> <table border='0' cellpadding='0' cellspacing='0' width='100%' style='background:#F7F7F7;padding: 25px;'> <tbody> <tr><tr>&nbsp;</tr></tr><tr> <td align='center' valign='top'> <table border='0' cellpadding='0' cellspacing='0' class='main_table' width='650' style='background: #fff;border-radius:10px;'> <tbody> <tr> <td> <table border='0' cellpadding='0' cellspacing='0' class='layout_table' style='border-radius: 5px;border:1px solid #CCCCCC;background: #ffffff;' width='100%' > <tbody> <tr><td style='font-size:13px;line-height:13px;margin:0;padding:0;'>&nbsp;</td></tr><tr> <td align='center' valign='top'> <table align='center' border='0' cellpadding='0' cellspacing='0' width='85%'> <tbody><tr><td style='text-align:center'><img src='$AppURL/User/CompanyLogo/$LogoFilename'></td></tr> <tr> <td align='left'> <p>Dear $DisplayName,</p><p>Purchase Order is revised by $From. Purchase OrderId is: $POrderId<br>Below are the details</p><p>$maildata1</p></td></tr></tbody> </table> </td></tr><tr><td>&nbsp;</td></tr></tbody> </table> </td></tr></tbody> </table> </td></tr></tbody> </table> </body> </html>";
			// 	$message = "$mailinfo\n\n<p>Regards,<br>Support Team,<br>$From</p>";
			// 	$headers = "Reply-To: $From <$FromId>\r\n";
			// 	$headers .= "Return-Path: $From <$FromId>\r\n";
			// 	$headers .= "From: $From <$FromId>\r\n";
			// 	$headers .= "Organization: $From\r\n";
			// 	$headers .= "Content-Type: text/html\r\n";
			// 	$headers .= "X-Sender: <$FromId>\n";
			// 	$headers .= "X-Mailer: PHP\n"; // mailer
			// 	$headers .= "X-Priority: 1\n"; //1 Urgent Message, 3 Normal
			// 	//$headers .= "Bcc:$BCC"."\n";
			// 	$headers .= "Cc:$Cc\n";

			// 	mail($CEmailId,$subject,$message,$headers);
			// }
			// /*Sent Email end */

			$Post = "Purchase Order updated as on $DATEIME";
			$Type="Purchase Order";
			$query2 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
			$query2->execute(array($SAId,$Post,$Type,$POrderId,$DATEIME));

			echo $result= "Success";
		}

	}
	else
	{
		echo $Errors = "Enter Mandatory Fields";

	}
}
?>
