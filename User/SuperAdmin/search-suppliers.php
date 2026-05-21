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
    	<script src="angularjs/search-supplier-script.js"></script>
	</head>

	<body class="no-skin"  data-ng-cloak data-ng-app="CategoryModule" data-ng-controller="CategoryController" ng-init="GetStates();GetVendors()">
		<?php include_once("../top.php");?>
		<div class="main-container ace-save-state" id="main-container">
			<?php include_once("sidebar.php");?>

			<div class="main-content">
				<div class="main-content-inner">

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
								<div class="col-md-12">
									<form class="form-horizontal" role="form" id="AddCategoryForm" name="AddCategoryForm" autocomplete="off" enctype="multipart/form-data" novalidate="">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="col-sm-4 control-label no-padding-right" for="PO Id"> Select Search Type </label>
													<div class="col-md-8">
														<select class="form-control" name="searchtype" ng-model="searchtype">
															<option value="">Select</option>
															<option value="Party Id">Party Id</option>
															<option value="Party Name">Party Name</option>
														</select>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6" ng-show="searchtype=='Party Id'">
												<div class="form-group">
													<label class="col-sm-4 control-label no-padding-right" for="Party Id"> Party Id </label>
													
													<div class="col-sm-8">
														<input type="text" id="salesorder" placeholder="" class="form-control" name="searchpartyid" required  data-ng-model="searchpartyid">
															<div class="error" data-ng-show="submitted || AddCategoryForm.searchpartyid.$dirty && AddCategoryForm.searchpartyid.$invalid">
																<small class="error" data-ng-show="AddCategoryForm.searchpartyid.$error.required">Party Id is required.</small>
															</div>
													</div>
												</div>
											</div>
											
											
											<div class="col-sm-6" ng-show="searchtype=='Party Name'">
												<label class="col-sm-4 control-label no-padding-right" for="Party Name"> Party Name </label>
												<div class="col-md-8">
													<ui-select ng-model="$parent.partyname" theme="selectize" title="Choose a person" required>
												    		<ui-select-match placeholder="Select or search">{{$select.selected.DisplayName}}</ui-select-match>
													    	<ui-select-choices  repeat="item in VendorArray | filter: $select.search">
													      		<div><span ng-bind-html="item.DisplayName | highlight: $select.search"></span></div>
													    	</ui-select-choices>
												  	</ui-select>
											  		<span ng-bind="Disablebtn()"></span>
											  	</div>
												<div class="error" data-ng-show="submitted || AddCategoryForm.partyname.$dirty && AddCategoryForm.partyname.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.partyname.$error.required">Party Name is required.</small>
												</div>
											</div>
											
											<div class="col-md-1" ng-show="searchtype!=''">
												<button type="button" ng-click="getData(pageno)" class="btn btn-sm btn-info" ng-disabled="disabledbtn">Search</button>
											</div>
											<div class="col-md-1" ng-show="searchtype!=''">
												<button type="button" ng-click="Clear()" class="btn btn-sm btn-danger">Clr filter</button>
											</div>
										</div>
									</form>
								</div>
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
									</div>
									<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf">
										<thead class="cf">
											<tr>
												<th ng-click="sort('Id')">Party Id <span class="glyphicon sort-icon" ng-show="sortKey=='Roll'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
												<th ng-click="sort('Name')">Party Name <span class="glyphicon sort-icon" ng-show="sortKey=='Name'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
												<th ng-click="sort('Address')">Address <span class="glyphicon sort-icon" ng-show="sortKey=='Address'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
												<th ng-click="sort('GST')">GST NO <span class="glyphicon sort-icon" ng-show="sortKey=='GST'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
												<th ng-click="sort('Contact')">Contact Person Name <span class="glyphicon sort-icon" ng-show="sortKey=='Contact'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
												<th ng-click="sort('Email')">Email-Id <span class="glyphicon sort-icon" ng-show="sortKey=='Email'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
												<th ng-click="sort('Mobile')">Mobile No <span class="glyphicon sort-icon" ng-show="sortKey=='Mobile'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
												<th ng-click="sort('Payment')">Payment Terms <span class="glyphicon sort-icon" ng-show="sortKey=='Payment'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
												<th>Action</th>
											</tr>
										</thead>										
										<tbody >
											<tr dir-paginate="sup in pagedItems|orderBy:sortKey:reverse|filter:search|itemsPerPage:10" total-items="{{total_count}}" current-page="mypageno">
												<td data-title="Party Id">{{sup.VendorId}}</td>
												<td data-title="Party Name">{{sup.DisplayName}}</td>
												<td data-title="Address">{{sup.Address}}</td>
												<!-- <td data-title="Shop Name">{{sup.ShopName}}</td> -->
												<td data-title="GST NO">{{sup.GSTNo}}</td>
												<td data-title="Contact Person Name">{{sup.CustomerName}}</td>
												<td data-title="Email-Id">{{sup.EmailId}}</td>
												<td data-title="Mobile No">{{sup.ContactNo}}</td>
												<td data-title="Payment Terms">{{sup.PaymentTerms}}</td>
												
												<td data-title="Action">
													<button type="button" class="btn btn-xs btn-info" data-ng-click="EditSupplier(sup.PkId,sup.VendorId,sup.Salutation,sup.DisplayName,sup.Address,sup.GSTNo,sup.CustomerName,sup.ContactNo,sup.EmailId,sup.Remarks,sup.PaymentTerms)">Edit &nbsp;
														<i class="ace-icon fa fa-pencil bigger-120"></i>
													</button>&nbsp;
													<button class="btn btn-xs btn-danger" data-ng-click="Delete(sup.PkId)">Delete &nbsp;
														<i class="ace-icon fa fa-trash-o bigger-120"></i>
													</button>
												</td>
											</tr>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="8">
													<ul class="pagination">
														<li>
														    <dir-pagination-controls max-size="10" direction-links="true" boundary-links="true" on-page-change="getData(newPageNumber)"></dir-pagination-controls>
												        </li>
												    </ul>
												</td>
											</tr>
										</tfoot>
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
										<label class="col-sm-3 control-label no-padding-right" for="CustomerId"> Party-Id <span class="error">*</span></label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="VendorId" name="VendorId" required placeholder="" data-ng-model="VendorId" disabled="">
											<div class="error" data-ng-show="submitted || AddCustomerForm.VendorId.$dirty && AddCustomerForm.VendorId.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.VendorId.$error.required">Party-Id is required.</small>
											</div>
										</div>
									</div>

									
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="State">Salutation</label>
										<div class="col-sm-6">
											<select type="text" name="salutation" class="form-control"  ng-model="salutation" ng-options="saltt.SalutationName as saltt.SalutationName for saltt in SalutationArr">
												<option value="">Select</option>
												<!-- <option ng-repeat="saltt in SalutationArr" value="{{saltt.SalutationName}}">{{saltt.SalutationName}}</option> -->
											</select>	
											<div class="error" data-ng-show="submitted || AddCustomerForm.salutation.$dirty && AddCustomerForm.salutation.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.salutation.$error.required">Select salutation Required</small>
											</div>
										</div>
									</div>

									<!-- <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Customer name"> Vendor Name <span class="error">*</span></label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="customername" name="customername" required placeholder="" autofocus data-ng-model="customername" data-ng-minlength="3" pattern="^[a-zA-Z- ]+">
											<div class="error" data-ng-show="submitted || AddCustomerForm.customername.$dirty && AddCustomerForm.customername.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.customername.$error.required">Vendor Name is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.customername.$error.minlength">Vendor Name is required to be at least 3 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.customername.$error.pattern">Vendor Name should alphabets ex:abcd</small>
											</div>
										</div>
									</div> -->

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Shop name"> Party Name <span class="error">*</span></label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="shopname" name="shopname" placeholder="" data-ng-model="shopname" data-ng-minlength="3" pattern="^[a-zA-Z- ]+" required="">
											<div class="error" data-ng-show="submitted || AddCustomerForm.shopname.$dirty && AddCustomerForm.shopname.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.shopname.$error.required">Party Name is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.shopname.$error.minlength">Party Name is required to be at least 3 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.shopname.$error.pattern">Party Name should alphabets ex:abcd</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Address">Address </label>
										<div class="col-sm-6">
											<textarea  name="address" id="address" class="form-control" ng-model="address" placeholder="" ></textarea>
												<div class="error" data-ng-show="submitted || AddCustomerForm.address.$dirty && AddCustomerForm.address.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.address.$error.required">Address Required</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="GSTN"> GSTN No </label>
										<div class="col-sm-6">
											<input class="form-control"  placeholder="" name="gstn" ng-model="gstn" maxlength="15" >
											<div class="error" ng-show="submitted || AddCustomerForm.gstn.$dirty && AddCustomerForm.gstn.$invalid">
											<small class="error" ng-show="AddCustomerForm.gstn.$error.required">GSTN No required.</small>	
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
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Contact Person Name"> Contact Person Name</label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="customername" name="customername" placeholder="" autofocus data-ng-model="customername" data-ng-minlength="3" pattern="^[a-zA-Z- ]+">
											<div class="error" data-ng-show="submitted || AddCustomerForm.customername.$dirty && AddCustomerForm.customername.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.customername.$error.required">Contact Person Name is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.customername.$error.minlength">Contact Person Name is required to be at least 3 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.customername.$error.pattern">Contact Person Name should alphabets ex:abcd</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Mobile No"> Contact No </label>
										<div class="col-sm-6">
											<input class="form-control" type="tel" id="contact" name="contact" maxlength="10"  placeholder="" data-ng-model="contact" data-ng-minlength="10" data-ng-maxlength="10" pattern="[6-9]{1}[0-9]{9}" number> 
											<!-- ng-change="checkMobile(FormPkId,contact)" -->
											<div class="error" data-ng-show="submitted || AddCustomerForm.contact.$dirty && AddCustomerForm.contact.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.contact.$error.required">Contact No is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.contact.$error.minlength">Contact No is required to be at least 10 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.contact.$error.maxlength">Contact No cannot be longer than 10 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.contact.$error.pattern">Contact No must be number & start from 6-9</small>
											</div>
											<small class="error" data-ng-if="MobileExists">Contact No already exists</small>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Email-Id">Email-Id </label>
										<div class="col-sm-6">
											<input type="email" class="form-control" placeholder="" id="emailid" name="emailid" pattern="^[a-zA-Z0-9-\_.]+@[a-zA-Z0-9-\_.]+\.[a-zA-Z0-9.]{2,5}$"   data-ng-model="emailid">
											<!-- data-ng-change="checkEmail(FormPkId,emailid)" -->						
							                  	<div class="error" data-ng-show="submitted || AddCustomerForm.emailid.$dirty && AddCustomerForm.emailid.$invalid">
													<small class="error" data-ng-show="AddCustomerForm.emailid.$error.required">Email-Id is required.</small>
													<small class="error" data-ng-show="AddCustomerForm.emailid.$error.email && !emailExists">Invalid Email-Id.</small>
												</div>
												<small class="error" data-ng-if="EmailExists">Email-Id already exists</small>
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
										<label class="col-sm-3 control-label no-padding-right" for="remarks">
											Remarks
										</label>
										<div class="col-sm-6">
											<textarea  name="remarks" id="remarks" class="form-control" ng-model="remarks" rows="5"></textarea>
											<div class="error" data-ng-show="submitted || AddCustomerForm.remarks.$dirty && AddCustomerForm.remarks.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.remarks.$error.required">Remarks Required</small>
											</div>
										</div>
									</div>


									<!-- <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Mobile No"> Mobile No <span class="error">*</span></label>
										<div class="col-sm-6">
											<input class="form-control" type="tel" id="mobileno" name="mobileno" maxlength="10" required placeholder="" data-ng-model="mobileno" data-ng-minlength="10" data-ng-maxlength="10" pattern="[6-9]{1}[0-9]{9}" number> 
											 ng-change="checkMobile(FormPkId,mobileno)" 
											<div class="error" data-ng-show="submitted || AddCustomerForm.mobileno.$dirty && AddCustomerForm.mobileno.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.mobileno.$error.required">Mobile-No is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.mobileno.$error.minlength">Mobile-No is required to be at least 10 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.mobileno.$error.maxlength">Mobile-No cannot be longer than 10 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.mobileno.$error.pattern">Mobile No must be number & start from 6-9</small>
											</div>
											<small class="error" data-ng-if="MobileExists">Mobile No already exists</small>
										</div>
									</div> -->


									<!-- <div class="form-group">
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
									</div> -->

									
									<!-- <div class="form-group">
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
											<input class="form-control" type="text" id="billingname" name="billingname" placeholder=""  data-ng-model="billingname" data-ng-minlength="3" pattern="^[a-zA-Z- ]+">
											<div class="error" data-ng-show="submitted || AddCustomerForm.billingname.$dirty && AddCustomerForm.billingname.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.billingname.$error.required">Person Name is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.billingname.$error.minlength">Person Name is required to be at least 3 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.billingname.$error.pattern">Person Name should alphabets ex:abcd</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Mobile No">Bill Mobile No</label>
										<div class="col-sm-6">
											<input class="form-control" type="tel" id="mobileno" name="billmobileno" maxlength="10"  placeholder="" data-ng-model="billmobileno" data-ng-minlength="10" data-ng-maxlength="10" pattern="[6-9]{1}[0-9]{9}" number> 
											<div class="error" data-ng-show="submitted || AddCustomerForm.billmobileno.$dirty && AddCustomerForm.billmobileno.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.billmobileno.$error.required">Mobile-No is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.billmobileno.$error.minlength">Mobile-No is required to be at least 10 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.billmobileno.$error.maxlength">Mobile-No cannot be longer than 10 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.billmobileno.$error.pattern">Mobile No must be number & start from 6-9</small>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Address">Address Lane1</label>
										<div class="col-sm-6">
											<textarea  name="address1" id="address1" class="form-control" ng-model="address1" placeholder=""  ></textarea>
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
											<input type="text"  name="state" class="form-control"   uib-typeahead="st as st for st in StateArray | filter:$viewValue" typeahead-editable="false" typeahead-on-select="onCSelect($item, $model, $label)" ng-model="state"  >
											 <select  name="state" class="form-control"  ng-model="state" required=""  ng-change="GetDist(state)" convert-to-number>
												<option value="">Select State</option>
												<option ng-repeat="state in StateArray" value="{{state.PkId}}">{{state.StateName}}</option>
											</select>	 
											<div class="error" data-ng-show="submitted || AddCustomerForm.state.$dirty && AddCustomerForm.state.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.state.$error.required">Select State Required</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="District">District </label>
										<div class="col-sm-6">
											<input type="text"  name="district" class="form-control"  ng-model="district" uib-typeahead="dist as dist for dist in DistrictArray | filter:$viewValue" typeahead-editable="false">
											 <select  name="district" class="form-control"  ng-model="district" required="" convert-to-number>
												 <option value="">Select District</option>
												<option ng-repeat="dist in DistrictArray" value="{{dist.PkId}}">{{dist.DistName}}</option>
											</select>	 
											<div class="error" data-ng-show="submitted || AddCustomerForm.district.$dirty && AddCustomerForm.district.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.district.$error.required">District is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="PinCode">PinCode </label>
										<div class="col-sm-6">
											<input class="form-control" type="tel" id="pincode" name="pincode" maxlength="6" placeholder="" data-ng-model="pincode" data-ng-minlength="6" data-ng-maxlength="6" data-ng-pattern="/^[0-9]*$/" >
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
										<label class="col-sm-3 control-label no-padding-right" for="Customer name"> Ship Person Name </label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="shippingname" name="shippingname" placeholder=""  data-ng-model="shippingname" data-ng-minlength="3" pattern="^[a-zA-Z- ]+">
											<div class="error" data-ng-show="submitted || AddCustomerForm.shippingname.$dirty && AddCustomerForm.shippingname.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.shippingname.$error.required">Person Name is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.shippingname.$error.minlength">Person Name is required to be at least 3 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.shippingname.$error.pattern">Person Name should alphabets ex:abcd</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Mobile No">Ship Mobile No</label>
										<div class="col-sm-6">
											<input class="form-control" type="tel" id="shipmobileno" name="shipmobileno" maxlength="10" placeholder="" data-ng-model="shipmobileno" data-ng-minlength="10" data-ng-maxlength="10" pattern="[6-9]{1}[0-9]{9}" number> 
											<div class="error" data-ng-show="submitted || AddCustomerForm.shipmobileno.$dirty && AddCustomerForm.shipmobileno.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.shipmobileno.$error.required">Mobile-No is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.shipmobileno.$error.minlength">Mobile-No is required to be at least 10 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.shipmobileno.$error.maxlength">Mobile-No cannot be longer than 10 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.shipmobileno.$error.pattern">Mobile No must be number & start from 6-9</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Address">Address Lane1</label>
										<div class="col-sm-6">
											<textarea  name="shipaddress1" id="address1" class="form-control" ng-model="shipaddress1" placeholder=""  ></textarea>
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
											<input type="text"  name="shipstate" class="form-control"  ng-model="shipstate"   uib-typeahead="st as st for st in StateArray | filter:$viewValue" typeahead-editable="false" typeahead-on-select="onCSelect($item, $model, $label)">
											 <select  name="state" class="form-control"  ng-model="state" required=""  ng-change="GetDist(state)" convert-to-number>
												<option value="">Select State</option>
												<option ng-repeat="state in StateArray" value="{{state.PkId}}">{{state.StateName}}</option>
											</select>	 
											<div class="error" data-ng-show="submitted || AddCustomerForm.shipstate.$dirty && AddCustomerForm.shipstate.$invalid">
											<small class="error" data-ng-show="AddCustomerForm.shipstate.$error.required">Select State Required</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="District">District </label>
										<div class="col-sm-6">
											<input type="text"  name="shipdistrict" class="form-control"  ng-model="shipdistrict" uib-typeahead="dist as dist for dist in DistrictArray | filter:$viewValue" typeahead-editable="false" >
											 <select  name="district" class="form-control"  ng-model="district" required="" convert-to-number>
												 <option value="">Select District</option>
												<option ng-repeat="dist in DistrictArray" value="{{dist.PkId}}">{{dist.DistName}}</option>
											</select>	 
											<div class="error" data-ng-show="submitted || AddCustomerForm.shipdistrict.$dirty && AddCustomerForm.shipdistrict.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.shipdistrict.$error.required">District is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="PinCode">PinCode </label>
										<div class="col-sm-6">
											<input class="form-control" type="tel" id="shippincode" name="shippincode" maxlength="6" placeholder="" data-ng-model="shippincode" data-ng-minlength="6" data-ng-maxlength="6" data-ng-pattern="/^[0-9]*$/"/ >
											<div class="error" data-ng-show="submitted || AddCustomerForm.shippincode.$dirty && AddCustomerForm.shippincode.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.shippincode.$error.required">PinCode is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.shippincode.$error.minlength">PinCode is required to be at least 6 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.shippincode.$error.maxlength">PinCode cannot be longer than 6 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.shippincode.$error.pattern">PinCode should be numeric ex:123456</small>
											</div>
										</div>
									</div> -->

									<!-- <div class="form-group">
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
									</div> -->

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
