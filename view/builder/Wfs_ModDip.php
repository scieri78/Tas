<?php
    $TotLiv=20;    
    if ( "$conn" == "Resource id #4" ) {
      $conn = db2_connect($db2_conn_string, '', '');
    }?>
   <form id="FormDipS"> 
    <input type="hidden" name="IdLegame" id="IdLegame" value="<?php echo $IdLegame; ?>" >
	<input type="hidden" name="IdFlu" id="IdFlu" value="<?php echo $IdFlu; ?>" >
	<input type="hidden" name="IdDip" id="IdDip" value="<?php echo $IdDip; ?>" >
	<input type="hidden" id="TopScroll" name="TopScroll" value="<?php echo $TopScroll; ?>" />
	<input type="hidden" id="BodyHeight" name="BodyHeight" value="<?php echo $BodyHeight; ?>" />
	<input type="hidden" Name="IdWorkFlow" id="IdWorkFlow" value="<?php echo $IdWorkFlow; ?>" >
	<input type="hidden" Name="ndialog" id="ndialog" value="<?php echo $ndialog; ?>" >
  
	
	<?php        
    include "./view/builder/FORM/dip_".$Tipo.".php";     
     ?>
  </form>

<script>

	$(".selectSearch").select2();
	$('.selectNoSearch').select2({minimumResultsForSearch: -1});
$.ui.dialog.prototype._allowInteraction = function (e) {
    return true;
}; 
</script>

