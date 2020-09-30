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
	public function getData(){
		$sqlQuery = "SELECT * FROM 
			". $this->db_table ."
				WHERE 
				deviceID = :deviceID
				and between id = :id and id = :id
				order by id ";

		$stmt = $this->connection->prepare($sqlQuery);
	
		$this->levelUp=htmlspecialchars(strip_tags($this->levelUp));
		$this->levelDown=htmlspecialchars(strip_tags($this->levelDown));
		$this->id=htmlspecialchars(strip_tags($this->id));
	
		// bind data
		//$stmt->bindParam(':deviceID', $this->deviceID);
		$stmt->bindParam(':deviceID', $this->deviceID);
		$stmt->bindParam(':dataDatetime', $this->levelDown);
		$stmt->bindParam(":id", $this->id);
	
		if($stmt->execute()){
		   return true;
		}
		return false;
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