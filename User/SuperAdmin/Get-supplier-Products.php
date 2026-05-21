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
			$suppliername = $data->suppliername;

			$delete_status = 1;
			$data1 = array();  
				
			$query = $dbConnection->prepare("SELECT PkId_ProductTypes,ProTypeName FROM DesignMngmntMaster AS dm INNER JOIN ProductTypes AS pm ON dm.PkId_ProductTypes=pm.PkId WHERE dm.PkId_SupplierMaster=? AND dm.DeleteStatus=?");
			$query->execute(array($suppliername,$delete_status));
			$num_rows = $query->rowCount();
			if($num_rows>0)
			{	
				while($rows = $query->fetch())
				{
					$PkId_ProductTypes = $rows['PkId_ProductTypes'];
					$ProTypeName = $rows['ProTypeName'];			

					$data1[] = array("PkId"=>$PkId_ProductTypes,"ProTypeName"=>$ProTypeName);
				}
				echo (json_encode($data1));
			}	
			else
			{
				echo "NoData";
			}
}
?>