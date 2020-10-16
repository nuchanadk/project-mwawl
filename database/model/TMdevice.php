<?php
class TMdevice
{
	private $connection;

	// Table
	private $db_table = "TMdevice";
		
	public $id;
	public $deviceID;
	public $deviceIP;
	public $stationID;
	public $deviceStatus;

	// Db connection
	public function __construct($db){
		$this->connection = $db;
	}

	// GET ALL
	public function getData(){
		$sqlQuery = "SELECT * FROM " . $this->db_table . " order by id ";
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
		$this->deviceID = $dataRow['deviceID'];
		$this->deviceIP = $dataRow['deviceIP'];
		$this->stationID = $dataRow['stationID'];
		$this->deviceStatus = $dataRow['deviceStatus'];
	}        
}
?>