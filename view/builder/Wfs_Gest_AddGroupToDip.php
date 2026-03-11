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
	$Tipo=$_POST['Tipo'];
	$IdDip=$_POST['IdDip'];
	$Dipendenza=$_POST['Dipendenza'];
	?><div>	
		<div class="TitCls" ><B>Assegna gruppo alla Dipendenza <?php echo $Dipendenza; ?> del flusso "<?php echo $Flusso; ?>"</B></div>
		<BR><BR>
		<div>
		  Gruppo:
		  <select id="AddGroupIn<?php echo $IdWorkFlow.$IdFlusso.$IdDip; ?>" >
			  <option value="" >Select..</option>
			  <?php
			  $SelGrp="SELECT ID_GRUPPO, GRUPPO FROM WFS.GRUPPI 
			  WHERE 1=1
			  AND ID_GRUPPO IN ( SELECT ID_GRUPPO FROM WFS.AUTORIZZAZIONI WHERE ID_WORKFLOW = $IdWorkFlow AND ID_FLUSSO = $IdFlusso )
			  AND ID_GRUPPO NOT IN ( SELECT ID_GRUPPO FROM WFS.AUTORIZZAZIONI_DIP WHERE ID_WORKFLOW = $IdWorkFlow AND ID_FLU = $IdFlusso AND TIPO = '$Tipo' AND  ID_DIP = $IdDip )
			  ORDER BY 2
			  ";
			  $rsSelGrp=$stmtTabRead = db2_prepare($conn, $SelGrp);
              $resultTabRead = db2_execute($rsSelGrp); 
			  while ($rwSelGrp = db2_fetch_assoc($rsSelGrp)) {
				?><option value="<?php echo $rwSelGrp['ID_GRUPPO']; ?>"><?php echo $rwSelGrp['GRUPPO']; ?></option><?php
			  }
			  ?>
		  </select>
		</div>
		<button id="AddGrp<?php echo $IdWorkFlow.$IdFlusso.$IdDip; ?>" hidden >Add Group</button>
	</div>
	 
	<button id="CDAuth<?php echo $IdWorkFlow.$IdFlusso.$IdDip; ?>" class="CloseDivAdd" style="font-align:center;">Close</button>
	<script>
		$("#CDAuth<?php echo $IdWorkFlow.$IdFlusso.$IdDip; ?>").click(function(){
			$('#ADDDiv').empty();
			$('#ADDDiv').hide();
		});		
		$("#AddGroupIn<?php echo $IdWorkFlow.$IdFlusso.$IdDip; ?>").change(function(){
		   if ( $(this).val() != '' ){
			   $("#AddGrp<?php echo $IdWorkFlow.$IdFlusso.$IdDip; ?>").show();
		   } else {
			   $("#AddGrp<?php echo $IdWorkFlow.$IdFlusso.$IdDip; ?>").hide();
		   }
		});
		$("#AddGrp<?php echo $IdWorkFlow.$IdFlusso.$IdDip; ?>").click(function(){
		  $('#ShowAuthDip_<?php echo $IdWorkFlow.$IdFlusso.$IdDip; ?>').empty().load('../PHP/Wfs_Gest_LoadAuthDip.php', {
			   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
			   WorkFlow: '<?php echo $WorkFlow; ?>',
			   IdFlusso: '<?php echo $IdFlusso; ?>',
			   TipoDip: '<?php echo $Tipo; ?>',
			   IdDip: '<?php echo $IdDip; ?>',
			   Dipendenza: '<?php echo $Dipendenza; ?>',
			   Flusso: '<?php echo $Flusso; ?>',
		       IdGroup: $('#AddGroupIn<?php echo $IdWorkFlow.$IdFlusso.$IdDip; ?>').val(),
		       AzioneDipAut: 'ADG'
		  });  		  
          $('#ADDDiv').empty().hide();
		});

    </script>
	
	<?php
}
?>

