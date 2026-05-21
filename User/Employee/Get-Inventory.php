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
//	$barcodeInput = $data->barcodeInput;
		$delete_status = 1;	
		$data2 = array();
		$data3 = array();
		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";

		$query3 = $dbConnection->prepare("SELECT PkId,PkId_RawPurchaseMasterDetails,UniqueRollNo,Quantity,ProductId_ProductMaster,ProductSize,Remarks FROM InventoryMaster WHERE DeleteStatus=? AND IsSplitQty=? AND Quantity>0 AND Status=?");
		$query3->execute(array($delete_status,'0',$delete_status));
		$query3->rowCount();
		if($query3->rowCount()>0)
		{
			while($row2 = $query3->fetch())
			{
				$InvPkId = $row2['PkId'];
				$PkId_RawPurchaseMasterDetails=$row2['PkId_RawPurchaseMasterDetails'];
				$UniqueRollNo = $row2['UniqueRollNo'];
				$quantity = $row2['Quantity'];
				$ProductId_ProductMaster = $row2['ProductId_ProductMaster'];
				$ProductSize = $row2['ProductSize'];
				$Remarks = $row2['Remarks'];

				$query1 = $dbConnection->prepare("SELECT RawPurchaseId_RawPurchaseMaster FROM RawPurchaseMasterDetails WHERE PkId=?");
				$query1->execute(array($PkId_RawPurchaseMasterDetails));
				$row1 = $query1->fetch();
				$RawPurchaseId_RawPurchaseMaster = $row1['RawPurchaseId_RawPurchaseMaster'];
				

				$query4 = $dbConnection->prepare("SELECT PkId_GoDownMaster FROM RawPurchaseMaster WHERE RawPurchaseId=?");
				$query4->execute(array($RawPurchaseId_RawPurchaseMaster));
				$row4 = $query4->fetch();
				$PkId_GoDownMaster = $row4['PkId_GoDownMaster'];

				$query5 = $dbConnection->prepare("SELECT GoDownName FROM GoDownMaster WHERE PkId=?");
				$query5->execute(array($PkId_GoDownMaster));
				$row5 = $query5->fetch();
				$GoDownName = $row5['GoDownName'];

				$query6 = $dbConnection->prepare("SELECT ProductId,ProductName,Unit,Micron FROM ProductMaster WHERE ProductId=?");
				$query6->execute(array($ProductId_ProductMaster));
				$row6 = $query6->fetch();
				$ProductId = $row6['ProductId'];
				$ProductName = $row6['ProductName'];	
				$Unit = $row6['Unit'];	
				$Micron = $row6['Micron'];	
				$TotalName= "$Micron $ProductName";	

			$data2[] = array("InvPkId"=>$InvPkId,"PkId_RawPurchaseMasterDetails"=>$PkId_RawPurchaseMasterDetails,"ProductId"=>$ProductId_ProductMaster,"ProductName"=>$ProductName,"Unit"=>$Unit,"Size"=>$ProductSize,"Remarks"=>$Remarks,"Micron"=>$Micron,"GoDownName"=>$GoDownName,"UniqueRollNo"=>$UniqueRollNo,"TotalName"=>$TotalName,"quantity"=>$quantity,);
		}
		echo json_encode($data2);	
	}
	else
	{
		echo "NoData";
	}
}
?>