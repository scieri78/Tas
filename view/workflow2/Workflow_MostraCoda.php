<?php
include './GESTIONE/connection.php';

$IdWorkFlow=$_POST["IdWorkFlow"];
$SelIdProcess=$_POST["IdProcess"];

if ( "$IdWorkFlow" == "" or "$SelIdProcess" == ""  ) { exit; }


$sqlTabRead = "SELECT 
ID_PROCESS
,C.ID_FLU ID_FLU
,(SELECT FLUSSO FROM WFS.FLUSSI WHERE ID_FLU = C.ID_FLU ) FLUSSO
,TIPO
,CASE TIPO
WHEN 'C' THEN (SELECT CARICAMENTO FROM WFS.CARICAMENTI WHERE ID_CAR = C.ID_DIP )
WHEN 'E' THEN (SELECT ELABORAZIONE FROM WFS.ELABORAZIONI WHERE ID_ELA = C.ID_DIP )
END AS DIPENDENZA
,ID_DIP
,AZIONE
,UTENTE
,FILE
,TO_CHAR(TMS_INSERT,'YYYY-MM-DD HH24:MI:SS') TMS_INSERT
,CASE TIPO
WHEN 'C' THEN null
WHEN 'E' THEN (
SELECT PARALL 
FROM 
WORK_CORE.CORE_SH_ANAG A,
WFS.ELABORAZIONI E
WHERE A.ID_SH = E.ID_SH
AND E.ID_ELA = C.ID_DIP
)
END AS SHELL_PARALL
, NVL(( SELECT ESITO FROM WFS.ULTIMO_STATO WHERE  ID_PROCESS = C.ID_PROCESS AND ID_WORKFLOW = C.ID_WORKFLOW AND ID_FLU = C.ID_FLU AND TIPO = C.TIPO AND ID_DIP = C.ID_DIP ),'N') ESITO
FROM WFS.CODA_RICHIESTE C
WHERE ID_WORKFLOW = $IdWorkFlow
ORDER BY TMS_INSERT
 ";

$stmtTabRead = db2_prepare($conn, $sqlTabRead);
$resultTabRead = db2_execute($stmtTabRead); 
if ( ! $resultTabRead) { echo "Stmt Exec Error ".db2_stmt_errormsg(); } 
?>
<div class="Bottone" onclick="$('#MostraCoda').empty().hide()" >Chiudi</div><BR>
<table class="ExcelTable" >
<tr>
    <th>ID_PROCESS</th>
    <th>FLUSSO</th>
    <th>TIPO</th>
    <th>DIPENDENZA</th>
    <th>UTENTE</th>
    <th>NOTE</th>
    <th>TMS_INSERT</th>
    <th></th>
<tr>
<?php
while ($rowTabRead = db2_fetch_assoc($stmtTabRead)) {
    $IdProcess =$rowTabRead['ID_PROCESS'];
    $IdFlu     =$rowTabRead['ID_FLU'];
    $Flusso    =$rowTabRead['FLUSSO'];
    $Tipo      =$rowTabRead['TIPO'];
    $Dipendenza=$rowTabRead['DIPENDENZA'];
    $IdDip     =$rowTabRead['ID_DIP'];
    $Azione    =$rowTabRead['AZIONE'];
    $Utente    =$rowTabRead['UTENTE'];
    $File      =$rowTabRead['FILE'];
    $Tms       =$rowTabRead['TMS_INSERT'];
	$Parall    =$rowTabRead['SHELL_PARALL'];
	$Esito    =$rowTabRead['ESITO'];
    
    ?>
    <tr>
        <td><?php echo $IdProcess ; ?></td>
        <td><?php echo $Flusso    ; ?></td>
        <td><?php 
		if( "$Tipo" == "C" ){ echo "Caricamento"; }else{ echo "Elaborazione"; } 
		if( "$Tipo" == "E" ){
		   if( "$Parall" == "Y" ){	
		     ?><img class="ImgIco" style="padding-left:10px;" title="Parallelo" src="./images/Parall.png" ><?php
		   } else {
			 ?><img class="ImgIco" style="padding-left:10px;" title="Non Parallelo" src="./images/NoParall.png" ><?php
		   }
		}
		?></td>
        <td><?php echo $Dipendenza; ?></td>
        <td><?php echo $Utente    ; ?></td>
        <td><?php echo $File      ; ?></td>
        <td><?php echo $Tms       ; ?></td>
        <td>
          <?php
          if ( "$IdProcess" == "$SelIdProcess" and "$Esito" == "N" ){ /* -- */
             ?><img class="ImgIco" src="./images/Cestino.png" style="cursor:pointer;" onclick="RimuoviDaCoda(<?php echo $IdFlu; ?>,'<?php echo $Tipo; ?>',<?php echo $IdDip; ?>)"><?php
          }
          ?>
        </td>
    <tr>                       
    <?php
}
?></table>
<script>
 function RimuoviDaCoda(vIdFlu,vTipo,vIdDip){
        var re = confirm('Confermi la rimozione dalla Coda?');
        if ( re == true ) {   
                   
                  var input = $("<input>")
                  .attr("type", "hidden")
                  .attr("name", "CodaIdFlu")
                  .val(vIdFlu);
                  $('#MainForm').append($(input));
                  
                  var input = $("<input>")
                  .attr("type", "hidden")
                  .attr("name", "CodaTipo")
                  .val(vTipo);
                  $('#MainForm').append($(input));
                  
                  var input = $("<input>")
                  .attr("type", "hidden")
                  .attr("name", "CodaIdDip")
                  .val(vIdDip);
                  $('#MainForm').append($(input));
                  
                  var input = $("<input>")
                  .attr("type", "hidden")
                  .attr("name", "Action")
                  .val('CancellaCoda');
                  $('#MainForm').append($(input));
                  
                //   $("#Waiting").show();
                   $('#MainForm').submit();         
                   
        }
 }
</script>
<?php
db2_close($conn); 
?>
