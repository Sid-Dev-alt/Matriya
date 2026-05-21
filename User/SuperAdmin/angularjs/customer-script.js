var AddCategory = angular.module("CategoryModule", ['ui.bootstrap','datatables',])
AddCategory.controller("CategoryController", function ($scope, $timeout, $http, jsonFilter, DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder)
{
	$scope.FormAdd = false;	
	$scope.FormList = true;	
	$scope.pagetitle = "List of Customers";

	$scope.vm = {};
$scope.vm.dtInstance = {};   
$scope.vm.dtColumnDefs = [
//DTColumnDefBuilder.newColumnDef(2).notSortable()
];
$scope.vm.dtOptions = DTOptionsBuilder.newOptions()
          		.withOption('order', [0, 'desc'])
				.withOption('info', false);	

	var logResult = function (data, status, headers, config)
	{
		return data;
	};
$scope.ctype= "Individual";
$scope.GotoAdd = function()
{
	$scope.pagetitle = "Add Customer";
	$scope.FormAdd = true;	
	$scope.FormList = false;
	$scope.GetCustomerId();
	$scope.GetSalutes();
	$scope.GetPaymentTerms();
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
	$scope.GetListData= function()
	{
		$http.get("load-customers.php")
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
				// $scope.query = {}
				// $scope.queryBy = '$';
				$scope.pagedItems = data;
				//$scope.FormList = true;
			}
		});
	}

$scope.salutation = "Mr.";
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

$scope.PaymentTermArr = [];
$scope.GetPaymentTerms = function()
{
    $http.get("load-payment-terms.php")
    .then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{
				$scope.PaymentTermArr = "";
			}
			else
			{
				$scope.PaymentTermArr = data;	
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

	$scope.checkMobile = function(FormPkId,mobileno)
	{
		 $http.post("check-customer-mobile.php",{'FormPkId': FormPkId, 'mobileno': mobileno})
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
		 $http.post("check-customer-email.php",{'FormPkId': FormPkId,'emailid': emailid})
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
		if($scope.FormPkId==undefined)
			{
			$http.post("add-customer-process.php",{
			'CustomerId':$scope.CustomerId,
			'salutation':$scope.salutation,
			'ctype':$scope.ctype,
			'customername':$scope.customername,
			'shopname':$scope.shopname,
			'emailid':$scope.emailid,
			'mobileno':$scope.mobileno,
			'ofcmobileno':$scope.ofcmobileno,
			//'gstin':$scope.gstin,
			//'pan':$scope.pan,
			'billingname':$scope.billingname,
			'billmobileno':$scope.billmobileno,
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

			'shippingname':$scope.shippingname,
			'shipmobileno':$scope.shipmobileno,
			'shipaddress1':$scope.shipaddress1,
			'shipaddress2':$scope.shipaddress2,
			'shiptown':$scope.shiptown,
			'shiplandmark':$scope.shiplandmark,
			'shipcity':$scope.shipcity,
			'shipstate':$scope.shipstate,
			'shipdistrict':$scope.shipdistrict,
			'shippincode':$scope.shippincode,
			'paymentterms':$scope.paymentterms,
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
					
					swal({title: "Success",
							     text: "Customer Created successfully",
							     type: "success",
							     timer: 2000 
						},function () {window.location.reload();})
						
		   			$timeout(function () { $scope.showSuccessAlert = false; window.location.href="list-customers.php";}, 3000);
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
			$http.post("update-customer-process.php",{

				'FormPkId':$scope.FormPkId,
			'CustomerId':$scope.CustomerId,
			'salutation':$scope.salutation,
			'ctype':$scope.ctype,
			'customername':$scope.customername,
			'shopname':$scope.shopname,
			'emailid':$scope.emailid,
			'mobileno':$scope.mobileno,
			'ofcmobileno':$scope.ofcmobileno,
			//'gstin':$scope.gstin,
			//'pan':$scope.pan,
			'billingname':$scope.billingname,
			'billmobileno':$scope.billmobileno,
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

			'shippingname':$scope.shippingname,
			'shipmobileno':$scope.shipmobileno,
			'shipaddress1':$scope.shipaddress1,
			'shipaddress2':$scope.shipaddress2,
			'shiptown':$scope.shiptown,
			'shiplandmark':$scope.shiplandmark,
			'shipcity':$scope.shipcity,
			'shipstate':$scope.shipstate,
			'shipdistrict':$scope.shipdistrict,
			'shippincode':$scope.shippincode,
			'paymentterms':$scope.paymentterms,
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

					swal({title: "Success",
							     text: "Customer updated successfully",
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

	$scope.Delete = function(PkId)
	{
		$scope.FormAdd = false;	
		$scope.FormList = true;
		$http.post('delete-customer-process.php', { 'PkId': PkId })
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data=="Success")
			{
				$scope.submitted = false;
				$scope.FormValid = true;

				swal({title: "Added",
						     text: "Customer deleted successfully",
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

AddCategory.directive('number', function () {
        return {
            require: 'ngModel',
            restrict: 'A',
            link: function (scope, element, attrs, ctrl) {

                ctrl.$parsers.push(function (input) {
                    if (input == undefined) return ''
                    var inputNumber = input.toString().replace(/[^0-9]/g, '');
                    if (inputNumber != input) {
                        ctrl.$setViewValue(inputNumber);
                        ctrl.$render();
                    }
                    return inputNumber;
                });
            }
        };
    });