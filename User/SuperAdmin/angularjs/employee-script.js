var AddCategory = angular.module("CategoryModule", ['datatables'])
AddCategory.controller("CategoryController", function ($scope, $timeout, $http, jsonFilter)
{
	$scope.FormAdd = false;	
	$scope.FormList = true;	
	$scope.pagetitle = "List of Employees";
	

	var logResult = function (data, status, headers, config)
	{
		return data;
	};

$scope.GotoAdd = function()
{
	$scope.pagetitle = "Add Employee";
	$scope.FormAdd = true;	
	$scope.FormList = false;
}

	$scope.GetListData= function()
	{
		$http.get("load-employees.php")
		.then(function successCallback(response)
		{
			var data = response.data;			
			if(data=="NoData")
			{
				swal("Empty", "No Records Found", "error");
							$timeout(function () { $scope.submitted = false;}, 2000);
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


$scope.GetRoles = function()
{
    $http.get("Get-Roles.php")
    .then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{
				$scope.RoleArray = "";
			}
			else
			{
				$scope.RoleArray = data;	
			}
            
       }, function errorCallback(response) {
		// called asynchronously if an error occurs
	    // or server returns response with an error status.
	  });
}

//$scope.StateArray = [];
$scope.GetStates = function()
{
    $http.get("Get-States.php")
    .then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{
				$scope.StateArray = "";
			}
			else
			{
				$scope.StateArray = data;	
			}
            
       }, function errorCallback(response) {
		// called asynchronously if an error occurs
	    // or server returns response with an error status.
	  });
}

//$scope.DistrictArray = [];
$scope.GetDist = function(state)
{
    $http.post("Get-Districts.php", {'state': state})
    .then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{

            $scope.DistrictArray = "";
			}
			else
			{

            $scope.DistrictArray = data;	
			}
       }, function errorCallback(response) {
		// called asynchronously if an error occurs
	    // or server returns response with an error status.
	  });
}

	$scope.checkMobile = function(FormPkId,mobileno)
	{
		 $http.post("check-employee-mobile.php",{'FormPkId': FormPkId, 'mobileno': mobileno})
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data!="OK")
			{
				$scope.MobileExists = true;
			}
			else
			{
				$scope.MobileExists = false;
			}
		}, function errorCallback(response) {
	    		// called asynchronously if an error occurs
			    // or server returns response with an error status.
			  });
	};

	$scope.checkEmail = function(FormPkId,emailid)
	{
		 $http.post("check-employee-email.php",{'FormPkId': FormPkId,'emailid': emailid})
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data!="OK")
			{
				$scope.EmailExists = true;
			}
			else
			{
				$scope.EmailExists = false;
			}
		}, function errorCallback(response) {
	    		// called asynchronously if an error occurs
			    // or server returns response with an error status.
			  });
	};

// 	$scope.GetGeoLocation = function()
// 	{
// 		 var DistName = "+" + $.grep($scope.DistrictArray, function (dist) {
//                     return dist.PkId == $scope.district;
//                 })[0].DistName;

// 		 var StateName = "+" + $.grep($scope.StateArray, function (state) {
//                     return state.PkId == $scope.state;
//                 })[0].StateName;


// // var str1 = $scope.address1;
// // var str2 = "+" + $scope.city;
// // $scope.res = str1.concat(str2, DistName, StateName);




// //  $http.get('https://maps.googleapis.com/maps/api/geocode/json?address=1600+Amphitheatre+Parkway,+Mountain+View,+CA&key=AIzaSyBF8RxmraKiCYJ3vpHgvN3qaetWFGvkeTA')  
// //             .then(function successCallback(response)
// // 		 { 
// // 		 	var data = response.data;
// // 		 	console.log(data);
// // 		 	alert(data.results);
// //                 //$scope.mapData = mapData.results; 
// //             });

// 		 $http.post("Get-Geo-Locations.php",{'address1': $scope.address1,'town': $scope.town,'city': $scope.city,'district': DistName,'state': StateName})
// 		.then(function successCallback(response)
// 		{

// 			var data = response.data;
// 			console.log(data);
// 			if(data!="NoData")
// 			{
// 				 $scope.latitude = true;
// 				 $scope.longitude = true;
// 			}
// 		}, function errorCallback(response) {
// 	    		// called asynchronously if an error occurs
// 			    // or server returns response with an error status.
// 			  });
// 	};
	
	$scope.AddCustomerData = function ()
	{
		$scope.submitted = true;
		if($scope.FormPkId==undefined)
			{
			$http.post("add-employee-process.php",{
			'empname':$scope.empname,
			'designation':$scope.designation,
			'emailid':$scope.emailid,
			'mobileno':$scope.mobileno,
			'othermobileno':$scope.othermobileno,
			//'gstin':$scope.gstin,
			//'pan':$scope.pan,
			'address1':$scope.address1,
			'address2':$scope.address2,
			'town':$scope.town,
			'landmark':$scope.landmark,
			'city':$scope.city,
			'state':$scope.state,
			'district':$scope.district,
			'pincode':$scope.pincode,
			'lattitude':$scope.lattitude,
			'longitude':$scope.longitude,
			//'bankname':$scope.bankname,
			//'branchname':$scope.branchname,
			//'accountname':$scope.accountname,
			//'accounttype':$scope.accounttype,
			//'acnumber':$scope.acnumber,
			//'ifsc':$scope.ifsc,
			//'tand5':$scope.tandc
			})
			.then(function successCallback(response)
				{
					var data = response.data;
				if(data=="Success")
				{
					window.scrollTo(500, 0);
					$scope.submitted = false;
					$scope.FormValid = true;
					
					swal({title: "",
							     text: "Employee Created successfully",
							     type: "success",
							     timer: 3000 
						},function () {window.location.reload();})
						
		   			$timeout(function () { $scope.showSuccessAlert = false; window.location.href="list-employees.php";}, 3000);
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
			//alert("update");
			$http.post("update-employee-process.php",{
				'FormPkId':$scope.FormPkId,
				'empname':$scope.empname,
				'designation':$scope.designation,
			'emailid':$scope.emailid,
			'mobileno':$scope.mobileno,
			'othermobileno':$scope.othermobileno,
			//'gstin':$scope.gstin,
			//'pan':$scope.pan,
			'address1':$scope.address1,
			'address2':$scope.address2,
			'town':$scope.town,
			'landmark':$scope.landmark,
			'city':$scope.city,
			'state':$scope.state,
			'district':$scope.district,
			'pincode':$scope.pincode,
			'lattitude':$scope.lattitude,
			'longitude':$scope.longitude,
			//'bankname':$scope.bankname,
			//'branchname':$scope.branchname,
			//'accountname':$scope.accountname,
			//'accounttype':$scope.accounttype,
			//'acnumber':$scope.acnumber,
			//'ifsc':$scope.ifsc,
			//'tand5':$scope.tandc
		})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.submitted = false;
					$scope.FormValid = true;

					swal({title: "",
							     text: "Employee updated successfully",
							     type: "success",
							     timer: 3000 
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
	};
	
	$scope.EditSupplier = function(PkId,EmpName,Designation,EmailId,Mobile,OtherMobileNo,AddressLane1,AddressLane2,Town,LandMark,City,State,PINCode,District,Lattitude,Longitude)
	{
		$scope.pagetitle = "Edit Employee";
		$scope.FormAdd = true;
		$scope.FormList = false;
	//	$scope.checkEmail(EmailId);
	//	$scope.checkMobile(Mobile);
		$scope.GetDist(State);
		$scope.FormPkId = PkId;
		$scope.empname = EmpName;
		$scope.designation = Designation;
		$scope.emailid = EmailId;
		$scope.mobileno = Mobile;
		$scope.othermobileno = OtherMobileNo;
		$scope.address1 = AddressLane1;
		$scope.address2 = AddressLane2;
		$scope.town = Town;
		$scope.landmark = LandMark;
		$scope.city = City;
		$scope.state = State;
		$scope.pincode = PINCode;
		$scope.district = District;
		$scope.lattitude = Lattitude;
		$scope.longitude = Longitude;

	}
	$scope.GotoList = function()
	{		
		window.location.href = "list-employees.php";
	}
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