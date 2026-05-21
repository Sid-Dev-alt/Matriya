var AddCategory = angular.module("CategoryModule", ['ui.bootstrap', 'datatables'])
AddCategory.controller("CategoryController", function ($scope, $timeout, $http, jsonFilter)
{
	$scope.FormAdd = false;	
	$scope.FormList = true;	
	$scope.pagetitle = "List of Subbcategories";
	
	var logResult = function (data, status, headers, config)
	{
		return data;
	};

$scope.GotoAdd = function()
{
	$scope.pagetitle = "Add Location";
	$scope.FormAdd = true;	
	$scope.FormList = false;	
	$scope.GetCat();
}
	$scope.GetCat= function()
	{
		$http.get("load-location-type.php")
		.then(function successCallback(response)
		{
			var data = response.data;			
			if(data=="NoData")
			{
			//	swal("Empty", "No Records Found", "error");
			//				$timeout(function () { $scope.submitted = false;}, 2000);
				$scope.CategoryArr = "";
				//$scope.FormList = false;
			}
			else
			{
				$scope.CategoryArr = data;
				//$scope.FormList = true;
			}
		});
	}
	$scope.GetListData= function()
	{
		$http.get("load-locations.php")
		.then(function successCallback(response)
		{
			var data = response.data;			
			if(data=="NoData")
			{
			//	swal("Empty", "No Records Found", "error");
			//				$timeout(function () { $scope.submitted = false;}, 2000);
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


	$scope.checkCatgory = function(FormPkId,locationtype,Location)
	{
		$http.post('check-sub-category.php', { 'FormPkId': $scope.FormPkId, 'locationtype': $scope.locationtype, 'locationname': $scope.locationname })
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data!="OK")
			{
				$scope.NameExists = true;
				//$scope.FormValid = true;
			}
			else
			{
				$scope.NameExists = false;
				//$scope.FormValid = false;
			}
		}, function errorCallback(response) {
    		// called asynchronously if an error occurs
		    // or server returns response with an error status.
		  });
	};

	$scope.EditCat = function (PkId,PkId_LocationType,LocationName)
	{	
		$scope.GetCat();
		$scope.pagetitle = "Edit Sub Category";
		$scope.FormAdd = true;	
		$scope.FormList = false;
		$scope.FormPkId = PkId;
		$scope.locationtype = PkId_LocationType;
		$scope.locationname = LocationName;
	}
	
	$scope.AddCategoryData = function ()
	{
		$scope.submitted = true;
		//var config = { params: { AddCategory: AddCategory } };
		//$scope.AddCategory.supervisor: [];
		if($scope.FormPkId==undefined)
		{
			$http.post("add-location-process.php",{'locationtype':$scope.locationtype,'locationname':$scope.locationname})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.submitted = false;
					$scope.FormValid = true;

					swal({title: "Added",
							     text: "Location added successfully",
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
		else
		{
			$http.post("update-location-process.php",{'FormPkId':$scope.FormPkId,'locationtype':$scope.locationtype,'locationname':$scope.locationname})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.submitted = false;
					$scope.FormValid = true;

					swal({title: "Updated",
							     text: "Location updated successfully",
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
	};
	
	$scope.GotoList = function()
	{		
		window.location.href = "locations.php";
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