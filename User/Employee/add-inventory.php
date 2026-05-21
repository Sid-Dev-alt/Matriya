<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']!="")
{
		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";
		
		
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once("../title.php");?>
    <script src="angularjs/inventory-script.js"></script>
    <script src="../../assets/js/angularjs/paginationjs/dirPagination.js"></script>
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="CategoryModule" data-ng-controller="CategoryController">
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
			<li class="active">Add Inventory</li>
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
						<div class="row" ng-show="FormAdd" data-ng-init="GetProductList()">
						<div class="col-xs-12">
						<!-- PAGE CONTENT BEGINS -->
						<form class="form-horizontal" role="form" id="AddSearchForm" name="AddSearchForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="FinalSubmisssion()" >
							 
							<div class="form-group">
								<div class="col-sm-9">
									<label>&nbsp;</label>
									<!-- <input type="text" id="productname" placeholder="" class="form-control" name="productname" required data-ng-model="productname"  uib-typeahead="cust as cust.ProductName for cust in ProductArray | filter:$viewValue" > -->

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
						   
						</form>
						</div><!-- /.col -->
						</div><!-- /.row -->

					<div class="row" ng-show="FormList">
						<div class="col-xs-12">
							<div class="media search-media" dir-paginate="sup in pagedItems|filter:query|itemsPerPage:10">
								<div class="media-left">
									<a href="#">
										<img class="media-object" alt="72x72" ng-src="../ProductImages/{{sup.FileName}}" style="width: 72px; height: 72px;">
									</a>
								</div>

								<div class="media-body">
									<div class="breadcrumbs ace-save-state" id="breadcrumbs">
										<ul class="breadcrumb" style="font-size: 10px;">
											<li>{{sup.CategoryName}}</li>
											<li class="">{{sup.SubCategoryName}}</li>
											<li class="active">{{sup.Level2SCName}} </li>
											<li class="active">{{sup.Level3SCName}} </li>
										</ul>

									</div>
										<h4 class="media-heading">
											<a href="" class="blue">{{sup.ProductName}}</a>
										</h4>
									<p>{{sup.ProductDescription}}</p>
									<div class="search-actions text-center">
										<p>Product Id</p>
										<span class="blue bolder bigger-150">{{sup.ProductId}}</span>

										<!-- <a class="search-btn-action btn btn-sm btn-block btn-info">Add Stock</a> -->
										<a href="#modal-form" class="search-btn-action btn btn-sm btn-block btn-info" data-toggle="modal" ng-click="AddStock(sup.ProductId,sup.ProductName,sup.ProductDescription,sup.PkId_Category,sup.CategoryName,sup.FileName)">Add Stock</a>

									</div>
								</div>
							</div>
							<div class="pagination pull-right">
								<dir-pagination-controls max-size="7" direction-links="true" boundary-links="true"></dir-pagination-controls>
							</div>
						</div>
					</div><!--FOrm lising-->

					<!--modal-->
					<div id="modal-form" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
					<div class="modal-dialog  modal-lg">
					<form class="form-horizontal" role="form" id="AddCategoryForm" name="AddCategoryForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="SaveInventory()" >
						<div class="modal-content">
							<div class="modal-header">
								<div class="search-area well col-xs-12">
								<div class="pull-left">
									<h4 class="blue bigger">{{ProductId}}</h4>
								</div>

								<div class="pull-right">
									<b class="text-primary">The product you are adding may already exist on your inventory </b>

									&nbsp;
									<a href="list-master-data.php" class="btn btn-sm btn-success">Manage Inventory</a>
									&nbsp;&nbsp;&nbsp;
									<button type="button" class="close" data-dismiss="modal">&times;</button>
								</div>
								</div>
							</div>

							<div class="modal-body">
							<div class="row">
								<div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="categoryname"> Location <span class="error">*</span></label>
								<div class="col-sm-6">
								<select type="text" id="location" placeholder="" class="form-control"  name="location" required data-ng-model="location" >
									<option value="">Select</option>
									<option ng-repeat="locate in LocationArr" value="{{locate.PkId}}">{{locate.LocationName}}</option>
								</select>
								<div class="error" data-ng-show="submitted || AddCategoryForm.location.$dirty && AddCategoryForm.location.$invalid">
									<small class="error" data-ng-show="AddCategoryForm.location.$error.required">Location  is required.</small>									
								</div>
								</div>
								</div>
<!-- 
								<div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="categoryname"> Category <span class="error">*</span></label>
								
								<div class="col-sm-6">
									<input type="hidden" value="{{PkId_Category}}" class="form-control">
								<input type="text" id="category" placeholder="" class="form-control"  name="category" required data-ng-model="category" disabled="">

								</div>
								</div> -->

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="categoryname"> Product Name <span class="error">*</span></label>
									<div class="col-sm-6">
										<input type="text" id="protype" placeholder="" class="form-control" name="protype" required  data-ng-model="protype" data-ng-minlength="3" disabled="">
											<div class="error" data-ng-show="submitted || AddCategoryForm.protype.$dirty && AddCategoryForm.protype.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.protype.$error.required">Product  is required.</small>
												<small class="error" data-ng-show="AddCategoryForm.protype.$error.minlength">Product  is required to be at least 3 characters</small>
												<small class="error" data-ng-show="AddCategoryForm.protype.$error.pattern">Product  should be alphabets ex:abcd</small>
												
											</div>
											<small ng-if="NameExists" class="error">Already Exists in Scart Catalogue</small> 
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="product Description"> Product Description </label>
									
									<div class="col-sm-6">
										<textarea  id="prodescription" placeholder="" class="form-control" name="prodescription"   data-ng-model="prodescription" maxlength="300" disabled=""></textarea>
											<div class="error" data-ng-show="submitted || AddCategoryForm.prodescription.$dirty && AddCategoryForm.prodescription.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.prodescription.$error.required">Product  is required.</small>
											</div>
									</div>
								</div>

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

								<!-- <div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="PinCode">Do you want add colour for this product <span class="error">*</span>
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

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="unit"> Quantity <span class="error">*</span></label>
									<div class="col-sm-6">
										<input type="text" id="quantity" placeholder="" class="form-control" name="quantity" data-ng-model="quantity" maxlength="10" required="" class="form-control" number>
											<div class="error" data-ng-show="submitted || AddCategoryForm.quantity.$dirty && AddCategoryForm.quantity.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.quantity.$error.required">Product Price is required.</small>
											</div>
									</div>
								</div>
							</div><!-- row-->

							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button type="submit" class="btn btn-success">
										<i class="ace-icon fa fa-check bigger-110"></i>
										Save
									</button>&nbsp;&nbsp;
									<button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
								</div>
								</div>
							</div>


						</div><!--modal -->
					</form>
					</div><!-- /.modal-content -->	
					</div>
					<!--modal-->		

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