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
$levelArr = array();


 $stmt = $items->getSingleData();
 $itemCount = $stmt->rowCount();

if($itemCount > 0){
        

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		extract($row);
		//echo $row; 
		$date = strtotime($dataDatetime); 
		//echo date('d/M/Y H:i:s', $date); 
		$e = array(
			"stationName" => $stationName,
			"stationID" => $stationID,
			"deviceID" => $deviceID,
			"dataValue" => $dataValue,
			"dataDatetime" => date('d-m-Y H:i', $date)	
		);

		array_push($levelArr, $e);
	}
	echo json_encode($levelArr);
}

else{
	
	echo json_encode($levelArr);
}

?>