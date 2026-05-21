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
		$FormPkId = $data->FormPkId;
		$POrderId = $data->POrderId;
		$entrydate = $data->entrydate;
		$referencenum = $data->referencenum;
		$VendorId = $data->VendorId;
		$GoDownId = $data->GoDownId;

		$EntryPkId = $data->EntryPkId;
		$ProductId = $data->ProductId;
		$ProductSize = $data->ProductSize;
		$quantity = $data->quantity;
		$IsSplitQty = $data->IsSplitQty;
		$Isinvoiced = $data->Isinvoiced;
		$remarks = $data->remarks;
		$cnotes = $data->cnotes;

		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";
		
		$delete_status = 1;
		$check = 0;
		$Errors .= "";
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

		 $Ordrchk=array();	
		 $Sizechk = array();  
	  $Qtychk=array();	
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

	 	if($ProductId[$key]=="")
    	{
    		$Ordrchk[]=1;	
    	}
    	else
    	{
    		$Ordrchk[]=0;
    	}
    	if($PkId_Category=='1' &&  $ProductSize[$key]=="")
    	{
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
			//$Errors .= "Please Enter Item Size"."\n";
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
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
			$entrydate = date("Y-m-d", strtotime($entrydate));
			$Dt = date("d", strtotime($entrydate));
			$Month = date("m", strtotime($entrydate));
			$DtMonth = $Dt."/".$Month;
			
			$SAId = $_SESSION['EmpId'];
			$DATEIME = GetDateTime();
			$todaydt = GetTDate();
			//echo "ok";
			// $sizearr = explode('-', $productsize);
			// $size = $sizearr[1];

			$query1 = $dbConnection->prepare("UPDATE RawPurchaseMaster SET RawPODate=?,Reference=?,VendorId_VendorMaster=?,PkId_GoDownMaster=?,Comments=? WHERE PkId=?");
			$query1->execute(array($entrydate,$referencenum,$VendorId,$GoDownId,$cnotes,$FormPkId));

			foreach ($ProductId as $key => $value) 
			{
					# code...			
				if($EntryPkId[$key]=="")
				{
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
				}
				else
				{
					if($Isinvoiced[$key]!=1 || $IsSplitQty[$key]!=1)
					{
						$query5 = $dbConnection->prepare("SELECT * FROM RawPurchaseMasterDetails WHERE  PkId=?");
						$query5->execute(array($EntryPkId[$key]));
						while($row5 = $query5->fetch())
						{
							$OldProductId_ProductMaster = $row5['ProductId_ProductMaster'];
							$OldPurchaseQty = $row5['PurchaseQty'];
							$OldProductSize = $row5['ProductSize'];
							$OldIsSplitQty = $row5['IsSplitQty'];
							$OldRollNo = $row5['RollNo'];

							if(($OldProductId_ProductMaster==$ProductId[$key]) && ($OldProductSize==$ProductSize[$key]))
							{
								$query6 = $dbConnection->prepare("UPDATE RawPurchaseMasterDetails SET TDate=?,PurchaseQty=?,Remarks=? WHERE PkId=?");
								$query6->execute(array($entrydate,$quantity[$key],$remarks[$key],$EntryPkId[$key]));

								$query66 = $dbConnection->prepare("UPDATE InventoryMaster SET Quantity=?,Remarks=? WHERE UniqueRollNo=?");
								$query66->execute(array($quantity[$key],$remarks[$key],$OldRollNo));
							}
							else
							{		
								$query9 = $dbConnection->prepare("UPDATE RawPurchaseMasterDetails SET ProductId_ProductMaster=?,TDate=?,ProductSize=?,PurchaseQty=?,Remarks=? WHERE PkId=?");
								$query9->execute(array($ProductId[$key],$entrydate,$ProductSize[$key],$quantity[$key],$remarks[$key],$EntryPkId[$key]));

								if($OldIsSplitQty=="1")
								{
									$query11 = $dbConnection->prepare("UPDATE InventoryMaster SET ProductId_ProductMaster=?,ProductSize=?,Remarks=? WHERE PkId_RawPurchaseMasterDetails=? AND UniqueRollNo=?");
									$query11->execute(array($ProductId[$key],$ProductSize[$key],$remarks[$key],$EntryPkId[$key],$OldRollNo));	

									$query12 = $dbConnection->prepare("UPDATE InventoryMaster SET ProductId_ProductMaster=?,Remarks=? WHERE PkId_RawPurchaseMasterDetails=? AND UniqueRollNo!=?");
									$query12->execute(array($ProductId[$key],$remarks[$key],$EntryPkId[$key],$OldRollNo));	
								}
								else
								{
									$query13 = $dbConnection->prepare("UPDATE InventoryMaster SET ProductId_ProductMaster=?,ProductSize=?,Quantity=?,Remarks=? WHERE PkId_RawPurchaseMasterDetails=? AND UniqueRollNo=?");
									$query13->execute(array($ProductId[$key],$ProductSize[$key],$quantity[$key],$remarks[$key],$EntryPkId[$key],$OldRollNo));
								}
							}
						}

					}
				}
				
				$Post = "Updated production with (RawPurchaseMaster (RawPurchaseId: $POrderId,RawPODate: $entrydate,VendorId_VendorMaster: $VendorId)) and (RawPurchaseMasterDetails (RawPurchaseId_RawPurchaseMaster: $POrderId,ProductId_ProductMaster: $ProductId[$key],ProductSize: $ProductSize[$key],TDate: $entrydate,RollId: $RollId,RollNo: $totalroll,PurchaseQty: $quantity[$key],Remarks: $remarks[$key])) as on $DATEIME by $SAId";
				$Type="Purchase";		
				$query25 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
				$query25->execute(array($SAId,$Post,$Type,$POrderId,$DATEIME));
					
			}
				
			
			echo $result= "Success";
		}
}	 
?>
