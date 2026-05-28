<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']!="")
{
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once("../title.php");?>
    	<script src="angularjs/order-form-script.js"></script>
    	<style type="text/css">
    		.order-form-container {
    			background: #ffffff;
    			border: 1px solid #e3e3e3;
    			border-radius: 4px;
    			padding: 30px;
    			margin-top: 15px;
    			box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    		}
    		.order-form-container .form-group {
    			margin-bottom: 15px;
    		}
    		.custom-table-form {
    			width: 100%;
    			margin-top: 25px;
    			margin-bottom: 20px;
    			border-collapse: collapse;
    		}
    		.custom-table-form th {
    			background-color: #f5f5f5;
    			border: 1px solid #ddd;
    			padding: 10px;
    			font-weight: normal;
    			color: #333;
    			font-size: 13px;
    		}
    		.custom-table-form td {
    			border: 1px solid #ddd;
    			padding: 10px;
    			vertical-align: top;
    			background-color: #fff;
    		}
    		.btn-add-more {
    			background-color: #5bc0de !important;
    			border-color: #46b8da !important;
    			color: #ffffff !important;
    			font-weight: normal;
    			border-radius: 0px;
    			padding: 6px 16px;
    			font-size: 13px;
    			transition: all 0.2s;
    		}
    		.btn-add-more:hover {
    			background-color: #31b0d5 !important;
    			border-color: #269abc !important;
    		}
    		.btn-save-custom {
    			background-color: #5b9bd5 !important;
    			border-color: #5b9bd5 !important;
    			color: #ffffff !important;
    			font-weight: normal;
    			border-radius: 3px;
    			padding: 8px 24px;
    			font-size: 13px;
    		}
    		.btn-save-custom:hover {
    			background-color: #417cb8 !important;
    			border-color: #417cb8 !important;
    		}
    		.btn-cancel-custom {
    			background-color: #444444 !important;
    			border-color: #444444 !important;
    			color: #ffffff !important;
    			font-weight: normal;
    			border-radius: 3px;
    			padding: 8px 24px;
    			font-size: 13px;
    		}
    		.btn-cancel-custom:hover {
    			background-color: #222222 !important;
    			border-color: #222222 !important;
    		}
    		.table-card-premium {
    			background: #ffffff;
    			border: 1px solid #e3e3e3;
    			border-radius: 4px;
    			padding: 20px;
    			margin-top: 30px;
    			box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    		}
    		.table-card-premium h3 {
    			margin-top: 0;
    			margin-bottom: 15px;
    			font-size: 17px;
    			font-weight: normal;
    			color: #333;
    			border-bottom: 1px solid #eee;
    			padding-bottom: 10px;
    		}
    		.custom-data-table {
    			width: 100%;
    			border: 1px solid #ddd;
    		}
    		.custom-data-table th {
    			background-color: #f5f5f5;
    			border: 1px solid #ddd;
    			font-weight: normal;
    			color: #555;
    			padding: 8px 12px;
    			font-size: 12px;
    		}
    		.custom-data-table td {
    			border: 1px solid #ddd;
    			padding: 8px 12px;
    			font-size: 12px;
    			color: #333;
    		}
    	</style>
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="OrderModule" data-ng-controller="OrderController" data-ng-init="initData()">
		<?php include_once("../top.php");?>

		<div class="main-container ace-save-state" id="main-container">
			<?php include_once("sidebar.php");?>

			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						
						<div class="page-header" style="padding-bottom: 0px; margin: 0 0 12px;">
							<h1>Order</h1>
						</div>

						<!-- Form container matching the design layout exactly -->
						<div class="order-form-container">
							<form class="form-horizontal" role="form" name="OrderForm" novalidate>
								
								<!-- Order-Id -->
								<div class="form-group">
									<label class="col-sm-4 control-label no-padding-right">Order-Id <span class="text-danger">*</span></label>
									<div class="col-sm-4">
										<input type="text" class="form-control" ng-model="OrderId" readonly style="background-color: #eeeeee !important; cursor: not-allowed;">
									</div>
								</div>

								<!-- Order-Date -->
								<div class="form-group">
									<label class="col-sm-4 control-label no-padding-right">Order-Date <span class="text-danger">*</span></label>
									<div class="col-sm-4">
										<div class="input-group">
											<input type="text" class="form-control" name="OrderDate" uib-datepicker-popup="dd-MMM-yyyy" ng-model="OrderDate" is-open="opened.openedDate" ng-click="openDatepicker($event)" required readonly style="background-color: #ffffff !important; cursor: pointer;">
											<span class="input-group-btn">
												<button type="button" class="btn btn-default btn-sm" ng-click="openDatepicker($event)" style="height: 34px;"><i class="fa fa-calendar"></i></button>
											</span>
										</div>
										<div class="text-danger" ng-show="submitted && OrderForm.OrderDate.$invalid">
											<small>Order Date is required.</small>
										</div>
									</div>
								</div>

								<!-- Party Name -->
								<div class="form-group">
									<label class="col-sm-4 control-label no-padding-right">Party Name <span class="text-danger">*</span></label>
									<div class="col-sm-4">
										<ui-select ng-model="$parent.partyname" theme="selectize" title="Choose a party" required>
											<ui-select-match placeholder="Select or search">{{$select.selected.DisplayName}}</ui-select-match>
											<ui-select-choices repeat="item in CustomerArray | filter: $select.search">
												<div><span ng-bind-html="item.DisplayName | highlight: $select.search"></span></div>
											</ui-select-choices>
										</ui-select>
										<div class="text-danger" ng-show="submitted && !partyname">
											<small>Party Name is required.</small>
										</div>
									</div>
								</div>

								<!-- Table for entering multiple item details -->
								<div class="table-responsive">
									<table class="custom-table-form">
										<thead>
											<tr>
												<th style="width: 40%;">Item Details</th>
												<th style="width: 25%;">Weight / Qty</th>
												<th style="width: 25%;">Remarks</th>
												<th style="width: 10%;">ACTION</th>
											</tr>
										</thead>
										<tbody>
											<tr ng-repeat="row in itemRows">
												<td>
													<ui-select ng-model="row.selectedProduct" theme="selectize" title="Choose a product" ng-change="onProductSelect(row, $index)">
														<ui-select-match placeholder="Select or search">{{$select.selected.TotalProductName}}</ui-select-match>
														<ui-select-choices repeat="item in ProductArray | filter: $select.search">
															<div><span ng-bind-html="item.TotalProductName | highlight: $select.search"></span></div>
														</ui-select-choices>
													</ui-select>
													<div style="margin-top: 5px; font-size: 11px; color: #666;" ng-if="row.selectedProduct">
														Available Qty: {{row.selectedProduct.AvlQty || '0'}} {{row.selectedProduct.Unit}}
													</div>
												</td>
												<td>
													<div class="input-group">
														<input type="text" class="form-control" ng-model="row.Quantity" required placeholder="0.000">
														<span class="input-group-addon">{{row.selectedProduct.Unit || 'Kg'}}</span>
													</div>
												</td>
												<td>
													<textarea class="form-control" ng-model="row.Remarks" rows="2" style="resize: vertical;"></textarea>
												</td>
												<td class="text-center" style="vertical-align: middle;">
													<a href="" class="orange" ng-click="removeRow($index)">
														<i class="ace-icon fa fa-remove bigger-150"></i>
													</a>
												</td>
											</tr>
										</tbody>
									</table>
								</div>

								<!-- Add More row button -->
								<div class="row" style="margin-bottom: 25px;">
									<div class="col-xs-12">
										<button type="button" class="btn btn-add-more" ng-click="addRow()" style="width: 30%;">Add More</button>
									</div>
								</div>

								<!-- Action buttons -->
								<div class="clearfix form-actions" style="background-color: transparent; border-top: none; margin: 0; padding: 0;">
									<div class="text-center">
										<button class="btn btn-save-custom" type="button" ng-click="saveOrder()">
											<i class="ace-icon fa fa-check bigger-110"></i>
											SAVE
										</button>
										&nbsp; &nbsp;
										<button class="btn btn-cancel-custom" type="button" ng-click="cancelOrder()">
											<i class="ace-icon fa fa-close bigger-110"></i>
											CANCEL
										</button>
									</div>
								</div>

							</form>
						</div>

						<!-- Two database tables listing saved entries -->
						<div class="row">
							
							<!-- Table 1: orders -->
							<div class="col-md-6">
								<div class="table-card-premium">
									<h3>orders</h3>
									<div class="table-responsive">
										<table class="table table-striped table-bordered custom-data-table">
											<thead>
												<tr>
													<th>Order ID</th>
													<th>Customer Name</th>
													<th>Order Date</th>
												</tr>
											</thead>
											<tbody>
												<tr ng-repeat="order in orders">
													<td><strong class="text-primary">{{order.OrderId}}</strong></td>
													<td>{{order.CustomerName}}</td>
													<td>{{order.OrderDate | date:'dd-MMM-yyyy'}}</td>
												</tr>
												<tr ng-if="orders.length === 0">
													<td colspan="3" class="text-center" style="color: #999;">
														No orders found.
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>

							<!-- Table 2: order details -->
							<div class="col-md-6">
								<div class="table-card-premium">
									<h3>order details</h3>
									<div class="table-responsive">
										<table class="table table-striped table-bordered custom-data-table">
											<thead>
												<tr>
													<th>Order ID</th>
													<th>Product Name</th>
													<th>Qty</th>
													<th>Remarks</th>
												</tr>
											</thead>
											<tbody>
												<tr ng-repeat="detail in orderDetails">
													<td><strong class="text-info">{{detail.OrderId}}</strong></td>
													<td>{{detail.ProductName}}</td>
													<td>{{detail.Quantity}}</td>
													<td>{{detail.Remarks}}</td>
												</tr>
												<tr ng-if="orderDetails.length === 0">
													<td colspan="4" class="text-center" style="color: #999;">
														No order details found.
													</td>
												</tr>
											</tbody>
										</table>
									</div>
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
	header("Location: ../../index.php");
}
?>
