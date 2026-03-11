<?php

include './GESTIONE/connection_work.php';
include './GESTIONE/SettaVar.php'; 
/*


$sql="SELECT A.*,
    timestampdiff(2,TO_DATE(OLD_FINE,'YYYY-MM-DD HH24:MI:SS')-TO_DATE(OLD_INIZIO,'YYYY-MM-DD HH24:MI:SS')) AS OLD_DIFF 
    ,TO_CHAR(TO_DATE(OLD_FINE,'YYYY-MM-DD HH24:MI:SS'),'YYYY-MM-DD HH24:MI:SS') AS OLD_DATE
    ,TO_CHAR(TO_DATE(END_TIME,'YYYY-MM-DD HH24:MI:SS'),'YYYY-MM-DD HH24:MI:SS') AS NOW_DATE 
    ,TO_CHAR(CURRENT_TIMESTAMP,'YYYY-MM-DD HH24:MI:SS')                         AS OGGI
    ,TO_CHAR(TO_DATE(END_TIME,'YYYY-MM-DD HH24:MI:SS'),'YYYYMM')                AS OLD_ESER_MESE 
    ,TO_CHAR(ADD_SECONDS(A.START_TIME,
    timestampdiff(2,TO_DATE(OLD_FINE,'YYYY-MM-DD HH24:MI:SS')-TO_DATE(OLD_INIZIO,'YYYY-MM-DD HH24:MI:SS'))
    ),'YYYY-MM-DD HH24:MI:SS')          AS PRWEND
    ,(
    SELECT MIN(START_TIME) 
    FROM WORK_CORE.CORE_SH S
    WHERE 1=1
      AND S.ID_SH IN ( SELECT T.ID_SH FROM WORK_CORE.CORE_RETI_TWS T WHERE T.RETE= A.RETE ) 
      AND S.ESER_ESAME = YEAR('$DataElab')
      AND S.MESE_ESAME = MONTH('$DataElab')
     ) START_RETE
FROM (
    SELECT B.*
        ,(SELECT  TO_CHAR(START_TIME,'YYYY-MM-DD HH24:MI:SS')  START_TIME FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = B.ID_RUN_SH_OLD ) OLD_INIZIO
        ,(SELECT  TO_CHAR(END_TIME,'YYYY-MM-DD HH24:MI:SS')    END_TIME   FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = B.ID_RUN_SH_OLD ) OLD_FINE
    FROM (
        SELECT C.*
         ,
            ( SELECT 
                    MAX(ID_RUN_SH) 
                FROM 
                    WORK_CORE.CORE_SH H
                WHERE 1=1
                    AND ID_SH = C.ID_SH
                    AND STATUS IN ('W','F')
                    AND H.ESER_ESAME||LPAD(H.MESE_ESAME,2,0) < YEAR('$DataElab')||LPAD(MONTH('$DataElab'),2,0)
             ) ID_RUN_SH_OLD  
          ,  NVL( (SELECT  REPLACE(substr(O.OLD_SH,1,5),'.sh','') FROM WORK_CORE.CORE_RETI_TWS_OLD O WHERE O.NEW_SH = C.PASSO||'.sh' ), '-') OLDNAMERETE
          ,  NVL( (SELECT  REPLACE(O.OLD_SH,'.sh','')             FROM WORK_CORE.CORE_RETI_TWS_OLD O WHERE O.NEW_SH = C.PASSO||'.sh' ), '-') OLDNAMEPASSO          
        FROM (
        SELECT 
            T.RETE, 
            REPLACE( 
            (   SELECT 
                    SHELL 
                FROM 
                    WORK_CORE.CORE_SH_ANAG 
                WHERE 
                    ID_SH = T.ID_SH 
                    AND ENABLE = 'Y' ),'.sh','') PASSO, 
            S.STATUS, 
            S.ESER_ESAME||'-'||LPAD(S.MESE_ESAME,2,0)                       TIMEELAB, 
            TO_CHAR(S.START_TIME,'YYYY-MM-DD HH24:MI:SS')                   START_TIME, 
            TO_CHAR(S.END_TIME,'YYYY-MM-DD HH24:MI:SS')                     END_TIME, 
            timestampdiff(2,NVL(S.END_TIME,CURRENT_TIMESTAMP)-S.START_TIME) SEC_DIFF, 
            S.ID_RUN_SH,
            RETE_ORD,
            PASSO_ORD,
            S.ID_SH
        FROM 
            WORK_CORE.CORE_RETI_TWS T 
        LEFT JOIN 
            WORK_CORE.CORE_SH S 
        ON 
            S.ID_SH = T.ID_SH 
        AND S.ID_SH IN 
            (   SELECT 
                    ID_SH 
                FROM 
                    WORK_CORE.CORE_SH_ANAG 
                WHERE 
                    VALID = 'Y' ) 
        AND ID_RUN_SH = 
            (   SELECT 
                    MAX(ID_RUN_SH) 
                FROM 
                    WORK_CORE.CORE_SH H
                WHERE 
                    ID_SH = S.ID_SH 
                    AND H.ESER_ESAME||LPAD(H.MESE_ESAME,2,0) <= YEAR('$DataElab')||LPAD(INT(MONTH('$DataElab')),2,0)
                    ) 
        WHERE T.ENABLE = 'Y'
        AND S.ESER_ESAME||LPAD(S.MESE_ESAME,2,0) <= YEAR('$DataElab')||LPAD(INT(MONTH('$DataElab')),2,0)
        AND S.ID_SH not in ( SELECT ID_SH FROM  WORK_CORE.CORE_PASSI_SOSPESI WHERE ESER_MESE < YEAR('$DataElab')||LPAD(INT(MONTH('$DataElab')),3,0)  )
        ) C
   ) B
) A
ORDER BY
    START_RETE DESC,
    PASSO_ORD DESC";    
$stmt=db2_prepare($conn, $sql);
$result=db2_execute($stmt);

//echo $sql;

if ( ! $result ){
   echo "ERROR DB2".db2_stmt_errormsg(); ;
}

$MIN_DATE="";
$MAX_DATE="";

$RETI=array();

foreach ($DatiReti as $row) {
   $RETE=$row['RETE'];
   $PASSO=$row['PASSO'];
   $STATUS=$row['STATUS'];
   $TIMEELAB=$row['TIMEELAB'];
   $START_TIME=$row['START_TIME'];
   $END_TIME=$row['END_TIME'];
   $ID_RUN_SH=$row['ID_RUN_SH'];
   $SEC_DIFF=$row['SEC_DIFF'];
   $PRWEND=$row['PRWEND'];   
   
   $ID_RUN_SH_OLD=$row['ID_RUN_SH_OLD'];
   $OLD_INIZIO=$row['OLD_INIZIO'];
   $OLD_FINE=$row['OLD_FINE'];
   $OLD_DIFF=$row['OLD_DIFF'];
   $OLD_DATE=$row['OLD_DATE'];
   $NOW_DATE=$row['NOW_DATE'];
   $OGGI=$row['OGGI'];
   $OLD_ESER_MESE=$row['OLD_ESER_MESE'];
   
   if ( "${'OLDNAME'.$RETE}" == "" ){
     ${'OLDNAME'.$RETE}=$row['OLDNAMERETE'];
   }
   $OLDNAMEPASSO=$row['OLDNAMEPASSO'];
   
   if ( ! in_array($RETE, $RETI) ){
        array_push($RETI,$RETE);        
        ${'New'.$RETE}=array();
        ${'Old'.$RETE}=array();
        ${'IdIn'.$RETE}=array();
        ${'IdNowIn'.$RETE}=array();
        ${'IdNowOldIn'.$RETE}=array();
        ${'IdOldIn'.$RETE}=array();                  
   }   

   if ( "$ID_RUN_SH" != "" ){
      array_push(${'IdIn'.$RETE},$ID_RUN_SH); 
      if ( "$TIMEELAB" == "$EserMese" ){
        array_push(${'IdNowIn'.$RETE},$ID_RUN_SH);
      } else {
        array_push(${'IdNowOldIn'.$RETE},$ID_RUN_SH);
      }     
   }
   
   if ( "$ID_RUN_SH_OLD" != "" ){
     array_push(${'IdOldIn'.$RETE},$ID_RUN_SH_OLD);
   }                                     
   
   if ( $MIN_DATE < $START_TIME ){
     $MIN_DATE=$START_TIME;
   }
   
   if ( $MAX_DATE < $END_TIME ){
     $MAX_DATE=$END_TIME;
   }
   
   if ( "$TIMEELAB" == "$EserMese" ){   
      array_push(${'New'.$RETE},array($PASSO,$STATUS,$START_TIME,$END_TIME,$ID_RUN_SH,$SEC_DIFF,$ID_RUN_SH_OLD,$OLD_INIZIO,$OLD_FINE,$OLD_DIFF,$OLD_DATE,$NOW_DATE,$OGGI,$OLD_ESER_MESE,$PRWEND,$OLDNAMEPASSO));
   }else{
      array_push(${'Old'.$RETE},array($PASSO,$STATUS,$START_TIME,$END_TIME,$ID_RUN_SH,$SEC_DIFF,$ID_RUN_SH_OLD,$OLD_INIZIO,$OLD_FINE,$OLD_DIFF,$OLD_DATE,$NOW_DATE,$OGGI,$OLD_ESER_MESE,$PRWEND,$OLDNAMEPASSO));
   }
}*/
?>
 <FORM id="FormRetiTWS" method="POST" >
    <input type="hidden" id="TopScrollShell1"  name="TopScrollShell1"  value="<?php echo $TopScrollShell1; ?>" />
    <input type="hidden" id="LeftScrollShell1" name="LeftScrollShell1" value="<?php echo $LeftScrollShell1; ?>" />
    <input type="hidden" id="ListOpenNet"      name="ListOpenNet"      value="<?php echo $ListOpenNet; ?>" />
    <input type="hidden" id="ListOpenStep"     name="ListOpenStep"     value="<?php echo $ListOpenStep; ?>" />
    <input type="hidden" id="ListOpenId"       name="ListOpenId"       value="<?php echo $ListOpenId; ?>" />
    <SELECT class="selectSearch" name="SET_DATA_ELAB" style="width:150px;margin-left:15px!important;" onchange="$('#FormRetiTWS').submit()" >
    <?php
 /*   $sql = "SELECT DISTINCT DATE(LAST_DAY(DATE(ESER_ESAME||'-'||LPAD(MESE_ESAME,2,0)||'-01'))) DATAELAB
    FROM WORK_CORE.CORE_SH 
WHERE 1=1
AND ESER_ESAME is not null
AND MESE_ESAME is not null
AND ID_SH IN ( SELECT ID_SH FROM WORK_CORE.CORE_SH_ANAG WHERE SHELL_PATH = '/area_staging_TAS/RETI_TWS')
ORDER BY 1 DESC";
    
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    if ( ! $result ){
        echo $sql;
        echo "ERROR DB2 1".db2_stmt_errormsg(); ;
    }*/
    
    foreach ($DatiSelectElab as $row) {
      $DtElb=$row['DATAELAB'];
      ?><option value="<?php echo $DtElb; ?>" <?php if ( "$SetDataElab" == "$DtElb" ){ ?>selected<?php } ?> ><?php echo $DtElb; ?></option><?php
    }
    ?></SELECT>
 <div id="DivRef" >
 
    <button id="refresh" onclick="$('#FormRetiTWS').submit();" class="btn"><i class="fa-solid fa-refresh"> </i> Refresh</button>
    <input type="checkbox" id="AutoRefresh11" name="AutoRefresh11" value="AutoRefresh11" <?php if ( "$AutoRefresh11" == "AutoRefresh11" ){ ?>checked<?php } ?> >AUTO REFRESH
    <input type="checkbox" id="ShowHidden"  name="ShowHidden"  value="ShowHidden"  <?php if ( "$ShowHidden"  == "ShowHidden" ){ ?>checked<?php } ?> >TO DO
 </div>
 <ul class="UlReti">
<?php

$ShowNet="";
$OpenStep="";
$ShowNet="NORETE";
$ShowStep="NOSTEP";
$ArrInCorso=array();
    
foreach( $RETI as $RETE ) {
    
    $ListId="";
    foreach( ${'IdIn'.$RETE} as $IdSh ) {
            if ( "$IdSh" != "" ){ $ListId=$ListId." ".$IdSh.","; }
    }
    $ListOldId="";
    foreach( ${'IdOldIn'.$RETE} as $IdSh ) {
            if ( "$IdSh" != "" ){ $ListOldId=$ListOldId." ".$IdSh.","; }
    }  
        
    $ListNowId="";
    foreach( ${'IdNowIn'.$RETE} as $IdSh ) {
            if ( "$IdSh" != "" ){ $ListNowId=$ListNowId." ".$IdSh.","; }
    }
    $ListNowOldId="";
    foreach( ${'IdNowOldIn'.$RETE} as $IdSh ) {
            if ( "$IdSh" != "" ){ $ListNowOldId=$ListNowOldId." ".$IdSh.","; }
    }   
    
   /* $sql="SELECT A.*    
      ,timestampdiff(2,
      NVL(TO_DATE(A.NOWMAX,'YYYY-MM-DD HH24:MI:SS'),CURRENT_TIMESTAMP)
         -TO_DATE(A.NOWMIN,'YYYY-MM-DD HH24:MI:SS')
      ) DIFF
      ,timestampdiff(2, TO_DATE(A.OLDMAX ,'YYYY-MM-DD HH24:MI:SS')-TO_DATE(A.OLDMIN,'YYYY-MM-DD HH24:MI:SS') )  OLDDIFF
    FROM (
      SELECT 
       ( SELECT count(*)  FROM (  SELECT * FROM  WORK_CORE.CORE_SH   WHERE  ID_RUN_SH IN ( $ListId $ListOldId 0 ) ) 
       WHERE ESER_ESAME||LPAD(MESE_ESAME,2,0) < YEAR('$DataElab')||LPAD(MONTH('$DataElab'),2,0) ) CNT_N
      ,( SELECT count(*)  FROM (  SELECT * FROM  WORK_CORE.CORE_SH   WHERE  ID_RUN_SH IN ( $ListId 0 ) ) WHERE STATUS = 'I' 
         AND ESER_ESAME||LPAD(MESE_ESAME,2,0) = YEAR('$DataElab')||LPAD(MONTH('$DataElab'),2,0) ) CNT_I
      ,( SELECT count(*)  FROM (  SELECT * FROM  WORK_CORE.CORE_SH   WHERE  ID_RUN_SH IN ( $ListId 0 ) ) WHERE STATUS IN ( 'F', 'M' )
         AND ESER_ESAME||LPAD(MESE_ESAME,2,0) = YEAR('$DataElab')||LPAD(MONTH('$DataElab'),2,0) ) CNT_F
      ,( SELECT count(*)  FROM (  SELECT * FROM  WORK_CORE.CORE_SH   WHERE  ID_RUN_SH IN ( $ListId 0 ) ) WHERE STATUS IN ( 'W' )
         AND ESER_ESAME||LPAD(MESE_ESAME,2,0) = YEAR('$DataElab')||LPAD(MONTH('$DataElab'),2,0) ) CNT_W
      ,( SELECT count(*)  FROM (  SELECT * FROM  WORK_CORE.CORE_SH   WHERE  ID_RUN_SH IN ( $ListId 0 ) ) WHERE STATUS = 'E' 
         AND ESER_ESAME||LPAD(MESE_ESAME,2,0) = YEAR('$DataElab')||LPAD(MONTH('$DataElab'),2,0) ) CNT_E
      ,TO_CHAR(( SELECT MIN(START_TIME)  FROM (  SELECT * FROM  WORK_CORE.CORE_SH   WHERE  ID_RUN_SH IN ( $ListId 0 ) 
         AND ESER_ESAME||LPAD(MESE_ESAME,2,0) = YEAR('$DataElab')||LPAD(MONTH('$DataElab'),2,0) 
      ) ),'YYYY-MM-DD HH24:MI:SS') NOWMIN
      ,TO_CHAR(( SELECT MAX(NVL(END_TIME,CURRENT_TIMESTAMP))  FROM (  SELECT END_TIME FROM  WORK_CORE.CORE_SH   WHERE  ID_RUN_SH IN ( $ListId 0 )
         AND ESER_ESAME||LPAD(MESE_ESAME,2,0) = YEAR('$DataElab')||LPAD(MONTH('$DataElab'),2,0) 
        ) ),'YYYY-MM-DD HH24:MI:SS') NOWMAX
      ,TO_CHAR(( SELECT MIN(START_TIME)  FROM (  SELECT START_TIME FROM  WORK_CORE.CORE_SH   WHERE  ID_RUN_SH IN ( $ListOldId 0 ) 
           ) ),'YYYY-MM-DD HH24:MI:SS') OLDMIN
      ,TO_CHAR(( SELECT MAX(NVL(END_TIME,CURRENT_TIMESTAMP))  FROM (  SELECT END_TIME FROM  WORK_CORE.CORE_SH   WHERE  ID_RUN_SH IN ( $ListOldId 0 )
           ) ),'YYYY-MM-DD HH24:MI:SS') OLDMAX
      FROM DUAL  
    ) A
    ";   
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    if ( ! $result ){
       echo "ERROR DB2 Dett RETE : $sql".db2_stmt_errormsg(); ;
    }   */
    $datiCTNReti =  $_modelReti->getCTNReti($ListId, $ListOldId, $DataElab);    
    $CNT_N="";
    $CNT_F="";
    $CNT_E="";
    $CNT_W="";
    $CNT_I="";
    $NOWMIN="";
    $NOWMAX="";
    $OLDMIN="";
    $OLDMAX="";
    $DIFF="";
    $OLDDIFF="";
    foreach ($datiCTNReti as $row) {
       $CNT_N=$row['CNT_N'];    
       $CNT_F=$row['CNT_F'];
       $CNT_E=$row['CNT_E'];
       $CNT_I=$row['CNT_I'];
       $CNT_W=$row['CNT_W'];
       $NOWMIN=$row['NOWMIN'];
       $NOWMAX=$row['NOWMAX'];
       $OLDMIN=$row['OLDMIN'];
       $OLDMAX=$row['OLDMAX'];    
       $DIFF=$row['DIFF'];
       $OLDDIFF=$row['OLDDIFF'];
    }
    if ( "$OLDMIN" == "" ){ $OLDDIFF=""; }
    
    $datiNowEserMese =  $_modelReti->getNowEserMese($ListId);
    
    $ArrRepPasso=array();
    foreach ($datiNowEserMese as $row) {
       $NOW_ESERMESE=$row['NOW_ESERMESE'];
    }
    $datiIdRunSh =  $_modelReti->getIdRunSh($ListId, $DataElab);
    /*
    $sql="SELECT ID_RUN_SH FROM WORK_CORE.CORE_SH  WHERE  MESE_ESAME = INT(MONTH('$DataElab')) AND ESER_ESAME = YEAR('$DataElab') AND  ID_RUN_SH IN ( $ListId 0 ) 
    AND START_TIME >= (
        SELECT MAX(END_TIME) END_TIME FROM WORK_CORE.CORE_SH  WHERE  MESE_ESAME = INT(MONTH('$DataElab')) AND ESER_ESAME = YEAR('$DataElab') AND  ID_RUN_SH IN ( $ListId 0 ) AND 
        ID_SH = ( SELECT C.ID_SH 
                    FROM
                        WORK_CORE.CORE_RETI_TWS  CRT, 
                        WORK_CORE.CORE_SH C 
                    WHERE 1=1
                        AND CRT.ENABLE = 'Y'
                        AND C.ID_SH = CRT.ID_SH 
                        AND MESE_ESAME = INT(MONTH('$DataElab')) AND ESER_ESAME = YEAR('$DataElab')
                        AND ID_RUN_SH IN ( $ListId 0 ) 
                        AND PASSO_ORD = (
                            SELECT MAX(PASSO_ORD) 
                            FROM 
                                WORK_CORE.CORE_RETI_TWS CRT, 
                                WORK_CORE.CORE_SH C 
                            WHERE 1=1
                                AND CRT.ENABLE = 'Y'
                                AND C.ID_SH = CRT.ID_SH 
                                AND ID_RUN_SH IN ( $ListId 0 )
                                AND C.MESE_ESAME = INT(MONTH('$DataElab')) AND C.ESER_ESAME = YEAR('$DataElab')
                        )
                )
    )
    "; */
    
    //echo $sql.'<BR><BR>';
   /* $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    if ( ! $result ){
       echo "ERROR DB2".db2_stmt_errormsg(); ;
    }*/
        
    $ArrRepPasso=array();
    foreach ( $datiIdRunSh as $row ) {
       $RepPasso=$row['ID_RUN_SH'];
       array_push($ArrRepPasso,$RepPasso);
    }
    
    
    $HideRete="hidden";
    $HidePasso="hidden";
    
    $ReteConclusa=0; 
    $ReteErrore=0;
    $ColorRete="gray";
    $STATUS='N';
    $ToDo="";
   
    //echo"
    //CNT_N: $CNT_N
    //CNT_F: $CNT_F
    //CNT_W: $CNT_W
    //CNT_E: $CNT_E
    //CNT_I: $CNT_I 
    //";
    
    
    if (( "$NOWMAX" == "$OLDMAX" ) or ( "$CNT_E" == "0" and "$CNT_I" == "0" and "$CNT_F" == "0" and "$CNT_W" == "0"   )){ 
        $HideRete="hidden";
        $HidePasso="hidden";
        $ToDo="ToDo";
    } else {    
    
    
    //NON FINITO
    if ( "$CNT_E" == "0" and "$CNT_I" == "0" and "$CNT_F" < "$CNT_N"  ){ 
        $HideRete="";
        $HidePasso="hidden"; 
        $ColorRete="gray"; 
        $ShowNet=$ShowNet.','.$RETE; 
        //$ShowStep=$ShowStep.','.$RETE;  
        $ColorRete="#327a7a";
        $STATUS='R';        
    }
    
    //DA FARE
    if ( "$CNT_E" == "0" and "$CNT_F" == "0" and  "$CNT_I" == "0" and "$CNT_N" != "0" ){
        $ColorRete="gray";      
        if ( "$ShowHidden" == "ShowHidden" ){
            $HideRete="";
            $ShowNet=$ShowNet.','.$RETE;        
        }
        $STATUS='N';
    }      
    //IN CORSO
    if ( "$CNT_E" == "0" and "$CNT_I" != "0" ){ 
        $HideRete="";
        $HidePasso="";      
        $ColorRete="rgb(192, 181, 54)";
        $ShowNet=$ShowNet.','.$RETE; 
        $ShowStep=$ShowStep.','.$RETE;
        $STATUS='I';
        if ( ! in_array($RETE,$ArrInCorso)) {
          array_push($ArrInCorso,$RETE);
        }
    }
    
    //COMPLETATO
    if ( "$CNT_E" == "0" and  "$CNT_I" == "0" and $CNT_F+$CNT_W >= $CNT_N ){ 
        $HideRete="";
        $HidePasso="hidden";
        $ColorRete="rgb(67, 168, 51)";
        $ReteConclusa=1; 
        $STATUS='F';
    }

    //IN WARNING
    if ( "$CNT_W" != "0" ){ 
        $HideRete="";
        $ReteWarning=1;
        $HidePasso="hidden"; 
        $ColorRete="orange";
        $STATUS='W';
    }

    
    //IN ERROE
    if ( "$CNT_E" != "0" ){ 
        $HideRete="";
        $ReteErrore=1; 
        $ColorRete="rgb(198, 66, 66)";
        $ShowNet=$ShowNet.','.$RETE;
        $ShowStep=$ShowStep.','.$RETE;
        $STATUS='E';
    }
    
    }   
    
    if ( "$OLDMIN" != "" ){
      $OldTrueTime=0;      
      $IdRunOld=0;
      $ListDone="";
      $Sql='CALL WORK_CORE.K_TRUE_TIME.TrueTime(?, ?, ?, ?)';
      $stmt = db2_prepare($conn, $Sql);
      db2_bind_param($stmt, 1,  "RETE"       , DB2_PARAM_IN);
      db2_bind_param($stmt, 2,  "ListOldId"  , DB2_PARAM_IN);
      db2_bind_param($stmt, 3,  "ListDone"   , DB2_PARAM_IN);
      db2_bind_param($stmt, 4,  "OldTrueTime", DB2_PARAM_OUT);
      $result=db2_execute($stmt);
    //  echo "<br>OldTrueTime:".$OldTrueTime;
 
      if (!$result){
         echo "ERROR DB2 TrueTime Old:".db2_stmt_errormsg();
      }
    // $OldTrueTime2 = $_modelReti->callTrueOldTime($RETE, $ListOldId, $ListDone, $OldTrueTime);
     // echo " OldTrueTime:".$OldTrueTime;
    //  echo "<br>";
    }
    if ( "$NOWMIN" != "" ){
      $TrueTime=0;
      $IdRunNow=0;
      $ListDone="";
      
      if ( "$NOW_ESERMESE" == "$EserMese" ){          
        $LisIdT=$ListNowId;
      }else{
        $LisIdT=$ListNowOldId;
      }
              
      $Sql='CALL WORK_CORE.K_TRUE_TIME.TrueTime(?, ?, ?, ?)';
      $stmt = db2_prepare($conn, $Sql);
      db2_bind_param($stmt,1,"RETE"     , DB2_PARAM_IN);
      db2_bind_param($stmt,2,"LisIdT", DB2_PARAM_IN);
      db2_bind_param($stmt,3,"ListDone" , DB2_PARAM_IN);
      db2_bind_param($stmt,4,"TrueTime" , DB2_PARAM_OUT);
      $result=db2_execute($stmt);
      if ( ! $result ){
         echo "ERROR DB2 TrueTime:".db2_stmt_errormsg();
      }
     // echo "<br>TrueTime:".$TrueTime;
  
      //$TrueTime2 = $_modelReti->callTrueTime($RETE, $LisIdT, $ListDone, $TrueTime);
      /*echo " TrueTime:".$TrueTime;
      echo "<br>";*/
    
    }
   
   ?><li class="liReti <?php if ( "$ToDo" == "ToDo" ){ ?>ToDo<?php } ?> R<?php echo $RETE; ?> Esito<?php echo $STATUS; ?>"  <?php echo $HideRete; ?> >
   <div class="RETE" onclick="OpenRete('<?php echo $RETE; ?>')" style="background:<?php echo $ColorRete; ?>;" >
   <?php 
   if ( $ReteConclusa == 1){ ?><img class="ReteConclusa" src="./images/OK.png" width="25px;" ><?php } 
   if ( $ReteErrore   == 1){ ?><img class="ReteConclusa" src="./images/KO.png" width="25px;" ><?php } 
   echo $RETE; 
   if ( "$RETE" != "${'OLDNAME'.$RETE}" and "${'OLDNAME'.$RETE}" != "-" ){ echo " [${'OLDNAME'.$RETE}]"; }
   if ( in_array($RETE,$RetiRich) ){ ?><img class="ReteRichiesta" title="Rete a Richiesta" src="./images/Stella.png" width="25px;" ><?php } 
   ?>
   <div class="TimeOld" >
  <?php
    $TstLong=0;
    if ( $TrueTime >= $OldTrueTime ){
      $TstLong=$TrueTime-$OldTrueTime;
    } else {
      $TstLong=1;
    }
    if ( "$OLDMIN" != "" and $TstLong == 1 ){
        $GAP=$Soglia;
        $SecLast=$TrueTime;
        $SecPre=$OldTrueTime;
        if ( "$SecPre" == "0" ){
            $SecPre = 1;
        }
        $Perc = round(( $SecLast * 100 ) / $SecPre );

        if ( $Perc <= 100 and "$STATUS" != "I" ) {
            $SColor = "$BarraMeglio";
        }
        
        if ( "$STATUS" == "I" ) {
            $SColor = "$BarraCaricamento";
        }   
        if ( $Perc > 120 ) {
            $SColor = "$BarraPeggio";
        }      
        
        if (
            (   1==1
                and ( $Perc > 120 or $Perc < 90 ) 
                and  ( "$STATUS" == "F" or "$STATUS" == "W" )
                and ( $SecLast - $SecPre > $GAP or $SecLast - $SecPre < -$GAP )
            ) 
            or ( "$STATUS" == "I" )
        ) {
            ?>
            <table class="tabella1" style="width:100%;">
            <tr><td style="padding:unset;background-color: white;height: 20px !important;border-color: transparent !important;">
                <div class="progress-bar progress-bar-striped <?php 
                if ("$STATUS" == "I") {
                    echo "active";
                } 
                ?>" role="progressbar" aria-valuenow="<?php echo $Perc; ?>" aria-valuemin="0" aria-valuemax="100" style="text-align:right;padding-right: 5px;min-width: 2em; width: <?php 
                if ($Perc > 100) {
                    echo 100;
                } else {
                    echo "$Perc";
                } 
                ?>%;background-color: <?php echo "$SColor"; ?>;height: 20px;border:1px solid white;float:left;" ><LABEL style="font-weight: 500;"><?php
                if ($Perc > 100) {
                    $Perc = $Perc - 100;
                    $Perc = "+" . $Perc;
                } else {
                    if ( "$STATUS" != "I" ){
                      $Perc = $Perc - 100;
                    } 
                }                                                                       
                echo $Perc;
                ?>%</LABEL>
                </div>
            </td></tr>
            <table>
            <?php
        }
    }
    ?>
   <table class="tabreti tabella2 ExcelTable">
   <tr>
   <th class="NoBordBot Esito<?php echo $STATUS; ?>"></th>
   <th class="BordBot Esito<?php echo $STATUS; ?>">Start</th>
   <th class="BordBot Esito<?php echo $STATUS; ?>">End</th>
   <th class="BordBot Esito<?php echo $STATUS; ?>">DieTime</th>
   <th class="BordBot Esito<?php echo $STATUS; ?>">TrueTime</th>
   </tr>
   <?php if ( "$NOW_ESERMESE" == "$EserMese" ){ ?>
     <tr>
         <td class="NoBordBot Esito<?php echo $STATUS; ?>" style="width:30px;" >Old</td>
         <td class="BordBot"><?php echo "$OLDMIN";  ?></td>
         <td class="BordBot"><?php echo "$OLDMAX";  ?></td>
         <td class="BordBot"><?php if ( "$OLDMIN" != "" ){ echo floor(($OLDDIFF-1)/(60*60*24))."g "; echo gmdate('H:i:s', $OLDDIFF);    }  ?></td>
         <td class="BordBot"><?php if ( "$OLDMIN" != "" ){ echo floor(($OldTrueTime-1)/(60*60*24))."g "; echo gmdate('H:i:s', $OldTrueTime);}  ?></td>
     </tr>
     <tr>
         <td class="Esito<?php echo $STATUS; ?>" style="width:30px;" >Now</td>
         <?php if ( "$NOWMAX" != "$OLDMAX" ){ ?>
         <td class="BordBot"><?php echo "$NOWMIN";  ?></td>
         <td class="BordBot"><?php echo "$NOWMAX";  ?></td>
         <td class="BordBot"><?php if ( "$NOWMIN" != "" ){ echo floor(($DIFF-1)/(60*60*24))."g "; echo gmdate('H:i:s', $DIFF);     } ?></td>
         <td class="BordBot"><?php if ( "$NOWMIN" != "" ){ echo floor(($TrueTime-1)/(60*60*24))."g "; echo gmdate('H:i:s', $TrueTime); } ?></td>
         <?php }else{ ?>
         <td class="BordBot"></td>
         <td class="BordBot"></td>
         <td class="BordBot"></td>
         <td class="BordBot"></td>
         <?php } ?>
     </tr>
   <?php } else { ?>
   <tr>
         <td class="NoBordBot Esito<?php echo $STATUS; ?>" style="width:30px;" >Old</td>
         <td class="BordBot"><?php echo "$OLDMIN";  ?></td>
         <td class="BordBot"><?php echo "$OLDMAX";  ?></td>
         <td class="BordBot"><?php if ( "$OLDMIN" != "" ){ echo floor(($OLDDIFF-1)/(60*60*24))."g "; echo gmdate('H:i:s', $OLDDIFF);    }  ?></td>
         <td class="BordBot"><?php if ( "$OLDMIN" != "" ){ echo floor(($OldTrueTime-1)/(60*60*24))."g "; echo gmdate('H:i:s', $OldTrueTime);}  ?></td>
     <?php
         /*
         <td class="NoBordBot Esito<?php echo $STATUS; ?>" style="width:30px;" >Old</td>
         <td class="BordBot"><?php echo "$NOWMIN";  ?></td>
         <td class="BordBot"><?php echo "$NOWMAX";  ?></td>
         <td class="BordBot"><?php if ( "$NOWMIN" != "" ){ echo floor(($DIFF-1)/(60*60*24))."g "; echo gmdate('H:i:s', $DIFF);     } ?></td>
         <td class="BordBot"><?php if ( "$NOWMIN" != "" ){ echo floor(($TrueTime-1)/(60*60*24))."g "; echo gmdate('H:i:s', $TrueTime); } ?></td>
         */
         ?>     
     </tr>
     <tr>
         <td class="Esito<?php echo $STATUS; ?>" style="width:30px;" >Now</td>
         <td class="BordBot"></td>
         <td class="BordBot"></td>
         <td class="BordBot"></td>
         <td class="BordBot"></td>
     </tr>   
   <?php }  ?>
   </table>
   </div>
   </div>

   <div class="ReteDett <?php echo $RETE; ?>" onclick="OpenRete('<?php echo $RETE; ?>')" <?php echo $HidePasso; ?>>
   </div>

   <ul class="Esito<?php echo $STATUS; ?>"><?php
   if ( ! empty(${'Old'.$RETE}) ){
       foreach( ${'Old'.$RETE} as $OldPasso ) {
            $PASSO=$OldPasso[0];
            $STATUS=$OldPasso[1];
            $START_TIME=$OldPasso[2];
            $END_TIME=$OldPasso[3];
            $ID_RUN_SH=$OldPasso[4];
            $SEC_DIFF=$OldPasso[5]; 
            
            $ID_RUN_SH_OLD=$OldPasso[6]; 
            $OLD_INIZIO=$OldPasso[7]; 
            $OLD_FINE=$OldPasso[8]; 
            $OLD_DIFF=$OldPasso[9]; 
            $OLD_DATE=$OldPasso[10]; 
            $NOW_DATE=$OldPasso[11]; 
            $OLD_ESER_MESE=$OldPasso[13]; 
            
			$OLDNAMEPASSO=$OldPasso[15]; 
			
            ?>
            <li class="liPassi S<?php echo $RETE; ?> " <?php echo $HidePasso; ?> >
               <div class="Old" >
               <table class="tabreti tabella3" class="ExcelTable TabDett" >
                    <tr>
                    <td class="Esito EsitoN" ><div class="Passo" ><?php echo $PASSO; 
					if ( "$PASSO" != "$OLDNAMEPASSO" and "$OLDNAMEPASSO" != "-" ){ echo " [$OLDNAMEPASSO]"; }
					?></div></td>                
                    </tr>
                   </table>
                   <table class="tabreti tabella5 ExcelTable">
                   <tr >
                   <th class="NoBordBot EsitoN"></th>
                   <th class="BordBot EsitoN">Start</th>
                   <th class="BordBot EsitoN">End</th>
                   <th class="BordBot EsitoN">Time</th>
                   <th class="BordBot EsitoN" ></th>
                   </tr>
                   <tr >
                       <td class="NoBordBot EsitoN" style="width:30px;" >Old</td>
                       <td class="BordBot" ><?php echo "$START_TIME";  ?></td>
                       <td class="BordBot" ><?php echo "$END_TIME";  ?></td>
                       <td class="BordBot" ><?php if ( "$SEC_DIFF" != "" ){ echo floor($SEC_DIFF/(60*60*24))."g "; echo gmdate('H:i:s', $SEC_DIFF); } ?></td>
                       <td class="BordBot"  style="width: 30px;" >
                       <?php if ( "$ID_RUN_SH" != "" ){ ?>
                       <img class="ImgIco" style="height:20px;cursor:pointer;" src="./images/LogProc.png" onclick="OpenLink(<?php echo $ID_RUN_SH; ?>,'<?php echo $PASSO; ?>','<?php echo $RETE; ?>','')" >
                       <?php } ?>
                       <?PHP /*
                       <td class="BordBot" ><?php echo "$OLD_INIZIO";  ?></td>
                       <td class="BordBot" ><?php echo "$OLD_FINE";  ?></td>
                       <td class="BordBot" ><?php if ( "$OLD_DIFF" != "" ){ echo floor($OLD_DIFF/(60*60*24))."g "; echo gmdate('H:i:s', $OLD_DIFF); } ?></td>
                       <td class="BordBot"  style="width: 30px;" >
                       <?php if ( "$ID_RUN_SH_OLD" != "" ){ ?>
                       <img class="ImgIco" style="height:20px;cursor:pointer;" src="./images/LogProc.png" onclick="OpenLink(<?php echo $ID_RUN_SH_OLD; ?>,'<?php echo $PASSO; ?>','<?php echo $RETE; ?>','')" >
                       <?php } ?>
                       ?*/ ?>
                       </td>
                   </tr>
                   </table>
                   <div id="ShowDett<?php echo $ID_RUN_SH_OLD.$PASSO; ?>" class="<?php echo $RETE; ?>" hidden  ></div>
                   <table class="tabreti tabella6 ExcelTable">     
                   <tr>
                       <td class="EsitoN"  style="width:30px;" >Now</td>
                       <td class="BordBot" ></td>
                       <td class="BordBot" ></td>
                       <td class="BordBot" ></td>
                       <td class="BordBot"  style="width: 30px;" >
                       <?PHP /*
                       <td class="BordBot" ><?php echo "$START_TIME";  ?></td>
                       <td class="BordBot" ><?php echo "$END_TIME";  ?></td>
                       <td class="BordBot" ><?php if ( "$SEC_DIFF" != "" ){ echo floor($SEC_DIFF/(60*60*24))."g "; echo gmdate('H:i:s', $SEC_DIFF); } ?></td>
                       <td class="BordBot"  style="width: 30px;" >
                       <?php if ( "$ID_RUN_SH" != "" ){ ?>
                       <img class="ImgIco" style="height:20px;cursor:pointer;" src="./images/LogProc.png" onclick="OpenLink(<?php echo $ID_RUN_SH; ?>,'<?php echo $PASSO; ?>','<?php echo $RETE; ?>','')" >
                       <?php } ?>
                       ?*/ ?>
                       </td>
                   </tr>
                   </table>
                   <div id="ShowDett<?php echo $ID_RUN_SH.$PASSO; ?>" class="<?php echo $RETE; ?>" hidden  ></div>
               </div>
           </li>
           <?php
       }
   }
   if ( ! empty(${'New'.$RETE}) ){
        foreach( ${'New'.$RETE} as $NewPasso ) {
            $PASSO=$NewPasso[0];
            $STATUS=$NewPasso[1];
            $START_TIME=$NewPasso[2];
            $END_TIME=$NewPasso[3];
            $ID_RUN_SH=$NewPasso[4];
            $SEC_DIFF=$NewPasso[5];
            
            $ID_RUN_SH_OLD=$NewPasso[6]; 
            $OLD_INIZIO=$NewPasso[7]; 
            $OLD_FINE=$NewPasso[8]; 
            $OLD_DIFF=$NewPasso[9]; 
            $OLD_DATE=$NewPasso[10]; 
            $NOW_DATE=$NewPasso[11];
            $OGGI=$NewPasso[12]; 
            $OLD_ESER_MESE=$NewPasso[13]; 
            $PRWEND=$NewPasso[14]; 
            
			$OLDNAMEPASSO=$NewPasso[15]; 
			
            if ( "$NOW_DATE" == "" ){ $NOW_DATE=$OGGI; }    
           ?>
           <li class="liPassi S<?php echo $RETE; ?>" <?php echo $HidePasso; ?> >
               <?php
               if ( in_array($ID_RUN_SH,$ArrRepPasso) ){
                 ?><div class="RepPasso"><img src="./images/Repeat.png" class="Repeat" ></div><?php 
               }
               ?>
               <div class="New DivP DivP<?php echo $ID_RUN_SH.$PASSO; ?>"  >
                    <table class="tabreti tabella7 ExcelTable TabDett" >
                    <tr>
                        <td class="Esito Esito<?php echo $STATUS; ?>" ><div class="Passo" ><?php echo $PASSO; 
					if ( "$PASSO" != "$OLDNAMEPASSO" and "$OLDNAMEPASSO" != "-" ){ echo " [$OLDNAMEPASSO]"; }
					?></div></td>
                    </tr>
                    </table>
                        <?php
                        if ( "$OLD_DIFF" != "" ){
                                $GAP=$Soglia;
                                $SecLast=$SEC_DIFF;
                                $SecPre=$OLD_DIFF;
                                if ( "$SecPre" == "0" ){
                                    $SecPre = 1;
                                }
                                $Perc = round(( $SecLast * 100 ) / $SecPre );

                                if ( $Perc <= 100 and "$STATUS" != "I" ) {
                                    $SColor = "$BarraMeglio";
                                }
                                if (  "$STATUS" == "I" ) {
                                    $SColor = "$BarraCaricamento";
                                }   
                                if ( $Perc > 120 ) {
                                    $SColor = "$BarraPeggio";
                                }   
                                
                                if (
                                    (   1==1
                                        and ( $Perc > 120 or $Perc < 90 ) 
                                        and  ( "$STATUS" == "F" or "$STATUS" == "W" )
                                        and ( $SecLast - $SecPre > $GAP or $SecLast - $SecPre < -$GAP )
                                    ) 
                                    or ( "$STATUS" == "I" )
                                ) {
                                    ?>
                                     <table style="width:100%;">
                                     <tr>
                                     <td style="padding:unset;background-color: white !important;height: 20px !important;border:none;border-color: transparent !important;">
                                        <div class="progress-bar progress-bar-striped <?php 
                                        if ("$STATUS" == "I") {
                                            echo "active";
                                        } 
                                        ?>" role="progressbar" aria-valuenow="<?php echo $Perc; ?>" aria-valuemin="0" aria-valuemax="100" style="text-align:right;padding-right: 5px;min-width: 2em; width: <?php 
                                        if ($Perc > 100) {
                                            echo 100;
                                        } else {
                                            echo "$Perc";
                                        } 
                                        ?>%;background-color: <?php echo "$SColor"; ?>;height: 20px;border:1px solid white;float:left;" ><LABEL style="font-weight: 500;"><?php
                                        if ($Perc > 100) {
                                            $Perc = $Perc - 100;
                                            $Perc = "+" . $Perc;
                                        } else {
                                            if ( "$STATUS" != "I" ){
                                              $Perc = $Perc - 100;
                                            } 
                                        }                                       
                                        echo $Perc;
                                        ?>%</LABEL>
                                        </div>
                                   </td>                           
                                   </tr>
                                   </table>
                                    <?php
                                }
                        }
                            ?> 
                   <table class="tabreti tabella8 ExcelTable" >
                   <tr>
                   <th class="NoBordBot Esito<?php echo $STATUS; ?>"></th>
                   <th class="BordBot Esito<?php echo $STATUS; ?>">Start</th>
                   <th class="BordBot Esito<?php echo $STATUS; ?>">End</th>
                   <th class="BordBot Esito<?php echo $STATUS; ?>">Time</th>
                   <th class="BordBot Esito<?php echo $STATUS; ?>"></th>
                   </tr>
                   <tr>
                       <td class="NoBordBot Esito<?php echo $STATUS; ?>" width="30px" >Old</td>
                       <td class="BordBot"><?php echo "$OLD_INIZIO";  ?></td>
                       <td class="BordBot"><?php echo "$OLD_FINE";  ?></td>
                       <td class="BordBot"><?php if ( "$OLD_DIFF" != "" ){ echo floor($OLD_DIFF/(60*60*24))."g ";echo gmdate('H:i:s', $OLD_DIFF); } ?></td>
                       <td class="BordBot" style="width: 30px;"  >
                       <?php if ( "$ID_RUN_SH_OLD" != "" ){ ?>
                       <img class="ImgIco" style="height:20px;cursor:pointer;" src="./images/LogProc.png" onclick="OpenLink(<?php echo $ID_RUN_SH_OLD; ?>,'<?php echo $PASSO; ?>','<?php echo $RETE; ?>','')" >
                       <?php } ?>
                       </td>
                   </tr>
                   </table>
                   <div id="ShowDett<?php echo $ID_RUN_SH_OLD.$PASSO; ?>" class="<?php echo $RETE; ?>" hidden  ></div>
                   <table class="tabreti tabella9 ExcelTable">                                
                   <tr>
                       <td class="Esito<?php echo $STATUS; ?>" width="30px" >Now</td>
                       <td><?php echo "$START_TIME";  ?></td>
                       <td><?php 
                       if ( "$STATUS" == "I" ){
                           if ( "$ID_RUN_SH_OLD" != "" ){
                             echo "Preview End:<BR>".$PRWEND;
                           }
                       }else{
                           echo "$NOW_DATE";  
                       }
                       ?></td>
                       <td><?php echo floor($SEC_DIFF/(60*60*24))."g ";echo  gmdate('H:i:s', $SEC_DIFF);  ?></td>
                       <td style="width: 30px;" >
                       <?php if ( "$ID_RUN_SH" != "" ){ ?>
                       <img class="ImgIco" style="height:20px;cursor:pointer;" src="./images/LogProc.png" onclick="OpenLink(<?php echo $ID_RUN_SH; ?>,'<?php echo $PASSO; ?>','<?php echo $RETE; ?>','<?php echo $OLD_ESER_MESE; ?>')" >
                       <?php } ?>
                       </td>
                   </tr>
                   </table>
                   <div id="ShowDett<?php echo $ID_RUN_SH.$PASSO; ?>" class="<?php echo $RETE; ?>" hidden  ></div>
               </div>
           </li>
           <?php 
             $OpenId=$_POST['OpenId'.$RETE.$ID_RUN_SH];
             ?>
             <input type="hidden" id="List<?php echo $RETE.$ID_RUN_SH; ?>" value="<?php echo $ListId; ?>" />
             <input type="hidden" name="OpenId<?php echo $RETE.$ID_RUN_SH; ?>" id="OpenId<?php echo $RETE.$ID_RUN_SH; ?>" value="<?php echo $OpenId; ?>" />
             <?php
             /*
             if( "$OpenId" == "1" ){
                 ?>
                 <script>
                    $('#ShowDett<?php echo $ID_RUN_SH.$PASSO; ?>').empty().load('./PHP/StatoShell.php', { 
                    'DA_RETITWS': '1', 
                    'IDSEL':      '<?php echo $ID_RUN_SH; ?>', 
                    'INRETE':     '<?php echo $RETE; ?>',
                    'LISTOPEN':   '<?php echo $ListId; ?>'
                    }).show();
                 </script>
                 <?php
             }
             */
        }
   }
   ?></ul></li><?php
}
?>
</ul>
<?php
if ( $ArrInCorso ){
  ?>
  <div id="RetiInCorso">
     <table class="tabreti tabella10 ExcelTable">
      <tr><th class="EsitoI" style="text-align:center;" >RETI IN EXEC</th></tr>
    <?php
    foreach( $ArrInCorso as $Rt ) {
      ?><tr><td><?php echo $Rt; ?></td></tr><?php
    }
    ?>
    </table>
  </div>
  <?php
}
?>
  </FORM>
<BR>
<BR>
<script>
$(document).ready(function () {
//	alert("ciao");
$('#Waiting').hide();
});
function OpenLink(vIdRunSh,vPasso,vRete,vEserMese){
   var vListStep=$('#ListOpenStep').val();  
    $('#Waiting').hide();
  var formData={
                    'DA_RETITWS': '1', 
                    'IDSEL':      vIdRunSh, 
                    'INRETE':     vRete,
                    'LISTOPEN':   $('#List'+vRete+vIdRunSh).val(),
                    'SelLastMeseElab': vEserMese
            };
            alert('#ShowDett'+vIdRunSh+vPasso);
   if ( $('#ShowDett'+vIdRunSh+vPasso).is(':empty') ){
 $.ajax({
          type: "POST",
		   data: formData,
		   encode: true,	  
           
          // specifico la URL della risorsa da contattare
           url: "index.php?controller=statoshell&action=contentList",  
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
           $('#ShowDett'+vIdRunSh+vPasso).html(risposta).show();
		  
				
          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            alert("Qualcosa è andato storto");
          }
        });
		 
		
      /*  $('#ShowDett'+vIdRunSh+vPasso).empty().load('./index.php?controller=statoshell&action=contentList',{
                    'DA_RETITWS': '1', 
                    'IDSEL':      vIdRunSh, 
                    'INRETE':     vRete,
                    'LISTOPEN':   $('#List'+vRete+vIdRunSh).val(),
                    'SelLastMeseElab': vEserMese
            }).show();*/
        $('#OpenId'+vRete+vIdRunSh).val(1);
        vListStep=vListStep+','+vPasso+'_'+vIdRunSh+'_'+vRete+'_'+vEserMese;
   } else {
        vListStep=vListStep.replace(','+vPasso+'_'+vIdRunSh+'_'+vRete+'_'+vEserMese,'');
        $('.DivP').each(function(){ $(this).css('border','none'); });
        $('#ShowDett'+vIdRunSh+vPasso).empty();
        $('#OpenId'+vRete+vIdRunSh).val(0);
   }  

   $('#ListOpenStep').val(vListStep);    
}

function OpenRete(vRete){
   var vListNet=$('#ListOpenNet').val();
   var vListNetOld=$('#ListOpenNet').val();
    $('.S'+vRete).each(function(){ 
       if ( $(this).is(':visible') ){       
            $(this).hide();
            vListNet=vListNet.replace(','+vRete,'');
       } else {
            $(this).show();
            if ( vListNetOld == vListNet ) {
                vListNet=vListNet+','+vRete;
            }
       }
   });
   $('#ListOpenNet').val(vListNet);
}

function Refresh1(){
  $('#Waiting').show();
  $('#TopScrollShell1').val($('body').scrollTop());  
  $('#LeftScrollShell1').val($('body').scrollLeft());
  $('#FormRetiTWS').submit();
};

$('#FormRetiTWS').submit(function(){
  $('#TopScrollShell1').val($('body').scrollTop());  
  $('#LeftScrollShell1').val($('body').scrollLeft());
});


setInterval(function(){ 
    if ( $('#AutoRefresh11').is(':checked') ){
    Refresh1(); 
    }
}, 30000);




function ReOpenNet(){
    var vListNet=$('#ListOpenNet').val();
    if ( vListNet != '' ){
        var NetArray = vListNet.split(',');
        for( var i=1; i<NetArray.length; i++ ){
            var vRete=NetArray[i];
            $('.R'+vRete).each(function(){ 
                 $(this).show();
                 $('.S'+vRete).each(function(){$(this).show();});
            }); 
        }
    }
}
ReOpenNet();


function ReOpenStep(){
    var vListStep=$('#ListOpenStep').val();
    if( vListStep != '' ){
        var StepArray = vListStep.split(',');
        for( var i=1; i<StepArray.length; i++ ){
            var vTargets=StepArray[i];
            var vTarget=vTargets.split('_');
            var vPasso=vTarget[0];
            var vIdRunSh=vTarget[1];
            var vRete=vTarget[2];
            var vEserMese=vTarget[3];
            $('#ShowDett'+vIdRunSh+vPasso).empty().load('./PHP/StatoShell.php', {
                    'DA_RETITWS': '1', 
                    'IDSEL':      vIdRunSh, 
                    'INRETE':     vRete,
                    'LISTOPEN':   $('#List'+vRete+vIdRunSh).val(),
                    'SelLastMeseElab': vEserMese   
                }).show();                      
        }
    }
}
ReOpenStep();

var vShowNet='<?php echo $ShowNet; ?>';
var OpenArray = vShowNet.split(',');
for( var i=1; i<OpenArray.length; i++ ){
    var vNet=OpenArray[i];
    $('.R'+vNet).show();
}

var ShowStep='<?php echo $ShowStep; ?>';
var OpenArray = ShowStep.split(',');
for( var i=1; i<OpenArray.length; i++ ){
    var vStep=OpenArray[i];
    $('.S'+vStep).each(function(){ $(this).show(); });
}

$('#ShowHidden').change(function(){ 
  $('.ToDo').each(function(){ $(this).hide(); }); 
  if ( $('#ShowHidden').is(':checked') ){
    $('.ToDo').each(function(){ $(this).show(); }); 
  }
});

$('.ToDo').each(function(){ $(this).hide(); }); 
if ( $('#ShowHidden').is(':checked') ){
$('.ToDo').each(function(){ $(this).show(); }); 
}

$('body').scrollTop($('#TopScrollShell1').val());
$('body').scrollLeft($('#LeftScrollShell1').val());

</script>
