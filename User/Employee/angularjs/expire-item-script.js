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
AddCategory.controller('CategoryController', ['$scope','$timeout', '$http','fileUpload', function($scope, $timeout, $http, jsonFilter, fileUpload) 
 {  

	$scope.FormAdd = false;	
	$scope.FormList = true;		
	$scope.pagetitle = "List of Expiry Products";

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
	$scope.pagetitle = "Add Shipping";
	$scope.FormAdd = true;	
	$scope.FormList = false;
	$scope.GetGrnId();
}


$scope.GetListData= function()
	{
		$http.get("load-expire-items.php")
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
    $scope.GetOrders($item.CustomerId);
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
$scope.disctype = "Rupee";
$scope.discvalue = "0.00";



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

$scope.OrderSum = function () {
		var ordersum = 0;
	if ($scope.BatchArr !== undefined) {

		for (var i = 0; i < $scope.BatchArr.length; i++)
		{
			ordersum += Number($scope.BatchArr[i]["quantity"]);
		}
	}

		$scope.ordersum=ordersum;
		return ordersum;


	}

$scope.PackedSum = function () {
		var packedsum = 0;
	if ($scope.BatchArr !== undefined) {

		for (var i = 0; i < $scope.BatchArr.length; i++)
		{
			packedsum += Number($scope.BatchArr[i]["PackedQty"]);
		}
	}

		$scope.packedsum=packedsum;
		return packedsum;


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
	window.location.href="packages.php";
}

	
$scope.ChangeQty = function(quantity,qtytopack,index)
{
	if(Number(qtytopack)>Number(quantity))
	{
		$scope.BatchArr[index].qtytopack = quantity;
	}
}
	
	
	$scope.AddCategoryData = function ()
	{
		$scope.submitted = true;
		//var config = { params: { AddCategory: AddCategory } };
		//$scope.AddCategory.supervisor: [];
	// 	var fd = new FormData();
 //          var file = $scope.docfile;
 //         fd.append('file', file);	

 //        var InvPkId=new Array();
	// 	var quantity=new Array();
	// 	var PackedQty = new Array();
	// 	var qtytopack=new Array();


	// for(i=0;i<$scope.BatchArr.length;i++)
	//     {
	// 	 InvPkId.push($scope.BatchArr[i].InvPkId);
	// 	 quantity.push($scope.BatchArr[i].quantity);
	// 	 PackedQty.push($scope.BatchArr[i].PackedQty);
	// 	 qtytopack.push($scope.BatchArr[i].qtytopack);
	// 	}
	
			//$scope.FileErr="";
			$http.post('add-shipping-process.php',{
            	'PackageId':$scope.PackageId,
				'ShipId':$scope.ShipId,
				// 'customerid':$scope.customerid,
				// 'customername':$scope.customername,
				'OrderId':$scope.OrderId,
				'carrier':$scope.carrier,
				'tracking':$scope.tracking,
				'shipcharge':$scope.shipcharge,
				'internalnotes':$scope.internalnotes,
				
				// 'referencenum':$scope.referencenum,
				 'entrydate':$scope.entrydate,
				// 'duedate':$scope.duedate,
				// 'paymentterms':$scope.paymentterms,
				// 'deliverymethod':$scope.deliverymethod,
				// 'salesperson':$scope.salesperson,

				// 'InvPkId':JSON.stringify(InvPkId),
				// 'quantity':JSON.stringify(quantity),
				// 'PackedQty':JSON.stringify(PackedQty),
				// 'qtytopack':JSON.stringify(qtytopack),
				// 'ordersum':$scope.ordersum,
				// 'packedsum':$scope.packedsum,
				// 'sum':$scope.sum,
				// 'disctype':$scope.disctype,
				// 'discvalue':$scope.discvalue,
				// 'disctotal':$scope.disctotal,
				// 'totalamount':$scope.totalamount,
				// 'cnotes':$scope.cnotes,
				// 'terms':$scope.terms,

			})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.submitted = false;
					$scope.FormValid = true;

					swal({title: "Added",
							     text: "Shipping created successfully",
							     type: "success",
							     timer: 1000 
						},function () {window.location.href="shipping.php";})
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
	

 $scope.SetasDeliver = function(PkId,ShipId,PackageId,OrderId)
    {

       swal({
      title: '',
      text: "Are you sure want to change shipping to delivered?",
      type: "warning",
      showCancelButton: true,
      confirmButtonClass: "btn-danger",
      confirmButtonText: "Yes, Delivered it!",
      cancelButtonText: "No, cancel!",
      closeOnConfirm: false,
      closeOnCancel: true,
        html: true
    },
    function(isConfirm) {
      if (isConfirm) {
        $http.post('ship-to-deliver-process.php',{'PkId':PkId,'ShipId':ShipId,'PackageId':PackageId,'OrderId':OrderId,
          }).then(function successCallback(response)
        {
        var data = response.data; 
              if(data=="Success")
              {   
                swal("Success!", "Status changed to Delivered.", "success");
              $timeout(function () {window.location.reload();}, 2000);

              }
              else
              {
                swal("", data, "error");
                  $timeout(function () { $scope.submitted = false;}, 2000);
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



}]);
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