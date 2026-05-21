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
	$ProductId = $data->ProductId;
	if($ProductId!="")
	{
		$delete_status = 1;	
		$data2 = array();
		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";

			$query3 = $dbConnection->prepare("SELECT * FROM InventoryMaster WHERE  ProductId_ProductMaster=? AND Quantity>? AND DeleteStatus=?");
			$query3->execute(array($ProductId,'0',$delete_status));
			while($row3 = $query3->fetch())
			{
				$data1[] = array(
					"InvPkId"=>$row3['PkId'],
					"batchno"=>$row3['BatchNoORSrNo'],
					"BatchManufacturer"=>$row3['BatchManufacturer'],
					"ManfactureDate"=>$row3['ManfactureDate'],
					"ExpireDate"=>$row3['ExpireDate'],
					"quantity"=>$row3['Quantity'],
					"DeleteStatus"=>$row3['DeleteStatus'],
			);
			}

			echo (json_encode($data1));
		}
		else
		{
			echo "NoData";
		}
	}
	else
	{
		echo "Invalid Id";
	}
}
?>