<script>
		//loadFlussi('<?php echo $IdWorkFlow; ?>','<?php echo $WorkFlow; ?>')
		/* $('#LoadFls<?php echo $IdWorkFlow; ?>').load('./PHP/Wfs_Gest_LoadFlussi.php',{
				   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
				   WorkFlow: '<?php echo $WorkFlow; ?>'
			});*/
</script>
		<?php
	 
	$CntFL=count($DatiAutorizzazioni);
	foreach ($DatiAutorizzazioni as $rwFL) {      
	  $IdAut=$rwFL['ID_AUT'];
	  $Gruppo=$rwFL['GRUPPO'];
	  ?>
	  <div onclick="deleteFlusso('<?php echo $IdWorkFlow; ?>',
								   '<?php echo $WorkFlow; ?>',
								   '<?php echo $IdFlusso; ?>',
								   '<?php echo $Flusso; ?>',
								   '<?php echo $IdAut; ?>')" id="PulRemGroup<?php echo $IdAut; ?>" class="Plst Mattone" >
		 
		 <i class="fa-solid fa-trash-can"></i>
		 <?php echo $Gruppo; ?>
	  </div>
	  
	  <?php } ?>

