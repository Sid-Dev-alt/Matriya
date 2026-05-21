<?php
error_reporting(0);
session_start();
if($_SESSION['UserRoleId']!="")
{
	$RoleId = $_SESSION['UserRoleId'];
	if($RoleId==1)
	{
		echo "<script language=\"javascript\">window.location=\"SuperAdmin/welcome.php\";</script>";
	}
	elseif($RoleId==2)
	{
		echo "<script language=\"javascript\">window.location=\"BDE/welcome.php\";</script>";
	}
	elseif($RoleId==3)
	{
		echo "<script language=\"javascript\">window.location=\"Inventory/welcome.php\";</script>";
	}
}
else
{	
	echo "<script language=\"javascript\">window.location=\"../index.php\";</script>";
}
?>