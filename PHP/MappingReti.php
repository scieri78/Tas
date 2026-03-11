
<style>

.Titolo{
    position:relative;
    left:0;
    right:0;
    margin:auto;
    font-size:20px;
    width:100%;
}


.Title{
  margin:4px;
  font-size:20px;
  left:0;
  right:0;
  margin:auto;
  width: max-content;
  color: blue;
}

.Button{
  width:145px;
  height:30px;
  border: 1px solid black;
  bachground:white;
  text-align: center;
  margin:5px;
  cursor:pointer;
}
</style>
<?php
include '../GESTIONE/sicurezza.php';

if ( $find == 1 ){
    
    function alert($msg) {
        echo "<script type='text/javascript'>alert('$msg');</script>";
    }
    $SetRete=$_POST['SET_RETE'];
    $SelResearchType=$_POST['SEL_RESEARCH_TYPE'];
    $SelEnableRete=$_POST['SEL_ENABLE_RETE'];
    $SelFind=$_POST['SelFind'];
    #$$SelResearchType=$_POST['SelResearchType'];
    $Filenamepart=$_POST['Filenamepart'];
    
    if ( $SelEnableRete == ""){
        $SelEnableRete="Y";
    }
    if ( $SelResearchType == ""){
        $SelResearchType="Table";
    }
    
    ?>
<form id="FormSelectRete" method="POST">

<center><div class="Titolo" >Mapping Reti TWS</div></center>
<table width="100%" class="ExcelTable" >    
<tr><td style="width: 30px;" ><input type="submit" value="REFRESH" class="Bottone" style="background:yellow;" ></td>
<th style="width: 100px;" >Select Rete:</th>
<td colspan=2 style="width: 100px;"  >
<select name="SET_RETE" id="Sel_Rete"  onchange="$('#Waiting').show();$('#FormSelectRete').submit();" style="width: 115px;">
            <option value=".." <?php if ( "$SetRete" == ".." Or "$SetRete" == "" ){ ?>selected<?php } ?>  >..</option>
            <?php
            if ( $SelEnableRete != "ALL"){
            $wcondEneble="AND RETE_ENABLE = '${SelEnableRete}'";
            }
            $sql = "SELECT RETE, RETE_SHOW, ENABLE FROM 
                        (SELECT distinct RETE, DECODE(RETE,LEFT(OLD_SH,5),RETE,RETE||' ('||LEFT(OLD_SH,5)||')') RETE_SHOW, ENABLE FROM WORK_CORE.CORE_RETI_TWS tws
                          INNER JOIN WORK_CORE.CORE_SH sh ON sh.ID_SH = tws.ID_SH
                          INNER JOIN WORK_CORE.CORE_RETI_TWS_OLD old ON old.NEW_SH = sh.NAME
                          WHERE 1=1 ) ORDER BY RETE";
            $conn = db2_connect($db2_conn_string, '', '');
            $stmt=db2_prepare($conn, $sql);
            $result=db2_execute($stmt);
            
            if ( ! $result ){
                echo "ERROR DB2: ".db2_stmt_errormsg($stmt).':'.$sql;
            }
            #db2_close($db2_conn_string);
          
            while ($row = db2_fetch_assoc($stmt)) {
                $NomeRete=$row['RETE'];
                $NomeReteShow=$row['RETE_SHOW'];
                ?><option value="<?php echo $NomeRete; ?>" <?php if ( "$SetRete" == "$NomeRete" ){ ?>selected<?php } ?> >
                    <?php echo $NomeReteShow; ?>
                </option><?php
            }
            $Filenamepart="Mapping${User}";
            if ( "${SetRete}"!="" And "${SetRete}"!=".."){
                $Filenamepart="${Filenamepart}${SetRete}";
            }
            if ( "${SelFind}"!="" ){
                $Filenamepart="${Filenamepart}Filtered";
            }
            $Filename="${rootdir}/TMP/${Filenamepart}.csv";
            ?>
</select>
<?php
#echo $sql;
$sql = "SELECT count(RETE) CNT FROM TASPCUSR.WORK_CORE.CORE_RETI_TWS";

#$conn = db2_connect($db2_conn_string, '', '');
$stmt=db2_prepare($conn, $sql);
$result=db2_execute($stmt);

if ( ! $result ){
    echo "ERROR DB2: ".db2_stmt_errormsg($stmt).':'.$sql;
}

db2_close($db2_conn_string);

while ($row = db2_fetch_assoc($stmt)) {
    $CntRetiUSR=$row['CNT'];
} ?>
</td>
<th style="width: 100px;" >Research</th>
<td style="width: 100px; " >
<select id="SEL_RESEARCH_TYPE" name="SEL_RESEARCH_TYPE" style="width: 100px; " onchange="ChangeMess()" >
            <option value="Table" <?php if ( "$SelResearchType" == "Table" ) { ?> selected <?php } ?> >Table</option>
            <option value="Shell" <?php if ( "$SelResearchType" == "Shell" ) { ?> selected <?php } ?> >Shell</option>
            <option value="Sql" <?php if ( "$SelResearchType" == "Sql" ) { ?> selected <?php } ?> >Sql</option>
</select>
</td>
<td style="width: 300px;border-right:none;">
  <input type="text" name="SelFind" id="SelFind" value="<?php echo "$SelFind"; ?>" >
  <input type="submit" name="Research" value="Research" style="width:100px;" onclick="$('#Waiting').show();$('#FormSelectRete').submit();" >
</td>
<td <?php if ( strlen("$SelFind")!=0 And strlen("$SelFind")<=3 ) { ?>  style="width: 750px;border-left:none;color:Red;"  
  <?php } else { ?> style="width: 750px;border-left:none;" <?php } ?> >
  <div id="ShowMeM" >
  <?php 
  if ( strlen("$SelFind")>0 & strlen("$SelFind")<=3 ) { 
    echo "Research with at least 4 characters";
  } else { 
    if ( "$SelResearchType"=="Table" ) {  echo "Exact search, use % to find more results (CLAIMS_1LIV%, PLUS%, ...)";  }
  } 
  ?> 
  </div>
</td>
<th style="width: 100px;" >Enabled:</th>
<td style="width: 50px; " >
<select name="SEL_ENABLE_RETE" onchange="$('#Waiting').show();$('#FormSelectRete').submit();">
            <option value="Y" <?php if ( "$SelEnableRete" == "Y" ) { ?> selected <?php } ?> >Y</option>
            <option value="N" <?php if ( "$SelEnableRete" == "N" ) { ?> selected <?php } ?> >N</option>
            <option value="ALL" <?php if ( "$SelEnableRete" == "ALL" ) { ?> selected <?php } ?> >ALL</option>
</select>
</td>
<td style="width: 30px;" ><a href="<?php echo "../TMP/${Filenamepart}.csv"; ?>"><img src="../images/download.png" style="width:30px;cursor:pointer;" ></img></a></td>
</tr>
<?php if ( "$CntRetiUSR" >0 ){ ?> 
<tr>
<td style="border-right:none;" align="right"> 
  <img  style="width:30px;" src="../images/Alert.png" > 
</td>
<td style="border-left:none;" colspan=6  > <?php echo 'Presenti ' . $CntRetiUSR . ' reti sul TASPCUSR (verificare e sistemare la WORK_CORE.CORE_RETI_TWS)'; ?> </td> 
</tr>
<?php } ?>
</table>
</form>
<table width="100%" class="ExcelTable" >
<form id="FormMappingReti" method="POST">
    <tr>
         <th></th>
         <th colspan=2>NAME_RETE</th>
         <th>PATH_SH</th>
         <th colspan=2>NAME_SH</th>
         <th>VARIABLES</th>
         <th>TAGS</th>
         <th>STEP_SH</th>  
         <th>STEP_DB</th> 
         <th colspan=2>FILE_SQL</th>  
         <th>ROUTINE</th>
         <th>LISTA_TABELLE_SH</th>
         <th>LISTA_TABELLE_SQL</th>
         <th>LISTA_TABELLE_PK</th>
         <th>START_TIME_SH</th>
         <th>END_TIME_SH</th>
         <th>FREQUENZA</th>
    </tr>
    <?php    
    $Testo="ENABLE;NAME_RETE;PASSO_RETE;NAME_RETE_STATSIN;DATABASE;PATH_SH;NAME_SH;VARIABLES;MAIL;TAGS;STEP_SH;STEP_DB;TYPE_RUN;FILE_SQL;PACKAGE_SCHEMA;PACKAGE_NAME;PACKAGE_ROUTINE;LISTA_TABELLE_SH;LISTA_TABELLE_SQL;LISTA_TABELLE_PK;START_TIME_SH;END_TIME_SH;CHIAMTE_SUCCESSIVE;FREQUENZA_RETE;FLUSSO_CHIAMANTE";
    if("${SetRete}"!=".."){
      $wcondRete="AND RETE = '${SetRete}'";
    }
    if("${SelFind}"!=""){
      $wcondTab="AND NVL(LENGTH('$SelFind'),0)>3 ";
      switch ($SelResearchType) {
        case "Table":
          $wcondTab="${wcondTab} AND (
               ','||UCASE(LISTA_TABELLE_SH) || ' ' LIKE UCASE(',%$SelFind %')
            OR ','||UCASE(LISTA_TABELLE_SQL) || ' ' LIKE UCASE(',%$SelFind %')
            OR ','||UCASE(LISTA_TABELLE_PK) || ' ' LIKE UCASE(',%$SelFind %')
            OR ','||UCASE(LISTA_TABELLE_SH) || ' ' LIKE UCASE('.%$SelFind %')
            OR ','||UCASE(LISTA_TABELLE_SQL) || ' ' LIKE UCASE('.%$SelFind %')
            OR ','||UCASE(LISTA_TABELLE_PK) || ' ' LIKE UCASE('.%$SelFind %')
            OR ','||UCASE(LISTA_TABELLE_SH) || ' ' LIKE UCASE(',%$SelFind.%')
            OR ','||UCASE(LISTA_TABELLE_SQL) || ' ' LIKE UCASE(',%$SelFind.%')
            OR ','||UCASE(LISTA_TABELLE_PK) || ' ' LIKE UCASE(',%$SelFind.%')) ";
          break;
        case "Shell":
          $wcondTab="${wcondTab} AND UPPER(NAME_SH) LIKE UPPER('%$SelFind%')";
          break;
        case "Sql":
          $wcondTab="${wcondTab} AND UPPER(FILE_SQL) LIKE UPPER('%$SelFind%')";
          break;
      }
    }
    $sql="SELECT RETE_ENABLE ENABLE
     , RETE
     , DECODE(NVL(NAME_RETE_OLD,NAME_RETE),NAME_RETE,NAME_RETE,NAME_RETE || ' (' || NAME_RETE_OLD || ')') NAME_RETE_SHOW
     , NAME_RETE
     , NAME_RETE_OLD
     , DATABASE
     , REPLACE(PATH_SH,'/area_staging_TAS/DIR_SHELL/','') PATH_SH
     , PATH_SH PATH_SH_FULL
     , NAME_SH
     , TAGS
     , MAIL
     , STEP_SH
     , VARIABLES_SH
     , STEP_DB
     , TYPE_RUN
     , FILE_SQL
     , PACKAGE_SCHEMA
     , PACKAGE_NAME
     , PACKAGE_ROUTINE
     , DECODE(PACKAGE_SCHEMA,'','',PACKAGE_SCHEMA || '.' || PACKAGE_NAME || '.' || PACKAGE_ROUTINE) ROUTINE
     , LISTA_TABELLE_SH 
     , LISTA_TABELLE_SQL
     , LISTA_TABELLE_PK
     , START_TIME_SH
     , END_TIME_SH
     , ID_SH_RETE
     , ID_RUN_SH_RETE
     , ID_SH
     , ID_RUN_SH
     , ID_RUN_SQL
     , ID_RUN_SH_LIV_SUCC
     , WORKFLOW
     , FREQUENZA_WORKFLOW
     , FLUSSO_CHIAMANTE
FROM WORK_RULES.V_MAPPING_RETI
WHERE 1=1
      ${wcondTab}
      ${wcondRete}
      ${wcondEneble}
ORDER BY RETE desc, NAME_RETE desc, NAME_SH desc;
    ";
    #echo $sql;
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    if ( ! $result) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
    while ($row = db2_fetch_assoc($stmt)) {
        $ENABLE            =$row['ENABLE'];
        $RETE              =$row['RETE'];
        $NAME_RETE_SHOW    =$row['NAME_RETE_SHOW'];
        $NAME_RETE         =$row['NAME_RETE'];
        $NAME_RETE_OLD     =$row['NAME_RETE_OLD'];
        $DATABASE          =$row['DATABASE'];
        $PATH_SH           =$row['PATH_SH'];
        $PATH_SH_FULL      =$row['PATH_SH_FULL'];
        $NAME_SH           =$row['NAME_SH'];
        $VARIABLES_SH      =$row['VARIABLES_SH'];
        $TAGS              =$row['TAGS'];
        $MAIL              =$row['MAIL'];
        $STEP_SH           =$row['STEP_SH'];
        $STEP_DB           =$row['STEP_DB'];
        $TYPE_RUN          =$row['TYPE_RUN'];
        $FILE_SQL          =$row['FILE_SQL'];
        $PACKAGE_SCHEMA    =$row['PACKAGE_SCHEMA'];
        $PACKAGE_NAME      =$row['PACKAGE_NAME'];
        $PACKAGE_ROUTINE   =$row['PACKAGE_ROUTINE'];
        $ROUTINE           =$row['ROUTINE'];
        $LISTA_TABELLE_SH  =$row['LISTA_TABELLE_SH'];
        $LISTA_TABELLE_SQL =$row['LISTA_TABELLE_SQL'];
        $LISTA_TABELLE_PK  =$row['LISTA_TABELLE_PK'];
        $START_TIME_SH     =$row['START_TIME_SH'];
        $END_TIME_SH       =$row['END_TIME_SH'];
        $ID_SH_RETE        =$row['ID_SH_RETE'];
        $ID_RUN_SH_RETE    =$row['ID_RUN_SH_RETE'];
        $ID_SH             =$row['ID_SH'];
        $ID_RUN_SH         =$row['ID_RUN_SH'];
        $ID_RUN_SQL        =$row['ID_RUN_SQL'];
        $ID_RUN_SH_LIV_SUCC=$row['ID_RUN_SH_LIV_SUCC'];
		$WORKFLOW          =$row['WORKFLOW'];
		$FREQUENZA_WORKFLOW=$row['FREQUENZA_WORKFLOW'];
		$FLUSSO_CHIAMANTE  =$row['FLUSSO_CHIAMANTE'];
		
        
    $Testo="$Testo\r\n$ENABLE;$RETE;$NAME_RETE;$NAME_RETE_OLD;$DATABASE;$PATH_SH_FULL;$NAME_SH;$VARIABLES_SH;$MAIL;$TAGS;$STEP_SH;$STEP_DB;$TYPE_RUN;$FILE_SQL;$PACKAGE_SCHEMA;$PACKAGE_NAME;$PACKAGE_ROUTINE;$LISTA_TABELLE_SH;$LISTA_TABELLE_SQL;$LISTA_TABELLE_PK;$START_TIME_SH;$END_TIME_SH;";
    if ( "$ID_RUN_SH_LIV_SUCC" != "" ){ 
	  $Testo.="Presenza chiamate successive!;";
	} else {
	  $Testo.=";";
    }
	$Testo.="$FREQUENZA_WORKFLOW;$FLUSSO_CHIAMANTE;";
    file_put_contents($Filename, $Testo );
    //shell_exec("$Testo >> $Filename");
      ?>
      <tr>
        <td> 
          <?php if ( "$ENABLE" == "N" ){ ?> <img  style="width:15px;" title="<?php if ( "$ENABLE" == "Y" ){ echo 'ENABLE'; } else { echo 'DISENABLE';}; ?>" 
           src="../images/StatusX.png"  class="IconStatusX" > <?php } ?>  
          <?php if ( "$DATABASE" == "TASPCUSR" ){ echo "USR"; }?> 
          <?php if ( "$ID_RUN_SH_LIV_SUCC" != "" ){ ?> <img style="width:15px;" title="<?php echo "Presenza chiamate successive!"; ?>" 
           src="../images/Attention.png"  class="IconAttention" > <?php } ?>  
        </td>
        <td style="border-right:none;cursor:pointer;" title="<?php echo $ID_SH_RETE; ?>" onclick="OpenFile('<?php echo $ID_SH_RETE; ?>');" >
          <?php echo $NAME_RETE_SHOW; ?>
        </td> 
        <td style="border-left:none;cursor:pointer;"> 
          <img  style="width:30px;" src="../images/Shell.png" title="<?php echo $ID_RUN_SH_RETE; ?>" class="IconFile" onclick="OpenShSel('<?php echo $ID_RUN_SH_RETE; ?>');"  > 
        </td>
        <td><?php echo $PATH_SH; ?></td>    
        <td style="border-right:none;cursor:pointer;" title="<?php echo $ID_SH; ?>"
            onclick="<?php if( "$DATABASE" == "TASPCUSR" ){ ?> OpenFileUsr(' <?php } else { ?> OpenFile('<?php } echo $ID_SH; ?>');" >
          <?php echo $NAME_SH; ?> 
        </td>
        <td style="border-left:none;"> 
          <img style="width:30px;" <?php if ( "$MAIL" == "Y" ){ ?>  src="../images/Mail.png"  <?php } ?>  class="IconMail" > 
        </td>
        <td><?php echo $VARIABLES_SH; ?></td>    
        <td><?php echo $TAGS; ?></td>
        <td><?php echo $STEP_SH; ?></td>
        <td><?php echo $STEP_DB; ?></td>
        <td style="border-right:none;"><?php echo $TYPE_RUN; ?></td>    
        <td <?php if ( "$ID_RUN_SQL" != "" && "$FILE_SQL" != "Anonymous Block" && "$FILE_SQL" != "File Anonymous Block"){ ?> 
             style="border-left:none;cursor:pointer;"   
            onclick="<?php if( "$DATABASE" == "TASPCUSR" ){ ?> OpenSqlFileUsr(' <?php } else { ?> OpenSqlFile('<?php } echo $ID_RUN_SQL; ?>');"
          <?php } else { ?> 
             style="border-left:none;" 
          <?php } ?>> <?php echo $FILE_SQL; ?>
        </td>    
        <td <?php if ( "$PACKAGE_SCHEMA" != "" ){ ?> style="cursor:pointer;"    
            onclick="<?php if( "$DATABASE" == "TASPCUSR" ){ ?> OpenPlsqlUsr(' <?php } else { ?> OpenPlsql('<?php } echo $PACKAGE_SCHEMA; ?>','<?php echo $PACKAGE_NAME; ?>');"
          <?php } ?> ><?php echo $ROUTINE; ?>
        </td>    
        <td><?php echo $LISTA_TABELLE_SH; ?></td>
        <td><?php echo $LISTA_TABELLE_SQL; ?></td>
        <td><?php echo $LISTA_TABELLE_PK; ?></td>
        <td><?php echo $START_TIME_SH; ?></td>
        <td><?php echo $END_TIME_SH; ?></td>
		<td><?php echo $WORKFLOW; ?></td>
      </tr>

      <?php
    }
    shell_exec("chmod 774 $Filename");
    ?>
   </form>
</table>
<SCRIPT>
    
function OpenSqlFile(vIdSql){    
     window.open('../PHP/ApriSqlFile.php?IDSQL='+vIdSql);
}
function OpenSqlFileUsr(vIdSql){    
     window.open('../../TASUSR/PHP/ApriSqlFile.php?IDSQL='+vIdSql);
}

function OpenFile(vIdSh){
    window.open('../PHP/ApriFile.php?IDSH='+vIdSh);
}
function OpenFileUsr(vIdSh){
    window.open('../../TASUSR/PHP/ApriFile.php?IDSH='+vIdSh);
}

function OpenShSel(vId){
    window.open('../PAGE/PgStatoShell.php?IDSELEM='+vId);
}
function OpenShSelUsr(vId){
    window.open('../../TASUSR/PAGE/PgStatoShell.php?IDSELEM='+vId);
}

function OpenPlsql(vSchema,VPackage){
    window.open('../PHP/ApriPlsql.php?SCHEMA='+vSchema+'&PACKAGE='+VPackage);
}
function OpenPlsqlUsr(vSchema,VPackage){
    window.open('../../TASUSR/PHP/ApriPlsql.php?SCHEMA='+vSchema+'&PACKAGE='+VPackage);
}

function OpenTabPlsql(vSchema,VPackage){
    window.open('../PHP/ApriTabPlsql.php?SCHEMA='+vSchema+'&PACKAGE='+VPackage,'TablePackage','width=500,height=500');
}
function OpenTabPlsqlUsr(vSchema,VPackage){
    window.open('../../TASUSR/PHP/ApriTabPlsql.php?SCHEMA='+vSchema+'&PACKAGE='+VPackage,'TablePackage','width=500,height=500');
}

function ChangeMess(){
    $('#ShowMeM').empty();
    if( $('#SEL_RESEARCH_TYPE').val() == 'Table' ){
      $('#ShowMeM').html('Exact search, use % to find more results (CLAIMS_1LIV%, PLUS%, ...)');
    }
}

</SCRIPT>
<?php
    }
    ?>