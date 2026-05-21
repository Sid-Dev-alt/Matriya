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
	$data = array();

	$sql = $dbConnection->prepare("SELECT PackageId FROM Packages ORDER BY PkId DESC LIMIT 0,1") ;
		$sql->execute();
		$rowCount = $sql->rowCount();
		if($rowCount>0)
		{
			$row = $sql->fetch();	
		 	$value = substr($row['PackageId'],3);
			$variable = $value + 1;
			$length = strlen($variable);
			if(strlen($variable)<3)
			{
				switch($length)
				{
					case 2:
					$variable = "0".$variable;
					break;
					case 1:
					$variable = "00".$variable;
					break;
				}
				$UserId = substr($row['PackageId'],0,3).$variable;
			}
			else
			{
			 $UserId = substr( $row['PackageId'],0,3).$variable;
			}
		}
		else
		{
			 $UserId = "PKG001";
		}
		echo $UserId;
}
?>