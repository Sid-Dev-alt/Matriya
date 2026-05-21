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
    	<script src="angularjs/printing-script.js"></script>
    	<style type="text/css">
    		.new-row {
  clear: left;
}
    	</style>
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
									<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf"  datatable="ng" dt-options="vm.dtOptions" dt-column-defs="vm.dtColumnDefs">
										<thead class="cf">
											<tr>
												<!-- <th>Sl.No</th> -->
												<th>Date</th>
												<th>Job Name</th>
												<th>Product Name</th>
												<th>Roll No</th>
												<th>Qty Consumed</th>
												<th>Print Output</th>
												<!-- <th>Action</th> -->
											</tr>
										</thead>										
										<tbody >
											<tr ng-repeat="cat in pagedItems">
												<td data-title="Date">{{cat.PrintDate | date: 'd MMM y'}}</td>
												<td data-title="Job Name">{{cat.JobName}}</td>
												<td data-title="Product Name">{{cat.ProductName}}</td>
												<td data-title="RollNo">{{cat.RollNo}}</td>
												<td data-title="Qty Consumed">{{cat.ConsumeQty}}</td>
												<td data-title="PrintOutput">{{cat.PrintOutput}}</td>
												<!-- <td data-title="Action">
													<a href="" ng-click="EditOrder(cat.FormPkId,cat.PrintDate,cat.PkId_JobMaster,cat.JobName,cat.PkId_Category,cat.ProductId_ProductMaster,cat.ProductName,cat.PkId_RawPurchaseMasterDetails,cat.RollNo,cat.ConsumeQty,cat.Balance,cat.Waste,cat.PrintOutput,cat.Remarks)"  class="btn btn-xs btn-info">Edit &nbsp;
														<i class="ace-icon fa fa-edit bigger-120"></i>
													</a>&nbsp;
												</td> -->
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
								<form class="form-horizontal" role="form" id="AddCategoryForm" name="AddCategoryForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="AddCategoryData()">
									<input type="hidden" value="{{FormPkId}}">
								<!-- <div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="Product Id"> Production Id <span class="error">*</span></label>
									
									<div class="col-sm-3">
										<input type="text" id="salesorder" placeholder="" class="form-control" name="productionid" required  data-ng-model="productionid" disabled="">
											<div class="error" data-ng-show="submitted || AddCategoryForm.productionid.$dirty && AddCategoryForm.productionid.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.productionid.$error.required">Production Id is required.</small>
											</div>
									</div>
								</div>-->

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="reason"> Date <span class="error">*</span></label>

									<div class="col-sm-3">
									<input type="text" class="form-control" name="entrydate"  placeholder="" id="entrydate" ng-model="entrydate" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened1" ng-click="singleopen($event,'opened1')"  datepicker-options="assDate1" required=""> 
										<div class="error" data-ng-show="submitted || AddCategoryForm.entrydate.$dirty && AddCategoryForm.entrydate.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.entrydate.$error.required">Date is required.</small>
										</div>
									</div>
								</div>

								<div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="Sub Projects"> Job Name <span class="error">*</span></label>
								<div class="col-sm-3">
									<input type="hidden" ng-model="Oldjobid">
									<!-- <input type="hidden" ng-model="jobid"> -->
									<!-- <input type="text" id="jobname"  class="form-control" name="jobname" required   data-ng-model="jobname"  uib-typeahead="cust as cust.JobName for cust in JobArray | filter:$viewValue" typeahead-editable="false" typeahead-on-select="onJobSelect($item, $model, $label)" placeholder="Type & Select"> -->
									<ui-select ng-model="$parent.jobname" theme="selectize" title="Choose a person" >
						            <ui-select-match placeholder="Select or search">{{$select.selected.JobName}}</ui-select-match>
						            <ui-select-choices  repeat="item in JobArray | filter: $select.search">
						              <div><span ng-bind-html="item.JobName | highlight: $select.search"></span></div>
						            </ui-select-choices>
						          </ui-select>

									<!-- <select type="text" id="jobname"  class="form-control" name="jobname" required   data-ng-model="jobname" >
										<option value="">Select</option>
										<option ng-repeat="job in JobArray" value="{{job.PkId}}">{{job.JobName}}</option>
									</select> -->
									<div class="error" data-ng-show="submitted || AddCategoryForm.jobname.$dirty && AddCategoryForm.jobname.$invalid">
										<small class="error" data-ng-show="AddCategoryForm.jobname.$error.required">Job Name is required.</small>
									</div>
									<a href="#modal-form" data-toggle="modal"><span class="label label-info arrowed-right arrowed-in" ng-click="AddCustomer()">New Job ?</span></a>
								</div>
								</div>
								
								<div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="Raw Category">Raw Category <span class="error">*</span></label>
								<div class="col-sm-3">
									<ui-select ng-model="$parent.rawcategory" theme="selectize" title="Choose a person" name="rawcategory" ng-change="GetProducts($parent.rawcategory)">
						            <ui-select-match placeholder="Select or search" >{{$select.selected.CategoryName}}</ui-select-match>
						            <ui-select-choices  repeat="item in CategoryArray | filter: $select.search">
						              <div><span ng-bind-html="item.CategoryName | highlight: $select.search"></span></div>
						            </ui-select-choices>
						          </ui-select>
									<!-- <select type="text" id="rawcategory" placeholder="" class="form-control" name="rawcategory" data-ng-model="rawcategory" required="" convert-to-number ng-change="GetProducts(rawcategory)">
										<option value="">Select</option>
										<option ng-repeat="cat in CategoryArray" value="{{cat.PkId}}">{{cat.CategoryName}}</option>
									</select> -->
									<div class="error" data-ng-show="submitted || AddCategoryForm.rawcategory.$dirty && AddCategoryForm.rawcategory.$invalid">
										<small class="error" data-ng-show="AddCategoryForm.rawcategory.$error.required">Raw Category is required.</small>
									</div>
								</div>
								</div>

								<div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for=" Size"> Size </label>
								
								<div class="col-sm-3">
									<!-- <input type="hidden" value="{{oldproductid}}"> -->
									<ui-select ng-model="$parent.productsize" theme="selectize" title="Choose a person" name="productsize" ng-change="GetRolls($parent.productsize)">
						            <ui-select-match placeholder="Select or search">{{$select.selected.ProductName}}</ui-select-match>
						            <ui-select-choices  repeat="item in ProductArray | filter: $select.search">
						              <div><span ng-bind-html="item.ProductName | highlight: $select.search"></span></div>
						            </ui-select-choices>
						          </ui-select>
									<!-- <input type="hidden" value="{{productid}}">
									<input type="text" name="productsize" ng-model="productsize" uib-typeahead="pro as pro.ProductName for pro in ProductArray | filter:$viewValue" typeahead-editable="false" typeahead-on-select="onProductSelect($item, $model, $label)" class="form-control" placeholder="Type & Select"> -->
										<div class="error" data-ng-show="submitted || AddCategoryForm.productsize.$dirty && AddCategoryForm.productsize.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.productsize.$error.required">Size  is required.</small>
										</div>
								</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="reason">Roll No <span class="error">*</span></label>
									<div class="col-sm-3">
										<input type="hidden" value="{{oldrollno}}">
										<ui-select ng-model="$parent.rollno" theme="selectize" title="Choose a person" name="rollno">
							            <ui-select-match placeholder="Select or search">{{$select.selected.RollNo}}</ui-select-match>
							            <ui-select-choices  repeat="item in RollArr | filter: $select.search">
							              <div><span ng-bind-html="item.RollNo | highlight: $select.search"></span></div>
							            </ui-select-choices>
							          </ui-select>
										<!-- <select type="text" id="rollno" placeholder="" class="form-control" name="rollno" data-ng-model="rollno"  required="" ng-change="GetRollQty(rollno)" convert-to-number> 
											<option value="">Select</option>
											<option ng-repeat="rol in RollArr" value="{{rol.PkId}}">{{rol.RollNo}}</option>
										</select> -->
										<p style="display: none">{{$parent.rollno.SplitQty}}</p>
										<p ng-if="rollno!=undefined">Available Qty: {{$parent.rollno.RollAvlQty || 0.000 | number:3}} / kgs</p>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="reason">Consumed <span class="error">*</span></label>
									<div class="col-sm-3">
										<div class="input-group">
										<input type="hidden" value="{{oldconsumeqty}}">
										<input type="text" id="consumeqty" placeholder="" class="form-control" name="consumeqty" data-ng-model="consumeqty" maxlength="10" required="" class="form-control" valid-number allow-decimal="true" allow-negative="false" decimal-upto="3"  ng-change="ChkQty(rollavlqty,consumeqty)"> 
										 <!-- ng-change="ChkPoly($parent.polyrawweight,$parent.PolyAvlQty)" -->
										<span class="input-group-addon">
											Kgs
										</span>
										</div>
										<small class="error">{{PetErr}}</small>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="Sub Projects">Balance</label>
									<div class="col-sm-3">
										<div class="input-group">
										<input type="text" id="balance" placeholder="" class="form-control" name="balance" ng-model="balance"  disabled="" ng-bind="GetBlc()">
										<span class="input-group-addon">
											Kgs
										</span>
										</div>
										<!-- ng-bind="GetBlc()" -->
										<div class="error" data-ng-show="submitted || AddCategoryForm.balance.$dirty && AddCategoryForm.balance.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.balance.$error.required">Balance is required.</small>
										</div>
									</div>
								</div>

									<!-- <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Sub Projects">Pet Balance</label>
										<div class="col-sm-3">
											<input type="text" id="cnotes" placeholder="" class="form-control" name="polyblc" value="{{$parent.PolyAvlQty-$parent.polyrawweight  | number:3}}" disabled="" >
											<div class="error" data-ng-show="submitted || AddCategoryForm.polyblc.$dirty && AddCategoryForm.polyblc.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.polyblc.$error.required">Balance is required.</small>
											</div>
										</div>
									</div> -->
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Sub Projects">Waste</label>
										<div class="col-sm-3">
											<div class="input-group">
											<input type="text" id="wastage" placeholder="" class="form-control" name="wastage" data-ng-model="wastage" valid-number allow-decimal="true" allow-negative="false" decimal-upto="3">
											<span class="input-group-addon">
												Kgs
											</span>
											</div>
											<div class="error" data-ng-show="submitted || AddCategoryForm.wastage.$dirty && AddCategoryForm.wastage.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.wastage.$error.required">Waste is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Printed Output">Printed Output</label>
										<div class="col-sm-3">
											<div class="input-group">
											<input type="text" id="printoutput" placeholder="" class="form-control" name="printoutput" data-ng-model="printoutput" valid-number allow-decimal="true" allow-negative="false" decimal-upto="3">
											<span class="input-group-addon">
												Kgs
											</span>
											</div>
											<div class="error" data-ng-show="submitted || AddCategoryForm.printoutput.$dirty && AddCategoryForm.printoutput.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.printoutput.$error.required">Output is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for=""> remarks </label>
										<div class="col-sm-3">
											<textarea type="text" id="printremarks" placeholder="" class="form-control" name="printremarks" data-ng-model="printremarks" tabindex="17"></textarea>
											<div class="error" data-ng-show="submitted || AddCategoryForm.printremarks.$dirty && AddCategoryForm.printremarks.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.printremarks.$error.required">Remarks is required.</small>
											</div>
										</div>
									</div>

									<div class="clearfix form-actions" >
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="submit" data-ng-disabled="FormValid" ng-if="pagetitle!='Edit Production'">
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
											<button class="btn btn-info" type="submit" data-ng-disabled="FormValid">
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