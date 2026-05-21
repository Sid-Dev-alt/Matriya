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

			$query = $dbConnection->prepare("SELECT * FROM PurchaseOrders WHERE DeleteStatus=? AND PkId=?");
			$query->execute(array($delete_status,$Id));
			$row = $query->fetch();
			$POrderId = $row['POrderId'];
			$POrderDate = $row['POrderDate'];
			$DeliveryDate = $row['DeliveryDate'];
			$SubTotal=$row['SubTotal'];
			$AdditionalCharges=$row['AdditionalCharges'];

			$DiscType=$row['DiscType'];
			$DiscountVal=$row['DiscountVal'];
			$DiscountAmount=$row['DiscountAmount'];
			$OrderTotal=$row['OrderTotal'];

			$query1 = $dbConnection->prepare("SELECT * FROM CompanyInfo WHERE DeleteStatus=?");
			$query1->execute(array($delete_status));
			$row1 = $query1->fetch();
			$CompanyName = $row1['CompanyName'];
			$CoEmailId = $row1['EmailId'];
			$CoMobile = $row1['MobileNo'];
			$LogoFilename = $row1['LogoFilename'];

			$query11 = $dbConnection->prepare("SELECT DisplayName,EmailId,Mobile FROM VendorMaster WHERE VendorId=? AND DeleteStatus=?");
			$query11->execute(array($row['VendorId_VendorMaster'],$delete_status));
			$row11 = $query11->fetch();
			$CustomerName = $row11['DisplayName'];
			$CEmailId = $row11['EmailId'];
			$CMobile = $row11['Mobile'];
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<?php include_once("../title.php");?>
		<link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
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
		</style>
	</head>
   <body onload="window.print()">
       <div class="container">
       	<div class="row">
            <div class="col-sm-12">
							<div class="widget-box transparent">
								<div class="widget-header widget-header-large">
									<h3 class="widget-title grey lighter">
										<img src="../CompanyLogo/<?php echo $LogoFilename;?>">
										<i class="ace-icon fa fa-leaf green"></i>
										Purchase Order
									</h3>

									<div class="widget-toolbar no-border invoice-info">
										<span class="invoice-info-label">PO Id:</span>
										<span class="red">#<?php echo $POrderId;?></span>

										<br>
										<span class="invoice-info-label">PO Date:</span>
										<span class="blue"><?php echo date('d M y', strtotime($POrderDate));?></span>
										<br>
										<span class="invoice-info-label">Delivery Dt:</span>
										<span class="blue"><?php echo date('d M y', strtotime($DeliveryDate));?></span>
									</div>

									<div class="widget-toolbar hidden-480">
										<a href="" onclick="PrintPage()">
											<i class="ace-icon fa fa-print"></i>
										</a>
									</div>
								</div>

								<div class="widget-body">
									<div class="widget-main padding-24">
										<div class="row">
											<div class="col-sm-6">
												<div class="row">
													<div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
														<b>Company Info</b>
													</div>
												</div>

												<div>
													<ul class="list-unstyled  spaced">
														<li>
															<i class="ace-icon fa fa-building green"></i> 
															<?php echo ucfirst($CompanyName);?>
														</li>

														<li>
															<i class="ace-icon fa fa-envelope green"></i> 
															<?php echo $CoEmailId;?>
														</li>

														<li>
															<i class="ace-icon fa fa-phone green"></i> 
															<?php echo $CoMobile;?>
														</li>
													</ul>
												</div>
											</div><!-- /.col -->
											<div class="col-sm-6">
												<div class="row">
													<div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
														<b>Vendor Info</b>
													</div>
												</div>

												<div>
													<ul class="list-unstyled  spaced">
														<li>
															<i class="ace-icon fa fa-user green"></i> 
															<?php echo ucfirst($CustomerName);?>
														</li>

														<li>
															<i class="ace-icon fa fa-envelope green"></i> 
															<?php echo $CEmailId;?>
														</li>

														<li>
															<i class="ace-icon fa fa-phone green"></i> 
															<?php echo $CMobile;?>
														</li>
													</ul>
												</div>
											</div><!-- /.col -->
										</div><!-- /.row -->

										<div class="space"></div>

										<div>
											<table class="table table-striped table-bordered">
												<thead>
													<tr>
														<th>Product</th>
														<th>Quantity</th>
														<th>Rate</th>
														<th>Total</th>
													</tr>
												</thead>

												<tbody>
													<?php
													$query1 = $dbConnection->prepare("SELECT * FROM PurchaseOrderDetails WHERE POrderId_PurchaseOrders=? AND DeleteStatus=?");
													$query1->execute(array($POrderId,$delete_status));
													while($row1 = $query1->fetch())
													{
														$PkId = $row1['PkId'];
														$Quantity = $row1['Quantity'];
														$Price = $row1['Price'];
														$Amount = $row1['Amount'];
														$ProductId_ProductMaster = $row1['ProductId_ProductMaster'];
														$ProductName = $row1['ProductName'];
														$SKU = $row1['SKU'];
														$query2 = $dbConnection->prepare("SELECT * FROM ProductMaster WHERE ProductId=? AND DeleteStatus=?");
														$query2->execute(array($ProductId_ProductMaster,$delete_status));
														$row2 = $query2->fetch();
														$ProductName = $row2['ProductName'];
														$SKU = $row2['SKU'];
														?>
													<tr >
														<td><?php echo $ProductName;?></td>
														<td><?php echo $Quantity;?></td>
														<td><?php echo $Price;?></td>
														<td><?php echo $Amount;?></td>
													</tr>
													<?php
												}
												?>
												</tbody>
											</table>
										</div>

										<div class="hr hr8 hr-double hr-dotted"></div>

										<div class="row">
											<div class="col-sm-5 pull-right">
												<h5 class="pull-right">
													Sub amount :
													<span class="red"><?php echo $SubTotal;?></span>
												</h5>
												<!-- <div class="clearfix">&nbsp;</div>
												<h5 class="pull-right">
													GST Charges (<?php echo $GST;?> %):
													<span class="red"><?php echo $GSTAmount;?></span>
												</h5> -->
												<div class="clearfix">&nbsp;</div>
												<h5 class="pull-right">
													Additional Charges :
													<span class="red"><?php echo $AdditionalCharges;?></span>
												</h5>
												<div class="clearfix">&nbsp;</div>
												<h5 class="pull-right">
													Discount amount :
													<span class="red"><?php echo $DiscountAmount;?></span>
												</h5>
												<div class="clearfix">&nbsp;</div>
												<h4 class="pull-right">
													Total amount :
													<span class="red"> <i class="ace-icon fa fa-inr red"></i> <?php echo $OrderTotal;?></span>
												</h4>
											</div>
											<div class="col-sm-7 pull-left"> Extra Information </div>
										</div>

										<div class="space-6"></div>
										<!-- <div class="well">
											Thank you for choosing our company products.
						We believe you will be satisfied by our services.
										</div> -->
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
		return $result . "Rupees  and  " . $points . "  Paise  ONLY";
	}
?>