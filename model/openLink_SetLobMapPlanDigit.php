<?php

class openLink_SetLobMapPlanDigit_model
{
	// set database config for mysql
	// open mysql data base
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
	 * getEsitoValidato
	 *
	 * @param  mixed $ID_PROCESS
	 * @param  mixed $ID_LEGAME
	 * @return void
	 */
	public function getEsitoValidato($ID_PROCESS, $ID_LEGAME)
	{
		try {
			$sql = "SELECT count(*) CNT FROM WFS.ULTIMO_STATO WHERE (ID_FLU, TIPO, ID_DIP) IN
					( SELECT ID_FLU, TIPO, ID_DIP FROM WFS.LEGAME_FLUSSI WHERE ID_LEGAME = ? )
					AND ID_PROCESS = ?
					AND ESITO = 'F'";
			
			$ret = $this->_db->getArrayByQuery($sql, [$ID_LEGAME,$ID_PROCESS]);
		//	$this->_db->printSql();
			$res = $ret[0]['CNT'];
			return $res;
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * insertPlanDigit
	 *
	 * @return void
	 */
	function insertPlanDigit()
	{
		foreach ($_POST as $key => $value) {
			${$key} = $value;
		}


		if ($Action == "SetLobMapPlanDigit") {

			$Ambito = "SETLOBMAPPLANDIGIT";

			if ($oldAnno != $SelAnno) {
				$Campo = "Anno";
				$Error = null;
				$Note = null;
				$CallPlSql = 'CALL WFS.K_WFS.SetParametriIdProcess(?, ?, ?, ?, ?, ?, ?)';
				//  $stmt = db2_prepare($conn, $Sql);
				$values = [
					[
						'name' => 'IdProcess',
						'value' => $IdProcess,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'Ambito',
						'value' => $Ambito,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'Campo',
						'value' => $Campo,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'SelAnno',
						'value' => $SelAnno,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'User',
						'value' => $_SESSION['codname'],
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'Errore',
						'value' => $Errore,
						'type' => DB2_PARAM_OUT
					],
					[
						'name' => 'Note',
						'value' => $Note,
						'type' => DB2_PARAM_OUT
					]
				];
				$ret = @$this->_db->callDb($CallPlSql, $values);
			}

			if ($oldWave != $SelWave) {
				$Campo = "Wave";
				$Error = null;
				$Note = null;
				$CallPlSql = 'CALL WFS.K_WFS.SetParametriIdProcess(?, ?, ?, ?, ?, ?, ?)';
				//  $stmt = db2_prepare($conn, $Sql);
				$values = [
					[
						'name' => 'IdProcess',
						'value' => $IdProcess,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'Ambito',
						'value' => $Ambito,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'Campo',
						'value' => $Campo,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'SelWave',
						'value' => $SelWave,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'User',
						'value' => $_SESSION['codname'],
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'Errore',
						'value' => $Errore,
						'type' => DB2_PARAM_OUT
					],
					[
						'name' => 'Note',
						'value' => $Note,
						'type' => DB2_PARAM_OUT
					]
				];
				$ret = @$this->_db->callDb($CallPlSql, $values);
			}

			$Errore = 0;
			$Note = "";
			$CallPlSql = 'CALL WFS.K_WFS.ValidaLegame(?, ?, ?, ?, ?, ? )';
			$values = [
				[
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
				],
				[
					'name' => 'IdProcess',
					'value' => $IdProcess,
					'type' => DB2_PARAM_IN
				],
				[
					'name' => 'IdLegame',
					'value' => $IdLegame,
					'type' => DB2_PARAM_IN
				],
				[
					'name' => 'User',
					'value' => $_SESSION['codname'],
					'type' => DB2_PARAM_IN
				],
				[
					'name' => 'Errore',
					'value' => $Errore,
					'type' => DB2_PARAM_OUT
				],
				[
					'name' => 'Note',
					'value' => $Note,
					'type' => DB2_PARAM_OUT
				]
			];
			$ret = @$this->_db->callDb($CallPlSql, $values);
		}
	}



public function getEserEsame()

	{
		try {
			$Sql = "SELECT DISTINCT ESER_ESAME FROM MVBS.PLAN_DIGIT";

			$res = $this->_db->getArrayByQuery($Sql, []);
			return $res;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			// $this->_db->close_db();
			throw $e;
		}
	}

	/**
	 * getParametriIdProcess
	 *
	 * @param  mixed $IdProcess
	 * @return void
	 */
	public function getParametriIdProcess($IdProcess)
	{
		try {
			$Sql = "SELECT CAMPO, VALORE FROM WFS.PARAMETRI_ID_PROCESS WHERE ID_PROCESS = ? and AMBITO = ?";

			$res = $this->_db->getArrayByQuery($Sql, [$IdProcess,'SETLOBMAPPLANDIGIT']);
			return $res;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			// $this->_db->close_db();
			throw $e;
		}
	}

	/**
	 * getPalnDigitWave
	 *
	 * @param  mixed $IdProcess
	 * @return void
	 */
	public function getPlanDigitWave($ESER_ESAME)
	{
		try {
		   //$SqlList = "SELECT DISTINCT WAVE FROM MVBS.PLAN_DIGIT WHERE ESER_ESAME <= SUBSTRING($IdProcess,1,4) ORDER BY WAVE";
			$SqlList = "SELECT DISTINCT p.WAVE, d.DESCR
						FROM MVBS.PLAN_DIGIT p
						LEFT JOIN MVBS.PLAN_DIGIT_DESCR d 
						ON p.ESER_ESAME = d.ESER_ESAME 
						AND p.WAVE = d.WAVE
						WHERE p.ESER_ESAME = ?
						ORDER BY p.WAVE";

			$res = $this->_db->getArrayByQuery($SqlList, [$ESER_ESAME]);
			return $res;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			// $this->_db->close_db();
			throw $e;
		}
	}
}
