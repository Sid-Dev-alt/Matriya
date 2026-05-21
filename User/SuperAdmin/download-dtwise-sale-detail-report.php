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
	$query = $dbConnection->prepare("SELECT Invoices.InvoiceId,Invoices.CustomerName,InvoiceDetails.* FROM Invoices INNER JOIN InvoiceDetails ON Invoices.InvoiceId=InvoiceDetails.InvoiceId_Invoices WHERE InvDate BETWEEN '$fromdate' AND '$todate' AND Invoices.DeleteStatus=? ORDER BY Invoices.PkId DESC");
    $query->execute(array($delete_status));
    $num_rows = $query->rowCount();
    $a = "1";
    if($num_rows>0)
    {			
		$fd = date('d-m-Y',strtotime($fromdate));
		$td = date('d-m-Y',strtotime($todate));

			//-<?php echo $TName."-".$fromdate."-".$todate-Report.xls"'
			
			header("Content-Type: application/ms-excel");
			header('Content-Disposition: attachment; filename="Datewise-Sale-Report.xls"'); # filename
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
	<?php
			
			print "<table border='1' align='center'><tr><td valign='top' align='center' colspan='8'><strong>Datewise Sale Report  ( $fd TO $td ) </strong></td></tr>
	<tr>
	<td valign='top' align='left'><strong>Bill Id</strong></td>
	<td valign='top' align='left'><strong>Bill Date</strong></td>
	<td valign='top' align='left'><strong>Customer Name</strong></td>
	<td valign='top' align='left'><strong>Micron</strong></td>
	<td valign='top' align='left'><strong>Item Name</strong></td>
	<td valign='top' align='left'><strong>Size</strong></td>
	<td valign='top' align='left'><strong>Roll No</strong></td>
	<td valign='top' align='left'><strong>Quantity</strong></td>
	</tr>
";
			$i=1;
		while($row = $query->fetch())
    	{
    		$InvoiceId = $row['InvoiceId'];
            $CustomerName = $row['CustomerName'];
			$InvDate = $row['InvDate'];
            $Quantity = $row['Quantity'];

            $query2 = $dbConnection->prepare("SELECT Micron,ProductName,Unit FROM ProductMaster WHERE ProductId=?");
            $query2->execute(array($row['ProductId_ProductMaster']));
            $row2 = $query2->fetch();
            $Micron =$row2['Micron'];
            $ProductName =$row2['ProductName'];
            $Unit =$row2['Unit'];

            $query3 = $dbConnection->prepare("SELECT UniqueRollNo,ProductSize FROM InventoryMaster WHERE PkId=?");
            $query3->execute(array($row['PkId_InventoryMaster']));
            $row3 = $query3->fetch();
            $UniqueRollNo =$row3['UniqueRollNo'];
            $ProductSize =$row3['ProductSize'];

					//$AvlQty1 =  number_format((float)$AvlQty, 2, '.', '');  // Outputs -> 105.00

				/* Total count of user Ends here */
				print "<tr><td valign='top' align='left'>$InvoiceId</td>
				<td valign='top' align='left'>$InvDate</td>
				<td valign='top' align='left'>$CustomerName</td>
				<td valign='top' align='left'>$Micron</td>
				<td valign='top' align='left'>$ProductName</td>
				<td valign='top' align='left'>$ProductSize</td>
				<td valign='top' align='left'>$UniqueRollNo</td>
				<td valign='top' align='left'>$Quantity</td></tr>";
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
			echo "<script language=javascript>window.location=\"datewise-sale-reports.php\";alert(\"Downloaded successfully.\");</script>";
			
		}
		else
		{			
			 echo "<script language=javascript>window.location=\"datewise-sale-reports.php\";alert(\"No Records Found.\");</script>";
		}
	 // }
	 // else
	 // {
	 // 		echo "<script language=javascript>window.location=\"reports.php\";alert(\"Unable to Download. Please try again later.\");</script>";
	 // }
}
?>