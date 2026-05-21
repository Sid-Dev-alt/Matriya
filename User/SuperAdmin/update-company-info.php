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
		$shopname = $_REQUEST['shopname'];
		$businesstype = $_REQUEST['businesstype'];
		$emailid = $_REQUEST['emailid'];
		$mobileno = $_REQUEST['mobileno'];
		$ofcmobileno = $_REQUEST['ofcmobileno'];
		$gstin = $_REQUEST['gstin'];
		$pan = $_REQUEST['pan'];
		$LogoFilename = $_REQUEST['LogoFilename'];
		$address1 = $_REQUEST['address1'];
		$address2 = $_REQUEST['address2'];
		$town = $_REQUEST['town'];
		$landmark = $_REQUEST['landmark'];
		$city = $_REQUEST['city'];
		$state = $_REQUEST['state'];
		$district = $_REQUEST['district'];
		$pincode = $_REQUEST['pincode'];


		 $delete_status = 1;
		if($shopname!="" && $emailid!="" && $mobileno!=""  && $address1!="" && $district!="" && $city!="" && $state!=""  && $pincode!="")
		{
			$check = 0;
			$Errors .= "";
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
			$SAId = $_SESSION['UserId'];
			$DATEIME = GetDateTime();

			if($_FILES["file"]["name"]!="")
			{
				unlink("../CompanyLogo/".$LogoFilename);
			}

				$upload_dir = "../CompanyLogo/";
				//$validextensions = array("jpeg", "jpg", "png", "gif");
				 $temporary = explode(".", $_FILES["file"]["name"]);
				 $file_extension = end($temporary);

				$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
				
				if($_FILES['file']['name']=="")
				{
					$filename1="$LogoFilename";	
				}else
				{
					$filename1 = $shopname.str_replace(' ', '', $_FILES['file']['name']);	
				}
				
				$targetPath = $upload_dir.$filename1; // Target path where file is to be stored

				move_uploaded_file($sourcePath,$targetPath); // Moving Uploaded file
		

			$query = $dbConnection->prepare("UPDATE CompanyInfo SET CompanyName=?,BusinessType=?,EmailId=?,MobileNo=?,OfcMobileNo=?,LogoFilename=?,GSTIN=?,PAN=?,AddressLane1=?,AddressLane2=?,Town=?,LandMark=?,City=?,State=?,District=?,PINCode=? WHERE DeleteStatus=?");
			$query->execute(array($shopname,$businesstype,$emailid,$mobileno,$ofcmobileno,$filename1,$gstin,$pan,$address1,$address2,$town,$landmark,$city,$state,$district,$pincode,$delete_status));

			 echo $result= "Success";
					
		}
		else
		{
			echo $Errors = "Enter Mandatory Fields";
			
		}
}	 
?>