<?php 

class debug_model
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
				$sql = "SELECT ID_SH,SHELL,SHELL_PATH,DEBUG_SH,DEBUG_DB
						FROM WORK_CORE.CORE_SH_ANAG 
						WHERE DEBUG_SH = 'N' ORDER BY SHELL";
                $res = $this->_db->getArrayByQuery($sql);	
				
                    
                return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}
		
		
		public function getSqlList()
		{
			try
			{
				$sql = "SELECT ID_SH,SHELL,SHELL_PATH,DEBUG_SH,DEBUG_DB
				FROM WORK_CORE.CORE_SH_ANAG 
				WHERE DEBUG_SH = 'N' ORDER BY SHELL";
                $res = $this->_db->getArrayByQuery($sql);	
				
                    
                return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}
				
		public function getDebugList()
		{
			try
			{
				$sql = "SELECT ID_SH,SHELL,SHELL_PATH,DEBUG_SH,DEBUG_DB
				FROM WORK_CORE.CORE_SH_ANAG 
				WHERE DEBUG_DB = 'Y' OR DEBUG_SH = 'Y' ORDER BY SHELL";
                $res = $this->_db->getArrayByQuery($sql);	
				
                    
                return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
			
		}
		
		public function updateShDebug($SelIdSh, $flag)
		{
			
			try
			{	
				$sql = "UPDATE WORK_CORE.CORE_SH_ANAG SET DEBUG_SH=? WHERE ID_SH =?";

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
		
		public function updateDbDebug($SelIdSh, $flag)
		{
			
			try
			{	
				$sql = "UPDATE WORK_CORE.CORE_SH_ANAG SET DEBUG_DB=? WHERE ID_SH = ?";

			    $values = array($flag, $SelIdSh);
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

		
		public function updateDb($SelIdSh, $flagSH,$flagDb)
		{
			
			try
			{	
				$sql = "UPDATE WORK_CORE.CORE_SH_ANAG SET DEBUG_DB=?, DEBUG_SH=? WHERE ID_SH = ?";

			    $values = [$flagDb,$flagSH, $SelIdSh];
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
		    
		
	}
	
		?>