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
		<link rel="stylesheet" href="../../assets/css/colorbox.min.css" />
    	<script src="angularjs/product-history-script.js"></script>
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="CategoryModule" data-ng-controller="CategoryController" data-ng-init="GetListData()">
		<?php include_once("../top.php");?>

		<div class="main-container ace-save-state" id="main-container">
			
			<?php include_once("sidebar.php");?>

			<div class="main-content">
				<div class="main-content-inner">
					<!-- <div class="breadcrumbs ace-save-state" id="breadcrumbs">
						<ul class="breadcrumb">
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class=""><a href="#">Masters</a></li>
							<li class="active">Sales Orders</li>
						</ul>
					</div> -->

					<div class="page-content" >
					<div  id="no-more-tables">
						<div class="row">
							<div class="col-xs-12 page-header">
		                  	 <div class="col-xs-12 col-sm-12 col-lg-6">
		                  	 	<div class="" >
									<h1>{{pagetitle}}</h1>
									</div>
		<!-- /.page-header --></div>
		                  	 	<!-- <div class="col-xs-12 col-sm-12 col-lg-6">
				                    <a href="" data-ng-click="GotoAdd()" ng-show="FormList">
									<div class="label label-lg label-success arrowed-in arrowed-right pull-right" >
									<b>Add New</b>
									</div>
									</a>
			                  </div> -->
			              </div>							
						</div>

							<div class="row" ng-show="FormList">
							<div class="col-xs-12">
								<form class="form-horizontal" role="form" id="AddCategoryForm" name="AddCategoryForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="GetData()">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Choose ">Inventory Type <span class="error">*</span></label>
										<div class="col-sm-6">
											<div class="control-group">
												<div class="radio">
													<label>
														<input name="invtype" ng-model="invtype" type="radio" class="ace" ng-required="!invtype" value="Raw" ng-change="GetInventory(invtype)">
														<span class="lbl"> Raw Material </span>
													</label>
												</div>
												<div class="radio">
													<label>
														<input name="invtype" ng-model="invtype" type="radio" class="ace" ng-required="!invtype" value="Finished" ng-change="GetInventory(invtype)" ng-init="GetInventory(invtype)">
														<span class="lbl"> Finished Goods</span>
													</label>
												</div>
											</div>
											<div class="error" data-ng-show="submitted || AddCategoryForm.invtype.$dirty && AddCategoryForm.invtype.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.invtype.$error.required"> is required.</small>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="reason"> Date <span class="error">*</span></label>

										<div class="col-sm-6">
										<input type="text" class="form-control" name="entrydate"  placeholder="" id="entrydate" ng-model="entrydate" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened1" ng-click="singleopen($event,'opened1')"  datepicker-options="assDate1" required="" tabindex="0"> 
											<div class="error" data-ng-show="submitted || AddCategoryForm.entrydate.$dirty && AddCategoryForm.entrydate.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.entrydate.$error.required">Date is required.</small>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Sub Projects"> Product Name <span class="error">*</span></label>
										<div class="col-sm-6">
											<input type="hidden" ng-model="productid">
											<input type="text"  id="productname" placeholder="" class="form-control" name="productname" required   data-ng-model="productname"  uib-typeahead="pro as pro.ProductName for pro in ProductArray | filter:$viewValue" typeahead-editable="false" typeahead-on-select="onProductSelect($item, $model, $label)" tabindex="1" autofocus>
												<div class="error" data-ng-show="submitted || AddCategoryForm.productname.$dirty && AddCategoryForm.productname.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.productname.$error.required">Product is required.</small>
												</div>
										</div>
									</div>
									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="submit" data-ng-disabled="FormValid">
												<i class="ace-icon fa fa-check bigger-110"></i>
												SAVE
											</button>
										</div>
									</div>
									</form>
								<div class="row" ng-if="invtype=='Finished'">
									<!-- <a class="btn btn-xs btn-danger pull-right" href="dw-account-raw-info.php"><i class="ace-icon fa fa-download bigger-120"></i> Download Excel</a> -->
									<div class="space-6"></div>
									<div class="clearfix"></div>
									
								
								<div class="alert alert-danger"  ng-if="ProducedArr.length==0">
								  <strong>No Production records found!</strong>
								</div>

								<div class="clearfix"></div>
								<div class="dataTables_wrapper form-inline no-footer" ng-if="ProducedArr.length!=undefined"  ng-hide="ProducedArr.length==0">
									<h4>Production</h4>
									<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf" >
										<thead class="cf">
											<tr>
												<th>Product</th>
												<!-- <th>Opening Qty</th> -->
												<th>Produced Qty</th>
												<th>Date & Time</th>
											</tr>
										</thead>										
										<tbody >
											<tr  ng-repeat="cat in ProducedArr">
												<td data-title="Product Name">
													{{cat.productname}}
												</td>
												<!-- <td data-title="Opening Qty">{{cat.OpeningQty}}</td> -->
												<td data-title="Produced Name">
													{{cat.Quantity}}
												</td>
												<td data-title="Date & Time">
													{{cat.CreatedTime}}
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="clearfix"></div>
								
								<div class="alert alert-danger"  ng-if="DispatchArr.length==0">
								  <strong>No dispatch records found!</strong>
								</div>
								<div class="dataTables_wrapper form-inline no-footer" ng-if="DispatchArr.length!=undefined" ng-hide="DispatchArr.length==0">
									<h4>Dispatch</h4>
									<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf"  datatable="ng" dt-options="vm.dtOptions" dt-column-defs="vm.dtColumnDefs" >
										<thead class="cf">
											<tr>
												<th>Invoice Id</th>
												<!-- <th>Opening Qty</th> -->
												<th>Customer Name</th>
												<th>Quantity</th>
											</tr>
										</thead>										
										<tbody >
											<tr  ng-repeat="cat in DispatchArr">
												<td data-title="Invoice Id">
													{{cat.InvoiceId_Invoices}}
												</td>
												<!-- <td data-title="Opening Qty">{{cat.OpeningQty}}</td> -->
												<td data-title="Customer Name">
													{{cat.DisplayName}}
												</td>
												<td data-title="Quantity">
													{{cat.Quantity}}
												</td>
											</tr>
										</tbody>
									</table>
								</div>

								</div><!-- /.row -->


								<div class="row" ng-if="invtype=='Raw'">
									<!-- <a class="btn btn-xs btn-danger pull-right" href="dw-account-raw-info.php"><i class="ace-icon fa fa-download bigger-120"></i> Download Excel</a> -->
									<div class="space-6"></div>
									<div class="clearfix"></div>
								
								<div class="alert alert-danger"  ng-if="RawDataArr.length==0">
								  <strong>No records found!</strong>
								</div>
								<div class="dataTables_wrapper form-inline no-footer" ng-if="RawDataArr.length!=undefined" ng-hide="RawDataArr.length==0">
									<h4>Raw Material Used</h4>
									<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf"  datatable="ng" dt-options="vm.dtOptions" dt-column-defs="vm.dtColumnDefs" >
										<thead class="cf">
											<tr>
												<th>Item Produced</th>
												<th>Raw Material Used</th>
												<th>Opening Quantity</th>
												<th>Quantity Used</th>
											</tr>
										</thead>										
										<tbody >
											<tr  ng-repeat="cat in RawDataArr">
												<td data-title="Item Produced">
													{{cat.FinishedProductName}}
												</td>
												<!-- <td data-title="Opening Qty">{{cat.OpeningQty}}</td> -->
												<td data-title="Raw Material Used">
													{{cat.rawproductname}}
												</td>
												<td data-title="Opening Quantity">
													{{cat.OpeningQuantity}}
												</td>
												<td data-title="Quantity Used">
													{{cat.DeductQuantity}}
												</td>
											</tr>
										</tbody>
									</table>
								</div>

								</div><!-- /.row -->


								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!--FOrm lising-->


					<div ng-show="FormAdd" >
						<div class="row">
							<div class="col-xs-12">
								 <form class="form-horizontal" role="form" id="AddCategoryForm" name="AddCategoryForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="AddCategoryData()">
									<input type="hidden" value="{{FormPkId}}">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Product Id"> Production Id <span class="error">*</span></label>
										
										<div class="col-sm-6">
											<input type="text" id="salesorder" placeholder="" class="form-control" name="productionid" required  data-ng-model="productionid" disabled="">
												<div class="error" data-ng-show="submitted || AddCategoryForm.productionid.$dirty && AddCategoryForm.productionid.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.productionid.$error.required">Production Id is required.</small>
												</div>
										</div>
									</div>

									<!-- <div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="Sub Projects"> Customer Name <span class="error">*</span></label>
									<div class="col-sm-6">
										<input type="hidden" ng-model="customerid">
										<input type="text" id="customername" placeholder="" class="form-control" name="customername" required   data-ng-model="customername"  uib-typeahead="cust as cust.DisplayName for cust in CustomerArray | filter:$viewValue" typeahead-editable="false" typeahead-on-select="onCustomerSelect($item, $model, $label)">
											
											<div class="error" data-ng-show="submitted || AddCategoryForm.customername.$dirty && AddCategoryForm.customername.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.customername.$error.required">Customer is required.</small>
											</div>
											<a href="#modal-form" data-toggle="modal"><span class="label label-info arrowed-right arrowed-in" ng-click="AddCustomer()">New Customer ?</span></a>
									</div>

									</div> -->


									<!-- <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="reason"> Reference# </label>
										
										<div class="col-sm-6">
										<input type="text" id="referencenum" placeholder="" class="form-control"  name="referencenum"  data-ng-model="referencenum">
											<div class="error" data-ng-show="submitted || AddCategoryForm.referencenum.$dirty && AddCategoryForm.referencenum.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.referencenum.$error.required">Reference Number is required.</small>
											</div>
										</div>
									</div> -->

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="reason"> Date <span class="error">*</span></label>

										<div class="col-sm-6">
										<input type="text" class="form-control" name="entrydate"  placeholder="" id="entrydate" ng-model="entrydate" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened1" ng-click="singleopen($event,'opened1')"  datepicker-options="assDate1" required=""> 
											<div class="error" data-ng-show="submitted || AddCategoryForm.entrydate.$dirty && AddCategoryForm.entrydate.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.entrydate.$error.required">Date is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Sub Projects"> Product Name <span class="error">*</span></label>
										<div class="col-sm-6">
											<input type="hidden" ng-model="productid">
											<input type="text" id="productname" placeholder="" class="form-control" name="productname" required   data-ng-model="productname"  uib-typeahead="pro as pro.ProductName for pro in ProductArray | filter:$viewValue" typeahead-editable="false" typeahead-on-select="onProductSelect($item, $model, $label)">
												<div class="error" data-ng-show="submitted || AddCategoryForm.productname.$dirty && AddCategoryForm.productname.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.productname.$error.required">Product is required.</small>
												</div>
										</div>
									</div>
									<!-- <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="reason">Expected Shipment Date </label>

										<div class="col-sm-6">
										<input type="text" class="form-control" name="shipdate"  placeholder="" id="shipdate" ng-model="shipdate" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened2" ng-click="singleopen($event,'opened2')"  datepicker-options="assDate1" > 
											<div class="error" data-ng-show="submitted || AddCategoryForm.shipdate.$dirty && AddCategoryForm.shipdate.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.shipdate.$error.required">Date is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="State">Payment Terms </label>
										<div class="col-sm-6">
											<select  name="paymentterms" class="form-control"  ng-model="paymentterms" >
												<option value="">Select State</option>
												<option ng-repeat="term in PaymentTermArr" value="{{term.TermName}}">{{term.TermName}}</option>
											</select>	
											<div class="error" data-ng-show="submitted || AddCustomerForm.paymentterms.$dirty && AddCustomerForm.paymentterms.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.paymentterms.$error.required">Select Payment terms Required</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="State">Delivery Method </label>
										<div class="col-sm-6">
											<input  type="text" name="deliverymethod" class="form-control"  ng-model="deliverymethod" uib-typeahead="dlvr as dlvr for dlvr in DeliveryArray | filter:$viewValue">
											<div class="error" data-ng-show="submitted || AddCustomerForm.deliverymethod.$dirty && AddCustomerForm.deliverymethod.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.deliverymethod.$error.required">Delivery Method Required</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="State">Sales Person </label>
										<div class="col-sm-6">
											<input  type="text" name="salesperson" class="form-control"  ng-model="salesperson">
											<div class="error" data-ng-show="submitted || AddCustomerForm.salesperson.$dirty && AddCustomerForm.salesperson.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.salesperson.$error.required">Sales Person Required</small>
											</div>
										</div>
									</div>

									<div class="clearfix"></div>
									 <div class="form-group">
									  	<label class="col-sm-3 control-label no-padding-right" for="PinCode">
									  		<span ng-if="pagetitle=='Add New Order'">Upload </span>
									  		<span ng-if="pagetitle=='Edit Order'">Replace</span>
									  	File </label>

										<div class="col-sm-6">
										  <input  type="file" id="IPkId" file-model="docfile" accept="image/*" style="display: none;">
										   <button type="button" id="uploadButton" class="btn btn-sm btn-primary text-small" onclick="document.getElementById('IPkId').click();" >
		                                    <i class="fa fa-paperclip" aria-hidden="true"></i>&nbsp;Choose Files..
		                                  </button> &nbsp;

		                                  {{docfile.name}}
		                              	<small class="error"> {{FileErr}}</small>
		                              	<div class="clearfix">&nbsp;</div>
		                              	<span ng-if="FileName!=undefined"><a href="../OrderImages/{{FileName}}" target="_blank">{{FileName}}</a></span>
		                              	</div>
									</div> -->
								
									<div class="form-group">
									<div class="col-sm-3">&nbsp;</div>
									<div class="col-sm-6">
									<div id="no-more-tables">
									<table class="table table-striped table-bordered" >
									<thead>
										<tr>
											<th>Raw Materials</th>
											<th>ACTION</th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="bat in BatchArr">
											<td data-title="Item Details" class="center">
											<div class="form-group">
												<div class="col-sm-12">
													<input type="hidden" value="{{bat.EntryPkId}}">
													<input type="hidden" value="{{bat.RawProductId}}">
												<input type="text" id="rawproduct" placeholder="" class="form-control" name="rawproduct{{$index}}" required data-ng-model="bat.rawproduct"  uib-typeahead="cat as cat.ProductName for cat in RawArray | filter:$viewValue"  typeahead-editable="false" typeahead-on-select="onRawSelect($item, $model, $label, $index)">
												<div class="error" data-ng-show="submitted || AddCategoryForm.rawproduct{{$index}}.$dirty && AddCategoryForm.rawproduct{{$index}}.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.rawproduct{{$index}}.$error.required">Product Name is required.</small>
												</div>
												</div>
											</div>
											</td>
											
											
											<td data-title="ACTION" >
												<a href="" class="orange" ng-click="removeRow($index)">
													<i class="ace-icon fa fa-trash bigger-130"></i>
												</a>
												<!-- <a class="orange" href="#modal-form" data-toggle="modal" ng-click="EditArr(bat.BatchNo,bat.ManfcBatch,bat.Mfgdate,bat.Expdate,bat.Quantity,$index)">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a> -->
											</td>
										</tr>
										
									</tbody>

								</table>
								<div class="form-group">	
								<div class="col-sm-12 col-md-4 col-lg-4">							
								<button type="button" class="btn btn-sm btn-block btn-info" ng-click="AddMore()">Add More</button>
									</div>
								</div>
								</div>
							</div>
							</div>

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="reason"> Quantity Produced <span class="error">*</span></label>
									<div class="col-sm-6">
									<input type="text" id="quantity" placeholder="" class="form-control" name="quantity" data-ng-model="quantity" maxlength="10" required="" class="form-control" number ng-change="Settrack()">
										<div class="error" data-ng-show="submitted || AddCategoryForm.quantity.$dirty && AddCategoryForm.quantity.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.quantity.$error.required">QUANTITY is required.</small>
										</div>
									</div>
								</div>

								<div class="form-group">
									<div class="col-sm-3">&nbsp;</div>
								<label class="col-sm-6">
									<input type="checkbox" class="ace" name="track" ng-model="track" >
									<span class="lbl"> Do you want track weight of each quantity</span>
								</label>
								</div>

								<div class="form-group" ng-if="track">
									<div class="col-sm-3">&nbsp;</div>
									<div class="col-sm-6">
									<table class="table table-striped table-bordered" >
										<thead>
											<tr>
												<th>S.No</th>
												<th>WEIGHT</th>
												<th>ACTION</th>
											</tr>
										</thead>
										<tbody>
											<tr ng-repeat="qty in QuantityArr"  >
												<td data-title="Item Details" class="center">{{$index+1}}</td>
												<td data-title="Item Details" class="center">
												<div class="form-group">
													<div class="col-sm-12">
														<input type="hidden" id="productweight" ng-model="qty.EntryWeightPkId">
													<input type="text" id="productweight" placeholder="" class="form-control" name="productweight{{$index}}" data-ng-model="qty.productweight" valid-number allow-decimal="true" allow-negative="false">
													<div class="error" data-ng-show="submitted || AddCategoryForm.productweight{{$index}}.$dirty && AddCategoryForm.productweight{{$index}}.$invalid">
														<small class="error" data-ng-show="AddCategoryForm.productweight{{$index}}.$error.required">Product Name is required.</small>
													</div>
													</div>
												</div>
												</td>
												
												
												<td data-title="ACTION" >
													<a href="" class="orange" ng-click="removeRow($index)">
														<i class="ace-icon fa fa-trash bigger-130"></i>
													</a>
												</td>
											</tr>
										</tbody>
									</table>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="reason"> Weight of Raw Material <span class="error">*</span></label>
									<div class="col-sm-6">
									<input type="text" id="weight" placeholder="" class="form-control" name="weight" data-ng-model="weight" maxlength="10" required="" class="form-control"  valid-number allow-decimal="true" allow-negative="false"  >
										<div class="error" data-ng-show="submitted || AddCategoryForm.weight.$dirty && AddCategoryForm.weight.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.weight.$error.required">Weight is required.</small>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="reason"> SCRAP (+) <span class="error">*</span></label>
									<div class="col-sm-6">
									<input type="text" id="scrap" placeholder="" class="form-control" name="scrap" data-ng-model="scrap" maxlength="10" required="" class="form-control"  valid-number allow-decimal="false" allow-negative="false">
										<div class="error" data-ng-show="submitted || AddCategoryForm.scrap.$dirty && AddCategoryForm.scrap.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.scrap.$error.required">SCRAP is required.</small>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="reason"> Dripper ( - )<span class="error">*</span></label>
									<div class="col-sm-6">
									<input type="text" id="dripper" placeholder="" class="form-control" name="dripper" data-ng-model="dripper" maxlength="10" required="" class="form-control"  valid-number allow-decimal="false" allow-negative="false">
										<div class="error" data-ng-show="submitted || AddCategoryForm.dripper.$dirty && AddCategoryForm.dripper.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.dripper.$error.required">Dripper is required.</small>
										</div>
									</div>
								</div>
							
							<span id="totalSum" ng-model="sum" ng-show="span1" ng-bind="calculateSum()"></span>
<div ng-if="track">{{GetTotal()}}</div>
							<div ng-if="!track">{{GetFalseTotal()}}</div>
							<div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="Choose ">Total </label>
								<div class="col-sm-6">
									<input type="text" class="form-control" name="total" ng-model="total" readonly=""  > 
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for=""> Notes </label>
								<div class="col-sm-6">
									<textarea type="text" id="cnotes" placeholder="" class="form-control" name="cnotes" data-ng-model="cnotes" ></textarea>
										<div class="error" data-ng-show="submitted || AddCategoryForm.cnotes.$dirty && AddCategoryForm.cnotes.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.cnotes.$error.required">Customer Notes is required.</small>
										</div>
								</div>
							</div>
							

									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="submit" data-ng-disabled="FormValid">
												<i class="ace-icon fa fa-check bigger-110"></i>
												SAVE
											</button>
											&nbsp; &nbsp;
											<button class="btn btn-inverse" type="button" data-ng-click="GotoList()">
												<i class="ace-icon fa fa-close bigger-110"></i>
												CANCEL
											</button>
										</div>
									</div>
								</form>
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!--Add lising-->

					<!--modal-->
					<div id="modal-form" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
						<div class="modal-dialog  modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<div class="col-xs-12">
									<div class="pull-left">
										<h4>Add Customer</h4>
									</div>
									<div class="pull-right">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
									</div>
									</div>
								</div>
								<div class="modal-body">
								<div class="row">
									<form class="form-horizontal" role="form" id="AddCustomerForm" name="AddCustomerForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="AddCustomerData()">	

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="CustomerId"> CustomerId <span class="error">*</span></label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="CustomerId" name="CustomerId" required placeholder="" data-ng-model="CustomerId" disabled="">
											<div class="error" data-ng-show="submitted || AddCustomerForm.CustomerId.$dirty && AddCustomerForm.CustomerId.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.CustomerId.$error.required">Customer Id is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Choose ">Customer Type <span class="error">*</span></label>
										<div class="col-sm-6">
											<div class="control-group">
												<div class="radio">
													<label>
														<input name="ctype" ng-model="ctype" type="radio" class="ace" ng-required="!ctype" value="Business">
														<span class="lbl"> Business</span>
													</label>
												</div>
												<div class="radio">
													<label>
														<input name="ctype" ng-model="ctype" type="radio" class="ace" ng-required="!ctype" value="Individual">
														<span class="lbl"> Individual</span>
													</label>
												</div>
											</div>
											<div class="error" data-ng-show="submitted || AddCategoryForm.ctype.$dirty && AddCategoryForm.ctype.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.ctype.$error.required"> is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="State">Salutation <span class="error">*</span></label>
										<div class="col-sm-6">
											<select type="text" name="salutation" class="form-control"  ng-model="salutation" required="" ng-options="saltt.SalutationName as saltt.SalutationName for saltt in SalutationArr">
												<option value="">Select</option>
												<!-- <option ng-repeat="saltt in SalutationArr" value="{{saltt.SalutationName}}">{{saltt.SalutationName}}</option> -->
											</select>	
											<div class="error" data-ng-show="submitted || AddCustomerForm.salutation.$dirty && AddCustomerForm.salutation.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.salutation.$error.required">Select salutation Required</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Customer name"> Customer Name <span class="error">*</span></label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="customername" name="customername" required placeholder="" autofocus data-ng-model="customername" data-ng-minlength="3" pattern="^[a-zA-Z- ]+">
											<div class="error" data-ng-show="submitted || AddCustomerForm.customername.$dirty && AddCustomerForm.customername.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.customername.$error.required">Customer Name is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.customername.$error.minlength">Customer Name is required to be at least 3 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.customername.$error.pattern">Customer Name should alphabets ex:abcd</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Shop name"> Company Name </label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="shopname" name="shopname" placeholder="" data-ng-model="shopname" data-ng-minlength="3" pattern="^[a-zA-Z- ]+">
											<div class="error" data-ng-show="submitted || AddCustomerForm.shopname.$dirty && AddCustomerForm.shopname.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.shopname.$error.required">Shop Name is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.shopname.$error.minlength">Shop Name is required to be at least 3 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.shopname.$error.pattern">Shop Name should alphabets ex:abcd</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Email-Id">Email-Id <span class="error">*</span></label>
										<div class="col-sm-6">
											<input type="email" class="form-control" placeholder="" id="emailid" name="emailid" pattern="^[a-zA-Z0-9-\_.]+@[a-zA-Z0-9-\_.]+\.[a-zA-Z0-9.]{2,5}$" required  data-ng-change="checkEmail(FormPkId,emailid)" data-ng-model="emailid">						
							                  	<div class="error" data-ng-show="submitted || AddCustomerForm.emailid.$dirty && AddCustomerForm.emailid.$invalid">
													<small class="error" data-ng-show="AddCustomerForm.emailid.$error.required">Email-Id is required.</small>
													<small class="error" data-ng-show="AddCustomerForm.emailid.$error.email && !emailExists">Invalid Email-Id.</small>
												</div>
												<small class="error" data-ng-if="EmailExists">Email-Id already exists</small>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Mobile No"> Mobile No <span class="error">*</span></label>
										<div class="col-sm-6">
											<input class="form-control" type="tel" id="mobileno" name="mobileno" maxlength="10" required placeholder="" data-ng-model="mobileno" data-ng-minlength="10" data-ng-maxlength="10" pattern="[6-9]{1}[0-9]{9}" ng-change="checkMobile(FormPkId,mobileno)"> 
											<div class="error" data-ng-show="submitted || AddCustomerForm.mobileno.$dirty && AddCustomerForm.mobileno.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.mobileno.$error.required">Mobile-No is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.mobileno.$error.minlength">Mobile-No is required to be at least 10 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.mobileno.$error.maxlength">Mobile-No cannot be longer than 10 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.mobileno.$error.pattern">Mobile No must be number & start from 6-9</small>
											</div>
											<small class="error" data-ng-if="MobileExists">Mobile No already exists</small>
										</div>
									</div>


									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="shop No"> Office / Shop Mobile No</label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="ofcmobileno" name="ofcmobileno"  placeholder="" data-ng-model="landline" maxlength="14" data-ng-minlength="14" data-ng-maxlength="14" data-ng-pattern="/^[0-9]*$/">
											<div class="error" data-ng-show="AddCustomerForm.ofcmobileno.$dirty && AddCustomerForm.ofcmobileno.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.ofcmobileno.$error.required">Office No. is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.ofcmobileno.$error.minlength">Office No. is required to be at least 14 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.ofcmobileno.$error.maxlength">Office No. is cannot be longer than 14 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.ofcmobileno.$error.pattern">Office should be numeric ex:1234567890</small>
											</div>
										</div>
									</div>

									
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Unit"> &nbsp;</label>
										<div class="col-xs-12 col-sm-5">
											<div class="control-group">
												<label class="control-label bolder blue">Billing Address</label>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Customer name"> Bill Person Name <span class="error">*</span></label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="billingname" name="billingname" required placeholder="" autofocus data-ng-model="billingname" data-ng-minlength="3" pattern="^[a-zA-Z- ]+">
											<div class="error" data-ng-show="submitted || AddCustomerForm.billingname.$dirty && AddCustomerForm.billingname.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.billingname.$error.required">Person Name is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.billingname.$error.minlength">Person Name is required to be at least 3 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.billingname.$error.pattern">Person Name should alphabets ex:abcd</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Mobile No">Bill Mobile No <span class="error">*</span></label>
										<div class="col-sm-6">
											<input class="form-control" type="tel" id="mobileno" name="billmobileno" maxlength="10" required placeholder="" data-ng-model="billmobileno" data-ng-minlength="10" data-ng-maxlength="10" pattern="[6-9]{1}[0-9]{9}"> 
											<div class="error" data-ng-show="submitted || AddCustomerForm.billmobileno.$dirty && AddCustomerForm.billmobileno.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.billmobileno.$error.required">Mobile-No is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.billmobileno.$error.minlength">Mobile-No is required to be at least 10 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.billmobileno.$error.maxlength">Mobile-No cannot be longer than 10 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.billmobileno.$error.pattern">Mobile No must be number & start from 6-9</small>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Address">Address Lane1<span class="error">*</span></label>
										<div class="col-sm-6">
											<textarea  name="address1" id="address1" class="form-control" ng-model="address1" placeholder="" required="" ></textarea>
												<div class="error" data-ng-show="submitted || AddCustomerForm.address1.$dirty && AddCustomerForm.address1.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.address1.$error.required">Address Lane1 Required</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Address">Address Lane2</label>
										<div class="col-sm-6">
											<textarea  name="address2" id="address2" class="form-control" ng-model="address2" placeholder=""></textarea>
												<div class="error" data-ng-show="submitted || AddCustomerForm.address2.$dirty && AddCustomerForm.address2.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.address2.$error.required">Address Lane2 Required</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Town">Town</label>
										<div class="col-sm-6">
											<input type="text" name="town" id="town" class="form-control" ng-model="town" placeholder="" >
												<div class="error" data-ng-show="submitted || AddCustomerForm.town.$dirty && AddCustomerForm.town.$invalid">
													<small class="error" data-ng-show="AddCustomerForm.town.$error.required">Town Required</small>
												</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Town">Landmark</label>
										<div class="col-sm-6">
											<textarea  name="landmark" id="landmark" class="form-control" ng-model="landmark" placeholder=""></textarea>
												<div class="error" data-ng-show="submitted || AddCustomerForm.landmark.$dirty && AddCustomerForm.landmark.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.landmark.$error.required">Landmark Required</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="City">City <span class="error">*</span></label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="city" name="city" required placeholder="" data-ng-model="city" data-ng-minlength="3" pattern="^[a-zA-Z- ]+">
											<div class="error" data-ng-show="submitted || AddCustomerForm.city.$dirty && AddCustomerForm.city.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.city.$error.required">City is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.city.$error.minlength">City is required to be at least 3 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.city.$error.pattern">City should alphabets ex:XXX XXX</small>
											</div>
										</div>
									</div>									

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="State">State <span class="error">*</span></label>
										<div class="col-sm-6">
											<input type="text"  name="state" class="form-control"  ng-model="state" required="" uib-typeahead="st as st for st in StateArray | filter:$viewValue" typeahead-editable="false" typeahead-on-select="onCSelect($item, $model, $label)">
											<!-- <select  name="state" class="form-control"  ng-model="state" required=""  ng-change="GetDist(state)" convert-to-number>
												<option value="">Select State</option>
												<option ng-repeat="state in StateArray" value="{{state.PkId}}">{{state.StateName}}</option>
											</select>	 -->
											<div class="error" data-ng-show="submitted || AddCustomerForm.state.$dirty && AddCustomerForm.state.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.state.$error.required">Select State Required</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="District">District <span class="error">*</span></label>
										<div class="col-sm-6">
											<input type="text"  name="district" class="form-control"  ng-model="district" required="" uib-typeahead="dist as dist for dist in DistrictArray | filter:$viewValue" typeahead-editable="false">
											<!-- <select  name="district" class="form-control"  ng-model="district" required="" convert-to-number>
												 <option value="">Select District</option>
												<option ng-repeat="dist in DistrictArray" value="{{dist.PkId}}">{{dist.DistName}}</option>
											</select>	 -->
											<div class="error" data-ng-show="submitted || AddCustomerForm.district.$dirty && AddCustomerForm.district.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.district.$error.required">District is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="PinCode">PinCode <span class="error">*</span></label>
										<div class="col-sm-6">
											<input class="form-control" type="tel" id="pincode" name="pincode" maxlength="6" placeholder="" data-ng-model="pincode" data-ng-minlength="6" data-ng-maxlength="6" data-ng-pattern="/^[0-9]*$/"/ required="">
											<div class="error" data-ng-show="submitted || AddCustomerForm.pincode.$dirty && AddCustomerForm.pincode.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.pincode.$error.required">PinCode is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.pincode.$error.minlength">PinCode is required to be at least 6 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.pincode.$error.maxlength">PinCode cannot be longer than 6 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.pincode.$error.pattern">PinCode should be numeric ex:123456</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Unit"> &nbsp;</label>
										<div class="col-xs-12 col-sm-5">
											<div class="control-group">
												<label class="control-label bolder blue">Shipping Address</label>
												<div class="checkbox">
													<label>
														
														<input name="form-field-checkbox" type="checkbox" name="sameasbill" ng-model="sameasbill" class="ace" ng-change="ChkdBIll(sameasbill)">&nbsp;
														<span class="lbl"> Same as billing address</span>
													</label>
												</div>

											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Customer name"> Bill Person Name <span class="error">*</span></label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="shippingname" name="shippingname" required placeholder="" autofocus data-ng-model="shippingname" data-ng-minlength="3" pattern="^[a-zA-Z- ]+">
											<div class="error" data-ng-show="submitted || AddCustomerForm.shippingname.$dirty && AddCustomerForm.shippingname.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.shippingname.$error.required">Person Name is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.shippingname.$error.minlength">Person Name is required to be at least 3 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.shippingname.$error.pattern">Person Name should alphabets ex:abcd</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Mobile No">Ship Mobile No <span class="error">*</span></label>
										<div class="col-sm-6">
											<input class="form-control" type="tel" id="shipmobileno" name="shipmobileno" maxlength="10" required placeholder="" data-ng-model="shipmobileno" data-ng-minlength="10" data-ng-maxlength="10" pattern="[6-9]{1}[0-9]{9}"> 
											<div class="error" data-ng-show="submitted || AddCustomerForm.shipmobileno.$dirty && AddCustomerForm.shipmobileno.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.shipmobileno.$error.required">Mobile-No is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.shipmobileno.$error.minlength">Mobile-No is required to be at least 10 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.shipmobileno.$error.maxlength">Mobile-No cannot be longer than 10 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.shipmobileno.$error.pattern">Mobile No must be number & start from 6-9</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Address">Address Lane1<span class="error">*</span></label>
										<div class="col-sm-6">
											<textarea  name="shipaddress1" id="address1" class="form-control" ng-model="shipaddress1" placeholder="" required="" ></textarea>
												<div class="error" data-ng-show="submitted || AddCustomerForm.shipaddress1.$dirty && AddCustomerForm.shipaddress1.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.shipaddress1.$error.required">Address Lane1 Required</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Address">Address Lane2</label>
										<div class="col-sm-6">
											<textarea  name="shipaddress2" id="shipaddress2" class="form-control" ng-model="shipaddress2" placeholder=""></textarea>
												<div class="error" data-ng-show="submitted || AddCustomerForm.shipaddress2.$dirty && AddCustomerForm.shipaddress2.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.shipaddress2.$error.required">Address Lane2 Required</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Town">Town</label>
										<div class="col-sm-6">
											<input type="text" name="shiptown" id="shiptown" class="form-control" ng-model="shiptown" placeholder="" >
												<div class="error" data-ng-show="submitted || AddCustomerForm.shiptown.$dirty && AddCustomerForm.shiptown.$invalid">
													<small class="error" data-ng-show="AddCustomerForm.shiptown.$error.required">Town Required</small>
												</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Town">Landmark</label>
										<div class="col-sm-6">
											<textarea  name="shiplandmark" id="shiplandmark" class="form-control" ng-model="shiplandmark" placeholder=""></textarea>
												<div class="error" data-ng-show="submitted || AddCustomerForm.shiplandmark.$dirty && AddCustomerForm.shiplandmark.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.shiplandmark.$error.required">Landmark Required</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="City">City <span class="error">*</span></label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="shipcity" name="shipcity" required placeholder="" data-ng-model="shipcity" data-ng-minlength="3" pattern="^[a-zA-Z- ]+">
											<div class="error" data-ng-show="submitted || AddCustomerForm.shipcity.$dirty && AddCustomerForm.shipcity.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.shipcity.$error.required">City is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.shipcity.$error.minlength">City is required to be at least 3 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.shipcity.$error.pattern">City should alphabets ex:XXX XXX</small>
											</div>
										</div>
									</div>									

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="State">State <span class="error">*</span></label>
										<div class="col-sm-6">
											<input type="text"  name="shipstate" class="form-control"  ng-model="shipstate" required="" uib-typeahead="st as st for st in StateArray | filter:$viewValue" typeahead-editable="false" typeahead-on-select="onCSelect($item, $model, $label)">
											<!-- <select  name="state" class="form-control"  ng-model="state" required=""  ng-change="GetDist(state)" convert-to-number>
												<option value="">Select State</option>
												<option ng-repeat="state in StateArray" value="{{state.PkId}}">{{state.StateName}}</option>
											</select>	 -->
											<div class="error" data-ng-show="submitted || AddCustomerForm.shipstate.$dirty && AddCustomerForm.shipstate.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.shipstate.$error.required">Select State Required</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="District">District <span class="error">*</span></label>
										<div class="col-sm-6">
											<input type="text"  name="shipdistrict" class="form-control"  ng-model="shipdistrict" required="" uib-typeahead="dist as dist for dist in DistrictArray | filter:$viewValue" typeahead-editable="false">
											<!-- <select  name="district" class="form-control"  ng-model="district" required="" convert-to-number>
												 <option value="">Select District</option>
												<option ng-repeat="dist in DistrictArray" value="{{dist.PkId}}">{{dist.DistName}}</option>
											</select>	 -->
											<div class="error" data-ng-show="submitted || AddCustomerForm.shipdistrict.$dirty && AddCustomerForm.shipdistrict.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.shipdistrict.$error.required">District is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="PinCode">PinCode <span class="error">*</span></label>
										<div class="col-sm-6">
											<input class="form-control" type="tel" id="shippincode" name="shippincode" maxlength="6" placeholder="" data-ng-model="shippincode" data-ng-minlength="6" data-ng-maxlength="6" data-ng-pattern="/^[0-9]*$/"/ required="">
											<div class="error" data-ng-show="submitted || AddCustomerForm.shippincode.$dirty && AddCustomerForm.shippincode.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.shippincode.$error.required">PinCode is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.shippincode.$error.minlength">PinCode is required to be at least 6 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.shippincode.$error.maxlength">PinCode cannot be longer than 6 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.shippincode.$error.pattern">PinCode should be numeric ex:123456</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="State">Payment Terms <span class="error">*</span></label>
										<div class="col-sm-6">
											<select  name="paymentterms" class="form-control"  ng-model="paymentterms" required="">
												<option value="">Select State</option>
												<option ng-repeat="term in PaymentTermArr" value="{{term.TermName}}">{{term.TermName}}</option>
											</select>	
											<div class="error" data-ng-show="submitted || AddCustomerForm.paymentterms.$dirty && AddCustomerForm.paymentterms.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.paymentterms.$error.required">Select Payment terms Required</small>
											</div>
										</div>
									</div>

									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="submit" data-ng-disabled="FormValid">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>
											&nbsp; &nbsp;
											<button class="btn btn-inverse" type="button" data-ng-click="GotoList()">
												<i class="ace-icon fa fa-close bigger-110"></i>
												Cancel
											</button>
										</div>
									</div>
									
								</form>
								</div>
								
							</div>
							</div>
						</div>
					</div> <!--modal-->

					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<!-- /.main-container ending in footer page -->
		<?php include_once("../footer.php");?>
<script type="text/javascript">
			jQuery(function($) {
		$('[data-rel=popover]').popover({container:'body'});

		if(!ace.vars['touch']) {
					$('.chosen-select').chosen({allow_single_deselect:true}); 
					//resize the chosen on window resize
			
					// $(window)
					// .off('resize.chosen')
					// .on('resize.chosen', function() {
					// 	$('.chosen-select').each(function() {
					// 		 var $this = $(this);
					// 		 $this.next().css({'width': $this.parent().width()});
					// 	})
					// }).trigger('resize.chosen');
					// //resize chosen on sidebar collapse/expand
					// $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
					// 	if(event_name != 'sidebar_collapsed') return;
					// 	$('.chosen-select').each(function() {
					// 		 var $this = $(this);
					// 		 $this.next().css({'width': $this.parent().width()});
					// 	})
					// });
			
				}

	});
</script>
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