<?php 
		
		
	class alertmail_model
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
					$sql = "SELECT ID_SH, SHELL, SHELL_PATH
					FROM WORK_CORE.CORE_SH_ANAG 
					WHERE ID_SH NOT IN ( SELECT ID_SH FROM WORK_CORE.CORE_ALERT_MAIL )
					ORDER BY SHELL_PATH, SHELL";
                $res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
			
		}
		
		public function getListMail()
		{
			try
			{
				$sql = "SELECT ID_MAIL_ANAG, MAIL, NAME, USERNAME FROM WORK_CORE.CORE_MAIL_ANAG WHERE VALID = 'Y' ORDER BY NAME";

                $res = $this->_db->getArrayByQuery($sql);	
				
				$ListMail=array();
				foreach ($res as $row) {
					$ID_MAIL_ANAG=$row['ID_MAIL_ANAG'];
					$MAIL=$row['MAIL'];
					$NAME=$row['NAME'];
					$USERNAME=$row['USERNAME'];
					array_push($ListMail, array("$ID_MAIL_ANAG","$MAIL","$NAME","$USERNAME") );
				}
				   
                return $ListMail;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
			
		}
		
		public function getTableList($id=null)
		{
			try
			{
				$sql="SELECT CAM.ID_SH ID_SH, SHELL, SHELL_PATH, CAM.ID_MAIL_ANAG, FLG_START, FLG_END, CMA.MAIL
					FROM WORK_CORE.CORE_ALERT_MAIL  CAM
					JOIN WORK_CORE.CORE_SH_ANAG CSA
					ON CAM.ID_SH = CSA.ID_SH
					JOIN WORK_CORE.CORE_MAIL_ANAG CMA
					ON CAM.ID_MAIL_ANAG = CMA.ID_MAIL_ANAG
					where 1=1 ";
					
			if($id)		
				{
				$sql.="and CAM.ID_SH=$id ";
				}
					
				$sql.="ORDER BY SHELL_PATH, SHELL, CMA.MAIL";
					
				

                $res = $this->_db->getArrayByQuery($sql);	
                    
                return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
			
		}
			
	 public function AddMail($ID_SH, $ID_MAIL_ANAG, $FLG_START, $FLG_END)
		{
			try
			{	
				
				$sql = "INSERT INTO WORK_CORE.CORE_ALERT_MAIL(ID_SH, ID_MAIL_ANAG, FLG_START, FLG_END, VALID) VALUES(?,?,?,?,?)";
			
			
			$last_id = $this->_db->insertDb($sql, array($ID_SH, $ID_MAIL_ANAG, $FLG_START, $FLG_END, 'Y'));

		    return $last_id;
			}
			catch (Exception $e) 
			{
			    $this->_db->close_db();	
            	throw $e;
        	}
		}
		
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
		
 public function RemoveMail($RemIdSh,$RemIdMailAnag)
		{
			try
			{	
				
			$sql = "DELETE FROM WORK_CORE.CORE_ALERT_MAIL WHERE ID_SH = ? AND ID_MAIL_ANAG = ? ";
			
			  $ret = $this->_db->deleteDb($sql, array($RemIdSh,$RemIdMailAnag));
			    return $ret;	
			}
			catch (Exception $e) 
			{
			    $this->_db->close_db();	
            	throw $e;
        	}
		}

		//update record
		/*
		 public function updateMail($flag, $EnIdSh, $EnIdMailAnag)
		 {
			
			 try
			 {	
         $sql = "UPDATE WORK_CORE.CORE_ALERT_MAIL SET FLG_START = ? WHERE ID_SH = ? AND ID_MAIL_ANAG = ? ";      

			     $values = array($flag, $EnIdSh);
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
		
		*/

		public function updateShMail($IDSH,$IDANAGMAIL,$TYPEMAIL,$FLAG)
		{
			
			try
			{	
         $sql = "UPDATE WORK_CORE.CORE_ALERT_MAIL SET";      
        if ( $TYPEMAIL == "Start" ){ 
          $sql.=" FLG_START = '$FLAG' ";
        }else{
          $sql.=" FLG_END = '$FLAG' ";
        }
        $sql.=" WHERE ID_SH = $IDSH AND ID_MAIL_ANAG = $IDANAGMAIL ";     

		
			   
				$ret = $this->_db->updateDb($sql,array());
				
				return $ret;
			}
			catch (Throwable $e) {
			  var_dump($e);
			  die();
			    throw $e;
			    
			}
			catch (Exception $e) 
			{
			    $this->_db->close_db();
            	throw $e;
        	}
        }	 



 public function addShMail($ID_SH, $ID_MAIL_ANAG, $FLG_START, $FLG_END)
		{
			try
			{	
				
			$sql = "INSERT INTO WORK_CORE.CORE_ALERT_MAIL(ID_SH, ID_MAIL_ANAG, FLG_START, FLG_END, VALID) 
			VALUES(?,?,?,?,?)";
			
			$last_id = $this->_db->insertDb($sql, array($ID_SH, $ID_MAIL_ANAG, $FLG_START, $FLG_END, 'Y'));

		    return $last_id;
			}catch (Throwable $e) {
			  var_dump($e);
			  die();
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