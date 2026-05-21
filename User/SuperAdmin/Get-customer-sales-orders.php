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
	$customerid = $data->customerid;
	if($customerid!="")
	{
		$delete_status = 1;	
		$data2 = array();
		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";
		$query = $dbConnection->prepare("SELECT * FROM SalesOrders WHERE DeleteStatus=? AND CustomerId_CustomerMaster=?  AND PackageStatus<?");
		$query->execute(array($delete_status,$customerid,'2'));
		$num_rows = $query->rowCount();
		$a = "1";	
		if($num_rows>0)
		{	
			while($row = $query->fetch())
			{
				$OrderId = $row['OrderId'];
				
				$data1[] = array(
					'PkId' => $row['PkId'],
					'CustomerId_CustomerMaster' => $row['CustomerId_CustomerMaster'],
					'OrderId' => $row['OrderId'],
					'OrderDate' => $row['OrderDate'],
					'Reference' => $row['Reference'],
					'ShipmentDate'=>$row['ShipmentDate'],
					'PaymentTerms'=>$row['PaymentTerms'],
					'DeliveryMethod'=>$row['DeliveryMethod'],
					'Salesperson'=>$row['Salesperson'],
					'CustomerNotes'=>$row['CustomerNotes'],
					'TermsCondition'=>$row['TermsCondition'],
					'FileName'=>$row['FileName'],
					'SubTotal'=>$row['SubTotal'],
					'DiscType'=>$row['DiscType'],
					'DiscountVal'=>$row['DiscountVal'],
					'DiscountAmount'=>$row['DiscountAmount'],
					'OrderTotal'=>$row['OrderTotal'],
					'OrderStatus'=>$row['OrderStatus'],
					'InvoiceStatus'=>$row['InvoiceStatus'],
					'data2'=>$data2,
				);
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