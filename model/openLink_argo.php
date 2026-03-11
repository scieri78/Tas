<?php
class openLink_argo_model extends openLink_model
{

	public  function  __construct() {
		parent::__construct();
	}
	/**
	 * getParametriIdProcess
	 *
	 * @param  mixed $IdProcess
	 * @return void
	 */
	public function getBestEstimates()
	{
		try {
			$Sql = "SELECT ID_RULE VALUE, NAME_RULE , DESC_RULE FROM IFRS17.MAP_RULE WHERE TIPO = 'BestEstimates' ORDER BY TIME_RULE DESC";
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
	 * getPalnDigitWave
	 *
	 * @param  mixed $IdProcess
	 * @return void
	 */
	public function getRiskAdjustament()
	{
		try {
			$SqlList = "SELECT ID_RULE VALUE, NAME_RULE , DESC_RULE FROM IFRS17.MAP_RULE WHERE TIPO = 'RiskAdjustament' ORDER BY TIME_RULE DESC";
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
//SELECT VALORE FROM WFS.PARAMETRI_ID_PROCESS WHERE AMBITO = 'ARGO' AND CAMPO = 'ID_RULE' AND ID_PROCESS = $IdProcess;
//select parametri by idprocess

	public function getParametriIdProcess($IdProcess)
	{
					try {
						$Sql = "SELECT VALORE FROM WFS.PARAMETRI_ID_PROCESS WHERE AMBITO = 'ARGO' AND CAMPO = 'ID_RULE' AND ID_PROCESS = ?";
						$res = $this->_db->getArrayByQuery($Sql, [$IdProcess]);
						return $res[0]['VALORE'];
					} catch (Throwable $e) {
						//  $this->_db->close_db();
						throw $e;
					} catch (Exception $e) {
						// $this->_db->close_db();
						throw $e;
					}
				}

//SELECT VALORE FROM WFS.PARAMETRI_ID_PROCESS WHERE AMBITO = 'ARGO' AND CAMPO = 'ID_RULE_RISK' AND ID_PROCESS = $IdProcess;
//select parametri by idprocess	

				public function getParametriIdProcessRisk($IdProcess)
				{
					try {
						$Sql = "SELECT VALORE FROM WFS.PARAMETRI_ID_PROCESS WHERE AMBITO = 'ARGO' AND CAMPO = 'ID_RULE_RISK' AND ID_PROCESS = ?";
						$res = $this->_db->getArrayByQuery($Sql, [$IdProcess]);
						return $res[0]['VALORE'];
					} catch (Throwable $e) {
						//  $this->_db->close_db();
						throw $e;
					} catch (Exception $e) {
						// $this->_db->close_db();
						throw $e;
					}
				}







	// remove parametri by idprocess
	public function removeParametri($IdProcess)
	{
		try {
			$Sql = "DELETE FROM WFS.PARAMETRI_ID_PROCESS WHERE ID_PROCESS = ? and AMBITO = 'ARGO'";
			$res = $this->_db->deleteDb($Sql, [$IdProcess]);
			return $res;
			} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			// $this->_db->close_db();
			throw $e;
		}
	}

	// funzione per insert parametri WFS.PARAMETRI_ID_PROCESS  INSERT INTO WFS.PARAMETRI_ID_PROCESS (ID_PROCESS, AMBITO, CAMPO, VALORE, "USER") VALUES($IdProcess, ‘ARGO', 'ID_RULE', '$IdRule', '$User');
	//INSERT INTO WFS.PARAMETRI_ID_PROCESS (ID_PROCESS, AMBITO, CAMPO, VALORE, "USER") VALUES($IdProcess, ‘ARGO', 'ID_RULE_RISK', '$IdRiskRule', '$User');
	/**
  * Inserts parameters for a process into the WFS.PARAMETRI_ID_PROCESS table
  * 
  * @param int $IdProcess The process identifier
  * @param string $IdRule The rule identifier
  * @param string $IdRiskRule The risk rule identifier
  * @return mixed Database insert result
  * @throws Throwable If database insertion fails
  */
 public function insertPatametri($IdProcess, $IdRule, $IdRiskRule)
	{
		try {
			$this->removeParametri($IdProcess);
			$User = $_SESSION['codname'];
			$Sql = "INSERT INTO WFS.PARAMETRI_ID_PROCESS (ID_PROCESS, AMBITO, CAMPO, VALORE, USER) VALUES(?, ?, ?, ?, ?)";

			$res = $this->_db->insertDb($Sql, [$IdProcess, 'ARGO', 'ID_RULE', $IdRule, $User]);
		//	$this->_db->printSql();

			$Sql = "INSERT INTO WFS.PARAMETRI_ID_PROCESS (ID_PROCESS, AMBITO, CAMPO, VALORE, USER) VALUES(?, ?, ?, ?, ?);";

			$res = $this->_db->insertDb($Sql, [$IdProcess, 'ARGO', 'ID_RULE_RISK', $IdRiskRule, $User]);
		//	$this->_db->printSql();

			return $res;
		} catch (Throwable $e) {
			//$this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			// $this->_db->close_db();
			throw $e;
		}
	}

	// funzione per validare legame
	function ValidaLegame()
	{
		try {
			foreach ($_POST as $key => $value) {
				${$key} = $value;
			}
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
				],
			];
			// db2_bind_param($stmt, 1, "IdWorkFlow", DB2_PARAM_IN);
			// db2_bind_param($stmt, 2, "IdProcess", DB2_PARAM_IN);
			// db2_bind_param($stmt, 3, "OnIdLegame", DB2_PARAM_IN);
			// db2_bind_param($stmt, 4, "User", DB2_PARAM_IN);
			// db2_bind_param($stmt, 5, "Errore", DB2_PARAM_OUT);
			// db2_bind_param($stmt, 6, "Note", DB2_PARAM_OUT);
			$res = $this->_db->callDb($CallPlSql, $values);
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			// $this->_db->close_db();
			throw $e;
		}
	}
}
