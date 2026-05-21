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
    	<script src="angularjs/godown-wise-reports-script.js"></script>
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="ReportModule" data-ng-controller="ReportController" ng-init="GetGoDowns()">
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
										<h1>Godown wise Items</h1>
										</div>
			<!-- /.page-header --></div>
				              </div>							
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label no-padding-right" for="Sub Projects"> GoDown <span class="error">*</span></label>
										<ui-select ng-model="$parent.godownname" theme="selectize" title="Choose a person" ng-change="GetListData(pageno)" >
							            <ui-select-match placeholder="Select or search">{{$select.selected.GoDownName}}</ui-select-match>
							            <ui-select-choices  repeat="item in GoDownArray | filter: $select.search">
							              <div><span ng-bind-html="item.GoDownName | highlight: $select.search"></span></div>
							            </ui-select-choices>
							          </ui-select>
										<div class="error" data-ng-show="submitted || AddCategoryForm.godownname.$dirty && AddCategoryForm.godownname.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.godownname.$error.required">Select Godown is required.</small>
										</div>
								</div>

								<!-- <div class="form-group col-md-5">
									<label class="col-sm-3 control-label no-padding-right" for="reason"> To Date <span class="error">*</span></label>

									<div class="col-sm-6">
									<input type="text" class="form-control" name="todate"  placeholder="" id="todate" ng-model="todate" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened2" ng-click="toopen($event,'opened2')"  datepicker-options="assDate1">
										<div class="error" data-ng-show="submitted || AddCategoryForm.todate.$dirty && AddCategoryForm.todate.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.todate.$error.required">Date is required.</small>
										</div>
									</div>
								</div> -->
								<!-- <div class="form-group col-md-2">
									<button type="button" class="btn btn-primary btn-sm" ng-click="GetListData(pageno)">Submit</button>
									<div class="clearfix">&nbsp;</div>
									
								</div> -->
							</div>
							<!-- <span class="error">{{errmsg}}</span> -->

							
							<div class="row">
								<div class="col-xs-12">
									<!-- PAGE CONTENT BEGINS -->
									<div class="row">
										<div class="space-6"></div>

									<div class="dataTables_wrapper form-inline no-footer" ng-if="pagedItems.length!='0'">
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
										<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf"  datatable="ng" dt-options="vm.dtOptions" dt-column-defs="vm.dtColumnDefs">
											<thead class="cf">
												<tr>
													<th>Item Name</th>
													<th>Roll No</th>
													<th>Qty</th>
												</tr>
											</thead>										
											<tbody >
												<tr ng-repeat="cat in pagedItems">
												<td data-title="Item Name">{{cat.TotalProduct}}</td>
												<td data-title="Roll No">{{cat.UniqueRollNo}}</td>
												<td data-title="Qty">{{cat.Quantity}} {{cat.Unit}}</td>
												</tr>
												
											</tbody>
										</table>
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