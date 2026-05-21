var ChangePwd = angular.module("ChangePwdModule", [])
ChangePwd.controller("ChangePwdController", function ($scope, $timeout, $http, jsonFilter)
{
	$scope.showWarningAlert = false;	
	$scope.showSuccessAlert = false;	
	var logResult = function (data, status, headers, config)
	{
		return data;
	};
	
	$scope.GotoList = function()
	{		
		window.location.href = "welcome.php";
	}
	
	
	$scope.ChangePwdData = function (SAChangePwd)
	{
		$scope.submitted = true;
		var config = { params: { SAChangePwd: SAChangePwd } };

		$http.post("change-password-process.php", null, config)
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
					},function () {window.location.href = "welcome.php";})
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
});
