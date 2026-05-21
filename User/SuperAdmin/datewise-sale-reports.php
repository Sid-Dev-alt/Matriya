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
    	<script src="angularjs/datewise-salereports-script.js"></script>
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="ReportModule" data-ng-controller="ReportController">
		<?php include_once("../top.php");?>
		<div class="main-container ace-save-state" id="main-container">
			
			<?php include_once("sidebar.php");?>

			<div class="main-content">
				<?php include_once("../loader.php");?>
				<div class="main-content-inner">
					<div class="page-content" >
						<div id="no-more-tables">
							<div class="row">
								<div class="col-xs-12 page-header">
			                  	 <div class="col-xs-8 col-sm-8 col-lg-9">
			                  	 	<div class="" >
										<h1>Datewise Sale Report</h1>
										</div>
			<!-- /.page-header --></div>
				              </div>							
							</div>
							<div class="col-md-12">
								<div class="form-group col-md-5">
									<label class="control-label no-padding-right" for="reason"> From Date <span class="error">*</span></label>
									<input type="text" class="form-control" name="fromdate"  placeholder="" id="fromdate" ng-model="fromdate" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened" ng-click="fromopen($event,'opened')"  datepicker-options="assDate" required=""> 
										<div class="error" data-ng-show="submitted || AddCategoryForm.fromdate.$dirty && AddCategoryForm.fromdate.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.fromdate.$error.required">Date is required.</small>
										</div>
								</div>

								<div class="form-group col-md-5">
									<label class="control-label no-padding-right" for="reason"> To Date <span class="error">*</span></label>
									<input type="text" class="form-control" name="todate"  placeholder="" id="todate" ng-model="todate" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened2" ng-click="toopen($event,'opened2')"  datepicker-options="assDate1">
										<div class="error" data-ng-show="submitted || AddCategoryForm.todate.$dirty && AddCategoryForm.todate.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.todate.$error.required">Date is required.</small>
										</div>
								</div>
								<div class="form-group col-md-2">
									<div class="clearfix">&nbsp;</div>
									<button type="button" class="btn btn-primary btn-sm" ng-click="GetListData(pageno)">Submit</button>
									<div class="clearfix">&nbsp;</div>
									
								</div>
							</div>
							<!-- <span class="error">{{errmsg}}</span> -->

							
							<div class="row">
								<div class="col-xs-12">
									<!-- PAGE CONTENT BEGINS -->
									<div class="row">
										<div class="space-6"></div>
										<div class="dataTables_wrapper form-inline no-footer" ng-if="pagedItems.length!='0'">
										<div class="row">
											<div class="col-xs-6">
												<div id="dynamic-table_filter" class="dataTables_filter pull-left"><label>Search:</label>
													<input type="text" ng-model="search" class="form-control" placeholder="Search"> &nbsp; Showing {{mypageno*itemsPerPage-itemsPerPage+1}}-{{mypageno*itemsPerPage}} of {{total_count}}
												</div>
											</div>
											<div class="col-xs-6" ng-if="pagedItems.length>0">
											<a href="download-dtwise-salereport.php?FromDate={{fromdate | date}}&ToDate={{todate | date}}" target="_self" class="btn btn-success" > Download</a>
											</div>
										</div>
									
										
										<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf">
											<thead class="cf">
												<tr>
													<th ng-click="sort('InvoiceId')">Bill Id <span class="glyphicon sort-icon" ng-show="sortKey=='InvoiceId'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
													<th ng-click="sort('InvDate')">Bill Date <span class="glyphicon sort-icon" ng-show="sortKey=='InvDate'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
													<th ng-click="sort('CustomerName')">Customer Name <span class="glyphicon sort-icon" ng-show="sortKey=='CustomerName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
													<th>Details</th>
													
												</tr>
											</thead>										
											<tbody >

												<tr dir-paginate="cat in pagedItems|orderBy:sortKey:reverse|filter:search|itemsPerPage:10" total-items="{{total_count}}" current-page="mypageno">
													<td data-title="Bill Id">{{cat.InvoiceId}}</td>
													<td data-title="Date">{{cat.InvDate | date: 'd MMM y'}}</td>
													<td data-title="Customer Name">{{cat.CustomerName | date: 'd MMM y'}}</td>
													<td data-title="Details">
														<button class="btn btn-sm btn-success" ng-click="ViewData(cat.InvoiceId,cat.InvDate,cat.CustomerName,cat.data2)">View</button>
													</td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="8">
														<ul class="pagination">
															<li>
															    <dir-pagination-controls max-size="10" direction-links="true" boundary-links="true" on-page-change="GetListData(newPageNumber)"></dir-pagination-controls>
													        </li>
													    </ul>
													</td>
												</tr>
											</tfoot>
										</table>
									</div>
									</div><!-- /.row -->

									<div id="myModal1" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
										<div class="modal-dialog  modal-lg">
											<div class="modal-content">
												<div class="modal-header">
													<div class="col-xs-12">
													<div class="pull-left">
														<h4>Invoice Details</h4>
													</div>
													<div class="pull-right">
														<button type="button" class="close" data-ng-click="myModal1Close()">&times;</button>
													</div>
													</div>
												</div>
												<div class="modal-body">
													<div class="row">
														<p>Invoice Id : {{InvoiceId}}</p>
														<p>Invoice Date : {{InvDate | date: 'd MMM y'}}</p>
														<p>Customer Name : {{CustomerName}}</p>
														<table id="dynamic-table1" class="table table-bordered table-striped table-condensed cf">
														<thead class="cf">
															<tr>
																<th>Product Name</th>
																<th>Roll No</th>
																<th>Quantity</th>
																<th>Remarks</th>
																<th>History</th>
															</tr>
														</thead>										
														<tbody >

															<tr ng-repeat="info in data2">
																<td data-title="Product Name">{{info.Micron}} {{info.ProductName}} {{info.InvProductSize}} MM</td>
																	<td data-title="Roll No">{{info.InvUniqueRollNo}}</td>
																	<td data-title="Quantity">{{info.Quantity}} {{info.Unit}}</td>
																	<td data-title="Remarks"><p class="pre-data">{{info.Remarks}} </p></td>
																	<td>
																		<ul>
																		<li><span class="text-danger">{{info.TotalName}} {{info.POProductSize}} MM ( {{info.PurchaseRollNo}} ) </span> Purchased from the <b> {{info.VendorName}}</b> vendor on <b>{{info.RawPODate | date: 'd MMM y'}}</b></li>
																		 <li>Purchase Weight/Qty is <b>{{info.PurchaseQty}} {{info.Unit}}</b></li>
																		 <!-- <li>Stock received at <b>{{GoDownName}}</b></li> -->
																			

																		<li ng-if="info.IsSlitted=='1'"><span class="text-success">This item roll has been slit from <span class="text-danger"> {{info.TotalName}} {{info.POProductSize}} MM </span> on <b>{{info.SlitDate | date: 'd MMM y'}} </b></li>	

																		<li ng-if="info.IsSlitted=='1'">Slit Qty is <b>{{info.SlitQty}} {{info.Unit}}</b></li>

																		<li ng-if="info/IsSplitQty=='1'">
																			<p>Roll has slitted into:</p>
																			<div ng-repeat="slit in info.data4">
																				<p><b>{{info.TotalName}} {{slit.SplitSize}} MM</b> Weight/Qty Is <b>{{slit.SlitQty}} {{UnitModal}}</b></p>
																			</div>
																		</li>
																		<li>
																			Roll has been dispatched to <span class="text-success">{{CustomerName}}</span> on <b>{{InvDate | date: 'd MMM y'}} </b></li>
																	</ul>
																	</td>
															</tr>
														</tbody>
													</table>
													</div>
												</div>
											</div>
										</div>
									</div>

                        <div id="myModal" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
						<div class="modal-dialog  modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<div class="col-xs-12">
									<div class="pull-left">
										<h4>Roll Details</h4>
									</div>
									<div class="pull-right">
										<button type="button" class="close" data-ng-click="ClosemyModal()">&times;</button>
									</div>
									</div>
								</div>
								<div class="modal-body">
								<div class="row">
								<div class="col-xs-12">
									<!-- PAGE CONTENT BEGINS -->
									<div class="card">
										<div class="space-6"></div>

									<div class="card-body" ng-if="result=='OK'">
										<h4>{{TotalName}} {{InvProductSize}} MM</h4>
										<p>{{InvUniqueRollNo}}</p>
										<h4>Avl Qty: {{AvlQuantity}} {{UnitModal}}</h4>
										<ul>
											<li><span class="text-danger">{{TotalName}} {{POProductSize}} MM ( {{PurchaseRollNo}} ) </span> Purchased from the <b> {{VendorNameModal}}</b> vendor on <b>{{RawPODateModal | date: 'd MMM y'}}</b></li>
											 <li>Purchase Weight/Qty is <b>{{PurchaseQtyModal}} {{UnitModal}}</b></li>
											 <!-- <li>Stock received at <b>{{GoDownName}}</b></li> -->
												

											<li ng-if="IsSlitted=='1'"><span class="text-success">{{TotalName}} {{InvProductSize}} MM ( {{InvUniqueRollNo}} ) </span> has been slit from <span class="text-danger"> {{TotalName}} {{POProductSize}} MM </span> on <b>{{SlitDate | date: 'd MMM y'}} </b></li>	

											<li ng-if="IsSlitted=='1'">Slit Qty is <b>{{SlitQty}} {{UnitModal}}</b></li>

											<li ng-if="IsSplitQty=='1'">
												<p>Roll has slitted into:</p>
												<div ng-repeat="slit in data2">
													<p><b>{{TotalName}} {{slit.SplitSize}} MM</b> Weight/Qty Is <b>{{slit.SlitQty}} {{UnitModal}}</b></p>
												</div>
											</li>

											<li ng-if="IsInvoice=='1'">
												Roll has been dispatched to <span class="text-success">{{CustomerNameModal}}</span> on <b>{{InvoiceDate | date: 'd MMM y'}} </b></li>
											
											<!-- <li ng-if="IsSlitted>'1'"><span class="text-success">{{TotalProductName}} {{ProductSize}} MM ( {{UniqueRollNo}} ) </span> Sliited From <span class="text-danger"> {{TotalName}} {{POProductSize}} MM </span> on <b>{{SlitDate | date: 'd MMM y'}} </b></li> -->
										</ul>
										<!-- <div class="row">
											<div class="col-xs-6">
												<div id="dynamic-table_filter" class="dataTables_filter pull-left"><label>Search:</label>
													<input type="text" ng-model="search" class="form-control" placeholder="Search"> &nbsp; Showing {{mypageno*itemsPerPage-itemsPerPage+1}}-{{mypageno*itemsPerPage}} of {{total_count}}
												</div>
											</div>
											<div class="col-xs-6">
											<a href="datewise-bill-report.php?FromDate={{fromdate | date}}&ToDate={{todate | date}}" target="_self" class="btn btn-success" > Download</a>
											</div>
										</div> -->
										<!-- <table id="dynamic-table" class="table table-bordered table-striped table-condensed cf"  datatable="ng" dt-options="vm.dtOptions" dt-column-defs="vm.dtColumnDefs">
											<thead class="cf">
												<tr>
													<th>Item Name</th>
													<th>Roll No</th>
													<th>Qty</th>
												</tr>
											</thead>										
											<tbody>
												<tr ng-repeat="cat in pagedItems">
												<td data-title="Item Name">{{cat.TotalProductName}} {{cat.ProductSize}} MM</td>
												<td data-title="Roll No">
													<a href="" data-toggle="modal" data-target="#modal-form" ng-click="GetProductData(cat.TotalProductName,cat.ProductSize,cat.UniqueRollNo,cat.Unit)"> {{cat.UniqueRollNo}}
													</a>
												</td>
												<td data-title="Qty">{{cat.Quantity}} {{cat.Unit}}</td>
												</tr>
												
											</tbody>
										</table> -->
									</div>
									</div><!-- /.row -->

									<!-- PAGE CONTENT ENDS -->
								</div><!-- /.col -->
							</div>
								
							</div>
							
							</div>
						</div>
					</div> <!--modal-->
									<!-- PAGE CONTENT ENDS -->
								</div><!-- /.col -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php include_once("../footer.php");?>
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