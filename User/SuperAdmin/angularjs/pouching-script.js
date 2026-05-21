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
	$scope.pagetitle = "List of Pouching";

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

//$scope.pouchinput = "0.000";
$scope.pouchoutput = "0.000";
$scope.wastage = "0.000";
// $scope.laminationwaste = "0.000";
// $scope.trimwaste = "0.000";
// $scope.cuttingsize = "0.000";

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
	$scope.GetCategories();
	$scope.GetJobs();
	//$scope.GetPet();
	var todt = new Date();
	var cdt = todt.getDate();
	var cmonth = todt.getMonth()+1;
	$scope.dtmonth = cdt+'|'+cmonth;

}
$scope.GetListData= function()
	{
		$http.get("load-pouching.php")
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
$scope.ChkQty = function(rollavlqty,consumeqty)
{
	if(parseFloat(consumeqty)>parseFloat(rollavlqty))
	{
		$scope.PetErr = "Qty is more than Available Quantity";
	}
	else
	{
		$scope.PetErr = "";
	}
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
    //$scope.GetSlitting($item.PkId);
};

$scope.PouchInputArr = [];
$scope.GetSlitting = function(jobname)
	{
        $http.post('get-slitting-value.php',{'JobPkId': jobname})
        .then(function successCallback(response)
		{
			var data = response.data;
        	if(data=="NoData")
			{
				$scope.PouchInputArr = "";
			}
			else
			{
				$scope.PouchInputArr = data;
			}
		});
	}

$scope.ProductArray = [];
	$scope.GetProducts = function(rawcategory)
	{
		$scope.productid="";
		$scope.productsize="";
		$scope.rollno="";
        $http.post('get-raw-rolls.php',{'CatPkId': rawcategory})
        .then(function successCallback(response)
		{
			var data = response.data;
        	if(data=="NoData")
			{
				$scope.productid="";
				$scope.productsize="";
				$scope.rollno="";
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
    $scope.productsize = $item.ProductName;
    $scope.$model = $model;
    $scope.$label = $label;
    $scope.RollArr = $item.data2;
};
$scope.GetRollQty = function(rollno)
{
	//alert();
    $http.post('get-roll-qty.php',{'rollno': rollno})
    .then(function successCallback(response)
	{
		var data = response.data;
		$scope.rollavlqty = data['AvlQty'];
	});
}
//$scope.DeliveryArray = ['Transport','Courier','Auto','Rikshaw'];
	

	$scope.EditOrder = function(FormPkId,PouchDate,PkId_JobMaster,JobName,PouchType,PouchSize,Machine,PouchInput,PouchQuality,PouchOutput,NoofBags,StartTime,EndTime,Wastage,Remarks)
	{		
		$scope.GetJobs();
		$scope.FormList = false;
		$scope.FormAdd = true;
		$scope.pagetitle = "Edit Pouching";
		$scope.FormPkId=FormPkId;
		$scope.entrydate=new Date(PouchDate);
		$scope.oldjobid=PkId_JobMaster;
		//$scope.jobid=PkId_JobMaster;
		$scope.jobname=JobName;		
		$scope.pouchtype=PouchType;
		$scope.pouchsize=PouchSize;
		$scope.machine=Machine;
		$scope.pouchinput=PouchInput;
		$scope.pouchquality=PouchQuality;
		$scope.pouchoutput=PouchOutput;
		$scope.noofbags=NoofBags;
		$scope.starttime=StartTime;
		$scope.endtime=EndTime;
		$scope.wastage=Wastage;
		$scope.remarks=Remarks;
	}

	$scope.AddCategoryData = function ()
	{
		$scope.submitted = true;
		if($scope.FormPkId==undefined)
		{
			$scope.FileErr="";
			$http.post('add-pouching-process.php',
			 // fd, {transformRequest: angular.identity,headers: {'Content-Type': undefined},
    //         params:
            {
				//'productionid':$scope.productionid,
				'entrydate':$scope.entrydate,
				'jobid':$scope.jobname,
				//'jobname':$scope.jobname,
				'pouchtype':$scope.pouchtype,
				'pouchsize':$scope.pouchsize,
				'machine':$scope.machine,
				'pouchinput':$scope.pouchinput,
				'pouchquality':$scope.pouchquality,
				'pouchoutput':$scope.pouchoutput,
				'noofbags':$scope.noofbags,
				'starttime':$scope.starttime,
				'endtime':$scope.endtime,
				'wastage':$scope.wastage,
				'remarks':$scope.remarks,
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
							     text: "Pouching created successfully",
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
			$http.post('update-pouching-process.php',
			 // fd, {transformRequest: angular.identity,headers: {'Content-Type': undefined},
    //         params:
            {
				'FormPkId':$scope.FormPkId,
				'entrydate':$scope.entrydate,
				'oldjobid':$scope.oldjobid,
				'jobid':$scope.jobid,
				'jobname':$scope.jobname,
				'pouchtype':$scope.pouchtype,
				'pouchsize':$scope.pouchsize,
				'machine':$scope.machine,
				'pouchinput':$scope.pouchinput,
				'pouchquality':$scope.pouchquality,
				'pouchoutput':$scope.pouchoutput,
				'noofbags':$scope.noofbags,
				'starttime':$scope.starttime,
				'endtime':$scope.endtime,
				'wastage':$scope.wastage,
				'remarks':$scope.remarks,
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
							     text: "Pouching details updated successfully",
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
