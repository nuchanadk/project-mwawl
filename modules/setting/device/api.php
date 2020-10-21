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

$items = new TMdevice($conn);

$data = json_decode(file_get_contents("php://input"));

$items->deviceID = $data->deviceID;


if($data->type=='Insert')
{

	$items->deviceStatus = 1;

    if($items->createData()){
		//http_response_code(200);
		echo json_encode(array("textdata" => "200"));
	}
	else{
		//http_response_code(400);
		echo json_encode(array("textdata" => "400"));
	}

}
if($data->type=='Update')
{
	$items->id = (int)$data->id ;
	$items->deviceID = $data->deviceID ;
	
	$items->deviceStatus = (int)$data->deviceStatus;

    if($items->updateData()){
		//http_response_code(200);
		echo json_encode(array("textdata" => "200"));
	}
	else{
		//http_response_code(400);
		echo json_encode(array("textdata" => "400"));
	}

}

if($data->type =='Delete')
{
	
	$items->id = (int)$data->id ;

	if($items->deleteData()){
		//http_response_code(200);
		echo json_encode(array("textdata" => "200"));
	}
	else{
		//http_response_code(400);
		echo json_encode(array("textdata" => "400"));
	}



}


if($_GET['r']=='get')
{
$stmt = $items->getData();
$itemCount = $stmt->rowCount();
$levelArr = array();
if($itemCount > 0){
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		extract($row);
		$e = array(
            "id" => $id,
            "deviceID" => $deviceID,
            "deviceIP" => $deviceIP,
			"stationID" => $stationID,
            "deviceStatus" => filter_var($deviceStatus, FILTER_VALIDATE_BOOLEAN)
		);

		array_push($levelArr, $e);
	}
	echo json_encode($levelArr);
}

else{
	
	echo json_encode($levelArr);
}


}

?>