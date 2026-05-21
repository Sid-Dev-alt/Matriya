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
	$Page = $_REQUEST['Page'];
	// barcode generaation start
	$barcodeType = "code128";
	$barcodeDisplay = "horizontal";
	$barcodeSize = "20";
	$printText = "true";

	$delete_status = 1;
	// $query3 = $dbConnection->prepare("SELECT * FROM SlittingRolls WHERE PkId=? AND DeleteStatus=?");
	// $query3->execute(array($FormPkId,$delete_status));
	// $row3 = $query3->fetch();
	// $RollNo = $row3['RollNo'];
	// $SlitQty = $row3['SlitQty'];

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

	// $query3 = $dbConnection->prepare("SELECT * FROM CompanyInfo WHERE DeleteStatus=?");
	// $query3->execute(array($delete_status));
	// $row3 = $query3->fetch();
	// $CompanyName = $row3['CompanyName'];
	// $EmailId = $row3['EmailId'];
	// $LogoFilename = $row3['LogoFilename'];
	// $AddressLane1 = $row3['AddressLane1'];
	// $City = $row3['City'];

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
       	        <p class="gap">&nbsp;</p>
       	        <p class="gap">&nbsp;</p>
				<a class="btn btn-info" href="#" onclick="window.print();"><i class="ace-icon fa fa-check bigger-110"></i> PRINT</a>&nbsp;&nbsp;&nbsp;
				<?php 
				if($Page=="Item")
				{
				?>
					<a class="btn btn-inverse" onclick="window.close();"><i class="ace-icon fa fa-close bigger-110"></i> BACK TO LIST</a>
				<?php
				}
				else
				{
				?>
				<a class="btn btn-inverse" href="slitting.php"><i class="ace-icon fa fa-close bigger-110"></i> BACK TO LIST</a>
				<?php
				}
				?>
			
			</div>
            <div class="col-sm-4 text-left">
			  <?php
				$query4 = $dbConnection->prepare("SELECT * FROM InventoryMaster WHERE PkId=? AND DeleteStatus=?");
				$query4->execute(array($FormPkId,$delete_status));
				while($row4 = $query4->fetch())
				{
					$ProductId_ProductMaster = $row4['ProductId_ProductMaster'];
					$UniqueRollNo = $row4['UniqueRollNo'];

					$query5 = $dbConnection->prepare("SELECT Micron,Size,Unit,ProductName FROM ProductMaster WHERE ProductId=?");
					$query5->execute(array($ProductId_ProductMaster));
					$row5 = $query5->fetch();
					$ProductName = $row5['ProductName'];
					$Micron = $row5['Micron'];
					$Size = $row5['Size'];
					$Unit = $row5['Unit'];
				?>

					<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf" >
					<tbody >
					<tr>
						<td>Roll Code:</td>
						<td><?php echo $UniqueRollNo;?></td>
					</tr>
					<tr>
						<td>Product:</td>
						<td><?php echo $ProductName;?></td>
					</tr>
					<tr>
						<td>ThickNess:</td>
						<td><?php echo $Micron;?></td>
					</tr>
					<tr>
						<td>Size:</td>
						<td><?php echo $Size;?> MM</td>
					</tr>
					<tr>
						<td>Net Weight:</td>
						<td><?php echo $SlitQty;?> <?php echo $Unit;?></td>
					</tr>
					<tr c>
						
					</tr>
					</tbody>	
				</table>
				<div class="clearfix"></div>
				<div class="panel-body centered">
				<img class="barcode" alt="'.<?php echo $ProductId;?>.'" src="barcode.php?text=<?php echo $UniqueRollNo;?>&codetype=<?php echo $barcodeType;?>&orientation=<?php echo $barcodeDisplay;?>&size=<?php echo $barcodeSize;?>&print=<?php echo $printText;?>" width="100%">
				</div>
				<hr>
					<div class="panel-body centered">
						
							<div class="text-center centered">
							<p><strong><?php echo ucfirst($CompanyName);?></strong></p>
							<p><?php echo $AddressLane1;?>  </p>
							<p><?php echo $AddressLane2;?></p>
							<p><strong>Contact-No: </strong><?php echo $CoMobile;?></p>
							<!--<p>EMAIL: <?php echo $CoEmailId;?></p>-->
							</div>
					</div>
				<?php
				}	
				?>
			<div>
			</div>
		</div>
	</div>
	</div>
</body>
</html>
<?php
}	 
?>
