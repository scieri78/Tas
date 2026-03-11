<?php
require_once './library/xlsxtocsv/SimpleXLSX.php';
require_once './library/simplexlsxgen/SimpleXLSXGen.php';

use SimpleXLSX;
use Shuchkin\SimpleXLSXGen;

class searchcoll_model
{
	// set database config for mysql
	// open mysql data base
	private $_db;

	public function __construct()
	{
		$this->_db = new db_driver();
	}



	public function getTable($COLNAME = '')
	{
		try {
			if ($COLNAME != "") {
				$sql = "select DISTINCT TABSCHEMA, TABNAME, COLNAME FROM syscat.columns WHERE UPPER(COLNAME) LIKE '" . strtoupper($COLNAME) . "' ESCAPE '\' ORDER BY TABSCHEMA, TABNAME, COLNAME";

				$res = $this->_db->getArrayByQuery($sql);
				//$this->_db->printSql();
			}
			return $res;
		} catch (Exception $e) {
			throw $e;
		}
	}

	//funzione crea file by dati
	public function creaFile($COLNAME)
	{
		$datiTable = $this->getTable($COLNAME);	
		$xlsx = new SimpleXLSXGen();
		$listaCampi = array_keys($datiTable[0]);
		$arrayTable = [];
		foreach ($datiTable as $valore) {
			$arrayTable[] = array_values($valore); // Assumendo che $valore sia un array associativo
		}

		$controlli = array_merge([$listaCampi], $arrayTable);
	/*	$this->_db->error_message("controlli", $controlli);
		$this->_db->error_message("listaCampi", $listaCampi);
		$this->_db->error_message("contrarrayTableolli", $arrayTable);*/
		$xlsx->addSheet($controlli, $COLNAME);
		//$this->_db->error_message($SOTTO_GRUPPO, $controlli);
		//$xlsx = Shuchkin\SimpleXLSXGen::fromArray($controlli,$SOTTO_GRUPPO);

		$NEW_NAME_FILE = "./TMP/".$COLNAME . "_" . date("YmdHis") . ".xlsx";
		$xlsx->saveAs($NEW_NAME_FILE);
		shell_exec('chmod 774 ' . $NEW_NAME_FILE);	

		return $COLNAME . "_" . date("YmdHis");
	} 
}
