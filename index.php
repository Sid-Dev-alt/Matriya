<?php
error_reporting(0);
session_start();
if(isset($_SESSION['UserId']))
{
	header('Location: User/SuperAdmin/welcome.php');
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Materiya | Login </title>

		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="assets/css/ace.min.css" />
		<link rel="stylesheet" href="assets/css/style.css" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="assets/css/ace-part2.min.css" />
		<![endif]-->
		<link rel="stylesheet" href="assets/css/ace-rtl.min.css" />

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->
		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
		<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->


 		<!-- AngularJS Script Starts Here -->
			<script src="assets/js/angularjs/angular.min.js"></script>			
			<script src="assets/js/angularjs/login-script.js"></script>
		<!-- AngularJS Script Ends Here --> 
	</head>

	<body data-ng-cloak class="login-layout light-login"   data-ng-cloak data-ng-app="LoginModule" data-ng-controller="LoginController">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">
								<h1>
									<span class="red">Materiya</span>
									<!--<span class="white" id="id-text2">Application</span>-->
								</h1>
								<!--<h4 class="blue" id="id-company-text">&copy; SmartBuilder</h4>-->
							</div>
							<div class="space-6"></div>
							<div class="row text-center">
							<?php
				        	if (isset($authUrl))
							{ 
				           // echo '<a class="login" href="' . $authUrl . '"><img src="assets/images/google-login-button.png" /></a>';
				            ?>
				            <?php
				        	}
				        	?>
							</div>
							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border"  data-ng-show="ShowLoginBox">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header blue lighter bigger">
												<i class="ace-icon fa fa-coffee green"></i>
												Please Enter Your Information
											</h4>

											<div class="space-6"></div>

											<form class="form-login" id="LoginForm" autocomplete="off" enctype="multipart/form-data" name="LoginForm" novalidate="" data-ng-submit="LoginData(SALogin)">
												
												<div class="alert alert-danger alert-dismissable" data-ng-show="showWarningAlert">
												  <button type="button" class="close" data-ng-click="switchBool('showWarningAlert')">×</button><strong>{{WarningAlert}}</strong>
												</div>
												
												<div class="alert alert-success alert-dismissable" data-ng-show="showSuccessAlert">
												  <button type="button" class="close" data-ng-click="switchBool('showSuccessAlert')">×</button>
												  <strong>{{SuccessAlert}}</strong>
												</div>
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="email" class="form-control" placeholder="Email-Id"  id="email" name="email" data-ng-model="SALogin.email" pattern="^[a-zA-Z0-9-\_.]+@[a-zA-Z0-9-\_.]+\.[a-zA-Z0-9.]{2,5}$" required autofocus>
															<i class="ace-icon fa fa-user"></i>
														</span>																				
										                  	<div class="error" data-ng-show="submitted || LoginForm.email.$dirty && LoginForm.email.$invalid">
																<small class="error" data-ng-show="LoginForm.email.$error.required">Email-Id is required.</small>
																<small class="error" data-ng-show="LoginForm.email.$error.email">Invalid Email-Id.</small>
															</div>                
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" placeholder="Password" maxlength="20" name="password" id="password" data-ng-model="SALogin.password" data-ng-minlength="8" data-ng-maxlength="20" required>
															<i class="ace-icon fa fa-lock"></i>
														</span>
														<div class="error" data-ng-show="submitted || LoginForm.password.$dirty && LoginForm.password.$invalid">
															<small class="error" data-ng-show="LoginForm.password.$error.required">Password is required.</small>
															<small class="error" data-ng-show="LoginForm.password.$error.minlength">Password is required to be at least 8 characters</small>
															<small class="error" data-ng-show="LoginForm.password.$error.maxlength">Password cannot be longer than 20 characters</small>
														</div>
													</label>

													<div class="space"></div>

														<button type="submit"  ng-click="submitData()"  class="width-35 btn btn-sm btn-primary">
															<i class="ace-icon fa fa-key"></i>
															<span class="bigger-110">Login</span>
														</button>

													<div class="space-4"></div>
												</fieldset>
											</form>

										</div><!-- /.widget-main -->

										<!--<div class="toolbar clearfix">
											<div>
												<a href="#" class="forgot-password-link" data-ng-click="ForgotPwdBox()">
													<i class="ace-icon fa fa-arrow-left"></i>
													I forgot my password
												</a>
											</div>
										</div>-->
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->

								<!--<div id="forgot-box" class="forgot-box visible widget-box no-border" data-ng-show="ShowForgotPwdBox">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header red lighter bigger">
												<i class="ace-icon fa fa-key"></i>
												Retrieve Password
											</h4>

											<div class="space-6"></div>

											<p>
												Enter your email to get new password
											</p>

											<form class="form-login" id="ForgotPwdForm" autocomplete="off" enctype="multipart/form-data" name="ForgotPwdForm" novalidate="" data-ng-submit="ForgotPwdData(SAForgotPwd)">
									            <div class="alert alert-danger alert-dismissable" data-ng-show="showFPwdWarningAlert">
									              <button type="button" class="close" data-ng-click="switchBool('showFPwdWarningAlert')">×</button><strong>{{FPwdWarningAlert}}</strong>
									            </div>
									            <div class="alert alert-success alert-dismissable" data-ng-show="showFPwdSuccessAlert">
									              <button type="button" class="close" data-ng-click="switchBool('showFPwdSuccessAlert')">×</button><strong>{{FPwdSuccessAlert}}</strong>
									            </div>
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input class="form-control" type="email" id="email" name="email" autofocus pattern="^[a-zA-Z0-9-\_.]+@[a-zA-Z0-9-\_.]+\.[a-zA-Z0-9.]{2,5}$" required placeholder="Email-Id" data-ng-model="SAForgotPwd.email"> 
															<i class="ace-icon fa fa-envelope"></i>
														</span>
														<div class="error" data-ng-show="submitted || ForgotPwdForm.email.$dirty && ForgotPwdForm.email.$invalid">
										                  <small class="error" data-ng-show="ForgotPwdForm.email.$error.required">Email-Id is required.</small>
										                  <small class="error" data-ng-show="ForgotPwdForm.email.$error.email">Invalid Email-Id.</small>
										                </div>
													</label>

													<div class="clearfix">
														<button type="submit" class="width-35 pull-right btn btn-sm btn-danger">
															<i class="ace-icon fa fa-lightbulb-o"></i>
															<span class="bigger-110">Send Me!</span>
														</button>
													</div>
												</fieldset>
											</form>
										</div>

										<div class="toolbar center">
											<a href=""  class="back-to-login-link" data-ng-click="LoginBox()">
												Back to login
												<i class="ace-icon fa fa-arrow-right"></i>
											</a>
										</div>
									</div>
								</div>-->
							</div><!-- /.position-relative -->

							<!--<div class="navbar-fixed-top align-right">
								<br />
								&nbsp;
								<a id="btn-login-dark" href="#">Dark</a>
								&nbsp;
								<span class="blue">/</span>
								&nbsp;
								<a id="btn-login-blur" href="#">Blur</a>
								&nbsp;
								<span class="blue">/</span>
								&nbsp;
								<a id="btn-login-light" href="#">Light</a>
								&nbsp; &nbsp; &nbsp;
							</div>-->
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script src="assets/js/jquery-2.1.4.min.js"></script>

		<!-- <![endif]-->

		<!--[if IE]>
<script src="assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
	</body>
</html>
