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
		$data3 = array();
		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";
		$query = $dbConnection->prepare("SELECT * FROM PurchaseOrders WHERE DeleteStatus=? AND PkId=?");
		$query->execute(array($delete_status,$PkId));
		$num_rows = $query->rowCount();
		$a = "1";
		if($num_rows>0)
		{	
				$row = $query->fetch();
				$POrderId = $row['POrderId'];

				$query11 = $dbConnection->prepare("SELECT DisplayName,Mobile,EmailId FROM VendorMaster WHERE VendorId=? AND DeleteStatus=?");
				$query11->execute(array($row['VendorId_VendorMaster'],$delete_status));
				$row11 = $query11->fetch();
				$CustomerName = $row11['DisplayName'];
				$CMobile = $row11['Mobile'];
				$CEmailId = $row11['EmailId'];

				$query1 = $dbConnection->prepare("SELECT * FROM PurchaseOrderDetails WHERE POrderId_PurchaseOrders=? AND DeleteStatus=?");
				$query1->execute(array($POrderId,$delete_status));
				while($row1 = $query1->fetch())
				{
					// if($row1['Quantity']!=$row1['InvoicedQty'])
					// {
						$query2 = $dbConnection->prepare("SELECT * FROM ProductMaster WHERE ProductId=? AND DeleteStatus=?");
						$query2->execute(array($row1['ProductId_ProductMaster'],$delete_status));
						$row2 = $query2->fetch();
						$ProPkId = $row2['PkId'];
						$ProductName = $row2['ProductName'];
						$SKU = $row2['SKU'];
						$TrackingMode = $row2['TrackingMode'];

						$query3 = $dbConnection->prepare("SELECT SUM(Quantity)  AS AvlQty FROM InventoryMaster WHERE ProductId_ProductMaster=? AND DeleteStatus=?");
						$query3->execute(array($row1['ProductId_ProductMaster'],$delete_status));
						$row3 = $query3->fetch();
						$AvlQty = $row3['AvlQty'];


						// $query4 = $dbConnection->prepare("SELECT * FROM InventoryMaster WHERE  ProductId_ProductMaster=? AND Quantity>? AND DeleteStatus=?");
						// $query4->execute(array($row1['ProductId_ProductMaster'],'0',$delete_status));
						// while($row4 = $query4->fetch())
						// {
						// 	$data3[] = array(
						// 		"InvPkId"=>$row4['PkId'],
						// 		"batchno"=>$row4['BatchNoORSrNo'],
						// 		"BatchManufacturer"=>$row4['BatchManufacturer'],
						// 		"ManfactureDate"=>$row4['ManfactureDate'],
						// 		"ExpireDate"=>$row4['ExpireDate'],
						// 		"InvQuantity"=>$row4['Quantity'],
						// 	);
						// }

						$data2[] = array(
						'POrderId_PurchaseOrders' => $row1['POrderId_PurchaseOrders'],
						'ProductId' => $row1['ProductId_ProductMaster'],
						'OrderQuantity' => $row1['Quantity'],
						'price' => $row1['Price'],
						'Amount'=>$row1['Amount'],
						//'PackedQty'=>$row1['PackedQty'],
						//'InvoicedQty'=>$row1['InvoicedQty'],
						//'quantity'=>$row1['Quantity']-$row1['InvoicedQty'],
						'quantity'=>$row1['Quantity'],
						'product'=>$ProductName,
						'AvlQty'=>$AvlQty,
						'SKU'=>$SKU,
						'TrackingMode'=>$TrackingMode,
						);
						//unset($data3);
					//}
				}

				$data1 = array(
					'PkId' => $row['PkId'],
					'VendorId_VendorMaster' => $row['VendorId_VendorMaster'],
					'CustomerName' => $CustomerName,
					'CMobile' => $CMobile,
					'CEmailId' => $CEmailId,
					'POrderId' => $row['POrderId'],
					'POrderDate' => $row['POrderDate'],
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
					'POStatus'=>$row['POStatus'],
					'CreatedTime'=>$row['CreatedTime'],
					'data2'=>$data2,
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