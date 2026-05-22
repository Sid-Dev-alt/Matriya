var ListInwardApp = angular.module("ListInwardModule", ['ui.bootstrap','angularUtils.directives.dirPagination','datatables','ui.select', 'ngSanitize']);

ListInwardApp.controller("ListInwardController", function ($scope, $timeout, $http, $window) {
	$scope.items = [];
	$scope.pagedItems = [];
	$scope.searchText = "";
	$scope.currentPage = 1;
	$scope.pageSize = 10;
	$scope.totalCount = 0;
	$scope.totalPages = 0;
	$scope.pageRange = [];
	$scope.Math = window.Math;

	$scope.LoadInwardEntries = function() {
		var postData = {
			search: $scope.searchText
		};
		
		$http.post("load-inward-entries.php", postData)
		.then(function successCallback(response) {
			if (response.data !== "NoData" && angular.isArray(response.data)) {
				$scope.items = response.data;
				$scope.totalCount = $scope.items.length;
				$scope.totalPages = Math.ceil($scope.totalCount / $scope.pageSize);
				$scope.GoToPage(1);
			} else {
				$scope.items = [];
				$scope.pagedItems = [];
				$scope.totalCount = 0;
				$scope.totalPages = 0;
			}
		});
	};

	$scope.Search = function() {
		$scope.currentPage = 1;
		$scope.LoadInwardEntries();
	};

	$scope.GoToPage = function(page) {
		if (page < 1 || page > $scope.totalPages) return;
		$scope.currentPage = page;
		
		var start = (page - 1) * $scope.pageSize;
		var end = start + $scope.pageSize;
		$scope.pagedItems = $scope.items.slice(start, end);
		
		// Update page range
		var range = [];
		var startPage = Math.max(1, page - 2);
		var endPage = Math.min($scope.totalPages, page + 2);
		for (var i = startPage; i <= endPage; i++) {
			range.push(i);
		}
		$scope.pageRange = range;
	};

	$scope.PrevPage = function() {
		if ($scope.currentPage > 1) {
			$scope.GoToPage($scope.currentPage - 1);
		}
	};

	$scope.NextPage = function() {
		if ($scope.currentPage < $scope.totalPages) {
			$scope.GoToPage($scope.currentPage + 1);
		}
	};

	$scope.PrintLabel = function(id) {
		var printUrl = "print-inward-barcode.php?Id=" + id;
		$window.open(printUrl, '_blank');
	};
});
