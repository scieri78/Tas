<?php

class mailconf_model
	{
		
		// set database config for mysql
		
		// open mysql data base
	    private $_db;
		
		private $_tableCoreMailAssign = 'WORK_CORE.CORE_MAIL_ASSIGN';

		 /**
		 * {@inheritDoc}
		 * @see db_driver::__construct()
		 */
		public function __construct()
		{
		    $this->_db = new db_driver();	
		}
		
		
		public function RemoveMail($SelMail)
		{	
			try{
				$SetVar=explode(';',$SelMail);
				$IdSh=$SetVar[0];
				$Type=$SetVar[1];
				$IdMail=$SetVar[2];
				$SelIdSh=$IdSh;
			$sql = "DELETE FROM ".$this->_tableCoreMailAssign." WHERE ID_SH = ? AND TYPE = ? AND ID_MAIL_ANAG = ?";
			    $ret = $this->_db->deleteDb($sql, array($SelIdSh,$Type,$IdMail));
			    return $ret;	
			}
			catch (Exception $e) 
			{
			    $this->_db->closeDb();
            	throw $e;
        	}		
        }
		
		public function AddMail($SelMail)
		{
			try
			{	
				$SetVar=explode(';',$SelMail);
				$IdSh=$SetVar[0];
				$Type=$SetVar[1];
				$IdMail=$SetVar[2];
				$SelIdSh=$IdSh;
				$sql = "INSERT INTO ".$this->_tableCoreMailAssign."(ID_SH,TYPE,ID_MAIL_ANAG,VALID) VALUES (?,?,?,'Y')";
			
			
			$last_id = $this->_db->insertDb($sql, array($SelIdSh,$Type,$IdMail));
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
		    
	
	public function updateShMail($val,$id)
		{
			try
			{	
				
			    $values = array($val,$id);
				$ret = $this->_db->updateDb("UPDATE WORK_CORE.CORE_SH_ANAG SET MAIL=? WHERE ID_SH = ?" ,$values);
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
		
		
		
		public function updateMail($mval,$SelMail)
		{
			try
			{	
				$SetVar=explode(';',$SelMail);
				$IdSh=$SetVar[0];
				$Type=$SetVar[1];
				$IdMail=$SetVar[2];
				$SelIdSh=$IdSh;
			    $values =  array($mval,$SelIdSh,$Type,$IdMail);
				$sql ="UPDATE ".$this->_tableCoreMailAssign." SET VALID = ? WHERE ID_SH = ? AND TYPE = ? AND ID_MAIL_ANAG = ?";
				$ret = $this->_db->updateDb($sql,$values);
				//echo $this->_db->getsqlQuery();
				//die();
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
		
		
           
       public function getSelEnableShMail()
        {
			try
			{
				
			/*	$sql = "SELECT ID_SH,SHELL,SHELL_PATH,SH_DEBUG,DB_DEBUG, SH_MAIL_ENABLE, TYPE, ID_MAIL_ANAG, MAIL, MAIL_ENABLE, MULTI_SEND 
		FROM WORK_CORE.V_CORE_MAIL_SETTING WHERE SH_MAIL_ENABLE = 'N' ORDER BY SHELL_PATH,SHELL";*/
		
				$sql = "SELECT distinct ID_SH,SHELL,SHELL_PATH,SH_DEBUG,DB_DEBUG, SH_MAIL_ENABLE
		FROM WORK_CORE.V_CORE_MAIL_SETTING WHERE SH_MAIL_ENABLE = 'N'  ORDER BY SHELL_PATH,SHELL";
				$res = $this->_db->getArrayByQuery($sql);	
				
			//	$this->_db->printSql();
			//	die();
				
			 return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}	
        }
		
		
		public function getShListMail($idSh=null)
		{
			try
			{
			    
			$sql = "SELECT ID_SH,SHELL,SHELL_PATH,SH_DEBUG,DB_DEBUG, SH_MAIL_ENABLE, TYPE, ID_MAIL_ANAG, MAIL, MAIL_ENABLE, MULTI_SEND
			,(SELECT count(*) FROM ".$this->_tableCoreMailAssign." SET WHERE ID_SH = V.ID_SH AND TYPE = 'R_TO' ) CNT_RTO
			,(SELECT count(*) FROM ".$this->_tableCoreMailAssign." SET WHERE ID_SH = V.ID_SH AND TYPE = 'R_CC' ) CNT_RCC
			,(SELECT count(*) FROM ".$this->_tableCoreMailAssign." SET WHERE ID_SH = V.ID_SH AND TYPE = 'T_TO' ) CNT_TTO
			,(SELECT count(*) FROM ".$this->_tableCoreMailAssign." SET WHERE ID_SH = V.ID_SH AND TYPE = 'T_CC' ) CNT_TCC
			,ATTACH
			FROM WORK_CORE.V_CORE_MAIL_SETTING V WHERE SH_MAIL_ENABLE = 'Y' ";
			if($idSh)
			{$sql .="and ID_SH = ".$idSh." ";}
			
			$sql .="ORDER BY SHELL_PATH,SHELL";
			    
			  
			$res = $this->_db->getArrayByQuery($sql);	
              	
			       
     
		
                    
            return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}
		
		
		
		public function getListMail()
		{
			try
			{
			$sql = "SELECT ID_MAIL_ANAG, MAIL, NAME, USERNAME FROM WORK_CORE.CORE_MAIL_ANAG WHERE VALID = 'Y' ORDER BY NAME";
			    
			  
			$res = $this->_db->getArrayByQuery($sql);	
              	
			       
     
		
                    
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