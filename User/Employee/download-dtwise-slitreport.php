<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']=="")
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
	$UserId = $_SESSION['EmpId'];
	$delete_status =1;
	$data1 = array();
	 $query = $dbConnection->prepare("SELECT * FROM Slitting WHERE DeleteStatus=? AND SlitDate BETWEEN '$FromDate' AND '$todate'");
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
	<td valign='top' align='left'><strong>From Item</strong></td>
	<td valign='top' align='left'><strong>Roll No</strong></td>
	<td valign='top' align='left'><strong>Slit Item</strong></td>
	<td valign='top' align='left'><strong>Slit Roll No</strong></td>
	<td valign='top' align='left'><strong>Slit Quantity</strong></td>
	</tr>
";
			$i=1;
		while($row = $query->fetch())
    	{
    		 $FormPkId = $row['PkId'];
            $SlitId = $row['SlitId'];
            $SlitDate = $row['SlitDate'];

			$PkId_InventoryMaster = $row['PkId_InventoryMaster'];
            $Remarks = $row['Remarks'];

            $query2 = $dbConnection->prepare("SELECT PkId_RawPurchaseMasterDetails,ProductId_ProductMaster,UniqueRollNo,ProductSize FROM InventoryMaster WHERE PkId=?");
            $query2->execute(array($PkId_InventoryMaster));
            $row2 = $query2->fetch();
            $PkId_RawPurchaseMasterDetails = $row2['PkId_RawPurchaseMasterDetails'];
            $UniqueRollNo = $row2['UniqueRollNo'];
            $ProductId_ProductMaster = $row2['ProductId_ProductMaster'];
            $ProductSize = $row2['ProductSize'];

            $query9 = $dbConnection->prepare("SELECT RawPurchaseMaster.PkId_GoDownMaster,GoDownMaster.GoDownName FROM RawPurchaseMasterDetails INNER JOIN RawPurchaseMaster ON RawPurchaseMasterDetails.RawPurchaseId_RawPurchaseMaster=RawPurchaseMaster.RawPurchaseId INNER JOIN GoDownMaster ON RawPurchaseMaster.PkId_GoDownMaster=GoDownMaster.PkId WHERE RawPurchaseMasterDetails.PkId=?");
            $query9->execute(array($PkId_RawPurchaseMasterDetails));
            $row9 = $query9->fetch();
            $GoDownName = $row9['GoDownName'];


            $query4 = $dbConnection->prepare("SELECT ProductName,Size,Micron,Unit FROM ProductMaster WHERE ProductId=?");
            $query4->execute(array($ProductId_ProductMaster));
            $row4 = $query4->fetch();
            $ProductName = $row4['ProductName'];
            $ProductSize = $row4['Size'];
            $Micron = $row4['Micron'];
            $Unit = $row4['Unit'];

             $query3 = $dbConnection->prepare("SELECT * FROM SlittingRolls WHERE SlitId_Slitting=? AND DeleteStatus=?");
            $query3->execute(array($SlitId,$delete_status));
            while($row3 = $query3->fetch())
            {
                $EntryPkId = $row3['PkId'];
                $SplitSize = $row3['SplitSize'];
                $RollNo = $row3['RollNo'];
                $SlitQty = $row3['SlitQty'];    
                

                // $data2[] = array(
                // 'EntryPkId' => $EntryPkId,
                // 'NewProductId_ProductMaster' => $NewProductId_ProductMaster,
                // 'NewProductName' => $NewProductName,
                // 'NewProductSize' => $NewProductSize,
                // 'NewMicron' => $NewMicron,
                // 'NewUnit' => $NewUnit,
                // 'SplitSize' => $SplitSize,
                // 'RollNo' => $RollNo,
                // 'SlitQty' => $SlitQty
                // );

					//$AvlQty1 =  number_format((float)$AvlQty, 2, '.', '');  // Outputs -> 105.00

				/* Total count of user Ends here */
				print "<tr><td valign='top' align='left'>$SlitDate</td>
				<td valign='top' align='left'>$Micron $ProductName $ProductSize</td>
				<td valign='top' align='left'>$UniqueRollNo</td>
				<td valign='top' align='left'>$Micron $ProductName $SplitSize</td>
				<td valign='top' align='left'>$RollNo</td>
				<td valign='top' align='left'>$SlitQty</td></tr>";
$i++;
			}

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