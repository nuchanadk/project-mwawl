<?php
class TMalarmconfig
{
	private $connection;

	// Table
	private $db_table = "TMalarmconfig";
		
	public $id;
	public $deviceID;
	public $alarmLL;
	public $alarmL;
	public $alarmH;
	public $alarmHH;
	public $alarmStatus;

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

	// CREATE
	public function createData(){
		$sqlQuery = "INSERT INTO
					". $this->db_table ."
				SET
					deviceID = :deviceID,
					alarmLL = :alarmLL,
					alarmL = :alarmL,
					alarmH = :alarmH,
					alarmHH = :alarmHH,
					alarmStatus = :alarmStatus";
	
		$stmt = $this->connection->prepare($sqlQuery);
	
		// sanitize
		$this->deviceID=htmlspecialchars(strip_tags($this->deviceID));
		$this->alarmLL=htmlspecialchars(strip_tags($this->alarmLL));
		$this->alarmL=htmlspecialchars(strip_tags($this->alarmL));
		$this->alarmH=htmlspecialchars(strip_tags($this->alarmH));
		$this->alarmHH=htmlspecialchars(strip_tags($this->alarmHH));
		$this->alarmStatus=htmlspecialchars(strip_tags($this->alarmStatus));
	
		// bind data
		$stmt->bindParam(':deviceID', $this->deviceID);
		$stmt->bindParam(':alarmLL', $this->alarmLL);
		$stmt->bindParam(':alarmL', $this->alarmL);
		$stmt->bindParam(':alarmH', $this->alarmH);
		$stmt->bindParam(':alarmHH', $this->alarmHH);
		$stmt->bindParam(':alarmStatus', '1' );
	
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
		$this->alarmLL = $dataRow['alarmLL'];
		$this->alarmL = $dataRow['alarmL'];
		$this->alarmH = $dataRow['alarmH'];
		$this->alarmHH = $dataRow['alarmHH'];
		$this->alarmStatus = $dataRow['alarmStatus'];

	}        

	// UPDATE
	public function updateData(){
		$sqlQuery = "UPDATE
					". $this->db_table ."
				SET
					deviceID = :deviceID,
					alarmLL = :alarmLL,
					alarmL = :alarmL,
					alarmH = :alarmH,
					alarmHH = :alarmHH,
					alarmStatus = :alarmStatus
				WHERE 
					id = :id";
	
		$stmt = $this->connection->prepare($sqlQuery);

		$this->deviceID=htmlspecialchars(strip_tags($this->deviceID));
		$this->alarmLL=htmlspecialchars(strip_tags($this->alarmLL));
		$this->alarmL=htmlspecialchars(strip_tags($this->alarmL));
		$this->alarmH=htmlspecialchars(strip_tags($this->alarmH));
		$this->alarmHH=htmlspecialchars(strip_tags($this->alarmHH));
		$this->alarmStatus=htmlspecialchars(strip_tags($this->alarmStatus));
		$this->id=htmlspecialchars(strip_tags($this->id));
		
		$status = ($this->alarmStatus == true ? 1 : 0 );

		// bind data
		$stmt->bindParam(':deviceID', $this->deviceID);
		$stmt->bindParam(':alarmLL', $this->alarmLL);
		$stmt->bindParam(':alarmL', $this->alarmL);
		$stmt->bindParam(':alarmH', $this->alarmH);
		$stmt->bindParam(':alarmHH', $this->alarmHH);
		$stmt->bindParam(':alarmStatus', $status );
		$stmt->bindParam(":id", $this->id);
	
		if($stmt->execute()){
		   return true;
		}
		return false;
	}

	// DELETE
	function deleteData(){
		$sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id = ?";
		$stmt = $this->connection->prepare($sqlQuery);
	
		$this->id=htmlspecialchars(strip_tags($this->id));
	
		$stmt->bindParam(1, $this->id);
	
		if($stmt->execute()){
			return true;
		}
		return false;
	}
}

?>