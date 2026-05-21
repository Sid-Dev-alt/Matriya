<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']!="")
{
	$POrderPkId= $_REQUEST['Id'];
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once("../title.php");?>		
		<link rel="stylesheet" href="../../assets/css/colorbox.min.css" />
    	<script src="angularjs/Receive-PO-script.js"></script>
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="CategoryModule" data-ng-controller="CategoryController" >
		<?php if($POrderPkId!="")
		{
		?>
		<div data-ng-init="GetOrder(<?php echo $POrderPkId;?>)"></div>
		<?php	
		}
		else
		{
		?>
		<div data-ng-init="GetOrder()"></div>
		<?php
		}
		?>
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
							<li class="active">Invoices</li>
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
				                    <a href="new-invoice.php" ng-show="FormList">
									<div class="label label-lg label-success arrowed-in arrowed-right pull-right" >
									<b>New Invoice</b>
									</div></a>
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
												<th>Invoice Id</th>
												<th>Reference</th>
												<th>Customer Name</th>
												<th>Invoice Status</th>
												<th>Package Status</th>
												<th>Action</th>
											</tr>
										</thead>										
										<tbody >
											<tr  ng-repeat="cat in pagedItems">
												<!-- <td data-title="Sl.No">{{$index+1}}</td> -->
												<td data-title="Date">{{cat.InvoiceDate | date: 'd MMM y'}}</td>
												<td data-title="Sales Order Id">{{cat.OrderId}}</td>
												<td data-title="Invoice Id">{{cat.InvoiceId}}</td>
												<td data-title="Reference">{{cat.Reference}}
													<p ng-if="cat.Reference==undefined">&nbsp;</p>
												</td>
												<td data-title="Customer Name">{{cat.CustomerName}}</td>
												<td data-title="Invoice Status">
													<span ng-if="cat.InvoiceStatus==0">Draft</span>
													<span ng-if="cat.InvoiceStatus==1">Sent</span>
													<span ng-if="cat.InvoiceStatus==2">Invoiced</span>
												</td>
												<td data-title="Package Status">
													<span ng-if="cat.PackageStatus=='0'">Not Packed</span>
													<span ng-if="cat.PackageStatus=='1'">Partially Packed</span>
													<span ng-if="cat.PackageStatus=='2'">Packed</span>
													<a ng-if="cat.PackageStatus=='0' || cat.PackageStatus=='1'" href="invoice-package.php?Id={{cat.PkId}}"><span class="label label-info arrowed-right arrowed-in" >Go to Package</span></a>
												</td>
												<td data-title="Action">
													<button type="button" class="btn btn-xs btn-danger" data-ng-click="ViewInvoice(cat.PkId,cat.InvoiceId,cat.InvoiceDate,cat.DueDate,cat.CustomerName,cat.CEmailId,cat.CMobile,cat.SubTotal,cat.GST,cat.GSTAmount,cat.AdditionalCharges,cat.DiscountAmount,cat.InvoiceTotal,cat.data2)">View &nbsp;
														<i class="ace-icon fa fa-eye bigger-120"></i>
													</button>&nbsp;
														<a ng-if="cat.InvoiceStatus=='2'" href="print-invoice.php?Id={{cat.PkId}}" target="_blank" class="btn btn-xs btn-info" >Print &nbsp;
															<i class="ace-icon fa fa-print bigger-120"></i>
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
								 <form class="form-horizontal" role="form" id="AddCategoryForm" name="AddCategoryForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="SaveData()">
									<input type="hidden" value="{{FormPkId}}">
									<input type="hidden" value="{{Type}}">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Product Id"> Purchase Receive Id <span class="error">*</span></label>
										<div class="col-sm-6">
											<input type="text" id="ReceiveId" placeholder="" class="form-control" name="ReceiveId" required  data-ng-model="ReceiveId" disabled="">
												<div class="error" data-ng-show="submitted || AddCategoryForm.ReceiveId.$dirty && AddCategoryForm.ReceiveId.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.ReceiveId.$error.required">PO receive Id is required.</small>
												</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Product Id"> PO Number <span class="error">*</span></label>
										
										<div class="col-sm-6">
											<input type="text" id="POrderId" placeholder="" class="form-control" name="POrderId" required  data-ng-model="POrderId" ng-disabled="FormPkId!=undefined">
												<div class="error" data-ng-show="submitted || AddCategoryForm.POrderId.$dirty && AddCategoryForm.POrderId.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.POrderId.$error.required">Order Id is required.</small>
												</div>
										</div>
									</div>
									

									<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="Sub Projects"> Vendor Name <span class="error">*</span></label>
									<div class="col-sm-6">
										<input type="hidden" ng-model="vendorid">
										<input type="text" id="vendorname" placeholder="" class="form-control" name="vendorname" required   data-ng-model="vendorname"  uib-typeahead="cust as cust.DisplayName for cust in CustomerArray | filter:$viewValue" typeahead-editable="false" typeahead-on-select="onCSelect($item, $model, $label, $entrydate)" ng-disabled="FormPkId!=undefined">
											
											<div class="error" data-ng-show="submitted || AddCategoryForm.vendorname.$dirty && AddCategoryForm.vendorname.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.vendorname.$error.required">Customer is required.</small>
											</div>
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
										<label class="col-sm-3 control-label no-padding-right" for="reason">Invoice Date <span class="error">*</span></label>

										<div class="col-sm-6">
										<input type="text" class="form-control" name="entrydate"  placeholder="" id="entrydate" ng-model="entrydate" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened1" ng-click="singleopen($event,'opened1')"  datepicker-options="assDate1" required="" ng-change="GetDueDate(entrydate,paymentterms)"> 
											<div class="error" data-ng-show="submitted || AddCategoryForm.entrydate.$dirty && AddCategoryForm.entrydate.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.entrydate.$error.required">Date is required.</small>
											</div>
										</div>
									</div>

									<!--<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="State">Payment Terms </label>
										<div class="col-sm-6">
											<select  name="paymentterms" class="form-control"  ng-model="paymentterms" ng-change="GetDueDate(entrydate,paymentterms)">
												<option value="">Select State</option>
												<option ng-repeat="term in PaymentTermArr" value="{{term.TermName}}">{{term.TermName}}</option>
											</select>	
											<div class="error" data-ng-show="submitted || AddCustomerForm.paymentterms.$dirty && AddCustomerForm.paymentterms.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.paymentterms.$error.required">Select Payment terms Required</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="reason">Due Date </label>

										<div class="col-sm-6">
										<input type="text" class="form-control" name="duedate"  placeholder="" id="duedate" ng-model="duedate" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened2" ng-click="singleopen($event,'opened2')"  datepicker-options="assDate1" > 
											<div class="error" data-ng-show="submitted || AddCategoryForm.duedate.$dirty && AddCategoryForm.duedate.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.duedate.$error.required">Date is required.</small>
											</div>
										</div>
									</div>

									

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="State">Delivery Method </label>
										<div class="col-sm-6">
											<input  type="text" name="deliverymethod" class="form-control"  ng-model="deliverymethod">
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
									</div> -->

									<div class="clearfix"></div>
									 <div class="form-group">
									  	<label class="col-sm-3 control-label no-padding-right" for="PinCode">Upload File </label>

										<div class="col-sm-6">
										  <input  type="file" id="IPkId" file-model="docfile" accept="image/*" style="display: none;">
										   <button type="button" id="uploadButton" class="btn btn-sm btn-primary text-small" onclick="document.getElementById('IPkId').click();" >
		                                    <i class="fa fa-paperclip" aria-hidden="true"></i>&nbsp;Choose Files..
		                                  </button> &nbsp;

		                                  {{docfile.name}}
		                              	<small class="error"> {{FileErr}}</small>
		                              	</div>
									</div>
								

									<div id="no-more-tables">
									<table class="table table-striped table-bordered" >
									<thead>
										<tr>
											<th>Item Details</th>
											<th>Order Qty</th>
											<!--<th>Invoice Qty</th>
											<th>Quantity</th>
											<th>Rate</th>
											<th>Amount</th>
											<th ng-if="FormPkId==undefined">ACTION</th> -->
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="bat in BatchArr">
											<td data-title="Item Details" class="center">
											<div class="form-group">
												<div class="col-sm-12">
												<input type="hidden" value="{{bat.ProductId}}">
												{{bat.product}}
												<p ng-if="bat.ProductId!=undefined && bat.batchno!=undefined">Batch: {{bat.batchno}}</p>
												<p ng-if="bat.ProductId!=undefined">SKU: {{bat.SKU}}</p>
												<p ng-if="bat.ProductId!=undefined">Available Quantity: {{bat.AvlQty}}
													<input type="hidden" value="{{bat.TrackingMode}}">
																	<!-- <h4>{{bat.TrackingMode}}</h4> -->
																</p>
											</div>
											</td>
											<!-- <td data-title="Order Qty" class="center">{{bat.OrderQuantity}}</td>
											<td data-title="Invoice Qty" class="center">{{bat.InvoicedQty}}</td> -->	
											<td data-title="Qty" class="center">
												<div class="form-group">
												<div class="col-sm-12">
													{{bat.quantity}}
												<!-- <input type="text" id="quantity" placeholder="" class="form-control" name="quantity{{$index}}" data-ng-model="bat.quantity" maxlength="10" required="" class="form-control" number ng-change="ChkQty(bat.AvlQty,bat.quantity,bat.OrderQuantity,$index)">
													<div class="error" data-ng-show="submitted || AddCategoryForm.quantity{{$index}}.$dirty && AddCategoryForm.quantity{{$index}}.$invalid">
														<small class="error" data-ng-show="AddCategoryForm.quantity{{$index}}.$error.required">QUANTITY is required.</small>
													</div>
												</div>
												<div class="clearfix">&nbsp;</div>
												<div class="col-sm-12" ng-if="bat.TrackingMode!='None'">
												<a href="#modal-form{{$index}}" class="btn btn-sm btn-block btn-danger " data-toggle="modal"  >
													<span ng-if="bat.pickquantity=='0'">Select Batches</span>
													<span ng-if="bat.pickquantity!='0'">You picked {{bat.pickquantity ? bat.pickquantity : 0}}</span>
												</a> -->	

															
												<!--modal-->
												<div id="modal-form{{$index}}" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
													<div class="modal-dialog  modal-lg">
														<div class="modal-content">
															<div class="modal-header">
																<div class="col-xs-12">
																<div class="pull-left">
																	<h4>{{bat.product}}</h4>
																	<p>Selected Quantity: {{bat.quantity}}</p>
																	<p>Picked Quantity: {{bat.pickquantity || 0}}</p>
																</div>
																<div class="pull-right">
																	<button type="button" class="close" data-dismiss="modal">&times;</button>
																</div>
																</div>
															</div>
															<div class="modal-body">
															<div class="row">
																<div ng-repeat="subbat in bat.InvArr">
																<div class="form-group">
																	<div class="col-sm-6">
																		<input type="hidden" value="{{subbat.InvPkId}}">
																		<p>{{bat.TrackingMode}} No: {{subbat.batchno}}</p>
																		<p ng-if="bat.TrackingMode=='Batch'">Expiry Date: {{subbat.ExpireDate}}</p>
																		<p>Availabe Qty: {{subbat.InvQuantity}}</p>
																	</div>
																	<div class="col-sm-6">
														<input type="text" id="chooseqty" placeholder="" class="form-control" name="chooseqty" data-ng-model="subbat.chooseqty" maxlength="10" required="" class="form-control" number ng-change="ChkBatchQty($parent.$index,subbat.InvQuantity,subbat.chooseqty,$index)"	 >
																	</div>
																</div>
																<hr>
																</div>
															</div>
															

															<div class="clearfix form-actions">
																<div class="col-md-offset-3 col-md-9">
																	<button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
																</div>
															</div>
														</div>
														</div>
													</div>
												</div> <!--modal-->
												<div class="form-group">
													<div class="col-sm-6">
														<span ng-bind="getTotal(bat.TrackingMode,$index)"></span>
													</div>
												</div>

												</div>
												
												</div>
											</td>
											<!-- <td data-title="Rate">
												<div class="form-group">
													<div class="col-sm-12">
													<input type="text" id="price" placeholder="" class="form-control" name="price{{$index}}" data-ng-model="bat.price" maxlength="8" valid-number="" allow-decimal="false" allow-negative="false" required="">
														<div class="error" data-ng-show="submitted || AddCategoryForm.price{{$index}}.$dirty &amp;&amp; AddCategoryForm.price.$invalid">
															<small class="error" data-ng-show="AddCategoryForm.price{{$index}}.$error.required">Opening Stock Rate per unit is required.</small>
														</div>
													</div>
												</div>
											</td>
											<td data-title="Amount" class="center" >
												{{bat.quantity*bat.price | number: 2}}
											</td>
											<td data-title="ACTION" ng-if="FormPkId==undefined">
												<a href="" class="orange" ng-click="removeRow($index)">
													<i class="ace-icon fa fa-trash bigger-130"></i>
												</a>
											</td> -->
										</tr>
										
									</tbody>

								</table>
							</div>
								<div class="form-group" ng-if="FormPkId==undefined">	
								<div class="col-sm-12 col-md-4 col-lg-4">							
								<button type="button" class="btn btn-sm btn-block btn-info" ng-click="AddMore()">Add More</button>
									</div>
								</div>

								<!-- <div class="form-group">
									<label class="col-sm-7 control-label no-padding-right" for="Choose ">Sub Total </label>
									<div class="col-sm-3">&nbsp;</div>
									<div class="col-sm-2">
										<span id="totalSum" ng-model="sum" ng-show="span1" ng-bind="calculateSum()"></span>{{sum ? sum : 0 | number:2}}
									</div>
								</div>

								<div class="form-group">
								<label class="col-sm-7 control-label no-padding-right" for="Choose ">GST (%) </label>
								<div class="col-sm-2 hidden-xs">&nbsp;</div>
								<div class="col-sm-1">
									<select  type="text" name="gstperc" class="form-control"  ng-model="gstperc"  ng-change="getDiscAmt(sum,disctype,discvalue,additionalcharges,gstperc)">
										<option value="">Select</option>
										<option ng-repeat="gst in GSTArray" value="{{gst}}">{{gst}}</option>
									</select>
									<div class="error" data-ng-show="submitted || AddCategoryForm.gstperc.$dirty && AddCategoryForm.gstperc.$invalid">
										<small class="error" data-ng-show="AddCategoryForm.gstperc.$error.required"> is required.</small>
									</div>
								</div>
								<div class="col-sm-2">
									{{gstpercamt || 0 | number: 2}}	
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-7 control-label no-padding-right" for="Choose ">Additional Charges </label>
								<div class="col-sm-3">&nbsp;</div>
								<div class="col-sm-2">
									<input  type="text" name="additionalcharges" class="form-control"  ng-model="additionalcharges" valid-number="" allow-decimal="false" allow-negative="false" ng-change="getDiscAmt(sum,disctype,discvalue,additionalcharges,gstperc)">	
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
												<input name="disctype" ng-model="disctype" type="radio" class="ace" ng-required="!disctype" value="Percent" ng-change="getDiscAmt(sum,disctype,discvalue,additionalcharges,gstperc)">
												<span class="lbl"> %</span>
											</label>
											<label>
												<input name="disctype" ng-model="disctype" type="radio" class="ace" ng-required="!disctype" value="Rupee" ng-change="getDiscAmt(sum,disctype,discvalue,additionalcharges,gstperc)">
												<span class="lbl"> Rs.</span>
											</label>
										</div>
									</div>
									<div class="error" data-ng-show="submitted || AddCategoryForm.disctype.$dirty && AddCategoryForm.disctype.$invalid">
										<small class="error" data-ng-show="AddCategoryForm.disctype.$error.required"> is required.</small>
									</div>
								</div>
								<div class="col-sm-1">
									<input  type="text" name="discvalue" class="form-control"  ng-model="discvalue" valid-number="" allow-decimal="false" allow-negative="false" ng-change="getDiscAmt(sum,disctype,discvalue,additionalcharges,gstperc)">	
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

									</div>
								</div> -->

							<div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="">Internal Notes </label>
								
								<div class="col-sm-6">
									<textarea type="text" id="internalnotes" placeholder="" class="form-control" name="internalnotes" data-ng-model="internalnotes" ></textarea>
										<div class="error" data-ng-show="submitted || AddCategoryForm.internalnotes.$dirty && AddCategoryForm.internalnotes.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.internalnotes.$error.required">Internal Notes is required.</small>
										</div>
								</div>
							</div>

							<!-- <div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="">Status <span class="error">*</span></label>
								
								<div class="col-sm-6">
									<select id="status" placeholder="" class="form-control" name="status" data-ng-model="status" required="">
										<option value="">Select</option>
										<option value="1">Draft</option>
										<option value="2">Save & Confirm</option>
									</select>
										<div class="error" data-ng-show="submitted || AddCategoryForm.status.$dirty && AddCategoryForm.status.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.status.$error.required">Status is required.</small>
										</div>
								</div>
							</div> -->



									<!-- <div class="form-group" ng-if="invtrack=='Batch'">
										<div class="col-sm-3">&nbsp;</div>
											<div class="col-sm-6">
											</a>
										</div>
									</div> -->
									


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
												Cancel
											</button>
										</div>
									</div>
								</form>
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!--Add lising-->

					<div class="row" ng-show="FormView">
						<div class="col-sm-10 col-sm-offset-1">
							<div class="widget-box transparent">
								<div class="widget-header widget-header-large">
									<h3 class="widget-title grey lighter">
										<i class="ace-icon fa fa-leaf green"></i>
										Invoice
									</h3>

									<div class="widget-toolbar no-border invoice-info">
										<span class="invoice-info-label">Invoice Id:</span>
										<span class="red">#{{InvoiceId}}</span>

										<br>
										<span class="invoice-info-label">Date:</span>
										<span class="blue">{{InvoiceDate | date:'d MMM y'}}</span>
										<br>
										<span class="invoice-info-label">Due Date:</span>
										<span class="blue">{{DueDate | date:'d MMM y'}}</span>
									</div>

									<div class="widget-toolbar hidden-480">
										<a href="#">
											<i class="ace-icon fa fa-print"></i>
										</a>
									</div>
								</div>

								<div class="widget-body">
									<div class="widget-main padding-24">
										<div class="row">
											<div class="col-sm-6">
												<div class="row">
													<div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
														<b>Customer Info</b>
													</div>
												</div>

												<div>
													<ul class="list-unstyled  spaced">
														<li>
															<i class="ace-icon fa fa-user green"></i> {{CustomerName}}
														</li>

														<li>
															<i class="ace-icon fa fa-envelope green"></i> {{CEmailId}}
														</li>

														<li>
															<i class="ace-icon fa fa-phone green"></i> {{CMobile}}
														</li>
													</ul>
												</div>
											</div><!-- /.col -->
										</div><!-- /.row -->

										<div class="space"></div>

										<div>
											<table class="table table-striped table-bordered">
												<thead>
													<tr>
														<th>Product</th>
														<th>Invoice Quantity</th>
														<th>Status</th>
														<th>Rate</th>
														<th>Total</th>
													</tr>
												</thead>

												<tbody>
													<tr ng-repeat="cap in data2">
														<td>{{cap.ProductName}}</td>
														<td>{{cap.Quantity}}</td>
														<td>
														<p>{{cap.PackedQty}} Packed</p>
														<p ng-if="cap.IsShipped=='1'">0 Shipped</p>
														<p ng-if="cap.IsShipped=='3' || cap.IsShipped=='2'">
															{{cap.PackedQty}} Shipped </p>
														<p ng-if="cap.IsShipped=='3'">{{cap.PackedQty}} Delivered</p>
														</td>
														<td>{{cap.Price}}</td>
														<td>{{cap.Amount}}</td>
													</tr>
												</tbody>
											</table>
										</div>

										<div class="hr hr8 hr-double hr-dotted"></div>

										<div class="row">
											<div class="col-sm-5 pull-right">
												<h5 class="pull-right">
													Sub amount :
													<span class="red">{{SubTotal}}</span>
												</h5>
												<div class="clearfix">&nbsp;</div>
												<h5 class="pull-right">
													GST Charges ({{GST || 0}} %):
													<span class="red">{{GSTAmount}}</span>
												</h5>
												<div class="clearfix">&nbsp;</div>
												<h5 class="pull-right">
													Additional Charges :
													<span class="red">{{AdditionalCharges}}</span>
												</h5>
												<div class="clearfix">&nbsp;</div>
												<h5 class="pull-right">
													Discount amount :
													<span class="red">{{DiscountAmount}}</span>
												</h5>
												<div class="clearfix">&nbsp;</div>
												<h4 class="pull-right">
													Total amount :
													<span class="red"> <i class="ace-icon fa fa-inr red"></i> {{InvoiceTotal}}</span>
												</h4>
											</div>
											<div class="col-sm-7 pull-left"> Extra Information </div>
										</div>

										<div class="space-6"></div>
										<div class="well">
											Thank you for choosing our Company products.
						We believe you will be satisfied by our services. <button ng-click="GotoList()" class="btn btn-sm btn-danger">Go Back</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>


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