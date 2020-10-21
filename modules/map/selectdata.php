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

$ajxres=array(); 
$features=array();
$ajxres['type']='FeatureCollection';

if($itemCount > 0){
        
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		extract($row);
		//echo $row; 
		$date = strtotime($dataDatetime); 
		$prop=array();
		$prop['name']=$stationName;
		$prop['value']=$dataValue;
		$prop['datetime']=date('d-m-Y H:i', $date);
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

else{
	
	echo json_encode($ajxres);
}

?>