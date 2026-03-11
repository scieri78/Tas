<?php 
		
		
	class dipstatus_model
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
		
		public function getTeam()
		{
			try
			{
				$sql="SELECT ID_TEAM, TEAM FROM WFS.TEAM ORDER BY TEAM";
                $res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}
		public function getWorkflow($SelIdTeam)
		{
			try
			{
				$sql="SELECT ID_WORKFLOW, WORKFLOW FROM WFS.WORKFLOW WHERE ID_TEAM = $SelIdTeam ORDER BY WORKFLOW";
                $res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}
		
		public function getFlussi($SelWkf)
		{
			try
			{
				 $sql="SELECT ID_FLU, FLUSSO FROM WFS.FLUSSI WHERE ID_WORKFLOW = $SelWkf AND ID_FLU IN (
						SELECT ID_FLU FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = $SelWkf
						)ORDER BY FLUSSO";
                $res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
		}	
		public function getLegameFlussi($SelWkf,$SelFls)
		{
			try
			{
				$sql="SELECT DISTINCT TIPO FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = $SelWkf AND ID_FLU = $SelFls ORDER BY TIPO";
                $res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}
			
		public function getDipendenza($SelWkf,$SelFls,$SelTp)
		{
			try
			{
				 $sql="SELECT ID_DIP, 
						CASE  
						WHEN TIPO = 'F' THEN ( SELECT FLUSSO       FROM WFS.FLUSSI       WHERE ID_FLU  = LF.ID_DIP )
						WHEN TIPO = 'V' THEN ( SELECT VALIDAZIONE  FROM WFS.VALIDAZIONI  WHERE ID_VAL  = LF.ID_DIP )
						WHEN TIPO = 'E' THEN ( SELECT ELABORAZIONE FROM WFS.ELABORAZIONI WHERE ID_ELA  = LF.ID_DIP )
						WHEN TIPO = 'C' THEN ( SELECT CARICAMENTO  FROM WFS.CARICAMENTI  WHERE ID_CAR  = LF.ID_DIP )
						WHEN TIPO = 'L' THEN ( SELECT LINK         FROM WFS.LINKS        WHERE ID_LINK = LF.ID_DIP )
						END NAME 
						FROM WFS.LEGAME_FLUSSI LF
						WHERE ID_WORKFLOW = $SelWkf AND ID_FLU = $SelFls AND TIPO = '$SelTp' ORDER BY 2";
                $res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}
			
		public function getIdProcess($SelWkf)
		{
			try
			{
				$sql="SELECT ID_PROCESS FROM WORK_CORE.ID_PROCESS WHERE ID_WORKFLOW = $SelWkf ORDER BY ID_PROCESS DESC";
                $res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}
		
			
			
			
	 public function addStato( $SelIdProc
               ,$SelWkf
               ,$SelFls
               ,$SelTp
               ,$SelDp
			   ,$User)
		{
			try
			{	
				
				$Sql="INSERT INTO WFS.ULTIMO_STATO() ";
			
			
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
		
			
	public function getStato($SelIdProc ,$SelWkf ,$SelFls ,$SelTp ,$SelDp)	
	{
		
		$sql="SELECT * FROM WFS.ULTIMO_STATO  
					 WHERE 1=1
					 AND ID_PROCESS  = ?
					 AND ID_WORKFLOW = ?					 
					 AND ID_FLU      = ?
					 AND TIPO        = ?
					 AND ID_DIP      = ?";
					 $SelVal =[$SelIdProc ,$SelWkf ,$SelFls ,$SelTp ,$SelDp];
				$ret = $this->_db->getArrayByQuery($sql, $SelVal);
					
		return $ret;
	}		
	public function getCodaStorico($SelIdProc ,$SelWkf ,$SelFls ,$SelTp ,$SelDp)	
	{
		
		$sql="SELECT * FROM WFS.CODA_STORICO  
					 WHERE 1=1
					 AND ID_PROCESS  = ?
					 AND ID_WORKFLOW = ?					 
					 AND ID_FLU      = ?
					 AND TIPO        = ?
					 AND ID_DIP      = ?";
					 $SelVal =[$SelIdProc ,$SelWkf ,$SelFls ,$SelTp ,$SelDp];
				$ret = $this->_db->getArrayByQuery($sql, $SelVal);
					
		return $ret;
	}	
		
	public function countStato($SelIdProc ,$SelWkf ,$SelFls ,$SelTp ,$SelDp)	
	{
		$sql="SELECT count(*) CNT FROM WFS.ULTIMO_STATO  
					 WHERE 1=1
					 AND ID_PROCESS  = ?
					 AND ID_WORKFLOW = ?					 
					 AND ID_FLU      = ?
					 AND TIPO        = ?
					 AND ID_DIP      = ?";
					 $SelVal =[$SelIdProc ,$SelWkf ,$SelFls ,$SelTp ,$SelDp];
					$DatiStati = $this->_db->getArrayByQuery($sql, $SelVal);
					 foreach ($DatiStati as $row) {
					   $vCnt=$row['CNT'];   
				  }
		return $vCnt;
	}	
	
	public function insertStato($SelSt,$SelIdProc ,$SelWkf ,$SelFls ,$SelTp ,$SelDp ,$User,$note)
	{
		 $sql="INSERT INTO WFS.ULTIMO_STATO
							 ( ID_PROCESS ,ID_WORKFLOW ,ID_FLU ,TIPO ,ID_DIP ,INIZIO ,FINE ,ESITO ,NOTE ,LOG ,FILE ,UTENTE)
							VALUES ( ? ,? ,? ,? ,? ,CURRENT_TIMESTAMP ,CURRENT_TIMESTAMP ,? ,? ,null ,null ,? )";
		$InsVal =[$SelIdProc ,$SelWkf ,$SelFls ,$SelTp ,$SelDp ,$SelSt ,$note,$User]; 
		//echo $this->_db->SqlDebug($sql,$InsVal);
		$last_id = $this->_db->insertDb($sql, $InsVal);				
		return $last_id;
	}
	
	public function insertCodaStorico($SelSt,$SelIdProc ,$SelWkf ,$SelFls ,$SelTp ,$SelDp ,$User,$note)
	{
		$sql="INSERT INTO WFS.CODA_STORICO(ID_PROCESS,ID_WORKFLOW,ID_FLU,TIPO,ID_DIP,AZIONE,UTENTE,NOTE,ESITO,INIZIO,FINE,LOG,FILE)
									VALUES (?,?,?,?,?,'S',?,?,?,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,null,null)";
		$InsVal =[$SelIdProc ,$SelWkf ,$SelFls ,$SelTp ,$SelDp ,$User,$note,$SelSt]; 
		
		//echo $this->_db->SqlDebug($sql,$InsVal);
		$last_id = $this->_db->insertDb($sql, $InsVal);				
		return $last_id;
	}
	
	public function updateStato($SelSt,$SelIdProc ,$SelWkf ,$SelFls ,$SelTp ,$SelDp ,$User,$note='N')
	{
		 $sql="UPDATE WFS.ULTIMO_STATO SET
						INIZIO = CURRENT_TIMESTAMP
					   ,FINE   = CURRENT_TIMESTAMP
					   ,ESITO  = ?
					   ,WARNING  = 0
					   ,NOTE   = ?
					   ,UTENTE = ?
					   WHERE 1=1
					   AND ID_WORKFLOW = ?
					   AND ID_PROCESS  = ?
					   AND ID_FLU      = ?
					   AND TIPO        = ?
					   AND ID_DIP      = ?   
					   ";		
	//	echo	$this->_db->SqlDebug($sql,[$SelSt,$note,$User,$SelWkf,$SelIdProc,$SelFls,$SelTp,$SelDp]);
			
	$ret = $this->_db->updateDb($sql, [$SelSt,$note,$User,$SelWkf,$SelIdProc,$SelFls,$SelTp,$SelDp]);
					
				
		return $ret;
	}
	
	public function resetWarningStato($SelSt,$SelIdProc ,$SelWkf ,$SelFls ,$SelTp ,$SelDp ,$User)
	{
		  $sql="UPDATE WFS.ULTIMO_STATO SET 
					  WARNING  = 0
					 ,UTENTE = ?					
					 WHERE 1=1
					 AND ID_WORKFLOW = ?
					 AND ID_PROCESS  = ?
					 AND ID_FLU      = ?
					 AND TIPO        = ?
					 AND ID_DIP      = ?  
					 ";
		$ret = $this->_db->updateDb($sql, [$User,$SelWkf,$SelIdProc,$SelFls,$SelTp,$SelDp]);
				
		return $ret;
	}
	
		
	public function getAction($Azione,$SelSt,$SelIdProc ,$SelWkf ,$SelFls ,$SelTp ,$SelDp,$User)
		{
		try
			{
			if ($Azione == "Salva" ){
					switch ($SelSt) {
						case 'F':
							$vCnt = $this->countStato($SelIdProc ,$SelWkf ,$SelFls ,$SelTp ,$SelDp ,$User);
							if($vCnt == 0)
							{
								$note= 'Valido Step Forzato';
								$this->insertStato($SelSt,$SelIdProc ,$SelWkf ,$SelFls ,$SelTp ,$SelDp ,$User,$note);
								
							}else{
							    $note= 'Svalido Step Forzato';
								$this->updateStato($SelSt,$SelIdProc ,$SelWkf ,$SelFls ,$SelTp ,$SelDp ,$User,$note);
								
							}
							break;
						case 'N':
							$this->updateStato($SelSt,$SelIdProc ,$SelWkf ,$SelFls ,$SelTp ,$SelDp ,$User);
							$this->insertCodaStorico($SelSt,$SelIdProc ,$SelWkf ,$SelFls ,$SelTp ,$SelDp ,$User,$note);
							//echo "i equals 1";
							break;
						case 'R':
						   $this->resetWarningStato($SelSt,$SelIdProc ,$SelWkf ,$SelFls ,$SelTp ,$SelDp ,$User);
							break;
						}
				}
			return $this->getStato($SelIdProc ,$SelWkf ,$SelFls ,$SelTp ,$SelDp);
			}catch (Throwable $e) {
			 throw $e;
							
			}catch (Exception $e) 
			{
			$this->_db->close_db();
			throw $e;
			}
		}
	
}
	
?>