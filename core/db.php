<?php

ini_set("log_errors", TRUE);
ini_set('error_log_mode', '0664');

abstract class abs_db
{
	protected $host;
	
	protected $user;
	protected $pass;
	protected $port;
	protected $type;
	protected $db;
	protected $conn;
	protected $res;
	protected $log_file_name;
	protected $raw_sql;
	protected $driver;
	protected $log_error_file_name;
	protected $_logFileFlag = 1;
	protected $class_up;



	/**
	 * __construct
	 *
	 * @param  mixed $db_name
	 * @return void
	 */
	function __construct($db_name = '',$class_up='')
	{

		$dbConn = new config($db_name);
		$this->host = $dbConn->getHost();
		$this->user = $dbConn->getUser();
		$this->pass = $dbConn->getPass();
		$this->db = $dbConn->getDb();
		$this->port = $dbConn->getPort();
		$this->driver = $dbConn->getDriver();
		$this->log_file_name = "./logs/db_debug.log";
		$this->log_error_file_name = "./logs/db_error.log";
		$this->class_up = $class_up;


		// setting the logging file in php.ini
		// ini_set('error_log', $log_file);
	}

	/**
	 * error_message
	 *
	 * @param  mixed $mess
	 * @param  mixed $arr_debug
	 * @return void
	 */
	public function error_message($mess, $arr_debug = null)
	{

	/*	echo "<div class='error-box'><pre>";
		echo "<h2>" . $mess . "</h2>";
		echo "<p>";
		print_r($arr_debug);
		echo "</p>";
		echo "</pre></div>";*/
		

	echo '<div class="mostraEsito">
	<div class="alert alert-danger alert-white rounded">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<div class="icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
		<div></div>';
		echo '<strong>'.$mess.'</strong>';
		if(isset($arr_debug))
		{
		echo "<pre>";
		print_r($arr_debug);
		echo "</pre>";
		}
	echo '</div></div>';




	}



		/**
	 * error_message
	 *
	 * @param  mixed $mess
	 * @param  mixed $arr_debug
	 * @return void
	 */
	public function info_message($mess, $arr_debug = null)
	{

	/*	echo "<div class='error-box'><pre>";
		echo "<h2>" . $mess . "</h2>";
		echo "<p>";
		print_r($arr_debug);
		echo "</p>";
		echo "</pre></div>";*/
	echo '<div class="mostraEsito">
		<div id="infoBox" class="alert alert-success alert-white rounded">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<div class="icon"><i class="fa-solid fa-circle-info"></i></div>
		<strong> </strong>'.$mess,'</div></div>';




	}

	/**
	 * printSql
	 *
	 * @param  mixed $mess
	 * @return void
	 */
	public function printSql($mess = '')
	{

		echo "<div class='error-box'><pre>";
		echo "<h2>" . $mess . "</h2>";
		echo "<p>";
		echo $this->raw_sql;
		echo "</p>";
		echo "</pre></div>";
	}


	/**
	 * SqlDebug
	 *
	 * @param  mixed $raw_sql
	 * @param  mixed $params
	 * @return void
	 */
	public function SqlDebug($raw_sql, $params = array())
	{
		$keys = array();
		$values = $params;
		/*echo "<pre>";
			print_r($values);
			echo $raw_sql;
	        echo "</pre>";*/
		foreach ($params as $key => $value) {
			// check if named parameters (':param') or anonymous parameters ('?') are    used
			if (is_string($key)) {
				$keys[] = '/:' . $key . '/';
			} else {
				$keys[] = '/[?]/';
			}
			// bring parameter into human-readable format
			if (is_string($value)) {
				$values[$key] = "'" . $value . "'";
			} elseif (is_array($value)) {
				$values[$key] = implode(',', $value);
			} elseif (is_null($value)) {
				$values[$key] = 'NULL';
			}
		}
		$this->raw_sql = preg_replace($keys, $values, $raw_sql, 1, $count);
		return $this->raw_sql;
	}

	/**
	 * db_log_debug
	 *
	 * @param  mixed $error_message
	 * @param  mixed $type
	 * @param  mixed $arr_debug
	 * @return void
	 */
	public function db_log_debug($error_message, $type = 'Message', $arr_debug = null)
	{
		if ($this->_logFileFlag) {
			$mess = $this->deleteFileIfExceedsSize($this->log_file_name);
			if($mess)
				{
				//$this->info_message($mess);
				}
			error_log('[' . date("F j, Y, g:i:s a e O") . ']' . $type . ": " . $error_message, 3, $this->log_file_name);
			if (isset($arr_debug)) {
				error_log(json_encode($arr_debug) . "\n\r", 3, $this->log_file_name);
			}
			chmod($this->log_file_name, 0755);
		}
	}

	function deleteFileIfExceedsSize($filePath, $maxSizeMB = 200) {
    // Converti la dimensione massima da MB a byte
    $maxSize = $maxSizeMB * 1024 * 1024;

    // Controlla se il file esiste
    if (file_exists($filePath)) {
        // Ottieni la dimensione del file
        $fileSize = filesize($filePath);

        // Controlla se la dimensione del file supera la dimensione massima consentita
        if ($fileSize > $maxSize) {
            // Elimina il file
            if (unlink($filePath)) {
                return "Il file è stato eliminato perché supera i {$maxSizeMB} MB.";
            } else {
                return "Errore durante l'eliminazione del file.";
            }
        } else {
          //  return "Il file non supera i {$maxSizeMB} MB.";
        }
    } else {
        return "Il file ".$filePath." non esiste.";
    }
}


	/**
	 * db_log_error
	 *
	 * @param  mixed $error_message
	 * @param  mixed $type
	 * @param  mixed $arr_debug
	 * @return void
	 */
	public function db_log_error($error_message, $type = 'Message', $arr_debug = null)
	{

		if ($this->_logFileFlag) {
			$mess = $this->deleteFileIfExceedsSize($this->log_error_file_name);
			error_log('[' . date("F j, Y, g:i:s a e O") . ']' . $type . ": " . $error_message . "\n\r", 3, $this->log_error_file_name);
			if (isset($arr_debug)) {
				error_log('[' . date("F j, Y, g:i:s a e O") . ']' . json_encode($arr_debug) . "\n\r", 3, $this->log_error_file_name);
			}
			chmod($this->log_error_file_name, 0755);
		}
	}	
	/**
	 * getsqlQuery
	 *
	 * @return void
	 */
	public function getsqlQuery()
	{
		return $this->raw_sql;
	}

	/**
	 * @param mixed $raw_sql
	 */	
	/**
	 * setRaw_sql
	 *
	 * @param  mixed $raw_sql
	 * @return void
	 */
	public function setRaw_sql($raw_sql)
	{
		$this->raw_sql = $raw_sql;
	}
	// open mysql data base
	abstract function open_db();
	// close database
	abstract function close_db();

	// insert record
	abstract function insertDb($sql, $values, $type);

	//update record
	abstract function updateDb($sql, $values, $type);
	// delete record
	abstract function deleteDb($sql, $values, $type);
	// select record     
	abstract function selectDb($sql, $values = array(), $type);

	abstract function fetchAssocDb();

	abstract function getArrayByQuery($sql, $values = array(), $type = "");

	/**
	 * @return mixed
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * @return mixed
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @return mixed
	 */
	public function getPass()
	{
		return $this->pass;
	}

	/**
	 * @return mixed
	 */
	public function getDb()
	{
		return $this->db;
	}

	/**
	 * @return mixed
	 */
	public function getRes()
	{
		return $this->res;
	}

	/**
	 * @return string
	 */
	public function getLog_file_name()
	{
		return $this->log_file_name;
	}

	/**
	 * @return mixed
	 */
	public function getRaw_sql()
	{
		return $this->raw_sql;
	}

	/**
	 * @return mixed
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param mixed $host
	 */
	public function setHost($host)
	{
		$this->host = $host;
	}

	/**
	 * @param mixed $user
	 */
	public function setUser($user)
	{
		$this->user = $user;
	}

	/**
	 * @param mixed $pass
	 */
	public function setPass($pass)
	{
		$this->pass = $pass;
	}

	/**
	 * @param mixed $db
	 */
	public function setDb($db)
	{
		$this->db = $db;
	}

	/**
	 * @param mixed $res
	 */
	public function setRes($res)
	{
		$this->res = $res;
	}

	/**
	 * @param string $log_file_name
	 */
	public function setLog_file_name($log_file_name)
	{
		$this->log_file_name = $log_file_name;
	}


	public function getDriver()
	{
		return $this->driver;
	}

	/**
	 * @param Ambigous <mixed, string> $driver
	 */
	public function setDriver($driver)
	{
		$this->driver = $driver;
	}
}
