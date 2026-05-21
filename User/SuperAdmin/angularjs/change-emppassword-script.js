var ChangePwd = angular.module("ChangePwdModule", ['ui.bootstrap','datatables','angularUtils.directives.dirPagination'])
ChangePwd.controller("ChangePwdController", function ($scope, $timeout, $http, jsonFilter, DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder)
{
	$scope.FormAdd = false;	
	$scope.FormList = true;	
	$scope.pagetitle = "List of Employees";
	
	$scope.vm = {};
$scope.vm.dtInstance = {};   
$scope.vm.dtColumnDefs = [
//DTColumnDefBuilder.newColumnDef(2).notSortable()
];
$scope.vm.dtOptions = DTOptionsBuilder.newOptions()
          		.withOption('order', [0, 'desc'])
				.withOption('info', false);		

	var logResult = function (data, status, headers, config)
	{
		return data;
	};


	$scope.pagedItems = []; //declare an empty array
	$scope.pageno = 1; // initialize page no to 1
	$scope.total_count = 0;
	$scope.itemsPerPage = 5; //this could be a dynamic value from a drop down
	$scope.loading = false;
	$scope.getData = function(pageno){ 
		$scope.loading = true;
		$scope.mypageno = pageno;
		
		$scope.pagedItems = [];  

		$http.post("load-employee-details.php",{'itemsPerPage': $scope.itemsPerPage, 'pagenumber': pageno})
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
    
    	$scope.ChangePwdData = function (SAChangePwd)
	{
		$scope.submitted = true;
		// var config = { params: { SAChangePwd: SAChangePwd } };

		$http.post("change-emppassword-process.php",{'UserId':$scope.UserId,'currentpwd':$scope.currentpwd,'newpwd':$scope.newpwd,'retypwd':$scope.retypwd})
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data=="Success")
			{
				$scope.submitted = false;
				$scope.FormValid = true;

				swal({title: "Added",
						     text: "Password Changed successfully",
						     type: "success",
						     timer: 2000 
					},function () {window.location.reload();})
			}
			else
			{
				swal("Required", data, "error");
				$timeout(function () { $scope.submitted = false;}, 3000);
			}
		}, function errorCallback(response) {
		// called asynchronously if an error occurs
		    // or server returns response with an error status.
		    // $scope.WarningAlert = logResult(data, status, headers, config);
		  });		
		
		$scope.switchBool = function (value) {
			$scope[value] = !$scope[value];
		};
	};
	
	$scope.ChangePass = function(UserId)
	{
		$scope.pagetitle = "Change Password";
		$scope.FormAdd = true;
		$scope.FormList = false;
		
		$scope.UserId = UserId;
	}

	$scope.GotoList = function()
	{		
		window.location.href = "change-emppassword.php";
	}
});
ChangePwd.directive('convertToNumber', function() {
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

ChangePwd.directive('number', function () {
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
