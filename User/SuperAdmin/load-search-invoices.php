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
	$data1 = array();
	$data2 = array();
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	 $data = json_decode(file_get_contents("php://input"));
	 $itemsPerPage = $data->itemsPerPage;
	 $pagenumber = $data->pagenumber;
	$first = ($pagenumber-1)*$itemsPerPage;
	$searchdisid = $data->searchdisid;
	$searchfromdate = $data->searchfromdate;
	$searchtodate = $data->searchtodate;
	$partyname = $data->partyname;
	$searchfromdate = date("Y-m-d", strtotime($searchfromdate));
	$searchtodate = date("Y-m-d", strtotime($searchtodate));
	$searchtype = $data->searchtype;
	
	$final = "";
	if($searchdisid!="")
	{
		$final .= "InvoiceId='$searchdisid' AND ";
	}
	if($searchfromdate!="" && $searchtodate!="" && $searchfromdate>"1970-01-01" && $searchtodate>"1970-01-01" && $searchtype=="From & To Date")
	{
		$final .= "(InvoiceDate BETWEEN '$searchfromdate' AND '$searchtodate') AND ";
	}
	if($partyname!="")
	{
		$final .= "CustomerId_CustomerMaster='$partyname' AND ";
	}
	
	if($final!="")
	{
		$qry .= "" .substr($final, 0, -4);
	}
	else
	{
		$qry = "";
	}
	
	$query = $dbConnection->prepare("SELECT * FROM Invoices WHERE DeleteStatus=? AND $qry GROUP BY InvoiceId ORDER BY PkId DESC LIMIT $first,$itemsPerPage");
	 //$query = $dbConnection->prepare("SELECT * FROM Invoices WHERE DeleteStatus=?");
	$query->execute(array($delete_status));
	$num_rows = $query->rowCount();
	$a = "1";
	if($num_rows>0)
	{	
		while($row = $query->fetch())
		{
			$InvoiceId = $row['InvoiceId'];
			$UserId_Users = $row['UserId_Users'];
			$InvoiceStatus = $row['InvoiceStatus'];
			$IsSaveDraft = $row['IsSaveDraft'];

			$query11 = $dbConnection->prepare("SELECT Name FROM Users WHERE UserId=?");
			$query11->execute(array($UserId_Users));
			$row11 = $query11->fetch();
			$UserName = $row11['Name'];

			if($IsSaveDraft=="Yes")
			{
				$query1 = $dbConnection->prepare("SELECT * FROM DraftInvoiceDetails WHERE InvoiceId_Invoices=? AND DeleteStatus=?");
				$query1->execute(array($InvoiceId,$delete_status));
				while($row1 = $query1->fetch())
				{
					$PkId = $row1['PkId'];
					$query3 = $dbConnection->prepare("SELECT * FROM InventoryMaster WHERE PkId=?");
					$query3->execute(array($row1['PkId_InventoryMaster']));
					$row3 = $query3->fetch();
					$ProductId_ProductMaster=$row3['ProductId_ProductMaster'];
					$batchno=$row3['BatchNoORSrNo'];
					//$Colour=$row3['Colour'];
					$AvlQuantity=$row3['Quantity'];
					$UniqueRollNo=$row3['UniqueRollNo'];
					$Size = $row3['ProductSize'];
					
					$query2 = $dbConnection->prepare("SELECT PkId_Category,PkId_SubCategoryMaster,PkId_Level2SubCategoryMaster,PkId_Level3SubCategoryMaster,ProductName,Micron,Size,SalesPrice,PurchasePrice FROM ProductMaster WHERE ProductId=? AND DeleteStatus=?");
					$query2->execute(array($ProductId_ProductMaster,$delete_status));
					$row2 = $query2->fetch();
					$PkId_Category = $row2['PkId_Category'];
					$PkId_SubCategoryMaster = $row2['PkId_SubCategoryMaster'];
					$PkId_Level2SubCategoryMaster = $row2['PkId_Level2SubCategoryMaster'];
					$PkId_Level3SubCategoryMaster = $row2['PkId_Level3SubCategoryMaster'];
					$ProductName = $row2['ProductName'];
					$Micron = $row2['Micron'];
					$PMSize = $row2['Size'];
					// $SalesPrice = $row2['SalesPrice'];					
					// $PurchasePrice = $row2['PurchasePrice'];

					$query4 = $dbConnection->prepare("SELECT CategoryName FROM Category WHERE  PkId=?");
					$query4->execute(array($PkId_Category));
					$rows4 = $query4->fetch();
					$CategoryName = $rows4['CategoryName'];

					// $query6 = $dbConnection->prepare("SELECT SubCategoryName FROM SubCategoryMaster WHERE PkId=?");
					// $query6->execute(array($PkId_SubCategoryMaster));
					// $rows6 = $query6->fetch();
					// $SubCategoryName = $rows6['SubCategoryName'];

					// $query7 = $dbConnection->prepare("SELECT Level2SCName FROM Level2SubCategoryMaster WHERE  PkId=?");
					// $query7->execute(array($PkId_Level2SubCategoryMaster));
					// $rows7 = $query7->fetch();
					// $Level2SCName = $rows7['Level2SCName'];

					$data2[] = array(
					'InvoiceId_Invoices' => $row1['InvoiceId_Invoices'],
					'InvPkId' => $row1['PkId_InventoryMaster'],
					"ProductId"=>$ProductId_ProductMaster,
					"ProductName"=>$ProductName,
					 "Micron"=>$Micron,
					 "Size"=>$Size,
					 "UniqueRollNo"=>$UniqueRollNo,
					 "AvlQty"=>$AvlQuantity,
					'quantity' => $row1['Quantity'],
					// 'price' => $row1['Price'],
					// 'amount'=>$row1['Amount'],
					// 'gstrate'=>$row1['TaxRate'],
					// 'gstamount'=>$row1['TaxAmount'],
					// 'finaltotal'=>$row1['TotalAmount']
					);
				}
			}
			if($IsSaveDraft=="No")
			{
				$query1 = $dbConnection->prepare("SELECT * FROM InvoiceDetails WHERE InvoiceId_Invoices=? AND DeleteStatus=?");
				$query1->execute(array($InvoiceId,$delete_status));
				while($row1 = $query1->fetch())
				{
					$EntryPkId = $row1['PkId'];
					$query3 = $dbConnection->prepare("SELECT * FROM InventoryMaster WHERE PkId=?");
					$query3->execute(array($row1['PkId_InventoryMaster']));
					$row3 = $query3->fetch();
					$ProductId_ProductMaster=$row3['ProductId_ProductMaster'];
					$batchno=$row3['BatchNoORSrNo'];
					//$Colour=$row3['Colour'];
					$AvlQuantity=$row3['Quantity'];
					$UniqueRollNo=$row3['UniqueRollNo'];
					$Size = $row3['ProductSize'];
					
					$query2 = $dbConnection->prepare("SELECT PkId_Category,PkId_SubCategoryMaster,PkId_Level2SubCategoryMaster,PkId_Level3SubCategoryMaster,ProductName,Micron,Size,SalesPrice,PurchasePrice FROM ProductMaster WHERE ProductId=? AND DeleteStatus=?");
					$query2->execute(array($ProductId_ProductMaster,$delete_status));
					$row2 = $query2->fetch();
					$PkId_Category = $row2['PkId_Category'];
					$PkId_SubCategoryMaster = $row2['PkId_SubCategoryMaster'];
					$PkId_Level2SubCategoryMaster = $row2['PkId_Level2SubCategoryMaster'];
					$PkId_Level3SubCategoryMaster = $row2['PkId_Level3SubCategoryMaster'];
					$ProductName = $row2['ProductName'];
					$Micron = $row2['Micron'];
					$PMSize = $row2['Size'];
					// $SalesPrice = $row2['SalesPrice'];					
					// $PurchasePrice = $row2['PurchasePrice'];

					$query4 = $dbConnection->prepare("SELECT CategoryName FROM Category WHERE  PkId=?");
					$query4->execute(array($PkId_Category));
					$rows4 = $query4->fetch();
					$CategoryName = $rows4['CategoryName'];

					// $query6 = $dbConnection->prepare("SELECT SubCategoryName FROM SubCategoryMaster WHERE PkId=?");
					// $query6->execute(array($PkId_SubCategoryMaster));
					// $rows6 = $query6->fetch();
					// $SubCategoryName = $rows6['SubCategoryName'];

					// $query7 = $dbConnection->prepare("SELECT Level2SCName FROM Level2SubCategoryMaster WHERE  PkId=?");
					// $query7->execute(array($PkId_Level2SubCategoryMaster));
					// $rows7 = $query7->fetch();
					// $Level2SCName = $rows7['Level2SCName'];

					$data2[] = array(
					'EntryPkId' => $EntryPkId,	
					'InvoiceId_Invoices' => $row1['InvoiceId_Invoices'],
					'InvPkId' => $row1['PkId_InventoryMaster'],
					"ProductId"=>$ProductId_ProductMaster,
					"ProductName"=>$ProductName,
					 "Micron"=>$Micron,
					 "Size"=>$Size,
					 "UniqueRollNo"=>$UniqueRollNo,
					 "AvlQty"=>$AvlQuantity,
					'quantity' => $row1['Quantity'],
					'DeleteStatus' => $row1['DeleteStatus'],
					// 'price' => $row1['Price'],
					// 'amount'=>$row1['Amount'],
					// 'gstrate'=>$row1['TaxRate'],
					// 'gstamount'=>$row1['TaxAmount'],
					// 'finaltotal'=>$row1['TotalAmount']
					);
				}
			}

			$data1[] = array(
				'PkId' => $row['PkId'],
				'CustomerId_CustomerMaster' => $row['CustomerId_CustomerMaster'],
				'CustomerName' => $row['CustomerName'],
				'CustomerMobile'=>$row['CustomerMobile'],
				'CustomerPlace' => $row['CustomerPlace'],
				'UserName' => $UserName,
				'IsSaveDraft' => $IsSaveDraft,
				'InvoiceId' => $row['InvoiceId'],
				'OrderId' => $row['OrderId'],
				'InvoiceDate' => $row['InvoiceDate'],
				'DueDate' => $row['DueDate'],
				'Reference' => $row['Reference'],
				'DueDate'=>$row['DueDate'],
				'PaymentTerms'=>$row['PaymentTerms'],
				
				//'DeliveryMethod'=>$row['DeliveryMethod'],
				'CustomerNotes'=>$row['CustomerNotes'],
				'TermsCondition'=>$row['TermsCondition'],
				'FileName'=>$row['FileName'],
				'SubTotal'=>$row['SubTotal'],
				'AdditionalCharges'=>$row['AdditionalCharges'],
				'GST'=>$row['GST'],
				'GSTAmount'=>$row['GSTAmount'],
				'DiscType'=>$row['DiscType'],
				'DiscountVal'=>$row['DiscountVal'],
				'DiscountAmount'=>$row['DiscountAmount'],
				'InvoiceTotal'=>$row['InvoiceTotal'],
				'InvoiceStatus'=>$InvoiceStatus,
				'PackageStatus'=>intval($row['PackageStatus']),
				'ShipmentStatus'=>intval($row['ShipmentStatus']),
				'data2'=>$data2,
			);
			unset($data2);
		}
		
		if($searchdisid!="")
		{
			$sql = $dbConnection->prepare("SELECT COUNT(PkId) AS Total FROM Invoices WHERE DeleteStatus=? AND InvoiceId='$searchdisid' GROUP BY InvoiceId ORDER BY PkId DESC");
			$sql->execute(array($delete_status));
			$row4 = $sql->fetch();
			$Total = $row4['Total'];
		}
		if($searchfromdate!="" && $searchtodate!="" && $searchfromdate>"1970-01-01" && $searchtodate>"1970-01-01" && $searchtype=="From & To Date")
		{
			$sql = $dbConnection->prepare("SELECT COUNT(PkId) AS Total FROM Invoices WHERE DeleteStatus=? AND InvoiceDate BETWEEN '$searchfromdate' AND '$searchtodate' ORDER BY PkId DESC");
			$sql->execute(array($delete_status));
			$row4 = $sql->fetch();
			$Total = $row4['Total'];
		}
		if($partyname!="")
		{
			$sql = $dbConnection->prepare("SELECT * FROM Invoices WHERE DeleteStatus=? AND $qry GROUP BY InvoiceId ORDER BY PkId DESC");
			$sql->execute(array($delete_status));
			$count_rows = $sql->rowCount();
			$Total = $count_rows;
			// $final .= "RawPurchaseMasterDetails.ProductId_ProductMaster='$itemname' AND ";
		}
		
		$data3 = array("Total"=>$Total,"data1"=>$data1);
		echo json_encode($data3);
	}
	else
	{
		echo "NoData";
	}
	
}
?>
