<?php
function GetIP()
{
	//return getenv("REMOTE_ADDR");
	$ipaddress = '';
	if(isset($_SERVER['HTTP_CLIENT_IP']))
	{
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	{
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	elseif(isset($_SERVER['HTTP_X_FORWARDED']))
	{
		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	}
	elseif(isset($_SERVER['HTTP_FORWARDED_FOR']))
	{
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	}
	elseif(isset($_SERVER['HTTP_FORWARDED']))
	{
		$ipaddress = $_SERVER['HTTP_FORWARDED'];
	}
	elseif(isset($_SERVER['REMOTE_ADDR']))
	{
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	}
	else
	{
		$ipaddress = 'UNKNOWN';
	}
	return $ipaddress;
}

function GetDateTime()
{
	$time = date('Y-m-d H:i:s');
	return $time;
}

function GetCurrentDateTime()
{
	$ctime = date('d-m-Y h:i A');
	return $ctime;
}

function GetTDate()
{
	$date = date('Y-m-d');
	return $date;
}

function GetYDate()
{
	$ydate = date('Ymd');
	return $ydate;
}
?>
