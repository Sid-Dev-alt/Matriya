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
    	<script src="angularjs/brand-script.js"></script>
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="CategoryModule" data-ng-controller="CategoryController" data-ng-init="GetListData()">
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
							<li class="active">Brands</li>
						</ul>
					</div> -->

					<div class="page-content" >
					<div  id="no-more-tables">
						<div class="row">
							<div class="col-xs-12 page-header">
		                  	 <div class="col-xs-8 col-sm-8 col-lg-9">
		                  	 	<div class="" >
									<h1>{{pagetitle}}</h1>
									</div>
		<!-- /.page-header --></div>
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
									<!-- <div class="row">
										<div class="col-xs-6">
											<div id="dynamic-table_filter" class="dataTables_filter pull-left"><label>Search:
												<input type="search" class="form-control input-lg" placeholder="" aria-controls="dynamic-table"  data-ng-model="query[queryBy]"></label>
											</div>
										</div>
									</div> -->
									<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf"  datatable="ng" dt-options="vm.dtOptions" dt-column-defs="vm.dtColumnDefs">
										<thead class="cf">
											<tr>
												<th  style="display: none">Sl.No</th>
												<th>Brand Name</th>
												<th>Action</th>
											</tr>
										</thead>										
										<tbody >
											<tr  ng-repeat="cat in pagedItems">
												<td data-title="Sl.No" style="display: none">{{cat.PkId}}</td>
												<td data-title="Brand Name">{{cat.BrandName}}</td>
												<td data-title="Action">
													<button type="button" class="btn btn-xs btn-info" data-ng-click="EditCat(cat.PkId,cat.BrandName)">Edit &nbsp;
														<i class="ace-icon fa fa-pencil bigger-120"></i>
													</button>&nbsp;
													<button type="button" class="btn btn-xs btn-danger" data-ng-click="Delete(cat.PkId)">Delete &nbsp;
														<i class="ace-icon fa fa-trash bigger-120"></i>
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
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								 <form class="form-horizontal" role="form" id="AddCategoryForm" name="AddCategoryForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="AddCategoryData()">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="categoryname"> Brand Name <span class="error">*</span></label>
										<input type="hidden" value="{{FormPkId}}">
										<div class="col-sm-9">
											<input type="text" autofocus="" id="brand" placeholder="" class="col-xs-10 col-sm-5" name="brand" required   data-ng-model="brand" data-ng-minlength="2" ng-change="checkCatgory(FormPkId,brand)">
												<div class="error" data-ng-show="submitted || AddCategoryForm.brand.$dirty && AddCategoryForm.brand.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.brand.$error.required">Brand is required.</small>
													<small class="error" data-ng-show="AddCategoryForm.brand.$error.minlength">Brand is required to be at least 2 characters</small>
													<small class="error" data-ng-show="AddCategoryForm.brand.$error.pattern">Brand should be alphabets ex:abcd</small>
													
												</div>
												<small ng-if="NameExists" class="error">Already Exists</small> 
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