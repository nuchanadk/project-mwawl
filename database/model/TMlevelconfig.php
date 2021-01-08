<?php
class TMlevelconfig
{
	private $connection;

	// Table
	private $db_table = "TMlevelconfig";
		
	public $id;
	public $deviceID;
	public $levelUp;
	public $levelDown;
	public $zeroG;

	// Db connection
	public function __construct($db){
		$this->connection = $db;
	}

    /*public function __construct($id,$uEmail,$uPassword,$uSurname,$uLastname,$uStatus) {
		$this->id = $id;
		$this->uEmail = $uEmail;
		$this->uPassword = $uPassword;
		$this->uSurname = $uSurname;
		$this->uLastname = $uLastname;
		$this->uStatus = $uStatus;
	}*/

	// GET ALL
	public function getData(){
		$sqlQuery = "SELECT A.id,A.deviceID,A.levelUp,A.levelDown,A.zeroG,B.stationID,B.stationName,B.stationID FROM " . $this->db_table . " A Left JOIN TMstation B ON A.deviceID = B.deviceID order by A.id ";
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
					levelUp = :levelUp,
					levelDown = :levelDown,
					zeroG = :zeroG";
	
		$stmt = $this->connection->prepare($sqlQuery);
	
		// sanitize
		$this->deviceID=htmlspecialchars(strip_tags($this->deviceID));
		$this->levelUp=htmlspecialchars(strip_tags($this->levelUp));
		$this->levelDown=htmlspecialchars(strip_tags($this->levelDown));
		$this->zeroG=htmlspecialchars(strip_tags($this->zeroG));
	
		// bind data
		$stmt->bindParam(':deviceID', $this->deviceID);
		$stmt->bindParam(':levelUp', $this->levelUp);
		$stmt->bindParam(':levelDown', $this->levelDown);
		$stmt->bindParam(':zeroG', $this->zeroG);
	
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
		$this->levelUp = $dataRow['levelUp'];
		$this->levelDown = $dataRow['levelDown'];
		$this->zeroG = $dataRow['zeroG'];

	}        

	// UPDATE
	public function updateData(){
		$sqlQuery = "UPDATE
					". $this->db_table ."
				SET
					levelUp = :levelUp,
					levelDown = :levelDown,
					zeroG = :zeroG
				WHERE 
					id = :id";
	
		$stmt = $this->connection->prepare($sqlQuery);
	
		$this->levelUp=htmlspecialchars(strip_tags($this->levelUp));
		$this->levelDown=htmlspecialchars(strip_tags($this->levelDown));
		$this->zeroG=htmlspecialchars(strip_tags($this->zeroG));
		$this->id=htmlspecialchars(strip_tags($this->id));
	
		// bind data
		//$stmt->bindParam(':deviceID', $this->deviceID);
		$stmt->bindParam(':levelUp', $this->levelUp);
		$stmt->bindParam(':levelDown', $this->levelDown);
		$stmt->bindParam(':zeroG', $this->zeroG);
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