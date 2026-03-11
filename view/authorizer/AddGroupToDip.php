<div>	
		<div class="TitCls" ><B>Assegna gruppo alla Dipendenza <?php echo $Dipendenza; ?> del flusso "<?php echo $Flusso; ?>"</B></div>
		<BR><BR>
		<div>
		  Gruppo:
		  <select onchange="AddGroupIn('<?php echo $IdWorkFlow.$IdFlusso.$IdDip; ?>')" id="AddGroupIn<?php echo $IdWorkFlow.$IdFlusso.$IdDip; ?>" >
			  <option value="" >Select..</option>
			  <?php 
			 
			  foreach ($DatiGruppiDip as $rwSelGrp) {
				?><option value="<?php echo $rwSelGrp['ID_GRUPPO']; ?>"><?php echo $rwSelGrp['GRUPPO']; ?></option><?php
			  }
			  ?>
		  </select>
		</div>
	 <button onclick="addAuthDipFlu('<?php echo $IdWorkFlow; ?>',
			    '<?php echo $WorkFlow; ?>',
			    '<?php echo $IdFlusso; ?>',
			    '<?php echo $Tipo; ?>',
			    '<?php echo $IdDip; ?>',
			    '<?php echo $Dipendenza; ?>',
			    '<?php echo $Flusso; ?>', 
				'ADG')" id="AddGrp<?php echo $IdWorkFlow.$IdFlusso.$IdDip; ?>" hidden >Add Group</button>
	</div>
	
			  
			   
			     


