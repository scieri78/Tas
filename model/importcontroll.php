<?php
require_once './library/xlsxtocsv/SimpleXLSX.php';
require_once './library/simplexlsxgen/SimpleXLSXGen.php';

use Shuchkin\SimpleXLSXGen;

class importcontroll_model
{
	// set dataimportcontroll config for mysql
	// open mysql data importcontroll
	private $_db;

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
	 * @return void
	 */
	public function getControlliAnagLancio($ID_LANCIO = '', $SOTTO_GRUPPO = '')
	{

		//select * from WORK_RULES.CONTROLLI_ANAG_GRUPPO CA left join WORK_RULES.CONTROLLI_LANCI_GRUPPO CL on CL.ID_CONTR = CA.ID_CONTR where 1=1 order by CA.ID_CONTR
		try {
			if ($ID_LANCIO) {
				$sql = "select *   from WORK_RULES.CONTROLLI_ANAG_GRUPPO CA 
						left join WORK_RULES.CONTROLLI_LANCI_GRUPPO CL on CL.ID_CONTR = CA.ID_CONTR
						 where  CL.ID_LANCIO='$ID_LANCIO' ";

				if ($SOTTO_GRUPPO) {
					$sql .= "and CA.SOTTO_GRUPPO='$SOTTO_GRUPPO' ";
				}
				$sql .= "order by CA.ID_CONTR";


				$res = $this->_db->getArrayByQuery($sql);
				//$this->_db->close_db();
			}
			//echo $sql2;
			return $res;
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}

	public function getSottoGruppi($idGruppo = '')
	{
		try {
			$sql = "SELECT SOTTO_GRUPPO, COUNT(*) count
			FROM WORK_RULES.CONTROLLI_ANAG_GRUPPO
			where 1=1 ";
			if ($idGruppo) {
				$sql .= "and ID_GRUPPO='$idGruppo' ";
			}

			$sql .= "GROUP BY SOTTO_GRUPPO ";

			$res = $this->_db->getArrayByQuery($sql);

			return $res;
		} catch (Exception $e) {
			//echo "ddd";
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
			$sql = "SELECT ID_GRUPPO, GRUPPO 
			FROM WORK_RULES.CONTROLLI_GRUPPI where 1=1 ";
			if ($idGruppo) {
				$sql .= "and ID_GRUPPO=$idGruppo ";
			}
			$res = $this->_db->getArrayByQuery($sql, []);

			return $res;
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}


	public function getLanciGruppo($idGruppo)
	{
		try {
			if ($idGruppo) {
				$sql = "SELECT VARCHAR_FORMAT(TMS_INSERT,'YYYY-MM-DD') DATA_LANCIO, ID_LANCIO, LANCIO
						FROM WORK_RULES.CONTROLLI_LANCI_ANAG
						where ID_GRUPPO = ? 
						ORDER BY ID_LANCIO DESC
						";
			}

			$res = $this->_db->getArrayByQuery($sql, [$idGruppo]);
			//print_r($res);
			return $res;
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}



	public	function bonuficaRisultati($risultati)
	{
		$ret = [];
		foreach ($risultati as $k => $val) {
			//$ret[$k]=$val;
			//CONTROLLO	DESCR	ESITO	RISULTATO	NOTE			
			
			$ret[$k]['CONTROLLO'] = $val['CONTROLLO'];
			$ret[$k]['DESCR'] = (strpos($val['DESCR'], 'unit') !== false) ? '-' : $val['DESCR'];
			$ret[$k]['TESTO'] = $val['TESTO'];
			$ret[$k]['CLASSE'] = controlli_dati::$array_classe;
			$ret[$k]['ESITO'] = ($val['RISULTATO'] == 0) ? 'OK' : 'KO';
			$ret[$k]['RISULTATO'] = $val['RISULTATO'];
			$ret[$k]['NOTE'] = $val['NOTE'];
			//$ret[$k]['DESCR']= htmlspecialchars($val['DESCR'], ENT_COMPAT,'ISO-8859-1', true);

		}
		return $ret;
	}
	/**
	 * createExcel
	 *
	 * @param  mixed $GRUPPO
	 * @return void
	 */
	public function createExcel($ID_LANCIO = '', $SOTTO_GRUPPO = '', $idGruppo = '')
	{
		if ($idGruppo) {
			$sottogruppi = $this->getSottoGruppi($idGruppo);
			$Agruppi = $this->getGruppi($idGruppo);
			$gruppo = $Agruppi[0]['GRUPPO'];
			$fileName = 'Controlli_' . $gruppo;
			if ($SOTTO_GRUPPO) {
				$fileName .= "_" . $SOTTO_GRUPPO;
				$sottogruppi = [];
				$sottogruppi[0]['SOTTO_GRUPPO'] = $SOTTO_GRUPPO;
			}
			$xlsx = new SimpleXLSXGen();
			foreach ($sottogruppi  as $val) {
				$SOTTO_GRUPPO = $val['SOTTO_GRUPPO'];
				$res = $this->getControlliAnagLancio($ID_LANCIO, $SOTTO_GRUPPO);
				$intestazione = [['Macro Controllo','Controllo','Query Controllo','Tipo','Esito', 'Risultato', 'Note']];
				$res = $this->bonuficaRisultati($res);

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
	public function getUltimoLancio()
	{
		try {

			echo  $sql = "select max(ID_LANCIO) ID from WORK_RULES.CONTROLLI_LANCI_ANAG;";
			$datiLancio = $this->_db->getArrayByQuery($sql);
			print_r($datiLancio);
			$idLancio =	$datiLancio[0]['ID'];
			return $idLancio;
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}

	/**
	 * generaLancio
	 *
	 * @param  mixed $idGruppo
	 * @param  mixed $gruppo
	 * @return void
	 */
	public function generaLancio($datiGruppo)
	{
		try {
			$idGruppo = $datiGruppo[0]['ID_GRUPPO'];
			$gruppo = $datiGruppo[0]['GRUPPO'];

			$sql =	"INSERT INTO WORK_RULES.CONTROLLI_LANCI_ANAG ( ID_GRUPPO) VALUES (?)";
			$idLancio = $this->_db->insertDb($sql, [$idGruppo]);

			$lancio = $gruppo . "_" . $idLancio;
			$sql = "UPDATE WORK_RULES.CONTROLLI_LANCI_ANAG SET LANCIO = ? WHERE ID_LANCIO = ? ";
			$this->_db->updateDb($sql, [$lancio, $idLancio]);
			//	$this->_db->close_db();
			return $idLancio;
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}

	public function updateControlliGruppoAnagValido($ID_GRUPPO, $VALIDO)
	{
		try {
			$sql = "UPDATE WORK_RULES.CONTROLLI_ANAG_GRUPPO  set VALIDO = ? WHERE ID_GRUPPO = ? ";
			$this->_db->updateDb($sql, [$VALIDO, $ID_GRUPPO]);
			//echo $this->_db->getsqlQuery();
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}
	/**
	 * getDatiByExcel
	 *
	 * @return void
	 */
	public function getDatiByExcel($ID_GRUPPO)
	{

		$ret = [];
		if ($ID_GRUPPO) {
			$ArrayGruppo = $this->getGruppi($ID_GRUPPO);

			$GRUPPO = $ArrayGruppo[0]['GRUPPO'];
			if ($ID_GRUPPO) {
				$this->updateControlliGruppoAnagValido($ID_GRUPPO, 0);
				if ($xlsx = SimpleXLSX::parse($_FILES["fileToUpload"]["tmp_name"])) {
					$sheets = $xlsx->sheetNames();
					$oldMacroControllo = '';

					foreach ($sheets as $index => $SOTTO_GRUPPO) {
						echo "Reading sheet :" . $SOTTO_GRUPPO . "<br>";

						$firstrow = 1;
						$rowCount = 0;
						foreach ($xlsx->rows($index) as $r => $row) {
							if ($rowCount > 0) {
								$MacroControllo = ($row[0] != '') ? $oldMacroControllo = $row[0] : $oldMacroControllo;
								if ($MacroControllo && $row[1] && $row[2]) {
									//(SOCIETA, GRUPPO, ORDINAMENTO, CONTROLLO, DESCR, TIPO, TESTO,UTENTE)
									//$sql = strtoupper(str_replace('count(*)', '*', $row[2]));
									$sql = $row[2];
									$sql = strtoupper(str_replace(';', '', $sql));
									$sql = strtoupper(str_replace('"?"', "'?'", $sql));


									$TIPO = 'M';

									//$TIPO = (strpos(strtoupper($row[2]),'COUNT(*)')!== false)?'S':$TIPO;
									$TIPO = (strpos(strtoupper($row[2]), 'SELECT') !== false) ? 'S' : $TIPO;
									$TIPO = (strpos(strtoupper($row[2]), 'SUM') !== false) ? 'C' : $TIPO;
									$TIPO = ($row[3] == 'N') ? 'N' : $TIPO;
									$ORDINAMENTO = $firstrow++;
									$DESCR = $row[1];
									$UTENTE = $_SESSION['codname'];

									//[SOCIETA, GRUPPO, ORDINAMENTO, CONTROLLO, DESCR, TIPO, TESTO,UTENTE]

									$ret[] = [$ID_GRUPPO, $SOTTO_GRUPPO, $ORDINAMENTO, $MacroControllo, $DESCR, $TIPO, $sql, $UTENTE];
								}
							}
							$rowCount++;
						}
					}
				}
			}
		}

		return $ret;
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
			$this->removeControlli($dati[0][0]);
			foreach ($dati as $dato) {

				$sql =	"INSERT INTO WORK_RULES.CONTROLLI_ANAG_GRUPPO (ID_GRUPPO, SOTTO_GRUPPO, ORDINAMENTO, CONTROLLO, DESCR, TIPO, TESTO,UTENTE) VALUES (?, ?,?, ?, ?, ?, ?,?);";

				$last_id = $this->_db->insertDb($sql, $dato);
				if (!$last_id) {
					echo "<pre>";
					echo $this->_db->getsqlQuery();
					echo "</pre>";
				}
			}

			return $last_id;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}
	public function removeControlli($SOCIETA)
	{
		try {
			$sql = "DELETE FROM WORK_RULES.CONTROLLI_ANAG_SOC WHERE GRUPPO = ?";
			$ret = $this->_db->deleteDb($sql, array($SOCIETA));
			return $ret;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}

	public function getControlliAnag($idGruppo)
	{
		try {
			$sql = "select * from WORK_RULES.CONTROLLI_ANAG_GRUPPO WHERE ID_GRUPPO=? and VALIDO=1";
			$datiControlli = $this->_db->getArrayByQuery($sql, [$idGruppo]);
			return $datiControlli;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}

	public function insertControlliLancio($CONTROLLI_LANCI_GRUPPO)
	{
		try {
			$sql =	"INSERT INTO WORK_RULES.CONTROLLI_LANCI_GRUPPO (ID_CONTR, ID_LANCIO, ESITO, RISULTATO, NOTE, UTENTE) VALUES (?, ?,?, ?, ?, ?);";
			$last_id = $this->_db->insertDb($sql, $CONTROLLI_LANCI_GRUPPO);
			if (!$last_id) {
				echo "<pre>";
				echo $this->_db->getsqlQuery();
				echo "</pre>";
			}
			return $last_id;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}

	public function eseguiQuery($sql)
	{
		try {

			$ris = $this->_db->getArrayNoAssocByQuery($sql, []);
			return $ris[0][0];
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}

	/**
	 * creaRisultatiControlli
	 *
	 * @param  mixed $datiControlli
	 * @param  mixed $ID_LANCIO
	 * @return void
	 */
	public function creaRisultatiControlli($datiControlli, $ID_LANCIO)
	{

		ini_set('memory_limit', '1024M');
		//ini_set('memory_limit', '-1');

		$CONTROLLI_LANCI_GRUPPO = [];
		$nControlli = 0;
		foreach ($datiControlli as $controlli) {
			$ID_CONTR = $controlli['ID_CONTR'];
			$SQL = $controlli['TESTO'];
			$TIPO = $controlli['TIPO'];
			$SOTTO_GRUPPO = $controlli['SOTTO_GRUPPO'];
			$RISULTATO = 0;
			$ESITO = ($RISULTATO == 0) ? "OK" : "KO";
			$NOTE = "Risultati=" . $RISULTATO;
			$UTENTE = $_SESSION['codname'];
			$CONTROLLI_LANCI_GRUPPO[$nControlli++] = [$ID_CONTR, $ID_LANCIO, $ESITO, $RISULTATO, $NOTE, $UTENTE];
		}

		$sql = "DELETE FROM WORK_RULES.CONTROLLI_LANCI_GRUPPO where ID_LANCIO=?";
		$this->_db->deleteDb($sql, [$ID_LANCIO]);
		//$this->_db->close_db();
		foreach ($CONTROLLI_LANCI_GRUPPO  as $valControlli) {
			$sql =	"INSERT INTO WORK_RULES.CONTROLLI_LANCI_GRUPPO (ID_CONTR, ID_LANCIO, ESITO, RISULTATO, NOTE, UTENTE) VALUES (?, ?,?, ?, ?, ?);";
			$last_id = $this->_db->insertDb($sql, $valControlli);
			if (!$last_id) {
				echo "<pre>";
				echo $this->_db->getsqlQuery();
				echo "</pre>";
			}
		}
		return  $nControlli;
	}
}
