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
	$PkId = $data->PkId;
	if($PkId!="")
	{
		$delete_status = 1;	
		$data2 = array();
		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";
		$query = $dbConnection->prepare("SELECT * FROM Packages WHERE DeleteStatus=? AND PkId=?");
		$query->execute(array($delete_status,$PkId));
		$num_rows = $query->rowCount();
		$a = "1";
		if($num_rows>0)
		{	
			$row = $query->fetch();
				$InvoiceId = $row['InvoiceId'];


				$data1 = array(
					'PkId' => $row['PkId'],
					'PackageId' => $row['PackageId'],
					'InvoiceId' => $InvoiceId
				);
				unset($data2);
			

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