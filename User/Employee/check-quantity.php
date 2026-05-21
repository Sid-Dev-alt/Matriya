<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{		
	$delete_status = 1;	
	$data2 = array();
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	$data = json_decode(file_get_contents("php://input"));
	 $Newsku = $data->Newsku;
	 $Newqty = $data->Newqty;
	 $ProductName = $data->ProductName;
	 $Size = $data->Size;
	 $SKU = $data->SKU;
	 $quantity = $data->quantity;

	if($quantity!="" || $quantity==0)
	{	
		$TransferArr = array();
		if(isset($TransferArr))
		{
			 $is_available = 0;
			 $SetQty	=0;
			 foreach($Newsku as $keys => $values)
			 {
			 	echo $Newsku[$keys];
			  if($Newsku[$keys] == $SKU)
			  {
			   $is_available++;
			   $TransferArr[$keys]['pquantity'] = $Newqty[$keys] + $quantity;
			  }
			 }
			 

			 if($is_available == 0)
			 {
			  $item_array = array(
			   'sku' => $SKU,  
			   'pname' => $ProductName,  
			   'psize' => $Size,
			   'pquantity' => $quantity
			  );
			  $TransferArr[] = $item_array;
			 }
		}
		else
		{
		 $item_array = array(
		 'sku' => $SKU,  
			   'pname' => $ProductName,  
			   'psize' => $Size,
			   'pquantity' => $quantity
		 );
		 $TransferArr[] = $item_array;
		 
		}
		$data1 = array("result"=>"Success","TransferArr"=>$TransferArr);
			echo json_encode($data1);
	}
}
?>