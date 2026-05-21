<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']!="")
{
		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";
		
		$SAId = $_SESSION['EmpId'];
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once("../title.php");?>
    <script src="angularjs/list-inventory-script.js"></script>
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="CategoryModule" data-ng-controller="CategoryController" ng-init="LoadLocations()" >
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
			<li class="">Inventory</li>
			<li class="active">Manage Inventory </li>
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
							<div class="row" ng-show="FormList">
								<div class="col-xs-12">
								<!-- <div class="pull-left page-header">
									<h1>{{pagetitle}}</h1>
								</div> -->
								<div class="pull-left form-group">
									<label class="control-label no-padding-right" for="categoryname"> Select Location</label>
									<select type="text" id="location" placeholder="" class="col-sm-6 col-lg-12"  name="location" required data-ng-model="location" ng-change="GetListData(location)" >
										<option value="">Select Location</option>
										<option ng-repeat="locate in LocationArr" value="{{locate.PkId}}">{{locate.LocationName}}</option>
									</select>
									<!-- <div class="radio">
									<label>
										<input name="form-field-radio" type="radio" class="ace input-lg" ng-model="vendortype" value="1" ng-change="GetListData(vendortype)">
										<span class="lbl bigger-120"> Active </span>
									</label>

									<label>
										<input name="form-field-radio" type="radio" class="ace input-lg" ng-model="vendortype" value="0" ng-init="GetListData(vendortype)" ng-change="GetListData(vendortype)">
										<span class="lbl bigger-120"> Inactive</span>
									</label>
									</div> -->
								</div>
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="space-6"></div>
								<div class="dataTables_wrapper form-inline no-footer" ng-if="pagedItems.length!='0'">
									<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf"   datatable="ng" dt-options="vm.dtOptions">
										<thead class="cf">
											<tr>
												<th>Product Details</th>
												<th>Size</th>
												<th>Brand</th>
												<th>Buy Price</th>
												<th>Sales Price</th>
												<th>Batch No</th>
												<th>Mfg date</th>
												<th>Exp date</th>
												<th>Available Qty</th>
												
												<th>View</th>
												<!-- <th>Action</th> -->
											</tr>
										</thead>										
										<tbody >
											<tr ng-repeat="sup in pagedItems | orderBy: 'OrderId'" >
												<td data-title="Product Name">
												<ul class="breadcrumb" style="font-size: 10px;">
													<li>{{sup.CategoryName}}</li>
													<li class="">{{sup.SubCategoryName}}</li>
													<li class="active">{{sup.Level2SCName}} </li>
													<li class="active">{{sup.Level3SCName}} </li>
												</ul>
												<h4>{{sup.ProductName}}</h4>
												<p>{{sup.SKU}}</p>
											</td>
												<td data-title="Size">{{sup.Size}}</td>
												<td data-title="Brand">{{sup.Brand}}</td>
												<td data-title="Buy Price">{{sup.BuyPrice}}</td>
												<td data-title="Sales Price">{{sup.SalesPrice}}</td>
												<td data-title="Batch No">{{sup.BatchNo}}</td>
												<td data-title="Mfg date">{{sup.ManfactureDate | date: 'd-MMM-y'}}</td>
												<td data-title="Exp date">{{sup.ExpireDate | date: 'd-MMM-y'}}</td>
												
												<td data-title="Available Qty">{{sup.AvailableQty}}</td>
												<!-- <td data-title="Status">
												<span ng-if="sup.Status=='1'">Active</span>
												<span ng-if="sup.Status=='0'">Inactive</span>
												</td> -->
												
												
												<td data-title="View">
													<button class="btn btn-block btn-danger " ng-click="ViewOrder(sup.PkId,sup.ProductId_ProductMaster,sup.ProductName,sup.Size,sup.Brand,sup.BatchNo,sup.ManfactureDate,sup.ExpireDate,sup.BuyPrice,sup.SalesPrice,sup.SKU,sup.AvailableQty,sup.Status)">Manage</a>
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

    <div class="row" ng-show="FormAdd">
	    <div class="col-xs-12">
	      <!-- PAGE CONTENT BEGINS -->
		 <form class="form-horizontal" role="form" id="AddCategoryForm" name="AddCategoryForm" autocomplete="off" enctype="multipart/form-data" novalidate="">
		 		<input type="hidden" class="form-control"  name="FormPkId"  placeholder="" ng-model="FormPkId" disabled="" > 
		 			<h3>{{protype}}	</h3>
			 		<div class="tabbable">
					<ul class="nav nav-tabs" id="myTab">
						<li class="active">
							<a data-toggle="tab" href="#home">
								<i class="green ace-icon fa fa-home bigger-120"></i>
								Edit Details
							</a>
						</li>
<!-- 
						<li>
							<a data-toggle="tab" href="#messages">
								Add More Quantity
							</a>
						</li> -->
					</ul>

					<div class="tab-content">
						<div id="home" class="tab-pane fade in active">
							
							<div class="form-group">
								<input type="hidden" value="{{ProductId}}">
									<label class="col-sm-3 control-label no-padding-right" for="categoryname"> Product Name <span class="error">*</span></label>
									<div class="col-sm-6">
										<input type="text" id="protype" placeholder="" class="form-control" name="protype" required  data-ng-model="protype" disabled="">
											<div class="error" data-ng-show="submitted || AddCategoryForm.protype.$dirty && AddCategoryForm.protype.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.protype.$error.required">Product  is required.</small>
												<small class="error" data-ng-show="AddCategoryForm.protype.$error.minlength">Product  is required to be at least 3 characters</small>
												<small class="error" data-ng-show="AddCategoryForm.protype.$error.pattern">Product  should be alphabets ex:abcd</small>
											</div>
									</div>
								</div>

								<!-- <div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="product Description"> Product Description </label>
									
									<div class="col-sm-6">
										<textarea  id="prodescription" placeholder="" class="form-control" name="prodescription"   data-ng-model="prodescription" maxlength="300" disabled=""></textarea>
											<div class="error" data-ng-show="submitted || AddCategoryForm.prodescription.$dirty && AddCategoryForm.prodescription.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.prodescription.$error.required">Product  is required.</small>
											</div>
									</div>
								</div> -->

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="product Size"> Size <span class="error">*</span></label>
									
									<div class="col-sm-6">
										<select type="text"  id="productsize" placeholder="" class="form-control" name="productsize" maxlength="100" required=""    data-ng-model="productsize">
											<option value="">Select</option>
											<option ng-repeat="size in SizeArr" value="{{size.Size}}">{{size.Size}}</option>
										</select>
										<!-- uib-typeahead="size for size in SizeArr | filter:$viewValue"
											<span class="help-block">Ex: 1Kg / 250 Gr / 100 Ml / 1 Ltr</span> -->
											<div class="error" data-ng-show="submitted || AddCategoryForm.productsize.$dirty && AddCategoryForm.productsize.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.productsize.$error.required">Size  is required.</small>
											</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="unit"> Brand </label>
									
									<div class="col-sm-6">
										<select type="text"  id="brand" placeholder="" class="form-control" name="brand" maxlength="100" data-ng-model="brand">
											<option value="">Select</option>
											<option ng-repeat="bnd in BrandArr" value="{{bnd.BrandName}}">{{bnd.BrandName}}</option>
										</select>
											<div class="error" data-ng-show="submitted || AddCategoryForm.brand.$dirty && AddCategoryForm.brand.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.brand.$error.required">Brand  is required.</small>
											</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="unit"> Product Buy Price <span class="error">*</span></label>
									<div class="col-sm-6">
										<input type="text" id="buyprice" placeholder="" class="form-control" name="buyprice" data-ng-model="buyprice" maxlength="8" required="" class="form-control" valid-number allow-decimal="false" allow-negative="false">
											<div class="error" data-ng-show="submitted || AddCategoryForm.buyprice.$dirty && AddCategoryForm.buyprice.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.buyprice.$error.required">Product Price is required.</small>
											</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="unit"> Product Selling Price <span class="error">*</span></label>
									<div class="col-sm-6">
										<input type="text" id="salesprice" placeholder="" class="form-control" name="salesprice" data-ng-model="salesprice" maxlength="8" required="" class="form-control" valid-number allow-decimal="false" allow-negative="false">
											<div class="error" data-ng-show="submitted || AddCategoryForm.salesprice.$dirty && AddCategoryForm.salesprice.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.salesprice.$error.required">Product Selling Price is required.</small>
											</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="unit"> Batch No </label>
									<div class="col-sm-6">
										<input type="text" id="batchno" placeholder="" class="form-control" name="batchno" data-ng-model="batchno" >
											<div class="error" data-ng-show="submitted || AddCategoryForm.batchno.$dirty && AddCategoryForm.batchno.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.batchno.$error.required">Batch No is required.</small>
											</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="unit"> Manfacture Date </label>
									<div class="col-sm-6">
										<input type="text" class="form-control" name="mfgdate"  placeholder="" id="mfgdate" ng-model="mfgdate" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened2" ng-click="open($event,'opened2')"  datepicker-options="assDate1" > 
											<div class="error" data-ng-show="submitted || AddCategoryForm.mfgdate.$dirty && AddCategoryForm.mfgdate.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.mfgdate.$error.required">Manfacture date is required.</small>
											</div>
									</div>
								</div>


								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="unit"> Expire Date </label>
									<div class="col-sm-6">
										<input type="text" class="form-control" name="expdate"  placeholder=""  id="expdate" ng-model="expdate" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened3" ng-click="open($event,'opened3')"  datepicker-options="assDate1" > 
											<div class="error" data-ng-show="submitted || AddCategoryForm.expdate.$dirty && AddCategoryForm.expdate.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.expdate.$error.required">Expire date is required.</small>
											</div>
									</div>
								</div>

								<!-- <div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="unit"> Unit <span class="error">*</span></label>
									
									<div class="col-sm-9">
										<input type="text" id="productunit" placeholder="" class="col-xs-10 col-sm-5" name="productunit" maxlength="20" required="" class="form-control" uib-typeahead="unit for unit in UnitArr | filter:$viewValue | limitTo:4" data-ng-model="productunit" >
											<div class="error" data-ng-show="submitted || AddCategoryForm.productunit.$dirty && AddCategoryForm.productunit.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.productunit.$error.required">Unit  is required.</small>
											</div>
									</div>
								</div> -->

								<!-- <div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="PinCode">Do you want add colour <span class="error">*</span>
								  	</label>
								  	<div class="col-sm-6">
										<div class="control-group">
											<div class="radio">
												<label>
													<input name="iscolourreq" type="radio" class="ace input-lg" ng-model="iscolourreq" value="Yes" ng-required="!iscolourreq">
													<span class="lbl bigger-120"> Yes</span>
												</label>
											
												<label>
													<input  type="radio" class="ace input-lg" name="iscolourreq" ng-model="iscolourreq" value="No" ng-required="!iscolourreq">
													<span class="lbl bigger-120"> No</span>
												</label>
											</div>
											<div class="error" data-ng-show="submitted || AddCategoryForm.iscolourreq.$dirty && AddCategoryForm.iscolourreq.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.iscolourreq.$error.required">required.</small>
											</div>
										</div>
									</div>
								</div>

								<div class="form-group" ng-if="iscolourreq=='Yes'">
									<label class="col-sm-3 control-label no-padding-right" for="unit"> Enter Colour Name <span class="error">*</span></label>
									<div class="col-sm-9">
										<input type="text" id="colourname" placeholder="" class="col-xs-10 col-sm-5" name="colourname" data-ng-model="$parent.colourname" maxlength="100" required="" class="form-control">
											<div class="error" data-ng-show="submitted || AddCategoryForm.colourname.$dirty && AddCategoryForm.colourname.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.colourname.$error.required">Unit  is required.</small>
											</div>
									</div>
								</div> -->
								<input type="hidden" id="Avlquantity" placeholder="" class="col-xs-10 col-sm-5" name="Avlquantity" data-ng-model="Avlquantity" class="form-control" number>

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="unit"> Quantity <span class="error">*</span></label>
									<div class="col-sm-9">
										<input type="text" id="quantity" placeholder="" class="col-xs-10 col-sm-5" name="quantity" data-ng-model="quantity" maxlength="5" class="form-control" number ng-change="Chkqty(quantity)">
											<div class="error" data-ng-show="submitted || AddCategoryForm.quantity.$dirty && AddCategoryForm.quantity.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.quantity.$error.required">Available Quantity is required.</small>
											</div>
									</div>
								</div>

								<!-- <div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="unit"> Status <span class="error">*</span></label>
									<div class="col-sm-9">
										<select  id="Status" placeholder="" class="col-xs-10 col-sm-5" name="Status" data-ng-model="Status" convert-to-number required="">
											<option value="">Select</option>
											<option value="1">Active</option>
											<option value="0">Inactive</option>
										</select>
											<div class="error" data-ng-show="submitted || AddCategoryForm.Status.$dirty && AddCategoryForm.Status.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.Status.$error.required">Status is required.</small>
											</div>
									</div>
								</div> -->

								

								<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button type="button" ng-click="Updatedata()" class="btn btn-success">
										<i class="ace-icon fa fa-check bigger-110"></i>
										Save
									</button>&nbsp;&nbsp;
									<button type="button" ng-click="GotoList()" class="btn btn-default">Cancel</button>
								</div>
								</div>
						</div>

						<div id="messages" class="tab-pane fade">

								<input type="hidden" value="{{SKU}}">
							<div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="unit"> Enter Quantity <span class="error">*</span></label>
								<div class="col-sm-9">
									<input type="text" id="freshquantity" placeholder="" class="col-xs-10 col-sm-5" name="freshquantity" data-ng-model="freshquantity" maxlength="5" class="form-control" number ng-init="freshquantity='0'" ng-change="GetTotal(freshquantity,quantity)">
										<div class="error" data-ng-show="submitted || AddCategoryForm.freshquantity.$dirty && AddCategoryForm.freshquantity.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.freshquantity.$error.required">Quantity is required.</small>
										</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="unit"> Available Quantity <span class="error">*</span></label>
								<div class="col-sm-9">
									<input type="text" id="quantity" placeholder="" class="col-xs-10 col-sm-5" name="quantity" data-ng-model="quantity" maxlength="10" class="form-control" number disabled="">
										<div class="error" data-ng-show="submitted || AddCategoryForm.quantity.$dirty && AddCategoryForm.quantity.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.quantity.$error.required">Product Price is required.</small>
										</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="unit"> Total Quantity will be<span class="error">*</span></label>
								<div class="col-sm-9">
									<input type="text" id="quantity" placeholder="" class="col-xs-10 col-sm-5" maxlength="10" class="form-control" value="{{totalqty || quantity}}" disabled="">
								</div>
							</div>

							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button type="button" ng-click="AddMoreQty()" class="btn btn-success">
										<i class="ace-icon fa fa-check bigger-110"></i>
										Save
									</button>&nbsp;&nbsp;
									<button type="button" ng-click="GotoList()" class="btn btn-default">Cancel</button>
								</div>
								</div>
						</div>
					</div>
				</div>
						 
						
						
				</form>
       			 </div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.page-content -->
	</div>
</div><!-- /.main-content -->
			<!-- /.main-container ending in footer page -->
		<?php include_once("../footer.php");?>
		<!-- inline scripts related to this page -->
		
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
