var AddCategory = angular.module("CategoryModule", ['ui.bootstrap','datatables',])
AddCategory.directive('fileModel', ['$parse', function ($parse) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            var model = $parse(attrs.fileModel);
            var modelSetter = model.assign;

            element.bind('change', function(){
                scope.$apply(function(){
                	//console.log(element[0].files[0]);
                    modelSetter(scope, element[0].files[0]);
                });
            });
        }
    };
}]);

AddCategory.controller("CategoryController", function ($scope, $timeout, $http, jsonFilter)
{
	$scope.FormAdd = true;	
	$scope.FormList = false;	
	$scope.pagetitle = "List of Customers";
	

	var logResult = function (data, status, headers, config)
	{
		return data;
	};

$scope.GotoAdd = function()
{
	$scope.pagetitle = "Add Customer";
	$scope.FormAdd = true;	
	$scope.FormList = false;
	$scope.GetCustomerId();
	$scope.GetSalutes();
	$scope.GetPaymentTerms();
}

	$scope.BusinessArray = ['Agriculturer','Agency', 'Art & Design', 'Constrction', 'Consulting', 'Consumer Packged goods', 'Education', 'Engineering', 'Entertainment', 'Food Services', 'Health Care', 'Manufacturing', 'Marketing', 'Real Estate', 'Web Development'];
	$scope.GetCompanyInfo= function()
	{
		$http.get("load-company-info.php")
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
				$scope.shopname=data[0]['CompanyName'];
				$scope.businesstype=data[0]['BusinessType'];
				$scope.emailid=data[0]['EmailId'];
				$scope.mobileno=data[0]['MobileNo'];
				$scope.ofcmobileno=data[0]['OfcMobileNo'];
				$scope.LogoFilename=data[0]['LogoFilename'];
				$scope.gstin=data[0]['GSTIN'];
				$scope.pan=data[0]['PAN'];
				$scope.address1=data[0]['AddressLane1'];
				$scope.address2=data[0]['AddressLane2'];
				$scope.town=data[0]['Town'];
				$scope.landmark=data[0]['LandMark'];
				$scope.city=data[0]['City'];
				$scope.state=data[0]['State'];
				$scope.district=data[0]['District'];
				$scope.pincode=data[0]['PINCode'];

				//$scope.pagedItems = data[];
			}
		});
	}


$scope.SalutationArr = [];
$scope.GetSalutes = function()
{
    $http.get("load-salutations.php")
    .then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{
				$scope.SalutationArr = "";
			}
			else
			{
				$scope.SalutationArr = data;	
			}
            
       }, function errorCallback(response) {
		// called asynchronously if an error occurs
	    // or server returns response with an error status.
	  });
}

$scope.StateArray = [];
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
$scope.onCSelect = function ($item, $model, $label) {
    $scope.$item = $item;
    $scope.$model = $model;
    $scope.$label = $label;
    $scope.GetDist($item);
};

$scope.DistrictArray = [];
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

	

$scope.ChkdBIll = function(sameasbill)
	{
		if ($scope.sameasbill) {
		$scope.shippingname=$scope.billingname;
		$scope.shipmobileno=$scope.billmobileno;
		$scope.shipaddress1=$scope.address1;
		$scope.shipaddress2=$scope.address2;
		$scope.shiptown=$scope.town;
		$scope.shiplandmark=$scope.landmark;
		$scope.shipcity=$scope.city;
		$scope.shipstate=$scope.state;
		$scope.shipdistrict=$scope.district;
		$scope.shippincode=$scope.pincode;

	}else{
		$scope.shippingname="";
		$scope.shipmobileno="";
		$scope.shipaddress1="";
		$scope.shipaddress2="";
		$scope.shiptown="";
		$scope.shiplandmark="";
		$scope.shipcity="";
		$scope.shipstate="";
		$scope.shipdistrict="";
		$scope.shippincode="";

		}
	}
	
	$scope.AddCustomerData = function ()
	{
		$scope.submitted = true;
		var fd = new FormData();
          var file = $scope.docfile;
         fd.append('file', file);
         $http.post('update-company-info.php', fd, {transformRequest: angular.identity,headers: {'Content-Type': undefined},
			            params: {
					'shopname':$scope.shopname,
					'businesstype':$scope.businesstype,
					'emailid':$scope.emailid,
					'mobileno':$scope.mobileno,
					'ofcmobileno':$scope.ofcmobileno,
					'gstin':$scope.gstin,
					'pan':$scope.pan,
					'LogoFilename':$scope.LogoFilename,
					'address1':$scope.address1,
					'address2':$scope.address2,
					'town':$scope.town,
					'landmark':$scope.landmark,
					'city':$scope.city,
					'state':$scope.state,
					'district':$scope.district,
					'pincode':$scope.pincode,
		}
			})
			.then(function successCallback(response)
				{
					var data = response.data;
				if(data=="Success")
				{
					window.scrollTo(500, 0);
					$scope.submitted = false;
					$scope.FormValid = true;
					
					swal({title: "Success",
							     text: "Details updated successfully",
							     type: "success",
							     timer: 2500 
						},function () {window.location.reload();})
						
		   			$timeout(function () { $scope.showSuccessAlert = false; window.location.reload();}, 2500);
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
	
	$scope.EditSupplier = function(PkId,CustomerId,CustomerType,Salutation,DisplayName,CompanyName,EmailId,Mobile,WorkPhone,PaymentTerms,BillingName,BillAddressLane1,BillAddressLane2,BillTown,BillLandmark,BillCity,BillState,BillDistrict,BillZipcode,BillPhone,ShipName,ShipAddressLane1,ShipAddressLane2,ShipTown,ShipLandmark,ShipCity,ShipState,ShipDistrict,ShipZipcode,ShipPhone)
	{
		$scope.pagetitle = "Edit Customer";
		$scope.FormAdd = true;
		$scope.FormList = false;
		$scope.GetSalutes();
	$scope.GetPaymentTerms();
		$scope.checkEmail(EmailId);
		$scope.checkMobile(Mobile);
		//$scope.GetDist(State);
		$scope.FormPkId = PkId;
		$scope.CustomerId = CustomerId;
		$scope.ctype = CustomerType;
		$scope.salutation = Salutation;
		
		$scope.customername = DisplayName;
		$scope.shopname = CompanyName;
		$scope.emailid = EmailId;
		$scope.mobileno = Mobile;
		$scope.ofcmobileno = WorkPhone;
		$scope.paymentterms = PaymentTerms;
		$scope.billingname = BillingName;
		
		$scope.address1 = BillAddressLane1;
		$scope.address2 = BillAddressLane2;
		$scope.town = BillTown;
		$scope.landmark = BillLandmark;
		$scope.city = BillCity;
		$scope.state = BillState;
		$scope.district = BillDistrict;
		$scope.pincode = BillZipcode;
		$scope.billmobileno = BillPhone;

		$scope.shippingname = ShipName;
		$scope.shipaddress1 = ShipAddressLane1;
		$scope.shipaddress2 = ShipAddressLane2;
		$scope.shiptown = ShipTown;
		$scope.shiplandmark = ShipLandmark;
		$scope.shipcity = ShipCity;
		$scope.shipstate = ShipState;
		$scope.shipdistrict = ShipDistrict;
		$scope.shippincode = ShipZipcode;
		$scope.shipmobileno = ShipPhone;

	}
	$scope.GotoList = function()
	{		
		window.location.href = "list-customers.php";
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