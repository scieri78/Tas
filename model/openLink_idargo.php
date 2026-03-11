<?php

class openLink_idargo_model
{
	// set dataidargo config for mysql
	// open mysql data idargo
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
	 * getArgo
	 *
	 * @param  mixed $IdProcess
	 * @param  mixed $COMPAGNIA
	 * @return void
	 */
	public function getArgo($ID_PROCESS, $COMPAGNIA = '')
	{
		try {
			$sql = "SELECT ESER_ESAME, MESE_ESAME, COMPAGNIA, ID_REMOTO, NAME, DESCR ,ID_PROCESS
						FROM WORK_RULES.ID_TAS_ARGO 
						WHERE 1=1
						AND ID_PROCESS = ?
						AND NAME != ?";
						
			if ($COMPAGNIA != '') {
				$sql .= " AND COMPAGNIA ='" . $COMPAGNIA . "'";
			}
			$res = $this->_db->getArrayByQuery($sql, [$ID_PROCESS,"CHIUSURA TAS"]);
			//	$this->_db->printSql();
			return $res;
		} catch (Exception $e) {
			throw $e;
		}
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
	 * updateArgo
	 *
	 * @param  mixed $NewIDArgo
	 * @param  mixed $NewNome
	 * @param  mixed $NewDescr
	 * @param  mixed $IdProcess
	 * @param  mixed $SelCompagnia
	 * @return void
	 */
	public function updateArgo($ID_REMOTO, $NAME, $DESCR, $ID_PROCESS, $COMPAGNIA)
	{
		try {

			$sql =	"UPDATE WORK_RULES.ID_TAS_ARGO  
					SET 
					ID_REMOTO= ?
					,NAME= ? 
					,DESCR= ?
					WHERE  ID_PROCESS = ? 
					AND COMPAGNIA = ?";
			$ret = $this->_db->updateDb($sql, [$ID_REMOTO, $NAME, $DESCR, $ID_PROCESS, $COMPAGNIA]);
			$this->updatePatametri($ID_PROCESS, $COMPAGNIA, $ID_REMOTO, "Set Id Remoto");
			return $ret;
		} catch (Exception $e) {
			throw $e;
		}
	}



	public function updatePatametri($IdProcess, $campo, $VALORE, $ambito)
	{
		try {
			
							$Sql = "UPDATE WFS.PARAMETRI_ID_PROCESS SET VALORE =?
							where
							 ID_PROCESS=? 
							 and AMBITO=? 
							 and CAMPO =?
							 ";
							$res = $this->_db->updateDb($Sql, [$VALORE, $IdProcess, $ambito, $campo]);
						
					
					//	$this->_db->printSql();
				
			


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



	public function removeParametri($IdProcess, $id_link)
	{
		try {
			$Sql = "DELETE FROM WFS.PARAMETRI_ID_PROCESS WHERE ID_PROCESS = ? and AMBITO = ? and AMBITO = ?";
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


	
	/**
	 * ValidaLegame
	 *
	 * @return void
	 */
	public function ValidaLegame()
	{

		try {
			foreach ($_POST as $key => $value) {
				${$key} = $value;
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
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getCodaRichieste
	 *
	 * @param  mixed $IdProcess
	 * @return void
	 */
	public function getCodaRichieste($IdProcess)
	{
		try {
			$sql = "SELECT count(*) CNT from wFS.CODA_RICHIESTE WHERE ID_PROCESS = ?";
		
			$ret = $this->_db->getArrayByQuery($sql, [$IdProcess]);
		//	$this->_db->printSql();
			$res = $ret[0]['CNT'];
			return $res;
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * callElaborazioniPossibili
	 *
	 * @return void
	 */
	public function callElaborazioniPossibili()
	{
		try {

			$PRGDIR = $_SESSION['PRGDIR'];
			$SERVER = $_SESSION['SERVER'];
			$SSHUSR = $_SESSION['SSHUSR'];
			$DIRSH = $_SESSION['DIRSH'];

			foreach ($_POST as $key => $value) {
				${$key} = $value;
			}

			$Errore = 0;
			$Note = "";
			$User = $_SESSION["codname"];

			if ($IdProcess != "" && $IdWorkFlow != "") {
				$CallPlSql = 'CALL WFS.K_WFS.ElaborazioniPossibili(?, ?, ?, ?, ? )';
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
						'name' => 'User',
						'value' => $User,
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
				// db2_bind_param($stmt, 3, "User", DB2_PARAM_IN);
				// db2_bind_param($stmt, 4, "Errore", DB2_PARAM_OUT);
				// db2_bind_param($stmt, 5, "Note", DB2_PARAM_OUT);
				$res = $this->_db->callDb($CallPlSql, $values);

				$ErrSt = 0;

			
				if (!$res['Errore']) {
			
					//   echo "PLSQL Procedure Calling Error $Errore: " . $Note;
					$ErrSt = 1;
				}

				if ($this->getCodaRichieste($IdProces)) {
					shell_exec("sh $PRGDIR/AvviaElabServer.sh '${SSHUSR}' '${SERVER}' '${DIRSH}' ${IdWorkFlow} ${IdProcess} 2>&1 >> $PRGDIR/AvviaElabServer.log &");
				}
			}
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}









}
