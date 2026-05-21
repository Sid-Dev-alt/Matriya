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
	$PkId = $data->PkId;
	if($PkId!="")
	{
		$delete_status = 1;	
		$data2 = array();
		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";
		$query = $dbConnection->prepare("SELECT * FROM Invoices WHERE DeleteStatus=? AND PkId=?");
		$query->execute(array($delete_status,$PkId));
		$num_rows = $query->rowCount();
		$a = "1";
		if($num_rows>0)
		{	
			$row = $query->fetch();
				$InvoiceId = $row['InvoiceId'];

				$query11 = $dbConnection->prepare("SELECT DisplayName,Mobile,EmailId FROM CustomerMaster WHERE CustomerId=? AND DeleteStatus=?");
				$query11->execute(array($row['CustomerId_CustomerMaster'],$delete_status));
				$row11 = $query11->fetch();
				$CustomerName = $row11['DisplayName'];
				$CMobile = $row11['Mobile'];
				$CEmailId = $row11['EmailId'];

				$query1 = $dbConnection->prepare("SELECT * FROM InvoiceDetails WHERE InvoiceId_Invoices=? AND DeleteStatus=?");
				$query1->execute(array($InvoiceId,$delete_status));
				while($row1 = $query1->fetch())
				{
					$InvoiceDetatilPkId = $row1['PkId'];
					$ProductId = $row1['ProductId_ProductMaster'];
					$Quantity = $row1['Quantity'];
					$Price = $row1['Price'];
					$Amount = $row1['Amount'];

					if($row1['Quantity']-$row1['PackedQty']>0)
					{

						$query2 = $dbConnection->prepare("SELECT * FROM ProductMaster WHERE ProductId=? AND DeleteStatus=?");
						$query2->execute(array($ProductId,$delete_status));
						$row2 = $query2->fetch();
						$ProductName = $row2['ProductName'];
						$SKU = $row2['SKU'];


						$data2[] = array(
						'InvoiceDetatilPkId' => $InvoiceDetatilPkId,
						'ProductId' => $ProductId,
						'quantity' => $Quantity,
						'price' => $Price,
						'Amount'=>$Amount,
						'PackedQty'=>$row1['PackedQty'],
						'qtytopack'=>$row1['Quantity']-$row1['PackedQty'],
						'product'=>$ProductName,
						'SKU'=>$SKU,
						);
					}

				}

				$data1 = array(
					'PkId' => $PkId,
					'CustomerId_CustomerMaster' => $row['CustomerId_CustomerMaster'],
					'CustomerName' => $CustomerName,
					'CMobile' => $CMobile,
					'CEmailId' => $CEmailId,
					'OrderId' => $row['OrderId'],
					'InvoiceId' => $row['InvoiceId'],
					'InvoiceDate' => $row['InvoiceDate'],
					'DueDate' => $row['DueDate'],
					'Reference' => $row['Reference'],
					'PaymentTerms'=>$row['PaymentTerms'],
					'DeliveryMethod'=>$row['DeliveryMethod'],
					'Salesperson'=>$row['Salesperson'],
					'CustomerNotes'=>$row['CustomerNotes'],
					'TermsCondition'=>$row['TermsCondition'],
					'FileName'=>$row['FileName'],
					'SubTotal'=>$row['SubTotal'],
					'AdditionalCharges'=>$row['AdditionalCharges'],
					'DiscType'=>$row['DiscType'],
					'DiscountVal'=>$row['DiscountVal'],
					'DiscountAmount'=>$row['DiscountAmount'],
					'InvoiceTotal'=>$row['InvoiceTotal'],
					'PackageStatus'=>$row['PackageStatus'],
					'ShipmentStatus'=>$row['ShipmentStatus'],
					'data2'=>$data2,
				);
				unset($data2);
			

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