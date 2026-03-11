<?php 
		
		
	class idprocess_model
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
		
		/**
		 * createExcel
		 *
		 * @param  mixed $GRUPPO
		 * @return void
		 */
		public function createExcel($GRUPPO = "INCONTRA")
			{
				$sql = "select * from WORK_RULES.CONTROLLI_LANCI Cl
				inner join WORK_RULES.CONTROLLI_ANAG on CA.ID_CONTR = CL.ID_CONTR 
				where 1=1 ";
				$sql.="and GRUPPO='$GRUPPO' ";
				$res = $this->_db->getArrayByQuery($sql);
				$xlsx = Shuchkin\SimpleXLSXGen::fromArray( $res );
				$xlsx->saveAs($_SESSION['PSITO'].'/TMP/controlli_incontra.xlsx'); 
			}
		
		public function getWorkFlow($IdTeam)
		{
			try
			{
				$res = null;
				if ( $IdTeam != "" ){
				$sql="SELECT ID_WORKFLOW,WORKFLOW,UPPER(DESCR) DESCR ,
				( SELECT count(*) FROM WORK_CORE.ID_PROCESS WHERE ID_WORKFLOW = W.ID_WORKFLOW AND FLAG_STATO != 'C' ) CONTA
				FROM WFS.WORKFLOW W 
				WHERE 1=1
				AND ABILITATO = 'Y' 
				AND ID_TEAM = '$IdTeam' 
				ORDER BY WORKFLOW";
                $res = $this->_db->getArrayByQuery($sql);	
				}
                return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}	
		
		public function getTeams()
		{
			try
			{				
				
				$sql="SELECT ID_TEAM,TEAM FROM WFS.TEAM ORDER BY TEAM";
                $res = $this->_db->getArrayByQuery($sql);	
				
                return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}
		
		
public function AzioneUatoDip()
{
		
	try
	{
	$Azione=$_POST['Azione'];
	$IdWorkFlow=$_REQUEST['IdWorkFlow'];
	$IdTeam=$_POST['IdTeam'];
	$FromId=$_POST['FromId'];
	$ToId=$_POST['ToId'];
	$ShowStatusCopy=$_POST['ShowStatusCopy'];
	$Errore=0;
	$Note="";
	$SSHUSR=$_SESSION['SSHUSR'];
    $PRGDIR=$_SESSION['PRGDIR'];
    $SERVER=$_SESSION['SERVER'];
	switch ($Azione){
	 case "Rimuovi" :
	  $SvuotaId=$_POST['SvuotaId'];
	  $CancellaId=$_POST['CancellaId'];
	  
	  if ( $SvuotaId != "" ){
		  $az="SvuotaId";
	     shell_exec("sh $PRGDIR/SvuotaIdP.sh '${SSHUSR}' '${SERVER}' '${SvuotaId}'");
	  }
	  if ( $CancellaId != "" ){
		  $az="CancellaId";
	     shell_exec("sh $PRGDIR/RimuoviIdP.sh '${SSHUSR}' '${SERVER}' '${CancellaId}'");
	  }
	
	  break;  		
	 case "Copia" :
	  $ShowStatusCopy="ShowStatusCopy";
	  shell_exec("sh $PRGDIR/CopiaIdP.sh '${SSHUSR}' '${SERVER}' '${FromId}' '${ToId}'");
		
      break;    
     case "Aggiungi" :
            
      $CallPlSql='CALL WORK_RULES.K_ID_PROCESS.AddIdProcess(?, ?, ?, ?, ?, ?, ?, ?, ?)';
      //$stmt=db2_prepare($conn, $CallPlSql);
      
      $Descr=$_POST['Descr'];
      $Esercizio=$_POST['Esercizio'];
	  $Tipo=$_POST['Tipo'];
	  $Tipo='Q';
      
      $Anno=date('Y',strtotime($Esercizio))+0;
      $Mese=date('m',strtotime($Esercizio))+0;
	  
	  $values = [
					 [
					'name' => 'Anno',
					'value' => $Anno,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'Mese',
					'value' => $Mese,
					'type' => DB2_PARAM_IN
					],  [
					'name' => 'Descr',
					'value' => $Descr,
					'type' => DB2_PARAM_IN
					], [
					'name' => 'Tipo',
					'value' => $Tipo,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'IdTeam',
					'value' => $IdTeam,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
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

        
   /*   db2_bind_param($stmt, 1, "Anno"       , DB2_PARAM_IN);
      db2_bind_param($stmt, 2, "Mese"       , DB2_PARAM_IN);
      db2_bind_param($stmt, 3, "Descr"      , DB2_PARAM_IN);
	  db2_bind_param($stmt, 4, "Tipo"       , DB2_PARAM_IN);
      db2_bind_param($stmt, 5, "IdTeam"     , DB2_PARAM_IN);
	  db2_bind_param($stmt, 6, "IdWorkFlow" , DB2_PARAM_IN);
      db2_bind_param($stmt, 7, "User"       , DB2_PARAM_IN);
      db2_bind_param($stmt, 8, "Errore"     , DB2_PARAM_OUT);
      db2_bind_param($stmt, 9, "Note"       , DB2_PARAM_OUT);*/
      $ret = $this->_db->callDb($CallPlSql,$values);
	  return $ret;
      break;    
    }   
    
	}catch (Exception $e) 
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
		
		public function setReadonly($IdProc,$flag)
		{
			try
			{
				
				
			    if ( $IdProc != "" ){		
					$CallSql="UPDATE WORK_CORE.ID_PROCESS SET READONLY='$flag' WHERE ID_PROCESS = $IdProc";
					$this->_db->updateDb($CallSql, []);
				}
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
		
		public function setReadonlyY($AddRO)
		{
			try
			{	
				
				if ( $AddRO != "" ){		
				  $CallSql="UPDATE WORK_CORE.ID_PROCESS SET READONLY='Y' WHERE ID_PROCESS = $AddRO";
				 $ret=  $this->_db->updateDb($CallSql, []);
				}
				
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

	public function AllineaDate($IdProcess)
		{
			try
			{
			$sql="UPDATE
					WORK_CORE.ID_PROCESS
					SET INZ_VAL = 
						CASE 
						WHEN FREQUENZA = 'A' THEN
						  SUBSTR(ID_PROCESS, 1, 4)||'01'
						WHEN FREQUENZA = 'Q' THEN
						  SUBSTR(ID_PROCESS, 1, 4)||LPAD(SUBSTR(ID_PROCESS, 5, 2)-2,2,0)
						WHEN FREQUENZA = 'M' THEN
						  SUBSTR(ID_PROCESS, 1, 6)
						END 
					WHERE
						ID_PROCESS = ?";

			$ret = $this->_db->updateDb($sql, [$IdProcess]);
			return $ret;
			}
			catch (Throwable $e) {
			  //  $this->_db->close_db();
			    throw $e;
			    
			}
			catch (Exception $e) 
			{
			   // $this->_db->close_db();
            	throw $e;
        	}
        }	
		
		
		public function UpdateInzVal($IdProcess,$inizVal)
		{
			try
			{
			$sql="UPDATE
					WORK_CORE.ID_PROCESS
					SET INZ_VAL = ?
						
					WHERE
						ID_PROCESS = ?";

			$ret = $this->_db->updateDb($sql, [$inizVal,$IdProcess]);
			return $ret;
			}
			catch (Throwable $e) {
			  //  $this->_db->close_db();
			    throw $e;
			    
			}
			catch (Exception $e) 
			{
			   // $this->_db->close_db();
            	throw $e;
        	}
        }
		
		public function addSveccFunc($AddSvecc, $User)
		{
			try
			{	
				
				if ( $AddSvecc != "" ){
					$SqlList="INSERT INTO WORK_CORE.DELETE_LIST_ID_PROCESS (ID_PROCESS, USER) VALUES ($AddSvecc, '$User') ";
				$ret =$this->_db->insertDb($SqlList, []);
				}
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
		
		public function remSveccFunc($RemSvecc)
		{
			try
			{	
				
				if ( $RemSvecc != "" ){
					$SqlList="DELETE FROM WORK_CORE.DELETE_LIST_ID_PROCESS WHERE ID_PROCESS = $RemSvecc ";
					$this->_db->deleteDb($SqlList, []);
				}
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
		
		public function getFrequenza($IdWorkFlow)
		{
			try
			{	
					$SqlList="SELECT FREQUENZA FROM WFS.WORKFLOW  WHERE ID_WORKFLOW = $IdWorkFlow";
					$ret = $this->_db->getArrayByQuery($SqlList);
				
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
		
		public function getIdProc()
		{
			try
			{	
					$SqlList="SELECT ID_PROCESS FROM WORK_CORE.DELETE_LIST_ID_PROCESS WHERE FLG_DONE = 0 ORDER BY ID_PROCESS DESC";
					$ret = $this->_db->getArrayByQuery($SqlList);
				
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
		
		public function getTableData($SelFromId, $SelToId)
		{
			try
			{	
				$WhrCnd = "";
				$WhrCnd2 = "";
				$TOTDIFF=0;

			
				if ( $SelFromId != "" and $SelToId != "" ){
				   $WhrCnd=" AND FROM_ID_PROCESS = '$SelFromId' AND ID_PROCESS = '$SelToId' ";
				   $WhrCnd2=" AND DATAPARTITIONNAME = '$SelFromId' ";
				 } else {
				   if ( $SelFromId != "" ){
					 $WhrCnd=" AND FROM_ID_PROCESS = '$SelFromId' ";
					 $WhrCnd2=" AND DATAPARTITIONNAME = '$SelFromId' ";
				   }else{
					 $WhrCnd=" AND ID_PROCESS = '$SelToId' ";
					 $WhrCnd2=" AND DATAPARTITIONNAME = '$SelToId' ";
				   }
				 }
			
				$sql="SELECT *
					 ,timestampdiff(2,NVL(TMS_END_COPY,CURRENT_TIMESTAMP)-TMS_START_COPY) AS DIFF 
					 FROM (
					 SELECT FROM_ID_PROCESS, ID_PROCESS, TABSCHEMA, TABNAME, FLG_PART, FLG_DATA_FOUND, TMS_INSERT, TMS_START_COPY, TMS_END_COPY, ESITO
					   FROM WORK_CORE.COPY_STATUS_ID_PROCESS 
					   WHERE 1=1
					   AND LASTRUN = 1
					   $WhrCnd
					 UNION ALL
					   SELECT DISTINCT null FROM_ID_PROCESS, null ID_PROCESS,  TRIM(TABSCHEMA) TABSCHEMA, TRIM(TABNAME) TABNAME, 1 FLG_PART, 0 FLG_DATA_FOUND, null TMS_INSERT, null TMS_START_COPY, null TMS_END_COPY , null ESITO
						 FROM SYSCAT.DATAPARTITIONS 
						 WHERE 1=1
						  $WhrCnd2
						  AND TABNAME NOT LIKE '%ID_PROCESS%'
						  AND TABSCHEMA IN ( 'MVBS')         
						 AND (TRIM(TABSCHEMA), TRIM(TABNAME)) NOT IN ( SELECT TABSCHEMA, TABNAME FROM WORK_CORE.COPY_STATUS_ID_PROCESS WHERE 1=1 AND LASTRUN = 1 $WhrCnd )
					 UNION ALL
					   SELECT DISTINCT null FROM_ID_PROCESS, null ID_PROCESS,  TRIM(TABSCHEMA) TABSCHEMA, TRIM(TABNAME) TABNAME, 0 FLG_PART, 0 FLG_DATA_FOUND, null TMS_INSERT, null TMS_START_COPY, null TMS_END_COPY, null ESITO 
						  FROM SYSCAT.TABLES A
						  WHERE 1=1
						  AND TYPE = 'T'
						  AND (TABSCHEMA,TABNAME) NOT IN (SELECT TABSCHEMA,TABNAME FROM SYSCAT.DATAPARTITIONS WHERE TABSCHEMA =  A.TABSCHEMA   AND DATAPARTITIONID != 0  )
						  AND (TABSCHEMA,TABNAME)     IN (SELECT TABSCHEMA,TABNAME FROM SYSCAT.COLUMNS WHERE TABSCHEMA =  A.TABSCHEMA  AND COLNAME = 'ID_PROCESS'  )
						  AND TABNAME NOT LIKE '%ID_PROCESS%'
						  AND TABSCHEMA IN ( 'MVBS')
						  AND (TRIM(TABSCHEMA), TRIM(TABNAME)) NOT IN ( SELECT TABSCHEMA, TABNAME FROM WORK_CORE.COPY_STATUS_ID_PROCESS WHERE 1=1 AND LASTRUN = 1 $WhrCnd )
					 )
					 ORDER BY FROM_ID_PROCESS, FLG_DATA_FOUND, TABSCHEMA, TABNAME
					 ";				
					 $ret=$this->_db->getArrayByQuery($sql);
				
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
		
		public function getListaIdProc($IdWorkFlow, $SelIdTeam)
		{
			try
			{	
			   $SqlList="SELECT ID_PROCESS, DESCR,  ESER_ESAME, MESE_ESAME, ESER_MESE, FLAG_CONSOLIDATO, TIPO, READONLY,FLAG_STATO, DATA_APERTURA,INZ_VAL,
			   NVL((SELECT NOMINATIVO FROM WEB.TAS_UTENTI WHERE UPPER(USERNAME) = UPPER(TRIM(UTENTE)) ),UTENTE) UTENTE
			   FROM WORK_CORE.ID_PROCESS
			   WHERE 1=1
			   AND ID_WORKFLOW = '$IdWorkFlow'
			   AND ID_TEAM = '$SelIdTeam'
			--   AND FLAG_CONSOLIDATO = 0
			   ORDER BY ID_PROCESS DESC";					
			   $ret=$this->_db->getArrayByQuery($SqlList);
				
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
		public function insertList($idProcess)
		{
		try
			{	
    if ( "$idProcess" != "" ){
      $SqlList="INSERT INTO WORK_CORE.DELETE_LIST_ID_PROCESS (ID_PROCESS, USER) VALUES (?, ?) ";
      $this->_db->insertDb($SqlList, [$idProcess,$_SESSION['codname']]);
    }
    }catch (Throwable $e) {
			  //  $this->_db->close_db();
			    throw $e;
			    
			}
			catch (Exception $e) 
			{
			    $this->_db->close_db();
            	throw $e;
        	}
        }	
	
	
	public function deleteList($idProcess)
		{
		try
			{	
    if ( "$idProcess" != "" ){
        $SqlList="DELETE FROM WORK_CORE.DELETE_LIST_ID_PROCESS WHERE ID_PROCESS = $idProcess ";
      $this->_db->insertDb($SqlList, []);
    }
    }catch (Throwable $e) {
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