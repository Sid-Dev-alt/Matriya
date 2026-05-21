<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']!="")
{
	//$OrderPkId= $_REQUEST['Id'];
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once("../title.php");?>		
		<link rel="stylesheet" href="../../assets/css/colorbox.min.css" />
    	<script src="angularjs/dchallan-script.js"></script>
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="CategoryModule" data-ng-controller="CategoryController" data-ng-init="GetListData()">
		<?php include_once("../top.php");?>

		<div class="main-container ace-save-state" id="main-container">
			
			<?php include_once("sidebar.php");?>

			<div class="main-content">
				<div class="main-content-inner">
					<div class="breadcrumbs ace-save-state" id="breadcrumbs">
						<ul class="breadcrumb">
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class=""><a href="#">Masters</a></li>
							<li class="active">Delivery Challans</li>
						</ul><!-- /.breadcrumb -->
					</div>

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
									<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf"  datatable="ng" dt-options="vm.dtOptions">
										<thead class="cf">
											<tr>
												<!-- <th>Sl.No</th> -->
												<th>Date</th>
												<th>DC Id</th>
												<th>Reference</th>
												<th>Customer Name</th>
												<th>DC Status</th>
												<th>Invoice Status</th>
												<th>Action</th>
											</tr>
										</thead>										
										<tbody >
											<tr  ng-repeat="cat in pagedItems">
												<!-- <td data-title="Sl.No">{{$index+1}}</td> -->
												<td data-title="Date">{{cat.DcDate | date: 'd MMM y'}}</td>
												<td data-title="DC Id">{{cat.DcId}}</td>
												<td data-title="Reference">{{cat.Reference}}
												</td>
												<td data-title="Customer Name">{{cat.CustomerName}}</td>
												<td data-title="DC Status">
													<span ng-if="cat.DCStatus=='1'">Draft</span>
													<span ng-if="cat.DCStatus=='2'">Convert to Open</span>
													<span ng-if="cat.DCStatus=='3'">Delivered</span>
												</td>
												<td data-title="Invoice Status">
													<span ng-if="cat.InvoiceStatus=='0'">Not Invoiced

													</span>
													<span ng-if="cat.InvoiceStatus=='1'">Invoiced</span>
												</td>
												<td data-title="Action">
												<a href="open-dc.php?Id={{cat.PkId}}"  class="btn btn-xs btn-inverse">OPEN &nbsp;
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
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								 <form class="form-horizontal" role="form" id="AddCategoryForm" name="AddCategoryForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="AddCategoryData()">
									<input type="hidden" value="{{FormPkId}}">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Product Id"> Delivery Challan Id <span class="error">*</span></label>
										
										<div class="col-sm-6">
											<input type="text" id="DcId" placeholder="" class="form-control" name="DcId" required  data-ng-model="DcId" disabled="">
												<div class="error" data-ng-show="submitted || AddCategoryForm.DcId.$dirty && AddCategoryForm.DcId.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.DcId.$error.required">Invoice Id is required.</small>
												</div>
										</div>
									</div>

									<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="Sub Projects"> Customer Name <span class="error">*</span></label>
									<div class="col-sm-6">
										<input type="hidden" ng-model="customerid">
										<input type="text" id="customername" placeholder="" class="form-control" name="customername" required   data-ng-model="customername"  uib-typeahead="cust as cust.DisplayName for cust in CustomerArray | filter:$viewValue" typeahead-editable="false" typeahead-on-select="onCSelect($item, $model, $label)" ng-disabled="FormPkId!=undefined">
											
											<div class="error" data-ng-show="submitted || AddCategoryForm.customername.$dirty && AddCategoryForm.customername.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.customername.$error.required">Customer is required.</small>
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
										<input type="text" class="form-control" name="entrydate"  placeholder="" id="entrydate" ng-model="entrydate" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened1" ng-click="singleopen($event,'opened1')"  datepicker-options="assDate1" required=""> 
											<div class="error" data-ng-show="submitted || AddCategoryForm.entrydate.$dirty && AddCategoryForm.entrydate.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.entrydate.$error.required">Date is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="reason">Challan Type <span class="error">*</span></label>

										<div class="col-sm-6">
										<input type="text" id="challantype" placeholder="" class="form-control" name="challantype" required  data-ng-model="challantype"  uib-typeahead="ctype for ctype in ChallanTypeArray | filter:$viewValue" >											
										<div class="error" data-ng-show="submitted || AddCategoryForm.challantype.$dirty && AddCategoryForm.challantype.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.challantype.$error.required">Challan Type is required.</small>
										</div>
										</div>
									</div>

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
													<input type="hidden" value="{{bat.InvPkId}}">

												
												<input type="text" id="product" placeholder="" class="form-control" name="product{{$index}}" required data-ng-model="bat.product"  uib-typeahead="cat as cat.ProductName for cat in InventoryArray | filter:$viewValue"  typeahead-editable="false" typeahead-on-select="onInvSelect($item, $model, $label, $index, quantity)">
												<div class="error" data-ng-show="submitted || AddCategoryForm.product{{$index}}.$dirty && AddCategoryForm.product{{$index}}.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.product{{$index}}.$error.required">Product Name is required.</small>
												</div>
												<p ng-if="bat.InvPkId!=undefined && bat.batchno!=undefined">Batch: {{bat.batchno}}</p>
												<p ng-if="bat.InvPkId!=undefined">SKU: {{bat.SKU}}</p>
											</div>
											</td>
											
											<td data-title="Qty" class="center">
												<div class="form-group">
												<div class="col-sm-12">
												<input type="text" id="quantity" placeholder="" class="form-control" name="quantity{{$index}}" data-ng-model="bat.quantity" maxlength="10" required="" class="form-control" number ng-change="ChkQty(bat.AvlQty,bat.quantity,$index)">
													<div class="error" data-ng-show="submitted || AddCategoryForm.quantity{{$index}}.$dirty && AddCategoryForm.quantity{{$index}}.$invalid">
														<small class="error" data-ng-show="AddCategoryForm.quantity{{$index}}.$error.required">QUANTITY is required.</small>
													</div>
												</div>
												<p ng-if="bat.InvPkId!=''">Available Quantity: {{bat.AvlQty}}</p>
												</div>
											</td>
											<td data-title="Rate">
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
											<td data-title="ACTION">
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
								<div class="form-group" ng-if="FormPkId==undefined">	
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
								<label class="col-sm-7 control-label no-padding-right" for="Choose ">Discount </label>
								<div class="col-sm-2">
									<div class="control-group">
										<div class="radio">
											<label>
												<input name="disctype" ng-model="disctype" type="radio" class="ace" ng-required="!disctype" value="Percent" ng-change="getDiscAmt(sum,disctype,discvalue)">
												<span class="lbl"> %</span>
											</label>
											<label>
												<input name="disctype" ng-model="disctype" type="radio" class="ace" ng-required="!disctype" value="Rupee" ng-change="getDiscAmt(sum,disctype,discvalue)">
												<span class="lbl"> Rs.</span>
											</label>
										</div>
									</div>
									<div class="error" data-ng-show="submitted || AddCategoryForm.disctype.$dirty && AddCategoryForm.disctype.$invalid">
										<small class="error" data-ng-show="AddCategoryForm.disctype.$error.required"> is required.</small>
									</div>
								</div>
								<div class="col-sm-1">
									<input  type="text" name="discvalue" class="form-control"  ng-model="discvalue" valid-number="" allow-decimal="false" allow-negative="false" ng-change="getDiscAmt(sum,disctype,discvalue)">	
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
									<select id="status" placeholder="" class="form-control" name="status" data-ng-model="status" required="">
										<option value="">Select</option>
										<option value="1">Draft</option>
										<option value="2">Convert to Open</option>
										<option value="3">Delivered</option>
									</select>
										<div class="error" data-ng-show="submitted || AddCategoryForm.status.$dirty && AddCategoryForm.status.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.status.$error.required">Status is required.</small>
										</div>
								</div>
							</div>



									<!-- <div class="form-group" ng-if="invtrack=='Batch'">
										<div class="col-sm-3">&nbsp;</div>
											<div class="col-sm-6">
											</a>
										</div>
									</div> -->
									<!--modal-->
								<!-- <div id="modal-form" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
									<div class="modal-dialog  modal-lg">
										<div class="modal-content">
											<div class="modal-header">
												<div class="col-xs-12">
												<div class="pull-left">
												</div>
												<div class="pull-right">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
												</div>
												</div>
											</div>

											<div class="modal-body">
											<div class="row">

											
											</div>

											<div class="clearfix form-actions">
												<div class="col-md-offset-3 col-md-9">
													<button type="button" class="btn btn-success" ng-click="AddBatch()">
														<i class="ace-icon fa fa-check bigger-110"></i>
														Add
													</button>&nbsp;&nbsp;
													<button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
												</div>
											</div>
										</div>
										</div>
									</div>
								</div> --><!--modal -->


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
												SAVE & Confirm
											</button>
											&nbsp; &nbsp;
											<button class="btn btn-inverse" type="button" data-ng-click="GotoList()">
												<i class="ace-icon fa fa-list bigger-110"></i>
												Go to List
											</button>
										</div>
									</div>
								</form>
							</div><!-- /.col -->
						</div><!-- /.row -->
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!--Add lising-->


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