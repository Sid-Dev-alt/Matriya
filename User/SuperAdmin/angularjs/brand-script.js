var AddCategory = angular.module("CategoryModule", ['datatables'])
AddCategory.controller("CategoryController", function ($scope, $timeout, $http, jsonFilter, DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder)
{
	$scope.FormAdd = false;	
	$scope.FormList = true;	
	$scope.pagetitle = "List of Brands";
	$scope.FormAdd = false;		
	

	$scope.vm = {};
$scope.vm.dtInstance = {};   
$scope.vm.dtColumnDefs = [
//DTColumnDefBuilder.newColumnDef(2).notSortable()
];
$scope.vm.dtOptions = DTOptionsBuilder.newOptions()
          		.withOption('order', [0, 'desc'])
				.withOption('info', false);

$scope.GotoAdd = function()
{
	$scope.pagetitle = "Add Brand";
	$scope.FormAdd = true;	
	$scope.FormList = false;	
}
	$scope.GetListData= function()
	{
		$http.get("load-brands.php")
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


	$scope.checkCatgory = function(FormPkId,brand)
	{
		$http.post('check-brand.php', { 'FormPkId': $scope.FormPkId,'brand': $scope.brand  })
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
			$http.post("add-brand-process.php",{'brand':$scope.brand})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.submitted = false;
					$scope.FormValid = true;
					swal({title: "Added",
								     text: "Brand added successfully",
								     type: "success",
								     timer: 1000 
							},function () {window.location.reload();})
					
	   			$timeout(function () { $scope.showSuccessAlert = false; window.location.href="list-brands.php";}, 3000);
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
			$http.post("update-brand-process.php",{'FormPkId':$scope.FormPkId,'brand':$scope.brand})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.submitted = false;
					$scope.FormValid = true;

					swal({title: "Updated",
							     text: "Brand updated successfully",
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

	$scope.EditCat = function (PkId,BrandName)
	{
		$scope.pagetitle = "Edit Brand";
		$scope.FormAdd = true;	
		$scope.FormList = false;
		$scope.FormPkId = PkId;
		$scope.brand = BrandName;
		$scope.checkCatgory(PkId,BrandName);
	}
	
	$scope.Delete = function(PkId)
	{
		$scope.FormAdd = false;	
		$scope.FormList = true;
		$http.post('delete-brand-process.php', { 'PkId': PkId })
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data=="Success")
			{
				$scope.submitted = false;
				$scope.FormValid = true;

				swal({title: "Added",
						     text: "Brand deleted successfully",
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
		window.location.href = "list-brands.php";
	}
});
