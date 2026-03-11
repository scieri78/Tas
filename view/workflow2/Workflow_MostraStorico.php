<div class="Bottone" onclick="$('#MostraStorico').empty().hide()" >Chiudi</div><BR>
<table class="ExcelTable" >
<tr>
    <th>INIZIO</th>
	<th>FINE</th>
	<th>TIME</th>
    <th>FLUSSO</th>
    <th>TIPO</th>
    <th>DIPENDENZA</th>
	<th>ESITO</th>
    <th>NOTE</th>
    <th>UTENTE</th>
<tr>
<?php
foreach ($DatiStoricoFlussi  as $rowTabRead) {
    $IdProcess =$rowTabRead['ID_PROCESS'];
    $IdFlu     =$rowTabRead['ID_FLU'];
    $Flusso    =$rowTabRead['FLUSSO'];
    $Tipo      =$rowTabRead['TIPO'];
    $Dipendenza=$rowTabRead['DIPENDENZA'];
    $IdDip     =$rowTabRead['ID_DIP'];
    $Azione    =$rowTabRead['AZIONE'];
    $Utente    =$rowTabRead['UTENTE'];
    $File      =$rowTabRead['FILE'];
    $DStart    =$rowTabRead['INIZIO'];
	$DEnd      =$rowTabRead['FINE'];
	$DipDiff   =$rowTabRead['DIFF'];
	$Parall    =$rowTabRead['SHELL_PARALL'];
	$Note      =$rowTabRead['NOTE'];
	$Esito     =$rowTabRead['ESITO'];
	$IdRunSh   =$rowTabRead['ID_RUN_SH'];
    
	
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
           $ImgTipo="Link";
           break;  
    }
	$ImgDip="";
	switch ($Esito) {
           case "E": 
             $ImgDip='./images/KO.png';
             break;
           case "F": 
             $ImgDip='./images/OK.png'; 
             break;
           case "C":               
             $ImgDip='./images/Loading.gif';
             break;  
           case "W":               
             $ImgDip='./images/Warning.png';
             break;                      
           default:
              $ImgDip='./images/OK.png'; 
         }      
	
    ?>
    <tr>
        <td><?php echo $DStart; ?></td>
		<td><?php echo $DEnd; ?></td>
		<td><?php echo gmdate('H:i:s', $DipDiff); ?></td>
        <td><?php echo $Flusso; ?></td>
        <td><img class="ImgIco" src="./images/<?php echo $ImgTipo; ?>.png" title="<?php echo $IdDip; ?>" ><?php
		if( "$Tipo" == "E" ){
		   if( "$Parall" == "Y" ){	
		     ?><img class="ImgIco" style="padding-left:10px;" title="Parallelo" src="./images/Parall.png" ><?php
		   } else {
			 ?><img class="ImgIco" style="padding-left:10px;" title="Non Parallelo" src="./images/NoParall.png" ><?php
		   }
		}
		?></td>
        <td><?php echo $Dipendenza; ?></td>
		<td><img class="ImgIco" src="<?php echo $ImgDip; ?>" title="<?php echo $IdDip; ?>" ></td>
		<td><?php 
		if ( "$IdRunSh" != "" ) { 
		  ?><img class="ImgIco"  style="height:35px;cursor:pointer;" src="./images/LogProc.png"    onclick="OpenProcessing(<?php echo $IdRunSh; ?>)" ><?php  
        } else { 
		  echo $Note; 
		}
		?></td>
        <td><?php echo $Utente    ; ?></td>
    <tr>                       
    <?php
}
?></table>
<script>
 
  function OpenProcessing(vIdRunSh){
      window.open('./index.php?sito='+getParameterByName('sito'))'&controller=statoshell&action=index&IDERR='+vIdRunSh+'&IDPROCERR=<?php echo $SelIdProcess; ?>');
  }
</script>

