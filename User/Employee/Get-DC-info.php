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
	$Type = $data->Type;
	if($PkId!="")
	{
		$delete_status = 1;	
		$data2 = array();
		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";
		$query = $dbConnection->prepare("SELECT * FROM DeliveryChallans WHERE DeleteStatus=? AND PkId=?");
		$query->execute(array($delete_status,$PkId));
		$num_rows = $query->rowCount();
		$a = "1";
		if($num_rows>0)
		{	
			$row = $query->fetch();

				$DcId = $row['DcId'];
				$query11 = $dbConnection->prepare("SELECT DisplayName,Mobile,EmailId FROM CustomerMaster WHERE CustomerId=? AND DeleteStatus=?");
				$query11->execute(array($row['CustomerId_CustomerMaster'],$delete_status));
				$row11 = $query11->fetch();
				$CustomerName = $row11['DisplayName'];
				$CMobile = $row11['Mobile'];
				$CEmailId = $row11['EmailId'];

				$query1 = $dbConnection->prepare("SELECT * FROM DeliveryChallanDetails WHERE DcId_DeliveryChallans=? AND DeleteStatus=?");
				$query1->execute(array($DcId,$delete_status));
				while($row1 = $query1->fetch())
				{

					$query2 = $dbConnection->prepare("SELECT im.ProductId_ProductMaster,im.BatchNoORSrNo,im.BatchManufacturer,im.ManfactureDate,im.ExpireDate,im.Quantity,pm.ProductName,pm.SKU FROM InventoryMaster AS im INNER JOIN ProductMaster AS pm ON im.ProductId_ProductMaster=pm.ProductId
						WHERE im.PkId=? AND im.DeleteStatus=?");
					$query2->execute(array($row1['PkId_InventoryMaster'],$delete_status));
					$row2 = $query2->fetch();
					$ProductId_ProductMaster = $row2['ProductId_ProductMaster'];
					$ProductName = $row2['ProductName'];
					$SKU = $row2['SKU'];
					$AvlQty = $row2['Quantity'];


					$data2[] = array(
					'DcId_DeliveryChallans' => $row1['DcId_DeliveryChallans'],
					'InvPkId' => $row1['PkId_InventoryMaster'],
					'quantity' => $row1['Quantity'],
					'price' => $row1['Price'],
					'Amount'=>$row1['Amount'],
					'PackedQty'=>$row1['PackedQty'],
					'qtytopack'=>$row1['Quantity']-$row1['PackedQty'],
					'ProductId_ProductMaster'=>$ProductId_ProductMaster,
					'product'=>$ProductName,
					'AvlQty'=>$AvlQty,
					'SKU'=>$SKU,
					);

				}

				$data1 = array(
					'PkId' => $row['PkId'],
					'CustomerId_CustomerMaster' => $row['CustomerId_CustomerMaster'],
					'CustomerName' => $CustomerName,
					'CMobile' => $CMobile,
					'CEmailId' => $CEmailId,
					'DcId' => $row['DcId'],
					'DcDate' => $row['DcDate'],
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
					'DCStatus'=>$row['DCStatus'],
					'InvoiceStatus'=>$row['InvoiceStatus'],
					'CreatedTime'=>$row['CreatedTime'],
					'data2'=>$data2,
					'Type'=>$Type,
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