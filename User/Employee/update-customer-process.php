<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{	
	if($_SERVER["REQUEST_METHOD"]==="POST")
	{
			
			$data = json_decode(file_get_contents("php://input")); 
			 $FormPkId = $data->FormPkId;
			$CustomerId = $data->CustomerId;
			$salutation = $data->salutation;
			$ctype = $data->ctype;
			 $customername = ucfirst($data->customername);
			 $shopname = $data->shopname;
			 $emailid = $data->emailid;
			 $mobileno = $data->mobileno;
			 $ofcmobileno = $data->ofcmobileno;
			 //$gstin = $data->gstin;
			 //$pan = $data->pan;
			 $billingname = $data->billingname;
			 $billmobileno = $data->billmobileno;
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

			 $shippingname = $data->shippingname;
			 $shipmobileno = $data->shipmobileno;
			 $shipaddress1 = $data->shipaddress1;
			 $shipaddress2 = $data->shipaddress2;
			 $shiptown = $data->shiptown;
			 $shiplandmark = $data->shiplandmark;
			 $shipcity = $data->shipcity;
			 $shipstate = $data->shipstate;
			 $shipdistrict = $data->shipdistrict;
			 $shippincode = $data->shippincode;
			 $paymentterms = $data->paymentterms;
			 // $bankname = $data->bankname;
			 // $branchname = $data->branchname;
			 // $accountname = $data->accountname;
			 // $accounttype = $data->accounttype;
			 // $acnumber = $data->acnumber;
			 // $ifsc = $data->ifsc;
			 // $tandc = $data->tandc;
			$check = 0;
			$Errors .= "";
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
			
			$SAId = $_SESSION['EmpId'];

			$From = $_SESSION['AppName'];
			$AppURL = $_SESSION['AppURL'];
			$FromId = $_SESSION['FromEmailId'];
			$PrimaryEmailId = $_SESSION['PrimaryEmailId'];
			$Cc = $_SESSION['Cc'];
			$Bcc = $_SESSION['Bcc'];
			$DATEIME = GetDateTime();	
				$delete_status = 1;

			// $query = $dbConnection->prepare("SELECT EmailId FROM CustomerMaster WHERE EmailId=? AND PkId!=? AND DeleteStatus=?");
			// $query->execute(array($emailid,$FormPkId,$delete_status));
			// if($query->rowCount()>0)
			// {			
			// 	$check = 1;
			// 	$Errors .= "Email Id already exists"."\n";	
			// }

			// $query1 = $dbConnection->prepare("SELECT Mobile FROM CustomerMaster WHERE Mobile=? AND PkId!=? AND  DeleteStatus=?");
			// $query1->execute(array($mobileno,$FormPkId,$delete_status));
			// if($query1->rowCount()>0)
			// {			
			// 	$check = 1;
			// 	$Errors .= "Mobile No already exists"."\n";	
			// }

			if($CustomerId=="")
			{
				$check = 1;
				$Errors .= "CustomerId is required"."\n";
			}
			if($shopname=="")
			{
				$check = 1;
				$Errors .= "Company Name is required"."\n";
			}
			// if($emailid=="")
			// {
			// 	$check = 1;
			// 	$Errors .= "Email Id is required"."\n";
			// }
			// if($mobileno=="")
			// {
			// 	$check = 1;
			// 	$Errors .= "Mobile No is required"."\n";
			// }
			// if($gstin=="")
			// {
			// 	$check = 1;
			// 	$Errors .= "GST is required"."\n";
			// }
		    if($check==1){
			echo $Errors;
			}
			else 
			{

				$query = $dbConnection->prepare("UPDATE CustomerMaster SET CustomerType=?,Salutation=?,DisplayName=?,CompanyName=?,EmailId=?,Mobile=?,WorkPhone=?,PaymentTerms=?,BillingName=?,BillAddressLane1=?,BillAddressLane2=?,BillTown=?,BillLandmark=?,BillCity=?,BillState=?,BillDistrict=?,BillZipcode=?,BillPhone=?,ShipName=?,ShipAddressLane1=?,ShipAddressLane2=?,ShipTown=?,ShipLandmark=?,ShipCity=?,ShipState=?,ShipDistrict=?,ShipZipcode=?,ShipPhone=? WHERE PkId=? AND DeleteStatus=?");
					$query->execute(array($ctype,$salutation,$customername,$shopname,$emailid,$mobileno,$ofcmobileno,$paymentterms,$billingname,$address1,$address2,$town,$landmark,$city,$state,$district,$pincode,$billmobileno,$shippingname,$shipaddress1,$shipaddress2,$shiptown,$shiplandmark,$shipcity,$shipstate,$shipdistrict,$shippincode,$shipmobileno,$FormPkId,$delete_status));

		
				$Post = "updated Customer as on $DATEIME";						
				$Type="Customer";		
				$query2 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
				$query2->execute(array($SAId,$Post,$Type,$CustomerId,$DATEIME));
						
				echo $result= "Success";
			}
	}
	else
	{
		$result= "Invalid Data";
		echo $result;
	}
	
}
?>