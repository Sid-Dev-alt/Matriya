<?php
session_start();

$role = isset($_GET['role']) ? $_GET['role'] : 'employee';

// Default AppInfo values
$_SESSION['AppName'] = "Matriya";
$_SESSION['AppURL'] = "http://localhost/projects/MRPolymers/";
$_SESSION['FromEmailId'] = "mrp.admin@dotweb.in";
$_SESSION['PrimaryEmailId'] = "mrp.admin@dotweb.in";
$_SESSION['LogId'] = 9999;

if ($role === 'superadmin') {
    $_SESSION['UserId'] = "IBU101"; // Admin UserId
    $_SESSION['SAName'] = "Admin";
    $_SESSION['SAEmail'] = "mrp.admin@dotweb.in";
    $_SESSION['SARole'] = "Super Admin";
    $_SESSION['SARoleId'] = 1;
    $_SESSION['UserRoleId'] = 1;
    header('Location: User/SuperAdmin/list-roll-inventory.php');
    exit;
} else {
    $_SESSION['EmpId'] = "IBU102"; // Employee One UserId
    $_SESSION['EmpName'] = "Employee One";
    $_SESSION['EmpEmail'] = "emp.one@dotweb.in";
    $_SESSION['EmpRole'] = "Employee";
    $_SESSION['EmpRoleId'] = 2;
    $_SESSION['UserRoleId'] = 2;
    header('Location: User/Employee/list-roll-inventory.php');
    exit;
}
?>
