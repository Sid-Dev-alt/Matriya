var AddCategory = angular.module("CategoryModule", ['datatables'])
AddCategory.controller("CategoryController", function ($scope, $timeout, $http, jsonFilter)
{
	$scope.FormAdd = false;	
	$scope.FormList = true;	
	$scope.pagetitle = "List of Sizes";
	


$scope.GotoAdd = function()
{
	$scope.pagetitle = "Add Size";
	$scope.FormAdd = true;	
	$scope.FormList = false;	
}
	$scope.GetListData= function()
	{
		$http.get("load-sizes.php")
		.then(function successCallback(response)
		{
			var data = response.data;			
			if(data=="NoData")
			{
				// swal("Empty", "No Records Found", "error");
				// 			$timeout(function () { $scope.submitted = false;}, 2000);
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


	$scope.checkCatgory = function(FormPkId,size)
	{
		$http.post('check-size.php', { 'FormPkId': $scope.FormPkId,'size': $scope.size  })
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data!="OK")
			{
				$scope.NameExists = true;
			}
			else
			{
				$scope.NameExists = false;
			}
		}, function errorCallback(response) {
    		// called asynchronously if an error occurs
		    // or server returns response with an error status.
		  });
	};

	/*Add & Remove Script Starts Here*/
		//to remove the row
		$scope.removeRow = function (idx) 
		{
			$scope.ProductData.splice(idx, 1);
		};

		$scope.ProductData = [{}];


		$scope.addFormField = function() 
		{
			$scope.ProductData.push({});
		}
		/*Add & Remove Script end Here*/


	$scope.AddCategoryData = function ()
	{
		$scope.submitted = true;
		if($scope.FormPkId==undefined && $scope.NameExists==false)
		{
			$http.post("add-size-process.php",{'size':$scope.size})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.submitted = false;
					$scope.FormValid = true;
					swal({title: "Added",
								     text: "Size added successfully",
								     type: "success",
								     timer: 1000 
							},function () {window.location.reload();})
					
	   			$timeout(function () { $scope.showSuccessAlert = false; window.location.href="list-sizes.php";}, 3000);
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
			//alert("update");
			$http.post("update-size-process.php",{'FormPkId':$scope.FormPkId,'size':$scope.size})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.submitted = false;
					$scope.FormValid = true;

					swal({title: "Updated",
							     text: "Size updated successfully",
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

	$scope.EditCat = function (PkId,Size)
	{
		$scope.pagetitle = "Edit Size";
		$scope.FormAdd = true;	
		$scope.FormList = false;
		$scope.FormPkId = PkId;
		$scope.size = Size;
		$scope.checkCatgory(PkId,Size);
	}
	
	$scope.Delete = function(PkId)
	{
		$scope.FormAdd = false;	
		$scope.FormList = true;
		$http.post('delete-size-process.php', { 'PkId': PkId })
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data=="Success")
			{
				$scope.submitted = false;
				$scope.FormValid = true;

				swal({title: "Added",
						     text: "Size deleted successfully",
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

	$scope.GotoList = function()
	{		
		window.location.href = "list-sizes.php";
	}
});
