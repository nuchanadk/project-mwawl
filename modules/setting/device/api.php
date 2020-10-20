<?php

require_once ('../../../database/db.php');
require_once ('../../../database/model/TMdevice.php');

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

$item = new TMdevice($conn);

$data = json_decode(file_get_contents("php://input"));

?>