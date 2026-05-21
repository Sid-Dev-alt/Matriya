<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']!="")
{
	$PkId =  $_REQUEST['Id'];
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once("../title.php");?>		
		<link rel="stylesheet" href="../../assets/css/colorbox.min.css" />
    	<script src="angularjs/open-dc-script.js"></script>
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="CategoryModule" data-ng-controller="CategoryController" data-ng-init="GetListData(<?php echo $PkId;?>)">
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
							<li class="active">Product Types</li>
						</ul>
					</div> -->

					<div class="page-content" >
					<div  id="">
						<div class="row">
							<div class="col-xs-12 page-header">
		                  	 <div class="col-xs-12 col-sm-12 col-lg-6">
		                  	 	<div class="" >
									<h1>{{DcId}}</h1>
									</div>
		<!-- /.page-header --></div>
		                  	 	<div class="col-xs-12 col-sm-12 col-lg-6">
				                    <a href="" data-ng-click="GotoAdd()" ng-show="FormList">
									<div class="label label-lg label-success arrowed-in arrowed-right pull-right" >
									<b>Add Product</b>
									</div></a>
			                  </div>
			              </div>							
						</div>

							<div class="row" ng-show="FormList">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="space-6"></div>

								<div class="dataTables_wrapper form-inline no-footer" ng-if="pagedItems.length!='0'">
									<!-- <div class="row">
										<div class="col-xs-6">
											<div id="dynamic-table_filter" class="dataTables_filter pull-left"><label>Search:
												<input type="search" class="form-control input-lg" placeholder="" aria-controls="dynamic-table"  data-ng-model="query[queryBy]"></label>
											</div>
										</div>
									</div> -->
									<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf"  datatable="ng" dt-options="vm.dtOptions">
										<thead class="cf">
											<tr>
												<!-- <th>Sl.No</th> -->
												<th>Date</th>
												<th>DC Id</th>
												<th>Reference</th>
												<th>Customer Name</th>
												<th>DC Status</th>
												<th>Invoiced</th>
												<th>Action</th>
											</tr>
										</thead>										
										<tbody >
											<tr  ng-repeat="cat in pagedItems">
												<!-- <td data-title="Sl.No">{{$index+1}}</td> -->
												<td data-title="Date">{{cat.DcDate | date: 'd MMM y'}}</td>
												<td data-title="Dc Id">{{cat.OrderId}}</td>
												<td data-title="Reference">{{cat.Reference}}
												</td>
												<td data-title="Customer Name">{{cat.CustomerName}}</td>
												<td data-title="Order Status">
													<span ng-if="cat.OrderStatus=='1'">Draft</span>
													<span ng-if="cat.OrderStatus=='2'">Sent</span>
													<span ng-if="cat.OrderStatus=='3'">Confirmed</span>
												</td>
												<td data-title="Invoiced">{{cat.InvoiceStatus}}
												</td>
												<td data-title="Action">
													<a href="open-order.php?Id={{cat.PkId}}"  class="btn btn-xs btn-info">OPEN &nbsp;
														<i class="ace-icon fa fa-pencil bigger-120"></i>
													</a>&nbsp;
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


					<div ng-show="FormAdd" >
						<div class="row">
							<div class="col-xs-12">
							<div class="tabbable">
								<ul class="nav nav-tabs padding-12 tab-color-blue background-blue">
									<li class="active" ng-if="InvoiceStatus=='0'">
										<a data-toggle="tab" href="#Next">What Next?</a>
									</li>


									<li>
										<a data-toggle="tab" href="#History">Comments & History</a>
									</li>
								</ul>

								<div class="tab-content">
									<div id="Next" class="tab-pane in active" ng-if="InvoiceStatus=='0'">
										<h2>Fulfill the Delivery Challan</h2>
										<p>You can create packages, shipments or invoices (in any sequence) to complete this DC order.</p>

										<a ng-if="InvoiceStatus=='0'" href="invoices.php?Id={{PkId}}&Type=DC" class="btn btn-sm btn-default">Convert to Invoice</a>
										<div class="clearfix">&nbsp;</div>
									</div>
							<!-- //first tab -->

								<div id="History" class="tab-pane">
									<p>{{CreatedTime}}</p>
								</div>
									<!-- //thitd tab close -->
								</div>
							</div>

						</div><!-- /.col -->
						</div><!-- /.row -->

						<div class="row">
							<div class="col-sm-10 col-sm-offset-1">
							<div class="widget-box transparent">
								<div class="widget-header widget-header-large">
									<h3 class="widget-title grey lighter">
										<i class="ace-icon fa fa-leaf green"></i>
										Delivery Challan
									</h3>

									<div class="widget-toolbar no-border invoice-info">
										<span class="invoice-info-label">Challan Id:</span>
										<span class="red">#{{DcId}}</span>

										<br>
										<span class="invoice-info-label">Date:</span>
										<span class="blue">{{DcDate | date:'d MMM y'}}</span>
									</div>

									<div class="widget-toolbar hidden-480">
										<a href="#">
											<i class="ace-icon fa fa-print"></i>
										</a>
									</div>
								</div>

								<div class="widget-body">
									<div class="widget-main padding-24">
										<div class="row">
											<div class="col-sm-6">
												<div class="row">
													<div class="col-xs-11 label label-lg label-info arrowed-in arrowed-right">
														<b>STATUS</b>
													</div>
												</div>

												<div>
													<div class="col-xs-12">
													<h4 class="header smaller lighter grey">
														<i class="ace-icon fa fa-spinner fa-spin green bigger-125"></i>
														Delivery Challan 
														<span  class="label label-grey arrowed" ng-if="DCStatus=='1'">Draft</span>
														<span class="label label-grey arrowed" ng-if="DCStatus=='2'">Convert to Open</span>
														<span class="label label-grey arrowed" ng-if="DCStatus=='3'">Delivered</span>
													</h4>

													<h4 class="header smaller lighter grey">
														<i class="ace-icon fa fa-spinner fa-spin red bigger-125"></i>
														Invoice 
														<span class="label label-grey arrowed" ng-if="InvoiceStatus=='0'">Not Invoiced</span>
														<span class="label label-grey arrowed" ng-if="InvoiceStatus=='1'">Invoiced</span>

													</h4>
													</div>
												</div>
											</div><!-- /.col -->

											<div class="col-sm-6">
												<div class="row">
													<div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
														<b>Customer Info</b>
													</div>
												</div>

												<div>
													<ul class="list-unstyled  spaced">
														<li>
															<i class="ace-icon fa fa-user green"></i> {{CustomerName}}
														</li>

														<li>
															<i class="ace-icon fa fa-envelope green"></i> {{CEmailId}}
														</li>

														<li>
															<i class="ace-icon fa fa-phone green"></i> {{CMobile}}
														</li>
													</ul>
												</div>
											</div><!-- /.col -->
										</div><!-- /.row -->

										<div class="space"></div>

										<div>
											<table class="table table-striped table-bordered">
												<thead>
													<tr>
														<th>Product</th>
														<th>Quantity</th>
														<th>Rate</th>
														<th>Total</th>
													</tr>
												</thead>

												<tbody>
													<tr ng-repeat="cap in pagedItems">
														<td>{{cap.product}}</td>
														<td>{{cap.quantity}}</td>
														<td>{{cap.price}}</td>
														<td>{{cap.Amount}}</td>
													</tr>
												</tbody>
											</table>
										</div>

										<div class="hr hr8 hr-double hr-dotted"></div>

										<div class="row">
											<div class="col-sm-5 pull-right">
												<h5 class="pull-right">
													Sub amount :
													<span class="red">{{SubTotal}}</span>
												</h5>
												<div class="clearfix">&nbsp;</div>
												<h5 class="pull-right">
													Discount amount :
													<span class="red">{{DiscountAmount}}</span>
												</h5>
												<div class="clearfix">&nbsp;</div>
												<h4 class="pull-right">
													Total amount :
													<span class="red"> <i class="ace-icon fa fa-inr red"></i> {{OrderTotal}}</span>
												</h4>
											</div>
											<div class="col-sm-7 pull-left"> Extra Information </div>
										</div>

										<div class="space-6"></div>
										<div class="well">
											Thank you for choosing Ace Company products.
						We believe you will be satisfied by our services.
										</div>
									</div>
								</div>
							</div>
						</div>
						</div>

					</div><!--Add lising-->


					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<!-- /.main-container ending in footer page -->
		<?php include_once("../footer.php");?>
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