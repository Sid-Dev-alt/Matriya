var AddCategory = angular.module("CategoryModule", ['ui.bootstrap','datatables','angularUtils.directives.dirPagination'])
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
	$scope.pagetitle = "List of Items";
	$scope.vm = {};
$scope.vm.dtInstance = {};   
$scope.vm.dtColumnDefs = [
DTColumnDefBuilder.newColumnDef(4).notSortable()
];
$scope.vm.dtOptions = DTOptionsBuilder.newOptions()
          		.withOption('order', [0, 'desc'])
				.withOption('info', false);	

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



// dropdown for phases 1-100
	$scope.MicronArr = [];
      var len=100;
      for(var i=1;i<101;i++)
      $scope.MicronArr.push(i);


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
	$scope.pagetitle = "Add Item";
	$scope.FormAdd = true;	
	$scope.FormList = false;	
	$scope.GetProductId();
	$scope.LoadCatgory();
	//$scope.LoadSizes();
	//$scope.LoadColour();
	$scope.GetUnits();
	//$scope.GetManufactures();
	//$scope.GetBrands();
	//$scope.GetVendors();
}

$scope.costprice = "0.00";
$scope.salesprice = "0.00";
$scope.stockrate = "0.00";
$scope.openweight = "0.000";
$scope.invtrack = "None";

	$scope.pagedItems = []; //declare an empty array
    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 10; //this could be a dynamic value from a drop down

 $scope.loading = false;
$scope.getData = function(pageno){ 

      $scope.mypageno = pageno;
    // This would fetch the data on page change.
        //In practice this should be in a factory.
        $scope.pagedItems = [];  
        $scope.loading = true;
        $http.post("load-product-types.php",{'itemsPerPage': $scope.itemsPerPage, 'pagenumber': pageno})
          .then(function successCallback(response)
        {
            var data = response.data;
            $scope.pagedItems = data['data1'];  // data to be displayed on current page.
            $scope.total_count = data['Total']; // total data count.
            $scope.loading = false;
        }, function errorCallback(response) {
    // called asynchronously if an error occurs
      // or server returns response with an error status.
      });
    };
    $scope.getData($scope.pageno); // Call the function to fetch initial data on page load.
    $scope.sort = function(keyname){
        $scope.sortKey = keyname;   //set the sortKey to the param passed
        $scope.reverse = !$scope.reverse; //if true make it false and vice versa
    }

    $scope.sort1 = function(keyname){
    	//alert(index);
        $scope.sortKey1 = keyname;   //set the sortKey to the param passed
        $scope.reverse1 = !$scope.reverse1; //if true make it false and vice versa
    }


$scope.GetProductId= function()
	{
		$http.get("generate-productid.php")
		.then(function successCallback(response)
		{
			var data = response.data;
			$scope.productid = data;
		});
	}

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
	
	$scope.SizeArr = [];
$scope.LoadSizes = function()
  {
	  alert();
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

$scope.invtype = "Raw";
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

$scope.Edit = function(PkId,ProductId,PkId_Category,ProductName,Micron,ProductSize,Unit)
	{
		$scope.LoadCatgory();
		$scope.GetUnits();
		$scope.FormList = false;
		$scope.FormAdd = true;
		$scope.FormPkId = PkId;
		$scope.productid = ProductId;
		$scope.category = PkId_Category;
		$scope.productname = ProductName;
		$scope.micron = Micron;
		$scope.productsize = ProductSize;
		$scope.unit = Unit;
	}
	
// 	$scope.UpdateArr = function(BatchNo,ManfcBatch,Mfgdate,Expdate,Quantity,index)
// 	{
			
// 			$scope.batchno = BatchNo;
// 			$scope.manfacturebatch = ManfcBatch;
// 			$scope.mfgdate = new Date(Mfgdate);
// 			$scope.expdate = new Date(Expdate);
// 			$scope.quantity = Quantity;
		
// 	}
	
	$scope.AddCategoryData = function ()
	{		//alert(SizeClr);
		if(parseFloat($scope.salesprice)<parseFloat($scope.costprice))
		{
			//alert("high");
			 swal({
		      title: 'Cost Price should be less than Selling Price',
		      text: "Are you sure to save the entry?",
		      type: "warning",
		      showCancelButton: true,
		      confirmButtonClass: "btn-danger",
		      confirmButtonText: "Yes, Save",
		      cancelButtonText: "No, cancel!",
		      closeOnConfirm: false,
		      closeOnCancel: true,
		        html: true
		    },
		    function(isConfirm) {
		      if (isConfirm) {
		      	$scope.SaveProduct();
		      	 } else {
        		swal("Cancelled", "Your record is safe :)", "error");
		      	}
		    });
		}
		else
		{
			$scope.SaveProduct();
		}
	}
$scope.productsize="";
$scope.SaveProduct = function ()
{
	if($scope.AddCategoryForm.$invalid)
	{
	    $scope.submitted = true;
	}
	else
	{
//		alert($scope.openweight);
		$scope.submitted = true;
		//var config = { params: { AddCategory: AddCategory } };
		//$scope.AddCategory.supervisor: [];
		var fd = new FormData();
          var file = $scope.docfile;
         fd.append('file', file);	

        var BatchNo=new Array();
		var ManfcBatch=new Array();
		var Mfgdate=new Array();
		var Expdate=new Array();
		var Quantity=new Array();


	for(i=0;i<$scope.BatchArr.length;i++)
    {
	 BatchNo.push($scope.BatchArr[i].batchno);
	 ManfcBatch.push($scope.BatchArr[i].manfacturebatch);
	 Mfgdate.push($scope.BatchArr[i].mfgdate);
	 Expdate.push($scope.BatchArr[i].expdate);
	 Quantity.push($scope.BatchArr[i].quantity);
	}

	if($scope.FormPkId==undefined)
	{
		$scope.loading = true;
				$http.post('add-product-type-process.php', fd, {transformRequest: angular.identity,headers: {'Content-Type': undefined},
	        params: {
				'category':$scope.category,
				'subcategory':$scope.subcategory,
				'lvl2subcat':$scope.lvl2subcat,
				'lvl3subcat':$scope.lvl3subcat,
				'productid':$scope.productid,
				'productname':$scope.productname,
				'sku':$scope.sku,
				'micron':$scope.micron,
				'unit':$scope.unit,
				'costprice':$scope.costprice,
				'salesprice':$scope.salesprice,
				'productsize':$scope.productsize,
				'colour':$scope.colour,
				'invtype':$scope.invtype,
				'isreturn':$scope.isreturn,
				'dimension':$scope.dimension,
				'weight':$scope.weight,
				'manfacture':$scope.manfacture,
				'brand':$scope.brand,
				'mpn':$scope.mpn,
				'upc':$scope.upc,
				'isbn':$scope.isbn,
				'ean':$scope.ean,
				'openstock':$scope.openstock,
				'openweight':$scope.openweight,
				'stockrate':$scope.stockrate,
				'reorderlevel':$scope.reorderlevel,
				'vendorname':$scope.vendorname,
				'invtrack':$scope.invtrack,
				'serailnumber':$scope.serailnumber,
				'BatchNo':JSON.stringify(BatchNo),
				'ManfcBatch':JSON.stringify(ManfcBatch),
				'Mfgdate':JSON.stringify(Mfgdate),
				'Expdate':JSON.stringify(Expdate),
				'Quantity':JSON.stringify(Quantity),
				'sum':$scope.sum,
				},
			})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.loading = false;
					$scope.submitted = false;
					$scope.FormValid = true;

					swal({title: "Added",
							     text: "Item created successfully",
							     type: "success",
							     timer: 2000 
						},function () {window.location.reload();})
				}
				else
				{
					$scope.loading = false;
					swal("Required", data, "error");
					$timeout(function () { $scope.submitted = false;}, 3000);
				}
			}, function errorCallback(response) {
	    		// called asynchronously if an error occurs
			    // or server returns response with an error status.
			  });
	}
	else
	{
		$scope.loading = true;
		$scope.submitted = true;
		$http.post('update-product-type-process.php', fd, {transformRequest: angular.identity,headers: {'Content-Type': undefined},
	        params: {
			//$http.post('update-product-type-process.php', {
		        'FormPkId':$scope.FormPkId,
				'category':$scope.category,
				'subcategory':$scope.subcategory,
				'lvl2subcat':$scope.lvl2subcat,
				'lvl3subcat':$scope.lvl3subcat,
				'productid':$scope.productid,
				'productname':$scope.productname,
				'sku':$scope.sku,
				'micron':$scope.micron,
				'unit':$scope.unit,
				'costprice':$scope.costprice,
				'salesprice':$scope.salesprice,
				'productsize':$scope.productsize,
				'colour':$scope.colour,
				'invtype':$scope.invtype,
				'isreturn':$scope.isreturn,
				'dimension':$scope.dimension,
				'weight':$scope.weight,
				'manfacture':$scope.manfacture,
				'brand':$scope.brand,
				'mpn':$scope.mpn,
				'upc':$scope.upc,
				'isbn':$scope.isbn,
				'ean':$scope.ean,
				'openstock':$scope.openstock,
				'openweight':$scope.openweight,
				'stockrate':$scope.stockrate,
				'reorderlevel':$scope.reorderlevel,
				'vendorname':$scope.vendorname,
				'invtrack':$scope.invtrack,
				'serailnumber':$scope.serailnumber,
				'BatchNo':JSON.stringify(BatchNo),
				'ManfcBatch':JSON.stringify(ManfcBatch),
				'Mfgdate':JSON.stringify(Mfgdate),
				'Expdate':JSON.stringify(Expdate),
				'Quantity':JSON.stringify(Quantity),
				'sum':$scope.sum,
			}
			})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.loading = false;
					$scope.submitted = false;
					$scope.FormValid = true;

					swal({title: "Update",
							     text: "Item updated successfully",
							     type: "success",
							     timer: 1000 
						},function () {})
						$scope.getData($scope.mypageno); // Call the function to fetch initial data on page load.
					$scope.FormAdd = false;
					$scope.FormList = true;
					$scope.pagetitle = "List of Items";
					
				}
				else
				{
					$scope.loading = false;
					swal("STOP", data, "error");
					$timeout(function () { $scope.submitted = false;}, 3000);
				}
			}, function errorCallback(response) {
	    		// called asynchronously if an error occurs
			    // or server returns response with an error status.
			  });
		}
	}
}

		
	
	$scope.GotoList = function()
	{		
		$scope.FormAdd = false;
		$scope.FormList = true;
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

        	var temp = '0.000';
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

		  var temp1 = '0.00';
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
