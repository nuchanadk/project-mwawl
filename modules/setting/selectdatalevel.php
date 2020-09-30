<?php
require_once ('../../database/db.php');
require_once ('../../database/model/TMlevelconfig.php');

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

$items = new TMlevelconfig($conn);

$stmt = $items->getData();
$itemCount = $stmt->rowCount();
$levelArr = array();
if($itemCount > 0){
        
	
	//$levelArr["body"] = array();
	//$levelArr["itemCount"] = $itemCount;

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		extract($row);
		$e = array(
			"id" => $id,
			"deviceID" => $deviceID,
			"levelUp" => $levelUp,
			"levelDown" => $levelDown
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


/*$query = "SELECT * FROM TMlevelconfig ORDER BY id";

$stmt = $conn->prepare( $query );
$stmt->execute();
$num = $stmt->rowCount();

$outp = "[";

while ( $array = $stmt->fetch(PDO::FETCH_ASSOC) )
{
	$id= $array['id'];
	$deviceID= $array['deviceID'];
	$levelUp = $array['levelUp'];
	$levelDown = $array['levelDown'];

	if ( $outp != "[" ) { $outp .= ","; }
	$outp .= "{";

	$outp .= ' "id" : "'.$id.'",';
	$outp .= ' "deviceID" : "'.$deviceID.'",';
	$outp .= ' "levelUp" : "'.$levelUp.'",';
	$outp .= ' "levelDown" : "'.$levelDown.'"';
	
	$outp .= "}";
}
$outp .="]";
echo $outp;*/

?>