<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{		
	include_once "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	
	$delete_status =1;
		$data1 = array();
	$query = $dbConnection->prepare("SELECT * FROM ProductMaster WHERE DeleteStatus=? AND InventoryType=?");
	$query->execute(array($delete_status,'Raw'));
	$num_rows = $query->rowCount();
	$a = "1";
	if($num_rows>0)
	{				

			//-<?php echo $TName."-".$fromdate."-".$todate-Report.xls"'
			
			header("Content-Type: application/ms-excel");
			header("Content-Disposition: attachment; filename=Rawmaterial-info.xls"); # filename
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
	<?php
			
			print "<table border='0'><tr><td valign='top' align='center' colspan='2'><strong>Account of Raw material</strong></td></tr>
	<tr>
	<td valign='top' align='left'><strong>Product</strong></td>
	<td valign='top' align='left'><strong>Available Qty (Kgs)</strong></td>
	</tr>
";
			$i=1;
			while($rows = $query->fetch())
			{
				$PkId = $rows['PkId'];
				$ProductId = $rows['ProductId'];
				$ProductName = $rows['ProductName'];
				$PkId_Category = $rows['PkId_Category'];
				$PkId_SubCategoryMaster = $rows['PkId_SubCategoryMaster'];
				$PkId_Level2SubCategoryMaster = $rows['PkId_Level2SubCategoryMaster'];
				$PkId_Level3SubCategoryMaster = $rows['PkId_Level3SubCategoryMaster'];
				//$CategoryName = $rows['CategoryName'];
				$FileName = $rows['FileName'];
				if($rows['Status']==1)
				{
					$displaystatus = "Active";
				}
				else
				{
					$displaystatus = "Inactive";
				}

					$query1 = $dbConnection->prepare("SELECT SUM(Quantity) AS AvlQty FROM InventoryMaster WHERE ProductId_ProductMaster=? AND DeleteStatus=?");
					$query1->execute(array($ProductId,$delete_status));
					$row = $query1->fetch();
					$AvlQty = $row['AvlQty'];
					$AvlQty1 =  number_format((float)$AvlQty, 2, '.', '');  // Outputs -> 105.00

				/* Total count of user Ends here */
				print "<tr><td valign='top' align='left'>$ProductName</td>
				<td valign='top' align='right'>$AvlQty1</td></tr>";
$i++;
			}
			print "</table>";
		//	echo "<script language=javascript>window.location=\"welcome.php?PId={{ProjectId}}\";alert(\"Downloaded successfully.\");</script>";
		}
		else
		{			
			echo "<script language=javascript>window.location=\"raw-material-list.php\";alert(\"No Records Found.\");</script>";
		}
	// }
	// else
	// {
	// 		echo "<script language=javascript>window.location=\"welcome.php\";alert(\"Unable to Download. Please try again later.\");</script>";
	// }
}
?>