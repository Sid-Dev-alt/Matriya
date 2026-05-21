<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{	
			$data = json_decode(file_get_contents("php://input")); 
			 $FormPkId = $data->FormPkId;
			$VendorId = $data->VendorId;
			$salutation = $data->salutation;
			$shopname = $data->shopname;
			$address = $data->address;
			$gstn = $data->gstn;
			$customername = ucfirst($data->customername);
			$contact = $data->contact;
			$emailid = $data->emailid;
			$paymentterms = $data->paymentterms;
			$remarks = $data->remarks;

			 $pan = $data->pan;

			 // $billingname = $data->billingname;
			 // $billmobileno = $data->billmobileno;
			 // $address1 = $data->address1;
			 // $address2 = $data->address2;
			 // $town = $data->town;
			 // $landmark = $data->landmark;
			 // $city = $data->city;
			 // $state = $data->state;
			 // $district = $data->district;
			 // $pincode = $data->pincode;
			 // $lattitude = $data->lattitude;
			 // $longitude = $data->longitude;

			 // $shippingname = $data->shippingname;
			 // $shipmobileno = $data->shipmobileno;
			 // $shipaddress1 = $data->shipaddress1;
			 // $shipaddress2 = $data->shipaddress2;
			 // $shiptown = $data->shiptown;
			 // $shiplandmark = $data->shiplandmark;
			 // $shipcity = $data->shipcity;
			 // $shipstate = $data->shipstate;
			 // $shipdistrict = $data->shipdistrict;
			 // $shippincode = $data->shippincode;
			 // $paymentterms = $data->paymentterms;
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
				$SAId = $_SESSION['UserId'];
				$DATEIME = GetDateTime();	
				$delete_status = 1;
			$From = $_SESSION['AppName'];
			$AppURL = $_SESSION['AppURL'];
			$FromId = $_SESSION['FromEmailId'];
			$PrimaryEmailId = $_SESSION['PrimaryEmailId'];
			$Cc = $_SESSION['Cc'];
			$Bcc = $_SESSION['Bcc'];

			$query = $dbConnection->prepare("SELECT EmailId FROM VendorMaster WHERE EmailId=? AND PkId!=? AND DeleteStatus=?");
			$query->execute(array($emailid,$FormPkId,$delete_status));
			if($query->rowCount()>0)
			{			
				$check = 1;
				$Errors .= "Email Id already exists"."\n";	
			}

			// $query1 = $dbConnection->prepare("SELECT Mobile FROM VendorMaster WHERE Mobile=? AND PkId!=? AND  DeleteStatus=?");
			// $query1->execute(array($mobileno,$FormPkId,$delete_status));
			// if($query1->rowCount()>0)
			// {			
			// 	$check = 1;
			// 	$Errors .= "Mobile No already exists"."\n";	
			// }
			if($VendorId=="")
			{
				$check = 1;
				$Errors .= "Vendor Id is required"."\n";
			}
			// if($salutation=="")
			// {
			// 	$check = 1;
			// 	$Errors .= "Salutation is required"."\n";
			// }
			if($shopname=="")
			{
				$check = 1;
				$Errors .= "Party Name is required"."\n";
			}
			// if($customername=="")
			// {
			// 	$check = 1;
			// 	$Errors .= "Customer Name is required"."\n";
			// }
			if($check==1){
			echo $Errors;
			}
			else 
			{

				$query = $dbConnection->prepare("UPDATE VendorMaster SET Salutation=?,DisplayName=?,Address=?,GSTNo=?,PAN=?,CustomerName=?,ContactNo=?,EmailId=?,Remarks=?,PaymentTerms=? WHERE PkId=? AND DeleteStatus=?");
					$query->execute(array($salutation,$shopname,$address,$gstn,$pan,$customername,$contact,$emailid,$remarks,$paymentterms,$FormPkId,$delete_status));

		
				$Post = "Updated Vendor with (VendorId: $VendorId,Salutation: $salutation,DisplayName: $shopname,Address: $address,GSTNo: $gstn,PAN: $pan,CustomerName: $customername,ContactNo: $contact,EmailId: $emailid,Remarks: $remarks,PaymentTerms: $paymentterms) as on $DATEIME by $SAId";						
				$Type="Vendor";		
				$query2 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
				$query2->execute(array($SAId,$Post,$Type,$VendorId,$DATEIME));
						
				echo $result= "Success";
			}
}
?>
