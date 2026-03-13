<?php
include_once 'db.php';
//require_once 'connectionMysql.php';

class db_driver extends abs_db
{

	// open mysql data base
	public function open_db($mess='')
	{
		try {
			if (!$this->conn) {
				$conn_string = "DRIVER={IBM DB2 ODBC DRIVER};DATABASE=$this->db;" .
					"HOSTNAME=$this->host;PORT=$this->port;PROTOCOL=TCPIP;UID=$this->user;PWD=$this->pass;";
			//		$this->db_log_error("db_db2.open_db: ".$this->class_up ."->".$mess);
				$this->conn = db2_connect($conn_string, '', '');
				if (!$this->conn) {
					throw new Exception("Connesione db Fallita!");
					//die("Connesione db Fallita!");
				}
				return $this->conn;
			}
		} catch (Throwable $e) {
			$this->error_message("DB2", "DB2.open_db Error accaduto: " . db2_stmt_errormsg());
			//$this->error_message("db_db2.open_db Impossibile connettersi a db", "db_db2.open_db");
			$this->db_log_error($e->getMessage(), "db_db2.open_db");
			throw $e;
		} catch (Exception $e) {
			$this->close_db();
			throw $e;
		}
	}
	// close database
	public function close_db()
	{
		db2_close($this->conn);
	}

	// insert record
	public function insertDb($sql, $values, $type = "")
	{
		try {
			$this->open_db("insertDb");
			$this->SqlDebug($sql, $values);
			$stmt = db2_prepare($this->conn, $sql);
			$result = db2_execute($stmt, $values);
			db2_free_stmt($stmt);

			if (!$result) {
				$ret = 0;
			} else {
				//$last_id =1;
				$ret = db2_last_insert_id($this->conn);
				$ret=($ret)?$ret:1;
			}
			return $ret;
		} catch (Throwable $e) {
			$this->error_message("DB2", "DB2.insertDb Error accaduto: " . db2_stmt_errormsg());
			$this->db_log_error("DB2.insertDb Error accaduto: " . $this->getsqlQuery(), "DB2.insertDb");
			$this->db_log_error($e->getMessage(), "DB2.insertDb");
			throw $e;
		} catch (Exception $e) {
			$this->close_db();
			throw $e;
		}
	}
	//update record
	public function updateDb($sql, $values, $type = "")
	{
		try {
			$this->open_db("updateDb");
			$this->SqlDebug($sql, $values);
			$stmt = db2_prepare($this->conn, $sql);
			$result = db2_execute($stmt, $values);
			db2_free_stmt($stmt);
			if (!$result) {
				$ret = 0;
			} else {
				$ret = 1;
			}
			if (!$ret) {
				throw new Exception("Update db Fallita!");
			}
			//$this->close_db();

			return $ret;
		} catch (Throwable $e) {
			$this->error_message("DB2", "DB2.callDb Error accaduto: " . db2_stmt_errormsg());
			$this->db_log_error("DB2.updateDb Error accaduto: " . $this->getsqlQuery(), "DB2.updateDb");
			$this->db_log_error($e->getTraceAsString(), "DB2.updateDb");
			throw $e;
		} catch (Exception $e) {
			$this->error_message("DB2", "DB2.updateDb Error accaduto: " . db2_stmt_errormsg());
			$this->db_log_error("DB2.updateDb Error accaduto: " . $this->getsqlQuery(), "DB2.updateDb");
			$this->db_log_error($e->getTraceAsString(), "DB2.updateDb");
			$this->close_db();
			throw $e;
		}
	} //update record
	public function callDb($sql, $values)
	{
	/*	try {*/

		//	$backtrace = debug_backtrace();
			//$this->db_log_debug("function:","Message",$backtrace[1]['function']);
			//$this->db_log_debug("class:","Message:",$backtrace[1]['class']);

			$this->open_db("callDb");
		//	$this->SqlDebug($sql, $values);
			
			$stmt = db2_prepare($this->conn, $sql);
			foreach ($values as $k => $val) {
				${$val['name']} = $val['value'];
				db2_bind_param($stmt, $k + 1, $val['name'], $val['type']);
			}
		//	echo $sql."simone1";
			$result = db2_execute($stmt);
				//	db2_free_stmt($stmt);	
			
			//$this->close_db();
			$retVal = [];
			foreach ($values as $k => $v) {
				if ($values[$k]['type'] == DB2_PARAM_OUT) {
					$retVal[$values[$k]['name']] = ${$values[$k]['name']};
				}
			}
		//	$retVal['ret'] = $ret;

			if (!$result) {
				$ret = 0;
			} else {
				$ret = 1;
			}
			if (!$ret) {
				$retVal['Note'] = "DB2.callDb Error accaduto: " . db2_stmt_errormsg();
				$retVal['Errore'] = 100;
				$this->error_message("DB2", "DB2.callDb Error accaduto: " . db2_stmt_errormsg());
			}

			if($retVal['Errore'])
				{
				 $this->error_message($retVal['Note']);
				}
			//$this->db_log_debug("DB2.callDb getsqlQuery", $this->getsqlQuery());
			//	print_r($retVal);	
			//$this->db_log_debug("getsqlQuery:","Message:",$this->getsqlQuery());
			return $retVal;
	/*	} catch (Throwable $e) {
			//print_r($values);			
			$this->error_message("DB2.callDb", "DB2.callDb Error accaduto: " . $this->getsqlQuery());
			$this->error_message("DB2.callDb", "DB2.callDb Error accaduto: " . db2_stmt_errormsg());
			$this->db_log_error("DB2.callDb Error accaduto: " . $this->getsqlQuery(), "DB2.callDb");
			$this->db_log_error($e->getTraceAsString(), "DB2.callDb");
		//	throw $e;
		} catch (Exception $e) {
			$this->error_message("DB2.callDb", "DB2.callDb Error accaduto: " . $this->getsqlQuery());
			$this->error_message("DB2.callDb", "DB2.callDb Error accaduto: " . db2_stmt_errormsg());
			$this->db_log_error("DB2.callDb Error accaduto: " . $this->getsqlQuery(), "DB2.callDb");
			$this->db_log_error($e->getTraceAsString(), "DB2.callDb");
			//$this->close_db();
			//throw $e;
		}*/
	}
	// delete record
	public function deleteDb($sql, $values, $type = "")
	{
		try {
			$this->open_db("deleteDb");
			$this->SqlDebug($sql, $values);
		//	$backtrace = debug_backtrace();
		//	$this->db_log_debug("function:","Message",$backtrace[1]['function']);
		//	$this->db_log_debug("class:","Message:",$backtrace[1]['class']);
			$stmt = db2_prepare($this->conn, $sql);
			$result = db2_execute($stmt, $values);
			db2_free_stmt($stmt);
			if (!$result) {
				$ret = 0;
			} else {
				$ret = 1;
			}
			//$this->close_db();
			return $ret;
		} catch (Throwable $e) {
			//$this->error_message("DB2.deleteDb", "DB2.deleteDb Error accaduto: " . $this->getsqlQuery());
			$this->error_message("DB2.deleteDb", "Error accaduto: " . db2_stmt_errormsg());
			$this->db_log_error("DB2.deleteDb Error accaduto: " . $this->getsqlQuery(), "DB2.deleteDb");
			$this->db_log_error($e->getTraceAsString(), "DB2.deleteDb");
			throw $e;
		} catch (Exception $e) {
			$this->close_db();
			throw $e;
		}
	}
	// select record     
	public function selectDb($sql, $values = array(), $type = "")
	{
		try {
			$this->open_db("selectDb");
			$this->SqlDebug($sql, $values);
			$sql2 = "select 1 from dual";
			$sql2 = $this->getsqlQuery();
			/*  $this->res  = db2_prepare($this->conn, $sql);
				$ret = db2_execute ($this->res );*/

			if ($this->conn) {
				$ret = array();
				$this->res  = db2_prepare($this->conn, $sql);
				$result = db2_execute($this->res, $values);
				db2_free_stmt($this->res);
				return $result;
			}
		} catch (Throwable $e) {
			$this->error_message("DB2", "DB2.selectDb Error accaduto: " . db2_stmt_errormsg());
			//$this->error_message("DB2.selectDb", "DB2.selectDb Error accaduto: " . $this->getsqlQuery());
			$this->db_log_error("DB2.selectDb Error accaduto: " . $this->getsqlQuery(), "DB2.selectDb");
			$this->db_log_error($e->getTraceAsString(), "DB2.selectDb");
			throw $e;
		} catch (Exception $e) {
			$this->close_db();
			throw $e;
		}
	}

	public function fetchAssocDb()
	{
		return db2_fetch_assoc($this->res);
	}

	function getArrayByQuery($sql, $values = array(), $type = "")
	{
		try {		
		//	$backtrace = debug_backtrace();
		//	$this->db_log_debug("function:","Message",$backtrace[1]['function']);
		//	$this->db_log_debug("class:","Message:",$backtrace[1]['class']);				
			
			$this->open_db("getArrayByQuery");
		//	$this->db_log_debug("class:","open_db:",$backtrace[1]['class']);	
			$this->SqlDebug($sql, $values);
				
				
			if ($this->conn) {
				$ret = array();

				$this->res  = db2_prepare($this->conn, $sql);
				$result = db2_execute($this->res, $values);
				$ret = array();
				if ($result) {
					while ($row = db2_fetch_assoc($this->res)) {
						$ret[] = $row;
					}
				}
				db2_free_stmt($this->res);
			//	$this->db_log_debug("DB2.getArrayByQuery getsqlQuery:","Message:",$this->getsqlQuery());
				//$this->close_db();
				return $ret;
			}
		} catch (Throwable $e) {
			$this->error_message("DB2", "DB2.getArrayByQuery Error accaduto: " . db2_stmt_errormsg());
			$this->db_log_error("DB2.getArrayByQuery Error accaduto: " . $this->getsqlQuery(), "DB2.getArrayByQuery");
			$this->db_log_error($e->getTraceAsString(), "DB2.getArrayByQuery");
			throw $e;
		} catch (Exception $e) {
			$this->close_db();
			throw $e;
		}
	}


	function getArrayNoAssocByQuery($sql, $values = array(), $type = "")
	{
		try {				
			$this->open_db("getArrayNoAssocByQuery");
			$this->SqlDebug($sql, $values);
			if ($this->conn) {
				$ret = array();

				$this->res  = db2_prepare($this->conn, $sql);
				$result = db2_execute($this->res, $values);
				$ret = array();
				if ($result) {
					while ($row = db2_fetch_array($this->res)) {
						$ret[] = $row;
					}
				}
				db2_free_stmt($this->res);
				//$this->close_db();
				return $ret;
			}
		} catch (Throwable $e) {
			$this->error_message("DB2", "DB2.getArrayNoAssocByQuery Error accaduto: " . db2_stmt_errormsg());
			$this->db_log_error("DB2.getArrayNoAssocByQuery Error accaduto: " . $this->getsqlQuery(), "DB2.getArrayByQuery");
			$this->db_log_error($e->getTraceAsString(), "DB2.getArrayNoAssocByQuery");
			throw $e;
		} catch (Exception $e) {
			$this->close_db();
			throw $e;
		}
	}





}
