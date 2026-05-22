<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
else
{	
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";
	$FormPkId = $_REQUEST['Id'];
	
	$barcodeType = "code128";
	$barcodeDisplay = "horizontal";
	$barcodeSize = "20";
	$printText = "true";

	$delete_status = 1;
	$query = $dbConnection->prepare("SELECT ir.*, v.DisplayName as SupplierName FROM InwardRolls ir JOIN VendorMaster v ON ir.VendorId = v.VendorId WHERE ir.PkId=? AND ir.DeleteStatus=?");
	$query->execute(array($FormPkId, $delete_status));
	
	if($query->rowCount() > 0)
	{
		$row = $query->fetch();
		$RollId = $row['RollId'];
		$SupplierName = $row['SupplierName'];
		$Material = $row['Material'];
		$GSM = $row['GSM'];
		$Thickness = $row['Thickness'];
		$Width = $row['Width'];
		$Weight = $row['Weight'];
		$InvoiceNo = $row['InvoiceNo'];
		$InvoiceDate = date("d-M-Y", strtotime($row['InvoiceDate']));
		$Notes = $row['Notes'];
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include_once("../title.php");?>
		<link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="../../assets/css/barcodeprint.css">    
		<title>Inward Roll Barcode Label - <?php echo $RollId; ?></title>
		<style type="text/css">
			body {
				background: #f5f5f5;
				font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
			}
			.print-container {
				background: #ffffff;
				max-width: 400px;
				margin: 30px auto;
				padding: 20px;
				border: 1px dashed #ccc;
				border-radius: 8px;
				box-shadow: 0 4px 10px rgba(0,0,0,0.05);
			}
			.label-title {
				text-align: center;
				font-weight: bold;
				font-size: 18px;
				margin-bottom: 10px;
				border-bottom: 2px solid #333;
				padding-bottom: 5px;
				text-transform: uppercase;
				letter-spacing: 1px;
			}
			.label-table {
				width: 100%;
				margin-bottom: 15px;
			}
			.label-table td {
				padding: 6px 4px;
				font-size: 14px;
				border-bottom: 1px solid #eee;
			}
			.label-table td:first-child {
				font-weight: bold;
				color: #555;
				width: 40%;
			}
			.label-table td:last-child {
				color: #000;
			}
			.barcode-wrapper {
				text-align: center;
				margin-top: 15px;
			}
			.barcode-img {
				max-width: 100%;
				height: auto;
			}
			.print-actions {
				margin-bottom: 20px;
				text-align: center;
			}
			@media print {
				.print-actions {
					display: none;
				}
				body {
					background: #fff;
				}
				.print-container {
					margin: 0;
					border: none;
					box-shadow: none;
					padding: 0;
					max-width: 100%;
				}
			}
		</style>
	</head>
	<body onload="window.print()">
		<div class="container">
			<div class="row print-actions" style="margin-top: 20px;">
				<div class="col-xs-12">
					<button class="btn btn-info" onclick="window.print();">
						<i class="ace-icon fa fa-print"></i> PRINT LABEL
					</button>
					&nbsp;&nbsp;
					<button class="btn btn-inverse" onclick="window.close();">
						<i class="ace-icon fa fa-close"></i> CLOSE WINDOW
					</button>
				</div>
			</div>
			
			<div class="print-container">
				<div class="label-title">MR Polymers - Inward Roll</div>
				<table class="label-table">
					<tbody>
						<tr>
							<td>Roll ID:</td>
							<td style="font-size: 16px; font-weight: bold; color: #d15b47;"><?php echo $RollId; ?></td>
						</tr>
						<tr>
							<td>Material:</td>
							<td><?php echo $Material; ?></td>
						</tr>
						<tr>
							<td>GSM:</td>
							<td><?php echo $GSM; ?> g/m²</td>
						</tr>
						<tr>
							<td>Thickness:</td>
							<td><?php echo $Thickness; ?> Micron (µm)</td>
						</tr>
						<tr>
							<td>Width:</td>
							<td><?php echo $Width; ?> mm</td>
						</tr>
						<tr>
							<td>Weight:</td>
							<td style="font-weight: bold;"><?php echo $Weight; ?> kg</td>
						</tr>
						<tr>
							<td>Supplier:</td>
							<td><?php echo $SupplierName; ?></td>
						</tr>
						<tr>
							<td>Invoice No:</td>
							<td><?php echo $InvoiceNo; ?></td>
						</tr>
						<tr>
							<td>Inward Date:</td>
							<td><?php echo $InvoiceDate; ?></td>
						</tr>
						<?php if(!empty($Notes)) { ?>
						<tr>
							<td>Details:</td>
							<td style="font-size: 12px; font-style: italic;"><?php echo $Notes; ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				
				<div class="barcode-wrapper">
					<img class="barcode-img" alt="<?php echo $RollId; ?>" src="barcode.php?text=<?php echo $RollId; ?>&codetype=<?php echo $barcodeType; ?>&orientation=<?php echo $barcodeDisplay; ?>&size=<?php echo $barcodeSize; ?>&print=<?php echo $printText; ?>">
				</div>
			</div>
		</div>
	</body>
</html>
<?php
	}
	else
	{
		echo "<h3>Roll not found.</h3>";
	}
}
?>
