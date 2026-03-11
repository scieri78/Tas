<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

?>
<style>

.IconSh{
   width:30px;
   float:right;
   cursor:pointer;
}

.IconFile{
   float:right;
   cursor:pointer;
   height: 23px;
   width: auto;
}

.EsitoE{
    background:rgb(198, 66, 66) !important;
}
.EsitoI{
    background:rgb(192, 181, 54) !important;
}
.EsitoF{
    background:rgb(67, 168, 51) !important;
}
.EsitoW{
    background:orange !important;
}
.EsitoN{
    background:#838383 !important;
}
.EsitoFN{
    background:rgb(59, 103, 52) !important;
}
.Bottone{
box-shadow: 0px 1px 2px 0px black inset;
cursor: pointer;
width: 68px;
height: 25px;
background: #5c5c84;
color: white;
padding: 5px;
text-align: center;
margin: 5px;
}

tr{
  height: 20px; 
}

td{
    color:white;
}
th{
    width:100px;
}

#CandInDef{
    width:800px;
    height:30px;
    margin:10px;
    box-shadow: 0px 1px 2px 0px black inset;
    color: white;
    padding: 3px;
    font-size: 18px;
}
#CuboApp{
    width:98%;
    height:30px;
    box-shadow: 0px 1px 2px 0px black inset;
    color: white;
    padding: 3px;
    font-size: 18px;
    text-align: center;
}
#CuboSolvency{
    width:98%;
    height:30px;
    box-shadow: 0px 1px 2px 0px black inset;
    color: white;
    padding: 3px;
    font-size: 18px;
    text-align: center;
    margin-top:16px;
}

#GiroSolvency{
    width:98%;
    height:80px;
    box-shadow: 0px 1px 2px 0px black inset;
    color: white;
    padding: 3px;
    font-size: 18px;
    padding-top: 30px;
    text-align: center;
}

.ShowDett{
    position:absolute;
    margin-top:30px;
    width:220px;
    height:65px;
    background:white;
    border:black;
    color:black;
    z-index:999;
    text-align:left;
}

.ShowPostDett{
    position:absolute;
    width:220px;
    height:70px;
    background:white;
    border:black;
    color:black;
    z-index:999;
    text-align:left;
    left:0;
    right:0;
    margin:auto;
    margin-top:4px;
}

.TabShow{
    width:400px;
    height: 30px;
    border: 1px solid black;
    padding:5px;
    cursor:pointer;
    font-align:center;
}

.Up{
    color:black;
    background:white;
}

.Down{
    color:white;
    background:gray;
    
}

.Lancio{
    width: 100%;
    height: 25px;
    margin: 2px;
    border: 1px solid black;
}

.InSel{
   border: 3px solid white !important;
}
</style>

<?php

include '../GESTIONE/sicurezza.php';
if ( $find == 1 )  {

$TestRest=shell_exec("ssh ${SSHUSR}@${SERVER} -o StrictHostKeyChecking=no \"find /area_staging_TAS/DIR_SHELL/UNIFY/ -name Restatement.sem |wc -l |tr -d ' '\"");
$TestRest=substr($TestRest,0,1);
if ( "$TestRest" == "1" ){
  ?><CENTER><div style="font-size: 15px;color:red;"><b>ATTENTION!! RESTATEMENT IS ENABLED!!</b></div><CENTER><BR><BR><?php
}

    $conn = db2_connect($db2_conn_string, '', '');
    
   
$TABSHOW=$_POST['TABSHOW'];  

    $ArrAZ=array();
    $ArrAZV=array('LoB_4','LoB_5','LoB_11');
    $ArrCRD=array('LoB_4','LoB_5','LoB_6','LoB_7','LoB_11','LoB_12','LoB_14','LoB_15');
    $ArrGNL=array('LoB_4','LoB_5','LoB_6','LoB_7','LoB_11','LoB_12','LoB_14','LoB_15');
   
    $sql = "SELECT DISTINCT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Compagnia'";        
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);

    if ( ! $result ){
        echo $sql;
        echo "ERROR DB2 Comp";
    }
    $Arr_CodComp=array();
    while ($row = db2_fetch_assoc($stmt)) {
         $CodComp=$row['VALORE'];
         array_push($Arr_CodComp,$CodComp);
    }

    $sql = "SELECT * FROM (
SELECT DISTINCT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'LineOfBusiness' AND ID_TIPO_EXEC = 1
) ORDER BY INT(REPLACE(UPPER(VALORE),'LOB_',''))";        
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);

    if ( ! $result ){
        echo $sql;
        echo "ERROR DB2 Lob";
    }
    $Arr_Lob=array();
    while ($row = db2_fetch_assoc($stmt)) {
         $Lob=$row['VALORE'];
         array_push($Arr_Lob,$Lob);
    }
     

if ( "$TABSHOW" == "TUTTI" ){

   $ClsCand="Down";
   $ClsTutti="Up";
   
    $sql = "SELECT
    A.*,( SELECT VALORE FROM UNIFY.LOAD_INFO  WHERE CAMPO = 'ID_RUN_SH' AND ID_INFO = A.ID_INFO ) ID_RUN_SH   
    FROM (
      SELECT 
        COMPAGNIA,
        LINEOFBUSINESS,
        STATUS,
        ID_INFO,
        DESCRIZIONE,
        FILEOUTPUTUFFICIALE1,
        FILEOUTPUTUFFICIALE2,
        TO_CHAR(DATA_INFO,'YYYY-MM-DD HH24:MI:SS') DATA_INFO,
        timestampdiff(2,NVL(SHELL_START,CURRENT_TIMESTAMP)-DATA_INFO) WAIT,
        SHELL_NAME, 
        TO_CHAR(SHELL_START,'YYYY-MM-DD HH24:MI:SS') SHELL_START,
        TO_CHAR(SHELL_END,'YYYY-MM-DD HH24:MI:SS') SHELL_END,
        timestampdiff(2,NVL(SHELL_END,CURRENT_TIMESTAMP)-SHELL_START) DIFF,
        NVL(SHELL_STATUS,STATUS) SHELL_STATUS
        ,VERSIONEUTENTE
        , (
          SELECT count(*)
          FROM UNIFY.V_LOSSRES_STORICO A
          WHERE 1=1
          AND ANNO = V.ANNO
          AND MESE = V.MESE
          AND COMPAGNIA = V.COMPAGNIA
          AND NVL(LINEOFBUSINESS,'-') = NVL(V.LINEOFBUSINESS,'-')
          AND ID_INFO IN (
            SELECT MAX(ID_INFO)
            FROM UNIFY.V_LOSSRES_STORICO B
            WHERE 1=1
            AND ANNO = A.ANNO
            AND MESE = A.MESE
            AND COMPAGNIA = A.COMPAGNIA
            AND NVL(LINEOFBUSINESS,'-') = NVL(A.LINEOFBUSINESS,'-')
            GROUP BY ANNO,MESE,COMPAGNIA,LINEOFBUSINESS,VERSIONEUTENTE
          )   
          GROUP BY ANNO,MESE,COMPAGNIA,LINEOFBUSINESS   
          ) CNTVERS
          , ( 
             SELECT count(*) FROM UNIFY.LOSSRESSTATUS B
                WHERE 1=1
                AND ANNO = V.ANNO
                AND MESE = V.MESE
                AND LINEOFBUSINESS IS NOT NULL
                AND ID_INFO = V.ID_INFO
                AND VERSIONE_DB IN ( 
                  SELECT ID_CU FROM LOSS_RESERVING.CATALOG_CU_LOB 
                  WHERE 1=1
                  AND CONSUNIT  = ( SELECT DISTINCT VALORE_TAS FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Compagnia'      AND ID_INFO = B.ID_INFO )
                  AND LOB       = ( SELECT DISTINCT VALORE_TAS FROM UNIFY.LOAD_INFO WHERE CAMPO = 'LineOfBusiness' AND ID_INFO = B.ID_INFO )
                  AND FLAG_TIPO = 1  
                )     
          
          ) CAND
          , ( 
             SELECT count(*) FROM UNIFY.LOSSRESSTATUS B
                WHERE 1=1
                AND ANNO = V.ANNO
                AND MESE = V.MESE
                AND ID_INFO = V.ID_INFO
                AND VERSIONE_DB IN ( 
                  SELECT ID_CU_CONT FROM LOSS_RESERVING.CATALOG_CU_CONT 
                  WHERE 1=1
                  AND CONSUNIT  = ( SELECT DISTINCT VALORE_TAS FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Compagnia'      AND ID_INFO = B.ID_INFO )
                  AND FLAG_TIPO = 1  
                )     
          
          ) CAND_CONT         
      FROM UNIFY.V_LOSSRES_STORICO V
      WHERE 1=1
      AND ANNO = ( SELECT SUBSTRING(VALORE,1,4) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
      AND MESE = ( SELECT INT(SUBSTRING(VALORE,6,2)) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
      AND ID_INFO IN (
        SELECT MAX(ID_INFO)
        FROM UNIFY.V_LOSSRES_STORICO A
        WHERE 1=1
        AND ANNO = V.ANNO
        AND MESE = V.MESE
        AND COMPAGNIA = V.COMPAGNIA
        AND NVL(LINEOFBUSINESS,'-') = NVL(V.LINEOFBUSINESS,'-')
        GROUP BY ANNO,MESE,COMPAGNIA,LINEOFBUSINESS,VERSIONEUTENTE
      )
      ORDER BY 
      COMPAGNIA,
      LINEOFBUSINESS,
      VERSIONEUTENTE
    ) A
   ";
} else {

   $ClsCand="Up";
   $ClsTutti="Down";

    $sql = "
    SELECT 
      COMPAGNIA,
      LINEOFBUSINESS,
      STATUS,
      ID_INFO,
      DESCRIZIONE,
      FILEOUTPUTUFFICIALE1,
      FILEOUTPUTUFFICIALE2,
      TO_CHAR(DATA_INFO,'YYYY-MM-DD HH24:MI:SS') DATA_INFO,
      timestampdiff(2,NVL(SHELL_START,CURRENT_TIMESTAMP)-DATA_INFO) WAIT,
      SHELL_NAME, 
      TO_CHAR(SHELL_START,'YYYY-MM-DD HH24:MI:SS') SHELL_START,
      TO_CHAR(SHELL_END,'YYYY-MM-DD HH24:MI:SS') SHELL_END,
      timestampdiff(2,NVL(SHELL_END,CURRENT_TIMESTAMP)-SHELL_START) DIFF,
      NVL(SHELL_STATUS,STATUS) SHELL_STATUS
      ,VERSIONEUTENTE
      , (
        SELECT count(*)
        FROM UNIFY.V_LOSSRES_STORICO A
        WHERE 1=1
        AND ANNO = V.ANNO
        AND MESE = V.MESE
        AND COMPAGNIA = V.COMPAGNIA
        AND NVL(LINEOFBUSINESS,'-') = NVL(V.LINEOFBUSINESS,'-')
        AND ID_INFO IN (
          SELECT MAX(ID_INFO)
          FROM UNIFY.V_LOSSRES_STORICO B
          WHERE 1=1
          AND ANNO = A.ANNO
          AND MESE = A.MESE
          AND COMPAGNIA = A.COMPAGNIA
          AND NVL(LINEOFBUSINESS,'-') = NVL(A.LINEOFBUSINESS,'-')
          GROUP BY ANNO,MESE,COMPAGNIA,LINEOFBUSINESS,VERSIONEUTENTE
        )     
        GROUP BY ANNO,MESE,COMPAGNIA,LINEOFBUSINESS 
        ) CNTVERS,
        1 CAND
    FROM UNIFY.V_LOSSRES_STORICO V
    WHERE 1=1
    AND ANNO = ( SELECT SUBSTRING(VALORE,1,4) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
    AND MESE = ( SELECT INT(SUBSTRING(VALORE,6,2)) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
    AND ID_INFO = (
      SELECT MAX(ID_INFO) FROM UNIFY.V_LOSSRES_STORICO A
      WHERE 1=1
      AND ANNO = V.ANNO
      AND MESE = V.MESE
      AND COMPAGNIA = V.COMPAGNIA
      AND NVL(LINEOFBUSINESS,'-') = NVL(V.LINEOFBUSINESS,'-')
      AND (
          ID_INFO IN ( 
            SELECT ID_INFO FROM UNIFY.LOSSRESSTATUS B
            WHERE 1=1
            AND ANNO = A.ANNO
            AND MESE = A.MESE
            AND COMPAGNIA = A.COMPAGNIA
      AND NVL(LINEOFBUSINESS,'-') = NVL(V.LINEOFBUSINESS,'-')
            AND VERSIONE_DB IN ( 
              SELECT ID_CU FROM LOSS_RESERVING.CATALOG_CU_LOB 
              WHERE 1=1
              AND CONSUNIT  = ( SELECT DISTINCT VALORE_TAS FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Compagnia'      AND ID_INFO = B.ID_INFO )
              AND LOB       = ( SELECT DISTINCT VALORE_TAS FROM UNIFY.LOAD_INFO WHERE CAMPO = 'LineOfBusiness' AND ID_INFO = B.ID_INFO )
              AND FLAG_TIPO = 1  
            )
          )
          OR ID_INFO IN ( 
            SELECT ID_INFO FROM UNIFY.LOSSRESSTATUS B
            WHERE 1=1
            AND ANNO = A.ANNO
            AND MESE = A.MESE
            AND VERSIONE_DB IN ( 
              SELECT ID_CU_CONT FROM LOSS_RESERVING.CATALOG_CU_CONT 
              WHERE 1=1
              AND CONSUNIT  = ( SELECT DISTINCT VALORE_TAS FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Compagnia' AND ID_INFO = B.ID_INFO )
              AND FLAG_TIPO = 1  
            )
          )    
    )
  )
  ";
}   
  
  
  
  
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);

    if ( ! $result ){
        echo $sql;
        echo "ERROR DB2 Run";
    }
    $Arr_Run=array();
    while ($row = db2_fetch_assoc($stmt)) {
         $Comp=$row['COMPAGNIA'];
         $Lob=$row['LINEOFBUSINESS'];
         $Status=$row['STATUS'];
         $IdInfo=$row['ID_INFO'];
         $Desc=$row['DESCRIZIONE'];
         $File=$row['FILEOUTPUTUFFICIALE1'];
         $Zonta=$row['FILEOUTPUTUFFICIALE2'];
         $DtInfo=$row['DATA_INFO'];
         $ShName=$row['SHELL_NAME'];
         $ShStart=$row['SHELL_START'];
         $ShEnd=$row['SHELL_END'];
         $ShStatus=$row['SHELL_STATUS'];
         $ShDiff=$row['DIFF'];
         $Vers=$row['VERSIONEUTENTE'];
         $CntVers=$row['CNTVERS'];
         $Cand=$row['CAND'];
         $CandCont=$row['CAND_CONT'];
         $IdRunSh=$row['ID_RUN_SH'];         
         $Wait=$row['WAIT'];

         array_push($Arr_Run,array($Comp,$Lob,$Status,$IdInfo,$Desc,$File,$Zonta,$DtInfo,$ShName,$ShStart,$ShEnd,$ShStatus,$ShDiff,$Vers,$CntVers,$Cand,$IdRunSh,$Wait,$CandCont));
    }   
 
    ?> 
    <form id="ShowLRStatus" method="POST">
    <CENTER>
    <input type="submit" name="refresh" value="REFRESH">
    <BR><BR>
    <input type="hidden" id="TABSHOW" name="TABSHOW" value="<?php echo $TABSHOW; ?>">   
    <div style="width 800px;" >
      <table  > 
      <tr>
      <td style="color:black;"><div class="TabShow <?php echo "$ClsCand"; ?>" onclick="$('#TABSHOW').val('CAND'); $('#ShowLRStatus').submit();"   >CARICAMENTI CANDIDATI</div></td>
      <td style="color:black;"><div class="TabShow <?php echo "$ClsTutti"; ?>" onclick="$('#TABSHOW').val('TUTTI'); $('#ShowLRStatus').submit();"  >TUTTI I CARICAMENTI</div></td>
      </tr>
      </table>
    </div>
    <BR>
<?php 

if ( "$TABSHOW" == "TUTTI" ){
?>  
    <div id="LastCand" >

    <div style="font-size: 15px;" ><B>TUTTI CARICAMENTI</B></div>
    <TABLE class="ExcelTable" style="width: 800px; margin: auto;">
    <tr>
    <th style="text-align:center;width:50px;" ></th>
    <?php   
    foreach( $Arr_CodComp as $CodComp ){    
    ?><th style="text-align:center;"><?php echo $CodComp; ?></th><?php
    }
    ?>
    </tr>
     <tr id="RigaCont" >
        <th style="text-align:center;width:50px;" >Cont</th> 
        <?php   
        foreach( $Arr_CodComp as $CodComp ){
           $Found=0;
           foreach( $Arr_Run as $Run ){
               $RunComp=$Run[0];
               $RunLob=$Run[1];
               $RunStatus=$Run[2];
               $RunIdInfo=$Run[3];
               $Desc=$Run[4];
               $File=$Run[5];
               $Zonta=$Run[6];
               $DtInfo=$Run[7];
               $ShName=$Run[8];
               $ShStart=$Run[9];
               $ShEnd=$Run[10];
               $ShStatus=$Run[11];
               $ShDiff=$Run[12];
               $vers=$Run[13];
               $CntVers=$Run[14];
               $Cand=$Run[18];
               $IdRunSh=$Run[16];
               $Wait=$Run[17];
               if ( "$RunComp" == "$CodComp" and "$RunLob" == "" and "${'TD'.$CodComp.'_'.$RunLob}" == "" ){
                  ?><td style="background:#a7a7a7;" ><?php
                  ${'TD'.$CodComp.'_'.$RunLob}=1;
                  ${'CntVers'.$RunLob}=0;
               }
               if ( "$RunComp" == "$CodComp" and "$RunLob" == "" ){
                  ${'CntVers'.$RunLob}=${'CntVers'.$RunLob}+1;
                  if ( "$Cand" == "0" and "$ShStatus" != "I" ){ $ShStatus=$ShStatus.'N'; }
                  ?>
                  <div id="<?php echo $CodComp.$vers; ?>Cont" class="Lancio Esito<?php echo $ShStatus; ?>" >
                  <div id="Dett<?php echo $CodComp.$vers; ?>Cont" class="ShowDett" hidden >
                      <B><?php echo $CodComp; ?> Cont</B>
                      <table class="ExcelTable" >
                      <TR><TD style="color:black;" >Descr  </TD><TD style="color:black;" ><?php echo $Desc; ?></TD></TR>
                      <TR><TD style="color:black;" >Richiesta  </TD><TD style="color:black;" ><?php echo $DtInfo; ?></TD></TR>
                      <TR><TD style="color:black;" >Attesa  </TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $Wait); ?></TD></TR>
                      <TR><TD style="color:black;" >Inizio Elab</TD><TD style="color:black;" ><?php echo $ShStart; ?></TD></TR>
                      <TR><TD style="color:black;" >Fine   Elab</TD><TD style="color:black;" ><?php echo $ShEnd; ?></TD></TR>
                      <TR><TD style="color:black;" >Time   Elab</TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $ShDiff); ?></TD></TR>
                      </table>
                  </div>
                  <script>
                   $('#<?php echo $CodComp.$vers; ?>Cont').mouseover(function(){ $('#Dett<?php echo $CodComp.$vers; ?>Cont').show();});
                   $('#<?php echo $CodComp.$vers; ?>Cont').mouseleave(function(){ $('#Dett<?php echo $CodComp.$vers; ?>Cont').hide();});
                   $('#Dett<?php echo $CodComp.$vers; ?>Cont').click(function(){ $('#Dett<?php echo $CodComp.$vers; ?>Cont').hide();});
                  </script>
                  <?php 
                  if ( "$Desc" != "Descrizione a piacere" ){
                     echo substr($Desc,0,10)."..";
                  }
                  if ( $CntVers > 1 ){
                    ?> Vers: <?php echo $vers;
                  }
                  if ( "$IdRunSh" != "" ){ ?><img src="../images/Shell.png" class="IconFile"  onclick="OpenShSel(<?php echo $IdRunSh; ?>);"/><?php }
                  if ( "$File" != ""  ){ ?><img src="../images/File.png" class="IconFile" title="Principale: <?php echo "$File"; ?>" ><?php }
                  if ( "$Zonta" != "" ){ ?><img src="../images/File.png" class="IconFile" title="Aggiunta: <?php echo "$Zonta"; ?>" ><?php }
                  
                  ?>
                  <div>
                  <?php               
                  $Found=1;
               }
               if ( "$RunComp" == "$CodComp" and "$RunLob" == "" and "${'TD'.$CodComp.'_'.$RunLob}" == "1" and "$TotVers" == "${'CntVers'.$RunLob}" ){
                  ?></td><?php
               }
           }
           if ( "$Found" == "0" ){
             ?><td class="EsitoN"></td><?php
           }
        }
        ?>
    </tr>
    <script>
      $('#RigaCont').mouseover(function(){ $('#RigaCont').addClass('InSel'); });
      $('#RigaCont').mouseleave(function(){ $('#RigaCont').removeClass('InSel');});
    </script>
    <?php   
    foreach( $Arr_Lob as $Lob ){
        ?>
        <tr id="Riga<?php echo $Lob;?>" >
        <th style="text-align:center;width:50px;" ><?php echo $Lob; ?></th> 
        <?php   
        foreach( $Arr_CodComp as $CodComp ){
           $Found=0;
           foreach( $Arr_Run as $Run ){
               $RunComp=$Run[0];
               $RunLob=$Run[1];
               $RunStatus=$Run[2];
               $RunIdInfo=$Run[3];
               $Desc=$Run[4];
               $File=$Run[5];
               $Zonta=$Run[6];
               $DtInfo=$Run[7]; 
               $ShName=$Run[8];
               $ShStart=$Run[9];
               $ShEnd=$Run[10];
               $ShStatus=$Run[11];
               $ShDiff=$Run[12]; 
               $vers=$Run[13]; 
               $CntVers=$Run[14];  
               $Cand=$Run[15];
               $IdRunSh=$Run[16];
               $Wait=$Run[17];
               if ( "$RunComp" == "$CodComp" and "$RunLob" == "$Lob" and "${'TD'.$CodComp.'_'.$RunLob}" == "" ){
                  ?><td  style="background:#a7a7a7;"  ><?php
                  ${'TD'.$CodComp.'_'.$RunLob}=1;
                  ${'CntVers'.$RunLob}=0;
               }               
               if ( "$RunComp" == "$CodComp" and "$RunLob" == "$Lob" ){
                  
                  if ( "$Cand" == "0" and "$ShStatus" != "I" ){ $ShStatus=$ShStatus.'N'; }
                  ?>
                  <div id="<?php echo $CodComp.$Lob.$vers; ?>" class="Lancio Esito<?php echo $ShStatus; ?>" >
                  <div id="Dett<?php echo $CodComp.$Lob.$vers; ?>" class="ShowDett" hidden >
                      <B><?php echo $CodComp.' '.$Lob; ?></B>
                      <table class="ExcelTable" >
                      <TR><TD style="color:black;" >Descr  </TD><TD style="color:black;" ><?php echo $Desc; ?></TD></TR>
                      <TR><TD style="color:black;" >Richiesta  </TD><TD style="color:black;" ><?php echo $DtInfo; ?></TD></TR>
                      <TR><TD style="color:black;" >Attesa  </TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $Wait); ?></TD></TR>
                      <TR><TD style="color:black;" >Inizio Elab</TD><TD style="color:black;" ><?php echo $ShStart; ?></TD></TR>
                      <TR><TD style="color:black;" >Fine   Elab</TD><TD style="color:black;" ><?php echo $ShEnd; ?></TD></TR>
                      <TR><TD style="color:black;" >Time   Elab</TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $ShDiff); ?></TD></TR>
                      </table>
                  </div>
                  <script>
                   $('#<?php echo $CodComp.$Lob.$vers; ?>').mouseover(function(){ $('#Dett<?php echo $CodComp.$Lob.$vers; ?>').show();});
                   $('#<?php echo $CodComp.$Lob.$vers; ?>').mouseleave(function(){ $('#Dett<?php echo $CodComp.$Lob.$vers; ?>').hide();});
                   $('#Dett<?php echo $CodComp.$Lob.$vers; ?>').click(function(){ $('#Dett<?php echo $CodComp.$Lob.$vers; ?>').hide();});
                  </script>
                  <?php 
                  if ( "$Desc" != "Descrizione a piacere" ){
                     echo substr($Desc,0,10)."..";
                  }
                  if ( $CntVers > 1 ){
                    ?> Vers: <?php echo $vers;
                  }
                  if ( "$IdRunSh" != "" ){ ?><img src="../images/Shell.png" class="IconFile"  onclick="OpenShSel(<?php echo $IdRunSh; ?>);" /><?php }
                  if ( "$File" != ""  ){ ?><img src="../images/File.png" class="IconFile" title="Principale: <?php echo "$File"; ?>" ><?php }
                  if ( "$Zonta" != "" ){ ?><img src="../images/File.png" class="IconFile" title="Aggiunta: <?php echo "$Zonta"; ?>" ><?php }
                  ?>
                  </div>
                  <?php
                  
                  if ( "$RunComp" == "$CodComp" and "$RunLob" == "" and "${'TD'.$CodComp.'_'.$RunLob}" == "1" and "$TotVers" == "${'CntVers'.$RunLob}" ){
                     ?></td><?php
                  }
                  
                  $Found=1;
               }
           }
           if ( "$Found" == "0" ){
             if ( in_array($Lob,${'Arr'.$CodComp}) ){
               ?><td class="EsitoN" style="color:black; font-size:20px;"><CENTER>-</CENTER></td><?php
             }else{
               ?><td class="EsitoN"></td><?php
             }
           }
        }
        ?>
          <script>
            $('#Riga<?php echo $Lob;?>').mouseover(function(){ $('#Riga<?php echo $Lob;?>').addClass('InSel'); });
            $('#Riga<?php echo $Lob;?>').mouseleave(function(){ $('#Riga<?php echo $Lob;?>').removeClass('InSel');});
          </script>
        </tr>
        <?php
    }
    ?>
   </TABLE>
  </div> 


<?php 
} else {
?>  

    <div id="ElencoCand" >
    <div style="font-size: 15px;" ><B>CARICAMENTI CANDIDATI</B></div>
    <TABLE class="ExcelTable" style="width: 800px; margin: auto;">
    <tr>
    <th style="text-align:center;width:50px;" ></th>
    <?php   
    foreach( $Arr_CodComp as $CodComp ){
    ?><th style="text-align:center;"><?php echo $CodComp; ?></th><?php
     ${$CodComp.'TotalTime'}=0;  
    }
    $TotalTime=0;  
    ?>
    </tr>
     <tr>
        <th style="text-align:center;width:50px;" >Cont</th> 
        <?php   
        foreach( $Arr_CodComp as $CodComp ){
           $Found=0;
           foreach( $Arr_Run as $Run ){
               $RunComp=$Run[0];
               $RunLob=$Run[1];
               $RunStatus=$Run[2];
               $RunIdInfo=$Run[3];
               $Desc=$Run[4];
               $File=$Run[5];
               $Zonta=$Run[6];
               $DtInfo=$Run[7];
               $ShName=$Run[8];
               $ShStart=$Run[9];
               $ShEnd=$Run[10];
               $ShStatus=$Run[11];
               $ShDiff=$Run[12]; 
               $vers=$Run[13];
               $CntVers=$Run[14];
               $IdRunSh=$Run[16];
               $Wait=$Run[17];
               if ( "$RunComp" == "$CodComp" and "$RunLob" == "" ){
                  $TotalTime=$TotalTime+$ShDiff;
                  ${$CodComp.'TotalTime'}=${$CodComp.'TotalTime'}+$ShDiff;
                  ?><td id="<?php echo $CodComp; ?>Cont" class="Esito<?php echo $ShStatus; ?>" >
                  <div id="Dett<?php echo $CodComp; ?>Cont" class="ShowDett" hidden >
                      <B><?php echo $CodComp; ?> Cont</B>
                      <table class="ExcelTable" >
                      <TR><TD style="color:black;" >Descr  </TD><TD style="color:black;" ><?php echo $Desc; ?></TD></TR>
                      <TR><TD style="color:black;" >Richiesta  </TD><TD style="color:black;" ><?php echo $DtInfo; ?></TD></TR>
                      <TR><TD style="color:black;" >Attesa  </TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $Wait); ?></TD></TR>
                      <TR><TD style="color:black;" >Inizio Elab</TD><TD style="color:black;" ><?php echo $ShStart; ?></TD></TR>
                      <TR><TD style="color:black;" >Fine   Elab</TD><TD style="color:black;" ><?php echo $ShEnd; ?></TD></TR>
                      <TR><TD style="color:black;" >Time   Elab</TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $ShDiff); ?></TD></TR>
                      </table>
                  </div>
                  <script>
                   $('#<?php echo $CodComp; ?>Cont').mouseover(function(){ $('#Dett<?php echo $CodComp; ?>Cont').show();});
                   $('#<?php echo $CodComp; ?>Cont').mouseleave(function(){ $('#Dett<?php echo $CodComp; ?>Cont').hide();});
                   $('#Dett<?php echo $CodComp; ?>Cont').click(function(){ $('#Dett<?php echo $CodComp; ?>Cont').hide();});
                  </script>
                  <?php 
                  if ( "$Desc" != "Descrizione a piacere" ){
                     if ( substr("$Desc",0,10) != "$Desc" ){ echo substr($Desc,0,10).".."; } else {echo $Desc;}
                  }
                  if ( $CntVers > 1 ){
                    ?> Vers: <?php echo $vers;
                  }
                  if ( "$File" != ""  ){ ?><img src="../images/File.png" class="IconFile" title="Principale: <?php echo "$File"; ?>" ><?php }
                  if ( "$Zonta" != "" ){ ?><img src="../images/File.png" class="IconFile" title="Aggiunta: <?php echo "$Zonta"; ?>" ><?php }
                  ?>
                  </td><?php
                  $Found=1;
               }
           }
           if ( "$Found" == "0" ){
             ?><td class="EsitoN"></td><?php
           }
        }
        ?>
    </tr>
    <?php   
    foreach( $Arr_Lob as $Lob ){
        ?>
        <tr>
        <th style="text-align:center;width:50px;" ><?php echo $Lob; ?></th> 
        <?php   
        foreach( $Arr_CodComp as $CodComp ){
           $Found=0;
           foreach( $Arr_Run as $Run ){
               $RunComp=$Run[0];
               $RunLob=$Run[1];
               $RunStatus=$Run[2];
               $RunIdInfo=$Run[3];
               $Desc=$Run[4];
               $File=$Run[5];
               $Zonta=$Run[6];
               $DtInfo=$Run[7]; 
               $ShName=$Run[8];
               $ShStart=$Run[9];
               $ShEnd=$Run[10];
               $ShStatus=$Run[11];
               $ShDiff=$Run[12];
               $vers=$Run[13];
               $CntVers=$Run[14];
               $IdRunSh=$Run[16];   
               $Wait=$Run[17];         
               if ( "$RunComp" == "$CodComp" and "$RunLob" == "$Lob" ){
                  $TotalTime=$TotalTime+$ShDiff;
                  ${$CodComp.'TotalTime'}=${$CodComp.'TotalTime'}+$ShDiff;                
                  ?><td id="<?php echo $CodComp.$Lob; ?>" class="Esito<?php echo $ShStatus; ?>" >
                  <div id="Dett<?php echo $CodComp.$Lob; ?>" class="ShowDett" hidden >
                      <B><?php echo $CodComp.' '.$Lob; ?></B>
                      <table class="ExcelTable" >
                      <TR><TD style="color:black;" >Descr  </TD><TD style="color:black;" ><?php echo $Desc; ?></TD></TR>
                      <TR><TD style="color:black;" >Richiesta  </TD><TD style="color:black;" ><?php echo $DtInfo; ?></TD></TR>
                      <TR><TD style="color:black;" >Attesa  </TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $Wait); ?></TD></TR>
                      <TR><TD style="color:black;" >Inizio Elab</TD><TD style="color:black;" ><?php echo $ShStart; ?></TD></TR>
                      <TR><TD style="color:black;" >Fine   Elab</TD><TD style="color:black;" ><?php echo $ShEnd; ?></TD></TR>
                      <TR><TD style="color:black;" >Time   Elab</TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $ShDiff); ?></TD></TR>
                      </table>
                  </div>
                  <script>
                   $('#<?php echo $CodComp.$Lob; ?>').mouseover(function(){ $('#Dett<?php echo $CodComp.$Lob; ?>').show();});
                   $('#<?php echo $CodComp.$Lob; ?>').mouseleave(function(){ $('#Dett<?php echo $CodComp.$Lob; ?>').hide();});
                   $('#Dett<?php echo $CodComp.$Lob; ?>').click(function(){ $('#Dett<?php echo $CodComp.$Lob; ?>').hide();});
                  </script>
                  <?php 
                  if ( "$Desc" != "Descrizione a piacere" ){
                     if ( substr("$Desc",0,10) != "$Desc" ){ echo substr($Desc,0,10).".."; } else {echo $Desc;}
                  }
                  if ( $CntVers > 1 ){
                    ?> Vers: <?php echo $vers;
                  }
                  if ( "$File" != ""  ){ ?><img src="../images/File.png" class="IconFile" title="Principale: <?php echo "$File"; ?>" ><?php }
                  if ( "$Zonta" != "" ){ ?><img src="../images/File.png" class="IconFile" title="Aggiunta: <?php echo "$Zonta"; ?>" ><?php }
                  ?>
                  </td><?php
                  $Found=1;
               }
           }
           if ( "$Found" == "0" ){
             if ( in_array($Lob,${'Arr'.$CodComp}) ){
               ?><td class="EsitoN" style="color:black; font-size:20px;"><CENTER>-</CENTER></td><?php
             }else{
               ?><td class="EsitoN"></td><?php
             }
           }
        }
        ?>
        </tr>
        <?php
    }
    ?>
   <th style="text-align:center;width:50px;" >Time Elab</th>
    <?php   
    foreach( $Arr_CodComp as $CodComp ){
    ?><th style="text-align:center;"><?php echo gmdate('H:i:s', ${$CodComp.'TotalTime'}); ?></th><?php
    }
    ?>
    </tr>
    <tr>
     <th style="text-align:center;width:50px;"  >Total Time</th>
     <th colspan="4"  style="text-align:center;" ><?php echo gmdate('H:i:s', $TotalTime); ?></th>
    </tr>

   </TABLE>
  </div> 
 <?php  
}
   
   //--------------------------------------------------------------------------------------
    $DtCiDInfo="";
    $ShName="";
    $ShStart="";
    $ShEnd="";
    $ShStatus="N";
    $ShDiff="";
    $sql = "SELECT DATA_INFO,
    SHELL_NAME, 
    TO_CHAR(SHELL_START,'YYYY-MM-DD HH24:MI:SS') SHELL_START,
    TO_CHAR(SHELL_END,'YYYY-MM-DD HH24:MI:SS') SHELL_END,
    timestampdiff(2,NVL(SHELL_END,CURRENT_TIMESTAMP)-SHELL_START) DIFF,
    NVL(SHELL_STATUS,STATUS) SHELL_STATUS   
    FROM (
SELECT
    C.ID_INFO,
    C.DATA_INFO,
    C.COMPAGNIA,
    C.ANNO,
    C.MESE,
    C.LINEOFBUSINESS,
    C.VERSIONEUTENTE,
    C.DESCRIZIONE,
    C.VERSIONENUOVA,
    C.FILEOUTPUTUFFICIALE1,
    C.FILEOUTPUTUFFICIALE2,
    C.JOBID,
    C.JOBOWNER,
    C.STATUS,
    C.WAITTIME,
    C.RIPETIZIONE,
    B.NAME       SHELL_NAME,
    B.START_TIME SHELL_START,
    B.END_TIME   SHELL_END,
    B.STATUS     SHELL_STATUS
FROM
    (   SELECT
            *,
            ROWNUMBER() OVER (
                          PARTITION BY
                              Compagnia,
                              Anno,
                              Mese,
                              LineOfBusiness,
                              VersioneUtente,
                              STATUS
                          ORDER BY
                              DATA_INFO ) RIPETIZIONE
        FROM
            (   SELECT
                    ID_INFO,
                    DATA_INFO ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Compagnia'
                        AND ID_INFO = A.ID_INFO ) Compagnia ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Anno'
                        AND ID_INFO = A.ID_INFO ) Anno ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Mese'
                        AND ID_INFO = A.ID_INFO ) Mese ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'LineOfBusiness'
                        AND ID_INFO = A.ID_INFO ) LineOfBusiness ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'VersioneUtente'
                        AND ID_INFO = A.ID_INFO ) VersioneUtente ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Descrizione'
                        AND ID_INFO = A.ID_INFO ) Descrizione ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'VersioneNuova'
                        AND ID_INFO = A.ID_INFO ) VersioneNuova ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'FileOutputUfficiale1'
                        AND ID_INFO = A.ID_INFO ) FileOutputUfficiale1 ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'FileOutputUfficiale2'
                        AND ID_INFO = A.ID_INFO ) FileOutputUfficiale2 ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'JobId'
                        AND ID_INFO = A.ID_INFO ) JobId ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'JobOwner'
                        AND ID_INFO = A.ID_INFO ) JobOwner ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Status'
                        AND ID_INFO = A.ID_INFO ) Status ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'WaitTime'
                        AND ID_INFO = A.ID_INFO ) WaitTime,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'ID_RUN_SH'
                        AND ID_INFO = A.ID_INFO ) ID_RUN_SH
                FROM
                    UNIFY.LOAD_INFO A
                WHERE 1=1
                   AND ID_TIPO_EXEC = ( SELECT ID_TIPO_EXEC FROM UNIFY.TAB_EXEC_ANAG WHERE TIPO_EXEC = 'CandInDef' ) 
                   AND ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Anno' AND ID_INFO = A.ID_INFO ) = ( SELECT SUBSTRING(VALORE,1,4) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
                   AND ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Mese' AND ID_INFO = A.ID_INFO ) = ( SELECT INT(SUBSTRING(VALORE,6,2)) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
                   --AND ID_INFO > NVL(( 
                   --   SELECT MAX(ID_INFO) 
                   --   FROM UNIFY.LOSSRESSTATUS B
                   --   WHERE 1=1
                   --   AND ANNO = ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Anno' AND ID_INFO = A.ID_INFO )
                   --   AND MESE = ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Mese' AND ID_INFO = A.ID_INFO )
                   --   AND VERSIONE_DB IN ( 
                   --     SELECT ID_CU_CONT FROM LOSS_RESERVING.CATALOG_CU_CONT 
                   --     WHERE 1=1
                   --     AND CONSUNIT  = ( SELECT DISTINCT VALORE_TAS FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Compagnia' AND ID_INFO = B.ID_INFO )
                   --     AND FLAG_TIPO = 1  
                   --   )
                   -- ),0)
                GROUP BY
                    ID_INFO,
                    DATA_INFO )
        ORDER BY
            Compagnia,
            Anno,
            Mese,
            LineOfBusiness,
            VersioneUtente,
            STATUS,
            ROWNUMBER() OVER (
                          PARTITION BY
                              Compagnia,
                              Anno,
                              Mese,
                              LineOfBusiness,
                              VersioneUtente,
                              STATUS
                          ORDER BY
                              DATA_INFO DESC ) ) C
JOIN 
    WORK_CORE.CORE_SH B
ON 
    (   SELECT 
            ID_RUN_SH 
        FROM 
            WORK_CORE.CORE_SH 
        WHERE 
            ID_RUN_SH_FATHER = C.ID_RUN_SH ) 
    = B.ID_RUN_SH   
    )
    
    WHERE 1=1
    ORDER BY DATA_INFO
    ";        
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);

    if ( ! $result ){
        echo $sql;
        echo "ERROR DB2 CandInDef";
    }
    while ($row = db2_fetch_assoc($stmt)) {
         $DtCiDInfo=$row['DATA_INFO'];
         $ShName=$row['SHELL_NAME'];
         $ShStart=$row['SHELL_START'];
         $ShEnd=$row['SHELL_END'];
         $ShStatus=$row['SHELL_STATUS'];
         $ShDiff=$row['DIFF'];
         
    }
    
    ?><div id="CandInDef" class="Esito<?php echo $ShStatus; ?>" title="<?php echo $DtCiDInfo; ?>" >Candidato in Definitivo</div>
     <div id="DettCf" class="ShowPostDett" hidden >
         <B>Candidato in Definitivo</B>
         <table class="ExcelTable" >
           <TR><TD style="color:black;" >Richiesta  </TD><TD style="color:black;" ><?php echo $DtCiDInfo; ?></TD></TR>
           <TR><TD style="color:black;" >Inizio Elab</TD><TD style="color:black;" ><?php echo $ShStart; ?></TD></TR>
           <TR><TD style="color:black;" >Fine   Elab</TD><TD style="color:black;" ><?php echo $ShEnd; ?></TD></TR>
           <TR><TD style="color:black;" >Time   Elab</TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $ShDiff); ?></TD></TR>
         </table>                
     </div>
     <script>
      $('#CandInDef').mouseover(function(){ $('#DettCf').show();});
      $('#CandInDef').mouseleave(function(){ $('#DettCf').hide();});
     </script>     
   <table style="width:800px;" >
   <tr><td style="width:50%;">
   <?php  
    //-----------------------------------------------------
    $DtInfo="";
    $ShName="";
    $ShStart="";
    $ShEnd="";
    $ShStatus="N";
    $ShDiff="";
    $sql = "SELECT DATA_INFO,
    SHELL_NAME, 
    TO_CHAR(SHELL_START,'YYYY-MM-DD HH24:MI:SS') SHELL_START,
    TO_CHAR(SHELL_END,'YYYY-MM-DD HH24:MI:SS') SHELL_END,
    timestampdiff(2,NVL(SHELL_END,CURRENT_TIMESTAMP)-SHELL_START) DIFF,
    NVL(SHELL_STATUS,STATUS) SHELL_STATUS   
    FROM (
SELECT
    C.ID_INFO,
    C.DATA_INFO,
    C.COMPAGNIA,
    C.ANNO,
    C.MESE,
    C.LINEOFBUSINESS,
    C.VERSIONEUTENTE,
    C.DESCRIZIONE,
    C.VERSIONENUOVA,
    C.FILEOUTPUTUFFICIALE1,
    C.FILEOUTPUTUFFICIALE2,
    C.JOBID,
    C.JOBOWNER,
    C.STATUS,
    C.WAITTIME,
    C.RIPETIZIONE,
    B.NAME       SHELL_NAME,
    B.START_TIME SHELL_START,
    B.END_TIME   SHELL_END,
    B.STATUS     SHELL_STATUS
FROM
    (   SELECT
            *,
            ROWNUMBER() OVER (
                          PARTITION BY
                              Compagnia,
                              Anno,
                              Mese,
                              LineOfBusiness,
                              VersioneUtente,
                              STATUS
                          ORDER BY
                              DATA_INFO ) RIPETIZIONE
        FROM
            (   SELECT
                    ID_INFO,
                    DATA_INFO ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Compagnia'
                        AND ID_INFO = A.ID_INFO ) Compagnia ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Anno'
                        AND ID_INFO = A.ID_INFO ) Anno ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Mese'
                        AND ID_INFO = A.ID_INFO ) Mese ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'LineOfBusiness'
                        AND ID_INFO = A.ID_INFO ) LineOfBusiness ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'VersioneUtente'
                        AND ID_INFO = A.ID_INFO ) VersioneUtente ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Descrizione'
                        AND ID_INFO = A.ID_INFO ) Descrizione ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'VersioneNuova'
                        AND ID_INFO = A.ID_INFO ) VersioneNuova ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'FileOutputUfficiale1'
                        AND ID_INFO = A.ID_INFO ) FileOutputUfficiale1 ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'FileOutputUfficiale2'
                        AND ID_INFO = A.ID_INFO ) FileOutputUfficiale2 ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'JobId'
                        AND ID_INFO = A.ID_INFO ) JobId ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'JobOwner'
                        AND ID_INFO = A.ID_INFO ) JobOwner ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Status'
                        AND ID_INFO = A.ID_INFO ) Status ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'WaitTime'
                        AND ID_INFO = A.ID_INFO ) WaitTime,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'ID_RUN_SH'
                        AND ID_INFO = A.ID_INFO ) ID_RUN_SH
                FROM
                    UNIFY.LOAD_INFO A
                WHERE 1=1
                   AND ID_TIPO_EXEC = ( SELECT ID_TIPO_EXEC FROM UNIFY.TAB_EXEC_ANAG WHERE TIPO_EXEC = 'SoloCuboApp' ) 
                   AND ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Anno' AND ID_INFO = A.ID_INFO ) = ( SELECT SUBSTRING(VALORE,1,4) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
                   AND ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Mese' AND ID_INFO = A.ID_INFO ) = ( SELECT INT(SUBSTRING(VALORE,6,2)) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
                   --AND ID_INFO > NVL( 
                   --   (SELECT MAX(ID_INFO) FROM UNIFY.LOSSRESSTATUS B
                   --   WHERE 1=1
                   --   AND ANNO = ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Anno' AND ID_INFO = A.ID_INFO )
                   --   AND MESE = ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Mese' AND ID_INFO = A.ID_INFO )
                   --   AND VERSIONE_DB IN ( 
                   --     SELECT ID_CU_CONT FROM LOSS_RESERVING.CATALOG_CU_CONT 
                   --     WHERE 1=1
                   --     AND CONSUNIT  = ( SELECT DISTINCT VALORE_TAS FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Compagnia' AND ID_INFO = B.ID_INFO )
                   --     AND FLAG_TIPO = 1  
                   --   )
                   --   )
                   -- ,0)
                GROUP BY
                    ID_INFO,
                    DATA_INFO )
        ORDER BY
            Compagnia,
            Anno,
            Mese,
            LineOfBusiness,
            VersioneUtente,
            STATUS,
            ROWNUMBER() OVER (
                          PARTITION BY
                              Compagnia,
                              Anno,
                              Mese,
                              LineOfBusiness,
                              VersioneUtente,
                              STATUS
                          ORDER BY
                              DATA_INFO DESC ) ) C
JOIN 
    WORK_CORE.CORE_SH B
ON 
    (   SELECT 
            ID_RUN_SH 
        FROM 
            WORK_CORE.CORE_SH 
        WHERE 
            ID_RUN_SH_FATHER = C.ID_RUN_SH ) 
    = B.ID_RUN_SH   
    )
    
    WHERE 1=1
    ORDER BY DATA_INFO
    ";        
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);

    if ( ! $result ){
        echo $sql;
        echo "ERROR DB2 CuboApp";
    }
    while ($row = db2_fetch_assoc($stmt)) {
         $DtInfo=$row['DATA_INFO'];
         $ShName=$row['SHELL_NAME'];
         $ShStart=$row['SHELL_START'];
         $ShEnd=$row['SHELL_END'];
         $ShStatus=$row['SHELL_STATUS'];
         $ShDiff=$row['DIFF'];
         
    }
    
    ?><div id="CuboApp" class="Esito<?php echo $ShStatus; ?>" title="<?php echo $DtInfo; ?>" >Cubo App</div>
     <div id="DettCA" class="ShowPostDett" hidden >
         <B>Cubo App</B>
         <table class="ExcelTable" >
           <TR><TD style="color:black;" >Richiesta  </TD><TD style="color:black;" ><?php echo $DtInfo; ?></TD></TR>
           <TR><TD style="color:black;" >Inizio Elab</TD><TD style="color:black;" ><?php echo $ShStart; ?></TD></TR>
           <TR><TD style="color:black;" >Fine   Elab</TD><TD style="color:black;" ><?php echo $ShEnd; ?></TD></TR>
           <TR><TD style="color:black;" >Time   Elab</TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $ShDiff); ?></TD></TR>
         </table>                
     </div>
     <script>
      $('#CuboApp').mouseover(function(){ $('#DettCA').show();});
      $('#CuboApp').mouseleave(function(){ $('#DettCA').hide();});
     </script>
  </td> 
  <td rowspan=2 style="width:50%;" >

 <?php  
    //-----------------------------------------------------
    $DtInfo="";
    $ShName="";
    $ShStart="";
    $ShEnd="";
    $ShStatus="N";
    $ShDiff="";
    $sql = "SELECT DATA_INFO,
    SHELL_NAME, 
    TO_CHAR(SHELL_START,'YYYY-MM-DD HH24:MI:SS') SHELL_START,
    TO_CHAR(SHELL_END,'YYYY-MM-DD HH24:MI:SS') SHELL_END,
    timestampdiff(2,NVL(SHELL_END,CURRENT_TIMESTAMP)-SHELL_START) DIFF,
    NVL(SHELL_STATUS,STATUS) SHELL_STATUS   
    FROM (
SELECT
    C.ID_INFO,
    C.DATA_INFO,
    C.COMPAGNIA,
    C.ANNO,
    C.MESE,
    C.LINEOFBUSINESS,
    C.VERSIONEUTENTE,
    C.DESCRIZIONE,
    C.VERSIONENUOVA,
    C.FILEOUTPUTUFFICIALE1,
    C.FILEOUTPUTUFFICIALE2,
    C.JOBID,
    C.JOBOWNER,
    C.STATUS,
    C.WAITTIME,
    C.RIPETIZIONE,
    B.NAME       SHELL_NAME,
    B.START_TIME SHELL_START,
    B.END_TIME   SHELL_END,
    B.STATUS     SHELL_STATUS
FROM
    (   SELECT
            *,
            ROWNUMBER() OVER (
                          PARTITION BY
                              Compagnia,
                              Anno,
                              Mese,
                              LineOfBusiness,
                              VersioneUtente,
                              STATUS
                          ORDER BY
                              DATA_INFO ) RIPETIZIONE
        FROM
            (   SELECT
                    ID_INFO,
                    DATA_INFO ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Compagnia'
                        AND ID_INFO = A.ID_INFO ) Compagnia ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Anno'
                        AND ID_INFO = A.ID_INFO ) Anno ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Mese'
                        AND ID_INFO = A.ID_INFO ) Mese ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'LineOfBusiness'
                        AND ID_INFO = A.ID_INFO ) LineOfBusiness ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'VersioneUtente'
                        AND ID_INFO = A.ID_INFO ) VersioneUtente ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Descrizione'
                        AND ID_INFO = A.ID_INFO ) Descrizione ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'VersioneNuova'
                        AND ID_INFO = A.ID_INFO ) VersioneNuova ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'FileOutputUfficiale1'
                        AND ID_INFO = A.ID_INFO ) FileOutputUfficiale1 ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'FileOutputUfficiale2'
                        AND ID_INFO = A.ID_INFO ) FileOutputUfficiale2 ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'JobId'
                        AND ID_INFO = A.ID_INFO ) JobId ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'JobOwner'
                        AND ID_INFO = A.ID_INFO ) JobOwner ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Status'
                        AND ID_INFO = A.ID_INFO ) Status ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'WaitTime'
                        AND ID_INFO = A.ID_INFO ) WaitTime,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'ID_RUN_SH'
                        AND ID_INFO = A.ID_INFO ) ID_RUN_SH
                FROM
                    UNIFY.LOAD_INFO A
                WHERE 1=1
                   AND ID_TIPO_EXEC = ( SELECT ID_TIPO_EXEC FROM UNIFY.TAB_EXEC_ANAG WHERE TIPO_EXEC = 'CuboSolvency' ) 
                   AND ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Anno' AND ID_INFO = A.ID_INFO ) = ( SELECT SUBSTRING(VALORE,1,4) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
                   AND ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Mese' AND ID_INFO = A.ID_INFO ) = ( SELECT INT(SUBSTRING(VALORE,6,2)) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
                  --AND ID_INFO > NVL( 
                  --   (SELECT MAX(ID_INFO) FROM UNIFY.LOSSRESSTATUS B
                  --   WHERE 1=1
                  --   AND ANNO = ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Anno' AND ID_INFO = A.ID_INFO )
                  --   AND MESE = ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Mese' AND ID_INFO = A.ID_INFO )
                  --   AND VERSIONE_DB IN ( 
                  --     SELECT ID_CU_CONT FROM LOSS_RESERVING.CATALOG_CU_CONT 
                  --     WHERE 1=1
                  --     AND CONSUNIT  = ( SELECT DISTINCT VALORE_TAS FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Compagnia' AND ID_INFO = B.ID_INFO )
                  --     AND FLAG_TIPO = 1  
                  --   )
                  --   )
                  -- ,0)
                GROUP BY
                    ID_INFO,
                    DATA_INFO )
        ORDER BY
            Compagnia,
            Anno,
            Mese,
            LineOfBusiness,
            VersioneUtente,
            STATUS,
            ROWNUMBER() OVER (
                          PARTITION BY
                              Compagnia,
                              Anno,
                              Mese,
                              LineOfBusiness,
                              VersioneUtente,
                              STATUS
                          ORDER BY
                              DATA_INFO DESC ) ) C
JOIN 
    WORK_CORE.CORE_SH B
ON 
    (   SELECT 
            ID_RUN_SH 
        FROM 
            WORK_CORE.CORE_SH 
        WHERE 
            ID_RUN_SH_FATHER = C.ID_RUN_SH ) 
    = B.ID_RUN_SH   
    )
    
    WHERE 1=1
    ORDER BY DATA_INFO
    ";        
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);

    if ( ! $result ){
        echo $sql;
        echo "ERROR DB2 GiroSolv";
    }
    while ($row = db2_fetch_assoc($stmt)) {
         $DtInfo=$row['DATA_INFO'];
         $ShName=$row['SHELL_NAME'];
         $ShStart=$row['SHELL_START'];
         $ShEnd=$row['SHELL_END'];
         $ShStatus=$row['SHELL_STATUS'];
         $ShDiff=$row['DIFF'];
         
    }
    
    ?><div id="GiroSolvency" class="Esito<?php echo $ShStatus; ?>" title="<?php echo $DtInfo; ?>" >Giro Solvency
	<?php if ( "$ShEnd" != "") { echo '<BR>'.$ShEnd; } ?></div>
     <div id="DettGS" class="ShowPostDett" hidden >
         <B>Giro Solvency</B>
         <table class="ExcelTable" >
           <TR><TD style="color:black;" >Richiesta  </TD><TD style="color:black;" ><?php echo $DtInfo; ?></TD></TR>
           <TR><TD style="color:black;" >Inizio Elab</TD><TD style="color:black;" ><?php echo $ShStart; ?></TD></TR>
           <TR><TD style="color:black;" >Fine   Elab</TD><TD style="color:black;" ><?php echo $ShEnd; ?></TD></TR>
           <TR><TD style="color:black;" >Time   Elab</TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $ShDiff); ?></TD></TR>
         </table>                
     </div>
     <script>
      $('#GiroSolvency').mouseover(function(){ $('#DettGS').show();});
      $('#GiroSolvency').mouseleave(function(){ $('#DettGS').hide();});
     </script>  
  </td>
  </tr>
  <tr>
  <td>
    <?php
    //--------------------------------------------------------------------------------------------
    $DtInfo="";
    $ShName="";
    $ShStart="";
    $ShEnd="";
    $ShStatus="N";
    $ShDiff="";
    $sql = "SELECT
    DATA_INFO,
    SHELL_NAME, 
    TO_CHAR(SHELL_START,'YYYY-MM-DD HH24:MI:SS') SHELL_START,
    TO_CHAR(SHELL_END,'YYYY-MM-DD HH24:MI:SS') SHELL_END,
    timestampdiff(2,NVL(SHELL_END,CURRENT_TIMESTAMP)-SHELL_START) DIFF,
    NVL(SHELL_STATUS,STATUS) SHELL_STATUS   
    FROM (
SELECT
    C.ID_INFO,
    C.DATA_INFO,
    C.COMPAGNIA,
    C.ANNO,
    C.MESE,
    C.LINEOFBUSINESS,
    C.VERSIONEUTENTE,
    C.DESCRIZIONE,
    C.VERSIONENUOVA,
    C.FILEOUTPUTUFFICIALE1,
    C.FILEOUTPUTUFFICIALE2,
    C.JOBID,
    C.JOBOWNER,
    C.STATUS,
    C.WAITTIME,
    C.RIPETIZIONE,
    B.NAME       SHELL_NAME,
    B.START_TIME SHELL_START,
    B.END_TIME   SHELL_END,
    B.STATUS     SHELL_STATUS
FROM
    (   SELECT
            *,
            ROWNUMBER() OVER (
                          PARTITION BY
                              Anno,
                              Mese
                          ORDER BY
                              DATA_INFO ) RIPETIZIONE
        FROM
            (   SELECT
                    ID_INFO,
                    DATA_INFO ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Compagnia'
                        AND ID_INFO = A.ID_INFO ) Compagnia ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Anno'
                        AND ID_INFO = A.ID_INFO ) Anno ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Mese'
                        AND ID_INFO = A.ID_INFO ) Mese ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'LineOfBusiness'
                        AND ID_INFO = A.ID_INFO ) LineOfBusiness ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'VersioneUtente'
                        AND ID_INFO = A.ID_INFO ) VersioneUtente ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Descrizione'
                        AND ID_INFO = A.ID_INFO ) Descrizione ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'VersioneNuova'
                        AND ID_INFO = A.ID_INFO ) VersioneNuova ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'FileOutputUfficiale1'
                        AND ID_INFO = A.ID_INFO ) FileOutputUfficiale1 ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'FileOutputUfficiale2'
                        AND ID_INFO = A.ID_INFO ) FileOutputUfficiale2 ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'JobId'
                        AND ID_INFO = A.ID_INFO ) JobId ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'JobOwner'
                        AND ID_INFO = A.ID_INFO ) JobOwner ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'Status'
                        AND ID_INFO = A.ID_INFO ) Status ,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'WaitTime'
                        AND ID_INFO = A.ID_INFO ) WaitTime,
                    (   SELECT
                            VALORE
                        FROM
                            UNIFY.LOAD_INFO
                        WHERE
                            CAMPO = 'ID_RUN_SH'
                        AND ID_INFO = A.ID_INFO ) ID_RUN_SH
                FROM
                    UNIFY.LOAD_INFO A
                WHERE 1=1
                   AND ID_TIPO_EXEC = ( SELECT ID_TIPO_EXEC FROM UNIFY.TAB_EXEC_ANAG WHERE TIPO_EXEC = 'SoloCuboSolvency' ) 
                   AND ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Anno' AND ID_INFO = A.ID_INFO ) = ( SELECT SUBSTRING(VALORE,1,4) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
                   AND ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Mese' AND ID_INFO = A.ID_INFO ) = ( SELECT INT(SUBSTRING(VALORE,6,2)) FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB' )
                   --AND ID_INFO > NVL( 
                   --   (SELECT MAX(ID_INFO) FROM UNIFY.LOSSRESSTATUS B
                   --   WHERE 1=1
                   --   AND ANNO = ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Anno' AND ID_INFO = A.ID_INFO )
                   --   AND MESE = ( SELECT VALORE FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Mese' AND ID_INFO = A.ID_INFO )
                   --   AND VERSIONE_DB IN ( 
                   --     SELECT ID_CU_CONT FROM LOSS_RESERVING.CATALOG_CU_CONT 
                   --     WHERE 1=1
                   --     AND CONSUNIT  = ( SELECT DISTINCT VALORE_TAS FROM UNIFY.LOAD_INFO WHERE CAMPO = 'Compagnia' AND ID_INFO = B.ID_INFO )
                   --     AND FLAG_TIPO = 1  
                   --   ))
                   -- ,0)   
                GROUP BY
                    ID_INFO,
                    DATA_INFO )
        ORDER BY
            Compagnia,
            Anno,
            Mese,
            LineOfBusiness,
            VersioneUtente,
            STATUS,
            ROWNUMBER() OVER (
                          PARTITION BY
                              Anno,
                              Mese
                          ORDER BY
                              DATA_INFO DESC ) ) C
JOIN 
    WORK_CORE.CORE_SH B
ON 
    (   SELECT 
            ID_RUN_SH 
        FROM 
            WORK_CORE.CORE_SH 
        WHERE 
            ID_RUN_SH_FATHER = C.ID_RUN_SH ) 
    = B.ID_RUN_SH
)   
    
    WHERE 1=1
    ORDER BY DATA_INFO

    ";        
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);

    if ( ! $result ){
        echo $sql;
        echo "ERROR DB2 CuboSolvency";
    }
    while ($row = db2_fetch_assoc($stmt)) {
         $DtInfo=$row['DATA_INFO'];
         $ShName=$row['SHELL_NAME'];
         $ShStart=$row['SHELL_START'];
         $ShEnd=$row['SHELL_END'];
         $ShStatus=$row['SHELL_STATUS'];
         $ShDiff=$row['DIFF'];       
    }
    
    ?><div id="CuboSolvency" class="Esito<?php echo $ShStatus; ?>">Cubo Solvency</div>
     <div id="DettBs" class="ShowPostDett" hidden >
         <B>Cubo Solvency</B>
         <table class="ExcelTable" >
           <TR><TD style="color:black;" >Richiesta  </TD><TD style="color:black;" ><?php echo $DtInfo; ?></TD></TR>
           <TR><TD style="color:black;" >Inizio Elab</TD><TD style="color:black;" ><?php echo $ShStart; ?></TD></TR>
           <TR><TD style="color:black;" >Fine   Elab</TD><TD style="color:black;" ><?php echo $ShEnd; ?></TD></TR>
           <TR><TD style="color:black;" >Time   Elab</TD><TD style="color:black;" ><?php echo gmdate('H:i:s', $ShDiff); ?></TD></TR>
         </table>            
     </div>
     <script>
      $('#CuboSolvency').mouseover(function(){ $('#DettBs').show();});
      $('#CuboSolvency').mouseleave(function(){ $('#DettBs').hide();});
     </script>
    </td>
    </tr>
    </table>     
    <?php   
   db2_close();
   ?></CENTER><?php
}
$NoControl=$_POST['NoControl'];
?>
<script>

$('#ShowLRStatus').submit(function(){
  $('#Waiting').show();
  var input = $("<input>")
  .attr("type", "hidden")
  .attr("name", "NoControl")
  .val('1');
  $('#ShowLRStatus').append($(input));  
});

setInterval(function(){ 
  $('#ShowLRStatus').submit();
}, 30000);



function OpenShSel(vId){
    window.open('../PAGE/PgStatoShell.php?IDSELEM='+vId);
}


</script>
<BR>
<BR>
<BR>
<BR>
<BR>
<BR>
