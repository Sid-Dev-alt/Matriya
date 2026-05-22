var InwardApp = angular.module("InwardModule", ['ui.bootstrap','angularUtils.directives.dirPagination','datatables','ui.select', 'ngSanitize']);

InwardApp.controller("InwardController", function ($scope, $timeout, $http, $window) {
	$scope.inward = {
		print_label: true,
		core_size: '3 Inch',
		invoice_date: new Date()
	};
	$scope.VendorArray = [];
	$scope.submitted = false;

	// Datepicker settings
	$scope.opened = {};
	$scope.singleopen = function($event, opened) {
		$event.preventDefault();
		$event.stopPropagation();
		$scope.opened[opened] = true;
	};
	$scope.dateOptions = {
		formatYear: 'yy',
		maxDate: new Date(2030, 5, 22),
		minDate: new Date(2010, 1, 1),
		startingDay: 1
	};

	// Fetch Vendors
	$scope.GetVendors = function() {
		$http.post("load-vendors.php")
		.then(function successCallback(response) {
			if (response.data !== "NoData") {
				$scope.VendorArray = response.data;
			}
		});
	};

	// Save Inward Entry
	$scope.SaveInwardData = function() {
		$scope.submitted = true;
		
		// Custom Validation
		if (!$scope.inward.supplier || !$scope.inward.material || !$scope.inward.gsm || 
			!$scope.inward.thickness || !$scope.inward.width || !$scope.inward.weight || 
			!$scope.inward.cost || !$scope.inward.invoice_no || !$scope.inward.invoice_date || 
			!$scope.inward.core_size) {
			swal("Validation Error", "Please fill in all mandatory fields.", "error");
			return;
		}

		// Prepare data for transmission
		var postData = {
			VendorId: $scope.inward.supplier.VendorId,
			VendorPkId: $scope.inward.supplier.PkId,
			Material: $scope.inward.material,
			GSM: $scope.inward.gsm,
			Thickness: $scope.inward.thickness,
			Width: $scope.inward.width,
			Weight: $scope.inward.weight,
			CostPerKg: $scope.inward.cost,
			InvoiceNo: $scope.inward.invoice_no,
			InvoiceDate: $scope.inward.invoice_date,
			CoreSize: $scope.inward.core_size,
			Notes: $scope.inward.notes || ''
		};

		$http.post("add-inward-entry-process.php", postData)
		.then(function successCallback(response) {
			var data = response.data;
			if (data && data.result === "Success") {
				swal({
					title: "Success",
					text: "Inward entry saved successfully!\nRoll ID: " + data.RollId,
					type: "success",
					showCancelButton: false,
					confirmButtonColor: "#3085d6",
					confirmButtonText: "OK",
					closeOnConfirm: true
				}, function() {
					// If print checked, open the printable barcode label
					if ($scope.inward.print_label) {
						var printUrl = "print-inward-barcode.php?Id=" + data.PkId;
						$window.open(printUrl, '_blank');
					}
					// Redirect to listing page
					$window.location.href = "list-inward-entries.php";
				});
			} else {
				// If not success, response data will be string error message
				swal("Error", data || response.data, "error");
			}
		}, function errorCallback(response) {
			swal("Error", "Server error occurred while saving. Please try again.", "error");
		});
	};

	$scope.Cancel = function() {
		$window.location.href = "list-inward-entries.php";
	};
});
