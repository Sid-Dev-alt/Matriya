var AddCategory = angular.module("CategoryModule", ['ui.bootstrap','angularUtils.directives.dirPagination','datatables','ui.select', 'ngSanitize'])

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
	$scope.pagetitle = "List of Laminations";

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
        maxDate: new Date(),
       minDate: new Date(),
       showWeeks:false,
        startingDay: 1
    };
	$scope.assDate1 = {
        //dateDisabled: disabled,
        formatYear: 'y',
      maxDate: new Date(),
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

//$scope.quantity = "0.000";
//$scope.polyrawweight = "0.000";
$scope.plylevel="Ply2";
$scope.consumeqty = "0.000";
// $scope.metweight = "0.000";
// $scope.ldweight = "0.000";

/*Add & Remove Script Starts Here*/
//to remove the row
$scope.removeRow1 = function (idx)
{
	$scope.BatchArr.splice(idx, 1);
};

$scope.BatchArr = [{subquantity:"0.000"}];

    $scope.AddMore1 = function()
    {
      $scope.BatchArr.push({subquantity:"0.000"});
    }

/*Add & Remove Script end Here*/


/*Add & Remove Script Starts Here*/
//to remove the row
$scope.removeRow = function (idx)
{
	$scope.Lvl1BatchArr.splice(idx, 1);
};

$scope.Lvl1BatchArr = [{lvl1subquantity:"0.000"}];

$scope.AddMore = function()
{
  $scope.Lvl1BatchArr.push({lvl1subquantity:"0.000"});
}
/*Add & Remove Script end Here*/

$scope.GotoAdd = function()
{
	$scope.pagetitle = "Add New";
	$scope.FormAdd = true;
	$scope.FormList = false;
	$scope.GetOrderId();
	$scope.GetCategories();
	$scope.GetRawCategories();
	$scope.GetJobs();
	//$scope.GetPet();
	var todt = new Date();
	var cdt = todt.getDate();
	var cmonth = todt.getMonth()+1;
	$scope.dtmonth = cdt+'|'+cmonth;

}
$scope.GetListData= function()
	{
		$http.get("load-laminations.php")
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
		$http.get("generate-production-id.php")
		.then(function successCallback(response)
		{
			var data = response.data;
			$scope.productionid = data;
		});
	}

$scope.GetBlc = function(rollavlqty,consumeqty){
$scope.balance = $scope.rollavlqty-$scope.consumeqty || "0.000";
}
$scope.GetCategories = function()
	{
        $http.get('load-category.php')
        .then(function successCallback(response)
		{
			var data = response.data;
        	if(data=="NoData")
			{
				$scope.CategoryArray = "";
			}
			else
			{
				$scope.CategoryArray = data;
			}
			});
	}

	$scope.RawCategoryArray=[];
	$scope.GetRawCategories = function()
	{
        $http.get('load-raw-category.php')
        .then(function successCallback(response)
		{
			var data = response.data;
        	if(data=="NoData")
			{
				$scope.RawCategoryArray = "";
			}
			else
			{
				$scope.RawCategoryArray = data;
			}
			});
	}
	
$scope.GetJobs = function()
	{
        $http.get('load-jobs.php')
        .then(function successCallback(response)
		{
			var data = response.data;
        	if(data=="NoData")
			{
				$scope.JobArray = "";
			}
			else
			{
				$scope.JobArray = data;
			}
			});
	}

	$scope.onJobSelect = function ($item, $model, $label) {
    $scope.$item = $item;
    $scope.jobid = $item.PkId;
    $scope.jobname = $item.JobName;
    $scope.$model = $model;
    $scope.$label = $label;
};

/////////////levle1 start//////////
$scope.Lvlsub1rawcategory = [];
$scope.Lvl1sub1rollno = [];

$scope.Lvl1sub2rawcategory=[];
$scope.Lvl1sub2rollno=[];

$scope.Lvl2sub2rawcategory = [];
$scope.Lvl2sub2rollno=[];

$scope.Lvl2sub1rawcategory = [];

$scope.Lvl1Sub1ProductArray = [];
$scope.Lvl1sub1productsize = [];
$scope.Lvl1sub2productsize = [];
$scope.Lvl2sub1productsize = [];
$scope.Lvl2sub2productsize = [];
	$scope.GetLvl1Sub1Products = function(jobname,Lvlsub1rawcategory)
	{
		//$scope.Lvl1sub1productid="";
		$scope.Lvl1sub1productsize="";
		$scope.Lvl1sub1rollno="";
		$scope.Lvl1sub1rollavlqty="";
        $http.post('get-print-lamiation-category-products.php',{'JobPkId': jobname.PkId,'CatPkId': Lvlsub1rawcategory.PkId})
        .then(function successCallback(response)
		{
			var data = response.data;
        	if(data=="NoData")
			{
				//$scope.Lvl1sub1productid="";
				$scope.Lvl1sub1productsize="";
				$scope.Lvl1sub1rollno="";
				$scope.Lvl1sub1rollavlqty="";

				$scope.Lvl1Sub1ProductArray = "";
				$scope.Lvl1sub1No = true;
			}
			else
			{
				$scope.Lvl1Sub1ProductArray = data;
				$scope.Lvl1sub1No = false;
				// $scope.Lvl1sub1productid = data['ProductId'];
				// $scope.Lvl1sub1productsize = data['ProductName'];
				$scope.PrintOutputArr = data[0]['data2'];
				
			}
			});
	}
// $scope.onProductLvl1Sub1 = function ($item, $model, $label) {
//     $scope.$item = $item;
//     $scope.Lvl1sub1productid = $item.ProductId;
//     $scope.Lvl1sub1productsize = $item.ProductName;
//     $scope.$model = $model;
//     $scope.$label = $label;
//     //$scope.Lvl1Sub1RollArr = $item.data2;
//     $scope.GetLvl1Sub1Rolls($item.ProductId,$item.ProductId);
// };
$scope.GetLvl1Sub1Rolls = function(Lvl1sub1productsize)
{
    $http.post('get-raw-rolls.php',{'ProductId': Lvl1sub1productsize.ProductId})
    .then(function successCallback(response)
	{
		var data = response.data;
		if(data=="NoData")
		{

			$scope.Lvl1Sub1RollArr = "";
		}
		else
		{
			$scope.Lvl1Sub1RollArr = data;
		}
		
	});
}
$scope.GetAvlPrintOP = function(Lvl1sub1rollno)
{
	$scope.Lvl1sub1rollavlqty = Lvl1sub1rollno.AvlBlc;
	// $http.post('get-print-avl-qty.php',{'printpkid': Lvl1sub1rollno.})
 //    .then(function successCallback(response)
	// {
	// 	var data = response.data;
	// 	$scope.Lvl1sub1rollavlqty = data['AvlQty'];
	// });	
}
$scope.GetLvl1Sub1RollQty = function(Lvl1sub1rollno)
{
		$scope.Lvl1sub1rollavlqty = Lvl1sub1rollno.RollAvlQty;
	// //alert();
 //    $http.post('get-roll-qty.php',{'rollno': Lvl1sub1rollno})
 //    .then(function successCallback(response)
	// {
	// 	var data = response.data;
	// 	$scope.Lvl1sub1rollavlqty = data['AvlQty'];
	// });
}
$scope.ChkLvl1Qty = function(Lvl1sub1rollavlqty,Lvl1sub1consumeqty)
{
	if(parseFloat(Lvl1sub1consumeqty)>parseFloat(Lvl1sub1rollavlqty))
	{
		$scope.Lvl1Sub1Err = "Qty is more than Available Quantity";
	}
	else
	{
		$scope.Lvl1Sub1Err = "";
	}
}
$scope.Lvl1Sub2ProductArray = [];
$scope.GetLvl1Sub2Products = function(Lvl1sub2rawcategory)
{
	//$scope.Lvl1sub12productid="";
	$scope.Lvl1sub2productsize="";
	$scope.Lvl1sub2rollno="";
	$scope.Lvl1sub2rollavlqty="";
    $http.post('get-category-products.php',{'CatPkId': Lvl1sub2rawcategory.PkId})
    .then(function successCallback(response)
	{
		var data = response.data;
    	if(data=="NoData")
		{
			//$scope.Lvl1sub2productid="";
			$scope.Lvl1sub2productsize="";
			$scope.Lvl1sub2rollno="";
			$scope.Lvl1sub2rollavlqty="";
			$scope.Lvl1Sub2ProductArray = "";
			$scope.Lvl1sub2No = true;
		}
		else
		{
			$scope.Lvl1Sub2ProductArray = data;
			$scope.Lvl1sub2No = false;
		}
		});
}
// 	$scope.onProductLvl1Sub2 = function ($item, $model, $label) {
//     $scope.$item = $item;
//     $scope.Lvl1sub2productid = $item.ProductId;
//     $scope.Lvl1sub2productsize = $item.ProductName;
//     $scope.$model = $model;
//     $scope.$label = $label;
//     $scope.GetLvl1Sub2Rolls($item.ProductId);
//     //$scope.Lvl1Sub2RollArr = $item.data2;
// };
$scope.GetLvl1Sub2Rolls = function(Lvl1sub2productsize)
{
	//alert();
    $http.post('get-raw-rolls.php',{'ProductId': Lvl1sub2productsize.ProductId})
    .then(function successCallback(response)
	{
		var data = response.data;
		if(data=="NoData")
		{

			$scope.Lvl1Sub2RollArr = "";
		}
		else
		{
			$scope.Lvl1Sub2RollArr = data;
		}
		
	});
}
$scope.GetLvl1Sub2RollQty = function(Lvl1sub2rollno)
{
	$scope.Lvl1sub2rollavlqty = Lvl1sub2rollno.RollAvlQty;
	//alert();
 //    $http.post('get-roll-qty.php',{'rollno': Lvl1sub2rollno})
 //    .then(function successCallback(response)
	// {
	// 	var data = response.data;
	// 	$scope.Lvl1sub2rollavlqty = data['AvlQty'];
	// });
}
$scope.ChkLvl1Qty2 = function(Lvl1sub2rollavlqty,Lvl1sub2consumeqty)
{
	if(parseFloat(Lvl1sub2consumeqty)>parseFloat(Lvl1sub2rollavlqty))
	{
		$scope.Lvl1Sub2Err = "Qty is more than Available Quantity";
	}
	else
	{
		$scope.Lvl1Sub2Err = "";
	}
}
/////////////levle1 end//////////
/////////////levle2 start//////////

$scope.Lvl2Sub2ProductArray = [];
$scope.GetLvl2Sub2Products = function(Lvl2sub2rawcategory)
	{
		$scope.Lvl2sub2productid="";
		$scope.Lvl2sub2productsize="";
		$scope.Lvl2sub2rollno="";
		$scope.Lvl2sub2rollavlqty="";
        $http.post('get-category-products.php',{'CatPkId': Lvl2sub2rawcategory.PkId})
        .then(function successCallback(response)
		{
			var data = response.data;
        	if(data=="NoData")
			{
				$scope.Lvl2sub2productid="";
				$scope.Lvl2sub2productsize="";
				$scope.Lvl2sub2rollno="";
				$scope.Lvl2sub2rollavlqty="";
				$scope.Lvl2Sub2ProductArray = "";
				$scope.Lvl2sub2No = true;
			}
			else
			{
				$scope.Lvl2Sub2ProductArray = data;
				$scope.Lvl2sub2No = true;
			}
			});
	}
	// $scope.onProductLvl2Sub2 = function ($item, $model, $label) {
 //    $scope.$item = $item;
 //    $scope.Lvl2sub2productid = $item.ProductId;
 //    $scope.Lvl2sub2productsize = $item.ProductName;
 //    $scope.$model = $model;
 //    $scope.$label = $label;
 //    $scope.GetLvl2Sub2Rolls($item.ProductId);
    //$scope.Lvl2Sub2RollArr = $item.data2;
//};
$scope.GetLvl2Sub2Rolls = function(Lvl2sub2productsize)
{
	//alert();
    $http.post('get-raw-rolls.php',{'ProductId': Lvl2sub2productsize.ProductId})
    .then(function successCallback(response)
	{
		var data = response.data;
		if(data=="NoData")
		{
			$scope.Lvl2Sub2RollArr = "";
		}
		else
		{
			$scope.Lvl2Sub2RollArr = data;
		}
	});
}
$scope.GetLvl2Sub2RollQty = function(Lvl2sub2rollno)
{
	//alert();
	$scope.Lvl2sub2rollavlqty = Lvl2sub2rollno.RollAvlQty;
 //    $http.post('get-roll-qty.php',{'rollno': Lvl2sub2rollno})
 //    .then(function successCallback(response)
	// {
	// 	var data = response.data;
	// 	$scope.Lvl2sub2rollavlqty = data['AvlQty'];
	// });
}
$scope.ChkLvl2Qty2 = function(Lvl2sub2rollavlqty,Lvl2sub2consumeqty)
{
	if(parseFloat(Lvl2sub2consumeqty)>parseFloat(Lvl2sub2rollavlqty))
	{
		$scope.Lvl2Sub2Err = "Qty is more than Available Quantity";
	}
	else
	{
		$scope.Lvl2Sub2Err = "";
	}
}
/////////////levle2 end//////////
//$scope.DeliveryArray = ['Transport','Courier','Auto','Rikshaw'];
	

	$scope.EditOrder = function(FormPkId,ProductionId,ProductId_ProductMaster,JobName,ProductionDate,Quantity,RawConstruction,Size,Colour,Type,IsPrinting,IsLamination,IsSlitting,IsPouching,PrintDate,PolysterProductId,PolyAvlQty,PolyQuantity,Waste,JobStartTime,PrintOutput,Remarks,SlittingDate,CuttingSize,NoOfRolls,SlitOutput,SlitTiming,SlitLabWaste,LaminateDate,PetAvlQty,PetQuantity,PetWaste,MetAvlQty,MetQuantity,MetWaste,LDAvlQty,LDQuantity,LDWaste,FirstOutput,FinalOutput,Gum,Hardner,laminationwaste,PouchDate,PouchType,PouchSize,PouchInput,PouchQty,PouchOutput,PouchBags,PouchStarttime,PouchEndtime,PouchWastage)
	{		

		$scope.FormList = false;
		$scope.FormAdd = true;
		$scope.pagetitle = "Edit Printing";
		// $scope.LoadSizes();
		// $scope.LoadColour();
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
	}

	$scope.AddCategoryData = function ()
	{
		$scope.submitted = true;

		var EntryPkId= new Array();
		var subquantity= new Array();

		var Lvl1EntryPkId = new Array();
		var lvl1subquantity = new Array();

	for(i=0;i<$scope.BatchArr.length;i++)
	    {
	    	EntryPkId.push($scope.BatchArr[i].EntryPkId);	
		 	subquantity.push($scope.BatchArr[i].subquantity);
		}
		for(i=0;i<$scope.Lvl1BatchArr.length;i++)
	    {
	    	Lvl1EntryPkId.push($scope.Lvl1BatchArr[i].Lvl1EntryPkId);	
		 	lvl1subquantity.push($scope.Lvl1BatchArr[i].lvl1subquantity);
		}
		if($scope.FormPkId==undefined)
		{
			$scope.FileErr="";
			$http.post('add-lamination-process.php',
			 // fd, {transformRequest: angular.identity,headers: {'Content-Type': undefined},
    //         params:
            {
				//'productionid':$scope.productionid,
				'entrydate':$scope.entrydate,
				'jobid':$scope.jobname.PkId,
				//'jobname':$scope.jobname,
				'plylevel':$scope.plylevel,
				'LvlMachineType':$scope.LvlMachineType,
				'Lvl1gum':$scope.Lvl1gum,
				'Lvl1hardner':$scope.Lvl1hardner,
				'Lvl1EAsolvent':$scope.Lvl1EAsolvent,
				'Lvl1OHslvnt':$scope.Lvl1OHslvnt,
				'Lvl1NCOslvnt':$scope.Lvl1NCOslvnt,
				'Lvlsub1rawcategory':$scope.Lvlsub1rawcategory.PkId,
				'Lvl1sub1productid':$scope.Lvl1sub1productsize.ProductId,
				'Lvl1sub1productsize':$scope.Lvl1sub1productsize.ProductName,

				'Lvl1sub1rollno':$scope.Lvl1sub1rollno.PkId,
				'Lvl1sub1rollavlqty':$scope.Lvl1sub1rollavlqty,

				'Lvl1sub1consumeqty':$scope.Lvl1sub1consumeqty,
				'Lvl1sub1wastage':$scope.Lvl1sub1wastage,

				'Lvl1sub1consumeqty':$scope.Lvl1sub1consumeqty,
				'Lvl1sub1wastage':$scope.Lvl1sub1wastage,

				'Lvl1sub2rawcategory':$scope.Lvl1sub2rawcategory.PkId,
				'Lvl1sub2productid':$scope.Lvl1sub2productsize.ProductId,
				'Lvl1sub2productsize':$scope.Lvl1sub2productsize.ProductName,

				'Lvl1sub2rollno':$scope.Lvl1sub2rollno.PkId,
				'Lvl1sub2rollavlqty':$scope.Lvl1sub2rollavlqty,
				'Lvl1sub2consumeqty':$scope.Lvl1sub2consumeqty,
				'Lvl1sub2wastage':$scope.Lvl1sub2wastage,
				'level1output':$scope.level1output,
				'lvl1noofrolls':$scope.lvl1noofrolls,
				'Lvl1IsSelectRollCheck':$scope.Lvl1IsSelectRollCheck,
				'lvl1subquantity':lvl1subquantity,
				
				'petmetoutput':$scope.petmetoutput,

				'Lvl2MachineType':$scope.Lvl2MachineType,
				'Lvl2gum':$scope.Lvl2gum,
				'Lvl2hardner':$scope.Lvl2hardner,
				'Lvl2EAsolvent':$scope.Lvl2EAsolvent,
				'Lvl2OHslvnt':$scope.Lvl2OHslvnt,
				'Lvl2NCOslvnt':$scope.Lvl2NCOslvnt,
				'Lvl2sub1rawcategory':$scope.Lvl2sub1rawcategory.PkId,
				// 'Lvl1sub1productid':$scope.Lvl1sub1productid,
				// 'Lvl1sub1productsize':$scope.Lvl1sub1productsize,
				// 'Lvl1sub1rollno':$scope.Lvl1sub1rollno,
				// 'Lvl1sub1rollavlqty':$scope.Lvl1sub1rollavlqty,
				// 'Lvl1sub1consumeqty':$scope.Lvl1sub1consumeqty,
				'Lvl2sub1wastage':$scope.Lvl2sub1wastage,

				'Lvl2sub2rawcategory':$scope.Lvl2sub2rawcategory.PkId,
				'Lvl2sub2productid':$scope.Lvl2sub2productsize.ProductId,
				'Lvl2sub2productsize':$scope.Lvl2sub2productsize.ProductName,
				'Lvl2sub2rollno':$scope.Lvl2sub2rollno.PkId,
				'Lvl2sub2rollavlqty':$scope.Lvl2sub2rollavlqty,
				'Lvl2sub2consumeqty':$scope.Lvl2sub2consumeqty,
				'Lvl2sub2wastage':$scope.Lvl2sub2wastage,
				'finaloutput':$scope.finaloutput,

				'noofrolls':$scope.noofrolls,
				'IsSelectRollCheck':$scope.IsSelectRollCheck,
				'subquantity':subquantity,
				//'printremarks':$scope.printremarks,
				
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
							     text: "Lamination created successfully",
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
		window.location.href = "lamination.php";
	}


//JOb script start
$scope.AddJobData = function()
{
	$http.post('add-job-process.php',
			 // fd, {transformRequest: angular.identity,headers: {'Content-Type': undefined},
    //         params:
            {
            	'newjobname':$scope.newjobname,
			//},

			})
			.then(function successCallback(response)
			{
					var data = response.data;
				if(data=="Success")
				{
					window.scrollTo(500, 0);
					$scope.submitted = false;
					$scope.FormValid = true;
					$scope.jobid="";
					$scope.jobname="";
					$scope.GotoAdd();
					 swal({title: "success!",
					     text: "Job Created successfully.",
					     type: "success",
					     timer: 2000});
					$('#modal-form').modal('hide');
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
//JOb script end

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
