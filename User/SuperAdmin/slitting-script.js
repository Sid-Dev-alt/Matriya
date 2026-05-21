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
	$scope.pagetitle = "List of Slitting";

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
$scope.removeRow = function (idx)
{
	$scope.BatchArr.splice(idx, 1);
};

$scope.BatchArr = [{splitsize:"0",quantity:"0.000",delflag:"0"},{splitsize:"0",quantity:"0.000",delflag:"0"}];

$scope.AddMore = function()
{
  $scope.BatchArr.push({splitsize:"0",quantity:"0.000",delflag:"0"});
}

/*Add & Remove Script end Here*/

$scope.GotoAdd = function()
{
	$scope.pagetitle = "Add New";
	$scope.FormAdd = true;
	$scope.FormList = false;
	$scope.GetOrderId();
	$scope.GetCategories();
	$scope.GetPurchaseBars();
	//$scope.GetPet();
	var todt = new Date();
	var cdt = todt.getDate();
	var cmonth = todt.getMonth()+1;
	$scope.dtmonth = cdt+'|'+cmonth;
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
			}
            
       }, function errorCallback(response) {
		// called asynchronously if an error occurs
	    // or server returns response with an error status.
	  });
}

$scope.GetListData= function()
	{
		$http.get("load-slitting.php")
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
	console.log($scope.barcodeInput);
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


$scope.BasicSum = function()
{
	var sumbasictotal = 0;
	if ($scope.BatchArr !== undefined) {

		for (var i = 0; i < $scope.BatchArr.length; i++)
		{
			sumbasictotal += Math.round(parseFloat($scope.BatchArr[i]["basicamount"]));
		}
	}
	$scope.sumbasictotal=sumbasictotal;
		return sumbasictotal;
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

$scope.BasicSum = function()
{
	var sumbasictotal = 0;
	if ($scope.BatchArr !== undefined) {

		for (var i = 0; i < $scope.BatchArr.length; i++)
		{
			sumbasictotal += parseFloat($scope.BatchArr[i]["splitsize"]);
		}
	}
	$scope.sumbasictotal=sumbasictotal;
		return sumbasictotal;
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
		$scope.BatchArr = data2;
		angular.forEach($scope.BatchArr, function (value, key) {
		$scope.BatchArr[key].EntryPkId = value.EntryPkId;
       	$scope.BatchArr[key].splitsize = value.SplitSize; 
   	 }); 
	}

	$scope.AddCategoryData = function ()
	{
	    if(parseFloat($scope.sumbasictotal)>parseFloat($scope.Size))
		{
		  $scope.finalT = parseFloat($scope.Size)+parseFloat(20.000);
			//alert("high");
			 swal({
		      title: 'Total size is greater than item size',
		      text: "Are you sure to want to proceed?",
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
		else if(parseFloat($scope.sumbasictotal)<parseFloat($scope.Size))
		{
            $scope.finalT = parseFloat($scope.Size)-parseFloat(20.000);
			//alert("high");
			 swal({
		      title: 'Total size is less than item size',
		      text: "Are you sure want to proceed?",
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
		var EntryPkId= new Array();
		var RollNo = new Array();
		var splitsize = new Array();
		var subquantity= new Array();
		for(i=0;i<$scope.BatchArr.length;i++)
	    {
	    	EntryPkId.push($scope.BatchArr[i].EntryPkId);	
	    	RollNo.push($scope.BatchArr[i].RollNo);	
	    	splitsize.push($scope.BatchArr[i].splitsize);
		 	subquantity.push($scope.BatchArr[i].quantity);
		}
		if($scope.BatchArr.length==1)
		{
			swal({title: "STOP",
				     text: "Please enter atleast two sizes for slitting",
				     type: "error",
				     timer: 2000
			});
		}
else if((parseFloat($scope.sumbasictotal)>parseFloat($scope.Size)) && (parseFloat($scope.sumbasictotal)>parseFloat($scope.finalT)))
		//else if(parseFloat($scope.sumbasictotal)>parseFloat($scope.finalT))
		{
			swal({title: "STOP",
				     text: "Total size should not greater than the" + $scope.finalT,
				     type: "error",
				     timer: 2000
			});
		}
		else if((parseFloat($scope.sumbasictotal)<parseFloat($scope.Size)) && (parseFloat($scope.sumbasictotal)<parseFloat($scope.finalT)))
		{
			swal({title: "STOP",
				     text: "Total size should not less than the" + $scope.finalT,
				     type: "error",
				     timer: 2000
			});
		}
		//else if($scope.sumbasictotal!=$scope.Size)
		//{
		//	swal({title: "STOP",
		//		     text: "Sum of Sizes should match with the Actual Size",
		//		     type: "error",
		//		     timer: 2000
		//	});
		//}
		else
		{
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

				'EntryPkId':EntryPkId,
				'splitsize':splitsize,
				'subquantity':subquantity,
				'sumbasictotal':$scope.sumbasictotal,
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
							     text: "Slitting created successfully",
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

				'EntryPkId':EntryPkId,
				'RollNo':RollNo,
				'splitsize':splitsize,
				'subquantity':subquantity,
				'sumbasictotal':$scope.sumbasictotal,
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
							     text: "Slitting details updated successfully",
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
		window.location.href = "slitting.php";
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
