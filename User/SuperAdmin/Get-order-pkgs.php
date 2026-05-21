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
	$PkId = $data->PkId;
	if($PkId!="")
	{
		$delete_status = 1;	
		$data2 = array();
		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";
		$query = $dbConnection->prepare("SELECT * FROM SalesOrders WHERE DeleteStatus=? AND PkId=?");
		$query->execute(array($delete_status,$PkId));
		$num_rows = $query->rowCount();
		$a = "1";
		if($num_rows>0)
		{	
			$row = $query->fetch();
				$OrderId = $row['OrderId'];

				$query11 = $dbConnection->prepare("SELECT DisplayName,Mobile,EmailId FROM CustomerMaster WHERE CustomerId=? AND DeleteStatus=?");
				$query11->execute(array($row['CustomerId_CustomerMaster'],$delete_status));
				$row11 = $query11->fetch();
				$CustomerName = $row11['DisplayName'];
				$CMobile = $row11['Mobile'];
				$CEmailId = $row11['EmailId'];

				$query1 = $dbConnection->prepare("SELECT * FROM SalesOrderDetails WHERE OrderId_SalesOrders=? AND DeleteStatus=?");
				$query1->execute(array($OrderId,$delete_status));
				while($row1 = $query1->fetch())
				{
					$SalePkId = $row1['PkId'];
					if($row1['Quantity']-$row1['PackedQty']>0)
					{

						$query2 = $dbConnection->prepare("SELECT * FROM ProductMaster WHERE ProductId=? AND DeleteStatus=?");
						$query2->execute(array($row1['ProductId_ProductMaster'],$delete_status));
						$row2 = $query2->fetch();
						
						$ProductId_ProductMaster = $row2['ProductId_ProductMaster'];
						$ProductName = $row2['ProductName'];
						$SKU = $row2['SKU'];
						$AvlQty = $row2['Quantity'];


						$data2[] = array(
						'OrderId_SalesOrders' => $row1['OrderId_SalesOrders'],
						'SalePkId' => $SalePkId,
						'ProductId' => $row1['ProductId_ProductMaster'],
						'quantity' => $row1['Quantity'],
						'price' => $row1['Price'],
						'Amount'=>$row1['Amount'],
						'PackedQty'=>$row1['PackedQty'],
						'InvoicedQty'=>$row1['InvoicedQty'],
						'qtytopack'=>$row1['Quantity']-$row1['PackedQty'],
						'ProductId_ProductMaster'=>$ProductId_ProductMaster,
						'product'=>$ProductName,
						'AvlQty'=>$AvlQty,
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