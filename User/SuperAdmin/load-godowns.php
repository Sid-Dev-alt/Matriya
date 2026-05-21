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
			$data = json_decode(file_get_contents("php://input"));
	
	$query = $dbConnection->prepare("SELECT * FROM GoDownMaster WHERE DeleteStatus=? ORDER BY PkId DESC");
	$query->execute(array($delete_status));
	$num_rows = $query->rowCount();
	$a = "1";
	if($num_rows>0)
	{	
		while($rows = $query->fetchall())
		{

		echo (json_encode($rows));
		// 	$PkId = $rows['PkId'];

		// 	$TotalName = $rows['PersonName']." ( ".$rows['Mobile']." )";
			
		// 	$data[] = array("PkId"=>$PkId,"CustomerId"=>$rows['CustomerId'],"PersonName"=>$rows['PersonName'],"ShopName"=>$rows['ShopName'],"EmailId"=>$rows['EmailId'],"Mobile"=>$rows['Mobile'],"OfcMobileNo"=>$rows['OfcMobileNo'],"AddressLane1"=>$rows['AddressLane1'],"AddressLane2"=>$rows['AddressLane2'],"Town"=>$rows['Town'],"LandMark"=>$rows['LandMark'],"City"=>$rows['City'],"State" => $rows['State'],"PINCode"=>$rows['PINCode'],"District"=>$rows['District'],"Lattitude"=>$rows['Lattitude'],"Longitude"=>$rows['Longitude']
		// 		,"TotalName"=>$TotalName
		// 		//,"Branch"=>$rows['Branch'],"AccountName"=>$rows['AccountName'],"AccountType"=>$rows['AccountType'],"AccountNo"=>$rows['AccountNo'],"IFSC"=>$rows['IFSC'],"added"=>$ct
		// );
		// 	$a++;
		}
	}
	else
	{
		echo "NoData";
	}
	
}
?>