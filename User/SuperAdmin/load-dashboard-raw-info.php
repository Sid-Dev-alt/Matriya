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
	
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";

	$data = json_decode(file_get_contents("php://input"));
	$todaydt = date("Y-m-d");

	$data1 =array();
	$PurcahseArr =array();
	$SlitArr =array();
	$data4 = array();
	// $CategoryName = "CategoryName!='Spares' AND CategoryName!='Others'";
	// $ctype = "Type='Raw Consumption'";
	// $ptype = "Type='Raw PO'";
	// $query6 = $dbConnection->prepare("SELECT * FROM Category WHERE $CategoryName AND DeleteStatus=?");
	// $query6->execute(array($delete_status));
	// while($rows6 = $query6->fetch())
	// {
	// 	$CatPkId =  $rows6['PkId'];
	// 	$CategoryName =  $rows6['CategoryName'];

		 $delete_status = 1;
		 $query = $dbConnection->prepare("SELECT RawPurchaseMasterDetails.*,RawPurchaseMaster.PkId_GoDownMaster FROM RawPurchaseMasterDetails INNER JOIN RawPurchaseMaster ON RawPurchaseMasterDetails.RawPurchaseId_RawPurchaseMaster=RawPurchaseMaster.RawPurchaseId WHERE RawPurchaseMasterDetails.TDate=? AND RawPurchaseMasterDetails.DeleteStatus=? ORDER BY RawPurchaseMasterDetails.PkId DESC");
		$query->execute(array($todaydt,$delete_status));
		    $num_rows = $query->rowCount();
		    $a = "1";
		    if($num_rows>0)
		    {	
		    	while($row = $query->fetch())
		    	{
		            $TDate = $row['TDate'];
		            $Quantity = $row['PurchaseQty'];
		            $RollNo = $row['RollNo'];
		            $PkId_GoDownMaster = $row['PkId_GoDownMaster'];
		            $ProductSize =$row['ProductSize'];

		            $query2 = $dbConnection->prepare("SELECT Micron,ProductName,Unit FROM ProductMaster WHERE ProductId=?");
		            $query2->execute(array($row['ProductId_ProductMaster']));
		            $row2 = $query2->fetch();
		            $Micron =$row2['Micron'];
		            $ProductName =$row2['ProductName'];
		            $Unit =$row2['Unit'];

		            $query3 = $dbConnection->prepare("SELECT GoDownName FROM GoDownMaster WHERE PkId=?");
		            $query3->execute(array($PkId_GoDownMaster));
		            $row3 = $query3->fetch();
		            $GoDownName =$row3['GoDownName'];

		    		$data1[] = array(
		    			'PkId' => $row['PkId'],
		                'TDate'=>$TDate,
		    			'UniqueRollNo'=>$RollNo,
		                'GoDownName'=>$GoDownName,
		    			'Quantity' => $Quantity,
		                'Micron' => $Micron,
		                'ProductSize' => $ProductSize,
		                'ProductName' => $ProductName,
		                'Unit' => $Unit,
		    		);
		    	}

				echo json_encode($data1);
		    }
		    // else
		    // {
		    // 	echo "NoData";
		    // }
	// 	$data1[] = array('CategoryName'=>$CategoryName,"data2"=>$data2);
	// 	unset($data2);
	// }
}
?>