var AddCategory = angular.module("CategoryModule", ['ui.bootstrap','datatables'])
.filter('sumByColumn', function () {
      return function (collection, column) {
        var total = 0;

        collection.forEach(function (item) {
          total += parseInt(item[column]);
        });

        return total || 0;
      };
    })
AddCategory.directive('fileModel', ['$parse', function ($parse) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            var model = $parse(attrs.fileModel);
            var modelSetter = model.assign;

            element.bind('change', function(){
                scope.$apply(function(){
                	//console.log(element[0].files[0]);
                    modelSetter(scope, element[0].files[0]);
                });
            });
        }
    };
}]);

AddCategory.controller("CategoryController", function ($scope, $timeout, $http, jsonFilter, DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder)
{
	$scope.FormAdd = false;	
	$scope.FormList = true;	
	$scope.pagetitle = "List of Spare Purchases";
	
	var logResult = function (data, status, headers, config)
	{
		return data;
	};

$scope.vm = {};
$scope.vm.dtInstance = {};   
$scope.vm.dtColumnDefs = [
//DTColumnDefBuilder.newColumnDef(2).notSortable()
];
$scope.vm.dtOptions = DTOptionsBuilder.newOptions()
          		.withOption('order', [1, 'desc'])
				.withOption('info', false);

	 $scope.entrydate=new Date();
	$scope.opened = {};
    $scope.opened.opened1 = false;
    $scope.opened.opened2 = false;
    
    $scope.singleopen = function($event,datepicker) {
      $event.preventDefault();
      $event.stopPropagation();
      $scope.opened[datepicker] = true;
    };
	$scope.assDate = {
        //dateDisabled: disabled,
        formatYear: 'y',
        maxDate: new Date(2050, 5, 22),
       minDate: new Date(),
       showWeeks:false,
        startingDay: 1
    };
	$scope.assDate1 = {
        //dateDisabled: disabled,
        formatYear: 'y',
      maxDate: new Date(2050, 5, 22),
       minDate: new Date(),
       showWeeks:false,
        startingDay: 1
    };


   $(document).ready(function() {
    $("#entrydate").keypress(function (event) { event.preventDefault(); });
    $("#entrydate").keydown(function (event) { event.preventDefault(); });


    $("#shipdate").keypress(function (event) { event.preventDefault(); });
    $("#shipdate").keydown(function (event) { event.preventDefault(); });
  });


/*Add & Remove Script Starts Here*/
//to remove the row
$scope.removeRow = function (idx) 
{
	$scope.BatchArr.splice(idx, 1);
};

$scope.BatchArr = [{price:0}];

    $scope.AddMore = function() 
    {
      $scope.BatchArr.push({price:0});
    }

/*Add & Remove Script end Here*/

$scope.GotoAdd = function()
{
	$scope.pagetitle = "Add Spare Purchase";
	$scope.FormAdd = true;	
	$scope.FormList = false;	
	$scope.GetOrderId();
	$scope.GetVendors();
	$scope.GetPaymentTerms();
	$scope.GetInventory();

}
$scope.DeliveryArray = ['Transport','Courier','Auto','Rikshaw'];
	$scope.GetListData= function()
	{
		$http.get("load-purchase-orders.php")
		.then(function successCallback(response)
		{
			var data = response.data;			
			if(data=="NoData")
			{
				// swal("Empty", "No Records Found", "error");
				// $timeout(function () { $scope.submitted = false;}, 2000);
				$scope.pagedItems = "";
				//$scope.FormList = false;
			}
			else
			{
				$scope.query = {}
				$scope.queryBy = '$';
				$scope.pagedItems = data;
				//$scope.FormList = true;
			}
		});
	}


$scope.GetOrderId= function()
	{
		$http.get("generate-purchase-id.php")
		.then(function successCallback(response)
		{
			var data = response.data;
			$scope.POrderId = data;
		});
	}

	$scope.GetVendors = function()
	{
        $http.get('load-vendors.php')
        .then(function successCallback(response)
		{
			var data = response.data;
        	if(data=="NoData")
			{
				$scope.VendorArray = "";
			}
			else
			{
				$scope.VendorArray = data;
			}
			});
	}

	$scope.PaymentTermArr = [];
$scope.GetPaymentTerms = function()
{
    $http.get("load-payment-terms.php")
    .then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{
				$scope.PaymentTermArr = "";
			}
			else
			{
				$scope.PaymentTermArr = data;	
			}
            
       }, function errorCallback(response) {
		// called asynchronously if an error occurs
	    // or server returns response with an error status.
	  });
}

 $scope.onVendorSelect = function ($item, $model, $label) {
    $scope.$item = $item;
    $scope.vendorid = $item.VendorId;
    $scope.paymentterms = $item.PaymentTerms;
    $scope.$model = $model;
    $scope.$label = $label;
};

$scope.onInvSelect = function ($item, $model, $label, index) {
    $scope.$item = $item;
    $scope.BatchArr[index].ProductId = $item.ProductId;
    $scope.BatchArr[index].product = $item.PName;
    $scope.BatchArr[index].price = $item.SalesPrice;
    $scope.BatchArr[index].AvlQty = $item.quantity;
    $scope.BatchArr[index].SKU = $item.SKU;
    $scope.$model = $model;
    $scope.$label = $label;
};
$scope.disctype = "Rupee";
$scope.discvalue = "0.00";
$scope.additionalcharges = "0.00"; 

$scope.InventoryArray = [];
	$scope.GetInventory= function()
	{
		$http.get("get-spares.php")
		.then(function successCallback(response)
		{
			var data = response.data;  
			if(data=="NoData")
			{
				$scope.InventoryArray = "";
			}
			else
			{
				$scope.InventoryArray = data;
			}
		});
	}
 
 $scope.getDiscAmt = function (sum,disctype,discvalue,additionalcharges) 
 {
 	if(disctype=="Percent")
 	{
 		$scope.disctotal = -(sum*(discvalue/100));
 		$scope.totalamount = sum+parseFloat(additionalcharges)-(sum*(discvalue/100));
 	}

 	if(disctype=="Rupee")
 	{
 		$scope.disctotal = -(discvalue);
 		$scope.totalamount = sum+parseFloat(additionalcharges)-(discvalue);
 	}
 	
}

$scope.calculateSum = function () {
		var sum = 0;
	if ($scope.BatchArr !== undefined) {

		for (var i = 0; i < $scope.BatchArr.length; i++)
		{
			sum += Number($scope.BatchArr[i]["quantity"])*$scope.BatchArr[i]["price"];
		}

		if($scope.disctype=="Percent")
	 	{
	 		$scope.disctotal = -(sum*($scope.discvalue/100));
	 		$scope.totalamount = sum+parseFloat($scope.additionalcharges)-(sum*($scope.discvalue/100));
	 	}

	 	if($scope.disctype=="Rupee")
	 	{
	 		$scope.disctotal = -($scope.discvalue);
	 		$scope.totalamount = sum+parseFloat($scope.additionalcharges)-$scope.discvalue;
	 	}
	}

		$scope.sum=sum;
		return sum;
	}

	$scope.EditOrder = function(PkId,VendorId_VendorMaster,VendorName,POrderId,POrderDate,Reference,DeliveryDate,PaymentTerms,ShipmentReference,CustomerNotes,TermsCondition,SubTotal,DiscType,DiscountVal,DiscountAmount,AdditionalCharges,OrderTotal,POStatus,FileName,data2)
	{
		$scope.FormList = false;
		$scope.FormAdd = true;
		$scope.pagetitle = "Edit Purchase Order";
		$scope.GetPaymentTerms();
		$scope.GetVendors();
		$scope.GetInventory();
		$scope.FormPkId = PkId; 
		$scope.vendorid = VendorId_VendorMaster;
		$scope.vendorname = VendorName;

		$scope.POrderId = POrderId; 
		$scope.entrydate= new Date(POrderDate);
		$scope.referencenum = Reference;
		$scope.shipdate= new Date(DeliveryDate);	
		$scope.paymentterms=PaymentTerms;
		$scope.deliverymethod=ShipmentReference;
		$scope.cnotes=CustomerNotes;
		$scope.terms=TermsCondition;
		// ProductId':JSON.stringify(ProductId),
		// product':JSON.stringify(product),
		// quantity':JSON.stringify(quantity),
		// price':JSON.stringify(price),
		$scope.sum=SubTotal;
		$scope.disctype=DiscType;
		$scope.discvalue=DiscountVal;
		$scope.disctotal=DiscountAmount;
		$scope.totalamount=OrderTotal;

		$scope.additionalcharges = AdditionalCharges;
		$scope.status=POStatus;
		$scope.FileName = FileName;
		$scope.BatchArr = data2;
	}


// 	$scope.AddBatch = function()
// 	{
// 		if($scope.batchno!==undefined && $scope.quantity!==undefined)
// 		{	
// 			$scope.BatchArr.push(
// 				{
// 					BatchNo:$scope.batchno,
// 					ManfcBatch:$scope.manfacturebatch,
// 					Mfgdate:new Date($scope.mfgdate),
// 					Expdate:new Date($scope.expdate),
// 					Quantity:$scope.quantity,
// 				}
// 			);

// 			$('#modal-form').modal('hide');	
// 			$scope.batchno = "";
// 			$scope.manfacturebatch = "";
// 			$scope.mfgdate = "";
// 			$scope.expdate = "";
// 			$scope.quantity = "";
// 		}
// 	}

// $scope.EditArr = function(BatchNo,ManfcBatch,Mfgdate,Expdate,Quantity,index)
// 	{
			
// 			$scope.batchno = BatchNo;
// 			$scope.manfacturebatch = ManfcBatch;
// 			$scope.mfgdate = new Date(Mfgdate);
// 			$scope.expdate = new Date(Expdate);
// 			$scope.quantity = Quantity;

		
// 	}
	
// 	$scope.UpdateArr = function(BatchNo,ManfcBatch,Mfgdate,Expdate,Quantity,index)
// 	{
			
// 			$scope.batchno = BatchNo;
// 			$scope.manfacturebatch = ManfcBatch;
// 			$scope.mfgdate = new Date(Mfgdate);
// 			$scope.expdate = new Date(Expdate);
// 			$scope.quantity = Quantity;
		
// 	}
	
	$scope.AddCategoryData = function ()
	{
		$scope.submitted = true;
		//var config = { params: { AddCategory: AddCategory } };
		//$scope.AddCategory.supervisor: [];
		var fd = new FormData();
          var file = $scope.docfile;
         fd.append('file', file);	

        var EntryPkId=new Array();
        var ProductId=new Array();
		var product=new Array();
		var quantity=new Array();
		var price=new Array();


	for(i=0;i<$scope.BatchArr.length;i++)
	    {
	    EntryPkId.push($scope.BatchArr[i].EntryPkId);	
		 ProductId.push($scope.BatchArr[i].ProductId);
		 product.push($scope.BatchArr[i].product);
		 quantity.push($scope.BatchArr[i].quantity);
		 price.push($scope.BatchArr[i].price);
		}

		if($scope.FormPkId==undefined)
		{
			$scope.FileErr="";
			$http.post('add-spare-PO-process.php', fd, {transformRequest: angular.identity,headers: {'Content-Type': undefined},
            params: {
				'POrderId':$scope.POrderId,
				'vendorid':$scope.vendorid,
				'vendorname':$scope.vendorname,
				
				'referencenum':$scope.referencenum,
				'entrydate':$scope.entrydate,
				'shipdate':$scope.shipdate,
				'paymentterms':$scope.paymentterms,
				'deliverymethod':$scope.deliverymethod,
				'salesperson':$scope.salesperson,

				'ProductId':JSON.stringify(ProductId),
				'product':JSON.stringify(product),
				'quantity':JSON.stringify(quantity),
				'price':JSON.stringify(price),
				'additionalcharges':$scope.additionalcharges,				
				'sum':$scope.sum,
				'disctype':$scope.disctype,
				'discvalue':$scope.discvalue,
				'disctotal':$scope.disctotal,
				'totalamount':$scope.totalamount,
				'cnotes':$scope.cnotes,
				'terms':$scope.terms,
				'status':$scope.status,
			},

			})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.submitted = false;
					$scope.FormValid = true;

					swal({title: "Added",
							     text: "Purchase Order created successfully",
							     type: "success",
							     timer: 2000 
						},function () {window.location.reload();})
				}
				else
				{
					swal("STOP", data, "error");
					$timeout(function () { $scope.submitted = false;}, 3000);
				}
			}, function errorCallback(response) {
	    		// called asynchronously if an error occurs
			    // or server returns response with an error status.
			  });
		}
		else
		{
			$http.post('update-PO-process.php', fd, {transformRequest: angular.identity,headers: {'Content-Type': undefined},
            params: {
				'FormPkId':$scope.FormPkId,
				'POrderId':$scope.POrderId,
				'vendorid':$scope.vendorid,
				'vendorname':$scope.vendorname,
				
				'referencenum':$scope.referencenum,
				'entrydate':$scope.entrydate,
				'shipdate':$scope.shipdate,
				'paymentterms':$scope.paymentterms,
				'deliverymethod':$scope.deliverymethod,
				'salesperson':$scope.salesperson,
				'FileName':$scope.FileName,

				'EntryPkId':JSON.stringify(EntryPkId),
				'ProductId':JSON.stringify(ProductId),
				'product':JSON.stringify(product),
				'quantity':JSON.stringify(quantity),
				'price':JSON.stringify(price),
				'additionalcharges':$scope.additionalcharges,				
				'sum':$scope.sum,
				'disctype':$scope.disctype,
				'discvalue':$scope.discvalue,
				'disctotal':$scope.disctotal,
				'totalamount':$scope.totalamount,
				'cnotes':$scope.cnotes,
				'terms':$scope.terms,
				'status':$scope.status,
			},

			})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.submitted = false;
					$scope.FormValid = true;

					swal({title: "Updated",
							     text: "Order updated successfully",
							     type: "success",
							     timer: 1000 
						},function () {window.location.reload();})
				}
				else
				{
					swal("STOP", data, "error");
					$timeout(function () { $scope.submitted = false;}, 3000);
				}
			}, function errorCallback(response) {
	    		// called asynchronously if an error occurs
			    // or server returns response with an error status.
			  });
		}
	}
	
	$scope.GotoList = function()
	{		
		window.location.href = "list-of-pos.php";
	}


	/* customer start*/
	$scope.AddCustomer  = function()
	{
		$scope.GetCustomerId();
		$scope.GetSalutes();
		$scope.GetPaymentTerms();
		$scope.GetStates();
	}
$scope.GetCustomerId = function(){
	$http.get("generate-customer-id.php")
		.then(function successCallback(response)
		{
			var data = response.data;			
			if(data=="")
			{
				//swal("Empty", "No Records Found", "error");$timeout(function () { $scope.submitted = false;}, 2000);
				$scope.CustomerId = "";
			}
			else
			{
				$scope.CustomerId = data;
			}
		});
}

$scope.StateArray = [];
$scope.GetStates = function()
{
    $http.get("Get-States.php")
    .then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{
				$scope.StateArray = "";
			}
			else
			{
				$scope.StateArray = data;	
			}
            
       }, function errorCallback(response) {
		// called asynchronously if an error occurs
	    // or server returns response with an error status.
	  });
}
$scope.onCSelect = function ($item, $model, $label) {
    $scope.$item = $item;
    $scope.$model = $model;
    $scope.$label = $label;
    $scope.GetDist($item);
};

$scope.DistrictArray = [];
$scope.GetDist = function(state)
{
    $http.post("Get-Districts.php", {'state': state})
    .then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{

            $scope.DistrictArray = "";
			}
			else
			{

            $scope.DistrictArray = data;	
			}
       }, function errorCallback(response) {
		// called asynchronously if an error occurs
	    // or server returns response with an error status.
	  });
}
$scope.SalutationArr = [];
$scope.GetSalutes = function()
{
    $http.get("load-salutations.php")
    .then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{
				$scope.SalutationArr = "";
			}
			else
			{
				$scope.SalutationArr = data;	
			}
            
       }, function errorCallback(response) {
		// called asynchronously if an error occurs
	    // or server returns response with an error status.
	  });
}

$scope.checkMobile = function(FormPkId,mobileno)
	{
		 $http.post("check-customer-mobile.php",{'FormPkId': FormPkId, 'mobileno': mobileno})
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data!="OK")
			{
				$scope.MobileExists = true;
			}
			else
			{
				$scope.MobileExists = false;
			}
		}, function errorCallback(response) {
	    		// called asynchronously if an error occurs
			    // or server returns response with an error status.
			  });
	};

	$scope.checkEmail = function(FormPkId,emailid)
	{
		 $http.post("check-customer-email.php",{'FormPkId': FormPkId,'emailid': emailid})
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data!="OK")
			{
				$scope.EmailExists = true;
			}
			else
			{
				$scope.EmailExists = false;
			}
		}, function errorCallback(response) {
	    		// called asynchronously if an error occurs
			    // or server returns response with an error status.
			  });
	};


$scope.ChkdBIll = function(sameasbill)
	{
		if ($scope.sameasbill) {
		$scope.shippingname=$scope.billingname;
		$scope.shipmobileno=$scope.billmobileno;
		$scope.shipaddress1=$scope.address1;
		$scope.shipaddress2=$scope.address2;
		$scope.shiptown=$scope.town;
		$scope.shiplandmark=$scope.landmark;
		$scope.shipcity=$scope.city;
		$scope.shipstate=$scope.state;
		$scope.shipdistrict=$scope.district;
		$scope.shippincode=$scope.pincode;

	}else{
		$scope.shippingname="";
		$scope.shipmobileno="";
		$scope.shipaddress1="";
		$scope.shipaddress2="";
		$scope.shiptown="";
		$scope.shiplandmark="";
		$scope.shipcity="";
		$scope.shipstate="";
		$scope.shipdistrict="";
		$scope.shippincode="";

		}
	}
	$scope.AddCustomerData = function ()
	{
		$scope.submitted = true;
		if($scope.FormPkId==undefined && $scope.MobileExists==false && $scope.EmailExists==false)
			{
			$http.post("add-customer-process.php",{
			'CustomerId':$scope.CustomerId,
			'salutation':$scope.salutation,
			'ctype':$scope.ctype,
			'customername':$scope.customername,
			'shopname':$scope.shopname,
			'emailid':$scope.emailid,
			'mobileno':$scope.mobileno,
			'ofcmobileno':$scope.ofcmobileno,
			//'gstin':$scope.gstin,
			//'pan':$scope.pan,
			'billingname':$scope.billingname,
			'billmobileno':$scope.billmobileno,
			'address1':$scope.address1,
			'address2':$scope.address2,
			'town':$scope.town,
			'landmark':$scope.landmark,
			'city':$scope.city,
			'state':$scope.state,
			'district':$scope.district,
			'pincode':$scope.pincode,
			'lattitude':$scope.lattitude,
			'longitude':$scope.longitude,

			'shippingname':$scope.shippingname,
			'shipmobileno':$scope.shipmobileno,
			'shipaddress1':$scope.shipaddress1,
			'shipaddress2':$scope.shipaddress2,
			'shiptown':$scope.shiptown,
			'shiplandmark':$scope.shiplandmark,
			'shipcity':$scope.shipcity,
			'shipstate':$scope.shipstate,
			'shipdistrict':$scope.shipdistrict,
			'shippincode':$scope.shippincode,
			'paymentterms':$scope.paymentterms,
			//'bankname':$scope.bankname,
			//'branchname':$scope.branchname,
			//'accountname':$scope.accountname,
			//'accounttype':$scope.accounttype,
			//'acnumber':$scope.acnumber,
			//'ifsc':$scope.ifsc,
			//'tand5':$scope.tandc
			})
			.then(function successCallback(response)
				{
					var data = response.data;
				if(data=="Success")
				{
					window.scrollTo(500, 0);
					$scope.submitted = false;
					$scope.FormValid = true;
					$scope.customername="";
					$scope.GotoAdd();
					$('#modal-form').modal('hide');
					 swal({title: "success!",
					     text: "Customer Created successfully.",
					     type: "success",
					     timer: 2000});
				}
				else
				{
					swal("STOP", data, "error");
					$timeout(function () { $scope.submitted = false;}, 3000);
				}
			}, function errorCallback(response) {
		    		// called asynchronously if an error occurs
				    // or server returns response with an error status.
				  });
		}
			
	};
	/* customer end*/
});
AddCategory.directive('number', function () {
        return {
            require: 'ngModel',
            restrict: 'A',
            link: function (scope, element, attrs, ctrl) {
                ctrl.$parsers.push(function (input) {
                    if (input == undefined) return ''
                    var inputNumber = input.toString().replace(/[^0-9]/g, '');
                    if (inputNumber != input) {
                        ctrl.$setViewValue(inputNumber);
                        ctrl.$render();
                    }
                    return inputNumber;
                });
            }
        };
    });

AddCategory.directive('convertToNumber', function() {
  return {
    require: 'ngModel',
    link: function(scope, element, attrs, ngModel) {
      ngModel.$parsers.push(function(val) {
        return val != null ? parseInt(val, 10) : null;
      });
      ngModel.$formatters.push(function(val) {
        return val != null ? '' + val : null;
      });
    }
  };
});
 AddCategory.directive('restrictField', function () {
    return {
        restrict: 'AE',
        scope: {
            restrictField: '='
        },
        link: function (scope) {
          // this will match spaces, tabs, line feeds etc
          // you can change this regex as you want
          var regex = /\s/g;

          scope.$watch('restrictField', function (newValue, oldValue) {
              if (newValue != oldValue && regex.test(newValue)) {
                scope.restrictField = newValue.replace(regex, '');
              }
          });
        }
    };
  });

AddCategory.directive('validNumber', function () {
    return {
        require: '?ngModel',
        link: function (scope, element, attrs, ngModelCtrl) {
var temp = '0.00';
		 element.on('focus', function (event) {
		//$(".decimaltemp").focus(function() {
		    if (temp == this.value)
		      // ------^-- empty the field only if the field value is initial  
		      this.value = '';
		      // emptying field value
		  }).on('blur', function () {
		    if (this.value.trim() == '')
		      // -----^-- condition field value is empty
		      this.value = temp;
		      // if empty then updating with initial value
		  }).val(temp);
		  //----^-- setting initial value

        	 var temp1 = '0.000';
		 element.on('focus', function (event) {
		//$(".decimaltemp").focus(function() {
		    if (temp1 == this.value)
		      // ------^-- empty the field only if the field value is initial  
		      this.value = '';
		      // emptying field value
		  }).on('blur', function () {
		    if (this.value.trim() == '')
		      // -----^-- condition field value is empty
		      this.value = temp1;
		      // if empty then updating with initial value
		  }).val(temp1);
		  //----^-- setting initial value

            element.on('keydown', function (event) {
              var keyCode=[]
              if(attrs.allowNegative == "true")
              { keyCode = [8, 9, 36, 35, 37, 39, 46, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 109, 110, 173, 190,189];
              }
              else{
               var keyCode = [8, 9, 36, 35, 37, 39, 46, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 110, 173, 190];
              }
              
              
              if(attrs.allowDecimal == "false") {
                
                var index = keyCode.indexOf(190);


if (index > -1) {
    keyCode.splice(index, 1);
}
                
              }
                        
              if ($.inArray(event.which, keyCode) == -1) event.preventDefault();
                else {console.log(2);
                    var oVal = ngModelCtrl.$modelValue || '';
                    if ($.inArray(event.which, [109, 173]) > -1 && oVal.indexOf('-') > -1) event.preventDefault();
                    else if ($.inArray(event.which, [110, 190]) > -1 && oVal.indexOf('.') > -1) event.preventDefault();
                }
            })
            .on('blur', function () {

                if (element.val() == '' || parseFloat(element.val()) == 0.0 || element.val() == '-') {
                    ngModelCtrl.$setViewValue('0.00');
                }
                else if(attrs.allowDecimal == "false")
               { 
                 ngModelCtrl.$setViewValue(element.val());
               }
               else{   
                 if(attrs.decimalUpto)
                 {
                 var fixedValue = parseFloat(element.val()).toFixed(attrs.decimalUpto);}
                 else{   var fixedValue = parseFloat(element.val()).toFixed(2);}
                 ngModelCtrl.$setViewValue(fixedValue);
               }
                    
                

                ngModelCtrl.$render();
                scope.$apply();
            });

            ngModelCtrl.$parsers.push(function (text) {
                var oVal = ngModelCtrl.$modelValue;
                var nVal = ngModelCtrl.$viewValue;
console.log(nVal);
                if (parseFloat(nVal) != nVal) {

                    if (nVal === null || nVal === undefined || nVal == '' || nVal == '-') oVal = nVal;

                    ngModelCtrl.$setViewValue(oVal);
                    ngModelCtrl.$render();
                    return oVal;
                }
                else {
                    var decimalCheck = nVal.split('.');
                    if (!angular.isUndefined(decimalCheck[1])) {
                      if(attrs.decimalUpto)
                         decimalCheck[1] = decimalCheck[1].slice(0, attrs.decimalUpto);
                         else
                        decimalCheck[1] = decimalCheck[1].slice(0, 2);
                        nVal = decimalCheck[0] + '.' + decimalCheck[1];
                    }

                    ngModelCtrl.$setViewValue(nVal);
                    ngModelCtrl.$render();
                    return nVal;
                }
            });

            ngModelCtrl.$formatters.push(function (text) {
                if (text == '0' || text == null && attrs.allowDecimal == "false") return '0';
                else if (text == '0' || text == null && attrs.allowDecimal != "false" && attrs.decimalUpto == undefined) return '0.00';
                else if (text == '0' || text == null && attrs.allowDecimal != "false" && attrs.decimalUpto != undefined) return parseFloat(0).toFixed(attrs.decimalUpto);
                else if (attrs.allowDecimal != "false" && attrs.decimalUpto != undefined) return parseFloat(text).toFixed(attrs.decimalUpto);
                else return parseFloat(text).toFixed(2);
            });
        }
    };
});
