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
	$SAName = $_SESSION['VPName'];

	$From = $_SESSION['AppName'];
	$AppURL = $_SESSION['AppURL'];
	$FromId = $_SESSION['FromEmailId'];
	$PrimaryEmailId = $_SESSION['PrimaryEmailId'];
	$Cc = $_SESSION['Cc'];
	$Bcc = $_SESSION['Bcc'];

	$DATEIME = GetDateTime();
	$todaydate = date("Y-m-d"); 

	$FormPkId=$data->FormPkId;
	$SKU=$data->SKU;
	$freshquantity=$data->freshquantity;
	$quantity=$data->quantity;
 	$delete_status=1;
// count(array_filter($fquantity));
		
					
	if($FormPkId!="" && $SKU!="" && $freshquantity!="")
	{	

		if($freshquantity==0)
		{
			echo "Quantity required atleast 1";
		}
		else
		{

		
		$totalqty = $freshquantity+$quantity;
		$qry_resArr = $dbConnection->prepare("UPDATE InventoryMaster SET AvailableQty=?,Status=? WHERE PkId=?");	
		$qry_resArr->execute(array($totalqty,$delete_status,$FormPkId));

		$transaction_type = 1; //GRN

		$sql1=$dbConnection->prepare("SELECT * FROM StockDetails WHERE SKU_InventoryMaster=? AND DeleteStatus=? ORDER BY PkId DESC LIMIT 0,1");
			$sql1->execute(array($SKU,$delete_status));
			$num_rows1 = $sql1->rowCount();
			if($num_rows1>0)
			{ 
				$row=$sql1->fetch();
				$opening_Balance=$row["OpeningStock"];
				$Closing_Balance=$row["ClosingStock"];
				$current_closing=$Closing_Balance+$freshquantity;
				
				
				$qry_resArr4 = $dbConnection->prepare("INSERT INTO StockDetails (EntryDate,SKU_InventoryMaster,TransactionType,ReferenceId,OpeningStock,Quantity,ClosingStock,CreatedTime) VALUES (?,?,?,?,?,?,?,?)");	
				$qry_resArr4->execute(array($todaydate,$SKU,$transaction_type,$FormPkId,$Closing_Balance,$freshquantity,$current_closing,$DATEIME));
			
			}
			else
			{
			$qry_resArr4 = $dbConnection->prepare("INSERT INTO StockDetails (EntryDate,SKU_InventoryMaster,TransactionType,ReferenceId,OpeningStock,Quantity,ClosingStock,CreatedTime) VALUES (?,?,?,?,?,?,?,?)");	
			$qry_resArr4->execute(array($todaydate,$SKU,$transaction_type,$GRNPkId,'0',$freshquantity,$freshquantity,$DATEIME));	
			}

				echo $result = "Success";
		}
		

	}
	else{
		echo "Enter mandatory fields";
	}			 
}	 
?>