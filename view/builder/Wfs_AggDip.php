 <form id="FormDipS"> 
    <input type="hidden" name="IdFlu" id="IdFlu" value="<?php echo $IdFlu; ?>" >
	<input type="hidden" name="IdDip" id="IdDip" value="<?php echo $IdDip; ?>" >
	<input type="hidden" id="TopScroll" name="TopScroll" value="<?php echo $TopScroll; ?>" />
	<input type="hidden" id="BodyHeight" name="BodyHeight" value="<?php echo $BodyHeight; ?>" />
	<input type="hidden" Name="IdWorkFlow" id="IdWorkFlow" value="<?php echo $IdWorkFlow; ?>" >
	<input type="hidden" Name="ndialog" id="ndialog" value="<?php echo $ndialog; ?>" >
	
	<label>Select Tipology</label>
	<select class="selectpicker" data-live-search="true" onchange="aggiungiDipendenza(<?php echo $IdWorkFlow; ?>,<?php echo $IdFlu; ?>,this.value,<?php echo $ndialog; ?>)" id="selectTipology" name="selectTipology">
					<option value="" <?php if ( $SelTipo == "" ){?>selected<?php } ?> > ... </option>
					<option value="F" <?php if ( $SelTipo == "F" ){?>selected<?php } ?> ><i class="fa-solid fa-network-wired"></i> Flusso</option>
					<option value="C" <?php if ( $SelTipo == "C" ){?>selected<?php } ?> ><i class="fa fa-upload"></i> Caricamento</option>
					<option value="LE" <?php if ( $SelTipo == "LE" ){?>selected<?php } ?> ><i class="fa fa-link"></i> Link Ext</option>
					<option value="LI" <?php if ( $SelTipo == "LI" ){?>selected<?php } ?> ><i class="fa fa-gear"></i> Link Int</option>
					<option value="E" <?php if ( $SelTipo == "E" ){?>selected<?php } ?> ><i class="fa fa-gears"></i> Elaborazione</option>
					<option value="V" <?php if ( $SelTipo == "V" ){?>selected<?php } ?> ><i class="fa fa-check"></i> Validazione</option>
					
				</select>
	
<?php  
if($Tipo)      
include "./view/builder/FORM/dip_".$Tipo.".php";     
?>

  </form>
  <script>
  $('#selectTipology').selectpicker('refresh');
  $(".selectSearch").select2();
  $('.selectNoSearch').select2({minimumResultsForSearch: -1});
  $.ui.dialog.prototype._allowInteraction = function (e) {return true;}; 
  </script>

	
