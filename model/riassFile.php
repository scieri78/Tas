<?php


class riassFile_model
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
	 * getriassFileFile
	 *
	 * @param  mixed $SelM
	 * @return void
	 */
	public function getRiassFile($SelM)
	{
		try {
			$sql = "SELECT ESER_ESAME, 
			MESE_ESAME, 
			TAGS, 
			START_TIME, 
			END_TIME, 
			timestampdiff(2,NVL(END_TIME,CURRENT_TIMESTAMP)-START_TIME) DIFF,
			VARIABLES
			FROM MVBS.V_RIASS_FILE
			WHERE ESER_ESAME||LPAD(MESE_ESAME,2,0) >= YEAR(DATE( CURRENT_TIMESTAMP - $SelM MONTH))||LPAD(MONTH(DATE( CURRENT_TIMESTAMP - $SelM MONTH)),2,0)
			ORDER BY ESER_ESAME desc, MESE_ESAME desc, START_TIME DESC;
				";
			$res = $this->_db->getArrayByQuery($sql);

			return $res;
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}
	/**
	 * getInputFiles
	 *
	 * @param  mixed $SelM
	 * @return void
	 */
	public function getInputFiles($SelM)
	{
		try {
			$sql = "SELECT * FROM (
				SELECT 
				  (SELECT ESER_ESAME FROM TASPCWRK.WORK_CORE.CORE_SH c WHERE c.ID_RUN_SH = I.ID_RUN_SH) ESER_ESAME
				 ,(SELECT MESE_ESAME FROM TASPCWRK.WORK_CORE.CORE_SH c WHERE c.ID_RUN_SH = I.ID_RUN_SH) MESE_ESAME
				 ,ID_RUN_SQL ID_FILE
				 ,LOAD_DATE
				 ,LAST_CONS
				 ,NUM_ROWS
				 ,INPUT_FILE
				 ,NOTE
			  FROM  
			  TASPCWRK.WORK_RULES.INPUT_FILES I
			  WHERE ID_SH = (SELECT ID_SH FROM TASPCWRK.WORK_CORE.CORE_SH_ANAG WHERE SHELL = 'RIASS_Carica.sh')
			  )
			  WHERE ESER_ESAME||LPAD(MESE_ESAME,2,0) >= YEAR(DATE( CURRENT_TIMESTAMP - $SelM MONTH))||LPAD(MONTH(DATE( CURRENT_TIMESTAMP - $SelM MONTH)),2,0)
			  ORDER BY LOAD_DATE DESC
			  ;";
			$res = $this->_db->getArrayByQuery($sql);

			return $res;
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}
	/**
	 * getInputFiles
	 *
	 * @param  mixed $SelM
	 * @return void
	 */
	public function getNameInputFiles($IdSql)
	{
		try {
			$sql = "SELECT INPUT_FILE FROM TASPCWRK.WORK_RULES.INPUT_FILES WHERE ID_RUN_SQL = $IdSql ";

			$res = $this->_db->getArrayByQuery($sql);
			foreach ($res as $rw) {
				$INPUT_FILE = $rw['INPUT_FILE'];
			  }
		return $INPUT_FILE;
			
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}
}
