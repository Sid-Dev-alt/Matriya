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
	$customerid = $data->customerid;
	if($customerid!="")
	{
		$delete_status = 1;	
		$data2 = array();
		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";
		
		$query = $dbConnection->prepare("SELECT * FROM Invoices WHERE DeleteStatus=? AND PaymentStatus=? AND CustomerId_CustomerMaster=?");
		$query->execute(array($delete_status,'0',$customerid));
		$num_rows = $query->rowCount();
		$a = "1";
		if($num_rows>0)
		{	
			while($row = $query->fetch())
			{
				$InvoiceId = $row['InvoiceId'];

				$query11 = $dbConnection->prepare("SELECT DisplayName,CompanyName,EmailId,Mobile FROM CustomerMaster WHERE CustomerId=? AND DeleteStatus=?");
				$query11->execute(array($row['CustomerId_CustomerMaster'],$delete_status));
				$row11 = $query11->fetch();
				$CustomerName = $row11['DisplayName'];
				$CCompanyName = $row11['CompanyName'];
				$CMobile = $row11['Mobile'];
				$CEmailId = $row11['EmailId'];

				$InvoiceTotal=$row['InvoiceTotal'];
				$query2 = $dbConnection->prepare("SELECT SUM(ReceivedAmount) AS Amt FROM Payments WHERE DeleteStatus=? AND InvoiceId_Invoices=?");
				$query2->execute(array($delete_status,$InvoiceId));
				$rows2 = $query2->fetch();
				$ReceivedAmount = $rows2['Amt'];
				if($ReceivedAmount==null || $ReceivedAmount=="" || $ReceivedAmount=="NULL")
				{
					$ReceivedAmount="0.00";
				}
				$PendingAmount = $InvoiceTotal-$ReceivedAmount;

				$query1 = $dbConnection->prepare("SELECT * FROM InvoiceDetails WHERE InvoiceId_Invoices=? AND DeleteStatus=?");
				$query1->execute(array($InvoiceId,$delete_status));
				while($row1 = $query1->fetch())
				{

					$query2 = $dbConnection->prepare("SELECT im.ProductId_ProductMaster,im.BatchNoORSrNo,im.BatchManufacturer,im.ManfactureDate,im.ExpireDate,pm.ProductName,pm.SKU FROM InventoryMaster AS im INNER JOIN ProductMaster AS pm ON im.ProductId_ProductMaster=pm.ProductId
						WHERE im.PkId=? AND im.DeleteStatus=?");
					$query2->execute(array($row1['PkId_InventoryMaster'],$delete_status));
					$row2 = $query2->fetch();
					$ProductId_ProductMaster = $row2['ProductId_ProductMaster'];
					$ProductName = $row2['ProductName'];
					$SKU = $row2['SKU'];


					$data2[] = array(
					'InvoiceId_Invoices' => $row1['InvoiceId_Invoices'],
					'PkId_InventoryMaster' => $row1['PkId_InventoryMaster'],
					'Quantity' => $row1['Quantity'],
					'Price' => $row1['Price'],
					'Amount'=>$row1['Amount'],
					'ProductId_ProductMaster'=>$ProductId_ProductMaster,
					'ProductName'=>$ProductName,
					'SKU'=>$SKU,
					);

				}

				$data1[] = array(
					'PkId' => $row['PkId'],
					'CustomerId_CustomerMaster' => $row['CustomerId_CustomerMaster'],
					'CustomerName' => $CustomerName,
					'CCompanyName' => $CCompanyName,
					'CMobile' => $CMobile,
					'CEmailId' => $CEmailId,
					'InvoiceId' => $row['InvoiceId'],
					'OrderId' => $row['OrderId'],
					'InvoiceDate' => $row['InvoiceDate'],
					'Reference' => $row['Reference'],
					'DueDate'=>$row['DueDate'],
					'PaymentTerms'=>$row['PaymentTerms'],
					//'DeliveryMethod'=>$row['DeliveryMethod'],
					'Salesperson'=>$row['Salesperson'],
					'CustomerNotes'=>$row['CustomerNotes'],
					'TermsCondition'=>$row['TermsCondition'],
					'FileName'=>$row['FileName'],
					'SubTotal'=>$row['SubTotal'],
					'DiscType'=>$row['DiscType'],
					'DiscountVal'=>$row['DiscountVal'],
					'DiscountAmount'=>$row['DiscountAmount'],

					'InvoiceTotal'=>$row['InvoiceTotal'],
					'PendingAmount'=>$PendingAmount,
					'ReceivedAmount'=>$ReceivedAmount,
					'InvoiceStatus'=>$row['InvoiceStatus'],
					//'data2'=>$data2,
				);
				unset($data2);
			}

			echo (json_encode($data1));
		}
		else
		{
			echo "NoData";
		}
	}
	else
	{
		echo "Invalid Id";
	}
}
?>