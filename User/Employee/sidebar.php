<?php
$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$url = $_SERVER['REQUEST_URI'];
$pagename = basename($url);
?>
<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>
			<div id="sidebar" class="sidebar responsive ace-save-state">
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
				/*if($pagename == "category.php" || $pagename == "sub-category.php" || $pagename == "sub-category-level2.php" || $pagename == "sub-category-level3.php" || $pagename== "list-brands.php" || $pagename== "list-sizes.php" || $pagename== "list-employees.php")
				{
				?>
					<li class="active open">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-desktop"></i>
							<span class="menu-text">
								MASTERS
							</span>
							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<?php	
							if($pagename == "category.php")
							{
							?>
								<li class="active">
									<a href="category.php"><i class="menu-icon fa fa-caret-right"></i>Main Categories</a>
									<b class="arrow"></b>
								</li>
							<?php
							}	
							else
							{
							?>
								<li class="">
									<a href="category.php"><i class="menu-icon fa fa-caret-right"></i>Main Categories</a>
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
							<i class="menu-icon fa fa-desktop"></i>
							<span class="menu-text">
								MASTERS
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>
						<ul class="submenu">
							<li class="">
								<a href="category.php">
									<i class="menu-icon fa fa-caret-right"></i>
									Main Categories
								</a>
								<b class="arrow"></b>
							</li>
						</ul>
					</li>
					<?php
					}
					if($pagename == "inventory-adjust.php" || $pagename == "list-inventory-adjust.php")
					{
					?>
						<li class="active open">
							<a href="list-requests.php" class="dropdown-toggle">
								<i class="menu-icon fa fa-pencil-square-o"></i>
								<span class="menu-text"> Inventory Adjust </span>

								<b class="arrow fa fa-angle-down"></b>
							</a>

							<b class="arrow"></b>
							<ul class="submenu">
								<?php
								if($pagename == "inventory-adjust.php")
								{
								?>
									<li class="active">
										<a href="inventory-adjust.php"><i class="menu-icon fa fa-caret-right"></i>Add Inventory Adjustments</a>
										<b class="arrow"></b>
									</li>
								<?php
								}	
								else
								{
								?>
									<li class="">
										<a href="inventory-adjust.php"><i class="menu-icon fa fa-caret-right"></i>Add Inventory Adjustments </a>
										<b class="arrow"></b>
									</li>
								<?php	
								}
								if($pagename == "list-inventory-adjust.php")
								{
								?>
									<li class="active">
										<a href="list-inventory-adjust.php"><i class="menu-icon fa fa-caret-right"></i>List of Inventory Adjustments</a>
										<b class="arrow"></b>
									</li>
								<?php
								}	
								else
								{
								?>
									<li class="">
										<a href="list-inventory-adjust.php"><i class="menu-icon fa fa-caret-right"></i>List of Inventory Adjustments </a>
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
							<a href="list-requests.php" class="dropdown-toggle">
								<i class="menu-icon fa fa-pencil-square-o"></i>
								<span class="menu-text"> Inventory Adjust </span>

								<b class="arrow fa fa-angle-down"></b>
							</a>

							<b class="arrow"></b>

							<ul class="submenu">

								<li class="">
									<a href="inventory-adjust.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Add Inventory Adjustments
									</a>

									<b class="arrow"></b>
								</li>
								<li class="">
									<a href="list-inventory-adjust.php">
										<i class="menu-icon fa fa-caret-right"></i>
										List of Inventory Adjustments
									</a>

									<b class="arrow"></b>
								</li>
							</ul>
						</li>
					<?php
					}
					*/
					if($pagename == "category.php")
					{
					?>
						<li class="active">
						<a href="category.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Main Category
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
						<a href="category.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Main Category
							</span>
						</a>

						<b class="arrow"></b>
						</li>
					<?php
					}
					if($pagename == "add-product-types.php" || $pagename == "product-types.php" || $pagename == "search-product-types.php")
					{
					?>
						<li class="active open">
							<a href="#" class="dropdown-toggle">
								<i class="menu-icon fa fa-pencil-square-o"></i>
								<span class="menu-text"> Items </span>

								<b class="arrow fa fa-angle-down"></b>
							</a>

							<b class="arrow"></b>
							<ul class="submenu">

								<?php
								if($pagename == "add-product-types.php")
								{
								?>

									<li class="active">
										<a href="add-product-types.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Add
										</a>

										<b class="arrdow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="add-product-types.php"><i class="menu-icon fa fa-caret-right"></i>
										Add
									</a>
										<b class="arrow"></b>
									</li>
								<?php	
								}
								if($pagename == "product-types.php")
								{
								?>

									<li class="active">
										<a href="product-types.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Listing
										</a>

										<b class="arrdow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="product-types.php"><i class="menu-icon fa fa-caret-right"></i>
										Listing
									</a>
										<b class="arrow"></b>
									</li>
								<?php	
								}
								if($pagename == "search-product-types.php")
								{
								?>

									<li class="active">
										<a href="search-product-types.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Search
										</a>

										<b class="arrdow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="search-product-types.php"><i class="menu-icon fa fa-caret-right"></i>
										Search
									</a>
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
								<span class="menu-text">  Items </span>

								<b class="arrow fa fa-angle-down"></b>
							</a>

							<b class="arrow"></b>

							<ul class="submenu">
								<li class="">
									<a href="add-product-types.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Add
									</a>

									<b class="arrow"></b>
								</li>
								<li class="">
									<a href="product-types.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Listing
									</a>

									<b class="arrow"></b>
								</li>
								<li class="">
									<a href="search-product-types.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Search
									</a>

									<b class="arrow"></b>
								</li>
							</ul>
						</li>
					<?php
					}
					if($pagename == "add-suppliers.php" || $pagename == "list-suppliers.php" || $pagename == "search-suppliers.php")
					{
					?>
						<li class="active open">
							<a href="#" class="dropdown-toggle">
								<i class="menu-icon fa fa-pencil-square-o"></i>
								<span class="menu-text"> Party </span>

								<b class="arrow fa fa-angle-down"></b>
							</a>

							<b class="arrow"></b>
							<ul class="submenu">

								<?php
								if($pagename == "add-suppliers.php")
								{
								?>

									<li class="active">
										<a href="add-suppliers.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Add
										</a>

										<b class="arrdow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="add-suppliers.php"><i class="menu-icon fa fa-caret-right"></i>
										Add
									</a>
										<b class="arrow"></b>
									</li>
								<?php	
								}
								if($pagename == "list-suppliers.php")
								{
								?>

									<li class="active">
										<a href="list-suppliers.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Listing
										</a>

										<b class="arrdow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="list-suppliers.php"><i class="menu-icon fa fa-caret-right"></i>
										Listing
									</a>
										<b class="arrow"></b>
									</li>
								<?php	
								}
								if($pagename == "search-suppliers.php")
								{
								?>

									<li class="active">
										<a href="search-suppliers.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Search
										</a>

										<b class="arrdow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="search-suppliers.php"><i class="menu-icon fa fa-caret-right"></i>
										Search
									</a>
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
								<span class="menu-text">  Party </span>

								<b class="arrow fa fa-angle-down"></b>
							</a>

							<b class="arrow"></b>

							<ul class="submenu">
								<li class="">
									<a href="add-suppliers.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Add
									</a>

									<b class="arrow"></b>
								</li>
								<li class="">
									<a href="list-suppliers.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Listing
									</a>

									<b class="arrow"></b>
								</li>
								<li class="">
									<a href="search-suppliers.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Search
									</a>

									<b class="arrow"></b>
								</li>
							</ul>
						</li>
					<?php
					}
					if($pagename == "add-inward-entry.php" || $pagename == "list-inward-entries.php")
					{
					?>
						<li class="active open">
							<a href="#" class="dropdown-toggle">
								<i class="menu-icon fa fa-pencil-square-o"></i>
								<span class="menu-text"> Inward Entry </span>

								<b class="arrow fa fa-angle-down"></b>
							</a>

							<b class="arrow"></b>
							<ul class="submenu">
								<?php
								if($pagename == "add-inward-entry.php")
								{
								?>
									<li class="active">
										<a href="add-inward-entry.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Add
										</a>
										<b class="arrow"></b>
									</li>
								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="add-inward-entry.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Add
										</a>
										<b class="arrow"></b>
									</li>
								<?php	
								}
								if($pagename == "list-inward-entries.php")
								{
								?>
									<li class="active">
										<a href="list-inward-entries.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Listing
										</a>
										<b class="arrow"></b>
									</li>
								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="list-inward-entries.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Listing
										</a>
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
								<span class="menu-text">  Inward Entry </span>

								<b class="arrow fa fa-angle-down"></b>
							</a>

							<b class="arrow"></b>

							<ul class="submenu">
								<li class="">
									<a href="add-inward-entry.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Add
									</a>
									<b class="arrow"></b>
								</li>
								<li class="">
									<a href="list-inward-entries.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Listing
									</a>
									<b class="arrow"></b>
								</li>
							</ul>
						</li>
					<?php
					}
					if($pagename == "list-roll-inventory.php")
					{
					?>
						<li class="active">
						<a href="list-roll-inventory.php">
							<i class="menu-icon fa fa-pencil-square-o"></i>
							<span class="menu-text">
								Inventory
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
						<a href="list-roll-inventory.php">
							<i class="menu-icon fa fa-pencil-square-o"></i>
							<span class="menu-text">
								Inventory
							</span>
						</a>
						<b class="arrow"></b>
						</li>
					<?php
					}
					if($pagename == "add-raw-purchase.php" || $pagename == "list-raw-purchase.php" || $pagename == "search-raw-purchase.php")
					{
					?>
						<li class="active open">
							<a href="#" class="dropdown-toggle">
								<i class="menu-icon fa fa-pencil-square-o"></i>
								<span class="menu-text"> Raw Purchase </span>

								<b class="arrow fa fa-angle-down"></b>
							</a>

							<b class="arrow"></b>
							<ul class="submenu">

								<?php
								if($pagename == "add-raw-purchase.php")
								{
								?>

									<li class="active">
										<a href="add-raw-purchase.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Add
										</a>

										<b class="arrdow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="add-raw-purchase.php"><i class="menu-icon fa fa-caret-right"></i>
										Add
									</a>
										<b class="arrow"></b>
									</li>
								<?php	
								}
								if($pagename == "list-raw-purchase.php")
								{
								?>

									<li class="active">
										<a href="list-raw-purchase.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Listing
										</a>

										<b class="arrdow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="list-raw-purchase.php"><i class="menu-icon fa fa-caret-right"></i>
										Listing
									</a>
										<b class="arrow"></b>
									</li>
								<?php	
								}
								if($pagename == "search-raw-purchase.php")
								{
								?>

									<li class="active">
										<a href="search-raw-purchase.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Search
										</a>

										<b class="arrdow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="search-raw-purchase.php"><i class="menu-icon fa fa-caret-right"></i>
										Search
									</a>
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
								<span class="menu-text">  Raw Purchase </span>

								<b class="arrow fa fa-angle-down"></b>
							</a>

							<b class="arrow"></b>

							<ul class="submenu">
								<li class="">
									<a href="add-raw-purchase.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Add
									</a>

									<b class="arrow"></b>
								</li>
								<li class="">
									<a href="list-raw-purchase.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Listing
									</a>

									<b class="arrow"></b>
								</li>
								<li class="">
									<a href="search-raw-purchase.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Search
									</a>

									<b class="arrow"></b>
								</li>
							</ul>
						</li>
					<?php
					}
					if($pagename == "add-slitting.php" || $pagename == "slitting.php" || $pagename == "search-slitting.php")
					{
					?>
						<li class="active open">
							<a href="#" class="dropdown-toggle">
								<i class="menu-icon fa fa-pencil-square-o"></i>
								<span class="menu-text"> Slitting </span>

								<b class="arrow fa fa-angle-down"></b>
							</a>

							<b class="arrow"></b>
							<ul class="submenu">

								<?php
								if($pagename == "add-slitting.php")
								{
								?>

									<li class="active">
										<a href="add-slitting.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Add
										</a>

										<b class="arrdow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="add-slitting.php"><i class="menu-icon fa fa-caret-right"></i>
										Add
									</a>
										<b class="arrow"></b>
									</li>
								<?php	
								}
								if($pagename == "slitting.php")
								{
								?>

									<li class="active">
										<a href="slitting.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Listing
										</a>

										<b class="arrdow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="slitting.php"><i class="menu-icon fa fa-caret-right"></i>
										Listing
									</a>
										<b class="arrow"></b>
									</li>
								<?php	
								}
								if($pagename == "search-slitting.php")
								{
								?>

									<li class="active">
										<a href="search-slitting.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Search
										</a>

										<b class="arrdow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="search-slitting.php"><i class="menu-icon fa fa-caret-right"></i>
										Search
									</a>
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
								<span class="menu-text">  Slitting </span>

								<b class="arrow fa fa-angle-down"></b>
							</a>

							<b class="arrow"></b>

							<ul class="submenu">
								<li class="">
									<a href="add-slitting.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Add
									</a>

									<b class="arrow"></b>
								</li>
								<li class="">
									<a href="slitting.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Listing
									</a>

									<b class="arrow"></b>
								</li>
								<li class="">
									<a href="search-slitting.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Search
									</a>

									<b class="arrow"></b>
								</li>
							</ul>
						</li>
					<?php
					}
					if($pagename == "add-new-invoice.php" || $pagename == "new-invoice.php" || $pagename == "search-new-invoice.php")
					{
					?>
						<li class="active open">
							<a href="#" class="dropdown-toggle">
								<i class="menu-icon fa fa-pencil-square-o"></i>
								<span class="menu-text"> Dispatch </span>

								<b class="arrow fa fa-angle-down"></b>
							</a>

							<b class="arrow"></b>
							<ul class="submenu">

								<?php
								if($pagename == "add-new-invoice.php")
								{
								?>

									<li class="active">
										<a href="add-new-invoice.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Add
										</a>

										<b class="arrdow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="add-new-invoice.php"><i class="menu-icon fa fa-caret-right"></i>
										Add
									</a>
										<b class="arrow"></b>
									</li>
								<?php	
								}
								if($pagename == "new-invoice.php")
								{
								?>

									<li class="active">
										<a href="new-invoice.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Listing
										</a>

										<b class="arrdow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="new-invoice.php"><i class="menu-icon fa fa-caret-right"></i>
										Listing
									</a>
										<b class="arrow"></b>
									</li>
								<?php	
								}
								if($pagename == "search-new-invoice.php")
								{
								?>

									<li class="active">
										<a href="search-new-invoice.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Search
										</a>

										<b class="arrdow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="search-new-invoice.php"><i class="menu-icon fa fa-caret-right"></i>
										Search
									</a>
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
								<span class="menu-text">  Dispatch </span>

								<b class="arrow fa fa-angle-down"></b>
							</a>

							<b class="arrow"></b>

							<ul class="submenu">
								<li class="">
									<a href="add-new-invoice.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Add
									</a>

									<b class="arrow"></b>
								</li>
								<li class="">
									<a href="new-invoice.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Listing
									</a>

									<b class="arrow"></b>
								</li>
								<li class="">
									<a href="search-new-invoice.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Search
									</a>

									<b class="arrow"></b>
								</li>
							</ul>
						</li>
					<?php
					}
					/*
					if($pagename == "slitting.php")
					{
					?>
						<li class="active">
						<a href="slitting.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Slitting
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
						<a href="slitting.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Slitting
							</span>
						</a>

						<b class="arrow"></b>
						</li>
					<?php
					}
					
					
					if($pagename == "orders.php" || $pagename == "open-order.php")
					{
					?>
						<li class="active">
						<a href="orders.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Sales Orders
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
						<a href="orders.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Sales Orders
							</span>
						</a>

						<b class="arrow"></b>
						</li>
					<?php
					}
					if($pagename == "new-invoice.php" || $pagename == "new-invoice.php")
					{
					?>
						<li class="active">
						<a href="new-invoice.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Dispatch
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
						<a href="new-invoice.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Dispatch
							</span>
						</a>

						<b class="arrow"></b>
						</li>
					<?php
					}*/
					/*if($pagename == "invoice-package.php")
					{
					?>
						<li class="active">
						<a href="invoice-package.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Packages
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
						<a href="invoice-package.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Packages
							</span>
						</a>

						<b class="arrow"></b>
						</li>
					<?php
					}
					if($pagename == "shipping.php")
					{
					?>
						<li class="active">
						<a href="shipping.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Delivery Challan
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
						<a href="shipping.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Delivery Challan
							</span>
						</a>

						<b class="arrow"></b>
						</li>
					<?php
					}

					//if($pagename == "delivery-challans.php")
					{
					?>
						<li class="active">
						<a href="delivery-challans.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Delivery Challans
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
						<a href="delivery-challans.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Delivery Challans
							</span>
						</a>

						<b class="arrow"></b>
						</li>
					<?php
					}//
					
					if($pagename == "payment-received.php")
					{
					?>
						<li class="active">
						<a href="payment-received.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Payment Received
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
						<a href="payment-received.php">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Payment Received
							</span>
						</a>

						<b class="arrow"></b>
						</li>
					<?php
					}*/	
					
					if($pagename == "order.php")
					{
					?>
						<li class="active">
						<a href="order.php">
							<i class="menu-icon fa fa-shopping-cart"></i>

							<span class="menu-text">
								Order
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
						<a href="order.php">
							<i class="menu-icon fa fa-shopping-cart"></i>

							<span class="menu-text">
								Order
							</span>
						</a>

						<b class="arrow"></b>
						</li>
					<?php
					}
					
					if($pagename == "datewise-sale-reports.php" || $pagename == "datewise-sale-detail-reports.php" || $pagename == "datewise-purchase-reports.php" || $pagename == "datewise-slitting-reports.php" || $pagename == "gain-loss-report.php" || $pagename == "item-wise-report.php" || $pagename == "godown-wise-report.php" || $pagename == "roll-wise-history.php" || $pagename == "stock-list.php")
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
								if($pagename == "stock-list.php")
								{
								?>

									<li class="active">
										<a href="stock-list.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Stock Report
										</a>

										<b class="arrdow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="stock-list.php"><i class="menu-icon fa fa-caret-right"></i>
										Stock Report
									</a>
										<b class="arrow"></b>
									</li>
								<?php	
								}
								if($pagename == "datewise-sale-reports.php")
								{
								?>

									<li class="active">
										<a href="datewise-sale-reports.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Datewise Sale report
										</a>

										<b class="arrdow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="datewise-sale-reports.php"><i class="menu-icon fa fa-caret-right"></i>
										Datewise Sale report
									</a>
										<b class="arrow"></b>
									</li>
								<?php	
								}
								if($pagename == "datewise-sale-detail-reports.php")
								{
								?>

									<li class="active">
										<a href="datewise-sale-detail-reports.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Datewise Sale Detail report
										</a>

										<b class="arrdow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="datewise-sale-detail-reports.php"><i class="menu-icon fa fa-caret-right"></i>
										Datewise Sale Detail report
									</a>
										<b class="arrow"></b>
									</li>
								<?php	
								}
								if($pagename == "datewise-purchase-reports.php")
								{
								?>

									<li class="active">
										<a href="datewise-purchase-reports.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Datewise Purchase report
										</a>

										<b class="arrdow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="datewise-purchase-reports.php"><i class="menu-icon fa fa-caret-right"></i>
										Datewise Purchase report
									</a>
										<b class="arrow"></b>
									</li>
								<?php	
								}
								if($pagename == "datewise-slitting-reports.php")
								{
								?>

									<li class="active">
										<a href="datewise-slitting-reports.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Datewise Production
										</a>

										<b class="arrdow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="datewise-slitting-reports.php"><i class="menu-icon fa fa-caret-right"></i>
										Datewise Production
									</a>
										<b class="arrow"></b>
									</li>
								<?php	
								}
								if($pagename == "gain-loss-report.php")
								{
								?>

									<li class="active">
										<a href="gain-loss-report.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Monthly report
										</a>

										<b class="arrow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="gain-loss-report.php"><i class="menu-icon fa fa-caret-right"></i>
										Monthly report
									</a>
										<b class="arrow"></b>
									</li>
								<?php	
								}
								if($pagename == "item-wise-report.php")
								{
								?>

									<li class="active">
										<a href="item-wise-report.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Item wise report
										</a>

										<b class="arrow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="item-wise-report.php"><i class="menu-icon fa fa-caret-right"></i>
										Item wise report
									</a>
										<b class="arrow"></b>
									</li>
								<?php	
								}
								if($pagename == "roll-wise-history.php")
								{
								?>

									<li class="active">
										<a href="roll-wise-history.php">
											<i class="menu-icon fa fa-caret-right"></i>
											Roll History
										</a>

										<b class="arrow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="roll-wise-history.php"><i class="menu-icon fa fa-caret-right"></i>
										Roll History
									</a>
										<b class="arrow"></b>
									</li>
								<?php	
								}
								/*if($pagename == "godown-wise-report.php")
								{
								?>

									<li class="active">
										<a href="godown-wise-report.php">
											<i class="menu-icon fa fa-caret-right"></i>
											GoDown wise Items
										</a>

										<b class="arrow"></b>
									</li>

								<?php
								}
								else
								{
								?>
									<li class="">
										<a href="godown-wise-report.php"><i class="menu-icon fa fa-caret-right"></i>
										GoDown wise Items
									</a>
										<b class="arrow"></b>
									</li>
								<?php	
								}*/
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
									<a href="stock-list.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Stock Report
									</a>

									<b class="arrow"></b>
								</li>
								<li class="">
									<a href="datewise-sale-reports.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Datewise Sale report
									</a>

									<b class="arrow"></b>
								</li>
								<li class="">
									<a href="datewise-sale-detail-reports.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Datewise Sale Detail report
									</a>

									<b class="arrow"></b>
								</li>
								<li class="">
									<a href="datewise-purchase-reports.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Datewise purchase report
									</a>

									<b class="arrow"></b>
								</li>
								<li class="">
									<a href="datewise-slitting-reports.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Datewise Production
									</a>

									<b class="arrow"></b>
								</li>
								<li class="">
									<a href="gain-loss-report.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Monthly report
									</a>

									<b class="arrow"></b>
								</li>
								<li class="">
									<a href="item-wise-report.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Item wise report
									</a>

									<b class="arrow"></b>
								</li>
								<li class="">
									<a href="roll-wise-history.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Roll History
									</a>

									<b class="arrow"></b>
								</li>
								<!--<li class="">
									<a href="godown-wise-report.php">
										<i class="menu-icon fa fa-caret-right"></i>
										GoDown wise Items
									</a>

									<b class="arrow"></b>
								</li>
								<li class="">
									<a href="ac-fabric.php">
										<i class="menu-icon fa fa-caret-right"></i>
										Account of Fabric Products
									</a>

									<b class="arrow"></b>
								</li>
								<li class="">
										<a href="material-dispatched-list.php"><i class="menu-icon fa fa-caret-right"></i>
										Account of Material Dispatched
									</a>
										<b class="arrow"></b>
								</li>
								 -->
							</ul>
						</li>
					<?php
					}
					?>
				
				</ul><!-- /.nav-list -->

				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>
			</div>
