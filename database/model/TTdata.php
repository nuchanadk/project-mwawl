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
		$sqlQuery = "SELECT * FROM " . $this->db_table . " order by id ";
		$stmt = $this->connection->prepare($sqlQuery);
		$stmt->execute();
		return $stmt;
	}
	
	// GET where
	public function getDataWhere($dateS,$dateE,$type){
		$sqlQuery = "SELECT * FROM 
			". $this->db_table ."
				WHERE deviceID = ? and dataDatetime between ? and ? order by id ";

		$stmt = $this->connection->prepare($sqlQuery);
	
		$this->deviceID=htmlspecialchars(strip_tags($this->deviceID));
		
		// bind data
		
		$stmt->execute([$this->deviceID,$dateS,$dateE]);
		
	
		
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
		
		$this->deviceID = $dataRow['deviceID'];
		$this->dataValue = $dataRow['dataValue'];
		$this->dataDatetime = $dataRow['dataDatetime'];
		$this->dataStatus = $dataRow['dataStatus'];
		$this->dataUpdate = $dataRow['dataUpdate'];

	}        
}

?>