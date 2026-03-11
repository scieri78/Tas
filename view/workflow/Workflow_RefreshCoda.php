<?php
include './GESTIONE/connection.php';

$IdWorkFlow=$_POST["IdWorkFlow"];
$IdProcess=$_POST["IdProcess"];

if ( "$IdWorkFlow" == "" or "$IdProcess" == ""  ) { exit; }


$SelIdFlu=$_POST["SelIdFlu"];
$SelNomeFlu=$_POST["SelNomeFlu"];
$SelIdDip=$_POST["SelIdDip"];
$SelNomeDip=$_POST["SelNomeDip"];
$SelTipo=$_POST["SelTipo"];
$OldMaxTime=$_POST["MaxTime"];
$SetMaxTime=$OldMaxTime;

if (  
     "$SelIdFlu" == "" 
     ){
    $SelIdFlu=0;
    $SelIdDip=0;
}


$sqlTabRead = "SELECT
( SELECT count(*)  
FROM WFS.CODA_RICHIESTE 
WHERE 1=1
 AND ID_WORKFLOW = $IdWorkFlow
 AND ID_PROCESS IN ( SELECT ID_PROCESS FROM WORK_CORE.ID_PROCESS WHERE FLAG_STATO != 'C' )
 AND AZIONE IN ('E','C')
) INTHISWORKFLOW
,( SELECT count(*)  
FROM WFS.CODA_RICHIESTE 
WHERE 1=1
 AND ID_WORKFLOW = $IdWorkFlow
 AND ID_PROCESS  = $IdProcess
 AND AZIONE IN ('E','C')
) INTHISPROCESS
,( SELECT TO_CHAR(MAX(TMS_INSERT),'YYYY-MM-DD HH24:MI:SS.FF')
FROM WFS.CODA_STORICO 
WHERE 1=1
 AND ID_WORKFLOW = $IdWorkFlow
 AND ID_PROCESS  = $IdProcess
) MAXTIME
,( SELECT count(*)  
FROM WFS.ULTIMO_STATO 
WHERE 1=1
 AND ID_WORKFLOW = $IdWorkFlow
 AND ID_PROCESS  = $IdProcess
 AND TIPO IN ('E','C')
 AND ESITO = 'I'
) INRUN
,TO_CHAR(CURRENT_TIMESTAMP,'YYYY-MM-DD HH24:MI:SS.FF') ORA
FROM DUAL
 ";
$CntProc=0;
$stmtTabRead = db2_prepare($conn, $sqlTabRead);
$resultTabRead = db2_execute($stmtTabRead); 
if ( ! $resultTabRead) { echo "Stmt Exec Error ".db2_stmt_errormsg(); } 
while ($rowTabRead = db2_fetch_assoc($stmtTabRead)) {
    $CntWfs=$rowTabRead['INTHISWORKFLOW'];
    $CntProc=$rowTabRead['INTHISPROCESS'];
    $MaxTime=$rowTabRead['MAXTIME'];
    $InRun=$rowTabRead['INRUN'];
	$Now=$rowTabRead['ORA'];
}

$sql="SELECT ID_FLU,TIPO,ID_DIP, 
(
SELECT ID_LEGAME FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = C.ID_WORKFLOW AND ID_FLU = C.ID_FLU AND TIPO = C.TIPO AND ID_DIP = C.ID_DIP
) ID_LEGAME
,( SELECT ESITO FROM WFS.ULTIMO_STATO WHERE ID_PROCESS = C.ID_PROCESS AND ID_WORKFLOW = C.ID_WORKFLOW AND ID_FLU = C.ID_FLU AND TIPO = C.TIPO AND ID_DIP = C.ID_DIP ) ESITO
FROM WFS.CODA_RICHIESTE C WHERE ID_WORKFLOW = $IdWorkFlow AND ID_PROCESS  = $IdProcess AND AZIONE IN ('E','C')";
$stmt = db2_prepare($conn, $sql);
$result = db2_execute($stmt);
if ( ! $result) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }  
while ($row = db2_fetch_assoc($stmt)) {
    $Flu=$row['ID_FLU'];
    $Tip=$row['TIPO'];
    $Dip=$row['ID_DIP'];
    $Legame=$row['ID_LEGAME'];
    $Esito=$row['ESITO'];
	
	?>
	<script>
	  $('#StatusFlusso<?php echo $Flu; ?>').load('./PHP/WorkFlow_Flusso.php',{
			 IdWorkFlow: <?php echo $IdWorkFlow; ?>,
			 IdProcess: <?php echo $IdProcess; ?>,
			 IdFlusso: <?php echo $Flu; ?>
	  });   
	</script>       

    <script>        
       $('#ImgRefresh<?php echo $Legame; ?>').hide();
       $('#IcoRefresh<?php echo $Flu; ?>').hide();
       
    <?php if ( "$Esito" == "I" ) { ?>
       $('#ImgDip<?php echo $Legame; ?>').hide();
       $('#IcoSveg<?php echo $Flu; ?>').hide();
       $('#ImgRun<?php echo $Legame; ?>').show();
       $('#IcoRun<?php echo $Flu; ?>').show();
	   $('#ImgSveglia<?php echo $Legame; ?>').hide();
       $('#ElabInCodaWait').hide();
       $('#ElabInCodaLoad').show();
    <?php   } else { ?>    
       $('#ImgRun<?php echo $Legame; ?>').hide();
       $('#IcoRun<?php echo $Flu; ?>').hide();
       $('#IcoSveg<?php echo $Flu; ?>').show();
	   $('#ImgSveglia<?php echo $Legame; ?>').show();
       $('#ElabInCodaLoad').hide();
       $('#ElabInCodaWait').show();
    <?php   } ?>   
    </script>
    <?php
}

if ( "$OldMaxTime" == "" ){
    $OldMaxTime=$Now;
	$SetMaxTime=$OldMaxTime;
}

function RefreshSons($Flu){
    global $conn,$IdWorkFlow,$IdProcess;
	$sql="SELECT ID_FLU  FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = $IdWorkFlow AND TIPO = 'F' AND ID_DIP = $Flu ";
    $stmt = db2_prepare($conn, $sql);
    $result = db2_execute($stmt);
    if ( ! $result) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }  
    while ($row = db2_fetch_assoc($stmt)) {
        $FluIn=$row['ID_FLU'];
        ?>
        <script>
          $('#StatusFlusso<?php echo $FluIn; ?>').load('./PHP/WorkFlow_Flusso.php',{
                 IdWorkFlow: <?php echo $IdWorkFlow; ?>,
                 IdProcess: <?php echo $IdProcess; ?>,
                 IdFlusso: <?php echo $FluIn; ?>
          });   
        </script>       
        <?php   
        RefreshSons($FluIn);
    } 
}


$sql="SELECT ID_FLU, ( SELECT FLUSSO FROM WFS.FLUSSI WHERE ID_FLU = C.ID_FLU ) FLUSSO, TIPO, ID_DIP
,(
SELECT ID_LEGAME FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = C.ID_WORKFLOW AND ID_FLU = C.ID_FLU AND TIPO = C.TIPO AND ID_DIP = C.ID_DIP
) ID_LEGAME 
,ESITO
,TO_CHAR(CURRENT_TIMESTAMP,'YYYY-MM-DD HH24:MI:SS.FF') LAST
FROM WFS.CODA_STORICO C WHERE ID_WORKFLOW = $IdWorkFlow AND ID_PROCESS  = $IdProcess AND TMS_INSERT > '$OldMaxTime'
ORDER BY LAST";

$stmt = db2_prepare($conn, $sql);
$result = db2_execute($stmt); 
if ( ! $result) { echo "Stmt Exec Error ".db2_stmt_errormsg(); } 
while ($row = db2_fetch_assoc($stmt)) {
    $Flu=$row['ID_FLU'];
    $NomeFlu=$row['FLUSSO'];
    $Tip=$row['TIPO'];
    $Dip=$row['ID_DIP'];
    $Legame=$row['ID_LEGAME'];
    $EsitoDip=$row['ESITO'];
    
	
    $SetMaxTime=$row['LAST'];
	
    ?>
    <script>
        $('#StatusFlusso<?php echo $Flu; ?>').load('./PHP/WorkFlow_Flusso.php',{
             IdWorkFlow: <?php echo $IdWorkFlow; ?>,
             IdProcess: <?php echo $IdProcess; ?>,
             IdFlusso: <?php echo $Flu; ?>
        }); 
    </script>       
    <?php   
	
    RefreshSons($Flu);
	
	?>
	<script>
	 $('#ImgRun<?php echo $Legame; ?>').hide();
	 $('#ImgDip<?php echo $Legame; ?>').hide();
	 $('#ImgRefresh<?php echo $Legame; ?>').show();
	</script>
	<?php
    
	if ( "$SelIdFlu" == "$Flu" ){
		  ?>
		  <script>
			  //$('#ShowDettFlusso').empty().load('./PHP/WorkFlow_LoadFlusso.php',{
				//	 IdWorkFlow: <?php echo $IdWorkFlow; ?>,
				//	 IdProcess:  <?php echo $IdProcess; ?>,
				//	 IdFlu:      <?php echo $Flu; ?>,
				//	 Flusso:     '<?php echo $NomeFlu; ?>'
			  //});
			  $('#ImgDip<?php echo $Flu; ?>').hide();
			  $('#ImgRefresh<?php echo $Flu; ?>').show();
		  </script>
		  <?php
	}
	
    if ( "$SelIdFlu" == "$Flu" and "$SelIdDip" == "$Dip" ){
		  
          if ( "$Tip" == "C" or "$Tip" == "E" ){
            if ( "$Tip" == "C" ){
              $NotaRis="Caricamento  $SelNomeDip del Flusso $SelNomeFlu eseguito correttamente";
            }else{
              $NotaRis="Elaborazione $SelNomeDip del Flusso $SelNomeFlu eseguito correttamente"; 
            }       
            $Img="CodaOK";
            if ( "$EsitoDip" != "F" ) {
				 if ( "$EsitoDip" == "W" ) {
					 $TMsg="Warning";
					 $Img="CodaWR"; 
				 }else{
					 $TMsg="Error";
                     $Img="CodaKO"; 
				 }
                 if ( "$Tipo" == "C" ){
                    $NotaRis="$TMsg nel Caricamento $SelNomeDip del Flusso $SelNomeFlu";
                 }else{
                    $NotaRis="$TMsg nell Elaborazione $SelNomeDip del Flusso $SelNomeFlu"; 
                 }
            }
            ?>
            <script>                
               $('#SelDipendenza').val('');
               $('#EsitoUpload').load('./PHP/WorkFlow_Esito.php',{ Img : '<?php echo $Img; ?>', NotaRis : '<?php echo $NotaRis; ?>' }).show();
            </script>
            <?php        
		  }
    } 
}


db2_close($conn); 
?>
<script>                
  $('#LastTimeCoda').val('<?php echo $SetMaxTime; ?>');
  $('#ElabInCodaLoad').hide();
  $('#ElabInCodaWait').hide();
  <?php  
     if ( $CntProc != 0 ){
        ?>$('#ElabInCoda').show();<?php
        if ( $InRun != 0 ){
           ?>$('#ElabInCodaLoad').show();<?php  
        } else {
           ?>$('#ElabInCodaWait').show();<?php  
        }
     } else {
        ?>$('#ElabInCoda').hide();<?php
     }
     if ( $CntProc == $CntWfs ){
         ?>
         $('#PulMostraCoda').css('width','170px');
         $('#FraseCoda').text('<?php  echo $CntProc; ?> in Coda');
         <?php
     }else{
         ?>
         $('#PulMostraCoda').css('width','170px');
         $('#FraseCoda').text("<?php  echo $CntProc; ?> in Coda su <?php  echo $CntWfs; ?> ");
         <?php
     }
     
   ?>
</script>

