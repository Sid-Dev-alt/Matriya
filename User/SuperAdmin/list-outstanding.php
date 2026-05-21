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
    	<script src="angularjs/outstanding-script.js"></script>
	</head>	

	<body class="no-skin" data-ng-cloak data-ng-app="CategoryModule" data-ng-controller="CategoryController" data-ng-init="GetListData()">
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
							<li class="active">Invoices</li>
						</ul>
					</div> -->

					<div class="page-content" >
					<div  id="">
						<div class="row">
							<div class="col-xs-12 page-header">
		                  	 <div class="col-xs-12 col-sm-12 col-lg-6">
		                  	 	<div class="" >
									<h1>{{pagetitle}}</h1>
									</div>
		<!-- /.page-header --></div>
		                  	 	<!-- <div class="col-xs-12 col-sm-12 col-lg-6">
				                    <a href="" data-ng-click="GotoAdd()" ng-show="FormList">
									<div class="label label-lg label-success arrowed-in arrowed-right pull-right" >
									<b>Add New</b>
									</div></a>
			                  </div> -->
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
												<th>Customer Name</th>
												<th>Invoice Id</th>
												<th>Invoice Date</th>
												<th>Due Date</th>
												<th>Invoice Total</th>
												<th>Received Amount</th>
												<th>Total Outstanding</th>
												<!-- <th>Action</th> -->
											</tr>
										</thead>										
										<tbody >
											<tr  ng-repeat="cat in pagedItems">
												<!-- <td data-title="Sl.No">{{$index+1}}</td> -->
												<!-- <td data-title="Product Name">{{cat.ShipDate | date: 'd MMM y'}}</td> -->
												<td data-title="Customer Name">{{cat.CName}}
												</td>
												<td data-title="Invoice Id">{{cat.InvoiceId}}
												</td>
												<td data-title="Invoice Date">{{cat.InvoiceDate | date: 'd MMM y'}}</td>
												
												<td data-title="Due Date">{{cat.DueDate | date: 'd MMM y'}}</td>
												<td data-title="Invoice Total">{{cat.InvoiceTotal | number:2}}</td>
												<td data-title="Received Amount">{{cat.received || 0 | number:2}}</td>
												<td data-title="Total Outstanding">{{cat.TotalOutstanding | number:2}}</td>
											
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
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								 <form class="form-horizontal" role="form" id="AddCategoryForm" name="AddCategoryForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="AddCategoryData()">
									<input type="hidden" value="{{FormPkId}}">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Product Id"> Package Id <span class="error">*</span></label>
										
										<div class="col-sm-6">
											<input type="text" id="PackageId" placeholder="" class="form-control" name="PackageId" required  data-ng-model="PackageId" disabled="">
												<div class="error" data-ng-show="submitted || AddCategoryForm.PackageId.$dirty && AddCategoryForm.PackageId.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.PackageId.$error.required">Package Id is required.</small>
												</div>
										</div>
									</div>
									<input type="hidden" ng-model="OrderId">

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Product Id"> Shippment Order <span class="error">*</span></label>
										
										<div class="col-sm-6">
											<input type="text" id="ShipId" placeholder="" class="form-control" name="ShipId" required  data-ng-model="ShipId" disabled="">
												<div class="error" data-ng-show="submitted || AddCategoryForm.ShipId.$dirty && AddCategoryForm.ShipId.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.ShipId.$error.required">Ship Id is required.</small>
												</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="reason">Ship Date <span class="error">*</span></label>

										<div class="col-sm-6">
										<input type="text" class="form-control" name="entrydate"  placeholder="" id="entrydate" ng-model="entrydate" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened1" ng-click="singleopen($event,'opened1')"  datepicker-options="assDate1" required=""> 
											<div class="error" data-ng-show="submitted || AddCategoryForm.entrydate.$dirty && AddCategoryForm.entrydate.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.entrydate.$error.required">Date is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="reason">Carrier <span class="error">*</span></label>

										<div class="col-sm-6">
										<input type="text" class="form-control" name="carrier"  placeholder="" id="carrier" ng-model="carrier" required=""> 
											<div class="error" data-ng-show="submitted || AddCategoryForm.carrier.$dirty && AddCategoryForm.carrier.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.carrier.$error.required">Carrier is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="reason">Tracking #</label>

										<div class="col-sm-6">
										<input type="text" class="form-control" name="tracking"  placeholder="" id="tracking" ng-model="tracking" > 
											<div class="error" data-ng-show="submitted || AddCategoryForm.tracking.$dirty && AddCategoryForm.tracking.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.tracking.$error.required">Tracking is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="reason">Shipping Charges (If any)</label>

										<div class="col-sm-6">
										<input type="text" id="shipcharge" placeholder="" class="form-control" name="shipcharge{{$index}}" data-ng-model="bat.shipcharge" maxlength="8" valid-number="" allow-decimal="false" allow-negative="false" required="">
										<div class="error" data-ng-show="submitted || AddCategoryForm.shipcharge{{$index}}.$dirty &amp;&amp; AddCategoryForm.shipcharge.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.shipcharge{{$index}}.$error.required">Shipping Charges is required.</small>
										</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="">Internal Notes </label>
										
										<div class="col-sm-6">
											<textarea type="text" id="internalnotes" placeholder="" class="form-control" name="internalnotes" data-ng-model="internalnotes" ></textarea>
												<div class="error" data-ng-show="submitted || AddCategoryForm.internalnotes.$dirty && AddCategoryForm.internalnotes.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.internalnotes.$error.required">Internal Notes is required.</small>
												</div>
										</div>
									</div>

									<div id="no-more-tables" ng-if="BatchArr.length>0">
									<table class="table table-striped table-bordered" >
									<thead>
										<tr>
											<th>Item Details</th>
											<th>Order Qty</th>
											<th>Packed Qty</th>
											<th>Qty to Pack</th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="bat in BatchArr">
											<td data-title="Item Details" class="center">
											<div class="form-group">
												<div class="col-sm-12">
													<input type="hidden" value="{{bat.InvPkId}}">

												{{bat.product}}
												<!-- <input type="text" id="product" placeholder="" class="form-control" name="product{{$index}}" required data-ng-model="bat.product"  uib-typeahead="cat as cat.ProductName for cat in InventoryArray | filter:$viewValue"  typeahead-editable="false" typeahead-on-select="onInvSelect($item, $model, $label, $index)">
												<div class="error" data-ng-show="submitted || AddCategoryForm.product{{$index}}.$dirty && AddCategoryForm.product{{$index}}.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.product{{$index}}.$error.required">Product Name is required.</small>
												</div>
												<p ng-if="bat.InvPkId!=undefined && bat.batchno!=undefined">Batch: {{bat.batchno}}</p>
												<p ng-if="bat.InvPkId!=undefined">SKU: {{bat.SKU}}</p> -->
											</div>
											</td>
											
											<td data-title="Order Qty" class="center">
												<div class="form-group">
												<div class="col-sm-12">
													{{bat.quantity}}
												<!--<input type="text" id="quantity" placeholder="" class="form-control" name="quantity{{$index}}" data-ng-model="bat.quantity" maxlength="10" required="" class="form-control" number>
													<div class="error" data-ng-show="submitted || AddCategoryForm.quantity{{$index}}.$dirty && AddCategoryForm.quantity{{$index}}.$invalid">
														<small class="error" data-ng-show="AddCategoryForm.quantity{{$index}}.$error.required">QUANTITY is required.</small>
													</div>
												</div>
												<p ng-if="bat.InvPkId!=''">Available Quantity: {{bat.AvlQty}}</p> -->
												</div>
											</td>

											<td data-title="Packed Qty" class="center" >
												{{bat.PackedQty}}
											</td>

											<td data-title="Qty to Pack" class="center" >
												<input type="text" id="qtytopack" placeholder="" class="form-control" name="qtytopack{{$index}}" data-ng-model="bat.qtytopack" maxlength="10" required="" class="form-control" number ng-change="ChangeQty(bat.quantity,bat.qtytopack,$index)">
													<div class="error" data-ng-show="submitted || AddCategoryForm.qtytopack{{$index}}.$dirty && AddCategoryForm.qtytopack{{$index}}.$invalid">
														<small class="error" data-ng-show="AddCategoryForm.qtytopack{{$index}}.$error.required">QUANTITY is required.</small>
													</div>
												</div>
											</td>
										</tr>
										
									</tbody>

								</table>
								<div class="form-group" style="display: none">
									<label class="col-sm-7 control-label no-padding-right" for="Choose ">Order Total </label>
									<div class="col-sm-3">&nbsp;</div>
									<div class="col-sm-2">
										<span id="OrderSum" ng-model="ordersum" ng-show="span1" ng-bind="OrderSum()"></span>{{ordersum ? ordersum : 0}}
									</div>
								</div>

								<div class="form-group" >
									<label class="col-sm-7 control-label no-padding-right" for="Choose ">Packed Total </label>
									<div class="col-sm-3">&nbsp;</div>
									<div class="col-sm-2">
										<span id="PackedSum" ng-model="packedsum" ng-show="span1" ng-bind="PackedSum()"></span>{{packedsum ? packedsum : 0}}
									</div>
								</div>

								<div class="form-group" style="display: none">
									<label class="col-sm-7 control-label no-padding-right" for="Choose ">Sub Total </label>
									<div class="col-sm-3">&nbsp;</div>
									<div class="col-sm-2">
										<span id="totalSum" ng-model="sum" ng-show="span1" ng-bind="calculateSum()"></span>{{sum ? sum : 0}}
									</div>
								</div>
							</div>


									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="submit" data-ng-disabled="FormValid">
												<i class="ace-icon fa fa-check bigger-110"></i>
												SAVE & Confirm
											</button>
											&nbsp; &nbsp;
											<button class="btn btn-inverse" type="button" data-ng-click="GotoList()">
												<i class="ace-icon fa fa-list bigger-110"></i>
												Go to List
											</button>
										</div>
									</div>
								</form>
							</div><!-- /.col -->
						</div><!-- /.row -->
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
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