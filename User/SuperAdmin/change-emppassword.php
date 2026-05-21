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
    	<script src="angularjs/change-emppassword-script.js"></script>
	</head>

	<body class="no-skin"  data-ng-cloak data-ng-app="ChangePwdModule" data-ng-controller="ChangePwdController">
		<?php include_once("../top.php");?>
		<div class="main-container ace-save-state" id="main-container">
			<?php include_once("sidebar.php");?>

			<div class="main-content">
				<div class="main-content-inner">

					<div class="page-content" >

					<div id="no-more-tables">
						<div class="row">
							<div class="col-xs-12 page-header">
								<div class="col-xs-8 col-sm-8 col-lg-9">
			                  	 	<div class="" >
										<h1>{{pagetitle}}</h1>
										</div>
								</div>
		                  	 	
			              </div>
							
						</div>
							<div class="row" ng-show="FormList">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="space-6"></div>
									<div class="dataTables_wrapper form-inline no-footer" ng-if="pagedItems.length!='0'">
									<div class="row">
										<div class="col-xs-6">
											<div id="dynamic-table_filter" class="dataTables_filter pull-left"><label>Search:</label>
												<input type="text" ng-model="search" class="form-control" placeholder="Search"> &nbsp; Showing {{mypageno*itemsPerPage-itemsPerPage+1}}-{{mypageno*itemsPerPage}} of {{total_count}}
											</div>
										</div>
									</div>
									<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf">
										<thead class="cf">
											<tr>
												<th ng-click="sort('UserId')">User Id <span class="glyphicon sort-icon" ng-show="sortKey=='Roll'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
												<th ng-click="sort('Name')">User Name <span class="glyphicon sort-icon" ng-show="sortKey=='Name'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
												<th ng-click="sort('EmailId')">Email Id <span class="glyphicon sort-icon" ng-show="sortKey=='Address'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
												<th>Action</th>
											</tr>
										</thead>										
										<tbody >
											<tr dir-paginate="sup in pagedItems|orderBy:sortKey:reverse|filter:search|itemsPerPage:5" total-items="{{total_count}}" current-page="mypageno">
												<td data-title="User Id">{{sup.UserId}}</td>
												<td data-title="User Name">{{sup.Name}}</td>
												<td data-title="Email Id">{{sup.EmailId}}</td>
												
												<td data-title="Action">
													<button type="button" class="btn btn-xs btn-info" data-ng-click="ChangePass(sup.UserId)">
													Change Password &nbsp;
														<i class="ace-icon fa fa-pencil bigger-120"></i>
													</button>
												</td>
											</tr>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="8">
													<ul class="pagination">
														<li>
														    <dir-pagination-controls max-size="10" direction-links="true" boundary-links="true" on-page-change="getData(newPageNumber)"></dir-pagination-controls>
												        </li>
												    </ul>
												</td>
											</tr>
										</tfoot>
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
								 <form class="form-horizontal" role="form" role="form" id="ChangePwdForm" name="ChangePwdForm" autocomplete="off" novalidate="" data-ng-submit="ChangePwdData()">	
								 	

									
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="currentpwd">Current Password <span class="error">*</span></label>
										<div class="col-sm-6">
											<input type="hidden" value="{{UserId}}">
											<input type="password" id="currentpwd" placeholder="Current Password" class="form-control" name="currentpwd" autofocus required maxlength="20" data-ng-model="currentpwd" data-ng-minlength="8" data-ng-maxlength="20">
											<div class="error" data-ng-show="submitted || ChangePwdForm.currentpwd.$dirty && ChangePwdForm.currentpwd.$invalid">
                                                <small class="error" data-ng-show="ChangePwdForm.currentpwd.$error.required">Current Password is required.</small>
                                                <small class="error" data-ng-show="ChangePwdForm.currentpwd.$error.minlength">Current Password is required to be at least 8 characters</small>
                                                <small class="error" data-ng-show="ChangePwdForm.currentpwd.$error.maxlength">Current Password cannot be longer than 20 characters</small>
                                            </div>
										</div>
									</div>
									
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="newpwd">New Password <span class="error">*</span></label>
										<div class="col-sm-6">
											<input type="password" id="newpwd" placeholder="New Password" class="form-control" name="newpwd" required maxlength="20" data-ng-model="newpwd" data-ng-minlength="8" data-ng-maxlength="20">
											<div class="error" data-ng-show="submitted || ChangePwdForm.newpwd.$dirty && ChangePwdForm.newpwd.$invalid">
                                                <small class="error" data-ng-show="ChangePwdForm.newpwd.$error.required">New Password is required.</small>
                                                <small class="error" data-ng-show="ChangePwdForm.newpwd.$error.minlength">New Password is required to be at least 8 characters</small>
                                                <small class="error" data-ng-show="ChangePwdForm.newpwd.$error.maxlength">New Password cannot be longer than 20 characters</small>
                                            </div>
										</div>
									</div>
									
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="retypwd">Retype Password <span class="error">*</span></label>
										<div class="col-sm-6">
											<input type="password" id="retypwd" placeholder="Retype Password" class="form-control" name="retypwd" required maxlength="20" data-ng-model="retypwd" data-ng-minlength="8" data-ng-maxlength="20">
											<div class="error" data-ng-show="submitted || ChangePwdForm.retypwd.$dirty && ChangePwdForm.retypwd.$invalid">
                                                <small class="error" data-ng-show="ChangePwdForm.retypwd.$error.required">Retype Password is required.</small>
                                                <small class="error" data-ng-show="ChangePwdForm.retypwd.$error.minlength">Retype Password is required to be at least 8 characters</small>
                                                <small class="error" data-ng-show="ChangePwdForm.retypwd.$error.maxlength">Retype Password cannot be longer than 20 characters</small>
                                            </div>
										</div>
									</div>

									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="submit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Change Password
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
