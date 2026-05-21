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
			 // $mobileno = $data->mobileno;
			 // $ofcmobileno = $data->ofcmobileno;
			 //$gstin = $data->gstin;
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
			$From = $_SESSION['AppName'];
			$AppURL = $_SESSION['AppURL'];
			$FromId = $_SESSION['FromEmailId'];
			$PrimaryEmailId = $_SESSION['PrimaryEmailId'];
			$Cc = $_SESSION['Cc'];
			$Bcc = $_SESSION['Bcc'];
			$DATEIME = GetDateTime();
			
			$query = $dbConnection->prepare("SELECT EmailId FROM VendorMaster WHERE EmailId=? AND DeleteStatus=?");
			$query->execute(array($emailid,$delete_status));
			if($query->rowCount()>0)
			{			
				$check = 1;
				$Errors .= "Email Id already exists"."\n";	
			}
			// $query1 = $dbConnection->prepare("SELECT Mobile FROM CustomerMaster WHERE Mobile=? AND DeleteStatus=?");
			// $query1->execute(array($contact,$delete_status));
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
			/*
			if($salutation=="")
			{
				$check = 1;
				$Errors .= "Salutation is required"."\n";
			}
			*/
			if($shopname=="")
			{
				$check = 1;
				$Errors .= "Party Name is required"."\n";
			}/*
			if($customername=="")
			{
				$check = 1;
				$Errors .= "Customer Name is required"."\n";
			}
			*/
			if($check==1){
			echo $Errors;
			}
			else 
			{
				$query = $dbConnection->prepare("INSERT INTO VendorMaster (VendorId,Salutation,DisplayName,Address,GSTNo,PAN,CustomerName,ContactNo,EmailId,Remarks,PaymentTerms,UserId_Users,CreatedTime) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
				$query->execute(array($VendorId,$salutation,$shopname,$address,$gstn,$pan,$customername,$contact,$emailid,$remarks,$paymentterms,$SAId,$DATEIME));

				// $newpwd = uniqid(rand(), true);	
				// $fnewpwd = substr($newpwd, 0, 8);			
				// $hash = hash('sha256', $fnewpwd);					
				// $md5 = md5(uniqid(rand(), TRUE));					
				// $salt = substr($md5, 0, 3);					
				// $enc_pwd = hash('sha256', $salt . $hash);
								
				// $log = $dbConnection->prepare("INSERT INTO CustomerPassword (CustomerId_CustomerMaster,ParolString,TuzString) VALUES(?,?,?)");
				// $log->execute(array($CustomerId,$enc_pwd,$salt));
				$Post = "Created Vendor with (VendorId: $VendorId,Salutation: $salutation,DisplayName: $shopname,Address: $address,GSTNo: $gstn,PAN: $pan,CustomerName: $customername,ContactNo: $contact,EmailId: $emailid,Remarks: $remarks,PaymentTerms: $paymentterms) as on $DATEIME by $SAId";
						
				$Type="Vendor";		
				$query2 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
				$query2->execute(array($SAId,$Post,$Type,$VendorId,$DATEIME));
						
				$result= "Success";
				echo $result;
			}
	
}
?>
