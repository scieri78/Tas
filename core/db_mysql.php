<?php
include_once 'db.php';
//require_once 'connectionMysql.php';

class db_driver extends abs_db
{

	protected $condb;
	// open mysql data base
	public function open_db()
	{
		if (!$this->condb) {
			$this->condb = new mysqli($this->host, $this->user, $this->pass, $this->db);
			$this->db_log_error("connessione: ".date('Y-m-d H:i:s'));
			if ($this->condb->connect_error) {
				die("Erron in connection: " . $this->condb->connect_error);
			}
		}
		return $this->condb;
	}
	// close database
	public function close_db()
	{
		$this->condb->close();
	}

	// insert record
	public function insertDb($sql, $values, $type)
	{
		try {
			//  print_r($values);
			// die();

			$this->open_db();
			$query = $this->condb->prepare($sql);
			$query->bind_param($type, ...$values);
			//echo $this->SqlDebug($sql,$values);
			//die();
			//$query->bind_param("ss",...array("dda","bdd"));
			$query->execute();
			//$res= $query->get_result();
			$last_id = $this->condb->insert_id;
			$query->close();
			$this->close_db();
			return $last_id;
		} catch (Throwable $e) {
			//echo "Captured Throwable: " . $e->getTraceAsString() . PHP_EOL;
			$this->error_message("insertDb;", "selectDb Error accaduto: " . $this->getsqlQuery());
			$this->db_log_error($e->getTraceAsString());
			throw $e;
		} catch (Exception $e) {
			$this->close_db();
			throw $e;
		}
	}
	//update record
	public function updateDb($sql, $values, $type)
	{
		try {
			$this->SqlDebug($sql, $values);
			$this->open_db();
			$query = $this->condb->prepare($sql);
			$query->bind_param($type, ...$values);


			//$query->bind_param("ssi", $obj->category,$obj->name,$obj->id);
			$query->execute();
			//$res=$query->get_result();						
			$query->close();
			$this->close_db();
			return true;
		} catch (Throwable $e) {
			// echo "Captured Throwable: " . $e->getTraceAsString() . PHP_EOL;
			$this->error_message("Error: db_mysql.updateDb: ", "selectDb Error accaduto: " . $this->getsqlQuery());
			$this->db_log_error($e->getTraceAsString());
			$this->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->close_db();
			throw $e;
		}
	}
	// delete record
	public function deleteDb($sql, $values, $type)
	{
		try {
			$this->open_db();
			$query = $this->condb->prepare($sql);
			$this->SqlDebug($sql, $values);
			$query->bind_param($type, ...$values);
			//$query->bind_param("ssi", $obj->category,$obj->name,$obj->id);
			$query->execute();
			//$res=$query->get_result();
			$query->close();
			$this->close_db();
			return true;
		} catch (Throwable $e) {
			//echo "Captured Throwable: " . $e->getTraceAsString() . PHP_EOL;
			$this->error_message("Error: db_mysql.deleteDb: ", "selectDb Error accaduto: " . $this->getsqlQuery());
			$this->db_log_error($e->getTraceAsString());
			$this->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->close_db();
			throw $e;
		}
	}
	// select record     
	public function selectDb($sql, $values = array(), $type = '')
	{
		try {
			$this->open_db();
			$query = $this->condb->prepare($sql);
			$this->SqlDebug($sql, $values);
			if ($type != '' && !empty($values)) {
				$query->bind_param($type, ...$values);
			}

			$query->execute();


			$this->res = $query->get_result();
			$query->close();
			$this->close_db();
			return $this->res;
		} catch (Throwable $e) {
			//echo "Captured Throwable: " . $e->getTraceAsString() . PHP_EOL;
			$this->error_message("Error: db_mysql.selectDb;", $this->getsqlQuery());
			$this->db_log_error($e->getTraceAsString());
			$this->close_db();
			throw $e;
		} catch (Exception $e) {
			echo $e->getMessage();
			$this->close_db();
			die();
			throw $e;
		} finally {
		}
	}

	public function fetchAssocDb()
	{
		try {
			return $this->res->fetch_assoc();
		} catch (Throwable $e) {
			//echo "Captured Throwable: " . $e->getTraceAsString() . PHP_EOL;
			$this->error_message("Error: db_mysql.fetchAssocDb;", $this->getsqlQuery());
			$this->db_log_error($e->getTraceAsString());
			$this->close_db();
			throw $e;
		} catch (Exception $e) {
			echo $e->getMessage();
			$this->close_db();
			die();
			throw $e;
		} finally {
		}
	}

	function getArrayByQuery($sql, $values = array(), $type = "")
	{
		try {
			$res = $this->selectDb($sql, $values, $type);
			$array = array();
			while ($row = $res->fetch_assoc()) {
				$array[] = $row;
			}
			return $array;
		} catch (Throwable $e) {
			//echo "Captured Throwable: " . $e->getTraceAsString() . PHP_EOL;
			$this->error_message("Error: db_mysql.getArrayByQuery;", $this->getsqlQuery());
			$this->db_log_error($e->getTraceAsString());
			$this->close_db();
			throw $e;
		} catch (Exception $e) {
			echo $e->getMessage();
			$this->close_db();
			die();
			throw $e;
		} finally {
		}
	}
}
