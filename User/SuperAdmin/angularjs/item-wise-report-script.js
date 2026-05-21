var AddCategory = angular.module("ReportModule", ['ui.bootstrap','angularUtils.directives.dirPagination','datatables','ui.select', 'ngSanitize'])
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

AddCategory.controller("ReportController", function ($scope, $timeout, $http, jsonFilter, DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder)
{

	$scope.FormAdd = false;	
	$scope.FormList = true;	
	$scope.pagetitle = "Reports";
	
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


	$scope.fromdate=new Date();
	$scope.todate=new Date();
	$scope.opened = {};
    // $scope.opened.opened2 = false;
    // $scope.opened.opened3 = false;


    //$scope.opened.bat = false;
  // $scope.open = function($event, bat) {
  //   $event.preventDefault();
  //   $event.stopPropagation();
  //   bat.opened = true;
  //   bat.opened1 = true;
  // };

  // $scope.opened1 = {};
  //   $scope.opened1 = false;
  // $scope.open1 = function($event, bat) {
  //   $event.preventDefault();
  //   $event.stopPropagation();
  //   bat.opened1 = true;
  // };
    
    $scope.fromopen = function($event,datepicker) {
      $event.preventDefault();
      $event.stopPropagation();
      $scope.opened[datepicker] = true;
      $scope.opened.opened2 = false;
    };

    $scope.opened2 = {};
    $scope.toopen = function($event,datepicker) {
      $event.preventDefault();
      $event.stopPropagation();
      $scope.opened[datepicker] = true;
      $scope.opened.opened = false;
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

    $("#fromdate").keypress(function (event) { event.preventDefault(); });
    $("#fromdate").keydown(function (event) { event.preventDefault(); });

    $("#todate").keypress(function (event) { event.preventDefault(); });
    $("#todate").keydown(function (event) { event.preventDefault(); });

    $("#expdate").keypress(function (event) { event.preventDefault(); });
    $("#expdate").keydown(function (event) { event.preventDefault(); });
  });

/*Add & Remove Script Starts Here*/
//to remove the row
$scope.removeRow = function (idx) 
{
	$scope.NewBatchArr.splice(idx, 1);
};

$scope.NewBatchArr = [{}];

    $scope.AddMore = function() 
    {
      $scope.NewBatchArr.push({});
    }

/*Add & Remove Script end Here*/

$scope.GotoAdd = function()
{
	$scope.pagetitle = "Add Product";
	$scope.FormAdd = true;	
	$scope.FormList = false;
	$scope.LoadInventory();
	
}
$scope.ReasonArr = [
	'Fresh Stock',
	'Stock on fire',
	'Stolen goods',
	'Damaged goods',
	'Stock written off',
	'Stock taking results',
	'Inventory revalutaion',
];

//$scope.godownname = [];
// $scope.GoDownArray = [];
// $scope.GetGoDowns = function()
// {
//     $http.get('load-godowns.php')
//     .then(function successCallback(response)
// 	{
// 		var data = response.data;
//     	if(data=="NoData")
// 		{
// 			$scope.GoDownArray = "";
// 		}
// 		else
// 		{
// 			$scope.GoDownArray = data;
// 		}
// 		});
// }
$scope.itemnname="";
$scope.ItemArray = [];
$scope.GetItems = function()
{
    $http.get('load-item-groups.php')
    .then(function successCallback(response)
	{
		var data = response.data;
    	if(data=="NoData")
		{
			$scope.ItemArray = "";
		}
		else
		{
			$scope.ItemArray = data;
		}
		});
}

$scope.itemsize="";
$scope.SizeArray = [];
$scope.GetSizes = function(itemnname)
{
	$scope.SizeArray = itemnname.data2;
	// alert();
	// alert(itemnname.Micron);
	// alert(itemnname.ProductName);
 //    $http.post('load-item-sizes.php',{'Micron':itemnname.Micron,'ProductName':itemnname.ProductName})
 //    .then(function successCallback(response)
	// {
	// 	var data = response.data;
 //    	if(data=="NoData")
	// 	{
	// 		$scope.SizeArray = "";
	// 	}
	// 	else
	// 	{
	// 		$scope.SizeArray = data;
	// 	}
	// 	});
}

$scope.loading = false;
$scope.downloadexcel = false;
$scope.pagedItems = [];
$scope.pageno = 1; // initialize page no to 1
$scope.total_count = 0;
$scope.itemsPerPage = 10;
	$scope.GetListData= function(pageno)
	{
        $scope.loading = true;
		$scope.mypageno = pageno;

		$scope.pagedItems = [];
		// if($scope.fromdate > $scope.todate){
	 //    	swal("STOP", 'To Date should be more than From date', "error");
		// 	$timeout(function () { $scope.submitted = false;}, 3000);
	 //    }
	 //    else
	 //    {
			$http.post("load-itemwise-report-data.php", {'itemsPerPage': $scope.itemsPerPage, 'pagenumber': pageno, 'Micron': $scope.itemnname.Micron, 'ProductName': $scope.itemnname.ProductName})
			.then(function successCallback(response)
			{
				var data = response.data; 
				$scope.loading = false;
				if(data=="NoData" || data==""){
					swal({title: "Empty",
							     text: "No Records Found",
							     type: "error",
							     timer: 2000 
						});
			    	$scope.pagedItems = "";
			    }
				else
				{
					$scope.pagedItems = data['data1'];
                    $scope.TotalQty = data['TotalQty'];
					$scope.total_count = data['Total'];
					$scope.downloadexcel = true;
                    $scope.loading = false;
				}
			}, function errorCallback(response) {
	    		// called asynchronously if an error occurs
			    // or server returns response with an error status.
			});
		//}
	}

$scope.adjust = "Add";
$scope.openstock = 0;

	
$scope.GetProductData= function(TotalProductName,ProductSize,UniqueRollNo,Unit)
{
	$scope.TotalProductName=TotalProductName;
	$scope.ProductSize=ProductSize;
	$scope.UniqueRollNo=UniqueRollNo;
	$scope.Unit=Unit;
	$http.post("get-raw-ledger.php",{'UniqueRollNo':UniqueRollNo})
	.then(function successCallback(response)
	{
		var data = response.data;
		$scope.VendorName = data['VendorName'];
		$scope.IsSplitQty = data['IsSplitQty'];
		$scope.AvlQuantity = data['AvlQuantity'];
		$scope.RawPODate = data['RawPODate'];
		$scope.PurchaseRollNo = data['PurchaseRollNo'];
    $scope.PurchaseQty = data['PurchaseQty'];
		$scope.TotalName = data['TotalName'];
		$scope.POProductSize = data['POProductSize'];
		$scope.GoDownName = data['GoDownName'];
		$scope.IsSlitted = data['IsSlitted'];
		$scope.SlitDate = data['SlitDate'];
		$scope.SplitSize = data['SplitSize'];
		$scope.SlitQty = data['SlitQty'];
		$scope.InvoiceDate = data['InvoiceDate'];
		$scope.CustomerName = data['CustomerName'];
	});
}


$scope.calculateSum = function () {
    var sum = 0;
  if ($scope.pagedItems !== undefined) {

    for (var i = 0; i < $scope.pagedItems.length; i++)
    {
      sum += parseFloat($scope.pagedItems[i]["Quantity"]);
    }
  }

    $scope.sum=sum;
    return sum;
  }
	$scope.GotoList = function()
	{		
		window.location.href = "inventory-adjust.php";
	}
});
AddCategory.directive('number', function () {
        return {
            require: 'ngModel',
            restrict: 'A',
            link: function (scope, element, attrs, ctrl) {
            	var temp = '0';
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
