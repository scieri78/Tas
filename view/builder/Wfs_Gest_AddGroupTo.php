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
	$IdFlusso=$_POST['IdFlusso'];
	$Flusso=$_POST['Flusso'];
	
	?><div>	
		<div class="TitCls" ><B>Assegna gruppo al flusso "<?php echo $Flusso; ?>"</B></div>
		<BR><BR>
		<div>
		  Gruppo:
		  <select id="AddGroupIn<?php echo $IdWorkFlow.$IdFlusso; ?>" >
			  <option value="" >Select..</option>
			  <?php
			  $SelGrp="SELECT ID_GRUPPO, GRUPPO FROM WFS.GRUPPI WHERE GRUPPO != 'ADMIN' AND ID_WORKFLOW = $IdWorkFlow AND ID_GRUPPO NOT IN ( 
                 SELECT ID_GRUPPO FROM WFS.AUTORIZZAZIONI WHERE ID_WORKFLOW = $IdWorkFlow AND ID_FLUSSO = $IdFlusso
			  )";
			  $rsSelGrp=$stmtTabRead = db2_prepare($conn, $SelGrp);
              $resultTabRead = db2_execute($rsSelGrp); 
			  while ($rwSelGrp = db2_fetch_assoc($rsSelGrp)) {
				?><option value="<?php echo $rwSelGrp['ID_GRUPPO']; ?>"><?php echo $rwSelGrp['GRUPPO']; ?></option><?php
			  }
			  ?>
		  </select>
		</div>
		<button id="AddGrp<?php echo $IdWorkFlow.$IdFlusso; ?>" hidden >Add Group</button>
	</div>
	 
	<div><button id="ClDiv<?php echo $IdWorkFlow.$IdFlusso; ?>" class="CloseDivAdd" >Close</button></div>
	<script>
		$("#ClDiv<?php echo $IdWorkFlow.$IdFlusso; ?>").click(function(){
		  $('#ADDDiv').empty().hide();
		});		
		$("#AddGroupIn<?php echo $IdWorkFlow.$IdFlusso; ?>").change(function(){
		   if ( $(this).val() != '' ){
			   $("#AddGrp<?php echo $IdWorkFlow.$IdFlusso; ?>").show();
		   } else {
			   $("#AddGrp<?php echo $IdWorkFlow.$IdFlusso; ?>").hide();
		   }
		});
		$("#AddGrp<?php echo $IdWorkFlow.$IdFlusso; ?>").click(function(){
		    $('#ShowFlu_<?php echo $IdWorkFlow.$IdFlusso; ?>').load('../PHP/Wfs_Gest_LoadFlusso.php',{
				   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
				   WorkFlow: '<?php echo $WorkFlow; ?>',
				   IdFlusso: '<?php echo $IdFlusso; ?>',
				   Flusso: '<?php echo $Flusso; ?>',
				   IdGroup: $('#AddGroupIn<?php echo $IdWorkFlow.$IdFlusso; ?>').val(),
				   AzioneAut: 'ADG'
			  });
              $('#ADDDiv').empty().hide();
		});

    </script>
	
	<?php
}
?>

