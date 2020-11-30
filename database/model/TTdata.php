<?php
class TTdata
{
	private $connection;

	// Table
	private $db_table = "TTdata";
		
	public $id;
	public $deviceID;
	public $dataValue;
	public $dataDatetime;
	public $dataStatus;
	public $dataUpdate;

	// Db connection
	public function __construct($db){
		$this->connection = $db;
	}

	// GET ALL
	public function getAllData(){
		$sqlQuery = "SELECT * FROM " . $this->db_table . " order by dataDatetime ";
		$stmt = $this->connection->prepare($sqlQuery);
		$stmt->execute();
		return $stmt;
	}
	
	// GET where
	public function getDataWhere($dateS,$dateE,$type){
		$sqlQuery = " SELECT a.stationName, a.stationID, c.deviceID, c.dataValue, c.dataDatetime
		FROM TMstation a
		LEFT JOIN TMdevice b ON a.stationID = b.stationID
		LEFT JOIN TTdata c ON b.deviceID = c.deviceID
		WHERE c.dataDatetime AND b.deviceStatus = 1 AND c.dataStatus = 1 
		AND a.stationID = ? AND c.dataDatetime between ? and ? order by c.dataDatetime ";

		$stmt = $this->connection->prepare($sqlQuery);
	
		$this->stationID=htmlspecialchars(strip_tags($this->stationID));
		
		// bind data
		
		$stmt->execute(array($this->stationID,$dateS,$dateE));
		return $stmt;
		
	}

	// READ single
	public function getSingleData(){
		$sqlQuery = " SELECT a.stationName, a.stationID, c.deviceID, c.dataValue, c.dataDatetime,a.stationLat,a.stationLng,a.stationAddress
		FROM TMstation a
		LEFT JOIN TMdevice b ON a.stationID = b.stationID
		LEFT JOIN TTdata c ON b.deviceID = c.deviceID
		WHERE c.dataDatetime
		IN (
		SELECT MAX( f.dataDatetime )
		FROM TTdata f
		WHERE f.deviceID = b.deviceID
		)
		AND b.deviceStatus = 1 order by a.stationID ";

		$stmt = $this->connection->prepare($sqlQuery);
		$stmt->execute();
		return $stmt;
		
	}    
	
	public function getSingleDataRealtime(){
		$rowArr = array();
		$sqlQuery = " SELECT a.stationName, a.stationID, a.deviceID,a.stationLat,a.stationLng,a.stationAddress
		FROM TMstation a where stationStatus = 1 order by a.stationID ";

		$stmt = $this->connection->prepare($sqlQuery);
		$stmt->execute();
		return $stmt;
	}   
}
?>