<div class="breadcrumbs">WFS Admin | 
	  <span>Forza Cambio Stato Dipendenza</span></div>

<form id="FormStatus" method="POST">
 <input type="hidden" id="Azione" name="Azione" value="" />

<table class="display dataTable" >
<thead class="headerStyle">
<tr>
      <th>TEAM</th>
      <th>WORKFLOW</th>
      <th>FLUSSO</th>
      <th>TIPO</th>
      <th>DIPENDENZA</th>
      <th>ID_PROCESS</th>
      <th>STATO</th>
      <th></th>
</tr>
	</thead>
		<tbody>
<tr>
    <td>
      <select class="selectSearch" onchange="getWorkflow()" name="SelIdTeam" id="SelIdTeam">
        <option value="" >..</option>
        <?php
        
        foreach ($datiTeam as $row) {
          $RIdDip=$row['ID_TEAM'];
          $RName=$row['TEAM'];
          ?><option value="<?php echo $RIdDip; ?>" <?php if ( "$RIdDip" == "$SelIdTeam" ){ ?> selected <?php } ?> ><?php echo $RName; ?></option><?php
        }
        ?>
      <select>
    </td>   
    <td>
      <select class="selectSearch ModSt" onchange="getFlusso()" name="SelWkf" id="SelWkf">
        <option value="" >..</option>
        <?php       
        foreach ($datiWorkflow as $row) {
          $RIdDip=$row['ID_WORKFLOW'];
          $RName=$row['WORKFLOW'];
          ?><option value="<?php echo $RIdDip; ?>" <?php if ( "$RIdDip" == "$SelWkf" ){ ?> selected <?php } ?> ><?php echo $RName; ?></option><?php
        }
        ?>
      <select>
    </td>
    <td>
      <select class="selectSearch ModSt"onchange="getTipo()" name="SelFls" id="SelFls" >
        <option value="" >..</option>
        <?php         
        foreach ($datiFlussi as $row) {
          $RIdDip=$row['ID_FLU'];
          $RName=$row['FLUSSO'];
          ?><option value="<?php echo $RIdDip; ?>" <?php if ( "$RIdDip" == "$SelFls" ){ ?> selected <?php } ?>><?php echo $RName; ?></option><?php
        }
        ?>
      <select>
    </td>
    <td>
      <select class="selectSearch ModSt" onchange="getDipendenza()" name="SelTp" id="SelTp" >
        <option value="" >..</option>
        <?php          
        foreach ($datiLegameFlussi as $row) {
          $RIdDip=$row['TIPO'];
          if ( "F" == "$RIdDip" ){ $RName="Flusso"; }
          if ( "V" == "$RIdDip" ){ $RName="Validazione"; }
          if ( "E" == "$RIdDip" ){ $RName="Elaborazione"; }
          if ( "C" == "$RIdDip" ){ $RName="Caricamento"; }
          if ( "L" == "$RIdDip" ){ $RName="Link"; }       
          ?><option value="<?php echo $RIdDip; ?>" <?php if ( "$RIdDip" == "$SelTp" ){ ?> selected <?php } ?>><?php echo $RName; ?></option><?php
        }
        ?>      
      <select>
    </td>
    <td>
      <select class="selectSearch ModSt" onchange="getIdProc()" name="SelDp" id="SelDp" >
        <option value="" >..</option>
        <?php   
          foreach ($datiDipendenza as $row) {
          $RIdDip=$row['ID_DIP'];
          $RName=$row['NAME'];
          ?><option value="<?php echo $RIdDip; ?>" <?php if ( "$RIdDip" == "$SelDp" ){ ?> selected <?php } ?>><?php echo $RName; ?></option><?php
        }
        ?>
      <select>
    </td>
    <td>
      <select class="selectSearch ModSt" onchange="getStato()" name="SelIdProc" id="SelIdProc">
        <option value="" >..</option>
        <?php       
        foreach ($datiIdProcess as $row) {
          $RIdDip=$row['ID_PROCESS'];
          ?><option value="<?php echo $RIdDip; ?>" <?php if ( "$RIdDip" == "$SelIdProc" ){ ?> selected <?php } ?> ><?php echo $RIdDip; ?></option><?php
        }
        ?>
      <select>
    </td>
    <td>
      <select class="selectSearch ModSt" onchange="setSave('<?php echo $HideSave; ?>')" name="SelSt" id="SelSt" >
        <option value="" >..</option>
        <option value="F" <?php if ( "F" == "$SelSt" ){ ?> selected <?php } ?> >Lanciato</option>
        <option value="N" <?php if ( "N" == "$SelSt" ){ ?> selected <?php } ?> >Da Lanciare</option>
		<option value="R" <?php if ( "R" == "$SelSt" ){ ?> selected <?php } ?> >Resetta Warning</option>
      <select>
    </td>
    <td>
	<button style="display:none" onclick="submitForm();$('#Azione').val('');submitForm();return false;"  id="SalvaChange" class="btn"><i class="fa-solid fa-save"> </i> Salva</button>
	
	</td>
</tr>
</tbody>
</table>
</form>
<br>
<br>
<br>
<?php echo $tabellaStato; ?>
<br>
<br>
<?php echo $tabellaCodaStorico; ?>
<script>
 $(".selectSearch").select2();
 $('.selectNoSearch').select2({minimumResultsForSearch: -1}); 
</script>



