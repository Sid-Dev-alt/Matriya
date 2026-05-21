var AddCategory = angular.module("CategoryModule", ['ui.bootstrap', 'datatables','angular.filter'])
AddCategory.controller("CategoryController", function ($scope, $timeout, $http, jsonFilter)
{
	$scope.FormAdd = false;	
	$scope.FormList = true;	
	$scope.pagetitle = "List of Available stock";
	
	var logResult = function (data, status, headers, config)
	{
		return data;
	};

$scope.GotoAdd = function()
{
	$scope.pagetitle = "Add Product";
	$scope.FormAdd = true;	
	$scope.FormList = false;	
	$scope.LoadCatgory();
}
$scope.loading = false;
	$scope.GetListData= function()
	{
		$scope.loading = true;
		$http.get("load-stock-data.php")
		.then(function successCallback(response)
		{
			var data = response.data;			
			if(data=="NoData")
			{
				swal("Empty", "No Records Found", "error");
							$timeout(function () { $scope.submitted = false;}, 2000);
				$scope.pagedItems = "";
				//$scope.FormList = false;
			}
			else
			{
				$scope.query = {}
				$scope.queryBy = '$';
				$scope.pagedItems = data;
				$scope.loading = false;
				//$scope.FormList = true;
			}
		});
	}
	
		$scope.getVolumeSum = function(items) {
  	return items
    .map(function(x) { return parseFloat(x.Quantity); })
    .reduce(function(a, b) { return a + b; });
  };

$scope.calculateTotal = function () {
	//alert();
	var tsum = 0;
if ($scope.pagedItems !== undefined) {

	for (var i = 0; i < $scope.pagedItems.length; i++)
	{
		tsum += parseFloat($scope.pagedItems[i]["AvlQty"] || 0);
	}
}
	$scope.tsum=tsum;
	return tsum;
}

$scope.calculateKgsTotal = function () {
	//alert();
	var ktsum = 0;
if ($scope.pagedItems !== undefined) {

	for (var i = 0; i < $scope.pagedItems.length; i++)
	{
		/*ktsum += parseFloat($scope.pagedItems[i]["AvlQty"] || 0);*/
		if($scope.pagedItems[i]["Unit"]=="Kg")
		{
		    ktsum += parseFloat($scope.pagedItems[i]["AvlQty"] || 0);
		}
	}
}
	$scope.ktsum=ktsum;
	return ktsum;
}

$scope.calculatePcsTotal = function () {
	//alert();
	var ptsum = 0;
if ($scope.pagedItems !== undefined) {

	for (var i = 0; i < $scope.pagedItems.length; i++)
	{
		/*ptsum += parseFloat($scope.pagedItems[i]["AvlQty"] || 0);*/
		if($scope.pagedItems[i]["Unit"]=="Pcs")
		{
		    ptsum += parseFloat($scope.pagedItems[i]["AvlQty"] || 0);
		}
	}
}
	$scope.ptsum=ptsum;
	return ptsum;
}

	$scope.ChangeInAct = function(PkId)
  {
    swal({
      title: "",
      text: "Are you sure want to change Inactive?",
      type: "warning",
      showCancelButton: true,
      confirmButtonClass: "btn-danger",
      confirmButtonText: "Yes, Inactive it!",
      cancelButtonText: "No, cancel!",
      closeOnConfirm: false,
      closeOnCancel: false,
        html: true
    },
    function(isConfirm) {
      if (isConfirm) {
        $http.post('stock-set-inactive.php', {'PkId':PkId})
      .then(function successCallback(response)
        {
        var data = response.data;  
        if(data=="Success")
        {
          $scope.GetListData();
             swal({title: "Success",
                   text: "Status Changed successfully",
                   type: "success",
                   timer: 2000 
                  });
            
          }
          else
          {
             swal({
               text: data,
               type: "error",
               timer: 3000 
              });
          }
        });
      } else {
        swal("Cancelled", "Nothing has changed :)", "error");
      }
    });
  }
  
  $scope.GetRollData= function(UniqueRollNo,ProductId,InvPkId)
	{
		//$scope.mypageno = pageno;

		// if($scope.fromdate > $scope.todate){
	 //    	swal("STOP", 'To Date should be more than From date', "error");
		// 	$timeout(function () { $scope.submitted = false;}, 3000);
	 //    }
	 //    else
	 //    {
	 	//'itemsPerPage': $scope.itemsPerPage, 'pagenumber': pageno, , 'ProductSize': $scope.itemsize.ProductSize
			$http.post("load-roll-history.php", {'rollno': UniqueRollNo})
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
            $scope.GetStockRemarks(UniqueRollNo,ProductId,InvPkId);
				 }
			}, function errorCallback(response) {
	    		// called asynchronously if an error occurs
			    // or server returns response with an error status.
			});
		//}
	}
	
	$scope.GetStockRemarks = function(UniqueRollNo,ProductId,InvPkId)
	{
		$scope.loading = true;
		$http.post("load-stock-remarks.php",{'UniqueRollNo':UniqueRollNo,'ProductId':ProductId,'InvPkId':InvPkId})
		.then(function successCallback(response)
		{
			var data = response.data;			
			if(data=="NoData")
			{
				swal("Empty", "No Records Found", "error");
							$timeout(function () { $scope.submitted = false;}, 2000);
				$scope.stkremarks = "";
				//$scope.FormList = false;
			}
			else
			{
				$scope.stkremarks = data;
				$scope.loading = false;
				//$scope.FormList = true;
			}
		});
	}

  $scope.ChangeAct = function(PkId)
  {
    swal({
      title: "",
      text: "Are you sure want to change Active?",
      type: "warning",
      showCancelButton: true,
      confirmButtonClass: "btn-danger",
      confirmButtonText: "Yes, Active it!",
      cancelButtonText: "No, cancel!",
      closeOnConfirm: false,
      closeOnCancel: false,
        html: true
    },
    function(isConfirm) {
      if (isConfirm) {
        $http.post('stock-set-active.php', {'PkId':PkId})
      .then(function successCallback(response)
        {
        var data = response.data;  
        if(data=="Success")
        {
          $scope.GetListData();
             swal({title: "Success",
                   text: "Status Changed successfully",
                   type: "success",
                   timer: 2000 
                  });
            
          }
          else
          {
             swal({
               text: data,
               type: "error",
               timer: 3000 
              });
          }
        });
      } else {
        swal("Cancelled", "Nothing has changed :)", "error");
      }
    });
  }


	$scope.LoadCatgory = function()
	{
		$http.get('load-category.php')
		.then(function successCallback(response)
		{
			var data = response.data;
			if(data=="NoData")
			{
				$scope.CategoryArr= '';
			}
			else
			{
				$scope.CategoryArr = data;
			}
		}, function errorCallback(response) {
    		// called asynchronously if an error occurs
		    // or server returns response with an error status.
		  });
	};
	
	

	$scope.checkProduct = function(FormPkId,category,protype)
	{
		$http.post('check-product-type.php', { 'FormPkId': $scope.FormPkId, 'category': $scope.category , 'protype': $scope.protype })
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
$scope.KiloArr = [{name:'1/4 Kg',price:"0",status:"1"},
	{name:'1/2 Kg',price:"0",status:"1"},
	{name:'3/4 Kg',price:"0",status:"0"},
	{name:'1 Kg',price:"0",status:"1"}];

$scope.QtyArr = [{name:'1',price:"0",status:"1"},
	{name:'3',price:"0",status:"1"},
	{name:'5',price:"0",status:"1"},
	{name:'10',price:"0",status:"1"}];
	$scope.KgArr=[];

$scope.SetMode = function(salemode){
	
	if(salemode=="Kgs"){
		if($scope.KgArr.length==0)
		{
				$scope.KgArr.push(
					{name:'1/4 Kg',price:"0",status:"1"},
					{name:'1/2 Kg',price:"0",status:"1"},
					{name:'3/4 Kg',price:"0",status:"0"},
					{name:'1 Kg',price:"0",status:"1"}
				);
		}
		else
		{
			for(i=0;i<$scope.KgArr.length;i++)
		    {
				 $scope.KgArr[i].name=$scope.KiloArr[i].name;	
			}
		}
		
	}

		if(salemode=="Qty"){
			if($scope.KgArr.length==0)
			{
				$scope.KgArr.push(
					{name:'1',price:"0",status:"1"},
					{name:'3',price:"0",status:"1"},
					{name:'5',price:"0",status:"1"},
					{name:'10',price:"0",status:"1"}
				);
			}
			else
			{
				for(i=0;i<$scope.KgArr.length;i++)
			    {
					 $scope.KgArr[i].name=$scope.QtyArr[i].name;	
				}
			}
	}
}
	// $scope.KgArr = [
	// {name:'1/4 Kg',price:"0",status:"1"},
	// {name:'1/2 Kg',price:"0",status:"1"},
	// {name:'3/4 Kg',price:"0",status:"1"},
	// {name:'1 Kg',price:"0",status:"1"}
	// ];

	// 	$scope.PiecesArr = [
	// {Qtypcs:'1',Qtyprice:"0",Qtystatus:"1"},
	// {Qtypcs:'3',Qtyprice:"0",Qtystatus:"1"},
	// {Qtypcs:'5',Qtyprice:"0",Qtystatus:"1"},
	// {Qtypcs:'10',Qtyprice:"0",Qtystatus:"1"}
	// ];

	// $scope.EditCat = function (PkId,PkId_Category,SalesIn,ProTypeName,data2)
	// {
	// 	$scope.LoadCatgory();
	// 	$scope.SetMode(SalesIn);
	// 	$scope.pagetitle = "Edit Product type";
	// 	$scope.FormAdd = true;	
	// 	$scope.FormList = false;
	// 	$scope.FormPkId = PkId;
	// 	$scope.category = PkId_Category;
	// 	$scope.salemode = SalesIn;
	// 	$scope.protype = ProTypeName;
	// 		 angular.forEach(data2, function (value, key) { 
 //                $scope.KgArr[key].KgArrPkId = value.ArrPkId; 
 //                $scope.KgArr[key].name = value.KgorQuantity; 
 //                $scope.KgArr[key].price = value.Price; 
 //                $scope.KgArr[key].status = value.Status; 
 //            }); 
		
	// 	$scope.checkProduct(PkId,PkId_Category,ProTypeName);
	// }
	
	// $scope.AddCategoryData = function ()
	// {
	// 	$scope.submitted = true;
	// 	//var config = { params: { AddCategory: AddCategory } };
	// 	//$scope.AddCategory.supervisor: [];

	// 	var KgArrPkId=new Array();
	// 		var name=new Array();
	// 		var price=new Array();
	// 		var status=new Array();


	// 	for(i=0;i<$scope.KgArr.length;i++)
	// 	    {
	// 	    	if ($scope.KgArr[i].name !== undefined && $scope.KgArr[i].price !== undefined) {
	// 			 KgArrPkId.push($scope.KgArr[i].KgArrPkId);
	// 			 name.push($scope.KgArr[i].name);
	// 			 price.push($scope.KgArr[i].price);
	// 			 status.push($scope.KgArr[i].status);
	// 			}
	// 		}
	// 	if($scope.FormPkId==undefined && $scope.NameExists==false)
	// 	{
			

	// 		$http.post("add-product-type-process.php",{
	// 			'category':$scope.category,
	// 			'protype':$scope.protype,
	// 			'salemode':$scope.salemode,

	// 			'KgArrPkId':KgArrPkId,
	// 			'name':name,
	// 			'price':price,
	// 			'status':status,

	// 		})
	// 		.then(function successCallback(response)
	// 		{
	// 			var data = response.data;
	// 			if(data=="Success")
	// 			{
	// 				$scope.submitted = false;
	// 				$scope.FormValid = true;

	// 				swal({title: "",
	// 						     text: "Product Type Created successfully",
	// 						     type: "success",
	// 						     timer: 2000 
	// 					},function () {window.location.reload();})
	// 			}
	// 			else
	// 			{
	// 				swal("STOP", data, "error");
	// 				$timeout(function () { $scope.submitted = false;}, 3000);
	// 			}
	// 		}, function errorCallback(response) {
	//     		// called asynchronously if an error occurs
	// 		    // or server returns response with an error status.
	// 		  });
	// 	}
	// 	else if($scope.NameExists==false)
	// 	{
	// 		//alert("update");
	// 		$http.post("update-product-type-process.php",{'FormPkId':$scope.FormPkId,
	// 			'category':$scope.category,
	// 			'protype':$scope.protype,
	// 			'salemode':$scope.salemode,

	// 			'KgArrPkId':KgArrPkId,
	// 			'name':name,
	// 			'price':price,
	// 			'status':status
	// 		})
	// 		.then(function successCallback(response)
	// 		{
	// 			var data = response.data;
	// 			if(data=="Success")
	// 			{
	// 				$scope.submitted = false;
	// 				$scope.FormValid = true;

	// 				swal({title: "",
	// 						     text: "Product Type updated successfully",
	// 						     type: "success",
	// 						     timer: 3000 
	// 					},function () {window.location.reload();})
	// 			}
	// 			else
	// 			{
	// 				swal("STOP", data, "error");
	// 				$timeout(function () { $scope.submitted = false;}, 3000);
	// 			}
	// 		}, function errorCallback(response) {
	//     		// called asynchronously if an error occurs
	// 		    // or server returns response with an error status.
	// 		  });
	// 	}
	// };
	
	$scope.GotoList = function()
	{		
		window.location.href = "stock-list.php";
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
AddCategory.directive('validNumber', function () {
    return {
        require: '?ngModel',
        link: function (scope, element, attrs, ngModelCtrl) {

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
// AddCategory.filter("groupBy",["$parse","$filter",function($parse,$filter){
//   return function(array,groupByField){
//     var result	= [];
//             var prev_item = null;
//             var groupKey = false;
//             var filteredData = $filter('orderBy')(array,groupByField);
//             for(var i=0;i<filteredData.length;i++){
//               groupKey = false;
//               if(prev_item !== null){
//                 if(prev_item[groupByField] !== filteredData[i][groupByField]){
//                   groupKey = true;
//                 }
//               } else {
//                 groupKey = true;  
//               }
//               if(groupKey){
//                 filteredData[i]['group_by_key'] =true;  
//               } else {
//                 filteredData[i]['group_by_key'] =false;  
//               }
//               result.push(filteredData[i]);
//               prev_item = filteredData[i];
//             }
//             return result;
//   }
// }])
