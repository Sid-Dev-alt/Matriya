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
    	<script src="angularjs/customer-script.js"></script>
	</head>

	<body class="no-skin"  data-ng-cloak data-ng-app="CategoryModule" data-ng-controller="CategoryController" ng-init="GetStates()">
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
							<li class="active">Customers</li>
						</ul>
					</div> -->
					<div class="page-content" >

					<div id="no-more-tables">
						<div class="row">
							<div class="col-xs-12 page-header">
								<div class="col-xs-8 col-sm-8 col-lg-9">
			                  	 	<div class="" >
										<h1>{{pagetitle}}</h1>
										</div>
								</div>
		                  	 	<div class="col-xs-4 col-sm-4 col-lg-3" ng-if="FormList">
				                    <a class="" href="" ng-click="GotoAdd()">
			                    <i class="pull-left add-thumbicon fa fa-plus btn-success no-hover"></i>
			                    </a>
			                  </div>
			              </div>
							
						</div>
							<div class="row" ng-show="FormList">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="space-6"></div>
								<div class="dataTables_wrapper form-inline no-footer">
									<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf" data-ng-init="GetListData()"   datatable="ng" dt-options="vm.dtOptions" dt-column-defs="vm.dtColumnDefs">
										<thead class="cf">
											<tr>
												<th>Customer Id</th>
												<!-- <th>Person Name</th> -->
												<th>Company Name</th>
												<th>Email-Id</th>
												<th>Mobile No</th>
												
												<th>Address</th>
												<th>Action</th>
											</tr>
										</thead>										
										<tbody >
											<tr ng-repeat="sup in pagedItems">
												<td data-title="Customer Id">{{sup.CustomerId}}</td>
												<!-- <td data-title="Person Name">{{sup.DisplayName}}</td> -->
												<td data-title="Company Name">{{sup.CompanyName}}</td>
												
												<td data-title="Email-Id">{{sup.EmailId}}</td>
												<td data-title="Mobile No">{{sup.Mobile}}</td>
												
												<td data-title="Address">{{sup.BillAddressLane1}} <br>{{sup.BillAddressLane2}} <br>{{sup.BillState}}</td>
												<td data-title="Action">
													<button type="button" class="btn btn-xs btn-info" data-ng-click="EditSupplier(sup.PkId,sup.CustomerId,sup.CustomerType,sup.Salutation,sup.DisplayName,sup.CompanyName,sup.EmailId,sup.Mobile,sup.WorkPhone,sup.PaymentTerms,sup.BillingName,sup.BillAddressLane1,sup.BillAddressLane2,sup.BillTown,sup.BillLandmark,sup.BillCity,sup.BillState,sup.BillDistrict,sup.BillZipcode,sup.BillPhone,sup.ShipName,sup.ShipAddressLane1,sup.ShipAddressLane2,sup.ShipTown,sup.ShipLandmark,sup.ShipCity,sup.ShipState,sup.ShipDistrict,sup.ShipZipcode,sup.ShipPhone)">Edit &nbsp;
														<i class="ace-icon fa fa-pencil bigger-120"></i>
													</button>&nbsp;
													<button class="btn btn-xs btn-danger" data-ng-click="Delete(sup.PkId)">Delete &nbsp;
														<i class="ace-icon fa fa-trash-o bigger-120"></i>
													</button>
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


					<div ng-show="FormAdd" id="no-more-tables">
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								 <form class="form-horizontal" role="form" id="AddCustomerForm" name="AddCustomerForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="AddCustomerData()">	
								 	<input type="hidden" value="{{FormPkId}}">

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
 
									<div class="form-group" style="display: none">
										<label class="col-sm-3 control-label no-padding-right" for="State">Salutation <span class="error">*</span></label>
										<div class="col-sm-6">
											<select type="text" name="salutation" class="form-control"  ng-model="salutation" required="" ng-options="saltt.SalutationName as saltt.SalutationName for saltt in SalutationArr">
												<option value="">Select </option>
												<!-- <option ng-repeat="saltt in SalutationArr" value="{{saltt.SalutationName}}">{{saltt.SalutationName}}</option> -->
											</select>	
											<div class="error" data-ng-show="submitted || AddCustomerForm.salutation.$dirty && AddCustomerForm.salutation.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.salutation.$error.required">Select salutation Required</small>
											</div>
										</div>
									</div>

									<div class="form-group" style="display: none">
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
										<label class="col-sm-3 control-label no-padding-right" for="Shop name"> Company Name <span class="error">*</span></label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="shopname" name="shopname" placeholder="" data-ng-model="shopname" data-ng-minlength="3" pattern="^[a-zA-Z- ]+" >
											<div class="error" data-ng-show="submitted || AddCustomerForm.shopname.$dirty && AddCustomerForm.shopname.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.shopname.$error.required">Company Name is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.shopname.$error.minlength">Company Name is required to be at least 3 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.shopname.$error.pattern">Company Name should alphabets ex:abcd</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Email-Id">Email-Id </label>
										<div class="col-sm-6">
											<input type="email" class="form-control" placeholder="" id="emailid" name="emailid" pattern="^[a-zA-Z0-9-\_.]+@[a-zA-Z0-9-\_.]+\.[a-zA-Z0-9.]{2,5}$"   data-ng-model="emailid">						
							                  	<div class="error" data-ng-show="submitted || AddCustomerForm.emailid.$dirty && AddCustomerForm.emailid.$invalid">
													<small class="error" data-ng-show="AddCustomerForm.emailid.$error.required">Email-Id is required.</small>
													<small class="error" data-ng-show="AddCustomerForm.emailid.$error.email && !emailExists">Invalid Email-Id.</small>
												</div>
												<small class="error" data-ng-if="EmailExists">Email-Id already exists</small>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Mobile No"> Mobile No </label>
										<div class="col-sm-6">
											<input class="form-control" type="tel" id="mobileno" name="mobileno" maxlength="10"  placeholder="" data-ng-model="mobileno" data-ng-minlength="10" data-ng-maxlength="10" pattern="[6-9]{1}[0-9]{9}" ng-change="checkMobile(FormPkId,mobileno)" number> 
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

									<!-- <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="GSTIN"> GSTIN No <span class="error">*</span></label>
										<div class="col-sm-6">
											<input class="form-control" required placeholder="" name="gstin" ng-model="gstin" maxlength="15">
											<div class="error" ng-show="submitted || AddCustomerForm.gstin.$dirty && AddCustomerForm.gstin.$invalid">
											<small class="error" ng-show="AddCustomerForm.gstin.$error.required">GSTIN No required.</small>	
											</div>
										</div>
									</div>


									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="PAN No">PAN No</label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="pan" name="pan" maxlength="10"  placeholder="ENTER CAPITAL LETTERS" data-ng-model="pan" data-ng-minlength="10" data-ng-maxlength="10"  pattern="[A-Z]{3}([CHFATBLJGP]){1}[A-Z]{1}[0-9]{4}[A-Z]{1}"/>
											<div class="error" data-ng-show="AddCustomerForm.pan.$dirty && AddCustomerForm.pan.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.pan.$error.required">PAN-No is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.pan.$error.minlength">PAN-No is required to be at least 10 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.pan.$error.maxlength">PAN-No cannot be longer than 10 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.pan.$error.pattern">PAN Number Not Matched</small>
											</div>
										</div>
									</div> -->
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Unit"> &nbsp;</label>
										<div class="col-xs-12 col-sm-5">
											<div class="control-group">
												<label class="control-label bolder blue">Billing Address</label>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Customer name"> Bill Person Name </label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="billingname" name="billingname" placeholder="" autofocus data-ng-model="billingname" data-ng-minlength="3" pattern="^[a-zA-Z- ]+">
											<div class="error" data-ng-show="submitted || AddCustomerForm.billingname.$dirty && AddCustomerForm.billingname.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.billingname.$error.required">Person Name is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.billingname.$error.minlength">Person Name is required to be at least 3 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.billingname.$error.pattern">Person Name should alphabets ex:abcd</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Mobile No">Bill Mobile No </label>
										<div class="col-sm-6">
											<input class="form-control" type="tel" id="mobileno" name="billmobileno" maxlength="10" placeholder="" data-ng-model="billmobileno" data-ng-minlength="10" data-ng-maxlength="10" pattern="[6-9]{1}[0-9]{9}" number> 
											<div class="error" data-ng-show="submitted || AddCustomerForm.billmobileno.$dirty && AddCustomerForm.billmobileno.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.billmobileno.$error.required">Mobile-No is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.billmobileno.$error.minlength">Mobile-No is required to be at least 10 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.billmobileno.$error.maxlength">Mobile-No cannot be longer than 10 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.billmobileno.$error.pattern">Mobile No must be number & start from 6-9</small>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Address">Address</label>
										<div class="col-sm-6">
											<textarea  name="address1" id="address1" class="form-control" ng-model="address1" placeholder="" ></textarea>
												<div class="error" data-ng-show="submitted || AddCustomerForm.address1.$dirty && AddCustomerForm.address1.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.address1.$error.required">Address  Required</small>
											</div>
										</div>
									</div>

									<!-- <div class="form-group">
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
										<label class="col-sm-3 control-label no-padding-right" for="City">City </label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="city" name="city"  placeholder="" data-ng-model="city" data-ng-minlength="3" pattern="^[a-zA-Z- ]+">
											<div class="error" data-ng-show="submitted || AddCustomerForm.city.$dirty && AddCustomerForm.city.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.city.$error.required">City is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.city.$error.minlength">City is required to be at least 3 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.city.$error.pattern">City should alphabets ex:XXX XXX</small>
											</div>
										</div>
									</div>									

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="State">State </label>
										<div class="col-sm-6">
											<input type="text"  name="state" class="form-control"   uib-typeahead="st as st for st in StateArray | filter:$viewValue" typeahead-editable="false" typeahead-on-select="onCSelect($item, $model, $label)" ng-model="state" >
											<div class="error" data-ng-show="submitted || AddCustomerForm.state.$dirty && AddCustomerForm.state.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.state.$error.required">Select State Required</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="District">District </label>
										<div class="col-sm-6">
											<input type="text"  name="district" class="form-control"  ng-model="district" uib-typeahead="dist as dist for dist in DistrictArray | filter:$viewValue" typeahead-editable="false">
											<div class="error" data-ng-show="submitted || AddCustomerForm.district.$dirty && AddCustomerForm.district.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.district.$error.required">District is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="PinCode">PinCode </label>
										<div class="col-sm-6">
											<input class="form-control" type="tel" id="pincode" name="pincode" maxlength="6" placeholder="" data-ng-model="pincode" data-ng-minlength="6" data-ng-maxlength="6" data-ng-pattern="/^[0-9]*$/"/ >
											<div class="error" data-ng-show="submitted || AddCustomerForm.pincode.$dirty && AddCustomerForm.pincode.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.pincode.$error.required">PinCode is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.pincode.$error.minlength">PinCode is required to be at least 6 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.pincode.$error.maxlength">PinCode cannot be longer than 6 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.pincode.$error.pattern">PinCode should be numeric ex:123456</small>
											</div>
										</div>
									</div>
 -->
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
										<label class="col-sm-3 control-label no-padding-right" for="Customer name"> Ship Person Name </label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="shippingname" name="shippingname"  placeholder="" autofocus data-ng-model="shippingname" data-ng-minlength="3" pattern="^[a-zA-Z- ]+">
											<div class="error" data-ng-show="submitted || AddCustomerForm.shippingname.$dirty && AddCustomerForm.shippingname.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.shippingname.$error.required">Person Name is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.shippingname.$error.minlength">Person Name is required to be at least 3 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.shippingname.$error.pattern">Person Name should alphabets ex:abcd</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Mobile No">Ship Mobile No </label>
										<div class="col-sm-6">
											<input class="form-control" type="tel" id="shipmobileno" name="shipmobileno" maxlength="10"  placeholder="" data-ng-model="shipmobileno" data-ng-minlength="10" data-ng-maxlength="10" pattern="[6-9]{1}[0-9]{9}" number> 
											<div class="error" data-ng-show="submitted || AddCustomerForm.shipmobileno.$dirty && AddCustomerForm.shipmobileno.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.shipmobileno.$error.required">Mobile-No is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.shipmobileno.$error.minlength">Mobile-No is required to be at least 10 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.shipmobileno.$error.maxlength">Mobile-No cannot be longer than 10 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.shipmobileno.$error.pattern">Mobile No must be number & start from 6-9</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Address">Address</label>
										<div class="col-sm-6">
											<textarea  name="shipaddress1" id="address1" class="form-control" ng-model="shipaddress1" placeholder=""  ></textarea>
												<div class="error" data-ng-show="submitted || AddCustomerForm.shipaddress1.$dirty && AddCustomerForm.shipaddress1.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.shipaddress1.$error.required">Address  Required</small>
											</div>
										</div>
									</div>

									<!-- <div class="form-group">
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
										<label class="col-sm-3 control-label no-padding-right" for="City">City </label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="shipcity" name="shipcity"  placeholder="" data-ng-model="shipcity" data-ng-minlength="3" pattern="^[a-zA-Z- ]+">
											<div class="error" data-ng-show="submitted || AddCustomerForm.shipcity.$dirty && AddCustomerForm.shipcity.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.shipcity.$error.required">City is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.shipcity.$error.minlength">City is required to be at least 3 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.shipcity.$error.pattern">City should alphabets ex:XXX XXX</small>
											</div>
										</div>
									</div>									

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="State">State </label>
										<div class="col-sm-6">
											<input type="text"  name="shipstate" class="form-control"  ng-model="shipstate"  uib-typeahead="st as st for st in StateArray | filter:$viewValue" typeahead-editable="false" typeahead-on-select="onCSelect($item, $model, $label)">
											
											<div class="error" data-ng-show="submitted || AddCustomerForm.shipstate.$dirty && AddCustomerForm.shipstate.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.shipstate.$error.required">Select State Required</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="District">District </label>
										<div class="col-sm-6">
											<input type="text"  name="shipdistrict" class="form-control"  ng-model="shipdistrict" uib-typeahead="dist as dist for dist in DistrictArray | filter:$viewValue" typeahead-editable="false" >
											<div class="error" data-ng-show="submitted || AddCustomerForm.shipdistrict.$dirty && AddCustomerForm.shipdistrict.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.shipdistrict.$error.required">District is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="PinCode">PinCode </label>
										<div class="col-sm-6">
											<input class="form-control" type="tel" id="shippincode" name="shippincode" maxlength="6" placeholder="" data-ng-model="shippincode" data-ng-minlength="6" data-ng-maxlength="6" data-ng-pattern="/^[0-9]*$/" >
											<div class="error" data-ng-show="submitted || AddCustomerForm.shippincode.$dirty && AddCustomerForm.shippincode.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.shippincode.$error.required">PinCode is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.shippincode.$error.minlength">PinCode is required to be at least 6 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.shippincode.$error.maxlength">PinCode cannot be longer than 6 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.shippincode.$error.pattern">PinCode should be numeric ex:123456</small>
											</div>
										</div>
									</div> -->

									<div class="form-group" style="display: none;">
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

									<!-- <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="PinCode">Lattitude </label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="lattitude" name="lattitude" placeholder="" data-ng-model="lattitude" >
											<div class="error" data-ng-show="submitted || AddCustomerForm.lattitude.$dirty && AddCustomerForm.lattitude.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.lattitude.$error.required">Lattitude is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="PinCode">Longitude </label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="longitude" name="longitude" placeholder="" data-ng-model="longitude" >
											<div class="error" data-ng-show="submitted || AddCustomerForm.longitude.$dirty && AddCustomerForm.longitude.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.longitude.$error.required">Longitude is required.</small>
											</div>
										</div>
									</div> -->
									<!-- <iframe width="100%"  height="300" src="https://maps.google.com/maps?q={{res}}&output=embed"></iframe>			 -->
									<!-- <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Address">Terms & Conditions</label>
										<div class="col-sm-6">
											<textarea  name="tandc" id="tandc" class="form-control" ng-model="tandc" placeholder=""></textarea>
												<div class="error" data-ng-show="submitted || AddCustomerForm.tandc.$dirty && AddCustomerForm.tandc.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.tandc.$error.required">Terms Required</small>
											</div>
										</div>
									</div>

									<h3 class="header smaller lighter blue">Bank Details</h3>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Bank Name"> Bank Name </label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="bankname" name="bankname"  placeholder=""  data-ng-model="bankname" pattern="^[a-zA-Z- ]+" data-ng-minlength="3" maxlength="30" data-ng-maxlength="30" >
											<div class="error" data-ng-show="submitted || AddCustomerForm.bankname.$dirty && AddCustomerForm.bankname.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.bankname.$error.required">Bank Name is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.bankname.$error.pattern">Bank Name should be alphabets ex:abcd</small>
												<small class="error" data-ng-show="AddCustomerForm.bankname.$error.minlength">Bank Name is required to be at least 3 characters</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Branch Name">Branch Name </label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="branchname" name="branchname"  placeholder=""  data-ng-model="branchname" pattern="^[a-zA-Z- ]+" data-ng-minlength="3" maxlength="30" data-ng-maxlength="30" >
											
											<div class="error" data-ng-show="submitted || AddCustomerForm.branchname.$dirty && AddCustomerForm.branchname.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.branchname.$error.required">Branch Name is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.branchname.$error.pattern">Branch Name should be alphabets ex:abcd</small>
												<small class="error" data-ng-show="AddCustomerForm.branchname.$error.minlength">Branch is required to be at least 3 characters</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Account Name">Account Name </label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="accountname" name="accountname"  placeholder=""  data-ng-model="accountname" pattern="^[a-zA-Z- ]+" data-ng-minlength="3" maxlength="30" data-ng-maxlength="30" >
											
											<div class="error" data-ng-show="submitted || AddCustomerForm.accountname.$dirty && AddCustomerForm.accountname.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.accountname.$error.required">Account Name is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.accountname.$error.pattern">Account Name should be alphabets ex:abcd</small>
												<small class="error" data-ng-show="AddCustomerForm.accountname.$error.minlength">Account Name is required to be at least 3 characters</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Account Type">Account Type </label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="accounttype" name="accounttype"  placeholder=""  data-ng-model="accounttype" pattern="^[a-zA-Z- ]+" data-ng-minlength="3" maxlength="30" data-ng-maxlength="30" >
											
											<div class="error" data-ng-show="submitted || AddCustomerForm.accounttype.$dirty && AddCustomerForm.accounttype.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.accounttype.$error.required">Account Type is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.accounttype.$error.pattern">Account Type should be alphabets ex:abcd</small>
												<small class="error" data-ng-show="AddCustomerForm.accounttype.$error.minlength">Account Type is required to be at least 3 characters</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Account Number">Account Number </label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="acnumber" name="acnumber"  placeholder=""  data-ng-model="acnumber"  data-ng-pattern="/^[0-9]*$/" maxlength="18" data-ng-minlength="6" data-ng-maxlength="18" >
											
											<div class="error" data-ng-show="submitted || AddCustomerForm.acnumber.$dirty && AddCustomerForm.acnumber.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.acnumber.$error.required">Account No is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.acnumber.$error.minlength">Account No is required to be at least 6 characters</small>
													<small class="error" data-ng-show="AddCustomerForm.acnumber.$error.maxlength">Account No cannot be longer than 18 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.acnumber.$error.pattern">Account No should be numeric ex:1234567890</small>
											</div>
										</div>
									</div>

									

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="IFSC Code">IFSC Code </label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="ifsc" name="ifsc"  placeholder=""  data-ng-model="ifsc" data-minlength="11" maxlength="11" >
											<div class="error" data-ng-show="submitted || AddCustomerForm.ifsc.$dirty && AddCustomerForm.ifsc.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.ifsc.$error.required">IFSC Code is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.ifsc.$error.minlength">IFSC Code is required to be at least 11 characters</small>
												
											</div>
										</div>
									</div> -->


									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="submit" >
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
					</div><!--FOrm lising-->

					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<!-- /.main-container ending in footer page -->
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