var AddCategory = angular.module("CategoryModule", ['ui.bootstrap', 'datatables'])
.filter('sumByColumn', function () {
      return function (collection, column) {
        var total = 0;

        collection.forEach(function (item) {
          total += parseInt(item[column]);
        });

        return total || 0;
      };
    })
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

AddCategory.controller("CategoryController", function ($scope, $timeout, $http, jsonFilter)
{
	$scope.FormAdd = false;	
	$scope.FormList = true;	
	$scope.pagetitle = "List of Requests";
	
	var logResult = function (data, status, headers, config)
	{
		return data;
	};


	$scope.GetListData= function()
	{
		$http.get("load-product-requests.php")
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


$scope.GetProductId= function()
	{
		$http.get("generate-productid.php")
		.then(function successCallback(response)
		{
			var data = response.data;
			$scope.productid = data;
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
	
	$scope.checkProduct = function(category,protype)
	{
		$http.post('check-product-type.php', {'category': category , 'protype': $scope.protype })
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
	

	$scope.EditCat = function (PkId,UserId_Users,ProductName,ProductDescription,PkId_Category,CategoryName,FileName,UploadFile1,UploadFile2,UploadFile3,UploadFile4,UploadFile5,Status)
	{
		$scope.LoadCatgory();
		$scope.GetProductId();
		$scope.pagetitle = "Edit Product";
		$scope.FormAdd = true;	
		$scope.FormList = false;
		$scope.FormPkId = PkId;
		$scope.UserId_Users = UserId_Users;
		$scope.protype = ProductName;
		$scope.prodescription = ProductDescription;
		$scope.category = PkId_Category;
		//$scope.category = CategoryName;
		$scope.FileName = FileName;
		$scope.UploadFile1 = UploadFile1;
		$scope.UploadFile2 = UploadFile2;
		$scope.UploadFile3 = UploadFile3;
		$scope.UploadFile4 = UploadFile4;
		$scope.UploadFile5 = UploadFile5;

		$scope.status = Status;
	}
	
	$scope.AddCategoryData = function ()
	{
		$scope.submitted = true;
		
					$http.post('approve-product-request-process.php', {
						'FormPkId':$scope.FormPkId,
						'UserId_Users':$scope.UserId_Users,
						'category':$scope.category,
						'productid':$scope.productid,
						'protype':$scope.protype,
						'prodescription':$scope.prodescription,
						'FileName':$scope.FileName,
						'UploadFile1':$scope.UploadFile1,
						'UploadFile2':$scope.UploadFile2,
						'UploadFile3':$scope.UploadFile3,
						'UploadFile4':$scope.UploadFile4,
						'UploadFile5':$scope.UploadFile5,
						'aprvtype':$scope.aprvtype,
						'comments':$scope.comments,
					})
					.then(function successCallback(response)
					{
						var data = response.data;
						if(data=="Success")
						{
							$scope.submitted = false;
							$scope.FormValid = true;

							swal({title: "",
									     text: "Product approved successfully",
									     type: "success",
									     timer: 2000 
								},function () {window.location.reload();})
						}
						else
						{
							//swal("STOP", data, "error");
							//$timeout(function () { $scope.submitted = false;}, 3000);
						}
					}, function errorCallback(response) {
			    		// called asynchronously if an error occurs
					    // or server returns response with an error status.
					  });
		}
		
	
	$scope.GotoList = function()
	{		
		window.location.href = "list-product-requests.php";
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