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
              .withOption('order', [0, 'desc'])
        .withOption('info', false);

 	$scope.MainList = true;
	$scope.FormAdd = false;	
	$scope.FormList = false;		
	$scope.pagetitle = "List of payment received";

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
// $scope.removeRow = function (idx) 
// {
// 	$scope.BatchArr.splice(idx, 1);
// };

// $scope.BatchArr = [{quantity:1,price:0}];

//     $scope.AddMore = function() 
//     {
//       $scope.BatchArr.push({quantity:1,price:0});
//     }

/*Add & Remove Script end Here*/
//$scope.POArr = {};

$scope.GotoAdd = function()
{
	$scope.pagetitle = "Add Payment";
	$scope.MainList = false;
	$scope.FormAdd = false;	
	$scope.FormList = true;
	$scope.GetCustomers();
}

$scope.onCSelect = function ($item, $model, $label) {
    $scope.$item = $item;
    $scope.customerid = $item.CustomerId;
    $scope.paymentterms = $item.PaymentTerms;
    $scope.$model = $model;
    $scope.$label = $label;
    $scope.GetInvoices($item.CustomerId);
};


$scope.pagedItems=[];
$scope.GetInvoices = function(customerid)
{
	 $http.post("Get-customer-unpaid-invoices.php",{'customerid': customerid})
    .then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{
				$scope.InvData = true;
				$scope.pagedItems = "";
			}
			else
			{
				
				$scope.InvData = false;
				$scope.pagedItems = data;	
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


$scope.GetOrderInfo = function(OrderPkId)
{
	$http.post("Get-order-pkgs.php",{'PkId': OrderPkId})
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
				$scope.GetOrders(data['CustomerId_CustomerMaster']);
				$scope.FormPkId =data['PkId'];
				$scope.OrderPkId =data['PkId'];


				

				$scope.OrderId =data['OrderId'];
				$scope.PkgDate =data['PkgDate'];
				$scope.SubTotal =data['SubTotal'];
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
				//$scope.FormList = true;
			}
		});
}
$scope.GetListData= function()
	{
		$http.get("load-payment-received.php")
		.then(function successCallback(response)
		{
			var data = response.data; 
			
			if(data=="NoData")
			{
				$scope.PaymentItems = "";
			}
			else
			{
				$scope.PaymentItems = data;
			}
		}, function errorCallback(response) {
	    		// called asynchronously if an error occurs
			    // or server returns response with an error status.
			  });
	}

$scope.EditSupplier = function(InvoiceId,CustomerId_CustomerMaster,CustomerName,CCompanyName,CMobile,InvoiceTotal,ReceivedAmount,PendingAmount)
	{


		$scope.pagetitle = "Add Payment";
		$scope.MainList = false;
		$scope.FormAdd = true;
		$scope.FormList = false;		

		$scope.InvoiceId = InvoiceId;
		$scope.CustomerId = CustomerId_CustomerMaster;
		$scope.customername = CustomerName;
		$scope.ShopName = CCompanyName;
		$scope.Mobile = CMobile;
		$scope.BillAmount = InvoiceTotal;
		$scope.tillreceive = ReceivedAmount;
		$scope.remainpay = PendingAmount;
	}


	$scope.Chkamount = function(remainpay,amount)
	{
		if(amount>remainpay)
		{
			$scope.amount = remainpay;
		}
	}
 




$scope.calculateSum = function () {
		var sum = 0;
	if ($scope.BatchArr !== undefined) {

		for (var i = 0; i < $scope.BatchArr.length; i++)
		{
			sum += Number($scope.BatchArr[i]["qtytopack"]);
		}

		// if($scope.disctype=="Percent")
	 // 	{
	 // 		$scope.disctotal = -(sum*($scope.discvalue/100));
	 // 		$scope.totalamount = sum-(sum*($scope.discvalue/100));
	 // 	}

	 // 	if($scope.disctype=="Rupee")
	 // 	{
	 // 		$scope.disctotal = -($scope.discvalue);
	 // 		$scope.totalamount = sum-$scope.discvalue;
	 // 	}
	}

		$scope.sum=sum;
		return sum;


	}


$scope.GotoList = function()
{
	window.location.href="payment-received.php";
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
$scope.ChangeQty = function(quantity,qtytopack,index)
{
	if(Number(qtytopack)>Number(quantity))
	{
		$scope.BatchArr[index].qtytopack = quantity;
	}
}
	
	
	$scope.AddCustomerData = function ()
	{
		$scope.submitted = true;

		var fd = new FormData();
          var file = $scope.docfile;
          fd.append('file', file)

		if($scope.FormPkId==undefined)
		{
			//$http.post("add-Order-process.php",{
			$http.post('payment-receive-process.php', fd, {transformRequest: angular.identity,headers: {'Content-Type': undefined},
            params: {
			'entrydate':$scope.entrydate,
			'CustomerId':$scope.CustomerId,
			'customername':$scope.customername,
			'InvoiceId':$scope.InvoiceId,
			'BillAmount':$scope.BillAmount,
			'tillreceive':$scope.tillreceive,
			'remainpay':$scope.remainpay,
			'paymentmode':$scope.paymentmode,
			'amount':$scope.amount,
			'remainamt':$scope.remainamt,
			'paymenttype':$scope.paymenttype,
			'walletname':$scope.walletname,
			'chequedate':$scope.chequedate,
			'chequeno':$scope.chequeno,
			'referenceid':$scope.referenceid,
			'comments':$scope.comments,
		},
			})
			.then(function successCallback(response)
				{
					var data = response.data;
					if(data=="Success")
					{
						$scope.submitted = false;
						$scope.FormValid = true;
						
						swal({title: "",
								     text: "Collection Created successfully",
								     type: "success",
								     timer: 2500 
							},function () {window.location.reload();})
							
			   			$timeout(function () { $scope.showSuccessAlert = false; window.location.href="payment-received.php";}, 2500);
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