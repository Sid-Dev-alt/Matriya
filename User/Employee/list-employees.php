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
    	<script src="angularjs/employee-script.js"></script>
	</head>

	<body class="no-skin"  data-ng-cloak data-ng-app="CategoryModule" data-ng-controller="CategoryController" ng-init="GetStates();GetRoles()">
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
							<li class="active">Employees</li>
						</ul><!-- /.breadcrumb -->

						<!-- <div class="nav-search" id="nav-search">
							<form class="form-search">
								<span class="input-icon">
									<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
									<i class="ace-icon fa fa-search nav-search-icon"></i>
								</span>
							</form>
						</div> --><!-- /.nav-search -->
					</div>

					<div class="page-content" >

					<div id="no-more-tables">
						<div class="row">
							<div class="col-xs-12 page-header">
								<div class="col-xs-12 col-sm-12 col-lg-6">
			                  	 	<div class="" >
										<h1>{{pagetitle}}</h1>
										</div>
								</div>
		                  	 	<div class="col-xs-12 col-sm-12 col-lg-6">
				                    <a href="" data-ng-click="GotoAdd()">
									<div class="label label-lg label-success arrowed-in arrowed-right pull-right" ng-show="FormList">
									<b>Add Employee</b>
									</div></a>
			                  </div>
			              </div>
							
						</div>
							<div class="row" ng-show="FormList">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="space-6"></div>
								<div class="dataTables_wrapper form-inline no-footer">
									<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf" data-ng-init="GetListData()"   datatable="ng" dt-options="vm.dtOptions">
										<thead class="cf">
											<tr>
												<th>Person Name</th>
												<th>Designation</th>
												<th>Email-Id</th>
												<th>Mobile No</th>
												<th>Address</th>
												<th>Action</th>
											</tr>
										</thead>										
										<tbody >
											<tr ng-repeat="sup in pagedItems">
												<td data-title="Person Name">{{sup.EmpName}}</td>
												<td data-title="Designation">{{sup.Designation}}</td>
												<td data-title="Email-Id">{{sup.EmailId}}</td>
												<td data-title="Mobile No">{{sup.Mobile}}</td>
												<td data-title="Address">{{sup.AddressLane1}} <br>{{sup.AddressLane2}} <br>{{sup.City}}</td>
												<td data-title="Action">
													<button type="button" class="btn btn-xs btn-info" data-ng-click="EditSupplier(sup.PkId,sup.EmpName,sup.Designation,sup.EmailId,sup.Mobile,sup.OtherMobileNo,sup.AddressLane1,sup.AddressLane2,sup.Town,sup.LandMark,sup.City,sup.State,sup.PINCode,sup.District,sup.Lattitude,sup.Longitude)">Edit &nbsp;
														<i class="ace-icon fa fa-pencil bigger-120"></i>
													</button>&nbsp;
													<!--<button class="btn btn-xs btn-danger" data-ng-click="EditCat(cat.CategoryId,cat.CategoryName,cat.Description)">Delete &nbsp;
														<i class="ace-icon fa fa-trash-o bigger-120"></i>
													</button>-->
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
										<label class="col-sm-3 control-label no-padding-right" for="Supplier name"> Employee Name <span class="error">*</span></label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="empname" name="empname" required placeholder=""  data-ng-model="empname" data-ng-minlength="3" pattern="^[a-zA-Z- ]+">
											<div class="error" data-ng-show="submitted || AddCustomerForm.empname.$dirty && AddCustomerForm.empname.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.empname.$error.required">Supplier Name is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.empname.$error.minlength">Supplier Name is required to be at least 3 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.empname.$error.pattern">Supplier Name should alphabets ex:abcd</small>
											</div>
										</div>
									</div>
									<!-- <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Supplier name"> Role <span class="error">*</span></label>
										<div class="col-sm-6">
											<select class="form-control" id="role" name="role" required placeholder=""  data-ng-model="role">
												<option value="">Select</option>
												<option ng-repeat="role in RoleArray" value="{{role.RoleId}}">{{role.RoleName}}</option>
												<option value="Other">Other</option>
											</select>
											<div class="error" data-ng-show="submitted || AddCustomerForm.role.$dirty && AddCustomerForm.role.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.role.$error.required">Role Name is required.</small>
											</div>
										</div>
									</div> -->

									<div class="form-group" ng-if="role=='Other'">
										<label class="col-sm-3 control-label no-padding-right" for="Supplier name"> Designation <span class="error">*</span></label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="designation" name="designation" required placeholder=""  data-ng-model="$parent.designation" data-ng-minlength="3" pattern="^[a-zA-Z- ]+">
											<div class="error" data-ng-show="submitted || AddCustomerForm.designation.$dirty && AddCustomerForm.designation.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.designation.$error.required">Designation Name is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.designation.$error.minlength">Designation Name is required to be at least 3 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.designation.$error.pattern">Designation Name should alphabets ex:abcd</small>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Email-Id">Email-Id <span ng-if="role=='Other'" class="error">*</span></label>
										<div class="col-sm-6">
											<input type="email" class="form-control" placeholder="" id="emailid" name="emailid" pattern="^[a-zA-Z0-9-\_.]+@[a-zA-Z0-9-\_.]+\.[a-zA-Z0-9.]{2,5}$"   data-ng-model="emailid" required="role=='Other'">						
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
											<input class="form-control" type="tel" id="mobileno" name="mobileno" maxlength="10" required placeholder="" data-ng-model="mobileno" data-ng-minlength="10" data-ng-maxlength="10" pattern="[6-9]{1}[0-9]{9}" > 
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
											<input class="form-control" type="text" id="othermobileno" name="othermobileno"  placeholder="" data-ng-model="landline" maxlength="14" data-ng-minlength="14" data-ng-maxlength="14" data-ng-pattern="/^[0-9]*$/">
											<div class="error" data-ng-show="AddCustomerForm.othermobileno.$dirty && AddCustomerForm.othermobileno.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.othermobileno.$error.required">Office No. is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.othermobileno.$error.minlength">Office No. is required to be at least 14 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.othermobileno.$error.maxlength">Office No. is cannot be longer than 14 characters</small>
												<small class="error" data-ng-show="AddCustomerForm.othermobileno.$error.pattern">Office should be numeric ex:1234567890</small>
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
										<label class="col-sm-3 control-label no-padding-right" for="District">District <span class="error">*</span></label>
										<div class="col-sm-6">
											<select  name="district" class="form-control"  ng-model="district" required="" convert-to-number>
												 <!-- ng-change="GetGeoLocation()" -->
												<option value="">Select District</option>
												<option ng-repeat="dist in DistrictArray" value="{{dist.PkId}}">{{dist.DistName}}</option>
											</select>	
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
									</div>
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
											<button class="btn btn-info" type="submit" data-ng-disabled="FormValid">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
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