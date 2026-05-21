<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']!="")
{
	include "../../CommonUtilities/Connections.php";
	include_once "../../CommonUtilities/Functions.php";

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once("../title.php");?>
    	<script src="angularjs/welcome-script.js"></script>
    	
      <script src="../../assets/js/ng-google-chart.min.js" type="text/javascript"></script>


      <!-- <script src="http://cdn.zingchart.com/zingchart.min.js"></script>
<script src="http://cdn.zingchart.com/angular/zingchart-angularjs.js"></script> -->
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="CategoryModule" data-ng-controller="CategoryController" ng-init="GetSalesData();GetMonthOrder();GetMonthtopsales();GetMonthtopCustomers();GetPOAct();GetProductionAct();GetSalesAct()">
		 <!-- ng-init="GetProjectData();GetRequestData();GetReviseRequest();GetSurplusRequest();GetCategory();DBInventory()" -->
		<?php include_once("../top.php");?>

		<div class="main-container ace-save-state" id="main-container">

			<?php include_once("sidebar.php");?>
			<div class="main-content">
				<?php include_once("../loader.php");?>
				<div class="main-content-inner">

					<!-- <div class="breadcrumbs ace-save-state" id="breadcrumbs">
						<ul class="breadcrumb">
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>
							</li>
							<li class="active">Dashboard</li>
						</ul>
					</div> -->

					<div class="page-content" id="no-more-tables">
						<div class="row" style="display: none">
							<div class="col-sm-12">
								<div class="widget-box">
									<div class="widget-header widget-header-flat widget-header-small">
										<h5 class="widget-title">
											<i class="ace-icon fa fa-signal"></i>
											SALES ORDER
										</h5>

										<div class="widget-toolbar no-border">
											<div class="inline dropdown-hover">
												<button class="btn btn-minier btn-primary">
													{{OrderTitle}}
													<i class="ace-icon fa fa-angle-down icon-on-right bigger-110"></i>
												</button>

												<ul class="dropdown-menu dropdown-menu-right dropdown-125 dropdown-lighter dropdown-close dropdown-caret">
													<li>
														<a href="" ng-click="GetMonthOrder()">
															<i class="ace-icon fa fa-caret-right bigger-110">&nbsp;</i>
															This Month
														</a>
													</li>

													<li>
														<a href="" ng-click="GetLastMonthOrder()">
															<i class="ace-icon fa fa-caret-right bigger-110">&nbsp;</i>
															Last Month
														</a>
													</li>
												</ul>
											</div>
										</div>
									</div>

									<div class="widget-body">
										<div class="widget-main">
											<!-- <div id="piechart-placeholder"></div> -->
											<div class="clearfix"></div>
											<div class="col-xs-12 col-sm-10">
											<div class="center">
												<span class="btn  btn-lg btn-light no-hover">
													<span class="line-height-1 bigger-170 blue"> {{draftcount}} </span>

													<br>
													<span class="line-height-1 smaller-90"> Draft </span>
												</span>
												&nbsp;
												<span class="btn btn-lg btn-yellow no-hover">
													<span class="line-height-1 bigger-170"> {{confirmcount}} </span>

													<br>
													<span class="line-height-1 smaller-90"> Confirmed </span>
												</span>
												<!-- &nbsp;
												<span class="btn btn-lg btn-pink no-hover">
													<span class="line-height-1 bigger-170"> {{packedcount}} </span>

													<br>
													<span class="line-height-1 smaller-90"> Packed </span>
												</span>
												&nbsp;
												<span class="btn  btn-lg btn-success no-hover">
													<span class="line-height-1 bigger-170"> {{shipcount}} </span>

													<br>
													<span class="line-height-1 smaller-90"> Shipped </span>
												</span> -->
												&nbsp;
												<span class="btn  btn-lg btn-primary no-hover">
													<span class="line-height-1 bigger-170"> {{invoicecount}} </span>

													<br>
													<span class="line-height-1 smaller-90"> Invoiced </span>
												</span>
												&nbsp;
												<span class="btn btn-lg btn-grey no-hover">
													<span class="line-height-1 bigger-170"><i class="ace-icon fa fa-inr"></i> {{invoicetotal || 0 | number:2 }} </span>

													<br>
													<span class="line-height-1 smaller-90"> Total Sales </span>
												</span>
											</div>
											<div class="space-6"></div>
										</div>
									
									<div class="clearfix">&nbsp;</div>	
									

										</div><!-- /.widget-main -->
									</div><!-- /.widget-body -->
								</div><!-- /.widget-box -->
							</div><!-- /.col -->
							
						</div>
<!--						<h2>Today Activities</h2>-->
						<div class="row" ng-if="PurcahseArr.length>0">
							<div class="col-sm-12">
								<div class="widget-box">
									<div class="widget-header widget-header-flat widget-header-small">
										<h5 class="widget-title">
											<i class="ace-icon fa fa-signal"></i>
											Today Purchase Activity
										</h5>
									</div>

									<div class="widget-body">
										<div class="widget-main no-padding">
											<table class="table table-bordered table-striped">
												<thead class="cf">
												<tr>
													<th>Date</th>
													<!-- <th>GoDown</th> -->
													<th>Product Name</th>
													<th>Roll No</th>
													<th>Quantity</th>
												</tr>
											</thead>										
											<tbody >

												<tr ng-repeat="cat in PurcahseArr">
													<td data-title="Date">{{cat.TDate | date: 'd MMM y'}}</td>
													<!-- <td data-title="GoDown">{{cat.GoDownName}}</td> -->
													<td data-title="Product Name">{{cat.Micron}} {{cat.ProductName}} {{cat.ProductSize}} MM</td>
													
													<td data-title="Roll No">{{cat.UniqueRollNo}}</td>
													<td data-title="Quantity">{{cat.Quantity}} {{cat.Unit}}</td>
												</tr>
												
											</tbody>
											</table>
										</div><!-- /.widget-main -->
									</div>
								</div>
							</div>
						</div>
						<p>&nbsp;</p>
						<div class="row" ng-if="ProductionArr.length>0">
							<div class="col-sm-12">
								<div class="widget-box">
									<div class="widget-header widget-header-flat widget-header-small">
										<h5 class="widget-title">
											<i class="ace-icon fa fa-signal"></i>
											Today Production Activity
										</h5>
									</div>

									<div class="widget-body">
										<div class="widget-main no-padding">
											<table class="table table-bordered table-striped">
											<thead class="cf">
												<tr>
													<th>Date</th>
													<!-- <th>GoDown</th> -->
													<th>From Product</th>
													<th>Roll No</th>
													<th>Cutting Details</th>
												</tr>
											</thead>										
											<tbody >

												<tr ng-repeat="cat in ProductionArr">
													<td data-title="Date">{{cat.SlitDate | date: 'd MMM y'}}</td>
													<!-- <td data-title="GoDown">{{cat.GoDownName}}</td> -->
													<td data-title="From Product">{{cat.Micron}} {{cat.ProductName}} {{cat.ProductSize}} MM</td>
													
													<td data-title="Roll No">{{cat.UniqueRollNo}}</td>
													<td data-title="Cutting Details">
														<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf" >
														<thead>
															<th>Product</th>
															<th>Roll No</th>
															<th>Weight / Qty</th>
														</thead>
													<tbody >
													<tr ng-repeat="sp in cat.data2">
														<td>{{sp.NewMicron}} {{sp.NewProductName}} {{sp.NewProductSize}} MM </td>
														<td>{{sp.RollNo}}</td>
														<td>
															<p ng-if="sp.SlitQty=='0.000' || sp.SlitQty=='0.00' || sp.SlitQty=='0'"><span class="label label-info arrowed-right arrowed-in" >Not yet added</span></p>
															<p ng-if="sp.SlitQty!='0.000'">{{sp.SlitQty}} {{sp.NewUnit}}</p>
														</td>
													</tr>
													</tbody>	
												</table>
													</td>
												</tr>
												
											</tbody>
											</table>
										</div><!-- /.widget-main -->
									</div>
								</div>
							</div>
						</div>
						<p>&nbsp;</p>
						<div class="row"  ng-if="SalesArr.length>0">
							<div class="col-sm-12">
								<div class="widget-box">
									<div class="widget-header widget-header-flat widget-header-small">
										<h5 class="widget-title">
											<i class="ace-icon fa fa-signal"></i>
											Today Sales Activity
										</h5>
									</div>

									<div class="widget-body">
										<div class="widget-main no-padding">
											<table class="table table-bordered table-striped">
											<thead class="cf">
												<tr>
													<th>Date</th>
													<th>Product Name</th>
													<th>Roll No</th>
													<th>Quantity</th>
												</tr>
											</thead>										
											<tbody >

												<tr ng-repeat="cat in SalesArr">
													<td data-title="Date">{{cat.InvDate | date: 'd MMM y'}}</td>
													<td data-title="Product Name">{{cat.Micron}} {{cat.ProductName}} {{cat.ProductSize}} MM</td>
													
													<td data-title="Roll No">{{cat.UniqueRollNo}}</td>
													<td data-title="Quantity">{{cat.Quantity}} {{cat.Unit}}</td>
												</tr>
												
											</tbody>
											</table>
										</div><!-- /.widget-main -->
									</div>
								</div>
							</div>
						</div>
						<div class="row" style="display: none">
							<div class="col-sm-8">
								<div class="widget-box">
									<div class="widget-header widget-header-flat widget-header-small">
										<h5 class="widget-title">
											<i class="ace-icon fa fa-signal"></i>
											Product Details
										</h5>
									</div>

									<div class="widget-body">
										<div class="widget-main">
										<div>
											<span class="label label-xlg label-pink arrowed-right pull-left">Reorder Level Products</span>
											<a href="reorder-items.php" class="btn pull-right">{{lowitemcount}}</a>
										</div>
										<div class="clearfix">&nbsp;</div>

										<!-- <div>
											<span class="label label-xlg label-pink arrowed-right pull-left">Expiry Products</span>
											<a href="expire-items.php" class="btn pull-right">{{ExpiredCount}}</a>
											<div class="clearfix">&nbsp;</div>
										</div> -->
<div class="clearfix">&nbsp;</div>


										<div><span class="label label-xlg label-pink arrowed-right pull-left">Total Categories</span>  
											<a  href="category.php" class="btn pull-right">{{Catcount}}</a>
											<div class="clearfix">&nbsp;</div>
										</div>
<div class="clearfix">&nbsp;</div>
										<div>
											<span class="label label-xlg label-pink arrowed-right pull-left">Total Products</span>
											<a href="product-types.php" class="btn pull-right">{{productcount}}</a>
											<div class="clearfix">&nbsp;</div>
										</div>
										
										</div><!-- /.widget-main -->
									</div><!-- /.widget-body -->
								</div><!-- /.widget-box -->
							</div><!-- /.col -->

							<div class="col-sm-6" style="display: none">
								<div class="widget-box">
									<div class="widget-header widget-header-flat widget-header-small">
										<h5 class="widget-title">
											<i class="ace-icon fa fa-signal"></i> 
											Inventory Summary
										</h5>
									</div>

									<div class="widget-body">
										<div class="widget-main">									
										<div class="grid12">
											<h4 class="grey ">
												&nbsp; Stock in Hand
											<span class="bigger pull-right">{{TotalQty}}</span></h4>
										</div>
										<hr>
										<div class="grid12">
											<h4 class="grey ">
												&nbsp; Total Stock Value
											<span class="bigger pull-right"><i class="ace-icon fa fa-inr"></i> {{TotalStockVal | number:2}}</span></h4>
										</div>
										<hr>
										<div class="grid12">
											<h4 class="grey">
												&nbsp; Total Outstanding Value
											<a href="list-outstanding.php" class="bigger pull-right" ><i class="ace-icon fa fa-inr"></i> {{TotalOutstanding | number:2}}</a></h4>
										</div>
										<hr>
										<div class="grid12">
											<h4 class="grey">
												&nbsp; Product Margin Value
											<a href="list-product-margin.php" class="bigger pull-right" ><i class="ace-icon fa fa-inr"></i> {{TotalMarginVal | number:2}}</a></h4>
										</div>

										

										<div class="clearfix">&nbsp;</div>	

										</div><!-- /.widget-main -->
									</div><!-- /.widget-body -->
								</div><!-- /.widget-box -->
							</div><!-- /.col -->							
						</div>

						<p>&nbsp;</p>
						<div class="row" style="display: none">
							<div class="col-sm-8">
								<div class="widget-box">
									<div class="widget-header widget-header-flat widget-header-small">
										<h5 class="widget-title">
											<i class="ace-icon fa fa-signal"></i>
											Top 5 Selling Items
										</h5>

										<div class="widget-toolbar no-border">
											<div class="inline dropdown-hover">
												<button class="btn btn-minier btn-primary">
													{{SellingTitle}}
													<i class="ace-icon fa fa-angle-down icon-on-right bigger-110"></i>
												</button>

												<ul class="dropdown-menu dropdown-menu-right dropdown-125 dropdown-lighter dropdown-close dropdown-caret">
													<li>
														<a href="" ng-click="GetMonthtopsales()">
															<i class="ace-icon fa fa-caret-right bigger-110">&nbsp;</i>
															This Month
														</a>
													</li>

													<li>
														<a href="" ng-click="GetLastMonthtopsales()">
															<i class="ace-icon fa fa-caret-right bigger-110">&nbsp;</i>
															Last Month
														</a>
													</li>
												</ul>
											</div>
										</div>
									</div>

									<div class="widget-body">
										<div class="widget-main">
											<!-- <div id="piechart-placeholder"></div> -->
											<div class="clearfix"></div>
											<div class="col-xs-12 col-sm-10">
											<div class="row">
												<div class="col-xs-6 col-sm-3 col-md-3"  ng-repeat="tops in TopItemSalesArr">
												<div class="thumbnail search-thumbnail">
														<img class="media-object" ng-src="../ProductImages/{{tops.FileName}}" />
														<div class="caption">

															<p class="search-title">{{tops.ProductName}}
															</p>
														</div>
													</div>
												</div>
											</div>
											<div class="space-6"></div>
										</div>
										<div class="clearfix">&nbsp;</div>	
										</div><!-- /.widget-main -->
									</div><!-- /.widget-body -->
								</div><!-- /.widget-box -->
							</div><!-- /.col -->

							<div class="col-sm-4">
								<div class="widget-box">
									<div class="widget-header widget-header-flat widget-header-small">
										<h5 class="widget-title">
											<i class="ace-icon fa fa-signal"></i>
											Top 3 Customers
										</h5>

										<div class="widget-toolbar no-border">
											<div class="inline dropdown-hover">
												<button class="btn btn-minier btn-primary">
													{{CustomerTitle}}
													<i class="ace-icon fa fa-angle-down icon-on-right bigger-110"></i>
												</button>

												<ul class="dropdown-menu dropdown-menu-right dropdown-125 dropdown-lighter dropdown-close dropdown-caret">
													<li>
														<a href="" ng-click="GetMonthtopCustomers()">
															<i class="ace-icon fa fa-caret-right bigger-110">&nbsp;</i>
															This Month
														</a>
													</li>

													<li>
														<a href="" ng-click="GetLastMonthtopCustomers()">
															<i class="ace-icon fa fa-caret-right bigger-110">&nbsp;</i>
															Last Month
														</a>
													</li>
												</ul>
											</div>
										</div>
									</div>

									<div class="widget-body">
										<div class="widget-main">
											<!-- <div id="piechart-placeholder"></div> -->
											<div class="clearfix"></div>
											<div class="col-xs-12 col-sm-10">
											<div class="row">
												<div class="grid12" ng-repeat="cust in TopCustomerSalesArr">
													<h4 class="grey ">
														&nbsp; {{cust.CustomerName}}
													<span class="bigger pull-right"><i class="ace-icon fa fa-inr"></i> {{cust.InvoiceTotal | number:2}}</span></h4>
													<hr>
												</div>
											</div>
											<div class="space-6"></div>
										</div>
										<div class="clearfix">&nbsp;</div>	
										</div><!-- /.widget-main -->
									</div><!-- /.widget-body -->
								</div><!-- /.widget-box -->
							</div><!-- /.col -->
						</div>
						<p>&nbsp;</p>
						
						<div class="row">
							<div class="col-md-12">
								<div google-chart chart="ProductChartObject" style="height:500px; width:100%;"></div>
							</div><!-- /.col -->
						</div><!-- /.row -->
			</div><!-- /.page-content -->
		</div>
	</div><!-- /.main-content -->

			<!-- /.main-container ending in footer page -->
		<?php include_once("../footer.php");?>
		<script type="text/javascript">
			jQuery(function($) {
							$('.dialogs,.comments').ace_scroll({
					size: 300
			    });
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
