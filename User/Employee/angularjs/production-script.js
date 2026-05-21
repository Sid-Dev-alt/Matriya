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
	$scope.pagetitle = "List of Productions";

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
	 $scope.printdate=new Date();
	 $scope.laminatedate=new Date();
	 $scope.slitdate=new Date();
	 $scope.pouchdate=new Date();

	 var mindt = new Date();
  mindt.setDate(mindt.getDate() -90);
	$scope.opened = {};
    $scope.opened.opened1 = false;
    $scope.opened.opened2 = false;
    $scope.opened.opened3 = false;
    $scope.opened.opened4 = false;
    $scope.opened.opened5 = false;

    $scope.singleopen = function($event,datepicker) {
      $event.preventDefault();
      $event.stopPropagation();
      $scope.opened[datepicker] = true;
    };
	$scope.assDate = {
        //dateDisabled: disabled,
        formatYear: 'y',
        maxDate: new Date(2050, 5, 22),
       minDate: new Date(2020, 8, 16),
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


    $("#printdate").keypress(function (event) { event.preventDefault(); });
    $("#printdate").keydown(function (event) { event.preventDefault(); });

    $("#laminatedate").keypress(function (event) { event.preventDefault(); });
    $("#laminatedate").keydown(function (event) { event.preventDefault(); });

    $("#slitdate").keypress(function (event) { event.preventDefault(); });
    $("#slitdate").keydown(function (event) { event.preventDefault(); });

    $("#pouchdate").keypress(function (event) { event.preventDefault(); });
    $("#pouchdate").keydown(function (event) { event.preventDefault(); });
  });

$scope.quantity = "0.000";
$scope.polyrawweight = "0.000";
$scope.petweight = "0.000";
$scope.metweight = "0.000";
$scope.ldweight = "0.000";

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
	$scope.GetPolyster();
	$scope.GetPet();
	$scope.GetMet();
	$scope.GetLD();
	$scope.LoadSizes();
	$scope.LoadColour();
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


 $scope.onCustomerSelect = function ($item, $model, $label) {
    $scope.$item = $item;
    $scope.customerid = $item.CustomerId;
    //$scope.paymentterms = $item.PaymentTerms;
    $scope.$model = $model;
    $scope.$label = $label;
};
//$scope.DeliveryArray = ['Transport','Courier','Auto','Rikshaw'];
	$scope.GetListData= function()
	{
		$http.get("load-productions.php")
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

$scope.GetOrderId= function()
	{
		$http.get("generate-production-id.php")
		.then(function successCallback(response)
		{
			var data = response.data;
			$scope.productionid = data;
		});
	}

$scope.ProductArray = [];
	$scope.GetFinshedProducts = function()
	{
        $http.get('get-finished-products.php')
        .then(function successCallback(response)
		{
			var data = response.data;
        	if(data=="NoData")
			{
				$scope.ProductArray = "";
			}
			else
			{
				$scope.ProductArray = data;
			}
			});
	}
	//$scope.productid = "IBP133";
$scope.onProductSelect = function ($item, $model, $label) {
    $scope.$item = $item;
    $scope.productid = $item.ProductId;
    $scope.productname = $item.ProductName;
    $scope.avlstock = $item.quantity;
    $scope.$model = $model;
    $scope.$label = $label;
};

//$scope.PPProductArray = [];
$scope.GetPet = function()
{
    $http.get('get-pet-productid.php')
    .then(function successCallback(response)
	{
		var data = response.data;
    	if(data=="NoData")
		{
			$scope.PetProductId = ""
		}
		else
		{
			$scope.PetProductId = data['ProductId'];
			$scope.PetAvlQty = data['AvlQty'];
		}
		});
}
$scope.GetMet = function()
{
    $http.get('get-met-productid.php')
    .then(function successCallback(response)
	{
		var data = response.data;
    	if(data=="NoData")
		{
			$scope.MetProductId = ""
		}
		else
		{
			$scope.MetProductId = data['ProductId'];
			$scope.MetAvlQty = data['AvlQty'];
		}
		});
}
$scope.GetLD = function()
{
    $http.get('get-LD-productid.php')
    .then(function successCallback(response)
	{
		var data = response.data;
    	if(data=="NoData")
		{
			$scope.LDProductId = ""
		}
		else
		{
			$scope.LDProductId = data['ProductId'];
			$scope.LDAvlQty = data['AvlQty'];
		}
		});
}



$scope.GetPolyster = function()
{
    $http.get('get-polyster-productid.php')
    .then(function successCallback(response)
	{
		var data = response.data;
    	if(data=="NoData")
		{
			$scope.PolysterProductId = ""
		}
		else
		{
			$scope.PolysterProductId = data['ProductId'];
			$scope.PolyAvlQty = data['AvlQty'];
		}
		});
}
// $scope.FillerArray = [];
// 	$scope.GetFillerMaterials = function()
// 	{
//         $http.get('get-filler-products.php')
//         .then(function successCallback(response)
// 		{
// 			var data = response.data;
//         	if(data=="NoData")
// 			{
// 				$scope.FillerArray = "";
// 			}
// 			else
// 			{
// 				$scope.FillerArray = data;
// 			}
// 			});
// 	}
// $scope.onFillerSelect = function ($item, $model, $label) {
//     $scope.$item = $item;
//     $scope.FillerProductId = $item.ProductId;
//     $scope.fillerrawproduct = $item.ProductName;
//     $scope.FillerAvlQty = $item.AvlQuantity;
//     $scope.FillerSKU = $item.SKU;
//     $scope.$model = $model;
//     $scope.$label = $label;
// };

// $scope.MasterArray = [];
// 	$scope.GetMasterMaterials = function()
// 	{
//         $http.get('get-masterbatch-products.php')
//         .then(function successCallback(response)
// 		{
// 			var data = response.data;
//         	if(data=="NoData")
// 			{
// 				$scope.MasterArray = "";
// 			}
// 			else
// 			{
// 				$scope.MasterArray = data;
// 			}
// 			});
// 	}
// $scope.onMasterSelect = function ($item, $model, $label) {
// 	//alert($item.ProductId);
//     $scope.$item = $item;
//     $scope.MasterProductId = $item.ProductId;
//     $scope.masterrawproduct = $item.ProductName;
//     $scope.MasterAvlQty = $item.AvlQuantity;
//     $scope.MasterSKU = $item.SKU;
//     $scope.$model = $model;
//     $scope.$label = $label;
// };

	$scope.EditOrder = function(FormPkId,ProductionId,ProductId_ProductMaster,JobName,ProductionDate,Quantity,RawConstruction,Size,Colour,Type,IsPrinting,IsLamination,IsSlitting,IsPouching,PrintDate,PolysterProductId,PolyAvlQty,PolyQuantity,Waste,JobStartTime,PrintOutput,Remarks,SlittingDate,CuttingSize,NoOfRolls,SlitOutput,SlitTiming,SlitLabWaste,LaminateDate,PetAvlQty,PetQuantity,PetWaste,MetAvlQty,MetQuantity,MetWaste,LDAvlQty,LDQuantity,LDWaste,FirstOutput,FinalOutput,Gum,Hardner,laminationwaste,PouchDate,PouchType,PouchSize,PouchInput,PouchQty,PouchOutput,PouchBags,PouchStarttime,PouchEndtime,PouchWastage)
	{		

		$scope.FormList = false;
		$scope.FormAdd = true;
		$scope.pagetitle = "Edit Production";
		$scope.GetFinshedProducts();
		$scope.GetPolyster();
		$scope.GetPet();
		$scope.GetMet();
		$scope.GetLD();
		$scope.LoadSizes();
		$scope.LoadColour();
		$scope.FormPkId=FormPkId;
		$scope.productionid=ProductionId;
		$scope.productid=ProductId_ProductMaster;
		$scope.productname=JobName;
		$scope.entrydate=new Date(ProductionDate);
		$scope.quantity=Quantity;
		$scope.rawconstruction=RawConstruction;
		$scope.productsize=Size;
		$scope.colour=Colour;
		$scope.productiontype=Type;
		
		//$scope.printing=IsPrinting;
		if(IsPrinting=='1'){$scope.printing = true;}
		else{$scope.printing = false;}

		if(IsLamination=='1'){$scope.lamination = true;}
		else{$scope.lamination = false;}

		if(IsSlitting=='1'){$scope.slitting = true;}
		else{$scope.slitting = false;}

		if(IsPouching=='1'){$scope.pouching = true;}
		else{$scope.pouching = false;}

		$scope.printdate=new Date(PrintDate);

		$scope.PolysterProductId=PolysterProductId;
		//$scope.Oldpolyrawweight=PolyQuantity;
		//alert(PolyQuantity);
		$scope.PolyAvlQty=PolyAvlQty;
		$scope.polyrawweight=PolyQuantity;

		
		$scope.printwaste=Waste;
		$scope.jobstarttime=JobStartTime;
		$scope.printoutput=PrintOutput;
		$scope.printremarks=Remarks;

		$scope.slitdate=new Date(SlittingDate);
		$scope.cutsize=CuttingSize;
		$scope.noofroles=NoOfRolls;
		$scope.slitoutput=SlitOutput;
		$scope.slittime=SlitTiming;
		$scope.labwaste=SlitLabWaste;

		$scope.laminatedate=new Date(LaminateDate);
		$scope.PetAvlQty=PetAvlQty;
		$scope.petweight=PetQuantity;
		$scope.MetAvlQty=MetAvlQty;
		$scope.metweight=MetQuantity
		
		$scope.LDAvlQty=LDAvlQty;
		$scope.ldweight=LDQuantity;
		$scope.ldwaste=LDWaste;
		$scope.firstoutput=FirstOutput;
		$scope.finalop=FinalOutput;
		$scope.gum=Gum;
		$scope.hardner=Hardner;
		$scope.laminationwaste=laminationwaste;

		$scope.pouchdate=new Date(PouchDate);
		$scope.pouchtype=PouchType;
		$scope.pouchsize=PouchSize;
		$scope.pocuhinput=PouchInput;
		$scope.pouchqty=PouchQty;
		$scope.pouchoutput=PouchOutput;
		$scope.pouchbags=PouchBags;
		$scope.pouchstarttime=PouchStarttime;
		$scope.pouchendtime=PouchEndtime;
		$scope.pouchwastage=PouchWastage;		
	}

$scope.ChkPoly = function(polyrawweight,PolyAvlQty)
{
	if(parseFloat(polyrawweight)>parseFloat(PolyAvlQty))
	{
		$scope.PPErr = "Qty is more than Available Quantity";
	}
	else
	{
		$scope.PPErr = "";
	}
}
$scope.ChkPet = function(petweight,PetAvlQty)
{
	if(parseFloat(petweight)>parseFloat(PetAvlQty))
	{
		$scope.PetErr = "Qty is more than Available Quantity";
	}
	else
	{
		$scope.PetErr = "";
	}
}
$scope.ChkMet = function(metweight,MetAvlQty)
{
	if(parseFloat(metweight)>parseFloat(MetAvlQty))
	{
		$scope.MetErr = "Qty is more than Available Quantity";
	}
	else
	{
		$scope.MetErr = "";
	}
}

$scope.ChkLD = function(ldweight,LDAvlQty)
{
	if(parseFloat(ldweight)>parseFloat(LDAvlQty))
	{
		$scope.LDErr = "Qty is more than Available Quantity";
	}
	else
	{
		$scope.LDErr = "";
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
			$http.post('add-production-process.php',
			 // fd, {transformRequest: angular.identity,headers: {'Content-Type': undefined},
    //         params:
            {
				'productionid':$scope.productionid,
				'entrydate':$scope.entrydate,
				//'customerid':$scope.customerid,
				'productid':$scope.productid,
				'productname':$scope.productname,
				'rawconstruction':$scope.rawconstruction,
				'productsize':$scope.productsize,
				'colour':$scope.colour,
				'productiontype':$scope.productiontype,
				'quantity':$scope.quantity,

				'printing':$scope.printing,
				'lamination':$scope.lamination,
				'slitting':$scope.slitting,
				'pouching':$scope.pouching,

				'printdate':$scope.printdate,
				'PolysterProductId':$scope.PolysterProductId,
				'polyrawweight':$scope.polyrawweight,
				'PolyAvlQty':$scope.PolyAvlQty,
				'Polyblc':$scope.PolyAvlQty-$scope.polyrawweight,
				'printwaste':$scope.printwaste,
				'jobstarttime':$scope.jobstarttime,
				'printoutput':$scope.printoutput,
				'printremarks':$scope.printremarks,

				'laminatedate':$scope.laminatedate,
				'PetProductId':$scope.PetProductId,
				'petweight':$scope.petweight,
				'PetAvlQty':$scope.PetAvlQty,
				'petblc':$scope.PetAvlQty-$scope.petweight,

				'MetProductId':$scope.MetProductId,
				'metweight':$scope.metweight,
				'MetAvlQty':$scope.MetAvlQty,
				'metblc':$scope.MetAvlQty-$scope.metweight,

				'LDProductId':$scope.LDProductId,
				'ldweight':$scope.ldweight,
				'LDAvlQty':$scope.LDAvlQty,
				'LDblc':$scope.LDAvlQty-$scope.ldweight,
				'ldwaste':$scope.ldwaste,

				'firstoutput':$scope.firstoutput,
				'finalop':$scope.finalop,
				'gum':$scope.gum,
				'hardner':$scope.hardner,
				'laminationwaste':$scope.laminationwaste,
				'slitdate':$scope.slitdate,
				'cutsize':$scope.cutsize,
				'noofroles':$scope.noofroles,
				'slitoutput':$scope.slitoutput,
				'slittime':$scope.slittime,
				'labwaste':$scope.labwaste,

				'pouchdate':$scope.pouchdate,
				'pouchtype':$scope.pouchtype,
				'pouchsize':$scope.pouchsize,
				'pocuhinput':$scope.pocuhinput,
				'pouchqty':$scope.pouchqty,
				'pouchoutput':$scope.pouchoutput,
				'pouchbags':$scope.pouchbags,
				'pouchstarttime':$scope.pouchstarttime,
				'pouchendtime':$scope.pouchendtime,
				'pouchwastage':$scope.pouchwastage,
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
							     text: "Production created successfully",
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
			$http.post('update-production-process.php',
			 // fd, {transformRequest: angular.identity,headers: {'Content-Type': undefined},
    //         params:
            {
				'FormPkId':$scope.FormPkId,
				'productionid':$scope.productionid,
				'entrydate':$scope.entrydate,
				'oldcustomerid':$scope.oldcustomerid,
				'customerid':$scope.customerid,

				'productid':$scope.productid,
				'productname':$scope.productname,
				'quantity':$scope.quantity,
				'EditQty':$scope.EditQty,

				'PPRawPkId':$scope.PPRawPkId,
				'OldPPRawProductId':$scope.OldPPRawProductId,
				'PPRawProductId':$scope.PPRawProductId,
				'pprawproduct':$scope.pprawproduct,
				'pprawweight':$scope.pprawweight,
				'Oldpprawweight':$scope.Oldpprawweight,
				'OldPPAvlQty':parseFloat($scope.OldPPAvlQty),
				'PPAvlQty':parseFloat($scope.PPAvlQty),

				'FillerRawPkId':$scope.FillerRawPkId,
				'OldFillerProductId':$scope.OldFillerProductId,
				'FillerProductId':$scope.FillerProductId,
				'fillerrawproduct':$scope.fillerrawproduct,
				'fillerrawweight':$scope.fillerrawweight,
				'Oldfillerrawweight':$scope.Oldfillerrawweight,
				'OldFillerAvlQty':parseFloat($scope.OldFillerAvlQty),
				'FillerAvlQty':parseFloat($scope.FillerAvlQty),

				'MasterRawPkId':$scope.MasterRawPkId,
				'OldMasterProductId':$scope.OldMasterProductId,
				'MasterProductId':$scope.MasterProductId,
				'masterrawproduct':$scope.masterrawproduct,
				'masterrawweight':$scope.masterrawweight,
				'Oldmasterrawweight':$scope.Oldmasterrawweight,
				'OldMasterAvlQty':parseFloat($scope.OldMasterAvlQty),
				'MasterAvlQty':parseFloat($scope.MasterAvlQty),

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
							     text: "Production details updated successfully",
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

	$scope.GotoList = function()
	{
		window.location.href = "list-productions.php";
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
