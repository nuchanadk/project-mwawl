<?php
require_once ('../../database/db.php');
require_once ('../../database/model/TMlevelconfig.php');

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

$item = new TMlevelconfig($conn);

$data = json_decode(file_get_contents("php://input"));

$item->deviceID = $data->deviceID;
$item->levelUp = $data->levelUp;
$item->levelDown  = $data->levelDown;
$item->zeroG  = $data->zeroG;
$item->scale  = $data->scale;
$type  = $data->type;
	
if($type == "Insert")
{
	if($item->createData()){

		//http_response_code(200);
		echo json_encode(array("textdata" => "200"));
	}
	else{
		//http_response_code(400);
		echo json_encode(array("textdata" => "400"));
	}
}

if ($type == 'Update') {

	$item->id  = $data->id;
	if($item->updateData()){

		//http_response_code(200);
		echo json_encode(array("textdata" => "200"));
	}
	else{
		//http_response_code(400);
		echo json_encode(array("textdata" => "400"));
	}
}

if ($type == 'Delete') {

	$item->id  = $data->id;
	if($item->deleteData()){

		//http_response_code(200);
		echo json_encode(array("textdata" => "200"));
	}
	else{
		//http_response_code(400);
		echo json_encode(array("textdata" => "400"));
	}
}

?>