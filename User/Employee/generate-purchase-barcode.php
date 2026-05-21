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
	// barcode generaation start
	$barcodeType = "code128";
	$barcodeDisplay = "horizontal";
	$barcodeSize = "20";
	$printText = "true";

	$delete_status = 1;
	$query3 = $dbConnection->prepare("SELECT * FROM RawPurchaseMasterDetails WHERE PkId=? AND DeleteStatus=?");
	$query3->execute(array($FormPkId,$delete_status));
	$row3 = $query3->fetch();
	$RollNo = $row3['RollNo'];
	$PurchaseQty = $row3['PurchaseQty'];

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
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include_once("../title.php");?>
		<link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="../../assets/css/barcodeprint.css">    
		<title>Barcode Print</title>
		<style type="text/css">
			hr{
				margin:0;	
				border-top: 1px solid #000;	
			}
			p{
				margin:0; 
			}
		</style>
	</head>
   <body onload="window.print()">
       <div class="container">
       	<div class="row">
       	    <div class="col-md-offset-3 col-md-9">
				<a class="btn btn-info" href="#" onclick="window.print();"><i class="ace-icon fa fa-check bigger-110"></i> PRINT</a>&nbsp;&nbsp;&nbsp;<a class="btn btn-inverse" onclick="window.close();"><i class="ace-icon fa fa-close bigger-110"></i> BACK TO LIST</a>
			</div>
            <div class="panel-body">
			  <?php
				$query4 = $dbConnection->prepare("SELECT * FROM InventoryMaster WHERE UniqueRollNo=? AND DeleteStatus=?");
				$query4->execute(array($RollNo,$delete_status));
				while($row4 = $query4->fetch())
				{
					$ProductId_ProductMaster = $row4['ProductId_ProductMaster'];
					$UniqueRollNo = $row4['UniqueRollNo'];
					$Size = $row4['ProductSize'];

					$query5 = $dbConnection->prepare("SELECT Micron,Unit,ProductName FROM ProductMaster WHERE ProductId=?");
					$query5->execute(array($ProductId_ProductMaster));
					$row5 = $query5->fetch();
					$ProductName = $row5['ProductName'];
					$Micron = $row5['Micron'];
					$Unit = $row5['Unit'];
				?>

					<table align="center" border="1" width="100%">
					<tbody>
					<tr>
						<td>Product:</td>
						<td><?php echo $ProductName;?></td>
					</tr>
					<tr>
						<td>Microns:</td>
						<td><?php echo $Micron;?></td>
					</tr>
					<tr>
						<td>Size:</td>
						<td><?php echo $Size;?> MM</td>
					</tr>
					<tr>
						<td>Net Weight:</td>
						<td><?php echo $PurchaseQty;?> <?php echo $Unit;?></td>
					</tr>
					<tr>
						<td>Roll Code:</td>
						<td><?php echo $UniqueRollNo;?></td>
					</tr>
					</tbody>	
				</table>
				<img class="barcode" alt="'.<?php echo $ProductId;?>.'" src="barcode.php?text=<?php echo $UniqueRollNo;?>&codetype=<?php echo $barcodeType;?>&orientation=<?php echo $barcodeDisplay;?>&size=<?php echo $barcodeSize;?>&print=<?php echo $printText;?>" width="100%">
				<?php
				}	
				?>
		</div>
	</div>
	</div>
</body>
</html>
<?php
}	 
?>
