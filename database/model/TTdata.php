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
		$sqlQuery = "SELECT * FROM 
			". $this->db_table ."
				WHERE deviceID = ? and dataDatetime between ? and ? order by dataDatetime ";

		$stmt = $this->connection->prepare($sqlQuery);
	
		$this->deviceID=htmlspecialchars(strip_tags($this->deviceID));
		
		// bind data
		
		$stmt->execute(array($this->deviceID,$dateS,$dateE));
		return $stmt;
		
	}

	// READ single
	public function getSingleData($dateS,$dateE,$type){
		$sqlQuery = "SELECT * FROM 
			". $this->db_table ."
				WHERE deviceID = ? and dataDatetime between ? and ? order by dataDatetime desc LIMIT 0,1";

		$stmt = $this->connection->prepare($sqlQuery);
	
		$this->deviceID=htmlspecialchars(strip_tags($this->deviceID));
		
		// bind data
		
		$stmt->execute(array($this->deviceID,$dateS,$dateE));
		return $stmt;
		
	}     
}
?>