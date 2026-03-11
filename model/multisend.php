<?php 
		
		
		class multisend_model
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
		    
        
		public function getShList()
		{
			try
			{
				$sql = "SELECT ID_SH,SHELL,SHELL_PATH,SH_DEBUG,DB_DEBUG,SH_MAIL_ENABLE, TYPE, ID_MAIL_ANAG, MAIL, MAIL_ENABLE, MULTI_SEND 
				FROM WORK_CORE.V_CORE_MAIL_SETTING
				WHERE MULTI_SEND ='N' ORDER BY SHELL";
                $res = $this->_db->getArrayByQuery($sql);	
				
                    
                return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
			
		}
		
		public function getMultiSend()
		{
			try
			{
				    $sql = "SELECT ID_SH,SHELL,SHELL_PATH,SH_DEBUG,DB_DEBUG,SH_MAIL_ENABLE, TYPE, ID_MAIL_ANAG, MAIL, MAIL_ENABLE, MULTI_SEND 
						FROM WORK_CORE.V_CORE_MAIL_SETTING
						WHERE MULTI_SEND ='Y' ORDER BY SHELL";
                $res = $this->_db->getArrayByQuery($sql);	
				
                    
                return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
			
		}
		
		//update record
		public function addShMultiSend($SelIdSh, $flag)
		{
			
			try
			{	
				$sql = "UPDATE WORK_CORE.CORE_SH_ANAG SET MULTI_SEND=? WHERE ID_SH = ?";

			    $values = array($flag, $SelIdSh);
				$ret = $this->_db->updateDb($sql, $values);
				
				return $ret;
			}
			catch (Throwable $e) {
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