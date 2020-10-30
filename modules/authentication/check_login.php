<?php
require_once ('../../database/db.php');
require_once ('../../database/model/TMuser.php');
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
@$username = $request->username;
@$password = $request->password;
if($username=="")
{
session_start();
session_destroy();

}
else
{

$charmap="1234ABCDEFGHIJKLMNOPQRSTUYWXYZabcdefghijklmnopqrstuvwxyz";
$codRandom = str_shuffle($charmap);

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

$items = new TMuser($conn);
$items->uEmail = $username;
$items->uPassword = $password;
$stmt = $items->getLogin();
$itemCount = $stmt->rowCount();

if($stmt->rowCount() < 1)
{
		echo 'false';
}
else
{
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

		session_start();
		$_SESSION["token"] = $codRandom;
		$_SESSION["uEmail"] = $username;
		//$_SESSION["type"] = $row['UserTypeID'];
		//$_SESSION["port"] = $row['PortLicenseNo'];
		$_SESSION["name"] = $row['uSurname'];
		$_SESSION["surname"] = $row['uLastname'];
		//$_SESSION["position"] = $row['Position'];
				
		echo $_SESSION["token"] ;
	}

}
?>