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

AddCategory.controller("CategoryController", function ($scope, $timeout, $http, jsonFilter)
{
	$scope.FormAdd = true;	
	$scope.FormList = false;	
	$scope.pagetitle = "List of Products";
	
	var logResult = function (data, status, headers, config)
	{
		return data;
	};



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
       minDate: new Date(2000, 1, 1),
       showWeeks:false,
        startingDay: 1
    };
	$scope.assDate1 = {
        //dateDisabled: disabled,
        formatYear: 'y',
      maxDate: new Date(2050, 5, 22),
       minDate: new Date(2000, 1, 1),
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

$scope.BatchArr = [{quantity:1,price:0}];

    $scope.AddMore = function() 
    {
      $scope.BatchArr.push({quantity:1,price:0});
    }

/*Add & Remove Script end Here*/

$scope.GotoAdd = function()
{
	$scope.pagetitle = "Add Product";
	$scope.FormAdd = true;	
	$scope.FormList = false;	
	$scope.GetOrderId();
	$scope.GetCustomers();
	$scope.GetPaymentTerms();
	$scope.GetInventory();

}
	$scope.GetListData= function(PkId)
	{
		$http.post("load-open-order-info.php",{'PkId': PkId})
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
				$scope.PkId =data['PkId'];
				$scope.OrderId =data['OrderId'];
				$scope.OrderDate =data['OrderDate'];
				$scope.SubTotal =data['SubTotal'];
				$scope.AdditionalCharges =data['AdditionalCharges'];
				$scope.DiscountAmount =data['DiscountAmount'];
				$scope.OrderTotal =data['OrderTotal'];
				$scope.OrderStatus =data['OrderStatus'];
				$scope.PackageStatus =data['PackageStatus'];
				$scope.InvoiceStatus =data['InvoiceStatus'];
				$scope.ShipmentStatus = data['ShipmentStatus'];
				$scope.CustomerName =data['CustomerName'];
				$scope.CMobile =data['CMobile'];
				$scope.CEmailId =data['CEmailId'];
				$scope.CreatedTime =data['CreatedTime'];
				$scope.pagedItems = data['data2'];
				//$scope.FormList = true;
			}
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
    $scope.$model = $model;
    $scope.$label = $label;
};
$scope.disctype = "Rupee";
$scope.discvalue = "0.00";


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
// $scope.getDiscAmt = function (sum,discount,index) 
//  {
//  	if(($scope.POArr[index].discount !== undefined || $scope.POArr[index].discount=="") && ($scope.POArr[index].price!==undefined || $scope.POArr[index].price=="") )
//  	{
//  	 $scope.POArr[index].disamount = parseFloat(($scope.POArr[index].price*$scope.POArr[index].discount/100));
//  	}

// }
 
 $scope.getDiscAmt = function (sum,disctype,discvalue) 
 {
 	if(disctype=="Percent")
 	{
 		$scope.disctotal = -(sum*(discvalue/100));
 		$scope.totalamount = sum-(sum*(discvalue/100));
 	}

 	if(disctype=="Rupee")
 	{
 		$scope.disctotal = -(discvalue);
 		$scope.totalamount = sum-discvalue;
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
	 		$scope.totalamount = sum-(sum*($scope.discvalue/100));
	 	}

	 	if($scope.disctype=="Rupee")
	 	{
	 		$scope.disctotal = -($scope.discvalue);
	 		$scope.totalamount = sum-$scope.discvalue;
	 	}
	}

		$scope.sum=sum;
		return sum;


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

        var InvPkId=new Array();
		var product=new Array();
		var quantity=new Array();
		var price=new Array();


	for(i=0;i<$scope.BatchArr.length;i++)
	    {
		 InvPkId.push($scope.BatchArr[i].InvPkId);
		 product.push($scope.BatchArr[i].product);
		 quantity.push($scope.BatchArr[i].quantity);
		 price.push($scope.BatchArr[i].price);
		}
	
			$scope.FileErr="";
			$http.post('add-sales-order-process.php', fd, {transformRequest: angular.identity,headers: {'Content-Type': undefined},
            params: {
				'OrderId':$scope.OrderId,
				'customerid':$scope.customerid,
				'customername':$scope.customername,
				
				'referencenum':$scope.referencenum,
				'entrydate':$scope.entrydate,
				'shipdate':$scope.shipdate,
				'paymentterms':$scope.paymentterms,
				'deliverymethod':$scope.deliverymethod,
				'salesperson':$scope.salesperson,

				'InvPkId':JSON.stringify(InvPkId),
				'product':JSON.stringify(product),
				'quantity':JSON.stringify(quantity),
				'price':JSON.stringify(price),
								
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
							     text: "Order created successfully",
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
	
	$scope.GotoList = function()
	{		
		window.location.href = "orders.php";
	}
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
