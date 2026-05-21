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
	$query = $dbConnection->prepare("SELECT scm.PkId,scm.PkId_CategoryMaster,scm.SubCategoryName,cat.CategoryName FROM SubCategoryMaster AS scm INNER JOIN Category AS cat ON cat.PkId=scm.PkId_CategoryMaster WHERE scm.DeleteStatus=?");
	$query->execute(array($delete_status));
	$num_rows = $query->rowCount();
	$a = "1";
	if($num_rows>0)
	{	
		while($rows = $query->fetch())
		{
			$PkId = $rows['PkId'];
			$PkId_CategoryMaster = $rows['PkId_CategoryMaster'];
			$CategoryName = $rows['CategoryName'];
			$SubCategoryName = $rows['SubCategoryName'];
			$data[] = array("PkId"=>$PkId,"PkId_CategoryMaster"=>$PkId_CategoryMaster,"CategoryName"=>$CategoryName,"SubCategoryName"=>$SubCategoryName);
			$a++;
		}

		echo (json_encode($data));
	}
	else
	{
		echo "NoData";
	}
}
?>