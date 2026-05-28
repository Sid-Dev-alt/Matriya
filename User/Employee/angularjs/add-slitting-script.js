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
	$scope.FormAdd = true;
	$scope.FormList = false;
	$scope.pagetitle = "Add New";

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

//$scope.slitinput = "0.000";
$scope.blcofjob = "0.000";
$scope.finaloutput = "0.000";
$scope.laminationwaste = "0.000";
$scope.trimwaste = "0.000";
$scope.cuttingsize = "0.000";

/*Add & Remove Script Starts Here*/
//to remove the row
$scope.Verions = [{
			'BatchArr':[{splitsize:"0",quantity:"0.000",delflag:"0"},{splitsize:"0",quantity:"0.000",delflag:"0"}]
	}];
$scope.AddCard = function()
{
	$scope.Verions.push({
			'BatchArr':[{splitsize:"0",quantity:"0.000",delflag:"0"},{splitsize:"0",quantity:"0.000",delflag:"0"}]});
}

$scope.DeleteCard = function(index) {
	$scope.Verions.splice(index, 1);
    };

$scope.AddMore = function(version)
{
  version.BatchArr.push({splitsize:"0",quantity:"0.000",delflag:"0"});
}
$scope.removeRow = function (version,index)
{
	version.BatchArr.splice(index, 1);
};

//$scope.BatchArr = [{splitsize:"0",quantity:"0.000",delflag:"0"},{splitsize:"0",quantity:"0.000",delflag:"0"}];

// $scope.AddMore = function(Verion)
// {
//   $scope.BatchArr.push({splitsize:"0",quantity:"0.000",delflag:"0"});
// }

// $scope.removeRow = function (index)
// {
// 	$scope.BatchArr.splice(index, 1);
// };

/*Add & Remove Script end Here*/

var todt = new Date();
var cdt = todt.getDate();
var cmonth = todt.getMonth()+1;
$scope.dtmonth = cdt+'|'+cmonth;

$scope.GotoAdd = function()
{
	$scope.pagetitle = "Add New";
	$scope.FormAdd = true;
	$scope.FormList = false;
	$scope.GetOrderId();
	$scope.GetCategories();
	$scope.GetPurchaseBars();
	//$scope.GetPet();
	
}
$scope.BarArr = [];
$scope.GetPurchaseBars = function()
{
	$http.post("Get-Inventory.php")
    .then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{
				$scope.BarArr="";
				//$scope.duedate = "";
				//swal("STOP", "No Item Found", "error");
				swal({
			    title: "No Item Found!",
			    text: "",
			    timer: 1500,
			    type:"error",
			    showConfirmButton: false
			  });
				//$timeout(function () { $scope.submitted = false;}, 1000);
			}
			else
			{
				$scope.BarArr = data;
				
				// Pre-fill RollId from URL query param if present
				var urlParams = new URLSearchParams(window.location.search);
				var queryRollId = urlParams.get('RollId');
				if (queryRollId) {
					$scope.barcodeInput = queryRollId;
					$scope.GetBar();
				}
			}
            
       }, function errorCallback(response) {
		// called asynchronously if an error occurs
	    // or server returns response with an error status.
	  });
}

$scope.loading = false;
$scope.pagedItems = []; //declare an empty array
    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 5; //this could be a dynamic value from a drop down

$scope.getData = function(pageno){ 
	$scope.loading = true;
      $scope.mypageno = pageno;
    // This would fetch the data on page change.
        //In practice this should be in a factory.
        $scope.pagedItems = [];  
        
        $http.post("load-slitting.php",{'itemsPerPage': $scope.itemsPerPage, 'pagenumber': pageno})
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
        $scope.sortKey1 = keyname;   //set the sortKey to the param passed
        $scope.reverse1 = !$scope.reverse1; //if true make it false and vice versa
    }
	
$scope.GetRollData= function(RollNo)
	{
		//$scope.mypageno = pageno;

		$scope.pagedItems = [];
		// if($scope.fromdate > $scope.todate){
	 //    	swal("STOP", 'To Date should be more than From date', "error");
		// 	$timeout(function () { $scope.submitted = false;}, 3000);
	 //    }
	 //    else
	 //    {
	 	//'itemsPerPage': $scope.itemsPerPage, 'pagenumber': pageno, , 'ProductSize': $scope.itemsize.ProductSize
			$http.post("load-roll-history.php", {'rollno': RollNo})
			.then(function successCallback(response)
			{
				var data = response.data; 
				
				if(data=="NoData" || data==""){
          $scope.result = data;
					swal({title: "Empty",
							     text: "No Records Found",
							     type: "error",
							     timer: 2000 
						});
			    }
				else
				{
				    $("#myModal").modal('show');
          $scope.result = "OK";
				    $scope.VendorNameModal = data['VendorName'];
            $scope.IsSplitQty = data['IsSplitQty'];
            $scope.AvlQuantity = data['AvlQuantity'];
            $scope.InvProductSize = data['InvProductSize'];
            $scope.InvUniqueRollNo = data['InvUniqueRollNo'];
            $scope.RawPODateModal = data['RawPODate'];
            $scope.PurchaseRollNo = data['PurchaseRollNo'];
            $scope.PurchaseQtyModal = data['PurchaseQty'];
            $scope.TotalName = data['TotalName'];
            $scope.UnitModal = data['Unit'];
            $scope.POProductSize = data['POProductSize'];
            $scope.GoDownName = data['GoDownName'];
            $scope.data2 = data['data2'];
            $scope.IsSlitted = data['IsSlitted'];
            $scope.SlitDate = data['SlitDate'];
            $scope.SplitSize = data['SplitSize'];
            $scope.SlitQty = data['SlitQty'];
            $scope.IsInvoice = data['IsInvoice'];
            $scope.InvoiceDateModal = data['InvoiceDate'];
            $scope.CustomerNameModal = data['CustomerName'];
				 }
			}, function errorCallback(response) {
	    		// called asynchronously if an error occurs
			    // or server returns response with an error status.
			});
		//}
	}

$scope.GetOrderId= function()
	{
		$http.get("generate-slitid.php")
		.then(function successCallback(response)
		{
			var data = response.data;
			$scope.slitid = data;
		});
	}

$scope.barcode="";
$scope.GetSelectBar = function()
{
	//alert($scope.barcode.UniqueRollNo);
	$scope.barcodeInput=$scope.barcode.UniqueRollNo; 
	$scope.GetBar();
}

$scope.GetBar = function()
{
	//console.log($scope.barcodeInput);
	$http.post("Get-Invoice-Inventory.php",{'barcodeInput': $scope.barcodeInput})
    .then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{
				$scope.barcodeInput="";
				$scope.barcode = "";
				//$scope.duedate = "";
				//swal("STOP", "No Item Found", "error");
				swal({
			    title: "No Item Found!",
			    text: "",
			    timer: 1500,
			    type:"error",
			    showConfirmButton: false
			  });
				//$timeout(function () { $scope.submitted = false;}, 1000);
			}
			else
			{
				$scope.barcodeInput = "";
				$scope.barcode = "";
				$scope.InvPkId = data['InvPkId'];
				$scope.PkId_RawPurchaseMasterDetails = data['PkId_RawPurchaseMasterDetails'];
				$scope.ProductId = data['ProductId'];
				$scope.ProductName = data['ProductName'];
				$scope.Size = data['Size'];
				$scope.Unit = data['Unit'];
				$scope.Micron = data['Micron'];
				$scope.UniqueRollNo = data['UniqueRollNo'];
				$scope.GoDownName = data['GoDownName'];
				$scope.Avlqty = data['quantity'];
			}
            
       }, function errorCallback(response) {
		// called asynchronously if an error occurs
	    // or server returns response with an error status.
	  });
}


// $scope.BasicSum = function()
// {
// 	var sumbasictotal = 0;

// 	if ($scope.BatchArr !== undefined) {

// 		for (var i = 0; i < $scope.BatchArr.length; i++)
// 		{
// 			sumbasictotal += Math.round(parseFloat($scope.BatchArr[i]["basicamount"]));
// 		}
// 	}
// 	$scope.sumbasictotal=sumbasictotal;
// 		return sumbasictotal;
// }
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

$scope.BasicSum = function()
{
	if ($scope.Verions !== undefined) {
		for (var i = 0; i < $scope.Verions.length; i++)
		{
			var sumbasictotal = 0;
			if ($scope.Verions[i]['BatchArr'] !== undefined) {
			//console.log($scope.Verions[i]['BatchArr']);
				for (var j = 0; j < $scope.Verions[i]['BatchArr'].length; j++)
				{
					sumbasictotal += parseFloat($scope.Verions[i].BatchArr[j]["splitsize"]);
				}
			}
			$scope.Verions[i].sumbasictotal=sumbasictotal;
		}
	}
}

	$scope.EditOrder = function(FormPkId,SlitId,SlitDate,PkId_InventoryMaster,PkId_RawPurchaseMasterDetails,ProductId_ProductMaster,ProductName,ProductSize,Micron,TotalName,Unit,UniqueRollNo,GoDownName,Quantity,data2)
	{		

		$scope.FormList = false;
		$scope.FormAdd = true;
		$scope.pagetitle = "Edit Slitting";
		$scope.GetPurchaseBars();
		
		//$scope.barcode="";
		// $scope.GetSelectBar = function()
		// {
		// 	$scope.barcodeInput=$scope.barcode.UniqueRollNo; 
		// 	$scope.GetBar();
		// }		
		$scope.FormPkId = FormPkId;
		$scope.slitid = SlitId;
		$scope.entrydate = new Date(SlitDate);
		$scope.InvPkId = PkId_InventoryMaster;
		$scope.PkId_RawPurchaseMasterDetails = PkId_RawPurchaseMasterDetails;
		$scope.ProductId = ProductId_ProductMaster;
		$scope.ProductName = ProductName;
		$scope.Size = ProductSize;
		$scope.Micron = Micron;		
		$scope.Unit = Unit;
		$scope.UniqueRollNo = UniqueRollNo;

		$scope.GoDownName = GoDownName;
		$scope.Avlqty = Quantity;

		// $scope.barcode = {ProductId:ProductId_ProductMaster,ProductName:ProductName,Size:ProductSize,Micron:Micron,TotalName:TotalName,UniqueRollNo:UniqueRollNo};
		// //$scope.GetSelectBar();
		// $scope.barcodeInput=$scope.UniqueRollNo; 
		//$scope.GetBar();
		$scope.Verions = data2;
		angular.forEach($scope.Verions, function (value, key) {
			//alert($scope.Verions[key]['data3']);
			$scope.Verions[key].BatchArr = $scope.Verions[key]['data3'];
			 // angular.forEach($scope.Verions[key]['data3'], function (value1, key1) {
			 // 	//alert($scope.Verions[key]['data3'][key1]['SplitSize']);
			 // 	//alert(value1.EntryPkId);
				// 	$scope.Verions[key]['BatchArr'][key].EntryPkId = value1.EntryPkId;
		  //        //	$scope.Verions[key]['BatchArr'][key1].splitsize = value1.SplitSize; 
	   //      	});
	   	 });
	}


	$scope.AddCategoryData = function ()
	{
		$scope.errors = [];

		for (var i = 0; i < $scope.Verions.length; i++)
		{
			var sumbasictotal = 0;
			if ($scope.Verions[i]['BatchArr'] !== undefined) {
			//console.log($scope.Verions[i]['BatchArr']);
				for (var j = 0; j < $scope.Verions[i]['BatchArr'].length; j++)
				{
					sumbasictotal += parseFloat($scope.Verions[i].BatchArr[j]["splitsize"]);
				}
			}
			$scope.Verions[i].sumbasictotal=sumbasictotal;
			if(parseFloat(sumbasictotal)>parseFloat($scope.Size))
			{
				$scope.finalT = parseFloat($scope.Size)+parseFloat(20.000);
				$scope.row = i+1;
				$scope.err = "Set"+$scope.row+ " of Total size is greater than item size\n";
				$scope.errors.push($scope.err);
			}
			if(parseFloat(sumbasictotal)<parseFloat($scope.Size))
			{
	            $scope.finalT = parseFloat($scope.Size)-parseFloat(20.000);
	            $scope.row = i+1;
	            $scope.err = "Set"+$scope.row+ " of Total size is less than item size\n";
	            $scope.errors.push($scope.err);
			}
		}
	 //    if(parseFloat($scope.sumbasictotal)>parseFloat($scope.Size))
		// {
		//   $scope.finalT = parseFloat($scope.Size)+parseFloat(20.000);
		// 	//alert("high");
		// 	 swal({
		//       title: 'Total size is greater than item size',
		//       text: "Are you sure to want to proceed?",
		//       type: "warning",
		//       showCancelButton: true,
		//       confirmButtonClass: "btn-danger",
		//       confirmButtonText: "Yes, Save",
		//       cancelButtonText: "No, cancel!",
		//       closeOnConfirm: false,
		//       closeOnCancel: true,
		//         html: true
		//     },
		//     function(isConfirm) {
		//       if (isConfirm) {
		//       	$scope.proceed();
		//       	 } else {
  //       		//swal("Cancelled", "Your record is safe :)", "error");
		//       	}
		//     });
		// }
		// else if(parseFloat($scope.sumbasictotal)<parseFloat($scope.Size))
		// {
  //           $scope.finalT = parseFloat($scope.Size)-parseFloat(20.000);
		// 	//alert("high");
		// 	 swal({
		//       title: 'Total size is less than item size',
		//       text: "Are you sure want to proceed?",
		//       type: "warning",
		//       showCancelButton: true,
		//       confirmButtonClass: "btn-danger",
		//       confirmButtonText: "Yes, Save",
		//       cancelButtonText: "No, cancel!",
		//       closeOnConfirm: false,
		//       closeOnCancel: true,
		//         html: true
		//     },
		//     function(isConfirm) {
		//       if (isConfirm) {
		//       	$scope.proceed();
		//       	 } else {
  //       		//swal("Cancelled", "Your record is safe :)", "error");
		//       	}
		//     });
		// }
		if($scope.errors.length>0)
		{
			swal({
		      title: "STOP",
		      text: $scope.errors,
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
		      	$scope.proceed();
		      	 } else {
        		//swal("Cancelled", "Your record is safe :)", "error");
		      	}
		    });
		}
		else
		{   
			$scope.proceed();
		}
	   
	}
	$scope.proceed = function ()
	{
		
		$scope.submitted = true;
		$scope.errors = [];
		// if($scope.BatchArr.length==1)
		// {
		// 	$scope.err = "Set"+$scope.row+ " Please enter atleast two sizes for slitting";
	 	//            $scope.errors.push($scope.err);
		// 	swal({title: "STOP",
		// 		     text: "Please enter atleast two sizes for slitting",
		// 		     type: "error",
		// 		     timer: 2000
		// 	});
		// }
		for (var i = 0; i < $scope.Verions.length; i++)
		{
			var sumbasictotal = 0;
			if ($scope.Verions[i]['BatchArr'] !== undefined) {
			//console.log($scope.Verions[i]['BatchArr']);
				for (var j = 0; j < $scope.Verions[i]['BatchArr'].length; j++)
				{
					sumbasictotal += parseFloat($scope.Verions[i].BatchArr[j]["splitsize"]);
				}
			}
			$scope.Verions[i].sumbasictotal=sumbasictotal;
			if((parseFloat(sumbasictotal)>parseFloat($scope.Size)) && (parseFloat(sumbasictotal)>parseFloat($scope.Size)+parseFloat(20.000)))
			{
				$scope.finalT = parseFloat($scope.Size)+parseFloat(20.000);
				$scope.row = i+1;
				$scope.err = "Set"+$scope.row+ " of Total size should not greater than the " + $scope.finalT +"\n";
				$scope.errors.push($scope.err);
			}
			if((parseFloat(sumbasictotal)<parseFloat($scope.Size))  && (parseFloat(sumbasictotal)<parseFloat($scope.Size)-parseFloat(20.000)))
			{
	            $scope.finalT = parseFloat($scope.Size)-parseFloat(20.000);
	            $scope.row = i+1;
	            $scope.err = "Set"+$scope.row+ " of Total size should not less than the " + $scope.finalT + "\n";
	            $scope.errors.push($scope.err);
			}
		}

		// var EntryPkId= new Array();
		// var RollNo = new Array();
		// var splitsize = new Array();
		// var subquantity= new Array();
		// for(i=0;i<$scope.BatchArr.length;i++)
	 //    {
	 //    	EntryPkId.push($scope.BatchArr[i].EntryPkId);	
	 //    	RollNo.push($scope.BatchArr[i].RollNo);	
	 //    	splitsize.push($scope.BatchArr[i].splitsize);
		//  	subquantity.push($scope.BatchArr[i].quantity);
		// }
		if($scope.errors.length>0)
		{
			swal({title: "STOP",
				     text: $scope.errors,
				     type: "error",
				     timer: 2000
			});
		}
		else
		{
			$scope.loading = true;
			if($scope.FormPkId==undefined)
			{
				$scope.FileErr="";
				$http.post('add-slitting-process.php',
				 // fd, {transformRequest: angular.identity,headers: {'Content-Type': undefined},
	    //         params:
	            {
				'slitid':$scope.slitid,
				'entrydate':$scope.entrydate,
				'remarks':$scope.remarks,

				'InvPkId':$scope.InvPkId,
				'PkId_RawPurchaseMasterDetails':$scope.PkId_RawPurchaseMasterDetails,
				'ProductId':$scope.ProductId,
				'GoDownName':$scope.GoDownName,
				'ProductName':$scope.ProductName,
				'Micron':$scope.Micron,
				'Size':$scope.Size,
				'UniqueRollNo':$scope.UniqueRollNo,
				'Avlqty':$scope.Avlqty,
				'Verions':$scope.Verions,

				// 'EntryPkId':EntryPkId,
				// 'splitsize':splitsize,
				// 'subquantity':subquantity,
				// 'sumbasictotal':$scope.sumbasictotal,
			//},

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
							     text: "Slitting created successfully",
							     type: "success",
							     timer: 2000
						},function () {window.location.href="slitting.php";})
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
		else
		{
			$scope.loading = true;
			$http.post('update-slitting-process.php',
			 // fd, {transformRequest: angular.identity,headers: {'Content-Type': undefined},
    //         params:
            {
				'FormPkId':$scope.FormPkId,
				'slitid':$scope.slitid,
				'entrydate':$scope.entrydate,
				'remarks':$scope.remarks,

				'InvPkId':$scope.InvPkId,
				'PkId_RawPurchaseMasterDetails':$scope.PkId_RawPurchaseMasterDetails,
				'ProductId':$scope.ProductId,
				'GoDownName':$scope.GoDownName,
				'ProductName':$scope.ProductName,
				'Micron':$scope.Micron,
				'Size':$scope.Size,
				'UniqueRollNo':$scope.UniqueRollNo,
				'Avlqty':$scope.Avlqty,
				'Verions':$scope.Verions,
				// 'EntryPkId':EntryPkId,
				// 'RollNo':RollNo,
				// 'splitsize':splitsize,
				// 'subquantity':subquantity,
				// 'sumbasictotal':$scope.sumbasictotal,
			//},

			})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.loading = false;
					$scope.submitted = false;
					$scope.FormValid = true;

					swal({title: "Updated",
							     text: "Slitting details updated successfully",
							     type: "success",
							     timer: 2000
						},function () {window.location.reload();})
				}
				else
				{
					
					swal("STOP", data, "error");
					$timeout(function () { $scope.submitted = false;}, 3000);
					$scope.loading = false;
				}
			}, function errorCallback(response) {
	    		// called asynchronously if an error occurs
			    // or server returns response with an error status.
			  });
		}
	}
	}

	$scope.DeleteRow = function(EntryPkId,RollNo,FormPkId,slitid){
		swal({
	      title: '',
	      text: "Are you sure want to delete?",
	      type: "warning",
	      showCancelButton: true,
	      confirmButtonClass: "btn-danger",
	      confirmButtonText: "Yes, delete it!",
	      cancelButtonText: "No, cancel!",
	      closeOnConfirm: false,
	      closeOnCancel: true,
	        html: true
	    },
	    function(isConfirm) {
	      if (isConfirm) {
	$http.post('delete-slit-eachset-process.php',{'EntryPkId':EntryPkId,'RollNo':RollNo,'FormPkId':FormPkId,'slitid':slitid})
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data['result']=="Success")
			{
				$scope.submitted = false;
				$scope.FormValid = true;

				$scope.EditOrder(data['FormPkId'],data['SlitId'],new Date(data['SlitDate']),data['PkId_InventoryMaster'],data['PkId_RawPurchaseMasterDetails'],data['ProductId_ProductMaster'],data['ProductName'],data['ProductSize'],data['Micron'],data['TotalName'],data['Unit'],data['UniqueRollNo'],data['GoDownName'],data['Quantity'],data['data2']);

				
				swal({title: "success!",
				     text: "Deleted successfully.",
				     type: "success",
				     timer: 2000});
				 
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
		
					  } else {
	        swal("Cancelled", "Your record is safe :)", "error");
	      }
	    });
	}

$scope.AddWeight = function(SlitId,SlitDate,Micron,ProductName,ProductSize,UniqueRollNo,EntryPkId,NewMicron,NewProductName,NewProductSize,RollNo,NewUnit,SlitQty,PurchaseQty,TotalSlitQty)
{
	$scope.SlitId = SlitId;
	$scope.SlitDate = SlitDate;
	$scope.Micron = Micron;
	$scope.ProductName = ProductName;
	$scope.ProductSize = ProductSize;
	$scope.UniqueRollNo = UniqueRollNo;
	$scope.EntryPkId = EntryPkId;
	$scope.NewMicron = NewMicron;
	$scope.NewProductName = NewProductName;
	$scope.NewProductSize = NewProductSize;
	$scope.RollNo = RollNo;
	$scope.NewUnit = NewUnit;
	$scope.quantity = SlitQty;
	$scope.PurchaseQty = PurchaseQty;
	$scope.TotalSlitQty = TotalSlitQty;
}
$scope.AddCustomerData = function()
{
	$scope.LeftQty = parseFloat($scope.PurchaseQty)-parseFloat($scope.TotalSlitQty);
//	alert($scope.LeftQty);
	if(parseFloat($scope.quantity)>=parseFloat($scope.PurchaseQty))
	{
		swal({title: "STOP!",
				     text: "Qty should be less than the purchase qty.",
				     type: "error",
				     timer: 2000});
	}
	// else if(parseFloat($scope.quantity)>(parseFloat($scope.TotalSlitQty)-parseFloat($scope.quantity)))
	// {
	// 	swal({title: "STOP!",
	// 			     text: "Qty should be less than the left over qty.",
	// 			     type: "error",
	// 			     timer: 2000});
	// }
	// else if((parseFloat($scope.quantity)!=parseFloat($scope.LeftQty)) && (parseFloat($scope.quantity)>parseFloat($scope.LeftQty)))
	// {
	// 	swal({title: "STOP!",
	// 			     text: "Qty cannot greater than the" + $scope.LeftQty + "",
	// 			     type: "error",
	// 			     timer: 2000});
	// }
	else {
	// 	alert("ok");
		$http.post('add-slit-weight-process.php',{'EntryPkId':$scope.EntryPkId,'RollNo':$scope.RollNo,'SlitId':$scope.SlitId,'quantity':$scope.quantity,'PurchaseQty':$scope.PurchaseQty})
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data=="Success")
			{
				//window.scrollTo(500, 0);
				$scope.submitted = false;
				$scope.FormValid = true;
				$scope.quantity="0.000";
				$scope.GotoList();
				 swal({title: "success!",
				     text: "Weight updated successfully.",
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
}
	$scope.GotoList = function()
	{
		$scope.quantity="0.000";
		window.location.href = "add-slitting.php";
	}
$scope.DeleteOrder = function(FormPkId,SlitId,PkId_RawPurchaseMasterDetails,PkId_InventoryMaster,UniqueRollNo,PurchaseQty){
	swal({
	      title: '',
	      text: "Are you sure want to delete?",
	      type: "warning",
	      showCancelButton: true,
	      confirmButtonClass: "btn-danger",
	      confirmButtonText: "Yes, delete it!",
	      cancelButtonText: "No, cancel!",
	      closeOnConfirm: false,
	      closeOnCancel: true,
	        html: true
	    },
	    function(isConfirm) {
	      if (isConfirm) {
	$http.post('delete-slit-process.php',{'FormPkId':FormPkId,'SlitId':SlitId,'PkId_RawPurchaseMasterDetails':PkId_RawPurchaseMasterDetails,'PkId_InventoryMaster':PkId_InventoryMaster,'UniqueRollNo':UniqueRollNo,'PurchaseQty':PurchaseQty,})
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data=="Success")
			{
				//window.scrollTo(500, 0);
				$scope.submitted = false;
				$scope.FormValid = true;
				$scope.quantity="0.000";
				$scope.GotoList();
				 swal({title: "success!",
				     text: "Deleted successfully.",
				     type: "success",
				     timer: 2000});
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
		
					  } else {
	        swal("Cancelled", "Your record is safe :)", "error");
	      }
	    });
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
