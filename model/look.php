<?php 

class look_model
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
		
		public function getLookDb()
		{
			try
			{
			$sql="SELECT DB_NAME, APPL_NAME, AUTHID, AGENT_ID, TBSP_NAME, TABSCHEMA, TABNAME, LOCK_OBJECT_TYPE, LOCK_MODE, LOCK_STATUS, count(*) CNT
			FROM SYSIBMADM.LOCKS_HELD
			WHERE TABSCHEMA NOT LIKE 'IBM%'
			--  AND TABNAME = 'SEG_DIN'
			GROUP BY DB_NAME, APPL_NAME, AUTHID, AGENT_ID,TBSP_NAME, TABSCHEMA, TABNAME, LOCK_OBJECT_TYPE, LOCK_MODE, LOCK_STATUS
			ORDER BY  TABSCHEMA, TABNAME, LOCK_OBJECT_TYPE, LOCK_MODE, LOCK_STATUS
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
		public function getLookAgent()
		{
			try
			{
			$sql="select
				APP.AGENT_ID
				,APP.APPL_NAME
				,APP.AUTHID
				,SUBSTR(APP.APPL_ID,1,INSTR(APP.APPL_ID,'.',1,4)-1) AS IP
				,APP.APPL_STATUS
				,APP.STATUS_CHANGE_TIME
				,SUBSTR(APP.CLIENT_NNAME,6,LENGTH(TRIM(APP.CLIENT_NNAME))-6) AS USER_CODE
				,DECODE(
					SUBSTR(APP.CLIENT_NNAME,6,LENGTH(TRIM(APP.CLIENT_NNAME))-6),
					 'RU17688','PACORA',
					 'RU17376','CREVATID',
					 'RU18578','ORAZIC',
					 APP.CLIENT_NNAME
				) AS USER_NAME
			    ,MSQL.ACTIVITY_STATE
				,MSQL.ACTIVITY_TYPE
				,MSQL.TOTAL_CPU_TIME
				,MSQL.ROWS_READ
				,MSQL.ROWS_RETURNED
				,MSQL.QUERY_COST_ESTIMATE
				,MSQL.STMT_TEXT
			 FROM
				SYSIBMADM.APPLICATIONS APP
			   ,SYSIBMADM.MON_CONNECTION_SUMMARY CON
			   ,SYSIBMADM.MON_CURRENT_SQL MSQL
		   WHERE APP.AGENT_ID = MSQL.APPLICATION_HANDLE (+)
			  AND APP.AGENT_ID = CON.APPLICATION_HANDLE (+)
		   ORDER BY
			   APP.AGENT_ID
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
		/**
		 * KillAgent
		 *
		 * @param  mixed $KillAgent
		 * @return void
		 */
		public function KillAgent($KillAgent)
		{
			try
			{
				$PRGDIR = $_SESSION['PRGDIR'];
				$DATABASE = $_SESSION['DATABASE'];
				$SSHUSR = $_SESSION['SSHUSR'];
				$SERVER = $_SESSION['SERVER']; 
				shell_exec("sh $PRGDIR/AvviaKillAgent.sh '${DATABASE}' '${SSHUSR}' '${SERVER}' '$KillAgent'");
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}
	}
	
?>