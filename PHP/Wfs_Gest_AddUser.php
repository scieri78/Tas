<?php

include '../GESTIONE/connection.php';
include '../GESTIONE/SettaVar.php';
include '../GESTIONE/login.php';
include '../GESTIONE/sicurezza.php';

if ( "$find" == "1" )  {

    $IdWorkFlow=$_REQUEST['IdWorkFlow'];
	$WorkFlow=$_POST['WorkFlow'];
    $IdGruppo=$_POST['IdGruppo'];
	$Gruppo=$_POST['Gruppo'];
    
	$ListU="SELECT UK,USERNAME,NOMINATIVO from WEB.TAS_UTENTI ORDER BY NOMINATIVO";
	$rsU=db2_prepare($conn, $ListU);
    $resultTabRead = db2_execute($rsU); 
	$CntU=db2_num_rows($rsU);
	?>
	<div class="TitAddUser" ><B><?php echo $WorkFlow.' - '.$Gruppo; ?><B></div>
	<label>Select utente già censito</label>
	<div>
	<SELECT id="SelUser" >
	<option value="" >Select..</option>
	<?php
	$ListNm="";
	while ($rwU = db2_fetch_assoc($rsU)) {      
	  $Kuk=$rwU['UK'];
	  $Nm=$rwU['USERNAME'];
	  $Nom=$rwU['NOMINATIVO'];
	  $ListNm=$ListNm.$Nm.',';
	  ?><option value="<?php echo $Kuk; ?>" ><?php echo "$Nm - $Nom"; ?></<option><?php	  
	}
	$ListNm=substr($ListNm,0,-1);
	?></SELECT>
	</div>
	<button id="PlsAddUserExits" >Aggiungi Utente</button>
	<br><br>
	<div>
		<label>Inserisci Nuovo Utente</label>
		<div>Username</div>
		<div><input style="width:150px%;"  type=text" id="AddUserName" /></div>
        <div>Nome</div>
		<div><input style="width:150px;" type=text" id="AddNome" /></div>
        <div>Cognome</div>
		<div><input style="width:150px;" type=text" id="AddCognome" /></div>		
		<div><button id="PlsAddNewUser" >Aggiungi Nuovo Utente</button></div>
	<div>
	<?php
}
?>
<div><button id="ClDiv" class="CloseDivAdd" >Close</button></div>
<script>
	$("#ClDiv").click(function(){
	  $('#ADDDiv').empty().hide();
	});		
	$("#AddUsr").keyup(function(){
	   $(this).val($(this).val().replace(/ /g,"_"));
	   $(this).val($(this).val().toUpperCase());
	});
    $('#PlsAddUserExits').click(function(){
	  $('#US_<?php echo $IdWorkFlow.'_'.$IdGruppo; ?>').empty().load('../PHP/Wfs_Gest_LoadUser.php',{
		   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
		   WorkFlow: '<?php echo $WorkFlow; ?>',
		   IdGruppo: '<?php echo $IdGruppo; ?>',
		   IdUsr: $('#SelUser').val(),
		   Azione: 'AU'
	  });
	  $('#ADDDiv').empty().hide();
	});
	
    $('#PlsAddNewUser').click(function(){
      var vUsername = $('#AddUserName').val();
	  var vNome = $('#AddNome').val();
	  var vCognome = $('#AddCognome').val();
	  if  ( vUsername == '' || vNome == '' || vCognome == '' ){ 
	     alert('There are empty input!');
	  } else {
		  $('#US_<?php echo $IdWorkFlow.'_'.$IdGruppo; ?>').empty().load('../PHP/Wfs_Gest_LoadUser.php',{
			   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
			   WorkFlow: '<?php echo $WorkFlow; ?>',
			   IdGruppo: '<?php echo $IdGruppo; ?>',			   
			   AddUserName: vUsername,
			   AddNome: vNome,
			   AddCognome: vCognome,
			   Azione: 'ANU'
		  });
		  $('#ADDDiv').empty().hide();
	  }
	});	
</script>				  
