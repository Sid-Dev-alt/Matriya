var AddCategory = angular.module("CategoryModule", ['ui.bootstrap', 'datatables'])
AddCategory.controller("CategoryController", function ($scope, $timeout, $http, jsonFilter)
{
	$scope.FormAdd = false;	
	$scope.FormList = true;	
	$scope.pagetitle = "List of Main Categories";
	
	var logResult = function (data, status, headers, config)
	{
		return data;
	};
$scope.location="All";
$scope.GotoAdd = function()
{
	$scope.pagetitle = "Add Main Category";
	$scope.FormAdd = true;	
	$scope.FormList = false;	
}

	$scope.GetListData= function()
	{
		$http.get("load-category.php")
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


	$scope.checkCatgory = function(FormPkId,catname)
	{
		$http.post('check-category.php', { 'FormPkId': $scope.FormPkId, 'catname': $scope.catname })
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

	$scope.EditCat = function (PkId,CategoryName,CategoryDescription)
	{
		$scope.pagetitle = "Edit Main Category";
		$scope.FormAdd = true;	
		$scope.FormList = false;
		$scope.FormPkId = PkId;
		$scope.catname = CategoryName;
		$scope.catdescription = CategoryDescription;
		$scope.checkCatgory(PkId,CategoryName);
	}
	
	$scope.Delete = function(PkId)
	{
		$scope.FormAdd = false;	
		$scope.FormList = true;
		$http.post('delete-category-process.php', { 'PkId': PkId })
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data=="Success")
			{
				$scope.submitted = false;
				$scope.FormValid = true;

				swal({title: "Added",
						     text: "Category deleted successfully",
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
	$scope.AddCategoryData = function ()
	{
		$scope.submitted = true;
		//var config = { params: { AddCategory: AddCategory } };
		//$scope.AddCategory.supervisor: [];
		if($scope.FormPkId==undefined && $scope.NameExists==false)
		{
			$http.post("add-category-process.php",{'catname':$scope.catname,'catdescription':$scope.catdescription})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.submitted = false;
					$scope.FormValid = true;

					swal({title: "Added",
							     text: "Category added successfully",
							     type: "success",
							     timer: 2500 
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
		else if($scope.NameExists==false)
		{
			$http.post("update-category-process.php",{'FormPkId':$scope.FormPkId,'catname':$scope.catname,'catdescription':$scope.catdescription})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.submitted = false;
					$scope.FormValid = true;

					swal({title: "Updated",
							     text: "Category updated successfully",
							     type: "success",
							     timer: 2500 
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
		window.location.href = "category.php";
	}
});
