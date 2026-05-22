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
    	<script src="angularjs/list-inward-entries-script.js"></script>
    	<style type="text/css">
    		.list-container {
    			background: #ffffff;
    			border-radius: 12px;
    			box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    			padding: 25px;
    			margin-top: 20px;
    			border-top: 4px solid #438eb9;
    		}
    		.list-header {
    			margin-bottom: 20px;
    			display: flex;
    			justify-content: space-between;
    			align-items: center;
    			border-bottom: 1px solid #e5e5e5;
    			padding-bottom: 15px;
    		}
    		.list-header h2 {
    			margin: 0;
    			color: #393939;
    			font-size: 22px;
    			font-weight: 600;
    		}
    		.table-hover tbody tr:hover {
    			background-color: #f6fafe;
    		}
    		.btn-action {
    			padding: 4px 10px;
    			font-size: 12px;
    			border-radius: 4px;
    			font-weight: bold;
    		}
    	</style>
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="ListInwardModule" data-ng-controller="ListInwardController">
		<?php include_once("../top.php");?>

		<div class="main-container ace-save-state" id="main-container">
			<?php include_once("sidebar.php");?>

			<div class="main-content">
				<?php include_once("../loader.php");?>
				<div class="main-content-inner">
					<div class="page-content">
						<div class="row">
							<div class="col-xs-12">
								<div class="list-container">
									<div class="list-header">
										<div>
											<h2>Inward Roll Entries</h2>
											<p class="text-muted" style="margin: 5px 0 0 0;">View and search all incoming jumbo roll registrations.</p>
										</div>
										<div>
											<a href="add-inward-entry.php" class="btn btn-success btn-sm" style="border-radius: 4px; font-weight: bold;">
												<i class="ace-icon fa fa-plus"></i> Add New Inward
											</a>
										</div>
									</div>

									<div class="row" style="margin-bottom: 15px;">
										<div class="col-xs-12 col-sm-6 col-md-4">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="ace-icon fa fa-search"></i>
												</span>
												<input type="text" class="form-control" placeholder="Search by Roll ID, Supplier, Material..." ng-model="searchText" ng-change="Search()">
											</div>
										</div>
									</div>

									<div id="no-more-tables" class="dataTables_wrapper form-inline no-footer">
										<table class="table table-bordered table-striped table-hover table-condensed cf" data-ng-init="LoadInwardEntries()">
											<thead class="cf">
												<tr>
													<th>Inward Date</th>
													<th>Roll ID</th>
													<th>Supplier</th>
													<th>Material</th>
													<th>GSM</th>
													<th>Thickness</th>
													<th>Width (mm)</th>
													<th>Weight (kg)</th>
													<th>Cost/Kg</th>
													<th>Invoice / Challan</th>
													<th>Action</th>
												</tr>
											</thead>										
											<tbody>
												<tr ng-repeat="item in pagedItems">
													<td data-title="Date">{{item.InvoiceDateFormatted}}</td>
													<td data-title="Roll ID" style="font-weight: bold; color: #438eb9;">{{item.RollId}}</td>
													<td data-title="Supplier">{{item.SupplierName}}</td>
													<td data-title="Material">{{item.Material}}</td>
													<td data-title="GSM">{{item.GSM}}</td>
													<td data-title="Thickness">{{item.Thickness}} µm</td>
													<td data-title="Width">{{item.Width}}</td>
													<td data-title="Weight" style="font-weight: bold;">{{item.Weight}}</td>
													<td data-title="Cost/Kg">Rs. {{item.CostPerKg}}</td>
													<td data-title="Invoice">{{item.InvoiceNo}}</td>
													<td data-title="Action">
														<button class="btn btn-info btn-action" ng-click="PrintLabel(item.PkId)">
															<i class="fa fa-barcode"></i> Print Label
														</button>
													</td>
												</tr>
												<tr ng-if="pagedItems.length == 0">
													<td colspan="11" class="text-center" style="padding: 20px;">No inward entry records found.</td>
												</tr>
											</tbody>
										</table>
										
										<!-- Pagination -->
										<div class="row" ng-if="totalCount > pageSize">
											<div class="col-xs-6">
												<div class="dataTables_info">
													Showing {{((currentPage-1)*pageSize)+1}} to {{Math.min(currentPage*pageSize, totalCount)}} of {{totalCount}} entries
												</div>
											</div>
											<div class="col-xs-6 text-right">
												<ul class="pagination" style="margin: 0;">
													<li ng-class="{disabled: currentPage == 1}">
														<a href="" ng-click="PrevPage()">« Prev</a>
													</li>
													<li ng-repeat="page in pageRange" ng-class="{active: currentPage == page}">
														<a href="" ng-click="GoToPage(page)">{{page}}</a>
													</li>
													<li ng-class="{disabled: currentPage == totalPages}">
														<a href="" ng-click="NextPage()">Next »</a>
													</li>
												</ul>
											</div>
										</div>

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
	header('Location: ../logout.php');
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
?>
