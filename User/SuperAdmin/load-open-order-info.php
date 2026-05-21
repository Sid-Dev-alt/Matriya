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
		$data3 = array();
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

				$query11 = $dbConnection->prepare("SELECT DisplayName,Mobile,EmailId FROM CustomerMaster WHERE CustomerId=?");
				$query11->execute(array($row['CustomerId_CustomerMaster']));
				$row11 = $query11->fetch();
				$CustomerName = $row11['DisplayName'];
				$CMobile = $row11['Mobile'];
				$CEmailId = $row11['EmailId'];

				$query1 = $dbConnection->prepare("SELECT * FROM SalesOrderDetails WHERE OrderId_SalesOrders=? AND DeleteStatus=?");
				$query1->execute(array($OrderId,$delete_status));
				while($row1 = $query1->fetch())
				{
					
						$query2 = $dbConnection->prepare("SELECT * FROM ProductMaster WHERE ProductId=?");
						$query2->execute(array($row1['ProductId_ProductMaster']));
						$row2 = $query2->fetch();
						$ProPkId = $row2['PkId'];
						$ProductName = $row2['ProductName'];
						$SKU = $row2['SKU'];
						$TrackingMode = $row2['TrackingMode'];

						$query3 = $dbConnection->prepare("SELECT SUM(Quantity)  AS AvlQty FROM InventoryMaster WHERE ProductId_ProductMaster=? AND DeleteStatus=?");
						$query3->execute(array($row1['ProductId_ProductMaster'],$delete_status));
						$row3 = $query3->fetch();
						$AvlQty = $row3['AvlQty'];


						 $ShippedQty=0;
						// $query5 = $dbConnection->prepare("SELECT * FROM Packages WHERE OrderId=? AND DeleteStatus=? AND PackageStatus=?");
						// $query5->execute(array($OrderId,$delete_status,'2'));
						// while($row5 = $query5->fetch())
						// {
						// 		$PackageId = $row5['PackageId'];

						// 		$query6 = $dbConnection->prepare("SELECT SUM(Packed)  AS shipPackedQty FROM PackageDetails WHERE PackageId_Packages=? AND DeleteStatus=?");
						// 		$query6->execute(array($PackageId,$delete_status));
						// 		$row6 = $query6->fetch();
						// 		$ShippedQty += $row6['shipPackedQty'];
						// }
						


						$query4 = $dbConnection->prepare("SELECT * FROM InventoryMaster WHERE  ProductId_ProductMaster=? AND Quantity>? AND DeleteStatus=?");
						$query4->execute(array($row1['ProductId_ProductMaster'],'0',$delete_status));
						while($row4 = $query4->fetch())
						{
							$data3[] = array(
								"InvPkId"=>$row4['PkId'],
								"batchno"=>$row4['BatchNoORSrNo'],
								"BatchManufacturer"=>$row4['BatchManufacturer'],
								"ManfactureDate"=>$row4['ManfactureDate'],
								"ExpireDate"=>$row4['ExpireDate'],
								"InvQuantity"=>$row4['Quantity'],
							);
						}

						$data2[] = array(
						'OrderId_SalesOrders' => $row1['OrderId_SalesOrders'],
						'ProductId' => $row1['ProductId_ProductMaster'],
						'OrderQuantity' => $row1['Quantity'],
						'price' => $row1['Price'],
						'Amount'=>$row1['Amount'],
						'PackedQty'=>$row1['PackedQty'],
						'ShippedQty'=>$ShippedQty,
						'InvoicedQty'=>$row1['InvoicedQty'],
						'quantity'=>$row1['Quantity']-$row1['InvoicedQty'],
						'product'=>$ProductName,
						'AvlQty'=>$AvlQty,
						'SKU'=>$SKU,
						'TrackingMode'=>$TrackingMode,
						'InvArr'=>$data3);
						unset($data3);
				}

				$data1 = array(
					'PkId' => $row['PkId'],
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
					'AdditionalCharges'=>$row['AdditionalCharges'],
					'DiscType'=>$row['DiscType'],
					'DiscountVal'=>$row['DiscountVal'],
					'DiscountAmount'=>$row['DiscountAmount'],
					'OrderTotal'=>$row['OrderTotal'],
					'OrderStatus'=>intval($row['OrderStatus']),
					'PackageStatus'=>intval($row['PackageStatus']),
					'InvoiceStatus'=>intval($row['InvoiceStatus']),
					'ShipmentStatus'=>intval($row['ShipmentStatus']),
					'CreatedTime'=>$row['CreatedTime'],
					'data2'=>$data2,
					'Type'=>$Type,
				);
				unset($data2);
				unset($data3);
			

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