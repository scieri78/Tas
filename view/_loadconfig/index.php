<script type="text/javascript" src="./view/loadconfig/JS/index.js?p=<?php echo rand(1000, 9999); ?>"></script>

<form id="FormTabDdl" method="POST">
	<BR>
	<aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note">
		<p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>Tools | Load Config</p>
		<label class="labelFiltro">SCHEMA</label>
		<select style="width: 234px;margin: 0 12px;" class="selectSearch" name="Sel_Schema" id="Sel_Schema" onchange="$('#Sel_Object').val('');$('#FormTabDdl').submit();">
			<option value="">..</option>
			<?php
			foreach ($datiSchema as $row) {
				$Schema = trim($row['TABSCHEMA']);
			?><option value="<?php echo $Schema; ?>" <?php if ($Sel_Schema == $Schema) { ?>selected<?php } ?>><?php echo $Schema; ?></option><?php
																																						}
																																							?>
		</select>

		<label class="labelFiltro">TABLE</label>
		<select style="width: 234px;margin: 0 12px;" class="selectSearch" name="Sel_Object" id="Sel_Object" onchange="$('#FormTabDdl').submit();">
			<option value="">..</option>
			<?php
			foreach ($datiTable as $row) {
				$Name = trim($row['TABNAME']);
				$Type = $row['TYPE'];
			?><option value="<?php echo $Name; ?>" <?php if ($Sel_Table == $Name) { ?>selected<?php } ?>><?php echo $Name ?></option><?php
																																								}
																																									?>
		</select>
		</aside>
		<BR>

		<?php
		if ($Sel_Table != "" && $Sel_Schema != "") {
		?>
			<table id="idTabella" class="display dataTable" style="width:50%">
				<thead class="headerStyle">
					<tr>
					<th style="width: 33%;">Nome Campo</th>
					<th style="width: 33%;">Type</th>
					<th style="width: 33%;">Funzione</th>
					</tr>
				</thead>
				<tbody>
					<?php

					foreach ($datiTableOutput as $row) {
						//console.log($row);
						$NomeCampo = $row['NAME'];
						$Type = $row['TYPE'];
						

					?>
							<tr>
								<td ><?php echo $NomeCampo; ?></td>
								<td ><?php echo $Type; ?></td>
								<td><input type="text" name="funzione"/> </td>

							</tr>
					<?php
						}
					}
					?>
				</tbody>
			</table>


</form>