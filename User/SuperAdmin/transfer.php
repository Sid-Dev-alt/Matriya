<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']!="")
{
		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";
		
		
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once("../title.php");?>
    <script src="angularjs/transfer_script.js"></script>
    <script src="../../assets/js/angularjs/paginationjs/dirPagination.js"></script>
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="CategoryModule" data-ng-controller="CategoryController" ng-init="GetWareHouse()">
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
			<li class="active">Transfer</li>
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
						<!-- <div class="row">
							<div class="col-xs-12 page-header">
								<div class="col-xs-12 col-sm-12 col-lg-6">
			                  	 	<div class="" >
										<h1>{{pagetitle}}</h1>
										</div>
								</div>
			              </div>
							
						</div> -->
						 <form class="form-horizontal" role="form" id="AddCategoryForm" name="AddCategoryForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="SaveInventory()" >
						<div class="row">
						<div class="col-xs-12">
						<!-- PAGE CONTENT BEGINS -->
						<div class="pull-left form-group">
							<label class="control-label no-padding-right" for="categoryname">Transfer From</label>
							<select type="text" id="location" placeholder="" class="col-sm-6 col-lg-12"  name="location" required data-ng-model="location" ng-change="GetProductList(location)" >
								<option value="">Select Location</option>
								<option ng-repeat="locate in WHArray" value="{{locate.PkId}}">{{locate.LocationName}}</option>
							</select>
						</div>
						<!-- <form class="form-horizontal" role="form" id="AddSearchForm" name="AddSearchForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="FinalSubmisssion()" >
							 
							<div class="form-group">
								<div class="col-sm-9">
									<label>&nbsp;</label>

									<input type="text" id="productname" placeholder="Serach By Product Name" class="form-control" name="productname" required   data-ng-model="productname"  uib-typeahead="cust as cust.ProductName for cust in ProductArray | filter:$viewValue" >
										
										<div class="error" data-ng-show="submitted || AddSearchForm.productname.$dirty && AddSearchForm.productname.$invalid">
											<small class="error" data-ng-show="AddSearchForm.productname.$error.required">Please enter product name.</small>
										</div>
								</div>
								<div class="col-sm-3">
									<div class="clearfix">&nbsp;</div>
								<button class="btn btn-sm btn-success" type="submit"  >
										<i class="ace-icon fa fa-search bigger-110"></i>
										Search
									</button>
								</div>
							</div>
							<p>
								<i class="ace-icon fa fa-circle green"></i>
								If it is not found: <a href="product-types.php">Create a new product</a>
							</p>
							 <p>&nbsp;</p>
							
							 <div class="clearfix"></div>

							 <div class="text-center muted" ng-if="NoRecord">
							 	<h3>No Records Found</h3>
							 	<p><a href="product-types.php">Create a new product</a></p>
							 </div>
						   
						</form> -->
						</div><!-- /.col -->
						</div><!-- /.row -->

					<div class="row">
									<div class="space-6"></div>
								<div class="dataTables_wrapper form-inline no-footer" ng-if="ProductArray.length!='0'">
									<table  id="dynamic-table"  class="table table-bordered table-striped table-condensed cf"   datatable="ng" dt-options="vm.dtOptions">
										<thead class="cf">
											<tr>
												<th>Product Details</th>
												<th>Buy Price</th>
												<th>Sales Price</th>
												<th>Batch No</th>
												<th>Mfg date</th>
												<th>Exp date</th>
												<th>Available Qty</th>
												<th>Add Qty</th>
												<!-- <th>Action</th> -->
											</tr>
										</thead>										
										<tbody >
											<tr ng-repeat="sup in ProductArray | orderBy: 'OrderId'" >
												<td data-title="Product Name">
												<h4>{{sup.ProductName}}</h4>
												<p>Size: {{sup.Size}}</p>
												<p ng-if="sup.Brand!=null || sup.Brand!=''">Brand: {{sup.Brand}}</p>

												<!-- <p>{{sup.SKU}}</p> -->
											</td>
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
													<input type="text" id="quantity" placeholder="" class="form-control" name="quantity" required  data-ng-model="sup.quantity" number>
													<div class="clearfix">&nbsp;</div>
													<button type="button" class="btn btn-block btn-success " ng-click="AddtoTransfer(sup.SKU,sup.ProductName,sup.Size,sup.AvailableQty,sup.AvailableQty,sup.quantity,$index)">Transfer</button>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								</div><!-- /.row -->
								<p>&nbsp;</p>

						<div class="row" ng-if="TransferArray.length!='0'">
							<h4>Below products are selected to Transfer</h4>
							<table class="table table-bordered table-striped table-condensed cf"   >
								<thead class="cf">
									<tr>
										<th>Product Details</th>
										<th>Qty</th>
										<th>Action</th>
									</tr>
								</thead>										
								<tbody >
									<tr ng-repeat="trsfr in TransferArray" ng-if="$index>=0" >
										<td data-title="Product Name">
										<h4>{{trsfr.pname}}</h4>
										<p>Size: {{trsfr.psize}}</p>
										<p>{{trsfr.sku}}</p>
										<p class="hidden">{{trsfr.FixedQty}}</p>
									</td>
										<td data-title="Qt">
											<input type="text" id="pquantity" placeholder="" class="form-control" name="pquantity" required  data-ng-model="trsfr.pquantity" number >
											<span class="error" ng-if="trsfr.FixedQty<trsfr.pquantity">Qty is not more than {{trsfr.FixedQty}}</span>
										</td>
										<td>
											<button type="button" class="btn btn-block btn-danger " ng-click="DeleteEntry($index,trsfr.sku,trsfr.pquantity)">Remove</button>
										</td>
									</tr>
								</tbody>
							</table>
							<p>&nbsp;</p>
						<div class="form-group">
							<label class="col-sm-3 control-label no-padding-right" for="PinCode">Transfer To<span class="error">*</span>
						  	</label>
						  	<div class="col-sm-6">
								<div class="control-group">
									<div class="radio">
										<label>
											<input name="TransferTo" type="radio" class="ace input-lg" ng-model="$parent.TransferTo" value="Store" ng-required="!TransferTo" ng-change="GetStore($parent.TransferTo)">
											<span class="lbl bigger-120"> Store</span>
										</label>
									
										<label>
											<input  type="radio" class="ace input-lg" name="TransferTo" ng-model="$parent.TransferTo" value="Customer" ng-required="!TransferTo" ng-change="GetStore($parent.TransferTo)">
											<span class="lbl bigger-120"> Customer</span>
										</label>
									</div>
									<div class="error" data-ng-show="submitted || AddCategoryForm.TransferTo.$dirty && AddCategoryForm.TransferTo.$invalid">
										<small class="error" data-ng-show="AddCategoryForm.TransferTo.$error.required">required.</small>
									</div>
								</div>
							</div>
						</div>
					</div>

						<div class="form-group" ng-if="TransferTo=='Store'">
							<label class="col-sm-3 control-label no-padding-right" for="PinCode">Transfer To<span class="error">*</span>
						  	</label>
						  	<div class="col-sm-6">
								<select type="text" id="storename" placeholder="" class="form-control"  name="storename" required data-ng-model="$parent.storename" >
									<option value="">Select</option>
									<option ng-repeat="store in StoreArr" value="{{store.PkId}}">{{store.LocationName}}</option>
								</select>
									<div class="error" data-ng-show="submitted || AddCategoryForm.storename.$dirty && AddCategoryForm.storename.$invalid">
										<small class="error" data-ng-show="AddCategoryForm.storename.$error.required">Store  is required.</small>	
									</div>
							</div>
						</div>

						<div class="form-group" ng-if="TransferTo=='Customer'">
							<label class="col-sm-3 control-label no-padding-right" for="PinCode">Customer Name<span class="error">*</span>
						  	</label>
						  	<div class="col-sm-6">
								<input type="text" id="cname" placeholder="" class="form-control"  name="cname"   uib-typeahead="cust as cust.PersonName for cust in CustomerArr | filter:$viewValue" required data-ng-model="$parent.cname"typeahead-on-select="onSelect($item,$model,$label)">
									<div class="error" data-ng-show="submitted || AddCategoryForm.cname.$dirty && AddCategoryForm.cname.$invalid">
										<small class="error" data-ng-show="AddCategoryForm.cname.$error.required">Customer Name is required.</small>	
									</div>
							</div>
						</div>

						<div class="form-group" ng-if="TransferTo=='Customer'">
							<label class="col-sm-3 control-label no-padding-right" for="PinCode">Customer Mobile
						  	</label>
						  	<div class="col-sm-6">
								<input type="text" id="cmobile" placeholder="" class="form-control"  name="cmobile" data-ng-model="$parent.cmobile" number maxlength="10">
									<div class="error" data-ng-show="submitted || AddCategoryForm.cmobile.$dirty && AddCategoryForm.cmobile.$invalid">
										<small class="error" data-ng-show="AddCategoryForm.cmobile.$error.required">Mobile  is required.</small>	
									</div>
							</div>
						</div>

						<div class="form-group" ng-if="TransferArray.length!='0'">
							<label class="col-sm-3 control-label no-padding-right" for="PinCode">Comments
						  	</label>
						  	<div class="col-sm-6">
								<textarea id="comments" placeholder="" class="form-control"  name="comments" data-ng-model="$parent.comments" ></textarea>
							</div>
						</div>

						<div class="clearfix form-actions"  ng-if="TransferArray.length!='0'">
							<div class="col-md-offset-3 col-md-9">
								<button type="submit" class="btn btn-success">
									<i class="ace-icon fa fa-check bigger-110"></i>
									Save
								</button>&nbsp;&nbsp;
								<button type="button" ng-click="Gotoback()" class="btn btn-default">Cancel</button>
							</div>
							</div>
						</div>
					
		
				</form>
			</div>
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