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
    	<script src="angularjs/slitting-script.js"></script>
    	<style type="text/css">
    		.new-row {
  clear: left;
}
    	</style>
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="CategoryModule" data-ng-controller="CategoryController">
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
		                  	 <div class="col-xs-12 col-sm-12 col-lg-12">
		                  	 	<div class="" >
									<h1>{{pagetitle}}</h1>
									</div>
		<!-- /.page-header --></div>
		                  	 	
			              </div>							
						</div>

						<div class="row" ng-show="FormList">
							<div class="col-xs-12">
								<div class="row">
									<ul class="pagination pull-right">
										<li>
										    <dir-pagination-controls max-size="10" direction-links="true" boundary-links="true" on-page-change="getData(newPageNumber)"></dir-pagination-controls>
								        </li>
								    </ul>

								    <div class="clearfix"></div>
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
    											<!-- <th>Sl.No</th> -->
    											<th ng-click="sort('SlitId')">Slit Id <span class="glyphicon sort-icon" ng-show="sortKey=='SlitId'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
    											<th ng-click="sort('SlitDate')">Date <span class="glyphicon sort-icon" ng-show="sortKey=='SlitDate'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
												<th ng-click="sort('UserName')">Created By <span class="glyphicon sort-icon" ng-show="sortKey=='UserName'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
    											<th ng-click="sort('UniqueRollNo')">From Item <span class="glyphicon sort-icon" ng-show="sortKey=='UniqueRollNo'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span></th>
    											<th>Slitting Details</th>
    											<th>Action</th>
    										</tr>
    									</thead>							
    									<tbody>
    										<tr dir-paginate="cat in pagedItems|orderBy:sortKey:reverse|filter:search|itemsPerPage:5" total-items="{{total_count}}" current-page="mypageno">
    											<td data-title="Slit Id">{{cat.SlitId}}</td>
    											<td data-title="Date">{{cat.SlitDate | date: 'd MMM y'}}</td>
												<td data-title="Created By">{{cat.UserName}}</td>
    											<td data-title="From Item">
    												<p>{{cat.Micron}} {{cat.ProductName}} {{cat.ProductSize}} MM </p>
    												<p><strong>Roll:</strong> {{cat.UniqueRollNo}}</p>
    												<p><strong>Purchase Qty:</strong> {{cat.PurchaseQty | number:3}} {{cat.Unit}}</p>

    												<div ng-if="cat.ParentSlit=='0'">
	    												<p><strong>Total Slit Qty:</strong> {{cat.TotalSlitQty | number:3}} {{cat.Unit}}</p>
	    												<p class="error" ng-if="cat.PurchaseQty-cat.TotalSlitQty>0"><strong>Left Over Qty:</strong> {{cat.PurchaseQty-cat.TotalSlitQty | number:3}} {{cat.Unit}}</p>

	    												<p class="text-success" ng-if="cat.PurchaseQty-cat.TotalSlitQty<0"><strong>Weight Gain Qty:</strong> {{cat.TotalSlitQty-cat.PurchaseQty | number:3}} {{cat.Unit}}</p>
    												</div>

    												<div ng-if="cat.ParentSlit=='1'">
    													<p ><strong>Parent Qty:</strong> {{cat.ParentSlitQty | number:3}} {{cat.Unit}}</p>

    													<p><strong>Total Slit Qty:</strong> {{cat.TotalSlitQty | number:3}} {{cat.Unit}}</p>
	    												<p class="error" ng-if="cat.ParentSlitQty-cat.TotalSlitQty>0"><strong>Left Over Qty:</strong> {{cat.ParentSlitQty-cat.TotalSlitQty | number:3}} {{cat.Unit}}</p>

	    												<p class="text-success" ng-if="cat.ParentSlitQty-cat.TotalSlitQty<0"><strong>Weight Gain Qty:</strong> {{cat.TotalSlitQty-cat.ParentSlitQty | number:3}} {{cat.Unit}}</p>
    												</div>

    												
    											</td>
    											<td data-title="Cutting Sizes">
    												<div ng-repeat="sp1 in cat.data2">
    													<!-- Set {{sp1.SetId}} -->
    													Set {{$index+1}}
    													<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf" >
        													<thead>
        														<th ng-click="sort1('index')">S No <br><span class="text-danger">({{sp1.data3.length}})</span><span class="glyphicon sort-icon" ng-show="sortKey1=='index'" ng-class="{'glyphicon-chevron-up':reverse1,'glyphicon-chevron-down':!reverse1}"></span></th>
        														<th ng-click="sort1('Item')">Item <span class="glyphicon sort-icon" ng-show="sortKey1=='Item'" ng-class="{'glyphicon-chevron-up':reverse1,'glyphicon-chevron-down':!reverse1}"></span></th>
        														<th ng-click="sort1('Roll')">Roll No <span class="glyphicon sort-icon" ng-show="sortKey1=='Roll'" ng-class="{'glyphicon-chevron-up':reverse1,'glyphicon-chevron-down':!reverse1}"></span></th>
        														<th ng-click="sort1('Qty')">Weight / Qty <br> <span class="text-danger">({{sp1.TotalStepQty | number:3}})</span><span class="glyphicon sort-icon" ng-show="sortKey1=='Qty'" ng-class="{'glyphicon-chevron-up':reverse1,'glyphicon-chevron-down':!reverse1}"></span></th>
        														<th ng-click="sort1('Remarks')">Remarks <span class="glyphicon sort-icon" ng-show="sortKey1=='Remarks'" ng-class="{'glyphicon-chevron-up':reverse1,'glyphicon-chevron-down':!reverse1}"></span></th>
        														<th>Print</th>
        													</thead>
            												<tbody>
                												<tr ng-repeat="sp in sp1.data3|orderBy:sortKey1:reverse1">
                													<td data-title="Sno">{{$index+1}}</td>
                													<td data-title="Item">{{sp.NewMicron}} {{sp.NewProductName}} {{sp.NewProductSize}} MM </td>
                													<td data-title="Roll No"><a href="" ng-click="GetRollData(sp.RollNo)">{{sp.RollNo}}</a></td>
                													<td>
                														<p ng-if="sp.quantity=='0.000' || sp.quantity=='0.00' || sp.quantity=='0'"><a href="#modal-form" data-toggle="modal"><span class="label label-info arrowed-right arrowed-in" ng-click="AddWeight(cat.SlitId,cat.SlitDate,cat.Micron,cat.ProductName,cat.ProductSize,cat.UniqueRollNo,sp.EntryPkId,sp.NewMicron,sp.NewProductName,sp.NewProductSize,sp.RollNo,sp.NewUnit,sp.quantity,cat.PurchaseQty,cat.TotalSlitQty)">Add Weight</span></a></p>
                														<p ng-if="sp.quantity!='0.000'"><a href="#modal-form" data-toggle="modal" ng-click="AddWeight(cat.SlitId,cat.SlitDate,cat.Micron,cat.ProductName,cat.ProductSize,cat.UniqueRollNo,sp.EntryPkId,sp.NewMicron,sp.NewProductName,sp.NewProductSize,sp.RollNo,sp.NewUnit,sp.quantity,cat.PurchaseQty,cat.TotalSlitQty)">{{sp.quantity}} {{sp.NewUnit}}</a></p>
                													</td>
                													<td>{{sp.remarks}}</td>
                													<td>
                														<a ng-if="sp.quantity!='0.000'" href="generate-barcode.php?Id={{sp.EntryPkId}}" target="_blank" class="btn btn-xs btn-danger" >Print &nbsp;
                														<i class="ace-icon fa fa-print bigger-120"></i>
                														</a>
                													</td>
                												</tr>
            												</tbody>
            											</table>
            										</div>
        										</td>
												<!-- <span ng-repeat="sp in cat.data2">
													<p>{{sp.RollNo}} - {{sp.SlitQty}}</p>
												</span> -->
												<td data-title="Action">
													<a href="" ng-click="EditOrder(cat.FormPkId,cat.SlitId,cat.SlitDate,cat.PkId_InventoryMaster,cat.PkId_RawPurchaseMasterDetails,cat.ProductId_ProductMaster,cat.ProductName,cat.ProductSize,cat.Micron,cat.TotalName,cat.Unit,cat.UniqueRollNo,cat.GoDownName,cat.Quantity,cat.data2)"  class="btn btn-xs btn-info">Edit &nbsp;
														<i class="ace-icon fa fa-edit bigger-120"></i>
													</a>
												</td>
        									</tr>
        								</tbody>
    									<tfoot>
    								</tfoot>
    							</table>
							</div>
						</div>
					</div>
				</div>
					<div id="modal-form" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
						<div class="modal-dialog  modal-md">
							<div class="modal-content">
								<div class="modal-header">
									<div class="col-xs-12">
									<div class="pull-left">
										<h4>Add Weight</h4>
									</div>
									<div class="pull-right">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
									</div>
									</div>
								</div>
								<div class="modal-body">
									<h5><strong>{{NewMicron}} {{NewProductName}} {{NewProductSize}} </strong></h5>
									<p>Roll No: {{RollNo}}</p>
								<div class="row">
									<form class="form-horizontal" role="form" id="AddVendorForm" name="AddVendorForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="AddCustomerData()">	
										<input type="hidden" value={{EntryPkId}}>
									<div class="">
									<div class="col-sm-12">
										<label class="control-label no-padding-right text-left" for="reason"> Weight / Quantity <span class="error">*</span></label>
										<div class="input-group">
											<input type="text" id="quantity" placeholder="" class="form-control" name="quantity" data-ng-model="quantity" maxlength="10" required="" class="form-control" valid-number="" allow-decimal="true" allow-negative="false" decimal-upto="3">
											<span class="input-group-addon">
												{{NewUnit}}
											</span>
										</div>
										<div class="error" data-ng-show="submitted || AddCategoryForm.quantity.$dirty && AddCategoryForm.quantity.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.quantity.$error.required">QUANTITY is required.</small>
										</div>
									</div>
									<div class="col-sm-12">
									<div class="clearfix">&nbsp;</div>
									<p>Roll Purchase Quantity is {{PurchaseQty}}</p>
									<!-- {{TotalSlitQty-quantity}} -->
									<p>Left over Qty : {{PurchaseQty-TotalSlitQty | number:3}}</p>
									<!-- <p class="error">Left over Qty : {{PurchaseQty-quantity | number:3}}</p> -->
									</div>
									</div>
									<div class="clearfix">&nbsp;</div>
									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="submit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>
											&nbsp; &nbsp;
											<button class="btn btn-inverse" type="button" data-ng-click="GotoFormList()">
												<i class="ace-icon fa fa-close bigger-110"></i>
												Cancel
											</button>
										</div>
									</div>
									
								</form>
								</div>
							</div>
							</div>
						</div>
					</div> <!--modal-->
					</div><!--FOrm lising-->


					<div ng-show="FormAdd" >
						<div class="row">
							<div class="col-xs-12">
								<form class="form-horizontal" role="form" id="AddCategoryForm" name="AddCategoryForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="AddCategoryData()">
									<input type="hidden" value="{{FormPkId}}">
								 <div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="Slitting Id"> Slitting Id <span class="error">*</span></label>
									
									<div class="col-sm-3">
										<input type="text" id="salesorder" placeholder="" class="form-control" name="slitid" required  data-ng-model="slitid" disabled="">
											<div class="error" data-ng-show="submitted || AddCategoryForm.slitid.$dirty && AddCategoryForm.slitid.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.slitid.$error.required">Slitting Id is required.</small>
											</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="reason"> Date <span class="error">*</span></label>

									<div class="col-sm-3">
									<input type="text" class="form-control" name="entrydate"  placeholder="" id="entrydate" ng-model="entrydate" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened1" ng-click="singleopen($event,'opened1')"  datepicker-options="assDate1" required="" > 
									<!-- ng-change="GetLamiation(jobid,entrydate)" -->
										<div class="error" data-ng-show="submitted || AddCategoryForm.entrydate.$dirty && AddCategoryForm.entrydate.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.entrydate.$error.required">Date is required.</small>
										</div>
									</div>
								</div>
							<!-- 	<div class="form-group" style="display: none">
									<label class="col-sm-3 control-label no-padding-right" for=""> remarks </label>
									<div class="col-sm-3">
										<textarea type="text" id="remarks" placeholder="" class="form-control" name="remarks" data-ng-model="remarks"></textarea>
										<div class="error" data-ng-show="submitted || AddCategoryForm.remarks.$dirty && AddCategoryForm.remarks.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.remarks.$error.required">Remarks is required.</small>
										</div>
									</div>
								</div> -->
								<div class="" ng-show="pagetitle=='Add New'">
									<div class="col-sm-6 no-padding-left">
										<input type="text" class="form-control" id="barcodeInput" ng-model="barcodeInput"  placeholder="Scan the bar code here.." 
										ng-model-options="{debounce: 500}" ng-change="GetBar()" autofocus >
									</div>
									<div class="col-sm-6 no-padding-right">
										<ui-select ng-model="$parent.barcode" theme="selectize" title="Choose a person" ng-change="GetSelectBar($parent.barcode)">
								            <ui-select-match placeholder="Select or search">{{$select.selected.Size}} MM</ui-select-match>
								            <ui-select-choices group-by="'TotalName'" repeat="item in BarArr | filter: $select.search">
								              <div><span ng-bind-html="item.Size | highlight: $select.search"></span> MM ({{item.UniqueRollNo}}) {{item.quantity}} {{item.Unit}}</div>
								            </ui-select-choices>
								          </ui-select>
										<!-- <input type="text" class="form-control" id="typebarcodeInput" ng-model="barcodeInput"  placeholder="Type bar code & Click on Go button.."   uib-typeahead="bar as bar.BarCode for bar in BarArr | filter:$viewValue"> -->
									</div>
									<!-- <div class="col-sm-2">
										<button type="button" class="btn btn-sm btn-success" ng-click="GetSelectBar()">Go</button>
				
									</div> -->
								</div>
								<div id="no-more-tables">
									<table class="table table-striped table-bordered" ng-show="InvPkId!=undefined">
									<thead>
										<tr>
											<!-- <th>Stock At</th> -->
											<th>Item Name</th>
											<th>Roll No</th>
											<th>Avl Qty</th>
										</tr>
									</thead>
									<tbody>
										<input type="hidden" value="{{InvPkId}}">
										<input type="hidden" value="{{PkId_RawPurchaseMasterDetails}}">
										<input type="hidden" value="{{ProductId}}">
										<!-- <td data-title="Stock At">{{GoDownName}}</td> -->
										<td data-title="Item Name">{{Micron}} {{ProductName}} {{Size}} MM</td>
										<td data-title="Roll No">{{UniqueRollNo}}</td>
										<td data-title="Avl Qty">{{Avlqty}} {{Unit}}</td>
									</tbody>
								</table>
								
								<div ng-show="InvPkId!=undefined">
								<div class="clearfix">&nbsp;</div>
								<h4 ng-show="Verions.length>0">Please Enter Size</h4>
								<div ng-repeat="version in Verions">
								<table class="table table-striped table-bordered"  >
								<thead>
									<tr>
										<th>Size (MM)</th>
										<th>Remarks</th>
										<th>ACTION</th>
									</tr>
								</thead>
								<tbody>
									<div class="row">
										<div class="col-md-6">
											<h3>Set {{$index+1}}</h3>
										</div>
										<div class="col-md-6">
											<button type="button" class="btn btn-sm btn-primary"  ng-click="AddMore(version)"><i class="ace-icon fa fa-plus bigger-130"></i> Add Size in Set {{$index+1}}</button>
											&nbsp;<button type="button" class="btn btn-sm btn-danger" ng-click="DeleteCard($index)"><i class="ace-icon fa fa-remove bigger-130"></i> Remove Set {{$index+1}}</button>
										</div>
									</div>
									
									<tr ng-repeat="bat in version.BatchArr">
										<td data-title="Item Details" class="center">
										<div class="form-group">
											<div class="col-sm-12">
												<div class="input-group">

										<input type="hidden" value="{{bat.RollNo}}">
											<input type="hidden" value="{{bat.EntryPkId}}">
											<input type="hidden" value="{{bat.quantity}}">

											<input type="text" id="splitsize" placeholder="" class="form-control" name="splitsize{{$index}}" data-ng-model="bat.splitsize" maxlength="10" required="" class="form-control" number>
											<span class="input-group-addon">
														MM
													</span>
												</div>
											<!-- valid-number="" allow-decimal="true" allow-negative="false" decimal-upto="3" -->

											<div class="error" data-ng-show="submitted || AddCategoryForm.splitsize{{$index}}.$dirty && AddCategoryForm.splitsize{{$index}}.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.splitsize{{$index}}.$error.required">Size is required.</small>
											</div>
										</div>
										</td>
										
										<td data-title="Remarks" class="center">
											<div class="form-group">
											<div class="col-sm-12">
												<div class="input-group">
													
													<textarea id="remarks" class="form-control" name="remarks{{$index}}" data-ng-model="bat.remarks"></textarea>
												</div>
												<div class="error" data-ng-show="submitted || AddCategoryForm.remarks{{$index}}.$dirty && AddCategoryForm.remarks{{$index}}.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.remarks{{$index}}.$error.required">Remarks is required.</small>
												</div>
											</div>
											</div>
										</td>
										
										<!-- <td data-title="Qty" class="center">
											<div class="form-group">
											<div class="col-sm-12">
												<div class="input-group">
													<input type="text" id="quantity" placeholder="" class="form-control" name="quantity{{$index}}" data-ng-model="bat.quantity" maxlength="10" required="" class="form-control" valid-number="" allow-decimal="true" allow-negative="false" decimal-upto="3">
													<span class="input-group-addon">
														{{Unit}}
													</span>
												</div>
												<div class="error" data-ng-show="submitted || AddCategoryForm.quantity{{$index}}.$dirty && AddCategoryForm.quantity{{$index}}.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.quantity{{$index}}.$error.required">QUANTITY is required.</small>
												</div>
											</div>
											</div>
										</td> -->
										<td data-title="ACTION">
											<span ng-if="bat.delflag==0">
												<a href="" class="orange" ng-click="removeRow(version,$index)">
												<i class="ace-icon fa fa-remove bigger-130"></i> Remove
											</a>
											</span>
										</td>
									</tr>
									<tr>
										<td>
											<span id="sumbasictotal" ng-model="version.sumbasictotal" ng-show="span1" ng-bind=BasicSum()></span>TOTAL: {{version.sumbasictotal ? version.sumbasictotal : 0}} MM
										</td>
										<td class="" colspan="2">&nbsp;</td>
									</tr>
									</tbody>
								</table>
							</div>
							<div class="row">
								<div class="col-sm-12 col-md-4 col-lg-4">
								<button type="button" class="btn btn-sm btn-inverse" ng-click="AddCard()"><i class="ace-icon fa fa-plus bigger-130"></i> Add Set</button>
								</div>
								<div class="col-sm-12 col-md-4 col-lg-4">
								
								</div>
							</div>

								<!-- <div class="form-group">	
								<div class="col-sm-12 col-md-4 col-lg-4">							
								<button type="button" class="btn btn-sm btn-block btn-info" ng-click="AddMore()">Add More</button>
									</div>
								</div> -->
								</div>

								</div>
								<div class="clearfix">&nbsp;</div>

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
					<div id="modal-form" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
						<div class="modal-dialog  modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<div class="col-xs-12">
									<div class="pull-left">
										<h4>Add Job</h4>
									</div>
									<div class="pull-right">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
									</div>
									</div>
								</div>
								<div class="modal-body">
								<div class="row">
									<form class="form-horizontal" role="form" id="AddCustomerForm" name="AddCustomerForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="AddJobData()">	

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Customer name"> Job Name <span class="error">*</span></label>
										<div class="col-sm-6">
											<input class="form-control" type="text" id="newjobname" name="newjobname" required placeholder="" autofocus data-ng-model="newjobname" data-ng-minlength="1" >
											<div class="error" data-ng-show="submitted || AddCustomerForm.newjobname.$dirty && AddCustomerForm.newjobname.$invalid">
												<small class="error" data-ng-show="AddCustomerForm.newjobname.$error.required">Job Name is required.</small>
												<small class="error" data-ng-show="AddCustomerForm.newjobname.$error.minlength">Job Name is required to be at least 1 characters</small>
												<!-- <small class="error" data-ng-show="AddCustomerForm.jobname.$error.pattern">Job Name should alphabets ex:abcd</small> -->
											</div>
										</div>
									</div>

									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="submit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>
											&nbsp; &nbsp;
											<button class="btn btn-inverse" type="button" data-ng-click="GotoList()">
												<i class="ace-icon fa fa-close bigger-110"></i>
												Cancel
											</button>
										</div>
									</div>
								</form>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--modal end-->
					
                <div id="myModal" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
						<div class="modal-dialog  modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<div class="col-xs-12">
									<div class="pull-left">
										<h4>Roll Details</h4>
									</div>
									<div class="pull-right">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
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
