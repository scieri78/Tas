
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
    width:70px !important;
}
.LastRun th{
    width:70px !important;
}

.progress {
    margin-bottom: 0px;
    border: 1px solid white;
    border-radius: 7px;
    height: 20px;
}

.ReadNote{
    width: 100%;
    border: none;
}

.ImgIco{
    vertical-align: middle;
}
</style>
<?php
session_cache_limiter(‘private_no_expire’);
session_start() ;

include './GESTIONE/connection.php';
include './GESTIONE/SettaVar.php';
//include './GESTIONE/login.php';
//include './GESTIONE/sicurezza.php';

if ( 1 )  {
	$Uk = $_SESSION['CodUk'];
   $IdWorkFlow=$_REQUEST['IdWorkFlow'];
   $IdProcess=$_POST['IdProcess'];
   $ProcMeseEsame=$_POST['ProcMeseEsame'];


   if ( "$IdWorkFlow" == "" or "$IdProcess" == ""  ) { exit; }

   $IdFlu=$_POST['IdFlu'];
   $NomeFlusso=$_POST['Flusso'];
   $DescFlusso=$_POST['DescFlusso'];

   $ProcAnno=$_SESSION['ProcAnno'];
   $ProcMese=$_SESSION['ProcMese'];
   if ( "$ProcMese" == "12" ){
      $ProcAnno=$ProcAnno+1;
      $ProcMese="01";
   }else{
      $ProcMese=$ProcMese+1;
   }
   $EserMese=$ProcAnno.substr($ProcMese+1000,-2);

   $WFSAdmin=False;
   $SqlWFSAdmin="SELECT count(*) CNT
   FROM WFS.ASS_GRUPPO
   WHERE 1=1
   AND ID_GRUPPO IN (  SELECT ID_GRUPPO FROM WFS.GRUPPI WHERE ID_WORKFLOW = $IdWorkFlow AND GRUPPO = 'ADMIN'  )
   AND ID_UK = $Uk
   ";
   $stmt=db2_prepare($conn, $SqlWFSAdmin);
   $res=db2_execute($stmt);
   if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
   while ($row = db2_fetch_assoc($stmt)) {
       $CntW=$row['CNT'];
       if ( "$CntW" != "0" ){
          $WFSAdmin=True;
       }
   }

?>
<script>
  function OpenMeterBar<?php echo $IdFlu; ?>(vIdFlu,vIdLegame,vInizio,vDipDiff,vOldDiff,vDipEsito){
    $('#DivMeterBar'+vIdLegame).load('./PHP/WorkFlow_MeterBar.php',{
            IdFLu:    vIdFlu,
            IdLegame: vIdLegame,
            Inizio:   vInizio,
            SecLast:  vDipDiff,
            SecPre:   vOldDiff,
            DipEsito: vDipEsito
    });
  }
</script>
<?php

    $BarraCaricamento = "rgb(21, 140, 240)";
    $BarraPeggio = "rgb(165, 108, 185)";
    $BarraMeglio = "rgb(104, 162, 111)";

//===============================================================================================
//==========   Lista Test Shell

   $SqlList="SELECT ID_ELA ID_DIP FROM WFS.ELABORAZIONI WHERE ID_WORKFLOW  = $IdWorkFlow AND ID_ELA IN ( SELECT ID_DIP FROM WFS.LEGAME_FLUSSI WHERE ID_FLU = $IdFlu ) AND ID_SH = 0";
   $stmt=db2_prepare($conn, $SqlList);
   $res=db2_execute($stmt);
   if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
   $ArrElaTest=array();
   while ($row = db2_fetch_assoc($stmt)) {
       $IdDp=$row['ID_DIP'];
       array_push($ArrElaTest,$IdDp);
   }

   $SqlList="SELECT ID_CAR ID_DIP FROM WFS.CARICAMENTI  WHERE ID_WORKFLOW  = $IdWorkFlow AND ID_CAR IN ( SELECT ID_DIP FROM WFS.LEGAME_FLUSSI WHERE ID_FLU = $IdFlu ) AND NOME_INPUT = 'WFS_TEST'";
   $stmt=db2_prepare($conn, $SqlList);
   $res=db2_execute($stmt);
   if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
   $ArrCarTest=array();
   while ($row = db2_fetch_assoc($stmt)) {
       $IdDp=$row['ID_DIP'];
       array_push($ArrCarTest,$IdDp);
   }

//===============================================================================================
//==========   Lista Batch Shell

   $SqlList="SELECT ID_SH,PARALL,BATCH,BLOCKWFS from WORK_CORE.CORE_SH_ANAG WHERE WFS = 'Y' AND ID_SH IN (
       SELECT ID_SH FROM WFS.ELABORAZIONI
       WHERE 1=1
        AND ID_WORKFLOW  = $IdWorkFlow
        AND ID_ELA   IN ( SELECT ID_DIP FROM WFS.LEGAME_FLUSSI WHERE ID_FLU = $IdFlu )
        )
   ORDER BY ID_SH";
   $stmt=db2_prepare($conn, $SqlList);
   $res=db2_execute($stmt);
   if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
   $ArrShellDett=array();
   while ($row = db2_fetch_assoc($stmt)) {
       $IdShell=$row['ID_SH'];
       $Parall=$row['PARALL'];
       $Batch=$row['BATCH'];
       $Block=$row['BLOCKWFS'];

       array_push($ArrShellDett,array($IdShell,$Parall,$Batch,$Block));

   }


//===============================================================================================
//==========   trovo Legami del flusso


   if ( "$IdProcess" != "" ){


    #Se PERIODO CONSOLIDATO
    $SqlList="SELECT BLOCK_CONS,
    ( SELECT count(*)
      FROM WORK_CORE.ID_PROCESS A
      WHERE 1=1
      AND (ESER_ESAME,MESE_ESAME) IN ( SELECT ESER_ESAME,MESE_ESAME FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
      AND FLAG_CONSOLIDATO = 1 AND ID_TEAM = ( SELECT ID_TEAM FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
      AND ID_WORKFLOW = $IdWorkFlow
    ) PERIDO_CONS
    FROM WFS.FLUSSI
    WHERE ID_FLU = $IdFlu ";
    $stmt=db2_prepare($conn, $SqlList);
    $res=db2_execute($stmt);
    if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
    while ($row = db2_fetch_assoc($stmt)) {
        $BlockCons=$row['BLOCK_CONS'];
        $PeriodCons=$row['PERIDO_CONS'];
        $SvalidaOff=false;
        if ( "$BlockCons" == "X" and "$PeriodCons" != "0" ){
           $BlockCons="S";
           $SvalidaOff=true;
        }
    }

    //STATO
    $ArrStato=array();
    $ListIdStato="0";
    $SqlList="SELECT ID_FLU,TIPO,ID_DIP,INIZIO,FINE,ESITO,NOTE FROM WFS.ULTIMO_STATO WHERE ID_WORKFLOW = $IdWorkFlow AND ID_PROCESS = '$IdProcess' ";
    $stmt=db2_prepare($conn, $SqlList);
    $res=db2_execute($stmt);
    if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
    while ($row = db2_fetch_assoc($stmt)) {
        $TabIdFlu=$row['ID_FLU'];
        $Tipo=$row['TIPO'];
        $IdDip=$row['ID_DIP'];
        $Inizio=$row['INIZIO'];
        $Fine=$row['FINE'];
        $Esito=$row['ESITO'];
        $Note=$row['NOTE'];

        array_push($ArrStato,array($TabIdFlu,$Tipo,$IdDip,$Inizio,$Fine,$Esito,$Note));
        $ListIdStato=$ListIdStato.",".$Id;
    }
   }

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

   //LEGAMI
   $SqlList="SELECT A.*
    ,CASE A.TIPO
        WHEN 'E' THEN
           timestampdiff(2,NVL(TO_DATE(A.OLD_FINE,'YYYY-MM-DD HH24:MI:SS'),CURRENT_TIMESTAMP)-TO_DATE(A.OLD_INIZIO,'YYYY-MM-DD HH24:MI:SS'))
        WHEN 'V' THEN
           timestampdiff(2,NVL(TO_DATE(A.OLD_FINE,'YYYY-MM-DD HH24:MI:SS'),CURRENT_TIMESTAMP)-TO_DATE(A.OLD_INIZIO,'YYYY-MM-DD HH24:MI:SS'))
        ELSE
          null
    END AS OLD_DIFF
    ,CASE A.TIPO
        WHEN 'E' THEN
           TO_CHAR((
             select ADD_SECONDS(
             TO_DATE(A.INIZIO,'YYYY-MM-DD HH24:MI:SS'),
             timestampdiff(2,NVL(TO_DATE(A.OLD_FINE,'YYYY-MM-DD HH24:MI:SS'),CURRENT_TIMESTAMP)-A.OLD_INIZIO)
             )
             FROM DUAL
             )
            ,'YYYY-MM-DD HH24:MI:SS')
        WHEN 'V' THEN
           TO_CHAR((
             select ADD_SECONDS(
             TO_DATE(A.INIZIO,'YYYY-MM-DD HH24:MI:SS'),
             timestampdiff(2,NVL(TO_DATE(A.OLD_FINE,'YYYY-MM-DD HH24:MI:SS'),CURRENT_TIMESTAMP)-A.OLD_INIZIO)
             )
             FROM DUAL
             )
            ,'YYYY-MM-DD HH24:MI:SS')
        ELSE
          null
        END AS   PREVIEWEND
    FROM
   (
        SELECT B.*
            ,CASE B.TIPO
                WHEN 'E' THEN
                   ( SELECT TO_CHAR(INIZIO,'YYYY-MM-DD HH24:MI:SS') INIZIO FROM WFS.CODA_STORICO C WHERE ID_WORKFLOW = B.ID_WORKFLOW AND AZIONE = 'E' AND ID_DIP = B.ID_DIP AND ID_RUN_SH = B.OLD_ID_RUN_SH)
                WHEN 'V' THEN
( SELECT TO_CHAR(MAX(INIZIO),'YYYY-MM-DD HH24:MI:SS') INIZIO FROM WFS.ULTIMO_STATO C WHERE C.ID_WORKFLOW = B.ID_WORKFLOW AND C.TIPO = 'V' AND C.ID_FLU = B.ID_FLU AND C.ID_DIP = B.ID_DIP AND C.INIZIO < B.INIZIO )
                ELSE
                  null
            END AS OLD_INIZIO
            ,CASE B.TIPO
                WHEN 'E' THEN
                   ( SELECT TO_CHAR(FINE,'YYYY-MM-DD HH24:MI:SS') FINE FROM WFS.CODA_STORICO C WHERE ID_WORKFLOW = B.ID_WORKFLOW AND AZIONE = 'E' AND ID_DIP = B.ID_DIP AND ID_RUN_SH = B.OLD_ID_RUN_SH)
                WHEN 'V' THEN
                   ( SELECT TO_CHAR(MAX(FINE),'YYYY-MM-DD HH24:MI:SS') FINE FROM WFS.ULTIMO_STATO C WHERE C.ID_WORKFLOW = B.ID_WORKFLOW AND C.TIPO = 'V' AND C.ID_FLU = B.ID_FLU AND C.ID_DIP = B.ID_DIP AND C.FINE < B.FINE )
                ELSE
                  null
            END AS OLD_FINE
            ,CASE B.TIPO
                WHEN 'E' THEN
                   ( SELECT UTENTE FROM WFS.CODA_STORICO C WHERE ID_WORKFLOW = B.ID_WORKFLOW AND AZIONE = 'E' AND ID_DIP = B.ID_DIP AND ID_RUN_SH = B.OLD_ID_RUN_SH )
                ELSE
                  null
            END AS OLD_RUN_USER
            ,CASE B.TIPO
                WHEN 'E' THEN
                   ( SELECT LOG FROM WFS.CODA_STORICO C WHERE ID_WORKFLOW = B.ID_WORKFLOW AND AZIONE = 'E' AND ID_DIP = B.ID_DIP AND ID_RUN_SH = B.OLD_ID_RUN_SH )
                ELSE
                  null
            END AS OLD_LOG
            ,CASE B.TIPO
                WHEN 'F' THEN
                   null
                WHEN 'C' THEN
                  ( SELECT CONFERMA_DATO FROM WFS.CARICAMENTI WHERE ID_CAR = B.ID_DIP )
                WHEN 'L' THEN
                  null
                WHEN 'E' THEN
                  ( SELECT SALTA_ELAB FROM WFS.ELABORAZIONI WHERE ID_ELA = B.ID_DIP )
                WHEN 'V' THEN
                  null
            END AS CONFERMA_DATO
            , WARNING
            ,( SELECT count(*) FROM WFS.AUTORIZZAZIONI_DIP WHERE ID_WORKFLOW = B.ID_WORKFLOW AND ID_FLU = B.ID_FLU
                 AND ID_GRUPPO IN ( SELECT ID_GRUPPO FROM WFS.ASS_GRUPPO WHERE ID_UK = $Uk  )
                 AND TIPO = B.TIPO
                 AND ID_DIP = B.ID_DIP
            ) RDONLYDIP
            ,( SELECT count(*) FROM WFS.AUTORIZZAZIONI_DIP
                 WHERE ID_WORKFLOW = B.ID_WORKFLOW
                 AND ID_FLU = B.ID_FLU
                 AND TIPO = B.TIPO
                 AND ID_DIP = B.ID_DIP
            ) RDONLYFLUDIP
        FROM
        (
        SELECT L.ID_WORKFLOW,ID_LEGAME,PRIORITA,L.TIPO,L.ID_DIP,US.WARNING,L.ID_FLU,
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
            ,CASE L.TIPO
                WHEN 'E' THEN
                   ( SELECT MAX(ID_RUN_SH) FROM WFS.CODA_STORICO WHERE ID_WORKFLOW = L.ID_WORKFLOW AND ID_RUN_SH < NVL(US.ID_RUN_SH,9999999) AND AZIONE = 'E' AND ID_DIP = L.ID_DIP  AND ESITO IN ('F','W') AND SUBSTR(ID_PROCESS,1,6) <= SUBSTR($IdProcess,1,6) )
                ELSE
                  null
            END AS OLD_ID_RUN_SH
            ,CASE L.TIPO
                WHEN 'E' THEN
                   ( SELECT SHOWPROC FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )
                ELSE
                  null
            END AS SHOWPROC,
            L.OPT
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
                AND L.ID_FLU       = $IdFlu
           WHERE 1=1
                AND DECODE(L.VALIDITA,null,' $ProcMeseEsame ',' '||L.VALIDITA||' ') like '% $ProcMeseEsame %'
                AND INZ_VALID <= INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
                AND FIN_VALID >= INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
                AND L.ID_WORKFLOW  = $IdWorkFlow
                AND L.ID_FLU       = $IdFlu
        ) b
   ) a
   ORDER BY PRIORITA, TIPO, DIPENDENZA
   ";
   $stmt=db2_prepare($conn, $SqlList);
   $res=db2_execute($stmt);
   if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
   //echo $SqlList;
   $ArrLegami=array();
   while ($row = db2_fetch_assoc($stmt)) {
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
       $ShowProc=$row['SHOWPROC'];
       $Opt=$row['OPT'];


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
       $DipRPreview=$row['PREVIEWEND'];

       array_push($ArrLegami,array($IdLegame,$Prio,$Tipo,$IdDip,$DipName,$DipDesc,$DipIniz,$DipFine,$DipDiff,$DipEsito,$DipNote,$DipLog,$DipFile,$DipInCoda,$DipTarget,$DipLinkTipo,$DipElaIdSh,$DipElaTags,$RdOnly,$Permesso,$WfsRdOnly,$IdRunSh,$DipUtente,$OldIdRunSh,$OldInizio,$OldFine,$OldDiff,$OldRunUser,$OldLog,$DipCarConf,$BlockWfs,$ReadOnlyWfs,$External,$Warning,$DipReadDip,$DipReadFlu,$DipRPreview,$ShowProc,$Opt));

   }


   ?>
   <input type="hidden" name="Flusso" value="<?php echo $NomeFlusso; ?>" >
   <img id="ImgRefresh" src="./images/refresh.png" width="25" height="25" style="position:fixed;left: 72.5%;background:white;border:1px solid black;" onclick="ReOpenDettFlusso(<?php echo $IdWorkFlow; ?>,<?php echo $IdProcess; ?>,<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','<?php echo $DescFlusso; ?>')" >
   <table class="ExcelTable" >
   <tr><td colspan=4  >

   <div style="text-align:center;" ><?php

        $BlkCons=false;
        $NotBlkCons=false;
        if ( "${BlockCons}" == "Y" and "${PeriodCons}" != "0" ){ $BlkCons=true; }
        if ( $Permesso == 0 and "${PeriodCons}" != "0" ){ $BlkCons=true; }
        if ( "${BlockCons}" == "S" and "${PeriodCons}" != "0" and $RdOnly == 0 ){ $NotBlkCons=true; }

        if ( "$NomeFlusso" != "UTILITY" ){
          if ( $BlkCons ){
           ?><img src="./images/Lock.png"  id="FlussoReadOnly" class="LockMode" ><?php
          } else {
               if ( $RdOnly != 0 or $Permesso == 0 or $WfsRdOnly != 0 or $BlkCons ){
                if ( ! $NotBlkCons ){
                if ( "${PeriodCons}" != "0" ){
                    ?><img src="./images/Lock.png"        id="FlussoReadOnly" class="LockMode" ><?php
                }else{
                  ?><img style="width:30px;margin:3px;position: absolute;top: 0px;right: 0px;" src="./images/ReadMode.png" ><?php
                }
              }
               }
          }
        }


    ?><img style="width: 25px; margin: 10px;" src="./images/Flusso.png" ><B><?php echo "$NomeFlusso";  if ( "$DescFlusso" != "" ){ echo " - $DescFlusso"; } ?></B>
    </div>
   </td></tr>
   <?php

   foreach($ArrLegami as $Legame ){
       $IdLegame      =$Legame[0];
       $Prio          =$Legame[1];
       $Tipo          =$Legame[2];
       $IdDip         =$Legame[3];
       $DipName       =$Legame[4];
       $DipDesc       =$Legame[5];
       $DipIniz       =$Legame[6];
       $DipFine       =$Legame[7];
       $DipDiff       =$Legame[8];
       $DipEsito      =$Legame[9];
       $DipNote       =$Legame[10];
       $DipLog        =$Legame[11];
       $DipFile       =$Legame[12];
       $DipInCoda     =$Legame[13];
       $DipTarget     =$Legame[14];
       $DipLinkTipo   =$Legame[15];
       $DipElaIdSh    =$Legame[16];
       $DipElaTags    =$Legame[17];
       $RdOnly        =$Legame[18];
       $Permesso      =$Legame[19];
       $WfsRdOnly     =$Legame[20];
       $IdRunSh       =$Legame[21];
       $DipUtente     =$Legame[22];
       $OldIdRunSh    =$Legame[23];
       $OldInizio     =$Legame[24];
       $OldFine       =$Legame[25];
       $OldDiff       =$Legame[26];
       $OldRunUser    =$Legame[27];
       $OldLog        =$Legame[28];
       $DipCarConf    =$Legame[29];
       $BlockWfs      =$Legame[30];
       $ReadOnlyWfs   =$Legame[31];
       $External      =$Legame[32];
       $Warning       =$Legame[33];
       $DipReadDip    =$Legame[34];
       $DipReadFlu    =$Legame[35];
       $DipRPreview   =$Legame[36];
       $ShowProc      =$Legame[37];
       $Opt           =$Legame[38];

    if ( "$Tipo"  == "F" ){
        $SqlList="
        WITH W_ABILITATE AS(
          SELECT ID_DIP , TIPO FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = $IdWorkFlow AND OPT NOT IN ('Y','M') AND DECODE(VALIDITA,null,' $ProcMeseEsame ',' '||VALIDITA||' ') like '% $ProcMeseEsame %'
           AND INZ_VALID <= INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
           AND FIN_VALID >= INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
        )
        SELECT ID_FLU,FLUSSO,DESCR
        ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = $IdWorkFlow AND ID_PROCESS = $IdProcess AND ID_FLU = F.ID_FLU AND ESITO = 'E' AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) KO
        ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = $IdWorkFlow AND ID_PROCESS = $IdProcess AND ID_FLU = F.ID_FLU AND ESITO IN ( 'F', 'W' )   AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) OK
        ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = $IdWorkFlow AND ID_PROCESS = $IdProcess AND ID_FLU = F.ID_FLU AND ESITO = 'I'   AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) IZ
        ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = $IdWorkFlow AND ID_PROCESS = $IdProcess AND ID_FLU = F.ID_FLU AND ESITO = 'N'   AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) TD
        ,( SELECT count(*) FROM WFS.LEGAME_FLUSSI  WHERE ID_WORKFLOW = $IdWorkFlow AND ID_FLU = F.ID_FLU AND TIPO NOT IN ( 'F', 'W' )   AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) TT
        ,( SELECT count(*) FROM WFS.LEGAME_FLUSSI  WHERE ID_WORKFLOW = $IdWorkFlow AND ID_FLU = F.ID_FLU AND TIPO IN ( 'F', 'W' )   AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) TTF
        FROM WFS.FLUSSI F
        WHERE ID_FLU = $IdDip AND ID_WORKFLOW = $IdWorkFlow ORDER BY FLUSSO";
        //echo $SqlList;
        $stmt=db2_prepare($conn, $SqlList);
        db2_execute($stmt);
        while ($row = db2_fetch_assoc($stmt)) {
            $CntKO=$row['KO'];
            $CntOK=$row['OK'];
            $CntIZ=$row['IZ'];
            $CntTD=$row['TD'];
            $CntTT=$row['TT'];
            $CntTTF=$row['TTF'];
        }

        if ( $CntTTF != 0 ){

           $Errore=0;
           $Note="";
           $Stato='N';

           $CallPlSql='CALL WFS.K_WFS.TestSottoFlussi(?, ?, ?, ?, ?, ? )';
           $stmt = db2_prepare($conn, $CallPlSql);
           db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
           db2_bind_param($stmt, 2, "IdProcess"   , DB2_PARAM_IN);
           db2_bind_param($stmt, 3, "IdDip"       , DB2_PARAM_IN);
           db2_bind_param($stmt, 4, "Stato"       , DB2_PARAM_OUT);
           db2_bind_param($stmt, 5, "Errore"      , DB2_PARAM_OUT);
           db2_bind_param($stmt, 6, "Note"        , DB2_PARAM_OUT);
           $res=db2_execute($stmt);

           if ( ! $res) {
             echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg();
           }

           if ( $Errore != 0 ) {
             echo "PLSQL Procedure Calling Error $Errore: ".$Note;
           }

           if ( "$Stato" == "S" ){
              // if (  $CntOK == 0 and $CntIZ == 0 ) { $CntOK=$CntOK+1; }
              null;
           } else {
               if (  $CntOK == $CntTT ) { $CntOK=$CntOK+1; }
           }
        }


        if (  $CntKO != 0 ) {
        $DipEsito="E";
        } else {
            if (  $CntOK == $CntTT ) {
                $DipEsito='F';
            } else {
                if (  $CntOK == 0 and $CntIZ == 0 ) {
                $DipEsito='N';
                }else{
                $DipEsito='I';
                }
            }
        }
    }


        $TestPrioNonCompleta=false;
        foreach($ArrLegami as $LegamePrio ){
          $TestPrio=$LegamePrio[1];
          if ( $TestPrio < $Prio and $LegamePrio[38] == "N" ){
            $TestDipEsito=$LegamePrio[9];
            $TestDipTipo=$LegamePrio[2];
            $TestIdDip=$LegamePrio[3];
            if ( "$TestDipTipo" != "F" ){
                if ( "$TestDipEsito" != "F" and "$TestDipEsito" != "W" ){ $TestPrioNonCompleta=true; }
            } else {
               $Errore=0;
               $Note="";
               $Stato='N';

               $CallPlSql='CALL WFS.K_WFS.FlussoCompletato(?, ?, ?, ?, ?, ? )';
               $stmt = db2_prepare($conn, $CallPlSql);
               db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
               db2_bind_param($stmt, 2, "IdProcess"   , DB2_PARAM_IN);
               db2_bind_param($stmt, 3, "TestIdDip"   , DB2_PARAM_IN);
               db2_bind_param($stmt, 4, "Stato"       , DB2_PARAM_OUT);
               db2_bind_param($stmt, 5, "Errore"      , DB2_PARAM_OUT);
               db2_bind_param($stmt, 6, "Note"        , DB2_PARAM_OUT);
               $res=db2_execute($stmt);

               if ( ! $res) {
                 echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg();
               }

               if ( $Errore != 0 ) {
                 echo "PLSQL Procedure Calling Error $Errore: ".$Note;
               }

               if ( "$Stato" == "N" ){
                  $TestPrioNonCompleta=true;
               }
            }
          }
        }

        $Blocca="";
        if( $TestPrioNonCompleta or ( "$BlockCons" == "Y" and "$PeriodCons" != "0" )  ){
            $Blocca=" Bloccato";
        }
        $TarTab="";
        $Batch="N";
        switch ( $Tipo ) {
         case "C":
           $ImgTipo="Carica";
           $SqlList="SELECT TAB_TARGET TARGTAB FROM WFS.LOAD_ANAG WHERE FLUSSO = '$DipName' AND TIPO_RECORD = 'T'";
           $stmt=db2_prepare($conn, $SqlList);
           db2_execute($stmt);
           while ($row = db2_fetch_assoc($stmt)) {
               $TarTab=$row['TARGTAB'];
           }
           break;
         case "F":
           $ImgTipo="Flusso";
           break;
         case "V":
           $ImgTipo="Valida";
            if ( "$External" == "Y" ){$ImgTipo="Elaborazione";}
           break;
         case "E":
           foreach($ArrShellDett as $DettShell ){
               $IdShell=$DettShell[0];
               if ( $IdShell == $DipElaIdSh ){
                    $Parall=$DettShell[1];
                    $Batch=$DettShell[2];
                    $Block=$DettShell[3];
               }
           }

          if ( "$Batch" == "Y" ){
              $ImgTipo="InMacchina";
           }else{
              if ( "$DipCarConf" == "N" ){
                $ImgTipo="Elaborazione";
              }else{
                $ImgTipo="SaltaElab";
              }
           }
           $SqlList="SELECT SHELL TARGTAB FROM WFS.ELABORAZIONI WHERE ID_ELA = '$IdDip'";
           $stmt=db2_prepare($conn, $SqlList);
           db2_execute($stmt);
           while ($row = db2_fetch_assoc($stmt)) {
               $TarTab=$row['TARGTAB'];
           }
           break;
         case "L":
           $ImgTipo="Setting";
           if ( "$DipLinkTipo" == "E" ){
             $ImgTipo="Link";
           }
           break;
       }

       $ImgDip='./images/Attesa.png';
       if ( $DipEsito == 'N' AND  $Blocca == "" AND $Tipo <> 'F' ) {
          $ImgDip='./images/Eseguibile.png';
       }

         $classDipendenza="";
         switch ($DipEsito) {
           case "E":
             $classDipendenza="Terminato";
             $ImgDip='./images/KO.png';
             break;
           case "F":
             $classDipendenza="Eseguito";
             $ImgDip='./images/OK.png';
             if ( "$DipNote" == "Confermo il dato in tabella" ){ $ImgDip='./images/ConfermoDato.png';  }
             $Abilita=true;
             break;
           case "C":
             $classDipendenza="InEsecuzione";
             $ImgDip='./images/Loading.gif';
             break;
           case "I":
             $classDipendenza="InEsecuzione";
             $ImgDip='./images/Loading.gif';
             break;
           case "W":
             $classDipendenza="Eseguito";
             $ImgDip='./images/Warning.png';
             break;
           default:
             null;
         }

       if ( $DipEsito != 'F' AND $DipEsito != 'W' AND $Tipo == 'F' ) {
         $ImgDip='./images/Attesa.png';
       }

       if ( "$Opt" == "Y" ){
         $ImgDip='./images/Opt.png';
       }
       if ( "$Opt" == "M" ){
         $ImgDip='./images/hand.png';
       }
       $Bloccato='N';
       $RdDipOnly='N';
       if ( "$Blocca" != "" ){ $Bloccato='Y'; }
       if ( $DipInCoda != 0 ){ $Bloccato='Y'; }
       if ( $WfsRdOnly != 0 ){ $Bloccato='Y'; }
       if ( $DipReadFlu != 0 and $DipReadDip == 0 ){ $Bloccato='Y'; $RdDipOnly='Y';}

        if( "$BlockCons" == "S" and "$PeriodCons" != "0" and ( $DipEsito == "N" or $DipEsito == "E" ) ){
            $Bloccato="N";
        }

        if ( $RdOnly != 0 ){ $Bloccato='Y'; }
        if ( $Permesso == 0 ){ $Bloccato='Y'; }

        if ( "$NomeFlusso" == "UTILITY" ){
          $Bloccato='N';
          $RdDipOnly='N';
          $Blocca="";
        }

       ?>
       <tr onclick="$('#Dett<?php echo $IdLegame; ?>').toggle()" class="<?php if ( "$Tipo" != "F" ){ ?>Dipendenza<?php } echo $Blocca; ?>" >
       <td style="width:35px;text-align: right;" class="<?php echo $Blocca; ?>" >
           <?php
           if ( "$Tipo" == "V" and "$External" == "Y" ){
             ?><div style="float:left;" >EXT.</div><?php
           }
           ?>
           <img class="ImgIco" src="./images/<?php echo $ImgTipo; ?>.png" title="<?php echo $IdDip; ?>" >
       </td>
       <td style="width:10px;text-align:center;" class="<?php echo $Blocca; ?>" title="<?php echo $TarTab; ?>" >
           <?php
            /*
            $qNum=$Prio;
            while ( $qNum > 0 ){
                $qNum=$qNum-1;
                ?><span class="ImgLiv" ></span><?php
            }
            */
            echo $Prio;
            if ( "$Warning" == "-1" ){ ?><label class="ImgSveglia" style="width:25px;color:red;" ><img class="ImgIco" src="./images/Alert.png" title="Strato Rimpiazzato"></label><?php }
            if ( "$Warning" > "0" ){ ?><label class="ImgSveglia" style="width:25px;color:red;" ><img class="ImgSveglia" src="./images/Attention.png" title="<?php if ( "$Warning" > "1" ){ echo $Warning; }  ?>" ></label><?php }

            ?>
       </td>
       <td style="width:250px;" class="<?php echo $Blocca; ?>" >
           <?php
           if (
            ( in_array($IdDip,$ArrElaTest) and "$Tipo" == "E" )
            ||
            ( in_array($IdDip,$ArrCarTest) and "$Tipo" == "C" )
           ){
               ?><img class="ImgIco" src="./images/Lab.png" title="Laboratorio" ><?php
           }
           if ( "$BlockCons" == "Y" and "$PeriodCons" != "0" and $Tipo != "F"  ){ ?><img class="ImgIco" src="./images/Lock.png" ><?php }
           if ( "$BlockWfs" == "Y" and "$BlockCons" == "Y" ){ ?><img class="ImgIco" src="./images/Lock.png" ><?php }
           if ( "$ReadOnlyWfs" == "S" ){ ?><img class="ImgIco" src="./images/bandiera.png" ><?php }
           if ( "$RdDipOnly" == "Y" ){
                if ( "${PeriodCons}" == "0" ){
                  ?><img src="./images/Lock.png"     class="ImgIco" ><?php
                }else{
                  ?><img src="./images/ReadMode.png" class="ImgIco" ><?php
                }
           }
           ?><div title="<?php
           if ( "$DipDesc" != "" ) { echo "$DipDesc"; }
           ?>" ><?php echo $DipName; ?></div><?php
           ?>
       </td>
       <td style="width:30px;" class="<?php echo $Blocca; ?>"   title="<?php echo $IdLegame; ?>" >
            <img id="ImgDip<?php echo $IdLegame ?>" class="ImgIco" src="<?php echo $ImgDip; ?>" <?php if ( $DipInCoda != 0 ){ ?>hidden<?php } ?> >
            <img id="ImgRun<?php echo $IdLegame ?>" class="ImgIco" src="./images/Loading.gif" hidden >
            <img id="ImgRefresh<?php echo $IdLegame ?>" class="ImgIco" src="./images/refresh.png" hidden >
            <img id="ImgSveglia<?php echo $IdLegame ?>" class="ImgIco" src="./images/Sveglia.png" <?php if ( $DipInCoda == 0 ){ ?>hidden<?php } ?> >
       </td>
       </tr>
       <?php
       if ( "$Tipo" != "F" ){

           $HiddenDett="hidden";
           if ( "$DipEsito" == "I" ){ $HiddenDett=""; }
           ?>
           <tr id="Dett<?php echo $IdLegame; ?>" <?php echo $HiddenDett; ?> >
           <td colspan=4  style="text-align: center;background:#eff4ff;" >
              <div id="NRef<?php echo $IdLegame; ?>" hidden  >
              <table class="ExcelTable"  >
              <tr>
                <td style="text-align: center;cursor:pointer;"  ><img class="ImgIco" src="./images/refresh.png" onclick="ReOpenDettFlusso(<?php echo $IdWorkFlow; ?>,<?php echo $IdProcess; ?>,<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','<?php echo $DescFlusso; ?>')" >Need Refresh</td>
              </tr>
              </table>
              </div>
              <?php


            if( "$DipElaTags" != "" ){
                ?>
                <table class="ExcelTable LastRun" ><tr><th>Tags</th><td><?php echo $DipElaTags; ?></td></tr></table>
                <?php
            }

            if( "$DipIniz" != "" ){
                ?>
                <table class="ExcelTable LastRun" >
                   <tr><th >User</th><td><?php
                   if ( "$DipUtente" == "Autorun" ){
                      echo "Autorun";
                   }else{
                       foreach( $ArrUsers as $DUser ){
                           $UNom=$DUser[0];
                           $UUser=$DUser[1];
                           if ( "$UUser" == "$DipUtente" ){ echo $UNom; }
                       }
                   }
                   ?></td></tr>
                   <?php
                   switch ( $Tipo ) {
                     case "C":
                            ?>
                            <tr><th>Start</th><td><?php echo $DipIniz; ?></td></tr>
                            <tr><th>End</th><td><?php
                            if ( "$DipFine" != ""  ){
                              echo $DipFine;
                            }else{
                              if ( "$OldFine" != "" ){
                                echo "</B>Preview<B> $DipRPreview";
                              }
                            }
                            ?></td></tr>
                            <tr><th>Time</th><td><?php echo gmdate('H:i:s', $DipDiff); ?></td></tr>
                            <tr><th>File</th><td><?php echo $DipFile; ?></td></tr>
                            <tr><th>Note</th><td><textarea class="ReadNote"  rows="4" readonly ><?php echo $DipNote; ?></textarea></td></tr>
                            <tr>
                             <th></th>
                             <td>
                                <?php if ( "$DipLog" != "" ) { ?><img class="ImgIco"  style="height:35px;cursor:pointer;"  src="./images/Log.png"    onclick="OpenLogFile('<?php echo $DipLog; ?>')" ><?php } ?>
                             </td>
                            </tr>
                            <?php
                            break;
                     case "E":
                            ?>
                            <tr><th>Start</th><td><?php echo $DipIniz; ?></td></tr>
                            <tr><th>End</th><td><?php
                            if ( "$DipFine" != "" ){
                              echo $DipFine;
                            }else{
                              if ( "$OldFine" != "" ){
                                echo "</B>Preview<B> $DipRPreview";
                              }
                            }
                            ?></td></tr>
                            <tr><th>Time</th><td><div id="DivTimeBar<?php echo $IdLegame; ?>" ><?php echo gmdate('H:i:s', $DipDiff); ?></div></td></tr>
                            <tr><th>Meter</th>
                            <td>
                            <?php
                            if ( "$OldDiff" != "" ){
                                ?>
                                <div id="DivMeterBar<?php echo $IdLegame; ?>" ></div>
                                <script>
                                    OpenMeterBar<?php echo $IdFlu; ?>(<?php echo $IdFlu; ?>,<?php echo $IdLegame; ?>,'<?php echo $DipIniz; ?>',<?php echo $DipDiff; ?>,<?php echo $OldDiff; ?>,'<?php echo $DipEsito; ?>');
                                <?php
                                if ( "$DipEsito" == "I" ){
                                    ?>
                                    if ( ! $('#ImgRefresh<?php echo $IdLegame ?>').is(":visible") ){
                                      setInterval(function() {
                                            OpenMeterBar<?php echo $IdFlu; ?>(<?php echo $IdFlu; ?>,<?php echo $IdLegame; ?>,'<?php echo $DipIniz; ?>',0,<?php echo $OldDiff; ?>,'<?php echo $DipEsito; ?>');
                                      }, 10000);
                                    }
                                    <?php
                                }
                                ?>
                                </script>
                                <?php
                            }
                            ?>
                            </td>
                            </tr>
                            <tr><th>Note</th><td><textarea class="ReadNote"  rows="4" readonly ><?php echo $DipNote; ?></textarea></td></tr>
                            <tr>
                             <th></th>
                             <td>
                                <?php if ( "$DipLog"  != "" ) { ?><img class="ImgIco" style="height:35px;cursor:pointer;" src="./images/Log.png"    onclick="OpenLogFile('<?php echo $DipLog; ?>')" ><?php } ?>
                                <?php if ( "$IdRunSh" != ""  and ( $Admin or   $TASAdmin or $WFSAdmin or "$ShowProc" == "Y" ) ) { ?>
                                <img class="ImgIco"  style="height:35px;cursor:pointer;" src="./images/LogProc.png"    onclick="OpenProcessing(<?php echo $IdRunSh; ?>)" >
                                <?php }  ?>
                                <img src="./images/Graph.png" onclick="ShowGraph(<?php echo $IdRunSh; ?>)" class="ImgIco" style="height:35px;cursor:pointer;" >
                             </td>
                            </tr>
                            <?php
                            break;
                     case "V":
                        if ( "$External" == "Y"  ){
                            ?>
                            <tr><th>Start</th><td><?php echo $DipIniz; ?></td></tr>
                            <tr><th>End</th><td><?php
                            if ( "$DipEsito" != "I" ){
                              echo $DipFine;
                            }else{
                              if ( "$OldFine" != "" ){
                                echo "</B>Preview<B> $DipRPreview";
                              }
                            }
                            ?></td></tr>
                            <tr><th>Time</th><td><div id="DivTimeBar<?php echo $IdLegame; ?>" ><?php echo gmdate('H:i:s', $DipDiff); ?></div></td></tr>
                            <tr><th>Meter</th>
                            <td>
                            <?php
                            if ( "$OldDiff" != "" and "$OldInizio" != "$OldFine" ){
                                ?>
                                <div id="DivMeterBar<?php echo $IdLegame; ?>" ></div>
                                <script>
                                    OpenMeterBar<?php echo $IdFlu; ?>(<?php echo $IdFlu; ?>,<?php echo $IdLegame; ?>,'<?php echo $DipIniz; ?>',0,<?php echo $OldDiff; ?>,'<?php echo $DipEsito; ?>');
                                <?php
                                if ( "$DipEsito" == "I" ){
                                    ?>
                                    if ( ! $('#ImgRefresh<?php echo $IdLegame ?>').is(":visible") ){
                                      setInterval(function() {
                                            OpenMeterBar<?php echo $IdFlu; ?>(<?php echo $IdFlu; ?>,<?php echo $IdLegame; ?>,'<?php echo $DipIniz; ?>',0,<?php echo $OldDiff; ?>,'<?php echo $DipEsito; ?>');
                                      }, 10000);
                                    }
                                    <?php
                                }
                                ?>
                                </script>
                                <?php
                            }
                            ?>
                            </td>
                            </tr>
                            <?php if ( $_SESSION['IdTeam'] == 4 ) { ?>
                            <tr>
                             <th>Log</th>
                             <td>
                                <img class="ImgIco"  style="height:35px;cursor:pointer;"  src="./images/Log.png"    onclick="OpenLogFile('/area_staging_TAS/DIR_LOG/RETI_TWS/<?php echo $DipName.'.sh_'.$EserMese.'*'; ?>.log')" >
                             </td>
                            </tr>
                            <?php } ?>
                            <tr><th>Note</th><td><textarea class="ReadNote"  rows="4" readonly ><?php echo $IdTeam.$DipNote; ?></textarea></td></tr>
                            <tr>
                            <?php
                           }else{
                                if ( $_SESSION['IdTeam'] == 4 ) {
                                    ?>
                                    <tr>
                                     <th>Log</th>
                                     <td>
                                        <img class="ImgIco"  style="height:35px;cursor:pointer;"  src="./images/Log.png"    onclick="OpenLogFile('/area_staging_TAS/DIR_LOG/RETI_TWS/<?php echo $DipName.'.sh_'.$EserMese.'*'; ?>.log')" >
                                     </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                <tr><th>Data</th><td><?php echo $DipIniz; ?></td></tr>
                                <?php
                            }
                            break;
                     case "L":
                            ?>
                            <tr><th>Data</th><td><?php echo $DipIniz; ?></td></tr>
                            <?php
                            break;
                   }
                   ?>
                </table>
                <?php
            }

            if ( ( "$Blocca" == "" ) and ( ( "$Bloccato" == "N" ) or ( $Tipo == 'L') or "$BlockCons" == "S" ) and $RdOnly == 0 and $Permesso != 0 ){
                switch ( $Tipo ) {
                    case "C":
                        if ( $DipEsito == "N" or $DipEsito == "E" ) {
                            ?>
                            <div>Load File:</div>
                            <input type="hidden" name="NomeInput" value="<?php echo $DipTarget; ?>" >
                            <input type="file" id="UploadFileName_<?php echo $IdLegame; ?>" name="UploadFileName_<?php echo $IdLegame; ?>" style="text-align: center;left: 0;right: 0;margin: auto;"/>
                            <div class="Bottone"  onclick="Action('Carica','',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" >Load</div>
                            <?php
                            if ( "$DipCarConf" == "S" ){
                                ?>
                                <div class="Bottone"  onclick="Action('ConfermaDato','Confermi di validare il dato presente in tabella?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" >Conferma Dato in Tabella</div>
                                <div class="Bottone"  onclick="Action('CopiaDato','Confermi di copiare dal vecchio strato e sostituire il dato presente in tabella?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" >Copia Periodo Prec in Tabella</div>
                                <?php
                            }
                        } else {
                            if ( "$DipEsito" != "I" and ! $SvalidaOff ) {
                            ?>
                            <div class="Bottone"  onclick="Action('Svalida','Confermi di voler svalidare questo passo?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')"  >Svalida</div>
                            <?php
                            }
                        }
                        if ( $DipEsito <> "F" AND $DipEsito <> "C" AND $DipEsito <> "E" AND $Rinnovabile == "S" ) {
                          ?>
                          <div class="Bottone"  onclick="Action('ValidaDato','Confermi il dato esitente in tabella?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" >Valida Dati Esistenti</div>
                          <?php
                        }
                        break;
                    case "V":
                        if ( $DipEsito <> "F" ) {
                          if ( "$External" == "N" ){
                            ?>
                            <div class="Bottone"  onclick="Action('Valida','Confermi le elaborazioni fino ad ora?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" >Valida</div>
                            <?php
                          }
                        } else {
                         if ( ! $SvalidaOff ){

                          if ( "$External" == "N" ){
                            ?>
                            <div class="Bottone" onclick="Action('Svalida','Confermi di voler svalidare il passo selezionato?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" >Svalida</div>
                            <?php
                          } else {
                            if ( "$Warning" > "0" ){
                              ?>
                              <div class="Bottone" onclick="Action('Svalida','Confermi di voler svalidare dal passo selezionato?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" >Spegni Warning e Svalida Succ.</div>
                              <?php
                            }else{
                              ?>
                              <div class="Bottone" onclick="Action('Svalida','Confermi di voler svalidare dal passo selezionato?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" >Svalida Passi Succ.</div>
                              <?php
                            }
                            if ( "$Warning" != "0" ){
                            ?><div class="Bottone" onclick="Action('Spegni','Confermi di voler spegnere il warning del passo selezionato?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" >Spegni Warning</div><?php
                          
							}
						 }
                         }
                        }

                        break;
                    case "E":
                        if ( $DipEsito == "N" ) {
                          ?>
                          <div class="Bottone" onclick="Action('Elabora','Confermi di voler elaborare questo passo?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" >Avvia Elaborazione</div>
                          <?php
                        }
                        if ( ( $DipEsito == "E" ||  $DipEsito == "F" ||  $DipEsito == "W" ) and ! $SvalidaOff  ) {
                          ?>
                          <div class="Bottone"  onclick="Action('Svalida','Confermi di voler svalidare il passo selezionato?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" >Svalida</div>
                           <?php

                        }
                        if ( "$DipCarConf" == "S" ){
                            ?>
                            <div class="Bottone"  onclick="Action('ConfermaDato','Confermi di voler saltare il passo?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" >Salta Elaborazione</div>
                            <div class="Bottone"  onclick="Action('CopiaDato','Confermi di copiare dal vecchio strato e sostituire il dato presente in tabella?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" >Copia Periodo Prec in Tabella</div><?php
                        }
                        break;
                    case "L":
                        if ( "$DipLinkTipo" == "E" ){
                              ?><a href='<?php echo $DipTarget; ?>' target='_blank'><div class="Bottone"  id="Puls_Valida<?php echo $IdLegame; ?>" >Apri Link</div></a><?php
                              if (  "$Bloccato" == "N" and $DipEsito != "F"  ){
                                ?><div class="Bottone" onclick="Action('Valida','Confermi di voler Validare il Link selezionato?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" >Valida</div><?php
                              }
                        } else {
                              ?><div class="Bottone" onclick="OpenLink(<?php echo $IdLegame; ?>,'<?php echo $DipTarget; ?>','<?php echo $DipName; ?>','<?php echo $Bloccato; ?>','<?php echo $DipEsito; ?>')" >Apri Link</div><?php
                        }

                        if ( $DipEsito == "F" and  "$Bloccato" == "N" and ! $SvalidaOff ){
                            ?><div class="Bottone"  onclick="Action('Svalida','Confermi di voler svalidare il passo selezionato?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>','<?php echo $Tipo; ?>')" >Svalida</div><?php
                        }

                        break;
                    case "F":
                         break;
                 }

            }


            if( "$OldInizio" != ""  and "$OldInizio" != "$OldFine"  ){
                ?>
                <table class="ExcelTable OldRun" >
                    <tr><th>Old User</th><td><?php
                    if ( "$OldRunUser" != "Autorun" ){
                     foreach( $ArrUsers as $DUser ){
                       $UNom=$DUser[0];
                       $UUser=$DUser[1];
                       if ( "$UUser" == "$OldRunUser" ){ echo $UNom; }
                     }
                    }else{
                      echo $OldRunUser ;
                    }
                    ?></td></tr>
                    <tr><th>Old Start</th><td><?php echo $OldInizio; ?></td></tr>
                    <tr><th>Old End</th><td><?php echo $OldFine; ?></td></tr>
                    <tr><th>Old Time</th><td><?php echo gmdate('H:i:s', $OldDiff); ?></td></tr>
                    <tr>
                     <th></th>
                     <td>
                        <?php if ( "$OldLog" != "" ) { ?><img class="ImgIco"  style="height:35px;cursor:pointer;"  src="./images/Log.png"    onclick="OpenLogFile('<?php echo $OldLog; ?>')" ><?php } ?>
                        <?php if ( "$OldIdRunSh" != "" and ( $Admin or  $TASAdmin or $WFSAdmin or "$ShowProc" == "Y" ) ) { ?><img class="ImgIco"  style="height:35px;cursor:pointer;" src="./images/LogProc.png"    onclick="OpenProcessing(<?php echo $OldIdRunSh; ?>)" ><?php } ?>
                     </td>
                    </tr>
                </table>
                <?php
            }
             ?><BR>
             </td>
             </tr>
             <?php
        }
        ?><?php
     }
     ?></table><?php

}
?>
<script>

  function OpenLogFile(vLog){
      window.open('./PHP/ApriLogElab.php?LOG='+vLog);
  }

  function OpenProcessing(vIdRunSh){
      window.open('./PAGE/PgStatoShell.php?IDWFM='+vIdRunSh);
  }

  function ShowGraph(vIdRunSh){
    window.open('./PHP/GraphShell.php?IDSH='+vIdRunSh);
  };

  function OpenLink(vIdLegame,vTarget,vNameDip,vBloccato,vEsitoDip){
     // $('#Waiting').show();
      $('#OpenLinkPage').empty().load('./PHP/Workflow_OpenLinkPage.php',{
            IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
            IdProcess: '<?php echo $IdProcess; ?>',
            LinkPagina: vTarget,
            LinkNameDip: vNameDip,
            LinkIdLegame: vIdLegame,
            LinkBloccato: vBloccato,
            LinkEsitoDip: vEsitoDip
      }).show();
  }

  function Action(vAction,vMess,vIdLeg,vIdDip,vNameDip,vTipo){
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

                  // $("#Waiting").show();
                   $('#MainForm').submit();

        }
  }
  $('#ShowDettFlusso').scrollTop($('#TopScrollDett').val());
  $('#Waiting').hide();
</script>