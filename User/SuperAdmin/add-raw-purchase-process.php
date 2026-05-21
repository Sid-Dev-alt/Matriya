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
		$POrderId = $data->POrderId;
		$entrydate = $data->entrydate;
		$VendorId = $data->VendorId;
		$ProductId = $data->ProductId;
		$ProductSize = $data->ProductSize;
		$quantity = $data->quantity;
		$remarks = $data->remarks;

		$delete_status = 1;
		$check = 0;
		$Errors = "";
		if($VendorId=="")
		{
			$check = 1;
			$Errors .= "Please Select Vendor"."\n";
		}
		// if($GoDownId=="")
		// {
		// 	$check = 1;
		// 	$Errors .= "Please Select GoDown"."\n";
		// }
		if($entrydate=="")
		{
			$check = 1;
			$Errors .= "Date is required"."\n";
		}
		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";

		 $Ordrchk=array();	
		 $Sizechk = array(); 
	  $Qtychk=array();	
	  $err = "";
  	foreach ($ProductId as $key => $value) {
	 	# code...
		$query4 = $dbConnection->prepare("SELECT PkId_Category,ProductName,Micron,Unit FROM ProductMaster WHERE ProductId=?");
		$query4->execute(array($ProductId[$key]));
		$row4 = $query4->fetch();
		$PkId_Category = $row4['PkId_Category'];
		$ProductName = $row4['ProductName'];
		$Micron = $row4['Micron'];
		$Unit = $row4['Unit'];

		$query11 = $dbConnection->prepare("SELECT CategoryName FROM Category WHERE PkId=?");
		$query11->execute(array($PkId_Category));
		$row11 = $query11->fetch();
		$CategoryName = $row11['CategoryName'];
		
	 	if($ProductId[$key]=="" || empty($ProductId[$key]))
    	{
    		$Ordrchk[]=1;	
    	}
    	else
    	{
    		$Ordrchk[]=0;
    	}
    	if($PkId_Category=='1' &&  $ProductSize[$key]=="")
    	{
			//$err = "Size is mandatory for Film Category items"."\n";
    		$Sizechk[]=1;	
    	}
    	else
    	{
    		$Sizechk[]=0;
    	}
    	if($quantity[$key]<0 || $quantity[$key]==0 || $quantity[$key]=="0.00" || $quantity[$key]=="0.000")
    	{
    		$Qtychk[]=1;	
    	}
    	else
    	{
    		$Qtychk[]=0;
    	}
	 }
	 //print_r($Ordrchk);
		//if(array_sum($Ordrchk)==0)
	 if(in_array('1', $Ordrchk))
	{
		$check = 1;
		$Errors .= "Please Select Item Name"."\n";
	}
	if(in_array('1', $Sizechk))
	{
		$check = 1;
		$Errors .= "Size is mandatory for Film Category items"."\n";
	}
	//if(array_sum($Qtychk)==0)
	if(in_array('1', $Qtychk))
	{
		$check = 1;
		$Errors .= "Quantity is required"."\n";
	}
	    if($check==1){
		echo $Errors;
		}
		if($check==0)
		{
			
			$entrydate = date("Y-m-d", strtotime($entrydate));
			$Dt = date("d", strtotime($entrydate));
			$Month = date("m", strtotime($entrydate));
			$DtMonth = $Dt."/".$Month;
			
			$SAId = $_SESSION['UserId'];
			$DATEIME = GetDateTime();
			$todaydt = GetTDate();
			//echo "ok";
			// $sizearr = explode('-', $productsize);
			// $size = $sizearr[1];

			$query1 = $dbConnection->prepare("INSERT INTO RawPurchaseMaster (RawPurchaseId,RawPODate,VendorId_VendorMaster,UserId_Users,CreatedTime) VALUES (?,?,?,?,?)");
			$query1->execute(array($POrderId,$entrydate,$VendorId,$SAId,$DATEIME));

			// $query9 = $dbConnection->prepare("SELECT Quantity FROM InventoryMaster WHERE ProductId_ProductMaster=?");
			// $query9->execute(array($productid));
			// $row9 = $query9->fetch();
			// $AvlQuantity = $row9['Quantity'];
			// $FreshQty = $AvlQuantity+$quantity;
			// $FreshQty = number_format((float)$FreshQty, 3, '.', '');
			// $query5 = $dbConnection->prepare("UPDATE InventoryMaster SET Quantity=? WHERE ProductId_ProductMaster=?");
			// $query5->execute(array($FreshQty,$productid));
			foreach ($ProductId as $key => $value) {
				# code...			
				$query2 = $dbConnection->prepare("SELECT RollId FROM RawPurchaseMasterDetails WHERE TDate=? AND DeleteStatus=? ORDER BY PkId DESC LIMIT 0,1");
				$query2->execute(array($entrydate,$delete_status));
				if($query2->rowCount()>0)
				{
					$row2 = $query2->fetch();
					$variable = $row2['RollId'] + 1;
					$length = strlen($variable);
					if(strlen($variable)<3)
					{
						switch($length)
						{
							case 2:
							$variable = "0".$variable;
							break;
							case 1:
							$variable = "00".$variable;
							break;
						}
					}
					$RollId = $variable;
				}
				else
				{
					$RollId = "001";
				}

				$str2 = substr($POrderId, 3); 
				$totalroll = $DtMonth."|"."P".$str2."|".$RollId;

				$query9 = $dbConnection->prepare("INSERT INTO RawPurchaseMasterDetails (RawPurchaseId_RawPurchaseMaster,ProductId_ProductMaster,ProductSize,TDate,RollId,RollNo,PurchaseQty,Remarks,CreatedTime) VALUES (?,?,?,?,?,?,?,?,?)");
				$query9->execute(array($POrderId,$ProductId[$key],$ProductSize[$key],$entrydate,$RollId,$totalroll,$quantity[$key],$remarks[$key],$DATEIME));
				$lastid = $dbConnection->lastInsertId();

				$query10 = $dbConnection->prepare("INSERT INTO InventoryMaster (PkId_RawPurchaseMasterDetails,ProductId_ProductMaster,ProductSize,UniqueRollNo,Quantity,Remarks,CreatedTime) VALUES (?,?,?,?,?,?,?)");
				$query10->execute(array($lastid,$ProductId[$key],$ProductSize[$key],$totalroll,$quantity[$key],$remarks[$key],$DATEIME));
			

				
				$Post = "Created production with (RawPurchaseMaster (RawPurchaseId: $POrderId,RawPODate: $entrydate,VendorId_VendorMaster: $VendorId)) and (RawPurchaseMasterDetails (RawPurchaseId_RawPurchaseMaster: $POrderId,ProductId_ProductMaster: $ProductId[$key],ProductSize: $ProductSize[$key],TDate: $entrydate,RollId: $RollId,RollNo: $totalroll,PurchaseQty: $quantity[$key],Remarks: $remarks[$key])) as on $DATEIME by $SAId";
				$Type="Purchase";		
				$query25 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
				$query25->execute(array($SAId,$Post,$Type,$POrderId,$DATEIME));
			}

			 echo $result= "Success";
		}
}	 
?>
