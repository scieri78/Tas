<?php


class lanci_model
{
	// set datalanci config for mysql
	// open mysql data lanci
	private $_db;

	/**
	 * {@inheritDoc}
	 * @see db_driver::__construct()
	 */
	public function __construct()
	{
		$this->_db = new db_driver();
	}

	public function getlanci()
	{
		try {
			$sql = "SELECT ID_INFO, DATA_INFO, COMPAGNIA, ANNO, MESE, LINEOFBUSINESS, VERSIONEUTENTE, DESCRIZIONE,
						VERSIONENUOVA, FILEOUTPUTUFFICIALE1, FILEOUTPUTUFFICIALE2, JOBID, JOBOWNER, STATUS,
						WAITTIME, RIPETIZIONE, SHELL_NAME, SHELL_START, SHELL_END, SHELL_STATUS, MESSAGE
						FROM UNIFY.V_LOSSRES_STORICO
						WHERE DATA_INFO >= CURRENT_DATE - 3 MONTHS
						ORDER BY DATA_INFO DESC";
			$res = $this->_db->getArrayByQuery($sql);

			return $res;
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}



}
