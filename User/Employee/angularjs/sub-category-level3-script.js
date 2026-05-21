var AddCategory = angular.module("CategoryModule", ['ui.bootstrap', 'datatables'])
AddCategory.controller("CategoryController", function ($scope, $timeout, $http, jsonFilter)
{
	$scope.FormAdd = false;	
	$scope.FormList = true;	
	$scope.pagetitle = "List of Level-3 Sub categories";
	
	var logResult = function (data, status, headers, config)
	{
		return data;
	};

// $scope.GotoAdd = function()
// {
// 	$scope.pagetitle = "Add Level-2 Sub Category";
// 	$scope.FormAdd = true;	
// 	$scope.FormList = false;	
// 	$scope.GetCat();
// }
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

	$scope.GetList= function()
	{
		$http.get("Load-Level3-subcategory.php",{})
		.then(function successCallback(response)
		{
			var data = response.data;			
			if(data=="NoData")
			{
			//	swal("Empty", "No Records Found", "error");
			//				$timeout(function () { $scope.submitted = false;}, 2000);
				$scope.Levle3Arr = "";
				//$scope.FormList = false;
			}
			else
			{
				// $scope.query = {}
				// $scope.queryBy = '$';
				$scope.Levle3Arr = data;
				//$scope.FormList = true;
			}
		});
	}


	$scope.checkCatgory = function(FormPkId,CatId,SubcatId,Level2PkId,lvl3subcatname)
	{
		$http.post('check-level3-sub-category.php', { 'FormPkId': $scope.FormPkId, 'catname': $scope.CatId, 'subcatname': $scope.SubcatId, 'lvl2subcatname': $scope.Level2PkId, 'lvl3subcatname': $scope.lvl3subcatname })
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

	$scope.Delete = function(Level3PkId)
	{
		$scope.FormAdd = false;	
		$scope.FormList = true;
		$http.post('delete-subcategory3-process.php', { 'PkId': Level3PkId })
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

	$scope.AddLevel3Cat = function (PkId_CategoryMaster,CategoryName,PkId_SubCategoryMaster,SubCategoryName,Level2PkId,Level2SCName)
	{	
		$scope.pagetitle = "Add Level-3 Sub Category";
		$scope.FormAdd = true;	
		$scope.FormList = false;
		$scope.CatId = PkId_CategoryMaster;
		$scope.catname = CategoryName;
		$scope.SubcatId = PkId_SubCategoryMaster;
		$scope.subcatname = SubCategoryName;
		$scope.Level2PkId = Level2PkId;
		$scope.lvl2subcatname = Level2SCName;
	}

	$scope.EditLevel3Cat = function (Level3PkId,Level3SCName,Level2PkId,Level2SCName,PkId_CategoryMaster,CategoryName,PkId_SubCategoryMaster,SubCategoryName)
	{	
		$scope.pagetitle = "Edit Level-3 Sub Category";
		$scope.FormAdd = true;	
		$scope.FormList = false;
		$scope.FormPkId = Level3PkId;
		$scope.lvl3subcatname = Level3SCName;
		$scope.Level2PkId = Level2PkId;
		$scope.lvl2subcatname = Level2SCName;
		$scope.CatId = PkId_CategoryMaster;
		$scope.catname = CategoryName;
		$scope.SubcatId = PkId_SubCategoryMaster;
		$scope.subcatname = SubCategoryName;
		$scope.checkCatgory(Level2PkId,PkId_CategoryMaster,PkId_SubCategoryMaster,Level2PkId,Level3SCName);
	}
	
	
	$scope.AddCategoryData = function ()
	{
		$scope.submitted = true;
		//var config = { params: { AddCategory: AddCategory } };
		//$scope.AddCategory.supervisor: [];
		if($scope.FormPkId==undefined && $scope.NameExists==false)
		{
			$http.post("add-level3-subcategory-process.php",{'catname':$scope.CatId,'subcatname':$scope.SubcatId,'lvl2subcatname':$scope.Level2PkId,'lvl3subcatname':$scope.lvl3subcatname})
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
		else if($scope.NameExists==false)
		{
			$http.post("update-level3-subcategory-process.php",{'FormPkId':$scope.FormPkId,'catname':$scope.CatId,'subcatname':$scope.SubcatId,'lvl2subcatname':$scope.Level2PkId,'lvl3subcatname':$scope.lvl3subcatname})
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
		window.location.href = "sub-category-level3.php";
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