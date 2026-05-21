<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{			
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	$data = json_decode(file_get_contents("php://input"));
	$SAId = $_SESSION['EmpId'];

	$From = $_SESSION['AppName'];
	$AppURL = $_SESSION['AppURL'];
	$FromId = $_SESSION['FromEmailId'];
	$PrimaryEmailId = $_SESSION['PrimaryEmailId'];
	$Cc = $_SESSION['Cc'];
	$Bcc = $_SESSION['Bcc'];

	$DATEIME = GetDateTime();
	$todaydate = date("Y-m-d"); 

	$FormPkId=$data->FormPkId;
	 $Status=$data->Status;
	$protype=$data->protype;
	//$prodescription=$data->prodescription;
	$productsize=$data->productsize;
	$brand=$data->brand;
	$buyprice=$data->buyprice;
	$salesprice=$data->salesprice;
	$batchno=$data->batchno;
	$SKU=$data->SKU;
	// $iscolourreq=$data->iscolourreq;
	// if($iscolourreq=="Yes")
	// {
	// 	$colourname=$data->colourname;
	// }
	// else{

	// 	$colourname="";
	// }
	if($data->mfgdate=="")
	{
		$mfgdate="";	
	}
	else
	{
		$mfgdate=date('Y-m-d',strtotime($data->mfgdate));
	}
	if($data->expdate=="")
	{
		$expdate="";	
	}
	else
	{
		$expdate=date('Y-m-d',strtotime($data->expdate));
	}
	
	$Avlquantity=$data->Avlquantity;
	$quantity=$data->quantity;
	
 	$delete_status=1;
// count(array_filter($fquantity));
		
					
	if($FormPkId!="" && $protype!="" && $productsize!="" && $salesprice!="")
	{	

		if($Avlquantity==$quantity)
		{
			$qry_resArr = $dbConnection->prepare("UPDATE InventoryMaster SET Size=?,Brand=?,BuyPrice=?,SalesPrice=?,SKU=?,BatchNo=?,ManfactureDate=?,ExpireDate=? WHERE PkId=?");	
			$qry_resArr->execute(array($productsize,$brand,$buyprice,$salesprice,$SKU,$batchno,$mfgdate,$expdate,$FormPkId));

			echo $result = "Success";
		}
		else
		{
			// else if($Avlquantity<$quantity)
			// {
			// 	echo "add";
			// }
			// else
			// {
			// 	echo "sub";
			// }


			$sql1=$dbConnection->prepare("SELECT * FROM StockDetails WHERE SKU_InventoryMaster=? AND DeleteStatus=? AND ReferenceId=? ORDER BY PkId DESC LIMIT 0,1");
			$sql1->execute(array($SKU,$delete_status,$FormPkId));
			$num_rows1 = $sql1->rowCount();
			if($num_rows1>0)
			{ 
				$row=$sql1->fetch();
				$opening_Balance=$row["OpeningStock"];
				$Closing_Balance=$row["ClosingStock"];

				if($Avlquantity<$quantity)
				{
					//echo "add";
					
					$Freshqty=$quantity-$Avlquantity;

					// $Freshqty=$Avlquantity+$updateqty;

					 $current_closing=$Closing_Balance+$Freshqty;
				}

				else
				{
					//echo "sub";
					$Freshqty=$Avlquantity-$quantity;

					$current_closing=$Closing_Balance-$Freshqty;
				}

				$transaction_type=2; //adjust	 
				$qry_resArr4 = $dbConnection->prepare("INSERT INTO StockDetails (EntryDate,SKU_InventoryMaster,TransactionType,ReferenceId,OpeningStock,Quantity,ClosingStock,CreatedTime) VALUES (?,?,?,?,?,?,?,?)");	
				$qry_resArr4->execute(array($todaydate,$SKU,$transaction_type,$FormPkId,$Closing_Balance,$Freshqty,$current_closing,$DATEIME));

				$qry_resArr = $dbConnection->prepare("UPDATE InventoryMaster SET Size=?,Brand=?,BuyPrice=?,SalesPrice=?,SKU=?,BatchNo=?,ManfactureDate=?,ExpireDate=?,AvailableQty=? WHERE PkId=?");	
				$qry_resArr->execute(array($productsize,$brand,$buyprice,$salesprice,$SKU,$batchno,$mfgdate,$expdate,$quantity,$FormPkId));
			
			}
		echo $result = "Success";	
		
		}
		

	}
	else{
		echo "Enter mandatory fields";
	}		
				 
}	 
?>