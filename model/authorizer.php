<?php 
		
		
	class authorizer_model
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
		
		public function getAuthorizer($IdTeam,$IdWorkFlow='')
		{
			try
			{
				$res = false;
				if(isset($IdTeam))
				{
				$sql="SELECT ID_WORKFLOW,WORKFLOW,UPPER(DESCR) DESCR, FREQUENZA, MULTI FROM WFS.WORKFLOW  
						WHERE ABILITATO = 'Y' AND ID_TEAM = '$IdTeam' ";
				if($IdWorkFlow)
					{
					$sql.=" AND ID_WORKFLOW = '$IdWorkFlow' ";
					}						
				$sql.=	"ORDER BY WORKFLOW";

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
		
		
		public function getUser($IdGruppo='')
		{
			try
			{
				$listUser= "";
				if($IdGruppo!='')
				{
					$listaUtenti = $this->getUtenti($IdGruppo);
					
					$arrayUser = [];
					foreach($listaUtenti as $row)
					{
						$arrayUser [] =$row['USERNAME'];
					}
					if(!empty($arrayUser))
					{
					$listUser ="'".implode("','",$arrayUser)."'";
					}
				}
			//	$this->_db->error_message("listUser",$listUser);
				$sql="SELECT UK,USERNAME,NOMINATIVO from WEB.TAS_UTENTI where 1=1";
				if($listUser) {
						$sql.=" and USERNAME NOT IN(".$listUser.")";
						}
				$sql.=" ORDER BY NOMINATIVO";
                $res = $this->_db->getArrayByQuery($sql);	
				
                return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}		
		public function getListGR($IdWorkFlow)
		{
			try
			{
				$res = false;
				if(isset($IdWorkFlow))
				{
				 $sql="SELECT ID_GRUPPO, GRUPPO, ( SELECT count(*) from WFS.ASS_GRUPPO WHERE ID_GRUPPO = g.ID_GRUPPO ) CONTA from WFS.GRUPPI g where g.ID_WORKFLOW = $IdWorkFlow ";
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
		public function getAutoDip($IdWorkFlow,$IdFlusso)
		{
			try
			{
				$res = false;
				if(isset($IdWorkFlow) && isset($IdFlusso))
				{
			$sql="SELECT ID_AUT_DIP,g.GRUPPO,a.ID_DIP
					from 
					WFS.AUTORIZZAZIONI_DIP a, 
					WFS.GRUPPI g
					where 1=1
					AND a.ID_WORKFLOW = g.ID_WORKFLOW 
					AND a.ID_GRUPPO = g.ID_GRUPPO 
					AND a.ID_WORKFLOW = $IdWorkFlow
					AND a.ID_FLU = $IdFlusso
					AND g.GRUPPO != 'ADMIN'
					ORDER BY a.ID_DIP, g.GRUPPO";	
					  $res = $this->_db->getArrayByQuery($sql);
				
				$aut= [];
				$ID_DIP ='';
				$i=0;
				foreach($res as $v ){
				if($ID_DIP!=$v['ID_DIP'])	
					{
					$ID_DIP = $v['ID_DIP'];
					$i=0;
					}
				$aut[$v['ID_DIP']][$i]['ID_AUT_DIP']= $v['ID_AUT_DIP'];
				$aut[$v['ID_DIP']][$i++]['GRUPPO']= $v['GRUPPO'];
				
				}		
			
                return $aut;
				}
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}
		
		
		public function getListDipFlusso($IdWorkFlow,$IdFlusso)
		{
			try
			{
				$res = false;
				if(isset($IdWorkFlow))
				{
				$sql="SELECT DISTINCT
					  L.ID_DIP
					  , L.TIPO
					  , CASE
					   WHEN L.TIPO = 'C' THEN (SELECT CARICAMENTO  FROM WFS.CARICAMENTI  WHERE ID_CAR  = L.ID_DIP )
					   WHEN L.TIPO = 'E' THEN (SELECT ELABORAZIONE FROM WFS.ELABORAZIONI WHERE ID_ELA  = L.ID_DIP )
					   WHEN L.TIPO = 'V' THEN (SELECT VALIDAZIONE  FROM WFS.VALIDAZIONI  WHERE ID_VAL  = L.ID_DIP )
					   WHEN L.TIPO = 'L' THEN (SELECT LINK         FROM WFS.LINKS        WHERE ID_LINK = L.ID_DIP )
					  END DIPENDENZA
					from
					  WFS.LEGAME_FLUSSI L
					LEFT JOIN
					  WFS.AUTORIZZAZIONI_DIP A
					ON 1=1
					  AND L.ID_WORKFLOW = A.ID_WORKFLOW
					  AND L.ID_FLU      = A.ID_FLU
					  AND L.TIPO        = A.TIPO
					  AND L.ID_DIP      = A.ID_DIP
					where 1=1
					  AND L.ID_WORKFLOW = $IdWorkFlow
					  AND L.ID_FLU = $IdFlusso
					  AND L.TIPO != 'F'
					ORDER BY 3
					";
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
		
		
		public function getUtenti($IdGruppo)
		{
			try
			{
				$res = false;
				if(isset($IdGruppo))
				{
				$sql="SELECT w.ID_ASS,u.USERNAME USERNAME,u.NOMINATIVO NOMINATIVO
					from WEB.TAS_UTENTI u,
						 WFS.ASS_GRUPPO  w
					where 1=1 
						 and w.ID_UK = u.UK
						 and w.ID_GRUPPO = '$IdGruppo'                                   
         ";
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
		public function getGruppi($IdWorkFlow,$IdFlusso)
		{
			try
			{
				$res = false;
				if(isset($IdWorkFlow) && isset($IdFlusso))
				{
			  $sql="SELECT ID_GRUPPO, GRUPPO FROM WFS.GRUPPI WHERE GRUPPO != 'ADMIN' AND ID_WORKFLOW = $IdWorkFlow AND ID_GRUPPO NOT IN ( 
                 SELECT ID_GRUPPO FROM WFS.AUTORIZZAZIONI WHERE ID_WORKFLOW = $IdWorkFlow AND ID_FLUSSO = $IdFlusso
			  )";
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
		
		
		public function getGruppiDip($IdWorkFlow,$IdFlusso,$Tipo,$IdDip)
		{
			try
			{
				$res = false;
			if(isset($IdWorkFlow) && isset($IdFlusso) && isset($Tipo) && isset($IdDip))
				{
			  $sql="SELECT ID_GRUPPO, GRUPPO FROM WFS.GRUPPI 
			  WHERE 1=1
			  AND ID_GRUPPO IN ( SELECT ID_GRUPPO FROM WFS.AUTORIZZAZIONI WHERE ID_WORKFLOW = $IdWorkFlow AND ID_FLUSSO = $IdFlusso )
			  AND ID_GRUPPO NOT IN ( SELECT ID_GRUPPO FROM WFS.AUTORIZZAZIONI_DIP WHERE ID_WORKFLOW = $IdWorkFlow AND ID_FLU = $IdFlusso AND TIPO = '$Tipo' AND  ID_DIP = $IdDip )
			  ORDER BY 2
			  ";
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
		
		
		public function getFlussi($IdWorkFlow,$idFlussi='')
		{
			try
			{
				$res = false;
				if(isset($IdWorkFlow))
				{
				if($idFlussi!="")
					{
					$sqladd=" and b.ID_FLU = $idFlussi ";
					}
				 $sql = "SELECT
					  b.ID_FLU, b.FLUSSO
					from
					  WFS.LEGAME_FLUSSI a,
					  WFS.FLUSSI b
					where 1=1
					  AND a.ID_WORKFLOW = b.ID_WORKFLOW
					  AND a.ID_FLU=b.ID_FLU
					  AND a.ID_WORKFLOW = $IdWorkFlow
					  $sqladd
					group by 
					  b.ID_FLU, b.FLUSSO
					order by b.FLUSSO 
					";
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
		
		
		public function getAutorizzazioni($IdWorkFlow)
		{
			try
			{
				$res = false;
				if(isset($IdWorkFlow))
				{
				$sql="SELECT ID_AUT,g.GRUPPO , a.ID_FLUSSO
					from 
					WFS.AUTORIZZAZIONI a, 
					WFS.GRUPPI g
					where 1=1
					AND a.ID_WORKFLOW = g.ID_WORKFLOW 
					AND a.ID_GRUPPO = g.ID_GRUPPO 
					AND a.ID_WORKFLOW = '$IdWorkFlow' 				
					AND g.GRUPPO != 'ADMIN'
					ORDER BY a.ID_FLUSSO, g.GRUPPO";
                $res = $this->_db->getArrayByQuery($sql);	
				}
				$aut= [];
				$idFlusso ='';
				$i=0;
				foreach($res as $v ){
				if($idFlusso!=$v['ID_FLUSSO'])	
					{
					$idFlusso =	$v['ID_FLUSSO'];
					$i=0;
					}
				$aut[$v['ID_FLUSSO']][$i]['ID_AUT']= $v['ID_AUT'];
				$aut[$v['ID_FLUSSO']][$i++]['GRUPPO']= $v['GRUPPO'];
				
				}
				
                return $aut;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}
		
		
		
		
		
			
			
			
	 public function AddMail($ID_SH, $ID_MAIL_ANAG, $FLG_START, $FLG_END)
		{
			try
			{	
				
				$sql = "INSERT INTO WORK_CORE.CORE_ALERT_MAIL(ID_SH, ID_MAIL_ANAG, FLG_START, FLG_END, VALID) VALUES(?,?,?,?,?)";
			
			
			$last_id = $this->_db->insertDb($sql, array($ID_SH, $ID_MAIL_ANAG, $FLG_START, $FLG_END, 'Y'));
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
		
		 
	public function addAzioneFlusso()
		{
		$IdWorkFlow=$_REQUEST['IdWorkFlow'];;
		$WorkFlow=$_REQUEST['WorkFlow'];
		$IdFlusso=$_REQUEST['IdFlusso'];
		$Flusso=$_REQUEST['Flusso'];
		

    $AzioneAut=$_REQUEST['AzioneAut'];
    $Errore=0;
	$Note="";
		try
			{
     switch($AzioneAut){
       case 'RAUT': 
            $CallPlSql='CALL WFS.K_AUTH.RimuoviAutorizzazione(?, ?, ?, ?, ? )';
           // $stmt = db2_prepare($conn, $CallPlSql);
                
            $IdAut=$_REQUEST['IdAut'];
			$values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdAut',
					'value' => $IdAut,
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
         /*   db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdAut"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 5, "Note"        , DB2_PARAM_OUT);*/
            break;	
			
		case 'ADG': 
            $CallPlSql='CALL WFS.K_AUTH.AggiungiAutorizzazione(?, ?, ?, ?, ?, ? )';
            //$stmt = db2_prepare($conn, $CallPlSql);
                
            $IdGroup=$_REQUEST['IdGroup'];
			
			$values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdFlusso',
					'value' => $IdFlusso,
					'type' => DB2_PARAM_IN
					],
					[
					'name' => 'IdGroup',
					'value' => $IdGroup,
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
		
           /* db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdFlusso"    , DB2_PARAM_IN);
			db2_bind_param($stmt, 3, "IdGroup"     , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 6, "Note"        , DB2_PARAM_OUT);*/
            break;	
	 }
		
		
		
	 if ( $AzioneAut != "" ){
		 	$ret = $this->_db->callDb($CallPlSql,$values);
		 }
		 
			}catch (Exception $e) 
			{
			  $this->_db->error_message("Impossibile eseguire l'operazione");
        	}
 }	
 
 public function AzioneUatoDip()
		{
		
		try
			{
			$IdWorkFlow=$_REQUEST['IdWorkFlow'];
			$WorkFlow=$_POST['WorkFlow'];
			$IdFlusso=$_POST['IdFlusso'];
			$IdDip=$_POST['IdDip'];
				
			$Azione=$_POST['AzioneDipAut'];;
			$Errore=0;
			$Note="";
	switch($Azione){
       case 'RAUT': 
            $CallPlSql='CALL WFS.K_AUTH.RimuoviAutorizzazioneDip(?, ?, ?, ?, ? )';
         //  $stmt = db2_prepare($conn, $CallPlSql);
                
            $IdAut=$_POST['IdAut'];
			
			$values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdAut',
					'value' => $IdAut,
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
                
            /*db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdAut"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 5, "Note"        , DB2_PARAM_OUT);*/
            break;  
            
        case 'ADG': 
            $CallPlSql='CALL WFS.K_AUTH.AggiungiAutorizzazioneDip(?, ?, ?, ?, ?, ?, ?, ? )';
          //  $stmt = db2_prepare($conn, $CallPlSql);
              
      $IdGroup=$_POST['IdGroup'];
	        $TipoDip=$_POST['TipoDip'];
			
			$values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdFlusso',
					'value' => $IdFlusso,
					'type' => DB2_PARAM_IN
					], [
					'name' => 'TipoDip',
					'value' => $TipoDip,
					'type' => DB2_PARAM_IN
					], [
					'name' => 'IdDip',
					'value' => $IdDip,
					'type' => DB2_PARAM_IN
					], [
					'name' => 'IdGroup',
					'value' => $IdGroup,
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
        
            /*db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdFlusso"    , DB2_PARAM_IN);
			db2_bind_param($stmt, 3, "TipoDip"     , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "IdDip"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "IdGroup"     , DB2_PARAM_IN);
            db2_bind_param($stmt, 6, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 7, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 8, "Note"        , DB2_PARAM_OUT);*/
            break;  
     }
	 
	 if ( $Azione != "" ){
		 	$ret = $this->_db->callDb($CallPlSql,$values);
		 }
		 
			}catch (Exception $e) 
			{
			    $this->_db->close_db();	
            	throw $e;
        	}
 }
		
		
		public function addAzioneDett()
		{
		
		try
			{
		  $IdWorkFlow=$_REQUEST['IdWorkFlow'];
			$WorkFlow=$_POST['WorkFlow'];
				
			$Azione=$_POST['Azione'];
			$Errore=0;
			$Note="";
		switch($Azione){        
       case 'AG': 
	   
            $RunPlSql='CALL WFS.K_AUTH.AggiungiGruppo(?, ?, ?, ?, ? )';
           // $stmt=db2_prepare($conn, $RunPlSql);
                        
            $AddGrp=$_POST['AddGrp'];
			$values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'AddGrp',
					'value' => $AddGrp,
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
                                    
          /*  db2_bind_param($stmt, 1, "IdWorkFlow" , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "AddGrp"     , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "User"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Errore"     , DB2_PARAM_OUT);
            db2_bind_param($stmt, 5, "Note"       , DB2_PARAM_OUT);*/
            
            break;
       case 'RG': 
            $RunPlSql='CALL WFS.K_AUTH.RimuoviGruppo( ?, ?, ?, ?, ?)';
          //  $stmt=db2_prepare($conn, $RunPlSql);
                
            $DelGr=$_POST['DelGr'];
			$values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'DelGr',
					'value' => $DelGr,
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
              
          /*  db2_bind_param($stmt, 1, "IdWorkFlow" , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "DelGr"      , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "User"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Errore"     , DB2_PARAM_OUT);
            db2_bind_param($stmt, 5, "Note"       , DB2_PARAM_OUT);*/
            break;
     }
     
     if ( $Azione != "" ){
		 	$ret = $this->_db->callDb($RunPlSql,$values);
		 }
		 
			}catch (Exception $e) 
			{
			    $this->_db->close_db();	
            	throw $e;
        	}
 }
	public function addAzione()
		{
	try
	{
	$IdWorkFlow=$_REQUEST['IdWorkFlow'];
    $WorkFlow=$_POST['WorkFlow'];
    $IdGruppo=$_POST['IdGruppo'];
    

    $Azione=$_POST['Azione'];
    $Errore=0;
	$Note="";
	$ret=false;
	if(isset($Azione))
	{
     switch($Azione){
       case 'AU': 
            $CallPlSql='CALL WFS.K_AUTH.AssegnaUtente(?, ?, ?, ?, ?, ? )';
           // $stmt = db2_prepare($conn, $CallPlSql);
                
            $IdUsr=$_POST['IdUsr'];
			$values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdGruppo',
					'value' => $IdGruppo,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdUsr',
					'value' => $IdUsr,
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
         /*   db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdGruppo"    , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "IdUsr"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 6, "Note"        , DB2_PARAM_OUT);*/
            break;
       case 'ANU': 
            $CallPlSql='CALL WFS.K_AUTH.AggiungiNuovoUtente(?, ?, ?, ?, ?, ?, ?, ? )';
            //$stmt = db2_prepare($conn, $CallPlSql);
          
            $AddUserName=strtoupper(trim($_POST['AddUserName']));
            $AddNome=$_POST['AddNome'];
            $AddCognome=$_POST['AddCognome'];
            $values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdGruppo',
					'value' => $IdGruppo,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'AddUserName',
					'value' => $AddUserName,
					'type' => DB2_PARAM_IN
					],
					[
					'name' => 'AddNome',
					'value' => $AddNome,
					'type' => DB2_PARAM_IN
					],
					[
					'name' => 'AddCognome',
					'value' => $AddCognome,
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
           /* db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdGruppo"    , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "AddUserName" , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "AddNome"     , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "AddCognome"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 6, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 7, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 8, "Note"        , DB2_PARAM_OUT);*/
            break;
        case 'DU': 
            $CallPlSql='CALL WFS.K_AUTH.RevocaUtente(?, ?, ?, ?, ? )';
           // $stmt = db2_prepare($conn, $CallPlSql);
                
            $IdAss=$_POST['IdAss'];
           // $values = array($IdWorkFlow,$IdAss,$User,$Errore,$Note);
	       $values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdAss',
					'value' => $IdAss,
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
			/*echo "<pre>";		
			print_r( $values);
			die();*/
			
           /* db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdAss"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 5, "Note"        , DB2_PARAM_OUT);*/
            break;
     }				
        
			   // $values = array($flag, $EnIdSh);
				$ret = $this->_db->callDb($CallPlSql,$values);
	 			
	}
				
				return $ret;
			}
			catch (Throwable $e) {
			  $this->_db->error_message("addAzione: Throwable Impossibile eseguire l'operazione".$e);			    
			}
			catch (Exception $e) 
			{ 
				$this->_db->error_message("addAzione: Exception Impossibile eseguire l'operazione".$e);
        	}
        }	



	
	}
	
?>