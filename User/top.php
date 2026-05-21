<div id="navbar" class="navbar navbar-default ace-save-state">

			<div class="navbar-container ace-save-state" id="navbar-container">

				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">

					<span class="sr-only">Toggle sidebar</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>

				<div class="navbar-header pull-left">

					<a href="welcome.php" class="navbar-brand">

						<small>

							<i class="fa fa-leaf"></i>

							Materiya

						</small>

						<!-- <small>

							<img src="../../assets/images/materia-logo.png" style="width: 125px;">

						</small> -->

						

					</a>

				</div>



				<div class="navbar-buttons navbar-header pull-right" role="navigation">



					<ul class="nav ace-nav">

						<li class="light-blue dropdown-modal">

							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<span class="user-info">

									<small>Welcome,</small>
										<?php
											if($_SESSION['UserRoleId']==1)
											{
										?>
										<?php echo $_SESSION['SAName']; ?> (<?php echo $_SESSION['SARole'];?>)
										<?php
											}
											else
											{
										?>
										<?php echo $_SESSION['EmpName']; ?> (<?php echo $_SESSION['EmpRole'];?>)
										<?php
											}
										?>

									</span>
								<i class="ace-icon fa fa-caret-down"></i>

							</a>



							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<?php
									if($_SESSION['UserRoleId']==1)
									{
								?>
									<li><a href="changepassword.php"><i class="ace-icon fa fa-pencil"></i>Change Password</a></li>
								<?php
									}
								?>

								<!--<li>

									<a href="company-info.php">

										<i class="ace-icon fa fa-user"></i>

										Company Profile

									</a>

								</li>-->



								<li class="divider"></li>

								<li>

									<a href="../logout.php">

										<i class="ace-icon fa fa-power-off"></i>

										Logout

									</a>

								</li>

							</ul>

						</li>

					</ul>

				</div>

			</div><!-- /.navbar-container -->

		</div>

