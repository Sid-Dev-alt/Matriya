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
	include_once "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";

	$data = json_decode(file_get_contents("php://input"));
	$UniqueRollNo = $data->UniqueRollNo;
	$ProductId = $data->ProductId;
	$InvPkId = $data->InvPkId;
    	$data1 = array();
    	
    	$AvlSizeQty = 0;
	$query3 = $dbConnection->prepare("SELECT * FROM InventoryMaster WHERE ProductId_ProductMaster=? AND Quantity>0 AND IsSplitQty=? AND Status=? AND DeleteStatus=? AND PkId=? ORDER BY CAST(ProductSize AS unsigned)");
	$query3->execute(array($ProductId,'0',$delete_status,$delete_status,$InvPkId));
	while($rows3 = $query3->fetch())
	{
	    $InvPkId = $rows3['PkId'];
	    $UniqueRollNo = $rows3['UniqueRollNo'];
	    $Quantity = $rows3['Quantity'];
	    $ProductSize = $rows3['ProductSize'];
	    if($rows3['Remarks']!="" || $rows3['Remarks']!=NULL || $rows3['Remarks']!=null)
	    {
	        $Remarks = $rows3['Remarks'];
	    }
	    else
	    {
	        $query4 = $dbConnection->prepare("SELECT * FROM SlittingRolls WHERE RollNo=? AND DeleteStatus=?");
		$query4->execute(array($UniqueRollNo,$delete_status));
		$rows4 = $query4->fetch();
		$Remarks = $rows4['Remarks'];
	    }
	    
		$data1[] = array("InvPkId"=>$InvPkId,"Remarks"=>$Remarks);
	}
	
	echo (json_encode($data1));
	
}
?>
