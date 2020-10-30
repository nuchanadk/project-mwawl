<?php
session_start();
$access_username=$_SESSION['uEmail'];
$access_token=$_SESSION["token"];
//$access_type=$_SESSION["type"];
//$access_port=$_SESSION["port"];
$access_name=$_SESSION["name"];
$access_surname=$_SESSION["surname"];
//$access_position=$_SESSION["position"];

$admin[] = array(
                'status' => 'true',
                'message' => 'this log in user',
		'username' => $access_username,
		'access_token' => $access_token,
		'name' => $access_name,
		'surname' => $access_surname
		);
header("Access-Control-Allow-Origin: *");
header("content-type:text/javascript;charset=utf-8");
header("Content-Type: application/json; charset=utf-8", true, 200);
print json_encode(array("data"=>$admin));
?>