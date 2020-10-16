<?php
class TMstation
{
	private $connection;

	// Table
	private $db_table = "TMstation";
		
	public $id;
	public $stationID;
	public $stationName;
	public $deviceID;
	public $stationLat;
	public $stationLng;
	public $stationAddress;
	public $stationStatus;

	// Db connection
	public function __construct($db){
		$this->connection = $db;
	}

	// GET ALL
	public function getData(){
		$sqlQuery = "SELECT * FROM " . $this->db_table . " where stationStatus='1' order by id ";
		$stmt = $this->connection->prepare($sqlQuery);
		$stmt->execute();
		return $stmt;
	}

	// READ single
	public function getSingleData(){
		$sqlQuery = "SELECT
					*
				  FROM
					". $this->db_table ."
				WHERE 
				   id = ?
				LIMIT 0,1";

		$stmt = $this->connection->prepare($sqlQuery);

		$stmt->bindParam(1, $this->id);

		$stmt->execute();

		$dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$this->id = $dataRow['id'];
		$this->stationID = $dataRow['stationID'];
		$this->stationName = $dataRow['stationName'];
		$this->deviceID = $dataRow['deviceID'];
		$this->stationLat = $dataRow['stationLat'];
		$this->stationLng = $dataRow['stationLng'];
		$this->stationAddress = $dataRow['stationAddress'];
		$this->stationStatus = $dataRow['stationStatus'];

	}        
}
?>