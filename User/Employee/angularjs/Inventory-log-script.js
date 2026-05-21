var AddCategory = angular.module("CategoryModule", ['ui.bootstrap','ngPatternRestrict', 'angularUtils.directives.dirPagination','datatables'])
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

//   $scope.dtOptions ={
//  ajax:{},
//  columns:[],
//  order:[0,'DESC'] //<= Use this

// }

	$scope.FormAdd = true;	
	$scope.FormList = false;		
	$scope.pagetitle = "Search Product";

	$scope.datte=new Date();
	$scope.opened = {};
    $scope.opened.opened2 = false;
    $scope.opened.opened3 = false;
    
    $scope.open = function($event,datepicker) {
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
    $("#mfgdate").keypress(function (event) { event.preventDefault(); });
    $("#mfgdate").keydown(function (event) { event.preventDefault(); });


    $("#expdate").keypress(function (event) { event.preventDefault(); });
    $("#expdate").keydown(function (event) { event.preventDefault(); });
  });


	
	var logResult = function (data, status, headers, config)
	{
		return data;
	};

  $scope.GetWareHouse = function()
  {
    $http.get("load-locations.php")
    .then(function successCallback(response)
    {
      var data = response.data; 
      
      if(data=="NoData")
      {
        $scope.WHArray = "";
      }
      else
      {
        $scope.WHArray = data;
      }
    }, function errorCallback(response) {
          // called asynchronously if an error occurs
          // or server returns response with an error status.
        });
  }

	/*Add & Remove Script Starts Here*/
		//to remove the row
		$scope.removeRow = function (idx) 
		{
			$scope.POArr.splice(idx, 1);
		};

		$scope.POArr = [{Price:0}];


		$scope.addFormField = function() 
		{
			$scope.POArr.push({Price:0});
		}
		/*Add & Remove Script end Here*/

//$scope.POArr = {};

// $scope.GetGrnId = function()
// 	{
// 		$http.get("generate-grn-id.php")
// 		.then(function successCallback(response)
// 		{
// 			var data = response.data;
// 			$scope.GRNId = data;
// 		});
// 	}
$scope.ProductArray =[];
$scope.GetProductList= function(location)
	{
    //alert(location);
		$http.post("load-inventory-log.php",{'location': location})
		.then(function successCallback(response)
		{
			var data = response.data; 
			
			if(data=="NoData")
			{
        $scope.ProductArray = "";
			}
			else
			{
        $scope.ProductArray = data[0]['data1'];
			}
		}, function errorCallback(response) {
	    		// called asynchronously if an error occurs
			    // or server returns response with an error status.
			  });
	}

$scope.LoadLocations = function()
  {
    $http.get('load-locations.php')
    .then(function successCallback(response)
    {
      var data = response.data;
      if(data=="NoData")
      {
        $scope.LocationArr= '';
      }
      else
      {
        $scope.LocationArr = data;
      }
    }, function errorCallback(response) {
        // called asynchronously if an error occurs
        // or server returns response with an error status.
      });
  };

  $scope.LoadSizes = function()
  {
    $http.get('load-sizes.php')
    .then(function successCallback(response)
    {
      var data = response.data;
      if(data=="NoData")
      {
        $scope.SizeArr= '';
      }
      else
      {
        $scope.SizeArr = data;
      }
    }, function errorCallback(response) {
        // called asynchronously if an error occurs
        // or server returns response with an error status.
      });
  };

  $scope.LoadBrands = function()
  {
    $http.get('load-brands.php')
    .then(function successCallback(response)
    {
      var data = response.data;
      if(data=="NoData")
      {
        $scope.BrandArr= '';
      }
      else
      {
        $scope.BrandArr = data;
      }
    }, function errorCallback(response) {
        // called asynchronously if an error occurs
        // or server returns response with an error status.
      });
  };
$scope.buyprice = 0;
$scope.salesprice = 0;
$scope.GotoList = function()
{
	window.location.reload();
}

// $scope.FromReq=function(Name)
// {
//  if(Name==undefined || Name=="")
//   {

//   }
//   else
//   {
//     $scope.productname = Name;
//     $scope.FinalSubmisssion();
//   }

// }

	$scope.FinalSubmisssion = function()
	{
    $http.post('Get-all-products.php',{'productname': $scope.productname,'selectedproductname': $scope.productname.ProductName})
    .then(function successCallback(response)
		{
			var data = response.data;
      if(data=="NoData")
			{
        $scope.NoRecord = true;
        $scope.FormList = false;
        $scope.pagedItems = "";
			}
			else
			{
        $scope.NoRecord = false;
        $scope.FormList = true;
        $scope.pagedItems = data;
			}
			});
	}

	
$scope.TransferArray = [];
	$scope.AddtoTransfer = function(SKU,ProductName,Size,AvailableQty,FixedQty,quantity,index){

		// $scope.SKU = SKU;
		// $scope.ProductName = ProductName;
  //   $scope.Size = Size;
		// $scope.quantity = quantity;
    if (quantity && quantity!=0 && quantity<=AvailableQty) {

      $scope.ProductArray[index].AvailableQty = $scope.ProductArray[index].AvailableQty-$scope.ProductArray[index].quantity;

        var obj = {
          SKU: SKU,
          ProductName: ProductName,
          Size: Size,
          FixedQty: FixedQty,
          quantity: quantity
        };
        
      if($scope.TransferArray.length>0)
      {
        $scope.aaa=0;
        angular.forEach($scope.TransferArray, function(value, key) {
           if($scope.TransferArray[key].sku==obj.SKU)
           {
            $scope.aaa++;
            $scope.TransferArray[key].pquantity = Number($scope.TransferArray[key].pquantity)+Number(obj.quantity);
           }
           });

        // if($scope.TransferArray.length==0)
        // {

        //   $scope.TransferArray.push({ sku: obj.SKU, pname: obj.ProductName, psize: obj.Size, pquantity: obj.quantity});
        // }
        if($scope.aaa==0)
        {
          $scope.TransferArray.push({ sku: obj.SKU, pname: obj.ProductName, psize: obj.Size, FixedQty: obj.FixedQty, pquantity: obj.quantity});
        }
      }
      else
      {
        $scope.TransferArray.push({ sku: obj.SKU, pname: obj.ProductName, psize: obj.Size, FixedQty: obj.FixedQty, pquantity: obj.quantity});
        
      }
      $scope.ProductArray[index].quantity = '';
       
        // else
        // {
        //    angular.forEach($scope.TransferArray, function(value, key) {
        //    if($scope.TransferArray[key].sku==obj.SKU)
        //    {
        //     $scope.TransferArray[key].pquantity = Number($scope.TransferArray[key].pquantity)+Number(obj.quantity);
        //    }
        //    else
        //    {
        //      $scope.TransferArray.push({ sku: obj.SKU, pname: obj.ProductName, psize: obj.Size, pquantity: obj.quantity});
        //      obj.quantity = '';
        //    }
        //    });
        // }
       

  //this.push(key + ': ' + value);


      }
	}
  
$scope.GetStore = function(TransferTo)
{
  if(TransferTo=="Store")
  {
    $scope.GetWHStores();
    $scope.cname='';
    $scope.cmobile='';
  }
  else
  {
    $scope.storename='';
     $scope.GetCustomers();
  }
}
$scope.GetWHStores = function()
{
  $http.get("Get-stores.php")
    .then(function successCallback(response)
    {
      var data = response.data; 
      
      if(data=="NoData")
      {
        $scope.StoreArr = "";
      }
      else
      {
        $scope.StoreArr = data;
      }
    }, function errorCallback(response) {
          // called asynchronously if an error occurs
          // or server returns response with an error status.
        });
}
$scope.GetCustomers = function()
{
  $http.get("load-customers.php")
    .then(function successCallback(response)
    {
      var data = response.data; 
      
      if(data=="NoData")
      {
        $scope.CustomerArr = "";
      }
      else
      {
        $scope.CustomerArr = data;
      }
    }, function errorCallback(response) {
          // called asynchronously if an error occurs
          // or server returns response with an error status.
        });
}
  
  $scope.onSelect = function ($item, $model, $label) {
//console.log($item);
$scope.cmobile = $item.Mobile;
};

//$scope.GetCustMobile = function(cname)
//{
  //alert(cname);
  // $http.post("Get-customer-data.php",{'CustomerId': cname.CustomerId})
  //   .then(function successCallback(response)
  //   {
  //     var data = response.data; 
      
  //     if(data=="NoData")
  //     {
  //       $scope.cmobile = "";
  //     }
  //     else
  //     {
  //       $scope.cmobile = data['mobile'];
  //     }
  //   }, function errorCallback(response) {
  //         // called asynchronously if an error occurs
  //         // or server returns response with an error status.
  //       });
//}

$scope.DeleteEntry = function (idx,sku,pquantity) 
  {
    angular.forEach($scope.ProductArray, function(value, key) {
           if($scope.ProductArray[key].SKU==sku)
           {
            $scope.ProductArray[key].AvailableQty = Number($scope.ProductArray[key].AvailableQty)+Number(pquantity);
           }
           });
    $scope.TransferArray.splice(idx, 1);
  };

  // $scope.calculateSum = function () 
  // {
  //   var sum = 0;
  //   if ($scope.TransferArray !== undefined) 
  //   {
  //     for (var i = 0; i < $scope.TransferArray.length; i++)
  //     {
  //       sum += ($scope.TransferArray[i]["Price"]*$scope.CartItems[i]["quantity"]);
  //     }
  //   }
  //   $scope.sum=sum;
  //   return sum;
  // }

$scope.SaveInventory = function()
  {

    if($scope.TransferArray.length>0)
    {
       var sku=new Array();
       var pquantity=new Array();
       var FixedQty=new Array();

      for(i=0;i<$scope.TransferArray.length;i++)
            {

           sku.push($scope.TransferArray[i].sku);
           pquantity.push($scope.TransferArray[i].pquantity);
           FixedQty.push($scope.TransferArray[i].FixedQty);
          }

      $scope.submitted = true;
      if($scope.cname !== undefined)
      {
      $http.post('add-transfer-process.php',{
        'location': $scope.location,
        'TransferTo': $scope.TransferTo,
        'storename': $scope.storename,
        'cname': $scope.cname,
        'customername': $scope.cname.PersonName,
        'cmobile': $scope.cmobile,
        'comments': $scope.comments,
        'sku': sku,
        'FixedQty': FixedQty,
        'pquantity': pquantity,
      })
      .then(function successCallback(response)
      {
        var data = response.data;
        if(data=="Success")
        {
          swal({title: "Success",
                     text: "Stock added successfully",
                     type: "success",
                     timer: 2000 
              },function () {window.location.href="list-inventory.php"})
        }
        else
        {
          swal("STOP", data, "error");
                $timeout(function () { $scope.submitted = false;}, 3000);
        }
        });
    }
  }
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