/* Module Starts Here*/
var app = angular.module('organisation_detailsApp', [])
app.controller('organisation_detailsCtrl', function ($scope, $http)
{
	
	$scope.input=false;
	$scope.list=true;
	$scope.edit=false;
	$scope.true_false = function()
	{
	
		if($scope.GST_present=="yes")
		{
			$scope.input=true;
		}
		if($scope.GST_present=="no")
		{
			$scope.input=false;
		}
		
	}
	
	$scope.portal=function()
	{
		$scope.portal_name=$scope.organisation_name;
	}
	
	$scope.adddb_organisation_details = function()
	{
		$http.post('adddb_organisation_details.php',
		{
			'organisation_name':$scope.organisation_name,
			'portal_name':$scope.portal_name,
			'Buissness_location':$scope.Buissness_location,
			'contact_person_name':$scope.contact_person_name,
			'email_id':$scope.email_id,
			'mobile_no':$scope.mobile_no,
			'Street1':$scope.Street1,
			'Street2':$scope.Street2,
			'state':$scope.state,
			'city':$scope.city,
			'pin':$scope.pin,
			'currency':$scope.currency,
			'language':$scope.language,
			'GST_present':$scope.GST_present,
			'GST_no':$scope.GST_no,
			'Intra_state_tax_rate':$scope.Intra_state_tax_rate,
			'Intra_state_GST_rate':$scope.Intra_state_GST_rate,
		})
		.success(function(data, status) 
		{
			console.log(data);
			$scope.status = status; 
			$scope.data = data;
			$scope.result = data; 
			alert("Data has been Added Successfully");
			window.open("list_organisation_details.php","_self");
		});
	}
	$scope.list_organisation = function()
	{
		$http.post('list_organisation.php')
		.success(function(data, status) 
		{
			$scope.data=data;
		});	
	}
	$scope.delete_org = function(org_id)
	{
		$http.post('delete_org.php',{'org_id':org_id})
		.success(function(data, status) 
		{
			console.log(data);
			$scope.status = status; 
			$scope.data = data;
			$scope.result = data; 
			alert("Data has been Deleted Successfully");
			window.open("list_organisation_details.php","_self");
		});	
	}
	
	$scope.edit_org = function(org_id)
	{
		$http.post('edit_org.php',{'org_id':org_id})
		.success(function(data, status) 
		{
			$scope.org_id=data[0]["org_id"];
			$scope.organisation_name=data[0]["organisation_name"];
			$scope.portal_name=data[0]["portal_name"];
			$scope.Buissness_location=data[0]["bussness_location"];
			$scope.contact_person_name=data[0]["contact_person_name"];
			$scope.email_id=data[0]["email_id"];
			$scope.mobile_no=data[0]["mobile_no"];
			$scope.Street1=data[0]["street1"];
			$scope.Street2=data[0]["street2"];
			$scope.state=data[0]["state"];
			$scope.city=data[0]["city"];
			$scope.pin=data[0]["pin"];
			$scope.currency=data[0]["regional_currency"];
			$scope.language=data[0]["regional_language"];
			$scope.GST_present=data[0]["GST_present"];
			if($scope.GST_present=="yes")
			{
				$scope.input=true;
				$scope.GST_no=data[0]["GST_num"];
			}
			if($scope.GST_present=="no")
			{
				$scope.input=false;
			}
			
			$scope.Intra_state_tax_rate=data[0]["Intra_state_tax_rate"];
			$scope.Intra_state_GST_rate=data[0]["Intra_state_GST_rate"];
			$scope.tenant_id=data[0]["tenant_id"];
			
			$scope.list=false;
			$scope.edit=true;
		});	
	}
	
	$scope.updatedb_organisation_details = function()
	{
		$http.post('updatedb_organisation_details.php',
		{
			'org_id':$scope.org_id,
			'tenant_id':$scope.tenant_id,
			'organisation_name':$scope.organisation_name,
			'portal_name':$scope.portal_name,
			'Buissness_location':$scope.Buissness_location,
			'contact_person_name':$scope.contact_person_name,
			'email_id':$scope.email_id,
			'mobile_no':$scope.mobile_no,
			'Street1':$scope.Street1,
			'Street2':$scope.Street2,
			'state':$scope.state,
			'city':$scope.city,
			'pin':$scope.pin,
			'currency':$scope.currency,
			'language':$scope.language,
			'GST_present':$scope.GST_present,
			'GST_no':$scope.GST_no,
			'Intra_state_tax_rate':$scope.Intra_state_tax_rate,
			'Intra_state_GST_rate':$scope.Intra_state_GST_rate,
		})
		.success(function(data, status) 
		{
			console.log(data);
			$scope.status = status; 
			$scope.data = data;
			$scope.result = data; 
			alert("Data has been Added Successfully");
			window.open("list_organisation_details.php","_self");
		});
	}
});