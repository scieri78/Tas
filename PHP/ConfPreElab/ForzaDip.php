
<style>

.Err{
    background:#ff4040 !important;
}
.Run{
    background:yellow !important;
}
.Com{
    background:#27ff27 !important;
}
.War{
    background:orange !important;
}

.OldRun th{
    background: lightgray !important;
    width:110px;
}
.LastRun th{
    width:110px;
}

.progress {
    margin-bottom: 0px;
    border: 1px solid white;
    border-radius: 7px;
    height: 20px;
}
</style>
<?php
if ( "$find" == "1" )  {
 

 
   if ( "$IdWorkFlow" == "" or "$IdProcess" == ""  ) { exit; }


  //UTENTI
    $ArrUsers=array();
    $SqlList="SELECT NOMINATIVO, USERNAME FROM WEB.TAS_UTENTI";
    $stmt=db2_prepare($conn, $SqlList);
    $res=db2_execute($stmt);
    if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
    while ($row = db2_fetch_assoc($stmt)) {
        $TNom=$row['NOMINATIVO']; 
        $TUserN=$row['USERNAME']; 
            
        array_push($ArrUsers,array($TNom,$TUserN));
        $ListIdStato=$ListIdStato.",".$Id;
    }

   
   $IdFlu=$_POST['IdFlu'];  
   $NomeFlusso=$_POST['Flusso'];
   $DescFlusso=$_POST['DescFlusso'];

    $BarraCaricamento = "rgb(21, 140, 240)";
    $BarraPeggio = "rgb(165, 108, 185)";
    $BarraMeglio = "rgb(104, 162, 111)";
    
   //LEGAMI
   $SqlList="SELECT 
    (   SELECT FLUSSO FROM  WFS.FLUSSI  WHERE ID_FLU = L.ID_FLU ) FLUSSO,
   ID_LEGAME,PRIORITA,L.TIPO,L.ID_DIP,
   CASE L.TIPO 
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
        WHEN 'L' THEN
          ( SELECT TARGET FROM WFS.LINKS WHERE ID_LINK = L.ID_DIP )
        WHEN 'E' THEN
          null
        WHEN 'V' THEN
          null
    END AS TARGET
    ,CASE L.TIPO 
        WHEN 'F' THEN
           null
        WHEN 'C' THEN
          null
        WHEN 'L' THEN
          ( SELECT TIPO FROM WFS.LINKS WHERE ID_LINK = L.ID_DIP )
        WHEN 'E' THEN
          null
        WHEN 'V' THEN
          null
    END AS LINK_TIPO    
    ,CASE L.TIPO 
        WHEN 'F' THEN
           null
        WHEN 'C' THEN
          null
        WHEN 'L' THEN
          null
        WHEN 'E' THEN
          ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )
        WHEN 'V' THEN
          null
    END AS ELAB_IDSH        
    ,CASE L.TIPO 
        WHEN 'F' THEN
           null
        WHEN 'C' THEN
          null
        WHEN 'L' THEN
          null
        WHEN 'E' THEN
          ( SELECT TAGS FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )
        WHEN 'V' THEN
          null
    END AS ELAB_TAGS    
    ,CASE L.TIPO 
        WHEN 'F' THEN
           null
        WHEN 'C' THEN
          null
        WHEN 'L' THEN
          null
        WHEN 'E' THEN
          ( SELECT READONLY FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )
        WHEN 'V' THEN
          null
    END AS READONLY     
    ,CASE L.TIPO 
        WHEN 'F' THEN
           null
        WHEN 'C' THEN
          null
        WHEN 'L' THEN
          null
        WHEN 'E' THEN
          ( SELECT BLOCKWFS FROM WORK_CORE.CORE_SH_ANAG WHERE ID_SH IN ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )  )
        WHEN 'V' THEN
          null
    END AS BLOCKWFS 
    ,CASE L.TIPO 
        WHEN 'F' THEN
           null
        WHEN 'C' THEN
          null
        WHEN 'L' THEN
          null
        WHEN 'E' THEN
          null
        WHEN 'V' THEN
          ( SELECT EXTERNAL FROM WFS.VALIDAZIONI V WHERE V.ID_VAL = L.ID_DIP )
    END AS EXTERNAL     
    ,US.UTENTE
    ,TO_CHAR(US.INIZIO,'YYYY-MM-DD HH24:MI:SS') INIZIO
    ,TO_CHAR(US.FINE,'YYYY-MM-DD HH24:MI:SS')   FINE
    ,timestampdiff(2,NVL(US.FINE,CURRENT_TIMESTAMP)-US.INIZIO) DIFF 
    ,NVL(US.ESITO,'N') AS ESITO
    ,US.NOTE
    ,US.LOG
    ,US.FILE
    ,( SELECT count(*) FROM WFS.CODA_RICHIESTE WHERE ID_WORKFLOW = L.ID_WORKFLOW AND ID_PROCESS  = $IdProcess AND ID_FLU = L.ID_FLU AND ID_DIP = L.ID_DIP AND TIPO = L.TIPO ) CODA
    ,( SELECT count(*) FROM WFS.AUTORIZZAZIONI WHERE ID_WORKFLOW = L.ID_WORKFLOW AND ID_FLUSSO = L.ID_FLU 
         AND ID_GRUPPO IN ( SELECT ID_GRUPPO FROM WFS.ASS_GRUPPO WHERE ID_UK = $Uk AND ID_WORKFLOW = $IdWorkFlow  )
         AND ID_GRUPPO = ( SELECT ID_GRUPPO FROM WFS.GRUPPI WHERE ID_WORKFLOW = L.ID_WORKFLOW AND GRUPPO = 'READ')       
    ) RDONLY
    ,( SELECT count(*) FROM WFS.AUTORIZZAZIONI WHERE ID_WORKFLOW = L.ID_WORKFLOW AND ID_FLUSSO = L.ID_FLU 
         AND ID_GRUPPO IN ( SELECT ID_GRUPPO FROM WFS.ASS_GRUPPO WHERE ID_UK = $Uk AND ID_WORKFLOW = $IdWorkFlow  )      
    ) PERMESSO
    ,( SELECT count(*) FROM WORK_CORE.ID_PROCESS WHERE ID_WORKFLOW = L.ID_WORKFLOW AND ID_PROCESS = $IdProcess AND READONLY = 'Y' ) WFSRDONLY
    , US.ID_RUN_SH  
    , US.WARNING
    ,( SELECT count(*) FROM WFS.AUTORIZZAZIONI_DIP WHERE ID_WORKFLOW = L.ID_WORKFLOW AND ID_FLU = L.ID_FLU 
         AND ID_GRUPPO IN ( SELECT ID_GRUPPO FROM WFS.ASS_GRUPPO WHERE ID_UK = $Uk  )    
         AND TIPO = L.TIPO
         AND ID_DIP = L.ID_DIP 
    ) RDONLYDIP 
    ,( SELECT count(*) FROM WFS.AUTORIZZAZIONI_DIP 
         WHERE ID_WORKFLOW = L.ID_WORKFLOW 
         AND ID_FLU = L.ID_FLU   
         AND TIPO = L.TIPO
         AND ID_DIP = L.ID_DIP  
    ) RDONLYFLUDIP 
   ,( SELECT count(*) FROM WORK_CORE.ID_PROCESS WHERE (ESER_ESAME,MESE_ESAME) IN ( SELECT ESER_ESAME,MESE_ESAME FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
     AND FLAG_CONSOLIDATO = 1 
    )  BLOCK_CONS	
  FROM 
        WFS.LEGAME_FLUSSI L
  LEFT JOIN
        WFS.ULTIMO_STATO US
  ON 1=1
        AND US.ID_PROCESS  = $IdProcess
        AND L.ID_WORKFLOW  = US.ID_WORKFLOW
        AND L.ID_FLU       = US.ID_FLU
        AND L.TIPO         = US.TIPO
        AND L.ID_DIP       = US.ID_DIP
        AND L.ID_WORKFLOW  = $IdWorkFlow 
   WHERE 1=1
        AND DECODE(L.VALIDITA,null,' $ProcMeseEsame ',' '||L.VALIDITA||' ') like '% $ProcMeseEsame %'
        AND L.ID_WORKFLOW  = $IdWorkFlow 
        AND L.TIPO NOT IN ( 'F', 'L', 'V' )
   ORDER BY FLUSSO, L.TIPO, DIPENDENZA";
   $stmt=db2_prepare($conn, $SqlList);
   $res=db2_execute($stmt);
   if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
   //echo $SqlList;
   
   ?><table class="ExcelTable"><?php
   while ($row = db2_fetch_assoc($stmt)) {
       $Flusso=$row['FLUSSO']; 
       $IdLegame=$row['ID_LEGAME']; 
       $Prio=$row['PRIORITA']; 
       $Tipo=$row['TIPO']; 
       $IdDip=$row['ID_DIP'];
       $DipName=$row['DIPENDENZA'];
       $DipDesc=$row['DESCR'];         
       $DipUtente=$row['UTENTE'];
       $DipIniz=$row['INIZIO'];
       $DipFine=$row['FINE'];
       $DipDiff=$row['DIFF'];
       $DipEsito=$row['ESITO'];
       $DipNote=$row['NOTE'];
       $DipLog=$row['LOG'];
       $DipFile=$row['FILE'];          
       $DipInCoda=$row['CODA'];
       $DipTarget=$row['TARGET'];
       $DipLinkTipo=$row['LINK_TIPO'];
       $DipElaIdSh=$row['ELAB_IDSH'];
       $DipElaTags=$row['ELAB_TAGS'];
       $RdOnly=$row['RDONLY']; 
       $Permesso=$row['PERMESSO'];
       $WfsRdOnly=$row['WFSRDONLY'];
       $IdRunSh=$row['ID_RUN_SH'];
       $BlockWfs=$row['BLOCKWFS'];
       $ReadOnlyWfs=$row['READONLY'];  
       $External=$row['EXTERNAL'];  
       $Warning=$row['WARNING'];  
           
       #Prev.RUN
       $OldIdRunSh=$row['OLD_ID_RUN_SH'];
       $OldInizio=$row['OLD_INIZIO'];
       $OldFine=$row['OLD_FINE'];
       $OldDiff=$row['OLD_DIFF'];
       $OldRunUser=$row['OLD_RUN_USER'];
       $OldLog=$row['OLD_LOG'];
       
       $DipCarConf=$row['CONFERMA_DATO'];
       
       $DipReadDip=$row['RDONLYDIP'];
       $DipReadFlu=$row['RDONLYFLUDIP'];
       $PeriodCons=$row['BLOCK_CONS'];
    
    
        $Blocca="";
        if( ( "$PeriodCons" == "0" )  ){
            $Blocca=" Bloccato"; 
        }
        
        switch ( $Tipo ) {
         case "C":
           $ImgTipo="Carica";
           break;
         case "F":
           $ImgTipo="Flusso";
           break;
         case "V":
           $ImgTipo="Valida";
           break;  
         case "E":
           $ImgTipo="Elaborazione";
           break;  
         case "L":
           $ImgTipo="Setting";
           if ( "$DipLinkTipo" == "E" ){
             $ImgTipo="Link";
           }
           break;  
       }
   
       $ImgDip='../images/Attesa.png';    
       if ( $DipEsito == 'N' AND  $Blocca == "" AND $Tipo <> 'F' ) {
          $ImgDip='../images/Eseguibile.png';
       } 
       
         $classDipendenza="";
         switch ($DipEsito) {
           case "E": 
             $classDipendenza="Terminato";
             $ImgDip='../images/KO.png';
             break;
           case "F": 
             $classDipendenza="Eseguito";
             $ImgDip='../images/OK.png'; 
             if ( "$DipNote" == "Confermo il dato in tabella" ){ $ImgDip='../images/ConfermoDato.png';  }
             $Abilita=true;
             break;
           case "C":               
             $classDipendenza="InEsecuzione";
             $ImgDip='../images/Loading.gif';
             break;  
           case "W":               
             $classDipendenza="InEsecuzione";
             $ImgDip='../images/Warning.png';
             break;                      
           default:
             null;
         }      
     
       if ( $DipEsito != 'F' AND $DipEsito != 'W' AND $Tipo == 'F' ) {
         $ImgDip='../images/Attesa.png';    
       }
     
     
       $Bloccato='N';
       $RdDipOnly='N';
       if ( "$Blocca" != "" ){ $Bloccato='Y'; }
       if ( $DipInCoda != 0 ){ $Bloccato='Y'; }
       if ( $RdOnly != 0 ){ $Bloccato='Y'; }
       if ( $Permesso == 0 ){ $Bloccato='Y'; }
       if ( $WfsRdOnly != 0 ){ $Bloccato='Y'; }
       if ( $DipReadFlu != 0 and $DipReadDip == 0 ){ $Bloccato='Y'; $RdDipOnly='Y';}

       ?>
       <tr onclick="$('#Dett<?php echo $IdLegame; ?>').toggle()" class="<?php if ( "$Tipo" != "F" ){ ?>Dipendenza<?php } echo $Blocca; ?>" >
       <td style="width:30px;" class="<?php echo $Blocca; ?>" ><img class="ImgIco" src="../images/Flusso.png" title="<?php echo $IdFlu; ?>" ></td> 
	   <td style="width:30px;" class="<?php echo $Blocca; ?>" ><?php echo $Flusso; ?></td>
       <td style="width:30px;" class="<?php echo $Blocca; ?>" > 
           <img class="ImgIco" src="../images/<?php echo $ImgTipo; ?>.png" title="<?php echo $IdDip; ?>" >
           <?php 
           if ( "$Tipo" == "V" and "$External" == "Y" ){
             ?><div style="float:left;" >EXT.</div><?php
           }
           ?>
       </td>
       <td style="width:250px;" class="<?php echo $Blocca; ?>" title="<?php if ( "$DipDesc" != "" ) { echo "$DipDesc"; } ?>"> 
           <?php 
           if ( 
            ( in_array($IdDip,$ArrElaTest) and "$Tipo" == "E" )
            ||
            ( in_array($IdDip,$ArrCarTest) and "$Tipo" == "C" )
           ){
               ?><img class="ImgIco" src="../images/Lab.png" title="Laboratorio" ><?php
           }
           if ( "$BlockCons" == "Y" and "$PeriodCons" != "0" and $Tipo != "F"  ){ ?><img class="ImgIco" src="../images/Lock.png" ><?php }
           if ( "$BlockWfs" == "Y" ){ ?><img class="ImgIco" src="../images/Lock.png" ><?php }
           if ( "$ReadOnlyWfs" == "S" ){ ?><img class="ImgIco" src="../images/bandiera.png" ><?php }
           if ( "$RdDipOnly" == "Y" ){ ?><img class="ImgIco" src="../images/ReadMode.png" ><?php }
           if ( "$Warning" > "0" ){ 
             ?><label class="ImgIco" style="width:30px;color:red;" ><img class="ImgIco" src="../images/Attention.png" ><?php if ( "$Warning" > "1" ){ echo $Warning; } ?></label><?php 
           }
           echo $DipName; 
           ?>
       </td>
       <td style="width:30px;" class="<?php echo $Blocca; ?>" >
            <img id="ImgDip<?php echo $IdLegame ?>" class="ImgIco" src="<?php echo $ImgDip; ?>" <?php if ( $DipInCoda != 0 ){ ?>hidden<?php } ?> >
            <img id="ImgRun<?php echo $IdLegame ?>" class="ImgIco" src="../images/Loading.gif" hidden >
            <img id="ImgRefresh<?php echo $IdLegame ?>" class="ImgIco" src="../images/refresh.png" hidden >
            <img id="ImgSveglia<?php echo $IdLegame ?>" class="ImgIco" src="../images/Sveglia.png" <?php if ( $DipInCoda == 0 ){ ?>hidden<?php } ?> >
       </td>           
       </tr>
       <?php
       
       $HiddenDett="hidden";
       if ( "$DipEsito" == "I" ){ $HiddenDett=""; }
       ?>
       <tr id="Dett<?php echo $IdLegame; ?>" <?php echo $HiddenDett; ?> >
       <td colspan=5  style="text-align: center;background:#eff4ff;" > 
       <?php
              

        if( "$DipElaTags" != "" ){
            ?>
            <table class="ExcelTable" ><tr><th>Tags</th><td><?php echo $DipElaTags; ?></td></tr></table>
            <?php
        }
       
        if( "$DipIniz" != "" ){
            ?>
            <table class="ExcelTable LastRun" >
               <tr><th>User</th><td><?php 
               foreach( $ArrUsers as $DUser ){
                   $UNom=$DUser[0];
                   $UUser=$DUser[1];
                   if ( "$UUser" == "$DipUtente" ){ echo $UNom; }
               }
               ?></td></tr>
               <?php
               switch ( $Tipo ) {
                 case "C":
                        ?>
                        <tr><th>Start</th><td><?php echo $DipIniz; ?></td></tr>
                        <tr><th>End</th><td><?php echo $DipFine; ?></td></tr>
                        <tr><th>Time</th><td><?php echo gmdate('H:i:s', $DipDiff); ?></td></tr>
                        <tr><th>File</th><td><?php echo $DipFile; ?></td></tr>
                        <tr><th>Note</th><td><?php echo $DipNote; ?></td></tr>
                        <tr>
                         <th></th>
                         <td>
                            <?php if ( "$DipLog" != "" ) { ?><img class="ImgIco"  style="height:35px;cursor:pointer;"  src="../images/Log.png"    onclick="OpenLogFile('<?php echo $DipLog; ?>')" ><?php } ?>
                         </td>  
                        </tr>
                        <?php
                        break;                       
                 case "E":
                        ?>
                        <tr><th>Start</th><td><?php echo $DipIniz; ?></td></tr>
                        <tr><th>End</th><td><?php echo $DipFine; ?></td></tr>
                        <tr><th>Time</th><td><?php echo gmdate('H:i:s', $DipDiff); ?></td></tr>
                        <tr><th>Meter</th>
                        <td>
                        <?php
                        if ( "$OldDiff" != "" ){
                            $SecLast=$DipDiff;
                            $SecPre=$OldDiff;
                            if ( "$SecPre" == "0" ){
                                $SecPre = 1;
                            }
                            $Perc = round(( $SecLast * 100 ) / $SecPre );

                            if ( $Perc > 100 ) {
                                $SColor = "$BarraPeggio";
                            }
                            if ( $Perc <= 100 and "$DipEsito" != "I" ) {
                                $SColor = "$BarraMeglio";
                            }
                            
                            if ( "$DipEsito" == "I" ) {
                                $SColor = "$BarraCaricamento";
                            }         
                            
                            if (
                                (   1==1
                                    and ( $Perc > 120 or $Perc < 90 ) 
                                    and  ( "$DipEsito" == "F" or "$DipEsito" == "W" )
                                    //and ( $SecLast - $SecPre > $GAP or $SecLast - $SecPre < -$GAP )
                                ) 
                                or ( "$DipEsito" == "I" )
                            ) {
                                ?>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped <?php 
                                    if ("$DipEsito" == "I") {
                                        echo "active";
                                    } 
                                    ?>" role="progressbar" aria-valuenow="<?php echo $Perc; ?>" aria-valuemin="0" aria-valuemax="100" style="text-align:right;padding-right: 5px;min-width: 2em; width: <?php 
                                    if ($Perc > 100) {
                                        echo 100;
                                    } else {
                                        echo "$Perc";
                                    } 
                                    ?>%;background-color: <?php echo "$SColor"; ?>;height: 100%;float:left;border-radius: 5px;" ><LABEL style="font-weight: 500;"><?php
                                    if ($Perc > 100) {
                                        $Perc = $Perc - 100;
                                        $Perc = "+" . $Perc;
                                    } else {
                                        if ( "$DipEsito" != "I" ){
                                          $Perc = $Perc - 100;
                                        } 
                                    }                                                                       
                                    echo $Perc;
                                    ?>%</LABEL>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>                         
                        </td>
                        </tr>
                        <tr><th>Note</th><td><?php echo $DipNote; ?></td></tr>
                        <tr>
                         <th></th>
                         <td>
                            <?php if ( "$DipLog"  != "" ) { ?><img class="ImgIco" style="height:35px;cursor:pointer;" src="../images/Log.png"    onclick="OpenLogFile('<?php echo $DipLog; ?>')" ><?php } ?>
                            <?php if ( "$IdRunSh" != "" and ( $Admin or $TASAdmin ) ) { ?><img class="ImgIco"  style="height:35px;cursor:pointer;" src="../images/LogProc.png"    onclick="OpenProcessing(<?php echo $IdRunSh; ?>)" ><?php } ?>
                            <img src="../images/Graph.png" onclick="ShowGraph(<?php echo $IdRunSh; ?>)" class="ImgIco" style="height:35px;cursor:pointer;" >
                         </td>  
                        </tr>
                        <?php
                        break;                              
               }
               ?>                      
            </table>
            <?php
        
                 
        }
             
            
		//if ( "$Bloccato" == "N" or "$BlockCons" == "S" ) {
			switch ( $Tipo ) {
				case "C":
						?>
						<div>Load File:</div>
						<input type="hidden" name="NomeInput" value="<?php echo $DipTarget; ?>" >
						<input type="file" id="UploadFileName_<?php echo $IdLegame; ?>" name="UploadFileName_<?php echo $IdLegame; ?>" style="text-align: center;left: 0;right: 0;margin: auto;"/>                                   
						<div class="Bottone"  onclick="ActionF('Carica','',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" >Load</div>                  
						<?php 
						break;
				case "E":
					   ?>
					   <div class="Bottone" onclick="ActionF('Elabora','Confermi di voler forzare l elaborazione di questo passo?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" >Avvia Elaborazione</div>
					   <?php                              
					   break;
			}      
		//}
					   
        ?><BR>
        </td>
        </tr>
        <?php 
    }
    ?></table><?php
       
}
?>
<script>
  
  function OpenLogFile(vLog){
      window.open('../PHP/ApriLogElab.php?LOG='+vLog);
  }
  
  function OpenProcessing(vIdRunSh){
      window.open('../PAGE/PgStatoShell.php?IDERR='+vIdRunSh+'&IDPROCERR=<?php echo $IdProcess; ?>');
  }
  
  function ShowGraph(vIdRunSh){
    window.open('../PHP/GraphShell.php?IDSH='+vIdRunSh);
  };
  
  function ActionF(vAction,vMess,vIdLeg,vIdDip,vNameDip,vTipo){
        if ( vMess != '' ){
        var re = confirm(vMess);
        } else {
            re = true
        }
        if ( re == true) {   

                  var input = $("<input>")
                  .attr("type", "hidden")
                  .attr("name", "IdFlu")
                  .val(<?php echo $IdFlu; ?>);
                  $('#MainForm').append($(input));

                  var input = $("<input>")
                  .attr("type", "hidden")
                  .attr("name", "Flusso")
                  .val('<?php echo $NomeFlusso; ?>');
                  $('#MainForm').append($(input));

                  var input = $("<input>")
                  .attr("type", "hidden")
                  .attr("name", "Action")
                  .val(vAction);
                  $('#MainForm').append($(input));
                  
                  var input = $("<input>")
                  .attr("type", "hidden")
                  .attr("name", "OnIdLegame")
                  .val(vIdLeg);
                  $('#MainForm').append($(input));
                  
                  var input = $("<input>")
                  .attr("type", "hidden")
                  .attr("name", "SelDipendenza")
                  .val(vIdDip);
                  $('#MainForm').append($(input));
                  
                  var input = $("<input>")
                  .attr("type", "hidden")
                  .attr("name", "SelNomeDipendenza")
                  .val(vNameDip);
                  $('#MainForm').append($(input));
                  
                  var input = $("<input>")
                  .attr("type", "hidden")
                  .attr("name", "SelTipo")
                  .val(vTipo);
                  $('#MainForm').append($(input));
				  
                  var input = $("<input>")
                  .attr("type", "hidden")
                  .attr("name", "Force")
                  .val('1');
                  $('#MainForm').append($(input));
				                   
                   $("#Waiting").show();
                   $('#MainForm').submit();         
                   
        }           
  }
  $('#ShowDettFlusso').scrollTop($('#TopScrollDett').val());
  $('#Waiting').hide();
</script>