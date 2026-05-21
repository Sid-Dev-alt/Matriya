var AddCategory = angular.module("CategoryModule", ['ui.bootstrap', 'datatables'])
AddCategory.controller("CategoryController", function ($scope, $timeout, $http, jsonFilter)
{
	$scope.FormAdd = false;	
	$scope.FormList = true;	
	$scope.pagetitle = "List of Level-1 Subcategories";
	
	var logResult = function (data, status, headers, config)
	{
		return data;
	};

$scope.GotoAdd = function()
{
	$scope.pagetitle = "Add Level-1 Subcategory";
	$scope.FormAdd = true;	
	$scope.FormList = false;	
	$scope.GetCat();
}
	$scope.GetCat= function()
	{
		$http.get("load-category.php")
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
	$scope.pagedItems=[];
	$scope.GetListData= function(PkId,index)
	{
		$http.post("Get-subcategory.php",{'category': PkId})
		.then(function successCallback(response)
		{
			var data = response.data;			
			if(data=="NoData")
			{
			//	swal("Empty", "No Records Found", "error");
			//				$timeout(function () { $scope.submitted = false;}, 2000);
				$scope.pagedItems[index] = "";
				//$scope.FormList = false;
			}
			else
			{
				// $scope.query = {}
				// $scope.queryBy = '$';
				$scope.pagedItems[index] = data;
				//$scope.FormList = true;
			}
		});
	}


	$scope.checkCatgory = function(FormPkId,catname,subcatname)
	{
		$http.post('check-sub-category.php', { 'FormPkId': $scope.FormPkId, 'catname': $scope.catname, 'subcatname': $scope.subcatname })
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

	$scope.EditCat = function (PkId,PkId_CategoryMaster,SubCategoryName)
	{	
		$scope.GetCat();
		$scope.pagetitle = "Edit Level-1 Subcategory";
		$scope.FormAdd = true;	
		$scope.FormList = false;
		$scope.FormPkId = PkId;
		$scope.catname = PkId_CategoryMaster;
		$scope.subcatname = SubCategoryName;
		$scope.checkCatgory(PkId,PkId_CategoryMaster,SubCategoryName);
	}
	
	$scope.Delete = function(PkId)
	{
		$scope.FormAdd = false;	
		$scope.FormList = true;
		$http.post('delete-subcategory-process.php', { 'PkId': PkId })
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data=="Success")
			{
				$scope.submitted = false;
				$scope.FormValid = true;

				swal({title: "Added",
						     text: "Subcategory deleted successfully",
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
			$http.post("add-sub-category-process.php",{'catname':$scope.catname,'subcatname':$scope.subcatname})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.submitted = false;
					$scope.FormValid = true;

					swal({title: "Added",
							     text: "Subcategory added successfully",
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
		else if($scope.NameExists==false)
		{
			$http.post("update-subcategory-process.php",{'FormPkId':$scope.FormPkId,'catname':$scope.catname,'subcatname':$scope.subcatname})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.submitted = false;
					$scope.FormValid = true;

					swal({title: "Updated",
							     text: "Subcategory updated successfully",
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
	};
	
	$scope.GotoList = function()
	{		
		window.location.href = "sub-category.php";
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