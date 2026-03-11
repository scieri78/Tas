<?php


class status_model
{
	// set datastatus config for mysql
	// open mysql data status
	private $_db;

	/**
	 * {@inheritDoc}
	 * @see db_driver::__construct()
	 */
	public function __construct()
	{
		$this->_db = new db_driver();
	}

	public function getStatus()
	{
		try {
			$sql = "select 'WORK_ADD_CUBO' TAB, ESER_ESAME, MESE_ESAME, count(*) CNT FROM LOSS_RESERVING.WORK_ADD_CUBO GROUP BY ESER_ESAME, MESE_ESAME  UNION ALL
					select 'WORK_CU_CONV ' TAB, ESER_ESAME, MESE_ESAME, count(*) CNT FROM LOSS_RESERVING.WORK_CU_CONV GROUP BY ESER_ESAME, MESE_ESAME  UNION ALL
					select 'WORK_CU_CONV_CONT' TAB, ESER_ESAME, MESE_ESAME, count(*) CNT FROM LOSS_RESERVING.WORK_CU_CONV_CONT GROUP BY ESER_ESAME, MESE_ESAME  UNION ALL
					select 'WORK_CU_CPN' TAB, ESER_ESAME, MESE_ESAME, count(*) CNT FROM LOSS_RESERVING.WORK_CU_CPN GROUP BY ESER_ESAME, MESE_ESAME  UNION ALL
					select 'WORK_CU_CPN_CONT ' TAB, ESER_ESAME, MESE_ESAME, count(*) CNT FROM LOSS_RESERVING.WORK_CU_CPN_CONT GROUP BY ESER_ESAME, MESE_ESAME  UNION ALL
					select 'WORK_CU_SIN' TAB, ESER_ESAME, MESE_ESAME, count(*) CNT FROM LOSS_RESERVING.WORK_CU_SIN GROUP BY ESER_ESAME, MESE_ESAME  UNION ALL
					select 'WORK_CU_SIN_CONT ' TAB, ESER_ESAME, MESE_ESAME, count(*) CNT FROM LOSS_RESERVING.WORK_CU_SIN_CONT GROUP BY ESER_ESAME, MESE_ESAME  UNION ALL
					select 'WORK_CUBO_CU_VERS' TAB, ESER_ESAME, MESE_ESAME, count(*) CNT FROM LOSS_RESERVING.WORK_CUBO_CU_VERS GROUP BY ESER_ESAME, MESE_ESAME  UNION ALL
					select 'WORK_CUBO_TECNICO' TAB, ESER_ESAME, MESE_ESAME, count(*) CNT FROM LOSS_RESERVING.WORK_CUBO_TECNICO GROUP BY ESER_ESAME, MESE_ESAME ORDER BY ESER_ESAME, MESE_ESAME";
			$res = $this->_db->getArrayByQuery($sql);

			return $res;
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}



}
