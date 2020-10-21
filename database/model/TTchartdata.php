<?php
class TTchartdata
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
	
	// GET where
	public function getDataWhere($dateS,$dateE,$type){
		$sqlQuery = " SELECT a.stationName, a.stationID, c.deviceID, c.dataValue, c.dataDatetime
		FROM TMstation a
		LEFT JOIN TMdevice b ON a.stationID = b.stationID
		LEFT JOIN TTdata c ON b.deviceID = c.deviceID
		WHERE c.dataDatetime AND b.deviceStatus = 1
		AND a.stationID = ? AND c.dataDatetime between ? and ? order by c.dataDatetime ";

		$stmt = $this->connection->prepare($sqlQuery);
	
		$this->stationID=htmlspecialchars(strip_tags($this->stationID));
	
		$stmt->execute(array($this->stationID,$dateS,$dateE));
		return $stmt;
		
	}

	// GET reportgauge
	public function getDatareport($dateS,$dateE){
		$sqlQuery = " SELECT DATE( r.dataDatetime) as datadate
	   	, count(CASE WHEN r.stationID = 'STN01' THEN r.dataValue END) 'STN01'
		, count(CASE WHEN r.stationID = 'STN02' THEN r.dataValue END) 'STN02'
		, count(CASE WHEN r.stationID = 'STN03' THEN r.dataValue END) 'STN03'
   		, count(CASE WHEN r.stationID = 'STN04' THEN r.dataValue END) 'STN04'
   		, count(CASE WHEN r.stationID = 'STN05' THEN r.dataValue END) 'STN05'
   		, count(CASE WHEN r.stationID = 'STN06' THEN r.dataValue END) 'STN06'
   		, count(CASE WHEN r.stationID = 'STN07' THEN r.dataValue END) 'STN07'
   		, count(CASE WHEN r.stationID = 'STN08' THEN r.dataValue END) 'STN08'
   		, count(CASE WHEN r.stationID = 'STN09' THEN r.dataValue END) 'STN09'
   		, count(CASE WHEN r.stationID = 'STN10' THEN r.dataValue END) 'STN10'
   		FROM (
   				SELECT a.stationName, a.stationID, c.deviceID, c.dataValue, c.dataDatetime
		   		FROM TMstation a
		   		LEFT JOIN TMdevice b ON a.stationID = b.stationID
		   		LEFT JOIN TTdata c ON b.deviceID = c.deviceID
	   		  ) r
		where  YEAR(r.dataDatetime) = YEAR(?) AND MONTH(r.dataDatetime) = MONTH(?)
   		GROUP BY DATE( r.dataDatetime) ORDER BY DATE( r.dataDatetime)";

		$stmt = $this->connection->prepare($sqlQuery);
	
		//$this->stationID=htmlspecialchars(strip_tags($this->stationID));
		
		$stmt->execute(array($dateS,$dateE));
		return $stmt;
		
	}
}
?>