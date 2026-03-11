<?php
session_cache_limiter(‘private_no_expire’);
session_start() ;

include '../GESTIONE/connection.php';
include '../GESTIONE/SettaVar.php';
include '../GESTIONE/login.php';
include '../GESTIONE/sicurezza.php';

if ( "$find" == "1" )  {

    $IdWorkFlow=$_REQUEST['IdWorkFlow'];
	$WorkFlow=$_POST['WorkFlow'];
	
	?><div>	
		<div class="TitCls" ><B>Workflow <?php echo $WorkFlow; ?></B></div><BR>
		Insert Name New Group
		<div><input id="AddGrp" type="text" /></div>
		<button id="PlsAddGrp" >Add Group</button>
	</div>
	 
	<div><button id="ClDiv" class="CloseDivAdd" >Close</button></div>
	<script>
		$("#ClDiv").click(function(){
		  $('#ADDDiv').empty().hide();
		});		
		$("#AddGrp").keyup(function(){
		   $(this).val($(this).val().replace(/ /g,"_"));
		   $(this).val($(this).val().toUpperCase());
		});
        $("#PlsAddGrp").click(function(){
		  if ( $("#AddGrp").val() != '' ) {
			  $('#LoadDett<?php echo $IdWorkFlow; ?>').load('../PHP/Wfs_Gest_DettWfs.php',{
					   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
					   WorkFlow: '<?php echo $WorkFlow; ?>',
					   AddGrp: $('#AddGrp').val(),
					   Azione: 'AG'
			  });		  
              $('#ADDDiv').empty().hide();
		  }  
		});
		
    </script>
	
	<?php
}
db2_close($db2_conn_string);
?>
