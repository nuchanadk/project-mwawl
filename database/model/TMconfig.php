<?php
class TMconfig
{
	private $connection;

	// Table
	private $db_table = "TMconfig";
		
	public $id;
	public $Token;
	public $tokenStatus;

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
					Token = :Token,
					tokenStatus = :tokenStatus";
	
		$stmt = $this->connection->prepare($sqlQuery);
	
		// sanitize
		$this->Token=htmlspecialchars(strip_tags($this->Token));
		$this->tokenStatus=htmlspecialchars(strip_tags($this->tokenStatus));
	
		// bind data
		$stmt->bindParam(':Token', $this->Token);
		$stmt->bindParam(':tokenStatus', $this->tokenStatus);
	
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
		
		$this->Token = $dataRow['Token'];
		$this->tokenStatus = $dataRow['tokenStatus'];
	}        

	// UPDATE
	public function updateData(){
		$sqlQuery = "UPDATE
					". $this->db_table ."
				SET
					Token = :Token,
					tokenStatus = :tokenStatus
				WHERE 
					id = :id";
	
		$stmt = $this->connection->prepare($sqlQuery);
	
		$this->Token=htmlspecialchars(strip_tags($this->Token));
		$this->tokenStatus=htmlspecialchars(strip_tags($this->tokenStatus));
		$this->id=htmlspecialchars(strip_tags($this->id));
	
		// bind data

		$status = ($this->tokenStatus == true ? 1 : 0 );
		$stmt->bindParam(':Token', $this->Token);
		$stmt->bindParam(':tokenStatus', $status);
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