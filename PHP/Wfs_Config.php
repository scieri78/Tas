<?php 
    $BodyHeight=$_POST['BodyHeight'];
?>
<style>
  #ScrollDiv{
    position: fixed;
    width: 500px;
    height: 30px;
    bottom: 12px;
    z-index: 999999;
    left: 0;
    right: 0;
    margin: auto;
  }

  #footer {
    height: 40px;
  }  
  
  #ScrollTop{
    padding:6px;
  }  

  #ScrollBt{
    width:160px;
  }  

  #ScrollEnd{
    padding:6px;
  }  
  body{
      height: <?php echo $BodyHeight; ?>px;
  }

</style>
<?php
include '../GESTIONE/sicurezza.php';
  
if ( "$find" == "1" )  {
    
    $TServer="Jiak";
    
    $TopScroll=$_POST['TopScroll'];
    $BodyHeight=$_POST['BodyHeight'];
    $IdWorkFlow=$_REQUEST['IdWorkFlow'];
    $IdTeam=$_POST['IdTeam'];
        
    $sql="SELECT WORKFLOW, DESCR
    FROM  WFS.WORKFLOW
    WHERE ID_WORKFLOW = $IdWorkFlow";

    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    while ($row = db2_fetch_assoc($stmt)) {
        $NomeWfs=$row['WORKFLOW'];
        $DescWfs=$row['DESCR'];
    }

    $Azione=$_POST['Azione'];
    $Flusso=$_POST['Flusso'];

    $Errore=0;
    $ShowErrore=0;
    $Note="";
            
            
    if ( "$Azione" != ""  ) {
       $Vali=$_POST['SELVAL'];
    }   
            
            
    switch($Azione){
       case 'RIL': 
	   
			$Id_List="";
			$sql="SELECT ID_FLU FROM WFS.FLUSSI WHERE ID_WORKFLOW = $IdWorkFlow ORDER BY FLUSSO";
	        $stmt=db2_prepare($conn, $sql);
            $result=db2_execute($stmt);
            while ($row = db2_fetch_assoc($stmt)) {
                $IdFls=$row['ID_FLU'];
				$TestF=$_POST['ChkRilascia_'.$IdFls];
			    if ( "$TestF" == "1" ){
					$Id_List=$Id_List.$IdFls.",";
				}
	        }
			$Id_List=rtrim($Id_List,',');			
            $CallPlSql='CALL WFS.K_RILASCIO.CreaRilascio(?, ?, ?, ?, ?)';
            $stmt = db2_prepare($conn, $CallPlSql);
            
            db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "Id_List"     , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 5, "Note"        , DB2_PARAM_OUT);
			
            break;
       case 'AF': 
            $CallPlSql='CALL WFS.K_CONFIG.AggiungiFlusso(?, ?, ?, ?, ?, ?, ?)';
            $stmt = db2_prepare($conn, $CallPlSql);
                
            $NomeFlusso=$_POST['NomeFlusso'];   
            $DescFlusso=$_POST['DescFlusso'];
            $BlockCons=$_POST['BlockCons'];
            
                
            db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "NomeFlusso"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "DescFlusso"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "BlockCons"   , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 6, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 7, "Note"        , DB2_PARAM_OUT);
            break;
       case 'ADF': 
            $CallPlSql='CALL WFS.K_CONFIG.AggiungiDipFlusso(?, ?, ?, ?, ?, ?, ?, ?)';
            $stmt = db2_prepare($conn, $CallPlSql);
                     
            $IdFlu=$_POST['IdFlu'];       
            $Priorita=$_POST['Priorita'];
            $IdDip=$_POST['SELFLUSSO'];
                
            db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdFlu"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "Priorita"    , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "IdDip"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Vali"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 6, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 7, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 8, "Note"        , DB2_PARAM_OUT);
            break;          
       case 'AC': 
           
            $CallPlSql='CALL WFS.K_CONFIG.AggiungiCaricamento(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )';
            $stmt = db2_prepare($conn, $CallPlSql);
            
            $IdFlu=$_POST['IdFlu'];
            $Priorita=$_POST['Priorita'];
            $NomeLoad=$_POST['SELCARICAMENTO'];
            $Nome=$_POST['NomeCar'];
            $Desc=$_POST['DescrCar'];
            $ConfermaDato=$_POST['ConfermaDato'];
            
            
            db2_bind_param($stmt,  1, "IdWorkFlow"       , DB2_PARAM_IN);
            db2_bind_param($stmt,  2, "IdFlu"            , DB2_PARAM_IN);
            db2_bind_param($stmt,  3, "Priorita"         , DB2_PARAM_IN);
            db2_bind_param($stmt,  4, "NomeLoad"         , DB2_PARAM_IN);
            db2_bind_param($stmt,  5, "Nome"             , DB2_PARAM_IN);
            db2_bind_param($stmt,  6, "Desc"             , DB2_PARAM_IN);
            db2_bind_param($stmt,  7, "ConfermaDato"     , DB2_PARAM_IN);
            db2_bind_param($stmt,  8, "Vali"             , DB2_PARAM_IN);
            db2_bind_param($stmt,  9, "User"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 10, "Errore"           , DB2_PARAM_OUT);
            db2_bind_param($stmt, 11, "Note"             , DB2_PARAM_OUT);
            break;
       case 'AL':
            $CallPlSql='CALL WFS.K_CONFIG.AggiungiLink(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $stmt = db2_prepare($conn, $CallPlSql);
            
            $IdFlu=$_POST['IdFlu'];
            $Priorita=$_POST['Priorita'];
            $Nome=$_POST['NomeLink'];
            $TipoLink=$_POST['TipoLink'];
            $Dest=$_POST['Destinazione'];
            $Desc=$_POST['DescrLink'];   
			$Opt=$_POST['SELOPT'];
            
            db2_bind_param($stmt,  1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt,  2, "IdFlu"       , DB2_PARAM_IN);
            db2_bind_param($stmt,  3, "Priorita"    , DB2_PARAM_IN);
            db2_bind_param($stmt,  4, "Nome"        , DB2_PARAM_IN);
            db2_bind_param($stmt,  5, "TipoLink"    , DB2_PARAM_IN);
            db2_bind_param($stmt,  6, "Dest"        , DB2_PARAM_IN);
            db2_bind_param($stmt,  7, "Desc"        , DB2_PARAM_IN);
            db2_bind_param($stmt,  8, "Vali"        , DB2_PARAM_IN);
			db2_bind_param($stmt,  9, "Opt"         , DB2_PARAM_IN);
            db2_bind_param($stmt, 10, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 11, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 12, "Note"        , DB2_PARAM_OUT);
            break;
       case 'AE':
            $CallPlSql='CALL WFS.K_CONFIG.AggiungiElaborazione(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $stmt = db2_prepare($conn, $CallPlSql);
            
            $IdFlu=$_POST['IdFlu'];
            $Priorita=$_POST['Priorita'];
            $Nome=$_POST['NomeEla'];
            $Desc=$_POST['DescrEla'];
            $IdElaborazione=$_POST['SELELABORAZIONE'];
            $Tags=$_POST['Tags'];
            $ReadOnly=$_POST['ReadOnly'];
            $SaltaElab=$_POST['SaltaElab'];
			$ShowProc=$_POST['ShowProc'];
            
            
            db2_bind_param($stmt, 1, "IdWorkFlow"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdFlu"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "Priorita"          , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Nome"              , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Tags"              , DB2_PARAM_IN);
            db2_bind_param($stmt, 6, "Desc"              , DB2_PARAM_IN);
            db2_bind_param($stmt, 7, "ReadOnly"          , DB2_PARAM_IN);
            db2_bind_param($stmt, 8, "IdElaborazione"    , DB2_PARAM_IN);
            db2_bind_param($stmt, 9, "Vali"              , DB2_PARAM_IN);
            db2_bind_param($stmt,10, "SaltaElab"         , DB2_PARAM_IN);
            db2_bind_param($stmt,11, "ShowProc"          , DB2_PARAM_IN);
            db2_bind_param($stmt,12, "User"              , DB2_PARAM_IN);
            db2_bind_param($stmt,13, "Errore"            , DB2_PARAM_OUT);
            db2_bind_param($stmt,14, "Note"              , DB2_PARAM_OUT);
            break;
       case 'AV':
            $CallPlSql='CALL WFS.K_CONFIG.AggiungiValidazione(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $stmt = db2_prepare($conn, $CallPlSql);

            $IdFlu=$_POST['IdFlu'];
            $Priorita=$_POST['Priorita'];
            $Nome=$_POST['NomeVal'];
            $Desc=$_POST['DescrVal'];
            $External=$_POST['ExtVal'];       

            db2_bind_param($stmt, 1, "IdWorkFlow"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdFlu"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "Priorita"         , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Nome"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Desc"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 6, "External"         , DB2_PARAM_IN);
            db2_bind_param($stmt, 7, "Vali"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 8, "User"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 9, "Errore"           , DB2_PARAM_OUT);
            db2_bind_param($stmt,10, "Note"             , DB2_PARAM_OUT);
            break;
        case 'MFL': 
            $CallPlSql='CALL WFS.K_CONFIG.ModificaFlusso(?, ?, ?, ?, ?, ?, ?, ?)';
            $stmt = db2_prepare($conn, $CallPlSql);
                          
            $IdFlu=$_POST['IdDip'];       
            $Nome=$_POST['NomeFlu'];
            $Desc=$_POST['DescrFlu'];
            $BlockCons=$_POST['BlockCons'];
	
            $LivPersAuto=$_POST['LivPersAuto'];			
			if ( "$LivPersAuto" == "" ){$LivPersAuto=0;}
			
			$LivPersNum=$_POST['LivPersNum'];
			$TabPersNum=$_POST['TabPersNum'];
			
			$sqlT="DELETE FROM WFS.FLUSSI_LIV_PERS WHERE ID_FLU = $IdFlu and ID_WORKFLOW = $IdWorkFlow ";
			$stmtT=db2_prepare($conn, $sqlT);
            $resT=db2_execute($stmtT);
			if ( ! $resT) {
              echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg();
            } else {
			  if( "$LivPersAuto" != "1" ){
			      $sqlT="INSERT INTO WFS.FLUSSI_LIV_PERS(ID_WORKFLOW,ID_FLU) VALUES($IdWorkFlow,$IdFlu)";
			    	$stmtT=db2_prepare($conn, $sqlT);
                  $resT=db2_execute($stmtT);
                  if ( ! $resT) {
                    echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg();
                  } else {
				   if ( "$LivPersNum" != "$TabPersNum" ){	  
			         $sqlT="UPDATE WFS.LEGAME_FLUSSI SET LIV = $LivPersNum WHERE ID_FLU = $IdFlu and ID_WORKFLOW = $IdWorkFlow ";
			         $stmtT=db2_prepare($conn, $sqlT);
                     $resT=db2_execute($stmtT);
                     if ( ! $resT) {
                       echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg();
                     }
				   }
			     }
			  }
			}
			
            db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdFlu"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "Nome"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Desc"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "BlockCons"   , DB2_PARAM_IN);
            db2_bind_param($stmt, 6, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 7, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 8, "Note"        , DB2_PARAM_OUT);
            break;                  
      case 'MF': 
            $CallPlSql='CALL WFS.K_CONFIG.ModificaDipFlusso(?, ?, ?, ?, ?, ?, ?, ?)';
            $stmt = db2_prepare($conn, $CallPlSql);
                          
            $IdFlu=$_POST['IdFlu'];       
            $Priorita=$_POST['Priorita'];
            $IdDip=$_POST['SELFLUSSO'];
                
            db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdFlu"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "Priorita"    , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "IdDip"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Vali"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 6, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 7, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 8, "Note"        , DB2_PARAM_OUT);
            break;          
       case 'MC': 
           
            $CallPlSql='CALL WFS.K_CONFIG.ModificaCaricamento(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )';
            $stmt = db2_prepare($conn, $CallPlSql);
            
            
            $IdDip=$_POST['IdDip'];
            $IdFlu=$_POST['IdFlu'];
            $Priorita=$_POST['Priorita'];
            $NomeLoad=$_POST['SELCARICAMENTO'];
            $Nome=$_POST['NomeCar'];
            $Desc=$_POST['DescrCar'];
            $ConfermaDato=$_POST['ConfermaDato'];
            
            db2_bind_param($stmt,  1, "IdWorkFlow"       , DB2_PARAM_IN);
            db2_bind_param($stmt,  2, "IdFlu"            , DB2_PARAM_IN);
            db2_bind_param($stmt,  3, "IdDip"            , DB2_PARAM_IN);
            db2_bind_param($stmt,  4, "Priorita"         , DB2_PARAM_IN);
            db2_bind_param($stmt,  5, "NomeLoad"         , DB2_PARAM_IN);
            db2_bind_param($stmt,  6, "Nome"             , DB2_PARAM_IN);
            db2_bind_param($stmt,  7, "ConfermaDato"     , DB2_PARAM_IN);
            db2_bind_param($stmt,  8, "Desc"             , DB2_PARAM_IN);
            db2_bind_param($stmt,  9, "Vali"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 10, "User"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 11, "Errore"           , DB2_PARAM_OUT);
            db2_bind_param($stmt, 12, "Note"             , DB2_PARAM_OUT);
            break;
       case 'ML':
            $CallPlSql='CALL WFS.K_CONFIG.ModificaLink(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )';
            $stmt = db2_prepare($conn, $CallPlSql);
            
            
            $IdFlu=$_POST['IdFlu'];
            $IdDip=$_POST['IdDip'];
            $Priorita=$_POST['Priorita'];
            $Nome=$_POST['NomeLink'];
            $Tipo=$_POST['TipoLink'];
            $Dest=$_POST['Destinazione'];
            $Desc=$_POST['DescrLink'];
			$Opt=$_POST['SELOPT'];
   
									         
            db2_bind_param($stmt,  1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt,  2, "IdFlu"       , DB2_PARAM_IN);
            db2_bind_param($stmt,  3, "IdDip"       , DB2_PARAM_IN);
            db2_bind_param($stmt,  4, "Priorita"    , DB2_PARAM_IN);
            db2_bind_param($stmt,  5, "Nome"        , DB2_PARAM_IN);
            db2_bind_param($stmt,  6, "Tipo"        , DB2_PARAM_IN);
            db2_bind_param($stmt,  7, "Dest"        , DB2_PARAM_IN);
            db2_bind_param($stmt,  8, "Desc"        , DB2_PARAM_IN);
            db2_bind_param($stmt,  9, "Vali"         , DB2_PARAM_IN);
			db2_bind_param($stmt, 10, "Opt"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 11, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 12, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 13, "Note"        , DB2_PARAM_OUT);
            break;
       case 'ME':
            $CallPlSql='CALL WFS.K_CONFIG.ModificaElaborazione(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $stmt = db2_prepare($conn, $CallPlSql);
            
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
            
            db2_bind_param($stmt, 1, "IdWorkFlow"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdFlu"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "IdDip"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Priorita"          , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Nome"              , DB2_PARAM_IN);
            db2_bind_param($stmt, 6, "Tags"              , DB2_PARAM_IN);
            db2_bind_param($stmt, 7, "Desc"              , DB2_PARAM_IN);
            db2_bind_param($stmt, 8, "ReadOnly"          , DB2_PARAM_IN);
            db2_bind_param($stmt, 9, "IdElaborazione"    , DB2_PARAM_IN);
            db2_bind_param($stmt,10, "Vali"              , DB2_PARAM_IN);
            db2_bind_param($stmt,11, "SaltaElab"         , DB2_PARAM_IN);
            db2_bind_param($stmt,12, "ShowProc"          , DB2_PARAM_IN);
            db2_bind_param($stmt,13, "User"              , DB2_PARAM_IN);
            db2_bind_param($stmt,14, "Errore"            , DB2_PARAM_OUT);
            db2_bind_param($stmt,15, "Note"              , DB2_PARAM_OUT);
            break;
       case 'MV':
            $CallPlSql='CALL WFS.K_CONFIG.ModificaValidazione(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $stmt = db2_prepare($conn, $CallPlSql);

            $IdDip=$_POST['IdDip'];
            $IdFlu=$_POST['IdFlu'];
            $Priorita=$_POST['Priorita'];
            $Nome=$_POST['NomeVal'];
            $Desc=$_POST['DescrVal'];
            $External=$_POST['External'];
                        
            db2_bind_param($stmt, 1, "IdWorkFlow"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdFlu"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "IdDip"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Priorita"         , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Nome"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 6, "Desc"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 7, "External"         , DB2_PARAM_IN);
            db2_bind_param($stmt, 8, "Vali"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 9, "User"             , DB2_PARAM_IN);
            db2_bind_param($stmt,10, "Errore"           , DB2_PARAM_OUT);
            db2_bind_param($stmt,11, "Note"             , DB2_PARAM_OUT);
            break;  
        case 'RAF': 
            $CallPlSql='CALL WFS.K_CONFIG.RimuoviFlusso(?, ?, ?, ?, ?)';
            $stmt = db2_prepare($conn, $CallPlSql);
                
            $IdFlu=$_POST['IdFlu']; 
                
            db2_bind_param($stmt, 1, "IdWorkFlow"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdFlu"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "User"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Errore"           , DB2_PARAM_OUT);
            db2_bind_param($stmt, 5, "Note"             , DB2_PARAM_OUT);
            break;      
        case 'RF': 
            $CallPlSql='CALL WFS.K_CONFIG.RimuoviDipFlusso(?, ?, ?, ?, ?)';
            $stmt = db2_prepare($conn, $CallPlSql);
                
            $IdLeg=$_POST['IdLeg']; 
                                        
            db2_bind_param($stmt, 1, "IdWorkFlow"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdLeg"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "User"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Errore"           , DB2_PARAM_OUT);
            db2_bind_param($stmt, 5, "Note"             , DB2_PARAM_OUT);
            break;
       case 'RC': 
            $CallPlSql='CALL WFS.K_CONFIG.RimuoviCaricamento(?, ?, ?, ?, ?, ?)';
            $stmt = db2_prepare($conn, $CallPlSql);
            
            $IdCar=$_POST['IdDip'];
            $IdLeg=$_POST['IdLeg'];
            
            db2_bind_param($stmt, 1, "IdWorkFlow"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdCar"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "IdLeg"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "User"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Errore"           , DB2_PARAM_OUT);
            db2_bind_param($stmt, 6, "Note"             , DB2_PARAM_OUT);
            break;
       case 'RL':
            $CallPlSql='CALL WFS.K_CONFIG.RimuoviLink(?, ?, ?, ?, ?, ? )';
            $stmt = db2_prepare($conn, $CallPlSql);
            
            $IdLink=$_POST['IdDip'];
            $IdLeg=$_POST['IdLeg'];
            
            db2_bind_param($stmt, 1, "IdWorkFlow"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdLink"           , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "IdLeg"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "User"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Errore"           , DB2_PARAM_OUT);
            db2_bind_param($stmt, 6, "Note"             , DB2_PARAM_OUT);
            break;
       case 'RE':
            $CallPlSql='CALL WFS.K_CONFIG.RimuoviElaborazione(?, ?, ?, ?, ?, ? )';
            $stmt = db2_prepare($conn, $CallPlSql);
            
            $IdElab=$_POST['IdDip'];
            $IdLeg=$_POST['IdLeg'];
            
            db2_bind_param($stmt, 1, "IdWorkFlow"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdElab"           , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "IdLeg"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "User"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Errore"           , DB2_PARAM_OUT);
            db2_bind_param($stmt, 6, "Note"             , DB2_PARAM_OUT);
            break;
       case 'RV':
            $CallPlSql='CALL WFS.K_CONFIG.RimuoviValidazione(?, ?, ?, ?, ?, ? )';
            $stmt = db2_prepare($conn, $CallPlSql);

            $IdVal=$_POST['IdDip']; 
            $IdLeg=$_POST['IdLeg'];
            
            db2_bind_param($stmt, 1, "IdWorkFlow"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdVal"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "IdLeg"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "User"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Errore"           , DB2_PARAM_OUT);
            db2_bind_param($stmt, 6, "Note"             , DB2_PARAM_OUT);
            break;          
    }   
    if( "$Azione" != "" ){
        $res=db2_execute($stmt);
        
        if ( ! $res) {
          echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg();
        }
        
        if ( $Errore != 0 ) {
          echo "PLSQL Procedure Calling Error $Errore: ".$Note;
        }           
    }
    ?>
    <div id="ScrollDiv" >
        <table>
        <tr>
        <td><div onclick="$('body').scrollTop(0);" id="ScrollTop" class="button" >SCROLL TOP</div>      
        <td><div onclick="$('body').scrollTop($('body').height());" id="ScrollEnd" class="button" >SCROLL END</div> 
        </tr>
        </table></div>
    <FORM id="FormMain" method="POST" >
	<center>
	 <table style="width: 300px;margin-bottom:0px;">
	 <tr>
	 <td><div class="button centrale CreaRilascio" onclick="CreaRilascio()"><img class="ImgIco" src="../images/Rilascio.png" >Crea Rilascio</div></td>
	 <td><input type="submit" class="button centrale CreaRilascio" value="REFRESH" ></td>
	 </tr>
	 </table>
    </center>
	<input type="hidden" id="TopScroll" name="TopScroll" value="<?php echo $TopScroll; ?>" />
    <input type="hidden" id="BodyHeight" name="BodyHeight" value="<?php echo $BodyHeight; ?>" />
    <input type="hidden" Name="IdWorkFlow" id="IdWorkFlow" value="<?php echo $IdWorkFlow; ?>" >
    <input type="hidden" id="IdTeam" name="IdTeam" value="<?php echo $IdTeam; ?>"  class="FieldMand" >
    <input type="hidden" Name="Azione" id="Azione" value="" >
    <table id="TabFlussi" >
    <tr>
      <th colspan=2 >
        <div id="BackAmb" class="button" ><img class="ImgIco" src="../images/Back.png" >BACK</div>
        <script>
            $("#BackAmb").click(function(){
                $('#FormMain').prop('action','../PAGE/PgWfsGest.php').submit();
            });
        </script>         
        <div class="Ambiente">
            <div style="color: brown;font-size:15px;" ><?php echo "$IdWorkFlow - $NomeWfs"; if ( "$DescrWfs" != "" ){ echo " ( $DescrWfs )"; } ?></div>
       </div>
      </th>
    </tr>
    <tr><th colspan=2 class="AggFlusso" >
    <?php if ( "$TServer" != "PROD USER" ){ ?>
    <div class="button centrale AggiungiFlusso" onclick="ShowAggiungiFlusso()" ><img class="ImgIco" src="../images/Aggiungi.png" >Aggiungi Flusso</div>
    <?php } ?>
    </th></tr>
    <?php   
    $sql="SELECT ID_FLU, FLUSSO, BLOCK_CONS,
     ( SELECT count(*) FROM WFS.LEGAME_FLUSSI WHERE ID_FLU = F.ID_FLU ) UTILIZZATO,
     ( SELECT count(*) FROM WFS.LEGAME_FLUSSI WHERE ID_FLU = F.ID_FLU AND ID_WORKFLOW = $IdWorkFlow ) USATO
    FROM 
        WFS.FLUSSI F
    WHERE 1=1
       AND ID_WORKFLOW = $IdWorkFlow
    ORDER BY FLUSSO";

    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    while ($row = db2_fetch_assoc($stmt)) {
        $IdFlusso=$row['ID_FLU'];
        $Flusso=$row['FLUSSO'];
        $BlockCons=$row['BLOCK_CONS'];
        $Utilizzato=$row['UTILIZZATO'];
        $Usato=$row['USATO'];
        
     
        ?>
        <tr class="borderbot" >
          <th>
             <div>
             <?php if ( "$TServer" != "PROD USER" ){ 
             ?><div class="Plst" style="float: left;" onclick="ModFlu(<?php echo $IdFlusso; ?>,'FL')" ><img class="ImgIco" src="../images/Matita.png" title="<?php echo $IdFlusso; ?>" ></div><?php
             } 
             if (  "$Utilizzato" == "0" and "$TServer" != "PROD USER" ) {
                 ?><div class="Plst" style="float: left;"  id="Del<?php echo $IdFlusso; ?>" onclick="RemoveFlu(<?php echo $IdFlusso; ?>)"><img class="ImgIco" src="../images/Cestino.png" ></div><?php
             }
             if (  "$BlockCons" == "Y" ) {
                 ?><div  style="float: left;" title="Blocca a Periodo Consolidato" ><img class="ImgIco" src="../images/Lock.png" /></div><?php
             }
             echo $Flusso;
             ?>
             </div>
          </th>
          <td>
            <div id="Load<?php echo $IdFlusso; ?>"></div>
            <?php
            if (  "$Usato" != "0" ) {
               ?>
               <script>
                      $("#Load<?php echo $IdFlusso; ?>").load('../PHP/Wfs_OpenFlusso.php', {
                                   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
                                   IdFlu: '<?php echo $IdFlusso; ?>'   
                      });
               </script>            
               <?php 
            } else {
             if ( "$TServer" != "PROD USER" ){ ?>
               <div id="AggDipIN<?php echo $IdFlusso; ?>" class="button" style="width:200px;" >
                 <img class="ImgIco" src="../images/Aggiungi.png" >Aggiungi Dipendenza
               </div>
               <script>
                  $("#AggDipIN<?php echo $IdFlusso; ?>").click(function(){
                      $("#InsDip").load('../PHP/Wfs_AggDip.php', {
                        IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
                        IdFlu: '<?php echo $IdFlusso; ?>'
                      }).show();
                  });
               </script>
               <?php }
            } ?>
          </td>
        </tr><?php  
        
    }
    ?>
    <tr><th colspan=2 class="AggFlusso" >
    <?php if ( "$TServer" != "PROD USER" ){ ?>
      <div class="button centrale AggiungiFlusso"  onclick="ShowAggiungiFlusso()" ><img class="ImgIco" src="../images/Aggiungi.png" >Aggiungi Flusso</div>
    <?php } ?>
    </th></tr>
    </table>
    <?php if ( "$TServer" != "PROD USER" ){ ?>
    <div id="AggFlusso" hidden >
        <B>Aggiungi nuovo FLUSSO</B>
        <BR>
        <label>Nome Flusso</label>
        <input type="text"  id="NomeFlusso" Name="NomeFlusso" />
        <BR>
        <label>Descr. Flusso</label>
        <input type="text"  id="DescFlusso" Name="DescFlusso" />
        <BR>
        <label>Comportamento con periodo Consolidato</label><BR>
        <select id="BlockConsFlu" Name="BlockCons"   >
          <option value="N" > Non far Nulla </option>
          <option value="Y" > Blocca con periodo Consolidato </option>
          <option value="S" > Abilita con periodo Consolidato </option>
        </select>       
        <table>
            <tr>
                <td><div class="button" id="AggiungiF" >Aggiungi</div></td>
                <td><div class="button" id="ChiudiF" >Close</div></td>
            </tr>
        </table>
    </div>
    <?php } ?>
    <div id="InsDip"></div>
    </FORM>
    <?php
    db2_close($db2_conn_string);  
    ?>
    <script>
        function ShowAggiungiFlusso(){
              $('#AggFlusso').show();
        }
        
        function RemoveFlu(vIdFlu){
                $('#TopScroll').val($( window ).scrollTop());
                $('#BodyHeight').val($('body').height());
                var re = confirm('Do you want remove the Flow?');
                if ( re == true) {  
                   $('#Azione').val('RAF');

                  var input = $("<input>")
                  .attr("type", "hidden")
                  .attr("name", "IdFlu")
                  .val(vIdFlu);
                  $('#FormMain').append($(input));
                   
                   $("#Waiting").show();
                   $('#FormMain').submit(); 
                }
        }                       

        
        function CreaRilascio(){
                var re = confirm('Do you want create the release?');
                if ( re == true) {  
                   $('#Azione').val('RIL');
                   $("#Waiting").show();
                   $('#FormMain').submit(); 
                }
        }                       
        
        
        function ModDip(vIdFlu,vIdDip,vTipo,vTLink){
            $('#TopScroll').val($( window ).scrollTop());
            $('#BodyHeight').val($('body').height());
            $('#InsDip').empty().load('../PHP/Wfs_ModDip.php', {
                   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
                   IdFlu: vIdFlu,
                   Tipo: vTipo,
                   IdDip: vIdDip,
                   TLink: vTLink
            }).show();
        }
        
        
        $('#NomeFlusso').keyup(function(){
           $(this).val($(this).val().replace(/ /g,"_"));
           $(this).val($(this).val().toUpperCase());
        });
        
        $('#AggiungiF').click(function(){
            if ( $("#NomeFlusso").val() == '' ){
              alert('There are empty input');
            } else {
              $('#Azione').val('AF');
              $("#FormMain").submit();
            }
        });
        
        $('#ChiudiF').click(function(){
                $("#AggFlusso").hide();
                $("#NomeFlusso").val('');               
        });     
        
        function ModFlu(vIdDip,vTipo){
            $('#TopScroll').val($( window ).scrollTop());
            $('#BodyHeight').val($('body').height());
            $('#InsDip').empty().load('../PHP/Wfs_ModDip.php', {
                   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
                   IdFlu: '<?php echo $IdFlu; ?>',
                   Tipo: vTipo,
                   IdDip: vIdDip
            }).show();
        }
        
        $(window).scrollTop($('#TopScroll').val());
    
   </script>  
    <?php
}
?>