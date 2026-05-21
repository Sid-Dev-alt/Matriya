<?php
session_start();
if(isset($_SESSION['LogId']))
{
	include "../CommonUtilities/Connections.php";
	include_once "../CommonUtilities/Functions.php";
	
	$LogId = $_SESSION['LogId'];
	$DATEIME = GetDateTime();
	
	$log = $dbConnection->prepare("UPDATE LoginInfo SET LogOutTime=? WHERE LocationId=?");
	$log->execute(array($DATEIME,$LogId));
	$dbConnection = null;
}						
unset($_SESSION['AppId']);
unset($_SESSION['AppName']);
unset($_SESSION['LogId']);

unset($_SESSION['UserId']);
unset($_SESSION['SAName']);
unset($_SESSION['SAEmail']);	
unset($_SESSION['SARole']);
unset($_SESSION['SARoleId']);

unset($_SESSION['EmpId']);
unset($_SESSION['UserRole']);
unset($_SESSION['UserRoleId']);
unset($_SESSION['UserName']);
unset($_SESSION['UserEmail']);

unset($_SESSION['VPId']);
unset($_SESSION['VPName']);
unset($_SESSION['VPEmail']);
//session_destroy();
 
echo "<script language=\"javascript\">window.location=\"../index.php\";</script>";
?>