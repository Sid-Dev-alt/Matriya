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
	$query = $dbConnection->prepare("SELECT scm.PkId,scm.PkId_LocationType,scm.LocationName,cat.Name FROM LocationMaster AS scm INNER JOIN LocationType AS cat ON cat.PkId=scm.PkId_LocationType WHERE scm.DeleteStatus=?");
	$query->execute(array($delete_status));
	$num_rows = $query->rowCount();
	$a = "1";
	if($num_rows>0)
	{	
		while($rows = $query->fetch())
		{
			$PkId = $rows['PkId'];
			$PkId_LocationType = $rows['PkId_LocationType'];
			$LocationName = $rows['LocationName'];
			$TypeName = $rows['Name'];
			$data[] = array("PkId"=>$PkId,"PkId_LocationType"=>$PkId_LocationType,"TypeName"=>$TypeName,"LocationName"=>$LocationName);
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