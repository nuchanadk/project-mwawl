<?php
class TMuser
{
	private $connection;

	// Table
	private $db_table = "TMuser";
		
	public $id;
	public $uEmail;
	public $uPassword;
	public $uSurname;
	public $uLastname;
	public $uStatus;

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
					uEmail = :uEmail,
					uPassword = :uPassword,
					uSurname = :uSurname,
					uLastname = :uLastname,
					uStatus = :uStatus";
	
		$stmt = $this->connection->prepare($sqlQuery);
	
		// sanitize
		$this->uEmail=htmlspecialchars(strip_tags($this->uEmail));
		$this->uPassword=htmlspecialchars(strip_tags($this->uPassword));
		$this->uSurname=htmlspecialchars(strip_tags($this->uSurname));
		$this->uLastname=htmlspecialchars(strip_tags($this->uLastname));
		//$this->uStatus=htmlspecialchars(strip_tags($this->uStatus));
		
		$password_hash = md5($this->uPassword);

		// bind data
		$stmt->bindParam(':uEmail', $this->uEmail);
		$stmt->bindParam(':uPassword', $password_hash);
		$stmt->bindParam(':uSurname', $this->uSurname);
		$stmt->bindParam(':uLastname', $this->uLastname);
		$stmt->bindParam(':uStatus', '1' );
	
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
		
		$this->uEmail = $dataRow['uEmail'];
		$this->uPassword = $dataRow['uPassword'];
		$this->uSurname = $dataRow['uSurname'];
		$this->uLastname = $dataRow['uLastname'];
		$this->uStatus = $dataRow['uStatus'];

	}        

	// UPDATE
	public function updateData(){
		$sqlQuery = "UPDATE
					". $this->db_table ."
				SET
					uEmail = :uEmail,
					uPassword = :uPassword,
					uSurname = :uSurname,
					uLastname = :uLastname,
					uStatus = :uStatus
				WHERE 
					id = :id";
	
		$stmt = $this->connection->prepare($sqlQuery);
	
		$this->uEmail=htmlspecialchars(strip_tags($this->uEmail));
		$this->uPassword=htmlspecialchars(strip_tags($this->uPassword));
		$this->uSurname=htmlspecialchars(strip_tags($this->uSurname));
		$this->uLastname=htmlspecialchars(strip_tags($this->uLastname));
		$this->uStatus=htmlspecialchars(strip_tags($this->uStatus));
		$this->id=htmlspecialchars(strip_tags($this->id));

		$password_hash = md5($this->uPassword);
		$status = ($this->uStatus == true ? 1 : 0 );
		// bind data
		$stmt->bindParam(':uEmail', $this->uEmail);
		$stmt->bindParam(':uPassword', $password_hash);
		$stmt->bindParam(':uSurname', $this->uSurname);
		$stmt->bindParam(':uLastname', $this->uLastname);
		$stmt->bindParam(':uStatus', $status );
	
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