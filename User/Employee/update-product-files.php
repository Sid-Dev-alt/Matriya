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
		$FormPkId  = $_REQUEST['FormPkId'];
		$protype  = $_REQUEST['protype'];
		$FileName  = $_REQUEST['FileName'];
		$UploadFile1  = $_REQUEST['UploadFile1'];
		$UploadFile2  = $_REQUEST['UploadFile2'];
		$UploadFile3  = $_REQUEST['UploadFile3'];
		$UploadFile4  = $_REQUEST['UploadFile4'];
		$UploadFile5  = $_REQUEST['UploadFile5'];

		if($FormPkId!="")
		{
			
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
			$SAId = $_SESSION['EmpId'];
			$DATEIME = GetDateTime();	
			$delete_status=1;	

if($_FILES["file"]["name"]!=""){

				unlink("../ProductImages/".$FileName);

				$upload_dir = "../ProductImages/";
				//$validextensions = array("jpeg", "jpg", "png", "gif");
				 $temporary = explode(".", $_FILES["file"]["name"]);
				 $file_extension = end($temporary);

				$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable

				$filename = $protype."-".time().$_FILES['file']['name'];

				$targetPath = $upload_dir.$filename; // Target path where file is to be stored
				move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file

				$query = $dbConnection->prepare("UPDATE ProductMaster SET DisplayPicture=? WHERE PkId=? AND DeleteStatus=?");
				$query->execute(array($filename,$FormPkId,$delete_status));

				$result= "Success";
				echo $result;
			}

			if($_FILES["file1"]["name"]!=""){

				unlink("../ProductImages/".$UploadFile1);

				$upload_dir = "../ProductImages/";
				//$validextensions = array("jpeg", "jpg", "png", "gif");
				 $temporary = explode(".", $_FILES["file1"]["name"]);
				 $file_extension = end($temporary);

				$sourcePath = $_FILES['file1']['tmp_name']; // Storing source path of the file in a variable

				$filename1 = $protype."-".time().$_FILES['file1']['name'];

				$targetPath = $upload_dir.$filename1; // Target path where file is to be stored
				move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file

				$query = $dbConnection->prepare("UPDATE ProductMaster SET UploadFile1=? WHERE PkId=? AND DeleteStatus=?");
				$query->execute(array($filename1,$FormPkId,$delete_status));

				$result= "Success";
				echo $result;
			}

			if($_FILES["file2"]["name"]!=""){

				unlink("../ProductImages/".$UploadFile1);

				$upload_dir = "../ProductImages/";
				//$validextensions = array("jpeg", "jpg", "png", "gif");
				 $temporary = explode(".", $_FILES["file2"]["name"]);
				 $file_extension = end($temporary);

				$sourcePath = $_FILES['file2']['tmp_name']; // Storing source path of the file in a variable

				$filename2 = $protype."-".time().$_FILES['file2']['name'];

				$targetPath = $upload_dir.$filename2; // Target path where file is to be stored
				move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file

				$query = $dbConnection->prepare("UPDATE ProductMaster SET UploadFile2=? WHERE PkId=? AND DeleteStatus=?");
				$query->execute(array($filename2,$FormPkId,$delete_status));

				$result= "Success";
				echo $result;
			}

			if($_FILES["file3"]["name"]!=""){

				unlink("../ProductImages/".$UploadFile1);

				$upload_dir = "../ProductImages/";
				//$validextensions = array("jpeg", "jpg", "png", "gif");
				 $temporary = explode(".", $_FILES["file3"]["name"]);
				 $file_extension = end($temporary);

				$sourcePath = $_FILES['file3']['tmp_name']; // Storing source path of the file in a variable

				$filename3 = $protype."-".time().$_FILES['file3']['name'];

				$targetPath = $upload_dir.$filename3; // Target path where file is to be stored
				move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file

				$query = $dbConnection->prepare("UPDATE ProductMaster SET UploadFile3=? WHERE PkId=? AND DeleteStatus=?");
				$query->execute(array($filename3,$FormPkId,$delete_status));

				$result= "Success";
				echo $result;
			}

			if($_FILES["file4"]["name"]!=""){

				unlink("../ProductImages/".$UploadFile1);

				$upload_dir = "../ProductImages/";
				//$validextensions = array("jpeg", "jpg", "png", "gif");
				 $temporary = explode(".", $_FILES["file4"]["name"]);
				 $file_extension = end($temporary);

				$sourcePath = $_FILES['file4']['tmp_name']; // Storing source path of the file in a variable

				$filename4 = $protype."-".time().$_FILES['file4']['name'];

				$targetPath = $upload_dir.$filename4; // Target path where file is to be stored
				move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file

				$query = $dbConnection->prepare("UPDATE ProductMaster SET UploadFile4=? WHERE PkId=? AND DeleteStatus=?");
				$query->execute(array($filename4,$FormPkId,$delete_status));

				$result= "Success";
				echo $result;
			}
			
			if($_FILES["file5"]["name"]!=""){

				unlink("../ProductImages/".$UploadFile1);

				$upload_dir = "../ProductImages/";
				//$validextensions = array("jpeg", "jpg", "png", "gif");
				 $temporary = explode(".", $_FILES["file5"]["name"]);
				 $file_extension = end($temporary);

				$sourcePath = $_FILES['file5']['tmp_name']; // Storing source path of the file in a variable

				$filename5 = $protype."-".time().$_FILES['file5']['name'];

				$targetPath = $upload_dir.$filename5; // Target path where file is to be stored
				move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file

				$query = $dbConnection->prepare("UPDATE ProductMaster SET UploadFile4=? WHERE PkId=? AND DeleteStatus=?");
				$query->execute(array($filename5,$FormPkId,$delete_status));

				$result= "Success";
				echo $result;
			}
		}
		else
		{
			$result= "Invalid Data";
			echo $result;
		}
	}
	else
	{
		echo "<script language=\"javascript\">window.location=\"product-types.php\";</script>";		
	}
}
?>