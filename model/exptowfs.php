<?php 
		
		
	class exptowfs_model
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
		
		public function getEleborazioni()
		{
			try
			{
				  $sql = "SELECT ID_SH,SHELL,SHELL_PATH, WFS	
						, (SELECT COUNT(*) FROM WFS.ELABORAZIONI E WHERE E.ID_SH = A.ID_SH ) WFSUSED
						, BLOCKWFS
						FROM WORK_CORE.CORE_SH_ANAG A WHERE WFS ='Y' ORDER BY SHELL";
                $res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
			
		}
		
/*		
	 public function RemoveShMail($vIdSh)
		{
			try
			{	
				
			$sql = "DELETE FROM WORK_CORE.CORE_ALERT_MAIL WHERE ID_SH = ?";
			
			  $ret = $this->_db->deleteDb($sql, array($vIdSh));
			    return $ret;	
			}
			catch (Exception $e) 
			{
			    $this->_db->close_db();	
            	throw $e;
        	}
		}
*/		
		 
	public function setShWfs($idSh,$flag)
		{
			try
			{	
				
			    $values = array($flag,$idSh);
				$sql = "UPDATE WORK_CORE.CORE_SH_ANAG SET WFS=? WHERE ID_SH=?";
				$ret = $this->_db->updateDb($sql ,$values);
				/*echo $this->_db->getsqlQuery();
				die();*/
				
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
		
		public function setBlockWfs($idSh,$flag)
		{
			try
			{	
				
			    $values = array($flag,$idSh);
				$sql = "UPDATE WORK_CORE.CORE_SH_ANAG SET BLOCKWFS=? WHERE ID_SH=?";
				$ret = $this->_db->updateDb($sql ,$values);
				/*echo $this->_db->getsqlQuery();
				die();*/
				
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