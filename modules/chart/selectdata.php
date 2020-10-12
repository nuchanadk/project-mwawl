<?php
require_once ('../../database/db.php');
require_once ('../../database/model/TTdata.php');
$req = file_get_contents("php://input");
$get = json_decode(stripslashes($req));
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

$items = new TTdata($conn);
$items->deviceID = $get->deviceID;

$dateS = $get->dates;
$dateE = $get->datee;
$type = $get->type;
$levelArr = array();


 $stmt = $items->getDataWhere($dateS,$dateE,$type);
 $itemCount = $stmt->rowCount();

if($itemCount > 0){
        

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		extract($row);
		$date = strtotime($dataDatetime);  
		$e = array(
			"id" => $id,
			"deviceID" => $deviceID,
			"dataValue" => $dataValue,
			"dataDatetime" => date('d-m-Y H:i', $date),
			"chartDatetime" => $dataDatetime,
			"dataStatus" => $dataStatus,
			"dataUpdate" => $dataUpdate
			
			
		);

		array_push($levelArr, $e);
	}
	echo json_encode($levelArr);
}

else{
	
	echo json_encode($levelArr);
}

?>