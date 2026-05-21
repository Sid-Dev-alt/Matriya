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
    	<script src="angularjs/list-inventory-adjust-script.js"></script>
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
							<li class="active">Inventory Adjust</li>
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
												<th>Reason</th>
												<th>Description</th>
												<th>Reference No</th>
												<th>Type</th>
												<th>Action</th>
											</tr>
										</thead>										
										<tbody >
											<tr  ng-repeat="cat in pagedItems">
												<!-- <td data-title="Sl.No">{{$index+1}}</td> -->
												<td data-title="Date">{{cat.EntryDate}}</td>
												<td data-title="Reason">{{cat.Reason}}</td>
												<td data-title="Description">{{cat.Description}}
													<p ng-if="cat.Description==undefined">&nbsp;</p>
												</td>
												<td data-title="Reference No">
												{{cat.Reference}}
												<p ng-if="cat.Reference==undefined">&nbsp;</p>
												</td>
												
												<td data-title="Type">
												{{cat.ModeofAdjust}}
												</td>
												<td data-title="Action">
													<button type="button" class="btn btn-xs btn-info" data-ng-click="EditCat(cat.data2)">View &nbsp;
														<i class="ace-icon fa fa-eye bigger-120"></i>
													</button>&nbsp;
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
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
								<div class="col-xs-12">
									<ul id="tasks" class="item-list" ng-repeat="view in data2">
									<li class="item-orange clearfix">
										<label class="inline">
											<h4 class="lbl"> {{view.ProductName}}</h4>
											<p>SKU: {{view.SKU}}</p>
											<p ng-if="view.TrackingMode!='None'">Batch No: {{view.BatchNoORSrNo}}</p>
										</label>

										<div class="pull-right">
											<h4 class="label label-warning arrowed arrowed-right">{{view.Quantity}}</h4>
										</div>
									</li>
								</ul>
								<!-- PAGE CONTENT BEGINS -->
								 <form class="form-horizontal" role="form" id="AddCategoryForm" name="AddCategoryForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="AddCategoryData()" style="display: none">
									<input type="hidden" value="{{FormPkId}}">
									<input type="hidden" value="{{ProductId}}">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="reason"> Selected Item <span class="error">*</span></label>
										<div class="col-sm-6">
										<input type="text" id="ProductName" placeholder="" class="form-control"  name="ProductName" required data-ng-model="ProductName" disabled="">
											<div class="error" data-ng-show="submitted || AddCategoryForm.ProductName.$dirty && AddCategoryForm.ProductName.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.ProductName.$error.required">Item is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="reason"> Reference Number </label>
										
										<div class="col-sm-6">
										<input type="text" id="referencenum" placeholder="" class="form-control"  name="referencenum"  data-ng-model="referencenum">
											<div class="error" data-ng-show="submitted || AddCategoryForm.referencenum.$dirty && AddCategoryForm.referencenum.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.referencenum.$error.required">Reference Number is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="reason"> Date <span class="error">*</span></label>

										<div class="col-sm-6">
										<input type="text" class="form-control" name="entrydate"  placeholder="" id="entrydate" ng-model="entrydate" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened" ng-click="singleopen($event,'opened')"  datepicker-options="assDate1" required=""> 
											<div class="error" data-ng-show="submitted || AddCategoryForm.entrydate.$dirty && AddCategoryForm.entrydate.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.entrydate.$error.required">Date is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="reason"> Reason <span class="error">*</span></label>
										
										<div class="col-sm-6">
										<input type="text" id="reason" placeholder="" class="form-control"  name="reason" required data-ng-model="reason"  uib-typeahead="rsn for rsn in ReasonArr | filter:$viewValue">
											<div class="error" data-ng-show="submitted || AddCategoryForm.reason.$dirty && AddCategoryForm.reason.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.reason.$error.required">Reason is required.</small>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="">Description </label>
										
										<div class="col-sm-6">
											<textarea type="text" id="description" placeholder="" class="form-control" name="description" data-ng-model="description" value=""></textarea>
												<div class="error" data-ng-show="submitted || AddCategoryForm.description.$dirty && AddCategoryForm.description.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.description.$error.required">Description is required.</small>
												</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="Choose ">Mode of Adjust <span class="error">*</span></label>
										<div class="col-sm-6">
										<div class="control-group" >
										<div class="radio">
											<label>
												<input name="adjust" ng-model="adjust" type="radio" class="ace" ng-required="!adjust" value="Add" ng-change="ChangeType(adjust)">
												<span class="lbl"> Add</span>
											</label>
										</div>
										<div class="radio">
											<label>
												<input name="adjust" ng-model="adjust" type="radio" class="ace" ng-required="!adjust" value="Minus" ng-change="ChangeType(adjust)">
												<span class="lbl"> Subtract</span>
											</label>
										</div>
										</div>
										<div class="error" data-ng-show="submitted || AddCategoryForm.adjust.$dirty && AddCategoryForm.adjust.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.adjust.$error.required"> is required.</small>
										</div>
										</div>
									</div>
						
									<div class="clearfix">&nbsp;</div>
								<div>
								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="Opening Stock">Quantity Available <span class="error">*</span></label>
									<div class="col-sm-6">
										<input type="text" id="AvlQty" placeholder="" class="form-control" name="AvlQty" data-ng-model="AvlQty" maxlength="10" class="form-control" number disabled="">
									</div>
								</div>

								<div class="form-group" ng-if="TrackingMode=='None' ||  (TrackingMode=='Serial' &&  adjust=='Add')">
									<label class="col-sm-3 control-label no-padding-right" for="Opening Stock">Enter Stock <span class="error">*</span></label>
									<div class="col-sm-6">
										<input type="text" id="openstock" placeholder="" class="form-control" name="openstock" data-ng-model="$parent.openstock" maxlength="10" required="" class="form-control" number ng-change="GetFreshQty(AvlQty,$parent.openstock,adjust,TrackingMode)">
											<div class="error" data-ng-show="submitted || AddCategoryForm.openstock.$dirty && AddCategoryForm.openstock.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.openstock.$error.required">Opening Stock is required.</small>
											</div>
									</div>
								</div>

								<!-- <div class="form-group" ng-if="TrackingMode=='Serial' && ">
									<label class="col-sm-3 control-label no-padding-right" for="Opening Stock">Enter Stock <span class="error">*</span></label>
									<div class="col-sm-6">
										<input type="text" id="openstock" placeholder="" class="form-control" name="openstock" data-ng-model="$parent.openstock" maxlength="10" required="" class="form-control" number ng-change="GetFreshQty(AvlQty,$parent.openstock,adjust,TrackingMode)">
											<div class="error" data-ng-show="submitted || AddCategoryForm.openstock.$dirty && AddCategoryForm.openstock.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.openstock.$error.required">Opening Stock is required.</small>
											</div>
									</div>
								</div> -->

								<div class="form-group" ng-if="TrackingMode=='Serial' && adjust=='Add'">
									<label class="col-sm-3 control-label no-padding-right" for="Serial Numbers">Enter the serial numbers for your Opening Stock </label>
									<div class="col-sm-6">
										<textarea type="text" id="serailnumber" placeholder="" class="form-control" rows="10" name="serailnumber" data-ng-model="$parent.serailnumber" class="form-control" required=""  ng-trim="false" restrict-field="$parent.serailnumber"></textarea>
										<div class="clearfix">&nbsp;</div>
										<small>Type (comma separated) values. Ex:12,25</small>
											<div class="error" data-ng-show="submitted || AddCategoryForm.serailnumber.$dirty && AddCategoryForm.serailnumber.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.serailnumber.$error.required">Enter Serial Numbers is required.</small>
											</div>
									</div>
								</div>

								<div class="form-group" ng-if="TrackingMode=='None'">
									<label class="col-sm-3 control-label no-padding-right" for="Opening Stock">New Quantity on hand <span class="error">*</span></label>
									<div class="col-sm-6">
										<input type="text" id="freshquantity" placeholder="" class="form-control" name="freshquantity" data-ng-model="$parent.freshquantity" maxlength="10" required="" class="form-control" number disabled="">
									</div>
								</div>

									<div id="no-more-tables">
									<table class="table table-striped table-bordered" ng-if="TrackingMode=='Batch' && adjust=='Add'">
									<thead>
										<tr>
											<th>BATCH REFERENCE</th>
											<th>MANUFACTURER BATCH</th>
											<th>MANUFACTURED DATE</th>
											<th>EXPIRY DATE</th>
											<th>QUANTITY IN</th>
											<th>ACTION</th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="bat in NewBatchArr">
											<td data-title="BATCH REFERENCE" class="center">
											<div class="form-group">
												<div class="col-sm-12">
												<input type="text" id="batchno" placeholder="" class="form-control" name="batchno{{$index}}" data-ng-model="bat.batchno" required="">
												<div class="error" data-ng-show="submitted || AddCategoryForm.batchno{{$index}}.$dirty && AddCategoryForm.batchno{{$index}}.$invalid">
													<small class="error" data-ng-show="AddCategoryForm.batchno{{$index}}.$error.required">Batch No is required.</small>
													</div>
												</div>
											</div>
											</td>
											<td data-title="MANUFACTURER BATCH"  class="center">
												<div class="form-group">
												<div class="col-sm-12">
												<input type="text" id="manfacturebatch" placeholder="" class="form-control" name="manfacturebatch{{$index}}" data-ng-model="bat.manfacturebatch" >
													<div class="error" data-ng-show="submitted || AddCategoryForm.manfacturebatch{{$index}}.$dirty && AddCategoryForm.manfacturebatch{{$index}}.$invalid">
														<small class="error" data-ng-show="AddCategoryForm.manfacturebatch{{$index}}.$error.required">Manfacturer Batch is required.</small>
													</div>
												</div>
												</div>
											</td>
											<td data-title="MANUFACTURED DATE"  class="center">
											<div class="form-group">
												<div class="col-sm-12">
												<input type="text" class="form-control" name="mfgdate{{$index}}"  placeholder="" id="mfgdate" ng-model="bat.mfgdate" uib-datepicker-popup="dd-MMM-yyyy" is-open="bat.opened" ng-click="open($event,bat)"  datepicker-options="assDate1" > 
													<div class="error" data-ng-show="submitted || AddCategoryForm.mfgdate{{$index}}.$dirty && AddCategoryForm.mfgdate{{$index}}.$invalid">
														<small class="error" data-ng-show="AddCategoryForm.mfgdate{{$index}}.$error.required">Manfacture date is required.</small>
													</div>
												</div>
											</div>
											</td>
											<td data-title="EXPIRY DATE"  class="center">
												<div class="form-group">
													<div class="col-sm-12">
													<input type="text" class="form-control" name="expdate{{$index}}"  placeholder=""  id="expdate" ng-model="bat.expdate" uib-datepicker-popup="dd-MMM-yyyy" is-open="bat.opened1" ng-click="open1($event,bat)"  datepicker-options="assDate1" > 
														<div class="error" data-ng-show="submitted || AddCategoryForm.expdate{{$index}}.$dirty && AddCategoryForm.expdate{{$index}}.$invalid">
															<small class="error" data-ng-show="AddCategoryForm.expdate{{$index}}.$error.required">Expire date is required.</small>
														</div>
													</div>
												</div>
											</td>
											<td data-title="QUANTITY IN" class="center">
												<div class="form-group">
												<div class="col-sm-12">
												<input type="text" id="addquantity" placeholder="" class="form-control" name="addquantity{{$index}}" data-ng-model="bat.addquantity" maxlength="10" required="" class="form-control" number ng-change="calculateSum()">
													<div class="error" data-ng-show="submitted || AddCategoryForm.addquantity{{$index}}.$dirty && AddCategoryForm.addquantity{{$index}}.$invalid">
														<small class="error" data-ng-show="AddCategoryForm.addquantity{{$index}}.$error.required">QUANTITY is required.</small>
													</div>
												</div>
												</div>
											</td>
											<td data-title="ACTION" >
												<!-- <a class="orange" href="#modal-form" data-toggle="modal" ng-click="EditArr(bat.BatchNo,bat.ManfcBatch,bat.Mfgdate,bat.Expdate,bat.Quantity,$index)">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a> -->

												<a class="red" href="" data-ng-click="removeRow($index)">
													<i class="ace-icon fa fa-trash bigger-130"></i>
												</a>
											</td>
										</tr>
										<tr>
                                		<td colspan="4" align="right"><strong>Total Quantity</strong></td>
										<td >
											<span id="totalSum" ng-model="sum" ng-show="span1" ng-bind="calculateSum()"></span>{{sum ? sum : 0}}</td>

										<td>&nbsp;</td>
										</tr>
										<tr>
											<td class=""><button type="button" class="btn btn-sm btn-block btn-info" ng-click="AddMore()">Add More</button></td>
											<td class="" colspan="5">
											
											</td>	
										</tr>
									</tbody>

								</table>
							</div>
									<div id="no-more-tables">
									<table class="table table-striped table-bordered" ng-if="TrackingMode!='None' && adjust=='Minus'">
									<thead>
										<tr>
											<th>{{TrackingMode}} No</th>
											<th>Qty Adjusted</th>
											<th>New Qty on hand</th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="oldbat in BatchArr">
											<td data-title="{{TrackingMode}} No" class="center">
											<div class="form-group">
												<div class="col-sm-12">
													{{oldbat.InvPkId}}
													<p>{{TrackingMode}} No: {{oldbat.batchno}}</p>
													<p>Qty Avl: {{oldbat.quantity}}</p>
												</div>
											</td>											
											<td data-title="Qty Adjusted" class="center">
												<div class="form-group">
												<div class="col-sm-12">
												<input type="text" id="setquantity" placeholder="" class="form-control" name="setquantity{{$index}}" data-ng-model="oldbat.setquantity" maxlength="10" required="" class="form-control" number ng-init="oldbat.setquantity='0'" ng-change="SetMinusQty(oldbat.quantity,oldbat.setquantity,$index)">
													<div class="error" data-ng-show="submitted || AddCategoryForm.setquantity{{$index}}.$dirty && AddCategoryForm.setquantity{{$index}}.$invalid">
														<small class="error" data-ng-show="AddCategoryForm.setquantity{{$index}}.$error.required">QUANTITY is required.</small>
													</div>
												</div>
												</div>
											</td>
											<td data-title="New Qty on hand" class="center">
												{{oldbat.newquantity}}
											</td>
										</tr>
										<tr>
                                		<td>&nbsp;</td>
										<td >
											<span id="totalSum" ng-model="subsum" ng-show="span1" ng-bind="OldcalculateSum()"></span>{{subsum ? subsum : 0}}</td>

										<td>&nbsp;</td>
										</tr>
									</tbody>

								</table>
							</div>

									<!-- <div class="form-group" ng-if="invtrack=='Batch'">
										<div class="col-sm-3">&nbsp;</div>
											<div class="col-sm-6">
											</a>
										</div>
									</div> -->
									<!--modal-->
								<!-- <div id="modal-form" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
									<div class="modal-dialog  modal-lg">
										<div class="modal-content">
											<div class="modal-header">
												<div class="col-xs-12">
												<div class="pull-left">
												</div>
												<div class="pull-right">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
												</div>
												</div>
											</div>

											<div class="modal-body">
											<div class="row">

											
											</div>

											<div class="clearfix form-actions">
												<div class="col-md-offset-3 col-md-9">
													<button type="button" class="btn btn-success" ng-click="AddBatch()">
														<i class="ace-icon fa fa-check bigger-110"></i>
														Add
													</button>&nbsp;&nbsp;
													<button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
												</div>
											</div>
										</div>
										</div>
									</div>
								</div> --><!--modal -->


									<!-- <div class="form-group" ng-if="pagetitle=='Edit Product'">
										<label class="col-sm-3 control-label no-padding-right" for="Status"> Status <span class="error">*</span></label>
										<div class="col-sm-9">
											<select  id="status" placeholder="" class="form-control" name="status"  data-ng-model="$parent.status" required="" convert-to-number>
												<option value="">Select</option>
												<option value="1">Active</option>
												<option value="0">Inactive</option>
											</select>
											<div class="error" data-ng-show="submitted || AddCategoryForm.status.$dirty && AddCategoryForm.status.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.status.$error.required">Status  is required.</small>
											</div>
										</div>
									</div> -->


									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="submit" data-ng-disabled="FormValid">
												<i class="ace-icon fa fa-check bigger-110"></i>
												SAVE
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