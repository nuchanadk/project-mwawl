<?php
class TMalarmconfig
{
	private $connection;

	// Table
	private $db_table = "TMalarmconfig";
		
	public $id;
	public $stationID;
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
		$sqlQuery = "SELECT A.id,A.alarmL,A.alarmLL,A.alarmH,A.alarmHH,A.alarmStatus,B.stationName,A.stationID FROM " . $this->db_table . " A LEFT JOIN TMstation B ON A.stationID = B.stationID order by A.id ";
		$stmt = $this->connection->prepare($sqlQuery);
		$stmt->execute();
		return $stmt;
	}

	// CREATE
	public function createData(){
		$sqlQuery = "INSERT INTO
					". $this->db_table ."
				SET
					stationID = :stationID,
					alarmLL = :alarmLL,
					alarmL = :alarmL,
					alarmH = :alarmH,
					alarmHH = :alarmHH,
					alarmStatus = :alarmStatus";
	
		$stmt = $this->connection->prepare($sqlQuery);
	
		// sanitize
		$this->stationID=htmlspecialchars(strip_tags($this->stationID));
		$this->alarmLL=htmlspecialchars(strip_tags($this->alarmLL));
		$this->alarmL=htmlspecialchars(strip_tags($this->alarmL));
		$this->alarmH=htmlspecialchars(strip_tags($this->alarmH));
		$this->alarmHH=htmlspecialchars(strip_tags($this->alarmHH));
		$this->alarmStatus=htmlspecialchars(strip_tags($this->alarmStatus));
	
		// bind data
		$stmt->bindParam(':stationID', $this->stationID,PDO::PARAM_STR);
		$stmt->bindParam(':alarmLL', $this->alarmLL,PDO::PARAM_STR);
		$stmt->bindParam(':alarmL', $this->alarmL,PDO::PARAM_STR);
		$stmt->bindParam(':alarmH', $this->alarmH,PDO::PARAM_STR);
		$stmt->bindParam(':alarmHH', $this->alarmHH,PDO::PARAM_STR);
		$stmt->bindParam(':alarmStatus', $this->alarmStatus,PDO::PARAM_INT);
	
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
		
		$this->stationID = $dataRow['stationID'];
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
				stationID = :stationID,
					alarmLL = :alarmLL,
					alarmL = :alarmL,
					alarmH = :alarmH,
					alarmHH = :alarmHH,
					alarmStatus = :alarmStatus
				WHERE 
					id = :id";
	
		$stmt = $this->connection->prepare($sqlQuery);

		$this->stationID=htmlspecialchars(strip_tags($this->stationID));
		$this->alarmLL=htmlspecialchars(strip_tags($this->alarmLL));
		$this->alarmL=htmlspecialchars(strip_tags($this->alarmL));
		$this->alarmH=htmlspecialchars(strip_tags($this->alarmH));
		$this->alarmHH=htmlspecialchars(strip_tags($this->alarmHH));
		$this->alarmStatus=htmlspecialchars(strip_tags($this->alarmStatus));
		$this->id=htmlspecialchars(strip_tags($this->id));
		
		$status = (int)$this->alarmStatus ;

		// bind data
		$stmt->bindParam(':stationID', $this->stationID,PDO::PARAM_STR);
		$stmt->bindParam(':alarmLL', $this->alarmLL,PDO::PARAM_STR);
		$stmt->bindParam(':alarmL', $this->alarmL,PDO::PARAM_STR);
		$stmt->bindParam(':alarmH', $this->alarmH,PDO::PARAM_STR);
		$stmt->bindParam(':alarmHH', $this->alarmHH,PDO::PARAM_STR);
		$stmt->bindParam(':alarmStatus', $status ,PDO::PARAM_INT);
		$stmt->bindParam(":id", $this->id,PDO::PARAM_INT);
	
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