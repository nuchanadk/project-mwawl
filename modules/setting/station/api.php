<?php
require_once ('../../../database/db.php');
require_once ('../../../database/model/TMstation.php');
require_once ('../../../database/model/TMdevice.php');

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

$items = new TMstation($conn);
$itemsDevice = new TMdevice($conn);
$data = json_decode(file_get_contents("php://input"));

$items->stationName = $data->stationName;
$items->deviceID = $data->deviceID;
$items->stationLat = $data->stationLat;
$items->stationLng = $data->stationLng;
$items->stationAddress = $data->stationAddress;
$itemsDevice->deviceID = $data->deviceID;


if($data->type=='Insert')
{

	$stnID = $items->getLastData();
	if(strlen($stnID) < 2)
	{
		$items->stationID = "STN0".($stnID + 1) ;
		$itemsDevice->stationID = "STN0".($stnID + 1);

	}
	else
	{
		$items->stationID = "STN".($stnID + 1);
		$itemsDevice->stationID = "STN".($stnID + 1);

	}

    $items->stationStatus = 1;

    if($items->createData() && $itemsDevice->UpdatestationID()){
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
	$items->stationID = $data->stationID ;
	$itemsDevice->stationID = $data->stationID ;

	$items->stationStatus = (int)$data->stationStatus;

    if($items->updateData() && $itemsDevice->UpdatestationID()){
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
			"stationID" => $stationID,
			"stationName" => $stationName,
			"deviceID" => $deviceID,
            "stationLat" => floatval($stationLat),
            "stationLng" => floatval($stationLng),
            "stationAddress" => $stationAddress,
            "stationStatus" => filter_var($stationStatus, FILTER_VALIDATE_BOOLEAN)
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