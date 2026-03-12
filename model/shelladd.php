<?php 
		
		
	class shelladd_model
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

		public function getDbName()
		{
			return $this->_db->getDb();
		}

		private function ignoreParallColumn()
		{
			return $this->_db->getDb() == 'TASPCWRK';
		}

		private function getParallSelect()
		{
			if ($this->ignoreParallColumn()) {
				return 'null PARALL';
			}

			return 'PARALL';
		}
		
		public function getShList()
		{
			try
			{
					$sql = "SELECT ID_SH, SHELL, SHELL_PATH, " . $this->getParallSelect() . "
					FROM WORK_CORE.CORE_SH_ANAG 
					ORDER BY SHELL_PATH, SHELL";
                $res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
			
		}		
		
		public function getTableList()
		{
			try
			{
				$sql = "SELECT ID_SH, SHELL, SHELL_PATH, " . $this->getParallSelect() . "
					FROM WORK_CORE.CORE_SH_ANAG 
					ORDER BY SHELL_PATH, SHELL";
                $res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
			
		}	
		
		

		public function updateShell($SHELL,$SHELL_PATH,$ID_SH,$PARALL)
		{
			try
			{
					if ($this->ignoreParallColumn()) {
						$sql = "update 
						 WORK_CORE.CORE_SH_ANAG 
						SET SHELL = ? , SHELL_PATH = ?
						WHERE ID_SH = ?
						";
                			$res = $this->_db->updateDb($sql,[$SHELL,$SHELL_PATH,$ID_SH]);
					} else {
						$sql = "update 
						 WORK_CORE.CORE_SH_ANAG 
						SET SHELL = ? , SHELL_PATH = ?, PARALL = ?
						WHERE ID_SH = ?
						";
                			$res = $this->_db->updateDb($sql,[$SHELL,$SHELL_PATH,$PARALL,$ID_SH]);
					}	
				 
                return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
			
		}
		
		public function addShell($SHELL, $SHELL_PATH)
		{
			try
			{   
			$res= false;
					$sql = "SELECT ID_SH
					FROM WORK_CORE.CORE_SH_ANAG 
					WHERE SHELL_PATH = ? and  SHELL = ?
					ORDER BY SHELL_PATH, SHELL";
                $vres = $this->_db->getArrayByQuery($sql,array( $SHELL_PATH, $SHELL));	
			if(!$vres){
				$sql='CALL WORK_CORE.K_CORE.Sh_Anag(?,?,?)';
				$ShellName=$SHELL;
				$ShellPath=$SHELL_PATH;
				$IdOutSh=0;
				$values = [
							[
								'name' => 'ShellName',
								'value' => $ShellName,
								'type' => DB2_PARAM_IN
								],
								[
								'name' => 'ShellPath',
								'value' => $ShellPath,
								'type' => DB2_PARAM_IN
								],
								[
								'name' => 'IdOutSh',
								'value' => $IdOutSh,
								'type' => DB2_PARAM_OUT
								]								
								
								];	
				
				
				/*db2_bind_param($stmt, 1, "ShellName"  , DB2_PARAM_IN);
				db2_bind_param($stmt, 2, "ShellPath"  , DB2_PARAM_IN);
				db2_bind_param($stmt, 3, "IdOutSh"    , DB2_PARAM_OUT);*/
				$ret = $this->_db->callDb($sql ,$values);
				
				//$ret = $this->_db->updateDb($sql, array($SHELL, $SHELL_PATH,0));
				$res= true;
				
			}		
                return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
			
		}
		
		public function removeSh($ID_SH)
		{
			try
			{	
				
			$sql = "DELETE FROM WORK_CORE.CORE_SH_ANAG WHERE ID_SH = ?";
			
			  $ret = $this->_db->deleteDb($sql, array($ID_SH));
			    return $ret;	
			}
			catch (Exception $e) 
			{
			    $this->_db->close_db();	
            	throw $e;
        	}
		}
		

	}
	
?>