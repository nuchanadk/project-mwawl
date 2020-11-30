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

if($itemCount > 0)
{   
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		extract($row);
		//echo $row; 

		$sqlQuery2 = " SELECT dataDatetime, dataValue FROM TTdata WHERE deviceID = '$deviceID' and dataStatus = 1 ORDER BY dataDatetime DESC LIMIT 1 ";
		$stmt2 = $conn->prepare($sqlQuery2);
		$stmt2->execute();
		$dataRow = $stmt2->fetch(PDO::FETCH_ASSOC);
		$dataDatetime = $dataRow['dataDatetime'];
		$dataValue = $dataRow['dataValue'];
		$date = ( $dataDatetime != null ) ? strtotime($dataDatetime) :  null ; 
		//echo date('d/M/Y H:i:s', $date); 

		$sqlQuery3 = " SELECT stationID, alarmLL, alarmL, alarmH, alarmHH FROM TMalarmconfig WHERE stationID = '$stationID' and alarmStatus = 1  ";
		$stmt3 = $conn->prepare($sqlQuery3);
		$stmt3->execute();
		$dataalarm = $stmt3->fetch(PDO::FETCH_ASSOC);
		$alarmLL = (float)$dataalarm['alarmLL'];
		$alarmL = (float)$dataalarm['alarmL'];
		$alarmH = (float)$dataalarm['alarmH'];
		$alarmHH = (float)$dataalarm['alarmHH'];

		if ( (float)$dataValue < $alarmLL && $alarmLL != null )
		{
			$alarm_level = "LL";
		}
		else if ( (float)$dataValue < $alarmL && $alarmL != null )
		{
			$alarm_level = "L";
		}
		else if ( (float)$dataValue > $alarmHH && $alarmHH != null )
		{
			$alarm_level = "HH";
		}
		else if ( (float)$dataValue > $alarmH && $alarmH != null )
		{
			$alarm_level = "H";
		}
		else
		{
			$alarm_level = "N";
		}

		if($dataDatetime != null)
		{
			$datadt = new DateTime($dataDatetime);
			$diff = $datadt->diff(new DateTime());
			$minutes = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;

			if( (float)$minutes < 30 )
			{
				$gray = false ; 
			}
			else{ $gray = true; }
		}else { $gray = true; }

		$e = array(
			"stationName" => $stationName,
			"stationID" => $stationID,
			"deviceID" => $deviceID,
			"dataValue" => $dataValue,
			//"datanow" => $datetime1,
			"datadate" => $dataDatetime,
			"dataDatetime" => (( $dataDatetime != null ) ? date('d-m-Y H:i', $date) :  null ),
			"alarmlevel" => $alarm_level,
			"alarmtimemin" => $minutes,
			//"alarmtimeH" => $diffH,
			//"alarmtimeM" => $diffM,
			"alarmtime" => $gray
		);

		array_push($levelArr, $e);
	}
	echo json_encode($levelArr);
}
else
{
	echo json_encode($levelArr);
}

?>