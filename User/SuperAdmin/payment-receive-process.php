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
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	$jsondata = json_decode(file_get_contents("php://input"));
	
	$DATEIME = GetDateTime();
	$todaydate = date("Y-m-d"); 
	$entrydate= date('Y-m-d', strtotime($_REQUEST['entrydate']));

	 $CustomerId = $_REQUEST['CustomerId'];
	 $InvoiceId= $_REQUEST['InvoiceId'];
	 $BillAmount= $_REQUEST['BillAmount'];
	 $tillreceive= $_REQUEST['tillreceive'];
	 $remainpay= $_REQUEST['remainpay'];

	 $paymentmode = $_REQUEST['paymentmode'];
	 $amount = $_REQUEST['amount'];
	 
	 $paymenttype = $_REQUEST['paymenttype'];
	 $walletname = $_REQUEST['walletname'];
	 $chequedate = date('Y-m-d', strtotime($_REQUEST['chequedate']));
	 $chequeno = $_REQUEST['chequeno'];
	 $referenceid = $_REQUEST['referenceid'];
	 $comments = $_REQUEST['comments'];
	
	if($paymentmode=="Partial")
	{
		$finalamt=$amount;
		$remainamt = $_REQUEST['remainpay']-$_REQUEST['amount'];
	}
	else if($paymentmode=="Full")
	{
		$finalamt=$remainpay;
		$remainamt = "0";
	}


	if($paymentmode=="Partial" && $amount>$remainpay)
	{
		echo "Please check the amount";
	}
	else if($paymenttype!="")
	{
		$upload_dir = "../PaymentReceiveDocs/";
		//$validextensions = array("jpeg", "jpg", "png", "gif");
		 $temporary = explode(".", $_FILES["file"]["name"]);
		 $file_extension = end($temporary);

		$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable

			if($_FILES['file']['name']=="")
			{
				$filename1="";	
			}else
			{
				$filename1 = $InvoiceId."-".$CustomerId.str_replace(' ', '', $_FILES['file']['name']);	
			}
			
			$targetPath = $upload_dir.$filename; // Target path where file is to be stored

			move_uploaded_file($sourcePath,$targetPath); // Moving Uploaded file

	
		$sql3 = $dbConnection->prepare("INSERT INTO Payments (InvoiceId_Invoices,CustomerId_CustomerMaster,PaymentDate,PaymentMode,PaymentType,OtherWalletName,ChequeDate,ChequeNo,ReferenceId,RemainPay,ReceivedAmount,DueAmount,Comments,UploadDocs,CreatedTime) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$sql3->execute(array($InvoiceId,$CustomerId,$entrydate,$paymentmode,$paymenttype,$walletname,$chequedate,$chequeno,$referenceid,$remainpay,$finalamt,$remainamt,$comments,$filename1,$DATEIME));


		$query2 = $dbConnection->prepare("SELECT SUM(ReceivedAmount) AS Amt FROM Payments WHERE DeleteStatus=? AND InvoiceId_Invoices=?");
		$query2->execute(array($delete_status,$InvoiceId));
		$rows2 = $query2->fetch();			
		$ReceivedAmount = $rows2['Amt'];

		if($BillAmount==$ReceivedAmount || $ReceivedAmount>$BillAmount)
		{

			$query1 = $dbConnection->prepare("UPDATE Invoices SET PaymentStatus=? WHERE DeleteStatus=? AND InvoiceId=?");
			$query1->execute(array('1',$delete_status,$InvoiceId));
		}

	
				/*Mail code start here */
				//echo $maildata1;

				// 	$From = $_SESSION['AppName'];
				// 	$AppURL = $_SESSION['AppURL'];
				// 	$FromId = $_SESSION['FromEmailId'];
				// 	$PrimaryEmailId = $_SESSION['PrimaryEmailId'];
				// 	$Cc = $_SESSION['Cc'];
				// 	$Bcc = $_SESSION['Bcc'];

				// $ToAdmin = [
				// 	    'FromEmail' => $FromId,
				// 	    'FromName' => $From,
				// 	    'Subject' => "Order received from $customername - raised by $BDEName",
				// 	    'Html-part' => "<!DOCTYPE html> <html> <head> <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'> <meta name='viewport' content='width=device-width, initial-scale=1.0'> <title>FieldTrack Email</title> <style type='text/css'> @media(max-width:480px){table[class=main_table],table[class=layout_table]{width:300px !important;}table[class=layout_table] tbody tr td.header_image img{width:300px !important;height:auto !important;}}a{color:#37aadc}table p{font-family:Arial, Helvetica, sans-serif;font-size:16px;line-height:22px;text-align:justify;}</style> </head> <body> <table border='0' cellpadding='0' cellspacing='0' width='100%' style='background:#F7F7F7;padding: 25px;'> <tbody> <tr><tr>&nbsp;</tr></tr><tr> <td align='center' valign='top'> <table border='0' cellpadding='0' cellspacing='0' class='main_table' width='650'> <tbody> <tr> <td> <table border='0' cellpadding='0' cellspacing='0' class='layout_table' style='border-radius: 5px;border:1px solid #CCCCCC;background: #ffffff;' width='100%' > <tbody> <tr><td style='font-size:13px;line-height:13px;margin:0;padding:0;'>&nbsp;</td></tr><tr> <td align='center' valign='top'> <table align='center' border='0' cellpadding='0' cellspacing='0' width='85%'> <tbody> <tr> <td align='left'> <p>Dear Admin,</p><p>$BDEName received order from $customername. Below are the Order details </p>$maildata1</p></td></tr> <tr><td></td></tr></tbody> </table> </td></tr><tr><td>&nbsp;</td></tr></tbody> </table> </td></tr></tbody> </table> </td></tr></tbody> </table> </body> </html>",
				// 	    'To' => "$PrimaryEmailId,$Cc,$Bcc"
				// 	];
				//$response = $mailjet->post(Mailjet\Resources::$Email, ['body' => $ToAdmin]);	
							
			//}	
			/*Mail code end here */	

			echo $result = "Success";
	}
	else
	{

			echo "Payment type is required";
		
		}
}
?>