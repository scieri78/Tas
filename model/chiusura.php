<?php 
		
		
	class chiusura_model
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
		
		public function getTestRest()
		{
			try
			{
				$PRGDIR=$_SESSION['PRGDIR'];
				$SERVER=$_SESSION['SERVER'];
				$SSHUSR=$_SESSION['SSHUSR'];
				$DIRSH=$_SESSION['DIRSH'];
				$TestRest=shell_exec("ssh ${SSHUSR}@${SERVER} -o StrictHostKeyChecking=no \"find /area_staging_TAS/DIR_SHELL/UNIFY/ -name Restatement.sem |wc -l |tr -d ' '\"");
				$TestRest=substr($TestRest,0,1);
				 
                return $TestRest;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}
		
		public function getLoadInfo($CAMPO)
		{
			try
			{
				$sql = "SELECT DISTINCT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Compagnia' AND VALORE IN ( SELECT COD_SOC_UTENTE FROM WORK_RULES.LOSS_RES_SOC WHERE YEAR(FINE) = '9999' )";  
                $res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}
		
		public function getLineOfBusiness()
		{
			try
			{
				  $sql = "SELECT * FROM (
						SELECT DISTINCT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'LineOfBusiness' AND ID_TIPO_EXEC = 1
						) ORDER BY INT(REPLACE(UPPER(VALORE),'LOB_',''))";
                $res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}
		
				
		public function getCaricamenti($TABSHOW)
		{
			try
			{
				if ($TABSHOW == "TUTTI" ){
   
					$sql = "SELECT
					A.*,( SELECT VALORE FROM UNIFY.LOAD_INFO  WHERE CAMPO = 'ID_RUN_SH' AND ID_INFO = A.ID_INFO ) ID_RUN_SH   
					FROM (
					  SELECT 
						COMPAGNIA,
						LINEOFBUSINESS,
						STATUS,
						ID_INFO,
						DESCRIZIONE,
						FILEOUTPUTUFFICIALE1,
						FILEOUTPUTUFFICIALE2,
						TO_CHAR(DATA_INFO,'YYYY-MM-DD HH24:MI:SS') DATA_INFO,
						timestampdiff(2,NVL(SHELL_START,CURRENT_TIMESTAMP)-DATA_INFO) WAIT,
						SHELL_NAME, 
						TO_CHAR(SHELL_START,'YYYY-MM-DD HH24:MI:SS') SHELL_START,
						TO_CHAR(SHELL_END,'YYYY-MM-DD HH24:MI:SS') SHELL_END,
						timestampdiff(2,NVL(SHELL_END,CURRENT_TIMESTAMP)-SHELL_START) DIFF,
						NVL(SHELL_STATUS,STATUS) SHELL_STATUS
						,VERSIONEUTENTE
						, (
						  SELECT count(*)
						  FROM UNIFY.V_LOSSRES_STORICO A
						  WHERE 1=1
						  AND ANNO = V.ANNO
						  AND MESE = V.MESE
						  AND COMPAGNIA = V.COMPAGNIA
						  AND NVL(LINEOFBUSINESS,'-') = NVL(V.LINEOFBUSINESS,'-')
						  AND ID_INFO IN (
							SELECT MAX(ID_INFO)
							FROM UNIFY.V_LOSSRES_STORICO B
							WHERE 1=1
							AND ANNO = A.ANNO
							AND MESE = A.MESE
							AND COMPAGNIA = A.COMPAGNIA
							AND NVL(LINEOFBUSINESS,'-') = NVL(A.LINEOFBUSINESS,'-')
							GROUP BY ANNO,MESE,COMPAGNIA,LINEOFBUSINESS,VERSIONEUTENTE
						  )   
						  GROUP BY ANNO,MESE,COMPAGNIA,LINEOFBUSINESS   
						  ) CNTVERS
						  , ( 
							 SELECT count(*) FROM UNIFY.LOSSRESSTATUS B
								WHERE 1=1
								AND ANNO = V.ANNO
								AND MESE = V.MESE
								AND LINEOFBUSINESS IS NOT NULL
								AND ID_INFO = V.ID_INFO
								AND VERSIONE_DB IN ( 
								  SELECT ID_CU FROM LOSS_RESERVING.CATALOG_CU_LOB 
								  WHERE 1=1
								  AND CONSUNIT  = ( SELECT DISTINCT VALORE_TAS FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Compagnia'      AND ID_INFO = B.ID_INFO )
								  AND LOB       = ( SELECT DISTINCT VALORE_TAS FROM UNIFY.LOAD_INFO WHERE CAMPO = 'LineOfBusiness' AND ID_INFO = B.ID_INFO )
								  AND FLAG_TIPO = 1  
								)     
						  
						  ) CAND
						  , ( 
							 SELECT count(*) FROM UNIFY.LOSSRESSTATUS B
								WHERE 1=1
								AND ANNO = V.ANNO
								AND MESE = V.MESE
								AND ID_INFO = V.ID_INFO
								AND VERSIONE_DB IN ( 
								  SELECT ID_CU_CONT FROM LOSS_RESERVING.CATALOG_CU_CONT 
								  WHERE 1=1
								  AND CONSUNIT  = ( SELECT DISTINCT VALORE_TAS FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Compagnia'      AND ID_INFO = B.ID_INFO )
								  AND FLAG_TIPO = 1  
								)     
						  
						  ) CAND_CONT         
					  FROM UNIFY.V_LOSSRES_STORICO V
					  WHERE 1=1
					  AND ANNO = ( SELECT SUBSTRING(VALORE,1,4) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
					  AND MESE = ( SELECT INT(SUBSTRING(VALORE,6,2)) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
					  AND ID_INFO IN (
						SELECT MAX(ID_INFO)
						FROM UNIFY.V_LOSSRES_STORICO A
						WHERE 1=1
						AND ANNO = V.ANNO
						AND MESE = V.MESE
						AND COMPAGNIA = V.COMPAGNIA
						AND NVL(LINEOFBUSINESS,'-') = NVL(V.LINEOFBUSINESS,'-')
						GROUP BY ANNO,MESE,COMPAGNIA,LINEOFBUSINESS,VERSIONEUTENTE
					  )
					  ORDER BY 
					  COMPAGNIA,
					  LINEOFBUSINESS,
					  VERSIONEUTENTE
					) A
				   ";
				} else {

				

					$sql = "
					SELECT 
					  COMPAGNIA,
					  LINEOFBUSINESS,
					  STATUS,
					  ID_INFO,
					  DESCRIZIONE,
					  FILEOUTPUTUFFICIALE1,
					  FILEOUTPUTUFFICIALE2,
					  TO_CHAR(DATA_INFO,'YYYY-MM-DD HH24:MI:SS') DATA_INFO,
					  timestampdiff(2,NVL(SHELL_START,CURRENT_TIMESTAMP)-DATA_INFO) WAIT,
					  SHELL_NAME, 
					  TO_CHAR(SHELL_START,'YYYY-MM-DD HH24:MI:SS') SHELL_START,
					  TO_CHAR(SHELL_END,'YYYY-MM-DD HH24:MI:SS') SHELL_END,
					  timestampdiff(2,NVL(SHELL_END,CURRENT_TIMESTAMP)-SHELL_START) DIFF,
					  NVL(SHELL_STATUS,STATUS) SHELL_STATUS
					  ,VERSIONEUTENTE
					  , (
						SELECT count(*)
						FROM UNIFY.V_LOSSRES_STORICO A
						WHERE 1=1
						AND ANNO = V.ANNO
						AND MESE = V.MESE
						AND COMPAGNIA = V.COMPAGNIA
						AND NVL(LINEOFBUSINESS,'-') = NVL(V.LINEOFBUSINESS,'-')
						AND ID_INFO IN (
						  SELECT MAX(ID_INFO)
						  FROM UNIFY.V_LOSSRES_STORICO B
						  WHERE 1=1
						  AND ANNO = A.ANNO
						  AND MESE = A.MESE
						  AND COMPAGNIA = A.COMPAGNIA
						  AND NVL(LINEOFBUSINESS,'-') = NVL(A.LINEOFBUSINESS,'-')
						  GROUP BY ANNO,MESE,COMPAGNIA,LINEOFBUSINESS,VERSIONEUTENTE
						)     
						GROUP BY ANNO,MESE,COMPAGNIA,LINEOFBUSINESS 
						) CNTVERS,
						1 CAND
					FROM UNIFY.V_LOSSRES_STORICO V
					WHERE 1=1
					AND ANNO = ( SELECT SUBSTRING(VALORE,1,4) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
					AND MESE = ( SELECT INT(SUBSTRING(VALORE,6,2)) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
					AND ID_INFO = (
					  SELECT MAX(ID_INFO) FROM UNIFY.V_LOSSRES_STORICO A
					  WHERE 1=1
					  AND ANNO = V.ANNO
					  AND MESE = V.MESE
					  AND COMPAGNIA = V.COMPAGNIA
					  AND NVL(LINEOFBUSINESS,'-') = NVL(V.LINEOFBUSINESS,'-')
					  AND (
						  ID_INFO IN ( 
							SELECT ID_INFO FROM UNIFY.LOSSRESSTATUS B
							WHERE 1=1
							AND ANNO = A.ANNO
							AND MESE = A.MESE
							AND COMPAGNIA = A.COMPAGNIA
					  AND NVL(LINEOFBUSINESS,'-') = NVL(V.LINEOFBUSINESS,'-')
							AND VERSIONE_DB IN ( 
							  SELECT ID_CU FROM LOSS_RESERVING.CATALOG_CU_LOB 
							  WHERE 1=1
							  AND CONSUNIT  = ( SELECT DISTINCT VALORE_TAS FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Compagnia'      AND ID_INFO = B.ID_INFO )
							  AND LOB       = ( SELECT DISTINCT VALORE_TAS FROM UNIFY.LOAD_INFO WHERE CAMPO = 'LineOfBusiness' AND ID_INFO = B.ID_INFO )
							  AND FLAG_TIPO = 1  
							)
						  )
						  OR ID_INFO IN ( 
							SELECT ID_INFO FROM UNIFY.LOSSRESSTATUS B
							WHERE 1=1
							AND ANNO = A.ANNO
							AND MESE = A.MESE
							AND VERSIONE_DB IN ( 
							  SELECT ID_CU_CONT FROM LOSS_RESERVING.CATALOG_CU_CONT 
							  WHERE 1=1
							  AND CONSUNIT  = ( SELECT DISTINCT VALORE_TAS FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Compagnia' AND ID_INFO = B.ID_INFO )
							  AND FLAG_TIPO = 1  
							)
						  )    
					)
				  )
				  ";
				}   
  
                $res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}
		
			
					
		public function getCandidatoDefinitivo()
		{
			try
			{
				 $sql = "SELECT DATA_INFO,
						SHELL_NAME, 
						TO_CHAR(SHELL_START,'YYYY-MM-DD HH24:MI:SS') SHELL_START,
						TO_CHAR(SHELL_END,'YYYY-MM-DD HH24:MI:SS') SHELL_END,
						timestampdiff(2,NVL(SHELL_END,CURRENT_TIMESTAMP)-SHELL_START) DIFF,
						NVL(SHELL_STATUS,STATUS) SHELL_STATUS   
						FROM (
					SELECT
						C.ID_INFO,
						C.DATA_INFO,
						C.COMPAGNIA,
						C.ANNO,
						C.MESE,
						C.LINEOFBUSINESS,
						C.VERSIONEUTENTE,
						C.DESCRIZIONE,
						C.VERSIONENUOVA,
						C.FILEOUTPUTUFFICIALE1,
						C.FILEOUTPUTUFFICIALE2,
						C.JOBID,
						C.JOBOWNER,
						C.STATUS,
						C.WAITTIME,
						C.RIPETIZIONE,
						B.NAME       SHELL_NAME,
						B.START_TIME SHELL_START,
						B.END_TIME   SHELL_END,
						B.STATUS     SHELL_STATUS
					FROM
						(   SELECT
								*,
								ROWNUMBER() OVER (
											  PARTITION BY
												  Compagnia,
												  Anno,
												  Mese,
												  LineOfBusiness,
												  VersioneUtente,
												  STATUS
											  ORDER BY
												  DATA_INFO ) RIPETIZIONE
							FROM
								(   SELECT
										ID_INFO,
										DATA_INFO ,
										(   SELECT
												VALORE
											FROM
												UNIFY.LOAD_INFO
											WHERE
												CAMPO = 'Compagnia'
											AND ID_INFO = A.ID_INFO ) Compagnia ,
										(   SELECT
												VALORE
											FROM
												UNIFY.LOAD_INFO
											WHERE
												CAMPO = 'Anno'
											AND ID_INFO = A.ID_INFO ) Anno ,
										(   SELECT
												VALORE
											FROM
												UNIFY.LOAD_INFO
											WHERE
												CAMPO = 'Mese'
											AND ID_INFO = A.ID_INFO ) Mese ,
										(   SELECT
												VALORE
											FROM
												UNIFY.LOAD_INFO
											WHERE
												CAMPO = 'LineOfBusiness'
											AND ID_INFO = A.ID_INFO ) LineOfBusiness ,
										(   SELECT
												VALORE
											FROM
												UNIFY.LOAD_INFO
											WHERE
												CAMPO = 'VersioneUtente'
											AND ID_INFO = A.ID_INFO ) VersioneUtente ,
										(   SELECT
												VALORE
											FROM
												UNIFY.LOAD_INFO
											WHERE
												CAMPO = 'Descrizione'
											AND ID_INFO = A.ID_INFO ) Descrizione ,
										(   SELECT
												VALORE
											FROM
												UNIFY.LOAD_INFO
											WHERE
												CAMPO = 'VersioneNuova'
											AND ID_INFO = A.ID_INFO ) VersioneNuova ,
										(   SELECT
												VALORE
											FROM
												UNIFY.LOAD_INFO
											WHERE
												CAMPO = 'FileOutputUfficiale1'
											AND ID_INFO = A.ID_INFO ) FileOutputUfficiale1 ,
										(   SELECT
												VALORE
											FROM
												UNIFY.LOAD_INFO
											WHERE
												CAMPO = 'FileOutputUfficiale2'
											AND ID_INFO = A.ID_INFO ) FileOutputUfficiale2 ,
										(   SELECT
												VALORE
											FROM
												UNIFY.LOAD_INFO
											WHERE
												CAMPO = 'JobId'
											AND ID_INFO = A.ID_INFO ) JobId ,
										(   SELECT
												VALORE
											FROM
												UNIFY.LOAD_INFO
											WHERE
												CAMPO = 'JobOwner'
											AND ID_INFO = A.ID_INFO ) JobOwner ,
										(   SELECT
												VALORE
											FROM
												UNIFY.LOAD_INFO
											WHERE
												CAMPO = 'Status'
											AND ID_INFO = A.ID_INFO ) Status ,
										(   SELECT
												VALORE
											FROM
												UNIFY.LOAD_INFO
											WHERE
												CAMPO = 'WaitTime'
											AND ID_INFO = A.ID_INFO ) WaitTime,
										(   SELECT
												VALORE
											FROM
												UNIFY.LOAD_INFO
											WHERE
												CAMPO = 'ID_RUN_SH'
											AND ID_INFO = A.ID_INFO ) ID_RUN_SH
									FROM
										UNIFY.LOAD_INFO A
									WHERE 1=1
									   AND ID_TIPO_EXEC = ( SELECT ID_TIPO_EXEC FROM UNIFY.TAB_EXEC_ANAG WHERE TIPO_EXEC = 'CandInDef' ) 
									   AND ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Anno' AND ID_INFO = A.ID_INFO ) = ( SELECT SUBSTRING(VALORE,1,4) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
									   AND ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Mese' AND ID_INFO = A.ID_INFO ) = ( SELECT INT(SUBSTRING(VALORE,6,2)) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
									   --AND ID_INFO > NVL(( 
									   --   SELECT MAX(ID_INFO) 
									   --   FROM UNIFY.LOSSRESSTATUS B
									   --   WHERE 1=1
									   --   AND ANNO = ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Anno' AND ID_INFO = A.ID_INFO )
									   --   AND MESE = ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Mese' AND ID_INFO = A.ID_INFO )
									   --   AND VERSIONE_DB IN ( 
									   --     SELECT ID_CU_CONT FROM LOSS_RESERVING.CATALOG_CU_CONT 
									   --     WHERE 1=1
									   --     AND CONSUNIT  = ( SELECT DISTINCT VALORE_TAS FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Compagnia' AND ID_INFO = B.ID_INFO )
									   --     AND FLAG_TIPO = 1  
									   --   )
									   -- ),0)
									GROUP BY
										ID_INFO,
										DATA_INFO )
							ORDER BY
								Compagnia,
								Anno,
								Mese,
								LineOfBusiness,
								VersioneUtente,
								STATUS,
								ROWNUMBER() OVER (
											  PARTITION BY
												  Compagnia,
												  Anno,
												  Mese,
												  LineOfBusiness,
												  VersioneUtente,
												  STATUS
											  ORDER BY
												  DATA_INFO DESC ) ) C
					JOIN 
						WORK_CORE.CORE_SH B
					ON 
						(   SELECT 
								ID_RUN_SH 
							FROM 
								WORK_CORE.CORE_SH 
							WHERE 
								ID_RUN_SH_FATHER = C.ID_RUN_SH ) 
						= B.ID_RUN_SH   
						)
						
						WHERE 1=1
						ORDER BY DATA_INFO
						";        
                $res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}	
					
		public function getCuboApp()
		{
			try
			{
				    $sql = "SELECT DATA_INFO,
				SHELL_NAME, 
				TO_CHAR(SHELL_START,'YYYY-MM-DD HH24:MI:SS') SHELL_START,
				TO_CHAR(SHELL_END,'YYYY-MM-DD HH24:MI:SS') SHELL_END,
				timestampdiff(2,NVL(SHELL_END,CURRENT_TIMESTAMP)-SHELL_START) DIFF,
				NVL(SHELL_STATUS,STATUS) SHELL_STATUS   
				FROM (
			SELECT
				C.ID_INFO,
				C.DATA_INFO,
				C.COMPAGNIA,
				C.ANNO,
				C.MESE,
				C.LINEOFBUSINESS,
				C.VERSIONEUTENTE,
				C.DESCRIZIONE,
				C.VERSIONENUOVA,
				C.FILEOUTPUTUFFICIALE1,
				C.FILEOUTPUTUFFICIALE2,
				C.JOBID,
				C.JOBOWNER,
				C.STATUS,
				C.WAITTIME,
				C.RIPETIZIONE,
				B.NAME       SHELL_NAME,
				B.START_TIME SHELL_START,
				B.END_TIME   SHELL_END,
				B.STATUS     SHELL_STATUS
			FROM
				(   SELECT
						*,
						ROWNUMBER() OVER (
									  PARTITION BY
										  Compagnia,
										  Anno,
										  Mese,
										  LineOfBusiness,
										  VersioneUtente,
										  STATUS
									  ORDER BY
										  DATA_INFO ) RIPETIZIONE
					FROM
						(   SELECT
								ID_INFO,
								DATA_INFO ,
								(   SELECT
										VALORE
									FROM
										UNIFY.LOAD_INFO
									WHERE
										CAMPO = 'Compagnia'
									AND ID_INFO = A.ID_INFO ) Compagnia ,
								(   SELECT
										VALORE
									FROM
										UNIFY.LOAD_INFO
									WHERE
										CAMPO = 'Anno'
									AND ID_INFO = A.ID_INFO ) Anno ,
								(   SELECT
										VALORE
									FROM
										UNIFY.LOAD_INFO
									WHERE
										CAMPO = 'Mese'
									AND ID_INFO = A.ID_INFO ) Mese ,
								(   SELECT
										VALORE
									FROM
										UNIFY.LOAD_INFO
									WHERE
										CAMPO = 'LineOfBusiness'
									AND ID_INFO = A.ID_INFO ) LineOfBusiness ,
								(   SELECT
										VALORE
									FROM
										UNIFY.LOAD_INFO
									WHERE
										CAMPO = 'VersioneUtente'
									AND ID_INFO = A.ID_INFO ) VersioneUtente ,
								(   SELECT
										VALORE
									FROM
										UNIFY.LOAD_INFO
									WHERE
										CAMPO = 'Descrizione'
									AND ID_INFO = A.ID_INFO ) Descrizione ,
								(   SELECT
										VALORE
									FROM
										UNIFY.LOAD_INFO
									WHERE
										CAMPO = 'VersioneNuova'
									AND ID_INFO = A.ID_INFO ) VersioneNuova ,
								(   SELECT
										VALORE
									FROM
										UNIFY.LOAD_INFO
									WHERE
										CAMPO = 'FileOutputUfficiale1'
									AND ID_INFO = A.ID_INFO ) FileOutputUfficiale1 ,
								(   SELECT
										VALORE
									FROM
										UNIFY.LOAD_INFO
									WHERE
										CAMPO = 'FileOutputUfficiale2'
									AND ID_INFO = A.ID_INFO ) FileOutputUfficiale2 ,
								(   SELECT
										VALORE
									FROM
										UNIFY.LOAD_INFO
									WHERE
										CAMPO = 'JobId'
									AND ID_INFO = A.ID_INFO ) JobId ,
								(   SELECT
										VALORE
									FROM
										UNIFY.LOAD_INFO
									WHERE
										CAMPO = 'JobOwner'
									AND ID_INFO = A.ID_INFO ) JobOwner ,
								(   SELECT
										VALORE
									FROM
										UNIFY.LOAD_INFO
									WHERE
										CAMPO = 'Status'
									AND ID_INFO = A.ID_INFO ) Status ,
								(   SELECT
										VALORE
									FROM
										UNIFY.LOAD_INFO
									WHERE
										CAMPO = 'WaitTime'
									AND ID_INFO = A.ID_INFO ) WaitTime,
								(   SELECT
										VALORE
									FROM
										UNIFY.LOAD_INFO
									WHERE
										CAMPO = 'ID_RUN_SH'
									AND ID_INFO = A.ID_INFO ) ID_RUN_SH
							FROM
								UNIFY.LOAD_INFO A
							WHERE 1=1
							   AND ID_TIPO_EXEC = ( SELECT ID_TIPO_EXEC FROM UNIFY.TAB_EXEC_ANAG WHERE TIPO_EXEC = 'SoloCuboApp' ) 
							   AND ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Anno' AND ID_INFO = A.ID_INFO ) = ( SELECT SUBSTRING(VALORE,1,4) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
							   AND ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Mese' AND ID_INFO = A.ID_INFO ) = ( SELECT INT(SUBSTRING(VALORE,6,2)) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
							   --AND ID_INFO > NVL( 
							   --   (SELECT MAX(ID_INFO) FROM UNIFY.LOSSRESSTATUS B
							   --   WHERE 1=1
							   --   AND ANNO = ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Anno' AND ID_INFO = A.ID_INFO )
							   --   AND MESE = ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Mese' AND ID_INFO = A.ID_INFO )
							   --   AND VERSIONE_DB IN ( 
							   --     SELECT ID_CU_CONT FROM LOSS_RESERVING.CATALOG_CU_CONT 
							   --     WHERE 1=1
							   --     AND CONSUNIT  = ( SELECT DISTINCT VALORE_TAS FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Compagnia' AND ID_INFO = B.ID_INFO )
							   --     AND FLAG_TIPO = 1  
							   --   )
							   --   )
							   -- ,0)
							GROUP BY
								ID_INFO,
								DATA_INFO )
					ORDER BY
						Compagnia,
						Anno,
						Mese,
						LineOfBusiness,
						VersioneUtente,
						STATUS,
						ROWNUMBER() OVER (
									  PARTITION BY
										  Compagnia,
										  Anno,
										  Mese,
										  LineOfBusiness,
										  VersioneUtente,
										  STATUS
									  ORDER BY
										  DATA_INFO DESC ) ) C
			JOIN 
				WORK_CORE.CORE_SH B
			ON 
				(   SELECT 
						ID_RUN_SH 
					FROM 
						WORK_CORE.CORE_SH 
					WHERE 
						ID_RUN_SH_FATHER = C.ID_RUN_SH ) 
				= B.ID_RUN_SH   
				)
				
				WHERE 1=1
				ORDER BY DATA_INFO"; 
                $res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}
		
						
		public function getGiroSolvency()
		{
			try
			{
				    $sql = "SELECT DATA_INFO,
    SHELL_NAME, 
    TO_CHAR(SHELL_START,'YYYY-MM-DD HH24:MI:SS') SHELL_START,
    TO_CHAR(SHELL_END,'YYYY-MM-DD HH24:MI:SS') SHELL_END,
    timestampdiff(2,NVL(SHELL_END,CURRENT_TIMESTAMP)-SHELL_START) DIFF,
    NVL(SHELL_STATUS,STATUS) SHELL_STATUS   
    FROM (
SELECT
    C.ID_INFO,
    C.DATA_INFO,
    C.COMPAGNIA,
    C.ANNO,
    C.MESE,
    C.LINEOFBUSINESS,
    C.VERSIONEUTENTE,
    C.DESCRIZIONE,
    C.VERSIONENUOVA,
    C.FILEOUTPUTUFFICIALE1,
    C.FILEOUTPUTUFFICIALE2,
    C.JOBID,
    C.JOBOWNER,
    C.STATUS,
    C.WAITTIME,
    C.RIPETIZIONE,
    B.NAME       SHELL_NAME,
    B.START_TIME SHELL_START,
    B.END_TIME   SHELL_END,
    B.STATUS     SHELL_STATUS
FROM
    (   SELECT
            *,
            ROWNUMBER() OVER (
                          PARTITION BY
                              Compagnia,
                              Anno,
                              Mese,
                              LineOfBusiness,
                              VersioneUtente,
                              STATUS
                          ORDER BY
                              DATA_INFO ) RIPETIZIONE
        FROM
            (   SELECT
                    ID_INFO,
                    DATA_INFO ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Compagnia'
                        AND ID_INFO = A.ID_INFO ) Compagnia ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Anno'
                        AND ID_INFO = A.ID_INFO ) Anno ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Mese'
                        AND ID_INFO = A.ID_INFO ) Mese ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'LineOfBusiness'
                        AND ID_INFO = A.ID_INFO ) LineOfBusiness ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'VersioneUtente'
                        AND ID_INFO = A.ID_INFO ) VersioneUtente ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Descrizione'
                        AND ID_INFO = A.ID_INFO ) Descrizione ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'VersioneNuova'
                        AND ID_INFO = A.ID_INFO ) VersioneNuova ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'FileOutputUfficiale1'
                        AND ID_INFO = A.ID_INFO ) FileOutputUfficiale1 ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'FileOutputUfficiale2'
                        AND ID_INFO = A.ID_INFO ) FileOutputUfficiale2 ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'JobId'
                        AND ID_INFO = A.ID_INFO ) JobId ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'JobOwner'
                        AND ID_INFO = A.ID_INFO ) JobOwner ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Status'
                        AND ID_INFO = A.ID_INFO ) Status ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'WaitTime'
                        AND ID_INFO = A.ID_INFO ) WaitTime,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'ID_RUN_SH'
                        AND ID_INFO = A.ID_INFO ) ID_RUN_SH
                FROM
                    UNIFY.LOAD_INFO A
                WHERE 1=1
                   AND ID_TIPO_EXEC = ( SELECT ID_TIPO_EXEC FROM UNIFY.TAB_EXEC_ANAG WHERE TIPO_EXEC = 'CuboSolvency' ) 
                   AND ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Anno' AND ID_INFO = A.ID_INFO ) = ( SELECT SUBSTRING(VALORE,1,4) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
                   AND ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Mese' AND ID_INFO = A.ID_INFO ) = ( SELECT INT(SUBSTRING(VALORE,6,2)) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
                  --AND ID_INFO > NVL( 
                  --   (SELECT MAX(ID_INFO) FROM UNIFY.LOSSRESSTATUS B
                  --   WHERE 1=1
                  --   AND ANNO = ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Anno' AND ID_INFO = A.ID_INFO )
                  --   AND MESE = ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Mese' AND ID_INFO = A.ID_INFO )
                  --   AND VERSIONE_DB IN ( 
                  --     SELECT ID_CU_CONT FROM LOSS_RESERVING.CATALOG_CU_CONT 
                  --     WHERE 1=1
                  --     AND CONSUNIT  = ( SELECT DISTINCT VALORE_TAS FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Compagnia' AND ID_INFO = B.ID_INFO )
                  --     AND FLAG_TIPO = 1  
                  --   )
                  --   )
                  -- ,0)
                GROUP BY
                    ID_INFO,
                    DATA_INFO )
        ORDER BY
            Compagnia,
            Anno,
            Mese,
            LineOfBusiness,
            VersioneUtente,
            STATUS,
            ROWNUMBER() OVER (
                          PARTITION BY
                              Compagnia,
                              Anno,
                              Mese,
                              LineOfBusiness,
                              VersioneUtente,
                              STATUS
                          ORDER BY
                              DATA_INFO DESC ) ) C
JOIN 
    WORK_CORE.CORE_SH B
ON 
    (   SELECT 
            ID_RUN_SH 
        FROM 
            WORK_CORE.CORE_SH 
        WHERE 
            ID_RUN_SH_FATHER = C.ID_RUN_SH ) 
    = B.ID_RUN_SH   
    )
    
    WHERE 1=1
    ORDER BY DATA_INFO
    ";
                $res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}
		
			
							
		public function getCuboSolvency()
		{
			try
			{
				 $sql = "SELECT
    DATA_INFO,
    SHELL_NAME, 
    TO_CHAR(SHELL_START,'YYYY-MM-DD HH24:MI:SS') SHELL_START,
    TO_CHAR(SHELL_END,'YYYY-MM-DD HH24:MI:SS') SHELL_END,
    timestampdiff(2,NVL(SHELL_END,CURRENT_TIMESTAMP)-SHELL_START) DIFF,
    NVL(SHELL_STATUS,STATUS) SHELL_STATUS   
    FROM (
SELECT
    C.ID_INFO,
    C.DATA_INFO,
    C.COMPAGNIA,
    C.ANNO,
    C.MESE,
    C.LINEOFBUSINESS,
    C.VERSIONEUTENTE,
    C.DESCRIZIONE,
    C.VERSIONENUOVA,
    C.FILEOUTPUTUFFICIALE1,
    C.FILEOUTPUTUFFICIALE2,
    C.JOBID,
    C.JOBOWNER,
    C.STATUS,
    C.WAITTIME,
    C.RIPETIZIONE,
    B.NAME       SHELL_NAME,
    B.START_TIME SHELL_START,
    B.END_TIME   SHELL_END,
    B.STATUS     SHELL_STATUS
FROM
    (   SELECT
            *,
            ROWNUMBER() OVER (
                          PARTITION BY
                              Anno,
                              Mese
                          ORDER BY
                              DATA_INFO ) RIPETIZIONE
        FROM
            (   SELECT
                    ID_INFO,
                    DATA_INFO ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Compagnia'
                        AND ID_INFO = A.ID_INFO ) Compagnia ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Anno'
                        AND ID_INFO = A.ID_INFO ) Anno ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Mese'
                        AND ID_INFO = A.ID_INFO ) Mese ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'LineOfBusiness'
                        AND ID_INFO = A.ID_INFO ) LineOfBusiness ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'VersioneUtente'
                        AND ID_INFO = A.ID_INFO ) VersioneUtente ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Descrizione'
                        AND ID_INFO = A.ID_INFO ) Descrizione ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'VersioneNuova'
                        AND ID_INFO = A.ID_INFO ) VersioneNuova ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'FileOutputUfficiale1'
                        AND ID_INFO = A.ID_INFO ) FileOutputUfficiale1 ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'FileOutputUfficiale2'
                        AND ID_INFO = A.ID_INFO ) FileOutputUfficiale2 ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'JobId'
                        AND ID_INFO = A.ID_INFO ) JobId ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'JobOwner'
                        AND ID_INFO = A.ID_INFO ) JobOwner ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Status'
                        AND ID_INFO = A.ID_INFO ) Status ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'WaitTime'
                        AND ID_INFO = A.ID_INFO ) WaitTime,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'ID_RUN_SH'
                        AND ID_INFO = A.ID_INFO ) ID_RUN_SH
                FROM
                    UNIFY.LOAD_INFO A
                WHERE 1=1
                   AND ID_TIPO_EXEC = ( SELECT ID_TIPO_EXEC FROM UNIFY.TAB_EXEC_ANAG WHERE TIPO_EXEC = 'SoloCuboSolvency' ) 
                   AND ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Anno' AND ID_INFO = A.ID_INFO ) = ( SELECT SUBSTRING(VALORE,1,4) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
                   AND ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Mese' AND ID_INFO = A.ID_INFO ) = ( SELECT INT(SUBSTRING(VALORE,6,2)) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
                   --AND ID_INFO > NVL( 
                   --   (SELECT MAX(ID_INFO) FROM UNIFY.LOSSRESSTATUS B
                   --   WHERE 1=1
                   --   AND ANNO = ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Anno' AND ID_INFO = A.ID_INFO )
                   --   AND MESE = ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Mese' AND ID_INFO = A.ID_INFO )
                   --   AND VERSIONE_DB IN ( 
                   --     SELECT ID_CU_CONT FROM LOSS_RESERVING.CATALOG_CU_CONT 
                   --     WHERE 1=1
                   --     AND CONSUNIT  = ( SELECT DISTINCT VALORE_TAS FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Compagnia' AND ID_INFO = B.ID_INFO )
                   --     AND FLAG_TIPO = 1  
                   --   ))
                   -- ,0)   
                GROUP BY
                    ID_INFO,
                    DATA_INFO )
        ORDER BY
            Compagnia,
            Anno,
            Mese,
            LineOfBusiness,
            VersioneUtente,
            STATUS,
            ROWNUMBER() OVER (
                          PARTITION BY
                              Anno,
                              Mese
                          ORDER BY
                              DATA_INFO DESC ) ) C
JOIN 
    WORK_CORE.CORE_SH B
ON 
    (   SELECT 
            ID_RUN_SH 
        FROM 
            WORK_CORE.CORE_SH 
        WHERE 
            ID_RUN_SH_FATHER = C.ID_RUN_SH ) 
    = B.ID_RUN_SH
)   
    
    WHERE 1=1
    ORDER BY DATA_INFO

    ";       
                $res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}					
		public function getf()
		{
			try
			{
					$sql = "select * from dual";
                $res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}
		
			
			
			
	 public function Addf()
		{
			try
			{	
				
				$sql = "INSERT INTO *****";
			
			
			$last_id = $this->_db->insertDb($sql, array('','',''));
		//	echo $this->_db->getsqlQuery();
		//	die();
		    return $last_id;
			}
			catch (Exception $e) 
			{
			    $this->_db->close_db();	
            	throw $e;
        	}
		}		
	 public function Removef($id)
		{
			try
			{	
				
			$sql = "DELETE FROM WORK_CORE.CORE_ALERT_MAIL WHERE ID_SH = ?";
			
			  $ret = $this->_db->deleteDb($sql, array($id));
			    return $ret;	
			}
			catch (Exception $e) 
			{
			    $this->_db->close_db();	
            	throw $e;
        	}
		}

		        //update record
		public function updatef()
		{
			
			try
			{	
        $sql = "UPDATE set b =? where c=? ";      

			    $values = array($v1, $v2);
				$ret = $this->_db->updateDb($sql, $values);
				
				return $ret;
			}
			catch (Throwable $e) {
			  //  $this->_db->close_db();
			    throw $e;
			    
			}
			catch (Exception $e) 
			{
			    $this->_db->close_db();
            	throw $e;
        	}
        }	
		
		public function callf()
		{
			try
			{
		    $CallPlSql='CALL WFS.K_CONFIG.RimuoviValidazione(?, ?, ?, ?, ?, ? )';
            $stmt = db2_prepare($conn, $CallPlSql);

            $IdVal=$_POST['IdDip']; 
            $IdLeg=$_POST['IdLeg'];
			
			    $values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdVal',
					'value' => $IdVal,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'IdLeg',
					'value' => $IdLeg,
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
					['name' => 'Note',
					'value' => $Note,
					'type' => DB2_PARAM_OUT
					]
					];	
            
          /*  db2_bind_param($stmt, 1, "IdWorkFlow"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdVal"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "IdLeg"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "User"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Errore"           , DB2_PARAM_OUT);
            db2_bind_param($stmt, 6, "Note"             , DB2_PARAM_OUT);*/       
	
	
			$ret = $this->_db->callDb($CallPlSql ,$values);return $ret;
	 			
		
			}
			catch (Throwable $e) {
			  //  $this->_db->close_db();
			    throw $e;
			    
			}
			catch (Exception $e) 
			{
			    $this->_db->close_db();
            	throw $e;
        	}
        }



		
	
	}
	
?>