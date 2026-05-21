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

AddCategory.controller("CategoryController", function ($scope, $timeout, $http, jsonFilter, DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder)
{

	$scope.vm = {};
$scope.vm.dtInstance = {};   
$scope.vm.dtColumnDefs = [
//DTColumnDefBuilder.newColumnDef(2).notSortable()
];
$scope.vm.dtOptions = DTOptionsBuilder.newOptions()
          		.withOption('order', [0, 'desc'])
				.withOption('info', false);
				
	$scope.FormAdd = false;	
	$scope.FormList = true;	
	$scope.pagetitle = "List of Inventory Adjusted";
	
	var logResult = function (data, status, headers, config)
	{
		return data;
	};

	$scope.entrydate=new Date();
	$scope.opened = {};
    $scope.opened.opened2 = false;
    $scope.opened.opened3 = false;


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
    $("#mfgdate").keypress(function (event) { event.preventDefault(); });
    $("#mfgdate").keydown(function (event) { event.preventDefault(); });


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
	'Stock on fire',
	'Stolen goods',
	'Damaged goods',
	'Stock written off',
	'Stock taking results',
	'Inventory revalutaion',
];

	$scope.GetListData= function()
	{
		$http.get("load-inventory-adjust-data.php")
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

$scope.InventoryArr = [];
	$scope.LoadInventory = function()
	{
		$http.get('load-inventory-data.php')
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{
				$scope.InventoryArr= '';
			}
			else
			{
				$scope.InventoryArr = data;
			}
		}, function errorCallback(response) {
    		// called asynchronously if an error occurs
		    // or server returns response with an error status.
		  });
	};
$scope.adjust = "Add";

$scope.EditCat = function (data2) {
	$scope.FormAdd = true;	
	$scope.FormList = false;	
	// $scope.FormPkId= PkId;
	// $scope.ProductId= ProductId;
	// $scope.ProductName= ProductName;
	// $scope.TrackingMode = TrackingMode;
	// $scope.AvlQty = AvlQty;
	$scope.data2 = data2;
};

$scope.AdjustArr = ['Add','Minus'];

$scope.ChangeType = function()
{
	$scope.openstock="";
	$scope.freshquantity="";
}
$scope.GetFreshQty = function(AvlQty,openstock,adjust,TrackingMode)
{
 if(TrackingMode=="None" && adjust=="Add")
	{
		$scope.freshquantity = Number(AvlQty)+Number(openstock);	
	}
	else if(TrackingMode=="None"  && adjust=="Minus" && Number(openstock)>Number(AvlQty))
	{
		$scope.openstock = Number(AvlQty);
		$scope.freshquantity = "0";	
	}
	else if(TrackingMode=="None" && adjust=="Minus")
	{
		$scope.freshquantity = Number(AvlQty)-Number(openstock);	
	}
	
}
$scope.calculateSum = function () {
		var sum = 0;
	if ($scope.NewBatchArr !== undefined) {

		for (var i = 0; i < $scope.NewBatchArr.length; i++)
		{
			sum += Number($scope.NewBatchArr[i]["quantity"]);
		}
	}

		$scope.sum=sum;
		return sum;
	}

	$scope.OldcalculateSum = function () {
		var subsum = 0;
	if ($scope.BatchArr !== undefined) {

		for (var i = 0; i < $scope.BatchArr.length; i++)
		{
			subsum += Number($scope.BatchArr[i]["setquantity"]);
		}
	}

		$scope.subsum=subsum || 0;
		return subsum;
	}


$scope.SetMinusQty = function(quantity,setquantity,index)
{
	if(Number(setquantity)>Number(quantity))
	{
		$scope.BatchArr[index].setquantity=Number(quantity);
	}
	else
	{
		$scope.BatchArr[index].newquantity=Number(quantity)-Number(setquantity);	
	}
}
	
	$scope.AddCategoryData = function ()
	{
		$scope.submitted = true;
		//var config = { params: { AddCategory: AddCategory } };
		//$scope.AddCategory.supervisor: [];

        var BatchNo=new Array();
		var ManfcBatch=new Array();
		var Mfgdate=new Array();
		var Expdate=new Array();
		var AddQuantity=new Array();


	for(i=0;i<$scope.NewBatchArr.length;i++)
	    {
		 BatchNo.push($scope.NewBatchArr[i].batchno);
		 ManfcBatch.push($scope.NewBatchArr[i].manfacturebatch);
		 Mfgdate.push($scope.NewBatchArr[i].mfgdate);
		 Expdate.push($scope.NewBatchArr[i].expdate);
		 AddQuantity.push($scope.NewBatchArr[i].addquantity);
		}

		var InvPkId=new Array();
		var quantity=new Array();
		var setquantity=new Array();
		var newquantity = new Array();


	for(i=0;i<$scope.BatchArr.length;i++)
	    {
		 InvPkId.push($scope.BatchArr[i].InvPkId);
		 quantity.push($scope.BatchArr[i].quantity);
		 setquantity.push($scope.BatchArr[i].setquantity);
		 newquantity.push($scope.BatchArr[i].newquantity);
		}

		
			$http.post('inventory-adjust-process.php', {
				
				'ProductId':$scope.ProductId,
				'referencenum':$scope.referencenum,
				'entrydate':$scope.entrydate,
				'reason':$scope.reason,
				'description':$scope.description,
				'adjust':$scope.adjust,
				'AvlQty':$scope.AvlQty,

				'openstock':$scope.openstock,
				'serailnumber':$scope.serailnumber,
				'freshquantity':$scope.freshquantity,

				'invtrack':$scope.TrackingMode,

				'BatchNo':BatchNo,
				'ManfcBatch':ManfcBatch,
				'Mfgdate':Mfgdate,
				'Expdate':Expdate,
				'AddQuantity':AddQuantity,

				'InvPkId':InvPkId,
				'quantity':quantity,
				'setquantity':setquantity,
				'newquantity':newquantity,
				
				'sum':$scope.sum,
				'subsum':$scope.subsum,
			

			})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.submitted = false;
					$scope.FormValid = true;

					swal({title: "Added",
							     text: "updated successfully",
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
		window.location.href = "inventory-adjust.php";
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
