<?php
error_reporting(0);
session_start();
if($_SESSION['EmpId']!="")
{
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once("../title.php");?>
    	<script src="angularjs/list-roll-inventory-script.js"></script>
    	<style type="text/css">
    		/* Premium Layout Styling */
    		.list-container {
    			background: #ffffff;
    			border-radius: 12px;
    			box-shadow: 0 4px 25px rgba(0,0,0,0.06);
    			padding: 30px;
    			margin-top: 20px;
    			border-top: 4px solid #438eb9;
    			position: relative;
    			transition: all 0.3s ease;
    		}
    		.list-header {
    			margin-bottom: 25px;
    			display: flex;
    			justify-content: space-between;
    			align-items: center;
    			border-bottom: 1px solid #f1f3f5;
    			padding-bottom: 20px;
    		}
    		.list-header h2 {
    			margin: 0;
    			color: #1e293b;
    			font-size: 26px;
    			font-weight: 700;
    			letter-spacing: -0.5px;
    		}
    		.list-header p {
    			margin: 5px 0 0 0;
    			color: #64748b;
    			font-size: 14px;
    		}
    		
    		/* Search & Filter bar */
    		.search-filter-wrapper {
    			display: flex;
    			align-items: center;
    			gap: 12px;
    			margin-bottom: 25px;
    		}
    		.search-input-group {
    			position: relative;
    			flex: 1;
    			max-width: 450px;
    		}
    		.search-input-group i {
    			position: absolute;
    			left: 14px;
    			top: 50%;
    			transform: translateY(-50%);
    			color: #94a3b8;
    			font-size: 16px;
    		}
    		.search-input-group input {
    			padding-left: 40px;
    			height: 44px;
    			border-radius: 8px;
    			border: 1px solid #cbd5e1;
    			font-size: 14px;
    			transition: all 0.2s ease;
    			background-color: #f8fafc;
    			width: 100%;
    		}
    		.search-input-group input:focus {
    			border-color: #438eb9;
    			background-color: #ffffff;
    			box-shadow: 0 0 0 3px rgba(67, 142, 185, 0.15);
    			outline: none;
    		}
    		.btn-filters {
    			height: 44px;
    			padding: 0 20px;
    			border-radius: 8px;
    			border: 1px solid #cbd5e1;
    			background: #ffffff;
    			color: #475569;
    			font-weight: 600;
    			font-size: 14px;
    			display: inline-flex;
    			align-items: center;
    			gap: 8px;
    			cursor: pointer;
    			transition: all 0.2s ease;
    		}
    		.btn-filters:hover {
    			background: #f8fafc;
    			border-color: #94a3b8;
    		}
    		
    		/* Tabs styling */
    		.tabs-wrapper {
    			margin-bottom: 25px;
    			border-bottom: 1px solid #e2e8f0;
    			display: flex;
    			gap: 8px;
    		}
    		.tab-btn {
    			padding: 12px 20px;
    			border: none;
    			background: none;
    			font-size: 15px;
    			font-weight: 600;
    			color: #64748b;
    			cursor: pointer;
    			position: relative;
    			transition: all 0.2s ease;
    			outline: none;
    			display: inline-flex;
    			align-items: center;
    			gap: 8px;
    		}
    		.tab-btn:hover {
    			color: #438eb9;
    		}
    		.tab-btn.active {
    			color: #438eb9;
    		}
    		.tab-btn.active::after {
    			content: '';
    			position: absolute;
    			bottom: -1px;
    			left: 0;
    			width: 100%;
    			height: 3px;
    			background: #438eb9;
    			border-radius: 3px 3px 0 0;
    		}
    		
    		/* Summary Cards */
    		.stats-container {
    			display: grid;
    			grid-template-columns: repeat(4, 1fr);
    			gap: 20px;
    			margin-bottom: 30px;
    		}
    		.stat-card {
    			background: #ffffff;
    			border: 1px solid #e2e8f0;
    			border-radius: 12px;
    			padding: 20px;
    			box-shadow: 0 4px 12px rgba(0,0,0,0.01);
    			display: flex;
    			flex-direction: column;
    			transition: transform 0.2s, box-shadow 0.2s;
    		}
    		.stat-card:hover {
    			transform: translateY(-2px);
    			box-shadow: 0 6px 15px rgba(0,0,0,0.03);
    		}
    		.stat-card-title {
    			font-size: 13px;
    			font-weight: 600;
    			color: #64748b;
    			margin-bottom: 6px;
    			text-transform: uppercase;
    			letter-spacing: 0.5px;
    		}
    		.stat-card-value {
    			font-size: 26px;
    			font-weight: 700;
    			color: #1e293b;
    			line-height: 1.2;
    		}
    		.stat-card-value.green { color: #16a34a; }
    		.stat-card-value.blue { color: #2563eb; }
    		.stat-card-value.orange { color: #ea580c; }
    		.stat-card-subtext {
    			font-size: 12px;
    			color: #94a3b8;
    			margin-top: 4px;
    			font-weight: 500;
    		}
    		
    		/* Stacked Tables Styling */
    		.section-header-custom {
    			font-size: 18px;
    			font-weight: 700;
    			color: #1e293b;
    			margin-top: 35px;
    			margin-bottom: 15px;
    			display: flex;
    			align-items: center;
    			gap: 10px;
    		}
    		.section-header-custom.remnant {
    			color: #7c3aed;
    		}
    		
    		/* Table interactions */
    		.custom-table {
    			width: 100%;
    			border-collapse: separate;
    			border-spacing: 0;
    			border-radius: 8px;
    			overflow: hidden;
    			border: 1px solid #e2e8f0;
    		}
    		.custom-table th {
    			background-color: #f8fafc;
    			color: #475569;
    			font-weight: 600;
    			text-transform: uppercase;
    			font-size: 11px;
    			letter-spacing: 0.5px;
    			padding: 12px 16px;
    			border-bottom: 1px solid #e2e8f0;
    			border-top: none;
    		}
    		.custom-table td {
    			padding: 14px 16px;
    			border-bottom: 1px solid #f1f5f9;
    			color: #334155;
    			font-size: 14px;
    			vertical-align: middle;
    		}
    		.custom-table tbody tr {
    			cursor: pointer;
    			transition: all 0.15s ease;
    		}
    		.custom-table tbody tr:hover {
    			background-color: #f1f5f9 !important;
    		}
    		.custom-table tbody tr.active-row {
    			background-color: #eff6ff !important;
    		}
    		.custom-table tbody tr.active-row td {
    			color: #1e40af;
    		}
    		.roll-select-indicator {
    			width: 18px;
    			height: 18px;
    			border-radius: 50%;
    			border: 2px solid #cbd5e1;
    			display: inline-flex;
    			align-items: center;
    			justify-content: center;
    			transition: all 0.2s ease;
    			margin-right: 8px;
    		}
    		.active-row .roll-select-indicator {
    			border-color: #2563eb;
    			background: #2563eb;
    		}
    		.active-row .roll-select-indicator::after {
    			content: '';
    			width: 6px;
    			height: 6px;
    			background: #ffffff;
    			border-radius: 50%;
    		}
    		
    		/* Badges */
    		.status-badge-custom {
    			display: inline-flex;
    			align-items: center;
    			gap: 6px;
    			padding: 4px 12px;
    			border-radius: 50px;
    			font-size: 12px;
    			font-weight: 600;
    			text-transform: capitalize;
    		}
    		.status-badge-custom.available {
    			background-color: #dcfce7;
    			color: #15803d;
    		}
    		.status-badge-custom.available::before {
    			content: '';
    			display: inline-block;
    			width: 6px;
    			height: 6px;
    			border-radius: 50%;
    			background-color: #16a34a;
    		}
    		.status-badge-custom.reserved {
    			background-color: #ffedd5;
    			color: #c2410c;
    		}
    		.status-badge-custom.reserved::before {
    			content: '';
    			display: inline-block;
    			width: 6px;
    			height: 6px;
    			border-radius: 50%;
    			background-color: #ea580c;
    		}
    		
    		/* Pagination block */
    		.pagination-wrapper {
    			display: flex;
    			justify-content: space-between;
    			align-items: center;
    			margin-top: 15px;
    			margin-bottom: 25px;
    		}
    		.pagination-info {
    			font-size: 13px;
    			color: #64748b;
    		}
    		.pagination-pages {
    			display: flex;
    			align-items: center;
    			gap: 4px;
    		}
    		.page-link-custom {
    			height: 36px;
    			min-width: 36px;
    			padding: 0 8px;
    			border-radius: 6px;
    			border: 1px solid #e2e8f0;
    			background: #ffffff;
    			color: #475569;
    			display: inline-flex;
    			align-items: center;
    			justify-content: center;
    			font-size: 13px;
    			font-weight: 600;
    			cursor: pointer;
    			transition: all 0.2s ease;
    		}
    		.page-link-custom:hover {
    			background: #f1f5f9;
    			border-color: #cbd5e1;
    		}
    		.page-link-custom.active {
    			background: #2563eb;
    			border-color: #2563eb;
    			color: #ffffff;
    		}
    		
    		/* Split Screen Right Detail Panel */
    		.detail-panel {
    			background: #ffffff;
    			border-radius: 12px;
    			border: 1px solid #e2e8f0;
    			box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    			padding: 24px;
    			position: sticky;
    			top: 20px;
    			margin-top: 20px;
    			max-height: calc(100vh - 150px);
    			overflow-y: auto;
    			animation: slideInRight 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    		}
    		@keyframes slideInRight {
    			from { transform: translateX(30px); opacity: 0; }
    			to { transform: translateX(0); opacity: 1; }
    		}
    		.detail-header {
    			display: flex;
    			justify-content: space-between;
    			align-items: flex-start;
    			border-bottom: 1px solid #f1f5f9;
    			padding-bottom: 16px;
    			margin-bottom: 20px;
    		}
    		.detail-title-block p {
    			margin: 0 0 4px 0;
    			font-size: 12px;
    			font-weight: 700;
    			color: #64748b;
    			text-transform: uppercase;
    			letter-spacing: 0.5px;
    		}
    		.detail-title-block h3 {
    			margin: 0;
    			font-size: 22px;
    			font-weight: 800;
    			color: #0f172a;
    			display: flex;
    			align-items: center;
    			gap: 10px;
    		}
    		.detail-close-btn {
    			background: #f1f5f9;
    			border: none;
    			width: 32px;
    			height: 32px;
    			border-radius: 50%;
    			display: flex;
    			align-items: center;
    			justify-content: center;
    			font-size: 18px;
    			color: #64748b;
    			cursor: pointer;
    			transition: all 0.2s ease;
    		}
    		.detail-close-btn:hover {
    			background: #e2e8f0;
    			color: #0f172a;
    		}
    		
    		/* Detail Spec Grid */
    		.detail-spec-grid {
    			display: grid;
    			grid-template-columns: repeat(2, 1fr);
    			gap: 12px;
    			margin-bottom: 24px;
    		}
    		.detail-spec-card {
    			background: #f8fafc;
    			border: 1px solid #e2e8f0;
    			border-radius: 8px;
    			padding: 12px;
    		}
    		.detail-spec-label {
    			font-size: 11px;
    			font-weight: 600;
    			color: #64748b;
    			text-transform: uppercase;
    			margin-bottom: 4px;
    		}
    		.detail-spec-val {
    			font-size: 14px;
    			font-weight: 700;
    			color: #1e293b;
    		}
    		
    		/* Sub-tabs inside Detail panel */
    		.detail-subtabs {
    			display: flex;
    			gap: 4px;
    			border-bottom: 1px solid #e2e8f0;
    			margin-bottom: 20px;
    		}
    		.detail-subtab-btn {
    			flex: 1;
    			padding: 10px;
    			border: none;
    			background: none;
    			font-weight: 600;
    			font-size: 13px;
    			color: #64748b;
    			cursor: pointer;
    			transition: all 0.2s ease;
    			text-align: center;
    			border-bottom: 2px solid transparent;
    			outline: none;
    		}
    		.detail-subtab-btn:hover {
    			color: #2563eb;
    		}
    		.detail-subtab-btn.active {
    			color: #2563eb;
    			border-bottom-color: #2563eb;
    		}
    		
    		/* Detail Blocks */
    		.detail-card-custom {
    			border: 1px solid #e2e8f0;
    			border-radius: 10px;
    			background: #ffffff;
    			padding: 16px;
    			margin-bottom: 16px;
    			box-shadow: 0 2px 8px rgba(0,0,0,0.01);
    		}
    		.detail-card-header {
    			font-size: 14px;
    			font-weight: 700;
    			color: #334155;
    			margin-bottom: 12px;
    			display: flex;
    			align-items: center;
    			gap: 8px;
    			border-bottom: 1px solid #f1f5f9;
    			padding-bottom: 8px;
    		}
    		.detail-card-header i {
    			color: #2563eb;
    		}
    		.detail-info-row {
    			display: flex;
    			justify-content: space-between;
    			font-size: 13px;
    			margin-bottom: 8px;
    		}
    		.detail-info-label {
    			color: #64748b;
    		}
    		.detail-info-value {
    			font-weight: 600;
    			color: #1e293b;
    		}
    		
    		/* Smart Suggestion Item */
    		.suggestion-item {
    			display: flex;
    			align-items: flex-start;
    			gap: 10px;
    			padding: 10px;
    			border-radius: 8px;
    			margin-bottom: 10px;
    			font-size: 13px;
    			background: #f8fafc;
    			border-left: 3px solid #cbd5e1;
    		}
    		.suggestion-item.best-match {
    			background: #f0fdf4;
    			border-left-color: #16a34a;
    		}
    		.suggestion-item.combine-match {
    			background: #faf5ff;
    			border-left-color: #7c3aed;
    		}
    		.suggestion-text {
    			flex: 1;
    			color: #334155;
    			font-weight: 500;
    		}
    		.suggestion-badge {
    			font-size: 10px;
    			font-weight: 700;
    			text-transform: uppercase;
    			padding: 2px 8px;
    			border-radius: 4px;
    		}
    		.suggestion-badge.green {
    			background: #dcfce7;
    			color: #15803d;
    		}
    		.suggestion-badge.purple {
    			background: #f3e8ff;
    			color: #6b21a8;
    			cursor: pointer;
    			border: none;
    			transition: all 0.2s;
    		}
    		.suggestion-badge.purple:hover {
    			background: #e9d5ff;
    		}
    		
    		/* Output Yield Panel */
    		.output-yield-card {
    			background: #eff6ff;
    			border: 1px solid #bfdbfe;
    			border-radius: 8px;
    			padding: 12px 16px;
    			display: flex;
    			justify-content: space-between;
    			align-items: center;
    			margin-bottom: 20px;
    		}
    		.output-yield-title {
    			font-size: 13px;
    			font-weight: 600;
    			color: #1e40af;
    			display: flex;
    			align-items: center;
    			gap: 8px;
    		}
    		.output-yield-val {
    			font-size: 16px;
    			font-weight: 700;
    			color: #1e40af;
    		}
    		
    		/* Slitting Button */
    		.btn-slitting-trigger {
    			width: 100%;
    			height: 48px;
    			background: #2563eb;
    			color: #ffffff;
    			border: none;
    			border-radius: 8px;
    			font-weight: 700;
    			font-size: 15px;
    			display: flex;
    			align-items: center;
    			justify-content: center;
    			gap: 10px;
    			cursor: pointer;
    			transition: all 0.2s ease;
    			box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    		}
    		.btn-slitting-trigger:hover {
    			background: #1d4ed8;
    			box-shadow: 0 6px 15px rgba(37, 99, 235, 0.3);
    		}
    	</style>
	</head>

	<body class="no-skin" data-ng-cloak data-ng-app="RollInventoryModule" data-ng-controller="RollInventoryController">
		<?php include_once("../top.php");?>

		<div class="main-container ace-save-state" id="main-container">
			<?php include_once("sidebar.php");?>

			<div class="main-content">
				<?php include_once("../loader.php");?>
				<div class="main-content-inner">
					<div class="page-content">
						
						<!-- Redesigned Grid Container -->
						<div class="row">
							
							<!-- Left Column (Resizes dynamically) -->
							<div ng-class="selectedRoll ? 'col-xs-12 col-md-8' : 'col-xs-12 col-md-12'" style="transition: all 0.3s ease;">
								<div class="list-container">
									
									<!-- Header -->
									<div class="list-header">
										<div>
											<h2>Inventory</h2>
											<p>Real-time inventory of full rolls and remnants</p>
										</div>
									</div>

									<!-- Search & Filter Area -->
									<div class="search-filter-wrapper">
										<div class="search-input-group">
											<i class="fa fa-search"></i>
											<input type="text" placeholder="Search Roll ID, Width, Status..." ng-model="searchText" ng-change="Search()">
										</div>
										<button class="btn-filters">
											<i class="fa fa-filter"></i> Filters
										</button>
									</div>

									<!-- Tabs -->
									<div class="tabs-wrapper">
										<button class="tab-btn" ng-class="{active: activeTab === 'Full'}" ng-click="SelectTab('Full')">
											<i class="fa fa-folder-open-o"></i> Full Rolls
										</button>
										<button class="tab-btn" ng-class="{active: activeTab === 'Remnant'}" ng-click="SelectTab('Remnant')">
											<i class="fa fa-scissors"></i> Remnants (Short Rolls)
										</button>
									</div>

									<!-- Stats Container -->
									<div class="stats-container" ng-show="items.length > 0">
										<div class="stat-card">
											<span class="stat-card-title">Total Full Rolls</span>
											<span class="stat-card-value">{{ totalFullRollsCount }}</span>
											<span class="stat-card-subtext">Rolls</span>
										</div>
										<div class="stat-card">
											<span class="stat-card-title">Total Weight</span>
											<span class="stat-card-value green">{{ totalFullRollsWeight | number:0 }} kg</span>
											<span class="stat-card-subtext">Dynamic Net Weight</span>
										</div>
										<div class="stat-card">
											<span class="stat-card-title">Available</span>
											<span class="stat-card-value blue">{{ availableFullRollsCount }}</span>
											<span class="stat-card-subtext">Ready for slitting</span>
										</div>
										<div class="stat-card">
											<span class="stat-card-title">Reserved</span>
											<span class="stat-card-value orange">{{ reservedFullRollsCount }}</span>
											<span class="stat-card-subtext">Allotted to slitting jobs</span>
										</div>
									</div>

									<!-- Section 1: Full Rolls Table -->
									<div id="full-rolls-section" class="table-responsive" data-ng-init="LoadInventory()">
										<table class="table custom-table">
											<thead>
												<tr>
													<th>Roll ID</th>
													<th>Width (mm)</th>
													<th>Weight (kg)</th>
													<th>Status</th>
													<th>Inward Date</th>
													<th>Location</th>
													<th style="width: 40px;"></th>
												</tr>
											</thead>
											<tbody>
												<tr ng-repeat="item in fullPagedItems" ng-class="{'active-row': selectedRoll.RollId === item.RollId}" ng-click="OpenRollDetails(item)">
													<td>
														<span class="roll-select-indicator"></span>
														<strong style="color: #2563eb;">{{item.RollId}}</strong>
													</td>
													<td style="font-weight: 600;">{{item.Width}} mm</td>
													<td style="font-weight: 600;">{{item.Weight}} kg</td>
													<td>
														<span class="status-badge-custom" ng-class="item.Status === 'Available' ? 'available' : 'reserved'">
															{{item.Status}}
														</span>
													</td>
													<td>{{item.CreatedDateFormatted}}</td>
													<td>
														<span class="label label-default" style="border-radius: 4px; font-weight: 600;">{{item.Location || 'Not Assigned'}}</span>
													</td>
													<td>
														<i class="fa fa-angle-right" style="color: #94a3b8; font-size: 16px;"></i>
													</td>
												</tr>
												<tr ng-if="fullPagedItems.length == 0">
													<td colspan="7" class="text-center" style="padding: 30px; color: #94a3b8;">
														<i class="fa fa-info-circle fa-lg"></i> No full rolls found.
													</td>
												</tr>
											</tbody>
										</table>
										
										<!-- Full rolls pagination -->
										<div class="pagination-wrapper" ng-if="fullTotalCount > fullPageSize">
											<div class="pagination-info">
												Showing {{((fullCurrentPage-1)*fullPageSize)+1}} to {{Math.min(fullCurrentPage*fullPageSize, fullTotalCount)}} of {{fullTotalCount}} results
											</div>
											<div class="pagination-pages">
												<button class="page-link-custom" ng-click="PrevFullPage()" ng-disabled="fullCurrentPage == 1">
													<i class="fa fa-chevron-left"></i>
												</button>
												<button class="page-link-custom" ng-repeat="page in fullPageRange" ng-class="{active: fullCurrentPage == page}" ng-click="GoToFullPage(page)">
													{{page}}
												</button>
												<button class="page-link-custom" ng-click="NextFullPage()" ng-disabled="fullCurrentPage == fullTotalPages">
													<i class="fa fa-chevron-right"></i>
												</button>
											</div>
										</div>
									</div>

									<!-- Section 2: Remnants (Short Rolls) -->
									<div id="remnants-section" class="section-header-custom remnant">
										<i class="fa fa-scissors"></i> Remnants (Short Rolls)
									</div>
									
									<div class="table-responsive">
										<table class="table custom-table">
											<thead>
												<tr>
													<th>Roll ID</th>
													<th>Width (mm)</th>
													<th>Weight (kg)</th>
													<th>Status</th>
													<th>Inward Date</th>
													<th>Location</th>
													<th style="width: 40px;"></th>
												</tr>
											</thead>
											<tbody>
												<tr ng-repeat="item in remnantPagedItems" ng-class="{'active-row': selectedRoll.RollId === item.RollId}" ng-click="OpenRollDetails(item)">
													<td>
														<span class="roll-select-indicator"></span>
														<strong style="color: #7c3aed;">{{item.RollId}}</strong>
													</td>
													<td style="font-weight: 600;">{{item.Width}} mm</td>
													<td style="font-weight: 600;">{{item.Weight}} kg</td>
													<td>
														<span class="status-badge-custom" ng-class="item.Status === 'Available' ? 'available' : 'reserved'">
															{{item.Status}}
														</span>
													</td>
													<td>{{item.CreatedDateFormatted}}</td>
													<td>
														<span class="label label-default" style="border-radius: 4px; font-weight: 600;">{{item.Location || 'Not Assigned'}}</span>
													</td>
													<td>
														<i class="fa fa-angle-right" style="color: #94a3b8; font-size: 16px;"></i>
													</td>
												</tr>
												<tr ng-if="remnantPagedItems.length == 0">
													<td colspan="7" class="text-center" style="padding: 30px; color: #94a3b8;">
														<i class="fa fa-info-circle fa-lg"></i> No remnants found.
													</td>
												</tr>
											</tbody>
										</table>
										
										<!-- Remnants pagination -->
										<div class="pagination-wrapper" ng-if="remnantTotalCount > remnantPageSize">
											<div class="pagination-info">
												Showing {{((remnantCurrentPage-1)*remnantPageSize)+1}} to {{Math.min(remnantCurrentPage*remnantPageSize, remnantTotalCount)}} of {{remnantTotalCount}} results
											</div>
											<div class="pagination-pages">
												<button class="page-link-custom" ng-click="PrevRemnantPage()" ng-disabled="remnantCurrentPage == 1">
													<i class="fa fa-chevron-left"></i>
												</button>
												<button class="page-link-custom" ng-repeat="page in remnantPageRange" ng-class="{active: remnantCurrentPage == page}" ng-click="GoToRemnantPage(page)">
													{{page}}
												</button>
												<button class="page-link-custom" ng-click="NextRemnantPage()" ng-disabled="remnantCurrentPage == remnantTotalPages">
													<i class="fa fa-chevron-right"></i>
												</button>
											</div>
										</div>
									</div>

								</div>
							</div>
							
							<!-- Right Column: Detail Panel (displays side-by-side) -->
							<div class="col-xs-12 col-md-4" ng-if="selectedRoll" style="transition: all 0.3s ease;">
								<div class="detail-panel">
									
									<!-- Detail Header -->
									<div class="detail-header">
										<div class="detail-title-block">
											<p>{{ selectedRoll.RollType }} Roll</p>
											<h3>
												{{ selectedRoll.RollId }}
												<span class="status-badge-custom" ng-class="selectedRoll.Status === 'Available' ? 'available' : 'reserved'" style="font-size: 11px;">
													{{ selectedRoll.Status }}
												</span>
											</h3>
										</div>
										<button class="detail-close-btn" ng-click="CloseRollDetails()">
											<i class="fa fa-close"></i>
										</button>
									</div>
									
									<!-- Quick Specs Grid -->
									<div class="detail-spec-grid">
										<div class="detail-spec-card">
											<div class="detail-spec-label">Width</div>
											<div class="detail-spec-val">{{ selectedRoll.Width }} mm</div>
										</div>
										<div class="detail-spec-card">
											<div class="detail-spec-label">Weight</div>
											<div class="detail-spec-val">{{ selectedRoll.Weight }} kg</div>
										</div>
										<div class="detail-spec-card">
											<div class="detail-spec-label">Inward Date</div>
											<div class="detail-spec-val">{{ selectedRoll.CreatedDateFormatted }}</div>
										</div>
										<div class="detail-spec-card">
											<div class="detail-spec-label">Location</div>
											<div class="detail-spec-val">{{ selectedRoll.Location || 'Not Assigned' }}</div>
										</div>
										<div class="detail-spec-card" style="grid-column: span 2;">
											<div class="detail-spec-label">Material</div>
											<div class="detail-spec-val">{{ selectedRoll.Material }} - {{ selectedRoll.Thickness }} Micron</div>
										</div>
									</div>
									
									<!-- Subtabs -->
									<div class="detail-subtabs">
										<button class="detail-subtab-btn" ng-class="{active: detailTab === 'Overview'}" ng-click="SetDetailTab('Overview')">Overview</button>
										<button class="detail-subtab-btn" ng-class="{active: detailTab === 'History'}" ng-click="SetDetailTab('History')">History</button>
										<button class="detail-subtab-btn" ng-class="{active: detailTab === 'Movement'}" ng-click="SetDetailTab('Movement')">Movement</button>
									</div>
									
									<!-- Tab Content: Overview -->
									<div ng-show="detailTab === 'Overview'">
										
										<!-- Roll Details Card -->
										<div class="detail-card-custom">
											<div class="detail-card-header">
												<i class="fa fa-database"></i> Roll Details
											</div>
											<div class="detail-info-row">
												<span class="detail-info-label">Roll ID</span>
												<span class="detail-info-value">{{ selectedRoll.RollId }}</span>
											</div>
											<div class="detail-info-row">
												<span class="detail-info-label">Material</span>
												<span class="detail-info-value">{{ selectedRoll.Material }}</span>
											</div>
											<div class="detail-info-row">
												<span class="detail-info-label">Width</span>
												<span class="detail-info-value">{{ selectedRoll.Width }} mm</span>
											</div>
											<div class="detail-info-row">
												<span class="detail-info-label">Thickness</span>
												<span class="detail-info-value">{{ selectedRoll.Thickness }} Micron</span>
											</div>
											<div class="detail-info-row">
												<span class="detail-info-label">Weight</span>
												<span class="detail-info-value">{{ selectedRoll.Weight }} kg</span>
											</div>
											<div class="detail-info-row">
												<span class="detail-info-label">Inward Date</span>
												<span class="detail-info-value">{{ selectedRoll.CreatedDateFormatted }}</span>
											</div>
											<div class="detail-info-row">
												<span class="detail-info-label">Core</span>
												<span class="detail-info-value">{{ selectedRoll.GSM }} Inch</span>
											</div>
											<div class="detail-info-row">
												<span class="detail-info-label">Location</span>
												<span class="detail-info-value">{{ selectedRoll.Location || 'Not Assigned' }}</span>
											</div>
										</div>
										
										<!-- Smart Suggestions Card -->
										<div class="detail-card-custom">
											<div class="detail-card-header">
												<i class="fa fa-flash" style="color: #7c3aed;"></i> Smart Suggestions
											</div>
											
											<!-- Suggested Uses Header -->
											<div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">Suggested Uses</div>
											
											<!-- Suggestion 1: Matches Order -->
											<div class="suggestion-item best-match" ng-if="selectedRoll.MatchingOrderNo">
												<i class="fa fa-check-circle" style="color: #16a34a; font-size: 16px; margin-top: 2px;"></i>
												<div class="suggestion-text">
													Matches <strong>{{ selectedRoll.MatchingOrderNo }}</strong>
													<div style="font-size: 11px; color: #64748b; margin-top: 2px;">{{ selectedRoll.MatchingOrderWidth }}mm required</div>
												</div>
												<span class="suggestion-badge green">Best Match</span>
											</div>
											
											<!-- Suggestion 2: Combine Option -->
											<div class="suggestion-item combine-match" ng-if="selectedRoll.CombineWithRollId">
												<i class="fa fa-link" style="color: #7c3aed; font-size: 14px; margin-top: 3px;"></i>
												<div class="suggestion-text">
													Can combine with <strong>Roll {{ selectedRoll.CombineWithRollId }}</strong>
													<div style="font-size: 11px; color: #64748b; margin-top: 2px;">140mm available</div>
												</div>
												<button class="suggestion-badge purple" ng-click="CreateSlittingJob(selectedRoll)">Combine</button>
											</div>
											
											<!-- Suggestion 3: General slit suggestions -->
											<div class="suggestion-item" style="border-left-color: #2563eb; cursor: pointer;" ng-click="CreateSlittingJob(selectedRoll)">
												<i class="fa fa-info-circle" style="color: #2563eb; font-size: 16px; margin-top: 2px;"></i>
												<div class="suggestion-text">
													Potential for multiple slits
													<div style="font-size: 11px; color: #64748b; margin-top: 2px;">120mm, 200mm, 250mm</div>
												</div>
												<i class="fa fa-chevron-right" style="color: #94a3b8; font-size: 12px; margin-top: 5px;"></i>
											</div>
										</div>
										
										<!-- Output Yield card -->
										<div class="output-yield-card">
											<span class="output-yield-title">
												<i class="fa fa-line-chart"></i> Estimated Output
											</span>
											<span class="output-yield-val">~ {{ (selectedRoll.Weight * 0.77).toFixed(0) }} kg</span>
										</div>
										
										<!-- Slitting job button -->
										<button class="btn-slitting-trigger" ng-click="CreateSlittingJob(selectedRoll)">
											<i class="fa fa-scissors"></i> Create Slitting Job
										</button>
										
									</div>
									
									<!-- Tab Content: History (Mocked placeholder matching premium feel) -->
									<div ng-show="detailTab === 'History'">
										<div class="text-center" style="padding: 40px 20px; color: #94a3b8;">
											<i class="fa fa-history fa-3x" style="margin-bottom: 15px;"></i>
											<p style="font-weight: 600; color: #475569; margin-bottom: 4px;">No History Available</p>
											<p style="font-size: 12px;">This roll has not undergone any slitting or modification operations yet.</p>
										</div>
									</div>
									
									<!-- Tab Content: Movement (Mocked placeholder matching premium feel) -->
									<div ng-show="detailTab === 'Movement'">
										<div class="text-center" style="padding: 40px 20px; color: #94a3b8;">
											<i class="fa fa-truck fa-3x" style="margin-bottom: 15px;"></i>
											<p style="font-weight: 600; color: #475569; margin-bottom: 4px;">No Movement Logged</p>
											<p style="font-size: 12px;">This roll is currently stored at its primary location ({{ selectedRoll.Location || 'Not Assigned' }}).</p>
										</div>
									</div>
									
								</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>

			<?php include_once("../footer.php");?>
		</div>
	</body>
</html>
<?php
}
else
{ 
	header('Location: ../logout.php');
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
}
?>
