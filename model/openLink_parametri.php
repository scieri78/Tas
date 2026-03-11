<?php

class openLink_parametri_model extends openLink_model
{

	public  function  __construct()
	{
		parent::__construct();
	}

	//get parametri partendo dalla tabella link trovo il gruppo in link_parametri_gruppo per poi prendere il parametri in link_parametri	
	/**
	 * getParametriGruppo
	 *
	 * @param  mixed $id_link
	 * @param  mixed $id_workflow
	 * @return void
	 */
	public function getParametriGruppo($id_link, $id_workflow,$IdProcess)
	{
		try {
			$Sql = "SELECT
					lpg.LABEL,
					lp.*
					FROM
						WFS.LINKS l
					JOIN
						WFS.LINK_PARAMETRI_GRUPPO lpg
					ON  l.ID_WORKFLOW   = lpg.ID_WORKFLOW
					AND l.ID_PAR_GRUPPO = lpg.ID_PAR_GRUPPO
					JOIN
						WFS.LINK_PARAMETRI_LEGAME lpl
					ON lpg.ID_PAR_GRUPPO = lpl.ID_PAR_GRUPPO
					JOIN
						WFS.LINK_PARAMETRI lp
					ON lpl.ID_PAR = lp.ID_PAR
					WHERE
						l.ID_LINK = ?
						AND l.ID_WORKFLOW = ?
						ORDER BY lpl.ORD;
					";
			$res = $this->_db->getArrayByQuery($Sql, [$id_link,	$id_workflow]);
			foreach ($res as $key => $value) {
				if ($value['SELECT'] != "") {
					$sql = str_replace("%ID_PROCESS%",$IdProcess,$value['SELECT']);
					$res[$key]['OPTIONS'] = $this->getquery($sql);
					/*$res[$key]['OPTIONS'] = array_map(function ($item) {
						return [
							'VALUE' => $item['VALUE'],
							'NAME_RULE' => $item['NAME_RULE'],
							'DEFAULT' => $item['DEFAULT']

						];
					}, $res[$key]['ret']);*/
				}
			}
			//print_r($res);
			return $res;
		} catch (Throwable $e) {
		}
	}

	/**
	 * getquery
	 *
	 * @param  mixed $sql
	 * @return void
	 */
	public function getquery($sql)
	{
		try {
			$res = $this->_db->getArrayByQuery($sql, []);
		//	$this->_db->printSql();
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
	 * @param  mixed $idLink
	 * @return void
	 */
	public function getParametriIdProcess($IdProcess, $idLink)
	{
		try {
			//$ambito = str_replace(' ', '', $nomeFlusso.$NameDip);
			$Sql = "SELECT VALORE,CAMPO FROM WFS.PARAMETRI_ID_PROCESS WHERE ID_LINK = ? AND ID_PROCESS = ?";
			$res = $this->_db->getArrayByQuery($Sql, [$idLink, $IdProcess]);
			$ret = [];
			foreach ($res as $key => $value) {
				$ret[$value['CAMPO']] = $value['VALORE'];
			}
			//$this->_db->printSql();
			//$this->_db->error_message("ret",$ret);						
			return $ret;
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
	/**
	 * removeParametri
	 *
	 * @param  mixed $IdProcess
	 * @return void
	 */
	public function removeParametri($IdProcess, $id_link)
	{
		try {
			$Sql = "DELETE FROM WFS.PARAMETRI_ID_PROCESS WHERE ID_PROCESS = ? and ID_LINK = ?";
			$res = $this->_db->deleteDb($Sql, [$IdProcess, $id_link]);
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
	public function insertPatametri($IdProcess, $nomeFlusso, $NameDip, $id_link)
	{
		try {
			$this->removeParametri($IdProcess, $id_link);
			$User = $_SESSION['codname'];
			echo "<pre>";
			print_r($_POST);
			echo "</pre>";
			foreach ($_POST as $k => $v) {
				// se $k contiene il valore "PAR_" allora inserisci parametro $k senza "_PAR"
				if (strpos($k, 'PAR_') !== false) {
					$campo = str_replace('PAR_', '', $k);
					$tipo = $_POST['TIPO_' . $campo];
					if ($tipo == "checkbox") {
						foreach ($v as $kc => $cv) {
							$Sql = "INSERT INTO WFS.PARAMETRI_ID_PROCESS (ID_PROCESS, AMBITO, CAMPO, VALORE, USER,ID_LINK) VALUES(?, ?, ?, ?, ?,?)";
							$res = $this->_db->insertDb($Sql, [$IdProcess, $ambito, $campo . "_" . $kc, $cv, $User, $id_link]);
						}
					} else {
						//ambito = nomeflusso + Nomedip senza spazzi
						$ambito = str_replace(' ', '', $nomeFlusso . $NameDip);
						$Sql = "INSERT INTO WFS.PARAMETRI_ID_PROCESS (ID_PROCESS, AMBITO, CAMPO, VALORE, USER,ID_LINK) VALUES(?, ?, ?, ?, ?,?)";
						$res = $this->_db->insertDb($Sql, [$IdProcess, $ambito, $campo, $v, $User, $id_link]);
					}
					//	$this->_db->printSql();
				}
			}


			//	$res = $this->_db->insertDb($Sql, [$IdProcess, 'ARGO', 'ID_RULE', $IdRule, $User]);
			//	$this->_db->printSql();

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


