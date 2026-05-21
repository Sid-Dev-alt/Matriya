<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{
  $FromDate = $_REQUEST['FromDate'];
  $ToDate = $_REQUEST['ToDate'];


	include_once "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";

	$fromdate = date("Y-m-d",strtotime($FromDate));
    $todate = date("Y-m-d",strtotime($ToDate));
	$UserId = $_SESSION['UserId'];
	$delete_status =1;
	$data1 = array();
	$query = $dbConnection->prepare("SELECT RawPurchaseMasterDetails.*,RawPurchaseMaster.* FROM RawPurchaseMasterDetails INNER JOIN RawPurchaseMaster ON RawPurchaseMasterDetails.RawPurchaseId_RawPurchaseMaster=RawPurchaseMaster.RawPurchaseId WHERE RawPurchaseMasterDetails.TDate BETWEEN '$fromdate' AND '$todate' AND RawPurchaseMasterDetails.DeleteStatus=? ORDER BY RawPurchaseMasterDetails.PkId DESC");
    $query->execute(array($delete_status));
    $num_rows = $query->rowCount();
    $a = "1";
    if($num_rows>0)
    {			
		$fd = date('d-m-Y',strtotime($fromdate));
		$td = date('d-m-Y',strtotime($todate));

			//-<?php echo $TName."-".$fromdate."-".$todate-Report.xls"'
			
		header("Content-Type: application/ms-excel");
		header('Content-Disposition: attachment; filename="Datewise-Purchase-Report.xls"'); # filename
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
	<?php
			
			print "<table border='1' align='center'><tr><td valign='top' align='center' colspan='16'><strong>Datewise Purchase Report  ( $fd TO $td ) </strong></td></tr>
	<tr>
	<td valign='top' align='left'><strong>Date</strong></td>
	<td valign='top' align='left'><strong>Micron</strong></td>
	<td valign='top' align='left'><strong>Item Name</strong></td>
	<td valign='top' align='left'><strong>Size</strong></td>
	<td valign='top' align='left'><strong>Roll No</strong></td>
	<td valign='top' align='left'><strong>Quantity</strong></td>
	<td valign='top' align='left'><strong>Remarks</strong></td>
	</tr>
";
			$i=1;
		while($row = $query->fetch())
    	{
			$TDate = $row['TDate'];
            $Quantity = $row['PurchaseQty'];
            $RollNo = $row['RollNo'];
            $PkId_GoDownMaster = $row['PkId_GoDownMaster'];
            $ProductSize =$row['ProductSize'];
            $Comments = $row['Comments'];

            $query2 = $dbConnection->prepare("SELECT Micron,ProductName,Unit FROM ProductMaster WHERE ProductId=?");
            $query2->execute(array($row['ProductId_ProductMaster']));
            $row2 = $query2->fetch();
            $Micron =$row2['Micron'];
            $ProductName =$row2['ProductName'];
            $Unit =$row2['Unit'];

            $query3 = $dbConnection->prepare("SELECT GoDownName FROM GoDownMaster WHERE PkId=?");
            $query3->execute(array($PkId_GoDownMaster));
            $row3 = $query3->fetch();
            $GoDownName =$row3['GoDownName'];

					//$AvlQty1 =  number_format((float)$AvlQty, 2, '.', '');  // Outputs -> 105.00

				/* Total count of user Ends here */
				print "<tr><td valign='top' align='left'>$TDate</td>
				<td valign='top' align='left'>$Micron</td>
				<td valign='top' align='left'>$ProductName</td>
				<td valign='top' align='left'>$ProductSize</td>
				<td valign='top' align='left'>$RollNo</td>
				<td valign='top' align='left'>$Quantity</td>
				<td valign='top' align='left'>$Comments</td></tr>";
$i++;
			}
			//print "<tr>
			// <td valign='top' colspan='8' align='right'><b>Total</b></td>
			// <td valign='top' align='left'><b>$CashTotal</b></td>
			// <td valign='top' align='left'><b>$CardTotal</b></td>
			// <td valign='top' align='left'><b>$CreditTotal</b></td>
			// <td valign='top' align='left'><b>$OnlineTotal</b></td>
			// <td valign='top' align='left'><b>$SumTotalPaid</b></td>
			// <td valign='top' align='left'><b>$SumTotalMargin</b></td>
			// </tr>";
			print "</table>";
			echo "<script language=javascript>window.location=\"datewise-purchase-reports.php\";alert(\"Downloaded successfully.\");</script>";
			
		}
		else
		{			
			 echo "<script language=javascript>window.location=\"datewise-purchase-reports.php\";alert(\"No Records Found.\");</script>";
		}
	 // }
	 // else
	 // {
	 // 		echo "<script language=javascript>window.location=\"reports.php\";alert(\"Unable to Download. Please try again later.\");</script>";
	 // }
}
?>