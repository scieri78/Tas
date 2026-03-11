<div class="TitAddUser" ><B><?php echo $WorkFlow.' - '.$Gruppo; ?><B></div>
<div>	<label class="labelFiltro">Utente già censito</label>
	
	<SELECT id="SelUser" class="selectSearch">
	<option value="" >Seleziona Utente</option>
	<?php
	$ListNm="";
	foreach ($DatiUser as $rwU) {      
	  $Kuk=$rwU['UK'];
	  $Nm=$rwU['USERNAME'];
	  $Nom=$rwU['NOMINATIVO'];
	  $ListNm=$ListNm.$Nm.',';
	  ?><option value="<?php echo $Kuk; ?>" ><?php echo "$Nm - $Nom"; ?></<option><?php	  
	}
	$ListNm=substr($ListNm,0,-1);
	?></SELECT>
	</div>
	<button id="addflusso" onclick="AddUserFlusso('<?php echo $IdWorkFlow; ?>','<?php echo $WorkFlow; ?>','<?php echo $IdGruppo; ?>','<?php echo $Gruppo; ?>');return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-plus-circle"> </i> Utente</button>		
	
	<br><br>
	<div>
		<h3>Inserisci Nuovo Utente</h3>
		<div><label class="labelFiltro">Username</label>
		<input style="width:150px;"  type=text" id="AddUserName" /></div>
        <div><label class="labelFiltro">Nome</label>
		<input style="width:150px;" type=text" id="AddNome" /></div>
        <div><label class="labelFiltro">Cognome</label>
		<input style="width:150px;" type=text" id="AddCognome" /></div>		
		<br>
		<div>
		<button id="addflusso" onclick="AddNewUserFlusso('<?php echo $IdWorkFlow; ?>',
			    '<?php echo $WorkFlow; ?>',
			    '<?php echo $IdGruppo; ?>', 
				'<?php echo $Gruppo; ?>', 
			    'ANU');return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-plus-circle"> </i> Nuovo Utente</button>		
		
	<div>
<script>

	$(".selectSearch").select2();
	$('.selectNoSearch').select2({minimumResultsForSearch: -1});
$.ui.dialog.prototype._allowInteraction = function (e) {
    return true;
}; 
</script>