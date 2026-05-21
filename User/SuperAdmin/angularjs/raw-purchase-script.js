var AddCategory = angular.module("CategoryModule", ['ui.bootstrap','angularUtils.directives.dirPagination','datatables','ui.select', 'ngSanitize'])

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

AddCategory.controller("CategoryController", function ($scope, $timeout, $http, $window, jsonFilter, DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder)
{
	$scope.FormAdd = false;
	$scope.FormList = true;
	$scope.pagetitle = "List of Raw Purchase";

	var logResult = function (data, status, headers, config)
	{
		return data;
	};
	$scope.Math = $window.Math;

$scope.vm = {};
$scope.vm.dtInstance = {};
$scope.vm.dtColumnDefs = [
//DTColumnDefBuilder.newColumnDef(8).notSortable()
];
$scope.vm.dtOptions = DTOptionsBuilder.newOptions()
          		.withOption('order', [0, 'desc'])
				.withOption('info', false);

	 $scope.entrydate=new Date();
	 $scope.printdate=new Date();
	 $scope.laminatedate=new Date();
	 $scope.slitdate=new Date();
	 $scope.pouchdate=new Date();

	 var mindt = new Date();
  mindt.setDate(mindt.getDate() -90);
	$scope.opened = {};
    $scope.opened.opened1 = false;
    $scope.opened.opened2 = false;
    $scope.opened.opened3 = false;
    $scope.opened.opened4 = false;
    $scope.opened.opened5 = false;

    $scope.singleopen = function($event,datepicker) {
      $event.preventDefault();
      $event.stopPropagation();
      $scope.opened[datepicker] = true;
    };
	$scope.assDate = {
        //dateDisabled: disabled,
        formatYear: 'y',
        maxDate: new Date(2050, 5, 22),
       minDate: new Date(2020, 8, 16),
       showWeeks:false,
        startingDay: 1
    };
	$scope.assDate1 = {
        //dateDisabled: disabled,
        formatYear: 'y',
      maxDate: new Date(),
       minDate: mindt,
       showWeeks:false,
        startingDay: 1
    };


   $(document).ready(function() {
    $("#entrydate").keypress(function (event) { event.preventDefault(); });
    $("#entrydate").keydown(function (event) { event.preventDefault(); });


    $("#printdate").keypress(function (event) { event.preventDefault(); });
    $("#printdate").keydown(function (event) { event.preventDefault(); });

    $("#laminatedate").keypress(function (event) { event.preventDefault(); });
    $("#laminatedate").keydown(function (event) { event.preventDefault(); });

    $("#slitdate").keypress(function (event) { event.preventDefault(); });
    $("#slitdate").keydown(function (event) { event.preventDefault(); });

    $("#pouchdate").keypress(function (event) { event.preventDefault(); });
    $("#pouchdate").keydown(function (event) { event.preventDefault(); });
  });

$scope.quantity = "0.000";
$scope.polyrawweight = "0.000";
$scope.petweight = "0.000";
$scope.metweight = "0.000";
$scope.ldweight = "0.000";

$scope.vendorname="";
$scope.godownname="";
$scope.rawcategory="";
$scope.productsize="";

/*Add & Remove Script Starts Here*/
//to remove the row
$scope.removeRow = function (idx)
{
	$scope.BatchArr.splice(idx, 1);
};

$scope.BatchArr = [];

    $scope.AddMore = function()
    {
    	//$scope.BatchArr.push({productname:{ProductId:"",TotalProductName:""});
      $scope.BatchArr.push({productname:{ProductId:'',TotalProductName:'',Micron:''},quantity:"0.00",DeleteStatus:"0"});
    }

/*Add & Remove Script end Here*/
$scope.itemname = "";
$scope.PushItems = function()
{
	if($scope.itemname=='' || $scope.itemname.ProductId==undefined)
	{
		swal({title: "STOP",
			     text: "Please Select item name",
			     type: "error",
			     timer: 2000
		});
	}
	else if($scope.noofrolls==undefined)
	{
		swal({title: "STOP",
			     text: "Please enter no of rolls",
			     type: "error",
			     timer: 2000
		});
	}
	else
	{
		for (var i = 0; i < $scope.noofrolls; i++) {
		    $scope.BatchArr.push({productname:{ProductId:$scope.itemname.ProductId,TotalProductName:$scope.itemname.TotalProductName,Micron:$scope.itemname.Micron},Unit:$scope.itemname.Unit,quantity:"0.00",DeleteStatus:"0"});
		};
		$scope.itemname = "";
		$scope.noofrolls = "";
		$scope.Unit = "";

	}
}

$scope.GotoAdd = function()
{
	$scope.pagetitle = "Add New";
	$scope.FormAdd = true;
	$scope.FormList = false;
	$scope.GetOrderId();
	$scope.GetVendors();
	// $scope.GetFinshedProducts();
	// $scope.GetPolyster();
	// $scope.GetPet();
	// $scope.GetMet();
	 $scope.GetGoDowns();
	//$scope.GetCategories();
	// $scope.LoadSizes();
	// $scope.LoadColour();
	//$scope.GetPaymentTerms();
	$scope.GetProducts();
}
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
$scope.GoDownArray = [];
$scope.GetGoDowns = function()
{
    $http.get('load-godowns.php')
    .then(function successCallback(response)
	{
		var data = response.data;
    	if(data=="NoData")
		{
			$scope.GoDownArray = "";
		}
		else
		{
			$scope.GoDownArray = data;
		}
		});
}

// $scope.GetCategories = function()
// 	{
//         $http.get('load-raw-category.php')
//         .then(function successCallback(response)
// 		{
// 			var data = response.data;
//         	if(data=="NoData")
// 			{
// 				$scope.CategoryArray = "";
// 			}
// 			else
// 			{
// 				$scope.CategoryArray = data;
// 			}
// 			});
// 	}

$scope.ProductArray = [];
	$scope.GetProducts = function()
	{
        $http.get('load-all-product-types.php')
        .then(function successCallback(response)
		{
			var data = response.data;
        	if(data=="NoData")
			{
				$scope.ProductArray = "";
			}
			else
			{
				$scope.ProductArray = data;
				// for( var i=$scope.ProductArray.length - 1; i>=0; i--){
			 //    for( var j=0; j<$scope.BatchArr.length; j++){
			 //    	//alert($scope.ProductArray[i].ProductId);
			 //    	 if($scope.BatchArr[j].productname !== undefined)
    // 				 {	
    // 					//console.log($scope.BatchArr[j].productname.ProductId);
				//         if($scope.ProductArray[i] && ($scope.ProductArray[i].ProductId === $scope.BatchArr[j].productname.ProductId))
				//         {
				//         	$scope.ProductArray.splice(i, 1);
				//         }
			 //    		}
			 //    	}
				// }
			}
			});
	}
$scope.SetUnit = function (productname,index) {
	//alert(productname.ProductId);
    $scope.BatchArr[index].Unit = productname.Unit;
};


$scope.GetCustomers = function()
	{
        $http.get('get-customers.php')
        .then(function successCallback(response)
		{
			var data = response.data;
        	if(data=="NoData")
			{
				$scope.CustomerArray = "";
			}
			else
			{
				$scope.CustomerArray = data;
			}
			});
	}

$scope.GetRollData= function(RollNo)
	{
		//$scope.mypageno = pageno;

		// $scope.pagedItems = [];
		// if($scope.fromdate > $scope.todate){
	 //    	swal("STOP", 'To Date should be more than From date', "error");
		// 	$timeout(function () { $scope.submitted = false;}, 3000);
	 //    }
	 //    else
	 //    {
	 	//'itemsPerPage': $scope.itemsPerPage, 'pagenumber': pageno, , 'ProductSize': $scope.itemsize.ProductSize
			$http.post("load-roll-history.php", {'rollno': RollNo})
			.then(function successCallback(response)
			{
				var data = response.data; 
				
				if(data=="NoData" || data==""){
          $scope.result = data;
					swal({title: "Empty",
							     text: "No Records Found",
							     type: "error",
							     timer: 2000 
						});
			    }
				else
				{
				    $("#myModal").modal('show');
          $scope.result = "OK";
				    $scope.VendorNameModal = data['VendorName'];
            $scope.IsSplitQty = data['IsSplitQty'];
            $scope.AvlQuantity = data['AvlQuantity'];
            $scope.InvProductSize = data['InvProductSize'];
            $scope.InvUniqueRollNo = data['InvUniqueRollNo'];
            $scope.RawPODateModal = data['RawPODate'];
            $scope.PurchaseRollNo = data['PurchaseRollNo'];
            $scope.PurchaseQtyModal = data['PurchaseQty'];
            $scope.TotalName = data['TotalName'];
            $scope.UnitModal = data['Unit'];
            $scope.POProductSize = data['POProductSize'];
            $scope.GoDownName = data['GoDownName'];
            $scope.data2 = data['data2'];
            $scope.IsSlitted = data['IsSlitted'];
            $scope.SlitDate = data['SlitDate'];
            $scope.SplitSize = data['SplitSize'];
            $scope.SlitQty = data['SlitQty'];
            $scope.IsInvoice = data['IsInvoice'];
            $scope.InvoiceDateModal = data['InvoiceDate'];
            $scope.CustomerNameModal = data['CustomerName'];
				 }
			}, function errorCallback(response) {
	    		// called asynchronously if an error occurs
			    // or server returns response with an error status.
			});
		//}
	}

$scope.DeliveryArray = ['Transport','Courier','Auto','Rikshaw'];
$scope.loading = false;
$scope.pagedItems = []; //declare an empty array
    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 5; //this could be a dynamic value from a drop down

$scope.getData = function(pageno){ 
	$scope.loading = true;
      $scope.mypageno = pageno;
    // This would fetch the data on page change.
        //In practice this should be in a factory.
        $scope.pagedItems = [];  
        
        $http.post("load-raw-purchase.php",{'itemsPerPage': $scope.itemsPerPage, 'pagenumber': pageno})
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
    $scope.sort1 = function(keyname){
        $scope.sortKey1 = keyname;   //set the sortKey to the param passed
        $scope.reverse1 = !$scope.reverse1; //if true make it false and vice versa
    }

$scope.SizeArr = [];
$scope.LoadSizes = function()
  {
    $http.get('load-sizes.php')
    .then(function successCallback(response)
    {
      var data = response.data;
      if(data=="NoData")
      {
        $scope.SizeArr= '';
      }
      else
      {
        $scope.SizeArr = data;
      }
    }, function errorCallback(response) {
        // called asynchronously if an error occurs
        // or server returns response with an error status.
      });
  };

  $scope.ColourArr = [];
$scope.LoadColour = function()
  {
    $http.get('load-colours.php')
    .then(function successCallback(response)
    {
      var data = response.data;
      if(data=="NoData")
      {
        $scope.ColourArr= '';
      }
      else
      {
        $scope.ColourArr = data;
      }
    }, function errorCallback(response) {
        // called asynchronously if an error occurs
        // or server returns response with an error status.
      });
  };

$scope.GetOrderId= function()
	{
		//generate-purchase-id.php
		$http.get("generate-raw-purchase-id.php")
		.then(function successCallback(response)
		{
			var data = response.data;
			$scope.POrderId = data;
		});
	}



$scope.AddCategoryData = function ()
{
	if($scope.AddCategoryForm.$invalid)
	{
	    $scope.submitted = true;
	}
	else
	{
		if($scope.BatchArr.length==0)
		{
			swal("STOP", "Please Select Items", "error");
			$timeout(function () { $scope.submitted = false;}, 3000);
		}
		else if($scope.vendorname==undefined)
		{
			swal("STOP", "Please Select Vendor", "error");
			$timeout(function () { $scope.submitted = false;}, 3000);
		}
		else if($scope.godownname==undefined)
		{
			swal("STOP", "Please Select GoDown", "error");
			$timeout(function () { $scope.submitted = false;}, 3000);
		}
		else
		{
			$scope.loading = true;
		var EntryPkId= new Array();
		var ProductId= new Array();
		var ProductSize = new Array();
		var quantity= new Array();
		var IsSplitQty= new Array();
		var Isinvoiced= new Array();
		var remarks = new Array();

	for(i=0;i<$scope.BatchArr.length;i++)
	    {
		    	EntryPkId.push($scope.BatchArr[i].EntryPkId);
	    	// if($scope.BatchArr[i].productname !== undefined)
    		// {	
		    	ProductId.push($scope.BatchArr[i].productname.ProductId);
			//}

		    ProductSize.push($scope.BatchArr[i].productsize);
			quantity.push($scope.BatchArr[i].quantity);
			IsSplitQty.push($scope.BatchArr[i].IsSplitQty);
			Isinvoiced.push($scope.BatchArr[i].Isinvoiced);
			remarks.push($scope.BatchArr[i].remarks);
		}
		//alert(ProductId);
		if($scope.FormPkId==undefined)
		{
			$scope.FileErr="";
			$http.post('add-raw-purchase-process.php',
			 // fd, {transformRequest: angular.identity,headers: {'Content-Type': undefined},
    //         params:
            {
				'POrderId':$scope.POrderId,
				'entrydate':$scope.entrydate,
				//'referencenum':$scope.referencenum,
				'VendorId':$scope.vendorname.VendorId,
				'VendorName':$scope.vendorname.DisplayName,
				'GoDownId':$scope.godownname.PkId,

				'EntryPkId':EntryPkId,
				'ProductId':ProductId,
				'ProductSize':ProductSize,
				'quantity':quantity,
				'remarks':remarks,
				'cnotes':$scope.cnotes,
			})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.loading = false;
					swal({title: "Added",
							     text: "Purchase Order created successfully",
							     type: "success",
							     timer: 2000
						},function () {window.location.reload();})
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
			$http.post('update-raw-purchase-process.php',
			 // fd, {transformRequest: angular.identity,headers: {'Content-Type': undefined},
    //         params:
            {
				'FormPkId':$scope.FormPkId,
				'POrderId':$scope.POrderId,
				'entrydate':$scope.entrydate,
				//'referencenum':$scope.referencenum,
				'VendorId':$scope.vendorname.VendorId,
				'VendorName':$scope.vendorname.DisplayName,
				'GoDownId':$scope.godownname.PkId,

				'EntryPkId':EntryPkId,
				'ProductId':ProductId,
				'ProductSize':ProductSize,
				'quantity':quantity,
				'IsSplitQty':IsSplitQty,
				'Isinvoiced':Isinvoiced,
                'remarks':remarks,
				'cnotes':$scope.cnotes,
			//},
			

			})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.loading = false;
					$scope.submitted = false;
					$scope.FormValid = true;

					swal({title: "Updated",
							     text: "Purchase details updated successfully",
							     type: "success",
							     timer: 2000
						},function () {})
						$scope.getData($scope.mypageno); // Call the function to fetch initial data on page load.
					$scope.FormAdd = false;
					$scope.FormList = true;
					$scope.pagetitle = "List of Raw Purchase";
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
	}
}

	$scope.GotoList = function()
	{
		$scope.FormAdd = false;
		$scope.FormList = true;
	}
$scope.EditOrder = function(FormPkId,RawPurchaseId,VendorId_VenodrMaster,VendorName,RawPODate,PkId_GoDownMaster,GoDownName,Comments,data2)
{
	$scope.FormList = false;
	$scope.FormAdd = true;
	$scope.FormPkId = FormPkId;
	$scope.POrderId = RawPurchaseId;
	$scope.vendorname = {VendorId:VendorId_VenodrMaster,DisplayName:VendorName};
	//$scope.FormPkId = VendorName;
	$scope.entrydate = new Date(RawPODate);
	$scope.godownname = {PkId:PkId_GoDownMaster,GoDownName:GoDownName};
	$scope.cnotes = Comments;
	//$scope.FormPkId = GoDownName;
    $scope.GetProducts();
	$scope.BatchArr = data2;
	angular.forEach($scope.BatchArr, function (value, key) {
		$scope.BatchArr[key].EntryPkId = value.EntryPkId;
       	$scope.BatchArr[key].productname = {Micron: value.Micron, TotalProductName: value.TotalProductName, ProductId: value.ProductId_ProductMaster, ProductName: value.ProductName, ProductSize: value.ProductSize}; 
       	$scope.BatchArr[key].productsize = value.ProductSize; 
       	$scope.BatchArr[key].quantity = value.PurchaseQty; 
       	$scope.BatchArr[key].IsSplitQty = value.IsSplitQty; 
       	$scope.BatchArr[key].Isinvoiced = value.Isinvoiced;
       	$scope.BatchArr[key].remarks = value.Remarks; 
    }); 
}

$scope.Delete = function(EntryPkId,productname,IsSplitQty,Isinvoiced)
{
	if(IsSplitQty==1)
	{
		swal("STOP", "You cannot delete this item. Slitting already done", "error");
		$timeout(function () { $scope.submitted = false;}, 3000);
	}
	else if(Isinvoiced==1)
	{
		swal("STOP", "You cannot delete this item. Item already dispatched", "error");
		$timeout(function () { $scope.submitted = false;}, 3000);
	}
	else
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
		$http.post('delete-poentry-process.php',
            {
				'EntryPkId':EntryPkId,
				'Micron':productname.Micron,
				'ProductName':productname.ProductName,
				'ProductSize':productname.ProductSize,
			})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.submitted = false;
					$scope.FormValid = true;
					swal({title: "Updated",
						     text: "Purchase entry deleted successfully",
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
}

	/* customer start*/
	$scope.AddVendor  = function()
	{
		$scope.GetVendorId();
		$scope.GetSalutes();
		$scope.GetPaymentTerms();
		$scope.GetStates();
	}
$scope.salutation = "Mr.";
$scope.GetVendorId = function(){
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
		$scope.submitted = true;
		 //&& $scope.MobileExists==false && $scope.EmailExists==false
		
			$http.post("add-supplier-process.php",{
			'VendorId':$scope.VendorId,
			'salutation':$scope.salutation,
			//'ctype':$scope.ctype,
			'shopname':$scope.shopname,
			'address':$scope.address,
			'gstn':$scope.gstn,
			'customername':$scope.customername,
			'contactno':$scope.contactno,
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
						window.scrollTo(500, 0);
						$scope.submitted = false;
						$scope.FormValid = false;
						//$scope.customername="";
						$scope.GetVendors();
							$('#modal-form').on('hidden.bs.modal', function (e) {
					  $(this)
					    .find("input,textarea,select")
					       .val('')
					       .end()
					    .find("input[type=checkbox], input[type=radio]")
					       .prop("checked", "")
					       .end();
					})
							$('#modal-form').modal('hide');
							$scope.GetVendorId();
							
						 swal({title: "success!",
						     text: "Vendor Created successfully.",
						     type: "success",
						     timer: 2000});
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
	/* customer end*/

	// var temp = '0.00';
 //  $(".decimaltemp").focus(function() {
 //    if (temp == this.value)
 //      // ------^-- empty the field only if the field value is initial
 //      this.value = '';
 //      // emptying field value
 //  }).blur(function() {
 //    if (this.value.trim() == '')
 //      // -----^-- condition field value is empty
 //      this.value = temp;
 //      // if empty then updating with initial value
 //  }).val(temp);
  //----^-- setting initial value
});


// AddCategory.directive("typeaheadOpenOnFocus", function() {
// return {

// require: "ngModel",
// link: function(scope, element, attr,ngModel) {
// element.bind('focus',function(){
// if (ngModel && !ngModel.$viewValue) {
// var viewValue = ngModel.$viewValue;
// if (ngModel.$viewValue == ' ' || ngModel.$viewValue == "") {
// ngModel.$setViewValue(null);
// }
// ngModel.$setViewValue(' ');
// ngModel.$setViewValue(viewValue || ' ');
// ngModel.$viewValue = "";
// }
// });
// }
// };
// });

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
 AddCategory.directive('restrictField', function () {
    return {
        restrict: 'AE',
        scope: {
            restrictField: '='
        },
        link: function (scope) {
          // this will match spaces, tabs, line feeds etc
          // you can change this regex as you want
          var regex = /\s/g;

          scope.$watch('restrictField', function (newValue, oldValue) {
              if (newValue != oldValue && regex.test(newValue)) {
                scope.restrictField = newValue.replace(regex, '');
              }
          });
        }
    };
  });

AddCategory.directive('validNumber', function () {
    return {
        require: '?ngModel',
        link: function (scope, element, attrs, ngModelCtrl) {
		var temp = '0.00';
		 element.on('focus', function (event) {
		//$(".decimaltemp").focus(function() {
		    if (temp == this.value)
		      // ------^-- empty the field only if the field value is initial
		      this.value = '';
		      // emptying field value
		  }).on('blur', function () {
		    if (this.value.trim() == '')
		      // -----^-- condition field value is empty
		      this.value = temp;
		      // if empty then updating with initial value
		  }).val(temp);
		  //----^-- setting initial value


		  var temp1 = '0.000';
		 element.on('focus', function (event) {
		//$(".decimaltemp").focus(function() {
		    if (temp1 == this.value)
		      // ------^-- empty the field only if the field value is initial
		      this.value = '';
		      // emptying field value
		  }).on('blur', function () {
		    if (this.value.trim() == '')
		      // -----^-- condition field value is empty
		      this.value = temp1;
		      // if empty then updating with initial value
		  }).val(temp1);
		  //----^-- setting initial value

            element.on('keydown', function (event) {
              var keyCode=[]
              if(attrs.allowNegative == "true")
              { keyCode = [8, 9, 36, 35, 37, 39, 46, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 109, 110, 173, 190,189];
              }
              else{
               var keyCode = [8, 9, 36, 35, 37, 39, 46, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 110, 173, 190];
              }


              if(attrs.allowDecimal == "false") {

                var index = keyCode.indexOf(190);


if (index > -1) {
    keyCode.splice(index, 1);
}

              }

              if ($.inArray(event.which, keyCode) == -1) event.preventDefault();
                else {console.log(2);
                    var oVal = ngModelCtrl.$modelValue || '';
                    if ($.inArray(event.which, [109, 173]) > -1 && oVal.indexOf('-') > -1) event.preventDefault();
                    else if ($.inArray(event.which, [110, 190]) > -1 && oVal.indexOf('.') > -1) event.preventDefault();
                }
            })
            .on('blur', function () {

                if (element.val() == '' || parseFloat(element.val()) == 0.0 || element.val() == '-') {
                    ngModelCtrl.$setViewValue('0.00');
                }
                else if(attrs.allowDecimal == "false")
               {
                 ngModelCtrl.$setViewValue(element.val());
               }
               else{
                 if(attrs.decimalUpto)
                 {
                 var fixedValue = parseFloat(element.val()).toFixed(attrs.decimalUpto);}
                 else{   var fixedValue = parseFloat(element.val()).toFixed(2);}
                 ngModelCtrl.$setViewValue(fixedValue);
               }



                ngModelCtrl.$render();
                scope.$apply();
            });

            ngModelCtrl.$parsers.push(function (text) {
                var oVal = ngModelCtrl.$modelValue;
                var nVal = ngModelCtrl.$viewValue;
console.log(nVal);
                if (parseFloat(nVal) != nVal) {

                    if (nVal === null || nVal === undefined || nVal == '' || nVal == '-') oVal = nVal;

                    ngModelCtrl.$setViewValue(oVal);
                    ngModelCtrl.$render();
                    return oVal;
                }
                else {
                    var decimalCheck = nVal.split('.');
                    if (!angular.isUndefined(decimalCheck[1])) {
                      if(attrs.decimalUpto)
                         decimalCheck[1] = decimalCheck[1].slice(0, attrs.decimalUpto);
                         else
                        decimalCheck[1] = decimalCheck[1].slice(0, 2);
                        nVal = decimalCheck[0] + '.' + decimalCheck[1];
                    }

                    ngModelCtrl.$setViewValue(nVal);
                    ngModelCtrl.$render();
                    return nVal;
                }
            });

            ngModelCtrl.$formatters.push(function (text) {
                if (text == '0' || text == null && attrs.allowDecimal == "false") return '0';
                else if (text == '0' || text == null && attrs.allowDecimal != "false" && attrs.decimalUpto == undefined) return '0.00';
                else if (text == '0' || text == null && attrs.allowDecimal != "false" && attrs.decimalUpto != undefined) return parseFloat(0).toFixed(attrs.decimalUpto);
                else if (attrs.allowDecimal != "false" && attrs.decimalUpto != undefined) return parseFloat(text).toFixed(attrs.decimalUpto);
                else return parseFloat(text).toFixed(2);
            });
        }
    };
});
