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
		$sqlQuery = "SELECT A.id,A.stationID,A.stationName,B.deviceID,A.stationLat,A.stationLng,A.stationAddress,A.stationStatus FROM " . $this->db_table . " A LEFT JOIN TMdevice B ON A.stationID=B.stationID  order by A.id ";
		$stmt = $this->connection->prepare($sqlQuery);
		$stmt->execute();
		return $stmt;
	}

	// READ Last Data
	public function getLastData(){
		$sqlQuery = "SELECT
					id
				  FROM
					". $this->db_table ."
				ORDER By id DESC
				LIMIT 0,1";

		$stmt = $this->connection->prepare($sqlQuery);

		//$stmt->bindParam(1, $this->id);

		$stmt->execute();

		$dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
		
		return $dataRow['id'];
		

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
	// CREATE
	public function createData(){
		$sqlQuery = "INSERT INTO
					". $this->db_table ."
				SET
				stationID = :stationID,
				stationName = :stationName,
				deviceID = :deviceID,
				stationLat = :stationLat,
				stationLng = :stationLng,
				stationAddress = :stationAddress,
				stationStatus = :stationStatus ";
	
		$stmt = $this->connection->prepare($sqlQuery);
	
		// sanitize
		$this->stationID=htmlspecialchars(strip_tags($this->stationID));
		$this->stationName=htmlspecialchars(strip_tags($this->stationName));
		$this->deviceID=htmlspecialchars(strip_tags($this->deviceID));
		$this->stationLat=htmlspecialchars(strip_tags($this->stationLat));
		$this->stationLng=htmlspecialchars(strip_tags($this->stationLng));
		$this->stationAddress=htmlspecialchars(strip_tags($this->stationAddress));
		
		
		

		// bind data
		
		$stmt->bindParam(':stationID', $this->stationID,PDO::PARAM_STR);
		$stmt->bindParam(':stationName', $this->stationName,PDO::PARAM_STR);
		$stmt->bindParam(':deviceID', $this->deviceID,PDO::PARAM_STR);
		$stmt->bindParam(':stationLat', $this->stationLat,PDO::PARAM_STR);
		$stmt->bindParam(':stationLng', $this->stationLng,PDO::PARAM_STR);
		$stmt->bindParam(':stationAddress', $this->stationAddress,PDO::PARAM_STR);
		$stmt->bindParam(':stationStatus', $this->stationStatus , PDO::PARAM_INT );
		
		if($stmt->execute()){
		   return true;
		}

		//$arr = $stmt->errorInfo();  
		//print_r($arr);
		
		return false;
	} 

	// UPDATE
	public function updateData(){
		$sqlQuery = "UPDATE
					". $this->db_table ."
				SET
				stationName = :stationName,
				deviceID = :deviceID,
				stationLat = :stationLat,
				stationLng = :stationLng,
				stationAddress = :stationAddress,
				stationStatus = :stationStatus
				WHERE 
					id = :id";
	
		$stmt = $this->connection->prepare($sqlQuery);
	
		$this->stationName=htmlspecialchars(strip_tags($this->stationName));
		$this->deviceID=htmlspecialchars(strip_tags($this->deviceID));
		$this->stationLat=htmlspecialchars(strip_tags($this->stationLat));
		$this->stationLng=htmlspecialchars(strip_tags($this->stationLng));
		$this->stationAddress=htmlspecialchars(strip_tags($this->stationAddress));
		$this->stationStatus=htmlspecialchars(strip_tags($this->stationStatus));
		$this->id=htmlspecialchars(strip_tags($this->id));
	
		// bind data
		//$stmt->bindParam(':deviceID', $this->deviceID);
		$stmt->bindParam(':stationName', $this->stationName);
		$stmt->bindParam(':deviceID', $this->deviceID);
		$stmt->bindParam(':stationLat', $this->stationLat);
		$stmt->bindParam(':stationLng', $this->stationLng);
		$stmt->bindParam(':stationAddress', $this->stationAddress);
		$stmt->bindParam(':stationStatus', $this->stationStatus,PDO::PARAM_INT);

		$stmt->bindParam(":id", $this->id,PDO::PARAM_INT);
		
	
		if($stmt->execute()){
		   return true;
		}
		return false;
	}

		// DELETE
		public	function deleteData(){
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