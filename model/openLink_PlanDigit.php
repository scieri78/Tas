<?php

class openLink_PlanDigit_model
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


		if ($Action == "SalvaPlanDigit") {

			$Ambito = "PLANDIGIT";

			if ($oldTipo != $SelTipo) {
				$Campo = "Tipo";
				$Error = null;
				$Note = null;

				$CallPlSql = 'CALL WFS.K_WFS.SetParametriIdProcess(?, ?, ?, ?, ?, ?, ?)';
				//   $stmt = db2_prepare($conn, $Sql);
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
						'name' => 'SelTipo',
						'value' => $SelTipo,
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
				/*  db2_bind_param($stmt, 1,  "IdProcess" , DB2_PARAM_IN);
					db2_bind_param($stmt, 2,  "Ambito"    , DB2_PARAM_IN);
					db2_bind_param($stmt, 3,  "Campo"     , DB2_PARAM_IN);
					db2_bind_param($stmt, 4,  "SelTipo"   , DB2_PARAM_IN);
					db2_bind_param($stmt, 5,  "User"      , DB2_PARAM_IN);
					db2_bind_param($stmt, 6,  "Error"     , DB2_PARAM_OUT);
					db2_bind_param($stmt, 7,  "Note"      , DB2_PARAM_OUT);*/

				/*  $result=db2_execute($stmt);
          if ( ! $result ){
             echo "ERROR DB2 SetParametriIdProcess:".db2_stmt_errormsg();
          }*/
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
				//$this->_db->error_message("Wave",$values);
				$ret = @$this->_db->callDb($CallPlSql, $values);
			
			}

			if ($oldDesc != $Desc) {
				$Campo = "Desc";
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
						'name' => 'Desc',
						'value' => $Desc,
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
				//$this->_db->error_message("Wave",$values);
				$ret = @$this->_db->callDb($CallPlSql, $values);
			
			}

			$Errore = 0;
			$Note = "";
			$CallPlSql = 'CALL WFS.K_WFS.ValidaLegame(?, ?, ?, ?, ?, ? )';
			/* $stmt = db2_prepare($conn, $CallP);
        db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
        db2_bind_param($stmt, 2, "IdProcess"   , DB2_PARAM_IN);
        db2_bind_param($stmt, 3, "IdLegame"    , DB2_PARAM_IN);
        db2_bind_param($stmt, 4, "User"        , DB2_PARAM_IN);
        db2_bind_param($stmt, 5, "Errore"      , DB2_PARAM_OUT);
        db2_bind_param($stmt, 6, "Note"        , DB2_PARAM_OUT);
        $res=db2_execute($stmt);*/


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
			//$this->_db->error_message('ValidaLegame values',$values);
			$ret = @$this->_db->callDb($CallPlSql, $values);
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
			$Sql = "SELECT CAMPO, VALORE FROM WFS.PARAMETRI_ID_PROCESS WHERE ID_PROCESS = ? and ambito = 'PLANDIGIT' ";

			$res = $this->_db->getArrayByQuery($Sql, [$IdProcess]);
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
	public function getPalnDigitWave($IdProcess)
	{
		try {

			$SqlList = "SELECT
	DISTINCT p.WAVE,
	d.DESCR
FROM
	MVBS.PLAN_DIGIT p
JOIN MVBS.PLAN_DIGIT_DESCR d ON
	p.ESER_ESAME = d.ESER_ESAME
	AND p.WAVE = d.WAVE
WHERE
	p.ESER_ESAME = ( 
	SELECT MAX(ESER_ESAME) FROM MVBS.PLAN_DIGIT WHERE ESER_ESAME <= SUBSTRING($IdProcess, 1, 4) 
	)
ORDER BY
	p.WAVE";

			$res = $this->_db->getArrayByQuery($SqlList, []);
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
