<?php
require_once ('../../database/db.php');
require_once ('../../database/model/TTchartdata.php');
$req = file_get_contents("php://input");
$get = json_decode(stripslashes($req));
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

$items = new TTchartdata($conn);

$dateS = $get->dates;
$dateE = $get->datee;
$levelArr = array();

 $stmt = $items->getDatareport($dateS,$dateE);
 $itemCount = $stmt->rowCount();

if($itemCount > 0){
        

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		extract($row);
		$date = strtotime($datadate); 
		$e = array(
			"dataDatetime" => date('m-Y', $date),
			"datadate" => $datadate,
			"STN01" => number_format(((float)$STN01/4320)*100, 2),
			"STN02" => number_format(((float)$STN02/4320)*100, 2),
			"STN03" => number_format(((float)$STN03/4320)*100, 2),
			"STN04" => number_format(((float)$STN04/4320)*100, 2),
			"STN05" => number_format(((float)$STN05/4320)*100, 2),
			"STN06" => number_format(((float)$STN06/4320)*100, 2),
			"STN07" => number_format(((float)$STN07/4320)*100, 2),
			"STN08" => number_format(((float)$STN08/4320)*100, 2),
			"STN09" => number_format(((float)$STN09/4320)*100, 2),
			"STN10" => number_format(((float)$STN10/4320)*100, 2)
		);

		array_push($levelArr, $e);
	}
	echo json_encode($levelArr);
}

else{
	
	echo json_encode($levelArr);
}

?>