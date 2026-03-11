<?php
class helper
{
	protected $debug_attivo = 0;
	protected $error_message = '';
	protected $include_css;
	protected $_model;

	function __construct()
	{
		$log_file = "./logs/errors.log";
		ini_set("log_errors", TRUE);
		// setting the logging file in php.ini
		ini_set('error_log', $log_file);
	}

	/**
	 * is_admin
	 *
	 * @return void
	 */
	public function is_admin()
	{
		$gruppi = explode(",", $_SESSION['CodGroup']);
		return in_array("'2'", $gruppi);
	}

	/**
	 * pageRedirect
	 *
	 * @param  mixed $url
	 * @return void
	 */
	protected function pageRedirect($url)
	{
		header('Location:' . $url);
	}

	/**
	 * debug
	 *
	 * @param  mixed $mess
	 * @param  mixed $arr_debug
	 * @return void
	 */
	protected function debug($mess, $arr_debug = null)
	{
		if ($this->debug_attivo) {
			echo "<div class='debug_box'><pre>";
			echo "<h5>" . $mess . "</h5>";
			print_r($arr_debug);
			echo "</pre></div>";
		}
	}


	/**
	 * get_errors_message
	 *
	 * @param  mixed $type
	 * @return void
	 */
	protected function get_errors_message($type = "")
	{
		if ($this->error_message != "") {
			echo '<div class="mostraEsito"><div class="alert alert-danger alert-white rounded">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true" onclick="restSession();">×</button>
		<div class="icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
		<div></div>';
			echo '<strong>' . $this->error_message . '</strong>';
			echo '</div>';
			echo '</div>';
		}
	}




	protected function get_info_message($mess = "")
	{
		if ($mess != "") {
			echo '	<div class="mostraEsito"><div id="infoBox" class="alert alert-success alert-white rounded">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true" onclick="restSession();">×</button>
			<div class="icon"><i class="fa-solid fa-circle-info"></i></div>
			<strong>' . $mess . '</strong>';
			echo '</div>';
			echo '</div>';
		}
	}

	/**
	 * log_message
	 *
	 * @param  mixed $error_message
	 * @param  mixed $arr_debug
	 * @return void
	 */
	protected function log_message($error_message, $arr_debug = null)
	{
		//  $error_message = "This is an error message!";

		// path of the log file where errors need to be logged


		// logging the error
		error_log($error_message);
		if (isset($arr_debug)) {
			error_log(json_encode($arr_debug));
		}

		// logging error message to given log file

	}

	/**
	 * @return number
	 */
	protected function getDebug_attivo()
	{
		return $this->debug_attivo;
	}

	/**
	 * @param number $debug_attivo
	 */
	protected function setDebug_attivo($debug_attivo)
	{
		$this->debug_attivo = $debug_attivo;
	}
	/**
	 * createTMPFile
	 *
	 * @param  mixed $ShPath
	 * @param  mixed $ShName
	 * @return void
	 */
	protected function createTMPFile($ShPath, $ShName)
	{
		shell_exec('find ' . $_SESSION['PSITO'] . '/TMP/* -mtime +30 |xargs rm');
		$TestoFile = shell_exec("ssh " . $_SESSION['SSHUSR'] . "@" . $_SESSION['SERVER'] . " \"cat $ShPath/$ShName\" ");
		//echo preg_replace("/\r\n|\r|\n/",'<br/>',$TestoFile);
		$ShName = str_replace('.sh', '', $ShName);
		$Dt = date("YmdHis");
		shell_exec('rm -f ' . $_SESSION['PSITO'] . '/TMP/' . $ShName . '_*');
		$filename = $ShName . '_' . $Dt . '.sh.txt';
		file_put_contents($_SESSION['PSITO'] . '/TMP/' . $filename, $TestoFile);
		shell_exec('chmod 774 ' . $_SESSION['PSITO'] . '/TMP/' . $ShName . '_*');
		$ret['TestoFile'] = $TestoFile;
		$ret['filename'] = $filename;
		return $ret;
	}
	/**
	 * createTMPLog
	 *
	 * @param  mixed $Log
	 * @return void
	 */
	protected function createTMPLog($Log)
	{
		$TestoFile = shell_exec("ssh " . $_SESSION['SSHUSR'] . "@" . $_SESSION['SERVER'] . " \"more $Log\" ");
		$NameLog = shell_exec("basename $Log");
		shell_exec('find ' . $_SESSION['PSITO'] . '/TMP/* -mtime +30 |xargs rm');
		$NameLog = substr($NameLog, 0, -1);
		$NameLog2 = substr($NameLog, 0, -22);
		$Dt = date("YmdHis");
		shell_exec('rm -f ' . $_SESSION['PSITO'] . '/TMP/' . $NameLog2 . '*');
		$filename = $NameLog;
		file_put_contents($_SESSION['PSITO'] . '/TMP/' . $filename, $TestoFile);
		shell_exec('chmod 774 ' . $_SESSION['PSITO'] . '/TMP/' . $NameLog2 . '*');
		$ret['TestoFile'] = $TestoFile;
		$ret['filename'] = $filename;
		return $ret;
	}


	protected function createTMPLogElab($Log)
	{
		$TestoFile = shell_exec("ssh " . $_SESSION['SSHUSR'] . "@" . $_SESSION['SERVER'] . " \"more $Log\" ");
		$NameLog = shell_exec("basename $Log");
		shell_exec('find ' . $_SESSION['PSITO'] . '/TMP/* -mtime +30 |xargs rm');
		$NameLog = substr($NameLog, 0, -1);
		$NameLog2 = substr($NameLog, 0, -22);
		$Dt = date("YmdHis");
		shell_exec('rm -f ' . $_SESSION['PSITO'] . '/TMP/' . $NameLog2 . '*');
		$filename = $NameLog;
		file_put_contents($_SESSION['PSITO'] . '/TMP/' . $filename, $TestoFile);
		shell_exec('chmod 774 ' . $_SESSION['PSITO'] . '/TMP/' . $NameLog2 . '*');
		$ret['TestoFile'] = $TestoFile;
		$ret['filename'] = $filename;
		return $ret;
	}

	/**
	 * createDdlFile
	 *
	 * @param  mixed $Sel_PkgSchema
	 * @param  mixed $Sel_PkgName
	 * @param  mixed $TestoPkg
	 * @return void
	 */
	protected function createDdlFile($Sel_PkgSchema = '', $Sel_PkgName = '', $TestoPkg = '')
	{
		if ($Sel_PkgSchema != '' && $Sel_PkgName != '') {
			shell_exec('find ' . $_SESSION['PSITO'] . '/DDL/* -mtime +30 |xargs rm');
			$Dt = date("YmdHis");
			$filename = $Sel_PkgSchema . "." . $Sel_PkgName . ".sql";
			shell_exec('rm -f ' . $_SESSION['PSITO'] . '/DDL/' . $Sel_PkgSchema . "." . $Sel_PkgName . '*');
			$filename = $Sel_PkgSchema . "." . $Sel_PkgName . "_" . $Dt . ".sql";
			file_put_contents($_SESSION['PSITO'] . '/DDL/' . $filename, $TestoPkg);
			shell_exec('chmod 774 ' . $_SESSION['PSITO'] . '/DDL/' . $filename);
			$ret['TestoFile'] = $TestoPkg;
			$ret['filename'] = $filename;
		}

		return $ret;
	}

	/**
	 * createDdlPLSQLFile
	 *
	 * @param  mixed $PkgSchema
	 * @param  mixed $PkgName
	 * @param  mixed $TestoPkg
	 * @return void
	 */
	protected function createDdlPLSQLFile($PkgSchema = '', $PkgName = '', $TestoPkg = '')
	{
		if ($PkgSchema != '' && $PkgName != '') {
			shell_exec('find ' . $_SESSION['PSITO'] . '/DDL/* -mtime +30 |xargs rm');
			$Dt = date("YmdHis");
			$filename = $PkgSchema . "." . $PkgName . ".sql";
			shell_exec('rm -f ' . $_SESSION['PSITO'] . '/DDL/' . $PkgSchema . "." . $PkgName . '*');
			$filename = $PkgSchema . "." . $PkgName . "_" . $Dt . ".sql.txt";
			file_put_contents($_SESSION['PSITO'] . '/DDL/' . $filename, $TestoPkg);
			shell_exec('chmod 774 ' . $_SESSION['PSITO'] . '/DDL/' . $filename);
			$ret['TestoFile'] = $TestoPkg;
			$ret['filename'] = $filename;
		}

		return $ret;
	}



	/**
	 * createTMPSqlFile
	 *
	 * @param  mixed $ShowVar
	 * @param  mixed $Dati
	 * @return void
	 */
	protected function createTMPSqlFile($ShowVar, $Dati)
	{


		if ($Dati['SqlName'] != "") {
			$TestoFile = shell_exec("ssh " . $_SESSION['SSHUSR'] . "@" . $_SESSION['SERVER'] . " \"cat " . $Dati['SqlName'] . "\" ");
			shell_exec('find ' . $_SESSION['PSITO'] . '/TMP/* -mtime +30 |xargs rm');
			//  $TestoFile = shell_exec("ssh $SSHUSR@$SERVER \"cat $SqlName\" ");
			$FileSql = shell_exec("basename " . $Dati['SqlName']);
			$FileSql = substr($FileSql, 0, -5);

			$TestoFile = str_ireplace('SET WORK_CORE.', '--SET WORK_CORE.', $TestoFile);

			if ($ShowVar == "1") {

				$TestoFile = str_ireplace('%ID_PROCESS%', $Dati['ID_PROCESS'], $TestoFile);
				$TestoFile = str_ireplace('%ESER_ESAME_PREC%', $Dati['ESER_ESAME_PREC'], $TestoFile);
				$TestoFile = str_ireplace('%ESER_ESAME%', $Dati['ESER_ESAME'], $TestoFile);
				$TestoFile = str_ireplace('%MESE_ESAME%', $Dati['MESE_ESAME'], $TestoFile);
				$TestoFile = str_ireplace('%ESER_MESE_PREC%', $Dati['ESER_MESE_PREC'], $TestoFile);
				$TestoFile = str_ireplace('%ESER_MESE%', $Dati['ESER_MESE'], $TestoFile);
				$TestoFile = str_ireplace('%QRT_ESAME%', CEIL($Dati['MESE_ESAME'] / 3), $TestoFile);
				$TestoFile = str_ireplace('%ESER_QRT%', $Dati['ESER_ESAME'] . "0" . CEIL($Dati['MESE_ESAME'] / 3), $TestoFile);

				$TestoFile = str_ireplace('WORK_CORE.VAR_ID_PROCESS', $Dati['ID_PROCESS'], $TestoFile);
				$TestoFile = str_ireplace('WORK_CORE.VAR_ESER_ESAME_PREC', $Dati['ESER_ESAME_PREC'], $TestoFile);
				$TestoFile = str_ireplace('WORK_CORE.VAR_ESER_ESAME', $Dati['ESER_ESAME'], $TestoFile);
				$TestoFile = str_ireplace('WORK_CORE.VAR_MESE_ESAME', $Dati['MESE_ESAME'], $TestoFile);
				$TestoFile = str_ireplace('WORK_CORE.VAR_ESER_MESE_PREC', $Dati['ESER_MESE_PREC'], $TestoFile);
				$TestoFile = str_ireplace('WORK_CORE.VAR_ESER_MESE', $Dati['ESER_MESE'], $TestoFile);
				$TestoFile = str_ireplace('WORK_CORE.VAR_QRT_ESAME', CEIL($Dati['MESE_ESAME'] / 3), $TestoFile);
				$TestoFile = str_ireplace('WORK_CORE.VAR_ESER_QRT', $Dati['ESER_ESAME'] . "0" . CEIL($Dati['MESE_ESAME'] / 3), $TestoFile);
			}

			$Dt = date("YmdHis");
			shell_exec('rm -f ' . $_SESSION['PSITO'] . '/TMP/' . $FileSql . '*');
			$filename = $FileSql . '_' . $Dt . '.sql.txt';
			file_put_contents($_SESSION['PSITO'] . '/TMP/' . $filename, $TestoFile);
			shell_exec('chmod 774 ' . $_SESSION['PSITO'] . '/TMP/' . $FileSql . '_*');
		}
		$ret['TestoFile'] = $TestoFile;
		$ret['filename'] = $filename;
		$ret['sqlfilename'] = $FileSql . '.sql';;
		return $ret;
	}


	/**
	 * getTabellaByArray
	 *
	 * @param  mixed $dati
	 * @param  mixed $titolo
	 * @return void
	 */
	function getTabellaByArray($dati, $titolo='')
	{
		$tabella = "";
		if($titolo)
			{
			$tabella .= "<h2 style='text-align: center;'>".$titolo ."</h2>";
			}
		if (count($dati) > 0) {
			$tabella .= '<table id="idTabella" class="display dataTable">
						
					<thead class="headerStyle">
						<tr>
						  <th>' . implode('</th><th>', array_keys(current($dati))) . '</th>
						</tr>
					</thead>
					<tbody>';
			foreach ($dati as $row) {
				array_map('htmlentities', $row);
				$tabella .= '<tr>
			  <td>' . implode('</td><td>', $row) . '</td>
			</tr>';
			}
			$tabella .= '  </tbody>
			</table>';
		} else {
			$tabella .= "<h4>Nessun dato in tabella</h4>";
		}
		return $tabella;
	}
}
