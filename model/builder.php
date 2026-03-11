<?php 
				
	class builder_model
	{
		// set database config for mysql
		// open mysql data base
	    private $_db;
		
		public function __construct()
		{
		    $this->_db = new db_driver();		  
		}
		
		public function getTeam()
		{
			try
			{
				$sql="SELECT ID_TEAM,TEAM FROM WFS.TEAM ORDER BY TEAM";
				$res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
		}		
		
		public function getWorkFlow($IdTeam)
		{
			try
			{
			$sql="SELECT ID_WORKFLOW,WORKFLOW,UPPER(DESCR) DESCR , READONLY,
			( SELECT count(*) FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = W.ID_WORKFLOW ) CONTA
			, FREQUENZA, MULTI
			FROM WFS.WORKFLOW W WHERE ABILITATO = 'Y'
			AND ID_TEAM = $IdTeam
   		    ORDER BY WORKFLOW";				
			$res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
		}		
		
		public function getModWfs($IdWorkFlow)
		{
			try
			{
				if ( $IdWorkFlow != "" ){
					$sql="SELECT WORKFLOW,UPPER(DESCR) DESCR, READONLY, FREQUENZA, MULTI ,OPEN_AUTO,OPEN_MESE,OPEN_GIORNO
					
					FROM WFS.WORKFLOW WHERE ID_WORKFLOW = $IdWorkFlow ";
				}
			$res = $this->_db->getArrayByQuery($sql);	
                return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
		}
		
		public function setAzione($Azione)
		{
			try
			{	
				if ($Azione) {
				// echo $Azione;
				// die();
					switch ($Azione){
						case "Modifica":
						case "Aggiungi":
				//		$CallPlSql='CALL WFS.K_CONFIG.ModifyWorkFlow(?, ?, ?, ?, ?, ?, ?, ?, ? )';
						$Errore=0;
						$ShowErrore=0;
						$Note="";
		
						$InpWorkFlow=$_POST['InpWorkFlow'];
						$InpDescr=$_POST['InpDescr'];
						$InpReadOnly=$_POST['InpReadOnly'];
						$InpFreq=$_POST['InpFreq'];
						$InpMulti=$_POST['InpMulti'];
						$IdWorkFlow=$_REQUEST['IdWorkFlow'];
						$IdTeam=$_POST['IdTeam'];
						$InOpenAuto=$_POST['InOpenAuto'];
						$InOpenMese=$_POST['InOpenMese'];
						$InOpenGiorno=$_POST['InOpenGiorno'];
						

						$CallPlSql='CALL WFS.K_CONFIG.ChangeWorkFlow(?, ?, ?, ?, ?, ?, ?, ?, ? , ?, ?, ?, ?)';


/*
        I_ID_WORKFLOW IN INTEGER
       ,I_NOME_WORKFLOW IN VARCHAR2
		 ,I_DESC_WORKFLOW IN VARCHAR2
		,I_FREQ IN CHAR
		,I_MULTI  IN CHAR
		,I_READONLY IN VARCHAR2
		,I_ID_TEAM IN INTEGER
		,I_OPEN_AUTO  IN VARCHAR2
		,I_OPEN_MESE IN VARCHAR2
		,I_OPEN_GIORNO IN VARCHAR2
		_USER IN VARCHAR2
		,O_ERROR OUT VARCHAR2
		,O_NOTE OUT VARCHAR2
		)*/


				$IdWorkFlow =$IdWorkFlow?$IdWorkFlow:null;

						$values = [
								 [
								'name' => 'I_ID_WORKFLOW',
								'value' => $IdWorkFlow,
								'type' => DB2_PARAM_IN
								],
								 [
								'name' => 'I_NOME_WORKFLOW',
								'value' => $InpWorkFlow,
								'type' => DB2_PARAM_IN
								],[
								'name' => 'I_DESC_WORKFLOW',
								'value' => $InpDescr,
								'type' => DB2_PARAM_IN
								],[
								'name' => 'I_FREQ',
								'value' => $InpFreq,
								'type' => DB2_PARAM_IN
								],[
								'name' => 'I_MULTI',
								'value' => $InpMulti,
								'type' => DB2_PARAM_IN
								],[
								'name' => 'I_READONLY',
								'value' => $InpReadOnly,
								'type' => DB2_PARAM_IN
								],	
								[
								'name' => 'I_ID_TEAM',
								'value' => $IdTeam,
								'type' => DB2_PARAM_IN
								],

								[
								'name' => 'I_OPEN_AUTO',
								'value' => $InOpenAuto,
								'type' => DB2_PARAM_IN
								],
								[
								'name' => 'I_OPEN_MESE',
								'value' => $InOpenMese,
								'type' => DB2_PARAM_IN
								],
								[
								'name' => 'I_OPEN_GIORNO',
								'value' => $InOpenGiorno,
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
					//	echo "<pre>";
					//	print_r($values);
					//	die();
					//	$variable =array($IdWorkFlow, $InpWorkFlow, $InpDescr,$InpFreq, $InpMulti, $InpReadOnly, $User,	$Errore, $Note);	
						break;	
							
						case "Cancella":	
						$CallPlSql='CALL WFS.K_CONFIG.RemoveWorkFlow(?, ?, ?, ?)';
						$Errore=0;
						$ShowErrore=0;
						$Note="";
						$IdWorkFlow=$_REQUEST['IdWorkFlow'];
						$values = [
								 [
								'name' => 'IdWorkFlow',
								'value' => $IdWorkFlow,
								'type' => DB2_PARAM_IN
								],[
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
						
						//$variable = array ($IdWorkFlow, $User, $Errore, $Note);
						break;	
							
					/*	case "Aggiungi":
						$InpWorkFlow=$_POST['InpWorkFlow'];
						$InpDescr=$_POST['InpDescr'];
						$InpFreq=$_POST['InpFreq'];
						$InpMulti=$_POST['InpMulti'];
						$IdTeam=$_POST['IdTeam'];
						$CallPlSql='CALL WFS.K_CONFIG.CreateWorkFlow(?, ?, ?, ?, ?, ?, ?, ?)';

						$Errore=0;
						$ShowErrore=0;
						$Note="";
							$values = [
							[
								'name' => 'InpWorkFlow',
								'value' => $InpWorkFlow,
								'type' => DB2_PARAM_IN
								],[
								'name' => 'InpDescr',
								'value' => $InpDescr,
								'type' => DB2_PARAM_IN
								],[
								'name' => 'IdTeam',
								'value' => $IdTeam,
								'type' => DB2_PARAM_IN
								],[
								'name' => 'InpFreq',
								'value' => $InpFreq,
								'type' => DB2_PARAM_IN
								],[
								'name' => 'InpMulti',
								'value' => $InpMulti,
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
								
					
						
					//	$variable = array ($InpWorkFlow, $InpDescr, $IdTeam,$InpFreq,$InpMulti,$User,$Errore, $Note);
					*/
						break;
					}	
						$ret = $this->_db->callDb($CallPlSql ,$values);
					//	$this->_db->printSql();
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
		
		public function getSelectedWf($IdWorkFlow)
		{
			try
			{
				$sql="SELECT WORKFLOW, DESCR
				FROM  WFS.WORKFLOW
				WHERE ID_WORKFLOW = $IdWorkFlow";		
				
				$res = $this->_db->getArrayByQuery($sql);	
				return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
		}		
		
		public function getRilWf($IdWorkFlow)
		{
			try
			{
				$sql="SELECT ID_FLU FROM WFS.FLUSSI WHERE ID_WORKFLOW = $IdWorkFlow ORDER BY FLUSSO";
				
				$res = $this->_db->getArrayByQuery($sql);	
				return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
		}		
		
		public function getDettagliWf($IdWorkFlow,$idFlu="")
		{
			try
			{
				$res="";
				$sqladd="";
				
				if($IdWorkFlow)
				{
					if($idFlu!='')
				{
					$sqladd="  AND ID_FLU = $idFlu ";
				}
				
				// if ( ( "$Dett" == "" and "$Tipo" == "E" ) || ( substr($Dett,0,8) == "WFS_TEST" and  "$Tipo" == "C" )
				$sql="SELECT *,
				 ( SELECT count(*) FROM WFS.LEGAME_FLUSSI WHERE ID_FLU = F.ID_FLU ) UTILIZZATO
				 
				,( SELECT count(*) FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_FLU = F.ID_FLU AND (
					  ( TIPO = 'E' AND ID_DIP IN ( SELECT ID_ELA FROM WFS.ELABORAZIONI WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_SH = 0 ) )
					  OR
					  ( TIPO = 'C' AND ID_DIP IN ( SELECT ID_CAR ID_DIP FROM WFS.CARICAMENTI  WHERE ID_WORKFLOW  = $IdWorkFlow AND NOME_INPUT = 'WFS_TEST' ))
					)
					) LAB
				 ,( SELECT count(*) FROM WFS.LEGAME_FLUSSI WHERE ID_FLU = F.ID_FLU AND ID_WORKFLOW = $IdWorkFlow ) USATO
				 ,(SELECT DISTINCT LIV FROM WFS.LEGAME_FLUSSI WHERE ID_FLU =  F.ID_FLU and ID_WORKFLOW = '$IdWorkFlow' ) LIV
				FROM 
					WFS.FLUSSI F
				WHERE 1=1
				   AND ID_WORKFLOW = $IdWorkFlow
				   $sqladd
				ORDER BY LIV,FLUSSO";				
				$res = $this->_db->getArrayByQuery($sql);	
				/*echo "<pre>";
				print_r($res);
				echo "</pre>";*/
				}
				return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
		}		
		
		public function getFlusso($IdFlu)
		{
			try
			
			{
				$sql="SELECT 
						 ID_LEGAME
						,ID_WORKFLOW
						,PRIORITA
						,TIPO
						,ID_DIP
						,CASE L.TIPO 
						WHEN 'E' THEN
						  ( SELECT PARAMETRI FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )
						ELSE
						   null
					    END PARAMETRI
						,CASE L.TIPO 

						WHEN 'F' THEN
						   ( SELECT FLUSSO FROM WFS.FLUSSI WHERE ID_FLU = L.ID_DIP )
						WHEN 'C' THEN
						  ( SELECT CARICAMENTO FROM WFS.CARICAMENTI WHERE ID_CAR = L.ID_DIP )
						WHEN 'L' THEN
						  ( SELECT LINK FROM WFS.LINKS WHERE ID_LINK = L.ID_DIP )
						WHEN 'E' THEN
						  ( SELECT ELABORAZIONE FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )
						WHEN 'V' THEN
						  ( SELECT VALIDAZIONE FROM WFS.VALIDAZIONI WHERE ID_VAL = L.ID_DIP )
						END AS DIPENDENZA
						,CASE L.TIPO 
						WHEN 'F' THEN
						   ( SELECT DESCR FROM WFS.FLUSSI WHERE ID_FLU = L.ID_DIP )
						WHEN 'C' THEN
						  ( SELECT DESCR FROM WFS.CARICAMENTI WHERE ID_CAR = L.ID_DIP )
						WHEN 'L' THEN
						  ( SELECT DESCR FROM WFS.LINKS WHERE ID_LINK = L.ID_DIP )
						WHEN 'E' THEN
						  ( SELECT DESCR FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )
						WHEN 'V' THEN
						  ( SELECT DESCR FROM WFS.VALIDAZIONI WHERE ID_VAL = L.ID_DIP )
						END AS DESCR
						,CASE L.TIPO 
						WHEN 'F' THEN
						   null
						WHEN 'C' THEN
						  ( SELECT NOME_INPUT FROM WFS.CARICAMENTI WHERE ID_CAR = L.ID_DIP )
						  ||
						  ( SELECT ' - Conferma Dato: '||CONFERMA_DATO FROM WFS.CARICAMENTI WHERE ID_CAR = L.ID_DIP )
						WHEN 'L' THEN
						  ( SELECT TARGET FROM WFS.LINKS WHERE ID_LINK = L.ID_DIP )
						WHEN 'E' THEN
						( SELECT C.SHELL FROM WFS.ELABORAZIONI E , WORK_CORE.CORE_SH_ANAG C 
						  WHERE 1=1
						  AND E.ID_SH = C.ID_SH
						  AND E.ID_ELA = L.ID_DIP )
						WHEN 'V' THEN
						  null
						END AS DETT
						,CASE L.TIPO 
						WHEN 'L' THEN
						  ( SELECT LS.TIPO FROM WFS.LINKS LS WHERE ID_LINK = L.ID_DIP )
						ELSE
						  null
						END AS TLINK	
						,CASE L.TIPO 
						WHEN 'E' THEN
						  ( SELECT READONLY FROM WFS.ELABORAZIONI E WHERE ID_ELA = L.ID_DIP )
						ELSE
						  null
						END AS READONLY			
						,CASE L.TIPO 
						WHEN 'V' THEN
						  ( SELECT EXTERNAL FROM WFS.VALIDAZIONI E WHERE ID_VAL = L.ID_DIP )
						ELSE
						  null
						END AS EXTERNAL			
						,CASE L.TIPO 
						WHEN 'E' THEN
						  ( SELECT SALTA_ELAB FROM WFS.ELABORAZIONI E WHERE ID_ELA = L.ID_DIP )
						ELSE
						  null
						END AS SALTA,
						TO_CHAR(TMS_INSERT,'YYYYMM') TMS_INSERT,
						INZ_VALID,
						FIN_VALID,
                        VALIDITA
						FROM 
							WFS.LEGAME_FLUSSI L
						WHERE 1=1
						AND ID_FLU = $IdFlu
						ORDER BY 3, 4, 5";		
				
				$res = $this->_db->getArrayByQuery($sql);	
				
				return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
		}

public function azioneBuilder () {
		try
		{	
	$TServer="Jiak";    
    $TopScroll=$_POST['TopScroll'];
    $BodyHeight=$_POST['BodyHeight'];
    $IdLegame=$_POST['IdLegame'];
	$IdWorkFlow=$_REQUEST['IdWorkFlow'];
    $IdTeam=$_POST['IdTeam'];
    $Azione=$_POST['Azione'];
    $Flusso=$_POST['Flusso'];
    $Errore=0;
    $ShowErrore=0;
    $Note=""; 
	$ret = "false";	
            
    if ( $Azione != ""  ) {
       $Vali=$_POST['SELVAL'];
	   $InzVali=$_POST['SELINZVAL'];
	   $FinVali=$_POST['SELFINVAL'];
   /* echo "<pre>";  
     print_r($_POST);  */     
            
    switch($Azione){
       case 'RIL': 
	   
			$Id_List="";
			$datiRilWf = $this->getRilWf($IdWorkFlow);
			$ChkRilascia =$_POST['ChkRilascia'];
		/*	print_r($ChkRilascia); */
            foreach ($datiRilWf as $row) {
                $IdFls=$row['ID_FLU'];
				//$TestF=$_POST['ChkRilascia_'.$IdFls];
			   if (in_array($IdFls, $ChkRilascia)) {
					$Id_List.=$IdFls.",";
				}
	        }
			$Id_List=rtrim($Id_List,',');			
           // $CallPlSql='CALL WFS.K_CONFIG.CreaRilascio(?, ?, ?, ?, ?)';
			$CallPlSql='CALL WFS.K_RILASCIO.CreaRilascio(?, ?, ?, ?, ?)';
           // $stmt = db2_prepare($conn, $CallPlSql);
		   
		   $values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'Id_List',
					'value' => $Id_List,
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
					
					
				/*	print_r($values);
					echo "</pre>";
					die();*/
            
         /*   db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "Id_List"     , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 5, "Note"        , DB2_PARAM_OUT);*/
			
            break;
       case 'AF': 
            $CallPlSql='CALL WFS.K_CONFIG.AggiungiFlusso(?, ?, ?, ?, ?, ?, ?)';
           // $stmt = db2_prepare($conn, $CallPlSql);
                
            $NomeFlusso=$_POST['NomeFlusso'];   
            $DescFlusso=$_POST['DescFlusso'];
            $BlockCons=$_POST['BlockCons'];
			
			  $values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'NomeFlusso',
					'value' => $NomeFlusso,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'DescFlusso',
					'value' => $DescFlusso,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'BlockCons',
					'value' => $BlockCons,
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
					
	
                
          /*  db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "NomeFlusso"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "DescFlusso"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "BlockCons"   , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 6, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 7, "Note"        , DB2_PARAM_OUT);*/
            break;
       case 'ADF': 
            $CallPlSql='CALL WFS.K_CONFIG.AggiungiDipFlusso(?, ?, ?, ?, ?, ?, ?, ?, ?, ? )';
          //  $stmt = db2_prepare($conn, $CallPlSql);
                     
            $IdFlu=$_POST['IdFlu'];       
            $Priorita=$_POST['Priorita'];
            $IdDip=$_POST['SELFLUSSO'];
			
			 $values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdFlu',
					'value' => $IdFlu,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Priorita',
					'value' => $Priorita,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'IdDip',
					'value' => $IdDip,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Vali',
					'value' => $Vali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'InzVali',
					'value' => $InzVali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'FinVali',
					'value' => $FinVali,
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
                
          /*  db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdFlu"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "Priorita"    , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "IdDip"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Vali"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 6, "InzVali"     , DB2_PARAM_IN);
            db2_bind_param($stmt, 7, "FinVali"     , DB2_PARAM_IN);
            db2_bind_param($stmt, 8, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 9, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt,10, "Note"        , DB2_PARAM_OUT);*/
            break;          
       case 'AC': 
           
          $CallPlSql='CALL WFS.K_CONFIG.AggiungiCaricamento(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? , ? , ? , ?, ?)';
          //  $stmt = db2_prepare($conn, $CallPlSql);
            
            $IdFlu=$_POST['IdFlu'];
            $Priorita=$_POST['Priorita'];
            $NomeLoad=$_POST['SELCARICAMENTO'];
            $CarLoad=$_POST['NOMCARICAMENTO'];
            $DestLoad=$_POST['TXTCARICAMENTO'];
            $Nome=$_POST['NomeCar'];
            $Desc=$_POST['DescrCar'];
            $ConfermaDato=$_POST['ConfermaDato'];
            $SHELL_TARGET=$_POST['SHELL_TARGET'];
            $SHELL_VAR=$_POST['SHELL_VAR'];
            $TARGET_TAB=$_POST['TARGET_TAB'];
			
			
			if ( "$NomeLoad" == "CarFile" ){
			    $NomeLoad=$CarLoad;
	        }
			
			
			 $values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdFlu',
					'value' => $IdFlu,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Priorita',
					'value' => $Priorita,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'NomeLoad',
					'value' => $NomeLoad,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Nome',
					'value' => $Nome,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Desc',
					'value' => $Desc,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'ConfermaDato',
					'value' => $ConfermaDato,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Vali',
					'value' => $Vali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'InzVali',
					'value' => $InzVali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'FinVali',
					'value' => $FinVali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'DestLoad',
					'value' => $DestLoad,
					'type' => DB2_PARAM_IN
					],						
					[
					'name' => 'SHELL_TARGET',
					'value' => $SHELL_TARGET,
					'type' => DB2_PARAM_IN
					],	
					[
					'name' => 'SHELL_VAR',
					'value' => $SHELL_VAR,
					'type' => DB2_PARAM_IN
					],	
					[
					'name' => 'TARGET_TAB',
					'value' => $TARGET_TAB,
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
					
				/*	echo "<pre>";
					print_r($values );
					echo "</pre>";
					die();*/
           
          /*  db2_bind_param($stmt,  1, "IdWorkFlow"       , DB2_PARAM_IN);
            db2_bind_param($stmt,  2, "IdFlu"            , DB2_PARAM_IN);
            db2_bind_param($stmt,  3, "Priorita"         , DB2_PARAM_IN);
            db2_bind_param($stmt,  4, "NomeLoad"         , DB2_PARAM_IN);
			db2_bind_param($stmt,  5, "Nome"             , DB2_PARAM_IN);
            db2_bind_param($stmt,  6, "Desc"             , DB2_PARAM_IN);
            db2_bind_param($stmt,  7, "ConfermaDato"     , DB2_PARAM_IN);
            db2_bind_param($stmt,  8, "Vali"             , DB2_PARAM_IN);
            db2_bind_param($stmt,  9, "InzVali"          , DB2_PARAM_IN);
            db2_bind_param($stmt, 10, "FinVali"          , DB2_PARAM_IN);
			db2_bind_param($stmt, 11, "DestLoad"         , DB2_PARAM_IN);
            db2_bind_param($stmt, 12, "User"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 13, "Errore"           , DB2_PARAM_OUT);
            db2_bind_param($stmt, 14, "Note"             , DB2_PARAM_OUT);*/
            break;
       case 'AL':
            $CallPlSql='CALL WFS.K_CONFIG.AggiungiLink(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )';
           // $stmt = db2_prepare($conn, $CallPlSql);
            
				$IdFlu=$_POST['IdFlu'];
				$Priorita=$_POST['Priorita'];
				$Nome=$_POST['NomeLink'];
				$TipoLink=$_POST['TipoLink'];
				$Dest=$_POST['Destinazione'];
				$Desc=$_POST['DescrLink'];   
				$Opt=$_POST['SELOPT'];
				$ID_PAR_GRUPPO=$_POST['ID_PAR_GRUPPO']?$_POST['ID_PAR_GRUPPO']:null;
				
			
			 $values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdFlu',
					'value' => $IdFlu,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Priorita',
					'value' => $Priorita,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Nome',
					'value' => $Nome,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'TipoLink',
					'value' => $TipoLink,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Dest',
					'value' => $Dest,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Desc',
					'value' => $Desc,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Vali',
					'value' => $Vali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'InzVali',
					'value' => $InzVali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'FinVali',
					'value' => $FinVali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Opt',
					'value' => $Opt,
					'type' => DB2_PARAM_IN
					],					 
					[
					'name' => 'ID_PAR_GRUPPO',
					'value' => $ID_PAR_GRUPPO,
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
					
				/*		echo "<pre>";
					print_r($values );
					echo "</pre>";
					die();*/
            
          /*  db2_bind_param($stmt,  1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt,  2, "IdFlu"       , DB2_PARAM_IN);
            db2_bind_param($stmt,  3, "Priorita"    , DB2_PARAM_IN);
            db2_bind_param($stmt,  4, "Nome"        , DB2_PARAM_IN);
            db2_bind_param($stmt,  5, "TipoLink"    , DB2_PARAM_IN);
            db2_bind_param($stmt,  6, "Dest"        , DB2_PARAM_IN);
            db2_bind_param($stmt,  7, "Desc"        , DB2_PARAM_IN);
            db2_bind_param($stmt,  8, "Vali"        , DB2_PARAM_IN);
            db2_bind_param($stmt,  9, "InzVali"     , DB2_PARAM_IN);
            db2_bind_param($stmt, 10, "FinVali"     , DB2_PARAM_IN);
			db2_bind_param($stmt, 11, "Opt"         , DB2_PARAM_IN);
            db2_bind_param($stmt, 12, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 13, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 14, "Note"        , DB2_PARAM_OUT);*/
            break;
       case 'AE':
            $CallPlSql='CALL WFS.K_CONFIG.AggiungiElaborazione(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
         //   $stmt = db2_prepare($conn, $CallPlSql);
            
            $IdFlu=$_POST['IdFlu'];
            $Priorita=$_POST['Priorita'];
            $Nome=$_POST['NomeEla'];
            $Desc=$_POST['DescrEla'];
            $IdElaborazione=$_POST['SELELABORAZIONE'];
            $Tags=$_POST['Tags'];
            $ReadOnly=$_POST['ReadOnly'];
            $SaltaElab=$_POST['SaltaElab'];
			$ShowProc=$_POST['ShowProc'];
			$Parametri=$_POST['Parametri'];			
			 $values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdFlu',
					'value' => $IdFlu,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Priorita',
					'value' => $Priorita,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Nome',
					'value' => $Nome,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Tags',
					'value' => $Tags,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Desc',
					'value' => $Desc,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'ReadOnly',
					'value' => $ReadOnly,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'IdElaborazione',
					'value' => $IdElaborazione,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Vali',
					'value' => $Vali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'InzVali',
					'value' => $InzVali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'FinVali',
					'value' => $FinVali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'SaltaElab',
					'value' => $SaltaElab,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'ShowProc',
					'value' => $ShowProc,
					'type' => DB2_PARAM_IN
					],				 
					[
					'name' => 'User',
					'value' => $_SESSION['codname'],
					'type' => DB2_PARAM_IN
					],
					[
					'name' => 'Parametri',
					'value' => $Parametri,
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
            
            
         /*   db2_bind_param($stmt, 1, "IdWorkFlow"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdFlu"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "Priorita"          , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Nome"              , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Tags"              , DB2_PARAM_IN);
            db2_bind_param($stmt, 6, "Desc"              , DB2_PARAM_IN);
            db2_bind_param($stmt, 7, "ReadOnly"          , DB2_PARAM_IN);
            db2_bind_param($stmt, 8, "IdElaborazione"    , DB2_PARAM_IN);
            db2_bind_param($stmt, 9, "Vali"              , DB2_PARAM_IN);
            db2_bind_param($stmt,10, "InzVali"           , DB2_PARAM_IN);
            db2_bind_param($stmt,11, "FinVali"           , DB2_PARAM_IN);
            db2_bind_param($stmt,12, "SaltaElab"         , DB2_PARAM_IN);
            db2_bind_param($stmt,13, "ShowProc"          , DB2_PARAM_IN);
            db2_bind_param($stmt,14, "User"              , DB2_PARAM_IN);
            db2_bind_param($stmt,15, "Errore"            , DB2_PARAM_OUT);
            db2_bind_param($stmt,16, "Note"              , DB2_PARAM_OUT);*/
            break;
       case 'AV':
            $CallPlSql='CALL WFS.K_CONFIG.AggiungiValidazione(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
           // $stmt = db2_prepare($conn, $CallPlSql);

            $IdFlu=$_POST['IdFlu'];
            $Priorita=$_POST['Priorita'];
            $Nome=$_POST['NomeVal'];
            $Desc=$_POST['DescrVal'];
            $External=$_POST['External'];   

			 $values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdFlu',
					'value' => $IdFlu,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Priorita',
					'value' => $Priorita,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Nome',
					'value' => $Nome,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Desc',
					'value' => $Desc,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'External',
					'value' => $External,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Vali',
					'value' => $Vali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'InzVali',
					'value' => $InzVali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'FinVali',
					'value' => $FinVali,
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
		/*	echo "<pre>";
					print_r($values );
					echo "</pre>";
					die();*/
			/*db2_bind_param($stmt, 1, "IdWorkFlow"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdFlu"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "Priorita"         , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Nome"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Desc"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 6, "External"         , DB2_PARAM_IN);
            db2_bind_param($stmt, 7, "Vali"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 8, "InzVali"          , DB2_PARAM_IN);
            db2_bind_param($stmt, 9, "FinVali"          , DB2_PARAM_IN);
            db2_bind_param($stmt,10, "User"             , DB2_PARAM_IN);
            db2_bind_param($stmt,11, "Errore"           , DB2_PARAM_OUT);
            db2_bind_param($stmt,12, "Note"             , DB2_PARAM_OUT);*/
            break;
        case 'MFL': 
            $CallPlSql='CALL WFS.K_CONFIG.ModificaFlusso(?, ?, ?, ?, ?, ?, ?, ?)';
          //  $stmt = db2_prepare($conn, $CallPlSql);
                          
            $IdFlu=$_POST['IdDip'];       
            $Nome=$_POST['NomeFlu'];
            $Desc=$_POST['DescrFlu'];
            $BlockCons=$_POST['BlockCons'];
	
            $LivPersAuto=$_POST['LivPersAuto'];			
			if ( "$LivPersAuto" == "" ){$LivPersAuto=0;}
			
			$LivPersNum=$_POST['LivPersNum'];
			$TabPersNum=$_POST['TabPersNum'];
			
			$sqlT="DELETE FROM WFS.FLUSSI_LIV_PERS WHERE ID_FLU = $IdFlu and ID_WORKFLOW = $IdWorkFlow ";
			$this->_db->deletedb($sqlT,[]);
		
			if( $LivPersAuto != "1" ){
			      $sqlT="INSERT INTO WFS.FLUSSI_LIV_PERS(ID_WORKFLOW,ID_FLU) VALUES(?,?)";
				  $resT = $this->_db->insertDb($sqlT,[$IdWorkFlow,$IdFlu]);
			    	
                  if ( ! $resT) {
                  //  echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg();
                  } else {
				   if ( $LivPersNum != $TabPersNum ){	  
			         $sqlT="UPDATE WFS.LEGAME_FLUSSI SET LIV = ? WHERE ID_FLU = ? and ID_WORKFLOW = ? ";
			         $resT = $this->_db->updateDb($sqlT,[$LivPersNum,$IdFlu,$IdWorkFlow]);
                    
                     if ( ! $resT) {
                    //   echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg();
                     }
				   }
			     }
			  }
			
			 $values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdFlu',
					'value' => $IdFlu,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Nome',
					'value' => $Nome,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Desc',
					'value' => $Desc,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'BlockCons',
					'value' => $BlockCons,
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
            db2_bind_param($stmt, 2, "IdFlu"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "Nome"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Desc"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "BlockCons"   , DB2_PARAM_IN);
            db2_bind_param($stmt, 6, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 7, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 8, "Note"        , DB2_PARAM_OUT);*/
            break;                  
      case 'MF': 
             $CallPlSql='CALL WFS.K_CONFIG.ModificaDipFlusso(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            //$stmt = db2_prepare($conn, $CallPlSql);
                          
			$IdFlu=$_POST['IdFlu'];       
            $Priorita=$_POST['Priorita'];
            $IdDip=$_POST['IdDip'];
            $SELFLUSSO=$_POST['SELFLUSSO'];
                
			
			 $values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdLegame',
					'value' => $IdLegame,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Priorita',
					'value' => $Priorita,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'IdDip',
					'value' => $SELFLUSSO,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Vali',
					'value' => $Vali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'InzVali',
					'value' => $InzVali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'FinVali',
					'value' => $FinVali,
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
			
				/*	echo "<pre>";
					print_r($values );
					echo "</pre>";
					die();   */
       
          /*  db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdFlu"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "Priorita"    , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "IdDip"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Vali"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 6, "InzVali"     , DB2_PARAM_IN);
            db2_bind_param($stmt, 7, "FinVali"     , DB2_PARAM_IN);
            db2_bind_param($stmt, 8, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 9, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt,10, "Note"        , DB2_PARAM_OUT);*/
            break;          
       case 'MC': 
           
            $CallPlSql='CALL WFS.K_CONFIG.ModificaCaricamento(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? , ?, ?, ?, ?, ?)';
					
         //   $stmt = db2_prepare($conn, $CallPlSql);
			
            $IdFlu=$_POST['IdFlu'];
            $IdDip=$_POST['IdDip'];
            $Priorita=$_POST['Priorita'];
            $NomeLoad=$_POST['SELCARICAMENTO'];
            $Nome=$_POST['NomeCar'];
            $CarLoad=$_POST['NOMCARICAMENTO'];
            $ConfermaDato=$_POST['ConfermaDato'];
            $Desc=$_POST['DescrCar'];
            $TarDir=$_POST['TXTCARICAMENTO'];
            $SHELL_TARGET=$_POST['SHELL_TARGET'];
            $SHELL_VAR=$_POST['SHELL_VAR'];
            $TARGET_TAB=$_POST['TARGET_TAB'];
			
			if ( "$NomeLoad" == "CarFile" ){
			    $NomeLoad=$CarLoad;
	        }
			$values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdFlu',
					'value' => $IdFlu,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'IdDip',
					'value' => $IdDip,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Priorita',
					'value' => $Priorita,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'NomeLoad',
					'value' => $NomeLoad,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Nome',
					'value' => $Nome,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'ConfermaDato',
					'value' => $ConfermaDato,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Desc',
					'value' => $Desc,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Vali',
					'value' => $Vali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'InzVali',
					'value' => $InzVali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'FinVali',
					'value' => $FinVali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'TarDir',
					'value' => $TarDir,
					'type' => DB2_PARAM_IN
					],	
					[
					'name' => 'SHELL_TARGET',
					'value' => $SHELL_TARGET,
					'type' => DB2_PARAM_IN
					],	
					[
					'name' => 'SHELL_VAR',
					'value' => $SHELL_VAR,
					'type' => DB2_PARAM_IN
					],	
					[
					'name' => 'TARGET_TAB',
					'value' => $TARGET_TAB,
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
				/*	echo "<pre>";
					print_r($values );				
					die();	*/
            /*db2_bind_param($stmt,  1, "IdWorkFlow"   , DB2_PARAM_IN);
            db2_bind_param($stmt,  2, "IdFlu"        , DB2_PARAM_IN);
            db2_bind_param($stmt,  3, "IdDip"        , DB2_PARAM_IN);
            db2_bind_param($stmt,  4, "Priorita"     , DB2_PARAM_IN);
            db2_bind_param($stmt,  5, "NomeLoad"     , DB2_PARAM_IN);
            db2_bind_param($stmt,  6, "Nome"         , DB2_PARAM_IN);
            db2_bind_param($stmt,  7, "ConfermaDato" , DB2_PARAM_IN);
            db2_bind_param($stmt,  8, "Desc"         , DB2_PARAM_IN);
            db2_bind_param($stmt,  9, "Vali"         , DB2_PARAM_IN);
            db2_bind_param($stmt, 10, "InzVali"      , DB2_PARAM_IN);
            db2_bind_param($stmt, 11, "FinVali"      , DB2_PARAM_IN);
            db2_bind_param($stmt, 12, "TarDir"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 13, "User"         , DB2_PARAM_IN);
            db2_bind_param($stmt, 14, "Errore"       , DB2_PARAM_OUT);
            db2_bind_param($stmt, 15, "Note"         , DB2_PARAM_OUT);*/
            break;
       case 'ML':
            $CallPlSql='CALL WFS.K_CONFIG.ModificaLink(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )';
           // $stmt = db2_prepare($conn, $CallPlSql);
            
            
            $IdFlu=$_POST['IdFlu'];
            $IdDip=$_POST['IdDip'];
            $Priorita=$_POST['Priorita'];
            $Nome=$_POST['NomeLink'];
            $Tipo=$_POST['TipoLink'];
            $Dest=$_POST['Destinazione'];
            $Desc=$_POST['DescrLink'];
			$Opt=$_POST['SELOPT'];
			$ID_PAR_GRUPPO=$_POST['ID_PAR_GRUPPO']?$_POST['ID_PAR_GRUPPO']:null;
			
			$values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdFlu',
					'value' => $IdFlu,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'IdDip',
					'value' => $IdDip,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Priorita',
					'value' => $Priorita,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Nome',
					'value' => $Nome,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Tipo',
					'value' => $Tipo,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Dest',
					'value' => $Dest,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Desc',
					'value' => $Desc,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Vali',
					'value' => $Vali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'InzVali',
					'value' => $InzVali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'FinVali',
					'value' => $FinVali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Opt',
					'value' => $Opt,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'ID_PAR_GRUPPO',
					'value' => $ID_PAR_GRUPPO,
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
   
									         
          /*  db2_bind_param($stmt,  1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt,  2, "IdFlu"       , DB2_PARAM_IN);
            db2_bind_param($stmt,  3, "IdDip"       , DB2_PARAM_IN);
            db2_bind_param($stmt,  4, "Priorita"    , DB2_PARAM_IN);
            db2_bind_param($stmt,  5, "Nome"        , DB2_PARAM_IN);
            db2_bind_param($stmt,  6, "Tipo"        , DB2_PARAM_IN);
            db2_bind_param($stmt,  7, "Dest"        , DB2_PARAM_IN);
            db2_bind_param($stmt,  8, "Desc"        , DB2_PARAM_IN);
            db2_bind_param($stmt,  9, "Vali"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 10, "InzVali"     , DB2_PARAM_IN);
            db2_bind_param($stmt, 11, "FinVali"     , DB2_PARAM_IN);
			db2_bind_param($stmt, 12, "Opt"         , DB2_PARAM_IN);
            db2_bind_param($stmt, 13, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 14, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 15, "Note"        , DB2_PARAM_OUT);*/
            break;
       case 'ME':
          $CallPlSql='CALL WFS.K_CONFIG.ModificaElaborazione(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
          //  $stmt = db2_prepare($conn, $CallPlSql);
            
			$IdDip=$_POST['IdDip'];
            $IdFlu=$_POST['IdFlu'];
            $Priorita=$_POST['Priorita'];
            $Nome=$_POST['NomeEla'];            
            $Tags=$_POST['Tags'];
            $Desc=$_POST['DescrEla'];
            $IdElaborazione=$_POST['SELELABORAZIONE'];
            $ReadOnly=$_POST['ReadOnly'];
            $SaltaElab=$_POST['SaltaElab'];
            $ShowProc=$_POST['ShowProc'];
			$Parametri=$_POST['Parametri'];	            
			$values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdFlu',
					'value' => $IdFlu,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'IdDip',
					'value' => $IdDip,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Priorita',
					'value' => $Priorita,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Nome',
					'value' => $Nome,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Tags',
					'value' => $Tags,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Desc',
					'value' => $Desc,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'ReadOnly',
					'value' => $ReadOnly,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'IdElaborazione',
					'value' => $IdElaborazione,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Vali',
					'value' => $Vali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'InzVali',
					'value' => $InzVali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'FinVali',
					'value' => $FinVali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'SaltaElab',
					'value' => $SaltaElab,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'ShowProc',
					'value' => $ShowProc,
					'type' => DB2_PARAM_IN
					],					 
					[
					'name' => 'User',
					'value' => $_SESSION['codname'],
					'type' => DB2_PARAM_IN
					],
					[
					'name' => 'Parametri',
					'value' => $Parametri,
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
            
         /*   db2_bind_param($stmt, 1, "IdWorkFlow"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdFlu"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "IdDip"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Priorita"          , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Nome"              , DB2_PARAM_IN);
            db2_bind_param($stmt, 6, "Tags"              , DB2_PARAM_IN);
            db2_bind_param($stmt, 7, "Desc"              , DB2_PARAM_IN);
            db2_bind_param($stmt, 8, "ReadOnly"          , DB2_PARAM_IN);
            db2_bind_param($stmt, 9, "IdElaborazione"    , DB2_PARAM_IN);
            db2_bind_param($stmt,10, "Vali"              , DB2_PARAM_IN);
            db2_bind_param($stmt,11, "InzVali"           , DB2_PARAM_IN);
            db2_bind_param($stmt,12, "FinVali"           , DB2_PARAM_IN);
            db2_bind_param($stmt,13, "SaltaElab"         , DB2_PARAM_IN);
            db2_bind_param($stmt,14, "ShowProc"          , DB2_PARAM_IN);
            db2_bind_param($stmt,15, "User"              , DB2_PARAM_IN);
            db2_bind_param($stmt,16, "Errore"            , DB2_PARAM_OUT);
            db2_bind_param($stmt,17, "Note"              , DB2_PARAM_OUT);*/
            break;
       case 'MV':
            $CallPlSql='CALL WFS.K_CONFIG.ModificaValidazione(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
          //  $stmt = db2_prepare($conn, $CallPlSql);

            $IdDip=$_POST['IdDip'];
            $IdFlu=$_POST['IdFlu'];
            $Priorita=$_POST['Priorita'];
            $Nome=$_POST['NomeVal'];
            $Desc=$_POST['DescrVal'];
            $External=$_POST['External'];
			
			$values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdFlu',
					'value' => $IdFlu,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'IdDip',
					'value' => $IdDip,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Priorita',
					'value' => $Priorita,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Nome',
					'value' => $Nome,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Desc',
					'value' => $Desc,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'External',
					'value' => $External,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'Vali',
					'value' => $Vali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'InzVali',
					'value' => $InzVali,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'FinVali',
					'value' => $FinVali,
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
            db2_bind_param($stmt, 2, "IdFlu"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "IdDip"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Priorita"         , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Nome"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 6, "Desc"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 7, "External"         , DB2_PARAM_IN);
            db2_bind_param($stmt, 8, "Vali"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 9, "InzVali"          , DB2_PARAM_IN);
            db2_bind_param($stmt,10, "FinVali"          , DB2_PARAM_IN);
            db2_bind_param($stmt,11, "User"             , DB2_PARAM_IN);
            db2_bind_param($stmt,12, "Errore"           , DB2_PARAM_OUT);
            db2_bind_param($stmt,13, "Note"             , DB2_PARAM_OUT);*/
            break;  
        case 'RAF': 
            $CallPlSql='CALL WFS.K_CONFIG.RimuoviFlusso(?, ?, ?, ?, ?)';
         //   $stmt = db2_prepare($conn, $CallPlSql);
                
            $IdFlu=$_POST['IdFlu']; 
			
			
			$values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdFlu',
					'value' => $IdFlu,
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
                
         /*   db2_bind_param($stmt, 1, "IdWorkFlow"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdFlu"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "User"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Errore"           , DB2_PARAM_OUT);
            db2_bind_param($stmt, 5, "Note"             , DB2_PARAM_OUT);*/
            break;      
        case 'RF': 
            $CallPlSql='CALL WFS.K_CONFIG.RimuoviDipFlusso(?, ?, ?, ?, ?)';
            //$stmt = db2_prepare($conn, $CallPlSql);
                
            $IdLeg=$_POST['IdLeg']; 
			
			$values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
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
                                        
        /*    db2_bind_param($stmt, 1, "IdWorkFlow"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdLeg"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "User"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Errore"           , DB2_PARAM_OUT);
            db2_bind_param($stmt, 5, "Note"             , DB2_PARAM_OUT);*/
            break;
       case 'RC': 
            $CallPlSql='CALL WFS.K_CONFIG.RimuoviCaricamento(?, ?, ?, ?, ?, ?)';
           // $stmt = db2_prepare($conn, $CallPlSql);
            
            $IdCar=$_POST['IdDip'];
            $IdLeg=$_POST['IdLeg'];
			
				$values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdCar',
					'value' => $IdCar,
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
            
         /*   db2_bind_param($stmt, 1, "IdWorkFlow"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdCar"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "IdLeg"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "User"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Errore"           , DB2_PARAM_OUT);
            db2_bind_param($stmt, 6, "Note"             , DB2_PARAM_OUT);*/
            break;
       case 'RL':
            $CallPlSql='CALL WFS.K_CONFIG.RimuoviLink(?, ?, ?, ?, ?, ? )';
         //   $stmt = db2_prepare($conn, $CallPlSql);
            
            $IdLink=$_POST['IdDip'];
            $IdLeg=$_POST['IdLeg'];
			
			$values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdLink',
					'value' => $IdLink,
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
            db2_bind_param($stmt, 2, "IdLink"           , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "IdLeg"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "User"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Errore"           , DB2_PARAM_OUT);
            db2_bind_param($stmt, 6, "Note"             , DB2_PARAM_OUT);*/
            break;
       case 'RE':
            $CallPlSql='CALL WFS.K_CONFIG.RimuoviElaborazione(?, ?, ?, ?, ?, ? )';
        //    $stmt = db2_prepare($conn, $CallPlSql);
            
            $IdElab=$_POST['IdDip'];
            $IdLeg=$_POST['IdLeg'];
            $values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdElab',
					'value' => $IdElab,
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
			
         /*   db2_bind_param($stmt, 1, "IdWorkFlow"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdElab"           , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "IdLeg"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "User"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Errore"           , DB2_PARAM_OUT);
            db2_bind_param($stmt, 6, "Note"             , DB2_PARAM_OUT);*/
            break;
       case 'RV':
            $CallPlSql='CALL WFS.K_CONFIG.RimuoviValidazione(?, ?, ?, ?, ?, ? )';
         //   $stmt = db2_prepare($conn, $CallPlSql);

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
            break;   
			case 'RDIS': 
            $CallPlSql="UPDATE WFS.LEGAME_FLUSSI SET TMS_INSERT=current_timestamp, FIN_VALID=TO_CHAR(current_date-1 month,'YYYYMM') WHERE ID_LEGAME = ?";
          //  $stmt = db2_prepare($conn, $CallPlSql);
                
            $IdLegame=$_POST['IdLegame']; 
			$values = [[
					'name' => 'IdLegame',
					'value' => $IdLegame,
					'type' => DB2_PARAM_IN
					]
					];
         //   db2_bind_param($stmt, 1, "IdLegame"       , DB2_PARAM_IN);
            break;     			
    }   
	
	
	$ret = $this->_db->callDb($CallPlSql ,$values);
/*	$this->_db->printSql();
	die();*/
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

	
		 	
	
	
	public function getDipFlusso($Tipo ,$IdDip,$IdWorkFlow,$IdFlu)
	
		{
			try
			{
		$ret=[];
		 switch ( $Tipo ){	
			case "FL":
      
        $sql="SELECT FLUSSO, DESCR, BLOCK_CONS FROM WFS.FLUSSI WHERE ID_FLU = $IdDip and ID_WORKFLOW = '$IdWorkFlow'";
        $res = $this->_db->getArrayByQuery($sql);	
        foreach ($res as $rowT) {
            $ret['TabNome']=$rowT['FLUSSO'];
            $ret['TabDesc']=$rowT['DESCR'];
            $ret['TabBlockCons']=$rowT['BLOCK_CONS'];
        }
        $sql="SELECT DISTINCT LIV, (SELECT count(*) FROM WFS.FLUSSI_LIV_PERS WHERE ID_FLU = $IdDip and ID_WORKFLOW = '$IdWorkFlow' ) LIVPRES FROM WFS.LEGAME_FLUSSI WHERE ID_FLU = $IdDip and ID_WORKFLOW = '$IdWorkFlow'";
        $res = $this->_db->getArrayByQuery($sql);	
       foreach ($res as $rowT) {
            $ret['TabLiv']=$rowT['LIV'];
            $ret['LivPers']=$rowT['LIVPRES'];
        }      
        
        break;          
		case "F": 	
		if(isset($IdDip)){
			 $sql="SELECT FLUSSO, DESCR,
			( SELECT PRIORITA  FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'F' AND ID_FLU = $IdFlu AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') PRIO,
			( SELECT VALIDITA  FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'F' AND ID_FLU = $IdFlu AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') VALIDITA,
			( SELECT INZ_VALID FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'F' AND ID_FLU = $IdFlu AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') INZ_VALID,
			( SELECT FIN_VALID FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'F' AND ID_FLU = $IdFlu AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') FIN_VALID
			FROM WFS.FLUSSI WHERE ID_FLU = $IdDip and ID_WORKFLOW = '$IdWorkFlow'";
			
			$res = $this->_db->getArrayByQuery($sql);	
			//print_r($res );
			foreach ($res as $rowT) {
				$ret['TabNome']=$rowT['FLUSSO'];
				$ret['TabDesc']=$rowT['DESCR'];
				$ret['TabPrio']=$rowT['PRIO'];
				$ret['TabVali']=$rowT['VALIDITA'];
				$ret['TabInzVali']=$rowT['INZ_VALID'];
				$ret['TabFinVali']=$rowT['FIN_VALID'];
			   // $ret['TabTDir']=$rowT['TARGET_DIR'];
			}  
		}		
		$sql="SELECT ID_FLU, ID_WORKFLOW, FLUSSO FROM WFS.FLUSSI WHERE ID_FLU != $IdFlu AND ID_WORKFLOW = $IdWorkFlow ORDER BY FLUSSO";
		$res = $this->_db->getArrayByQuery($sql);
		$ret['datiSelectF']=$res;
      break;    
      case "C":

		$sql="SELECT 
        NOME_INPUT, CARICAMENTO, DESCR, CONFERMA_DATO,TARGET_DIR,SHELL_TARGET,SHELL_VAR,TARGET_TAB,
        ( SELECT PRIORITA  FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'C' AND ID_FLU = $IdFlu AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') PRIO,
		( SELECT VALIDITA  FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'C' AND ID_FLU = $IdFlu AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') VALIDITA,
		( SELECT INZ_VALID FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'C' AND ID_FLU = $IdFlu AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') INZ_VALID,
		( SELECT FIN_VALID FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'C' AND ID_FLU = $IdFlu AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') FIN_VALID
        FROM WFS.CARICAMENTI WHERE ID_CAR = $IdDip and ID_WORKFLOW = '$IdWorkFlow'";
        $res = $this->_db->getArrayByQuery($sql);	
		foreach ($res as $rowT) {
            $ret['TabNome']=$rowT['CARICAMENTO'];
            $ret['TabDesc']=$rowT['DESCR'];
            $ret['TabInput']=$rowT['NOME_INPUT'];
            $ret['TabPrio']=$rowT['PRIO'];
            $ret['TabConfermaDato']=$rowT['CONFERMA_DATO']; 
			$ret['TabVali']=$rowT['VALIDITA']; 
			$ret['TabInzVali']=$rowT['INZ_VALID'];
			$ret['TabFinVali']=$rowT['FIN_VALID'];
            $ret['TabTDir']=$rowT['TARGET_DIR'];
            $ret['TabShellTarget']=$rowT['SHELL_TARGET'];
            $ret['TabShellVar']=$rowT['SHELL_VAR'];
            $ret['TabTargetTab']=$rowT['TARGET_TAB'];
			
        }
		
		$sql="SELECT DISTINCT FLUSSO NOME_FLUSSO
                                    FROM WFS.LOAD_ANAG
                                    WHERE FLUSSO NOT IN ( SELECT NOME_INPUT FROM WFS.CARICAMENTI WHERE NOME_INPUT != ?) 
                                    ORDER BY FLUSSO";


		$res = $this->_db->getArrayByQuery($sql,[$ret['TabInput']]);
		$ret['datiSelectC']=$res;
      /*  SELECT DISTINCT NOME_FLUSSO 

                                    FROM WORK_RULES.LOAD_TRASCODIFICA 

                                    WHERE NOME_FLUSSO NOT IN ( SELECT NOME_INPUT FROM WFS.CARICAMENTI ) 

                                    ORDER BY NOME_FLUSSO*/
 
        
        break;    
  case "L":


		if($IdDip){
          $sql="SELECT  TIPO,LINK,TARGET,DESCR,ID_PAR_GRUPPO,
        ( SELECT PRIORITA  FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'L' AND ID_FLU = $IdFlu AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') PRIO,
		( SELECT VALIDITA  FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'L' AND ID_FLU = $IdFlu AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') VALIDITA,
		( SELECT INZ_VALID FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'L' AND ID_FLU = $IdFlu AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') INZ_VALID,
		( SELECT FIN_VALID FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'L' AND ID_FLU = $IdFlu AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') FIN_VALID,
		( SELECT OPT FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'L' AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') OPT
        FROM WFS.LINKS WHERE ID_LINK = $IdDip and ID_WORKFLOW = '$IdWorkFlow'";
		}else{
		 $sql="SELECT TARGET FROM WFS.LINKS WHERE ID_WORKFLOW = $IdWorkFlow AND TIPO = 'I' ";
		}
        $res = $this->_db->getArrayByQuery($sql);	
		foreach ($res as $rowT) {
            $ret['TabNome']=$rowT['LINK'];
            $ret['TabDesc']=$rowT['DESCR'];
            $ret['TabTipo']=$rowT['TIPO'];
            $ret['TabIdParGruppo']=$rowT['ID_PAR_GRUPPO'];
            $ret['TabDest']=$rowT['TARGET'];
            $ret['TabPrio']=$rowT['PRIO'];
			$ret['TabVali']=$rowT['VALIDITA'];
			$ret['TabInzVali']=$rowT['INZ_VALID'];
			$ret['TabFinVali']=$rowT['FIN_VALID'];
            $ret['TabOpt']=$rowT['OPT'];
        }

        $ret['ArrListConfPhp']=array();
        $sql="SELECT TARGET FROM WFS.LINKS WHERE ID_WORKFLOW = $IdWorkFlow AND TIPO = 'I' ";
        $res = $this->_db->getArrayByQuery($sql);
       
        foreach ($res as $row) {
            $ret['TargLink']=$row['TARGET']; 
            array_push($ret['ArrListConfPhp'],$ret['TargLink']);
        }        
      
        break;          
      case "E":
//$ret['TabSh'] = 0;
		if(!$IdDip)
		{
		$sql="SELECT ID_SH, SHELL 
				FROM (
				  SELECT DISTINCT ID_SH, SHELL
				  FROM WORK_CORE.CORE_SH_ANAG 
				  WHERE ID_SH NOT IN ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE SHELL != 'ConsolidaIdProcess.sh'  AND ID_WORKFLOW = $IdWorkFlow ) 
				  AND WFS = 'Y'
				  UNION ALL
				  SELECT ID_SH, SHELL
				  FROM WORK_CORE.CORE_SH_ANAG 
				  WHERE ID_SH = ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE SHELL = 'ConsolidaIdProcess.sh' AND ID_WORKFLOW = $IdWorkFlow  ) 
				  AND ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE SHELL = 'ConsolidaIdProcess.sh' AND ID_WORKFLOW = $IdWorkFlow  ) NOT IN ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE SHELL != 'ConsolidaIdProcess.sh'  AND ID_WORKFLOW = $IdWorkFlow )
				  AND WFS = 'Y'
				)
				ORDER BY SHELL";
		}else{
		$sql="SELECT  ID_SH,ELABORAZIONE,TAGS,DESCR,SALTA_ELAB,SHOWPROC,
			( SELECT PRIORITA FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'E' AND ID_FLU = $IdFlu  AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') PRIO,
			READONLY,
			( SELECT VALIDITA FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'E' AND ID_FLU = $IdFlu  AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') VALIDITA,
			( SELECT INZ_VALID FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'E' AND ID_FLU = $IdFlu  AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') INZ_VALID,
			( SELECT FIN_VALID FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'E' AND ID_FLU = $IdFlu  AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') FIN_VALID,
			PARAMETRI
			FROM WFS.ELABORAZIONI WHERE ID_ELA = $IdDip and ID_WORKFLOW = '$IdWorkFlow'";
		}
        $res = $this->_db->getArrayByQuery($sql);	
		foreach ($res as $rowT) {
            $ret['TabNome']=$rowT['ELABORAZIONE'];
            $ret['TabDesc']=$rowT['DESCR'];
            $ret['TabSh']=$rowT['ID_SH'];
            $ret['TabTags']=$rowT['TAGS'];
            $ret['TabPrio']=$rowT['PRIO'];
            $ret['TabROnly']=$rowT['READONLY'];
			$ret['TabVali']=$rowT['VALIDITA'];
			$ret['TabInzVali']=$rowT['INZ_VALID'];
			$ret['TabFinVali']=$rowT['FIN_VALID'];
			$ret['TabSalta']=$rowT['SALTA_ELAB'];
			$ret['TabShowProc']=$rowT['SHOWPROC'];
			$ret['TabParametri']=$rowT['PARAMETRI'];
        }  
		
		  $sql="SELECT ID_SH, SHELL 
                                FROM (
                                  SELECT DISTINCT ID_SH, SHELL
                                  FROM WORK_CORE.CORE_SH_ANAG 
								  WHERE 1=1
                                 -- WHERE ID_SH NOT IN ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE SHELL != 'ConsolidaIdProcess.sh' AND ID_SH != ".$ret['TabSh']."   AND ID_WORKFLOW = $IdWorkFlow ) 
                                  AND WFS = 'Y'
                                  UNION ALL
                                  SELECT ID_SH, SHELL
                                  FROM WORK_CORE.CORE_SH_ANAG 
                                  WHERE ID_SH = ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE SHELL = 'ConsolidaIdProcess.sh' AND ID_WORKFLOW = $IdWorkFlow  ) 
								  AND ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE SHELL = 'ConsolidaIdProcess.sh' AND ID_WORKFLOW = $IdWorkFlow  ) NOT IN ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE SHELL != 'ConsolidaIdProcess.sh'  AND ID_WORKFLOW = $IdWorkFlow )
                                  AND WFS = 'Y'
                                )
                                ORDER BY SHELL";


 /*$sql="SELECT ID_SH, SHELL 
                                FROM (
                                  SELECT DISTINCT ID_SH, SHELL
                                  FROM WORK_CORE.CORE_SH_ANAG 
                                  WHERE ID_SH NOT IN ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE SHELL != 'ConsolidaIdProcess.sh'  AND ID_WORKFLOW = $IdWorkFlow ) 
                                  AND WFS = 'Y'
                                  UNION ALL
                                  SELECT ID_SH, SHELL
                                  FROM WORK_CORE.CORE_SH_ANAG 
                                  WHERE ID_SH = ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE SHELL = 'ConsolidaIdProcess.sh' AND ID_WORKFLOW = $IdWorkFlow  ) 
								  AND ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE SHELL = 'ConsolidaIdProcess.sh' AND ID_WORKFLOW = $IdWorkFlow  ) NOT IN ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE SHELL != 'ConsolidaIdProcess.sh'  AND ID_WORKFLOW = $IdWorkFlow )
                                  AND WFS = 'Y'
                                )
                                ORDER BY SHELL";	*/							
		$res = $this->_db->getArrayByQuery($sql,[$ret['TabInput']]);
		$ret['datiSelectE']=$res;								
                
	  break;          
      case "V":

          $sql="SELECT VALIDAZIONE,DESCR,
				( SELECT PRIORITA FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'V' AND ID_FLU = $IdFlu  AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') PRIO,
				EXTERNAL,
				( SELECT VALIDITA FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'V' AND ID_FLU = $IdFlu AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') VALIDITA,
				( SELECT INZ_VALID FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'V' AND ID_FLU = $IdFlu  AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') INZ_VALID,
				( SELECT FIN_VALID FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'V' AND ID_FLU = $IdFlu AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') FIN_VALID
				FROM WFS.VALIDAZIONI WHERE ID_VAL = $IdDip and ID_WORKFLOW = '$IdWorkFlow'";
    //    $stmtT=db2_prepare($conn, $sqlT);
        $res = $this->_db->getArrayByQuery($sql);	
		
		foreach ($res as $rowT) {
            $ret['TabNome']=$rowT['VALIDAZIONE'];
            $ret['TabDesc']=$rowT['DESCR'];
            $ret['TabPrio']=$rowT['PRIO'];
			$ret['TabVali']=$rowT['VALIDITA'];
			$ret['TabExt']=$rowT['EXTERNAL'];
			$ret['TabInzVali']=$rowT['INZ_VALID'];
			$ret['TabFinVali']=$rowT['FIN_VALID'];
        }   
      
        break;      
    }
				
				
				
				
				$res = $this->_db->getArrayByQuery($sql,$value);	
				 
                return $ret;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
		}	
		
		
		
		public function getLegamiFlussi($IdFlusso ,$IdWorkFlow)
		{
	   $ArrLegami=array();
       $SqlList="SELECT ID_LEGAME,LIV,ID_FLU,PRIORITA,TIPO,ID_DIP
	  -- ( SELECT FLUSSO FROM WFS.LOAD_ANAG WHERE ID_FLU = L.ID_FLU ) NOME_FLUSSO
	   FROM WFS.LEGAME_FLUSSI 
	   WHERE ID_WORKFLOW = $IdWorkFlow 
	  -- AND DECODE(VALIDITA,null,' $ProcMeseEsame ',' '||VALIDITA||' ') like '% $ProcMeseEsame %'
	 --  AND INZ_VALID <= INZ_VALFROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
	 --  AND FIN_VALID >= INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
	   AND ( ID_FLU = $IdFlusso OR ( TIPO = 'F' AND ID_DIP = $IdFlusso ) )
	    ORDER BY LIV asc, TIPO asc, ID_DIP asc";
       
	  $res = $this->_db->getArrayByQuery($SqlList,[]);
      foreach ($res as $row) {
           $Id=$row['ID_LEGAME']; 
           $Liv=$row['LIV']; 
           $IdFlu=$row['ID_FLU']; 
           $Prio=$row['PRIORITA']; 
           $Tipo=$row['TIPO']; 
           $IdDip=$row['ID_DIP']; 
               
           array_push($ArrLegami,array($Id,$Liv,$IdFlu,$Prio,$Tipo,$IdDip));
       }
	   
	//DIP FLUSSI
	$ArrPreFlussi=array();       
	foreach( $ArrLegami as $Legame ){
		//ID_LEGAME,LIV,ID_FLU,PRIORITA,TIPO,ID_DIP
		$IdFlu=$Legame[2];
		$Tipo=$Legame[4];
		$IdDip=$Legame[5];
		if ( $Tipo == "F" and $IdFlu == $IdFlusso ){
		   array_push($ArrPreFlussi,$IdDip);
		}
		
	}
	
	
	$ArrSucFlussi=array();
	   foreach( $ArrLegami as $Legame ){
		//ID_LEGAME,LIV,ID_FLU,PRIORITA,TIPO,ID_DIP
			$IdFlu=$Legame[2];
			$Tipo=$Legame[4];
			$IdDip=$Legame[5];
			if ( $Tipo == "F" and $IdDip == $IdFlusso ){
			   array_push($ArrSucFlussi,$IdFlu);
			}
		}
	$ArrFlussi =[];	
	$ArrFlussi['pre'] = $ArrPreFlussi;	
	$ArrFlussi['suc'] = $ArrSucFlussi;	
	return $ArrFlussi;
	}
	
	//getGruppiByIdWorkflow dalla tabella WFS.LINK_PARAMETRI_GRUPPO 
	public function getGruppiByIdWorkflow($IdWorkFlow){
		
		$sql="SELECT * FROM WFS.LINK_PARAMETRI_GRUPPO WHERE ID_WORKFLOW = $IdWorkFlow ";
		$res = $this->_db->getArrayByQuery($sql);
		return $res;
		
	
	}
	
}
?>