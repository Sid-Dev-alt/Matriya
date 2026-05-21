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
    	<script src="angularjs/vendor-script.js"></script>
	</head>

	<body class="no-skin"  data-ng-cloak data-ng-app="CategoryModule" data-ng-controller="CategoryController">
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
							<li class="active">Vendors</li>
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
								<div class="radio">
									<label>
										<input name="form-field-radio" type="radio" class="ace input-lg" ng-model="vendortype" value="1" ng-change="Getlist(vendortype)">
										<span class="lbl bigger-120"> Approve Vendors</span>
									</label>

									<label>
										<input name="form-field-radio" type="radio" class="ace input-lg" ng-model="vendortype" value="0" ng-init="Getlist(vendortype)" ng-change="Getlist(vendortype)">
										<span class="lbl bigger-120"> Unapprove Vendors</span>
									</label>
								</div>
		                  	 	<!-- <div class="col-xs-12 col-sm-12 col-lg-6">
				                    <a href="" data-ng-click="GotoAdd()">
									<div class="label label-lg label-success arrowed-in arrowed-right pull-right" ng-show="FormList">
									<b>Add Customer</b>
									</div></a>
			                  </div> -->
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
												<th>Company Name</th>
												<!-- <th>Email Address</th> -->
												<th>Name of the Products</th>
												<th>Contact Name</th>
												<th>Contact Phone</th>
												<th>Contact EmailId</th>
												<th>Action</th>
											</tr>
										</thead>										
										<tbody >
											<tr ng-repeat="sup in pagedItems">
												<td data-title="Company name">{{sup.CompanyName}}</td>
												<!-- <td data-title="Email Address">{{sup.EmailAdrs}}</td> -->
												<td data-title="Name of the Products">{{sup.NameoftheProducts}}</td>
												<td data-title="Contact Name">{{sup.ContactName}}</td>
												<td data-title="Contact Phone">{{sup.ContactPhone}}</td>
												<td data-title="Contact EmailId">{{sup.ContactEmailId}}</td>
												<td data-title="Action">
													<a href="#modal-form" class="btn btn-block btn-danger " data-toggle="modal" ng-click="View(sup.PkId,sup.EmailAdrs,sup.CompanyName,sup.NameoftheProducts,sup.Areyourproductslisted,sup.certifications,sup.ContactName,sup.ContactEmailId,sup.ContactPhone,sup.ContactMethod,sup.Sustainable,sup.Status)">View</a>
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

					<!--modal-->
						<div id="modal-form" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
						<div class="modal-dialog  modal-lg">
							<form class="form-horizontal" role="form" id="AddCategoryForm" name="AddCategoryForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="FinalSubmisssion()" >
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="blue bigger">{{OrderId}}</h4>
								</div>

								<div class="modal-body">
								<div class="row">
									<div class="profile-user-info profile-user-info-striped">
									<div class="profile-info-row">
										<div class="profile-info-name" style="width: 300px"> Company Name </div>

										<div class="profile-info-value">
											<span class="editable editable-click" id="username">{{CompanyName}}</span>
										</div>
									</div>

									<!-- <div class="profile-info-row">
										<div class="profile-info-name">Email Address </div>

										<div class="profile-info-value">
											<span class="editable editable-click" id="username">{{EmailAdrs}}</span>
										</div>
									</div> -->

									<div class="profile-info-row">
										<div class="profile-info-name">Name of the Products /Services </div>

										<div class="profile-info-value">
											<span class="editable editable-click" id="username">{{NameoftheProducts}}</span>
										</div>
									</div>

									<div class="profile-info-row">
										<div class="profile-info-name">Are your products / services listed anywhere? </div>

										<div class="profile-info-value">
											<span class="editable editable-click" id="username">{{Areyourproductslisted}}</span>
										</div>
									</div>

									<div class="profile-info-row">
										<div class="profile-info-name">Certifications on the product/ service </div>

										<div class="profile-info-value">
											<span class="editable editable-click" id="username">{{certifications}}</span>
										</div>
									</div>

									<div class="profile-info-row">
										<div class="profile-info-name">Name of the contact person </div>

										<div class="profile-info-value">
											<span class="editable editable-click" id="username">{{ContactName}}</span>
										</div>
									</div>

									<div class="profile-info-row">
										<div class="profile-info-name">Phone number </div>

										<div class="profile-info-value">
											<span class="editable editable-click" id="username">{{ContactPhone}}</span>
										</div>
									</div>

									<div class="profile-info-row">
										<div class="profile-info-name">Email ID </div>

										<div class="profile-info-value">
											<span class="editable editable-click" id="username">{{ContactEmailId}}</span>
										</div>
									</div>

									<div class="profile-info-row">
										<div class="profile-info-name">Preferred contact method </div>

										<div class="profile-info-value">
											<span class="editable editable-click" id="username">{{ContactMethod}}</span>
										</div>
									</div>

									<div class="profile-info-row">
										<div class="profile-info-name">Which of the Sustainable Development Goals does your product/ service support?</div>

										<div class="profile-info-value">
											<span class="editable editable-click" id="username">
												<p ng-repeat="st in Sustainable">{{st}}</p>
											</span>
										</div>
									</div>
								</div>
								</div><!-- row-->

								 	<div class="clearfix form-actions" ng-if="Status=='0'">
									<div class="col-md-offset-3 col-md-9">
										<button type="button" class="btn btn-success" ng-click="ApproveVendor()"  >
											<i class="ace-icon fa fa-check bigger-110"></i>
											Approve
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