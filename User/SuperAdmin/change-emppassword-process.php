<?php
error_reporting(0);
session_start();
if($_SESSION['UserId']=="")
{
	echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";

}
else
{
	if($_SERVER["REQUEST_METHOD"]==="POST")
	{
		include "../../CommonUtilities/Connections.php";
		include_once "../../CommonUtilities/Functions.php";
		$SAId = $_SESSION['UserId'];
		// $cpwd = json_decode($_GET["SAChangePwd"]);
		
		// $result = array("UserId" => $cpwd->UserId,"pwd" => $cpwd->currentpwd,"npwd" => $cpwd->newpwd,"rpwd" => $cpwd->retypwd);
		
		$data = json_decode(file_get_contents("php://input"));
		
		$UserId = $data->UserId;
		$currentpwd = $data->currentpwd;
		$newpwd = $data->newpwd;
		$retypwd = $data->retypwd;
		/*$currentpwd = $result['pwd'];
		$newpwd = $result['npwd'];
		$retypepwd = $result['rpwd'];*/
		
		$check = 0;
		$Errors .= "";
		
		if($currentpwd=="")
		{
			$check = 1;
			$Errors .= "Current Password is required"."\n";
		}
		if($newpwd=="")
		{
			$check = 1;
			$Errors .= "New Password is required"."\n";
		}
		if($retypwd=="")
		{
			$check = 1;
			$Errors .= "Re-Type Password is required"."\n";
		}
		
		$DATEIME = GetDateTime();
		
		if($check==1){
		echo $Errors;
		}
		if($check==0)
		{
		
			$query = $dbConnection->prepare("SELECT ParolString,TuzString FROM Parolni WHERE UserId_Users=? ORDER BY UserId_Users DESC");
			$query->execute(array($UserId));
			$row = $query->fetch();
			$password = $row['ParolString'];		
			$csalt = $row['TuzString'];
			$currentpwd_hash = hash('sha256', $currentpwd);	
			$encrypted_password = hash('sha256', $csalt . $currentpwd_hash);
			
			if($password==$encrypted_password)
			{
				if($newpwd==$retypwd)
				{
					$hash = hash('sha256', $newpwd);					
					$md5 = md5(uniqid(rand(), TRUE));					
					$salt = substr($md5, 0, 3);					
					$enc_pwd = hash('sha256', $salt . $hash);
					
					$stmt = $dbConnection->prepare("UPDATE Parolni SET ParolString=?,TuzString=? WHERE UserId_Users=?");
					$stmt->execute(array($enc_pwd,$salt,$UserId));
					
					$Post = "$SAId Changed Password of $UserId as on $DATEIME";
					$Type="Change Password";		
					$query2 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
					$query2->execute(array($SAId,$Post,$Type,$UserId,$DATEIME));
					
					$result= "Success";
					echo $result;
				}
				else
				{
					$result = "Please re-type correct password.";
					echo $result;
				}
			}
			else
			{
				$result = "Invalid password.";
				echo $result;
			}
		}
	}
	else
	{
		echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
	}
}
?>
