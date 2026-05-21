<?php
error_reporting(0);
session_start();
	if($_SERVER["REQUEST_METHOD"]==="POST")
	{
		if(isset($_GET["SALogin"]))
		{
			include "CommonUtilities/Connections.php";
			include_once "CommonUtilities/Functions.php";
			
			$parameters = json_decode($_GET["SALogin"]);
			$result = array("username" => $parameters->email,"password" => $parameters->password);
			
			$username = $result['username'];
			$password = $result['password'];		
			$delete_status = 1;
			$data1 = array();
			$sql = $dbConnection->prepare("SELECT UserId,RoleId_Roles,Name,EmailId FROM Users WHERE EmailId=? AND Status=? AND DeleteStatus=?");
			$sql->execute(array($username,$delete_status,$delete_status));
			$count = $sql->rowCount();
			if($count>0)
			{
				$row = $sql->fetch();
				$Id = $row['UserId'];
				$Name = $row['Name'];
				$Email = $row['EmailId'];
				$RoleId = $row['RoleId_Roles'];
								
				$sql1 = $dbConnection->prepare("SELECT RoleName FROM Roles WHERE RoleId=? AND DeleteStatus=?");
				$sql1->execute(array($RoleId,$delete_status));
				$row1 = $sql1->fetch();
				$Role = $row1['RoleName'];
								
				$sql2 = $dbConnection->prepare("SELECT AppInfoId,AppName,AppURL,FromEmailId,PrimaryEmailId,CarbonCopyEmailId,BlindCarbonCopyEmailId FROM AppInfo WHERE DeleteStatus=?");
				$sql2->execute(array($delete_status));
				$row2 = $sql2->fetch();
				$AppName = $row2['AppName'];
				$FromId = $row2['FromEmailId'];
				$PrimaryEmailId = $row2['PrimaryEmailId'];
				$Cc = $row2['CarbonCopyEmailId'];
				$Bcc = $row2['BlindCarbonCopyEmailId'];
				$AppURL = $row2['AppURL'];
				
				$sql_one = $dbConnection->prepare("SELECT UserId_Users,ParolString,TuzString FROM Parolni WHERE UserId_Users=?");
				$sql_one->execute(array($Id));
				$count_one = $sql_one->rowCount();
				if($count_one>0)
				{
					$row_one = $sql_one->fetch();
					$password_string = $row_one['ParolString'];
					$salt_string = $row_one['TuzString'];
						
					$hashkey = hash('sha256',$password);
					$password_hashkey = hash('sha256',$salt_string.$hashkey);
					
					if($password_string==$password_hashkey)
					{
						$_SESSION['AppName'] = $AppName;
						$_SESSION['AppURL'] = $AppURL;
						$_SESSION['FromEmailId'] = $FromId;
						$_SESSION['PrimaryEmailId'] = $PrimaryEmailId;
						$_SESSION['Cc'] = $Cc;
						$_SESSION['Bcc'] = $Bcc;

						if($RoleId==1) //Super Admin
						{
							$_SESSION['UserId'] = $Id;
							$_SESSION['SAName'] = $Name;
							$_SESSION['SAEmail'] = $Email;
							$_SESSION['SARole'] = $Role;
							$_SESSION['SARoleId'] = $RoleId;
							$URL = "User/SuperAdmin/welcome.php";
						}
						else if($RoleId==2) //Employee
						{
							$_SESSION['EmpId'] = $Id;
							$_SESSION['EmpName'] = $Name;
							$_SESSION['EmpEmail'] = $Email;
							$_SESSION['EmpRole'] = $Role;
							$_SESSION['EmpRoleId'] = $RoleId;
							$URL = "User/Employee/welcome.php";
						}
						// elseif($RoleId==3) //Inventory 
						// 						{
						// 	$_SESSION['IPId']=$Id;
						// 	$_SESSION['IPName'] = $Name;
						// 	$_SESSION['IPEmail'] = $Email;
						// 	$_SESSION['IPRole'] = $Role;
						// 	$URL = "User/Inventory/welcome.php";
						// }

						$_SESSION['UserRoleId'] = $RoleId;
						

						$ip = GetIP();						
						$DATEIME = GetDateTime();					
											
						$log = $dbConnection->prepare("INSERT INTO LoginInfo (UserId_Users,IPAddress,LogInTime,CreatedTime) VALUES (?,?,?,?)");
						$log->execute(array($Id,$ip,$DATEIME,$DATEIME));						
						$pkid = $dbConnection->lastInsertId();
						
						$_SESSION['LogId'] = $pkid;
						
						$result = "Success";
						$data1[] = array("result"=>$result,"URL"=>$URL);
						echo json_encode($data1);
					}
					else
					{
						$result = "Invalid";
						$URL = "Invalid password";
						$data1[] = array("result"=>$result,"URL"=>$URL);
						echo json_encode($data1);
					}
				}
				else
				{
					$result = "Invalid";
						$URL = "Invalid password";
						$data1[] = array("result"=>$result,"URL"=>$URL);
						echo json_encode($data1);					
				}				
			}
			else
			{
				$result = "Invalid";
				$URL = "Invalid Username";
				$data1[] = array("result"=>$result,"URL"=>$URL);
				echo json_encode($data1);					
			}				
		}
		else
		{
			$result = "Invalid";
			$URL = "Enter Mandatory Fields";
			$data1[] = array("result"=>$result,"URL"=>$URL);
			echo json_encode($data1);
		}
	}
	else
	{
		$result = "Invalid";
			$URL = "Invalid Data";
			$data1[] = array("result"=>$result,"URL"=>$URL);
			echo json_encode($data1);
	}
?>