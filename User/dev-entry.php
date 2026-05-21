<?php

error_reporting(0);
session_start();

$Id = "IBU101";
$Name = "Admin";
$Email = "mrp.admin@dotweb.in";
$RoleId = "1";
$Role = "Superadmin";

$_SESSION['UserId'] = $Id;
$_SESSION['SAName'] = $Name;
$_SESSION['SAEmail'] = $Email;
$_SESSION['SARole'] = $Role;
$_SESSION['SARoleId'] = $RoleId;

echo "<script language=\"javascript\">window.location=\"SuperAdmin/welcome.php\";</script>";

/*
$Id = "IBU102";
$Name = "Employee One";
$Email = "emp.one@dotweb.in";
$RoleId = "2";
$Role = "Employee";

$_SESSION['EmpId'] = $Id;
$_SESSION['EmpName'] = $Name;
$_SESSION['EmpEmail'] = $Email;
$_SESSION['EmpRole'] = $Role;
$_SESSION['EmpRoleId'] = $RoleId;

echo "<script language=\"javascript\">window.location=\"Employee/welcome.php\";</script>";
echo "<script language=\"javascript\">window.location=\"../index.php\";</script>";
*/

?>