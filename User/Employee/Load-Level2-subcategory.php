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
	
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	$query = $dbConnection->prepare("SELECT * FROM Category WHERE DeleteStatus=?");
	$query->execute(array($delete_status));
	$num_rows = $query->rowCount();
	$a = "1";
	$data1 = array();
	$data2 = array();
	$data3 = array();
	if($num_rows>0)
	{	
		while($rows = $query->fetch())
		{
			$PkId = $rows['PkId'];
			$CategoryName = $rows['CategoryName'];

			$query1 = $dbConnection->prepare("SELECT * FROM SubCategoryMaster WHERE PkId_CategoryMaster=? AND DeleteStatus=?");
			$query1->execute(array($PkId,$delete_status));
			while($rows1 = $query1->fetch())
			{
				$SubPkId = $rows1['PkId'];
				$SubCategoryName = $rows1['SubCategoryName'];

				$query2 = $dbConnection->prepare("SELECT * FROM Level2SubCategoryMaster WHERE PkId_CategoryMaster=? AND PkId_SubCategoryMaster=? AND DeleteStatus=?");
				$query2->execute(array($PkId,$SubPkId,$delete_status));
				while($rows2 = $query2->fetch())
				{
					$Level2PkId = $rows2['PkId'];
					$Level2SCName = $rows2['Level2SCName'];

					$data3[] = array("Level2PkId"=>$Level2PkId,"Level2SCName"=>$Level2SCName);
				}

				$data2[] = array("SubPkId"=>$SubPkId,"SubCategoryName"=>$SubCategoryName,"data3"=>$data3);
				unset($data3);
			}
			
			$data1[] = array("PkId"=>$PkId,"CategoryName"=>$CategoryName,"data2"=>$data2);
			unset($data2);
			$a++;
		}

		echo (json_encode($data1));
	}
	else
	{
		echo "NoData";
	}
}
?>