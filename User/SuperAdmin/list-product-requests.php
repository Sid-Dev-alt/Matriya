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
		<link rel="stylesheet" href="../../assets/css/colorbox.min.css" />
    	<script src="angularjs/list-product-script.js"></script>
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
							<li class=""><a href="#">Catelogue</a></li>
							<li class="active">List of Requests </li>
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
			              </div>							
						</div>

							<div class="row" ng-show="FormList">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="space-6"></div>

								<div class="dataTables_wrapper form-inline no-footer">
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
												<th>Category Name</th>
												<th>Product Name</th>
												<th>Product Description</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>										
										<tbody >
											<tr  ng-repeat="cat in pagedItems">
												<!-- <td data-title="Sl.No">{{$index+1}}</td> -->
												<td data-title="Category Name">
													<input type="hidden" value="{{cat.UserId_Users}}">
												{{cat.CategoryName}}</td>
												<td data-title="Product Type Name">{{cat.ProductName}}
													<ul class="ace-thumbnails clearfix">
														<li ng-if="cat.FileName!=null">
															<img width="150" height="150" alt="150x150" ng-src="../ProductImages/{{cat.FileName}}">
														</li>

													</ul>
												</td>
												<td>{{cat.ProductDescription}}</td>
												<td>{{cat.displaystatus}}</td>
												<td data-title="Action">
													<button  ng-if="cat.ApproveStatus=='0'" type="button" class="btn btn-xs btn-info" data-ng-click="EditCat(cat.PkId,cat.UserId_Users,cat.ProductName,cat.ProductDescription,cat.PkId_Category,cat.CategoryName,cat.FileName,cat.UploadFile1,cat.UploadFile2,cat.UploadFile3,cat.UploadFile4,cat.UploadFile5,cat.Status)">Edit &nbsp;
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


					<div ng-show="FormAdd" id="no-more-tables">

						<div class="row">
							<div class="col-xs-12">
								<div class="search-area well col-xs-12">
								<div class="pull-left">
									<b class="text-primary">The product you are adding may already exist on SCART </b><a href="list-master-data.php" target="_blank" class="btn btn-sm btn-yellow">FIND IT IN SCART</a>
								</div>
							</div>
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								 <form class="form-horizontal" role="form" id="AddCategoryForm" name="AddCategoryForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="AddCategoryData()">
									<input type="hidden" value="{{FormPkId}}">
									<input type="hidden" value="{{UserId_Users}}">
									
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="categoryname"> Category <span class="error">*</span></label>
										
										<div class="col-sm-9">
											<input type="hidden" value="{{PkId_Category}}">
										<!-- <input type="text" id="category" placeholder="" class="col-xs-10 col-sm-5"  name="category" required data-ng-model="category"  uib-typeahead="cat as cat.CategoryName for cat in CategoryArr | filter:$viewValue" typeahead-editable="false"> -->

										<select type="text" id="category" placeholder="" class="col-xs-10 col-sm-5" name="category" required  autofocus data-ng-model="category" convert-to-number disabled="">
											<option value="">Select</option>
											<option ng-repeat="cat in CategoryArr" value="{{cat.PkId}}">{{cat.CategoryName}}</option>
										</select>
											<div class="error" data-ng-show="submitted || AddCategoryForm.category.$dirty && AddCategoryForm.category.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.category.$error.required">Category is required.</small>
											</div>
										</div>
									</div>

									

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="categoryname"> Product Name <span class="error">*</span></label>
										
										<div class="col-sm-9">
											<input type="text" id="protype" placeholder="" class="col-xs-10 col-sm-5" name="protype" required  data-ng-model="protype" data-ng-minlength="3" disabled="">
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
										
										<div class="col-sm-9">
											<textarea  id="prodescription" placeholder="" class="col-xs-10 col-sm-5" name="prodescription"   data-ng-model="prodescription" maxlength="300" readonly=""></textarea>
												<div class="error" data-ng-show="submitted || AddCategoryForm.prodescription.$dirty && AddCategoryForm.prodescription.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.prodescription.$error.required">Product  is required.</small>
												</div>
										</div>
									</div>

									<div class="form-group"  ng-if="FileName!=null">
									  	<label class="col-sm-3 control-label no-padding-right" for="PinCode">
									  		View File
									  	</label>
									  	<div class="col-sm-9">
									  		<ul class="ace-thumbnails clearfix">
												<li ng-if="FileName!=null">
													<img width="150" height="150" alt="150x150" ng-src="../ProductImages/{{FileName}}">
												</li>

											</ul>
											<ul class="ace-thumbnails clearfix">
												<li ng-if="UploadFile1!=null && UploadFile1!=''">
													<img width="150" height="150" alt="150x150" ng-src="../ProductImages/{{UploadFile1}}">
												</li>
												<li ng-if="UploadFile2!=null && UploadFile2!=''">
													<img width="150" height="150" alt="150x150" ng-src="../ProductImages/{{UploadFile2}}">
												</li>
												<li ng-if="UploadFile3!=null && UploadFile3!=''">
													<img width="150" height="150" alt="150x150" ng-src="../ProductImages/{{UploadFile3}}">
												</li>
												<li ng-if="UploadFile4!=null && UploadFile4!=''">
													<img width="150" height="150" alt="150x150" ng-src="../ProductImages/{{UploadFile4}}">
												</li>
												<li ng-if="UploadFile5!=null && UploadFile5!=''">
													<img width="150" height="150" alt="150x150" ng-src="../ProductImages/{{UploadFile5}}">
												</li>

											</ul>
									  	</div>
									  </div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="PinCode">
									  	</label>
									  	<div class="col-sm-6">
											<div class="control-group">
												<label class="control-label bolder blue">Action</label>
												<div class="radio">
													<label>
														<input name="aprvtype" type="radio" class="ace input-lg" ng-model="aprvtype" value="1" ng-required="!aprvtype">
														<span class="lbl bigger-120"> Generate Id & Approve Request</span>
													</label>
												</div>

												<div class="radio">
													<label>
														<input  type="radio" class="ace input-lg" name="aprvtype" ng-model="aprvtype" value="0" ng-required="!aprvtype">
														<span class="lbl bigger-120"> Already Exist</span>
													</label>
												</div>
												<div class="error" data-ng-show="submitted || AddCategoryForm.aprvtype.$dirty && AddCategoryForm.aprvtype.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.aprvtype.$error.required">required.</small>
												</div>
											</div>
										</div>
									</div>

									<div class="form-group"  ng-if="aprvtype=='1'">
										<label class="col-sm-3 control-label no-padding-right" for="Product Id"> Product Id <span class="error">*</span></label>
										
										<div class="col-sm-9">
											<input type="text" id="productid" placeholder="" class="col-xs-10 col-sm-5" name="productid" required  data-ng-model="$parent.productid" disabled="">
												<div class="error" data-ng-show="submitted || AddCategoryForm.productid.$dirty && AddCategoryForm.productid.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.productid.$error.required">ProductId is required.</small>
												</div>
										</div>
									</div>

									<div class="form-group" ng-if="aprvtype=='0'">
										<label class="col-sm-3 control-label no-padding-right" for="product Description">Enter Product Id & Name <span class="error">*</span></label>
										
										<div class="col-sm-9">
											<textarea  id="comments" placeholder="" class="col-xs-10 col-sm-5" name="comments"   data-ng-model="$parent.comments" maxlength="300" required="" ></textarea>
												<div class="error" data-ng-show="submitted || AddCategoryForm.comments.$dirty && AddCategoryForm.comments.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.comments.$error.required"> required.</small>
												</div>
										</div>
									</div>

									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="submit" data-ng-disabled="FormValid">
												<i class="ace-icon fa fa-check bigger-110"></i>
												SAVE
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