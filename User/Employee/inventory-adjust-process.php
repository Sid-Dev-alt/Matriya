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
		 
		  
		  $ProductId = $data->ProductId;
		  $referencenum = $data->referencenum;
		  $entrydate = date('Y-m-d', strtotime($data->entrydate));
		  $reason = $data->reason;
		  $description = $data->description;
		  $adjust = $data->adjust;
		  $AvlQty = $data->AvlQty;

		  $openstock = $data->openstock;
		  $invtrack = $data->invtrack;
		  $serailnumber = $data->serailnumber;

		  $freshquantity = $data->freshquantity;
		 
		 $BatchNo = $data->BatchNo;
		 $ManfcBatch = $data->ManfcBatch;
		 $Mfgdate = $data->Mfgdate;
		 $Expdate = $data->Expdate;
		 $AddQuantity = $data->AddQuantity;


		  $InvPkId = $data->InvPkId;
		  $quantity = $data->quantity;
		 $setquantity = $data->setquantity;
		 $newquantity = $data->newquantity;

		 $sum = $data->sum;
		 $subsum = $data->subsum;
		 
		
		$snocount = substr_count( $serailnumber, ",") +1; 
		 
	$serialchk=array();	 
   $var=explode(',',$serailnumber);
   foreach($var as $row)
    {
    	//echo $row.""."a\n";
    	if($row!="")
    	{
    		$serialchk[]=1;	
    	}
    	else
    	{
    		$serialchk[]=0;
    	}
    }

$BatchDtChk=array();
    if($invtrack=="Batch")
    {
	  	foreach ($BatchNo as $key => $value) {
		 	# code...
		 if($Expdate[$key]!="" && $Mfgdate[$key]!="")	
		 {
		 	if(date('Y-m-d',strtotime($Expdate[$key]))>date('Y-m-d',strtotime($Mfgdate[$key])))
	    	{
	    		$BatchDtChk[]=1;	//true
	    	}
	    	else
	    	{
	    		$BatchDtChk[]=0; //false
	    	}
		 }
		}
    }

    $Batchchk=array();	 
  	foreach ($BatchNo as $key => $value) {
	 	# code...
	 	if($BatchNo[$key]!="")
    	{
    		$Batchchk[]=1;	
    	}
    	else
    	{
    		$Batchchk[]=0;
    	}
	 }

	  $Qtychk=array();	 
  	foreach ($AddQuantity as $key => $value) {
	 	# code...
	 	if($AddQuantity[$key]!="")
    	{
    		$Qtychk[]=1;	
    	}
    	else
    	{
    		$Qtychk[]=0;
    	}
	 }

	 $SetQtychk=array();	 
  	foreach ($setquantity as $key => $value) {
	 	# code...
	 	if($setquantity[$key]>0)
    	{
    		$SetQtychk[]=1;	
    	}
    	else
    	{
    		$SetQtychk[]=0;
    	}
	 }
    //print_r($serialchk);

		 $delete_status = 1;
		if($ProductId!="" && $entrydate!="" && $reason!="" && $adjust!="")
		{
			$check = 0;
			$Errors .= "";
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
			$SAId = $_SESSION['EmpId'];
			$DATEIME = GetDateTime();
			$todaydt = GetTDate();
			$PArr = array();
			$skuArr = array();
			
			if($invtrack=="None")
			{
				if($invtrack=="None" && $openstock=="")
				{
					$check = 1;
					echo $Errors .= "Please enter quantity"."\n";
				}
				else
				{
					// if($ModeofAdjust=="Add")
					// {
					// 	$Type = "Add Inv Adjust";
					// }else if($ModeofAdjust=="Minus")
					// {
					// 	$Type = "Minus Inv Adjust";
					// }
					$query1 = $dbConnection->prepare("INSERT INTO InventoryAdjustments (Reference,EntryDate,Reason,Description,ModeofAdjust,CreatedTime) VALUES (?,?,?,?,?,?)");
					 $query1->execute(array($referencenum,$entrydate,$reason,$description,$adjust,$DATEIME));
					$APkId = $dbConnection->lastInsertId();

					$query5 = $dbConnection->prepare("INSERT INTO InventoryAdjustmentDetails (PkId_InventoryAdjustments,ProductId_ProductMaster,Quantity,CreatedTime) VALUES (?,?,?,?)");
					$query5->execute(array($APkId,$ProductId,$openstock,$DATEIME));

					$query = $dbConnection->prepare("UPDATE InventoryMaster SET Quantity=? WHERE ProductId_ProductMaster=?");
					$query->execute(array($freshquantity,$ProductId));

					// $query4 = $dbConnection->prepare("INSERT INTO Product_Transaction (TDate,Type,ReferenceId,ProductId_ProductMaster,Quantity,Opening_Balance,Closing_Balance,CreatedTime) VALUES (?,?,?,?,?,?,?,?)");
					// $query4->execute(array($todaydt,$Type,$APkId,$ProductId,$openstock,$AvlQty,$freshquantity,$DATEIME));
				}
				
				$Post = "Updated Quantity of the product as on $DATEIME";
				$Type="Product";		
				$query4 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
				$query4->execute(array($SAId,$Post,$Type,$ProductId,$DATEIME));
				echo $result= "Success";
			}

			if($invtrack=="Serial")
			{
				if($invtrack=="Serial" && $adjust=="Add" && $openstock=="")
				{
					$check = 1;
					$Errors .= "Please enter quantity"."\n";
				}

				if($invtrack=="Serial" && $adjust=="Add" && $serailnumber=="")
				{
					$check = 1;
					$Errors .= "Please make sure that you have entered serial numbers for all the item units"."\n";
				}
				if($serailnumber!="" && $adjust=="Add" && $snocount!=$openstock)
				{
					$check = 1;
					$Errors .= "Looks like there's a mismatch between the quantity and the serial numbers entered"."\n";
				}
				if($invtrack=="Serial" && $adjust=="Add" && in_array('0', $serialchk))
				{
					$check = 1;
					$Errors .= "Looks like some of serial numbers is empty"."\n";
				}

				if($check==1){
				echo $Errors;
				}
				else 
				{
					if($adjust=="Add")
					{
						//$fresh = $AvlQty+1;
						$query1 = $dbConnection->prepare("INSERT INTO InventoryAdjustments (Reference,EntryDate,Reason,Description,ModeofAdjust,CreatedTime) VALUES (?,?,?,?,?,?)");
						 $query1->execute(array($referencenum,$entrydate,$reason,$description,$adjust,$DATEIME));
						 $PkId = $dbConnection->lastInsertId();

						 $var=explode(',',$serailnumber);
					   foreach($var as $row)
					    {
						# code...
							$query2 = $dbConnection->prepare("INSERT INTO InventoryMaster (ProductId_ProductMaster,BatchNoORSrNo,Quantity,CreatedTime) VALUES (?,?,?,?)");
							$query2->execute(array($ProductId,$row,'1',$DATEIME));
							$InvPkId = $dbConnection->lastInsertId();

							$query3 = $dbConnection->prepare("INSERT INTO InventoryAdjustmentDetails (PkId_InventoryAdjustments,ProductId_ProductMaster,PkId_InventoryMaster,Quantity,CreatedTime) VALUES (?,?,?,?,?)");
							$query3->execute(array($PkId,$ProductId,$InvPkId,'1',$DATEIME));

							// $query4 = $dbConnection->prepare("INSERT INTO Product_Transaction (TDate,Type,ReferenceId,ProductId_ProductMaster,Quantity,Opening_Balance,Closing_Balance,CreatedTime) VALUES (?,?,?,?,?,?,?,?)");
							// $query4->execute(array($todaydt,'Add Inv Adjust',$InvPkId,$ProductId,'1',$AvlQty,$fresh,$DATEIME));
						}
					}
					if($adjust=="Minus")
					{
						if(in_array('1', $SetQtychk))
						{
							$query1 = $dbConnection->prepare("INSERT INTO InventoryAdjustments (Reference,EntryDate,Reason,Description,ModeofAdjust,CreatedTime) VALUES (?,?,?,?,?,?)");
							 $query1->execute(array($referencenum,$entrydate,$reason,$description,$adjust,$DATEIME));
							 $PkId = $dbConnection->lastInsertId();						

							foreach ($InvPkId as $key => $value) {
								# code...
								$InvPkId1 = $InvPkId[$key];
								$newquantity1 = $quantity[$key]-$setquantity[$key];

								if($setquantity[$key]>0)
								{
									$query3 = $dbConnection->prepare("INSERT INTO InventoryAdjustmentDetails (PkId_InventoryAdjustments,ProductId_ProductMaster,PkId_InventoryMaster,Quantity,CreatedTime) VALUES (?,?,?,?,?)");
									$query3->execute(array($PkId,$ProductId,$InvPkId1,$setquantity[$key],$DATEIME));

									$query2 = $dbConnection->prepare("UPDATE InventoryMaster SET Quantity=? WHERE PkId=?");
									$query2->execute(array($newquantity1,$InvPkId1));
								}
							}
						}
					}
					$Post = "Updated Quantity of the product as on $DATEIME";
					$Type="Product";		
					$query4 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
					$query4->execute(array($SAId,$Post,$Type,$ProductId,$DATEIME));
					echo $result= "Success";
				}
			}
		
		if($invtrack=="Batch")
		{	
			if($invtrack=="Batch" && $adjust=="Add" && in_array('0', $Batchchk))
			{
				$check = 1;
				$Errors .= "BatchNo could not be empty"."\n";
			}
			if($invtrack=="Batch" && $adjust=="Add" &&  in_array('0', $Qtychk))
			{
				$check = 1;
				$Errors .= "Quantity could not be empty"."\n";
			}
			if($invtrack=="Batch" &&  in_array('0', $BatchDtChk))
			{
				$check = 1;
				$Errors .= "Expiry date should be greater than the Manufacture date"."\n";
			}

		    if($check==1){
			echo $Errors;
			}
			else 
			{
			
				if($adjust=="Add")
				{
					$query1 = $dbConnection->prepare("INSERT INTO InventoryAdjustments (Reference,EntryDate,Reason,Description,ModeofAdjust,CreatedTime) VALUES (?,?,?,?,?,?)");
					 $query1->execute(array($referencenum,$entrydate,$reason,$description,$adjust,$DATEIME));
					 $PkId = $dbConnection->lastInsertId();		

					foreach ($BatchNo as $key => $value) {
					# code...
						$BatchNo1 = $BatchNo[$key];
						$ManfcBatch1 = $ManfcBatch[$key];
						if($Mfgdate[$key]=="")
						{
							$Mfgdate1 = "";
						}
						else
						{
							$Mfgdate1 = date('Y-m-d',strtotime($Mfgdate[$key]));
						}
						if($Expdate[$key]=="")
						{
			 				$Expdate1 = "";
						}
						else
						{
			 				$Expdate1 = date('Y-m-d',strtotime($Expdate[$key]));	
						}
			 			$Quantity1 = $AddQuantity[$key];

					$query2 = $dbConnection->prepare("INSERT INTO InventoryMaster (ProductId_ProductMaster,BatchNoORSrNo,BatchManufacturer,ManfactureDate,ExpireDate,Quantity,CreatedTime) VALUES (?,?,?,?,?,?,?)");
					$query2->execute(array($ProductId,$BatchNo1,$ManfcBatch1,$Mfgdate1,$Expdate1,$Quantity1,$DATEIME));
					$InvPkId1 = $dbConnection->lastInsertId();	

					$query3 = $dbConnection->prepare("INSERT INTO InventoryAdjustmentDetails (PkId_InventoryAdjustments,ProductId_ProductMaster,PkId_InventoryMaster,Quantity,CreatedTime) VALUES (?,?,?,?,?)");
					$query3->execute(array($PkId,$ProductId,$InvPkId1,$Quantity1,$DATEIME));

					}
				}
				else
				{
					if(in_array('1', $SetQtychk))
					{
						$query1 = $dbConnection->prepare("INSERT INTO InventoryAdjustments (Reference,EntryDate,Reason,Description,ModeofAdjust,CreatedTime) VALUES (?,?,?,?,?,?)");
						 $query1->execute(array($referencenum,$entrydate,$reason,$description,$adjust,$DATEIME));
						 $PkId = $dbConnection->lastInsertId();		

					foreach ($InvPkId as $key => $value) {
					# code...
						$InvPkId1 = $InvPkId[$key];
			 			$fresh1 = $quantity[$key]-$setquantity[$key];
			 			if($setquantity[$key]>0)
						{

							$query3 = $dbConnection->prepare("INSERT INTO InventoryAdjustmentDetails (PkId_InventoryAdjustments,ProductId_ProductMaster,PkId_InventoryMaster,Quantity,CreatedTime) VALUES (?,?,?,?,?)");
							$query3->execute(array($PkId,$ProductId,$InvPkId1,$setquantity[$key],$DATEIME));

			 			$query2 = $dbConnection->prepare("UPDATE InventoryMaster SET Quantity=? WHERE PkId=?");
						$query2->execute(array($fresh1,$InvPkId1));
						}
					}

				}
			}

			$Post = "Updated Quantity of the product as on $DATEIME";
			$Type="Product";		
			$query4 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
			$query4->execute(array($SAId,$Post,$Type,$ProductId,$DATEIME));

			echo $result= "Success";
			}	
		}
					
	}
	else
	{
		echo $Errors = "Enter Mandatory Fields";
		
	}
}	 
?>