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

	$scope.FormAdd = true;	
	$scope.FormList = false;		
	$scope.pagetitle = "List of GRN's";

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
			$scope.POArr.splice(idx, 1);
		};

		$scope.POArr = [{mrp:0,price:0}];


		$scope.addFormField = function() 
		{
			$scope.POArr.push({mrp:0,price:0});
		}
		/*Add & Remove Script end Here*/

//$scope.POArr = {};

$scope.GotoAdd = function()
{
	$scope.pagetitle = "Add Stock to Warehouse";
	$scope.FormAdd = true;	
	$scope.FormList = false;	
	$scope.GetGrnId();	
	$scope.LoadProducts();
}
$scope.GetGrnId = function()
	{
		$http.get("generate-grn-id.php")
		.then(function successCallback(response)
		{
			var data = response.data;
			$scope.GRNId = data;
		});
	}
$scope.GotoList = function()
{
	window.location.reload();
}

	// $scope.GetSuppliers = function()
	// {
 //        $http.get('load-suppliers.php')
 //        .then(function successCallback(response)
	// 	{
	// 		var data = response.data;
 //        	if(data=="NoData")
	// 		{
	// 			$scope.SupplierArray = "";
	// 		}
	// 		else
	// 		{
	// 			$scope.SupplierArray = data;
	// 		}
	// 		});
	// }

	// $scope.ProductTypeArray = [];
	// $scope.GetProducts= function(suppliername)
	// {
	// 	$http.post("Get-supplier-Products.php",{'suppliername': suppliername})
	// 	.then(function successCallback(response)
	// 	{
	// 		var data = response.data;
	// 		if(data=="NoData")
	// 		{
	// 			$scope.ProductTypeArray = "";
	// 		}
	// 		else
	// 		{
	// 			$scope.ProductTypeArray = data;
	// 		}
	// 	});
	// }

	

	// $scope.GetRate= function(product,brand,prosize,procolour,index)
	// {
	// 	$http.post("Get-Product-Rate.php",{'product': product,'brand': brand,'prosize': prosize,'procolour': procolour})
	// 	.then(function successCallback(response)
	// 	{
	// 		var data = response.data;  
	// 		$scope.POArr[index].price = data;
	// 	}, function errorCallback(response) {
 //    		// called asynchronously if an error occurs
	// 	    // or server returns response with an error status.
	// 	  });
	// }

	// $scope.EditSupplier = function(PkId,POrderId,PODate,PkId_SupplierMaster,PersonName,ShopName,POrderStatus,RequiredBy,Comments,data2,index){
	// 	$scope.pagetitle = "Edit Purchase Order";
	// 	$scope.FormAdd = true;
	// 	$scope.FormList = false;
	// 	$scope.GetGrnId();
	// 	$scope.GetProducts(PkId_SupplierMaster);
	// 	$scope.FormPkId = PkId;
	// 	$scope.POId = POrderId;
	// 	$scope.datte = new Date(PODate);
	// 	$scope.suppliername = PkId_SupplierMaster;
	// 	$scope.req_by = new Date(RequiredBy);
	// 	$scope.notes = Comments;
	// 	$scope.POArr = data2;
	// 	angular.forEach(data2, function (value, key) {
	// 		$scope.POArr[key].mrp = 0;
	// 		$scope.POArr[key].price = 0;
	// 	  $scope.GetBrand(data2[key].product,key);
	// 	  $scope.GetRate(0,0,key);
	// 	})	

	// }

$scope.ProductTypeArray = [];
	$scope.LoadProducts= function()
	{
		$http.get("load-product-types.php")
		.then(function successCallback(response)
		{
			var data = response.data;  
			if(data=="NoData")
			{
				$scope.ProductTypeArray = "";
			}
			else
			{
				$scope.ProductTypeArray = data;
			}
		});
	}

	$scope.GetAvlQty= function(product,index)
	{
		if (product !== undefined) {
		$http.post("Get-Available-Qty.php",{'product': product.PkId})
		.then(function successCallback(response)
		{
			var data = response.data;  
			$scope.POArr[index].SalesIn = data['SalesIn'];
			$scope.POArr[index].AvlQty = data['AvlQty'];
		}, function errorCallback(response) {
    		// called asynchronously if an error occurs
		    // or server returns response with an error status.
		  });
		}
	}

	$scope.GetListData= function(requestno)
	{
		$http.post("load-po-data.php", {'requestno' :$scope.requestno})
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

	$scope.GetTotal = function(AvlQty,finalquantity,index){
		
		$scope.POArr[index].TotalQty = Number(AvlQty)+Number(finalquantity);
	}

$scope.calculateSum = function () {
		var sum = 0;
	if ($scope.POArr !== undefined) {

		for (var i = 0; i < $scope.POArr.length; i++)
		{
			sum += ($scope.POArr[i]["RowTotal"]);
		}
	}
		$scope.sum=sum;
		return sum;
	}


	
	$scope.FinalSubmisssion = function ()
	{
		$scope.submitted = true;

			var ArrId=new Array();
			var product=new Array();
			var PkId_Category=new Array();
			var AvlQty=new Array();
			var SalesIn= new Array();
			var finalquantity= new Array();
			var TotalQty = new Array();

		for(i=0;i<$scope.POArr.length;i++)
			    {

				 ArrId.push($scope.POArr[i].ArrId);
				 product.push($scope.POArr[i].product.PkId);				 
				 PkId_Category.push($scope.POArr[i].product.PkId_Category);
				 AvlQty.push($scope.POArr[i].AvlQty);
				 SalesIn.push($scope.POArr[i].SalesIn);
				 finalquantity.push($scope.POArr[i].finalquantity);
				 TotalQty.push($scope.POArr[i].TotalQty);
				}

		var fd = new FormData();
          var file = $scope.docfile;
          fd.append('file', file)

          $http.post('upload-grn-files.php', fd, {
				transformRequest: angular.identity,
				headers: {'Content-Type': undefined},
				params:{
					'GRNId':$scope.GRNId,
				},
			})
			.then(function successCallback(response)
			{
			var data = response.data;
				
				$scope.path = data;

			$http.post('add-grn-process.php', {
        	'GRNId':$scope.GRNId,
        	//'POId':$scope.POId,
        	'datte':$scope.datte,
        	//'suppliername':$scope.suppliername,
			//'invoiceno':$scope.invoiceno,
			//'dcno':$scope.dcno,
			'ArrId':ArrId,
			'product':product,
			'PkId_Category':PkId_Category,
			'AvlQty':AvlQty,
			'SalesIn':SalesIn,
			'finalquantity':finalquantity,
			'TotalQty':TotalQty,

			'ufile':$scope.path,
			'grncomments':$scope.grncomments,
			//'sum':$scope.sum,
		})
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data=="Success")
			{
				$scope.submitted = false;
				
				swal({title: "success!",
					     text: "Stock added successfully.",
					     type: "success",
					     timer: 2000},
				     	function(){ location.reload();});
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
		});
		
		
	};

	


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