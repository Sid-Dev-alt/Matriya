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
    <script src="angularjs/change-password-script.js"></script> 
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="ChangePwdModule" data-ng-controller="ChangePwdController">
		<?php include_once("../top.php");?>

		<div class="main-container ace-save-state" id="main-container">
			
			<?php include_once("sidebar.php");?>

			<div class="main-content">
				<div class="main-content-inner">

					<div class="page-content" >
						<div class="row">
							<div class="col-xs-12 page-header">
		                  	 <div class="col-xs-8 col-sm-8 col-lg-9">
		                  	 	<div class="" >
									<h1>{{pagetitle}}</h1>
									</div></div>
		                  	 	<div class="col-xs-4 col-sm-4 col-lg-3" ng-if="FormList">
		                  	 		<a class="" href="" ng-click="GotoAdd()">
			                    <i class="pull-left add-thumbicon fa fa-plus btn-success no-hover"></i>
			                    </a>
			                  </div>
			              </div>							
						</div>


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
								 <form class="form-horizontal" role="form" role="form" id="ChangePwdForm" name="ChangePwdForm" autocomplete="off" novalidate="" data-ng-submit="ChangePwdData(SAChangePwd)">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="currentpwd">Current Password <span class="error">*</span></label>
										<div class="col-sm-6">
											<input type="password" id="currentpwd" placeholder="Current Password" class="form-control" name="currentpwd" autofocus required maxlength="20" data-ng-model="SAChangePwd.currentpwd" data-ng-minlength="8" data-ng-maxlength="20">
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
											<input type="password" id="newpwd" placeholder="New Password" class="form-control" name="newpwd" required maxlength="20" data-ng-model="SAChangePwd.newpwd" data-ng-minlength="8" data-ng-maxlength="20">
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
											<input type="password" id="retypwd" placeholder="Retype Password" class="form-control" name="retypwd" required maxlength="20" data-ng-model="SAChangePwd.retypwd" data-ng-minlength="8" data-ng-maxlength="20">
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
