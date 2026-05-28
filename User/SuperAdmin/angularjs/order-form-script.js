var app = angular.module("OrderModule", ['ui.bootstrap', 'ui.select', 'ngSanitize']);

app.controller("OrderController", function($scope, $http, $timeout) {
    $scope.orders = [];
    $scope.orderDetails = [];
    
    // Arrays for dropdowns
    $scope.CustomerArray = [];
    $scope.ProductArray = [];
    
    // Initialize form and dynamic rows
    $scope.initForm = function() {
        // Generates Order-Id matching layout format like MPO112
        $scope.OrderId = "MPO" + Math.floor(100 + Math.random() * 900);
        $scope.OrderDate = new Date();
        $scope.partyname = "";
        
        // Start with one empty item details row
        $scope.itemRows = [
            { selectedProduct: null, Quantity: "", Remarks: "" }
        ];
        
        $scope.submitted = false;
    };
    
    // Datepicker configuration
    $scope.opened = {};
    $scope.openDatepicker = function($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.opened.openedDate = true;
    };
    
    // Fetch initial data
    $scope.initData = function() {
        $scope.initForm();
        $scope.GetCustomers();
        $scope.GetProducts();
        $scope.loadData();
    };
    
    // Fetch customers
    $scope.GetCustomers = function() {
        $http.get("load-vendors.php").then(function(response) {
            if (response.data !== "NoData") {
                $scope.CustomerArray = response.data;
            } else {
                $scope.CustomerArray = [];
            }
        });
    };
    
    // Fetch products
    $scope.GetProducts = function() {
        $http.get("load-all-product-types.php").then(function(response) {
            if (response.data !== "NoData") {
                $scope.ProductArray = response.data;
            } else {
                $scope.ProductArray = [];
            }
        });
    };
    
    // Load historical orders and details for tables below
    $scope.loadData = function() {
        $http.get("load-custom-orders.php").then(function(response) {
            if (response.data !== "NoData") {
                $scope.orders = response.data;
            } else {
                $scope.orders = [];
            }
        });
        
        $http.get("load-custom-order-details.php").then(function(response) {
            if (response.data !== "NoData") {
                $scope.orderDetails = response.data;
            } else {
                $scope.orderDetails = [];
            }
        });
    };
    
    // Add row to item details table
    $scope.addRow = function() {
        $scope.itemRows.push({ selectedProduct: null, Quantity: "", Remarks: "" });
    };
    
    // Remove row from item details table
    $scope.removeRow = function(index) {
        $scope.itemRows.splice(index, 1);
    };
    
    // Product select change handler to set focus or custom logic if needed
    $scope.onProductSelect = function(row, index) {
        if (row.selectedProduct) {
            // Can pre-populate details or defaults if needed
        }
    };
    
    // Save order
    $scope.saveOrder = function() {
        $scope.submitted = true;
        
        if (!$scope.OrderId || !$scope.OrderDate || !$scope.partyname) {
            swal("Error", "Please fill in all mandatory fields (Order ID, Date, and Party Name).", "error");
            return;
        }
        
        // Build items payload
        var itemsPayload = [];
        var isValid = true;
        
        for (var i = 0; i < $scope.itemRows.length; i++) {
            var row = $scope.itemRows[i];
            if (!row.selectedProduct || !row.Quantity || parseFloat(row.Quantity) <= 0) {
                isValid = false;
                break;
            }
            itemsPayload.push({
                ProductName: row.selectedProduct.TotalProductName,
                Quantity: parseFloat(row.Quantity),
                Remarks: row.Remarks
            });
        }
        
        if (!isValid || itemsPayload.length === 0) {
            swal("Error", "Please select a product and enter a valid quantity for all rows.", "error");
            return;
        }
        
        var payload = {
            OrderId: $scope.OrderId,
            CustomerName: $scope.partyname.DisplayName,
            OrderDate: $scope.OrderDate,
            TotalAmount: 0.00,
            items: itemsPayload
        };
        
        $http.post("save-custom-order.php", payload).then(function(response) {
            if (response.data.trim() === "Success") {
                swal({
                    title: "Success",
                    text: "Order and item details saved successfully!",
                    type: "success",
                    timer: 1500
                });
                $scope.loadData();
                $scope.initForm();
            } else {
                swal("Error", response.data, "error");
            }
        }, function(err) {
            swal("Error", "Failed to save order", "error");
        });
    };
    
    // Cancel action
    $scope.cancelOrder = function() {
        window.location.href = "order.php";
    };
});
