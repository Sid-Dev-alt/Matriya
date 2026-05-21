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
    	<script src="angularjs/sub-category-level3-script.js"></script>
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="CategoryModule" data-ng-controller="CategoryController" data-ng-init="GetList()">
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
							<li class="active">Sub Categories</li>
						</ul>
					</div> -->

					<div class="page-content" >
					<div  id="no-more-tables">
						<div class="row">
							<div class="col-xs-12 page-header">
		                  	 <div class="col-xs-12 col-sm-12 col-lg-6">
		                  	 	<div class="" >
									<h1>{{pagetitle}}</h1>
									</div>
		<!-- /.page-header --></div>
		                  	 	<!-- <div class="col-xs-12 col-sm-12 col-lg-6">
				                    <a href="" data-ng-click="GotoAdd()" ng-show="FormList">
									<div class="label label-lg label-success arrowed-in arrowed-right pull-right" >
									<b>Add Level-2 Sub category</b>
									</div></a>
			                  </div> -->
			              </div>							
						</div>

							<div class="row" ng-show="FormList">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="col-sm-12">
										<div class="col-md-3 hidden-sm  hidden-xs">&nbsp;</div>
										<div class="col-sm-6">
										<div class="dd" id="nestable">
											<ol class="dd-list">
												<li class="dd-item " ng-class="{'dd-collapsed' : $index!='0'}" data-id="{{cat.PkId}}" ng-repeat="cat in Levle3Arr">
													<button class="white" data-action="collapse" type="button" style="display: none;"  ng-style="$index=='0' && {'display': 'block'}">Collapse</button>
													<button class="white" data-action="expand" type="button" style="display: block;" ng-style="$index=='0' && {'display': 'none'}">Expand</button>
												<div class="dd-handle  btn-info no-hover" >
												{{cat.CategoryName}}
												</div>
													<ol class="dd-list" >
														<li class="dd-item" ng-repeat="subcat in cat.data2"  data-id="{{subcat.SubPkId}}">
															<button class="black" data-action="collapse" type="button" style="display: none;"  ng-style="$parent.$index=='0' && {'display': 'block'}">Collapse</button>
															<button class="black" data-action="expand" type="button" style="display: block;" ng-style="$parent.$index=='0' && {'display': 'none'}">Expand</button>
														<div class="dd-handle btn-default no-hover">{{subcat.SubCategoryName}}
														</div>
															<ol class="dd-list" >
															<li class="dd-item item-red" ng-repeat="lvl2 in subcat.data3"  data-id="{{lvl2.Level2PkId}}" >
																<button class="black" data-action="collapse" type="button" style="display: none;"  ng-style="$parent.$parent.$index=='0' && {'display': 'block'}">Collapse</button>
																<button class="black" data-action="expand" type="button" style="display: block;" ng-style="$parent.$parent.$index=='0' && {'display': 'none'}">Expand</button>
																<div class="dd-handle btn-light">{{lvl2.Level2SCName}}
																	<div class="pull-right action-buttons">
																		<a class="red" href="" data-ng-click="AddLevel3Cat(cat.PkId,cat.CategoryName,subcat.SubPkId,subcat.SubCategoryName,lvl2.Level2PkId,lvl2.Level2SCName)">
																			<div class="label label-lg label-inverse arrowed-in arrowed-right pull-right">
																			<b>Add Level-3 Sub category</b>
																			</div>
																		</a>
																	</div>
																</div>
																<ol class="dd-list" >
																	<li class="dd-item item-red" ng-repeat="lvl3 in lvl2.data4"  data-id="{{lvl3.Level3PkId}}" >
																		<a class="red" href="" data-ng-click="EditLevel3Cat(lvl3.Level3PkId,lvl3.Level3SCName,lvl2.Level2PkId,lvl2.Level2SCName,cat.PkId,cat.CategoryName,subcat.SubPkId,subcat.SubCategoryName)">
																		<div class="dd-handle">
																			<i class="primary ace-icon fa fa-pencil bigger-130"></i>&nbsp;&nbsp;
																			{{lvl3.Level3SCName}}
																			<div class="pull-right action-buttons">
																					<i class="red ace-icon fa fa-pencil bigger-130"></i>
																			</div>
																		</div>
																		</a>
																			
																		<div class="dd-handle dd2-handle">
																		<a href="" ng-click="Delete(lvl3.Level3PkId)" title="Delete"><i class="normal-icon ace-icon fa fa-trash red bigger-130"></i>
																		</a>
																		</div>
																		
																	</li>
																	</ol>
															</li>
															</ol>
														</li>
													</ol>
												</li>
											</ol>
										</div>
										</div>
										<div class="col-md-3 hidden-sm  hidden-xs">&nbsp;</div>
									</div>
									<div class="space-6"></div>
									<!-- <div class="row">
										<div class="col-xs-6">
											<div id="dynamic-table_filter" class="dataTables_filter pull-left"><label>Search:
												<input type="search" class="form-control input-lg" placeholder="" aria-controls="dynamic-table"  data-ng-model="query[queryBy]"></label>
											</div>
										</div>
									</div> -->
								<!-- <div class="dataTables_wrapper form-inline no-footer">
									
									<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf"  datatable="ng" dt-options="vm.dtOptions">
										<thead class="cf">
											<tr>
												<th>Sl.No</th>
												<th>Category</th>
												<th>Sub Category Name</th>
												<th>Action</th>
											</tr>
										</thead>										
										<tbody >
											<tr  ng-repeat="cat in pagedItems">
												<td data-title="Sl.No">{{$index+1}}</td>
												<td data-title="Category Name">{{cat.CategoryName}}</td>
												<td data-title="Sub Category Name">{{cat.SubCategoryName}}</td>
												<td data-title="Action">
													<button type="button" class="btn btn-xs btn-info" data-ng-click="EditCat(cat.PkId,cat.PkId_CategoryMaster,cat.SubCategoryName)">Edit &nbsp;
														<i class="ace-icon fa fa-pencil bigger-120"></i>
													</button>&nbsp;
													</td>
											</tr>
										</tbody>
									</table>
								</div> -->
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
										<label class="col-sm-3 control-label no-padding-right" for="categoryname"> Category Name <span class="error">*</span></label>
										<input type="hidden" value="{{FormPkId}}">
										<input type="hidden" value="{{CatId}}">
										<input type="hidden" value="{{SubcatId}}">
										<input type="hidden" value="{{Level2PkId}}">
										<div class="col-sm-9">
											<input type="text" id="catname" placeholder="" class="col-xs-10 col-sm-5" name="catname" required  data-ng-model="catname" disabled="" >
												<div class="error" data-ng-show="submitted || AddCategoryForm.catname.$dirty && AddCategoryForm.catname.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.catname.$error.required">Category Name is required.</small>
												</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="categoryname">Level-1 Sub Category Name <span class="error">*</span></label>
										<div class="col-sm-9">
											<input type="text" id="subcatname" placeholder="" class="col-xs-10 col-sm-5" name="subcatname" required  data-ng-model="subcatname" disabled="">
												<div class="error" data-ng-show="submitted || AddCategoryForm.subcatname.$dirty && AddCategoryForm.subcatname.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.subcatname.$error.required">Subcategory Name is required.</small>
												</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="categoryname">Level-2 Sub Category Name <span class="error">*</span></label>
										<div class="col-sm-9">
											<input type="text" id="lvl2subcatname" placeholder="" class="col-xs-10 col-sm-5" name="lvl2subcatname" required   data-ng-model="lvl2subcatname" disabled="">
												<div class="error" data-ng-show="submitted || AddCategoryForm.lvl2subcatname.$dirty && AddCategoryForm.lvl2subcatname.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.lvl2subcatname.$error.required"> Name is required.</small>
												</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="categoryname">Level-3 Sub Category Name <span class="error">*</span></label>
										<div class="col-sm-9">
											<input type="text" id="lvl3subcatname" placeholder="" class="col-xs-10 col-sm-5" name="lvl3subcatname" required  autofocus data-ng-model="lvl3subcatname" data-ng-minlength="3" ng-change="checkCatgory(FormPkId,CatId,SubcatId,lvl2subcatname,lvl3subcatname)">
												<div class="error" data-ng-show="submitted || AddCategoryForm.lvl3subcatname.$dirty && AddCategoryForm.lvl3subcatname.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.lvl3subcatname.$error.required"> Name is required.</small>
													<small class="error" data-ng-show="AddCategoryForm.lvl3subcatname.$error.minlength">Name is required to be at least 3 characters</small>
													<small class="error" data-ng-show="AddCategoryForm.lvl3subcatname.$error.pattern">Name should be alphabets ex:abcd</small>
													
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