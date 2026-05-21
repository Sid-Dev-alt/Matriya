<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{
	if($_REQUEST['Id']!="")
	{
		$Id = $_REQUEST['Id'];
			$delete_status = 1;	
			include "../../CommonUtilities/Connections.php";
			include_once "../../CommonUtilities/Functions.php";
			$UserName = $_SESSION['SAName'];

			$query = $dbConnection->prepare("SELECT * FROM Invoices WHERE DeleteStatus=? AND PkId=?");
			$query->execute(array($delete_status,$Id));
			$row = $query->fetch();
			$InvoiceId = $row['InvoiceId'];
			$InvoiceDate = $row['InvoiceDate'];
			$DueDate = $row['DueDate'];
			$SubTotal=$row['SubTotal'];
			$AdditionalCharges=$row['AdditionalCharges'];
			$GST=$row['GST'];
			$GSTAmount=$row['GSTAmount'];

			$CustomerName = $row['CustomerName'];
			$CPlace = $row['CustomerPlace'];
			$CMobile = $row['CustomerMobile'];
			$Reference = $row['Reference'];
			//$ReturnId_SalesReturns = $row['ReturnId_SalesReturns'];
			//$ReturnAmt = $row['ReturnAmt'];
			// $query9 = $dbConnection->prepare("SELECT * FROM SalesReturns WHERE SaleReturnId=? AND DeleteStatus=?");
			// $query9->execute(array($ReturnId_SalesReturns,$delete_status));
			// $row9 = $query9->fetch();
			// $OldInvoiceId_Invoices = $row9['InvoiceId_Invoices'];

			$DiscType=$row['DiscType'];
			$DiscountVal=$row['DiscountVal'];
			$DiscountAmount=$row['DiscountAmount'];
			$InvoiceTotal=$row['InvoiceTotal'];
			$CreatedTime=$row['CreatedTime'];
			//$invtime = substr($CreatedTime, 10); 
			$invtime = date('h:i A', strtotime($CreatedTime));

			$query1 = $dbConnection->prepare("SELECT * FROM CompanyInfo WHERE DeleteStatus=?");
			$query1->execute(array($delete_status));
			$row1 = $query1->fetch();
			$CompanyName = $row1['CompanyName'];
			$CoEmailId = $row1['EmailId'];
			$CoMobile = $row1['MobileNo'];
			$GSTIN = $row1['GSTIN'];
			$PAN = $row1['PAN'];
			$LogoFilename = $row1['LogoFilename'];
			$AddressLane1 = $row1['AddressLane1'];
			$AddressLane2 = $row1['AddressLane2'];
			$City = $row1['City'];

			$barcodeType = "code128";
	$barcodeDisplay = "horizontal";
	$barcodeSize = "20";
	$printText = "true";

?>
<!DOCTYPE html>
<html>
	<head>
		<?php include_once("../title.php");?>
		<link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="../../assets/css/bill-print.css">
		<title>Invoice Print</title>
		<!--Print Script & Css Starts Here-->
			<script language="javascript" type="text/javascript">
				function PrintPage()
				{
				    window.print();
				}
			</script>			
		<!--Print Script & Css Ends Here-->
		<style type="text/css">
			.table
			{
				margin:0 !important; 
			}

			hr
			{
				margin-top: 5px !important;
				margin-bottom: 5px !important;
				border-top: 2px solid #000 !important;
			}
		</style>
	</head>
   <body onload="window.print()">
       <div class="container">
       	<div class="row">
       	    <div class="col-md-offset-3 col-md-9">
       	        <p class="gap">&nbsp;</p>
       	        <p class="gap">&nbsp;</p>
				<a class="btn btn-info" href="#" onclick="window.print();"><i class="ace-icon fa fa-check bigger-110"></i> PRINT</a>&nbsp;&nbsp;&nbsp;<a class="btn btn-inverse" href="#" onclick="window.close();"><i class="ace-icon fa fa-close bigger-110"></i> BACK TO LIST</a>
			</div>
            <div class="col-sm-12 text-center ticket">
							<div class="widget-box transparent">
								<div class="widget-header widget-header-large">&nbsp;</div>

								<div class="widget-body">
										<table class="table table-bordered">
										<tr>
										<td colspan="4">
										<div class="row">											
											<div class="col-sm-12">
												<ul class="list-unstyled  spaced">
													<li>
														<strong style="font-size:18px;text-decoration:underline;">PACKING LIST</strong>
													</li>
												</ul>
											</div>
										</div>
										</td>
										</tr>
										<div class="clearfix"></div>
										<tr class="text-center">
											<td class="text-left" style="border: none !important;"><strong>Bill-No:</strong> <?php echo $Reference;?></td>
											<td class="text-left" style="border: none !important;"><strong>Date:</strong> <?php echo date('d-M-y', strtotime($InvoiceDate));?></td>
											<td class="text-left"  style="border: none !important;"><strong>Time:</strong> <?php echo $invtime;?></td>
											<td class="text-left" style="border: none !important;"><strong>User:</strong> <?php echo ucfirst($UserName);?></td></td>
										</tr>
										<tr>
											<td><strong>Sl.No</strong></td>
											<td colspan="2"><strong>Particulars</strong></td>
											<td ><strong>Qty</strong></td>
										</tr>
										<?php
										$i = 1;
										$sql1 = $dbConnection->prepare("SELECT ProductId_ProductMaster,sum(Quantity) AS Total FROM InvoiceDetails WHERE InvoiceId_Invoices=? AND DeleteStatus=? GROUP BY ProductId_ProductMaster");
										$sql1->execute(array($InvoiceId,$delete_status));
										while($row9 = $sql1->fetch())
										{
											$ProductId_ProductMaster = $row9['ProductId_ProductMaster'];
											$Total = $row9['Total'];

												$query1 = $dbConnection->prepare("SELECT * FROM InvoiceDetails WHERE InvoiceId_Invoices=? AND DeleteStatus=? AND ProductId_ProductMaster=?");
												$query1->execute(array($InvoiceId,$delete_status,$ProductId_ProductMaster));
												while($row1 = $query1->fetch())
												{
													$PkId = $row1['PkId'];
													$Quantity = $row1['Quantity'];
													$Price = $row1['Price'];
													$Amount = $row1['Amount'];
													//$MRP = $row1['MRP'];
													// $TaxRate = $row1['TaxRate'];
													// $TaxAmount = $row1['TaxAmount'];
													//$TotalAmount = $row1['TotalAmount'];
													$PkId_Category = $row1['PkId_Category'];
													$PkId_SubCategoryMaster = $row1['PkId_SubCategoryMaster'];
													$PkId_Level2SubCategoryMaster = $row1['PkId_Level2SubCategoryMaster'];
													$ProductId_ProductMaster = $row1['ProductId_ProductMaster'];
													$PkId_InventoryMaster = $row1['PkId_InventoryMaster'];
													//$Brand = $row1['Brand'];
													$ProductId_ProductMaster = $row1['ProductId_ProductMaster'];
													$ProductName = $row1['ProductName'];
													$SKU = $row1['SKU'];

													$query8 = $dbConnection->prepare("SELECT Unit,Micron FROM ProductMaster WHERE  ProductId=?");
													$query8->execute(array($ProductId_ProductMaster));
													$rows8 = $query8->fetch();
													$Micron = $rows8['Micron'];
													$Unit = $rows8['Unit'];

													$query3 = $dbConnection->prepare("SELECT UniqueRollNo,ProductSize FROM InventoryMaster WHERE  PkId=?");
													$query3->execute(array($PkId_InventoryMaster));
													$rows3 = $query3->fetch();
													$UniqueRollNo = $rows3['UniqueRollNo'];
													$Size = $rows3['ProductSize'];

													$query11 = $dbConnection->prepare("SELECT SUM(Quantity) AS Total FROM InvoiceDetails WHERE InvoiceId_Invoices=? AND ProductId_ProductMaster=?");
													$query11->execute(array($InvoiceId,$ProductId_ProductMaster));
													$rows11 = $query11->fetch();
													$Total1 = $rows11['Total'];
													?>
													<tr>
													<td><?php echo $i;?></td>
													<td colspan="2">
														<p class="text-left"><?php echo $Micron;?> <?php echo $ProductName;?> <?php echo $Size;?> MM</p>
														
													</td>
													<td><?php echo number_format((float)$Quantity, 3, '.', '');?> <?php echo $Unit;?></td>
													</tr>
												<?php
												$i++;
											}
											?>
											<tr >
												<td colspan="3"><b>Total</b></td>
												<td><b><?php echo number_format((float)$Total, 3, '.', '');?> <?php echo $Unit;?></b></td>
											</tr>
											<?php
											}
											?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>          
		 </div>			
		</div>
	</body>
</html>
<?php
}
	else
	{
		echo "<script language=javascript>window.location=\"invoices.php\";alert(\"Unable to Download. Please try again later.\");</script>";
	}
}
function number2word($inputNo){
		//return $inputNo;
		$number = $inputNo;
		$num = $number;
		$no = round($number);
		$point = round($number - $no, 2) * 100;
		$point = ($point < 0) ? (100 + $point) : $point;
		$hundred = null;
		$digits_1 = strlen($no);
		$i = 0;
		$str = array();
		$words = array('0' => '', '1' => 'One', '2' => 'Two',
			'3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
			'7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
			'10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
			'13' => 'Thirteen', '14' => 'Fourteen',
			'15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
			'18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
			'30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
			'60' => 'Sixty', '70' => 'Seventy',
			'80' => 'Eighty', '90' => 'Ninety');
		$digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
		while ($i < $digits_1) {
			$divider = ($i == 2) ? 10 : 100;
			$number = floor($num % $divider);
			$num = floor($num / $divider);
			$i += ($divider == 10) ? 1 : 2;
			if ($number) {
				$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
				$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
				$str [] = ($number < 21) ? $words[$number] .
				" " . $digits[$counter] . $plural . " " . $hundred
				:
				$words[floor($number / 10) * 10]
				. " " . $words[$number % 10] . " "
				. $digits[$counter] . $plural . " " . $hundred;
			} else $str[] = null;
		}
		$str = array_reverse($str);
		$result = implode('', $str);
		$points = ($point) ?
		" " . $words[floor($point / 10)*10] . " " . 
		$words[$point = $point % 10] : '';
		return $result . "Rupees " . $points . "ONLY";
	}
?>
