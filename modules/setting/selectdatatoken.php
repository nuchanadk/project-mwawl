<?php
require_once ('../../database/db.php');
require_once ('../../database/model/TMconfig.php');

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

$items = new TMconfig($conn);

$stmt = $items->getData();
$itemCount = $stmt->rowCount();
$levelArr = array();
if($itemCount > 0){
        
	
	//$levelArr["body"] = array();
	//$levelArr["itemCount"] = $itemCount;

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		extract($row);
		$status = ($tokenStatus == 1 ? true : false );
		$e = array(
			"id" => $id,
			"Token" => $Token,
			"tokenStatus" => filter_var($status, FILTER_VALIDATE_BOOLEAN) 
		);

		array_push($levelArr, $e);
	}
	echo json_encode($levelArr);
}

else{
	//http_response_code(404);
	//echo json_encode(array("message" => "No record found."));
	echo json_encode($levelArr);
}

?>