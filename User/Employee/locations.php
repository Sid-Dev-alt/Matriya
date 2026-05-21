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
    	<script src="angularjs/location-script.js"></script>
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
							<li class=""><a href="#">Masters</a></li>
							<li class="active">Locations</li>
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
		                  	 	<div class="col-xs-12 col-sm-12 col-lg-6">
				                    <a href="" data-ng-click="GotoAdd()" ng-show="FormList">
									<div class="label label-lg label-success arrowed-in arrowed-right pull-right" >
									<b>Add Location</b>
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
												<th>Sl.No</th>
												<th>Location Type</th>
												<th>Location Name</th>
												<th>Action</th>
											</tr>
										</thead>										
										<tbody >
											<tr  ng-repeat="cat in pagedItems">
												<td data-title="Sl.No">{{$index+1}}</td>
												<td data-title="Location Type">{{cat.TypeName}}</td>
												<td data-title="Location Name">{{cat.LocationName}}</td>
												<td data-title="Action">
													<button type="button" class="btn btn-xs btn-info" data-ng-click="EditCat(cat.PkId,cat.PkId_LocationType,cat.LocationName)">Edit &nbsp;
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
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								 <form class="form-horizontal" role="form" id="AddCategoryForm" name="AddCategoryForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="AddCategoryData()">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="categoryname"> Location Type <span class="error">*</span></label>
										<input type="hidden" value="{{FormPkId}}">
										<div class="col-sm-9">
											<select id="locationtype" placeholder="" class="col-xs-10 col-sm-5" name="locationtype" required  autofocus data-ng-model="locationtype" convert-to-number>
												<option value="">Select</option>
												<option ng-repeat="cat in CategoryArr" value="{{cat.PkId}}">{{cat.Name}}</option>
											</select>
												<div class="error" data-ng-show="submitted || AddCategoryForm.locationtype.$dirty && AddCategoryForm.locationtype.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.locationtype.$error.required">required.</small>
												</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Name">Location Name <span class="error">*</span></label>
										<div class="col-sm-9">
											<input type="text" id="locationname" placeholder="" class="col-xs-10 col-sm-5" name="locationname" required  data-ng-model="locationname" data-ng-minlength="3" >
												<div class="error" data-ng-show="submitted || AddCategoryForm.locationname.$dirty && AddCategoryForm.locationname.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.locationname.$error.required">Location Name is required.</small>
													<small class="error" data-ng-show="AddCategoryForm.locationname.$error.minlength">Location Name is required to be at least 3 characters</small>
													<small class="error" data-ng-show="AddCategoryForm.locationname.$error.pattern">Location Name should be alphabets ex:abcd</small>
													
												</div>
												<small ng-if="NameExists" class="error">Already Exists</small> 
										</div>
									</div>

									<!-- <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Category Description"> Category Description </label>
										<div class="col-sm-9">
											<textarea type="text" id="catdescription" placeholder="" class="col-xs-10 col-sm-5" name="catdescription" data-ng-model="catdescription" maxlength="200"></textarea>
												<div class="error" data-ng-show="submitted || AddCategoryForm.catdescription.$dirty && AddCategoryForm.catdescription.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.catdescription.$error.required">Category Description is required.</small>
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