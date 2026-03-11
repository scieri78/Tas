<?php

class openLink_ForzaDip_model
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
	 * getListaUtentiForzaDip
	 *
	 * @return void
	 */
	function getListaUtentiForzaDip()
	{
		try {
			$ret = [];
			$sql = "SELECT NOMINATIVO, USERNAME FROM WEB.TAS_UTENTI";
			$res = $this->_db->getArrayByQuery($sql, []);

			foreach ($res as $row) {
				$TNom = $row['NOMINATIVO'];
				$TUserN = $row['USERNAME'];

				$ret[] = [$TNom, $TUserN];
				// $ListIdStato = $ListIdStato . "," . $Id;
			}


			return $ret;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			// $this->_db->close_db();
			throw $e;
		}
	}



	/**
	 * getLegamiForzaDip
	 *
	 * @param  mixed $IdProcess
	 * @param  mixed $IdWorkFlow
	 * @param  mixed $ProcMeseEsame
	 * @param  mixed $Uk
	 * @return void
	 */
	function getLegamiForzaDip($IdProcess, $IdWorkFlow, $ProcMeseEsame, $Uk)
	{
		try {

			$sql = "SELECT 
			(   SELECT FLUSSO FROM  WFS.FLUSSI  WHERE ID_FLU = L.ID_FLU ) FLUSSO,
		ID_LEGAME,PRIORITA,L.TIPO,L.ID_DIP,
		CASE L.TIPO 
				WHEN 'F' THEN
				( SELECT FLUSSO FROM WFS.FLUSSI WHERE ID_FLU = L.ID_DIP )
				WHEN 'C' THEN
				( SELECT CARICAMENTO FROM WFS.CARICAMENTI WHERE ID_CAR = L.ID_DIP )
				WHEN 'L' THEN
				( SELECT LINK FROM WFS.LINKS WHERE ID_LINK = L.ID_DIP )
				WHEN 'E' THEN
				( SELECT ELABORAZIONE FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )
				WHEN 'V' THEN
				( SELECT VALIDAZIONE FROM WFS.VALIDAZIONI WHERE ID_VAL = L.ID_DIP )
			END AS DIPENDENZA
			,CASE L.TIPO 
				WHEN 'F' THEN
				( SELECT DESCR FROM WFS.FLUSSI WHERE ID_FLU = L.ID_DIP )
				WHEN 'C' THEN
				( SELECT DESCR FROM WFS.CARICAMENTI WHERE ID_CAR = L.ID_DIP )
				WHEN 'L' THEN
				( SELECT DESCR FROM WFS.LINKS WHERE ID_LINK = L.ID_DIP )
				WHEN 'E' THEN
				( SELECT DESCR FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )
				WHEN 'V' THEN
				( SELECT DESCR FROM WFS.VALIDAZIONI WHERE ID_VAL = L.ID_DIP )
			END AS DESCR
			,CASE L.TIPO 
				WHEN 'F' THEN
				null
				WHEN 'C' THEN
				( SELECT NOME_INPUT FROM WFS.CARICAMENTI WHERE ID_CAR = L.ID_DIP )
				WHEN 'L' THEN
				( SELECT TARGET FROM WFS.LINKS WHERE ID_LINK = L.ID_DIP )
				WHEN 'E' THEN
				null
				WHEN 'V' THEN
				null
			END AS TARGET
			,CASE L.TIPO 
				WHEN 'F' THEN
				null
				WHEN 'C' THEN
				null
				WHEN 'L' THEN
				( SELECT TIPO FROM WFS.LINKS WHERE ID_LINK = L.ID_DIP )
				WHEN 'E' THEN
				null
				WHEN 'V' THEN
				null
			END AS LINK_TIPO    
			,CASE L.TIPO 
				WHEN 'F' THEN
				null
				WHEN 'C' THEN
				null
				WHEN 'L' THEN
				null
				WHEN 'E' THEN
				( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )
				WHEN 'V' THEN
				null
			END AS ELAB_IDSH        
			,CASE L.TIPO 
				WHEN 'F' THEN
				null
				WHEN 'C' THEN
				null
				WHEN 'L' THEN
				null
				WHEN 'E' THEN
				( SELECT TAGS FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )
				WHEN 'V' THEN
				null
			END AS ELAB_TAGS    
			,CASE L.TIPO 
				WHEN 'F' THEN
				null
				WHEN 'C' THEN
				null
				WHEN 'L' THEN
				null
				WHEN 'E' THEN
				( SELECT READONLY FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )
				WHEN 'V' THEN
				null
			END AS READONLY     
			,CASE L.TIPO 
				WHEN 'F' THEN
				null
				WHEN 'C' THEN
				null
				WHEN 'L' THEN
				null
				WHEN 'E' THEN
				( SELECT BLOCKWFS FROM WORK_CORE.CORE_SH_ANAG WHERE ID_SH IN ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )  )
				WHEN 'V' THEN
				null
			END AS BLOCKWFS 
			,CASE L.TIPO 
				WHEN 'F' THEN
				null
				WHEN 'C' THEN
				null
				WHEN 'L' THEN
				null
				WHEN 'E' THEN
				null
				WHEN 'V' THEN
				( SELECT EXTERNAL FROM WFS.VALIDAZIONI V WHERE V.ID_VAL = L.ID_DIP )
			END AS EXTERNAL     
			,US.UTENTE
			,TO_CHAR(US.INIZIO,'YYYY-MM-DD HH24:MI:SS') INIZIO
			,TO_CHAR(US.FINE,'YYYY-MM-DD HH24:MI:SS')   FINE
			,timestampdiff(2,NVL(US.FINE,CURRENT_TIMESTAMP)-US.INIZIO) DIFF 
			,NVL(US.ESITO,'N') AS ESITO
			,US.NOTE
			,US.LOG
			,US.FILE   
			,( SELECT count(*) FROM WFS.CODA_RICHIESTE WHERE ID_WORKFLOW = L.ID_WORKFLOW AND ID_PROCESS  = $IdProcess AND ID_FLU = L.ID_FLU AND ID_DIP = L.ID_DIP AND TIPO = L.TIPO ) CODA
			,( SELECT count(*) FROM WFS.AUTORIZZAZIONI WHERE ID_WORKFLOW = L.ID_WORKFLOW AND ID_FLUSSO = L.ID_FLU 
				AND ID_GRUPPO IN ( SELECT ID_GRUPPO FROM WFS.ASS_GRUPPO WHERE ID_UK = $Uk AND ID_WORKFLOW = $IdWorkFlow  )
				AND ID_GRUPPO = ( SELECT ID_GRUPPO FROM WFS.GRUPPI WHERE ID_WORKFLOW = L.ID_WORKFLOW AND GRUPPO = 'READ')       
			) RDONLY
			,( SELECT count(*) FROM WFS.AUTORIZZAZIONI WHERE ID_WORKFLOW = L.ID_WORKFLOW AND ID_FLUSSO = L.ID_FLU 
				AND ID_GRUPPO IN ( SELECT ID_GRUPPO FROM WFS.ASS_GRUPPO WHERE ID_UK = $Uk AND ID_WORKFLOW = $IdWorkFlow  )      
			) PERMESSO
			,( SELECT count(*) FROM WORK_CORE.ID_PROCESS WHERE ID_WORKFLOW = L.ID_WORKFLOW AND ID_PROCESS = $IdProcess AND READONLY = 'Y' ) WFSRDONLY
			, US.ID_RUN_SH  
			, US.WARNING
			,( SELECT count(*) FROM WFS.AUTORIZZAZIONI_DIP WHERE ID_WORKFLOW = L.ID_WORKFLOW AND ID_FLU = L.ID_FLU 
				AND ID_GRUPPO IN ( SELECT ID_GRUPPO FROM WFS.ASS_GRUPPO WHERE ID_UK = $Uk  )    
				AND TIPO = L.TIPO
				AND ID_DIP = L.ID_DIP 
			) RDONLYDIP 
			,( SELECT count(*) FROM WFS.AUTORIZZAZIONI_DIP 
				WHERE ID_WORKFLOW = L.ID_WORKFLOW 
				AND ID_FLU = L.ID_FLU   
				AND TIPO = L.TIPO
				AND ID_DIP = L.ID_DIP  
			) RDONLYFLUDIP 
		,( SELECT count(*) FROM WORK_CORE.ID_PROCESS WHERE (ESER_ESAME,MESE_ESAME) IN ( SELECT ESER_ESAME,MESE_ESAME FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
			AND FLAG_CONSOLIDATO = 1 
			)  BLOCK_CONS	
		, (SELECT SHELL FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP) as SHELL
		FROM 
				WFS.LEGAME_FLUSSI L
		LEFT JOIN
				WFS.ULTIMO_STATO US
		ON 1=1
				AND US.ID_PROCESS  = $IdProcess
				AND L.ID_WORKFLOW  = US.ID_WORKFLOW
				AND L.ID_FLU       = US.ID_FLU
				AND L.TIPO         = US.TIPO
				AND L.ID_DIP       = US.ID_DIP
				AND L.ID_WORKFLOW  = $IdWorkFlow 
		WHERE 1=1
				AND DECODE(L.VALIDITA,null,' '||SUBSTR($IdProcess,5,2)||' ',' '||L.VALIDITA||' ') like '% '||SUBSTR($IdProcess,5,2)||' %'
				AND L.ID_WORKFLOW  = $IdWorkFlow 
				AND L.TIPO IN ( 'E', 'C' )
		ORDER BY FLUSSO, PRIORITA,L.TIPO, PRIORITA,DIPENDENZA";


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
