<?php 
		
		
	class ddlPackage_model
	{
		// set database config for mysql
		// open mysql data base
	    private $_db;
		
		 /**
		 * {@inheritDoc}
		 * @see db_driver::__construct()
		 */
		public function __construct($sito='')
		{
		    $this->_db = new db_driver($sito);		  
		}
		
		public function getSchema()
		{
			try
			{
				$sql = "select DISTINCT TRIM(MODULESCHEMA) MODULESCHEMA from SYSIBM.SYSMODULES";

                $res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
			
		}			
		
		public function getPackage($Sel_PkgSchema='')
		{
			try
			{
						if ( $Sel_PkgSchema != "" ){
						$sql = "select DISTINCT TRIM(MODULENAME) MODULENAME from SYSIBM.SYSMODULES WHERE TRIM(MODULESCHEMA) = '$Sel_PkgSchema' ";
						
						$res = $this->_db->getArrayByQuery($sql);	
						}
                return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
			
		}	
		
		public function getInfoFile($Sel_PkgSchema='', $Sel_PkgName='')
		{
			try
			{
						if ( $Sel_PkgSchema != "" && $Sel_PkgName != ''){
						$sql="select SOURCEHEADER, SOURCEBODY from SYSIBM.SYSMODULES WHERE  MODULESCHEMA = '$Sel_PkgSchema' AND  MODULENAME = '$Sel_PkgName'";
						
						$res = $this->_db->getArrayByQuery($sql);	
						}
                return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
			
		}		
		
		

	}
	
?>