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
	$data2 = array();
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	$query = $dbConnection->prepare("SELECT * FROM Payments WHERE DeleteStatus=?");
	$query->execute(array($delete_status));
	$num_rows = $query->rowCount();
	$a = "1";
	if($num_rows>0)
	{	
		while($row = $query->fetch())
		{
			$InvoiceId = $row['InvoiceId_Invoices'];

			$query11 = $dbConnection->prepare("SELECT DisplayName FROM CustomerMaster WHERE CustomerId=? AND DeleteStatus=?");
			$query11->execute(array($row['CustomerId_CustomerMaster'],$delete_status));
			$row11 = $query11->fetch();
			$CustomerName = $row11['DisplayName'];
			
			$query1 = $dbConnection->prepare("SELECT InvoiceTotal FROM Invoices WHERE InvoiceId=? AND DeleteStatus=?");
			$query1->execute(array($InvoiceId,$delete_status));
			$row1 = $query1->fetch();
			$InvoiceTotal = $row1['InvoiceTotal'];


			$data1[] = array(
				'PkId' => $row['PkId'],
				'CustomerId_CustomerMaster' => $row['CustomerId_CustomerMaster'],
				'CustomerName' => $CustomerName,
				'InvoiceId' => $InvoiceId,
				'PaymentDate' => $row['PaymentDate'],
				'PaymentMode' => $row['PaymentMode'],
				'RemainPay'=>$row['RemainPay'],
				'DueAmount'=>$row['DueAmount'],
				'PaymentType'=>$row['PaymentType'],
				'OtherWalletName'=>$row['OtherWalletName'],
				'ChequeDate'=>$row['ChequeDate'],
				//'DeliveryMethod'=>$row['DeliveryMethod'],
				'ChequeNo'=>$row['ChequeNo'],
				'ReferenceId'=>$row['ReferenceId'],
				'ReceivedAmount'=>$row['ReceivedAmount'],
				'UploadDocs'=>$row['UploadDocs'],
				'InvoiceTotal'=>$InvoiceTotal,
				'Comments'=>$row['Comments'],
			);
		}

		echo (json_encode($data1));
	}
	else
	{
		echo "NoData";
	}
}
?>