<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']!="")
{
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once("../title.php");?>		
		<link rel="stylesheet" href="../../assets/css/colorbox.min.css" />
    	<script src="angularjs/add-raw-purchase-script.js"></script>
    	<style type="text/css">
    		.new-row {
  clear: left;
}
    	</style>
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="CategoryModule" data-ng-controller="CategoryController" data-ng-init="GetOrderId();GetVendors();GetGoDowns();GetProducts()">
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
						<li class=""><a href="#">Masters</a></li>
						<li class="active">Sales Orders</li>
					</ul>
				</div> -->

				<div class="page-content" >
				<div  id="no-more-tables">
					<div class="row">
						<div class="col-xs-12 page-header">
	                  	 <div class="col-xs-12 col-sm-12 col-lg-6">
	                  	 	<div class="" >
								<h1>{{pagetitle}}</h1>
								</div>
	<!-- /.page-header --></div>
	                  	 	<div class="col-xs-12 col-sm-12 col-lg-6">
			                    <a href="" data-ng-click="GotoAdd()" ng-show="FormList">
								<div class="label label-lg label-success arrowed-in arrowed-right pull-right" >
								<b>Add New</b>
								</div>
								</a>
		                  </div>
		              </div>							
					</div>

						


				<div ng-show="FormAdd" >
					<div class="row">
						<div class="col-xs-12">
							<form class="form-horizontal" role="form" id="AddCategoryForm" name="AddCategoryForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="AddCategoryData()">
								<input type="hidden" value="{{FormPkId}}">
								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="Product Id"> PO Id <span class="error">*</span></label>
									
									<div class="col-sm-3">
										<input type="text" id="POrderId" placeholder="" class="form-control" name="POrderId" required  data-ng-model="POrderId" disabled="">
											<div class="error" data-ng-show="submitted || AddCategoryForm.POrderId.$dirty && AddCategoryForm.POrderId.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.POrderId.$error.required">PO Id is required.</small>
											</div>
									</div>
								</div>

							<!-- <div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="reason"> Reference# </label>
								
								<div class="col-sm-6">
								<input type="text" id="referencenum" placeholder="" class="form-control"  name="referencenum"  data-ng-model="referencenum">
									<div class="error" data-ng-show="submitted || AddCategoryForm.referencenum.$dirty && AddCategoryForm.referencenum.$invalid">
										<small class="error" data-ng-show="AddCategoryForm.referencenum.$error.required">Reference Number is required.</small>
									</div>
								</div>
							</div> -->

							<div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="reason"> Date <span class="error">*</span></label>

								<div class="col-sm-3">
								<input type="text" class="form-control" name="entrydate"  placeholder="" id="entrydate" ng-model="entrydate" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened1" ng-click="singleopen($event,'opened1')"  datepicker-options="assDate1" required="" > 
									<div class="error" data-ng-show="submitted || AddCategoryForm.entrydate.$dirty && AddCategoryForm.entrydate.$invalid">
										<small class="error" data-ng-show="AddCategoryForm.entrydate.$error.required">Date is required.</small>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="Sub Projects"> Party Name <span class="error">*</span></label>
								<div class="col-sm-3">
									<ui-select ng-model="$parent.vendorname" theme="selectize" title="Choose a person" required>
						            <ui-select-match placeholder="Select or search">{{$select.selected.DisplayName}}</ui-select-match>
						            <ui-select-choices  repeat="item in VendorArray | filter: $select.search">
						              <div><span ng-bind-html="item.DisplayName | highlight: $select.search"></span></div>
						            </ui-select-choices>
						          </ui-select>
									<div class="error" data-ng-show="submitted || AddCategoryForm.vendorname.$dirty && AddCategoryForm.vendorname.$invalid">
										<small class="error" data-ng-show="AddCategoryForm.vendorname.$error.required">Party is required.</small>
									</div>
									<!--<a href="#modal-form" data-toggle="modal"><span class="label label-info arrowed-right arrowed-in" ng-click="AddVendor()">New Party ?</span></a>-->
								</div>
							</div>

							<!-- <div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="Sub Projects"> GoDown <span class="error">*</span></label>
								<div class="col-sm-3">
									<ui-select ng-model="$parent.godownname" theme="selectize" title="Choose a person" >
						            <ui-select-match placeholder="Select or search">{{$select.selected.GoDownName}}</ui-select-match>
						            <ui-select-choices  repeat="item in GoDownArray | filter: $select.search">
						              <div><span ng-bind-html="item.GoDownName | highlight: $select.search"></span></div>
						            </ui-select-choices>
						          </ui-select>
									<div class="error" data-ng-show="submitted || AddCategoryForm.godownname.$dirty && AddCategoryForm.godownname.$invalid">
										<small class="error" data-ng-show="AddCategoryForm.godownname.$error.required">Select Godown is required.</small>
									</div>
								</div>
							</div> -->

							<!-- <div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="">Remarks </label>
								<div class="col-sm-6">
									<textarea type="text" id="cnotes" placeholder="" class="form-control" name="cnotes" data-ng-model="cnotes" ></textarea>
										<div class="error" data-ng-show="submitted || AddCategoryForm.cnotes.$dirty && AddCategoryForm.cnotes.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.cnotes.$error.required">Remarks is required.</small>
										</div>
								</div>
							</div> -->

							<div class="row">
								<div class="col-sm-4">
									<label class="control-label no-padding-right" for="Sub Projects"> Item Name <span class="error">*</span></label>
								
									<ui-select ng-model="$parent.itemname" theme="selectize" title="Choose a person">
							            <ui-select-match placeholder="Select or search">{{$select.selected.TotalProductName}}</ui-select-match>
							            <ui-select-choices  group-by="'Micron'" repeat="item in ProductArray | filter: $select.search">
							              <div><span ng-bind-html="item.TotalProductName | highlight: $select.search"></span></div>
							              <!-- <small>{{item.ProductId}}</small> -->
							            </ui-select-choices>
							          </ui-select>
									<div class="error" data-ng-show="submitted || AddCategoryForm.itemname.$dirty && AddCategoryForm.itemname.$invalid">
										<small class="error" data-ng-show="AddCategoryForm.itemname.$error.required">Select item is required.</small>
									</div>
								</div>

							<div class="col-sm-4">
								<label class="control-label no-padding-right" for="Sub Projects">No of Rolls <span class="error">*</span></label>
								<input type="text" class="form-control" number ng-model="noofrolls" name="noofrolls">
								<div class="error" data-ng-show="submitted || AddCategoryForm.noofrolls.$dirty && AddCategoryForm.noofrolls.$invalid">
									<small class="error" data-ng-show="AddCategoryForm.noofrolls.$error.required">No of Rolls is required.</small>
								</div>
							</div>

							<div class="col-sm-2">
								<p>&nbsp;</p>
								<button type="button" ng-click="PushItems()" class="btn btn-sm btn-primary" >Add</button>
								<div class="error" data-ng-show="submitted || AddCategoryForm.noofrolls.$dirty && AddCategoryForm.noofrolls.$invalid">
									<small class="error" data-ng-show="AddCategoryForm.noofrolls.$error.required">No of Rolls is required.</small>
								</div>
							</div>

							</div>

							<div id="no-more-tables" ng-show="BatchArr.length>0">
								<table class="table table-striped table-bordered" >
								<thead>
									<tr>
										<th>Item Details</th>
										<th>Size</th>
										<th>Weight / Qty</th>
										<th>Remarks</th>
										<th>ACTION</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="bat in BatchArr" >
										<td data-title="Item Details" class="center">
										<div class="form-group">
											<div class="col-sm-12">
											<input type="hidden" value="{{bat.EntryPkId}}">
											<ui-select ng-model="bat.productname" theme="selectize" title="Choose a person" ng-change="SetUnit(bat.productname,$index)" ng-disabled="bat.IsSplitQty=='1' || bat.Isinvoiced=='1'">
									            <ui-select-match placeholder="Select or search">{{$select.selected.TotalProductName}}</ui-select-match>
									            <ui-select-choices  group-by="'Micron'" repeat="item in ProductArray | filter: $select.search">
									              <div><span ng-bind-html="item.TotalProductName | highlight: $select.search"></span></div>
									              <!-- <small>{{item.ProductId}}</small> -->
									            </ui-select-choices>
									          </ui-select>
											<div class="error" data-ng-show="submitted || AddCategoryForm.productname{{$index}}.$dirty && AddCategoryForm.productname{{$index}}.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.productname{{$index}}.$error.required">Product Name is required.</small>
											</div>
											<small class="error" ng-if="bat.IsSplitQty=='1'">you cannot edit this item.Slitting already done</small><br>
											<small class="error" ng-if="bat.Isinvoiced=='1'">you cannot edit this item. Item already dispatched</small>
										</div>
										</td>

										<td data-title="Item Details" class="center">
										<div class="form-group">
											<div class="col-sm-12">
												<div class="input-group">
											<input type="text"  placeholder="" class="form-control" name="productsize" data-ng-model="bat.productsize" uib-typeahead="size as size.Size for size in SizeArr" number ng-disabled="bat.IsSplitQty=='1'|| bat.Isinvoiced=='1'">
											<span class="input-group-addon">MM</span>
											<div class="error" data-ng-show="submitted || AddCategoryForm.productname{{$index}}.$dirty && AddCategoryForm.productname{{$index}}.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.productname{{$index}}.$error.required">Product Name is required.</small>
											</div>
											</div>
											</div>
										</div>
										</td>
										
										
										<td data-title="Qty" class="center">
											<div class="form-group">
											<div class="col-sm-12">
												<div class="input-group">
											<input type="text" id="quantity" placeholder="" class="form-control" name="quantity{{$index}}" data-ng-model="bat.quantity" maxlength="10" required="" class="form-control" valid-number="" allow-decimal="true" allow-negative="false" decimal-upto="3" ng-disabled="bat.IsSplitQty=='1'|| bat.Isinvoiced=='1'">
											<span class="input-group-addon">
												{{bat.Unit}}
											</span>
										</div>
												<div class="error" data-ng-show="submitted || AddCategoryForm.quantity{{$index}}.$dirty && AddCategoryForm.quantity{{$index}}.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.quantity{{$index}}.$error.required">QUANTITY is required.</small>
												</div>
											</div>
											</div>
										</td>
										<td data-title="Remarks" class="center">
											<div class="form-group">
											<div class="col-sm-12">
												
											<textarea id="remarks" placeholder="" class="form-control" name="remarks{{$index}}" data-ng-model="bat.remarks"></textarea>
										
												<div class="error" data-ng-show="submitted || AddCategoryForm.remarks{{$index}}.$dirty && AddCategoryForm.remarks{{$index}}.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.remarks{{$index}}.$error.required">Remarks is required.</small>
												</div>
											</div>
											</div>
										</td>
										<td data-title="ACTION">
											<a ng-if="bat.DeleteStatus=='0'" href="" class="orange" ng-click="removeRow($index);GetProducts()">
												<i class="ace-icon fa fa-remove bigger-130"></i>
											</a>

											<a ng-if="bat.DeleteStatus=='1'" href="" class="orange" ng-click="Delete(bat.EntryPkId,bat.productname,bat.IsSplitQty,bat.Isinvoiced);GetProducts()">
												<i class="ace-icon fa fa-trash bigger-130"></i>
											</a>
										</td>
									</tr>
									</tbody>
								</table>
								<div class="form-group">	
								<div class="col-sm-12 col-md-4 col-lg-4">							
								<button type="button" class="btn btn-sm btn-block btn-info" ng-click="AddMore();GetProducts()">Add More</button>
									</div>
								</div>
							</div>

							
								<div class="clearfix form-actions" >
									<div class="col-md-offset-3 col-md-9">
										<button class="btn btn-info" type="submit"  ng-if="pagetitle!='Edit Production'">
											<i class="ace-icon fa fa-check bigger-110"></i>
											SAVE
										</button>
										&nbsp; &nbsp;
										<button class="btn btn-inverse" type="button" data-ng-click="GotoList()">
											<i class="ace-icon fa fa-close bigger-110"></i>
											CANCEL
										</button>
									</div>
								</div>
							</form>
						</div><!-- /.col -->
					</div><!-- /.row -->
				</div><!--Add lising-->

				<!--modal-->
					<div id="myModal" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
						<div class="modal-dialog  modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<div class="col-xs-12">
									<div class="pull-left">
										<h4>Roll Details</h4>
									</div>
									<div class="pull-right">
										<button type="button" class="close" data-ng-click="GotoList()">&times;</button>
									</div>
									</div>
								</div>
								<div class="modal-body">
								<div class="row">
								<div class="col-xs-12">
									<!-- PAGE CONTENT BEGINS -->
									<div class="card">
										<div class="space-6"></div>

									<div class="card-body" ng-if="result=='OK'">
										<h4>{{TotalName}} {{InvProductSize}} MM</h4>
										<p>{{InvUniqueRollNo}}</p>
										<h4>Avl Qty: {{AvlQuantity}} {{UnitModal}}</h4>
										<ul>
											<li><span class="text-danger">{{TotalName}} {{POProductSize}} MM ( {{PurchaseRollNo}} ) </span> Purchased from the <b> {{VendorNameModal}}</b> vendor on <b>{{RawPODateModal | date: 'd MMM y'}}</b></li>
											 <li>Purchase Weight/Qty is <b>{{PurchaseQtyModal}} {{UnitModal}}</b></li>
											 <!-- <li>Stock received at <b>{{GoDownName}}</b></li> -->
												

											<li ng-if="IsSlitted=='1'"><span class="text-success">{{TotalName}} {{InvProductSize}} MM ( {{InvUniqueRollNo}} ) </span> has been slit from <span class="text-danger"> {{TotalName}} {{POProductSize}} MM </span> on <b>{{SlitDate | date: 'd MMM y'}} </b></li>	

											<li ng-if="IsSlitted=='1'">Slit Qty is <b>{{SlitQty}} {{UnitModal}}</b></li>

											<li ng-if="IsSplitQty=='1'">
												<p>Roll has slitted into:</p>
												<div ng-repeat="slit in data2">
													<p><b>{{TotalName}} {{slit.SplitSize}} MM</b> Weight/Qty Is <b>{{slit.SlitQty}} {{UnitModal}}</b></p>
												</div>
											</li>
											

											<li ng-if="IsInvoice=='1'">
												Roll has been dispatched to <span class="text-success">{{CustomerNameModal}}</span> on <b>{{InvoiceDate | date: 'd MMM y'}} </b></li>

											
											<!-- <li ng-if="IsSlitted>'1'"><span class="text-success">{{TotalProductName}} {{ProductSize}} MM ( {{UniqueRollNo}} ) </span> Sliited From <span class="text-danger"> {{TotalName}} {{POProductSize}} MM </span> on <b>{{SlitDate | date: 'd MMM y'}} </b></li> -->
										</ul>
										<!-- <div class="row">
											<div class="col-xs-6">
												<div id="dynamic-table_filter" class="dataTables_filter pull-left"><label>Search:</label>
													<input type="text" ng-model="search" class="form-control" placeholder="Search"> &nbsp; Showing {{mypageno*itemsPerPage-itemsPerPage+1}}-{{mypageno*itemsPerPage}} of {{total_count}}
												</div>
											</div>
											<div class="col-xs-6">
											<a href="datewise-bill-report.php?FromDate={{fromdate | date}}&ToDate={{todate | date}}" target="_self" class="btn btn-success" > Download</a>
											</div>
										</div> -->
										<!-- <table id="dynamic-table" class="table table-bordered table-striped table-condensed cf"  datatable="ng" dt-options="vm.dtOptions" dt-column-defs="vm.dtColumnDefs">
											<thead class="cf">
												<tr>
													<th>Item Name</th>
													<th>Roll No</th>
													<th>Qty</th>
												</tr>
											</thead>										
											<tbody>
												<tr ng-repeat="cat in pagedItems">
												<td data-title="Item Name">{{cat.TotalProductName}} {{cat.ProductSize}} MM</td>
												<td data-title="Roll No">
													<a href="" data-toggle="modal" data-target="#modal-form" ng-click="GetProductData(cat.TotalProductName,cat.ProductSize,cat.UniqueRollNo,cat.Unit)"> {{cat.UniqueRollNo}}
													</a>
												</td>
												<td data-title="Qty">{{cat.Quantity}} {{cat.Unit}}</td>
												</tr>
												
											</tbody>
										</table> -->
									</div>
									</div><!-- /.row -->

									<!-- PAGE CONTENT ENDS -->
								</div><!-- /.col -->
							</div>
								
							</div>
							
							</div>
						</div>
					</div> <!--modal-->

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
