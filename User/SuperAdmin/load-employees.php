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
	
	$query = $dbConnection->prepare("SELECT * FROM EmployeeMaster WHERE DeleteStatus=? ORDER BY PkId DESC");
	$query->execute(array($delete_status));
	$num_rows = $query->rowCount();
	$a = "1";
	if($num_rows>0)
	{	
		while($rows = $query->fetch())
		{
			$PkId = $rows['PkId'];
			if($rows['CreatedTime']=="0000-00-00" || $rows['CreatedTime']=="")
			{
				$ct = "";
			}
			else
			{
				$ct = date("d-m-Y", strtotime($rows['CreatedTime']));
			}
			
			$data[] = array("PkId"=>$PkId,"EmpName"=>$rows['EmpName'],"Designation"=>$rows['Designation'],"EmailId"=>$rows['EmailId'],"Mobile"=>$rows['Mobile'],"OtherMobileNo"=>$rows['OtherMobileNo'],"AddressLane1"=>$rows['AddressLane1'],"AddressLane2"=>$rows['AddressLane2'],"Town"=>$rows['Town'],"LandMark"=>$rows['LandMark'],"City"=>$rows['City'],"State" => $rows['State'],"PINCode"=>$rows['PINCode'],"District"=>$rows['District'],"Lattitude"=>$rows['Lattitude'],"Longitude"=>$rows['Longitude']
				//,"BankName"=>$rows['BankName'],"Branch"=>$rows['Branch'],"AccountName"=>$rows['AccountName'],"AccountType"=>$rows['AccountType'],"AccountNo"=>$rows['AccountNo'],"IFSC"=>$rows['IFSC'],"added"=>$ct
		);
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