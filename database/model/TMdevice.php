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
	
	public function UpdatestationID()
	 {
		$sqlQueryUp ="UPDATE
					".$this->db_table."
					SET
					stationID = ''	
					WHERE
					stationID =:stationID";

					$stm = $this->connection->prepare($sqlQueryUp);
					$this->stationID=htmlspecialchars(strip_tags($this->stationID));
					$stm->bindParam(':stationID', $this->stationID,PDO::PARAM_STR);
		$stm->execute();
		
			$sqlQuery ="UPDATE
						".$this->db_table."
						SET
						stationID = :stationID,
						deviceStatus = 1
						WHERE
						deviceID =:deviceID";

			$stmt = $this->connection->prepare($sqlQuery);
			$this->stationID=htmlspecialchars(strip_tags($this->stationID));
			$this->deviceID=htmlspecialchars(strip_tags($this->deviceID));

			$stmt->bindParam(':stationID', $this->stationID,PDO::PARAM_STR);
			$stmt->bindParam(':deviceID', $this->deviceID,PDO::PARAM_STR);
			
			if($stmt->execute()){
				return true;
			}
		
		 return false;


	}
}
?>