var AddCategory = angular.module("CategoryModule", ['datatables'])
AddCategory.controller("CategoryController", function ($scope, $timeout, $http, jsonFilter)
{
	$scope.FormAdd = false;	
	$scope.FormList = true;	
	$scope.pagetitle = "List of Vendors";
	

	var logResult = function (data, status, headers, config)
	{
		return data;
	};

$scope.GotoAdd = function()
{
	$scope.pagetitle = "Add Vendor";
	$scope.FormAdd = true;	
	$scope.FormList = false;
	$scope.GetCustomerId();
}
$scope.GetCustomerId = function(){
	$http.get("generate-customer-id.php")
		.then(function successCallback(response)
		{
			var data = response.data;			
			if(data=="")
			{
				//swal("Empty", "No Records Found", "error");$timeout(function () { $scope.submitted = false;}, 2000);
				$scope.CustomerId = "";
			}
			else
			{
				$scope.CustomerId = data;
			}
		});
}
$scope.vendortype = "1";
	$scope.Getlist= function(vendortype)
	{
		$http.post("load-vendors.php",{'vendortype': vendortype})
		.then(function successCallback(response)
		{
			var data = response.data;			
			if(data=="NoData")
			{
				// swal("Empty", "No Records Found", "error");
				// $timeout(function () { $scope.submitted = false;}, 2000);
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


	$scope.View = function(PkId,EmailAdrs,CompanyName,NameoftheProducts,Areyourproductslisted,certifications,ContactName,ContactEmailId,ContactPhone,ContactMethod,Sustainable,Status)
	{
		$scope.pagetitle = "Edit Customer";
		$scope.FormPkId = PkId;
		$scope.EmailAdrs = EmailAdrs;
		$scope.CompanyName = CompanyName;
		$scope.NameoftheProducts = NameoftheProducts;
		$scope.Areyourproductslisted = Areyourproductslisted;
		$scope.certifications = certifications;
		$scope.ContactName = ContactName;
		$scope.ContactEmailId = ContactEmailId;
		$scope.ContactPhone = ContactPhone;
		$scope.ContactMethod = ContactMethod;
		$scope.Sustainable = Sustainable.split(', ');
		$scope.Status = Status;

	}

	$scope.ApproveVendor = function ()
	{
		$scope.submitted = true;
			$http.post("vendor-approve-process.php",{
			'FormPkId':$scope.FormPkId,
			'ContactName':$scope.ContactName,
			'ContactPhone':$scope.ContactPhone,
			'ContactEmailId':$scope.ContactEmailId
			})
			.then(function successCallback(response)
				{
					var data = response.data;
				if(data=="Success")
				{
					swal({title: "Approved",
							     text: "",
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
			
	};
	

	$scope.GotoList = function()
	{		
		window.location.href = "list-vendors.php";
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