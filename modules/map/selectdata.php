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

$ajxres=array(); 
$features=array();
$ajxres['type']='FeatureCollection';

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

		$sqlQuery3 = " SELECT stationID, alarmLL, alarmL, alarmH, alarmHH FROM TMalarmconfig WHERE stationID = '$stationID' and alarmStatus = 1  ";
		$stmt3 = $conn->prepare($sqlQuery3);
		$stmt3->execute();
		$dataalarm = $stmt3->fetch(PDO::FETCH_ASSOC);
		$alarmLL = (float)$dataalarm['alarmLL'];
		$alarmL = (float)$dataalarm['alarmL'];
		$alarmH = (float)$dataalarm['alarmH'];
		$alarmHH = (float)$dataalarm['alarmHH'];

		if ( (float)$dataValue < $alarmLL && $alarmLL != null && (float)$dataValue != 0 )
		{
			$alarm_level = "LL";
		}
		else if ( (float)$dataValue < $alarmL && $alarmL != null && (float)$dataValue != 0 ) 
		{
			$alarm_level = "L";
		}
		else if ( (float)$dataValue > $alarmHH && $alarmHH != null && (float)$dataValue != 0 )
		{
			$alarm_level = "HH";
		}
		else if ( (float)$dataValue > $alarmH && $alarmH != null && (float)$dataValue != 0 )
		{
			$alarm_level = "H";
		}
		else
		{
			$alarm_level = "N";
		}

		if($dataDatetime != null)
		{
			$datenow = date("Y-m-d H:i:s");

			$datetime1 = new DateTime($datenow);
			$datetime2 = new DateTime($dataDatetime);  // change the millenium to see output difference
			$diff = $datetime1->diff($datetime2);

			$diffd = $diff->format('%d');
			$diffH = $diff->format('%H');
			$diffM = $diff->format('%i');

			if( (float)$diffd < 1 )
			{
				if( (float)$diffH < 1 )
				{
					if( (float)$diffM > 30){ $gray = true; }
					else{ $gray = false ; }
				}
				else{ $gray = true; }
			}
			else{ $gray = true; }
		}else { $gray = true; }

		$date = ( $dataDatetime != null ) ? strtotime($dataDatetime) :  null ;
		$prop=array();
		$prop['name']=$stationName;
		$prop['value']= number_format($dataValue, 3);
		$prop['datetime']= (( $dataDatetime != null ) ? date('d-m-Y H:i', $date) :  null );
		$prop['alarmlevel']= $alarm_level;
		$prop['alarmtime']= $gray;
		$f=array();
		$geom=array();
		$coords=array();

		$geom['type']='Point';
		$coords[0]=floatval($stationLng);
		$coords[1]=floatval($stationLat);
		$geom['coordinates']=$coords;
		$f['type']='Feature';
		$f['id']=$stationID;
		$f['geometry']=$geom;
		$f['properties']=$prop;

		$features[]=$f;
	}
	$ajxres['features']=$features;
	echo json_encode($ajxres);
}
else
{
	echo json_encode($ajxres);
}

?>