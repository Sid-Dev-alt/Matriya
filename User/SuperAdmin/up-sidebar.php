<?php
$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$url = $_SERVER['REQUEST_URI'];
$pagename = basename($url);
?>
<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>
			<!-- <div id="sidebar" class="sidebar responsive ace-save-state"> -->
				<div id="sidebar" class="sidebar      h-sidebar                navbar-collapse collapse          ace-save-state" data-sidebar="true" data-sidebar-scroll="true" data-sidebar-hover="true" aria-expanded="false">
				<script type="text/javascript">
					try{ace.settings.loadState('sidebar')}catch(e){}
				</script>

				<!-- <div class="sidebar-shortcuts" id="sidebar-shortcuts">
					<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
						<button class="btn btn-success">
							<i class="ace-icon fa fa-signal"></i>
						</button>

						<button class="btn btn-info">
							<i class="ace-icon fa fa-pencil"></i>
						</button>

						<button class="btn btn-warning">
							<i class="ace-icon fa fa-users"></i>
						</button>

						<button class="btn btn-danger">
							<i class="ace-icon fa fa-cogs"></i>
						</button>
					</div>

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>

						<span class="btn btn-info"></span>

						<span class="btn btn-warning"></span>

						<span class="btn btn-danger"></span>
					</div>
				</div>/.sidebar-shortcuts -->
			
				<ul class="nav nav-list">
				<?php
					if($pagename == "welcome.php")
					{
				?>
					<li class="active">
						<a href="welcome.php">
							<i class="menu-icon fa fa-tachometer"></i>
							<span class="menu-text"> Dashboard </span>
						</a>

						<b class="arrow"></b>
					</li>
				<?php
				}
				else
				{
				?>
					<li class="">
						<a href="welcome.php">
							<i class="menu-icon fa fa-tachometer"></i>
							<span class="menu-text"> Dashboard </span>
						</a>

						<b class="arrow"></b>
					</li>

				<?php
				}
				if($pagename == "locations.php")
				{
				?>
					<li class="active">
						<a href="locations.php">
							<i class="menu-icon fa fa-tachometer"></i>
							<span class="menu-text"> Locations </span>
						</a>

						<b class="arrow"></b>
					</li>
				<?php
				}
				else
				{
				?>
					<li class="">
						<a href="locations.php">
							<i class="menu-icon fa fa-tachometer"></i>
							<span class="menu-text"> Locations </span>
						</a>

						<b class="arrow"></b>
					</li>

				<?php
				}
				if($pagename == "category.php" || $pagename == "sub-category.php" || $pagename == "sub-category-level2.php" || $pagename == "sub-category-level3.php" || $pagename == "product-types.php" || $pagename== "list-brands.php" || $pagename== "list-sizes.php" || $pagename == "list-suppliers.php" || $pagename== "list-vendors.php"  || $pagename== "list-customers.php" || $pagename== "list-suppliers.php" || $pagename== "list-employees.php")
				{
				?>
					<li class="active open hover">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-desktop"></i>
							<span class="menu-text">
								MASTERS
							</span>
							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu  can-scroll">
							<?php	
							if($pagename == "category.php")
							{
							?>
								<li class="active open hover">
									<a href="category.php"><i class="menu-icon fa fa-caret-right"></i>Categories</a>
									<b class="arrow"></b>
								</li>
							<?php
							}	
							else
							{
							?>
								<li class="open hover">
									<a href="category.php"><i class="menu-icon fa fa-caret-right"></i>Categories</a>
									<b class="arrow"></b>
								</li>
							<?php	
							}
							if($pagename == "sub-category.php")
							{
							?>
								<li class="active open hover">
									<a href="sub-category.php"><i class="menu-icon fa fa-caret-right"></i>Level-1 Sub Category</a>
									<b class="arrow"></b>
								</li>
							<?php
							}	
							else
							{
							?>
								<li class="open hover">
									<a href="sub-category.php"><i class="menu-icon fa fa-caret-right"></i>Level-1 Sub Category</a>
									<b class="arrow"></b>
								</li>
							<?php	
							}
							if($pagename == "sub-category-level2.php")
							{
							?>
								<li class="active open hover">
									<a href="sub-category-level2.php"><i class="menu-icon fa fa-caret-right"></i>Level-2 Sub Category</a>
									<b class="arrow"></b>
								</li>
							<?php
							}	
							else
							{
							?>
								<li class="open hover">
									<a href="sub-category-level2.php"><i class="menu-icon fa fa-caret-right"></i>Level-2 Sub Category</a>
									<b class="arrow"></b>
								</li>
							<?php	
							}
							if($pagename == "sub-category-level3.php")
							{
							?>
								<li class="active open hover">
									<a href="sub-category-level3.php"><i class="menu-icon fa fa-caret-right"></i>Level-3 Sub Category</a>
									<b class="arrow"></b>
								</li>
							<?php
							}	
							else
							{
							?>
								<li class="open hover">
									<a href="sub-category-level3.php"><i class="menu-icon fa fa-caret-right"></i>Level-3 Sub Category</a>
									<b class="arrow"></b>
								</li>
							<?php	
							}
							if($pagename == "list-brands.php")
							{
							?>
							<li class="active open hover">
									<a href="list-brands.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Brands
									</a>
									<b class="arrow"></b>
								</li>
							<?php
							}	
							else
							{
							?>

								<li class="open hover">
									<a href="list-brands.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Brands
									</a>
									<b class="arrow"></b>
								</li>
							<?php
							}
							if($pagename == "list-sizes.php")
							{
							?>
								<li class="active open hover">
									<a href="list-sizes.php"><i class="menu-icon fa fa-caret-right"></i>Sizes</a>
									<b class="arrow"></b>
								</li>
							<?php
							}	
							else
							{
							?>
								<li class="open hover">
									<a href="list-sizes.php"><i class="menu-icon fa fa-caret-right"></i>Sizes </a>
									<b class="arrow"></b>
								</li>
							<?php	
							}
							
							if($pagename == "product-types.php")
							{
							?>
								<li class="active open hover">
									<a href="product-types.php"><i class="menu-icon fa fa-caret-right"></i>Products</a>
									<b class="arrow"></b>
								</li>
							<?php
							}	
							else
							{
							?>
								<li class="open hover">
									<a href="product-types.php"><i class="menu-icon fa fa-caret-right"></i>Products </a>
									<b class="arrow"></b>
								</li>
							<?php	
							}
							
							if($pagename == "list-customers.php")
							{
							?>
							<li class="active open hover">
									<a href="list-customers.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Customers
									</a>
									<b class="arrow"></b>
								</li>
							<?php
							}	
							else
							{
							?>

								<li class="open hover">
									<a href="list-customers.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Customers
									</a>
									<b class="arrow"></b>
								</li>
							<?php	
							/*}
							if($pagename== "list-suppliers.php")
							{
							?>
							<li class="active">
									<a href="list-suppliers.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Suppliers
									</a>
									<b class="arrow"></b>
								</li>
							<?php
							}	
							else
							{
							?>

								<li class="">
									<a href="list-suppliers.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Suppliers
									</a>
									<b class="arrow"></b>
								</li>
							<?php	
							}
							if($pagename== "list-employees.php")
							{
							?>
							<li class="active">
									<a href="list-employees.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Employees
									</a>
									<b class="arrow"></b>
								</li>
							<?php
							}	
							else
							{
							?>

								<li class="">
									<a href="list-employees.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Employees
									</a>
									<b class="arrow"></b>
								</li>
							<?php	*/
							}
							?>
						</ul>
					</li>
					<?php
					}
					else
					{
					?>
						<li class="hover">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-desktop"></i>
							<span class="menu-text">
								MASTERS
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>
						<ul class="submenu  can-scroll">
							<li class="open hover">
								<a href="category.php">
									<i class="menu-icon fa fa-caret-right"></i>
									Categories
								</a>
								<b class="arrow"></b>
							</li>
							<li class="open hover">
								<a href="sub-category.php">
									<i class="menu-icon fa fa-caret-right"></i>
									Level-1 Sub Category
								</a>
								<b class="arrow"></b>
							</li>
							<li class="open hover">
								<a href="sub-category-level2.php">
									<i class="menu-icon fa fa-caret-right"></i>
									Level-2 Sub Category
								</a>
								<b class="arrow"></b>
							</li>
							<li class="open hover">
								<a href="sub-category-level3.php">
									<i class="menu-icon fa fa-caret-right"></i>
									Level-3 Sub Category
								</a>
								<b class="arrow"></b>
							</li>
							
							<li class="open hover">
								<a href="list-brands.php">
									<i class="menu-icon fa fa-caret-right"></i>
									Brands
								</a>
								<b class="arrow"></b>
							</li>
							<li class="open hover">
								<a href="list-sizes.php">
									<i class="menu-icon fa fa-caret-right"></i>
									Sizes
								</a>
								<b class="arrow"></b>
							</li>
							<li class="open hover">
								<a href="product-types.php">
									<i class="menu-icon fa fa-caret-right"></i>
									Products
								</a>
								<b class="arrow"></b>
							</li>
							
							
							<li class="open hover">
								<a href="list-customers.php">
									<i class="menu-icon fa fa-caret-right"></i>
									Customers
								</a>
								<b class="arrow"></b>
							</li>
							<!--<li class="">
								<a href="list-suppliers.php">
									<i class="menu-icon fa fa-caret-right"></i>
									Suppliers
								</a>
								<b class="arrow"></b>
							</li>
							<li class="">
								<a href="list-employees.php">
									<i class="menu-icon fa fa-caret-right"></i>
									Employees
								</a>
								<b class="arrow"></b>
							</li>-->


						</ul>
					</li>
					<?php
					}
					if($pagename == "add-inventory.php" || $pagename == "list-inventory.php")
					{
					?>
						<li class="active open hover">
							<a href="#" class="dropdown-toggle">
								<i class="menu-icon fa fa-pencil-square-o"></i>
								<span class="menu-text"> Inventory </span>

								<b class="arrow fa fa-angle-down"></b>
							</a>

							<b class="arrow"></b>
							<ul class="submenu can-scroll">
								<?php
								if($pagename == "add-inventory.php")
								{
								?>

									<li class="active open hover">
										<a href="add-inventory.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Add Inventory
										</a>

										<b class="arrow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="open hover">
										<a href="add-inventory.php"><i class="menu-icon fa fa-caret-right"></i>Add Inventory</a>
										<b class="arrow"></b>
									</li>
								<?php	
								}
								if($pagename == "list-inventory.php")
								{
								?>

									<li class="active open  hover">
										<a href="list-inventory.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Manage Inventory 
										</a>

										<b class="arrow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="open hover">
										<a href="list-inventory.php"><i class="menu-icon fa fa-caret-right"></i>Manage Inventory  </a>
										<b class="arrow"></b>
									</li>
								<?php	
								}
								?>
								
							</ul>
						</li>
					<?php
					}
					else
					{
					?>	
						<li class="hover">
							<a href="#" class="dropdown-toggle">
								<i class="menu-icon fa fa-pencil-square-o"></i>
								<span class="menu-text">  Inventory </span>

								<b class="arrow fa fa-angle-down"></b>
							</a>

							<b class="arrow"></b>

							<ul class="submenu">

								<li class="open hover">
									<a href="add-inventory.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Add Inventory
									</a>

									<b class="arrow"></b>
								</li>
								<li class="open hover">
									<a href="list-inventory.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Manage Inventory 
									</a>

									<b class="arrow"></b>
								</li>
							</ul>
						</li>
						<?php
						}
						/*if($pagename == "transfer.php")
						{
						?>
							<li class="active">
								<a href="transfer.php">
									<i class="menu-icon fa fa-tachometer"></i>
									<span class="menu-text"> Transfer </span>
								</a>

								<b class="arrow"></b>
							</li>
						<?php
						}
						else
						{
						?>
							<li class="">
								<a href="transfer.php">
									<i class="menu-icon fa fa-tachometer"></i>
									<span class="menu-text"> Transfer </span>
								</a>

								<b class="arrow"></b>
							</li>

						<?php
						}*/
						
						if($pagename == "drogtransfer.php")
						{
						?>
							<li class="active">
								<a href="drogtransfer.php">
									<i class="menu-icon fa fa-tachometer"></i>
									<span class="menu-text"> Transfer </span>
								</a>

								<b class="arrow"></b>
							</li>
						<?php
						}
						else
						{
						?>
							<li class="">
								<a href="drogtransfer.php">
									<i class="menu-icon fa fa-tachometer"></i>
									<span class="menu-text"> Transfer </span>
								</a>

								<b class="arrow"></b>
							</li>

						<?php
						}
						if($pagename == "inventory-log.php")
						{
						?>
							<li class="active">
								<a href="inventory-log.php">
									<i class="menu-icon fa fa-tachometer"></i>
									<span class="menu-text"> Inventory Log </span>
								</a>

								<b class="arrow"></b>
							</li>
						<?php
						}
						else
						{
						?>
							<li class="">
								<a href="inventory-log.php">
									<i class="menu-icon fa fa-tachometer"></i>
									<span class="menu-text"> Inventory Log </span>
								</a>

								<b class="arrow"></b>
							</li>

						<?php
						}
					/*if($pagename == "list-of-grn.php")
					{
					?>
						<li class="active">
						<a href="list-of-grn.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Add Stock to W.H
							</span>
						</a>

						<b class="arrow"></b>
						</li>
					<?php
					}
					else
					{
					?>	
						<li class="">
						<a href="list-of-grn.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Add Stock to W.H
							</span>
						</a>

						<b class="arrow"></b>
						</li>
					<?php
					}
					if($pagename == "stock-list.php")
					{
					?>
						<li class="active">
						<a href="stock-list.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Available Stock List
							</span>
						</a>

						<b class="arrow"></b>
						</li>
					<?php
					}
					else
					{
					?>	
						<li class="">
						<a href="stock-list.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Available Stock List
							</span>
						</a>

						<b class="arrow"></b>
						</li>
					<?php
					}
					if($pagename == "list-invoice.php")
					{
					?>
						<li class="active">
						<a href="list-invoice.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Customer Purchase
							</span>
						</a>

						<b class="arrow"></b>
						</li>
					<?php
					}
					else
					{
					?>	
						<li class="">
						<a href="list-invoice.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Customer Purchase
							</span>
						</a>

						<b class="arrow"></b>
						</li>
					<?php
					}
					if($pagename == "list-of-pos.php")
					{
					?>
						<li class="active">
						<a href="list-of-pos.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Purchase Orders
							</span>
						</a>

						<b class="arrow"></b>
						</li>
					<?php
					}
					else
					{
					?>	
						<li class="">
						<a href="list-of-pos.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Purchase Orders
							</span>
						</a>

						<b class="arrow"></b>
						</li>
					<?php
					}
					if($pagename == "#")
					{
					?>
						<li class="active open">
							<a href="#" class="dropdown-toggle">
								<i class="menu-icon fa fa-pencil-square-o"></i>
								<span class="menu-text"> Reports </span>

								<b class="arrow fa fa-angle-down"></b>
							</a>

							<b class="arrow"></b>
							<ul class="submenu">
								<?php
								if($pagename == "#")
								{
								?>

									<li class="active">
										<a href="#">
											<i class="menu-icon fa fa-caret-right"></i>
											Consumption Report
										</a>

										<b class="arrow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="#"><i class="menu-icon fa fa-caret-right"></i>Consumption Report</a>
										<b class="arrow"></b>
									</li>
								<?php	
								}
								?>
								
							</ul>
						</li>
					<?php
					}
					else
					{
					?>	
						<li class="">
							<a href="#" class="dropdown-toggle">
								<i class="menu-icon fa fa-pencil-square-o"></i>
								<span class="menu-text">  Reports </span>

								<b class="arrow fa fa-angle-down"></b>
							</a>

							<b class="arrow"></b>

							<ul class="submenu">

								<li class="">
									<a href="#">
										<i class="menu-icon fa fa-caret-right"></i>
										Consumption Report
									</a>

									<b class="arrow"></b>
								</li>
							</ul>
						</li>
					<?php
					}*/
					?>

					
					

				
				</ul><!-- /.nav-list -->

				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>
			</div>
