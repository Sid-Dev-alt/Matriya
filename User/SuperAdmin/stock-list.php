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
		<link rel="stylesheet" href="../../assets/css/colorbox.min.css" />
		<!--Print Script & Css Starts Here-->
		<link rel="stylesheet" href="../../assets/css/stockprint.css" />
			<script language="javascript" type="text/javascript">
				function PrintPage()
				{
				    window.print();
				}
			</script>			
		<!--Print Script & Css Ends Here-->
    	<script src="angularjs/stock-list-script.js"></script>
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="CategoryModule" data-ng-controller="CategoryController" data-ng-init="LoadCatgory();GetListData()">
		<?php include_once("../top.php");?>

		<div class="main-container ace-save-state" id="main-container">
			
			<?php include_once("sidebar.php");?>

			<div class="main-content">
				<?php include_once("../loader.php");?>
				<div class="main-content-inner">
					<!-- <div class="breadcrumbs ace-save-state" id="breadcrumbs">
						<ul class="breadcrumb">
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class=""><a href="#">Masters</a></li>
							<li class="active">Product Types</li>
						</ul>
					</div> -->

					<div class="page-content" >
					<div  id="">
						<div class="row">
							<div class="col-xs-12 page-header">
		                  	 <div class="col-xs-8 col-sm-8 col-lg-9">
		                  	 	<div class="" >
									<h1>{{pagetitle}} as on<small><?php echo GetCurrentDateTime();?></small></h1>
									</div>
		<!-- /.page-header --></div>
		                  	 	<div class="col-xs-4 col-sm-4 col-lg-3" ng-if="FormList">
		                  	 		<a class="custom-btn" href="" ng-click="GotoAdd()">
			                    <i class="pull-left add-thumbicon fa fa-plus btn-success no-hover"></i>
			                    </a>&nbsp;&nbsp;&nbsp;<a class="btn btn-info" href="#" onclick="window.print();"><i class="ace-icon fa fa-check bigger-110"></i> PRINT</a>
			                  </div>
			              </div>							
						</div>

							<div class="row" ng-show="FormList">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="space-6"></div>
									<div class="col-sm-8">
										<input type="search" class="form-control input-lg" placeholder="search by item name" data-ng-model="search">
									</div>
									
								</div><!-- /.row -->
									<div class="space-6">&nbsp;</div>
									<div class="row">
									<div class="col-sm-12">
									<div id="accordion" class="accordion-style1 panel-group ">
										<div class="panel panel-default" ng-repeat="cat in pagedItems|filter:search">
											
									    <div class="panel-heading">
												<h4 class="panel-title">
													
													<a class="accordion-toggle collapsed" style="background-color: #CE6F9E !important;color: #fff !important" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$index}}" aria-expanded="false">
														{{$index+1}} <i class="bigger-110 ace-icon fa fa-angle-right" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
														&nbsp;{{cat.Micron | uppercase}} {{cat.ProductName | uppercase}} | <span class="">No. of Rolls: {{cat.TotalRolls || 0}} </span> | <span class="" > Total Qty: {{cat.AvlQty | number:3}} {{cat.Unit}}</span>
													</a>
												</h4>
											</div>

											<div class="panel-collapse collapse" id="collapse{{$index}}" aria-expanded="false" style="height: 0px;">
												<div class="panel-body">
													<div ng-repeat="(key, value) in cat.data2 | groupBy: 'ProductSize'">
													    <p class="count-btn btn-pink" ng-hide="key=='null' || key==''"><b>{{key}}</b></p>
														<!--<button type="button" class="btn btn-pink" ng-hide="key=='null' || key==''"><b>{{key}}</b></button>-->
													<!-- <h4>{{roll.ProductSize}}  <span class="error">Total: {{roll.data3.length}}</span></h4> -->
													<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf">
													<thead>
														<th>S.No <span class="badge badge-pink">{{value.length}}</span></th>
														<th>Roll No</th>
														<th>Qty <span class="badge badge-pink">{{getVolumeSum(value) | number:3}} {{cat.Unit}}</span></th>
														<!-- <th>Remarks</th> -->
														<!-- <th>Print</th> -->
													</thead>
													<tbody >
													<tr  ng-repeat="sp in value">
														<td>{{$index+1}}</td>
														<td><a href="" ng-click="GetRollData(sp.UniqueRollNo,cat.ProductId,sp.InvPkId)">{{sp.UniqueRollNo}}</a></td>
														<td >{{sp.Quantity |  number:3}} {{cat.Unit}} </td>
														<!-- <td ><p class="pre-data">{{sp.Remarks}}</p></td> -->
														<!-- ng-init="roll.data3.sum.Quantity = roll.data3.sum.Quantity + sp.Quantity"
														<td><a target="_blank" href="generate-inv-barcode.php?Id={{sp.InvPkId}}" class="btn btn-xs btn-danger" >Print &nbsp;
															<i class="ace-icon fa fa-print bigger-120"></i>
														</a>&nbsp;</td> -->
													</tr>
													<!-- <tr>
														<td colspan="2"><strong>Total</strong></td>
														<td><strong></strong></td>
														<td>&nbsp;</td>
													</tr> -->
													</tbody>	
													</table>
													</div>
												</div>
											</div>
										</div>

										<!-- <div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false">
														<i class="bigger-110 ace-icon fa fa-angle-right" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
														&nbsp;Group Item #2
													</a>
												</h4>
											</div>

											<div class="panel-collapse collapse" id="collapseTwo" aria-expanded="false" style="height: 0px;">
												<div class="panel-body">
													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.
												</div>
											</div>
										</div> -->
									</div>
										
									</div>	
								</div>
								<div class="well text-center">
								<p>Total Kgs Qty of all items is:  <span id="totalKSum" ng-model="ktsum" ng-show="span1" ng-bind="calculateKgsTotal()"></span>{{ktsum ? ktsum : 0 | number: 3}}</p>
								<p>Total Pcs Qty of all items is:  <span id="totalPSum" ng-model="ptsum" ng-show="span1" ng-bind="calculatePcsTotal()"></span>{{ptsum ? ptsum : 0 | number: 3}}</p>
								<p>Total Qty of all items is:  <span id="totalSum" ng-model="tsum" ng-show="span1" ng-bind="calculateTotal()"></span>{{tsum ? tsum : 0 | number: 3}}</p>
								</div>
									<!-- <table id="dynamic-table" class="table table-bordered table-striped table-condensed cf" >
										<thead class="cf">
											<tr>
												<th>Category Name</th>
												<th>Micron</th>
												<th>Item Name</th>
												<th>Available Qty</th>
												<th>Details</th>
											</tr>
										</thead>										
										<tbody>
											<tr ng-repeat="cat in pagedItems">
												<td data-title="Category Name" >{{cat.CategoryName}}</td>
												<td data-title="Micron/Thick Ness">{{cat.Micron}}</td>
													<td data-title="Item Name">{{cat.ProductName}}
												</td>
												<td data-title="Available Qty">{{cat.AvlQty || 0 | number:3}} {{cat.Unit}}</td>
												<td data-title="Details">
												<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf" ng-if="cat.data2.length>0" >
													<thead>
														<th>Roll No</th>
														<th>Qty</th>
														<th>Print</th>
													</thead>
												<tbody >
												<tr ng-repeat="sp in cat.data2">
													<td>{{sp.UniqueRollNo}}</td>
													<td>{{sp.Quantity}}</td>
													<td><a target="_blank" href="generate-inv-barcode.php?Id={{sp.InvPkId}}" class="btn btn-xs btn-danger" >Print &nbsp;
														<i class="ace-icon fa fa-print bigger-120"></i>
													</a>&nbsp;</td>
												</tr>
												</tbody>	
												</table>
												</td>
											</tr>
										</tbody>
									</table> -->


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
								<div id="error-container">
									<div class="alert alert-danger alert-dismissable" data-ng-show="showWarningAlert">
				                      <button type="button" class="close" data-ng-click="switchBool('showWarningAlert')">×</button><strong>{{WarningAlert}}</strong>
				                    </div>
				                    
				                    <div class="alert alert-success alert-dismissable" data-ng-show="showSuccessAlert">
				                      <button type="button" class="close" data-ng-click="switchBool('showSuccessAlert')">×</button>
				                      <strong>{{SuccessAlert}}</strong>
				                    </div>
				                </div>
								<!-- PAGE CONTENT BEGINS -->
								 <form class="form-horizontal" role="form" id="AddCategoryForm" name="AddCategoryForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="AddCategoryData()">
									<input type="hidden" value="{{FormPkId}}">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Product Id"> Item Id <span class="error">*</span></label>
										
										<div class="col-sm-6">
											<input type="text" id="productid" placeholder="" class="form-control" name="productid" required  data-ng-model="productid" disabled="">
												<div class="error" data-ng-show="submitted || AddCategoryForm.productid.$dirty && AddCategoryForm.productid.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.productid.$error.required">ProductId is required.</small>
												</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="categoryname"> Category <span class="error">*</span></label>
										
										<div class="col-sm-6">
										<!-- <input type="text" id="category" placeholder="" class="form-control"  name="category" required data-ng-model="category"  uib-typeahead="cat as cat.CategoryName for cat in CategoryArr | filter:$viewValue" typeahead-editable="false"> -->

										<select type="text" id="category" placeholder="" class="form-control" name="category" required  autofocus data-ng-model="category" convert-to-number ng-change="GetSubCatgory(category)">
											<option value="">Select</option>
											<option ng-repeat="cat in CategoryArr" value="{{cat.PkId}}">{{cat.CategoryName}}</option>
										</select>
											<div class="error" data-ng-show="submitted || AddCategoryForm.category.$dirty && AddCategoryForm.category.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.category.$error.required">Category is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group" ng-if="SubCategoryArr.length!='0'" style="display: none">
										<label class="col-sm-3 control-label no-padding-right" for="categoryname">Sub Category </label>
										
										<div class="col-sm-6">
										<select type="text" id="subcategory" placeholder="" class="form-control" name="subcategory" data-ng-model="$parent.subcategory" convert-to-number ng-change="GetLevel2(category,subcategory)">
											<option value="">Select</option>
											<option ng-repeat="subcat in SubCategoryArr" value="{{subcat.PkId}}">{{subcat.SubCategoryName}}</option>
										</select>
											<div class="error" data-ng-show="submitted || AddCategoryForm.subcategory.$dirty && AddCategoryForm.subcategory.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.subcategory.$error.required">Sub Category is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group" ng-if="Level2Arr.length!='0'" style="display: none">
										<label class="col-sm-3 control-label no-padding-right" for="Level-2">Level-2 Sub Category </label>
										
										<div class="col-sm-6">
										<select type="text" id="lvl2subcat" placeholder="" class="form-control" name="lvl2subcat" data-ng-model="$parent.lvl2subcat" convert-to-number ng-change="GetLevel3(category,subcategory,lvl2subcat)">
											<option value="">Select</option>
											<option ng-repeat="lvl2 in Level2Arr" value="{{lvl2.PkId}}">{{lvl2.Level2SCName}}</option>
										</select>
											<div class="error" data-ng-show="submitted || AddCategoryForm.lvl2subcat.$dirty && AddCategoryForm.lvl2subcat.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.lvl2subcat.$error.required">Sub Category is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group" ng-if="Level3Arr.length!='0'" style="display: none">
										<label class="col-sm-3 control-label no-padding-right" for="Level-3 ">Level-3 Sub Category </label>
										
										<div class="col-sm-6">
										<select type="text" id="lvl3subcat" placeholder="" class="form-control" name="lvl3subcat" data-ng-model="$parent.lvl3subcat" convert-to-number >
											<option value="">Select</option>
											<option ng-repeat="lvl3 in Level3Arr" value="{{lvl3.PkId}}">{{lvl3.Level3SCName}}</option>
										</select>
											<div class="error" data-ng-show="submitted || AddCategoryForm.lvl3subcat.$dirty && AddCategoryForm.lvl3subcat.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.lvl3subcat.$error.required">Sub Category is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="categoryname"> Item Name <span class="error">*</span></label>
										
										<div class="col-sm-6">
											<input type="text" id="productname" placeholder="" class="form-control" name="productname" required  data-ng-model="productname" data-ng-minlength="2" value="">
											<!-- <small>Item name will be save as (ItemName-Size-Colour) format</small> -->
												<div class="error" data-ng-show="submitted || AddCategoryForm.productname.$dirty && AddCategoryForm.productname.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.productname.$error.required">Item Name is required.</small>
													<small class="error" data-ng-show="AddCategoryForm.productname.$error.minlength">Item Name is required to be at least 2 characters</small>
													<small class="error" data-ng-show="AddCategoryForm.productname.$error.pattern">Item Name should be alphabets ex:abcd</small>
												</div>
										</div>
									</div>

									

									<div class="form-group" style="display: none;">
										<label class="col-sm-3 control-label no-padding-right" for="Choose ">Inventory Type <span class="error">*</span></label>
										<div class="col-sm-6">
											<div class="control-group">
												<div class="radio">
													<label>
														<input name="invtype" ng-model="invtype" type="radio" class="ace" ng-required="!invtype" value="Raw">
														<span class="lbl"> Raw Material </span>
													</label>
												</div>
												<div class="radio">
													<label>
														<input name="invtype" ng-model="invtype" type="radio" class="ace" ng-required="!invtype" value="Finished">
														<span class="lbl"> Finished Goods</span>
													</label>
												</div>
											</div>
											<div class="error" data-ng-show="submitted || AddCategoryForm.invtype.$dirty && AddCategoryForm.invtype.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.invtype.$error.required"> is required.</small>
											</div>
										</div>
									</div>
									<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="product Size"> Micron </label>
									
									<div class="col-sm-6">
										<select type="text"  id="micron" placeholder="" class="form-control" name="micron"  data-ng-model="micron" >
											<option value="">Select</option>
											<!-- <option value="NA">NA</option> -->
											<option ng-repeat="micron in MicronArr" value="{{micron}}">{{micron}}</option>
										</select>
											<div class="error" data-ng-show="submitted || AddCategoryForm.micron.$dirty && AddCategoryForm.micron.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.micron.$error.required">Micron is required.</small>
											</div>
									</div>
									</div>
									<div class="form-group" style="display: none">
									<label class="col-sm-3 control-label no-padding-right" for="product Size"> Size / Width </label>
									
									<div class="col-sm-6">
										<div class="input-group">
										<input type="text"  id="productsize" placeholder="" class="form-control" name="productsize" data-ng-model="productsize" uib-typeahead="size as size.Size for size in SizeArr" number>
										<span class="input-group-addon">MM</span>
											<!-- <option value="">Select</option>
											<option ng-repeat="size in SizeArr" value="{{size.Size}}">{{size.Size}}</option> 
										</select>-->
										</div>
											<div class="error" data-ng-show="submitted || AddCategoryForm.productsize.$dirty && AddCategoryForm.productsize.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.productsize.$error.required">Size  is required.</small>
											</div>
									</div>
									</div>

									<!-- <div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="Colour"> Colour</label>
									
									<div class="col-sm-6">
										<select type="text"  id="colour" placeholder="" class="form-control" name="colour" data-ng-model="colour">
											<option value="">Select</option>
											<option ng-repeat="clr in ColourArr" value="{{clr.ClrName}}">{{clr.ClrName}}</option>
										</select>
											<div class="error" data-ng-show="submitted || AddCategoryForm.colour.$dirty && AddCategoryForm.colour.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.colour.$error.required">Colour  is required.</small>
											</div>
									</div>
									</div>
									<div class="clearfix"></div>
									<div class="form-group">
									  	<label class="col-sm-3 control-label no-padding-right" for="PinCode">Upload Display File </label>
									  	

										<div class="col-sm-6">
										  <input  type="file" id="IPkId" file-model="docfile" accept="image/*" style="display: none;">
										   <button type="button" id="uploadButton" class="btn btn-sm btn-primary text-small" onclick="document.getElementById('IPkId').click();" >
		                                    <i class="fa fa-paperclip" aria-hidden="true"></i>&nbsp;Choose Files..
		                                  </button> &nbsp;

		                                  {{docfile.name}}
		                              	<small class="error"> {{FileErr}}</small>
		                              	</div>
									</div>

									 <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="SKU"> SKU <span class="error">*</span></label>
										
										<div class="col-sm-6">
											<input type="text"  id="sku" placeholder="" class="form-control" name="sku" required=""  data-ng-model="sku" maxlength="100">
										
											<div class="error" data-ng-show="submitted || AddCategoryForm.sku.$dirty && AddCategoryForm.sku.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.sku.$error.required">SKU  is required.</small>
											</div>
										</div>
									</div>
 -->
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Unit"> Unit <span class="error">*</span></label>
										<div class="col-xs-12 col-sm-6">
											<select class="form-control" name="unit" ng-model="unit" required="">
												<option value="">Select</option>
												<option ng-repeat="unit in UnitArr" value="{{unit.UnitName}}">{{unit.UnitName}}</option>
											</select>
												<div class="error" data-ng-show="submitted || AddCategoryForm.unit.$dirty && AddCategoryForm.unit.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.unit.$error.required">Unit  is required.</small>
												</div>
										</div>
									</div>

									<!-- <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="unit"> Item Cost Price <span class="error">*</span></label>
										<div class="col-sm-6">
											<input type="text" id="costprice" placeholder="" class="form-control" name="costprice" data-ng-model="costprice" maxlength="8" required="" class="form-control" valid-number="" allow-decimal="true" allow-negative="false">
												<div class="error" data-ng-show="submitted || AddCategoryForm.costprice.$dirty && AddCategoryForm.costprice.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.costprice.$error.required">Item Cost Price is required.</small>
												</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="unit"> Item Selling Price <span class="error">*</span></label>
										<div class="col-sm-6">
											<input type="text" id="salesprice" placeholder="" class="form-control" name="salesprice" data-ng-model="salesprice" maxlength="8" required="" class="form-control" valid-number="" allow-decimal="true" allow-negative="false">
												<div class="error" data-ng-show="submitted || AddCategoryForm.salesprice.$dirty && AddCategoryForm.salesprice.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.salesprice.$error.required">Item Selling Price is required.</small>
												</div>
										</div>
									</div>

									

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Unit"> &nbsp;</label>
										<div class="col-xs-12 col-sm-5">
											<div class="control-group">
												<div class="checkbox">
													<label>
														<input name="form-field-checkbox" type="checkbox" name="isreturn" ng-model="isreturn" class="ace">&nbsp;
														<span class="lbl"> Returnable Item</span>
													</label>
													<span class="help-button" data-rel="popover" data-trigger="hover" data-placement="left" data-content="More details." title="Enable this option if the item eligible for sale retutn">?</span>
												</div>

											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Dimension"> Dimension (cm)</label>

										<div class="col-sm-6">
											<input type="text" placeholder="" class="form-control" name="dimension"  data-ng-model="dimension" maxlength="100">
												<div class="clearfix"></div>
											<small class="help-block">(Length X Width X Height)</small>
										
											<div class="error" data-ng-show="submitted || AddCategoryForm.dimension.$dirty && AddCategoryForm.dimension.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.dimension.$error.required">SKU  is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Weight"> Weight (kg)</label>

										<div class="col-sm-6">
											<input type="text" placeholder="" class="form-control" name="weight" data-ng-model="weight" maxlength="100">
										
											<div class="error" data-ng-show="submitted || AddCategoryForm.weight.$dirty && AddCategoryForm.weight.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.weight.$error.required">Weight  is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Manufacturer"> Manufacturer </label>
										<div class="col-xs-12 col-sm-6">
											<select class="form-control" name="manfacture" ng-model="manfacture">
												<option value="">Select</option>
												<option ng-repeat="mfc in MfgArr" value="{{mfc.MfcName}}">{{mfc.MfcName}}</option>
											</select>
												<div class="error" data-ng-show="submitted || AddCategoryForm.manfacture.$dirty && AddCategoryForm.manfacture.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.manfacture.$error.required">Manufacturer  is required.</small>
												</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Brand"> Brand </label>
										<div class="col-xs-12 col-sm-6">
											<select  placeholder="" class="form-control" name="brand" maxlength="100" data-ng-model="brand">
											<option value="">Select</option>
											<option ng-repeat="bnd in BrandArr" value="{{bnd.BrandName}}">{{bnd.BrandName}}</option>
											</select>
											<div class="error" data-ng-show="submitted || AddCategoryForm.brand.$dirty && AddCategoryForm.brand.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.brand.$error.required">Brand  is required.</small>
											</div>
										</div>
									</div>


									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="MPN"> MPN (Manufacturer Part Number)</label>

										<div class="col-sm-6">
											<input type="text" placeholder="" class="form-control" name="mpn"  data-ng-model="mpn" maxlength="100">
											<div class="error" data-ng-show="submitted || AddCategoryForm.mpn.$dirty && AddCategoryForm.mpn.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.mpn.$error.required">MPN  is required.</small>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="UPC"> UPC (Universal Product Code)</label>

										<div class="col-sm-6">
											<input type="text" placeholder="" class="form-control" name="upc"  data-ng-model="upc" maxlength="100">
											<div class="error" data-ng-show="submitted || AddCategoryForm.upc.$dirty && AddCategoryForm.upc.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.upc.$error.required">UPC  is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="ISBN"> ISBN (International Standard Book Number)</label>

										<div class="col-sm-6">
											<input type="text" placeholder="" class="form-control" name="isbn"  data-ng-model="isbn" maxlength="100">
											<div class="error" data-ng-show="submitted || AddCategoryForm.isbn.$dirty && AddCategoryForm.isbn.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.isbn.$error.required">ISBN  is required.</small>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="EAN"> EAN (European Article Number)</label>

										<div class="col-sm-6">
											<input type="text" placeholder="" class="form-control" name="ean"  data-ng-model="ean" maxlength="100">
											<div class="error" data-ng-show="submitted || AddCategoryForm.ean.$dirty && AddCategoryForm.ean.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.ean.$error.required">EAN  is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group" ng-if="invtype=='Finished'">
										<label class="col-sm-3 control-label no-padding-right" for="Opening Stock"> Opening Stock <span class="error">*</span></label>
										<div class="col-sm-6">
											<input type="text" id="openstock" placeholder="" class="form-control" name="openstock" data-ng-model="$parent.openstock" maxlength="10" required="" class="form-control" number>
												<div class="error" data-ng-show="submitted || AddCategoryForm.openstock.$dirty && AddCategoryForm.openstock.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.openstock.$error.required">Opening Stock is required.</small>
												</div>
										</div>
									</div>
 -->
									<!--<div class="form-group" ng-if="invtype=='Raw'">
										<label class="col-sm-3 control-label no-padding-right" for="Opening Weight"> Opening Weight <span class="error">*</span></label>
										<div class="col-sm-6">
											<input type="text" id="openweight" placeholder="" class="form-control" name="openweight" data-ng-model="$parent.openweight" maxlength="10" required="" class="form-control" valid-number="" allow-decimal="true" allow-negative="false" decimal-upto="3">
												<div class="error" data-ng-show="submitted || AddCategoryForm.openweight.$dirty && AddCategoryForm.openweight.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.openweight.$error.required">Opening Weight is required.</small>
												</div>
										</div>
									</div>

									 <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="unit">Opening Stock Rate per unit <span class="error">*</span></label>
										<div class="col-sm-6">
											<input type="text" id="stockrate" placeholder="" class="form-control" name="stockrate" data-ng-model="stockrate" maxlength="8" valid-number="" allow-decimal="false" allow-negative="false" >
												<div class="error" data-ng-show="submitted || AddCategoryForm.stockrate.$dirty &amp;&amp; AddCategoryForm.stockrate.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.stockrate.$error.required">Opening Stock Rate per unit is required.</small>
												</div>
										</div>
									</div> -->

									<div class="form-group" style="display: none;">
										<label class="col-sm-3 control-label no-padding-right" for="Reorder Level"> Reorder Level </label>
										<div class="col-sm-6">
											<input type="text" id="reorderlevel" placeholder=""  name="reorderlevel" data-ng-model="reorderlevel"  maxlength="10" required="" class="form-control" valid-number="" allow-decimal="true" allow-negative="false" decimal-upto="3">
												<div class="error" data-ng-show="submitted || AddCategoryForm.reorderlevel.$dirty && AddCategoryForm.reorderlevel.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.reorderlevel.$error.required">Reorder level is required.</small>
												</div>
										</div>
									</div>

									<!-- <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Preferred Vendor"> Preferred Vendor </label>
										<div class="col-sm-6">
											<input type="hidden" ng-model="VendorId">
											<input type="text" id="vendorname" placeholder="" class="form-control" name="vendorname" data-ng-model="vendorname"  uib-typeahead="vndr as vndr.DisplayName for vndr in VednorArray | filter:$viewValue" typeahead-editable="false" typeahead-on-select="onVendorSelect($item, $model, $label)">
												<div class="error" data-ng-show="submitted || AddCategoryForm.vendorname.$dirty && AddCategoryForm.vendorname.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.vendorname.$error.required">Vendor is required.</small>
												</div>
										</div>
									</div> -->

									<div class="form-group" style="display: none">
										<label class="col-sm-3 control-label no-padding-right" for="Choose ">Choose Inventory Tracking <span class="error">*</span></label>
										<div class="col-sm-6">
											<div class="control-group">
												<div class="radio">
													<label>
														<input name="invtrack" ng-model="invtrack" type="radio" class="ace" ng-required="!invtrack" value="None">
														<span class="lbl"> None</span>
													</label>
												</div>
												<!-- <div class="radio">
													<label>
														<input name="invtrack" ng-model="invtrack" type="radio" class="ace" ng-required="!invtrack" value="Serial">
														<span class="lbl"> Track Serial Number</span>
													</label>
												</div>
												<div class="radio">
													<label>
														<input name="invtrack" ng-model="invtrack" type="radio" class="ace" ng-required="!invtrack" value="Batch"> 
														<span class="lbl"> Track Batches</span>
													</label>
												</div> -->
											</div>
											<div class="error" data-ng-show="submitted || AddCategoryForm.invtrack.$dirty && AddCategoryForm.invtrack.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.invtrack.$error.required"> is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group" ng-if="invtrack=='Serial' && openstock!=null">
										<label class="col-sm-3 control-label no-padding-right" for="Serial Numbers">Enter the serial numbers for your Opening Stock </label>
										<div class="col-sm-6">
											<textarea type="text" id="serailnumber" placeholder="" class="form-control" rows="10" name="serailnumber" data-ng-model="$parent.serailnumber" class="form-control" required=""  ng-trim="false" restrict-field="$parent.serailnumber"></textarea>
											<div class="clearfix">&nbsp;</div>
											<small>Type (comma separated) values. Ex:12,25</small>
												<div class="error" data-ng-show="submitted || AddCategoryForm.serailnumber.$dirty && AddCategoryForm.serailnumber.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.serailnumber.$error.required">Enter Serial Numbers is required.</small>
												</div>
										</div>
									</div>
									<div class="clearfix">&nbsp;</div>
									
									<div id="" ng-if="invtrack=='Batch' && openstock!=null" style="overflow: auto">
									<table class="table table-striped table-bordered" >
									<p >Total Quantity : {{openstock}}</p>
									<p >Quantity to be added : 
										<span ng-if="sum>openstock">0</span>
										<span ng-if="sum<=openstock">{{openstock-sum}}</span>
									</p>
									<thead>
										<tr>
											<th>BATCH REFERENCE</th>
											<th>MANUFACTURER BATCH</th>
											<th>MANUFACTURED DATE</th>
											<th>EXPIRY  DATE</th>
											<th>QUANTITY IN</th>
											<th>ACTION</th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="bat in BatchArr">
											<td data-title="BATCH REFERENCE" class="center">
											<div class="form-group">
												<div class="col-sm-12">
												<input type="text" id="batchno" placeholder="" class="form-control" name="batchno{{$index}}" data-ng-model="bat.batchno" required="">
													<div class="error" data-ng-show="submitted || AddCategoryForm.batchno{{$index}}.$dirty && AddCategoryForm.batchno{{$index}}.$invalid">
														<small class="error" data-ng-show="AddCategoryForm.batchno{{$index}}.$error.required">Batch No is required.</small>
													</div>
												</div>
											</div>
											</td>
											<td data-title="MANUFACTURER BATCH"  class="center">
												<div class="form-group">
												<div class="col-sm-12">
												<input type="text" id="manfacturebatch" placeholder="" class="form-control" name="manfacturebatch{{$index}}" data-ng-model="bat.manfacturebatch" >
													<div class="error" data-ng-show="submitted || AddCategoryForm.manfacturebatch{{$index}}.$dirty && AddCategoryForm.manfacturebatch{{$index}}.$invalid">
														<small class="error" data-ng-show="AddCategoryForm.manfacturebatch{{$index}}.$error.required">Manfacturer Batch is required.</small>
													</div>
												</div>
												</div>
											</td>
											<td data-title="MANUFACTURED DATE"  class="center">
											<div class="form-group">
												<div class="col-sm-12">
												<input type="text" class="form-control" name="mfgdate{{$index}}"  placeholder="" id="mfgdate" ng-model="bat.mfgdate" uib-datepicker-popup="dd-MMM-yyyy" is-open="bat.opened" ng-click="open($event,bat)"  datepicker-options="assDate1" > 
													<div class="error" data-ng-show="submitted || AddCategoryForm.mfgdate{{$index}}.$dirty && AddCategoryForm.mfgdate{{$index}}.$invalid">
														<small class="error" data-ng-show="AddCategoryForm.mfgdate{{$index}}.$error.required">Manfacture date is required.</small>
													</div>
												</div>
											</div>
											</td>
											<td data-title="EXPIRY DATE"  class="center">
												<div class="form-group">
													<div class="col-sm-12">
													<input type="text" class="form-control" name="expdate{{$index}}"  placeholder=""  id="expdate" ng-model="bat.expdate" uib-datepicker-popup="dd-MMM-yyyy" is-open="bat.opened1" ng-click="open1($event,bat)"  datepicker-options="assDate1" > 
														<div class="error" data-ng-show="submitted || AddCategoryForm.expdate{{$index}}.$dirty && AddCategoryForm.expdate{{$index}}.$invalid">
															<small class="error" data-ng-show="AddCategoryForm.expdate{{$index}}.$error.required">Expire date is required.</small>
														</div>
													</div>
												</div>
											</td>
											<td data-title="QUANTITY IN" class="center">
												<div class="form-group">
												<div class="col-sm-12">
												<input type="text" id="quantity" placeholder="" class="form-control" name="quantity{{$index}}" data-ng-model="bat.quantity" maxlength="10" required="" class="form-control" number ng-change="calculateSum()">
													<div class="error" data-ng-show="submitted || AddCategoryForm.quantity{{$index}}.$dirty && AddCategoryForm.quantity{{$index}}.$invalid">
														<small class="error" data-ng-show="AddCategoryForm.quantity{{$index}}.$error.required">QUANTITY is required.</small>
													</div>
												</div>
												</div>
											</td>
											<td data-title="ACTION" >
												<!-- <a class="orange" href="#modal-form" data-toggle="modal" ng-click="EditArr(bat.BatchNo,bat.ManfcBatch,bat.Mfgdate,bat.Expdate,bat.Quantity,$index)">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a> -->

												<a class="red" href="" data-ng-click="removeRow($index)">
													<i class="ace-icon fa fa-trash bigger-130"></i>
												</a>
											</td>
										</tr>
										<tr>
                                		<td colspan="4" align="right"><strong>Total Quantity</strong></td>
										<td >
											<span id="totalSum" ng-model="sum" ng-show="span1" ng-bind="calculateSum()"></span>{{sum ? sum : 0}}</td>

										<td>&nbsp;</td>
										</tr>
										<tr>
											<td class=""><button type="button" class="btn btn-sm btn-block btn-info" ng-click="AddMore()">Add More</button></td>
											<td class="" colspan="5">
											
											</td>	
										</tr>
									</tbody>

								</table>
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
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!--Add lising-->

                       <div id="myModal" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
						<div class="modal-dialog  modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<div class="col-xs-12">
									<div class="pull-left">
										<h4>Roll Details</h4>
									</div>
									<div class="pull-right">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
									</div>
									</div>
								</div>
								<div class="modal-body">
								<div class="row">
								<div class="col-xs-12">
									<!-- PAGE CONTENT BEGINS -->
									<div class="card">
										<div class="space-6"></div>

									<div class="card-body" ng-if="result=='OK'">
										<h4>{{TotalName}} {{InvProductSize}} MM</h4>
										<p>{{InvUniqueRollNo}}</p>
										<p><strong>Remarks:</strong> <span ng-repeat="rem in stkremarks">{{rem.Remarks}}</span><br></p>
										<h4>Avl Qty: {{AvlQuantity}} {{UnitModal}}</h4>
										<ul>
											<li><span class="text-danger">{{TotalName}} {{POProductSize}} MM ( {{PurchaseRollNo}} ) </span> Purchased from the <b> {{VendorNameModal}}</b> vendor on <b>{{RawPODateModal | date: 'd MMM y'}}</b></li>
											 <li>Purchase Weight/Qty is <b>{{PurchaseQtyModal}} {{UnitModal}}</b></li>
											 <!-- <li>Stock received at <b>{{GoDownName}}</b></li> -->
												

											<li ng-if="IsSlitted=='1'"><span class="text-success">{{TotalName}} {{InvProductSize}} MM ( {{InvUniqueRollNo}} ) </span> has been slit from <span class="text-danger"> {{TotalName}} {{POProductSize}} MM </span> on <b>{{SlitDate | date: 'd MMM y'}} </b></li>	

											<li ng-if="IsSlitted=='1'">Slit Qty is <b>{{SlitQty}} {{UnitModal}}</b></li>

											<li ng-if="IsSplitQty=='1'">
												<p>Roll has slitted into:</p>
												<div ng-repeat="slit in data2">
													<p><b>{{TotalName}} {{slit.SplitSize}} MM</b> Weight/Qty Is <b>{{slit.SlitQty}} {{UnitModal}}</b></p>
												</div>
											</li>
											

											<li ng-if="IsInvoice=='1'">
												Roll has been dispatched to <span class="text-success">{{CustomerNameModal}}</span> on <b>{{InvoiceDate | date: 'd MMM y'}} </b></li>

											
											<!-- <li ng-if="IsSlitted>'1'"><span class="text-success">{{TotalProductName}} {{ProductSize}} MM ( {{UniqueRollNo}} ) </span> Sliited From <span class="text-danger"> {{TotalName}} {{POProductSize}} MM </span> on <b>{{SlitDate | date: 'd MMM y'}} </b></li> -->
										</ul>
										<!-- <div class="row">
											<div class="col-xs-6">
												<div id="dynamic-table_filter" class="dataTables_filter pull-left"><label>Search:</label>
													<input type="text" ng-model="search" class="form-control" placeholder="Search"> &nbsp; Showing {{mypageno*itemsPerPage-itemsPerPage+1}}-{{mypageno*itemsPerPage}} of {{total_count}}
												</div>
											</div>
											<div class="col-xs-6">
											<a href="datewise-bill-report.php?FromDate={{fromdate | date}}&ToDate={{todate | date}}" target="_self" class="btn btn-success" > Download</a>
											</div>
										</div> -->
										<!-- <table id="dynamic-table" class="table table-bordered table-striped table-condensed cf"  datatable="ng" dt-options="vm.dtOptions" dt-column-defs="vm.dtColumnDefs">
											<thead class="cf">
												<tr>
													<th>Item Name</th>
													<th>Roll No</th>
													<th>Qty</th>
												</tr>
											</thead>										
											<tbody>
												<tr ng-repeat="cat in pagedItems">
												<td data-title="Item Name">{{cat.TotalProductName}} {{cat.ProductSize}} MM</td>
												<td data-title="Roll No">
													<a href="" data-toggle="modal" data-target="#modal-form" ng-click="GetProductData(cat.TotalProductName,cat.ProductSize,cat.UniqueRollNo,cat.Unit)"> {{cat.UniqueRollNo}}
													</a>
												</td>
												<td data-title="Qty">{{cat.Quantity}} {{cat.Unit}}</td>
												</tr>
												
											</tbody>
										</table> -->
									</div>
									</div><!-- /.row -->

									<!-- PAGE CONTENT ENDS -->
								</div><!-- /.col -->
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
		<!-- page specific plugin scripts -->
		<script src="../../assets/js/jquery.without-dd-nestable.min.js"></script>
		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($){
			
				$('.dd').nestable();
			
				$('.dd-handle a').on('mousedown', function(e){
					e.stopPropagation();
				});
				
				$('[data-rel="tooltip"]').tooltip();
			
			});
		</script>

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
