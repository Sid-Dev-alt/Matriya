var AddCategory = angular.module("CategoryModule", ['ui.bootstrap','googlechart'])
AddCategory.controller("CategoryController", function ($scope, $filter, $timeout, $http, jsonFilter)
{
   
$scope.loading = false;
$scope.FormList = true;
$scope.FormEdit = false;
$scope.FormAdd = false;

  $scope.showWarningAlert = false;  
  $scope.showSuccessAlert = false;

  $scope.showWarningAlert1 = false; 
  $scope.showSuccessAlert1 = false;

  $scope.FormAdd = false;   
  $scope.datte=new Date();
  $scope.opened = {};
    $scope.opened.opened2 = false;
    $scope.opened.opened3 = false;
    
    $scope.open = function($event,datepicker) {
      $event.preventDefault();
      $event.stopPropagation();
      $scope.opened[datepicker] = true;
    };
  $scope.assDate = {
        //dateDisabled: disabled,
        formatYear: 'y',
        maxDate: new Date(2050, 5, 22),
       minDate: new Date(),
        startingDay: 1
    };
  $scope.assDate1 = {
        //dateDisabled: disabled,
        formatYear: 'y',
      maxDate: new Date(2050, 5, 22),
       minDate: new Date(),
        startingDay: 1
    };
  
  var logResult = function (data, status, headers, config)
  {
    return data;
  };

$scope.GotoList = function()
{
  window.location.reload();
}

/*main list*/
$scope.GetSalesData= function()
  {
    //$scope.requestno=requestno;
    //alert($scope.ProjectId);
    $http.get("load-dashbaord-data.php")
    .then(function successCallback(response)
    {
      var data = response.data;
        $scope.remainpkdqty = data['remainpkdqty'];
        $scope.TobeShippd = data['TobeShippd'];
        $scope.TobeDeliver = data['TobeDeliver'];
        $scope.TobeInvoice = data['TobeInvoice'];
        $scope.PartialInvoice = data['PartialInvoice'];
        $scope.Invoiced = data['Invoiced'];
        $scope.productcount = data['procount'];
        $scope.Catcount = data['Catcount'];
        $scope.lowitemcount = data['lowitemcount'];
        $scope.ExpiredCount = data['ExpiredCount'];
        $scope.TotalQty = data['TotalQty'];
        $scope.TotalStockVal = data['TotalStockVal'];
        $scope.TotalOutstanding = data['TotalOutstanding'];
        $scope.TotalMarginVal = data['TotalMarginVal'];
        $scope.CustomerCount = data['CustomerCount'];
        $scope.VendorCount = data['VendorCount'];
      
    });
  }
  /*main list*/
  $scope.OrderTitle="This Month";
  $scope.GetMonthOrder= function()
  {
    $scope.OrderTitle="This Month";
    //$scope.requestno=requestno;
    //alert($scope.ProjectId);
    $http.post("load-month-order-data.php",{'OrderTitle': $scope.OrderTitle})
    .then(function successCallback(response)
    {
      var data = response.data;
        $scope.draftcount = data['draftcount'];
        $scope.confirmcount = data['confirmcount'];
        $scope.packedcount = data['packedcount'];
        $scope.shipcount = data['shipcount'];
        $scope.invoicecount = data['invoicecount'];
        $scope.invoicetotal = data['invoicetotal'];
    });
  }

  $scope.GetLastMonthOrder= function()
  {
     $scope.OrderTitle="Last Month";
     
    $http.post("load-month-order-data.php",{'OrderTitle': $scope.OrderTitle})
    .then(function successCallback(response)
    {
      var data = response.data;
        $scope.draftcount = data['draftcount'];
        $scope.confirmcount = data['confirmcount'];
        $scope.packedcount = data['packedcount'];
        $scope.shipcount = data['shipcount'];
        $scope.invoicecount = data['invoicecount'];
        $scope.invoicetotal = data['invoicetotal'];
    });
  }

  $scope.SellingTitle="This Month";
  $scope.GetMonthtopsales= function()
  {
    $scope.SellingTitle="This Month";
    //$scope.requestno=requestno;
    //alert($scope.ProjectId);
    $http.post("load-month-topsale-products.php",{'SellingTitle': $scope.SellingTitle})
    .then(function successCallback(response)
    {
      var data = response.data;
        $scope.TopItemSalesArr = data;
    });
  }

  $scope.GetLastMonthtopsales= function()
  {
     $scope.SellingTitle="Last Month";
     
    $http.post("load-month-topsale-products.php",{'SellingTitle': $scope.SellingTitle})
    .then(function successCallback(response)
    {
      var data = response.data;
        $scope.TopItemSalesArr = data;
    });
  }

$scope.CustomerTitle="This Month";
  $scope.GetMonthtopCustomers= function()
  {
    $scope.CustomerTitle="This Month";
    //$scope.requestno=requestno;
    //alert($scope.ProjectId);
    $http.post("load-month-topsale-customers.php",{'CustomerTitle': $scope.CustomerTitle})
    .then(function successCallback(response)
    {
      var data = response.data;
        $scope.TopCustomerSalesArr = data;
    });
  }

  $scope.GetLastMonthtopCustomers= function()
  {
     $scope.CustomerTitle="Last Month";
     
    $http.post("load-month-topsale-customers.php",{'CustomerTitle': $scope.CustomerTitle})
    .then(function successCallback(response)
    {
      var data = response.data;
        $scope.TopCustomerSalesArr = data;
    });
  }


//fABRIC START
$scope.TotalProduction = [];
$scope.FabricTitle="This Week";
  $scope.GetFabricWeek= function()
  {
    $scope.FabricTitle="This Week";
    $http.post("load-dashboard-fabric-info.php",{'FabricTitle': $scope.FabricTitle})
    .then(function successCallback(response)
    {
      var data = response.data;
       $scope.TotalProduction = data;
    });
  }

  $scope.GetFabricMonth= function()
  {
     $scope.FabricTitle="This Month";
     
    $http.post("load-dashboard-fabric-info.php",{'FabricTitle': $scope.FabricTitle})
    .then(function successCallback(response)
    {
      var data = response.data;
        $scope.TotalProduction = data;
    });
  }

  $scope.GetFabricYear= function()
  {
     $scope.FabricTitle="This Year";
     
    $http.post("load-dashboard-fabric-info.php",{'FabricTitle': $scope.FabricTitle})
    .then(function successCallback(response)
    {
      var data = response.data;
        $scope.TotalProduction = data;
    });
  }
  //fABRIC End


//PP Bags START
$scope.PPBagProduction = [];
$scope.PPBagTitle="This Week";
  $scope.GetPPBagWeek= function()
  {
    $scope.PPBagTitle="This Week";
    $http.post("load-dashboard-ppbag-info.php",{'PPBagTitle': $scope.PPBagTitle})
    .then(function successCallback(response)
    {
      var data = response.data;
       $scope.PPBagProduction = data;
    });
  }

  $scope.GetPPBagMonth= function()
  {
     $scope.PPBagTitle="This Month";
     
    $http.post("load-dashboard-ppbag-info.php",{'PPBagTitle': $scope.PPBagTitle})
    .then(function successCallback(response)
    {
      var data = response.data;
        $scope.PPBagProduction = data;
    });
  }


  $scope.GetPPBagYear= function()
  {
     $scope.PPBagTitle="This Year";
     
    $http.post("load-dashboard-ppbag-info.php",{'PPBagTitle': $scope.PPBagTitle})
    .then(function successCallback(response)
    {
      var data = response.data;
        $scope.PPBagProduction = data;
    });
  }

$scope.PurcahseArr=[];
  $scope.GetPOAct= function()
  {
    $http.get("load-dashboard-raw-info.php")
    .then(function successCallback(response)
    {
      var data = response.data;
        $scope.PurcahseArr = data;
    });
  }

  $scope.ProductionArr=[];
  $scope.GetProductionAct= function()
  {
    $http.get("load-dashboard-production-info.php")
    .then(function successCallback(response)
    {
      var data = response.data;
        $scope.ProductionArr = data;
    });
  }

   $scope.SalesArr=[];
  $scope.GetSalesAct= function()
  {
    $http.get("load-dashboard-sales-info.php")
    .then(function successCallback(response)
    {
      var data = response.data;
        $scope.SalesArr = data;
    });
  }
  
/*dashboard REPORTS start*/
// $scope.dbCatArray=[];
// $scope.DBInventory = function()
// {
//  $http.post("load-pie-data.php")
//   .then(function successCallback(response)
//     {
//       var data = response.data;
//        $scope.dbCatArray = data;
//        var oldval = [];
//           var k;
//           var newval = [];
//         for (k = 0; k < $scope.dbCatArray.length; k++) {
//          oldval = {c: [
//            {v: $scope.dbCatArray[k]['ProTypeName']},{v: $scope.dbCatArray[k]['AvlStock']}]};
//            newval.push(oldval);
//        }


//         $scope.InventoryChart = {};    
//          $scope.InventoryChart.type = "PieChart";

          
//          $scope.InventoryChart.data = {"cols": [
//              {label: "Topping", type: "string"},
//              {label: "Slices", type: "number"}
//          ], "rows": newval};

//          $scope.InventoryChart.options = {
//              'title': 'Available Stocks',
//              pieSliceText: 'none',
//              is3D:true
//          };
         
//    });
// }

// $scope.GetCatpro= function(catproload)
// {
//  $http.post("dashboard-products.php",{'catproload':catproload})
//    .success(function (data, status, headers, config)
//    {
//        $scope.DbProductsArr = data;
//    })
    
//    .error(function (data, status, headers, config)
//    {
//      $scope.WarningAlert = logResult(data, status, headers, config);
//    }); 

  
// }

$scope.GetProductData= function(location)
{
  
  $http.post("load-inventory-data.php",{'location':location})
    .then(function successCallback(response)
    {
      var data = response.data;
        $scope.DbProjectProductsArr = data;
        //alert(data[0]['MreqCount']);
        //$scope.EstimatedProjectCost = ;
        //$scope.EstimatedProjectMargin = ;

        /*<!--googlechart start-->*/
          var val2 = [];
           var i;
           var val3 = [];
         for (i = 0; i < $scope.DbProjectProductsArr.length; i++) {
          if($scope.DbProjectProductsArr[i]['ProductName']!==undefined && $scope.DbProjectProductsArr[i]['SKU']!==undefined)
          {
          val2 = {c: [
            {v: $scope.DbProjectProductsArr[i]['ProductName']+'\n'+ $scope.DbProjectProductsArr[i]['SKU']},
            {v: $scope.DbProjectProductsArr[i]['AvailableQty']}
          ]};

          val3.push(val2);
        }
        }
          
          $scope.ProductChartObject = {};
    
          $scope.ProductChartObject.type = "BarChart";
      
          $scope.ProductChartObject.data = {"cols": [
              {id: "t", label: "ProductName", type: "string"},
              {id: "s", label: "Avl Qty", type: "number"}
          ], "rows": val3};

          $scope.ProductChartObject.options = {
              'title': 'Inventory information',
               colors: ['#29A2CC','#7CA82B','#EF8535'],
              is3D:true
          };
          /*<!--googlechart end-->*/
    })

  
}



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
