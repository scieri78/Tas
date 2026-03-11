<?php 
		
		
	class pendingtab_model
	{
		// set datapendingtab config for mysql
		// open mysql data pendingtab
	    private $_db;
		
		 /**
		 * {@inheritDoc}
		 * @see db_driver::__construct()
		 */
		public function __construct()
		{
		    $this->_db = new db_driver();		  
		}
		
		public function getTabschema()
		{
			try
			{
				$sql = "select DISTINCT TRIM(TABSCHEMA) TABSCHEMA from syscat.tables WHERE TABSCHEMA NOT LIKE 'SYS%' ORDER BY 1";
                $res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}
		
		
		
		public function getTables($Sel_Schema)
		{
			try
			{
				$res=false;
				if ( "$Sel_Schema" != "" ){
				$sql = "select DISTINCT TRIM(TABNAME) TABNAME  from syscat.tables WHERE TYPE = 'T' AND TRIM(TABSCHEMA) = '$Sel_Schema' ORDER BY 1";
                $res = $this->_db->getArrayByQuery($sql);	
				}
                return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}
				
			
			
	


		
	
	}
	
?>