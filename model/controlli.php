<?php
require_once './library/xlsxtocsv/SimpleXLSX.php';
require_once './library/simplexlsxgen/SimpleXLSXGen.php';

use SimpleXLSX;
use Shuchkin\SimpleXLSXGen;

class controlli_model
{
	// set datacontrolli config for mysql
	// open mysql data importcontroll
	// dafare CONTROLLI_ANAG => CONTROLLI_ANAG
	// dafare CONTROLLI_LANCI =>  CONTROLLI_LANCI
	private $_db;

	private $_tableControlliAnag = 'WORK_RULES.CONTROLLI_ANAG';
	private $_tableControlliGruppi = 'WORK_RULES.CONTROLLI_GRUPPI';
	private $_tableControlliLanci = 'WORK_RULES.CONTROLLI_LANCI';
	private $_tableControlliLanciAnag = 'WORK_RULES.CONTROLLI_LANCI_ANAG';
	private $_tableControlliTipo = 'WORK_RULES.CONTROLLI_TIPO';
	private $_tableInputFile = 'WORK_RULES.INPUT_FILES';
	private $_tableControlliInport = 'WORK_RULES.CONTROLLI_INPORT';
	private $_tableControlliExport = 'WORK_RULES.CONTROLLI_EXPORT';

	/**
	 * {@inheritDoc}
	 * @see db_driver::__construct()
	 */
	public function __construct()
	{
		$this->_db = new db_driver();
	}


	/**
	 * getControlliAnag
	 *
	 * @param  mixed $idGruppo
	 * @return  mixed $datiControlli
	 */
	public function getControlliAnag($ID_GRUPPO, $SOTTO_GRUPPO = '', $CONTROLLO = '')
	{
		try {
			$sql = "select * from " . $this->_tableControlliAnag . " WHERE ID_GRUPPO=? and VALIDO=1 ";
			if ($SOTTO_GRUPPO) {
				$sql .= "and SOTTO_GRUPPO='$SOTTO_GRUPPO' ";
			}
			if ($CONTROLLO) {
				$sql .= "and CONTROLLO='$CONTROLLO' ";
			}


			$datiControlli = $this->_db->getArrayByQuery($sql, [$ID_GRUPPO]);
			return $datiControlli;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}


	public function getFILE($GRUPPO, $SOTTO_GRUPPO)
	{
		try {
			$NOTE = $GRUPPO . " " . $SOTTO_GRUPPO;
			$sql = "SELECT INPUT_FILE , max(ID_RUN_SQL) ID_FILE, max(LOAD_DATE) LOAD_DATE  
						FROM " . $this->_tableInputFile . "
						WHERE NOTE like '%".$NOTE."%' 
						group by INPUT_FILE 
						ORDER BY INPUT_FILE desc;";
			$res = $this->_db->getArrayByQuery($sql, []);
			return $res;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}





	/**
	 * getControlliAnag
	 *
	 * @return void
	 */
	public function getControlliAnagLancio($ID_LANCIO = '', $SOTTO_GRUPPO = '', $ID_CONTR = '', $ID_TIPO = "", $ESITO = "", $CLASSE = "")
	{

		//select * from $this->_tableControlliAnag CA left join $this->_tableControlliLanci CL on CL.ID_CONTR = CA.ID_CONTR where 1=1 order by CA.ID_CONTR
		try {
			if ($ID_LANCIO) {
				$sql = "select CL.UTENTE as LANCIO_UTENTE, CA.DESCR as DESCR_ANAG, CA.ID_TIPO as ID_TIPO_ANAG, CA.SOTTO_GRUPPO as SOTTO_GRUPPO_ANAG,  CA.CLASSE as CLASSE_ANAG, CA.ID_CONTR as ID, * from " . $this->_tableControlliAnag . " CA 
						left join " . $this->_tableControlliLanci . " CL on CL.ID_CONTR = CA.ID_CONTR
						left join " . $this->_tableControlliTipo . " CT on CT.ID_TIPO = CA.ID_TIPO
						left join " . $this->_tableControlliLanciAnag . " CLA on CLA.ID_LANCIO = CL.ID_LANCIO
						 where  CL.ID_LANCIO='$ID_LANCIO' ";


				if (is_array($SOTTO_GRUPPO)) {
					$SOTTO_GRUPPO_IN = $this->arrayToSqlInString($SOTTO_GRUPPO);
					$sql .= "and CA.SOTTO_GRUPPO in ($SOTTO_GRUPPO_IN) ";
				}

				if (is_array($ID_TIPO)) {
					$ID_TIPO_IN = $this->arrayToSqlInString($ID_TIPO);
					$sql .= "and CA.ID_TIPO in ($ID_TIPO_IN) ";
				}
				if (is_array($CLASSE)) {
					$CLASSE_IN = $this->arrayToSqlInString($CLASSE);
					$sql .= "and CA.CLASSE in ($CLASSE_IN) ";
				}
				if (is_array($ESITO)) {
					$ESITO_IN = $this->arrayToSqlInString($ESITO);
					$sql .= "and CL.ESITO in ($ESITO_IN) ";
				}
				if ($ID_CONTR) {					
					$sql .= "and CA.ID_CONTR = $ID_CONTR ";
				}





				$sql .= "order by CA.ID_CONTR";


				$res = $this->_db->getArrayByQuery($sql);
				foreach ($res as $k => $v) {
					$res[$k]['DATI_LANCIO'] = [];
					$res[$k]['DATI_LANCIO'] = $this->getDatiLancio($v['ID_LANCIO']);
				}
				//$this->_db->close_db();
			}
			//echo $sql2;
			return $res;
		} catch (Exception $e) {

			throw $e;
		}
	}	
	/**
	 * getControlliLanci
	 *
	 * @param  mixed $ID_CONTR
	 * @return void
	 */
	public function getControlliLanci($ID_CONTR = '')
	{

		//select * from $this->_tableControlliAnag CA left join $this->_tableControlliLanci CL on CL.ID_CONTR = CA.ID_CONTR where 1=1 order by CA.ID_CONTR
		try {
			if ($ID_CONTR) {
				$sql = "select * from " . $this->_tableControlliAnag . " CA 
						LEFT JOIN " . $this->_tableControlliLanci . " CL on CL.ID_CONTR = CA.ID_CONTR
						LEFT JOIN " . $this->_tableControlliTipo . " CT on CT.ID_TIPO = CA.ID_TIPO
						 where  CA.ID_CONTR=? order by CL.ID_LANCIO DESC, CA.ID_CONTR";

				$res = $this->_db->getArrayByQuery($sql, [$ID_CONTR]);
				foreach ($res as $k => $v) {
					$res[$k]['DATI_LANCIO'] = [];
					$res[$k]['DATI_LANCIO'] = $this->getDatiLancio($v['ID_LANCIO']);
				}
			}

			return $res;
		} catch (Exception $e) {

			throw $e;
		}
	}
	/**
	 * getDatiLancio
	 *
	 * @param  mixed $ID_LANCIO
	 * @return void
	 */
	public function getDatiLancio($ID_LANCIO)
	{
		$DATI_LANCIO = [];
		if ($ID_LANCIO) {
			$LANCI = $this->getLancio($ID_LANCIO);

			$DATI_LANCIO = $LANCI[0];
			$FILTRI = '';
			/*SOTTO_GRUPPO
			ID_CONTR
			CLASSE
			ID_TIPO
			FORZATURA*/
			$TIPO = "";
			if ($LANCI[0]['ID_TIPO']) {
				$TIPI = $this->getTipo($LANCI[0]['ID_TIPO']);
				$TIPO = $TIPI[0]['TIPO'];
			}
			$FILTRI .= $LANCI[0]['SOTTO_GRUPPO'] ? 'SOTTO_GRUPPO:' . $LANCI[0]['SOTTO_GRUPPO'] . "<br>" : '';
			$FILTRI .= $LANCI[0]['CLASSE'] ? 'CLASSE:' . controlli_dati::$array_classe[$LANCI[0]['CLASSE']] . "<br>" : '';
			$FILTRI .= $TIPO ? 'TIPO:' . $TIPO . "<br>" : '';
			$FILTRI .= $LANCI[0]['FORZATURA'] ? 'FORZATURA:' . $LANCI[0]['FORZATURA'] . "<br>" : '';
			$DATI_LANCIO['FILTRI'] =  $FILTRI;
		}
		return $DATI_LANCIO;
	}

	/**
	 * getControlliAnagByIdContr
	 *
	 * @param  mixed $ID_CONTR
	 * @return void
	 */
	public function getControlliAnagByIdContr($ID_CONTR = '')
	{
		try {
			if ($ID_CONTR) {
				$sql = "select * from " . $this->_tableControlliAnag . " CA 
						LEFT JOIN " . $this->_tableControlliTipo . " CT on CT.ID_TIPO = CA.ID_TIPO
						where CA.ID_CONTR=? ";

				$res = $this->_db->getArrayByQuery($sql, [$ID_CONTR]);
			}
			//$this->_db->close_db();

			//echo $sql2;
			return $res;
		} catch (Exception $e) {

			throw $e;
		}
	}
	public function arrayToSqlInString($array_in)
	{
		$names = implode('\', \'', $array_in);
		$ret = "'" . $names . "'";
		//$this->_db->error_message('arrayToSqlInString',$ret);
		return $ret;
	}	
	/**
	 * getControlliByIdGruppo
	 *
	 * @param  mixed $ID_GRUPPO
	 * @param  mixed $ID_INPORT
	 * @param  mixed $SOTTO_GRUPPO
	 * @param  mixed $ID_TIPO
	 * @param  mixed $ESITO
	 * @param  mixed $CLASSE
	 * @param  mixed $ID_FILE
	 * @return void
	 */
	public function getControlliByIdGruppo($ID_GRUPPO = '', $ID_INPORT = '', $SOTTO_GRUPPO = '', $ID_TIPO = '', $ESITO = '', $CLASSE = '', $ID_FILE = '')
	{

		//select * from $this->_tableControlliAnag CA left join $this->_tableControlliLanci CL on CL.ID_CONTR = CA.ID_CONTR where 1=1 order by CA.ID_CONTR
		try {
			if ($ID_GRUPPO) {
				$sql = "SELECT CL.UTENTE as LANCIO_UTENTE, CA.DESCR as DESCR_ANAG,  CA.ID_TIPO as ID_TIPO_ANAG, CA.SOTTO_GRUPPO as SOTTO_GRUPPO_ANAG,  CA.CLASSE as CLASSE_ANAG, CA.ID_CONTR as ID, * FROM " . $this->_tableControlliAnag . " CA 
				LEFT JOIN " . $this->_tableControlliLanci . " CL ON CL.ID_CONTR = CA.ID_CONTR  
				LEFT JOIN " . $this->_tableControlliLanciAnag . " CLA ON CLA.ID_LANCIO = CL.ID_LANCIO  
				LEFT JOIN " . $this->_tableControlliTipo . " CT on CT.ID_TIPO = CA.ID_TIPO
				WHERE CA.ID_GRUPPO= '$ID_GRUPPO' 
				AND (CL.ID_LANCIO = (SELECT MAX(CL2.ID_LANCIO) FROM $this->_tableControlliLanci CL2 WHERE CL2.ID_CONTR = CL.ID_CONTR) OR CL.ID_CONTR is NULL) ";

				if ($ID_INPORT) {
					$sql .= "and CA.ID_INPORT='$ID_INPORT' ";
				}
				//$this->_db->error_message('SOTTO_GRUPPO',$SOTTO_GRUPPO);
				if (is_array($SOTTO_GRUPPO)) {
					$SOTTO_GRUPPO_IN = $this->arrayToSqlInString($SOTTO_GRUPPO);
					$sql .= "and CA.SOTTO_GRUPPO in ($SOTTO_GRUPPO_IN) ";
				}

				if (is_array($ID_TIPO)) {
					$ID_TIPO_IN = $this->arrayToSqlInString($ID_TIPO);
					$sql .= "and CA.ID_TIPO in ($ID_TIPO_IN) ";
				}
				if (is_array($CLASSE)) {
					$CLASSE_IN = $this->arrayToSqlInString($CLASSE);
					$sql .= "and CA.CLASSE in ($CLASSE_IN) ";
				}
				if (is_array($ESITO)) {
					$ESITO_IN = $this->arrayToSqlInString($ESITO);
					$sql .= "and CL.ESITO in ($ESITO_IN) ";
				}
				if (is_array($ID_FILE)) {
					$ID_FILE_IN = $this->arrayToSqlInString($ID_FILE);
					$sql .= "and CLA.ID_FILE in ($ID_FILE_IN) ";
				}

				$sql .= " ORDER BY CA.SOTTO_GRUPPO, CA.ID_CONTR";
				$res = $this->_db->getArrayByQuery($sql);
                //$this->_db->printSql();
				foreach ($res as $k => $v) {
					$res[$k]['DATI_LANCIO'] = [];
					$res[$k]['DATI_LANCIO'] = $this->getDatiLancio($v['ID_LANCIO']);
				}

				//$this->_db->close_db();
			}
			//echo $sql2;
			return $res;
		} catch (Exception $e) {

			throw $e;
		}
	}


	public function getControlliByIdGruppoLight($ID_GRUPPO = '', $ID_INPORT = '', $SOTTO_GRUPPO = '', $ID_TIPO = '', $ESITO = '', $CLASSE = '', $ID_FILE = '')
	{

		//select * from $this->_tableControlliAnag CA left join $this->_tableControlliLanci CL on CL.ID_CONTR = CA.ID_CONTR where 1=1 order by CA.ID_CONTR
		try {
			if ($ID_GRUPPO) {
				$sql = "SELECT CA.DESCR as DESCR_ANAG,  CA.ID_TIPO as ID_TIPO_ANAG, CA.SOTTO_GRUPPO as SOTTO_GRUPPO_ANAG,  CA.CLASSE as CLASSE_ANAG, CA.ID_CONTR as ID, * 
				FROM " . $this->_tableControlliAnag . " CA 			  
				LEFT JOIN " . $this->_tableControlliTipo . " CT on CT.ID_TIPO = CA.ID_TIPO
				WHERE CA.ID_GRUPPO= '$ID_GRUPPO' 
			 ";

				if ($ID_INPORT) {
					$sql .= "and CA.ID_INPORT='$ID_INPORT' ";
				}
				//$this->_db->error_message('SOTTO_GRUPPO',$SOTTO_GRUPPO);
				if (is_array($SOTTO_GRUPPO)) {
					$SOTTO_GRUPPO_IN = $this->arrayToSqlInString($SOTTO_GRUPPO);
					$sql .= "and CA.SOTTO_GRUPPO in ($SOTTO_GRUPPO_IN) ";
				}

				if (is_array($ID_TIPO)) {
					$ID_TIPO_IN = $this->arrayToSqlInString($ID_TIPO);
					$sql .= "and CA.ID_TIPO in ($ID_TIPO_IN) ";
				}
				if (is_array($CLASSE)) {
					$CLASSE_IN = $this->arrayToSqlInString($CLASSE);
					$sql .= "and CA.CLASSE in ($CLASSE_IN) ";
				}
				/*if (is_array($ESITO)) {
					$ESITO_IN = $this->arrayToSqlInString($ESITO);
					$sql .= "and CL.ESITO in ($ESITO_IN) ";
				}
				if (is_array($ID_FILE)) {
					$ID_FILE_IN = $this->arrayToSqlInString($ID_FILE);
					$sql .= "and CLA.ID_FILE in ($ID_FILE_IN) ";
				}*/

				$sql .= " ORDER BY CA.SOTTO_GRUPPO, CA.ID_CONTR";
				$res = $this->_db->getArrayByQuery($sql);
                $this->_db->printSql();
				foreach ($res as $k => $v) {
					$res[$k]['DATI_LANCIO'] = [];
					$res[$k]['DATI_LANCIO'] = $this->getDatiLancio($v['ID_LANCIO']);
				}

				//$this->_db->close_db();
			}
			//echo $sql2;
			return $res;
		} catch (Exception $e) {

			throw $e;
		}
	}
	/**
	 * getMaxLanci
	 *
	 * @param  mixed $controlli
	 * @return void
	 */
	public function getMaxLanci($controlli)
	{
		$maxLanci = 0;
		foreach ($controlli as $v) {
			$nLanci = count($v['LANCI']);
			if ($nLanci > $maxLanci) {
				$maxLanci = $nLanci;
			}
		}
		return $maxLanci;
	}

	/**
	 * getSottoGruppi
	 *
	 * @param  mixed $idGruppo
	 * @return void
	 */
	public function getSottoGruppi($ID_GRUPPO = '', $ID_INPORT = '', $SOTTO_GRUPPO = '', $ID_CONTR = '')
	{
		try {
			$sql = "SELECT SOTTO_GRUPPO, COUNT(*) count
			FROM " . $this->_tableControlliAnag . "
			where 1=1 ";
			if ($ID_GRUPPO) {
				$sql .= "and ID_GRUPPO='$ID_GRUPPO' ";
			}

			if ($ID_INPORT) {
				$sql .= "and ID_INPORT='$ID_INPORT' ";
			}
			if ($SOTTO_GRUPPO) {
				$SOTTO_GRUPPO = strtolower($SOTTO_GRUPPO);
				$sql .= "and LCASE(SOTTO_GRUPPO) Like '%" . $SOTTO_GRUPPO . "%' ";
			}

			if ($ID_CONTR) {
				$sql .= "and ID_CONTR='$ID_CONTR' ";
			}

			$sql .= "GROUP BY SOTTO_GRUPPO ";

			$res = $this->_db->getArrayByQuery($sql);

			return $res;
		} catch (Exception $e) {

			throw $e;
		}
	}
	/**
	 * getFile
	 *
	 * @param  mixed $idGruppo
	 * @return void
	 */
	public function getListaFile($ID_GRUPPO = '', $SOTTO_GRUPPO = '')
	{
		try {
			$sql = "SELECT distinct ID_FILE,NAME_FILE FROM " . $this->_tableControlliLanciAnag . " where NAME_FILE IS NOT NULL ";
			if (is_array($SOTTO_GRUPPO)) {
				$SOTTO_GRUPPO_IN =  $this->arrayToSqlInString($SOTTO_GRUPPO);
				$sql .= "and SOTTO_GRUPPO in ($SOTTO_GRUPPO_IN) ";
			}
			if ($ID_GRUPPO) {
				$sql .= "and ID_GRUPPO = $ID_GRUPPO ";
			}

			$res = $this->_db->getArrayByQuery($sql, []);
			//$this->_db->printSql();
			return $res;
		} catch (Exception $e) {

			throw $e;
		}
	}

	/**
	 * getGruppi
	 *
	 * @param  mixed $idGruppo
	 * @return void
	 */
	public function getGruppi($idGruppo = '')
	{
		try {
			$sql = "SELECT ID_GRUPPO, DESCR, STATO 
			FROM " . $this->_tableControlliGruppi . " where 1=1 ";
			if ($idGruppo) {
				$sql .= "and ID_GRUPPO=$idGruppo ";
			}
			$res = $this->_db->getArrayByQuery($sql, []);

			return $res;
		} catch (Exception $e) {

			throw $e;
		}
	}

	
	/**
	 * getInport
	 *
	 * @param  mixed $ID_GRUPPO
	 * @return void
	 */
	public function getInport($ID_GRUPPO = '')
	{
		try {
			if ($ID_GRUPPO) {
				$sql = "SELECT VARCHAR_FORMAT(TMS_INSERT,'YYYY-MM-DD') DATA_INPORT,*
			FROM " . $this->_tableControlliInport . " where ID_GRUPPO=?
			ORDER BY ID_INPORT DESC";
			}
			$res = $this->_db->getArrayByQuery($sql, [$ID_GRUPPO]);

			return $res;
		} catch (Exception $e) {

			throw $e;
		}
	}

	public function getStoricoDownload()
	{
		try {
			$UTENTE = $_SESSION['codname'];
			$sql = "SELECT *
			FROM " . $this->_tableControlliExport . " where UTENTE = ?
			ORDER BY ID_EXPORT DESC";
			
			$res = $this->_db->getArrayByQuery($sql, [$UTENTE]);

			return $res;
		} catch (Exception $e) {

			throw $e;
		}
	}

	/**
	 * getControlliConLanci
	 *
	 * @param  mixed $ID_GRUPPO
	 * @param  mixed $SOTTO_GRUPPO
	 * @return void
	 */
	public function getControlliConLanci($ID_GRUPPO, $SOTTO_GRUPPO, $ID_INPORT)
	{
		$res = [];
		try {
			if ($ID_GRUPPO) {
				$sql = "SELECT CA.ID_CONTR as ID, CL.ID_LANCIO,* FROM $this->_tableControlliAnag CA 
				LEFT JOIN " . $this->_tableControlliLanci . " CL ON CL.ID_CONTR = CA.ID_CONTR  
				LEFT JOIN " . $this->_tableControlliTipo . " CT ON CT.ID_TIPO = CA.ID_TIPO 
				WHERE CA.ID_GRUPPO= ?";
				if ($SOTTO_GRUPPO) {
					$sql .= "and CA.SOTTO_GRUPPO='$SOTTO_GRUPPO' ";
				}
				if ($ID_INPORT) {
					$sql .= "and CA.ID_INPORT='$ID_INPORT' ";
				}

				$sql .= "ORDER BY CA.SOTTO_GRUPPO, CA.ID_CONTR, CL.ID_LANCIO DESC";


				$datiControlliLanci = $this->_db->getArrayByQuery($sql, [$ID_GRUPPO]);

				//$this->_db->printSql("getControlliConLanci");
				//$this->_db->close_db();
			}
			$id = '';

			$plancio = 0;
			foreach ($datiControlliLanci as $k => $row) {
				$plancio++;
				if ($row['ID'] != $id) {
					$id = $row['ID'];
					$plancio = 0;
					$res[$id] = $datiControlliLanci[$k];
					$res[$id]['LANCI'] = [];
				}
				/*
			[INIZIO_LANCIO] => 2024-07-10 15:16:39.049116
            [FINE_LANCIO] => 
            [ESITO] => OK
            [RISULTATO] => 0
            [NOTE] => Risultati=0
			*/
				$LANCIO =  $this->getDatiLancio($row['ID_LANCIO']);
				if ($LANCIO) {
					$res[$id]['LANCI'][$plancio] = [];
					$res[$id]['LANCI'][$plancio] = $LANCIO;
					$res[$id]['LANCI'][$plancio]['ID_LANCIO'] = $row['ID_LANCIO'];
					$res[$id]['LANCI'][$plancio]['INIZIO_LANCIO'] = $row['INIZIO_LANCIO'];
					$res[$id]['LANCI'][$plancio]['FINE_LANCIO'] = $row['FINE_LANCIO'];
					$res[$id]['LANCI'][$plancio]['ESITO'] = $row['ESITO'];
					$res[$id]['LANCI'][$plancio]['RISULTATO'] = $row['RISULTATO'];
					$res[$id]['LANCI'][$plancio]['NOTE'] = $row['NOTE'];
				}
			}
			//$this->_db->error_message("getControlliConLanci", $res);
			//echo $sql2;
			return $res;
		} catch (Exception $e) {

			throw $e;
		}
	}

	/**
	 * getLanciGruppo
	 *
	 * @param  mixed $idGruppo
	 * @return void
	 */
	public function getLanciGruppo($ID_GRUPPO, $ID_FILE = '', $ID_INPORT = '')
	{
		try {
			if ($ID_GRUPPO) {
				$sql = "SELECT VARCHAR_FORMAT(TMS_INSERT,'YYYY-MM-DD') DATA_LANCIO, *
						FROM " . $this->_tableControlliLanciAnag . "
						where ID_GRUPPO = ?";
				if ($ID_INPORT) {
					$sql .= " and ID_INPORT = $ID_INPORT";
				}				
				if (is_array($ID_FILE)) {
					$ID_FILE_IN = $this->arrayToSqlInString($ID_FILE);
					$sql .= "and ID_FILE in ($ID_FILE_IN) ";
				}
				$sql .= "	ORDER BY ID_LANCIO DESC";
			}

			$res = $this->_db->getArrayByQuery($sql, [$ID_GRUPPO]);
			//print_r($res);
			return $res;
		} catch (Exception $e) {

			throw $e;
		}
	}


	/**
	 * getLancio
	 *
	 * @param  mixed $ID_LANCIO
	 * @return void
	 */
	public function getLancio($ID_LANCIO)
	{
		try {
			if ($ID_LANCIO) {
				$sql = "SELECT VARCHAR_FORMAT(TMS_INSERT,'YYYY-MM-DD') DATA_LANCIO, *
						FROM " . $this->_tableControlliLanciAnag . "
						where ID_LANCIO = ? 
						ORDER BY ID_LANCIO DESC
						";
			}

			$res = $this->_db->getArrayByQuery($sql, [$ID_LANCIO]);
			//print_r($res);
			return $res;
		} catch (Exception $e) {

			throw $e;
		}
	}


	/**
	 * getTipo
	 *
	 * @param  mixed $ID_TIPO
	 * @param  mixed $TIPOLIKE
	 * @param  mixed $TIPO
	 * @return void
	 */
	public function getTipo($ID_TIPO = '', $TIPOLIKE = '', $TIPO = '')
	{
		try {
			$sql = "SELECT *
			FROM " . $this->_tableControlliTipo . " where 1=1 ";
			if ($ID_TIPO) {
				$sql .= "and ID_TIPO=$ID_TIPO ";
			}
			if ($TIPOLIKE) {
				$TIPOLIKE = strtolower($TIPOLIKE);
				$sql .= "and LCASE(TIPO) like '%" . $TIPOLIKE . "%' ";
			}
			if ($TIPO) {
				$TIPO = strtolower($TIPO);
				$sql .= "and LCASE(TIPO) = '$TIPO' ";
			}
			$res = $this->_db->getArrayByQuery($sql, []);

			return $res;
		} catch (Exception $e) {

			throw $e;
		}
	}

	/**
	 * getUltimoLancio
	 *
	 * @param  mixed $ID_GRUPPO
	 * @return void
	 */
	public function getUltimoLancio($ID_GRUPPO)
	{
		try {
			$idLancio = false;
			if ($ID_GRUPPO) {
				$sql = "select max(ID_LANCIO) ID from $this->_tableControlliLanciAnag where ID_GRUPPO =?";
				$datiLancio = $this->_db->getArrayByQuery($sql, [$ID_GRUPPO]);
				//print_r($datiLancio);
				$idLancio =	$datiLancio[0]['ID'];
			}
			return $idLancio;
		} catch (Exception $e) {

			throw $e;
		}
	}

	/**
	 * getMAXOrdinamento
	 *
	 * @param  mixed $ID_GRUPPO
	 * @param  mixed $SOTTO_GRUPPO
	 * @return void
	 */
	public function getMAXOrdinamento($ID_GRUPPO, $SOTTO_GRUPPO)
	{
		try {
			$idLancio = false;
			if ($ID_GRUPPO) {
				$sql = "select max(ORDINAMENTO) ID from " . $this->_tableControlliAnag . " where SOTTO_GRUPPO=? AND ID_GRUPPO =?";
				$datiControlli = $this->_db->getArrayByQuery($sql, [$SOTTO_GRUPPO, $ID_GRUPPO]);
				//print_r($datiLancio);
				$maxOrdinamento =	$datiControlli[0]['ID'];
			}
			return $maxOrdinamento;
		} catch (Exception $e) {

			throw $e;
		}
	}

	/**
	 * getInputForzatura
	 *
	 * @param  mixed $forzatura
	 * @return void
	 */
	public function getLancioForzatura($forzatura)
	{
		$forzatura = strtolower($forzatura);
		// Eseguiamo una query SQL per trovare le variabili che corrispondono al valore del campo di input
		$sql = "SELECT FORZATURA, ID_LANCIO FROM " . $this->_tableControlliLanciAnag . " WHERE LCASE(FORZATURA) LIKE '%" . $forzatura . "%'";

		$result = $this->_db->getArrayByQuery($sql, []);

		return $result;
	}

	/*************************************************
	 *        EXPORT EXCEL
	 ************************************************/


	/**
	 * bonificaControlli
	 *
	 * @param  mixed $risultati
	 * @return void
	 */
	public function bonificaControlli($risultati, $storico = 1)
	{
		$ret = [];
		foreach ($risultati as $k => $val) {
			//$ret[$k]=$val;
			//CONTROLLO	DESCR	ESITO	RISULTATO	NOTE			
			$array_classe = controlli_dati::$array_classe;
			$array_esito = controlli_dati::$array_esito;
			//	$ret[$k]['ID'] = $val['ID'];
			$ret[$k]['CONTROLLO'] = $val['CONTROLLO'];
			$ret[$k]['TIPO'] = $val['TIPO'];
			$ret[$k]['CLASSE'] = $val['CLASSE'];
			$ret[$k]['DESCR'] = (strpos($val['DESCR'], 'xunit') !== false) ? '-' : $val['DESCR'];
			$ret[$k]['TESTO'] = $val['TESTO'];
			$ret[$k]['ATTESO'] = $val['ATTESO'];
			$nlancio = 1;
			$LANCI = $val['LANCI'];
			/*
			ID_LANCIO,ID_GRUPPO,SOTTO_GRUPPO,ID_CONTR,CLASSE,ID_TIPO,FORZATURA,DESCR,ID_FILE,NAME_FILE,TMS_INSERT,TMS_ELAB,TMS_END,STATO,UTENTE
			*/
			if ($storico == 1) {
				foreach ($LANCI as $LANCIO) {
					$INIZIO_LANCIO = substr($LANCIO['INIZIO_LANCIO'], 0, -7);
					$FINE_LANCIO = substr($LANCIO['FINE_LANCIO'], 0, -7);
					$DURATA = controlli_dati::getDurata($INIZIO_LANCIO, $FINE_LANCIO);
					//$ret[$k]['ID_LANCIO_' . $nlancio] = $LANCIO['ID_LANCIO'];
					$ret[$k]['DATA_' . $nlancio] = $INIZIO_LANCIO;
					$ret[$k]['LANCIO_' . $nlancio] = $LANCIO['DESCR'];
					$ret[$k]['FILE_' . $nlancio] = $LANCIO['NAME_FILE'];
					$ret[$k]['FILTRI_' . $nlancio] = str_replace(['<br>', '<br/>'], ['; ', '; '], $LANCIO['FILTRI']);
					
					//$ret[$k]['DURATA_' . $nlancio] = $DURATA;
					$ret[$k]['ESITO_' . $nlancio] = $array_esito[$LANCIO['ESITO']];
					$ret[$k]['RISULTATO_' . $nlancio] = $LANCIO['RISULTATO'];
					$ret[$k]['NOTE_' . $nlancio] = $LANCIO['NOTE'];
					$nlancio++;
				}
			}

			//$ret[$k]['DESCR']= htmlspecialchars($val['DESCR'], ENT_COMPAT,'ISO-8859-1', true);

		}
		return $ret;
	}

	/**
	 * createExcelControlli
	 *
	 * @param  mixed $ID_GRUPPO
	 * @param  mixed $SOTTO_GRUPPO
	 * @return void
	 */
	public function createExcelControlli($ID_GRUPPO, $ID_INPORT, $SOTTO_GRUPPO, $storico = 1)
	{
		if ($ID_GRUPPO) {
			$sottogruppi = $this->getSottoGruppi($ID_GRUPPO, $ID_INPORT);
			$Agruppi = $this->getGruppi($ID_GRUPPO);
			$gruppo = $Agruppi[0]['DESCR'];
			$fileName = ($storico==1)? 'Controlli_Storico_' . $gruppo:'Controlli_' . $gruppo;
			
			if ($SOTTO_GRUPPO) {
			//	$fileName .= "_" . $SOTTO_GRUPPO;
				$sottogruppi = [];
				$sottogruppi[0]['SOTTO_GRUPPO'] = $SOTTO_GRUPPO;
			}
			$xlsx = new SimpleXLSXGen();
			foreach ($sottogruppi  as $val) {
				$SOTTO_GRUPPO = $val['SOTTO_GRUPPO'];
				$controlli = $this->getControlliConLanci($ID_GRUPPO, $SOTTO_GRUPPO, $ID_INPORT);
				$MaxLanci = $this->getMaxLanci($controlli);
				//$this->_db->error_message('MaxLanci',$MaxLanci);
				$intestazione[0] = ['Controllo', 'Tipo', 'Classe', 'Descrizione', 'Query/Controllo','Valore Atteso'];
				if ($storico == 1) {
					for ($i = 1; $i <= $MaxLanci; $i++) {
						//$intestazione[0][] = 'Id Lancio_' . $i;
						$intestazione[0][] = 'Data_' . $i;
						$intestazione[0][] = 'Lancio_' . $i;
						$intestazione[0][] = 'File_' . $i;
						$intestazione[0][] = 'Filtri_' . $i;
						
						//$intestazione[0][] = 'Durata_' . $i;
						$intestazione[0][] = 'Esito_' . $i;
						$intestazione[0][] = 'Risultato_' . $i;
						$intestazione[0][] = 'Note_' . $i;
					}
				}



				$res = $this->bonificaControlli($controlli,$storico);

				$controlli = array_merge($intestazione, $res);


				$xlsx->addSheet($controlli, $SOTTO_GRUPPO);
				//$this->_db->error_message($SOTTO_GRUPPO, $controlli);
				//$xlsx = Shuchkin\SimpleXLSXGen::fromArray($controlli,$SOTTO_GRUPPO);

			}
			$xlsx->saveAs('./TMP/' . $fileName . '.xlsx');
			shell_exec('chmod 774 ./TMP/' . $fileName . '.xlsx');
		}
		return $fileName;
	}



	/**
	 * bonificaLanci
	 *
	 * @param  mixed $risultati
	 * @return void
	 */
	public	function bonificaLanci($risultati)
	{
		$ret = [];
		foreach ($risultati as $k => $val) {
			//$ret[$k]=$val;
			//CONTROLLO	DESCR	ESITO	RISULTATO	NOTE			
			$array_classe = controlli_dati::$array_classe;
			$array_esito = controlli_dati::$array_esito;

			//$ret[$k]['ID'] = $val['ID'];
			$ret[$k]['CONTROLLO'] = $val['CONTROLLO'];
			$ret[$k]['TIPO'] = $val['TIPO'];
			$ret[$k]['CLASSE'] = $val['CLASSE'];
			$ret[$k]['DESCR'] = (strpos($val['DESCR'], 'xunit') !== false) ? '-' : $val['DESCR'];
			//$ret[$k]['TESTO'] = $val['TESTO'];
			//$ret[$k]['ID_LANCIO'] = $val['ID_LANCIO'];
			$INIZIO_LANCIO = substr($val['INIZIO_LANCIO'], 0, -7);
			//$FINE_LANCIO = substr($val['FINE_LANCIO'], 0, -7);
			//$DURATA = controlli_dati::getDurata($INIZIO_LANCIO, $FINE_LANCIO);
			$ret[$k]['LANCIO'] = $val['DATI_LANCIO']['DESCR'];
			$ret[$k]['DATA'] = $INIZIO_LANCIO;
			$ret[$k]['FILE'] = $val['DATI_LANCIO']['NAME_FILE'];
			$ret[$k]['FILTRI'] = str_replace(['<br>', '<br/>'], ['; ', '; '], $val['DATI_LANCIO']['FILTRI']);
			$ret[$k]['ESITO'] = $array_esito[$val['ESITO']];
			$ret[$k]['RISULTATO'] = $val['RISULTATO'];
			$ret[$k]['NOTE'] = $val['NOTE'];
		}
		return $ret;
	}
	/**
	 * createExcelLancio
	 *
	 * @param  mixed $GRUPPO
	 * @return void
	 */
	public function createExcelLancio($ID_LANCIO = '', $ID_INPORT, $SOTTO_GRUPPO = '', $ID_GRUPPO = '')
	{
		if ($ID_GRUPPO) {
			$sottogruppi = $this->getSottoGruppi($ID_GRUPPO, $ID_INPORT);
			$Agruppi = $this->getGruppi($ID_GRUPPO);
			$gruppo = $Agruppi[0]['DESCR'];
			$lancio = $this->getLancio($ID_LANCIO);
			$fileName = 'LANCIO_' .$lancio[0]['DESCR'];
			
			if (is_array($SOTTO_GRUPPO)) {
				//$fileName .= "_" . $SOTTO_GRUPPO;
				$sottogruppi = [];
				foreach($SOTTO_GRUPPO as $v)
				{
				$sottogruppi[]['SOTTO_GRUPPO'] = $v;
				}
			}
			$xlsx = new SimpleXLSXGen();
			foreach ($sottogruppi  as $val) {
				$SOTTO_GRUPPO = $val['SOTTO_GRUPPO'];
				$res = $this->getControlliAnagLancio($ID_LANCIO, $SOTTO_GRUPPO);
				$intestazione = [['Controllo', 'Tipo', 'Classe', 'Descrizione', 'Lancio', 'Data', 'File', 'Filtri', 'Esito', 'Risultato', 'Note']];
				$res = $this->bonificaLanci($res);

				$controlli = array_merge($intestazione, $res);


				$xlsx->addSheet($controlli, $SOTTO_GRUPPO);
				//$this->_db->error_message($fileName, $res);
				//$xlsx = Shuchkin\SimpleXLSXGen::fromArray($controlli,$SOTTO_GRUPPO);

			}
			$xlsx->saveAs('./TMP/' . $fileName . '.xlsx');
			shell_exec('chmod 774 ./TMP/' . $fileName . '.xlsx');
		}
		return $fileName;
	}


	/*************************************************
	 *        IMPORT CONTROLLI BY EXCEL
	 ************************************************/


	/**
	 * insertTipoByTesto
	 *
	 * @param  mixed $tipo
	 * @return void
	 */
	public function insertTipoByTesto($tipo)
	{
		try {
			$res = $this->getTipo('', '', $tipo);
			$ID_TIPO = $res[0]['ID_TIPO'];
			if (!$ID_TIPO) {
				$sql = "INSERT INTO " . $this->_tableControlliTipo . " (TIPO) VALUES (?)";
				$ID_TIPO = $this->_db->insertDb($sql, [$tipo]);
				//$this->_db->error_message("insertTipoByTesto ID_TIPO", $ID_TIPO);
			}

			return $ID_TIPO;
		} catch (Exception $e) {

			throw $e;
		}
	}



	/**
	 * updateValiditaControllo
	 *
	 * @param  mixed $ID_GRUPPO
	 * @param  mixed $VALIDO
	 * @param  mixed $ID_CONTR
	 * @return void
	 */
	public function updateValiditaControllo($ID_CONTR, $VALIDO)
	{
		try {
			if ($ID_CONTR) {
				$sql = "UPDATE " . $this->_tableControlliAnag . "  set VALIDO = ? WHERE 1=1 ";

				$sql .= "and ID_CONTR = ?";


				$this->_db->updateDb($sql, [$VALIDO, $ID_CONTR]);
			}
			//echo $this->_db->getsqlQuery();}
		} catch (Exception $e) {

			throw $e;
		}
	}

	/**
	 * getDatiByExcel
	 *
	 * @return void
	 */
	public function getDatiByExcel($ID_GRUPPO, $INPORT)
	{

		$ret = [];
		if ($ID_GRUPPO) {
			//$ArrayGruppo = $this->getGruppi($ID_GRUPPO);
			$ID_INPORT = $this->generaInport($ID_GRUPPO, $INPORT, $_FILES["FormTabDdl"]["name"]);
			//$GRUPPO = $ArrayGruppo[0]['DESCR'];
			if ($ID_INPORT) {
				//$this->updateValiditaControllo($ID_GRUPPO, 0);
				if ($xlsx = SimpleXLSX::parse($_FILES["FormTabDdl"]["tmp_name"])) {
					$sheets = $xlsx->sheetNames();
					$oldTiPO = '';

					foreach ($sheets as $index => $SOTTO_GRUPPO) {
						//	echo "Reading sheet :" . $SOTTO_GRUPPO . "<br>";

						$firstrow = 1;
						$rowCount = 0;
						foreach ($xlsx->rows($index) as $r => $row) {
							if ($rowCount > 0) {
								//['Controllo', 'Tipo', 'Classe', 'Descrizione', 'Query/Controllo']
								$CONTROLLO = $row[0];
								$TIPO = ($row[1] != '') ? $oldTiPO = $row[1] : $oldTiPO;

								if ($CONTROLLO && $TIPO) {

									//$sql = strtoupper(str_replace('count(*)', '*', $row[2]));


									$CLASSE = 'M';
									$CLASSE = (strpos(strtoupper($row[4]), 'SELECT') !== false) ? 'C' : $CLASSE;
									$CLASSE = (strpos(strtoupper($row[4]), 'SUM') !== false) ? 'S' : $CLASSE;
								//	$CLASSE = ($row[2] == 'NECESSARIO') ? 'N' : $CLASSE;
									$CLASSE = $row[2];
									$DESCR = $row[3];

									$TESTO = $row[4];
									$TESTO = strtoupper(str_replace(';', '', $TESTO));
									$TESTO = strtoupper(str_replace('"?"', "'?'", $TESTO));
									$TESTO = strtoupper(str_replace('count(*)', '*', $TESTO));
									$TESTO = strtoupper(str_replace('COUNT(*)', '*', $TESTO));
									$TESTO .=(strpos($TESTO , 'WHERE') !== false) ? '' : ' WHERE 1=1 ';
									$ATTESO = $row[5]?$row[5]:0;	
									$ORDINAMENTO = $firstrow++;



									$UTENTE = $_SESSION['codname'];
									$ID_TIPO = $this->insertTipoByTesto($TIPO);
									//[SOCIETA, GRUPPO, ORDINAMENTO, CONTROLLO, DESCR, CLASSE, TESTO,UTENTE]
									$TMS_MODIFICA = date("Y-m-d H:i:s", time());
									$ret[] = [$ID_GRUPPO, $SOTTO_GRUPPO, $ORDINAMENTO, $CONTROLLO, $DESCR, $ID_TIPO, $CLASSE, $TESTO, $ATTESO, $UTENTE, $ID_INPORT, $TMS_MODIFICA, $UTENTE, $ID_INPORT];
								}
							}
							$rowCount++;
						}
					}
				}
			}
		}

		$this->importControlliToDb($ret);
		return $ID_INPORT;
	}




	/**
	 * importControlliToDb
	 *
	 * @param  mixed $dati
	 * @return void
	 */
	public function importControlliToDb($dati)
	{
		try {
			//$this->removeControlli($dati[0][0]);
			foreach ($dati as $dato) {
				$ID_GRUPPO = $dato[0];
				$SOTTO_GRUPPO = $dato[1];
				$maxOrdinamento = $this->getMAXOrdinamento($ID_GRUPPO, $SOTTO_GRUPPO);
				$dato[2] = $maxOrdinamento + 1;
				$ORDINAMENTO = $dato[2];
				$CONTROLLO = $dato[3];
				$DESCR = $dato[4];
				$ID_TIPO = $dato[5];
				$CLASSE = $dato[6];
				$TESTO = $dato[7];
				$ATTESO = $dato[8];
				$UTENTE = $dato[9];
				$ID_INPORT = $dato[10];
				$DatiControlo = $this->getControlliAnag($ID_GRUPPO, $SOTTO_GRUPPO, $CONTROLLO);
				$ID_CONTR = $DatiControlo[0]['ID_CONTR'];				
				if (!$ID_CONTR) {
					$sql =	"INSERT INTO " . $this->_tableControlliAnag . " (ID_GRUPPO, SOTTO_GRUPPO, ORDINAMENTO, CONTROLLO, DESCR, ID_TIPO, CLASSE, TESTO,ATTESO,UTENTE,ID_INPORT,TMS_MODIFICA,UTENTE_MODIFICA,ID_INPORT_MODIFICA) 
				VALUES (?, ?,?, ?, ?, ?, ?, ?,?,?,?, ?,?,?);";

					$last_id = $this->_db->insertDb($sql, $dato);
					if (!$last_id) {
						$this->_db->printSql();
					}
				} else {

					$TMS_MODIFICA = date("Y-m-d H:i:s", time());
					$sql = "UPDATE " . $this->_tableControlliAnag . "  
						SET 						
						DESCR = ?,
						ID_TIPO = ?,
						CLASSE = ?,
						TESTO = ?,
						ATTESO = ?,
						UTENTE_MODIFICA = ?,
						ID_INPORT_MODIFICA = ?,		
						TMS_MODIFICA = ?		
						WHERE ID_CONTR = ?;";
					$this->_db->updateDb($sql, [$DESCR, $ID_TIPO, $CLASSE, $TESTO, $ATTESO, $UTENTE, $ID_INPORT, $TMS_MODIFICA, $ID_CONTR]);

				//	$this->_db->printSql();
				}
			}

			return $last_id;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}


	/**
	 * updateStatoGruppo
	 *
	 * @param  mixed $idGruppo
	 * @param  mixed $stato
	 * @return void
	 */
	public function updateStatoGruppo($idGruppo, $stato)
	{
		try {
			$sql = "UPDATE " . $this->_tableControlliGruppi . " 
					SET STATO = ?
					WHERE ID_GRUPPO = ?";
			$res = $this->_db->updateDb($sql, [$stato, $idGruppo]);
			return $res;
		} catch (Exception $e) {

			throw $e;
		}
	}





	/**
	 * updateControllo
	 *
	 * @param  mixed $array_controllo
	 * @return void
	 */
	public function updateControllo($array_controllo)
	{
		try {
			$UTENTE = $_SESSION['codname'];

			if ($array_controllo['TIPO']) {
				$array_controllo['ID_TIPO'] = $this->insertTipoByTesto($array_controllo['TIPO']);
			} else {
				$array_controllo['ID_TIPO'] = 1;
			}
			$UTENTE = $_SESSION['codname'];
			$DatiControlo = $this->getControlliAnag($array_controllo['ID_GRUPPO'], $array_controllo['SOTTO_GRUPPO'], $array_controllo['CONTROLLO']);
			$ID_CONTR = $DatiControlo[0]['ID_CONTR'];	
			$ID_CONTR = ($array_controllo['ID_CONTR'])?$array_controllo['ID_CONTR']:$ID_CONTR;
			$TMS_MODIFICA = date("Y-m-d H:i:s", time());
			if ($ID_CONTR) {
				$sql = "UPDATE " . $this->_tableControlliAnag . "  
						SET 
						SOTTO_GRUPPO = ?,
						CONTROLLO = ?,
						DESCR = ?,
						ID_TIPO = ?,
						CLASSE = ?,
						TESTO = ?,
						ATTESO = ?,
						UTENTE_MODIFICA = ?	,		
						TMS_MODIFICA = ?			
						WHERE ID_CONTR = ? ";
				$this->_db->updateDb($sql, [$array_controllo['SOTTO_GRUPPO'], $array_controllo['CONTROLLO'], $array_controllo['DESCR'], $array_controllo['ID_TIPO'],  $array_controllo['CLASSE'], $array_controllo['TESTO'], $array_controllo['ATTESO'], $UTENTE, $TMS_MODIFICA, $ID_CONTR]);
			} else {
				$debugArray = [];
				$maxOrdinamento = $this->getMAXOrdinamento($array_controllo['ID_GRUPPO'], $array_controllo['SOTTO_GRUPPO']);
				$maxOrdinamento=(!$maxOrdinamento)?1:$maxOrdinamento + 1;
				$array_controllo['ID_INPORT'] = ($array_controllo['ID_INPORT'])?$array_controllo['ID_INPORT']:null;
				$sql =	"INSERT INTO $this->_tableControlliAnag (ID_GRUPPO,ID_INPORT,  SOTTO_GRUPPO, ORDINAMENTO, CONTROLLO, DESCR, ID_TIPO, CLASSE, TESTO,ATTESO,UTENTE,UTENTE_MODIFICA,TMS_MODIFICA) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?);";
				$dato =  [$array_controllo['ID_GRUPPO'], $array_controllo['ID_INPORT'], $array_controllo['SOTTO_GRUPPO'], $maxOrdinamento, $array_controllo['CONTROLLO'], $array_controllo['DESCR'], $array_controllo['ID_TIPO'], $array_controllo['CLASSE'], $array_controllo['TESTO'], $array_controllo['ATTESO'], $UTENTE, $UTENTE, $TMS_MODIFICA];


				$ID_CONTR = $this->_db->insertDb($sql, $dato);
				//	$this->_db->printSql();
				$debugArray["ID_CONTR"] = $ID_CONTR;
				return $debugArray;
			}
		} catch (Exception $e) {

			throw $e;
		}
	}	
	/**
	 * modificaEsito
	 *
	 * @param  mixed $array_controllo
	 * @return void
	 */
	public function modificaEsito($array_controllo)
	{
		try {
			$UTENTE = $_SESSION['codname'];
			$TMS_MODIFICA = date("Y-m-d H:i:s", time());
			$LISTA_CONTROLLI_IN = $this->arrayToSqlInString($array_controllo['LISTA_ID_CONTR']);
			if ($array_controllo['ID_LANCIO'] && $LISTA_CONTROLLI_IN) {
				$sql = "UPDATE " . $this->_tableControlliLanci . "  
							SET 
							ESITO = ?,							
							NOTE = ?,
							UTENTE = ?,
							MODIFICA_LANCIO = ?							
							WHERE ID_LANCIO = ? and ID_CONTR IN (".$LISTA_CONTROLLI_IN.")";
				$this->_db->updateDb($sql, [$array_controllo['ESITO'], $array_controllo['NOTE'],$UTENTE,$TMS_MODIFICA, $array_controllo['ID_LANCIO']]);
				//$this->_db->printSql();
			}
		} catch (Exception $e) {

			throw $e;
		}
	}
	
	
	/**
	 * modificaValidita
	 *
	 * @param  mixed $array_controllo
	 * @return void
	 */
	public function modificaValidita($array_controllo)
	{
		try {
			$UTENTE = $_SESSION['codname'];
			$TMS_MODIFICA = date("Y-m-d H:i:s", time());
			$LISTA_CONTROLLI_IN = $this->arrayToSqlInString($array_controllo['LISTA_ID_CONTR']);
			if ($LISTA_CONTROLLI_IN) {
				$sql = "UPDATE " . $this->_tableControlliAnag . "  
							SET 
							VALIDO = ?,
							UTENTE_MODIFICA = ?,
							TMS_MODIFICA = ?							
							WHERE ID_CONTR IN (".$LISTA_CONTROLLI_IN.")";
				$this->_db->updateDb($sql, [$array_controllo['VALIDO'],$UTENTE,$TMS_MODIFICA]);
				//$this->_db->printSql();
			}
		} catch (Exception $e) {

			throw $e;
		}
	}


	public function modificaTipo($array_controllo)
	{
		try {
			$UTENTE = $_SESSION['codname'];
			$TMS_MODIFICA = date("Y-m-d H:i:s", time());
			$LISTA_CONTROLLI_IN = $this->arrayToSqlInString($array_controllo['LISTA_ID_CONTR']);

			$ID_TIPO = $this->insertTipoByTesto($array_controllo['TIPO']);
			if ($LISTA_CONTROLLI_IN) {
				$sql = "UPDATE " . $this->_tableControlliAnag . "  
							SET 
							ID_TIPO = ?,
							UTENTE_MODIFICA = ?,
							TMS_MODIFICA = ?							
							WHERE ID_CONTR IN (".$LISTA_CONTROLLI_IN.")";
				$this->_db->updateDb($sql, [$ID_TIPO,$UTENTE,$TMS_MODIFICA]);
				//$this->_db->printSql();
			}
		} catch (Exception $e) {

			throw $e;
		}
	}


	/**
	 * updateLancio
	 *
	 * @param  mixed $array_controllo
	 * @return void
	 */
	public function updateLancio($array_controllo)
	{
		try {
			$UTENTE = $_SESSION['codname'];
			$TMS_MODIFICA = date("Y-m-d H:i:s", time());
			if ($array_controllo['ID_LANCIO']) {
				$sql = "UPDATE " . $this->_tableControlliLanci . "  
							SET 
							ESITO = ?,
							RISULTATO = ?,
							NOTE = ?,
							UTENTE = ?,
							MODIFICA_LANCIO = ?							
							WHERE ID_LANCIO = ? and ID_CONTR = ?";
				$this->_db->updateDb($sql, [$array_controllo['ESITO'], $array_controllo['RISULTATO'], $array_controllo['NOTE'],$UTENTE,$TMS_MODIFICA, $array_controllo['ID_LANCIO'], $array_controllo['ID_CONTR']]);
				//$this->_db->printSql();
			}
		} catch (Exception $e) {

			throw $e;
		}
	}

	/**
	 * aggionaLancioControllo
	 *
	 * @param  mixed $ID_CONTR
	 * @param  mixed $ID_LANCIO
	 * @param  mixed $RISULTATO
	 * @return void
	 */
	public function aggionaLancioControllo($ID_CONTR, $ID_LANCIO, $RISULTATO)
	{
		try {
			$UTENTE = $_SESSION['codname'];
			$ESITO = ($RISULTATO == 0) ? 'OK' : 'KO';
			$NOTE = "Lancio aggiornato RISULTATO=" . $RISULTATO;
			if ($ID_LANCIO && $ID_CONTR) {
				$sql = "UPDATE " . $this->_tableControlliLanci . "  
				SET 
				ESITO = ?,
				RISULTATO = ?,
				NOTE = ?
			
				WHERE ID_LANCIO = ? and ID_CONTR = ?";
				$this->_db->updateDb($sql, [$ESITO, $RISULTATO, $NOTE, $ID_LANCIO, $ID_CONTR]);
			}
		} catch (Exception $e) {

			throw $e;
		}
	}


	/**
	 * removeControlli
	 *
	 * @param  mixed $SOCIETA
	 * @return void
	 */
	public function removeControlli($ID_GRUPPO)
	{
		try {
			$sql = "DELETE FROM " . $this->_tableControlliAnag . " WHERE ID_GRUPPO = ?";
			$ret = $this->_db->deleteDb($sql, array($ID_GRUPPO));
			return $ret;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}

	/************************************
	 *   GENERAZIONE LANCIO SENZA SH
	 ************************************/

	/** NON UTILIZZATO
	 * eseguiQuery
	 *
	 * @param  mixed $sql
	 * @return void
	 */
	/*public function eseguiQuery($sql)
	{
		try {

			$ris = $this->_db->getArrayNoAssocByQuery($sql, []);
			return $ris[0][0];
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}*/

	/** NON UTILIZZATO
	 * creaRisultatiControlli 
	 *
	 * @param  mixed $datiControlli
	 * @param  mixed $ID_LANCIO
	 * @return void
	 */
	/*	public function creaRisultatiControlli($datiControlli, $ID_LANCIO)
	{

		ini_set('memory_limit', '1024M');
		//ini_set('memory_limit', '-1');

		$CONTROLLI_LANCI = [];
		$nControlli = 0;
		foreach ($datiControlli as $controlli) {
			$ID_CONTR = $controlli['ID_CONTR'];

			$RISULTATO = 0;
			$ESITO = ($RISULTATO == 0) ? "OK" : "KO";
			$NOTE = "Risultati=" . $RISULTATO;
			$CONTROLLI_LANCI[$nControlli++] = [$ID_CONTR, $ID_LANCIO, $ESITO, $RISULTATO, $NOTE];
		}

		$sql = "DELETE FROM ".$this->_tableControlliLanci." where ID_LANCIO=?";
		$this->_db->deleteDb($sql, [$ID_LANCIO]);
		//$this->_db->close_db();
		foreach ($CONTROLLI_LANCI  as $valControlli) {
			$sql =	"INSERT INTO ".$this->_tableControlliLanci." (ID_CONTR, ID_LANCIO, ESITO, RISULTATO, NOTE) VALUES (?, ?,?, ?, ?);";
			$last_id = $this->_db->insertDb($sql, $valControlli);
			if (!$last_id) {
				echo "<pre>";
				echo $this->_db->getsqlQuery();
				echo "</pre>";
			}
		}
		return  $nControlli;
	}*/

	/************************************
	 *    CREAZIONE LANCIO
	 ******************************************/

	/**
	 * generaLancio
	 *
	 * @param  mixed $idGruppo
	 * @param  mixed $gruppo
	 * @return void
	 */
	public function generaLancio($datiLancio)
	{
		try {
			$ID_GRUPPO = $datiLancio['ID_GRUPPO'];
			$ID_INPORT = $datiLancio['ID_INPORT'] ? $datiLancio['ID_INPORT'] : null;
			$SOTTO_GRUPPO = $datiLancio['SOTTO_GRUPPO'] ? $datiLancio['SOTTO_GRUPPO'] : null;
			$ID_CONTR =is_array($datiLancio['ID_CONTR']) ?$this->arrayToSqlInString( $datiLancio['ID_CONTR']) : null;
			
			$CLASSE = is_array($datiLancio['CLASSE']) ? $this->arrayToSqlInString($datiLancio['CLASSE']) : null;
			$ID_TIPO = is_array($datiLancio['ID_TIPO']) ? $this->arrayToSqlInString($datiLancio['ID_TIPO']) : null;
			
			$FORZATURA = $datiLancio['FORZATURA'] ? $datiLancio['FORZATURA'] : null;
			$DESCR = $datiLancio['DESCR'] ? $datiLancio['DESCR'] : null;
			$NAME_FILE = $datiLancio['NAME_FILE'] ? $datiLancio['NAME_FILE'] : null;
			$ID_FILE = $datiLancio['ID_FILE'] ? $datiLancio['ID_FILE'] : null;
			$STATO = 2;
			$UTENTE = $_SESSION['codname'];
			$sql =	"INSERT INTO " . $this->_tableControlliLanciAnag . " (ID_CONTR ,ID_GRUPPO ,ID_INPORT  ,SOTTO_GRUPPO ,CLASSE ,ID_TIPO ,FORZATURA ,DESCR ,STATO ,NAME_FILE ,ID_FILE, UTENTE) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
			$idLancio = $this->_db->insertDb($sql, [$ID_CONTR, $ID_GRUPPO, $ID_INPORT, $SOTTO_GRUPPO, $CLASSE, $ID_TIPO, $FORZATURA, $DESCR, $STATO, $NAME_FILE, $ID_FILE, $UTENTE]);

			//$this->_db->printSql();
			//	$this->_db->close_db();
			return $idLancio;
		} catch (Exception $e) {

			throw $e;
		}
	}

	
	/**
	 * generaInport
	 *
	 * @param  mixed $ID_GRUPPO
	 * @param  mixed $INPORT
	 * @param  mixed $NAME_FILE
	 * @return void
	 */
	public function generaInport($ID_GRUPPO, $INPORT, $NAME_FILE = '')
	{
		try {

			$UTENTE = $_SESSION['codname'];
			$sql =	"INSERT INTO " . $this->_tableControlliInport . " (ID_GRUPPO,INPORT,NAME_FILE, UTENTE) VALUES (?,?,?,?)";
			$id = $this->_db->insertDb($sql, [$ID_GRUPPO, $INPORT, $NAME_FILE, $UTENTE]);

			//$this->_db->printSql();
			//	$this->_db->close_db();
			return $id;
		} catch (Exception $e) {

			throw $e;
		}
	}

	public function generaExport($ID_GRUPPO, $NAME_FILE = '')
	{
		try {

			$UTENTE = $_SESSION['codname'];
			$sql =	"INSERT INTO " . $this->_tableControlliExport . " (ID_GRUPPO,NAME_FILE, UTENTE) VALUES (?,?,?)";
			$id = $this->_db->insertDb($sql, [$ID_GRUPPO, $NAME_FILE, $UTENTE]);

			//$this->_db->printSql();
			//	$this->_db->close_db();
			return $id;
		} catch (Exception $e) {

			throw $e;
		}
	}

	public function modificaExport($ID_EXPORT, $NAME_FILE = '')
	{
		try {
			$sql = "UPDATE " . $this->_tableControlliExport . "  set NAME_FILE = ? WHERE ID_EXPORT=? ";			
			$id = $this->_db->updateDb($sql, [$NAME_FILE, $ID_EXPORT]);
			//$this->_db->printSql();
			//	$this->_db->close_db();
			return $id;
		} catch (Exception $e) {

			throw $e;
		}
	}


	/**
	 * creaLancio
	 *
	 * @param  mixed $ID_LANCIO
	 * @param  mixed $ID_GRUPPO
	 * @param  mixed $SOTTO_GRUPPO
	 * @param  mixed $ID_CONTR
	 * @return void
	 */
	public function creaLancio()
	{
		try {


			$PRGDIR = $_SESSION['PRGDIR'];
			$SSHUSR = $_SESSION['SSHUSR'];
			$SERVER = $_SESSION['SERVER'];
			$shLancio = "sh $PRGDIR/AvviaShellServer.sh 'b' '${SSHUSR}' '${SERVER}' '/area_staging_TAS/DIR_SHELL/WORK' 'Controlli.sh' ' '>$PRGDIR/AvviaShellServer2.log";
			shell_exec($shLancio);
			shell_exec('chmod 664 ' . $PRGDIR . '/AvviaShellServer2.log');
			//$this->_db->error_message("creaLancio", $shLancio);


			return 1;
		} catch (Exception $e) {

			throw $e;
		}
	}
}
