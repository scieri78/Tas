
    <form method="post" id=formChangeUser>
      
        <label>User:</label><br>
        <SELECT id="userchange" name="userchange" class="selectSearch" style="width: 300px;">
	<option value="" >Seleziona Utente</option>
	<?php
	$ListNm="";
	foreach ($DatiUser as $rwU) {      
	  $Kuk=$rwU['UK'];
	  $Nm=$rwU['USERNAME'];
	  $Nom=$rwU['NOMINATIVO'];
	  $ListNm=$ListNm.$Nm.',';
	  ?><option value="<?php echo $Nm; ?>" ><?php echo "$Nm - $Nom"; ?></<option><?php	  
	}
	$ListNm=substr($ListNm,0,-1);
	?></SELECT>
       <br> 
        
        <br>   
        <br>
        <button id="addworkflow" onclick="changeUser();return false;" class="btn"><i class="fa-solid fa-save"> </i> Salva</button>        
    </form>


    <script>

	$(".selectSearch").select2();
	$('.selectNoSearch').select2({minimumResultsForSearch: -1});
$.ui.dialog.prototype._allowInteraction = function (e) {
    return true;
}; 
</script>