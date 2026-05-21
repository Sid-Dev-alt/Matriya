var AddCategory = angular.module("CategoryModule", ['ui.bootstrap','angularUtils.directives.dirPagination','ngPatternRestrict','ui.select', 'ngSanitize'])

.filter('totalSumPriceQty', function() {
    return function(data, key1, key2) {
      if (angular.isUndefined(data) || angular.isUndefined(key1) || angular.isUndefined(key2))
        return 0;

      var sum = 0;
      angular.forEach(data, function(v, k) {
        sum = sum + (parseInt(v[key1]) * parseInt(v[key2]));
      });
      return sum || 0;
    }
});
AddCategory.directive('fileModel', ['$parse', function ($parse) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            var model = $parse(attrs.fileModel);
            var modelSetter = model.assign;

            element.bind('change', function(){
                scope.$apply(function(){
                    modelSetter(scope, element[0].files[0]);
                });
            });
        }
    };
}]);
AddCategory.directive('autofocus', ['$timeout', function($timeout) {
  return {
    restrict: 'A',
    link : function($scope, $element) {
      $timeout(function() {
        $element[0].focus();
      });
    }
  }
}]);

AddCategory.service('fileUpload', ['$http', function ($http) {
    this.uploadFileToUrl = function(file, uploadUrl){
        var fd = new FormData();
        fd.append('file', file);
        $http.post(uploadUrl, fd, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
        })
        .success(function(data){
      
        })
        .error(function(){
        });
    }
}]);
AddCategory.controller('CategoryController', ['$scope','$timeout', '$http','fileUpload', function($scope, $timeout, $http, jsonFilter, fileUpload) 
 {  

	$scope.FormAdd = false;	
	$scope.FormList = true;		
	$scope.pagetitle = "List of Bills";

	$scope.entrydate=new Date();
	var mindt = new Date();
	mindt.setDate(mindt.getDate() -90);

	$scope.opened = {};
    $scope.opened.opened1 = false;
    $scope.opened.opened2 = false;
    
    $scope.singleopen = function($event,datepicker) {
      $event.preventDefault();
      $event.stopPropagation();
      $scope.opened[datepicker] = true;
    };
	$scope.assDate = {
        //dateDisabled: disabled,
        formatYear: 'y',
        maxDate: new Date(2050, 5, 22),
       minDate: mindt,
       showWeeks:false,
        startingDay: 1
    };
	$scope.assDate1 = {
        //dateDisabled: disabled,
        formatYear: 'y',
      maxDate: new Date(2050, 5, 22),
       minDate: mindt,
       showWeeks:false,
        startingDay: 1
    };
	
	var logResult = function (data, status, headers, config)
	{
		return data;
	};
	 $(document).ready(function() {
	 	//$("#barcodeInput").keypress(function (event) { event.preventDefault(); });
    $("#entrydate").keypress(function (event) { event.preventDefault(); });
    $("#entrydate").keydown(function (event) { event.preventDefault(); });


    $("#shipdate").keypress(function (event) { event.preventDefault(); });
    $("#shipdate").keydown(function (event) { event.preventDefault(); });
  });
	

		//var vm = this;
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
        
        $http.post("load-invoices.php",{'itemsPerPage': $scope.itemsPerPage, 'pagenumber': pageno})
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


/*Add & Remove Script Starts Here*/
//to remove the row
$scope.removeRow = function (idx) 
{
	$scope.BatchArr.splice(idx, 1);
};

$scope.BatchArr = [];

    // $scope.AddMore = function() 
    // {
    //   $scope.BatchArr.push({price:0});
    // }

/*Add & Remove Script end Here*/
//$scope.POArr = {};

// dropdown for phases 1-100
	$scope.GSTArray = [];
      var len=30;
      for(var i=0;i<31;i++)
      $scope.GSTArray.push(i);
$scope.GotoAdd = function()
{
	$scope.pagetitle = "Add New";
	$scope.FormAdd = true;	
	$scope.FormList = false;
	$scope.GetGrnId();
	//$scope.GetPaymentTerms();
	$scope.GetPurchaseBars();
	$scope.GetCustomers();
}
$scope.BarArr = [];
$scope.GetPurchaseBars = function()
{
	$http.post("Get-Inventory.php")
    .then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{
				$scope.BarArr="";
				//$scope.duedate = "";
				//swal("STOP", "No Item Found", "error");
				swal({
			    title: "No Item Found!",
			    text: "",
			    timer: 1500,
			    type:"error",
			    showConfirmButton: false
			  });
				//$timeout(function () { $scope.submitted = false;}, 1000);
			}
			else
			{
				$scope.BarArr = data;
				 for( var i=$scope.BarArr.length - 1; i>=0; i--){
			    for( var j=0; j<$scope.BatchArr.length; j++){
			        if($scope.BarArr[i] && ($scope.BarArr[i].InvPkId === $scope.BatchArr[j].InvPkId)){
			            $scope.BarArr.splice(i, 1);
			        }
			    	}
				}
			}
            
       }, function errorCallback(response) {
		// called asynchronously if an error occurs
	    // or server returns response with an error status.
	  });
}
$scope.barcode="";
$scope.GetSelectBar = function()
{
	$scope.barcodeInput=$scope.barcode.UniqueRollNo; 
	$scope.GetBar();
	$scope.GetPurchaseBars();
}

$scope.GetBar = function()
{
	//console.log($scope.barcodeInput);
	$http.post("Get-Invoice-Inventory.php",{'barcodeInput': $scope.barcodeInput})
    .then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{
				$scope.barcodeInput="";
				$scope.barcode = "";
				swal({
			    title: "No Item Found!",
			    text: "",
			    timer: 1500,
			    type:"error",
			    showConfirmButton: false
			  });
				//$timeout(function () { $scope.submitted = false;}, 1000);
			}
			else
			{
				 
				$scope.barcodeInput="";
				$scope.barcode = "";
	              index = $scope.BatchArr.findIndex(x => x.InvPkId ===data['InvPkId']);
	               //console.log(index);
	               	if(index !== -1)
	               	{
	               		swal({
					    title: "Item already added!",
					    text: "",
					    timer: 1500,
					    type:"error",
					    showConfirmButton: false
					  });
	               		// $scope.BatchArr[index].quantity = parseFloat($scope.BatchArr[index].quantity)+"0.000";
	               		// $scope.GetSelectBar();
	               		// $scope.BarArr.splice(index, 1);

	               		// $scope.ChkQty($scope.BatchArr[index].AvlQty,$scope.BatchArr[index].quantity,index);
	                }
	                else
	                {
	                	//console.log("Value Not exists!");
						 	$scope.BatchArr.push({
							InvPkId:data['InvPkId'],
							PkId_RawPurchaseMasterDetails:data['PkId_RawPurchaseMasterDetails'],
							ProductId:data['ProductId'],
							ProductName:data['ProductName'],
							Size:data['Size'],
							Unit:data['Unit'],
							Micron:data['Micron'],
							UniqueRollNo:data['UniqueRollNo'],
							GoDownName:data['GoDownName'],
							AvlQty:data['quantity'],
							DeleteStatus:0,
							//quantity:"0.000",
							});
	                }
			}
			// else
			// {
			// 	$scope.barcodeInput = "";
			// 	$scope.InvPkId = data['InvPkId'];
			// 	$scope.PkId_RawPurchaseMasterDetails = data['PkId_RawPurchaseMasterDetails'];
			// 	$scope.ProductId = data['ProductId'];
			// 	$scope.ProductName = data['ProductName'];
			// 	$scope.Size = data['Size'];
			// 	$scope.Unit = data['Unit'];
			// 	$scope.Micron = data['Micron'];
			// 	$scope.UniqueRollNo = data['UniqueRollNo'];
			// 	$scope.GoDownName = data['GoDownName'];
			// 	$scope.Avlqty = data['quantity'];
			// }
            
       }, function errorCallback(response) {
		// called asynchronously if an error occurs
	    // or server returns response with an error status.
	  });
}
$scope.SetEachProduct = function()
{

	$http.post("Get-Inventory-Data.php",{'eachproductname': $scope.eachproductname.InvPkId})
    .then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{
				$('#myModal').modal('hide');
				$scope.eachproductname = "";
				$scope.barcodeInput="";
				//$scope.duedate = "";
				//swal("STOP", "No Item Found", "error");
				swal({
			    title: "No Item Found!",
			    text: "",
			    timer: 1500,
			    type:"error",
			    showConfirmButton: false
			  });
				//$timeout(function () { $scope.submitted = false;}, 1000);
			}
			else
			{
				 $scope.barcodeInput="";
				 $('#myModal').modal('hide');
				$scope.eachproductname = "";
	              index = $scope.BatchArr.findIndex(x => x.InvPkId ===data['InvPkId']);
	               //console.log(index);
	               	if(index !== -1)
	               	{
	               		$scope.BatchArr[index].quantity = Number($scope.BatchArr[index].quantity)+1;
	               		$scope.ChkQty($scope.BatchArr[index].AvlQty,$scope.BatchArr[index].quantity,index);
	                }
	                else
	                {
	                	//console.log("Value Not exists!");
						 	$scope.BatchArr.push({
							InvPkId:data['InvPkId'],
							ProductId:data['ProductId'],
							ProductName:data['ProductName'],
							Brand:data['Brand'],
							BarCode:data['BarCode'],
							HSN:data['HSN'],
							Size:data['Size'],
							Colour:data['Colour'],
							AvlQty:data['AvlQty'],
							quantity:1,
							gstrate:0,
							price:data['price'],
							DeleteStatus:0,
							});
	                }
			}
            
       }, function errorCallback(response) {
		// called asynchronously if an error occurs
	    // or server returns response with an error status.
	  });
}
$scope.GetDueDate = function(entrydate,paymentterms)
{
	$http.post("Get-Due-Date.php",{'entrydate': entrydate,'paymentterms': paymentterms})
    .then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{
				$scope.duedate = "";
			}
			else
			{
				$scope.duedate = new Date(data);	
			}
            
       }, function errorCallback(response) {
		// called asynchronously if an error occurs
	    // or server returns response with an error status.
	  });
}
$scope.customername=[];
$scope.CustomerArray=[];
$scope.GetCustomers = function()
	{
        $http.get('load-customers.php')
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

 $scope.GetCustomerShip = function (customername) {
    $scope.customermobileno = customername.Mobile;
};

$scope.GetGrnId = function()
	{
		$http.get("generate-invoice-id.php")
		.then(function successCallback(response)
		{
			var data = response.data;
			$scope.InvoiceId = data;
		});
	}
	$scope.ChkQty = function(AvlQty,quantity,index)
	{
		if(parseFloat(quantity)>parseFloat(AvlQty))
		{
			$scope.BatchArr[index].QtyError = "Qty is more than Avl Qty";	
		}
		else
		{
			$scope.BatchArr[index].QtyError = "";	
		}
	}


// dropdown for phases 1-100
	$scope.GSTArray = [];
      var len=30;
      for(var i=0;i<31;i++)
      $scope.GSTArray.push(i);


$scope.onInvSelect = function ($item, $model, $label, index) {
    $scope.$item = $item;
    $scope.BatchArr[index].ProductId = $item.ProductId;
    $scope.BatchArr[index].product = $item.PName;
    $scope.BatchArr[index].price = $item.SalesPrice;
    $scope.BatchArr[index].AvlQty = $item.quantity;
    $scope.BatchArr[index].batchno = $item.batchno;
    $scope.BatchArr[index].SKU = $item.SKU;
    $scope.BatchArr[index].TrackingMode = $item.TrackingMode;
    $scope.BatchArr[index].InvArr = $item.InvArr;
    $scope.$model = $model;
    $scope.$label = $label;
};

$scope.disctype = "Percent";
$scope.gstperc = "0";
$scope.gstpercamt = "0.00";
$scope.discvalue = "0.00";
$scope.additionalcharges = "0.00";  
$scope.returnamt = "0.00";

// $scope.CategoryArr = [];
//  $scope.LoadCatgory = function()
// 	{
// 		$http.get('load-category.php')
// 		.then(function successCallback(response)
// 		{
// 			var data = response.data;
// 			if(data=="NoData")
// 			{
// 				$scope.CategoryArr= '';
// 			}
// 			else
// 			{
// 				$scope.CategoryArr = data;
				
// 			}
// 		}, function errorCallback(response) {
//     		// called asynchronously if an error occurs
// 		    // or server returns response with an error status.
// 		  });
// 	};

// 	$scope.SubCategoryArr = [];
// 	//$scope.ChkSubCat = [];
// 	$scope.GetSubCatgory = function(category,index)
// 	{	
// 		// if($scope.SubCategoryArr[index] == undefined){
// 		$http.post('Get-subcategory.php',{'category': category})
// 		.then(function successCallback(response)
// 		{
// 			var data = response.data;
			
// 			if(data=="NoData")
// 			{
// 				$scope.SubCategoryArr[index] = '';
// 				//$scope.ChkSubCat[index] = "No Data";
// 			}
// 			else
// 			{
// 				$scope.SubCategoryArr[index] = data;
// 				//$scope.ChkSubCat[index] = '';
// 				$scope.Level2Arr[index]= '';
// 				//$scope.BrandArray[index] = "";
// 			}
// 		}, function errorCallback(response) {
//     		// called asynchronously if an error occurs
// 		    // or server returns response with an error status.
// 		  });
// 	//}
// 	};
	
// 	$scope.Level2Arr = [];
// 	//$scope.ChkL2 = [];
// 	$scope.GetLevel2 = function(category,subcategory,index)
// 	{
// 		// if($scope.Level2Arr[index] !== undefined){
// 		$http.post('Get-Level2.php',{'category': category,'subcategory': subcategory})
// 		.then(function successCallback(response)
// 		{
// 			var data = response.data;
// 			if(data=="NoData")
// 			{
// 				$scope.Level2Arr[index]= '';
// 				//$scope.ChkL2 = "No Data";
// 			}
// 			else
// 			{
// 				$scope.Level2Arr[index] = data;
// 			//	$scope.ChkL2 = "";
// 			//	$scope.BrandArray[index] = "";
// 			}
		
// 		}, function errorCallback(response) {
//     		// called asynchronously if an error occurs
// 		    // or server returns response with an error status.
// 		  });
// 		//}
// 	};

	// $scope.GetLevel3 = function(category,subcategory,lvl2subcat)
	// {
	// 	$http.post('Get-Level3.php',{'category': category,'subcategory': subcategory,'lvl2subcat': lvl2subcat})
	// 	.then(function successCallback(response)
	// 	{
	// 		var data = response.data;
	// 		if(data=="NoData")
	// 		{
	// 			$scope.Level3Arr= '';
	// 		}
	// 		else
	// 		{
	// 			$scope.Level3Arr = data;
	// 		}
	// 	}, function errorCallback(response) {
 //    		// called asynchronously if an error occurs
	// 	    // or server returns response with an error status.
	// 	  });
	// };
// $scope.BrandArray = [];
// 	$scope.GetBrands= function(category,subcategory,lvl2subcat,index)
// 	{
// 		// if($scope.BrandArray[index] !== undefined){
// 		$http.post("Get-Category-Brands.php",{'category': category,'subcategory': subcategory,'lvl2subcat': lvl2subcat})
// 		.then(function successCallback(response)
// 		{
// 			var data = response.data;			
// 			if(data=="NoData")
// 			{
// 				$scope.BrandArray[index] = "";
// 			}
// 			else
// 			{
// 				$scope.BrandArray[index] = data;
// 			}
// 		});
// 		//}
// 	}

// $scope.ProductArray = [];
// 	$scope.GetProducts= function(category,subcategory,lvl2subcat,brand,index)
// 	{
// 		$http.post("Get-Catwise-Products.php",{'category': category,'subcategory': subcategory,'lvl2subcat': lvl2subcat,'brand': brand})
// 		.then(function successCallback(response)
// 		{
// 			var data = response.data;  
// 			if(data=="NoData")
// 			{
// 				$scope.ProductArray[index] = "";
// 			}
// 			else
// 			{
// 				$scope.ProductArray[index] = data;
// 			}
// 		});
// 	}
// $scope.InventoryArray = [];
// 	$scope.GetInventory= function()
// 	{
// 		$http.get("Get-Invoice-Inventory.php")
// 		.then(function successCallback(response)
// 		{
// 			var data = response.data;  
// 			if(data=="NoData")
// 			{
// 				$scope.InventoryArray = "";
// 			}
// 			else
// 			{
// 				$scope.InventoryArray = data;
// 			}
// 		});
// 	}
$scope.GetReturnAmt = function(returnid)
{
	$http.post("Get-return-invoice-amt.php",{'returnid': returnid})
		.then(function successCallback(response)
		{
			var data = response.data;  
			if(data=="NoData")
			{
				$scope.returnamt = "0.00";
				$scope.ReturnChk = "Invalid Or Already Used";
			}
			else
			{
				$scope.returnamt = data['ReturnTotal'];
				$scope.ReturnChk = "";
			}
		});
}

var GSTRateArr = []; 
for(var i=0;i<19;i++) { 
	if(i==0)
	{
		i = 0;	
	}
	else
	{
		i = i-0.5;	
	}
	
   GSTRateArr.push(i); 
} 
$scope.GSTRateArr = GSTRateArr;

$scope.GetGstAmt = function()
{
	for (var i = 0; i < $scope.BatchArr.length; i++)
	{
		// if(parseFloat($scope.BatchArr[i]["price"])<"1000.00")
		// {
		// 	$scope.BatchArr[i]["gstrate"] = 5;
		// }
		// else
		// {
		// 	$scope.BatchArr[i]["gstrate"] = 12;
		// }

		$scope.amt = parseFloat($scope.BatchArr[i]["quantity"]*$scope.BatchArr[i]["price"]);
		$scope.BatchArr[i]["basicamount"]= Math.round($scope.amt);
		$scope.BatchArr[i]["gstamount"] = parseFloat(Math.round($scope.amt)*($scope.BatchArr[i]["gstrate"]/100));

		$scope.BatchArr[i]["finaltotal"] = Math.round(Math.round($scope.amt)+$scope.BatchArr[i]["gstamount"]);
		
	}
}
$scope.GSTSum = function()
{
	var gstsum = 0;
	if ($scope.BatchArr !== undefined) {

		for (var i = 0; i < $scope.BatchArr.length; i++)
		{
			gstsum += parseFloat($scope.BatchArr[i]["gstamount"]);
		}
	}
	$scope.gstsum=gstsum;
		return gstsum;
}
$scope.BasicSum = function()
{
	var sumbasictotal = 0;
	if ($scope.BatchArr !== undefined) {

		for (var i = 0; i < $scope.BatchArr.length; i++)
		{
			sumbasictotal += Math.round(parseFloat($scope.BatchArr[i]["basicamount"]));
		}
	}
	$scope.sumbasictotal=sumbasictotal;
		return sumbasictotal;
}

 $scope.getDiscAmt = function () 
 {
 	if($scope.disctype=="Percent")
 	{
 		$scope.disctotal = -($scope.sum+parseFloat($scope.additionalcharges))*($scope.discvalue/100);
 		$scope.totalamount = Math.round($scope.sum+parseFloat($scope.additionalcharges)+parseFloat($scope.disctotal)-parseFloat($scope.returnamt));
 	}

 	if($scope.disctype=="Rupee")
 	{
 		$scope.disctotal = -($scope.discvalue);
 		$scope.totalamount = Math.round($scope.sum+parseFloat($scope.additionalcharges)-($scope.discvalue)-parseFloat($scope.returnamt));
 	}
 	
}

$scope.calculateSum = function () {
		var sum = 0;
	if ($scope.BatchArr !== undefined) {

		for (var i = 0; i < $scope.BatchArr.length; i++)
		{
			sum += Math.round($scope.BatchArr[i]["finaltotal"]);
		}

		if($scope.disctype=="Percent")
	 	{
	 		$scope.disctotal = -($scope.sum+parseFloat($scope.additionalcharges))*($scope.discvalue/100);
	 		$scope.totalamount = Math.round($scope.sum+parseFloat($scope.additionalcharges)+parseFloat($scope.disctotal)-parseFloat($scope.returnamt));
	 	}

	 	if($scope.disctype=="Rupee")
	 	{
	 		$scope.disctotal = -($scope.discvalue);
	 		$scope.totalamount = Math.round(sum+parseFloat($scope.additionalcharges)-$scope.discvalue-parseFloat($scope.returnamt));
	 	}
	}

		$scope.sum=sum;
		return sum;
	}
$scope.paycash = "0.00";
$scope.paycard = "0.00";
$scope.payothers = "0.00";
$scope.TotalPay = function()
{
	$scope.totalpaid = Math.round(parseFloat($scope.paycash)+parseFloat($scope.paycard)+parseFloat($scope.payothers));
}
$scope.GotoList = function()
{
	$scope.FormAdd = false;
	$scope.FormList = true;
}
$scope.EntryType="New";

$scope.customername="";
$scope.loading = true;
$scope.AddCategoryData = function ()
	{		
		 if($scope.customername==undefined)
		{
			swal("STOP", "Please Enter Customer", "error");
			$timeout(function () { $scope.submitted = false;}, 3000);
		}
		else if($scope.BatchArr.length==0)
		{
			swal("STOP", "Please Select Items", "error");
			$timeout(function () { $scope.submitted = false;}, 3000);
		}
		else 
		{
		    
			
		$scope.submitted = true;
		var fd = new FormData();
          var file = $scope.docfile;
         fd.append('file', file);	

         var EntryPkId=new Array();
        var InvPkId=new Array();
        var ProductId=new Array();
		var ProductName=new Array();
		var UniqueRollNo = new Array();
		// var HSN = new Array();
		var Size = new Array();
		//var Colour=new Array();
		var AvlQty = new Array();
		var quantity = new Array();
		// var price=new Array();
		// var basicamount=new Array();
		// var gstrate = new Array();
		// var gstamount = new Array();
		// var finaltotal = new Array();

		for(i=0;i<$scope.BatchArr.length;i++)
	    {
	    	EntryPkId.push($scope.BatchArr[i].EntryPkId);	
		 InvPkId.push($scope.BatchArr[i].InvPkId);
		 ProductId.push($scope.BatchArr[i].ProductId);
		 ProductName.push($scope.BatchArr[i].ProductName);
		 UniqueRollNo.push($scope.BatchArr[i].UniqueRollNo);
		 // HSN.push($scope.BatchArr[i].HSN);
		 Size.push($scope.BatchArr[i].Size);
		 //Colour.push($scope.BatchArr[i].Colour);
		 AvlQty.push($scope.BatchArr[i].AvlQty);
		 quantity.push($scope.BatchArr[i].quantity);
		 // price.push($scope.BatchArr[i].price);
		 // basicamount.push($scope.BatchArr[i].basicamount);
		 // gstrate.push($scope.BatchArr[i].gstrate);
		 // gstamount.push($scope.BatchArr[i].gstamount);
		 // finaltotal.push($scope.BatchArr[i].finaltotal);
		}
		
		$scope.FileErr="";
		$scope.FormValid = true;
		$scope.loading = true;
		$http.post('add-new-invoice-process.php', {
		// $http.post('add-new-invoice-process.php', fd, {transformRequest: angular.identity,headers: {'Content-Type': undefined},		
  //       params: {
  			'FormPkId':$scope.FormPkId,
        	'InvoiceId':$scope.InvoiceId,
        	//'OrderId':$scope.OrderId,
			'IsSaveDraft':$scope.IsSaveDraft,
			'CustomerId':$scope.customername.VendorId,
			'CustomerName':$scope.customername.DisplayName,
			'customermobileno':$scope.customermobileno,
			//'customerplace':$scope.place,
			
			'referencenum':$scope.referencenum,
			'entrydate':$scope.entrydate,
			// 'duedate':$scope.duedate,
			// 'paymentterms':$scope.paymentterms,
			//'deliverymethod':$scope.deliverymethod,
			'EntryType':$scope.EntryType,

			//'InvPkId':JSON.stringify(InvPkId),
			'EntryPkId':EntryPkId,
			'InvPkId':InvPkId,
			'ProductId':ProductId,
			'product':ProductName,
			'UniqueRollNo':UniqueRollNo,
			//'HSN':HSN,
			'Size':Size,
			//'Colour':Colour,
			'AvlQty':AvlQty,
			'quantity':quantity,
			// 'price':price,
			// 'basicamount':basicamount,
			// 'gstrate':gstrate,
			// 'gstamount':gstamount,
			// 'finaltotal':finaltotal,
							
			// 'sum':$scope.sum,
			// 'additionalcharges':$scope.additionalcharges,
			// 'gstperc':$scope.gstperc,
			// 'gstpercamt':$scope.gstpercamt,

			// 'disctype':$scope.disctype,
			// 'discvalue':$scope.discvalue,
			// 'disctotal':$scope.disctotal,

			// 'returnid':$scope.returnid,
			// 'returnamt':$scope.returnamt,
			// 'totalamount':$scope.totalamount,
			// 'paycash':$scope.paycash,
			// 'paycard':$scope.paycard,
			// 'payothers':$scope.payothers,
			// 'totalpaid':$scope.totalpaid,

			'cnotes':$scope.cnotes,
			// 'terms':$scope.terms,
			// 'Type':$scope.Type,
		//},

		})
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data['result']=="Success")
			{
			    $scope.FormValid = true;
				$scope.loading = false;
				$scope.submitted = false;
				/*swal({
				    title: "Bill Created",
				    text: "Do you want to take print out?",
				    type: "success",
				    showCancelButton: true,
				    confirmButtonColor: "#DD6B55",confirmButtonText: "Yes, Print it!",
				    cancelButtonText: "No, cancel!",
				    closeOnConfirm: false,
				    closeOnCancel: false,
				    showLoaderOnConfirm: true           // Add this line
				}, function(isConfirm){
				    if (!isConfirm) {
				    	
				    	window.location.href="new-invoice.php";
				    	
				    } else {
				        // $timeout is sample code. Put your http call function into here instead of $timeout.
				        $timeout(function(){
				        		//Bill
				        		window.location.href="bill-print.php?Id="+data['FormPkId'];
				        	//swal("Deleted!", "Your imaginary file has been deleted.", "success");
				        },2000);
				    }
				});*/
				swal({title: "Updated",
							     text: "Bill updated successfully",
							     type: "success",
							     timer: 2000
						},function () {})
						$scope.getData($scope.mypageno); // Call the function to fetch initial data on page load.
					$scope.FormAdd = false;
					$scope.FormList = true;
					$scope.pagetitle = "List of Bills";
			}
			else
			{
				$scope.FormValid = false;
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

$scope.DeleteArr = function(EntryPkId,ProductId,InvPkId,UniqueRollNo)
{
	$http.post('delete-invoiceitem.php', { 'EntryPkId': EntryPkId,'ProductId': ProductId,'InvPkId': InvPkId,'UniqueRollNo': UniqueRollNo })
	.then(function successCallback(response)
	{
		var data = response.data;
		if(data=="Success")
		{
			swal({title: "Added",
						 text: "Item deleted successfully",
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


$scope.SaveDraft = function ()
	{	
	if($scope.customername==undefined)
		{
			swal("STOP", "Please Enter Customer", "error");
			$timeout(function () { $scope.submitted = false;}, 3000);
		}
		else if($scope.BatchArr.length==0)
		{
			swal("STOP", "Please Select Items", "error");
			$timeout(function () { $scope.submitted = false;}, 3000);
		}
		else {
			$scope.loading = true;
		$scope.submitted = true;
		var fd = new FormData();
          var file = $scope.docfile;
         fd.append('file', file);	

        var EntryPkId=new Array();
        var InvPkId=new Array();
        var ProductId=new Array();
		var ProductName=new Array();
		var UniqueRollNo = new Array();
		// var HSN = new Array();
		var Size = new Array();
		//var Colour=new Array();
		var AvlQty = new Array();
		var quantity = new Array();
		// var price=new Array();
		// var basicamount=new Array();
		// var gstrate = new Array();
		// var gstamount = new Array();
		// var finaltotal = new Array();

		for(i=0;i<$scope.BatchArr.length;i++)
	    {
		 InvPkId.push($scope.BatchArr[i].InvPkId);
		 ProductId.push($scope.BatchArr[i].ProductId);
		 ProductName.push($scope.BatchArr[i].ProductName);
		 UniqueRollNo.push($scope.BatchArr[i].UniqueRollNo);
		 // HSN.push($scope.BatchArr[i].HSN);
		 Size.push($scope.BatchArr[i].Size);
		 //Colour.push($scope.BatchArr[i].Colour);
		 AvlQty.push($scope.BatchArr[i].AvlQty);
		 quantity.push($scope.BatchArr[i].quantity);
		 // price.push($scope.BatchArr[i].price);
		 // basicamount.push($scope.BatchArr[i].basicamount);
		 // gstrate.push($scope.BatchArr[i].gstrate);
		 // gstamount.push($scope.BatchArr[i].gstamount);
		 // finaltotal.push($scope.BatchArr[i].finaltotal);
		}
		
		$scope.FileErr="";
		//
		$scope.FormValid = true;
		$http.post('add-save-draft-process.php', {
		// $http.post('add-new-invoice-process.php', fd, {transformRequest: angular.identity,headers: {'Content-Type': undefined},		
  //       params: {
  			'FormPkId':$scope.FormPkId,
        	'InvoiceId':$scope.InvoiceId,
        	//'OrderId':$scope.OrderId,
			'IsSaveDraft':$scope.IsSaveDraft,
			'CustomerId':$scope.customername.VendorId,
			'CustomerName':$scope.customername.DisplayName,
			'customermobileno':$scope.customermobileno,
			//'customerplace':$scope.place,
			
			'referencenum':$scope.referencenum,
			'entrydate':$scope.entrydate,
			// 'duedate':$scope.duedate,
			// 'paymentterms':$scope.paymentterms,
			//'deliverymethod':$scope.deliverymethod,
			'EntryType':$scope.EntryType,

			//'InvPkId':JSON.stringify(InvPkId),
			'InvPkId':InvPkId,
			'ProductId':ProductId,
			'product':ProductName,
			'UniqueRollNo':UniqueRollNo,
			//'HSN':HSN,
			'Size':Size,
			//'Colour':Colour,
			'AvlQty':AvlQty,
			'quantity':quantity,
			// 'price':price,
			// 'basicamount':basicamount,
			// 'gstrate':gstrate,
			// 'gstamount':gstamount,
			// 'finaltotal':finaltotal,
							
			// 'sum':$scope.sum,
			// 'additionalcharges':$scope.additionalcharges,
			// 'gstperc':$scope.gstperc,
			// 'gstpercamt':$scope.gstpercamt,

			// 'disctype':$scope.disctype,
			// 'discvalue':$scope.discvalue,
			// 'disctotal':$scope.disctotal,

			// 'returnid':$scope.returnid,
			// 'returnamt':$scope.returnamt,
			// 'totalamount':$scope.totalamount,
			// 'paycash':$scope.paycash,
			// 'paycard':$scope.paycard,
			// 'payothers':$scope.payothers,
			// 'totalpaid':$scope.totalpaid,

			'cnotes':$scope.cnotes,
			// 'terms':$scope.terms,
			// 'Type':$scope.Type,
		//},
		})	
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data=="Success")
			{
				$scope.loading = false;
				$scope.submitted = false;
				$scope.FormValid = false;
				swal({title: "Added",
						     text: "Bill saved as draft mode",
						     type: "success",
						     timer: 2500 
					},function () {window.location.href="new-invoice.php";})
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
$scope.GenerateBill = function(PkId,InvoiceId,InvoiceDate,CustomerId_CustomerMaster,CustomerName,CustomerMobile,CustomerPlace,Reference,IsSaveDraft,SubTotal,DiscType,DiscountVal,DiscountAmount,InvoiceTotal,CustomerNotes,data2)	
{
	$scope.GetCustomers();
	$scope.GetPurchaseBars();
	$scope.pagetitle = "Edit Bill";
	$scope.EntryType="Update";
	$scope.FormAdd = true;
	$scope.FormList = false;
	$scope.FormPkId = PkId;
	$scope.InvoiceId = InvoiceId;
	$scope.entrydate = new Date(InvoiceDate);

	$scope.customername = {VendorId:CustomerId_CustomerMaster,DisplayName:CustomerName};
	$scope.customermobileno = CustomerMobile;
	$scope.place = CustomerPlace;
	$scope.referencenum = Reference;
	$scope.IsSaveDraft = IsSaveDraft;
	$scope.sum = SubTotal;
	$scope.disctype = DiscType;
	$scope.discvalue = DiscountVal;
	$scope.disctotal = DiscountAmount;
	$scope.totalamount = InvoiceTotal;
	$scope.cnotes = CustomerNotes;
if(data2=="" || data2=="null" || data2=="NULL" || data2==undefined)
	{
		$scope.BatchArr = [];
	}
	else
	{
		$scope.BatchArr = data2;
	}
	 // angular.forEach(data2, function (value, key) { 
  //       //$scope.names.push(value.name); 
  //       $scope.BatchArr[key].InvPkId = value.PkId_InventoryMaster;
  //       $scope.barcodeInput = value.BarCode;
  //       $scope.GetBar();
  //       $scope.BatchArr[key].quantity = value.Quantity;
        
  //       $scope.BatchArr[key].price = value.Price;
  //       $scope.BatchArr[key].gstrate = value.TaxRate;
  //       // $scope.BatchArr[key].InvPkId = value.PkId_InventoryMaster;
  //       // $scope.BatchArr[key].InvPkId = value.PkId_InventoryMaster;
  //   });  

}

	/* customer start*/
	$scope.AddVendor  = function()
	{
		$scope.GetCustomerId();
		$scope.GetSalutes();
		//$scope.GetPaymentTerms();
		$scope.GetStates();
	}
	$scope.ctype = "Individual";
	$scope.salutation = "Mr.";	
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
		// $scope.shipaddress2=$scope.address2;
		// $scope.shiptown=$scope.town;
		// $scope.shiplandmark=$scope.landmark;
		// $scope.shipcity=$scope.city;
		// $scope.shipstate=$scope.state;
		// $scope.shipdistrict=$scope.district;
		// $scope.shippincode=$scope.pincode;

	}else{
		$scope.shippingname="";
		$scope.shipmobileno="";
		$scope.shipaddress1="";
		// $scope.shipaddress2="";
		// $scope.shiptown="";
		// $scope.shiplandmark="";
		// $scope.shipcity="";
		// $scope.shipstate="";
		// $scope.shipdistrict="";
		// $scope.shippincode="";

		}
	}
	$scope.AddCustomerData = function ()
	{
		$scope.submitted = true;
			$http.post("add-customer-process.php",{
			'CustomerId':$scope.CustomerId,
			'salutation':$scope.salutation,
			'ctype':$scope.ctype,
			'customername':$scope.customername,
			'shopname':$scope.shopname,
			'emailid':$scope.emailid,
			'mobileno':$scope.mobileno,
			//'ofcmobileno':$scope.ofcmobileno,
			//'gstin':$scope.gstin,
			//'pan':$scope.pan,
			'billingname':$scope.billingname,
			'billmobileno':$scope.billmobileno,
			'address1':$scope.address1,
			// 'address2':$scope.address2,
			// 'town':$scope.town,
			// 'landmark':$scope.landmark,
			// 'city':$scope.city,
			// 'state':$scope.state,
			// 'district':$scope.district,
			// 'pincode':$scope.pincode,
			// 'lattitude':$scope.lattitude,
			// 'longitude':$scope.longitude,

			'shippingname':$scope.shippingname,
			'shipmobileno':$scope.shipmobileno,
			'shipaddress1':$scope.shipaddress1,
			// 'shipaddress2':$scope.shipaddress2,
			// 'shiptown':$scope.shiptown,
			// 'shiplandmark':$scope.shiplandmark,
			// 'shipcity':$scope.shipcity,
			// 'shipstate':$scope.shipstate,
			// 'shipdistrict':$scope.shipdistrict,
			// 'shippincode':$scope.shippincode,
			'paymentterms':$scope.paymentterms,
			'remarks':$scope.remarks,
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
					$scope.customername="";
					$scope.GotoAdd();
					$('#modal-form').modal('hide');
					 swal({title: "success!",
					     text: "Customer Created successfully.",
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
	
	
var temp = '0.00';
  $(".decimaltemp").focus(function() {
    if (temp == this.value)
      // ------^-- empty the field only if the field value is initial  
      this.value = '';
      // emptying field value
  }).blur(function() {
    if (this.value.trim() == '')
      // -----^-- condition field value is empty
      this.value = temp;
      // if empty then updating with initial value
  }).val(temp);
  //----^-- setting initial value

}]);
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
AddCategory.config(['$qProvider', function ($qProvider) {
    $qProvider.errorOnUnhandledRejections(false);
}]);

AddCategory.directive('number', function () {
        return {
            require: 'ngModel',
            restrict: 'A',
            link: function (scope, element, attrs, ctrl) {
            	var temp = '0';
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
