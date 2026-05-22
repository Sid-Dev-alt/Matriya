<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']!="")
{
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once("../title.php");?>		
		<link rel="stylesheet" href="../../assets/css/colorbox.min.css" />
    	<script src="angularjs/add-inward-entry-script.js"></script>
    	<style type="text/css">
    		.form-container {
    			background: #ffffff;
    			border-radius: 12px;
    			box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    			padding: 30px;
    			margin-top: 20px;
    			border-top: 4px solid #438eb9;
    		}
    		.form-header {
    			margin-bottom: 25px;
    			border-bottom: 1px solid #e5e5e5;
    			padding-bottom: 15px;
    		}
    		.form-header h2 {
    			margin: 0;
    			color: #393939;
    			font-size: 24px;
    			font-weight: 600;
    		}
    		.form-group label {
    			font-weight: 600;
    			color: #555;
    		}
    		.error-msg {
    			color: #dd5a43;
    			font-size: 12px;
    			margin-top: 5px;
    		}
    		.btn-save {
    			background-color: #438eb9 !important;
    			border-color: #438eb9;
    			padding: 8px 20px;
    			font-size: 15px;
    			font-weight: bold;
    			border-radius: 4px;
    			transition: all 0.2s;
    		}
    		.btn-save:hover {
    			background-color: #2c6e95 !important;
    			border-color: #2c6e95;
    		}
    	</style>
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="InwardModule" data-ng-controller="InwardController" data-ng-init="GetVendors();">
		<?php include_once("../top.php");?>

		<div class="main-container ace-save-state" id="main-container">			
			<?php include_once("sidebar.php");?>
			
			<div class="main-content">
				<?php include_once("../loader.php");?>
				<div class="main-content-inner">
					<div class="page-content">
						<div class="row">
							<div class="col-xs-12 col-md-10 col-md-offset-1">
								<div class="form-container">
									<div class="form-header">
										<h2>Inward Entry (Jumbo Roll)</h2>
										<p class="text-muted">Capture incoming jumbo roll details and auto-generate unique Roll ID & Barcode.</p>
									</div>
									
									<form class="form-horizontal" role="form" id="AddInwardForm" name="AddInwardForm" autocomplete="off" novalidate data-ng-submit="SaveInwardData()">
										
										<!-- Supplier -->
										<div class="form-group">
											<label class="col-sm-3 control-label no-padding-right" for="supplier">Supplier <span class="text-danger">*</span></label>
											<div class="col-sm-6">
												<ui-select ng-model="inward.supplier" theme="selectize" title="Choose a supplier" required>
													<ui-select-match placeholder="Select or search supplier...">{{$select.selected.DisplayName}}</ui-select-match>
													<ui-select-choices repeat="item in VendorArray | filter: $select.search">
														<div ng-bind-html="item.DisplayName | highlight: $select.search"></div>
													</ui-select-choices>
												</ui-select>
												<div class="error-msg" ng-show="submitted && !inward.supplier">
													Supplier is required.
												</div>
											</div>
										</div>
										
										<!-- Material Type -->
										<div class="form-group">
											<label class="col-sm-3 control-label no-padding-right" for="material">Material <span class="text-danger">*</span></label>
											<div class="col-sm-6">
												<select class="form-control" name="material" ng-model="inward.material" required>
													<option value="">-- Select Material --</option>
													<option value="HDPE">HDPE</option>
													<option value="LDPE">LDPE</option>
													<option value="LLDPE">LLDPE</option>
													<option value="PP">PP</option>
													<option value="BOPP">BOPP</option>
													<option value="PET">PET</option>
													<option value="CPP">CPP</option>
												</select>
												<div class="error-msg" ng-show="submitted && !inward.material">
													Material type is required.
												</div>
											</div>
										</div>
										
										<!-- GSM -->
										<div class="form-group">
											<label class="col-sm-3 control-label no-padding-right" for="gsm">GSM <span class="text-danger">*</span></label>
											<div class="col-sm-4">
												<div class="input-group">
													<input type="number" class="form-control" name="gsm" ng-model="inward.gsm" placeholder="e.g. 23" required min="1">
													<span class="input-group-addon">g/m²</span>
												</div>
												<div class="error-msg" ng-show="submitted && !inward.gsm">
													GSM is required.
												</div>
											</div>
										</div>
										
										<!-- Thickness / Micron -->
										<div class="form-group">
											<label class="col-sm-3 control-label no-padding-right" for="thickness">Thickness (Micron) <span class="text-danger">*</span></label>
											<div class="col-sm-4">
												<div class="input-group">
													<input type="number" class="form-control" name="thickness" ng-model="inward.thickness" placeholder="e.g. 25" required min="1">
													<span class="input-group-addon">µm</span>
												</div>
												<div class="error-msg" ng-show="submitted && !inward.thickness">
													Thickness/Micron is required.
												</div>
											</div>
										</div>
										
										<!-- Width -->
										<div class="form-group">
											<label class="col-sm-3 control-label no-padding-right" for="width">Width <span class="text-danger">*</span></label>
											<div class="col-sm-4">
												<div class="input-group">
													<input type="number" class="form-control" name="width" ng-model="inward.width" placeholder="e.g. 1250" required min="1">
													<span class="input-group-addon">mm</span>
												</div>
												<div class="error-msg" ng-show="submitted && !inward.width">
													Width is required.
												</div>
											</div>
										</div>
										
										<!-- Weight -->
										<div class="form-group">
											<label class="col-sm-3 control-label no-padding-right" for="weight">Weight <span class="text-danger">*</span></label>
											<div class="col-sm-4">
												<div class="input-group">
													<input type="number" step="0.001" class="form-control" name="weight" ng-model="inward.weight" placeholder="e.g. 980.5" required min="0.001">
													<span class="input-group-addon">kg</span>
												</div>
												<div class="error-msg" ng-show="submitted && !inward.weight">
													Weight is required.
												</div>
											</div>
										</div>
										
										<!-- Cost/kg -->
										<div class="form-group">
											<label class="col-sm-3 control-label no-padding-right" for="cost">Cost per Kg <span class="text-danger">*</span></label>
											<div class="col-sm-4">
												<div class="input-group">
													<span class="input-group-addon">Rs.</span>
													<input type="number" step="0.01" class="form-control" name="cost" ng-model="inward.cost" placeholder="e.g. 102.50" required min="0.01">
												</div>
												<div class="error-msg" ng-show="submitted && !inward.cost">
													Cost per Kg is required.
												</div>
											</div>
										</div>
										
										<!-- Invoice / Challan No -->
										<div class="form-group">
											<label class="col-sm-3 control-label no-padding-right" for="invoice_no">Invoice / Challan No <span class="text-danger">*</span></label>
											<div class="col-sm-6">
												<input type="text" class="form-control" name="invoice_no" ng-model="inward.invoice_no" placeholder="e.g. KP/24-25/1187" required>
												<div class="error-msg" ng-show="submitted && !inward.invoice_no">
													Invoice/Challan Number is required.
												</div>
											</div>
										</div>
										
										<!-- Invoice Date -->
										<div class="form-group">
											<label class="col-sm-3 control-label no-padding-right" for="invoice_date">Invoice Date <span class="text-danger">*</span></label>
											<div class="col-sm-4">
												<input type="text" class="form-control" name="invoice_date" placeholder="Click to select date" id="invoice_date" ng-model="inward.invoice_date" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened1" ng-click="singleopen($event,'opened1')" datepicker-options="dateOptions" required readonly>
												<div class="error-msg" ng-show="submitted && !inward.invoice_date">
													Invoice Date is required.
												</div>
											</div>
										</div>
										
										<!-- Core Size -->
										<div class="form-group">
											<label class="col-sm-3 control-label no-padding-right" for="core_size">Core Size <span class="text-danger">*</span></label>
											<div class="col-sm-4">
												<select class="form-control" name="core_size" ng-model="inward.core_size" required>
													<option value="3 Inch">3 Inch</option>
													<option value="6 Inch">6 Inch</option>
												</select>
												<div class="error-msg" ng-show="submitted && !inward.core_size">
													Core size is required.
												</div>
											</div>
										</div>
										
										<!-- Notes -->
										<div class="form-group">
											<label class="col-sm-3 control-label no-padding-right" for="notes">Notes (Optional)</label>
											<div class="col-sm-6">
												<textarea class="form-control" name="notes" ng-model="inward.notes" rows="3" placeholder="Enter notes if any..."></textarea>
											</div>
										</div>
										
										<!-- Print Label Checkbox -->
										<div class="form-group">
											<div class="col-sm-offset-3 col-sm-6">
												<div class="checkbox">
													<label>
														<input type="checkbox" class="ace" ng-model="inward.print_label">
														<span class="lbl"> Print barcode label after save</span>
													</label>
												</div>
											</div>
										</div>
										
										<!-- Actions -->
										<div class="clearfix form-actions">
											<div class="col-md-offset-3 col-md-9">
												<button class="btn btn-info btn-save" type="submit">
													<i class="ace-icon fa fa-check bigger-110"></i>
													SAVE ENTRY
												</button>
												&nbsp; &nbsp;
												<button class="btn btn-inverse" type="button" ng-click="Cancel()">
													<i class="ace-icon fa fa-close bigger-110"></i>
													CANCEL
												</button>
											</div>
										</div>
										
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<?php include_once("../footer.php");?>
		</div>
	</body>
</html>
<?php
}
else
{ 
	header('Location: ../logout.php');
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
?>
