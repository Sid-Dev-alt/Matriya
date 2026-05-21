<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{	
	if($_SERVER["REQUEST_METHOD"]==="POST")
	{
			
			$data = json_decode(file_get_contents("php://input")); 
			 $empname = $data->empname;
			 $designation = $data->designation;
			 $emailid = $data->emailid;
			 $mobileno = $data->mobileno;
			 $othermobileno = $data->othermobileno;
			 //$gstin = $data->gstin;
			 //$pan = $data->pan;
			 $address1 = $data->address1;
			 $address2 = $data->address2;
			 $town = $data->town;
			 $landmark = $data->landmark;
			 $city = $data->city;
			 $state = $data->state;
			 $district = $data->district;
			 $pincode = $data->pincode;
			 $lattitude = $data->lattitude;
			 $longitude = $data->longitude;
			 // $bankname = $data->bankname;
			 // $branchname = $data->branchname;
			 // $accountname = $data->accountname;
			 // $accounttype = $data->accounttype;
			 // $acnumber = $data->acnumber;
			 // $ifsc = $data->ifsc;
			 // $tandc = $data->tandc;
				
			
				
			if($empname!="" && $designation!="" && $mobileno!="" && $address1!="" && $district!="" && $city!="" && $state!=""  && $pincode!="")
			{

				include "../../CommonUtilities/Connections.php";
				include_once "../../CommonUtilities/Functions.php";
				
				$SAId = $_SESSION['UserId'];
				$DATEIME = GetDateTime();	
				$delete_status = 0;

				$query = $dbConnection->prepare("INSERT INTO EmployeeMaster (EmpName,Designation,EmailId,Mobile,OtherMobileNo,AddressLane1,AddressLane2,Town,LandMark,City,State,District,PINCode,Lattitude,Longitude,CreatedTime) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
					$query->execute(array($empname,$designation,$emailid,$mobileno,$othermobileno,$address1,$address2,$town,$landmark,$city,$state,$district,$pincode,$lattitude,$longitude,$DATEIME));

				$PkId = $dbConnection->lastInsertId();
		
				$Post = "$SAId Created New Employee (Emp Name: $empname, Designation: $designation, EmailId: $emailid, Mobile: $mobileno, othermobileno: $othermobileno, AddressLane1: $address1, Address : $address2, Town: $town, LandMark: $landmark, City: $city, State: $state,District: $district, PIN: $pincode) as on $DATEIME";
						
				$Type="Employee";		
				$query2 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
				$query2->execute(array($SAId,$Post,$Type,$PkId,$DATEIME));
						
					$result= "Success";
					echo $result;
			}
			else
			{
				$result= "Enter mandatory fields";
				echo $result;
			}
		
	}
	else
	{
		$result= "Invalid Data";
		echo $result;
	}
	
}
?>