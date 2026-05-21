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
	$mainarray = array();
	$data0 = array();
	$data1 = array();
	$data2 = array();
	$data3 = array();
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";

	$data = json_decode(file_get_contents("php://input"));
	$UniqueRollNo = $data->rollno;
	$entrydate = date('Y-m-d');
if($UniqueRollNo!="")
{
$query2 = $dbConnection->prepare("SELECT * FROM InventoryMaster WHERE UniqueRollNo LIKE '%$UniqueRollNo%' AND DeleteStatus=?");
$query2->execute(array($delete_status));
if($query2->rowCount()>0)
{
	$row2 = $query2->fetch();
//{
	$PkId_InventoryMaster = $row2['PkId'];
	$AvlQuantity = $row2['Quantity'];
	$PkId_RawPurchaseMasterDetails = $row2['PkId_RawPurchaseMasterDetails'];
	$IsSplitQty = $row2['IsSplitQty'];
	$InvProductSize = $row2['ProductSize'];
	$InvUniqueRollNo = $row2['UniqueRollNo'];

	$query1 = $dbConnection->prepare("SELECT rpm.RawPODate,rpm.PkId_GoDownMaster,rpm.VendorId_VendorMaster,prmd.ProductId_ProductMaster,prmd.PurchaseQty,prmd.RollNo,prmd.ProductSize FROM RawPurchaseMasterDetails AS prmd INNER JOIN  RawPurchaseMaster AS rpm ON prmd.RawPurchaseId_RawPurchaseMaster=rpm.RawPurchaseId AND prmd.PkId=?");
	$query1->execute(array($PkId_RawPurchaseMasterDetails));
	$row1 = $query1->fetch();
	$ProductId_ProductMaster = $row1['ProductId_ProductMaster'];
	$VendorId_VendorMaster = $row1['VendorId_VendorMaster'];
	$PurchaseQty = $row1['PurchaseQty'];
	$PkId_GoDownMaster = $row1['PkId_GoDownMaster'];
	$RawPODate = $row1['RawPODate'];
	$PurchaseRollNo = $row1['RollNo'];
	$ProductSize = $row1['ProductSize'];

	$query4 = $dbConnection->prepare("SELECT DisplayName FROM VendorMaster WHERE VendorId=?");
	$query4->execute(array($VendorId_VendorMaster));
	$row4 = $query4->fetch();
	$VendorName = $row4['DisplayName'];

	$query5 = $dbConnection->prepare("SELECT GoDownName FROM GoDownMaster WHERE PkId=?");
	$query5->execute(array($PkId_GoDownMaster));
	$row5 = $query5->fetch();
	$GoDownName = $row5['GoDownName'];

	$query6 = $dbConnection->prepare("SELECT ProductName,Micron,Unit FROM ProductMaster WHERE ProductId=?");
	$query6->execute(array($ProductId_ProductMaster));
	$row6 = $query6->fetch();
	$ProductName = $row6['ProductName'];
	$Micron = $row6['Micron'];
	$Unit = $row6['Unit'];
	$TotalName= "$Micron $ProductName";	

	if($IsSplitQty==1)
	{
		$query8 = $dbConnection->prepare("SELECT Slitting.SlitId,Slitting.SlitDate,SlittingRolls.* FROM Slitting INNER JOIN SlittingRolls ON Slitting.SlitId=SlittingRolls.SlitId_Slitting WHERE  Slitting.PkId_InventoryMaster=?");
	    $query8->execute(array($PkId_InventoryMaster));
	    $IsSlitted = $query8->rowCount();
		while($row8 = $query8->fetch())
		{
			$SlitId = $row8['SlitId'];
			$SlitDate = $row8['SlitDate'];
			$SplitSize = $row8['SplitSize'];
			$SplitRollNo = $row8['RollNo'];
			$SlitQty = $row8['SlitQty'];

			$data2[] = array(
				'SplitSize' => $SplitSize,
				'SplitRollNo' => $SplitRollNo,
				'SlitQty' => $SlitQty
				);
		}
	}
	$query8 = $dbConnection->prepare("SELECT Slitting.SlitId,Slitting.SlitDate,SlittingRolls.* FROM Slitting INNER JOIN SlittingRolls ON Slitting.SlitId=SlittingRolls.SlitId_Slitting WHERE  SlittingRolls.RollNo LIKE '%$UniqueRollNo%'");
    $query8->execute(array());
    $IsSlitted = $query8->rowCount();
	$row8 = $query8->fetch();
	$SlitId = $row8['SlitId'];
	$SlitDate = $row8['SlitDate'];
	$SplitSize = $row8['SplitSize'];
	$SlitQty = $row8['SlitQty'];
	

	$query9 = $dbConnection->prepare("SELECT Invoices.InvoiceId,Invoices.InvoiceDate,Invoices.CustomerName,InvoiceDetails.* FROM Invoices INNER JOIN InvoiceDetails ON Invoices.InvoiceId=InvoiceDetails.InvoiceId_Invoices INNER JOIN InventoryMaster ON InvoiceDetails.PkId_InventoryMaster=InventoryMaster.PkId WHERE InventoryMaster.Status=? AND InvoiceDetails.PkId_InventoryMaster=? AND Invoices.DeleteStatus=?");
    $query9->execute(array('0',$PkId_InventoryMaster,$delete_status));
   $IsInvoice = $query9->rowCount();
	$row9 = $query9->fetch();
	$InvoiceId = $row9['InvoiceId'];
	$InvoiceDate = $row9['InvoiceDate'];
	$CustomerName = $row9['CustomerName'];

	
	$mainarray = array('IsSplitQty'=>$IsSplitQty,'InvUniqueRollNo'=>$InvUniqueRollNo,'InvProductSize'=>$InvProductSize,'AvlQuantity'=>$AvlQuantity,'VendorName'=>$VendorName,'RawPODate'=>$RawPODate,'TotalName'=>$TotalName,'Unit'=>$Unit,'POProductSize'=>$ProductSize,'PurchaseQty'=>$PurchaseQty,'PurchaseRollNo'=>$PurchaseRollNo,'GoDownName'=>$GoDownName,'IsSlitted'=>$IsSlitted,'SlitDate'=>$SlitDate,'SplitSize'=>$SplitSize,'SlitQty'=>$SlitQty,'data2'=>$data2,'IsInvoice'=>$IsInvoice,'InvoiceDate'=>$InvoiceDate,'CustomerName'=>$CustomerName);
//}
echo json_encode($mainarray);
//	print_r($input);
}
else
{
	echo "NoData";
}
}
else
{
	echo "NoData";
}
}
?>