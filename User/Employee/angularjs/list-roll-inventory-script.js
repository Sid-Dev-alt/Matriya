var RollInventoryApp = angular.module("RollInventoryModule", ['ui.bootstrap','angularUtils.directives.dirPagination','datatables','ui.select', 'ngSanitize']);

RollInventoryApp.controller("RollInventoryController", function ($scope, $timeout, $http, $window) {
	$scope.items = [];
	$scope.fullPagedItems = [];
	$scope.remnantPagedItems = [];
	$scope.searchText = "";
	$scope.activeTab = "Full"; // 'Full' or 'Remnant'
	
	// Pagination details - Full Rolls
	$scope.fullCurrentPage = 1;
	$scope.fullPageSize = 10;
	$scope.fullTotalCount = 0;
	$scope.fullTotalPages = 0;
	$scope.fullPageRange = [];
	
	// Pagination details - Remnants
	$scope.remnantCurrentPage = 1;
	$scope.remnantPageSize = 10;
	$scope.remnantTotalCount = 0;
	$scope.remnantTotalPages = 0;
	$scope.remnantPageRange = [];
	
	$scope.Math = window.Math;
	
	// Selection & Detail Panel State
	$scope.selectedRoll = null;
	$scope.detailTab = "Overview"; // 'Overview', 'History', 'Movement'
	
	// Stats Cards
	$scope.totalFullRollsCount = 0;
	$scope.totalFullRollsWeight = 0;
	$scope.availableFullRollsCount = 0;
	$scope.reservedFullRollsCount = 0;

	// Load all rolls from backend
	$scope.LoadInventory = function() {
		var postData = {
			search: $scope.searchText
		};
		
		$http.post("load-roll-inventory.php", postData)
		.then(function successCallback(response) {
			if (response.data !== "NoData" && angular.isArray(response.data)) {
				$scope.items = response.data;
				$scope.CalculateStats();
				$scope.FilterAndPaginate();
			} else {
				$scope.items = [];
				$scope.fullPagedItems = [];
				$scope.remnantPagedItems = [];
				$scope.ResetStats();
			}
		});
	};
	
	$scope.ResetStats = function() {
		$scope.totalFullRollsCount = 0;
		$scope.totalFullRollsWeight = 0;
		$scope.availableFullRollsCount = 0;
		$scope.reservedFullRollsCount = 0;
		$scope.fullTotalCount = 0;
		$scope.fullTotalPages = 0;
		$scope.remnantTotalCount = 0;
		$scope.remnantTotalPages = 0;
	};
	
	$scope.CalculateStats = function() {
		var fullRolls = $scope.items.filter(function(item) {
			return item.RollType === 'Full';
		});
		
		$scope.totalFullRollsCount = fullRolls.length;
		
		var totalWeight = 0;
		var availableCount = 0;
		var reservedCount = 0;
		
		fullRolls.forEach(function(item) {
			totalWeight += parseFloat(item.Weight);
			if (item.Status === 'Available') {
				availableCount++;
			} else if (item.Status === 'Reserved') {
				reservedCount++;
			}
		});
		
		$scope.totalFullRollsWeight = totalWeight;
		$scope.availableFullRollsCount = availableCount;
		$scope.reservedFullRollsCount = reservedCount;
	};

	// Filter and Paginate both lists independently
	$scope.FilterAndPaginate = function() {
		// 1. Filter & Paginate Full Rolls
		var fullList = $scope.items.filter(function(item) {
			return item.RollType === 'Full';
		});
		
		$scope.fullTotalCount = fullList.length;
		$scope.fullTotalPages = Math.ceil($scope.fullTotalCount / $scope.fullPageSize);
		
		if ($scope.fullCurrentPage > $scope.fullTotalPages && $scope.fullTotalPages > 0) {
			$scope.fullCurrentPage = $scope.fullTotalPages;
		}
		
		var fullStart = ($scope.fullCurrentPage - 1) * $scope.fullPageSize;
		var fullEnd = fullStart + $scope.fullPageSize;
		$scope.fullPagedItems = fullList.slice(fullStart, fullEnd);
		
		// Full Page Range
		var fullRange = [];
		var fullStartPage = Math.max(1, $scope.fullCurrentPage - 2);
		var fullEndPage = Math.min($scope.fullTotalPages, $scope.fullCurrentPage + 2);
		for (var i = fullStartPage; i <= fullEndPage; i++) {
			fullRange.push(i);
		}
		$scope.fullPageRange = fullRange;
		
		// 2. Filter & Paginate Remnants
		var remnantList = $scope.items.filter(function(item) {
			return item.RollType === 'Remnant';
		});
		
		$scope.remnantTotalCount = remnantList.length;
		$scope.remnantTotalPages = Math.ceil($scope.remnantTotalCount / $scope.remnantPageSize);
		
		if ($scope.remnantCurrentPage > $scope.remnantTotalPages && $scope.remnantTotalPages > 0) {
			$scope.remnantCurrentPage = $scope.remnantTotalPages;
		}
		
		var remnantStart = ($scope.remnantCurrentPage - 1) * $scope.remnantPageSize;
		var remnantEnd = remnantStart + $scope.remnantPageSize;
		$scope.remnantPagedItems = remnantList.slice(remnantStart, remnantEnd);
		
		// Remnant Page Range
		var remnantRange = [];
		var remnantStartPage = Math.max(1, $scope.remnantCurrentPage - 2);
		var remnantEndPage = Math.min($scope.remnantTotalPages, $scope.remnantCurrentPage + 2);
		for (var j = remnantStartPage; j <= remnantEndPage; j++) {
			remnantRange.push(j);
		}
		$scope.remnantPageRange = remnantRange;
	};

	// Tab Selection with Smooth Scroll
	$scope.SelectTab = function(tabType) {
		$scope.activeTab = tabType;
		var elementId = tabType === 'Full' ? 'full-rolls-section' : 'remnants-section';
		var element = document.getElementById(elementId);
		if (element) {
			element.scrollIntoView({ behavior: 'smooth', block: 'start' });
		}
	};

	// Triggered on search text change
	$scope.Search = function() {
		$scope.fullCurrentPage = 1;
		$scope.remnantCurrentPage = 1;
		$scope.CloseRollDetails();
		$scope.LoadInventory();
	};

	// Roll Details Selection
	$scope.OpenRollDetails = function(roll) {
		$scope.selectedRoll = roll;
		$scope.detailTab = "Overview"; // Reset to Overview tab when a new roll is clicked
	};

	$scope.CloseRollDetails = function() {
		$scope.selectedRoll = null;
	};

	// Pagination Actions - Full Rolls
	$scope.GoToFullPage = function(page) {
		if (page < 1 || page > $scope.fullTotalPages) return;
		$scope.fullCurrentPage = page;
		$scope.FilterAndPaginate();
	};

	$scope.PrevFullPage = function() {
		if ($scope.fullCurrentPage > 1) {
			$scope.GoToFullPage($scope.fullCurrentPage - 1);
		}
	};

	$scope.NextFullPage = function() {
		if ($scope.fullCurrentPage < $scope.fullTotalPages) {
			$scope.GoToFullPage($scope.fullCurrentPage + 1);
		}
	};

	// Pagination Actions - Remnants
	$scope.GoToRemnantPage = function(page) {
		if (page < 1 || page > $scope.remnantTotalPages) return;
		$scope.remnantCurrentPage = page;
		$scope.FilterAndPaginate();
	};

	$scope.PrevRemnantPage = function() {
		if ($scope.remnantCurrentPage > 1) {
			$scope.GoToRemnantPage($scope.remnantCurrentPage - 1);
		}
	};

	$scope.NextRemnantPage = function() {
		if ($scope.remnantCurrentPage < $scope.remnantTotalPages) {
			$scope.GoToRemnantPage($scope.remnantCurrentPage + 1);
		}
	};

	// Set active tab inside detail panel
	$scope.SetDetailTab = function(tabName) {
		$scope.detailTab = tabName;
	};

	// Redirect to Slitting Job
	$scope.CreateSlittingJob = function(roll) {
		$window.location.href = "add-slitting.php?RollId=" + roll.RollId;
	};
});
