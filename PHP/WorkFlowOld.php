<?php
include '../GESTIONE/sicurezza.php';
    
if ( $find == 1 )   { 
    $db2conn = db2_connect($db2_conn_string, '', '');
    $Filtra="";
    $Filtra=$_POST["Filtra"];
    
    $NascPrio="";
    $NascPrio=$_POST["NascPrio"];

    $CheckACapo="";
    $CheckACapo=$_POST["CheckACapo"];   
    
    $FLogici="";
    $FLogici=$_POST["FLogici"];

    $FSito="";
    $FSito=$_POST["FSito"];
    
    $OrdPer="";
    $OrdPer=$_POST["OrdinaPer"];
    
    $FlussoSelez=$_POST["FlussoSelez"];

   
    $DefSaw=$_POST['DefSaw']; 
    if ( "$DefSaw" != "" ){
        $UpdateDefSaw="update WEB.TAS_UTENTI set KSAW='$DefSaw' where upper(USERNAME)=upper('$User') ";
        $stmtTabRead = db2_prepare($db2conn, $UpdateDefSaw);
        $resultTabRead = db2_execute($stmtTabRead);  
    }
    //$KSaw=$_SESSION['KSaw'];
    $SelSaw=$_POST['SelKSAW'];   
    if ( "$SelSaw" != "" ){
      $KSaw=$SelSaw;
      $_SESSION['KSaw']=$KSaw;
    } 
    
   $SqlCheck="SELECT DISTINCT a.WORKFLOW,a.DESCR,a.READMODE
   from 
     WFS.AMBIENTI a, 
     WEB.TAS_GRUPPI g	 
   where 1=1
	AND a.ABILITATO = 'S'
	AND a.KSAW=$KSaw 
	AND g.GK in ( $CodGroup )
	";
   $res=db2_prepare($db2conn, $SqlCheck);
   $resultTabRead = db2_execute($res);  
   while ($rowmysql = db2_fetch_assoc($res)) {
       $WorkFlow=$rowmysql['WORKFLOW']; 
       $Descr=$rowmysql['DESCR'];
       $RdMOde=$rowmysql['READMODE'];
   } 
    
    $RicFlusso="";
    $RicFlusso=$_POST["RicFlusso"];
   
    $RicDipendenza="";
    $RicDipendenza=$_POST["RicDipendenza"];
    $RicTipo="";
    $RicTipo=$_POST["RicTipo"]; 
    $RicAzione="";
    $RicAzione=$_POST["RicAzione"];     
 
    
    function EsitoRichiesta( $NotaRis, $EsitoRis , $ErroreRis ){
        $Img="CodaOK";
        if ( $EsitoRis == 'KO' ) {
           $Img="CodaKO"; 
        }
        ?>
        <div id="EsitoUpload" >
            <div id="EsitoImg"><img src="../images/<?php echo $Img; ?>.png" height="60%"></div>
            <div id="EsitoText"><?php echo $NotaRis; ?></div>
            <?php 
            if ( $ErroreRis <> '' ) {
            ?><div id="EsitoText"><?php print_r ($ErroreRis); ?></div><?php
            }
            ?>
        </div>
        <?php
    }
    
//-----------------------------------------------------------------------------------------------------------
//  CARICA FILE
//-----------------------------------------------------------------------------------------------------------
        
    $PulCaricaFile = $_POST["PulCaricaFile"];
    if ( "$PulCaricaFile" == "Load" ){
       $InpFlusso = $_POST["FlussoC"];         
       $InpDipendenza = $_POST["DipendenzaC"];
       $Tipo = $_POST["TipoC"];
       
       if( isset($_FILES['UploadFileName_'.$InpFlusso.'_'.$InpDipendenza]) ){
          $errors= array();
          $file_name = $_FILES['UploadFileName_'.$InpFlusso.'_'.$InpDipendenza]['name'];
          $file_size =$_FILES['UploadFileName_'.$InpFlusso.'_'.$InpDipendenza]['size'];
          $file_tmp =$_FILES['UploadFileName_'.$InpFlusso.'_'.$InpDipendenza]['tmp_name'];
          $file_type=$_FILES['UploadFileName_'.$InpFlusso.'_'.$InpDipendenza]['type'];
          $file_ext=strtolower(end(explode('.',$_FILES['UploadFileName_'.$InpFlusso.'_'.$InpDipendenza]['name'])));
                    
          $expensions= array("xls","xlsx","csv");
          
          if(in_array($file_ext,$expensions)=== false){
             $errors[]="Il File non e' conforme al formato richiesto: xls,xlsx,csv";
          }
          
          if($file_size > 62428800){
             $errors[]='Il File eccede dai 60 MB massimi di caricamento';
          }
          
          $Azione= "C";
          
          $RicTipo = "C";
          $RicAzione="C";
          $RicFlusso=$InpFlusso;
          $RicDipendenza=$InpDipendenza; 

          if(empty($errors)==true){
               
               $UpdLastFile="update WFS.CARICAMENTI set LASTFILE='${file_name}' where CARICAMENTO = '$InpDipendenza' "; 
               $stmtTabRead = db2_prepare($db2conn, $UpdLastFile);
               $resultTabRead = db2_execute($stmtTabRead);               
          
               $moved = move_uploaded_file($file_tmp,"$UPLOADDIR/".ente."_".$InpDipendenza.".".$file_ext);
                
                if( $moved ) {
		    $Prosegui=true;				                   
                    if ( "$MetSCopy" == "scp" ) {
                     $command='scp '.${UPLOADDIR}.'/'.${InpDipendenza}.'.'.${file_ext}.' '.${SSHUSR}.'@'.${SERVER}.':'.${ELABDIR}.'/ ';
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
                      $db2conn1 = db2_connect($db2_conn_string, '', ''); 
                      $SqlCaricamento='CALL WEB.CORE.AggiungiInCoda(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                      $stmt = db2_prepare($db2conn1, $SqlCaricamento);

                      $Errore=false;
                      $Note="";
                      
                      db2_bind_param($stmt, 3, "InpFlusso", DB2_PARAM_IN);
                      db2_bind_param($stmt, 6, "InpDipendenza", DB2_PARAM_IN);
                      db2_bind_param($stmt, 7, "Tipo", DB2_PARAM_IN);
                      db2_bind_param($stmt, 8, "Azione", DB2_PARAM_IN);
                      db2_bind_param($stmt, 9, "User", DB2_PARAM_IN);
                      db2_bind_param($stmt, 10, "Errore", DB2_PARAM_OUT);
                      db2_bind_param($stmt, 11, "Note", DB2_PARAM_OUT);
                      db2_execute($stmt);
                      db2_close($db2conn1);
                      
                      if ( $Errore ) {
                          $errors="";
                          if ( "$Note" == "" ) {$Note="PLSQL Procedure Calling Error";  }
                          EsitoRichiesta( $Note ,'KO', $errors);    
                      } else {
                        EsitoRichiesta( $Note ,'OK', '' ); 
                        shell_exec("sh $PRGDIR/AvviaElabServer.sh '${SSHUSR}' '${SERVER}' '${DirWfsAix}' 2>&1 > $PRGDIR/AvviaElabServer.log &");
                      }
                       
                    } else {
                      $Note="Error upload file to Aix";
                      $errors="";
                      EsitoRichiesta( $Note ,'KO', $errors);                        
                    }
                } else {
                  $Note="Error upload file to WebServer";
                  $errors="";
                  EsitoRichiesta( $Note ,'KO', $errors);
                }   
                   
 
          }else{
             $Note="Errore nel caricamento del File:";
             EsitoRichiesta( $Note ,'KO', $errors);        
          }
       }else{
         $Note="Errore Caricamento File Web:";
         EsitoRichiesta( $Note ,'KO', $errors);     
       }
       unset($_POST['PulCaricaFile']);
    }
 
//-----------------------------------------------------------------------------------------------------------
//  PULSANTE VALIDA DATI
//-----------------------------------------------------------------------------------------------------------

    $PulValidaDati=$_POST['PulValidaDati'];
    if ( "$PulValidaDati" == "Valida Dati Esistenti" ) {
      $InpFlusso = $_POST["FlussoVD"];        
      $InpDipendenza = $_POST["DipendenzaVD"];            
      $Tipo = $_POST["TipoVD"];    
      $Azione="F";

      $db2conn1 = db2_connect($db2_conn_string, '', ''); 
      $SqlCaricamento='CALL WEB.CORE.AggiungiInCoda(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
      $stmt = db2_prepare($db2conn1, $SqlCaricamento);

      $Errore=false;
      $Note="";
      
      db2_bind_param($stmt, 3, "InpFlusso", DB2_PARAM_IN);
      db2_bind_param($stmt, 6, "InpDipendenza", DB2_PARAM_IN);
      db2_bind_param($stmt, 7, "Tipo", DB2_PARAM_IN);
      db2_bind_param($stmt, 8, "Azione", DB2_PARAM_IN);
      db2_bind_param($stmt, 9, "User", DB2_PARAM_IN);
      db2_bind_param($stmt, 10, "Errore", DB2_PARAM_OUT);
      db2_bind_param($stmt, 11, "Note", DB2_PARAM_OUT);
      db2_execute($stmt);
      
      if ( $Note == "" ) {
          $Errore=true;
          $Note="PLSQL Procedure Calling Error";
      }
      
      if ( $Errore ) {
        EsitoRichiesta( $Note ,'KO', '' );
      } else {
        EsitoRichiesta( $Note ,'OK', '' );  
      }  
      db2_close($db2conn1);       
      unset($_POST['PulValidaDati']);

    } 
 
 //-----------------------------------------------------------------------------------------------------------
//  PULSANTE VALIDA
//-----------------------------------------------------------------------------------------------------------

    $PulValida=$_POST['PulValida'];
    if ( "$PulValida" == "Valida" ) {
      $InpFlusso = $_POST["FlussoV"];         
      $InpDipendenza = $_POST["DipendenzaV"];         
      $Tipo = $_POST["TipoV"]; 
      $Azione="V";

      $db2conn1 = db2_connect($db2_conn_string, '', ''); 
      $SqlCaricamento='CALL WEB.CORE.AggiungiInCoda(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
      $stmt = db2_prepare($db2conn1, $SqlCaricamento);

      $Errore=false;
      $Note="";
      
      db2_bind_param($stmt, 3, "InpFlusso", DB2_PARAM_IN);
      db2_bind_param($stmt, 6, "InpDipendenza", DB2_PARAM_IN);
      db2_bind_param($stmt, 7, "Tipo", DB2_PARAM_IN);
      db2_bind_param($stmt, 8, "Azione", DB2_PARAM_IN);
      db2_bind_param($stmt, 9, "User", DB2_PARAM_IN);
      db2_bind_param($stmt, 10, "Errore", DB2_PARAM_OUT);
      db2_bind_param($stmt, 11, "Note", DB2_PARAM_OUT);
      db2_execute($stmt);
      
      if ( $Note == "" ) {
          $Errore=true;
          $Note="PLSQL Procedure Calling Error";
      }

      if ( $Errore ) {
        EsitoRichiesta( $Note ,'KO', '' );
      } else {
        EsitoRichiesta( $Note ,'OK', '' );        
      }  
      
      db2_close($db2conn1);       
      unset($_POST['PulValida']);

    } 
 
//-----------------------------------------------------------------------------------------------------------
//  PULSANTE RESET
//-----------------------------------------------------------------------------------------------------------
    $PulResettaE=$_POST['PulResettaE'];     
    $PulResetta=$_POST['PulResetta'];
    if ( "$PulResetta" == "Invalida" || "$PulResettaE" == "Invalida" ) {
      $InpFlusso = $_POST["FlussoS"];         
      $InpDipendenza = $_POST["DipendenzaS"];   
      $Tipo = $_POST["TipoS"];    
      $Azione="S";

      $db2conn1 = db2_connect($db2_conn_string, '', ''); 
      $SqlCaricamento='CALL WEB.CORE.AggiungiInCoda(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
      $stmt = db2_prepare($db2conn1, $SqlCaricamento);

      $Errore=false;
      $Note="";
      
      db2_bind_param($stmt, 3, "InpFlusso", DB2_PARAM_IN);
      db2_bind_param($stmt, 6, "InpDipendenza", DB2_PARAM_IN);
      db2_bind_param($stmt, 7, "Tipo", DB2_PARAM_IN);
      db2_bind_param($stmt, 8, "Azione", DB2_PARAM_IN);
      db2_bind_param($stmt, 9, "User", DB2_PARAM_IN);
      db2_bind_param($stmt, 10, "Errore", DB2_PARAM_OUT);
      db2_bind_param($stmt, 11, "Note", DB2_PARAM_OUT);
      db2_execute($stmt);
      
      if ( $Note == "" ) {
          $Errore=true;
          $Note="PLSQL Procedure Calling Error";
      }
      
      if ( $Errore ) {
        EsitoRichiesta( $Note ,'KO', '' );
      } else {
        EsitoRichiesta( $Note ,'OK', '' ); 
      }  
      
      db2_close($db2conn1);       
      unset($_POST['PulResetta']);
      
    }

//-----------------------------------------------------------------------------------------------------------
//  PULSANTE ELABORAZIONE
//-----------------------------------------------------------------------------------------------------------
     
    $PulElaborazione=$_POST['PulElaborazione'];
    if ( "$PulElaborazione" == "Avvia Elaborazione" ) {
      $InpFlusso = $_POST["FlussoE"];         
      $InpDipendenza = $_POST["DipendenzaE"]; 
      $Tipo = $_POST["TipoE"]; 
      $Azione="E";
      
      $RicTipo="E";
      $RicAzione="E";
      $RicFlusso=$InpFlusso;
      $RicDipendenza=$InpDipendenza; 

      $db2conn1 = db2_connect($db2_conn_string, '', ''); 
      $SqlCaricamento='CALL WEB.CORE.AggiungiInCoda(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
      $stmt = db2_prepare($db2conn1, $SqlCaricamento);

      $Errore=false;
      $Note="";
      
      db2_bind_param($stmt, 3, "InpFlusso", DB2_PARAM_IN);
      db2_bind_param($stmt, 6, "InpDipendenza", DB2_PARAM_IN);
      db2_bind_param($stmt, 7, "Tipo", DB2_PARAM_IN);
      db2_bind_param($stmt, 8, "Azione", DB2_PARAM_IN);
      db2_bind_param($stmt, 9, "User", DB2_PARAM_IN);
      db2_bind_param($stmt, 10, "Errore", DB2_PARAM_OUT);
      db2_bind_param($stmt, 11, "Note", DB2_PARAM_OUT);
      db2_execute($stmt);
      
      if ( $Note == "" ) {
          $Errore=true;
          $Note="PLSQL Procedure Calling Error";
      }
      
      if ( $Errore ) {
        EsitoRichiesta( $Note ,'KO', '' );
      } else {
        EsitoRichiesta( $Note ,'OK', '' );               
      }  
      
      db2_close($db2conn1);       
      shell_exec("sh $PRGDIR/AvviaElabServer.sh '${SSHUSR}' '${SERVER}' '${DirWfsAix}' 2>&1 > $PRGDIR/AvviaElabServer.log &");       
      //echo "sh $PRGDIR/AvviaElabServer.sh '${SSHUSR}' '${SERVER}' '${DirWfsAix}' 2>&1 > $PRGDIR/AvviaElabServer.log &";       
      unset($_POST['PulElaborazione']);
    }    
	
//-----------------------------------------------------------------------------------------------------------
//  REFRESH STATO FLUSSI
//-----------------------------------------------------------------------------------------------------------
//echo "Inizio Refresh Stato Flussi : ".date("Y-m-d H:i:s")."</BR>";    
    
$SqlFlussiReset="select LIVELLO,FLUSSO from WFS.FLUSSI order by LIVELLO, FLUSSO";
$resFlusso=db2_prepare($db2conn, $SqlFlussiReset);
db2_execute($resFlusso);
while ($rowFlusso = db2_fetch_assoc($resFlusso)) { 
    $Livello=$rowFlusso["LIVELLO"];
    $Flusso=$rowFlusso["FLUSSO"]; 
    
	$InCorso=false;
    $CntF=0;
    $CntErr=0;
    $CntTot=0;
          
    $SqlSearchDip="select DIPENDENZA,TIPO from WFS.LEGAME_FLUSSI where FLUSSO='$Flusso' order by LIV";
    $resDip=db2_prepare($db2conn, $SqlSearchDip);
    db2_execute($resDip);
    while ($rowDip = db2_fetch_assoc($resDip)) { 
      $Dipendenza=$rowDip['DIPENDENZA'];
      $Tipo=$rowDip['TIPO'];
      
      $CntTot=$CntTot+1;
      $CasoFlusso=false;
      switch ( $Tipo ) {
        case "F":
          $SqlEsitoDip="select ESITO from WFS.FLUSSI where FLUSSO='$Dipendenza'";
          $CasoFlusso=true;
          break;
        case "V":
          $SqlEsitoDip="select ESITO from WFS.VALIDAZIONI where VALIDAZIONE='$Dipendenza'";
          break; 
        case "E":
          $SqlEsitoDip="select ESITO from WFS.ELABORAZIONI where ELABORAZIONE='$Dipendenza'";        
          break;              
        default :
          $SqlEsitoDip="select ESITO from WFS.CARICAMENTI where CARICAMENTO='$Dipendenza'";         
          break;            
      }
      $resEsitoDip=db2_prepare($db2conn, $SqlEsitoDip);   
      while ($rowEsitoDip = db2_fetch_assoc($resEsitoDip)) { $Esito=$rowEsitoDip['ESITO']; }
      switch ( "$Esito" ) {
      case "F":
        $CntF=$CntF+1;
        break;
      case "E":
        if ( $CasoFlusso == false ){$CntErr=$CntErr+1;}
        break;
      case "N":
        null;
        break;
      case "C":
        $InCorso=true;
        break; 
      }   
    }
	
    if ( $CntErr > 0 ) {
      $SqlUpdateStato="update WFS.FLUSSI set ESITO='E' where FLUSSO = '$Flusso' "; 
    } else {
       if ( $InCorso ) {
         $SqlUpdateStato="update WFS.FLUSSI set ESITO='I' where FLUSSO = '$Flusso' "; 
       } else {
         if ( $CntTot == $CntF ) {
            $SqlUpdateStato="update WFS.FLUSSI set ESITO='F' where FLUSSO = '$Flusso' "; 
         } else {
           if ( $CntF > 0 ) {
             $SqlUpdateStato="update WFS.FLUSSI set ESITO='I' where FLUSSO = '$Flusso' "; 
           } else {
             $SqlUpdateStato="update WFS.FLUSSI set ESITO='N' where FLUSSO = '$Flusso' ";
           }
         } 
       }
    }
    $stmtTabRead = db2_prepare($db2conn, $SqlUpdateStato); 
    db2_execute($stmtTabRead);	
}       
//echo "Fine Refresh Stato Flussi Logici: ".date("Y-m-d H:i:s")."</BR>";      

//-----------------------------------------------------------------------------------------------------------
//  LIVELLO MINIMO
//-----------------------------------------------------------------------------------------------------------
//echo "Inizio Livello Minimo: ".date("Y-m-d H:i:s")."</BR>";        
//Minimo Liv non finito
$SqlMinLiv="select 
             min(LIVELLO) MINLIV
           from 
             WFS.FLUSSI      
           where 1=1
               AND ESITO <> 'F'
               AND ONESHOT <> 'S'
               AND  FLUSSO in (
               select FLUSSO from WFS.LEGAME_FLUSSI a where
                a.WORKFLOW='$WorkFlow' AND            
                a.DIPENDENZA in ( 
                 select 
                   c.DIPENDENZA 
                 from 
                   WFS.LEGAME_FLUSSI c,
                   WFS.CARICAMENTI d        
                 where c.WORKFLOW='$WorkFlow'              
                   AND c.DIPENDENZA=d.CARICAMENTO
                   AND ESITO <> 'F'
                   AND TIPO = 'C'                      
               )
               OR a.DIPENDENZA in ( 
                 select 
                   e.DIPENDENZA 
                 from 
                   WFS.LEGAME_FLUSSI e,
                   WFS.VALIDAZIONI f        
                 where e.WORKFLOW='$WorkFlow'              
                   AND e.DIPENDENZA=f.VALIDAZIONE
                   AND ESITO <> 'F'
                   AND TIPO = 'V'                      
               )
               OR a.DIPENDENZA in ( 
                 select 
                   g.DIPENDENZA 
                 from 
                   WFS.LEGAME_FLUSSI g,
                   WFS.ELABORAZIONI h        
                 where g.WORKFLOW='$WorkFlow'             
                   AND g.DIPENDENZA=h.ELABORAZIONE
                   AND ESITO <> 'F' 
                   AND TIPO = 'E'
               )    
                OR a.DIPENDENZA in ( 
                 select 
                   i.DIPENDENZA 
                 from 
                   WFS.LEGAME_FLUSSI i,
                   WFS.FLUSSI l        
                 where i.WORKFLOW='$WorkFlow'               
                   AND i.DIPENDENZA=l.FLUSSO
                   AND ESITO <> 'F' 
                   AND TIPO = 'F'
               )    
               )
               ";   
$resMinLiv=db2_prepare($db2conn, $SqlMinLiv);
db2_execute($resMinLiv);	
$MinLiv==0; 
while ($rowMinLiv = db2_fetch_assoc($resMinLiv)) { 
  $MinLiv=$rowMinLiv["MINLIV"];  
}  
 
	$WfsAdmin=false;
	$sqlWfsAdmin="SELECT UK from WEB.TAS_WORKGROUP WHERE UK = (SELECT UK from WEB.TAS_UTENTI where USERNAME = '$User' ) AND GK = ( SELECT GK FROM WEB.TAS_GRUPPI WHERE GRUPPO = 'ADMIN')";
 	$resultWfsAdmin=db2_prepare($db2conn, $sqlWfsAdmin);
    db2_execute($resultWfsAdmin);	
	while ($rowWfsAdmin = db2_fetch_assoc($resultWfsAdmin)) {
	  $WfsAdmin=true;
	}		


 ?>

 <form method="post" id="FormCaric" enctype="multipart/form-data"> 
    <input type="hidden" id="RicFlusso" name="RicFlusso" value="<?php echo $RicFlusso; ?>" />
    <input type="hidden" id="RicDipendenza" name="RicDipendenza" value="<?php echo $RicDipendenza; ?>" /> 
    <input type="hidden" id="RicTipo" name="RicTipo" value="<?php echo $RicTipo; ?>" /> 
    <input type="hidden" id="RicAzione" name="RicAzione" value="<?php echo $RicAzione; ?>" /> 
    <input type="hidden" name="FlussoSelez" id="FlussoSelez" value="<?php echo $FlussoSelez; ?>"/>
    <?php
    //---------------------------------------------------------------------------------------------------------------------------------------------------
    //---------------------------------------------------FLUSSI DA ESEGUIRE------------------------------------------------------------------------------
    //--------------------------------------------------------------------------------------------------------------------------------------------------- 
	
    $ReadMode = "N";
    if ( "$RdMOde" == "S" ) { 
        $InReadMode="<img src=\"../images/ReadMode.png\" height=\"60px\">"; 
        $ReadMode = "S";
    } 
	
    
    ?>
    <div id="AmbienteInUso">
      <div id="TitPag" >WORKFLOW</div>
      <select name="SelKSAW"  id="SelSaw" style="width:100%;height:20px;color:black;">  
      <option value="0" >Select..</option>
        <?php
       $SqlCheck="SELECT a.KSAW,a.DESCR 
	   from 
	   WFS.AMBIENTI a,
	   WEB.TAS_GRUPPI g
	   where 1=1 
	   AND g.GK IN ( $CodGroup )
	   AND a.ABILITATO = 'S' 
	   GROUP BY a.KSAW,a.DESCR
	   ORDER BY a.KSAW,a.DESCR
	   ";
       //echo $SqlCheck;
       $res=db2_prepare($db2conn, $SqlCheck);
       db2_execute($res);	
       while ($rowmysql = db2_fetch_assoc($res)) {
           $MKSAW=$rowmysql['KSAW'];        
           $MDESCR=$rowmysql['DESCR'];
		   ?><option value="<?php echo $MKSAW; ?>" <?php if ( "$MKSAW" == "$KSaw"){ ?>selected<?php } ?>><?php echo "$MDESCR"; ?></option><?php
	   } 
       ?>
      </select>
      <div id="DivPulPag" >
      <div id="SetDefault" >Set as Default</div>
      <?php
       $TestUty=true;     
	   $vSearchUser="SELECT a.FLUSSO, b.GRUPPO
	    FROM 
		WFS.AUTHFLOW a,
		WEB.TAS_GRUPPI b
	    WHERE 1=1 
		AND a.GK = b.GK		
		AND a.WORKFLOW = '${WorkFlow}'
	    AND a.GK IN ( $CodGroup )
		 ";
		$resSearchUser=db2_prepare($db2conn, $vSearchUser);
        db2_execute($resSearchUser);
		while ($rwSearchUser= db2_fetch_assoc($resSearchUser)) {
			   $SearchGruppo=$rwSearchUser["GRUPPO"];
			   if ( "$SearchGruppo" != "READ" ){
			      $TestUty=false;
			   }
		}		  
		?>
      </div>
      <script>
         $("#SelSaw").change(function(){
           $("#FormCaric").submit();
         });
         
         $("#SetDefault").click(function(){
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "DefSaw")
            .val('<?php echo $KSaw; ?>');
            $('#FormCaric').append($(input));          
            $("#FormCaric").submit();
         });    
                 
      </script>
    </div>
     <div id="TitoloFlussi" >
        <div id="Titolo" >
          <?php echo ${InReadMode}; ?> 
          <?php echo "${WorkFlow} ${Periodo}"; ?>
          <img id="ImgRefresh" src="../images/refresh.png" height="25" width="25"> 
         <script>
         $("#ImgRefresh").click(function(){
           $("#FormCaric").submit();
         });
         </script>        
        </div>
        <div class="Elementi" >
         <input type="checkbox" id="CheckACapo" name="CheckACapo" value="Si" <?php if ( "$CheckACapo" == "Si" ) { ?>checked<?php } ?>  style="margin-left: 10px;">Break Level    
         <input type="checkbox" id="NascPrio" name="NascPrio" value="Si" <?php if ( "$NascPrio" == "Si" ) { ?>checked<?php } ?>  style="margin-left: 10px;">Hide Priority    
         <input type="checkbox" id="Filtra" name="Filtra" value="Si" <?php if ( "$Filtra" == "Si" ) { ?>checked<?php } ?>  style="margin-left: 10px;">Possible Exec    
        </div>  
    </div>
    <div id="AreaPreFlussi"> 
    <div><?php
	
    $SqlPopRipet="SELECT DISTINCT
      a.FLUSSO,INIZIO,FINE,ESITO,X SCADENZA,LIVELLO,ABILITATO,ONESHOT
    from
      WFS.LEGAME_FLUSSI a,
      WFS.FLUSSI b
    where a.WORKFLOW='${WorkFlow}'          
      AND a.FLUSSO=b.FLUSSO
      AND b.ABILITATO='S'
    order by LIVELLO, ONESHOT desc, FLUSSO 
    ";
    $resPopRipet=db2_prepare($db2conn, $SqlPopRipet);
    db2_execute($resPopRipet);
    $ContaFlusso=0; 
    $OldLiv="";
    while ($rowPopRipet = db2_fetch_assoc($resPopRipet)) { 
      $Flusso=$rowPopRipet["FLUSSO"];     
      $Inizio=$rowPopRipet["INIZIO"]; 
      $Fine=$rowPopRipet["FINE"]; 
      $Esito=$rowPopRipet["ESITO"]; 
      $Scadenza=$rowPopRipet["SCADENZA"];
      $Livello=$rowPopRipet["LIVELLO"];
      $Abilitato=$rowPopRipet["ABILITATO"];
      $OneShot=$rowPopRipet["ONESHOT"]; 

	  
       $FlowReadMode=true;     
	   $vSearchUser="SELECT a.FLUSSO, b.GRUPPO
	    FROM 
		WFS.AUTHFLOW a,
		WEB.TAS_GRUPPI b
	    WHERE 1=1 
		AND a.GK = b.GK		
		AND a.WORKFLOW = '${WorkFlow}'
	    AND a.FLUSSO = '${Flusso}'
		AND a.GK IN ( $CodGroup )
		 ";
		$resSearchUser=db2_prepare($db2conn, $vSearchUser);
        db2_execute($resSearchUser);
		while ($rwSearchUser= db2_fetch_assoc($resSearchUser)) {
			   $SearchGruppo=$rwSearchUser["GRUPPO"];
			   if ( "$SearchGruppo" != "READ" ){
			      $FlowReadMode=false;
			   }
		}
        
	  if ( $Admin or $WfsAdmin ) {	$FlowReadMode=false; }
	  
      $Rinnovato="N"; 
      $SqlRinDip="select RINNOVATO, ESITO from WFS.CARICAMENTI 
        where CARICAMENTO in 
        (select DIPENDENZA 
        from WFS.LEGAME_FLUSSI 
        where FLUSSO='$Flusso' 
        AND TIPO='C')";     
        $stmtRinDip=db2_prepare($db2conn, $SqlRinDip);
        db2_execute($stmtRinDip);
      while ($rowRinDip = db2_fetch_assoc($stmtRinDip)) {
             $Rin=$rowRinDip['RINNOVATO'];
             $CarEsito=$rowRinDip['ESITO'];
			 if ( "$CarEsito" == "W"){ $Esito="I";}
             if ( "$Rin" == "S"){ $Rinnovato="S";}
      }   

      $ContaFlusso=$ContaFlusso+1;
     
      $CntErr=0;
      $CntEsec=0;
      $CntFine=0;
      
      $EsitoDip="N";
      $Mostra="NonMostrare";
      $classEsitoDip="";  
      $Img="DaEseguire";
      $classFlusso="";    
      switch ( $Esito ){
      case 'E' :
        $Img="Terminato";
        $classFlusso="FlussoTerminato";
        $Mostra="";
        break;
      case 'C':
        $Img="InEsecuzione";
        $classFlusso="FlussoInEsecuzione"; 
        $Mostra="";
        break;
      case 'F':
        $classFlusso="FlussoEseguito";
        $Img="Eseguito"; 
        break;
      case 'I':
        $classFlusso="FlussoIncompleto"; 
        $Img="Incompleto";      
        $SqlNumFlussi="select DIPENDENZA, TIPO from WFS.LEGAME_FLUSSI 
        where 1=1
        AND WORKFLOW='$WorkFlow' 
        AND FLUSSO = '$Flusso' 
        AND TIPO in ('C','E','V')";
        $stmtElencoDip=db2_prepare($db2conn, $SqlNumFlussi);
        db2_execute($stmtElencoDip);
        while ($rowElencoDip = db2_fetch_assoc($stmtElencoDip)) {
          $Dip=$rowElencoDip['DIPENDENZA'];
          $Tipo=$rowElencoDip['TIPO'];
          $EsitoDip="N";
          switch ( $Tipo ) {
            case "E":
              $SqlEsitoDip="select ESITO from WFS.ELABORAZIONI where ELABORAZIONE='$Dip'";
              break;              
            case "C":
              $SqlEsitoDip="select ESITO from WFS.CARICAMENTI where CARICAMENTO='$Dip'";     
              break;  
            case "V":
              $SqlEsitoDip="select ESITO from WFS.VALIDAZIONI where VALIDAZIONE='$Dip'";     
              break; 
           }
           $resEsitoDip=db2_prepare($db2conn, $SqlEsitoDip);
           db2_execute($resEsitoDip);
           while ($rowEsitoDip = db2_fetch_assoc($resEsitoDip)) { $EsitoDip=$rowEsitoDip['ESITO']; }             
           switch ( $EsitoDip ) {
             case 'W':
               $CntEsec=$CntEsec=+1;
               break;			   
             case 'C':
               $CntEsec=$CntEsec=+1;
               break;
             case 'E':  
               $CntErr=$CntErr=+1;
               break;              
           }
        }
           
        if ( $CntEsec > 0 ) {
            $Img="InEsecuzione";
            $classFlusso="FlussoInEsecuzione"; 
        }       
        
        if ( $CntErr > 0 ) {
            $Img="Terminato";
            $classFlusso="FlussoTerminato"; 
        }
        
        //Se elaborazione possibile
        $SqlElencoDip="select DIPENDENZA from WFS.LEGAME_FLUSSI where WORKFLOW='$WorkFlow' AND FLUSSO = '$Flusso' AND TIPO = 'F'";
        $stmtElencoDip=db2_prepare($db2conn, $SqlElencoDip);
        db2_execute($stmtElencoDip);
        $CtFF=0;
        $NumFlussi=0;
        while ($rowElencoDip = db2_fetch_assoc($stmtElencoDip)) {
          $Dip=$rowElencoDip['DIPENDENZA'];
          $SqlEsitoFlusso="select ESITO from WFS.FLUSSI where FLUSSO='$Dip' AND ONESHOT = 'N'";     
          $resEsitoFlusso=db2_prepare($db2conn, $SqlEsitoFlusso);
          db2_execute($resEsitoFlusso);
          $NumFlussi=$NumFlussi+1;
          while ($rowEsitoFlusso = db2_fetch_assoc($resEsitoFlusso)) { 
		     $EstoDip=$rowEsitoFlusso['ESITO'];
             if ( "$EstoDip" == "F" ){ $CtFF=$CtFF+1; }
          }            
        }       
        if ( "$CtFF" == "$NumFlussi" ){ $Mostra=""; }
        break;
      default :
        break;
      }  
      
  
      if ( "$MinLiv" == "$Livello" AND "$EsitoDip" <> "F" ) { $Mostra=""; }

      $SqlElencoDip="select DIPENDENZA from WFS.LEGAME_FLUSSI where WORKFLOW='$WorkFlow' AND FLUSSO = '$Flusso' AND TIPO = 'F'";
      $stmtElencoDip=db2_prepare($db2conn, $SqlElencoDip);
      db2_execute($stmtElencoDip);
      while ($rowElencoDip = db2_fetch_assoc($stmtElencoDip)) {
          $Dip=$rowElencoDip['DIPENDENZA'];
          $classEsitoDip=$classEsitoDip." $Dip";
      }
      
      if ( "$Livello$OneShot" <> "$OldLiv" ){
          if ( "$OneShot" == "S" AND $Livello == 0 ) {
              ?></div><div id="Liv<?php echo $Livello; ?>" style="display:none;" class="LivOneShot <?php echo $Mostra; ?>" >
              <div class="TitoloLiv" ><B><font style="color:red;">ONE SHOT</font></B></div><?php
          } else {
             ?></div>
             <div class="ACapo" style="display:block;"></div>      
             <div id="Liv<?php echo $Livello; ?>" class="Livello <?php echo $Mostra; ?>" >
             <div class="TitoloLiv" ><B>LEVEL <?php echo $Livello ?></B></div><?php
          }
          $OldLiv=$Livello.$OneShot;
      }
      
      $InCoda = "N";
      $SqlElencoDip="select DIPENDENZA from WFS.CODA_RICHIESTE where FLUSSO = '$Flusso'";
      $stmtElencoDip=db2_prepare($db2conn, $SqlElencoDip);
      db2_execute($stmtElencoDip);
      while ($rowElencoDip = db2_fetch_assoc($stmtElencoDip)) { $InCoda='S'; }

      $ElabPos = "";
      $ErrFind=0;
      $PosElabFind=0;
      $SqlLivPos="select LIV ".
      " from WFS.LEGAME_FLUSSI ".
      " where 1=1 ".
      " AND WORKFLOW = '${WorkFlow}' ".
      " AND FLUSSO = '${Flusso}' ".
      " group by LIV ".
      " order by LIV";
      $stmtLivPos=db2_prepare($db2conn, $SqlLivPos);
      db2_execute($stmtLivPos);
      while ($rowLivPos = db2_fetch_assoc($stmtLivPos)) { 
          $LivPos=$rowLivPos['LIV'];
          $SqlElabPos="select DIPENDENZA, TIP ".
          " from WFS.LEGAME_FLUSSI ".
          " where 1=1 ".
          " AND FLUSSO = '${Flusso}' ".
          " AND WORKFLOW = '${WorkFlow}' ".
          " AND LIV = '$LivPos'";
          $stmtElabPos=db2_prepare($db2conn, $SqlElabPos);
          db2_execute($stmtElabPos);
          while ($rowElabPos = db2_fetch_assoc($stmtElabPos)) { 
            $PosDip=$rowElabPos['DIPENDENZA'];
            $PosTip=$rowElabPos['TIPO']; 
                    
            switch ( "$PosTip" ) {
                case "F":
                  $SqlEsiDip="select ESITO from WFS.FLUSSI ".
                  " where ESITO <> 'F' ".
                  " AND  FLUSSO='$PosDip' ";
                  break;  
                case "V":
                  $SqlEsiDip="select ESITO ".
                  " from WFS.VALIDAZIONI ".
                  " where 1=1 ".
                  " AND ESITO <> 'F' ".
                  " AND VALIDAZIONE='$PosDip'";
                  break;  
                case "E":
                  $SqlEsiDip="select ESITO ".
                  " from WFS.ELABORAZIONI ".
                  " where 1=1 ".
                  " AND ESITO <> 'F' ".
                  " AND  ELABORAZIONE='$PosDip'";
                  break;              
                case "C":
                  $SqlEsiDip="select ESITO ".
                  " from WFS.CARICAMENTI ".
                  " where 1=1 ".
                  " AND ESITO <> 'F' ".
                  " AND  CARICAMENTO='$PosDip'";            
                  break;
            } 
            $resEsiDip=db2_prepare($db2conn, $SqlEsiDip);
            db2_execute($resEsiDip);
            while ($rowEsiPos = db2_fetch_assoc($resEsiDip)) {    
                $EsiPos=$rowEsiPos['ESITO']; 
                switch ( "$PosTip" ){
                  case "E":
                    if ( "$EsiPos" == "N"  ){ 
                      $PosElabFind=1;  
                      break 4;
                    } else { 
                      $ErrFind=1; 
                      break; 
                    }       
                  default :
                    $ErrFind=1;
                    break;                  
                }
            }
          } 
      }   
      //echo " Err: $ErrFind  Find: $PosElabFind";
      if ( "$ErrFind" == "0" AND "$PosElabFind" == "1" ){
         $ElabPos=" ElabPos";
      }   
      
      //File Disponibile:
      $FileDisp=false;
      $SqlFileDisp="select 
                 b.AMBIENTE,
                 b.VALIDAZIONE
              from 
                 WFS.LEGAME_FLUSSI a,
                 WFS.VALIDAZIONI b        
               where a.WORKFLOW='$WorkFlow'
                   AND TIPO = 'V'
                   AND a.DIPENDENZA=b.VALIDAZIONE
                   AND a.FLUSSO='$Flusso'
                   AND b.DISP='S'";
      $resFileDisp=db2_prepare($db2conn, $SqlFileDisp);
      db2_execute($resFileDisp);
      $numFD=0;
      while ($rowFileDisp = db2_fetch_assoc($resFileDisp)) {
          $FDVal=$rowFileDisp['VALIDAZIONE'];
          $FileDisp=true; 
          $ArrayFD[$numFD]=$FDVal;
          $numFD=$numFD+1;
      }   

      $ClassiCar=$classEsitoDip." ".$Mostra.$DivSito.$ElabPos;
      ?>
      <div id="<?php echo $Flusso; ?>" class="Flusso <?php echo $ClassiCar; ?> <?php if ( $Esito == 'F'  ) { ?>Esex<?php } ?>" >
       <div id="NomeFlusso" class="<?php echo $classFlusso; ?>"<?php 
	   if ( strlen($Flusso) > 10 ) {
		   ?>style="font-size:80%;"<?php
	   } 
	   ?> 
	   ><?php echo $Flusso; ?>  
	   </div>
       <div id="ImageIn" class="<?php echo $classFlusso; ?>">
         <div id="IcoFlusso" >
               <?php
               $SveNasc="nascondi";
			   if ( "$InCoda" == "S" AND "$CntEsec" == "0" ) {$SveNasc="";}
               ?><img id="Sveg<?php echo $Flusso ?>" class="ImgSveglia <?php echo $SveNasc; ?>" src="../images/Sveglia.png" ><?php
               if ( "$Rinnovato" == "S" ) {
                   ?><img class="ImgRinnovato" src="../images/Rinnovato.png" ><?php
               }  
               if ( $FileDisp ){
                   ?><img class="ImgFileDisp" src="../images/File.png" ><?php
               }
			   if ( $FlowReadMode ){
				   ?><img class="ImgRinnovato" src="../images/ReadMode.png" ><?php
			   }
               ?>
         </div>
       <?php
       if ( "$Abilitato" == "S" ){
         ?><img src="../images/<?php echo $Img; ?>.png" id="ImageEsito"><?php 
       } else {
         if ( "$Abilitato" == "L" ){
             ?><img src="../images/LavoriInCorso.png" id="LavoriInCorso"><?php
         }
       } 
       $Illumina=" ";
       $NumGG=" ";
       if ( $Scadenza > 0 ) {
           $Fnt="0.9em";
           if ( $Esito <> 'F' ) { 
              $NumGG=date("d")-$Scadenza; 
           } else {
              $NumGG=date("d", strtotime($Fine))-$Scadenza; 
           }

             switch ( true ) {
                 case ( $NumGG > 0 ):
                   $colGG="rgb(195, 0, 195)";                     
                   $NumGG='+'.$NumGG.' G'; 
                   $Fnt="1.3em";
                   $padval="1px";                  
                   break;
                 case ( $NumGG == 0 ) :
                   $NumGG='OGGI';            
                   if ( "$Esito" == 'F' ) {  
                      $NumGG='0'.' G';
                   }
                   $Fnt="1.3em";  
                   $padval="1px"; 
                   $colGG="rgb(221, 41, 41)";                
                   break;
                 case ( $NumGG > -6 ):
                   $colGG="#5159ff";
                   $NumGG=$NumGG.' G'; 
                   $Fnt="1.3em";
                   $padval="1px";                
                   break;
                 default:
                   $colGG="#5159ff";
                   $NumGG=$NumGG.' G'; 
                   $Fnt="1.3em";
                   $padval="1px";                
             }
             if ( $Esito <> 'F' ) { $Illumina="box-shadow: 0px 0px 5px 0px $colGG inset;"; } else { $Illumina="";}
       }
       ?>
       </div>
       <div id="DataFlusso"  class="<?php echo $classFlusso; ?>" style="color:<?php echo $colGG; ?>;font-size:<?php echo $Fnt; ?>;<?php echo $Illumina;?>>;padding-top:<?php echo $padval; ?>;"><B><?php echo $NumGG; ?></B></div>
      </div>
      
      <?php
      if ( $Abilitato == "S" ){ 
         ?>
           <div id="<?php echo "Dett".$Flusso;; ?>" class="DettFlusso nascondi" >  
            <div id="Inselez"><?php
			if ( $FlowReadMode ){
				   ?><img style="height:30px;margin:3px;" src="../images/ReadMode.png" ><?php
			} 
			?><B><?php echo $Flusso;?></B></div>
            <?php
         
             $sqlDip = "
             select 
                 f.DIPENDENZA,
                 f.LIV,
                 f.INIZIO,
                 f.FINE,
                 f.ESITO,
                 f.TIPO,
                 f.BATCH,
                 f.RINNOVABILE,
                 f.NOTE,
                 f.LOG,
                 f.RINNOVATO,
				 f.SVALIDABILE,
                 f.READMODE_ON
             from
             (
             (select        
                 a.DIPENDENZA,
                 a.LIV,
                 INIZIO,
                 FINE,
                 ESITO,
                 TIPO,
                 BATCH,
                 b.RINNOVABILE,
                 b.NOTE,
                 b.LOG,
                 b.RINNOVATO,
				 b.SVALIDABILE,
                 'N' READMODE_ON
               from 
                 WFS.LEGAME_FLUSSI a,
                 WFS.CARICAMENTI b        
               where a.WORKFLOW='$WorkFlow'
                   AND TIPO = 'C'              
                   AND a.DIPENDENZA=b.CARICAMENTO
                   AND a.FLUSSO='$Flusso'
              )
              union all
              (select
                 a.DIPENDENZA,
                 a.LIV,
                 INIZIO,
                 FINE,
                 ESITO,
                 TIPO,
                 'N' BATCH,
                 'N' RINNOVABILE,
                 '' NOTE,
                 '' LOG,
                 'N' RINNOVATO,
				 'S' SVALIDABILE,
                 'N' READMODE_ON
               from 
                 WFS.LEGAME_FLUSSI a,
                 WFS.FLUSSI b        
               where a.WORKFLOW='$WorkFlow'
                   AND TIPO = 'F'
                   AND a.DIPENDENZA=b.FLUSSO
                   AND a.FLUSSO='$Flusso'
              ) 
              union all
             (select
                 a.DIPENDENZA,
                 a.LIV,
                 '-' INIZIO,
                 DATA FINE,
                 ESITO,
                 TIPO,
                 'N' BATCH,
                 'N' RINNOVABILE,
                 b.DESC NOTE,
                 '' LOG,
                 'N' RINNOVATO,
				 'S' SVALIDABILE,				 
                 b.READMODE_ON
               from 
                 WFS.LEGAME_FLUSSI a,
                 WFS.VALIDAZIONI b        
               where a.WORKFLOW='$WorkFlow'
                   AND TIPO = 'V'
                   AND a.DIPENDENZA=b.VALIDAZIONE
                   AND a.FLUSSO='$Flusso'
              )
              union all
              (select
                 a.DIPENDENZA,
                 a.LIV,
                 INIZIO,
                 FINE,
                 ESITO,
                 TIPO,
                 'N' BATCH,
                 'N' RINNOVABILE,
                 b.NOTE,
                 b.LOG,
                 'N' RINNOVATO,
				 'S' SVALIDABILE,				 
                 b.READMODE_ON
              from 
                WFS.LEGAME_FLUSSI a,
                WFS.ELABORAZIONI b            
              where a.WORKFLOW='$WorkFlow'
                  AND a.DIPENDENZA=b.ELABORAZIONE
                  AND a.FLUSSO='$Flusso'
                  AND TIPO = 'E'
              ) 
              ) f 
              ORDER BY 
                 LIV, TIPO desc , DIPENDENZA       
               ";
            
            $ShowPreFlussi="";
            $HidePreFlussi="";   
            $ContaDip=0;
            $stmtDip=db2_prepare($db2conn, $sqlDip);
            db2_execute($stmtDip);
            while ($rowDip = db2_fetch_assoc($stmtDip)) {
               $Dipendenza=$rowDip['DIPENDENZA'];
               $Liv=$rowDip['LIV'];
               $Inizio=$rowDip["INIZIO"]; 
               $Fine=$rowDip["FINE"]; 
               $DipEsito=$rowDip['ESITO'];
               $Tipo=$rowDip['TIPO'];
               $Batch=$rowDip['BATCH'];
               $Rinnovabile=$rowDip['RINNOVABILE'];
               $Note=$rowDip['NOTE'];
               $Log=$rowDip['LOG'];
               $DipRinnovato=$rowDip['RINNOVATO']; 
			   $DipSvalidabile=$rowDip['SVALIDABILE']; 
               $RModeOn=$rowDip['READMODE_ON']; 
               
               $ContaDip=$ContaDip+1;
                     
               $Abilita=true;
               $Blocca="";
                   $SqlPreDipNonF="select sum(f.CONTA) CONTA from (
                                 ( select 
                                   count(*) CONTA
                                 from 
                                   WFS.LEGAME_FLUSSI a,
                                   WFS.CARICAMENTI b
                                 wherea.WORKFLOW='$WorkFlow'
                                   AND a.DIPENDENZA=b.CARICAMENTO
                                   AND a.FLUSSO='$Flusso' 
                                   AND LIV<$Liv
                                   AND ESITO <> 'F' 
                                   AND TIPO = 'C' )
                                 union all  
                                  ( select 
                                   count(*) CONTA
                                 from 
                                   WFS.LEGAME_FLUSSI a,
                                   WFS.FLUSSI c
                                 where a.WORKFLOW='$WorkFlow'
                                   AND a.DIPENDENZA=c.FLUSSO
                                   AND a.FLUSSO='$Flusso' 
                                   AND LIV<$Liv
                                   AND ESITO <> 'F' 
                                   AND TIPO = 'F') 
                                 union all  
                                  ( select 
                                   count(*) CONTA
                                 from 
                                   WFS.LEGAME_FLUSSI a,
                                   WFS.VALIDAZIONI c
                                 where a.WORKFLOW='$WorkFlow'
                                   AND a.DIPENDENZA=c.VALIDAZIONE
                                   AND a.FLUSSO='$Flusso' 
                                   AND LIV<$Liv
                                   AND ESITO <> 'F' 
                                   AND TIPO = 'V')  
                                 union all  
                                  ( select 
                                   count(*) CONTA
                                 from 
                                   WFS.LEGAME_FLUSSI a,
                                   WFS.ELABORAZIONI c
                                 where a.WORKFLOW='$WorkFlow'
                                   AND a.DIPENDENZA=c.ELABORAZIONE
                                   AND a.FLUSSO='$Flusso' 
                                   AND LIV<$Liv
                                   AND ESITO <> 'F' 
                                   AND TIPO = 'E')                                     
                                 ) f ";
                   $stPreDipNonF=db2_prepare($db2conn, $SqlPreDipNonF);
                   db2_execute($stPreDipNonF);
                   $ContaPreDipNonF=0;
                   while ($rowPreDipNonF = db2_fetch_assoc($stPreDipNonF)) {
                     $ContaPreDipNonF=$rowPreDipNonF['CONTA'];
                   }
                   if ( $ContaPreDipNonF > 0 ) { 
                     $Abilita=false; 
                     $Blocca=" Blocca";
                   }

			   $ImgSval='';
			   if ( "$DipSvalidabile" == "N" ){
			      $ImgSval='<img class="ImgSval" src="../images/Puntina.png" >';
			   }	 
				   
               $classDipendenza=""; 
               $ImgTipo=""; 
               $ImgUtente="";
               $Pendente="";
               $ImgDip='../images/Attesa.png';    
               if ( $DipEsito == 'N' AND  $Blocca == "" AND $Tipo <> 'F') {
                  $ImgDip='../images/Eseguibile.png';
               } 
               $ListWfs="";            
               switch ( $Tipo ) {
                 case "C":
                   $ImgTipo="Carica";
                   $SqlListWfs="select WORKFLOW from WFS.LEGAME_FLUSSI where TIPO='C' AND WORKFLOW <> '$WorkFlow' group by WORKFLOW order by WORKFLOW";
                   break;
                 case "F":
                   $ImgTipo="Flusso";
                   $SqlListWfs="select WORKFLOW from WFS.LEGAME_FLUSSI where WORKFLOW <> '$WorkFlow' group by WORKFLOW order by WORKFLOW";                
                   break;
                 case "V":
                   $ImgTipo="Valida";
                   $SqlListWfs="select WORKFLOW from WFS.LEGAME_FLUSSI where TIPO='V' AND WORKFLOW <> '$WorkFlow' group by WORKFLOW order by WORKFLOW";
                   break;  
                 case "E":
                   $ImgTipo="Elaborazione";
                   $SqlListWfs="select WORKFLOW from WFS.LEGAME_FLUSSI where TIPO='E' AND WORKFLOW <> '$WorkFlow' group by WORKFLOW order by WORKFLOW";
                   break;  
               }
               
               $stmtListWfs=db2_prepare($db2conn, $SqlListWfs);
               db2_execute($stmtListWfs);
               while ($rowListWfs= db2_fetch_assoc($stmtListWfs)) {
                  $WfsDescr=$rowListWfs['WORKFLOW'];
                  $SqlDescWfs="select DESCR from WFS.AMBIENTI where WORKFLOW = '$WfsDescr'";
                  $stmtDescWfs=db2_prepare($db2conn, $SqlDescWfs);
                  db2_execute($stmtDescWfs);
                  while ($rowDescWfs= db2_fetch_assoc($stmtDescWfs)) {
                    $ListWfs=$ListWfs." ".$rowDescWfs['DESCR'];
                  }
               }                          
               
               if ( $Batch == "S" ) {
                   if ( $Batch == "S" AND $Rinnovabile != "S" ) {
                     $Abilita=false; 
                     $ImgDip='../images/Divieto.png';   
                   } else {
                     $Abilita=true;    
                   }
                   $ImgTipo="InMacchina";
                   //if ( $DipEsito <> 'F' AND $Rinnovabile != "S" ){
                     //$Pendente=" Pendente";
                   //}
                }
                
                 $classDipendenza="";
                 switch ($DipEsito) {
                   case "E": 
                     $classDipendenza="Terminato";
                     $ImgDip='../images/KO.png';
                     break;
                   case "F": 
                     $classDipendenza="Eseguito";
                     if ( $DipRinnovato == 'S'){
                        $ImgDip='../images/Rinnovato.png'; 
                     } else {
                        $ImgDip='../images/OK.png'; 
                     }
                     $Abilita=true;
                     break;
                   case "C": 			   
                     $classDipendenza="InEsecuzione";
                     $ImgDip='../images/Loading.gif';
                     break;  
                   case "W": 			   
                     $classDipendenza="InEsecuzione";
                     $ImgDip='../images/Loading.gif';
                     break;  					 
                   default:
                     null;
                 }   				 
  
                 $ImgDip='<img id="ImgDip'.$Flusso.$Dipendenza.'" class="ImgIco" src="'.$ImgDip.'" >'; 
				  

                 if ( $Tipo == 'F' ) { $Abilita=false; }
                 
                 $InCoda = "N";
                 $SqlElencoDip="select DIPENDENZA from WFS.CODA_RICHIESTE where FLUSSO = '$Flusso' AND DIPENDENZA = '$Dipendenza' ";
                 $stmtElencoDip=db2_prepare($db2conn, $SqlElencoDip);
                 db2_execute($stmtElencoDip);
                 while ($rowElencoDip = db2_fetch_assoc($stmtElencoDip)) { $InCoda='S'; }
       				 
                 $Altezza=0;
               ?>
               <div id="DDip<?php echo $ContaFlusso."_".$ContaDip; ?>" class="Dipendenza <?php echo $Blocca.$Pendente; ?>" >

                 <div id="Icon<?php echo $ContaFlusso."_".$ContaDip; ?>" class="Icon">
                     <img class="ImgIco" src="../images/<?php echo $ImgTipo; ?>.png" >
                     <?php
                     if ( "$Tipo" == "E" AND ( $WfsAdmin OR $Admin )) {
                       $SqlShell="select SHELL from WFS.ELABORAZIONI where ELABORAZIONE = '$Dipendenza'";
                       $stmtShell=db2_prepare($db2conn, $SqlShell);
                       db2_execute($stmtShell);
                       while ($rowShell = db2_fetch_assoc($stmtShell)) { $ShellDip=$rowShell['SHELL']; }   
                       ?>
                       <SCRIPT>
                          $("#Icon<?php echo $ContaFlusso."_".$ContaDip; ?>").click(function(){
                                 $( "#pageAlbero" ).load('../PHP/Albero.php?INSH=<?php echo $ShellDip; ?>').show();
                          });
                       </SCRIPT>
                       <?php
                     } 
                     if ( "$Tipo" == "V" ) {
                         if ( "$Note" == "" ){ $Note="Validare Esito Elaborazione Precedente"; }
                         ?>
                         <div id="DescVal<?php echo $ContaFlusso."_".$ContaDip; ?>" class="DescValidazione" style="display:none;"><?php echo $Note; ?></div>
                         <script>
                            $("#Icon<?php echo $ContaFlusso."_".$ContaDip; ?>").mouseenter(function(){
                                   $("#DescVal<?php echo $ContaFlusso."_".$ContaDip; ?>").show();    
                                });
                            $("#Icon<?php echo $ContaFlusso."_".$ContaDip; ?>").mouseleave(function(){
                                   $("#DescVal<?php echo $ContaFlusso."_".$ContaDip; ?>").hide();    
                                });                             
                         </script>
                         <?php 
                     } ?>
                 </div>
                 <?php             
				 $SvegliaNasc="nascondi";
                 if ( "$InCoda" == "S"  ) { $SvegliaNasc=""; }
			     ?>
			     <img id="Clock<?php echo $Flusso.$Dipendenza; ?>" class="ImgDipSveglia <?php echo $SvegliaNasc; ?>" src="../images/Sveglia.png" >
				   <script>
				  	$("#Clock<?php echo $Flusso.$Dipendenza; ?>").click(function(){
				  		$("#RicFlusso").val("<?php echo $Flusso; ?>");                           
				  		$("#RicDipendenza").val("<?php echo $Dipendenza; ?>");                          
				  		$("#RicTipo").val("<?php echo $Tipo; ?>");  
				  		$("#RicAzione").val("<?php echo $Tipo; ?>");
				  		$('#FormCaric').submit();
				  	});
				   </script>                 
			     <?php
                 if ( $InCoda <> "S" AND $Abilita == true ) {
                    ?><label id="Collapse<?php echo $ContaFlusso."_".$ContaDip; ?>" class="ImgPiu" >+</label><?php 
                 }
                 ?>
                 <div id="DNomeDip<?php echo $ContaFlusso."_".$ContaDip; ?>" class="NomeDip" ><?php 
                     echo $ImgUtente;
                     $qNum=$Liv;
                     while ( $qNum > 0 ){
                        $qNum=$qNum-1;
                        ?><span class="ImgLiv" ></span><?php
                     }
                     for ( $y=0; $y<=$numFD; $y++){
                        if ( "$ArrayFD[$y]" == "$Dipendenza" ){
                           ?><img class="ImgDettFileDisp" src="../images/File.png" ><?php
                        }
                     }                   
                     ?>
                     <font style="margin-left:3px;" ><?php echo $Dipendenza; ?></font>
                     <?php
                     if ( $RModeOn == 'S' ) {
                         ?><img class="ImgFlag" src="../images/bandiera.png" ><?php 
                     } 
                     ?>
                 </div>
                 <?php 
                 if ( ! $Abilita ) {  ?>
                     <div id="DetGruppo<?php echo $ContaFlusso."_".$ContaDip; ?>" class="DetUteGroup" ><?php echo $ListaGruppi; ?></div>
                     <script>
                       $("#ImgUte<?php echo $ContaFlusso."_".$ContaDip; ?>").click(function () {
                           $("#DetGruppo<?php echo $ContaFlusso."_".$ContaDip; ?>").toggle();
                       });  
                       $("#DetGruppo<?php echo $ContaFlusso."_".$ContaDip; ?>").click(function () {
                           $("#DetGruppo<?php echo $ContaFlusso."_".$ContaDip; ?>").toggle();
                       });  
                     </script><?php 
                 } 
				 
                 ?>
                 <div id="StatoDip<?php echo $Flusso.$Dipendenza; ?>" class="StatoDip" ><?php echo $ImgDip.$ImgSval; ?></div>
                 <div id="Note<?php echo $ContaFlusso."_".$ContaDip; ?>" class="Note nascondi" ><text><?php echo $Dipendenza.':<bR>'.preg_replace("/\r\n|\r|\n/",'<br/>',$Note); ?></text></div>
               </div>
               <div id="D<?php echo $ContaFlusso."_".$ContaDip; ?>" class="Dett nascondi">
                         <?php 
                         if ( "$DipEsito" == "F" || "$DipEsito" == "E" || "$DipEsito" == "C" ) {
                             $Altezza=$Altezza+63;
                             $UteLancio="";
                             $SqlUteLancio="select UTENTE from WFS.CODA_STORICO where FLUSSO='$Flusso' AND DIPENDENZA='$Dipendenza' AND INIZIO='$Inizio' AND FINE='$Fine' ";
                             $stmtUteLancio=db2_prepare($db2conn, $SqlUteLancio);
                             db2_execute($stmtUteLancio);
                             while ($rowUteLancio = db2_fetch_assoc($stmtUteLancio)) {
                                $UteLancio=$rowUteLancio['UTENTE']; 
                             } 
                             if ( "$UteLancio" == "" ){ $UteLancio="Lancio Macchina"; }
                             ?>
                             <div id="UteLancio"><B>User: </B><?php echo $UteLancio; ?></div>                     
                             <div id="DataInz"><B>Start: </B><?php echo $Inizio; ?></div>
                             <div id="DataFine"><B>End: </B><?php echo $Fine; ?></div>
                             <?php 
                         }
                         if ( "$Tipo" == 'C') {
                              $Altezza=$Altezza+50;
                              $LastFile="";
                              $SqlLastFile="SELECT LASTFILE from WFS.CARICAMENTI where CARICAMENTO='$Dipendenza' ";
                              $stmtLastFile=db2_prepare($db2conn, $SqlLastFile);
                              db2_execute($stmtLastFile);
                              while ($rowLastFile = db2_fetch_assoc($stmtLastFile)) { $LastFile=$rowLastFile['LASTFILE']; }                               
                              ?><div id="LastFile"><B>Last File: </B><?php echo $LastFile; ?></div><?php
                         }
                         if ( "$Tipo" == 'E' ) {?>
                         <div id="LogElab<?php echo $ContaFlusso."_".$ContaDip; ?>" class="Log" >
                           <a href="../PHP/ApriLogElab.php?Elab=<?php echo $Dipendenza; ?>" target="_blank"><img src="../images/Log.png" style="width:100%;" ></a>
                         </div><?php 
                         }
                         
                         if ( "$DipEsito" <> 'N' AND "$Log" <> "") {?>
                         <div id="Log<?php echo $ContaFlusso."_".$ContaDip; ?>" class="Log" >
                           <a href="../PHP/ApriLog.php?LOG=<?php echo $Log; ?>&ESITO=<?php echo $DipEsito; ?>" target="_blank"><img src="../images/Log.png" style="width:100%;" ></a>
                         </div>
                         <?php 
                         }                     
                         if ( "$ReadMode" == "N" AND ! $FlowReadMode ) {
                         ?>
                         <div id="DivPulsante" >
                         <?php
                         
                         switch ( $Tipo ) {
                            case "C":
                                if ( $Batch != "S" ) {
                                    if ( $DipEsito == "N" or $DipEsito == "E" ) {
                                        $Altezza=$Altezza+140;
                                        ?>
                                        <div id="Carica">
                                           <input type="hidden" name="action" value="upload" />
                                           <label>Load File:</label>
                                           <input type="file" name="UploadFileName_<?php echo ${Flusso}."_".${Dipendenza}; ?>" />                                   
                                           <input name="PulCaricaFile" id="PulCaricaFile<?php echo $ContaFlusso."_".$ContaDip; ?>" value="Load" type="submit" class="Bottone" />
                                           <script>
                                             $("#PulCaricaFile<?php echo $ContaFlusso."_".$ContaDip; ?>").click(function () {
                                            
                                                var input = $("<input>")
                                                .attr("type", "hidden")
                                                .attr("name", "DipendenzaC")
                                                .val("<?php echo $Dipendenza; ?>");
                                                $('#FormCaric').append($(input));

                                                var input = $("<input>")
                                                .attr("type", "hidden")
                                                .attr("name", "FlussoC")
                                                .val("<?php echo $Flusso; ?>");
                                                $('#FormCaric').append($(input));      

                                                var input = $("<input>")
                                                .attr("type", "hidden")
                                                .attr("name", "TipoC")
                                                .val("<?php echo $Tipo; ?>");
                                                $('#FormCaric').append($(input));     
                                                
                                             });
                                           </script>
                                        </div>                        
                                        <?php                           
                                    } else {
                                        $Altezza=$Altezza+90;
                                        $CountInCorso=0;
                                        $SqlContaSql="select count(*) CONTA from WFS.FLUSSI where ESITO = 'C' ";
                                        $stmtContaSql=db2_prepare($db2conn, $SqlContaSql);
                                        db2_execute($stmtContaSql);
                                        while ($rowContaSql = db2_fetch_assoc($stmtContaSql)) {$CountInCorso=$rowContaSql['CONTA'];}
                                        if ( $CountInCorso == 0 ) {
                                        ?>                  
                                          <label class="Conferma" id="Puls_Reset<?php echo $ContaFlusso."_".$ContaDip; ?>" >Invalidate</label>
                                          <script>
                                             $('#Puls_Reset<?php echo $ContaFlusso."_".$ContaDip; ?>').click(function(){
                                             if (confirm('Are you sure you want to invalidate this launch?')) {
                                             
                                                var input = $("<input>")
                                                .attr("type", "hidden")
                                                .attr("name", "PulResetta")
                                                .val("Invalida");
                                                $('#FormCaric').append($(input));                                                    
                                             
                                                var input = $("<input>")
                                                .attr("type", "hidden")
                                                .attr("name", "DipendenzaS")
                                                .val("<?php echo $Dipendenza; ?>");
                                                $('#FormCaric').append($(input));

                                                var input = $("<input>")
                                                .attr("type", "hidden")
                                                .attr("name", "FlussoS")
                                                .val("<?php echo $Flusso; ?>");
                                                $('#FormCaric').append($(input));   

                                                var input = $("<input>")
                                                .attr("type", "hidden")
                                                .attr("name", "TipoS")
                                                .val("<?php echo $Tipo; ?>");
                                                $('#FormCaric').append($(input));                                        
                                                
                                                $("#Waiting").show();
                                                $('#FormCaric').submit();
                                             } 
                                             });
                                           </script>                                            
                                        <?php 
                                        }                               
                                    }
                                } else {																	
                                   $Altezza=$Altezza+30; 
                                }
                                if ( $DipEsito <> "F" AND $DipEsito <> "C" AND $DipEsito <> "E" AND $Rinnovabile == "S" ) {
                                  $Altezza=$Altezza+30; 
                                  ?>
                                  <label class="Valida" id="Pul_Valida<?php echo $ContaFlusso."_".$ContaDip; ?>" >Validate Existing Data</label>
                                  <script>
                                   $('#Pul_Valida<?php echo $ContaFlusso."_".$ContaDip; ?>').click(function(){
                                    var v = confirm('Are you sure you want to validate existing data?');
                                    if (v == true) {
                                    
                                        var input = $("<input>")
                                        .attr("type", "hidden")
                                        .attr("name", "PulValidaDati")
                                        .val("Valida Dati Esistenti");
                                        $('#FormCaric').append($(input));                                   
                                    
                                        var input = $("<input>")
                                        .attr("type", "hidden")
                                        .attr("name", "DipendenzaVD")
                                        .val("<?php echo $Dipendenza; ?>");
                                        $('#FormCaric').append($(input));

                                        var input = $("<input>")
                                        .attr("type", "hidden")
                                        .attr("name", "FlussoVD")
                                        .val("<?php echo $Flusso; ?>");
                                        $('#FormCaric').append($(input));                   

                                        var input = $("<input>")
                                        .attr("type", "hidden")
                                        .attr("name", "TipoVD")
                                        .val("<?php echo $Tipo; ?>");
                                        $('#FormCaric').append($(input));  

                                        $("#Waiting").show();
                                        $('#FormCaric').submit();
                                    } 
                                   });
                                  </script>                           
                                  <?php 
                                }
                                break;
                            case "V":
                                $Altezza=$Altezza+40;
                                if ( $DipEsito <> "F" ) {
                                  $Altezza=$Altezza+40;
                                  ?>
                                  <label class="Valida"  id="Puls_Valida<?php echo $ContaFlusso."_".$ContaDip; ?>" >Validate</label>
                                  <script>
                                     $('#Puls_Valida<?php echo $ContaFlusso."_".$ContaDip; ?>').click(function(){
                                      var pv = confirm('Are you sure you want to validate this?');
                                      if (pv == true) {         

                                        var input = $("<input>")
                                        .attr("type", "hidden")
                                        .attr("name", "PulValida")
                                        .val("Valida");
                                        $('#FormCaric').append($(input));   
                                      
                                        var input = $("<input>")
                                        .attr("type", "hidden")
                                        .attr("name", "DipendenzaV")
                                        .val("<?php echo $Dipendenza; ?>");
                                        $('#FormCaric').append($(input));

                                        var input = $("<input>")
                                        .attr("type", "hidden")
                                        .attr("name", "FlussoV")
                                        .val("<?php echo $Flusso; ?>");
                                        $('#FormCaric').append($(input));   

                                        var input = $("<input>")
                                        .attr("type", "hidden")
                                        .attr("name", "TipoV")
                                        .val("<?php echo $Tipo; ?>");
                                        $('#FormCaric').append($(input));                                   
                                                                                
                                        $("#Waiting").show(); 
                                        $('#FormCaric').submit();                                       
                                      } 
                                     });
                                  </script>                           
                                  <?php 
                                }                               
                                break;
                            case "E":
                                $Altezza=$Altezza+40;
                                if ( $DipEsito == "N" ) {
                                  $Altezza=$Altezza+40;
                                  ?>
                                  <label class="Valida"   id="Puls_AvviaElab<?php echo $ContaFlusso."_".$ContaDip; ?>" >Start Processing</label>
                                  <script>
                                     $('#Puls_AvviaElab<?php echo $ContaFlusso."_".$ContaDip; ?>').click(function(){
                                      var va = confirm('Are you sure you want to start processing this step?');
                                      if ( va == true) {    

                                          var input = $("<input>")
                                          .attr("type", "hidden")
                                          .attr("name", "PulElaborazione")
                                          .val("Avvia Elaborazione");
                                          $('#FormCaric').append($(input)); 
                                        
                                          var input = $("<input>")
                                          .attr("type", "hidden")
                                          .attr("name", "DipendenzaE")
                                          .val("<?php echo $Dipendenza; ?>");
                                          $('#FormCaric').append($(input));
     
                                          var input = $("<input>")
                                          .attr("type", "hidden")
                                          .attr("name", "FlussoE")
                                          .val("<?php echo $Flusso; ?>");
                                          $('#FormCaric').append($(input)); 

                                          var input = $("<input>")
                                          .attr("type", "hidden")
                                          .attr("name", "TipoE")
                                          .val("<?php echo $Tipo; ?>");
                                          $('#FormCaric').append($(input));  

                                          $("#Waiting").show();
                                          $('#FormCaric').submit();
                                      } 
                                     });
                                  </script>                           
                                  <?php 
                                }   
                                if ( $DipEsito == "E" ||  $DipEsito == "F"  ) {
                                        $Altezza=$Altezza+40;
                                        $SvalidaElab="";
                                        $SqlSvalSql="select SVALIDA from WFS.ELABORAZIONI where  ELABORAZIONE = '$Dipendenza' ";
                                        $stmtSvalSql=db2_prepare($db2conn, $SqlSvalSql);
                                        db2_execute($stmtSvalSql);
                                        while ($rowSvalSql = db2_fetch_assoc($stmtSvalSql)) { 
                                           $SvalidaElab=$rowSvalSql['SVALIDA']; 
                                        }
                                        if ( "$SvalidaElab" == "S" ) {
                                          ?>                  
                                          <label class="Conferma"  id="Puls_ResetE<?php echo $ContaFlusso."_".$ContaDip; ?>" >Invalidate</label>
                                          <input  type="text" name="PulResettaE" value=""  id="PulsResetE<?php echo $ContaFlusso."_".$ContaDip; ?>" class="nascondi" hidden />
                                           <script>
                                             $('#Puls_ResetE<?php echo $ContaFlusso."_".$ContaDip; ?>').click(function(){
                                              var re = confirm('Are you sure you want to invalidate this launch?');
                                              if ( re == true) {    

                                                var input = $("<input>")
                                                .attr("type", "hidden")
                                                .attr("name", "PulResettaE")
                                                .val("Invalida");
                                                $('#FormCaric').append($(input));   
                                              
                                                var input = $("<input>")
                                                .attr("type", "hidden")
                                                .attr("name", "DipendenzaS")
                                                .val("<?php echo $Dipendenza; ?>");
                                                $('#FormCaric').append($(input));

                                                var input = $("<input>")
                                                .attr("type", "hidden")
                                                .attr("name", "FlussoS")
                                                .val("<?php echo $Flusso; ?>");
                                                $('#FormCaric').append($(input));   
                                                
                                                var input = $("<input>")
                                                .attr("type", "hidden")
                                                .attr("name", "TipoS")
                                                .val("<?php echo $Tipo; ?>");
                                                $('#FormCaric').append($(input));  
                                                
                                                $('#PulsResetE<?php echo $ContaFlusso."_".$ContaDip; ?>').val('Invalida');
                                                $("#Waiting").show();
                                                $('#FormCaric').submit();
                                             } 
                                             });
                                           </script>                                            
                                           <?php 
                                        }                                   
                                }                                   
                                break;
                            case "F":
                                 break;
                         }
                         ?>  
                       </div> 
                       <?php                    
                         // Chiudo Test ReadMode
                         }
                        ?>
                     </div>
               <?php 
               if ( $Abilita ) {
               ?>
               <script>  
                   $("#DNomeDip<?php echo $ContaFlusso."_".$ContaDip; ?>").click(function() {  
                     if ( $("#D<?php echo $ContaFlusso."_".$ContaDip; ?>").hasClass("nascondi") ){
                       $("#D<?php echo $ContaFlusso."_".$ContaDip; ?>").height('<?php echo $Altezza; ?>px');
                       $("#D<?php echo $ContaFlusso."_".$ContaDip; ?>").removeClass("nascondi");
                       $("#Collapse<?php echo $ContaFlusso."_".$ContaDip; ?>").text("-");
                     } else {  
                       $("#D<?php echo $ContaFlusso."_".$ContaDip; ?>").height('25px');                       
                       $("#D<?php echo $ContaFlusso."_".$ContaDip; ?>").addClass("nascondi");
                       $("#Collapse<?php echo $ContaFlusso."_".$ContaDip; ?>").text("+");
                     }
                   }); 
                   
                   $("#StatoDip<?php echo $Flusso.$Dipendenza; ?>").click(function(){
                      $("#Note<?php echo $ContaFlusso."_".$ContaDip; ?>").toggleClass("nascondi");
                   });  
                   $("#Note<?php echo $ContaFlusso."_".$ContaDip; ?>").click(function(){
                      $(this).toggleClass("nascondi");
                   });                    
               </script>           
               <?php
               }
            }    
            ?>
            </div>
            <script>
               function HidePre<?php echo $Flusso; ?>(){
                   <?php
                      $SqlElencoDip="select DIPENDENZA from WFS.LEGAME_FLUSSI where FLUSSO = '$Flusso' AND TIPO = 'F'";
                      $stmtElencoDip=db2_prepare($db2conn, $SqlElencoDip);
                      db2_execute($stmtElencoDip);
                      while ($rowElencoDip = db2_fetch_assoc($stmtElencoDip)) {
                          $Dip=$rowElencoDip['DIPENDENZA'];
                          ?>$("#<?php echo $Dip; ?>").removeClass("PreDipende");<?php
                      }                
                    ?>
               }
            
               function ShowPre<?php echo $Flusso; ?>(){
                   <?php
                      $SqlElencoDip="select DIPENDENZA from WFS.LEGAME_FLUSSI where FLUSSO = '$Flusso' AND TIPO = 'F'";
                      $stmtElencoDip=db2_prepare($db2conn, $SqlElencoDip);
                      db2_execute($stmtElencoDip);
                      while ($rowElencoDip = db2_fetch_assoc($stmtElencoDip)) {
                          $Dip=$rowElencoDip['DIPENDENZA'];
                          ?>$("#<?php echo $Dip; ?>").addClass("PreDipende");<?php
                      }                
                    ?>                             
               }
               
               $("#<?php echo $Flusso; ?>").click(function() {  
                 if ( $("#<?php echo "Dett".$Flusso; ?>").hasClass("nascondi") ){    
                   $(".DettFlusso").addClass("nascondi");
                   $("#<?php echo $Flusso; ?>").removeClass("Selex");
                   $("#Legenda").addClass("nascondi");             
                   $(".Flusso").removeClass('Selezione');
                   $("#<?php echo "Dett".$Flusso; ?>").removeClass("nascondi");
                   $("#<?php echo $Flusso; ?>").addClass("Selezione");
                   $('#FlussoSelez').val("<?php echo $Flusso; ?>");                
                 } else {    
                   $(".DettFlusso").addClass("nascondi"); 
                   $("#Legenda").removeClass("nascondi");
                   $('#FlussoSelez').val('');                  
                   $("#<?php echo "Dett".$Flusso; ?>").addClass("nascondi");
                   $("#<?php echo $Flusso; ?>").removeClass("Selezione");            
                 }
               });  
               
               $("#<?php echo $Flusso; ?>").mouseover(function() {  
                    if ( !$("#<?php echo $Flusso; ?>").hasClass("Selezione") ){
                      $("#<?php echo $Flusso; ?>").addClass("Selex");
                    }
                    $(".<?php echo $Flusso; ?>").addClass("Dipende");
                    ShowPre<?php echo $Flusso; ?>();                   
               });             
               $("#<?php echo $Flusso; ?>").mouseleave(function() {  
                    $("#<?php echo $Flusso; ?>").removeClass("Selex");
                    $(".<?php echo $Flusso; ?>").removeClass("Dipende");
                    HidePre<?php echo $Flusso; ?>();                 
               });                 
           </script>
            <?php
         }
    }
    ?>
    </div>
    </div>
    <div id="pageAlbero" ></div>
    <div id="pageEsito" ></div>
    <div id="PulMostraCoda" ><img id="ElabInCoda" src="../images/Loading.gif" style="height:18px;width:18px;float:left;display:none;"><p id="FraseCoda"></p></div>
    <div id="MostraCoda" class="nascondi" ></div>
    </form> 
    <div id="countdown">
        <p class="minutes">00</p>:<p class="seconds">00</p>
    </div>  
    <script>  
      
      $("#pageAlbero").dblclick(function(){
         $("#pageAlbero").hide();          
      });
        
      function ResizeAltezze(){
              var vFooter = $("#footer").offset();
              var vAltezza = vFooter.top - 220;
              $(".DettFlusso").css('max-height', vAltezza+'px');
              var vAlto  = $("#AreaPreFlussi").offset()
              var vAltezza = vFooter.top - vAlto.top - 10 ;
              $("#AreaPreFlussi").css('height', vAltezza+'px');   
      }
      
      function LoadDiv(){
            $( "#MostraCoda" ).load('../PHP/RefreshCoda.php?'+
			  'WorkFlow=<?php echo "$WorkFlow"; ?>');    
            $( "#pageEsito" ).load('../PHP/CheckComplete.php?'+
			  'Flusso=<?php echo "$RicFlusso"; ?>&'+
			  'Dipendenza=<?php echo "$RicDipendenza"; ?>&'+
			  'Tipo=<?php echo "$RicTipo"; ?>&'+
			  'Azione=<?php echo "$RicAzione"; ?>');
      }
    
      setInterval(function(){ LoadDiv(); }, 5000);     
      
        function  CheckVista(){ 
            if ( $("#NascPrio").prop('checked') == false ){
              $('.ImgLiv').show();
            } else {
              $('.ImgLiv').hide();
            }       
            if ( $("#CheckACapo").prop('checked') == false ){
              $(".ACapo").hide();
              $('.LivOneShot').css('width','auto');
              $('.Livello').css('width','auto');                      
            } else {
              $(".ACapo").show(); 
              $('.LivOneShot').css('width','98%');
              $('.Livello').css('width','98%');               
            }             
            if ( $("#CheckOneShot").prop('checked') == true ){
              $('.LivOneShot').show();
            } else {            
              $('.LivOneShot').hide();           
            }           
            if ( $("#Filtra").prop('checked') == true ){
              $('.NonMostrare').hide();
            } else {            
              $('.NonMostrare').show();
              $('.Esex').show();              
            }                                
            if ( $("#Filtra").prop('checked') == true ){
              $('.Esex').hide();
            } 
        }
    
        $("#Filtra").on('change',function() {  
           CheckVista();
        }); 
        
        $("#NascPrio").on('change',function() {  
           CheckVista();
        }); 
        
        $("#CheckACapo").on('change',function() {  
           CheckVista();
        });
        
        $("#CheckOneShot").on('change',function() {  
           CheckVista();
        });
        
        $("#FLogici").on('change',function() {  
           CheckVista();
        });    

        $("#FSito").on('change',function() {    
           CheckVista();   
        });   
		
        CheckVista();

        $("#PulMostraCoda").click(function() {   
            $("#MostraCoda").toggleClass("nascondi");
        });     
        
        $( "#OrdinaPer" ).on('change',function() {
          $( "#FormCaric" ).submit();
        });

         $("#ConfElab").click(function() { 
           $("#Strato").toggleClass("nascondi");         
           var input = $("<input>")
           .attr("type", "hidden")
           .attr("name", "PulsConfermoElab")
           .val("Confermo Elaborazione");
           $('#FormCaric').append($(input));
           $('#FormCaric').submit();             
        }); 
 
        $("#ChiudiStrato").click(function() {   
            $("#Strato").toggleClass("nascondi");
        });
        
        $("#EsitoUpload").click(function() {   
            $("#EsitoUpload").toggleClass("nascondi");
        });
        
        $("#FormCaric").submit(function() {   
            $("#Waiting").show();
        }); 
        
        $("#FormUpload").submit(function() {   
            $("#Waiting").show();
        });     
                
        $("#pageEsito").click(function() {   
           $('#FormCaric').submit();
        }); 
                
       
        $('#MioAmb').click(function(){ 
            
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "DefWfs")
            .val($("#WorkFlow").val());         
            $('#FormCaric').append($(input)); 
            
            $("#Waiting").show();
            $('#FormCaric').submit();       
        });
        
        $(window ).resize(function(){
          ResizeAltezze();
        });
        
    </script>        
    <script>        
  $( document ).ready(function() {
            
            (function (e) {
              e.fn.countdown = function (t, n) {
                  function i() {              
                    eventDate = Date.parse(r.date) / 1e3;
                    currentDate = Math.floor(e.now() / 1e3);        
                    seconds = eventDate - currentDate;
                    days = Math.floor(seconds / 86400);
                    seconds -= days * 60 * 60 * 24;
                    hours = Math.floor(seconds / 3600);
                    seconds -= hours * 60 * 60;
                    minutes = Math.floor(seconds / 60);
                    seconds -= minutes * 60;
                    if ( minutes == 0 && seconds == 0 ){ $('#FormCaric').submit();  }           
                    if (r["format"] == "on") {              
                      days = String(days).length >= 2 ? days : "0" + days;
                      hours = String(hours).length >= 2 ? hours : "0" + hours;
                      minutes = String(minutes).length >= 2 ? minutes : "0" + minutes;
                      seconds = String(seconds).length >= 2 ? seconds : "0" + seconds;
                    }
                    if (!isNaN(eventDate)) {
                      thisEl.find(".days").text(days);
                      thisEl.find(".hours").text(hours);
                      thisEl.find(".minutes").text(minutes);
                      thisEl.find(".seconds").text(seconds);
                    } else {
                      alert("Invalid date. Example: 30 Mounth 2013 15:50:00");
                      clearInterval(interval)
                    }       
                  }
                  var thisEl = e(this);
                  var r = {
                    date: null,
                    format: null
                  };
                  t && e.extend(r, t);
                  i();            
                  interval = setInterval(i, 1e3);         
              }  
              })(jQuery);

      
              function e() {
                var e = new Date;
                e.setDate(e.getDate() + 60);
                dd = e.getDate();
                mm = e.getMonth() + 1;
                y = e.getFullYear();
                futureFormattedDate = mm + "/" + dd + "/" + y;
                return futureFormattedDate
              }
              
              $("#countdown").countdown({
                date: "<?php 
				echo date("Y-m-d H:i:s", (strtoftime(Date()) + 300 ));
				?>", 
                format: "on"
              });
            
              LoadDiv();
          
              if ( "<?php echo $FlussoSelez; ?>" != "" ) {
                //$("#MostraCoda").addClass("nascondi"); 
                $("#Legenda").addClass("nascondi"); 
                
                $("#<?php echo $FlussoSelez; ?>").addClass("Selezione");
                $("#Dett<?php echo $FlussoSelez; ?>").removeClass("nascondi");
              }

              CheckVista();   

              ResizeAltezze();
          
        });          
    </script>   
    <div id="Legenda" >
      <div id="TitoloLeg" ><B>LEGEND</B></div>     
      <div class="Legenda" >
          <div id="LegBloccato"  class="LegColore" ><img id="LegBlocImg" src="../images/Carica.png" ></div>
          <div id="DescBloccato"  class="LegDescr" >Loading</div>
      </div>
      <div class="Legenda" >
          <div id="LegBloccato"  class="LegColore" ><img id="LegBlocImg" src="../images/Valida.png" ></div>
          <div id="DescBloccato"  class="LegDescr" >Validation</div>
      </div>
      <div class="Legenda" >
          <div id="LegBloccato"  class="LegColore" ><img id="LegBlocImg" src="../images/Flusso.png" ></div>
          <div id="DescBloccato"  class="LegDescr" >Flow</div>
      </div>    
      <div class="Legenda" >
          <div id="LegBloccato"  class="LegColore" ><img id="LegBlocImg" src="../images/Elaborazione.png" ></div>
          <div id="DescBloccato"  class="LegDescr" >Processing</div>
      </div>          
      <div class="Legenda" >
          <div id="LegBloccato"  class="LegColore" ><img id="LegBlocImg" src="../images/InMacchina.png" ></div>
          <div id="DescBloccato"  class="LegDescr" >Batch</div>
      </div> 
      <div class="Legenda" >
          <div id="LegBloccato"  class="LegColore" ><img id="LegBlocImg" src="../images/Sveglia.png" ></div>
          <div id="DescBloccato"  class="LegDescr" >In Queue</div>
      </div>        
	  <?php /*
      <div class="Legenda" >
          <div id="LegBloccato"  class="LegColore" ><img id="LegBlocImg" src="../images/Utente.png" ></div>
          <div id="DescBloccato"  class="LegDescr" >Altro Utente</div>
      </div>
      <div class="Legenda" >
          <div id="LegBloccato"  class="LegColore" ><img id="LegBlocImg" src="../images/Divieto.png" ></div>
          <div id="DescBloccato"  class="LegDescr" >Altra Comp.</div>
      </div>  
	  */ ?>     
      <div class="Legenda " style="border: 2px solid blue;" >
          <div id="LegBloccato"  class="LegColore" ></div>
          <div id="DescBloccato"  class="LegDescr" >Prec.Step</div>
      </div> 
      <div class="Legenda" style="border: 2px solid red;" >
          <div id="LegBloccato"  class="LegColore" ></div>
          <div id="DescBloccato"  class="LegDescr" >Future Step</div>
      </div> 
      <div class="Legenda ElabPos" >
          <div id="LegBloccato"  class="LegColore" ></div>
          <div id="DescBloccato"  class="LegDescr" >Elab.Poss.</div>
      </div>    
      <div class="Legenda" >
          <div id="LegBloccato"  class="LegColore" ><img id="LegBlocImg" src="../images/Rinnovato.png" ></div>
          <div id="DescBloccato"  class="LegDescr" >Data Val.</div>
      </div>
      <div class="Legenda" >
          <div id="LegBloccato"  class="LegColore" ><img id="LegBlocImg" src="../images/bandiera.png" ></div>
          <div id="DescBloccato"  class="LegDescr" >ReadMode</div>
      </div> 	  
    </div>

    <?php   
} 
?>
