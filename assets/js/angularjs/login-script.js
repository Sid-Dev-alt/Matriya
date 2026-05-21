var Login = angular.module("LoginModule", [])
Login.controller("LoginController", function ($scope, $timeout, $http, jsonFilter)
{
	$scope.ShowLoginBox = true;
	$scope.ShowForgotPwdBox = false;
	$scope.showWarningAlert = false;
	$scope.showSuccessAlert = false;
	
	var logResult = function (data, status, headers, config)
	{
		return data;
	};
	
	$scope.LoginData = function (SALogin)
	{		
		$scope.submitted = true;
		var config = {	params: { SALogin: SALogin }	};
				
		$http.post("login-process.php", null, config)
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data[0]["result"]=="Success")
			{
				$scope.submitted = false;
				window.location.href = data[0]['URL'];
				$scope.SuccessAlert = data[0]["result"];
	   			$scope.showSuccessAlert = true;
	   			$scope.showWarningAlert = false;
	   			$timeout(function () { $scope.showSuccessAlert = false; }, 3000);
			
			//	$scope.SALogin = {};
				
			$scope.LoginForm.$setPristine();
			$scope.LoginForm.$setUntouched();
			}
			
			if(data[0]["result"]=="Invalid")
			{
				$scope.WarningAlert = data[0]["URL"];
   			$scope.showWarningAlert = true;
   			$scope.showSuccessAlert = false;
			}
		}, function errorCallback(response) {
    		// called asynchronously if an error occurs
		    // or server returns response with an error status.
		  });
	};
	
	$scope.ForgotPwdBox = function () {
		$scope.ShowForgotPwdBox = true;
		$scope.ShowLoginBox = false;
		$scope.showFPwdWarningAlert = false;
		$scope.showFPwdSuccessAlert = false;
		
		$scope.ForgotPwdData = function (SAForgotPwd)
		{
			$scope.submitted = true;
			var config = {	params: { SAForgotPwd: SAForgotPwd }	};
					
			$http.post("forgot-password-process.php", null, config)
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.submitted = false;
					$scope.FPwdSuccessAlert = "Check your mail for new password";
	   			$scope.showFPwdSuccessAlert = true;
	   			$scope.showFPwdWarningAlert = false;
	   			$timeout(function () { $scope.showFPwdSuccessAlert = false; }, 3000);				
   			
					$scope.SAForgotPwd = {};
					
					$scope.ForgotPwdForm.$setPristine();
					$scope.ForgotPwdForm.$setUntouched();
				}
				
				if(data!="Success")
				{
					$scope.FPwdWarningAlert = data;
	   			$scope.showFPwdWarningAlert = true;
	   			$scope.showFPwdSuccessAlert = false;
				}
			}, function errorCallback(response) {
    		// called asynchronously if an error occurs
		    // or server returns response with an error status.
		  });	
		};
	};	

	$scope.LoginBox = function()
	{
		$scope.ShowForgotPwdBox = false;
		$scope.ShowLoginBox = true;
		$scope.showFPwdWarningAlert = false;
		$scope.showFPwdSuccessAlert = false;
	}
	
});