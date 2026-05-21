var AddCategory = angular.module("CategoryModule", ['ui.bootstrap','datatables'])

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

AddCategory.controller("CategoryController", function ($scope, $timeout, $http, $window, jsonFilter, DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder)
{
	$scope.FormAdd = false;	
	$scope.FormList = true;	
	$scope.pagetitle = "List of PP Bags Productions";
	
	var logResult = function (data, status, headers, config)
	{
		return data;
	};
	$scope.Math = $window.Math;

$scope.vm = {};
$scope.vm.dtInstance = {};   
$scope.vm.dtColumnDefs = [
//DTColumnDefBuilder.newColumnDef(8).notSortable()
];
$scope.vm.dtOptions = DTOptionsBuilder.newOptions()
          		.withOption('order', [0, 'desc'])
				.withOption('info', false);

	 $scope.entrydate=new Date();
	 var mindt = new Date();
  mindt.setDate(mindt.getDate() -90);
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
       minDate: mindt,
       showWeeks:false,
        startingDay: 1
    };


   $(document).ready(function() {
    $("#entrydate").keypress(function (event) { event.preventDefault(); });
    $("#entrydate").keydown(function (event) { event.preventDefault(); });


    $("#shipdate").keypress(function (event) { event.preventDefault(); });
    $("#shipdate").keydown(function (event) { event.preventDefault(); });
  });
$scope.instantrawweight = "0.000";
$scope.reducerrawweight = "0.000";
//$scope.dripper = "0.000";

/*Add & Remove Script Starts Here*/
//to remove the row
$scope.removeRow = function (idx) 
{
	$scope.BatchArr.splice(idx, 1);
};

$scope.BatchArr = [{rawquantity:0}];

    $scope.AddMore = function() 
    {
      $scope.BatchArr.push({rawquantity:0});
    }

/*Add & Remove Script end Here*/

$scope.GotoAdd = function()
{
	$scope.pagetitle = "Add New";
	$scope.FormAdd = true;	
	$scope.FormList = false;	
	$scope.GetOrderId();
	$scope.GetFinshedProducts();
	//$scope.GetPPMaterials();
	$scope.GetInstantMaterials();
	$scope.GetReduceMaterials();
	$scope.GetCustomers();
	//$scope.GetPaymentTerms();
	//$scope.GetInventory();

}
$scope.GetCustomers = function()
	{
        $http.get('get-customers.php')
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

	
 $scope.onCustomerSelect = function ($item, $model, $label,entrydate) {
    $scope.$item = $item;
    $scope.customerid = $item.CustomerId;
    //$scope.paymentterms = $item.PaymentTerms;
    $scope.$model = $model;
    $scope.$label = $label;
    $scope.GetFabricInfo($item.CustomerId,entrydate);
};
$scope.GetFabricInfo= function(CustomerId,entrydate)
	{
		$http.post("get-customer-fabric-info.php",{'CustomerId': CustomerId,'entrydate': entrydate})
		.then(function successCallback(response)
		{
			var data = response.data;
			$scope.FabricProductId = data['FabricProductId'];
			$scope.TotalProduction = data['TotalProduction'];
			$scope.TotalConsumed = data['TotalConsumed'];
			$scope.TotalFabric = data['TotalFabric'];

			//alert($scope.TotalProduction);
		});
	}


//$scope.DeliveryArray = ['Transport','Courier','Auto','Rikshaw'];
	$scope.GetListData= function()
	{
		$http.get("load-unit-productions.php")
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
		$http.get("generate-unit-production-id.php")
		.then(function successCallback(response)
		{
			var data = response.data;
			$scope.productionid = data;
		});
	}

$scope.ProductArray = [];
	$scope.GetFinshedProducts = function()
	{
        $http.get('get-ppbag-productid.php')
        .then(function successCallback(response)
		{
			var data = response.data;
        	if(data=="NoData")
			{
				//$scope.ProductArray = "";
				$scope.productid = data[0]['ProductId'];
				$scope.productname = data[0]['ProductName'];
			}
			else
			{
				//$scope.ProductArray = data;
				$scope.productid = data[0]['ProductId'];
				$scope.productname = data[0]['ProductName'];
			}
			});
	}
$scope.onProductSelect = function ($item, $model, $label) {
    $scope.$item = $item;
    $scope.productid = $item.ProductId;
    $scope.productname = $item.ProductName;
    $scope.avlstock = $item.quantity;
    $scope.$model = $model;
    $scope.$label = $label;
};

$scope.PPProductArray = [];
$scope.GetInstantMaterials = function()
{
    $http.get('get-instant-materials.php')
    .then(function successCallback(response)
	{
		var data = response.data;
    	if(data=="NoData")
		{
			$scope.PPProductArray = "";
		}
		else
		{
			$scope.PPProductArray = data;
		}
		});
}

$scope.onPPSelect = function ($item, $model, $label) {
    $scope.$item = $item;
    $scope.InstantRawProductId = $item.ProductId;
    $scope.instantrawproduct = $item.ProductName;
    $scope.InstantAvlQty = $item.AvlQuantity;
    $scope.PPSKU = $item.SKU;
    $scope.$model = $model;
    $scope.$label = $label;
};
// $scope.startNewRow = function (index, count) {
//       return ((index) % count) === 0;
//   };
$scope.FillerArray = [];
	$scope.GetReduceMaterials = function()
	{
        $http.get('get-reducer-materials.php')
        .then(function successCallback(response)
		{
			var data = response.data;
        	if(data=="NoData")
			{
				$scope.FillerArray = "";
			}
			else
			{
				$scope.FillerArray = data;
			}
			});
	}
$scope.onFillerSelect = function ($item, $model, $label) {
    $scope.$item = $item;
    $scope.ReducerProductId = $item.ProductId;
    $scope.reducerrawproduct = $item.ProductName;
    $scope.ReducerAvlQty = $item.AvlQuantity;
    $scope.FillerSKU = $item.SKU;
    $scope.$model = $model;
    $scope.$label = $label;
};

	
$scope.ChkPP = function(pprawweight,PPAvlQty)
{
	if(parseFloat(pprawweight)>parseFloat(PPAvlQty))
	{
		$scope.PPErr = "Qty is more than Available Quantity";
	}
	else
	{
		$scope.PPErr = "";
	}
}
$scope.ChkFiller = function(fillerrawweight,FillerAvlQty)
{
	if(parseFloat(fillerrawweight)>parseFloat(FillerAvlQty))
	{
		$scope.FillerErr = "Qty is more than Available Quantity";
	}
	else
	{
		$scope.FillerErr = "";
	}
}
$scope.ChkFabric = function(TotalFabric,fabricqty)
{
	if(parseFloat(fabricqty)>parseFloat(TotalFabric))
	{
		$scope.FabricErr = "Qty is more than Available Quantity";
	}
	else
	{
		$scope.FabricErr = "";
	}

}

	$scope.AddCategoryData = function ()
	{
		$scope.submitted = true;
		//var config = { params: { AddCategory: AddCategory } };
		//$scope.AddCategory.supervisor: [];
		// var fd = new FormData();
  //         var file = $scope.docfile;
  //        fd.append('file', file);	

 //        var EntryPkId=new Array();
 //        var RawProductId=new Array();
 //        var rawproduct = new Array();
 //        var AvlQty = new Array();
 //        var rawweight = new Array();


	// for(i=0;i<$scope.BatchArr.length;i++)
	//     {
	//     EntryPkId.push($scope.BatchArr[i].EntryPkId);	
	// 	 RawProductId.push($scope.BatchArr[i].RawProductId);
	// 	 rawproduct.push($scope.BatchArr[i].rawproduct);
	// 	 AvlQty.push($scope.BatchArr[i].AvlQty);
	// 	 rawweight.push($scope.BatchArr[i].rawweight);
	// 	}

		
        
		if($scope.FormPkId==undefined)
		{
			$scope.FileErr="";
			$http.post('add-unitproduction-process.php',
			 // fd, {transformRequest: angular.identity,headers: {'Content-Type': undefined},
    //         params: 
            {
				'productionid':$scope.productionid,
				'entrydate':$scope.entrydate,
				'customerid':$scope.customerid,
				'customername':$scope.customername,

				'productid':$scope.productid,
				'productname':$scope.productname,
				'quantity':$scope.quantity,

				'FabricProductId':$scope.FabricProductId,
				'TotalProduction':$scope.TotalProduction,
				'TotalConsumed':$scope.TotalConsumed,
				'TotalFabric':$scope.TotalFabric,
				'fabricqty':$scope.fabricqty,


				'InstantRawProductId':$scope.InstantRawProductId,
				'instantrawproduct':$scope.instantrawproduct,
				'instantrawweight':$scope.instantrawweight,
				'InstantAvlQty':parseFloat($scope.InstantAvlQty),

				'ReducerProductId':$scope.ReducerProductId,
				'reducerrawproduct':$scope.reducerrawproduct,
				'reducerrawweight':$scope.reducerrawweight,
				'ReducerAvlQty':parseFloat($scope.ReducerAvlQty),

				'wastagename':$scope.wastagename,
				'wastageqty':parseFloat($scope.wastageqty),

				// 'MasterProductId':$scope.MasterProductId,
				// 'masterrawproduct':$scope.masterrawproduct,
				// 'masterrawweight':$scope.masterrawweight,
				// 'MasterAvlQty':parseFloat($scope.MasterAvlQty),

				'cnotes':$scope.cnotes,
			//},

			})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.submitted = false;
					$scope.FormValid = true;

					swal({title: "Added",
							     text: "Fabric Production created successfully",
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
			$http.post('update-unitproduction-process.php',
			 // fd, {transformRequest: angular.identity,headers: {'Content-Type': undefined},
    //         params: 
            {
				'FormPkId':$scope.FormPkId,
				'productionid':$scope.productionid,
				'entrydate':$scope.entrydate,
				'customerid':$scope.customerid,
				'oldcustomerid':$scope.oldcustomerid,
				'customername':$scope.customername,

				'productid':$scope.productid,
				'productname':$scope.productname,
				'EditQty':$scope.EditQty,
				'quantity':$scope.quantity,

				'FabricProductId':$scope.FabricProductId,
				'TotalProduction':$scope.TotalProduction,
				'TotalConsumed':$scope.TotalConsumed,
				'TotalFabric':$scope.TotalFabric,
				'FabricEditQty':$scope.FabricEditQty,
				'fabricqty':$scope.fabricqty,

				'InstaRawPkId':$scope.InstaRawPkId,
				'OldInstantRawProductId':$scope.OldInstantRawProductId,
				'InstantRawProductId':$scope.InstantRawProductId,
				'instantrawproduct':$scope.instantrawproduct,
				'Oldinstantrawweight':$scope.Oldinstantrawweight,
				'instantrawweight':$scope.instantrawweight,
				'OldInstantAvlQty':parseFloat($scope.OldInstantAvlQty),
				'InstantAvlQty':parseFloat($scope.InstantAvlQty),

				'ReducerRawPkId':$scope.ReducerRawPkId,
				'OldReducerProductId':$scope.OldReducerProductId,
				'ReducerProductId':$scope.ReducerProductId,
				'reducerrawproduct':$scope.reducerrawproduct,
				'Oldreducerrawweight':$scope.Oldreducerrawweight,
				'reducerrawweight':$scope.reducerrawweight,
				'OldReducerAvlQty':parseFloat($scope.OldReducerAvlQty),
				'ReducerAvlQty':parseFloat($scope.ReducerAvlQty),

				'wastagename':$scope.wastagename,
				'wastageqty':parseFloat($scope.wastageqty),

				'cnotes':$scope.cnotes,
			//},

			})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.submitted = false;
					$scope.FormValid = true;

					swal({title: "Updated",
							     text: "Fabric Production details updated successfully",
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
	}
	

	$scope.EditOrder = function(FormPkId,ProductionId,ProductId_ProductMaster,ProductName,ProductionDate,CustomerId,CompanyName,ConsumedFabricQty,Quantity,Notes,WastageName,WastageQty,data3)
	{
		$scope.FormList = false;
		$scope.FormAdd = true;
		$scope.GetFinshedProducts();
		$scope.GetInstantMaterials();
		$scope.GetReduceMaterials();
		$scope.GetCustomers();
		$scope.pagetitle = "Edit PP Bag Production";
		$scope.FormPkId = FormPkId;

		$scope.productionid = ProductionId;
		$scope.productid = ProductId_ProductMaster;
		$scope.productname = ProductName;
		$scope.entrydate= new Date(ProductionDate);
		$scope.oldcustomerid=CustomerId;
		$scope.GetFabricInfo(CustomerId,$scope.entrydate);
		$scope.customername=CompanyName;
		$scope.fabricqty=ConsumedFabricQty;
		$scope.FabricEditQty=ConsumedFabricQty;

		$scope.quantity=Quantity;
		$scope.EditQty=Quantity;

		$scope.cnotes = Notes;
		$scope.wastagename = WastageName;
		$scope.wastageqty = WastageQty;
		//$scope.QuantityArr = data3;
		angular.forEach(data3, function(value, key) {
		  if(key==0)
		  {
		  	$scope.InstaRawPkId = value.RawPkId;
		  	$scope.OldInstantRawProductId = value.RawProductId;
			$scope.instantrawproduct = value.RawProductName;
			$scope.OldInstantAvlQty = value.RawAvlQty;
			$scope.Oldinstantrawweight = value.DeductQuantity;	
			$scope.instantrawweight = value.DeductQuantity;	
		  }
		  if(key==1)
		  {
		  	$scope.ReducerRawPkId = value.RawPkId;
		  	$scope.OldReducerProductId = value.RawProductId;
			$scope.reducerrawproduct = value.RawProductName;
			$scope.OldReducerAvlQty = value.RawAvlQty;
			$scope.Oldreducerrawweight = value.DeductQuantity;	
			$scope.reducerrawweight = value.DeductQuantity;	
		  }
		});
		//$scope.QuantityArr = data3;
	}



	$scope.GotoList = function()
	{		
		window.location.href = "list-unit-productions.php";
	}

	

	// var temp = '0.00';
 //  $(".decimaltemp").focus(function() {
 //    if (temp == this.value)
 //      // ------^-- empty the field only if the field value is initial  
 //      this.value = '';
 //      // emptying field value
 //  }).blur(function() {
 //    if (this.value.trim() == '')
 //      // -----^-- condition field value is empty
 //      this.value = temp;
 //      // if empty then updating with initial value
 //  }).val(temp);
  //----^-- setting initial value
});


// AddCategory.directive("typeaheadOpenOnFocus", function() {
// return {

// require: "ngModel",
// link: function(scope, element, attr,ngModel) {
// element.bind('focus',function(){
// if (ngModel && !ngModel.$viewValue) {
// var viewValue = ngModel.$viewValue;
// if (ngModel.$viewValue == ' ' || ngModel.$viewValue == "") {
// ngModel.$setViewValue(null);
// }
// ngModel.$setViewValue(' ');
// ngModel.$setViewValue(viewValue || ' ');
// ngModel.$viewValue = "";
// }
// });
// }
// };
// });

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