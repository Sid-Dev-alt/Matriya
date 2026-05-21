<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']!="")
{
		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";
		
		$SAId = $_SESSION['UserId'];
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once("../title.php");?>
    <script src="angularjs/Invoice-script.js"></script>
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="CategoryModule" data-ng-controller="CategoryController"  ng-init="GetCustomers()">
		<?php include_once("../top.php");?>

		<div class="main-container ace-save-state" id="main-container">
			
			<?php include_once("sidebar.php");?>

			
   <div class="main-content">
    <div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-home home-icon"></i>
				<a href="#">Home</a>
			</li>
			<li class="active">Customer Purchase Form</li>
		</ul><!-- /.breadcrumb -->

		<!-- <div class="nav-search" id="nav-search">
			<form class="form-search">
				<span class="input-icon">
					<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
					<i class="ace-icon fa fa-search nav-search-icon"></i>
				</span>
			</form>
		</div> --><!-- /.nav-search -->
	</div>

    <div class="page-content" >
    	<div id="no-more-tables">
						<div class="row">
							<div class="col-xs-12 page-header">
								<div class="col-xs-12 col-sm-12 col-lg-6">
			                  	 	<div class="" >
										<h1>{{pagetitle}}</h1>
										</div>
								</div>
		                  	 	<div class="col-xs-12 col-sm-12 col-lg-6">
				                    <a href="" data-ng-click="GotoAdd()">
									<div class="label label-lg label-success arrowed-in arrowed-right pull-right" ng-show="FormList">
									<b>Add Purchase</b>
									</div></a>
			                  </div>
			              </div>
							
						</div>
							<div class="row" ng-show="FormList">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="space-6"></div>
								<div class="dataTables_wrapper form-inline no-footer">
									<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf" data-ng-init="GetListData()"   datatable="ng" dt-options="vm.dtOptions">
										<thead class="cf">
											<tr>
												<th>Order Id</th>
												<th>Order Date</th>
												<th>Customer Name</th>
												<th width="35%">Ship Address</th>
												<th>Amount</th>
												<th>View</th>
												<!-- <th>Action</th> -->
											</tr>
										</thead>										
										<tbody >
											<tr ng-repeat="sup in pagedItems | orderBy: 'OrderId'" >
												<td data-title="Order Id">{{sup.OrderId}}</td>
												<td data-title="Order Date">{{sup.OrderDate | date: 'dd MMM y'}}</td>
												<td data-title="Customer Name">{{sup.PersonName}}</td>
												<td data-title="Ship Address" width="35%">
												<p><i class="fa fa-user bigger-120"></i>&nbsp;{{sup.ShipPersonName}}<br>
												<i class="fa fa-phone bigger-120"></i>&nbsp;{{sup.ShipMobileNo}}<br>
												<i class="fa fa-map-marker bigger-120"></i>&nbsp;{{sup.ShipAdrs1}}<br>
												{{sup.ShipAdrs2}}<br>
												{{sup.ShipCity}}<br>
												{{sup.ShipState}} - {{sup.ShipPin}}</p>
												</td>
													<td data-title="TotalAmount">{{sup.TotalAmount}}</td>
												
												<td data-title="View">
													<a href="#modal-form" class="btn btn-block btn-danger " data-toggle="modal" ng-click="ViewOrder(sup.OrderId,sup.OrderDate,sup.PersonName,sup.TotalAmount,sup.data2)">View</a>
													<!-- <button type="button" class="btn btn-xs btn-info" data-ng-click="EditSupplier(sup.PkId,sup.POrderId,sup.PODate,sup.PkId_SupplierMaster,sup.PersonName,sup.ShopName,sup.POrderStatus,sup.RequiredBy,sup.Comments,sup.data2,$index)">View &nbsp;
														<i class="ace-icon fa fa-pencil bigger-120"></i>
													</button>&nbsp; -->
													<!--<button class="btn btn-xs btn-danger" data-ng-click="EditCat(cat.CategoryId,cat.CategoryName,cat.Description)">Delete &nbsp;
														<i class="ace-icon fa fa-trash-o bigger-120"></i>
													</button>-->
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								</div><!-- /.row -->
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
						<!--modal-->
						<div id="modal-form" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
						<div class="modal-dialog  modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="blue bigger">{{OrderId}}</h4>
								</div>

								<div class="modal-body">
								<div class="row">
									<div class="col-xs-12 col-sm-12">
										<h4 class="blue bigger">Customer Name: {{PersonName}}</h4>
										<h4 class="blue bigger">Order Date: {{OrderDate | date: 'dd MMM y'}}</h4>

										<table id="dynamic-table" class="table table-bordered table-striped table-condensed cf" >
										<thead class="cf">
											<tr>
												<th>Product Name</th>
												<th>Price</th>
												<th>Quantity</th>
												<th>Total</th>
											</tr>
										</thead>										
										<tbody >
											<tr ng-repeat="cap in orderdata" >
												<td data-title="ProTypeName">{{cap.ProTypeName}}</td>
												<td data-title="Price">{{cap.Price}}</td>
												<td data-title="Quantity">
													{{cap.Quantity}}<br>{{cap.KgType}} {{cap.QtyType}}</td>
													<td data-title="Total">{{cap.TotalVal}}</td>
											</tr>
											<tr><td colspan="3">Total</td>
												<td>{{TotalAmount}}</td>
											</tr>
										</tbody>
									</table>
									</div>
								</div><!-- cotntent-->
								</div>
							</div><!--modal -->
						</div><!-- /.modal-content -->	
						</div>
						<!--modal-->			
					</div><!--FOrm lising-->

    <div class="row" ng-show="FormAdd">
	    <div class="col-xs-12">
	      <!-- PAGE CONTENT BEGINS -->
		 <form class="form-horizontal" role="form" id="AddCategoryForm" name="AddCategoryForm" autocomplete="off" enctype="multipart/form-data" novalidate="" data-ng-submit="FinalSubmisssion()" >
		 		<input type="hidden" class="form-control"  name="FormPkId"  placeholder="" ng-model="FormPkId" disabled="" > 
					 <div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="Date"> Date <span class="error">*</span></label>
						<div class="col-sm-6">
					 		<input type="text" class="form-control" autofocus name="datte"  placeholder=" Enter Date" ng-model="datte" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened3" ng-click="open($event,'opened3')"  datepicker-options="assDate1" required=""  ng-disabled="ViewRecords" > 
					 	</div>
					 </div>
					 
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="Sub Projects"> Customer Name <span class="error">*</span></label>
						<div class="col-sm-6">
							<input type="text" id="customername" placeholder="" class="form-control" name="customername" required   data-ng-model="customername"  uib-typeahead="cust as cust.TotalName for cust in CustomerArray | filter:$viewValue" typeahead-editable="false">
								
								<div class="error" data-ng-show="submitted || AddCategoryForm.customername.$dirty && AddCategoryForm.customername.$invalid">
									<small class="error" data-ng-show="AddCategoryForm.customername.$error.required">Supplier is required.</small>
								</div>
						</div>
					</div>
				<!--list start-->
					<div class="row"  id="no-more-tables">
						<div class="space-6"></div>
					
	                <div class="clearfix"></div>

					<div class="dataTables_wrapper form-inline no-footer">
						<table class="table  table-striped table-condensed cf" >
							<thead class="cf">
								<tr >
									<th width="25%">Product</th>
									<th width="15%">Avaliable Qty</th>
									<th width="25%">Price</th>
									<th width="15%">Enter Qty</th>
									<th width="10%">Total</th>
									<th width="10%">Action</th>
								</tr>
							</thead>										
							<tbody >
								<tr ng-repeat="po in POArr">
									<td>
										<input type="hidden" id="ArrId" placeholder="" class="form-control"  required data-ng-model="po.ArrId" >

										<input type="text" id="product" placeholder="" class="form-control" name="product{{$index}}" required data-ng-model="po.product"  uib-typeahead="cat as cat.ProTypeName for cat in ProductTypeArray | filter:$viewValue"  typeahead-editable="false">
											<div class="error" data-ng-show="submitted || AddCategoryForm.product{{$index}}.$dirty && AddCategoryForm.product{{$index}}.$invalid">
												<small class="error" data-ng-show="AddCategoryForm.product{{$index}}.$error.required">Product Name is required.</small>
											</div>
									</td>
									<td>
										{{po.product.AvlStock || 0}} {{po.product.SalesIn}}
									</td>
									<td>
										<div class="control-group"  ng-repeat="kg in po.product.data2">
											<div class="radio-inline" >
												<label>
													<input name="Price{{$parent.$index}}" type="radio" class="ace input-lg" ng-change="handleRadioClick(kg,po.finalquantity,$parent.$index)"
													ng-model="po.Price" value="{{kg.Price}}"
													>
													<span class="lbl bigger-120"> {{kg.KgorQuantity}} {{kg.Type}} - Rs. {{kg.Price}} /-</span>
												</label>
											</div>

										</div>

									</td>
									<td>
										<input type="text" id="finalquantity" placeholder="" class="col-sm-12" name="finalquantity{{$index}}"  data-ng-model="po.finalquantity" pattern="^[0-9]+$" number placeholder="" required="" ng-change="GetTotalQty(po.KgType,po.finalquantity,$index)" >
										<small ng-if="po.SalesIn != null">Enter in {{po.SalesIn}}</small>
										<div class="error" data-ng-show="submitted || AddCategoryForm.finalquantity{{$index}}.$dirty && AddCategoryForm.finalquantity{{$index}}.$invalid">
											<small class="error" data-ng-show="AddCategoryForm.finalquantity{{$index}}.$error.required">Quantity is required.</small>
											<small class="error" data-ng-show="AddCategoryForm.finalquantity{{$index}}.$error.minlength">Quantity is required to be at least 1 digit</small>

										</div>
										<small class="error" ng-if="po.TotalQty>po.product.AvlStock">Qty not more than <br>{{po.product.AvlStock || 0}} {{po.product.SalesIn}}</small>
										
									</td>
									<td>
										{{po.Price * po.finalquantity || 0}} 
										({{po.TotalQty || 0}} {{po.Type}})
									</td>

									<td>
										<button type="button" ng-click="removeRow($index)"  class="btn btn-sm btn-danger">Remove</button>
									</td>
								</tr>
								<tr>
                                <td colspan="4" align="right"><strong>Total Amount</strong></td>
                                
                                <td ><span id="totalSum" ng-model="sum" ng-show="span1" ng-bind="calculateSum()"></span>{{sum ? sum : 0}}</td>
                                <td></td>
                              </tr>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="8">
										<p>&nbsp;</p>
                                	<button type="button" class="btn btn-sm btn-info" ng-click="addFormField()"><i class="fa fa-plus"></i> Add More</button>
                                </td>
                            	</tr>
                        	</tfoot>
						</table>
					</div>
					</div><!-- /.row -->
				<!-- list end-->
					<!-- <div class="form-group" >
					<label class="col-sm-3 control-label no-padding-right" for="Required"> Required By </label>
						<div class="col-sm-6">
					 		<input type="text" class="form-control" name="req_by"  placeholder="" ng-model="req_by" uib-datepicker-popup="dd-MMM-yyyy" is-open="opened.opened2" ng-click="open($event,'opened2')"  datepicker-options="assDate" > 
					 	</div>
					 </div> 
					<div class="form-group">
					  	<label class="col-sm-3 control-label no-padding-right" for="PinCode">Upload File </label>
						<div class="col-sm-6">
						  <input  type="file" id="IPkId" file-model="docfile" accept="image/*" style="display: none;">
						   <button type="button" id="uploadButton" class="btn btn-sm btn-primary text-small" onclick="document.getElementById('IPkId').click();" >
                            <i class="fa fa-paperclip" aria-hidden="true"></i>&nbsp;Choose Files..
                          </button> &nbsp;

                          {{docfile.name}}
                      	</div>
					</div>-->

					 <p>&nbsp;</p>
					 <div class="clearfix"></div>
					 <!-- <div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="Notes"> Purchase Notes </label>
						<div class="col-sm-6">
					 		<textarea class="form-control" name="notes"  placeholder="" ng-model="notes" disabled=""></textarea>
					 	</div>
					 </div> -->

					 <div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="Notes">Comments </label>
						<div class="col-sm-6">
					 		<textarea class="form-control" name="grncomments"  placeholder="" ng-model="grncomments"></textarea>
					 	</div>
					 </div>

					 <div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-success" type="submit"  >
								<i class="ace-icon fa fa-check bigger-110"></i>
								SUBMIT
							</button>&nbsp;&nbsp;
							<button class="btn btn-danger" type="button" ng-click="GotoList()">
								<i class="ace-icon fa fa-list bigger-110"></i>
								GO TO LIST
							</button>&nbsp;&nbsp;
						</div>
					</div>
                   
				</form>
       			 </div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.page-content -->
	</div>
</div><!-- /.main-content -->
			<!-- /.main-container ending in footer page -->
		<?php include_once("../footer.php");?>
		<!-- inline scripts related to this page -->
		
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
