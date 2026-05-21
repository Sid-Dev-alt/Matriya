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
			$cpwd = json_decode($_GET["SAChangePwd"]);
			$result = array("pwd" => $cpwd->currentpwd,"npwd" => $cpwd->newpwd,"rpwd" => $cpwd->retypwd);
			
			$currentpwd = $result['pwd'];
			$newpwd = $result['npwd'];
			$retypepwd = $result['rpwd'];
			
			$DATEIME = GetDateTime();
			
			$query = $dbConnection->prepare("SELECT ParolString,TuzString FROM Parolni WHERE UserId_Users=? ORDER BY UserId_Users DESC");
			$query->execute(array($SAId));
			$row = $query->fetch();
			$password = $row['ParolString'];		
			$csalt = $row['TuzString'];
			$currentpwd_hash = hash('sha256', $currentpwd);	
			$encrypted_password = hash('sha256', $csalt . $currentpwd_hash);
			
			if($password==$encrypted_password)
			{
				if($newpwd==$retypepwd)
				{
					$hash = hash('sha256', $newpwd);					
					$md5 = md5(uniqid(rand(), TRUE));					
					$salt = substr($md5, 0, 3);					
					$enc_pwd = hash('sha256', $salt . $hash);
					
					$stmt = $dbConnection->prepare("UPDATE Parolni SET ParolString=?,TuzString=? WHERE UserId_Users=?");
					$stmt->execute(array($enc_pwd,$salt,$SAId));
					
					$Post = "$SAId Changed Password as on $DATEIME";
					$Type="Change Password";		
					$query2 = $dbConnection->prepare("INSERT INTO ActivityLog (UserId_Users,ActivityInfo,ModuleType,UpdatedId,CreatedTime) VALUES (?,?,?,?,?)");
					$query2->execute(array($SAId,$Post,$Type,$SAId,$DATEIME));
					
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
	else
	{
		echo "<script language=\"javascript\">window.location=\"../../index.php\";</script>";
	}
}
?>
