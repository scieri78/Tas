<?php 
				
	class mappingreti_model
	{
		// set database config for mysql
		// open mysql data base
	    private $_db;
		
		public function __construct()
		{
		    $this->_db = new db_driver();		  
		}
		
		public function getRete()
		{
			try
			{
				$sql = "SELECT RETE, RETE_SHOW, ENABLE FROM 
				(SELECT distinct RETE, DECODE(RETE,LEFT(OLD_SH,5),RETE,RETE||' ('||LEFT(OLD_SH,5)||')') RETE_SHOW, ENABLE FROM WORK_CORE.CORE_RETI_TWS tws
				  INNER JOIN WORK_CORE.CORE_SH sh ON sh.ID_SH = tws.ID_SH
				  INNER JOIN WORK_CORE.CORE_RETI_TWS_OLD old ON old.NEW_SH = sh.NAME
				  WHERE 1=1 ) ORDER BY RETE";                
				
				$res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
			
		}				
		
		public function getRowCount()
		{
			try
			{
				$sql = "SELECT count(RETE) CNT FROM TASPCUSR.WORK_CORE.CORE_RETI_TWS";				
				$res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
			
		}			
		
		public function getTable($Sel_Schema='')
		{
			try
			{
				if ( $Sel_Schema != "" ){
			$sql = "select DISTINCT TABNAME, TYPE  from syscat.tables WHERE TYPE in ('T','V','N') AND TRIM(TABSCHEMA) = '$Sel_Schema' ORDER BY 1";
				
				$res = $this->_db->getArrayByQuery($sql,array($Sel_Schema));	
				}
                return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
			
		}	
		
		public function getSQLTable($Sel_Schema='', $Sel_Table='', $DATABASE='USER')
		{
			try {
				if ( $Sel_Schema != "" and $Sel_Table != "" ){
				

					$sql="SELECT TIPO, PATH, FILE, 
					( SELECT MAX(ID_RUN_SQL) FROM WORK_CORE.CORE_DB WHERE FILE_SQL = TIF.PATH||'/'||TIF.FILE ) ID_SQL , 
					( SELECT ID_SH FROM WORK_CORE.CORE_SH_ANAG WHERE SHELL_PATH = TIF.PATH AND SHELL = TIF.FILE ) ID_SH 
					FROM WORK_RULES.TABLES_IN_FILE TIF WHERE TRIM(SCHEMA) = '$Sel_Schema' AND TRIM(TABELLA) = '$Sel_Table' AND DATABASE = '$DATABASE' 
					ORDER BY FILE";

					$res = $this->_db->getArrayByQuery($sql);	
					 
					return $res;
				}
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
			
		} 			
		
		public function getPkgTable($Sel_Schema='', $Sel_Table='')
		{
			try {
				
					$sql="SELECT  DISTINCT 
					ROUTINESCHEMA as PACKAGE_SCHEMA, 
					ROUTINEMODULENAME as PACKAGE
					FROM SYSCAT.ROUTINEDEP a
					where a.btype in ('N','T')
					  AND TRIM(BSCHEMA)= '$Sel_Schema'
					  AND TRIM(BNAME) = '$Sel_Table'  
					  AND ROUTINEMODULENAME IS NOT NULL
					order by 1, 2
					";

					$res = $this->_db->getArrayByQuery($sql);	
					 
					return $res;
				
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
			
		} 			
		
		public function getViewTable($Sel_Schema='', $Sel_Table='')
		{
			try {
				
					$sql="SELECT  DISTINCT 
						VIEWSCHEMA, 
						VIEWNAME
						FROM SYSCAT.VIEWS a
						where TEXT like '%$Sel_Schema.$Sel_Table %'
						order by 1, 2
					";

					$res = $this->_db->getArrayByQuery($sql);	
					 
					return $res;
				
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
			
		} 			
		
		public function getTableList($SelEnableRete, $SetRete, $SelFind)
		{
			
			
			try {
				
						if ( $SelEnableRete != "ALL"){
						$wcondEneble="AND RETE_ENABLE = '${SelEnableRete}'";
						}
						if("${SetRete}"!=".."){
					  $wcondRete="AND RETE = '${SetRete}'";
					}
						if("${SelFind}"!=""){
			  $wcondTab="AND NVL(LENGTH('$SelFind'),0)>3 ";
			  switch ($SelResearchType) {
				case "Table":
				  $wcondTab="${wcondTab} AND (
					   ','||UCASE(LISTA_TABELLE_SH) || ' ' LIKE UCASE(',%$SelFind %')
					OR ','||UCASE(LISTA_TABELLE_SQL) || ' ' LIKE UCASE(',%$SelFind %')
					OR ','||UCASE(LISTA_TABELLE_PK) || ' ' LIKE UCASE(',%$SelFind %')
					OR ','||UCASE(LISTA_TABELLE_SH) || ' ' LIKE UCASE('.%$SelFind %')
					OR ','||UCASE(LISTA_TABELLE_SQL) || ' ' LIKE UCASE('.%$SelFind %')
					OR ','||UCASE(LISTA_TABELLE_PK) || ' ' LIKE UCASE('.%$SelFind %')
					OR ','||UCASE(LISTA_TABELLE_SH) || ' ' LIKE UCASE(',%$SelFind.%')
					OR ','||UCASE(LISTA_TABELLE_SQL) || ' ' LIKE UCASE(',%$SelFind.%')
					OR ','||UCASE(LISTA_TABELLE_PK) || ' ' LIKE UCASE(',%$SelFind.%')) ";
				  break;
				case "Shell":
				  $wcondTab="${wcondTab} AND UPPER(NAME_SH) LIKE UPPER('%$SelFind%')";
				  break;
				case "Sql":
				  $wcondTab="${wcondTab} AND UPPER(FILE_SQL) LIKE UPPER('%$SelFind%')";
				  break;
			  }
			}
				
				$sql="SELECT RETE_ENABLE ENABLE
						 , RETE
						 , DECODE(NVL(NAME_RETE_OLD,NAME_RETE),NAME_RETE,NAME_RETE,NAME_RETE || ' (' || NAME_RETE_OLD || ')') NAME_RETE_SHOW
						 , NAME_RETE
						 , NAME_RETE_OLD
						 , DATABASE
						 , REPLACE(PATH_SH,'/area_staging_TAS/DIR_SHELL/','') PATH_SH
						 , PATH_SH PATH_SH_FULL
						 , NAME_SH
						 , TAGS
						 , MAIL
						 , STEP_SH
						 , VARIABLES_SH
						 , STEP_DB
						 , TYPE_RUN
						 , FILE_SQL
						 , PACKAGE_SCHEMA
						 , PACKAGE_NAME
						 , PACKAGE_ROUTINE
						 , DECODE(PACKAGE_SCHEMA,'','',PACKAGE_SCHEMA || '.' || PACKAGE_NAME || '.' || PACKAGE_ROUTINE) ROUTINE
						 , LISTA_TABELLE_SH 
						 , LISTA_TABELLE_SQL
						 , LISTA_TABELLE_PK
						 , START_TIME_SH
						 , END_TIME_SH
						 , ID_SH_RETE
						 , ID_RUN_SH_RETE
						 , ID_SH
						 , ID_RUN_SH
						 , ID_RUN_SQL
						 , ID_RUN_SH_LIV_SUCC
						 , WORKFLOW
						 , FREQUENZA_WORKFLOW
						 , FLUSSO_CHIAMANTE
					FROM WORK_RULES.V_MAPPING_RETI
					WHERE 1=1
						  ${wcondTab}
						  ${wcondRete}
						  ${wcondEneble}
					ORDER BY RETE desc, NAME_RETE desc, NAME_SH desc;
						";

					$res = $this->_db->getArrayByQuery($sql);	
					 
					return $res;
				
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
			
		} 	
	} 	
	
	
?>