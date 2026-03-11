<script type="text/javascript" src="./view/ddltabcreator/JS/index.js?p=<?php echo rand(1000,9999);?>"></script>

<form id="FormTabDdl" method="POST"  >
	<input type="hidden" name="sito" value="<?= $_GET['sito']?$_GET['sito']:$_POST['sito'] ?>"/>
	<BR>
	<aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note"> <p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>Tools | DDL Table </p> 
	<button onclick="changeForm();return false;" id="refresh" class="btn"><i class="fa-solid fa-refresh"> </i> Refresh</button>		  
	<label class="labelFiltro">SCHEMA</label>
					<select style="width: 234px;margin: 0 12px;" class="selectSearch" name="Sel_Schema" id="Sel_Schema" onchange="$('#Sel_Object').val('');changeForm()">
						<option value="" >..</option>
						<?php 
						foreach ($datiSchema as $row) {
						  $Schema=$row['TABSCHEMA'];
						  ?><option value="<?php echo $Schema ?>" <?php if ( $Sel_Schema == "$Schema"){ ?>selected<?php } ?> ><?php echo $Schema; ?></option><?php
						}
						?>
					</select>
				
			<label class="labelFiltro">TABLE</label>
					<select style="width: 234px;margin: 0 12px;" class="selectSearch" name="Sel_Object" id="Sel_Object"  onchange="changeForm()">
						<option value="" >..</option>
						<?php
							foreach ($datiTable as $row) {
							  $Name=$row['TABNAME'];
							  $Type=$row['TYPE'];
							  ?><option value="<?php echo $Name.'|'.$Type ?>" <?php if ( $Sel_Table == $Name ){ ?>selected<?php } ?> ><?php echo $Name ?></option><?php
							}
						?>
					</select>
				
					<?php
						if ( $Sel_Table != "" && $Sel_Schema != "" ){
							?>
								<button onclick="window.open('./DDL/<?php echo $filename; ?>');return false;" id="removeWorkFlow" class="btn" style="display: inline-block;"><i class="fa-solid fa-download"> </i> Download File</button></a>
						<button onclick="copy();return false;" id="removeWorkFlow" class="btn" style="display: inline-block;"><i class="fa-solid fa-copy"> </i> Copia Testo</button></a>
					
							<?php
						}
					?>
				
	</aside>
	<BR>
	<?php
	if ( $Sel_Table != "" && $Sel_Schema != "" ){
		

		?>
		<textarea class="Pkg" readonly ><?php echo $TestoFile; ?></textarea>
		<?php
	}

	?>
</form>
	



