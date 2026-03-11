<?php


class girorias_model
{
	// set datagiroris config for mysql
	// open mysql data giroris
	private $_db;

	/**
	 * {@inheritDoc}
	 * @see db_driver::__construct()
	 */
	public function __construct()
	{
		$this->_db = new db_driver();
	}

	function getGiroRIASViva($IdProcess, $Tipo)
	{
		try {
			$sql = "SELECT 
		ID_FILE ,
		LOAD_DATE , 
		CONS_DATE , 
		LAST_CONS , 
		SIZE_FILE , 
		NUM_ROWS , 
		INPUT_FILE , 
		ARCHIVE , 
		NOTE , 
		TAGS 
	FROM 
		ENTRY_DATA.N_INPUT_FILES_RIASS
	WHERE 1=1
		AND ( ESER_ESAME, MESE_ESAME) IN  ( SELECT ESER_ESAME, MESE_ESAME FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess ) 
		AND NOTE = '$Tipo'
		ORDER BY LOAD_DATE DESC";


			$res = $this->_db->getArrayByQuery($sql, []);
			//$this->_db->printSql();
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
	 * @param  mixed $IdProcess
	 * @param  mixed $Tipo
	 * @return void
	 */
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
