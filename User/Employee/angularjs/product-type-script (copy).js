var AddCategory = angular.module("CategoryModule", ['ui.bootstrap', 'datatables'])
AddCategory.controller("CategoryController", function ($scope, $timeout, $http, jsonFilter)
{
	$scope.FormAdd = false;	
	$scope.FormList = true;	
	$scope.pagetitle = "List of Products";
	
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
	$scope.GetListData= function()
	{
		$http.get("load-product-types.php")
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
				//$scope.FormList = true;
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

	$scope.EditCat = function (PkId,PkId_Category,SalesIn,ProTypeName,data2)
	{
		$scope.LoadCatgory();
		$scope.SetMode(SalesIn);
		$scope.pagetitle = "Edit Product type";
		$scope.FormAdd = true;	
		$scope.FormList = false;
		$scope.FormPkId = PkId;
		$scope.category = PkId_Category;
		$scope.salemode = SalesIn;
		$scope.protype = ProTypeName;
			 angular.forEach(data2, function (value, key) { 
                $scope.KgArr[key].KgArrPkId = value.ArrPkId; 
                $scope.KgArr[key].name = value.KgorQuantity; 
                $scope.KgArr[key].price = value.Price; 
                $scope.KgArr[key].status = value.Status; 
            }); 
		
		$scope.checkProduct(PkId,PkId_Category,ProTypeName);
	}
	
	$scope.AddCategoryData = function ()
	{
		$scope.submitted = true;
		//var config = { params: { AddCategory: AddCategory } };
		//$scope.AddCategory.supervisor: [];

		var KgArrPkId=new Array();
			var name=new Array();
			var price=new Array();
			var status=new Array();


		for(i=0;i<$scope.KgArr.length;i++)
		    {
		    	if ($scope.KgArr[i].name !== undefined && $scope.KgArr[i].price !== undefined) {
				 KgArrPkId.push($scope.KgArr[i].KgArrPkId);
				 name.push($scope.KgArr[i].name);
				 price.push($scope.KgArr[i].price);
				 status.push($scope.KgArr[i].status);
				}
			}
		if($scope.FormPkId==undefined && $scope.NameExists==false)
		{
			

			$http.post("add-product-type-process.php",{
				'category':$scope.category,
				'protype':$scope.protype,
				'salemode':$scope.salemode,

				'KgArrPkId':KgArrPkId,
				'name':name,
				'price':price,
				'status':status,

			})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.submitted = false;
					$scope.FormValid = true;

					swal({title: "",
							     text: "Product Type Created successfully",
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
		else if($scope.NameExists==false)
		{
			//alert("update");
			$http.post("update-product-type-process.php",{'FormPkId':$scope.FormPkId,
				'category':$scope.category,
				'protype':$scope.protype,
				'salemode':$scope.salemode,

				'KgArrPkId':KgArrPkId,
				'name':name,
				'price':price,
				'status':status
			})
			.then(function successCallback(response)
			{
				var data = response.data;
				if(data=="Success")
				{
					$scope.submitted = false;
					$scope.FormValid = true;

					swal({title: "",
							     text: "Product Type updated successfully",
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
	
	$scope.GotoList = function()
	{		
		window.location.href = "product-types.php";
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