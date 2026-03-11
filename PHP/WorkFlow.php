<style>
  .ingrandFlu{
     box-shadow: 0px 0px 7px 1px black !important;
     -o-box-shadow: 0px 0px 7px 1px black !important;
     -moz-box-shadow: 0px 0px 7px 1px black !important;
     transform: scale(1.3);
     -webkit-transform: scale(1.3);
     -ms-transform: scale(1.3);
     z-index: 9990;   
     border: 1px solid black;
  }
  #PeriodCons{
      color:red;
      border: 1px solid red;
      margin: 2px;
      padding: 2px;
  }
  .LockIco{
    position:fixed;
    height: 35px;
    top: 112px;
    left: 70%;
  }
</style>
<?php
include '../GESTIONE/sicurezza.php';

if ( "$find" == "1" )  {

    $ResettaPer=$_POST['ResettaPer'];
    if ( "$ResettaPer" == "Resetta" ){
        unset($_SESSION['IdPeriod']);
    }
    $Resetta=$_POST['Resetta'];
    if ( "$Resetta" == "Resetta" ){
        unset($_SESSION['IdPeriod']);
        unset($_SESSION['IdWorkFlow']);
        unset($_SESSION['IdProcess']);
        unset($_POST['IdWorkFlow']);
        unset($_POST['IdProcess']);     
        unset($_POST['SelFlusso']);
        unset($_POST['SelNomeFlusso']);
        unset($_POST['SelDipendenza']);
        unset($_POST['SelNomeDipendenza']);
        unset($_POST['SelTipo']);
        unset($_POST['LinkIdLegame']);  
        unset($_POST['LinkPagina']);
        unset($_POST['LinkNameDip']);
        unset($_POST['Action']);
        unset($_POST['OnIdLegame']);
    
    }

    $IdPeriod=$_POST['IdPeriod'];
    $IdWorkFlow=$_REQUEST['IdWorkFlow'];
    $IdProcess=$_POST['IdProcess'];
    $TopScroll=$_POST['TopScroll'];
    $TopScrollDett=$_POST['TopScrollDett'];
    $Regime=$_POST['Regime'];
    
    if ( "$IdWorkFlow" == "" and isset($_SESSION['IdWorkFlow']) ){
        $IdWorkFlow=$_SESSION['IdWorkFlow'];
    } else {
        $_SESSION['IdWorkFlow']=$IdWorkFlow;
    }
    
    if ( "$IdPeriod" == "" and isset($_SESSION['IdPeriod']) ){
        $IdPeriod=$_SESSION['IdPeriod'];
    } else {
        $_SESSION['IdPeriod']=$IdPeriod;
    }
    
    
    if ( "$IdProcess" == "" and isset($_SESSION['IdProcess']) ){
        $IdProcess=$_SESSION['IdProcess'];
    } else {
        $_SESSION['IdProcess']=$IdProcess;
    }
    
   $IdUser=$_SESSION['CodUk'];

   $SelFlusso=$_POST['SelFlusso'];
   $SelNomeFlusso=$_POST['SelNomeFlusso'];
   $SelDipendenza=$_POST['SelDipendenza'];
   $SelNomeDipendenza=$_POST['SelNomeDipendenza'];
   $SelTipo=$_POST['SelTipo'];


//===============================================================================================
//==========   Variabili per Link

   $LinkIdLegame=$_POST['LinkIdLegame'];  
   $LinkPagina=$_POST['LinkPagina'];
   $LinkNameDip=$_POST['LinkNameDip'];

//===============================================================================================
//==========   PLSQL Call Services  

$CambiaStato=$_POST['CambiaStato'];
if ( "$CambiaStato" == "CambiaStato" ) {
   if ( "$IdProcess" != "" ) {
     $SelStatoWfs=$_POST['SelStatoWfs'];
     $SqlTest="SELECT FLAG_STATO FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess";
           $stmt=db2_prepare($conn, $SqlTest);
           $res=db2_execute($stmt);
           if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
           while ($row = db2_fetch_assoc($stmt)) {
              $WfsStato=$row['FLAG_STATO'];
     } 
     if ( "$SelStatoWfs" != "$WfsStato" and "$SelStatoWfs" != "" ) {
	     switch ($SelStatoWfs){
          case 'A': $SetRo="N";  break;   
          case 'C': $SetRo="Y";  break;
          case 'S': $SetRo="Y";  break;
         }
         $Sql="UPDATE WORK_CORE.ID_PROCESS SET FLAG_STATO = '$SelStatoWfs', READONLY = '$SetRo' WHERE ID_PROCESS = ${IdProcess}";
         $stmt = db2_prepare($conn, $Sql);
         $res=db2_execute($stmt);
         
         if ( ! $res) {
           echo "Set Status IdProcess Error ".db2_stmt_errormsg();
         }else{
             $WfsStato=$SelStatoWfs;
         }
     }
   }
}
 
$ChSens=$_POST['ChSens'];
if ( "$ChSens" == "ChSens" ) {
   if ( "$IdProcess" != "" ) {
     $SelChSens=$_POST['SelChSens'];
     
         $Sql="UPDATE WORK_CORE.ID_PROCESS SET TIPO = '$SelChSens' WHERE ID_PROCESS = ${IdProcess}";
         $stmt = db2_prepare($conn, $Sql);
         $res=db2_execute($stmt);
         
         if ( ! $res) {
           echo "Set Tipo IdProcess Error ".db2_stmt_errormsg();
         }else{
             $WfsStato=$SelStatoWfs;
         }

   }
}  
   
   
   
   $ForzaScodatore=$_POST['ForzaScodatore'];
   if ( "$ForzaScodatore" == "1" ) {
         shell_exec("sh $PRGDIR/AvviaElabServer.sh '${SSHUSR}' '${SERVER}' '${DIRSH}' ${IdWorkFlow} ${IdProcess} 2>&1 > $PRGDIR/AvviaElabServer.log &");
   }


   $NewDesc=$_POST['NewDesc'];
   if ( "$NewDesc" == "NewDesc" ) {
           $DescrIdP=$_POST['DescrIdP'];
           $Sql="UPDATE WORK_CORE.ID_PROCESS SET DESCR='${DescrIdP}' WHERE ID_PROCESS = ${IdProcess}";
           $stmt = db2_prepare($conn, $Sql);
           $res=db2_execute($stmt);
           
           if ( ! $res) {
             echo "Descr Update Error ".db2_stmt_errormsg();
           }
           $Sql="UPDATE WORK_RULES.CATALOGO_ID_PROCESS SET DESCR='${DescrIdP}' WHERE ID_PROCESS = ${IdProcess}";
           $stmt = db2_prepare($conn, $Sql);
           $res=db2_execute($stmt);
           
           if ( ! $res) {
             echo "Descr Update Catalogo Error ".db2_stmt_errormsg();
           }       
   }



    $Action=$_POST['Action'];
    $OnIdLegame=$_POST['OnIdLegame'];
   
    $Errore=0;
    $Note="";
    
    switch($Action){    
      case 'CancellaCoda': 
      
           $IdFlu=$_POST['CodaIdFlu'];
           $Tipo=$_POST['CodaTipo'];
           $IdDip=$_POST['CodaIdDip'];
                   
           $CallPlSql='CALL WFS.K_WFS.RimuoviDaCoda(?, ?, ?, ?, ?, ?, ?, ? )';
           $stmt = db2_prepare($conn, $CallPlSql);
           db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
           db2_bind_param($stmt, 2, "IdProcess"   , DB2_PARAM_IN);
           db2_bind_param($stmt, 3, "IdFlu"       , DB2_PARAM_IN);
           db2_bind_param($stmt, 4, "Tipo"        , DB2_PARAM_IN);
           db2_bind_param($stmt, 5, "IdDip"       , DB2_PARAM_IN);
           db2_bind_param($stmt, 6, "User"        , DB2_PARAM_IN);
           db2_bind_param($stmt, 7, "Errore"      , DB2_PARAM_OUT);
           db2_bind_param($stmt, 8, "Note"        , DB2_PARAM_OUT);
           $res=db2_execute($stmt);
           
           if ( ! $res) {
             echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg();
           }
           
           if ( $Errore != 0 ) {
             echo "PLSQL Procedure Calling Error $Errore: ".$Note;
           }           
           break;           
      case 'Valida': 
           $CallPlSql='CALL WFS.K_WFS.ValidaLegame(?, ?, ?, ?, ?, ? )';
           $stmt = db2_prepare($conn, $CallPlSql);
           db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
           db2_bind_param($stmt, 2, "IdProcess"   , DB2_PARAM_IN);
           db2_bind_param($stmt, 3, "OnIdLegame"  , DB2_PARAM_IN);
           db2_bind_param($stmt, 4, "User"        , DB2_PARAM_IN);
           db2_bind_param($stmt, 5, "Errore"      , DB2_PARAM_OUT);
           db2_bind_param($stmt, 6, "Note"        , DB2_PARAM_OUT);
           $res=db2_execute($stmt);
           
           if ( ! $res) {
             echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg();
           }
           
           if ( $Errore != 0 ) {
             echo "PLSQL Procedure Calling Error $Errore: ".$Note;
           }  

           break;
      case 'Svalida': 
           $IdFlu=$_POST['CodaIdFlu'];
           $Tipo=$_POST['CodaTipo'];
           $IdDip=$_POST['CodaIdDip'];
		   
           $CallPlSql='CALL WFS.K_WFS.SvalidaLegame(?, ?, ?, ?, ?, ? )';
           $stmt = db2_prepare($conn, $CallPlSql);
           db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
           db2_bind_param($stmt, 2, "IdProcess"   , DB2_PARAM_IN);
           db2_bind_param($stmt, 3, "OnIdLegame"  , DB2_PARAM_IN);
           db2_bind_param($stmt, 4, "User"        , DB2_PARAM_IN);
           db2_bind_param($stmt, 5, "Errore"      , DB2_PARAM_OUT);
           db2_bind_param($stmt, 6, "Note"        , DB2_PARAM_OUT);
           $res=db2_execute($stmt);
           
           if ( ! $res) {
             echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg();
           }
           
           if ( $Errore != 0 ) {
             echo "PLSQL Procedure Calling Error $Errore: ".$Note;
           }           
           break;
      case 'Spegni': 
	  
               $Sql="UPDATE WFS.ULTIMO_STATO SET 
				  WARNING  = 0
				 ,UTENTE = '$User'
				 WHERE 1=1
				 AND ID_WORKFLOW = $IdWorkFlow
				 AND ID_PROCESS  = $IdProcess
				 AND ID_FLU      = $SelFlusso
				 AND TIPO        = '$SelTipo'
				 AND ID_DIP      = $SelDipendenza   
				 ";
			  $stmt = db2_prepare($conn, $Sql);

			  $result=db2_execute($stmt);
			  if ( ! $result ){
				echo "ERROR DB2 Save New:".db2_stmt_errormsg();
			  }      
					
			  $Sql="INSERT INTO WFS.CODA_STORICO(
				ID_PROCESS
				,ID_WORKFLOW
				,ID_FLU
				,TIPO
				,ID_DIP
				,AZIONE
				,UTENTE
				,NOTE
				,ESITO
				,INIZIO
				,FINE
				,LOG
				,FILE
				)
				VALUES (
				 $IdProcess
				,$IdWorkFlow
				,$SelFlusso
				,'$SelTipo'
				,$SelDipendenza
				,'R'
				,'$User'
				,'Reset Warning'
				,'N'
				,CURRENT_TIMESTAMP
				,CURRENT_TIMESTAMP
				,null
				,null
				)   
				";
			  $stmt = db2_prepare($conn, $Sql);

			  $result=db2_execute($stmt);
			  if ( ! $result ){
				echo "ERROR DB2 Save New:".db2_stmt_errormsg();
			  }  
				  
           break;
      case 'Elabora': 
            $SqlTest="SELECT ID_SH FROM WFS.ELABORAZIONI WHERE ID_ELA = (SELECT ID_DIP FROM WFS.LEGAME_FLUSSI WHERE ID_LEGAME = $OnIdLegame )";
            $stmt=db2_prepare($conn, $SqlTest);
            $res=db2_execute($stmt);
            if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
            while ($row = db2_fetch_assoc($stmt)) {
               $IdDip=$row['ID_SH'];
            }
           
            if ( "$IdDip" != "0" ){
               
               $CallPlSql='CALL WFS.K_WFS.ElaboraLegame(?, ?, ?, ?, ? , ?, ?)';
               $stmt = db2_prepare($conn, $CallPlSql);

               $Force=0;
               $SelForce=$_POST['Force'];
               if ( "$SelForce" == "1" ){ $Force=1; }
               
               db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
               db2_bind_param($stmt, 2, "IdProcess"   , DB2_PARAM_IN);
               db2_bind_param($stmt, 3, "OnIdLegame"  , DB2_PARAM_IN);
               db2_bind_param($stmt, 4, "Force"       , DB2_PARAM_IN);
               db2_bind_param($stmt, 5, "User"        , DB2_PARAM_IN);
               db2_bind_param($stmt, 6, "Errore"      , DB2_PARAM_OUT);
               db2_bind_param($stmt, 7, "Note"        , DB2_PARAM_OUT);
               $res=db2_execute($stmt);
               
               $Lancia=true;
               
               if ( ! $res) {
                 echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg();
                 $Lancia=false;
               }
               
               if ( $Errore != 0 ) {
                 echo "PLSQL Procedure Calling Error $Errore: ".$Note;
                 $Lancia=false;
               }         

               if ( $Lancia ) {
                 shell_exec("sh $PRGDIR/AvviaElabServer.sh '${SSHUSR}' '${SERVER}' '${DIRSH}' ${IdWorkFlow} ${IdProcess} 2>&1 > $PRGDIR/AvviaElabServer.log &");             
               }
            } else {
 
               $CallPlSql='CALL WFS.K_WFS.ValidaLegame(?, ?, ?, ?, ? , ?)';
               $stmt = db2_prepare($conn, $CallPlSql);
               db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
               db2_bind_param($stmt, 2, "IdProcess"   , DB2_PARAM_IN);
               db2_bind_param($stmt, 3, "OnIdLegame"  , DB2_PARAM_IN);
               db2_bind_param($stmt, 4, "User"        , DB2_PARAM_IN);
               db2_bind_param($stmt, 5, "Errore"      , DB2_PARAM_OUT);
               db2_bind_param($stmt, 6, "Note"        , DB2_PARAM_OUT);
               $res=db2_execute($stmt);
                           
               if ( ! $res) {
                 echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg();
               }
            }               
           break;
           
      case 'CopiaDato': 
           $Copia='Y'; 
               
           $CallPlSql='CALL WFS.K_WFS.ConfermaDato(?, ?, ?, ?, ?, ?, ? )';
           $stmt = db2_prepare($conn, $CallPlSql);  
           
           db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
           db2_bind_param($stmt, 2, "IdProcess"   , DB2_PARAM_IN);
           db2_bind_param($stmt, 3, "OnIdLegame"  , DB2_PARAM_IN);
           db2_bind_param($stmt, 4, "Copia"       , DB2_PARAM_IN);
           db2_bind_param($stmt, 5, "User"        , DB2_PARAM_IN);
           db2_bind_param($stmt, 6, "Errore"      , DB2_PARAM_OUT);
           db2_bind_param($stmt, 7, "Note"        , DB2_PARAM_OUT);
           $res=db2_execute($stmt);
           
           if ( ! $res) {
             echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg();
             $Lancia=false;
           }
           
           if ( $Errore != 0 ) {
             echo "PLSQL Procedure Calling Error $Errore: ".$Note;
             $Lancia=false;
           }
           
           break; 
       case 'ConfermaDato':  
           $Copia='N';
               
           $CallPlSql='CALL WFS.K_WFS.ConfermaDato(?, ?, ?, ?, ?, ?, ? )';
           $stmt = db2_prepare($conn, $CallPlSql); 
           db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
           db2_bind_param($stmt, 2, "IdProcess"   , DB2_PARAM_IN);
           db2_bind_param($stmt, 3, "OnIdLegame"  , DB2_PARAM_IN);
           db2_bind_param($stmt, 4, "Copia"       , DB2_PARAM_IN);
           db2_bind_param($stmt, 5, "User"        , DB2_PARAM_IN);
           db2_bind_param($stmt, 6, "Errore"      , DB2_PARAM_OUT);
           db2_bind_param($stmt, 7, "Note"        , DB2_PARAM_OUT);
           $res=db2_execute($stmt);
           
           if ( ! $res) {
             echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg();
             $Lancia=false;
           }
           
           if ( $Errore != 0 ) {
             echo "PLSQL Procedure Calling Error $Errore: ".$Note;
             $Lancia=false;
           }
           
           break;           
      case 'Carica': 
            $SqlTest="SELECT NOME_INPUT FROM WFS.CARICAMENTI WHERE ID_CAR = (SELECT ID_DIP FROM WFS.LEGAME_FLUSSI WHERE ID_LEGAME = $OnIdLegame )";
            $stmt=db2_prepare($conn, $SqlTest);
            $res=db2_execute($stmt);
            if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
            while ($row = db2_fetch_assoc($stmt)) {
               $NomeInput=$row['NOME_INPUT'];
            }
           
            if ( "$NomeInput" != "WFS_TEST" ){               
                 if( isset($_FILES['UploadFileName_'.$OnIdLegame]) ){
                
                  $file_name = str_replace('(','',$_FILES['UploadFileName_'.$OnIdLegame]['name']);
                  $file_name = str_replace(')','',$file_name);
                  $file_name = str_replace(' ','_',$file_name);
                  $file_size =$_FILES['UploadFileName_'.$OnIdLegame]['size'];
                  $file_tmp  =$_FILES['UploadFileName_'.$OnIdLegame]['tmp_name'];
                  $file_type =$_FILES['UploadFileName_'.$OnIdLegame]['type'];
                  $file_ext  =strtolower(end(explode('.',$_FILES['UploadFileName_'.$OnIdLegame]['name'])));
                            
                  $expensions= array("xls","xlsx","csv");
                  
                  $ErroreUp=0;
                  
                  if(in_array($file_ext,$expensions)=== false){
                     $Note="Il File non e' conforme al formato richiesto: xls,xlsx,csv";
                     echo $Note;
                     $ErroreUp=1;
                  }
                  
                  if($file_size > 62428800){
                     $Note='Il File eccede dai 60 MB massimi di caricamento';
                     echo $Note;
                     $ErroreUp=1;
                  }
                  
                  if( $ErroreUp == 0 ){
                       $FileRename="$UPLOADDIR/".$IdProcess."_".$NomeInput.".".$file_ext;
                       
                       $moved = move_uploaded_file($file_tmp,$FileRename);
                        
                        if( $moved ) {
                            $Prosegui=true;                                
                            if ( "$MetSCopy" == "scp" ) {
                             $command='scp '.$FileRename.' '.${SSHUSR}.'@'.${SERVER}.':'.${UPFILEDIR}.'/ ';
                            } else {
                             $command='echo';
                            }
                            $out = shell_exec($command);                      
                            if ( empty($out) ){
                               $Prosegui=true;
                            } else {
                               $Prosegui=false;
                            }                    
                            if ( $Prosegui ) {                                          
                              
                               $CallPlSql='CALL WFS.K_WFS.CaricaLegame(?, ?, ?, ?, ?, ?, ?, ? )';
                               $stmt = db2_prepare($conn, $CallPlSql);  
                               
                               $Force=0;
                               $SelForce=$_POST['Force'];
                               if ( "$SelForce" == "1" ){ $Force=1; }
                        
                               db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
                               db2_bind_param($stmt, 2, "IdProcess"   , DB2_PARAM_IN);
                               db2_bind_param($stmt, 3, "OnIdLegame"  , DB2_PARAM_IN);
                               db2_bind_param($stmt, 4, "file_name"   , DB2_PARAM_IN);
                               db2_bind_param($stmt, 5, "Force"       , DB2_PARAM_IN);
                               db2_bind_param($stmt, 6, "User"        , DB2_PARAM_IN);
                               db2_bind_param($stmt, 7, "Errore"      , DB2_PARAM_OUT);
                               db2_bind_param($stmt, 8, "Note"        , DB2_PARAM_OUT);
                               $res=db2_execute($stmt);

                               $Lancia=true;
                               
                               if ( ! $res) {
                                 echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg();
                                 $Lancia=false;
                               }
                               
                               if ( $Errore != 0 ) {
                                 echo "PLSQL Procedure Calling Error $Errore: ".$Note;
                                 $Lancia=false;
                               }
                              
                              if ( $Lancia ) {
                                shell_exec("sh $PRGDIR/AvviaElabServer.sh '${SSHUSR}' '${SERVER}' '${DIRSH}' ${IdWorkFlow} ${IdProcess} 2>&1 >> $PRGDIR/AvviaElabServer.log &");
                              }
                               
                            } else {
                              $Note="Error upload file to Aix";
                              echo $Note;
                            }
                        } else {
                          $Note="Error upload file to WebServer";
                          echo $Note;
                        }   
                           
         
                  }else{
                     $Note="Errore nel caricamento del File:";
                     echo $Note;
                  }
               }else{
                 $Note="Errore Caricamento File Web:";
                 echo $Note;
               }
            } else {
 
               $CallPlSql='CALL WFS.K_WFS.ValidaLegame(?, ?, ?, ?, ? , ?)';
               $stmt = db2_prepare($conn, $CallPlSql);
               db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
               db2_bind_param($stmt, 2, "IdProcess"   , DB2_PARAM_IN);
               db2_bind_param($stmt, 3, "OnIdLegame"  , DB2_PARAM_IN);
               db2_bind_param($stmt, 4, "User"        , DB2_PARAM_IN);
               db2_bind_param($stmt, 5, "Errore"      , DB2_PARAM_OUT);
               db2_bind_param($stmt, 6, "Note"        , DB2_PARAM_OUT);
               $res=db2_execute($stmt);
                           
               if ( ! $res) {
                 echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg();
               }
            }              
       break;          
    }
    
    if ( "$Action" != "" and "$IdProcess" != "" and "$IdWorkFlow" != "" ){
           $CallPlSql='CALL WFS.K_WFS.ElaborazioniPossibili(?, ?, ?, ?, ? )';
           $stmt = db2_prepare($conn, $CallPlSql);
           db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
           db2_bind_param($stmt, 2, "IdProcess"   , DB2_PARAM_IN);
           db2_bind_param($stmt, 3, "User"        , DB2_PARAM_IN);
           db2_bind_param($stmt, 4, "Errore"      , DB2_PARAM_OUT);
           db2_bind_param($stmt, 5, "Note"        , DB2_PARAM_OUT);
           $res=db2_execute($stmt);
           
           $ErrSt=0;
           
           if ( ! $res) {
             echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg();
             $ErrSt=1;
           }
           
           if ( $Errore != 0 ) {
             echo "PLSQL Procedure Calling Error $Errore: ".$Note;
             $ErrSt=1;
           }  
           
           if (( $ErrSt == 0 and "$Action" != "Carica" and "$Action" != "Elabora" ) or ( $ErrSt == 0 and "$Action" == "CancellaCoda" )) {
              shell_exec("sh $PRGDIR/AvviaElabServer.sh '${SSHUSR}' '${SERVER}' '${DIRSH}' ${IdWorkFlow} ${IdProcess} 2>&1 >> $PRGDIR/AvviaElabServer.log &");
           }
    }
    
    

//===============================================================================================
//==========   Create Arrs ListGruppi


   $ArrListGruppi=array();
   $ListIdGroups="0";
   $SqlListGroup="SELECT ID_GRUPPO FROM WFS.ASS_GRUPPO WHERE ID_UK = $IdUser ";
   $stmt=db2_prepare($conn, $SqlListGroup);
   $res=db2_execute($stmt);
   if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); } 
   while ($row = db2_fetch_assoc($stmt)) {
       $IdGruppo=$row['ID_GRUPPO']; 
       array_push($ArrListGroup,$IdGruppo);
       $ListIdGroups=$ListIdGroups.",".$IdGruppo;
   }


//=================================================================================================

//===============================================================================================
//==========   Create Arrs Data
    //WORKFLOW
    $ArrWfs=array();
    $SqlList="SELECT DISTINCT W.ID_WORKFLOW,WORKFLOW,W.DESCR,W.READONLY,W.FREQUENZA,W.MULTI
    FROM 
    WFS.WORKFLOW  W,
    WFS.AUTORIZZAZIONI A
    WHERE 1=1
    AND A.ID_WORKFLOW = W.ID_WORKFLOW
    AND A.ID_GRUPPO IN ( $ListIdGroups ) 
    AND W.ABILITATO = 'Y'
    ORDER BY WORKFLOW
    ";
    $stmt=db2_prepare($conn, $SqlList);
    $res=db2_execute($stmt);
    if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
    while ($row = db2_fetch_assoc($stmt)) {
       $Id=$row['ID_WORKFLOW']; 
       $Name=$row['WORKFLOW']; 
       $Desc=$row['DESCR'];
       $ReadOnly=$row['READONLY'];
       $Freq=$row['FREQUENZA'];
       $Multi=$row['MULTI'];
       
       if( "$Id" == "$IdWorkFlow" ){
           $WfsName=$Name;
           $WfsDescr=$Desc;
           $WfsReadOnly=$ReadOnly;
           $WfsFreq=$Freq;
           $WfsMulti=$Multi;
       }
       
       array_push($ArrWfs,array($Id,$Name,$Desc,$ReadOnly,$Freq,$Multi));
    }
    if ( count($ArrWfs) == 1 ){ 
           $IdWorkFlow=$Id; 
           $WfsName=$Name;
           $WfsDescr=$Desc;
           $WfsReadOnly=$ReadOnly;
           $WfsFreq=$Freq;
           $WfsMulti=$Multi;
    }
   

    if ( "$IdWorkFlow" != "" ){
        
         //PERIOD
        $ArrPeriod=array();
        $SqlList="SELECT DISTINCT ESER_ESAME||LPAD(MESE_ESAME,3,0) PERIODO
        FROM WORK_CORE.ID_PROCESS P 
        WHERE 1=1
        --AND FLAG_STATO != 'C'
        AND ID_WORKFLOW = $IdWorkFlow
        ORDER BY 1 DESC";
        $stmt=db2_prepare($conn, $SqlList);
        $res=db2_execute($stmt);
        if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }  
        while ($row = db2_fetch_assoc($stmt)) {
            $Perd=$row['PERIODO']; 
			if ( "$IdPeriod" == "" ){$IdPeriod=$Perd;}
            array_push($ArrPeriod,$Perd);
        } 
        if ( count($ArrPeriod) == 1 ){ $IdPeriod=$Perd; }
    
                
        if ( "$IdPeriod" != "" ){
           $AddIdProcess=$_POST['AddIdProcess'];
           if ( "$AddIdProcess" == "1" ) {          
                      
             $CallPlSql='CALL WORK_RULES.K_ID_PROCESS.AddIdProcess(?, ?, ?, ?, ?, ?, ?, ?, ?)';
             $stmt=db2_prepare($conn, $CallPlSql);
             
             
             $ProcEserEsame=substr($IdPeriod,0,4);
             $ProcMeseEsame=ltrim(substr($IdPeriod,4,3),0);
             
             $SqlList="SELECT count(*)+1 CNT FROM WORK_CORE.ID_PROCESS WHERE ID_WORKFLOW = $IdWorkFlow AND ESER_ESAME||LPAD(MESE_ESAME,3,0) = $IdPeriod ";
             $stmt=db2_prepare($conn, $SqlList);
             $res=db2_execute($stmt);
             if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); } 
             while ($row = db2_fetch_assoc($stmt)) {
                 $CntIdP=$row['CNT']; 
             }
             $Descr="Chiusura $ProcEserEsame $ProcMeseEsame [$CntIdP]";
             $Tipo='Q';
                 
             db2_bind_param($stmt, 1, "ProcEserEsame" , DB2_PARAM_IN);
             db2_bind_param($stmt, 2, "ProcMeseEsame" , DB2_PARAM_IN);
             db2_bind_param($stmt, 3, "Descr"         , DB2_PARAM_IN);
             db2_bind_param($stmt, 4, "Tipo"          , DB2_PARAM_IN);
             db2_bind_param($stmt, 5, "IdTeam"        , DB2_PARAM_IN);
             db2_bind_param($stmt, 6, "IdWorkFlow"    , DB2_PARAM_IN);
             db2_bind_param($stmt, 7, "User"          , DB2_PARAM_IN);
             db2_bind_param($stmt, 8, "Errore"        , DB2_PARAM_OUT);
             db2_bind_param($stmt, 9, "Note"          , DB2_PARAM_OUT);
             
             $res=db2_execute($stmt);
             
             if ( ! $res) {
                 $Note="PLSQL Exec Calling Error: ".db2_stmt_errormsg();
                 $ShowErrore=1;
             }
             
             if ( "$Errore" != "0" ) {
                 $Note="PLSQL Procedure Calling Error: ".$Note;
                 $ShowErrore=1;
             }
             
             if ( $ShowErrore != 0 ) {
               echo $Note;
             }
           }               
      
           //FLUSSI
           $ArrFlussi=array();
           $ArrPreFlussi=array();
           $ArrSucFlussi=array();
           $SqlList="SELECT ID_FLU,FLUSSO,DESCR FROM WFS.FLUSSI WHERE ID_WORKFLOW = $IdWorkFlow ORDER BY FLUSSO";
           $stmt=db2_prepare($conn, $SqlList);
           $res=db2_execute($stmt);
           if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg($stmt); } 
           while ($row = db2_fetch_assoc($stmt)) {
               $Id=$row['ID_FLU']; 
               $Name=$row['FLUSSO']; 
               $Desc=$row['DESCR'];        
               array_push($ArrFlussi,array($Id,$Name,$Desc));           
           }
           
           //ID_PROCESS
           $ArrIdProcess=array();
           $SqlList="SELECT ID_PROCESS, DESCR,  ESER_ESAME, MESE_ESAME, ESER_MESE, FLAG_CONSOLIDATO, TIPO, READONLY, FLAG_STATO, ID_TEAM
           ,( SELECT count(*) FROM WORK_CORE.ID_PROCESS WHERE ESER_ESAME = I.ESER_ESAME AND MESE_ESAME = I.MESE_ESAME AND FLAG_CONSOLIDATO = 1 AND ID_TEAM = I.ID_TEAM AND ID_WORKFLOW = I.ID_WORKFLOW) CONS
           FROM WORK_CORE.ID_PROCESS  I
           WHERE 1=1
           AND ID_WORKFLOW = $IdWorkFlow 
           AND ESER_ESAME||LPAD(MESE_ESAME,3,0) = $IdPeriod
           ORDER BY ID_PROCESS";
           $stmt=db2_prepare($conn, $SqlList);
           $res=db2_execute($stmt);
           if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); } 
           while ($row = db2_fetch_assoc($stmt)) {
               $IdProc=$row['ID_PROCESS']; 
               $DescrProc=$row['DESCR']; 
               $EserEsameProc=$row['ESER_ESAME'];
               $MeseEsameProc=$row['MESE_ESAME'];  
               $EserMeseProc=$row['ESER_MESE']; 
               $FlagProc=$row['FLAG_CONSOLIDATO']; 
               $TipoProc=$row['TIPO']; 
               $ReadOnly=$row['READONLY'];
               $FlgStato=$row['FLAG_STATO'];
               $PCons=$row['CONS'];
               $IdTeam=$row['ID_TEAM'];
               
               array_push($ArrIdProcess,array($IdProc,$DescrProc,$EserEsameProc,$MeseEsameProc,$EserMeseProc,$FlagProc,$TipoProc,$ReadOnly,$FlgStato,$PCons,$IdTeam));        
           }   
           if ( count($ArrIdProcess) == 1 or "$AddIdProcess" == "1" ){ $IdProcess=$IdProc;}    
           
        }
    }
        
 
//===============================================================================================
//===============================================================================================
//==========   FORM
    ?>
    <FORM id="MainForm" method="POST" enctype="multipart/form-data" >
    <div id="ListWfs">
      <input type="hidden" name="LastTimeCoda" id="LastTimeCoda" value="" >
      <input type="hidden" name="TopScroll" id="TopScroll" value="<?php echo $TopScroll; ?>" >    
      <input type="hidden" name="TopScrollDett" id="TopScrollDett" value="<?php echo $TopScrollDett; ?>" >  
      <div id="TitPag">WORKFLOW</div>
      <select id="IdWorkFlow" name="IdWorkFlow" style="width:100%;height:20px;color:black; box-shadow: -3px 2px 2px 0px lightgray !important; -o-box-shadow: -3px 2px 2px 0px lightgray !important; -moz-box-shadow: -3px 2px 2px 0px lightgray !important;"  >
           <option value="" >..</option>
           <?php           
            foreach( $ArrWfs as $Wfs ){
                  //ID_WORKFLOW,WORKFLOW,DESCR,READONLY
                  $Id=$Wfs[0];
                  $Name=$Wfs[1];
                  $Descr=$Wfs[2];
                  ?><option value="<?php echo $Id; ?>" <?php 
                  if ( "$Id" == "$IdWorkFlow" ){ 
                    ?>selected<?php 
                  } ?> 
                  ><?php echo $Name; if ( "$Descr" != "" ) { echo "  ( $Descr )"; } ?></option><?php
            }
           ?>
      </select>
      <div id="TitPag">PERIOD</div>
      <select id="IdPeriod" name="IdPeriod" style="width:100%;height:20px;color:black;box-shadow: -3px 2px 2px 0px lightgray !important; -o-box-shadow: -3px 2px 2px 0px lightgray !important; -moz-box-shadow: -3px 2px 2px 0px lightgray !important;"  >
           <option value="" >..</option>
           <?php           
            foreach( $ArrPeriod as $Prd ){
                  ?><option value="<?php echo $Prd; ?>" <?php if ( "$Prd" == "$IdPeriod" ){ ?>selected<?php } ?> ><?php echo $Prd; ?></option><?php
            }
           ?>
      </select>   
      <div id="TitPag"> PROCESS</div>
      <table width="100%">
      <tr>
      <td>
      <select id="IdProcess" name="IdProcess" style="width:100%;height:20px;color:black;box-shadow: -3px 2px 2px 0px lightgray !important; -o-box-shadow: -3px 2px 2px 0px lightgray !important; -moz-box-shadow: -3px 2px 2px 0px lightgray !important;"  >
           <option value="" >..</option>
           <?php           
            foreach( $ArrIdProcess as $DettProc ){
                  $IdProc=$DettProc[0];
                  $DescrProc=$DettProc[1];
                  $StatoProc=$DettProc[8];
                  $PeriodCons=$DettProc[9];
                  $IdTeam=$DettProc[10];
                  switch ($StatoProc){
                    case 'A': $StWfs="APERTO";  break;   
                    case 'C': $StWfs="CHIUSO";  break;
                    case 'S': $StWfs="SALVATO"; break;
                   }
                  $TipoProc=$DettProc[6];
                  if ( "$DettProc[5]" == "1" ){ $StWfs="CONSOLIDATO";}
                  if ( "$IdProc" == "$IdProcess" ){
                      $ProcDescr=$DettProc[1];
                      $ProcEserEsame=$DettProc[2];
                      $ProcMeseEsame=$DettProc[3];
                      $ProcEserMese=$DettProc[4];
                      $ProcFlag=$DettProc[5]; 
                      $ProcTipo=$DettProc[6];
                      $WfsRdOnly=$DettProc[7];
                      $WfsStato=$DettProc[8];
                      $LabelTipo="";
                      switch ($ProcTipo){
                       case 'R': $LabelTipo="REST ";  break;
                       default: $LabelTipo=""; break;
                      }
					  $_SESSION['IdTeam']=$IdTeam;
					  $_SESSION['ProcAnno']=$ProcEserEsame;
					  if ( "$ProcMeseEsame" < 10 ){
					    $_SESSION['ProcMese']='0'.$ProcMeseEsame;
					  }else{
						$_SESSION['ProcMese']=$ProcMeseEsame;
					  }
					  
                  }
                  ?><option value="<?php echo $IdProc; ?>" <?php if ( "$IdProc" == "$IdProcess" ){ ?>selected<?php } ?> ><?php echo $LabelTipo.$StWfs.': '.$DescrProc; ?></option><?php
            }
           ?>
      </select>    
      </td>
      <?php if( "$WfsMulti" == "Y" and "$IdPeriod" != "" ){ ?>
        <td width="25px" title="Aggiungi IdProcess" onclick="AggiungiIdP()" style="text-align:right;" ><img width="20px" src="../images/Aggiungi.png" ></td>
      <?php } ?>
      </tr>
      </table>
      <input type="hidden" name="SelFlusso" id="SelFlusso" value="<?php echo $SelFlusso; ?>">
      <input type="hidden" name="SelNomeFlusso" id="SelNomeFlusso" value="<?php echo $SelNomeFlusso; ?>">
    </div>
    <?php 
    

    $AddIdProcess=$_POST['AddIdProcess'];
    if ( "$AddIdProcess" == "1" ) {
      $CallPlSql='CALL WORK_RULES.K_ID_PROCESS.AddIdProcess(?, ?, ?, ?, ?, ?, ?, ?, ?)';
      $stmt=db2_prepare($conn, $CallPlSql);
      
      $CntIdP=count($ArrIdProcess)+1;
      $Descr="Chiusura $ProcEserEsame $ProcMeseEsame [$CntIdP]";
      $Tipo='Q';
          
      db2_bind_param($stmt, 1, "ProcEserEsame" , DB2_PARAM_IN);
      db2_bind_param($stmt, 2, "ProcMeseEsame" , DB2_PARAM_IN);
      db2_bind_param($stmt, 3, "Descr"         , DB2_PARAM_IN);
      db2_bind_param($stmt, 4, "Tipo"          , DB2_PARAM_IN);
      db2_bind_param($stmt, 5, "IdTeam"        , DB2_PARAM_IN);
      db2_bind_param($stmt, 6, "IdWorkFlow"    , DB2_PARAM_IN);
      db2_bind_param($stmt, 7, "User"          , DB2_PARAM_IN);
      db2_bind_param($stmt, 8, "Errore"        , DB2_PARAM_OUT);
      db2_bind_param($stmt, 9, "Note"          , DB2_PARAM_OUT);
      
      $res=db2_execute($stmt);
      
      if ( ! $res) {
          $Note="PLSQL Exec Calling Error: ".db2_stmt_errormsg();
          $ShowErrore=1;
      }
      
      if ( "$Errore" != "0" ) {
          $Note="PLSQL Procedure Calling Error: ".$Note;
          $ShowErrore=1;
      }
      
      if ( $ShowErrore != 0 ) {
        echo $Note;
      } else {
           ?>
		  <script>
				$('#Waiting').show(); 
				$('#MainForm').submit();  
		  </script>		  
		  <?php
	  }
   
    }       
    
    if ( "$IdWorkFlow" != "" and "$IdPeriod" != "" ) {
        //LEGAMI
           $ArrLegami=array();
           $SqlList="SELECT ID_LEGAME,LIV,ID_FLU,PRIORITA,TIPO,ID_DIP,
           ( SELECT FLUSSO FROM WFS.FLUSSI WHERE ID_FLU = L.ID_FLU ) NOME_FLUSSO 
           FROM WFS.LEGAME_FLUSSI L
           WHERE ID_WORKFLOW = $IdWorkFlow 
           AND DECODE(VALIDITA,null,' $ProcMeseEsame ',' '||VALIDITA||' ') like '% $ProcMeseEsame %'";
           
           if ( "$Regime" != "" ){
              $SqlList=$SqlList."
              AND ID_FLU IN ( SELECT ID_FLU FROM WFS.REGIME_LEGAME_FLUSSI WHERE ID_REGIME = $Regime ) ";   
           }
           
           $SqlList=$SqlList."
           ORDER BY LIV, NOME_FLUSSO, TIPO, ID_DIP";
           //echo $SqlList;
           $stmt=db2_prepare($conn, $SqlList);
           $res=db2_execute($stmt);
           if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }  
           while ($row = db2_fetch_assoc($stmt)) {
               $Id=$row['ID_LEGAME']; 
               $Liv=$row['LIV']; 
               $IdFlu=$row['ID_FLU']; 
               $Prio=$row['PRIORITA']; 
               $Tipo=$row['TIPO']; 
               $IdDip=$row['ID_DIP']; 
                   
               array_push($ArrLegami,array($Id,$Liv,$IdFlu,$Prio,$Tipo,$IdDip));
           } 
      ?>
      <div id="ShowDiagram" style="position:fixed; width:90%; height:600px; left:0; right:0; top:0; bottom:0; margin:auto; z-index:9999999; background:white; overflow:auto; box-shadow: 0px 0px 10px 1px black;" hidden ></div>
      <div id="TitoloFlussi" style="background:whitesmoke;">
          <div id="Titolo" title="<?php echo $IdWorkFlow; ?>" >
          <img style="float: left;" id="ImgRefresh1" src="../images/refresh.png" width="25" height="25" onclick="RefreshPage()" > 
          <div style="float: left;"  ><?php echo $WfsName; if ( "$WfsDescr" != "" ) { echo "    ( $WfsDescr ) - $LabelProcTipo"; } ?></div >
          <img src="../images/Diagram.png" title="Diagramma" onclick="OpenDiagram()" style="float: left;width: 25px; margin: 10px; cursor:pointer;background: white;border: 1px solid;margin: 4px;" >
          <?php
          if ( "$PeriodCons" != "0" and "$PeriodCons" != "" ) { 
            if ( "$ProcFlag" == "1" ) {       
               ?><div id="PeriodCons" style="float: left;font-size: 14px; background: yellow;"  >CONSOLIDATO</div><?php
            } else {
               ?><div id="PeriodCons" style="float: left;font-size: 14px; background: yellow;"  >PERIODO GIA' CONSOLIDATO</div><?php
            }
          } 
          ?>
          </div>
          <div id="ShowIdProcDett" style="margin-left: 5px;">
          <?php
          if ( "$IdProcess" != "" ) { 
              ?>
             <table class="ExcelTable" style="box-shadow: -3px 2px 2px 0px lightgray !important; -o-box-shadow: -3px 2px 2px 0px lightgray !important; -moz-box-shadow: -3px 2px 2px 0px lightgray !important; text-align: center;" >
                <tr>
				  <td rowspan=2>
				   <?php
                   if ( "$WfsRdOnly" == "Y" ) { 
                     if ( "${PeriodCons}" != "0" ){ 
                       ?><img src="../images/Lock.png" width="30px"><?php
                     }else{
                       ?><img src="../images/ReadMode.png" width="60px"><?php
                     }
                   }           
                   ?>
				   </td>
				   <th style="width: 80px;">IdProcess</th>
                   <th style="width: 100px;">Descrizione</th>
                   <?php
                   $SqlList="SELECT count(*) CNT FROM WFS.REGIME_FLUSSI WHERE ID_WORKFLOW = $IdWorkFlow AND ID_REGIME IN (SELECT DISTINCT ID_REGIME FROM WFS.REGIME_LEGAME_FLUSSI )";
                   $stmt=db2_prepare($conn, $SqlList);
                   $res=db2_execute($stmt);
                   if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
                   while ($row = db2_fetch_assoc($stmt)) {
                        $nrows=$row['CNT']; 
                   }
                   if ( $nrows > 0 ){
				     ?><th style="width: 30px;">Raggruppamento</th><?php
				   }
				   ?>
                   <th style="min-width: 45px;">Anno</th>
                   <th style="min-width: 45px;">Mese</th>
                   <th style="width: 80px;">Tipo</th>
                   <th style="width: 80px;">Frequenza</th>
                   <th style="width: 30px;">Stato</th>
				</tr>
				<tr>
                   <td style="width: 80px;"><?php echo $IdProcess; ?></td>
                   <td style="width: 100% !important;" title="<?php echo $IdProcess; ?>" >
                     <table style="width:100%; border: none;"><tr>
                     <td style="width: 100%; border: none;"><input type="text" style="width: 100%;" name="DescrIdP" id="DescrIdP" value="<?php echo $ProcDescr; ?>" ></td>
                     <td style="width: 30px; border: none;"><img src="../images/Matita.png" id="PulsIdP" title="Change Descr" onclick="SaveNewDescr()" style="height: 18px;width: 18px;background: white;position: relative;cursor:pointer;" hidden ></td>
                     </tr></table>
                   </td>
                   <?php
                   if ( $nrows > 0 ){
                     ?>
                     <td style="width: 100px;">
                     <select id="Regime" name="Regime" style="font-size: 15px; color: blue;" >
                     <option value="" >Tutti</option>
                     <?php
                     $SqlList="SELECT ID_REGIME, REGIME FROM WFS.REGIME_FLUSSI WHERE ID_WORKFLOW = $IdWorkFlow AND ID_REGIME IN ( SELECT DISTINCT ID_REGIME FROM WFS.REGIME_LEGAME_FLUSSI )";
                     $stmt=db2_prepare($conn, $SqlList);
                     $res=db2_execute($stmt);
                     if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }                   
                     while ($row = db2_fetch_assoc($stmt)) {
                        $IdRegime=$row['ID_REGIME']; 
                        $NameRegime=$row['REGIME']; 
                        ?><option value="<?php echo $IdRegime; ?>" <?php if ( "$Regime" == "$IdRegime" ) { ?> selected <?php } ?> ><?php echo $NameRegime; ?></option><?php
                     }
                     ?>
                     </select>
                     </td>
                     <?php 
                   }
                   ?>
                   <td style="width: 80px;"><?php echo $ProcEserEsame; ?></td>
                   <td style="width: 80px;"><?php echo $ProcMeseEsame; ?></td>
                   <td style="width: 100px;"><?php 
                   if ( "$ProcTipo" != "R" or $StatoProc != 'A' ){
                       switch ($ProcTipo){
                           case 'R': echo "Restatement ";  break;
						   case 'S': echo "Sensibility ";  break;
                           default:  echo "Closing "; break;
                       }
				   }else{
					 ?>
                      <select name="SelChSens" id="SelChSens" onchange="ChSens()" >
                        <option value="S" <?php if ( "$ProcTipo" == "S" ){ ?> selected <?php } ?> >Sensibility</option>
                        <option value="R" <?php if ( "$ProcTipo" == "R" ){ ?> selected <?php } ?> >Restatement</option>
                      </select>
					  <script>
	                  function ChSens(){
                           var input = $("<input>")
                            .attr("type", "hidden")
                            .attr("name", "ChSens")
                            .val('ChSens');
                            $('#MainForm').append($(input));
                            $('#Waiting').show(); 
                            $('#MainForm').submit();  
                      }
	                  </script>
                      <?php     
				   }
                   ?></td>
                   <td style="width: 100px;"><?php 
                       switch ($WfsFreq){
                           case 'M': echo "Mensile ";  break;   
                           case 'Q': echo "Trimestrare "; break;
                           case 'A': echo "Annuale "; break;
                       }
                   ?></td>
                   <td style="width: 30px;">
                   <?php 
                   if ( "$WfsStato" != "C" ){
                      ?>
                      <select name="SelStatoWfs" id="SelStatoWfs" onchange="CambiaStato()" >
                        <option value="A" <?php if ( "$WfsStato" == "A" ){ ?> selected <?php } ?> >Aperto</option>
                        <option value="S" <?php if ( "$WfsStato" == "S" ){ ?> selected <?php } ?> >Salvato</option>
                        <option value="C" <?php if ( "$WfsStato" == "C" ){ ?> selected <?php } ?> >Chiuso</option>
                      </select>
					  <script>
	                  function CambiaStato(){
                           var input = $("<input>")
                            .attr("type", "hidden")
                            .attr("name", "CambiaStato")
                            .val('CambiaStato');
                            $('#MainForm').append($(input));
                            $('#Waiting').show(); 
                            $('#MainForm').submit();  
                      }
	                  </script>
                      <?php                   
                   }else{
                      echo "CHIUSO";
                   }
                   ?>
                   </td>
                </tr>
             </table>
              <?php
          }
          ?>
          </div>
      </div>
  <?php
  }
  if ( "$IdProcess" != "" and "$IdPeriod" != ""  ) { 
  ?>
      <div id="ShowDettFlusso" class="DettFlusso" ></div>
      <script>

        var vId=$('#SelFlusso').val();
        var vName=$('#SelNomeFlusso').val();
        if ( vId != '' ){
            $('#ShowDettFlusso').empty().load('../PHP/WorkFlow_LoadFlusso.php',{
                     IdWorkFlow: <?php echo $IdWorkFlow; ?>,
                     IdProcess:  <?php echo $IdProcess; ?>,
                     IdFlu:      vId,
                     Flusso:     vName,
                     ProcMeseEsame: <?php echo $ProcMeseEsame; ?>
            }); 
            OpenStatusFlusso(<?php echo $IdWorkFlow; ?>,<?php echo $IdProcess; ?>,vId);
        } else {
            $('#ShowDettFlusso').empty().load('../PHP/WorkFlow_Legend.php');
        }

       </script>
       <div id="AreaPreFlussi" >           
         <?php         
         if ( "$IdWorkFlow" != "" and "$IdProcess" != "" ){
            $OldLiv="";
            foreach( $ArrLegami as $Legame1 ){
                  //ID_LEGAME,LIV,ID_FLU,PRIORITA,TIPO,ID_DIP
                  $Liv=$Legame1[1];
                  if ( "$Liv" != "$OldLiv"){
                    ?>
                     <div class="ACapo" style="display:block;"></div>      
                     <div id="Liv<?php echo $Liv; ?>" class="Livello" >
                     <div class="TitoloLiv" ><B>LEVEL <?php echo $Liv; ?></B></div>
                    <?php
                    $OldLiv=$Liv;
                    foreach( $ArrLegami as $Legame ){
                       //ID_LEGAME,LIV,ID_FLU,PRIORITA,TIPO,ID_DIP
                       if ( $Liv == $Legame[1] ){  
                         $IdFlu=$Legame[2];
                         if ( "$IdFlu" != "$OldIdFlu"){
                           $OldIdFlu=$IdFlu;
                           foreach( $ArrFlussi as $Flusso ){
                             //ID_FLU,FLUSSO,DESCR
                             $IdFlusso=$Flusso[0];
                             if ( "$IdFlusso" == "$IdFlu" ){ 
                                ?>
                                <div id="StatusFlusso<?php echo $IdFlusso; ?>" onclick="OpenStatusFlusso(<?php echo $IdWorkFlow; ?>,<?php echo $IdProcess; ?>,<?php echo $IdFlusso; ?>)" style="position: relative; float: left;" class="StatusFlusso" ></div>
                                <script>
                                 $('#StatusFlusso<?php echo $IdFlusso; ?>').load('../PHP/WorkFlow_Flusso.php',{
                                           IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
                                           IdProcess: '<?php echo $IdProcess; ?>',
                                           IdFlusso: <?php echo $IdFlusso; ?>,
                                           ProcMeseEsame: <?php echo $ProcMeseEsame; ?>
                                  });                                     
                                  $('#StatusFlusso<?php echo $IdFlusso; ?>').click(function(){
                                    $('.Flusso').each(function(){$(this).removeClass('ingrandFlu');});
                                  });   
                                </script>
                                <?php
                             }
                           }
                         }
                       }
                    }
                    ?></div><?php
                  }
            }
         }
         ?>
       </div>
       <?php 
    } 
    
    ?>    
    <div id="EsitoUpload" hidden ></div>
    
   
    <div id="pageEsito" ></div>
    <div id="PulMostraCoda" >
        <img id="ElabInCodaLoad" src="../images/Loading.gif" style="height:18px;width:18px;float:left;display:none;background: white;" hidden>
        <img id="ElabInCodaWait" src="../images/Sveglia.png" style="height:18px;width:18px;float:left;display:none;background: white;" hidden>
        <p id="FraseCoda"></p>
    </div>
    <div id="PulMostraStorico" >
        All Launches
    </div>  
    <div id="RefreshCoda" ></div>
    <div id="MostraCoda" hidden ></div>
    <div id="MostraStorico" hidden ></div>
    <div id="OpenLinkPage" hidden ></div> 
    </FORM>
    <script>
    
        var vId=$('#SelFlusso').val();
        if ( vId != '' ){ $().show(); }
    
        $( "#RefreshCoda" ).load('../PHP/Workflow_RefreshCoda.php',{
            IdWorkFlow : <?php echo $IdWorkFlow          ; ?>,
            IdProcess  : <?php echo $IdProcess           ; ?>,
            SelIdFlu   : '<?php echo $SelFlusso          ; ?>',
            SelNomeFlu : '<?php echo $SelNomeFlusso      ; ?>',
            SelIdDip   : '<?php echo $SelDipendenza      ; ?>',
            SelNomeDip : '<?php echo $SelNomeDipendenza  ; ?>',
            SelTipo    : '<?php echo $SelTipo            ; ?>',
            MaxTime    : $('#LastTimeCoda').val()
        });  
    </script>
    <div id="countdown"></div> 
    <?php

//===============================================================================================
//==========   SCRIPT
 
    if ( "$LinkPagina" != ""  ){

        ?>
        <script>
          $('#Waiting').show();
          $('#OpenLinkPage').empty().load('../PHP/Workflow_OpenLinkPage.php',{
                IdWorkFlow:   '<?php echo $IdWorkFlow; ?>'
                <?php
                foreach($_POST as $Nome => $Variabile ){
                   echo ",$Nome : '".$Variabile."'";;
                }
                ?>
          }).show();
        </script>
        <?php

    }
    ?>        
    <script> 
    
      $('#DescrIdP').keyup(function(){
          if ( $('#DescrIdP').val() == '<?php echo $ProcDescr; ?>' ){
            $('#PulsIdP').hide();
          }else{
            $('#PulsIdP').show(); 
          }
      });
    
    
      function AggiungiIdP(){
        var re = confirm('Confermi di voler creare un nuovo IdProcess?');
        if ( re == true ) {
          var input = $("<input>")
           .attr("type", "hidden")
           .attr("name", "AddIdProcess")
           .val('1');
           $('#MainForm').append($(input));
          $('#Waiting').show();
          $('#MainForm').submit();
        }
      };
    
      function SaveNewDescr(){
        var re = confirm('Confermi la modifica dalla descrizione?');
        if ( re == true ) {
          var input = $("<input>")
           .attr("type", "hidden")
           .attr("name", "NewDesc")
           .val('NewDesc');
           $('#MainForm').append($(input));
          $('#Waiting').show();
          $('#MainForm').submit();
        }
      };
    
      $('#IdWorkFlow').change(function(){
        $('#SelFlusso').val('');
        $('#SelNomeFlusso').val('');
        $('#ShowDettFlusso').empty().load('../PHP/WorkFlow_Legend.php');
        $('#IdPeriod').val('');
        $('#IdProcess').val('');
        if ( $('#IdWorkFlow').val() == '' ){
         var input = $("<input>")
         .attr("type", "hidden")
         .attr("name", "Resetta")
         .val('Resetta');
         $('#MainForm').append($(input));
         $('#TopScroll').val('');
        } else {
         var input = $("<input>")
          .attr("type", "hidden")
          .attr("name", "ResettaPer")
          .val('Resetta');
          $('#MainForm').append($(input));
          $('#TopScroll').val('');
        }
        $('#MainForm').submit();
      });
      $('#IdPeriod').change(function(){
        $('#SelFlusso').val('');
        $('#SelNomeFlusso').val('');
        $('#ShowDettFlusso').empty().load('../PHP/WorkFlow_Legend.php');
        $('#IdProcess').val('');
        $('#MainForm').submit(); 
      }); 
      $('#IdProcess').change(function(){
        $('#Waiting').show();
        $('#TopScroll').val('');
        $('#MainForm').submit();
      });     
    </script>
    <script>
 
      function ReOpenDettFlusso(vIdWorkFlow,vIdProcess,vIdFlusso,vNomeFlusso,vDescFlusso){
          $('#LastTimeCoda').val('');
          $('#TopScrollDett').val($('#ShowDettFlusso').scrollTop());
          $('#StatusFlusso'+vIdFlusso).load('../PHP/WorkFlow_Flusso.php',{
             IdWorkFlow: vIdWorkFlow,
             IdProcess: vIdProcess,
             IdFlusso: vIdFlusso,
             DescFlusso: vDescFlusso,
             ProcMeseEsame: <?php echo $ProcMeseEsame; ?>
          });
          //$('.Flusso').each(function(){
          //  $(this).css('border','1px solid white');
          //});
          $('#SelFlusso').val(vIdFlusso);
          $('#SelNomeFlusso').val(vNomeFlusso);
          $('#ShowDettFlusso').empty().load('../PHP/WorkFlow_LoadFlusso.php',{
                 IdWorkFlow: vIdWorkFlow,
                 IdProcess:  vIdProcess,
                 IdFlu:      vIdFlusso,
                 Flusso:     vNomeFlusso,
                 DescFlusso: vDescFlusso,
                 ProcMeseEsame: <?php echo $ProcMeseEsame; ?>
          });
     }
 
     function OpenDettFlusso(vIdWorkFlow,vIdProcess,vIdFlusso,vNomeFlusso,vDescFlusso){
          $('#LastTimeCoda').val('');
          //$('.Flusso').each(function(){
          //  $(this).css('border','1px solid white');
          //});
          var vVal = $('#SelFlusso').val();
          if ( vVal == vIdFlusso ){
            $('#SelFlusso').val('');
            $('#SelNomeFlusso').val('');
            $('#ShowDettFlusso').empty().load('../PHP/WorkFlow_Legend.php');
          }else{
            $('#SelFlusso').val(vIdFlusso);
            $('#SelNomeFlusso').val(vNomeFlusso);
            $('#ShowDettFlusso').empty().load('../PHP/WorkFlow_LoadFlusso.php',{
                     IdWorkFlow: vIdWorkFlow,
                     IdProcess:  vIdProcess,
                     IdFlu:      vIdFlusso,
                     Flusso:     vNomeFlusso,
                     DescFlusso: vDescFlusso,
                     ProcMeseEsame: <?php echo $ProcMeseEsame; ?>
            });
          }
     }
     
     function OpenStatusFlusso(vIdWorkFlow,vIdProcess,vIdFlusso){
        $('#LastTimeCoda').val('');
        $('#StatusFlusso'+vIdFlusso).load('../PHP/WorkFlow_Flusso.php',{
             IdWorkFlow: vIdWorkFlow,
             IdProcess: vIdProcess,
             IdFlusso: vIdFlusso,
             ProcMeseEsame: <?php echo $ProcMeseEsame; ?>
        });
     }
    
    function RefreshPage(){
       $('#TopScroll').val($('#AreaPreFlussi').scrollTop());
       $('#TopScrollDett').val($('#ShowDettFlusso').scrollTop());
       $('#MainForm').submit();
    }

    
    </script>
    <script>   

      function LoadDiv(){
            $( "#RefreshCoda" ).load('../PHP/Workflow_RefreshCoda.php',{
                IdWorkFlow : '<?php echo $IdWorkFlow         ; ?>',
                IdProcess  : '<?php echo $IdProcess          ; ?>',
                SelIdFlu   : '<?php echo $SelFlusso          ; ?>',
                SelNomeFlu : '<?php echo $SelNomeFlusso      ; ?>',
                SelIdDip   : '<?php echo $SelDipendenza      ; ?>',
                SelNomeDip : '<?php echo $SelNomeDipendenza  ; ?>',
                SelTipo    : '<?php echo $SelTipo            ; ?>',
                MaxTime    : $('#LastTimeCoda').val()
            }); 
            if ( $( "#MostraCoda" ).text() != "" ){
              $( "#MostraCoda" ).load('../PHP/Workflow_MostraCoda.php',{
                IdWorkFlow : '<?php echo $IdWorkFlow; ?>',
                IdProcess  : '<?php echo $IdProcess; ?>'
              });   
            }
      }
    
      setInterval(function(){ LoadDiv(); }, 5000); 
    
    
      $('#PulMostraCoda').click(function(){
           $( "#MostraCoda" ).empty().load('../PHP/Workflow_MostraCoda.php',{
                IdWorkFlow : '<?php echo $IdWorkFlow; ?>',
                IdProcess  : '<?php echo $IdProcess; ?>'
            }).show();
      });
     
      $('#PulMostraStorico').click(function(){
           $( "#MostraStorico" ).empty().load('../PHP/Workflow_MostraStorico.php',{
                IdWorkFlow : '<?php echo $IdWorkFlow; ?>',
                IdProcess  : '<?php echo $IdProcess; ?>'
            }).show();
      });   
    <?php 
    if ( "$Action" == "CancellaCoda" ){
        ?>
        $( "#MostraCoda" ).load('../PHP/Workflow_MostraCoda.php',{
                IdWorkFlow : '<?php echo $IdWorkFlow; ?>',
                IdProcess  : '<?php echo $IdProcess; ?>'
            }).show();
        <?php
    }
    ?>
    
    $(window).ready( function() {

        var time = 300

        setInterval( function() {

            time--;

            $('#countdown').html(time+' secs');

            if (time === 0) {
                $('#TopScroll').val($('#AreaPreFlussi').scrollTop());
                $('#TopScrollDett').val($('#ShowDettFlusso').scrollTop());
                $('#MainForm').submit();
            }    


        }, 1000 );

    });
    
    
    function OpenDiagram(){
        $( "#ShowDiagram" ).load('../PHP/GeneraDiagram.php',{
                IdWorkFlow : '<?php echo $IdWorkFlow; ?>',
                IdProcess  : '<?php echo $IdProcess; ?>',
                WfsName    : '<?php echo $WfsName; ?>',
                ProcDescr  : '<?php echo $ProcDescr; ?>'
        }).show();
    }
    
        $('#AreaPreFlussi').scrollTop($('#TopScroll').val());
        $('#ShowDettFlusso').scrollTop($('#TopScrollDett').val());
        
        
     $('#Regime').change(function(){
       $('#Waiting').show();
       $('#MainForm').submit();
     });


    function OpenDiagram(){
        $( "#ShowDiagram" ).load('../PHP/GeneraDiagram.php',{
                IdWorkFlow : '<?php echo $IdWorkFlow; ?>',
                IdProcess  : '<?php echo $IdProcess; ?>',
                WfsName    : '<?php echo $WfsName; ?>',
                ProcDescr  : '<?php echo $ProcDescr; ?>'
        }).show();
    }
	                     
    </script>
    <?php   
    

}
?>



