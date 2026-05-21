var AddCategory = angular.module("CategoryModule", ['ui.bootstrap'])
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
	$scope.FormAdd = false;	
	$scope.FormList = true;	
	$scope.pagetitle = "List of Products";
	
	var logResult = function (data, status, headers, config)
	{
		return data;
	};


$scope.opened = {};
    $scope.opened.bat = false;
  $scope.open = function($event, bat) {
    $event.preventDefault();
    $event.stopPropagation();
    bat.opened = true;
  };

  $scope.opened1 = {};
    $scope.opened1.bat = false;
  $scope.open1 = function($event, bat) {
    $event.preventDefault();
    $event.stopPropagation();
    bat.opened1 = true;
  };


	// $scope.datte=new Date();
	// $scope.opened = {};
 //    $scope.opened.opened2 = false;
 //    $scope.opened.opened3 = false;
    
 //    $scope.open = function($event,datepicker) {
 //      $event.preventDefault();
 //      $event.stopPropagation();
 //      $scope.opened[datepicker] = true;
 //    };
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


/*Add & Remove Script Starts Here*/
//to remove the row
$scope.removeRow = function (idx) 
{
	$scope.BatchArr.splice(idx, 1);
};

$scope.BatchArr = [{}];

    $scope.AddMore = function() 
    {
      $scope.BatchArr.push({});
    }

/*Add & Remove Script end Here*/

$scope.GotoAdd = function()
{
}
$scope.costprice = "0";
$scope.salesprice = "0";
$scope.stockrate = "0";
$scope.invtrack = "None";

	$scope.GetListData= function(ProductPkId)
	{
		$scope.pagetitle = "Edit Product";
		$scope.FormAdd = true;	
		$scope.FormList = false;	
		$scope.LoadCatgory();
		$scope.LoadSizes();
		$scope.LoadColour();
		$scope.GetUnits();
		$scope.GetManufactures();
		$scope.GetBrands();
		$scope.GetVendors();

		$http.post("Get-product-info.php",{'ProductPkId': ProductPkId})
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
				$scope.FormPkId = data['PkId'];
				$scope.productid = data['ProductId'];
				$scope.category = data['PkId_Category'];
				$scope.GetSubCatgory(data['PkId_Category']);
				$scope.subcategory = data['PkId_SubCategoryMaster'];
				$scope.GetLevel2(data['PkId_Category'],data['PkId_SubCategoryMaster']);
				$scope.lvl2subcat = data['PkId_Level2SubCategoryMaster'];
				$scope.GetLevel3(data['PkId_Category'],data['PkId_SubCategoryMaster'],data['PkId_Level2SubCategoryMaster'])
				$scope.lvl3subcat = data['PkId_Level3SubCategoryMaster'];

				$scope.productname = data['ProductName'];
				$scope.productsize = data['Size'];
				$scope.colour = data['Colour'];

				
				$scope.invtype = data['InventoryType'];
				$scope.sku = data['SKU'];
				$scope.unit = data['Unit'];
				$scope.costprice = data['PurchasePrice'];
				$scope.salesprice = data['SalesPrice'];
				//alert(data['IsItReturnable']);
				if(data['IsItReturnable']==1)
				{
					$scope.isreturn = true;
				}
				else
				{
					$scope.isreturn = false;
				}
				//$scope.isreturn = data['IsItReturnable'];
				$scope.dimension = data['Dimensions'];
				$scope.weight = data['Weight'];
				$scope.manfacture = data['Manufacturer'];
				$scope.brand = data['Brand'];
				$scope.mpn = data['MPN'];
				$scope.upc = data['UPC'];
				$scope.isbn = data['ISBN'];
				$scope.ean = data['EAN'];
				//$scope.openstock = data['OpeningStock'];
				$scope.stockrate = data['OpeningStockRateperUnit'];
				$scope.reorderlevel = data['ReorderPoint'];
				$scope.invtrack = data['TrackingMode'];
				$scope.oldiinvtrack = data['TrackingMode'];
				$scope.VendorId = data['VendorId_VendorMaster'];
				$scope.vendorname = data['VendorName'];
				$scope.serailnumber = data['serailnumber'];
				$scope.FileName = data['FileName'];
				//alert($scope.data['data2']);
				$scope.BatchArr = data['data2'];
				angular.forEach($scope.BatchArr, function(value, key) {
					   $scope.BatchArr[key].EntryPkId = value.EntryPkId;
					   $scope.BatchArr[key].batchno = value.BatchNoORSrNo;
					   $scope.BatchArr[key].manfacturebatch = value.BatchManufacturer;
					   //alert(value.ManfactureDate);
					   if(value.ManfactureDate==undefined || value.ManfactureDate==null)
					   {
					   	$scope.BatchArr[key].mfgdate = "";
					   }
					   else
					   {
					   	$scope.BatchArr[key].mfgdate = new Date(value.ManfactureDate);	
					   }

					   if(value.ExpireDate==undefined || value.ExpireDate==null)
					   {
					   	$scope.BatchArr[key].expdate = "";
					   }
					   else
					   {
					   	$scope.BatchArr[key].expdate = new Date(value.ExpireDate);	
					   }
					   $scope.BatchArr[key].AvlQuantity = value.Quantity;
					   $scope.BatchArr[key].quantity = value.OpeningQuantity;
					});
				//$scope.FormList = true;

			}
		});
	}

 $scope.onCustomerSelect = function ($item, $model, $label) {
    $scope.$item = $item;
    $scope.customerid = $item.CustomerId;
    $scope.paymentterms = $item.PaymentTerms;
    $scope.$model = $model;
    $scope.$label = $label;
};

	$scope.LoadCatgory = function()
	{
		$http.get('load-category.php')
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{
				$scope.CategoryArr= '';
			}
			else
			{
				$scope.CategoryArr = data;
			}
		}, function errorCallback(response) {
    		// called asynchronously if an error occurs
		    // or server returns response with an error status.
		  });
	};

	$scope.GetSubCatgory = function(category)
	{
		$http.post('Get-subcategory.php',{'category': category})
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{
				$scope.SubCategoryArr= '';
			}
			else
			{
				$scope.SubCategoryArr = data;
			}
		}, function errorCallback(response) {
    		// called asynchronously if an error occurs
		    // or server returns response with an error status.
		  });
	};
	
	$scope.GetLevel2 = function(category,subcategory)
	{
		$http.post('Get-Level2.php',{'category': category,'subcategory': subcategory})
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{
				$scope.Level2Arr= '';
			}
			else
			{
				$scope.Level2Arr = data;
			}
		}, function errorCallback(response) {
    		// called asynchronously if an error occurs
		    // or server returns response with an error status.
		  });
	};

	$scope.GetLevel3 = function(category,subcategory,lvl2subcat)
	{
		$http.post('Get-Level3.php',{'category': category,'subcategory': subcategory,'lvl2subcat': lvl2subcat})
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{
				$scope.Level3Arr= '';
			}
			else
			{
				$scope.Level3Arr = data;
			}
		}, function errorCallback(response) {
    		// called asynchronously if an error occurs
		    // or server returns response with an error status.
		  });
	};
	$scope.SizeArr = [];
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

  $scope.ColourArr = [];
$scope.LoadColour = function()
  {
    $http.get('load-colours.php')
    .then(function successCallback(response)
    {
      var data = response.data;
      if(data=="NoData")
      {
        $scope.ColourArr= '';
      }
      else
      {
        $scope.ColourArr = data;
      }
    }, function errorCallback(response) {
        // called asynchronously if an error occurs
        // or server returns response with an error status.
      });
  };

  	$scope.UnitArr=[];
	$scope.GetUnits = function()
	{
		$http.get('load-units.php')
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{
				$scope.UnitArr= '';
			}
			else
			{
				$scope.UnitArr = data;
			}
		}, function errorCallback(response) {
    		// called asynchronously if an error occurs
		    // or server returns response with an error status.
		  });
	};

	$scope.MfgArr=[];
	$scope.GetManufactures = function()
	{
		$http.get('load-manufactures.php')
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{
				$scope.MfgArr= '';
			}
			else
			{
				$scope.MfgArr = data;
			}
		}, function errorCallback(response) {
    		// called asynchronously if an error occurs
		    // or server returns response with an error status.
		  });
	}

	$scope.BrandArr=[];
	$scope.GetBrands = function()
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
	}
	

$scope.calculateSum = function () {
		var sum = 0;
	if ($scope.BatchArr !== undefined) {

		for (var i = 0; i < $scope.BatchArr.length; i++)
		{
			sum += Number($scope.BatchArr[i]["quantity"]);
		}
	}

		$scope.sum=sum;
		return sum;
	}


$scope.GetVendors = function()
	{
        $http.get('load-suppliers.php')
        .then(function successCallback(response)
		{
			var data = response.data;
        	if(data=="NoData")
			{
				$scope.VednorArray = "";
			}
			else
			{
				$scope.VednorArray = data;
			}
			});
	}

$scope.onVendorSelect = function ($item, $model, $label) {
    $scope.$item = $item;
    $scope.VendorId = $item.VendorId;
    $scope.$model = $model;
    $scope.$label = $label;
};

		$scope.Updatedata = function ()
		{
		$scope.submitted = true;
		var fd = new FormData();
          var file = $scope.docfile;
         fd.append('file', file);	

         var EntryPkId = new Array();
        var BatchNo=new Array();
		var ManfcBatch=new Array();
		var Mfgdate=new Array();
		var Expdate=new Array();
		var Quantity=new Array();
		var AvlQuantity=new Array();


	for(i=0;i<$scope.BatchArr.length;i++)
	    {
		 EntryPkId.push($scope.BatchArr[i].EntryPkId);
		 BatchNo.push($scope.BatchArr[i].batchno);
		 ManfcBatch.push($scope.BatchArr[i].manfacturebatch);
		 Mfgdate.push($scope.BatchArr[i].mfgdate);
		 Expdate.push($scope.BatchArr[i].expdate);
		 Quantity.push($scope.BatchArr[i].quantity);
		 AvlQuantity.push($scope.BatchArr[i].AvlQuantity);
		}
		$http.post('update-product-type-process.php', fd, {transformRequest: angular.identity,headers: {'Content-Type': undefined},
            params: {
		        'FormPkId':$scope.FormPkId,
				'productid':$scope.productid,
				'category':$scope.category,
				'subcategory':$scope.subcategory,
				'lvl2subcat':$scope.lvl2subcat,
				'lvl3subcat':$scope.lvl3subcat,
				'productname':$scope.productname,
				'productsize':$scope.productsize,
				'colour':$scope.colour,
				'invtype':$scope.invtype,
				'FileName':$scope.FileName,
				'sku':$scope.sku,
				'unit':$scope.unit,
				'costprice':$scope.costprice,
				'salesprice':$scope.salesprice,
				'isreturn':$scope.isreturn,
				'dimension':$scope.dimension,
				'weight':$scope.weight,
				'manfacture':$scope.manfacture,
				'brand':$scope.brand,
				'mpn':$scope.mpn,
				'upc':$scope.upc,
				'isbn':$scope.isbn,
				'ean':$scope.ean,
			//	'openstock':$scope.openstock,
				'stockrate':$scope.stockrate,
				'reorderlevel':$scope.reorderlevel,
				'VendorId':$scope.VendorId,
				'vendorname':$scope.vendorname,
				'oldiinvtrack':$scope.oldiinvtrack,
				'invtrack':$scope.invtrack,
				'serailnumber':$scope.serailnumber,
				'EntryPkId':JSON.stringify(EntryPkId),
				'BatchNo':JSON.stringify(BatchNo),
				'ManfcBatch':JSON.stringify(ManfcBatch),
				'Mfgdate':JSON.stringify(Mfgdate),
				'Expdate':JSON.stringify(Expdate),
				'Quantity':JSON.stringify(Quantity),
				'AvlQuantity':JSON.stringify(AvlQuantity),
				'sum':$scope.sum,
			},
			})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.submitted = false;
					$scope.FormValid = true;

					swal({title: "Update",
							     text: "Product updated successfully",
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
		window.location.href = "product-types.php";
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
