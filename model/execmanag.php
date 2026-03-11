<?php 
		
		
	class execmanag_model
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
		
		public function getf()
		{
			try
			{
					$sql = "select * from dual";
                $res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}
		
		
		public function SchedFile($IDOBJ,$TYPE)
		{
			try
			{
				$ret =[];
				$extension = "";				
				$SSHUSR=$_SESSION["SSHUSR"];
				$SERVER=$_SESSION["SERVER"];
			  
				$Label="LOG:";
				if ( $TYPE != "LSH"  ){
				  $sql = "SELECT LOG_EXEC_MANAG AS FILE,TARGET FROM WORK_CORE.EXEC_MANAG_OBJECTS WHERE ID_OBJECT = $IDOBJ";
				  
				  IF ( $TYPE == "S"  ){
					$sql = "SELECT '/area_staging_TAS/'||TARGET_DIR||'/'||TARGET AS FILE,TARGET FROM WORK_CORE.EXEC_MANAG_OBJECTS WHERE ID_OBJECT = $IDOBJ";
					$Label="FILE";
				  }
				  $res = $this->_db->getArrayByQuery($sql);	
				  
				  foreach ($res as $row) {
					$File=$row['FILE'];
					$filenameWithExtension = basename($row['TARGET']);
					$extension = pathinfo($filenameWithExtension, PATHINFO_EXTENSION);
					$Target = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
				  }
				} else {
				$filenameWithExtension = basename($IDOBJ);
				// Ottieni solo l'estensione
				$extension = pathinfo($filenameWithExtension, PATHINFO_EXTENSION);
				// Ottieni il nome del file senza estensione
				$Target = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
				$File=$IDOBJ;
				$IDOBJ="";
				}
				
				$TestoLog = shell_exec("ssh $SSHUSR@$SERVER \"more $File\" ");
				//$TestoFile = "-------------------------------------------------------------------------------------<BR>";
				//$TestoFile .= preg_replace("/\r\n|\r|\n/",'<br/>',$TestoLog);
				//$TestoFile .= "-------------------------------------------------------------------------------------";
				//echo $_SESSION['PSITO'];

				$Dt = date("YmdHis");
				shell_exec('rm -f ' . $_SESSION['PSITO'] . '/TMP/' . $Target . '*');
				$filename = $Target.".".$extension;
				file_put_contents($_SESSION['PSITO'] . '/TMP/' . $filename, $TestoLog);
				shell_exec('chmod 774 ' . $_SESSION['PSITO'] . '/TMP/' . $Target . '_*');
			/*	$Dt=date("YmdHis");
				shell_exec('rm -f '.$_SESSION['PSITO'].'/TMP/'.$Target);
				$filename=$Target;	
				file_put_contents($_SESSION['PSITO'].'/TMP/'.$filename, $TestoLog );*/
				$ret['titolo']="$Label : ($IDOBJ) $File";	
				$ret['filename'] = $filename;		 
				$ret['TestoLog'] = $sql." -------------- ".$TestoLog;		 
				 return $ret;
				}
				catch(Exception $e)
				{
					//echo "ddd";
					throw $e; 	
				}
				
			}
			
			
			
			
	 public function Addf()
		{
			try
			{	
				
				$sql = "INSERT INTO *****";
			
			
			$last_id = $this->_db->insertDb($sql, array('','',''));
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
	 public function Removef($id)
		{
			try
			{	
				
			$sql = "DELETE FROM WORK_CORE.CORE_ALERT_MAIL WHERE ID_SH = ?";
			
			  $ret = $this->_db->deleteDb($sql, array($id));
			    return $ret;	
			}
			catch (Exception $e) 
			{
			    $this->_db->close_db();	
            	throw $e;
        	}
		}

		        //update record
		public function updatef()
		{
			
			try
			{	
        $sql = "UPDATE set b =? where c=? ";      

			    $values = array($v1, $v2);
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
		
		public function callf()
		{
			try
			{
		    $CallPlSql='CALL WFS.K_CONFIG.RimuoviValidazione(?, ?, ?, ?, ?, ? )';
            $stmt = db2_prepare($conn, $CallPlSql);

            $IdVal=$_POST['IdDip']; 
            $IdLeg=$_POST['IdLeg'];
			
			    $values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdVal',
					'value' => $IdVal,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'IdLeg',
					'value' => $IdLeg,
					'type' => DB2_PARAM_IN
					],
					[
					'name' => 'User',
					'value' => $_SESSION['codname'],
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'Errore',
					'value' => $Errore,
					'type' => DB2_PARAM_OUT
					],
					['name' => 'Note',
					'value' => $Note,
					'type' => DB2_PARAM_OUT
					]
					];	
            
          /*  db2_bind_param($stmt, 1, "IdWorkFlow"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdVal"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "IdLeg"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "User"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Errore"           , DB2_PARAM_OUT);
            db2_bind_param($stmt, 6, "Note"             , DB2_PARAM_OUT);*/       
	
	
			$ret = $this->_db->callDb($CallPlSql ,$values);return $ret;
	 			
		
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