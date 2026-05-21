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
	$data2 = array();
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	$VPId = $_SESSION['UserId'];
	$data = json_decode(file_get_contents("php://input"));
	$data1 = array();
	$data2 = array();

	$query = $dbConnection->prepare("SELECT * FROM ProductMaster WHERE Status=? AND DeleteStatus=?");
	$query->execute(array($delete_status,$delete_status));
	$num_rows = $query->rowCount();
	if($num_rows>0)
	{	
		while($rows = $query->fetch())
		{
			$PkId = $rows['PkId'];
			$ProductId = $rows['ProductId'];
			$PkId_Category = $rows['PkId_Category'];
			$ProductName = $rows['ProductName'];	
			$Size = $rows['Size'];
			$SKU = $rows['SKU'];
			$SalesPrice = $rows['SalesPrice'];
			$TrackingMode = $rows['TrackingMode'];
			$Status = $rows['Status'];

			$query4 = $dbConnection->prepare("SELECT CategoryName FROM Category WHERE  PkId=? AND DeleteStatus=?");
			$query4->execute(array($PkId_Category,$delete_status));
			$row = $query4->fetch();
			$CategoryName = $row['CategoryName'];

			$query3 = $dbConnection->prepare("SELECT * FROM InventoryMaster WHERE  ProductId_ProductMaster=? AND Quantity>? AND DeleteStatus=?");
			$query3->execute(array($ProductId,'0',$delete_status));
			while($row3 = $query3->fetch())
			{
				$data2[] = array("InvPkId"=>$row3['PkId'],
					"batchno"=>$row3['BatchNoORSrNo'],
					"BatchManufacturer"=>$row3['BatchManufacturer'],
					"ManfactureDate"=>$row3['ManfactureDate'],
					"ExpireDate"=>$row3['ExpireDate'],
					"quantity"=>$row3['Quantity'],
					"DeleteStatus"=>$row3['DeleteStatus'],
			);
			}

			$query5 = $dbConnection->prepare("SELECT SUM(Quantity) AS AvlQty FROM InventoryMaster WHERE  ProductId_ProductMaster=? AND DeleteStatus=?");
			$query5->execute(array($ProductId,$delete_status));
			$row5 = $query5->fetch();
			$AvlQty = $row5['AvlQty'];
				$pname = "<ul>
	<li>
	<h4>$ProductName</h4>
	<p>SKU: $SKU</p>
	<p>stock in hand: $AvlQty</p>
	</li>
	</ul>";


			$data1[] = array("PkId"=>$PkId,"ProductId"=>$ProductId,
				
				"ProductName"=>$ProductName,
				"CategoryName"=>$CategoryName,
				"SKU"=>$SKU,
				"pname"=>$pname,
				"SalesPrice"=>$SalesPrice,
				"AvlQty"=>$AvlQty,
				"TrackingMode"=>$TrackingMode,
				"Status"=>$Status,
				"data2"=>$data2
			);
			unset($data2);
		}

		echo (json_encode($data1));
	}
	else
	{
		echo "NoData";
	}
}
?>
