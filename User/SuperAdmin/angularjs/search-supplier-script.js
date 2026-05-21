var AddCategory = angular.module("CategoryModule", ['ui.bootstrap','datatables','angularUtils.directives.dirPagination','ui.select', 'ngSanitize'])
AddCategory.controller("CategoryController", function ($scope, $timeout, $http, jsonFilter, DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder)
{
	$scope.FormAdd = false;	
	$scope.FormList = true;	
	$scope.pagetitle = "List of Party's";
	
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

$scope.GotoAdd = function()
{
	$scope.pagetitle = "Add Party";
	$scope.FormAdd = true;	
	$scope.FormList = false;
	$scope.GetCustomerId();
	$scope.GetSalutes();
	$scope.GetPaymentTerms();
}
$scope.GetCustomerId = function(){
	$http.get("generate-vendor-id.php")
		.then(function successCallback(response)
		{
			var data = response.data;			
			if(data=="")
			{
				//swal("Empty", "No Records Found", "error");$timeout(function () { $scope.submitted = false;}, 2000);
				$scope.VendorId = "";
			}
			else
			{
				$scope.VendorId = data;
			}
		});
}

$scope.partyname = "";

$scope.VendorArray = [];
$scope.GetVendors = function()
{
    $http.get('load-vendors.php')
    .then(function successCallback(response)
	{
		var data = response.data;
    	if(data=="NoData")
		{
			$scope.VendorArray = "";
		}
		else
		{
			$scope.VendorArray = data;
		}
	});
}


	$scope.pagedItems = []; //declare an empty array
    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 10; //this could be a dynamic value from a drop down
$scope.loading = false;
$scope.getData = function(pageno){ 
	$scope.loading = true;
      $scope.mypageno = pageno;
    // This would fetch the data on page change.
        //In practice this should be in a factory.
        $scope.pagedItems = [];  
        
        $http.post("load-search-suppliers.php",{'itemsPerPage': $scope.itemsPerPage, 'pagenumber': pageno,'searchpartyid':$scope.searchpartyid,'partyname':$scope.partyname.DisplayName})
          .then(function successCallback(response)
        {
            var data = response.data;
            $scope.pagedItems = data['data1'];  // data to be displayed on current page.
            $scope.total_count = data['Total']; // total data count.
            $scope.loading = false;
        }, function errorCallback(response) {
    // called asynchronously if an error occurs
      // or server returns response with an error status.
      });
    };
    $scope.getData($scope.pageno); // Call the function to fetch initial data on page load.
    $scope.sort = function(keyname){
        $scope.sortKey = keyname;   //set the sortKey to the param passed
        $scope.reverse = !$scope.reverse; //if true make it false and vice versa
    }
    
$scope.Clear = function()
{
	$scope.searchtype = "";
	$scope.searchpartyid = "";
	$scope.partyname = "";
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

	// $scope.checkMobile = function(FormPkId,mobileno)
	// {
	// 	 $http.post("check-customer-mobile.php",{'FormPkId': FormPkId, 'mobileno': mobileno})
	// 	.then(function successCallback(response)
	// 	{
	// 		var data = response.data;
	// 		if(data!="OK")
	// 		{
	// 			$scope.MobileExists = true;
	// 		}
	// 		else
	// 		{
	// 			$scope.MobileExists = false;
	// 		}
	// 	}, function errorCallback(response) {
	//     		// called asynchronously if an error occurs
	// 		    // or server returns response with an error status.
	// 		  });
	// };

	// $scope.checkEmail = function(FormPkId,emailid)
	// {
	// 	 $http.post("check-supplier-email.php",{'FormPkId': FormPkId,'emailid': emailid})
	// 	.then(function successCallback(response)
	// 	{
	// 		var data = response.data;
	// 		if(data!="OK")
	// 		{
	// 			$scope.EmailExists = true;
	// 		}
	// 		else
	// 		{
	// 			$scope.EmailExists = false;
	// 		}
	// 	}, function errorCallback(response) {
	//     		// called asynchronously if an error occurs
	// 		    // or server returns response with an error status.
	// 		  });
	// };

// $scope.ChkdBIll = function(sameasbill)
// 	{
// 		if ($scope.sameasbill) {
// 		$scope.shippingname=$scope.billingname;
// 		$scope.shipmobileno=$scope.billmobileno;
// 		$scope.shipaddress1=$scope.address1;
// 		$scope.shipaddress2=$scope.address2;
// 		$scope.shiptown=$scope.town;
// 		$scope.shiplandmark=$scope.landmark;
// 		$scope.shipcity=$scope.city;
// 		$scope.shipstate=$scope.state;
// 		$scope.shipdistrict=$scope.district;
// 		$scope.shippincode=$scope.pincode;

// 	}else{
// 		$scope.shippingname="";
// 		$scope.shipmobileno="";
// 		$scope.shipaddress1="";
// 		$scope.shipaddress2="";
// 		$scope.shiptown="";
// 		$scope.shiplandmark="";
// 		$scope.shipcity="";
// 		$scope.shipstate="";
// 		$scope.shipdistrict="";
// 		$scope.shippincode="";

// 		}
// 	}
	
$scope.AddCustomerData = function ()
{
	if($scope.AddCustomerForm.$invalid)
	{
	    $scope.submitted = true;
	}
	else
	{
		 //&& $scope.MobileExists==false && $scope.EmailExists==false
		if($scope.FormPkId==undefined)
		{
			$scope.loading = true;
			$http.post("add-supplier-process.php",{
			'VendorId':$scope.VendorId,
			'salutation':$scope.salutation,
			//'ctype':$scope.ctype,
			'shopname':$scope.shopname,
			'address':$scope.address,
			'gstn':$scope.gstn,
			'customername':$scope.customername,
			'contact':$scope.contact,
			'emailid':$scope.emailid,
			//'mobileno':$scope.mobileno,
			//'ofcmobileno':$scope.ofcmobileno,
			'paymentterms':$scope.paymentterms,
			'remarks':$scope.remarks,
			'pan':$scope.pan,
			// 'billingname':$scope.billingname,
			// 'billmobileno':$scope.billmobileno,
			// 'address1':$scope.address1,
			// 'address2':$scope.address2,
			// 'town':$scope.town,
			// 'landmark':$scope.landmark,
			// 'city':$scope.city,
			// 'state':$scope.state,
			// 'district':$scope.district,
			// 'pincode':$scope.pincode,
			// 'lattitude':$scope.lattitude,
			// 'longitude':$scope.longitude,

			// 'shippingname':$scope.shippingname,
			// 'shipmobileno':$scope.shipmobileno,
			// 'shipaddress1':$scope.shipaddress1,
			// 'shipaddress2':$scope.shipaddress2,
			// 'shiptown':$scope.shiptown,
			// 'shiplandmark':$scope.shiplandmark,
			// 'shipcity':$scope.shipcity,
			// 'shipstate':$scope.shipstate,
			// 'shipdistrict':$scope.shipdistrict,
			// 'shippincode':$scope.shippincode,
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
					$scope.loading = false;
					window.scrollTo(500, 0);
					$scope.submitted = false;
					$scope.FormValid = true;
					
					swal({title: "",
							     text: "Party Created successfully",
							     type: "success",
							     timer: 2000 
						},function () {window.location.reload();})
						
		   			$timeout(function () { $scope.showSuccessAlert = false; window.location.href="list-suppliers.php";}, 3000);
				}
				else
				{
					$scope.loading = false;
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
			$scope.loading = true;
			$http.post("update-supplier-process.php",{

				'FormPkId':$scope.FormPkId,
			'VendorId':$scope.VendorId,
			'salutation':$scope.salutation,
			//'ctype':$scope.ctype,
			'shopname':$scope.shopname,
			'address':$scope.address,
			'gstn':$scope.gstn,
			'customername':$scope.customername,
			'contact':$scope.contact,
			'emailid':$scope.emailid,
			//'mobileno':$scope.mobileno,
			//'ofcmobileno':$scope.ofcmobileno,
			'paymentterms':$scope.paymentterms,
			'remarks':$scope.remarks,
			'pan':$scope.pan,
			
			// 'billingname':$scope.billingname,
			// 'billmobileno':$scope.billmobileno,
			// 'address1':$scope.address1,
			// 'address2':$scope.address2,
			// 'town':$scope.town,
			// 'landmark':$scope.landmark,
			// 'city':$scope.city,
			// 'state':$scope.state,
			// 'district':$scope.district,
			// 'pincode':$scope.pincode,
			// 'lattitude':$scope.lattitude,
			// 'longitude':$scope.longitude,

			// 'shippingname':$scope.shippingname,
			// 'shipmobileno':$scope.shipmobileno,
			// 'shipaddress1':$scope.shipaddress1,
			// 'shipaddress2':$scope.shipaddress2,
			// 'shiptown':$scope.shiptown,
			// 'shiplandmark':$scope.shiplandmark,
			// 'shipcity':$scope.shipcity,
			// 'shipstate':$scope.shipstate,
			// 'shipdistrict':$scope.shipdistrict,
			// 'shippincode':$scope.shippincode,
			// 'paymentterms':$scope.paymentterms,
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
					$scope.loading = false;
					$scope.submitted = false;
					$scope.FormValid = true;

					swal({title: "",
							     text: "Party updated successfully",
							     type: "success",
							     timer: 3000 
						},function () {})
						$scope.getData($scope.mypageno); // Call the function to fetch initial data on page load.
					$scope.FormAdd = false;
					$scope.FormList = true;
					$scope.pagetitle = "List of Party's";
				}
				else
				{
					$scope.loading = false;
					swal("STOP", data, "error");
					$timeout(function () { $scope.submitted = false;}, 3000);
				}
			}, function errorCallback(response) {
	    		// called asynchronously if an error occurs
			    // or server returns response with an error status.
			  });
		}	
	}	
};
	
	$scope.EditSupplier = function(PkId,VendorId,Salutation,DisplayName,Address,GSTNo,CustomerName,ContactNo,EmailId,Remarks,PaymentTerms)
	{
		$scope.pagetitle = "Edit Party";
		$scope.FormAdd = true;
		$scope.FormList = false;
		$scope.GetSalutes();
	$scope.GetPaymentTerms();
		// $scope.checkEmail(EmailId);
		// $scope.checkMobile(Mobile);
		//$scope.GetDist(State);
		$scope.FormPkId = PkId;
		$scope.VendorId = VendorId;
		$scope.salutation = Salutation;		
		$scope.shopname = DisplayName;
		$scope.address = Address;
		$scope.gstn = GSTNo;
		$scope.customername = CustomerName;
		$scope.contact = ContactNo;
		$scope.emailid = EmailId;
		$scope.remarks = Remarks;
		//$scope.ofcmobileno = WorkPhone;
		$scope.paymentterms = PaymentTerms;
		// $scope.billingname = BillingName;
		
		// $scope.address1 = BillAddressLane1;
		// $scope.address2 = BillAddressLane2;
		// $scope.town = BillTown;
		// $scope.landmark = BillLandmark;
		// $scope.city = BillCity;
		// $scope.state = BillState;
		// $scope.district = BillDistrict;
		// $scope.pincode = BillZipcode;
		// $scope.billmobileno = BillPhone;

		// $scope.shippingname = ShipName;
		// $scope.shipaddress1 = ShipAddressLane1;
		// $scope.shipaddress2 = ShipAddressLane2;
		// $scope.shiptown = ShipTown;
		// $scope.shiplandmark = ShipLandmark;
		// $scope.shipcity = ShipCity;
		// $scope.shipstate = ShipState;
		// $scope.shipdistrict = ShipDistrict;
		// $scope.shippincode = ShipZipcode;
		// $scope.shipmobileno = ShipPhone;

	}

	
	$scope.Delete = function(PkId)
	{
		swal({
	     title: '',
	     text: "Are you sure want to delete?",
	     type: "warning",
	     showCancelButton: true,
	     confirmButtonClass: "btn-danger",
	     confirmButtonText: "Yes, delete it!",
	     cancelButtonText: "No, cancel!",
	     closeOnConfirm: false,
	     closeOnCancel: true,
	       html: true
	   },
		// $scope.FormAdd = false;	
		// $scope.FormList = true;
		function(isConfirm) {
     	if (isConfirm) {
		$scope.FormAdd = false;	
		$scope.FormList = true;
		$http.post('delete-supplier-process.php', { 'PkId': PkId })
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data=="Success")
			{
				$scope.submitted = false;
				$scope.FormValid = true;

				swal({title: "Added",
						     text: "Party deleted successfully",
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
	  } else {
       	swal("Cancelled", "Your record is safe :)", "error");
     		}
     		});
	}
	
	$scope.GotoList = function()
	{		
		$scope.FormAdd = false;
		$scope.FormList = true;
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
