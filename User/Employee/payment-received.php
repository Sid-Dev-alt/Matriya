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
    	<script src="angularjs/payment-received-script.js"></script>
	</head>	

	<body class="no-skin" data-ng-cloak data-ng-app="CategoryModule" data-ng-controller="CategoryController" data-ng-init="GetCustomers();GetListData()">
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
							<li class="active">Receipts</li>
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
				                    <a href="" data-ng-click="GotoAdd()" ng-show="MainList">
									<div class="label label-lg label-success arrowed-in arrowed-right pull-right" >
									<b>Add New</b>
									</div></a>
			                  </div>
			              </div>							
						</div>
						<div class="row" ng-show="MainList">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="space-6"></div>
									
								<div class="dataTables_wrapper form-inline no-footer" ng-if="PaymentItems.length!='0'">
									<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf"  datatable="ng" dt-options="vm.dtOptions" dt-column-defs="vm.dtColumnDefs">
										<thead class="cf">
											<tr>
												<th class="hidden">PkId</th>
												<th>Date</th>
												<th>Customer Name</th>
												<th>Invoice Id</th>
												<th>Payment Type</th>
												<th>Open Amount</th>
												<th>Received Amount</th>
												<th>Due Amount</th>
											</tr>
										</thead>										
										<tbody >
											<tr  ng-repeat="cat in PaymentItems">
												<td data-title="PkId"  class="hidden">{{cat.PkId}}</td>
												<td data-title="Date">{{cat.PaymentDate | date: 'd MMM y'}}</td>
												<td data-title="Customer Name">{{cat.CustomerName}}
												</td>
												<td data-title="Invoice Id">{{cat.InvoiceId}}</td>
												<td data-title="Payment Type">
													{{cat.PaymentType}}
													<p ng-if="cat.PaymentType=='Other'">( {{cat.OtherWalletName}} )</p>
												</td>
												<td data-title="Open Amount">{{cat.RemainPay | number:2}}
													<p ng-if="cat.RemainPay==undefined">&nbsp;</p>
												</td>
												<td data-title="Received Amount">{{cat.ReceivedAmount | number:2}}</td>
												<td data-title="Due Amount">{{cat.DueAmount | number:2}}
													<p ng-if="cat.DueAmount==undefined">&nbsp;</p>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								</div><!-- /.row -->


								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->

							<div class="row" ng-show="FormList">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="space-6"></div>
									<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="Sub Projects"> Customer Name <span class="error">*</span></label>
										<div class="col-sm-6">
											<input type="hidden" ng-model="customerid">
											<input type="text" id="customername" placeholder="" class="form-control" name="customername" required   data-ng-model="customername"  uib-typeahead="cust as cust.DisplayName for cust in CustomerArray | filter:$viewValue" typeahead-editable="false" typeahead-on-select="onCSelect($item, $model, $label)" ng-disabled="FormPkId!=undefined" >
												
												<div class="error" data-ng-show="submitted || AddCategoryForm.customername.$dirty && AddCategoryForm.customername.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.customername.$error.required">Customer is required.</small>
												</div>
										</div>
									</div>
									<div class="clearfix"></div>

									<div class="space-6"></div>
								<div class="well well-lg" ng-if="InvData">
									<h4 class="blue">No Inoives found</h4>
									No Inoives found for this customer
								</div>

								<div class="dataTables_wrapper form-inline no-footer" ng-if="pagedItems.length!='0'">
									<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf"  datatable="ng" dt-options="vm.dtOptions">
										<thead class="cf">
											<tr>
												<!-- <th>Sl.No</th> -->
												<th>Date</th>
												<th>Invoice Id</th>
												<th>Invoice Amount</th>
												<th>Received Amount</th>
												<th>Balance Due</th>
												<th>Action</th>
											</tr>
										</thead>										
										<tbody >
											<tr  ng-repeat="cat in pagedItems">
												<!-- <td data-title="Sl.No">{{$index+1}}</td> -->
												<td data-title="Date">{{cat.InvoiceDate | date: 'd MMM y'}}</td>
												<td data-title="Invoice Id">{{cat.InvoiceId}}
												</td>
												<td data-title="Invoice Amount">{{cat.InvoiceTotal}}</td>
												<td data-title="Received Amount">{{cat.ReceivedAmount}}</td>
												
												<td data-title="Invoice Due">{{cat.PendingAmount | number:2}}</td>
												
												<td data-title="Action">
													<button type="button" class="btn btn-xs btn-info" data-ng-click="EditSupplier(cat.InvoiceId,cat.CustomerId_CustomerMaster,cat.CustomerName,cat.CCompanyName,cat.CMobile,cat.InvoiceTotal,cat.ReceivedAmount,cat.PendingAmount)">Get Payment &nbsp;
														<i class="ace-icon fa fa-pencil bigger-120"></i>
													</button>&nbsp;
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
								<form class="form-horizontal" role="form" id="AddCustomerForm" name="AddCustomerForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="AddCustomerData()">	
								 	<input type="hidden" value="{{FormPkId}}">
								 	<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Date"> Date <span class="error">*</span></label>
										<div class="col-sm-6">
									 		<input type="text" class="form-control" autofocus name="entrydate" id="entrydate" placeholder=" Enter Date" ng-model="entrydate" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened1" ng-click="singleopen($event,'opened1')"  datepicker-options="assDate1" required=""  ng-disabled="ViewRecords" > 
									 		<div class="error" data-ng-show="submitted || AddCustomerForm.entrydate.$dirty && AddCustomerForm.entrydate.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.entrydate.$error.required">Date is required.</small>
											</div>
									 	</div>
									 </div>
 									
										<div class="form-group" >
										<label class="col-sm-3 control-label no-padding-right" for="CustomerId"> Customer Name <span class="error">*</span></label>
										<div class="col-sm-6">
											<input type="hidden" name="CustomerId"  ng-model="CustomerId" class="form-control" >

											<input type="text" name="customername"  ng-model="customername" class="form-control" required=""  disabled="">
											<div class="error" data-ng-show="submitted || AddCustomerForm.customername.$dirty && AddCustomerForm.customername.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.customername.$error.required"> Customer Name is required.</small>
											</div>
										</div>
										</div>

										<div class="form-group" >
										<label class="col-sm-3 control-label no-padding-right" for="ShopName"> Company Name <span class="error">*</span></label>
										<div class="col-sm-6">
											<input type="text" name="ShopName"  ng-model="ShopName" class="form-control" disabled="">
										</div>
										</div>
										<div class="form-group" >
										<label class="col-sm-3 control-label no-padding-right" for="Mobile"> Mobile <span class="error">*</span></label>
										<div class="col-sm-6">
											<input type="text" name="Mobile"  ng-model="Mobile" class="form-control"  disabled="">
										</div>
										</div>

								  <!--start order taking form-->
								  	<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="PinCode">Invoice Id </label>
									<div class="col-sm-6">
										
										<input type="text" name="InvoiceId"  ng-model="InvoiceId" class="form-control" required=""  disabled="">
										<div class="error" data-ng-show="submitted || AddCustomerForm.InvoiceId.$dirty && AddCustomerForm.InvoiceId.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.InvoiceId.$error.required">InvoiceId is required.</small>
										</div>
									</div>
									</div>

									<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="PinCode">Total Order Amount </label>
									<div class="col-sm-6">
										<input type="text" name="BillAmount"  ng-model="BillAmount" class="form-control" disabled="" >
									</div>
									</div>

									<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="PinCode">Till received </label>
									<div class="col-sm-6">
										<input type="text" name="tillreceive"  ng-model="tillreceive" class="form-control" disabled="" >
										<div class="error" data-ng-show="submitted || AddCustomerForm.tillreceive.$dirty && AddCustomerForm.tillreceive.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.tillreceive.$error.required">Till received is required.</small>
										</div>
									</div>
									</div>

									<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="PinCode">Remain Pay </label>
									<div class="col-sm-6">
										<input type="text" name="remainpay"  ng-model="remainpay" class="form-control" disabled="" valid-number  allow-decimal="false" allow-negative="false">
										<div class="error" data-ng-show="submitted || AddCustomerForm.remainpay.$dirty && AddCustomerForm.remainpay.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.remainpay.$error.required">Till received is required.</small>
										</div>
									</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Town">Mode of Payment <span class="error">*</span></label>
										<div class="col-sm-9">
									<div class="control-group">	
									<div class="radio">
										<label for="full">
											<input name="paymentmode" type="radio" class="ace input-lg" id="full" value="Full" ng-model="paymentmode"  ng-required="!paymentmode" ng-change="ChangeMode(paymentmode)">
											<span class="lbl bigger-120"> Full Payment</span>
										</label>
									</div>
									</div>
									<div class="control-group">
										<div class="radio">
										<label for="partial">
											<input name="paymentmode" type="radio" class="ace input-lg" id="partial" value="Partial" ng-model="paymentmode"  ng-required="!paymentmode" ng-change="ChangeMode(paymentmode)">
											<span class="lbl bigger-120"> Partial Payment</span>
										</label>
										</div>
								      </div>
								      <div class="error" data-ng-show="submitted || AddCustomerForm.paymentmode.$dirty && AddCustomerForm.paymentmode.$invalid">
										<small class="error" data-ng-show="AddCustomerForm.paymentmode.$error.required">Payment Mode is  required.</small>
									</div>
									</div>
								  </div>

								  <div ng-if="paymentmode=='Partial'">
									<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="PinCode">Amount Collected </label>
									<div class="col-sm-6">
										<input type="text" name="amount"  ng-model="$parent.amount" class="form-control" required="" valid-number  allow-decimal="false" allow-negative="false" ng-change="Chkamount(remainpay,amount)">
										<div class="error" data-ng-show="submitted || AddCustomerForm.amount.$dirty && AddCustomerForm.amount.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.amount.$error.required">Amount is required.</small>
										</div>
									</div>
									</div>

									<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="PinCode">Remain Amount </label>
									<div class="col-sm-6">
										<input type="text" name="remainamt"  class="form-control" disabled="" value="{{remainpay-$parent.amount}}">
										<div class="error" data-ng-show="submitted || AddCustomerForm.remainamt.$dirty && AddCustomerForm.remainamt.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.remainamt.$error.required">Remain Amount is required.</small>
										</div>
									</div>
									</div>
								</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Town"> Payment Type <span class="error">*</span></label>
										<div class="col-sm-9">
									<div class="control-group">	
									<div class="radio">
										<label for="Cash">
											<input name="paymenttype" type="radio" class="ace input-lg" id="Cash" value="Cash" ng-model="paymenttype" ng-required="!paymenttype	">
											<span class="lbl bigger-120"> Cash</span>
										</label>
									</div>
									</div>
									<div class="control-group">
										<div class="radio">
										<label for="Cheque">
											<input name="paymenttype" type="radio" class="ace input-lg" id="Cheque" value="Cheque" ng-model="paymenttype" ng-required="!paymenttype">
											<span class="lbl bigger-120"> Cheque</span>
										</label>
										</div>
								      </div>
								      <div class="control-group">
										<div class="radio">
										<label for="Gpay">
											<input name="paymenttype" type="radio" class="ace input-lg" id="Gpay" value="GooglePay/PhonePe" ng-model="paymenttype" ng-required="!paymenttype">
											<span class="lbl bigger-120"> Google Pay / PhonePe</span>
										</label>
										</div>
								      </div>
								      <div class="control-group">
										<div class="radio">
										<label for="NEFT">
											<input name="paymenttype" type="radio" class="ace input-lg" id="NEFT" value="NEFT/RTGS" ng-model="paymenttype" ng-required="!paymenttype">
											<span class="lbl bigger-120"> RTGS / NEFT</span>
										</label>
										</div>
								      </div>
								      <div class="control-group">
										<div class="radio">
										<label for="other">
											<input name="paymenttype" type="radio" class="ace input-lg" id="other" value="Other" ng-model="paymenttype" ng-required="!paymenttype">
											<span class="lbl bigger-120"> Other Wallet</span>
										</label>
										</div>
								      </div>
								      <div class="error" data-ng-show="submitted || AddCustomerForm.paymenttype.$dirty && AddCustomerForm.paymenttype.$invalid">
										<small class="error" data-ng-show="AddCustomerForm.paymenttype.$error.required">Payment Type is  required.</small>
									</div>
									</div>
								  </div>

								  <div class="form-group" ng-if="paymenttype=='Other'">
									<label class="col-sm-3 control-label no-padding-right" for="walletname">Wallet Name <span class="error">*</span></label>
									<div class="col-sm-6">
										<input type="text" name="walletname"  ng-model="$parent.walletname" class="form-control" required >
										<div class="error" data-ng-show="submitted || AddCustomerForm.walletname.$dirty && AddCustomerForm.walletname.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.walletname.$error.required">Wallet Name is required.</small>
										</div>
									</div>
									</div>

								  	<div class="form-group" ng-if="paymenttype=='Cheque'">
										<label class="col-sm-3 control-label no-padding-right" for="Date">Cheque Date <span class="error">*</span></label>
										<div class="col-sm-6">
									 		<input type="text" class="form-control" id="chequedate" name="chequedate"  placeholder="" ng-model="$parent.chequedate" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened2" ng-click="singleopen($event,'opened2')"  datepicker-options="assDate1" required="" > 
									 		<div class="error" data-ng-show="submitted || AddCustomerForm.chequedate.$dirty && AddCustomerForm.chequedate.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.chequedate.$error.required">Date is required.</small>
											</div>
									 	</div>
									 </div>



									 <div class="form-group" ng-if="paymenttype=='Cheque'">
									<label class="col-sm-3 control-label no-padding-right" for="Cheque No">Cheque No <span class="error">*</span></label>
									<div class="col-sm-6">
										<input type="text" name="chequeno"  ng-model="$parent.chequeno" class="form-control" required >
										<div class="error" data-ng-show="submitted || AddCustomerForm.chequeno.$dirty && AddCustomerForm.chequeno.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.chequeno.$error.required">Cheque No is required.</small>
										</div>
									</div>
									</div>

									<div class="form-group" ng-if="paymenttype=='GooglePay/PhonePe' || paymenttype=='Other' || paymenttype=='NEFT/RTGS'">
									<label class="col-sm-3 control-label no-padding-right" for="Cheque No"> 
										<span  ng-if="paymenttype=='GooglePay/PhonePe' || paymenttype=='Other'">Reference Id</span>
										<span  ng-if="paymenttype=='NEFT/RTGS'">UTR Details</span>
										<span class="error">*</span></label>
									<div class="col-sm-6">
										<input type="text" name="referenceid"  ng-model="$parent.referenceid" class="form-control" required >
										<div class="error" data-ng-show="submitted || AddCustomerForm.referenceid.$dirty && AddCustomerForm.referenceid.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.referenceid.$error.required">Reference Id is required.</small>
										</div>
									</div>
									</div>

								  <div class="form-group">
								  	<label class="col-sm-3 control-label no-padding-right" for="PinCode">Upload File </label>
									<div class="col-sm-6">
									  <input  type="file" id="IPkId" file-model="docfile" accept="image/*" style="display: none;">
									   <button type="button" id="uploadButton" class="btn btn-sm btn-primary text-small" onclick="document.getElementById('IPkId').click();" >
	                                    <i class="fa fa-paperclip" aria-hidden="true"></i>&nbsp;Choose Files..
	                                  </button> &nbsp;

	                                  {{docfile.name}}
	                              	</div>

								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="PinCode">Comments </label>
									<div class="col-sm-6">
										<textarea class="form-control"  id="comments" name="comments" placeholder="" data-ng-model="comments" ></textarea>
										<div class="error" data-ng-show="submitted || AddCustomerForm.comments.$dirty && AddCustomerForm.comments.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.comments.$error.required">comments required.</small>
										</div>
									</div>
								</div>

								<div class="clearfix form-actions">
									<div class="col-md-offset-3 col-md-9">
										<button class="btn btn-success" type="submit" data-ng-disabled="FormValid">
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