<?php

class checka_model
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
	 * getStatoElaborazioniDaIniziare
	 *
	 * @return void
	 */
	public function getStatoElaborazioniDaIniziare()
	{
		try {
			$sql = "WITH W_STATI(priorita, stato, descr) AS (values
(0  ,'Inizio-OK', 'Stato iniziale')
)
SELECT w.STATO, DESCR, NVL(CNT,0) CNT, (SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3) TOT, DECIMAL(NVL(CNT,0)/(SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3),5,2)*100 PERC
FROM W_STATI w
LEFT OUTER JOIN (SELECT STATO, COUNT(*) CNT FROM TASPCWRK.WORK_ELAB.PILOTA3 GROUP BY STATO ) p ON w.STATO = p.STATO
WHERE RIGHT(W.STATO,3)<>'Err' OR (RIGHT(W.STATO,3)='Err' AND CNT >0 )
ORDER BY PRIORITA;";
			$res = $this->_db->getArrayByQuery($sql, []);
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
	 * getStatoElaborazioniInElaborazione
	 *
	 * @return void
	 */
	public function getStatoElaborazioniInElaborazione()
	{
		try {
			$sql = "WITH W_STATI(priorita, stato, descr) AS (values
(2  ,'SIN-OK', 'P_POPOLA_SIN in elaborazione'),
(4  ,'MOV-OK', 'P_POPOLA_MOV_CONT in elaborazione'),
(6  ,'CPN-OK', 'P_POPOLA_CPN in elaborazione'),
(7  ,'AGG-SIN-OK', 'AGG_POST_SIN in elaborazione')
)
SELECT w.STATO, DESCR, NVL(CNT,0) CNT, (SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3) TOT, DECIMAL(NVL(CNT,0)/(SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3),5,2)*100 PERC
FROM W_STATI w
LEFT OUTER JOIN (SELECT STATO, COUNT(*) CNT FROM TASPCWRK.WORK_ELAB.PILOTA3 GROUP BY STATO ) p ON w.STATO = p.STATO
WHERE RIGHT(W.STATO,3)<>'Err' OR (RIGHT(W.STATO,3)='Err' AND CNT >0 )
ORDER BY PRIORITA;";
			$res = $this->_db->getArrayByQuery($sql, []);
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
	 * getStatoElaborazioniInErrore
	 *
	 * @return void
	 */
	public function getStatoElaborazioniInErrore()
	{
		try {
			$sql = "WITH W_STATI(priorita, stato, descr) AS (values
(2  ,'SIN-Err', 'P_POPOLA_SIN in errore'),
(4  ,'MOV-Err', 'P_POPOLA_MOV_CONT in errore'),
(6  ,'CPN-Err', 'P_POPOLA_CPN in errore'),
(8  ,'AGG-SIN-Err', 'AGG_POST_SIN in errore'),
(9  ,'AGG-MOV-Err', 'Stato finale in errore')
)
SELECT w.STATO, DESCR, NVL(CNT,0) CNT, (SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3) TOT, DECIMAL(NVL(CNT,0)/(SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3),5,2)*100 PERC
FROM W_STATI w
LEFT OUTER JOIN (SELECT STATO, COUNT(*) CNT FROM TASPCWRK.WORK_ELAB.PILOTA3 GROUP BY STATO ) p ON w.STATO = p.STATO
WHERE RIGHT(W.STATO,3)<>'Err' OR (RIGHT(W.STATO,3)='Err' AND CNT >0 )
ORDER BY PRIORITA;";
			$res = $this->_db->getArrayByQuery($sql, []);
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
	 * getElaborazioniInCorso
	 *
	 * @return void
	 */
	public function getStatoElaborazioniFiniti()
	{
		try {
			$sql = "WITH W_STATI(priorita, stato, descr) AS (values
(7  ,'AGG-MOV-OK', 'AGG_POST_SIN in elaboraziohne')
)
SELECT w.STATO, DESCR, NVL(CNT,0) CNT, (SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3) TOT, DECIMAL(NVL(CNT,0)/(SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3),5,2)*100 PERC
FROM W_STATI w
LEFT OUTER JOIN (SELECT STATO, COUNT(*) CNT FROM TASPCWRK.WORK_ELAB.PILOTA3 GROUP BY STATO ) p ON w.STATO = p.STATO
WHERE RIGHT(W.STATO,3)<>'Err' OR (RIGHT(W.STATO,3)='Err' AND CNT >0 )
ORDER BY PRIORITA;";
			$res = $this->_db->getArrayByQuery($sql, []);
			return $res;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			// $this->_db->close_db();
			throw $e;
		}
	}


	public function getElaborazioniInCorso()
	{
		try {
			$sql = "WITH W_STATI(priorita, stato, descr) AS (values
(2  ,'SIN-OK', 'P_POPOLA_SIN in elaborazione'),
(4  ,'MOV-OK', 'P_POPOLA_MOV_CONT in elaborazione'),
(6  ,'CPN-OK', 'P_POPOLA_CPN in elaborazione'),
(7  ,'AGG-SIN-OK', 'AGG_POST_SIN in elaborazione')
)
SELECT w.STATO, DESCR, NVL(CNT,0) CNT, (SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3) TOT, DECIMAL(NVL(CNT,0)/(SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3),5,2)*100 PERC
FROM W_STATI w
LEFT OUTER JOIN (SELECT STATO, COUNT(*) CNT FROM TASPCWRK.WORK_ELAB.PILOTA3 GROUP BY STATO ) p ON w.STATO = p.STATO
WHERE RIGHT(W.STATO,3)<>'Err' OR (RIGHT(W.STATO,3)='Err' AND CNT >0 )
ORDER BY PRIORITA;";
			$res = $this->_db->getArrayByQuery($sql, []);
			return $res;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			// $this->_db->close_db();
			throw $e;
		}
	}

	function getElaborazioniInErrore()
	{
		try {
			$sql = "WITH W_STATI(priorita, stato, descr) AS (values
(2  ,'SIN-Err', 'P_POPOLA_SIN in errore'),
(4  ,'MOV-Err', 'P_POPOLA_MOV_CONT in errore'),
(6  ,'CPN-Err', 'P_POPOLA_CPN in errore'),
(8  ,'AGG-SIN-Err', 'AGG_POST_SIN in errore'),
(9  ,'AGG-MOV-Err', 'Stato finale in errore')
)
SELECT w.STATO, DESCR, NVL(CNT,0) CNT, (SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3) TOT, DECIMAL(NVL(CNT,0)/(SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3),5,2)*100 PERC
FROM W_STATI w
LEFT OUTER JOIN (SELECT STATO, COUNT(*) CNT FROM TASPCWRK.WORK_ELAB.PILOTA3 GROUP BY STATO ) p ON w.STATO = p.STATO
WHERE RIGHT(W.STATO,3)<>'Err' OR (RIGHT(W.STATO,3)='Err' AND CNT >0 )
ORDER BY PRIORITA;";
			$res = $this->_db->getArrayByQuery($sql, []);
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