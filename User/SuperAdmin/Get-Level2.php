<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{	
	
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
					
			$UserId = $_SESSION['UserId'];		

			$data = json_decode(file_get_contents("php://input"));
			 $category = $data->category;
			 $subcategory = $data->subcategory;

			$delete_status = 1;
			$data1 = array();  
				
			$query = $dbConnection->prepare("SELECT * FROM Level2SubCategoryMaster WHERE PkId_CategoryMaster=? AND PkId_SubCategoryMaster=? AND DeleteStatus=?");
			$query->execute(array($category,$subcategory,$delete_status));
			$num_rows = $query->rowCount();
			$data1 = $query->fetchall();
			echo json_encode($data1);
}
?>