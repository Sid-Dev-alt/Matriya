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
    	<script src="angularjs/order-script.js"></script>
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
		                  	 	<div class="col-xs-12 col-sm-12 col-lg-6">
				                    <a href="" data-ng-click="GotoAdd()" ng-show="FormList">
									<div class="label label-lg label-success arrowed-in arrowed-right pull-right" >
									<b>Add New</b>
									</div>
									</a>
			                  </div>
			              </div>							
						</div>

							<div class="row" ng-show="FormList">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="space-6"></div>

								<div class="dataTables_wrapper form-inline no-footer" ng-if="pagedItems.length!='0'">
									<!-- <div class="row">
										<div class="col-xs-6">
											<div id="dynamic-table_filter" class="dataTables_filter pull-left"><label>Search:
												<input type="search" class="form-control input-lg" placeholder="" aria-controls="dynamic-table"  data-ng-model="query[queryBy]"></label>
											</div>
										</div>
									</div> -->
									<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf"  datatable="ng" dt-options="vm.dtOptions" dt-column-defs="vm.dtColumnDefs">
										<thead class="cf">
											<tr>
												<!-- <th>Sl.No</th> -->
												<th>Date</th>
												<th>Sales Order</th>
												<th>Reference</th>
												<th>Customer Name</th>
												<th>Order Status</th>
												<th>Invoiced</th>
												<th>Action</th>
											</tr>
										</thead>										
										<tbody >
											<tr  ng-repeat="cat in pagedItems">
												<!-- <td data-title="Sl.No">{{$index+1}}</td> -->
												<td data-title="Date">{{cat.OrderDate | date: 'd MMM y'}}</td>
												<td data-title="Sales Order">{{cat.OrderId}}</td>
												<td data-title="Reference">
													{{cat.Reference}}
													<p ng-if="cat.Reference==undefined">&nbsp;</p>
												</td>
												<td data-title="Customer Name">{{cat.CustomerName}}</td>
												<td data-title="Order Status">
													<span class="label label-info arrowed" ng-if="cat.OrderStatus=='1'">Draft</span>
													<span class="label label-info arrowed" ng-if="cat.OrderStatus=='2'">Sent</span>
													<span class="label label-info arrowed" ng-if="cat.OrderStatus=='3'">Confirmed</span>
												</td>
												<td data-title="Invoiced">
													<span class="label label-grey arrowed" ng-if="cat.InvoiceStatus=='0'">Not Invoiced</span>

													<span class="label label-grey arrowed" ng-if="cat.InvoiceStatus=='1'">Invoiced</span>
													<span class="label label-grey arrowed" ng-if="cat.InvoiceStatus=='2'">Partially Invoiced</span>
												</td>
												<td data-title="Action">
													<a href="open-order.php?Id={{cat.PkId}}"  class="btn btn-xs btn-inverse">OPEN &nbsp;
														<i class="ace-icon fa fa-eye bigger-120"></i>
													</a>&nbsp;

													<a ng-if="cat.InvoiceStatus!='1'" href="" ng-click="EditOrder(cat.PkId,cat.CustomerId_CustomerMaster,cat.CustomerName,cat.OrderId,cat.OrderDate,cat.Reference,cat.ShipmentDate,cat.PaymentTerms,cat.DeliveryMethod,cat.Salesperson,cat.CustomerNotes,cat.TermsCondition,cat.SubTotal,cat.DiscType,cat.DiscountVal,cat.DiscountAmount,cat.AdditionalCharges,cat.OrderTotal,cat.OrderStatus,cat.FileName,cat.data2)"  class="btn btn-xs btn-info">EDIT &nbsp;
														<i class="ace-icon fa fa-pencil bigger-120"></i>
													</a>&nbsp;
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
										<label class="col-sm-3 control-label no-padding-right" for="Product Id"> SO Id <span class="error">*</span></label>
										
										<div class="col-sm-6">
											<input type="text" id="salesorder" placeholder="" class="form-control" name="OrderId" required  data-ng-model="OrderId" disabled="">
												<div class="error" data-ng-show="submitted || AddCategoryForm.OrderId.$dirty && AddCategoryForm.OrderId.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.OrderId.$error.required">SO Id is required.</small>
												</div>
										</div>
									</div>

									<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="Sub Projects"> Customer Name <span class="error">*</span></label>
									<div class="col-sm-6">
										<input type="hidden" ng-model="customerid">
										<input type="text" id="customername" placeholder="" class="form-control" name="customername" required   data-ng-model="customername"  uib-typeahead="cust as cust.DisplayName for cust in CustomerArray | filter:$viewValue" typeahead-editable="false" typeahead-on-select="onCustomerSelect($item, $model, $label)">
											
											<div class="error" data-ng-show="submitted || AddCategoryForm.customername.$dirty && AddCategoryForm.customername.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.customername.$error.required">Customer is required.</small>
											</div>
											<a href="#modal-form" data-toggle="modal"><span class="label label-info arrowed-right arrowed-in" ng-click="AddCustomer()">New Customer ?</span></a>
									</div>

									</div>


									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="reason"> Reference# </label>
										
										<div class="col-sm-6">
										<input type="text" id="referencenum" placeholder="" class="form-control"  name="referencenum"  data-ng-model="referencenum">
											<div class="error" data-ng-show="submitted || AddCategoryForm.referencenum.$dirty && AddCategoryForm.referencenum.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.referencenum.$error.required">Reference Number is required.</small>
											</div>
										</div>
									</div>

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
											<div class="error" data-ng-show="submitted || AddCategoryForm.paymentterms.$dirty && AddCategoryForm.paymentterms.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.paymentterms.$error.required">Select Payment terms Required</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="State">Delivery Method </label>
										<div class="col-sm-6">
											<input  type="text" name="deliverymethod" class="form-control"  ng-model="deliverymethod" uib-typeahead="dlvr as dlvr for dlvr in DeliveryArray | filter:$viewValue">
											<div class="error" data-ng-show="submitted || AddCategoryForm.deliverymethod.$dirty && AddCategoryForm.deliverymethod.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.deliverymethod.$error.required">Delivery Method Required</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="State">Sales Person </label>
										<div class="col-sm-6">
											<input  type="text" name="salesperson" class="form-control"  ng-model="salesperson">
											<div class="error" data-ng-show="submitted || AddCategoryForm.salesperson.$dirty && AddCategoryForm.salesperson.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.salesperson.$error.required">Sales Person Required</small>
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
									</div>
								

									<div id="no-more-tables">
									<table class="table table-striped table-bordered" >
									<thead>
										<tr>
											<th>Item Details</th>
											<th>Qty</th>
											<th>Rate</th>
											<th>Amount</th>
											<th>ACTION</th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="bat in BatchArr">
											<td data-title="Item Details" class="center">
											<div class="form-group">
												<div class="col-sm-12">
													<input type="hidden" value="{{bat.EntryPkId}}">
													<input type="hidden" value="{{bat.ProductId}}">
												<input type="text" id="product" placeholder="" class="form-control" name="product{{$index}}" required data-ng-model="bat.product"  uib-typeahead="cat as cat.ProductName for cat in InventoryArray | filter:$viewValue"  typeahead-editable="false" typeahead-on-select="onInvSelect($item, $model, $label, $index)">
												<div class="error" data-ng-show="submitted || AddCategoryForm.product{{$index}}.$dirty && AddCategoryForm.product{{$index}}.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.product{{$index}}.$error.required">Product Name is required.</small>
												</div>
												<p ng-if="bat.ProductId!=undefined">Available Quantity: {{bat.AvlQty | number:2}}</p>
												<p ng-if="bat.ProductId!=undefined">SKU: {{bat.SKU}}</p>
											</div>
											</td>
											
											<td data-title="Qty" class="center">
												<div class="form-group">
												<div class="col-sm-12">
												<input type="text" id="quantity" placeholder="" class="form-control" name="quantity{{$index}}" data-ng-model="bat.quantity" maxlength="10" required="" class="form-control" number ng-change="ChkQty(bat.AvlQty,bat.quantity,$index)">
													<div class="error" data-ng-show="submitted || AddCategoryForm.quantity{{$index}}.$dirty && AddCategoryForm.quantity{{$index}}.$invalid">
														<small class="error" data-ng-show="AddCategoryForm.quantity{{$index}}.$error.required">QUANTITY is required.</small>
													</div>
														<small class="error">{{bat.AvlQtyErr}}</small>
														
												</div>
												</div>
											</td>
											<td data-title="Rate">
												<div class="form-group">
													<div class="col-sm-12">
														<input type="text" id="price" placeholder="" class="form-control" name="price{{$index}}" data-ng-model="bat.price" maxlength="8" valid-number="" allow-decimal="false" allow-negative="false" required="" readonly="">
															<div class="error" data-ng-show="submitted || AddCategoryForm.price{{$index}}.$dirty &amp;&amp; AddCategoryForm.price.$invalid">
																<small class="error" data-ng-show="AddCategoryForm.price{{$index}}.$error.required">Rate is required.</small>
															</div>
													</div>
												</div>
											</td>
											<td data-title="Amount" class="center" >
												{{bat.quantity*bat.price | number: 2}}
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

								<div class="form-group">
									<label class="col-sm-7 control-label no-padding-right" for="Choose ">Sub Total </label>
									<div class="col-sm-3">&nbsp;</div>
									<div class="col-sm-2">
										<span id="totalSum" ng-model="sum" ng-show="span1" ng-bind="calculateSum()"></span>{{sum ? sum : 0 | number:2}}
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-7 control-label no-padding-right" for="Choose ">Additional Charges </label>
								<div class="col-sm-3">&nbsp;</div>
								<div class="col-sm-2">
									<input  type="text" id="ads" name="additionalcharges" class="form-control decimaltemp"  ng-model="additionalcharges" valid-number="" allow-decimal="false" allow-negative="false" ng-change="getDiscAmt(sum,disctype,discvalue,additionalcharges)">	
									<div class="error" data-ng-show="submitted || AddCategoryForm.additionalcharges.$dirty && AddCategoryForm.additionalcharges.$invalid">
										<small class="error" data-ng-show="AddCategoryForm.additionalcharges.$error.required"> is required.</small>
									</div>

								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-7 control-label no-padding-right" for="Choose ">Discount </label>
								<div class="col-sm-2">
									<div class="control-group">
										<div class="radio">
											<label>
												<input name="disctype" ng-model="disctype" type="radio" class="ace" ng-required="!disctype" value="Percent" ng-change="getDiscAmt(sum,disctype,discvalue,additionalcharges)">
												<span class="lbl"> %</span>
											</label>
											<label>
												<input name="disctype" ng-model="disctype" type="radio" class="ace" ng-required="!disctype" value="Rupee" ng-change="getDiscAmt(sum,disctype,discvalue,additionalcharges)">
												<span class="lbl"> Rs.</span>
											</label>
										</div>
									</div>
									<div class="error" data-ng-show="submitted || AddCategoryForm.disctype.$dirty && AddCategoryForm.disctype.$invalid">
										<small class="error" data-ng-show="AddCategoryForm.disctype.$error.required"> is required.</small>
									</div>
								</div>
								<div class="col-sm-1">
									<input  type="text" name="discvalue" class="form-control decimaltemp"  ng-model="discvalue" valid-number="" allow-decimal="false" allow-negative="false" ng-change="getDiscAmt(sum,disctype,discvalue,additionalcharges)">	
									<div class="error" data-ng-show="submitted || AddCategoryForm.discvalue.$dirty && AddCategoryForm.discvalue.$invalid">
										<small class="error" data-ng-show="AddCategoryForm.discvalue.$error.required"> is required.</small>
									</div>
								</div>
								<div class="col-sm-2">
									{{disctotal || 0 | number: 2}}	
								</div>
							</div>

							

							<div class="form-group">
									<label class="col-sm-7 control-label no-padding-right" for="Choose ">Total </label>
									<div class="col-sm-3">&nbsp;</div>
									<div class="col-sm-2">
										{{totalamount || 0 | number:2}}
										<!-- <span id="totalSum" ng-model="totalamt" ng-show="span1" ng-bind="GetSum()"></span>{{totalamt ? totalamt : 0 | totalamt:2}} -->

									</div>
								</div>

							<div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="">Customer Notes </label>
								
								<div class="col-sm-6">
									<textarea type="text" id="cnotes" placeholder="" class="form-control" name="cnotes" data-ng-model="cnotes" ></textarea>
										<div class="error" data-ng-show="submitted || AddCategoryForm.cnotes.$dirty && AddCategoryForm.cnotes.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.cnotes.$error.required">Customer Notes is required.</small>
										</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="">Terms & Conditions </label>
								
								<div class="col-sm-6">
									<textarea type="text" id="terms" placeholder="" class="form-control" name="terms" data-ng-model="terms" ></textarea>
										<div class="error" data-ng-show="submitted || AddCategoryForm.terms.$dirty && AddCategoryForm.terms.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.terms.$error.required">Terms & Conditions is required.</small>
										</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="">Status <span class="error">*</span></label>
								
								<div class="col-sm-6">
									<select id="status" placeholder="" class="form-control" name="status" data-ng-model="status" required="" convert-to-number>
										<option value="">Select</option>
										<option value="1">Draft</option>
										<option value="2">Save & Send</option>
										<option value="3">Save & Confirm</option>
									</select>
										<div class="error" data-ng-show="submitted || AddCategoryForm.status.$dirty && AddCategoryForm.status.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.status.$error.required">Status is required.</small>
										</div>
								</div>
							</div>



									


									<!-- <div class="form-group" ng-if="pagetitle=='Edit Product'">
										<label class="col-sm-3 control-label no-padding-right" for="Status"> Status <span class="error">*</span></label>
										<div class="col-sm-9">
											<select  id="status" placeholder="" class="form-control" name="status"  data-ng-model="$parent.status" required="" convert-to-number>
												<option value="">Select</option>
												<option value="1">Active</option>
												<option value="0">Inactive</option>
											</select>
											<div class="error" data-ng-show="submitted || AddCategoryForm.status.$dirty && AddCategoryForm.status.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.status.$error.required">Status  is required.</small>
											</div>
										</div>
									</div> -->


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