<?php
require_once ('../../database/db.php');
require_once ('../../database/model/TMstation.php');
$req = file_get_contents("php://input");
$get = json_decode(stripslashes($req));
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

$items = new TMstation($conn);
$levelArr = array();

$stmt = $items->getData();
$itemCount = $stmt->rowCount();

if($itemCount > 0){
        

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		extract($row);
		$e = array(
			"id" => $id,
			"stationID" => $stationID,
			"stationName" => $stationName,
			"deiviceID" => $deiviceID,
			"stationLat" => $stationLat,
			"stationLng" => $stationLng,
			"stationAddress" => $stationAddress,
			"stationStatus" => $stationStatus
			
			
		);

		array_push($levelArr, $e);
	}
	echo json_encode($levelArr);
}

else{
	
	echo json_encode($levelArr);
}

?>