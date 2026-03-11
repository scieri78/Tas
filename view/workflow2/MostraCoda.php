<?php if($CountCodaRichieste && $WfsRdOnly!=1){?>
<button id="forzaElaborazioniPossibili" onclick="forzaElaborazioniPossibili(<?php echo $IdWorkFlow; ?>,<?php echo $IdProcess; ?>);return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-retweet"></i> Forza Coda</button>
<button id="CancellaCoda" onclick="svuotaCoda(<?php echo $IdProcess; ?>);return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-trash-can"></i> Svuota Coda</button>
<br><br>
<?php }?>
<table id="idTabella" class="display dataTable">
     <thead class="headerStyle">
    <tr>
    <th>ID_PROCESS</th>
    <th>FLUSSO</th>
    <th>TIPO</th>
    <th>DIPENDENZA</th>
    <th>UTENTE</th>
    <th>NOTE</th>
    <th>TMS_INSERT</th>
    <th></th>
    </tr>
	</thead>
	<tbody>
<tr>
<?php
foreach ($DatiCodaFlussi as $rowTabRead) {
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
		switch($Tipo){
		case "C" : echo "Caricamento"; break;
		case "L" : echo "Link";  break;
		case "E" : echo "Elaborazione";
		   if( "$Parall" == "Y" ){	
		     ?><img class="ImgIco" style="padding-left:10px;" title="Parallelo" src="./images/Parall.png" ><?php
		   } else {
			 ?><img class="ImgIco" style="padding-left:10px;" title="Non Parallelo" src="./images/NoParall.png" ><?php
		   }
		   break;
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
?>
<tbody>
</table>
<script>

</script>

