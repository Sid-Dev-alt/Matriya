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
    	<script src="angularjs/roll-history-script.js"></script>
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="ReportModule" data-ng-controller="ReportController" ng-init="GetItems()">
		<?php include_once("../top.php");?>
		<div class="main-container ace-save-state" id="main-container">
			
			<?php include_once("sidebar.php");?>

			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content" >
						<div id="no-more-tables">
							<div class="row">
								<div class="col-xs-12 page-header">
			                  	 <div class="col-xs-8 col-sm-8 col-lg-9">
			                  	 	<div class="" >
										<h1>Roll History</h1>
										</div>
			<!-- /.page-header --></div>
				              </div>							
							</div>
							<div class="row">
								 <form class="form-horizontal" role="form" id="AddCategoryForm" name="AddCategoryForm" autocomplete="off" enctype="multipart/form-data" novalidate="" ng-submit="GetListData()">
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label no-padding-right" for="Sub Projects"> Enter Roll No <span class="error">*</span></label>
									<input type="text" class="form-control" ng-model="rollno" name="rollno">
										<!-- <ui-select ng-model="$parent.itemnname" theme="selectize" title="Choose a person" ng-change="GetListData()" >
							            <ui-select-match placeholder="Select or search">{{$select.selected.TotalProductName}}</ui-select-match>
							            <ui-select-choices  repeat="item in ItemArray | filter: $select.search">
							              <div><span ng-bind-html="item.TotalProductName | highlight: $select.search"></span></div>
							            </ui-select-choices>
							          </ui-select> -->
										<div class="error" data-ng-show="submitted || AddCategoryForm.rollno.$dirty && AddCategoryForm.rollno.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.rollno.$error.required">Roll No is required.</small>
										</div>
								</div>
							</div>
							<!-- <div class="col-md-4">
								<div class="form-group">
									<label class="control-label no-padding-right" for="Sub Projects"> Sizes <span class="error">*</span></label>
										<ui-select ng-model="$parent.itemsize" theme="selectize" title="Choose a person">
							            <ui-select-match placeholder="Select or search">{{$select.selected.ProductSize}} MM</ui-select-match>
							            <ui-select-choices  repeat="item in SizeArray | filter: $select.search">
							              <div><span ng-bind-html="item.ProductSize | highlight: $select.search"></span> MM</div>
							            </ui-select-choices>
							          </ui-select>
										<div class="error" data-ng-show="submitted || AddCategoryForm.itemsize.$dirty && AddCategoryForm.itemsize.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.itemsize.$error.required">Select Godown is required.</small>
										</div>
								</div>

							</div> -->
								<!-- <div class="form-group col-md-5">
									<label class="col-sm-3 control-label no-padding-right" for="reason"> To Date <span class="error">*</span></label>

									<div class="col-sm-6">
									<input type="text" class="form-control" name="todate"  placeholder="" id="todate" ng-model="todate" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened2" ng-click="toopen($event,'opened2')"  datepicker-options="assDate1">
										<div class="error" data-ng-show="submitted || AddCategoryForm.todate.$dirty && AddCategoryForm.todate.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.todate.$error.required">Date is required.</small>
										</div>
									</div>
								</div> -->
								<div class="col-md-2">
								<div class="form-group">
									<p style="margin: 0 0 7px;">&nbsp;</p>
									<button type="submit" class="btn btn-primary btn-sm" >Search</button>
									<div class="clearfix">&nbsp;</div>
								</div>
								</div>
							</form>
						</div>
							<!-- <span class="error">{{errmsg}}</span> -->

							
							<div class="row">
								<div class="col-xs-12">
									<!-- PAGE CONTENT BEGINS -->
									<div class="card">
										<div class="space-6"></div>

									<div class="card-body" ng-if="result=='OK'">
										<h4>{{TotalName}} {{InvProductSize}} MM</h4>
										<p>{{InvUniqueRollNo}}</p>
										<h4 ng-if="IsInvoice=='0'">Avl Qty: {{AvlQuantity}} {{Unit}}</h4>
										<h4 ng-if="IsInvoice=='1'">Avl Qty: 0.000 {{Unit}}</h4>
										<ul>
											<li><span class="text-danger">{{TotalName}} {{POProductSize}} MM ( {{PurchaseRollNo}} ) </span> Purchased from the <b> {{VendorName}}</b> vendor on <b>{{RawPODate | date: 'd MMM y'}}</b></li>
											 <li>Purchase Weight/Qty is <b>{{PurchaseQty}} {{Unit}}</b></li>
											 <!-- <li>Stock received at <b>{{GoDownName}}</b></li> -->
												

											<li ng-if="IsSlitted=='1'"><span class="text-success">{{TotalName}} {{InvProductSize}} MM ( {{InvUniqueRollNo}} ) </span> has been slit from <span class="text-danger"> {{TotalName}} {{POProductSize}} MM </span> on <b>{{SlitDate | date: 'd MMM y'}} </b></li>	

											<li ng-if="IsSlitted=='1'">Slit Qty is <b>{{SlitQty}} {{Unit}}</b></li>

											<li ng-if="IsSplitQty=='1'">
												<p>Roll has slitted into:</p>
												<div ng-repeat="slit in data2">
													<p><b>{{TotalName}} <span class="text-warning">{{slit.SplitRollNo}}</span> {{slit.SplitSize}} MM</b> Weight/Qty Is <b>{{slit.SlitQty}} {{Unit}}</b></p>
												</div>
											</li>
											

											<li ng-if="IsInvoice>'0'">
												Roll has been dispatched to <span class="text-success">{{CustomerName}}</span> on <b>{{InvoiceDate | date: 'd MMM y'}} </b></li>

											
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
			</div>
		</div>
		<?php include_once("../footer.php");?>
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