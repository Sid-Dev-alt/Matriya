<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']!="")
{
		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";
		
		$SAId = $_SESSION['UserId'];
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once("../title.php");?>
    <script src="angularjs/master-script.js"></script>
    <script src="../../assets/js/angularjs/paginationjs/dirPagination.js"></script>
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="CategoryModule" data-ng-controller="CategoryController" >
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
			<li class="active">Search Products</li>
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
			              </div>
							
						</div>
						<div class="row" ng-show="FormAdd" data-ng-init="GetProductList()">
						<div class="col-xs-12">
						<!-- PAGE CONTENT BEGINS -->
						<form class="form-horizontal" role="form" id="AddCategoryForm" name="AddCategoryForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="FinalSubmisssion()" >
							 
							<div class="form-group">
								<div class="col-sm-9">
									<label>Search Scart Catalogue First</label>
									<!-- <input type="text" id="productname" placeholder="" class="form-control" name="productname" required data-ng-model="productname"  uib-typeahead="cust as cust.ProductName for cust in ProductArray | filter:$viewValue" > -->

									<input type="text" id="productname" placeholder="" class="form-control" name="productname" required   data-ng-model="productname"  uib-typeahead="cust as cust.ProductName for cust in ProductArray | filter:$viewValue">
										
										<div class="error" data-ng-show="submitted || AddCategoryForm.productname.$dirty && AddCategoryForm.productname.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.productname.$error.required">Please enter product name.</small>
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
								If it is not in SCART catalogue: <a href="product-types.php">Create a new product</a>
							</p>
							 <p>&nbsp;</p>
							
							 <div class="clearfix"></div>

							 <div class="text-center muted" ng-if="NoRecord">
							 	<h3>No Records Found</h3>
							 	<!-- <p><a href="add-product.php">Create a new product</a></p> -->
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
									<div>
										<h4 class="media-heading">
											<a href="" class="blue">{{sup.ProductName}}</a>
											<span class="label label-success arrowed">{{sup.CategoryName}}</span>
										</h4>
									</div>
									<p>{{sup.ProductDescription}}</p>
									<!-- <div class="search-actions text-center">

										<span class="blue bolder bigger-150">{{sup.ProductId}}</span>

										<a class="search-btn-action btn btn-sm btn-block btn-info">Add Stock</a>
									</div> -->
								</div>
							</div>
							<div class="pagination pull-right">
								<dir-pagination-controls max-size="7" direction-links="true" boundary-links="true"></dir-pagination-controls>
							</div>
						</div>
				</div><!--FOrm lising-->

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
