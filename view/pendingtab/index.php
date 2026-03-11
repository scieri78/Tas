
<form id="FormDdl" method="POST"  >
	<aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note"> <p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>Tools | Pending Tab</p> 	
		<label class="labelFiltro">SCHEMA</label>
		<select class="selectSearch" name="Sel_Schema" id="Sel_Schema" style="width: 234px;margin: 0 12px;" onchange="$('#Sel_Table').val('');$('#FormDdl').submit();">
			<option value="" >..</option>
			<?php 	
			foreach ($datiTabschema as $row) {
			  $Schema=$row['TABSCHEMA'];
			  ?><option value="<?php echo $Schema ?>" <?php if ( "$Sel_Schema" == "$Schema"){ ?>selected<?php } ?> ><?php echo $Schema; ?></option><?php
			}
			?>
		</select>
		<label class="labelFiltro">TABLE</label>
		
		<select class="selectSearch" style="width: 234px;margin: 0 12px;" name="Sel_Table" id="Sel_Table" >
			<option value="" >..</option>
			<?php
			if ( $Sel_Schema != "" ){
				
				foreach ($datiTables as $row) {
				  $Name=$row['TABNAME'];
				  ?><option value="<?php echo $Name ?>" <?php if ( $Sel_Table == $Name ){ ?>selected<?php } ?> ><?php echo $Name ?></option><?php
				}
			}
			?>
		</select>	
		<button id="removeWorkFlow" class="btn" style="display: inline-block;"><i class="fa-solid fa-trash"> </i> Rimuovi Pending</button>
	</aside>
</form>	

<script>
 $(".selectSearch").select2();
 $('.selectNoSearch').select2({minimumResultsForSearch: -1}); 
</script>
