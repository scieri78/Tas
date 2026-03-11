<?php

class emailanag_model
	{
		
		// set database config for mysql
		
		// open mysql data base
	    private $_db;
		private $tableSpace ="WORK_CORE";
		private $tableName ="CORE_MAIL_ANAG";
		private $strTableSpace;
		 /**
		 * {@inheritDoc}
		 * @see db_driver::__construct()
		 */
		public function __construct()
		{
		    $this->_db = new db_driver();
		    $this->strTableSpace = '';
		    if ($this->_db->getDriver() =='db2')
		    {
		        $this->strTableSpace = $this->tableSpace.".";;
		    }
		  
		    
		}
		    
	
		public function AddMail()
		{
			try
			{	
			$AddName     =$_POST['AddName'];
			$AddUsername =$_POST['AddUsername'];
			$AddMail     =$_POST['AddMail'];
			//$values = array($obj->category,$obj->name);
			
			if($this->_db->getDriver()=="mysql")
			{
			    $sqlQuery ="INSERT INTO ".$this->strTableSpace.$this->tableName."
                         (NAME,USERNAME,MAIL,TECNIC,VALID,CHIUSURA,CCN)
                   VALUES (?, ?, ?, 'N','Y','N','N');";
			    
			    
			}else{
			    $sqlQuery ="INSERT INTO ".$this->strTableSpace.$this->tableName."
							(ID_MAIL_ANAG,NAME,USERNAME,MAIL,TECNIC,VALID,CHIUSURA,CCN) 
							VALUES
							((SELECT Max(NVL(ID_MAIL_ANAG,0))+1 FROM ".$this->strTableSpace.$this->tableName."), ?, ?, ?, 'N','Y','N','N');";
		   
			}
			$last_id = $this->_db->insertDb($sqlQuery, array($AddName,$AddUsername,$AddMail), "sss");
		    return $last_id;
			}
			catch (Exception $e) 
			{
			    $this->_db->close_db();	
            	throw $e;
        	}
		}
        //update record
		public function updateRecord($field,$val,$id)
		{
			try
			{	
				
			    $values = array($val,$id);
				$ret = $this->_db->updateDb("UPDATE ".$this->strTableSpace.$this->tableName." SET ".$field." =? WHERE ID_MAIL_ANAG =?", $values, "si");
				
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
         // delete record
		public function deleteRecord($id)
		{	
			try{
			    $ret = $this->_db->deleteDb("DELETE FROM  ".$this->strTableSpace.$this->tableName." WHERE ID_MAIL_ANAG = ?", array($id),"i");
			    return $ret;	
			}
			catch (Exception $e) 
			{
			    $this->_db->closeDb();
            	throw $e;
        	}		
        }   
           
        
        
        
		public function getListMail($id=0)
		{
			try
			{
			    $sql = "SELECT * FROM ".$this->strTableSpace.$this->tableName." WHERE 1=1";
			    
			    if($id>0)
    				{	
    				    $sql.=" and ID_MAIL_ANAG= ?";
    				    $res = $this->_db->getArrayByQuery($sql,array($id),"i");
    					
    				}
                else
                    {
                        $sql.=" ORDER BY USERNAME";
                        $res = $this->_db->getArrayByQuery($sql);	
                    }	
                    
                
                    $this->_db->db_log_debug("getListMail","sql",$this->_db->getsqlQuery());
                    $this->_db->db_log_debug("getListMail","dati",$res);
                    
                    
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