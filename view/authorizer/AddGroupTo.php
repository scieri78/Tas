<div>	
		<div class="TitCls" ><B>Assegna gruppo al flusso "<?php echo $Flusso; ?>"</B></div>
		<BR><BR>
		<div>
		  Gruppo:
		  <select id="AddGroupIn<?php echo $IdWorkFlow.$IdFlusso; ?>" >
			  <option value="" >Select..</option>
			  <?php
			 
			  foreach ($DatiGruppi as $rwSelGrp) {
				?><option value="<?php echo $rwSelGrp['ID_GRUPPO']; ?>"><?php echo $rwSelGrp['GRUPPO']; ?></option><?php
			  }
			  ?>
		  </select>
		</div>
		<button onclick="addFlusso(
					'<?php echo $IdWorkFlow; ?>',
				    '<?php echo $WorkFlow; ?>',
				    '<?php echo $IdFlusso; ?>',
				    '<?php echo $Flusso; ?>',				   
				    'ADG')" id="AddGrp<?php echo $IdWorkFlow.$IdFlusso; ?>" hidden >Add Group</button>
	</div>

	<script>
			
		$("#AddGroupIn<?php echo $IdWorkFlow.$IdFlusso; ?>").change(function(){
		   if ( $(this).val() != '' ){
			   $("#AddGrp<?php echo $IdWorkFlow.$IdFlusso; ?>").show();
		   } else {
			   $("#AddGrp<?php echo $IdWorkFlow.$IdFlusso; ?>").hide();
		   }
		});
		

    </script>
	


