<?php
require_once ('../../database/db.php');
require_once ('../../database/model/TTdata.php');
require_once ('../../database/model/TMalarmconfig.php');
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

$stmt = $items->getSingleDataRealtime();
$itemCount = $stmt->rowCount();
//echo $itemCount;

if($itemCount > 0){
        

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		extract($row);
		//echo $row; 

		$sqlQuery2 = " SELECT dataDatetime, dataValue FROM TTdata WHERE deviceID = '$deviceID' ORDER BY dataDatetime DESC LIMIT 1 ";
		$stmt2 = $conn->prepare($sqlQuery2);
		$stmt2->execute();
		$dataRow = $stmt2->fetch(PDO::FETCH_ASSOC);
		$dataDatetime = $dataRow['dataDatetime'];
		$dataValue = $dataRow['dataValue'];
		$date = strtotime($dataDatetime); 
		//echo date('d/M/Y H:i:s', $date); 

		$sqlQuery3 = " SELECT stationID, alarmLL, alarmL, alarmH, alarmHH FROM TMalarmconfig WHERE stationID = '$stationID' and alarmStatus = 1  ";
		$stmt3 = $conn->prepare($sqlQuery3);
		$stmt3->execute();
		$dataalarm = $stmt3->fetch(PDO::FETCH_ASSOC);
		$alarmLL = $dataalarm['alarmLL'];
		$alarmL = $dataalarm['alarmL'];
		$alarmH = $dataalarm['alarmH'];
		$alarmHH = $dataalarm['alarmHH'];

		if ( $dataValue < $alarmLL && $alarmLL != null )
		{
			$alarm_level = "LL";
		}
		else if ( $dataValue < $alarmL && $alarmL != null )
		{
			$alarm_level = "L";
		}
		else if ( $dataValue > $alarmHH && $alarmHH != null )
		{
			$alarm_level = "HH";
		}
		else if ( $dataValue > $alarmH && $alarmH != null )
		{
			$alarm_level = "H";
		}
		else
		{
			$alarm_level = "N";
		}

		$e = array(
			"stationName" => $stationName,
			"stationID" => $stationID,
			"deviceID" => $deviceID,
			"dataValue" => $dataValue,
			"dataDatetime" => date('d-m-Y H:i', $date),
			"alarmlevel" => $alarm_level
		);

		array_push($levelArr, $e);
	}
	echo json_encode($levelArr);
}

else{
	
	echo json_encode($levelArr);
}

?>