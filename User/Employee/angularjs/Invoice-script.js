var AddCategory = angular.module("CategoryModule", ['ui.bootstrap','ngPatternRestrict', 'datatables'])
.filter('totalSumPriceQty', function() {
    return function(data, key1, key2) {
      if (angular.isUndefined(data) || angular.isUndefined(key1) || angular.isUndefined(key2))
        return 0;

      var sum = 0;
      angular.forEach(data, function(v, k) {
        sum = sum + (parseInt(v[key1]) * parseInt(v[key2]));
      });
      return sum || 0;
    }
});
AddCategory.directive('fileModel', ['$parse', function ($parse) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            var model = $parse(attrs.fileModel);
            var modelSetter = model.assign;

            element.bind('change', function(){
                scope.$apply(function(){
                    modelSetter(scope, element[0].files[0]);
                });
            });
        }
    };
}]);

AddCategory.service('fileUpload', ['$http', function ($http) {
    this.uploadFileToUrl = function(file, uploadUrl){
        var fd = new FormData();
        fd.append('file', file);
        $http.post(uploadUrl, fd, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
        })
        .success(function(data){
      
        })
        .error(function(){
        });
    }
}]);
AddCategory.controller('CategoryController', function($scope, $timeout, $http, jsonFilter, fileUpload, DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder) 
 {  

$scope.vm = {};
$scope.vm.dtInstance = {};   
$scope.vm.dtColumnDefs = [
//DTColumnDefBuilder.newColumnDef(2).notSortable()
];
$scope.vm.dtOptions = DTOptionsBuilder.newOptions()
          		.withOption('order', [2, 'desc'])
				.withOption('info', false);

	$scope.FormAdd = false;	
	$scope.FormList = true;		
	$scope.FormView = false;
	$scope.pagetitle = "Convert to Invoice";

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
	
	var logResult = function (data, status, headers, config)
	{
		return data;
	};
	
/*Add & Remove Script Starts Here*/
//to remove the row
$scope.removeRow = function (idx) 
{
	$scope.BatchArr.splice(idx, 1);
};

$scope.BatchArr = [{}];

    $scope.AddMore = function() 
    {
      $scope.BatchArr.push();
    }

/*Add & Remove Script end Here*/
//$scope.POArr = {};

// dropdown for phases 1-100
	$scope.GSTArray = [];
      var len=30;
      for(var i=0;i<31;i++)
      $scope.GSTArray.push(i);

$scope.GotoAdd = function()
{
	$scope.pagetitle = "Add Invoice";
	$scope.FormAdd = true;	
	$scope.FormList = false;
	$scope.FormView = false;
	$scope.GetGrnId();
	$scope.GetPaymentTerms();
	$scope.GetInventory();
	$scope.GetCustomers();
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

$scope.GetCustomers = function()
	{
        $http.get('load-customers.php')
        .then(function successCallback(response)
		{
			var data = response.data;
        	if(data=="NoData")
			{
				$scope.CustomerArray = "";
			}
			else
			{
				$scope.CustomerArray = data;
			}
			});
	}


$scope.GetGrnId = function()
	{
		$http.get("generate-invoice-id.php")
		.then(function successCallback(response)
		{
			var data = response.data;
			$scope.InvoiceId = data;
		});
	}

	$scope.GetDueDate = function(entrydate,paymentterms)
{
	$http.post("Get-Due-Date.php",{'entrydate': entrydate,'paymentterms': paymentterms})
    .then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{
				$scope.duedate = "";
			}
			else
			{
				$scope.duedate = new Date(data);	
			}
            
       }, function errorCallback(response) {
		// called asynchronously if an error occurs
	    // or server returns response with an error status.
	  });
}

	$scope.ChkQty = function(AvlQty,InvoicedQty,quantity,OrderQuantity,index)
	{
		// console.log(AvlQty);
		// console.log(InvoicedQty);
		// console.log(quantity);
		// console.log(OrderQuantity);
		remainqty = Number(OrderQuantity)-Number(InvoicedQty);
		if(Number(quantity)>Number(AvlQty))
		{
			$scope.BatchArr[index].AvlQtyErr = "Qty is more than the actual stock";	
		}
		else
		{
			$scope.BatchArr[index].AvlQtyErr = "";
		}

		if((Number(InvoicedQty=="0")) && Number(quantity)>Number(OrderQuantity))
		{
			$scope.BatchArr[index].OrderQtyErr = "Qty is more than the Order stock";	
		}
		else
		{
			$scope.BatchArr[index].OrderQtyErr = "";
		}
		if((Number(InvoicedQty!="0")) && Number(quantity)>Number(remainqty))
		{
			$scope.BatchArr[index].RemainQtyErr = "Qty is more than the Qty to be Invoice";	
		}
		else
		{
			$scope.BatchArr[index].RemainQtyErr = "";
		}
	}
$scope.GetOrder = function(OrderPkId)
{
	if(OrderPkId==undefined)
	{
		$scope.GetListData();
	}
	else
	{
		$scope.FormAdd = true;	
		$scope.FormList = false;
		$scope.FormView = false;
		$scope.GetGrnId();
		$scope.GetPaymentTerms();
		$scope.GetInventory();
		
			$http.post("Get-order-info.php",{'PkId': OrderPkId})
			.then(function successCallback(response)
			{
				var data = response.data;			
				if(data=="NoData")
				{
					// swal("Empty", "No Records Found", "error");
					// $timeout(function () { $scope.submitted = false;}, 2000);
					//$scope.pagedItems = "";
					//$scope.FormList = false;
				}
				else
				{
					$scope.FormPkId =data['PkId'];
					$scope.OrderId =data['OrderId'];
					$scope.OrderDate =data['OrderDate'];
					$scope.SubTotal =data['SubTotal'];
					$scope.additionalcharges =data['AdditionalCharges'];
					$scope.DiscountAmount =data['DiscountAmount'];
					$scope.OrderTotal =data['OrderTotal'];
					$scope.OrderStatus =data['OrderStatus'];
					$scope.InvoiceStatus =data['InvoiceStatus'];
					$scope.customerid =data['CustomerId_CustomerMaster'];
					$scope.customername =data['CustomerName'];
					$scope.CMobile =data['CMobile'];
					$scope.CEmailId =data['CEmailId'];
					$scope.paymentterms = data['PaymentTerms'];
					$scope.cnotes = data['CustomerNotes'];
					$scope.terms = data['TermsCondition'];
					$scope.disctype = data['DiscType'];
					$scope.discvalue = data['DiscountVal'];
					$scope.disctotal = data['DiscountAmount'];
					$scope.deliverymethod = data['DeliveryMethod'];
					$scope.salesperson = data['Salesperson'];

					$scope.BatchArr = data['data2'];
					$scope.Type = data['Type'];
					$scope.GetDueDate(data['OrderDate'],data['PaymentTerms']);
					//$scope.entrydate
					//$scope.FormList = true;
				}
			});
		
	}
}


$scope.ChkBatchQty= function(parent,InvQuantity,chooseqty,index)
{	
	if(chooseqty > InvQuantity)
	{	
		$scope.BatchArr[parent]["InvArr"][index]['chooseqty'] = $scope.BatchArr[parent]["InvArr"][index]['InvQuantity'];	
	}
}

$scope.getTotal=function(TrackingMode,index){
		var pickqty=0;
		if(TrackingMode=="Batch" || TrackingMode=="Serial")
		{
			for (var j = 0; j < $scope.BatchArr[index]['InvArr'].length; j++)
			{
				if($scope.BatchArr[index]["InvArr"][j]['chooseqty']==undefined)
				{
					$scope.BatchArr[index]["InvArr"][j]['chooseqty'] = 0;
				}

					pickqty += Number($scope.BatchArr[index]["InvArr"][j]['chooseqty']);		
				
			}
			$scope.BatchArr[index].pickquantity = pickqty;
		}
		else
		{
				$scope.BatchArr[index].pickquantity = Number($scope.BatchArr[index]["quantity"]);
		}
    }


$scope.GetListData= function()
	{
		$http.get("load-invoices.php")
		.then(function successCallback(response)
		{
			var data = response.data; 
			
			if(data=="NoData")
			{
				$scope.pagedItems = "";
			}
			else
			{
				$scope.pagedItems = data;
			}
		}, function errorCallback(response) {
	    		// called asynchronously if an error occurs
			    // or server returns response with an error status.
			  });
	}


 $scope.onCSelect = function ($item, $model, $label) {
    $scope.$item = $item;
    $scope.customerid = $item.CustomerId;
    $scope.paymentterms = $item.PaymentTerms;
    $scope.$model = $model;
    $scope.$label = $label;
};

$scope.onInvSelect = function ($item, $model, $label, index) {
    $scope.$item = $item;
    $scope.BatchArr[index].InvPkId = $item.InvPkId;
    $scope.BatchArr[index].product = $item.PName;
    $scope.BatchArr[index].price = $item.SalesPrice;
    $scope.BatchArr[index].AvlQty = $item.quantity;
    $scope.BatchArr[index].batchno = $item.batchno;
    $scope.BatchArr[index].SKU = $item.SKU;
    $scope.$model = $model;
    $scope.$label = $label;
};
$scope.gstperc = "0";
$scope.gstpercamt = "0.00";
$scope.disctype = "Rupee";
$scope.discvalue = "0.00";
$scope.additionalcharges = "0.00"; 

$scope.InventoryArray = [];
	$scope.GetInventory= function()
	{
		$http.get("Get-Inventory.php")
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


 $scope.getDiscAmt = function (sum,disctype,discvalue,additionalcharges,gstperc) 
 {
 	if(disctype=="Percent")
 	{
 		
 		$scope.gstpercamt = parseFloat(sum*(gstperc/100));
 		$scope.disctotal = -(sum+$scope.gstpercamt+parseFloat(additionalcharges))*(discvalue/100);
 		$scope.totalamount = (sum+$scope.gstpercamt+parseFloat(additionalcharges))+parseFloat($scope.disctotal);
 	}

 	if(disctype=="Rupee")
 	{
 		$scope.disctotal = -(discvalue);
 		$scope.gstpercamt = parseFloat(sum*(gstperc/100));
 		$scope.totalamount = (sum+$scope.gstpercamt+parseFloat(additionalcharges))-discvalue;
 	}
 	
}

$scope.calculateSum = function () {
		var sum = 0;
	if ($scope.BatchArr !== undefined) {

		for (var i = 0; i < $scope.BatchArr.length; i++)
		{
			//alert($scope.BatchArr[i]["quantity"]);
			sum += Number($scope.BatchArr[i]["quantity"])*$scope.BatchArr[i]["price"];
		}

		if($scope.disctype=="Percent")
	 	{
	 		//$scope.disctotal = -(sum*($scope.discvalue/100));
	 		$scope.gstpercamt = parseFloat(sum*($scope.gstperc/100));
	 		$scope.disctotal = -(sum+$scope.gstpercamt+parseFloat($scope.additionalcharges))*($scope.discvalue/100);

	 		$scope.totalamount = sum+$scope.gstpercamt+parseFloat($scope.additionalcharges)+parseFloat($scope.disctotal);
	 	}

	 	if($scope.disctype=="Rupee")
	 	{
	 		$scope.disctotal = -($scope.discvalue);
	 		$scope.gstpercamt = parseFloat(sum*($scope.gstperc/100));
	 		$scope.totalamount = sum+$scope.gstpercamt+parseFloat($scope.additionalcharges)-($scope.discvalue);

	 	}
	}

		$scope.sum=sum;
		return sum;
		//return totalqty;
	}


$scope.GotoList = function()
{
	window.location.href="invoices.php";
}

	$scope.GetCustomers = function()
	{
        $http.get('load-customers.php')
        .then(function successCallback(response)
		{
			var data = response.data;
        	if(data=="NoData")
			{
				$scope.CustomerArray = "";
			}
			else
			{
				$scope.CustomerArray = data;
			}
			});
	}

	
	
	$scope.AddCategoryData = function ()
	{
		$scope.submitted = true;
		//var config = { params: { AddCategory: AddCategory } };
		//$scope.AddCategory.supervisor: [];
		var fd = new FormData();
          var file = $scope.docfile;
         fd.append('file', file);	

        var ProductId=new Array();
		var product=new Array();
		var OrderQuantity = new Array();
		var InvoicedQty = new Array();
		var AvlQty = new Array();
		var quantity=new Array();
		var pickquantity = new Array();
		var price=new Array();
		var TrackingMode=new Array();
		var InvArr = new Array();


	for(i=0;i<$scope.BatchArr.length;i++)
	    {
		 ProductId.push($scope.BatchArr[i].ProductId);
		 product.push($scope.BatchArr[i].product);
		 OrderQuantity.push($scope.BatchArr[i].OrderQuantity);
		 InvoicedQty.push($scope.BatchArr[i].InvoicedQty);
		 AvlQty.push($scope.BatchArr[i].AvlQty);
		 quantity.push($scope.BatchArr[i].quantity);
		 pickquantity.push($scope.BatchArr[i].pickquantity);
		 price.push($scope.BatchArr[i].price);
		 TrackingMode.push($scope.BatchArr[i].TrackingMode);
		 InvArr.push($scope.BatchArr[i].InvArr);
		}
	
			$scope.FileErr="";
			$http.post('add-invoice-process.php', fd, {transformRequest: angular.identity,headers: {'Content-Type': undefined},
            params: {
            	'InvoiceId':$scope.InvoiceId,
				'OrderId':$scope.OrderId,
				'customerid':$scope.customerid,
				'customername':$scope.customername,
				
				'referencenum':$scope.referencenum,
				'entrydate':$scope.entrydate,
				'duedate':$scope.duedate,
				'paymentterms':$scope.paymentterms,
				'deliverymethod':$scope.deliverymethod,
				'salesperson':$scope.salesperson,

				'ProductId':JSON.stringify(ProductId),
				'product':JSON.stringify(product),
				'OrderQuantity':JSON.stringify(OrderQuantity),
				'InvoicedQty':JSON.stringify(InvoicedQty),
				'AvlQty':JSON.stringify(AvlQty),
				'quantity':JSON.stringify(quantity),
				'pickquantity':JSON.stringify(pickquantity),
				'price':JSON.stringify(price),
				'TrackingMode':JSON.stringify(TrackingMode),
				'InvArr':JSON.stringify(InvArr),
								
				'additionalcharges':$scope.additionalcharges,
				'sum':$scope.sum,

				'gstperc':$scope.gstperc,
				'gstpercamt':$scope.gstpercamt,

				'disctype':$scope.disctype,
				'discvalue':$scope.discvalue,
				'disctotal':$scope.disctotal,
				'totalamount':$scope.totalamount,
				'cnotes':$scope.cnotes,
				'terms':$scope.terms,
				'Type':$scope.Type,
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
							     text: "Invoice created successfully",
							     type: "success",
							     timer: 1000 
						},function () {window.location.href="invoices.php";})
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
	

	$scope.ViewInvoice = function(PkId,InvoiceId,InvoiceDate,DueDate,CustomerName,CEmailId,CMobile,SubTotal,GST,GSTAmount,AdditionalCharges,DiscountAmount,InvoiceTotal,data2){
		$scope.FormAdd = false;
		$scope.FormList = false;
		$scope.FormView = true;
		$scope.pagetitle = "View Invoice";
		$scope.FormPkId =PkId;
		$scope.InvoiceId =InvoiceId;
		$scope.InvoiceDate =InvoiceDate;
		$scope.DueDate =DueDate;
		$scope.CustomerName =CustomerName;
		$scope.CEmailId =CEmailId;
		$scope.CMobile =CMobile;
		$scope.SubTotal =SubTotal;
		$scope.AdditionalCharges =AdditionalCharges;
		$scope.GST =GST;
		$scope.GSTAmount =GSTAmount;
		$scope.DiscountAmount =DiscountAmount;
		$scope.InvoiceTotal =InvoiceTotal;
		$scope.data2 =data2;
	}

	$scope.Delete = function(PkId,InvoiceId,InvoiceDate)
	{
		swal({
	      title: '',
	      text: "Are you sure want to delete?",
	      type: "warning",
	      showCancelButton: true,
	      confirmButtonClass: "btn-danger",
	      confirmButtonText: "Yes, delete it!",
	      cancelButtonText: "No, cancel!",
	      closeOnConfirm: false,
	      closeOnCancel: true,
	        html: true
	    },
	    function(isConfirm) {
	      if (isConfirm) {

				$http.post('delete-invoice-process.php',
		            {
						'FormPkId':PkId,
						'InvoiceId':InvoiceId,
						'InvoiceDate':InvoiceDate,
					})
					.then(function successCallback(response)
					{
						var data = response.data;
						if(data=="Success")
						{
							$scope.submitted = false;
							$scope.FormValid = true;

							swal({title: "Added",
									     text: "Production deleted successfully",
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
					  } else {
	        swal("Cancelled", "Your record is safe :)", "error");
	      }
	    });
	}
	
var temp = '0.00';
  $(".decimaltemp").focus(function() {
    if (temp == this.value)
      // ------^-- empty the field only if the field value is initial  
      this.value = '';
      // emptying field value
  }).blur(function() {
    if (this.value.trim() == '')
      // -----^-- condition field value is empty
      this.value = temp;
      // if empty then updating with initial value
  }).val(temp);
  //----^-- setting initial value

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
AddCategory.config(['$qProvider', function ($qProvider) {
    $qProvider.errorOnUnhandledRejections(false);
}]);
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
    AddCategory.directive('validNumber', function () {
    return {
        require: '?ngModel',
        link: function (scope, element, attrs, ngModelCtrl) {

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