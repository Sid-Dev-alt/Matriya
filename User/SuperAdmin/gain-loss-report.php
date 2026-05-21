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
    	<script src="angularjs/gain-loss-script.js"></script>
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="ReportModule" data-ng-controller="ReportController" ng-init="GetItems()">
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
										<h1>Item wise Available Report</h1>
										</div>
			<!-- /.page-header --></div>
				              </div>							
							</div>
                            
                            <div class="col-md-4">
								<div class="form-group">
									<label class="control-label no-padding-right" for="Sub Projects"> Item Name <span class="error">*</span></label>
										<ui-select ng-model="$parent.itemnname" theme="selectize" title="Choose a person" >
							            <ui-select-match placeholder="Select or search">{{$select.selected.TotalProductName}}</ui-select-match>
							            <ui-select-choices  repeat="item in ItemArray | filter: $select.search">
							              <div><span ng-bind-html="item.TotalProductName | highlight: $select.search"></span></div>
							            </ui-select-choices>
							          </ui-select>
										<div class="error" data-ng-show="submitted || AddCategoryForm.itemnname.$dirty && AddCategoryForm.itemnname.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.itemnname.$error.required">Select Item is required.</small>
										</div>
								</div>
							</div>
                            <div class="form-group col-md-3">
                                <label class="control-label no-padding-right" for="reason"> From Date <span class="error">*</span></label>
                                <input type="text" class="form-control" name="fromdate"  placeholder="" id="fromdate" ng-model="fromdate" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened" ng-click="fromopen($event,'opened')"  datepicker-options="assDate" required=""> 
                                    <div class="error" data-ng-show="submitted || AddCategoryForm.fromdate.$dirty && AddCategoryForm.fromdate.$invalid">
                                        <small class="error" data-ng-show="AddCategoryForm.fromdate.$error.required">Date is required.</small>
                                    </div>
                            </div>

                            <div class="form-group col-md-3">
                                <label class="control-label no-padding-right" for="reason"> To Date <span class="error">*</span></label>
                                <input type="text" class="form-control" name="todate"  placeholder="" id="todate" ng-model="todate" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened2" ng-click="toopen($event,'opened2')"  datepicker-options="assDate1">
                                    <div class="error" data-ng-show="submitted || AddCategoryForm.todate.$dirty && AddCategoryForm.todate.$invalid">
                                        <small class="error" data-ng-show="AddCategoryForm.todate.$error.required">Date is required.</small>
                                    </div>
                            </div>
							
							<!-- <div class="col-md-4">
								<div class="form-group">
									<label class="control-label no-padding-right" for="Sub Projects"> Sizes <span class="error">*</span></label>
										<ui-select ng-model="$parent.itemsize" theme="selectize" title="Choose a person">
							            <ui-select-match placeholder="Select or search">{{$select.selected.ProductSize}} MM</ui-select-match>
							            <ui-select-choices  repeat="item in SizeArray | filter: $select.search">
							              <div><span ng-bind-html="item.ProductSize | highlight: $select.search"></span> MM</div>
							            </ui-select-choices>
							          </ui-select>
										<div class="error" data-ng-show="submitted || AddCategoryForm.itemsize.$dirty && AddCategoryForm.itemsize.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.itemsize.$error.required">Select Godown is required.</small>
										</div>
								</div>

							</div> -->
								<!-- <div class="form-group col-md-5">
									<label class="col-sm-3 control-label no-padding-right" for="reason"> To Date <span class="error">*</span></label>

									<div class="col-sm-6">
									<input type="text" class="form-control" name="todate"  placeholder="" id="todate" ng-model="todate" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened2" ng-click="toopen($event,'opened2')"  datepicker-options="assDate1">
										<div class="error" data-ng-show="submitted || AddCategoryForm.todate.$dirty && AddCategoryForm.todate.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.todate.$error.required">Date is required.</small>
										</div>
									</div>
								</div> -->
								<div class="col-md-2">
								<div class="form-group">
									<p>&nbsp;</p>
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
											<!-- <div class="col-xs-6">
											<a href="datewise-bill-report.php?FromDate={{fromdate | date}}&ToDate={{todate | date}}" target="_self" class="btn btn-success" > Download</a>
											</div> -->
										</div>
										<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf">
											<thead class="cf">
												<tr>
													<th ng-click="sort('Item')">Item Name <span class="glyphicon sort-icon" ng-show="sortKey=='Item'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
													<th ng-click="sort('Roll')">Roll No <span class="glyphicon sort-icon" ng-show="sortKey=='Roll'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
													<th ng-click="sort('Purchase')">Purchase Qty <span class="glyphicon sort-icon" ng-show="sortKey=='Purchase'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
                                                    <th ng-click="sort('Slit')">Slit Qty <span class="glyphicon sort-icon" ng-show="sortKey=='Slit'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
													<th ng-click="sort('Difference')">Difference Qty <span class="glyphicon sort-icon" ng-show="sortKey=='Difference'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
                                                    <th ng-click="sort('Weight')">Weight Left/Gain <span class="glyphicon sort-icon" ng-show="sortKey=='Weight'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
                                                    <th ng-click="sort('Remarks')">Remarks <span class="glyphicon sort-icon" ng-show="sortKey=='Remarks'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
												</tr>
											</thead>										
											<tbody>
												<tr dir-paginate="cat in pagedItems|orderBy:sortKey:reverse|filter:search|itemsPerPage:10" total-items="{{total_count}}" current-page="mypageno">
												<td data-title="Item Name">{{cat.TotalProductName}} {{cat.ProductSize}} MM</td>
												<td data-title="Roll No"><a href="" ng-click="GetRollData(cat.UniqueRollNo)">{{cat.UniqueRollNo}}</a></td>
													<td data-title="Purchase Qty">{{cat.PurchaseQty}}</td>
                                                    <td data-title="Slit Qty">{{cat.TotalSlitQty}}</td>
                                                    <td data-title="Diff Qty">
														<p class="error" ng-if="cat.PurchaseQty-cat.TotalSlitQty>0"> {{cat.PurchaseQty-cat.TotalSlitQty | number:3}} {{cat.Unit}}</p>

														<p class="text-success" ng-if="cat.PurchaseQty-cat.TotalSlitQty<0">{{cat.TotalSlitQty-cat.PurchaseQty | number:3}} {{cat.Unit}}</p>
													</td>
													<td data-title="Weight Left/Gain">
														<p class="error" ng-if="cat.PurchaseQty-cat.TotalSlitQty>0"><strong>Weight Left</strong></p>

														<p class="text-success" ng-if="cat.PurchaseQty-cat.TotalSlitQty<0"><strong>Weight Gain</p>
													</td>
													<td><p class="pre-data">{{cat.Remarks}}</p></td>
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

									<!--modal-->
									<div id="modal-form" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
										<div class="modal-dialog  modal-lg">
											<div class="modal-content">
												<div class="modal-header">
													<div class="col-xs-12">
													<div class="pull-left">
														<h4>{{TotalProductName}} {{ProductSize}} MM</h4>
														<h4>Avl Qty: {{AvlQuantity}} {{Unit}}</h4>
													</div>
													<div class="pull-right">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
													</div>
													</div>
												</div>
												<div class="modal-body">
												<div class="row">
													<!-- <div class="alert alert-danger">
													  <strong>No records found!</strong>
													</div> -->
													<ul>
													<li><span class="text-danger">{{TotalName}} {{POProductSize}} MM ( {{PurchaseRollNo}} ) </span> Purchased from the  <b> {{VendorName}}</b> vendor on <b>{{RawPODate | date: 'd MMM y'}}</b></li>
													<li>Purchase Weight/Qty is <b>{{PurchaseQty}} {{Unit}}</b></li>
											 		<li>Stock received at <b>{{GoDownName}}</b></li>
													<li ng-if="IsSlitted=='1'"><span class="text-success">{{TotalProductName}} {{ProductSize}} MM ( {{UniqueRollNo}} ) </span> has been slit from <span class="text-danger"> {{TotalName}} {{POProductSize}} MM </span> on <b>{{SlitDate | date: 'd MMM y'}} </b></li>
													<li ng-if="IsSlitted=='1'">Slit Qty is <b>{{SlitQty}} {{Unit}}</b></li>

													<!-- <li ng-if="IsSlitted>'1'"><span class="text-success">{{TotalProductName}} {{ProductSize}} MM ( {{UniqueRollNo}} ) </span> Sliited From <span class="text-danger"> {{TotalName}} {{POProductSize}} MM </span> on <b>{{SlitDate | date: 'd MMM y'}} </b></li> -->
												</ul>

													<div class="dataTables_wrapper form-inline no-footer" >

													<!-- <table id="dynamic-table" class="table table-bordered table-striped table-condensed cf"  datatable="ng" dt-options="vm.dtOptions" dt-column-defs="vm.dtColumnDefs">
														<thead class="cf">
															<tr>
																<th>Date</th>
																<th>Produced</th>
																<th>Dispatched</th>
																<th>Balance</th>
															</tr>
														</thead>										
														<tbody >
															<tr  ng-repeat="led in LedgerArr">
																<td data-title="Date">{{led.CreatedDate | date: 'd MMM y'}}</td>
																<td data-title="Produced">{{led.totalproduce || 0}}</td>
																<td data-title="Dispatched">{{led.totalinvoice || 0}}</td>
																<td data-title="Dispatched">{{led.Closing_Balance || 0}}</td>
															</tr>
														</tbody>
													</table> -->
													</div>

												</div>
												
											</div>
											</div>
										</div>
									</div> <!--modal-->
									<div id="myModal" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
						<div class="modal-dialog  modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<div class="col-xs-12">
									<div class="pull-left">
										<h4>Roll Details</h4>
									</div>
									<div class="pull-right">
										<button type="button" class="close" data-ng-click="GotoList()">&times;</button>
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