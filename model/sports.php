<?php

class sports_model
	{
		
		// set database config for mysql
		
		// open mysql data base
		public $_db;
		 /**
		 * {@inheritDoc}
		 * @see db_driver::__construct()
		 */
		public function __construct()
		{
		    $this->_db = new db_driver();
		    
		}
		    
	
		public function insertRecord($obj)
		{
			try
			{	
			$values = array($obj->category,$obj->name);
			$last_id = $this->_db->insertDb("INSERT INTO sports (category,name) VALUES (?, ?)", $values, "ss");
				return $last_id;
			}
			catch (Exception $e) 
			{
			    $this->_db->close_db();	
            	throw $e;
        	}
		}
        //update record
		public function updateRecord($obj)
		{
			try
			{	
				
				$values = array($obj->category,$obj->name,$obj->id);
				$ret = $this->_db->updateDb("UPDATE sports SET category=?,name=? WHERE id=?", $values, "ssi");
				
				return $ret;
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
			    $ret = $this->_db->deleteDb("DELETE FROM sports WHERE id=?", array($id),"i");
			    return $ret;	
			}
			catch (Exception $e) 
			{
			    $this->_db->closeDb();
            	throw $e;
        	}		
        }   
        // select record    
        public function fetchAssoc($id)
        {
            $this->selectRecord($id);
            $ret = $this->_db->fetchAssocdb();
           // print_r($ret);
            $this->_db->db_log_error("Risultato",'fetchAssoc',$ret);
            return $ret;
        }
        
        
		public function selectRecord($id)
		{
			try
			{
			    if($id>0)
    				{	
    				    $res = $this->_db->selectDb("SELECT * FROM sports WHERE id=?",array($id),"i");
    					
    				}
                else
                    {
                        $res = $this->_db->selectDb("SELECT * FROM sports");	
                    }		
                    $this->_db->db_log_error("selectRecord");
                    $this->_db->db_log_error($this->_db->getsqlQuery());
                    
                    
                return $res;
			}
			catch(Exception $e)
			{
				
				throw $e; 	
			}
			
		}
	}

?>