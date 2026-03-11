<form id="FormTabDdl" method="POST">
<input type="hidden" name="FOGLIO" id="FOGLIO" value="<?php echo $FOGLIO; ?>" />
	<input type="hidden" name="RIGA_INZ" id="RIGA_INZ" value="<?php echo $RIGA_INZ; ?>" />
	<input type="hidden" name="TAB_TARGET" id="TAB_TARGET" value="<?php echo $TAB_TARGET; ?>" />
	<input type="hidden" name="ID_LOAD_ANAG" id="ID_LOAD_ANAG" value="<?php echo $ID_LOAD_ANAG; ?>" />

	<aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note">
		<p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>Tools | Load Config</p>
		<div class="formelement">
			<label class="labelFiltro">SCHEMA</label>
			<select class="selectpicker" data-live-search="true" class="FieldMand selectSearch" name="Sel_Schema" id="Sel_Schema" onchange="selSchema();return false;">
				<option value="">-- SELEZIONA SCHEMA --</option>
				<?php
				foreach ($datiSchema as $row) {
					$Schema = trim($row['TABSCHEMA']);
				?><option value="<?php echo $Schema; ?>" <?php if ($Sel_Schema == $Schema) { ?>selected<?php } ?>><?php echo $Schema; ?></option><?php
																																				}
																																					?>
			</select>
		</div>

		<div class="formelement">
			<label class="labelFiltro">TABLE</label>
			<select class="selectpicker" data-live-search="true" class="FieldMand selectSearch" name="Sel_Object" id="Sel_Object" onchange="SelTabella()">
				<option value="">-- SELEZIONA TABELLA --</option>
				<?php
				foreach ($datiTable as $row) {
					$Name = trim($row['TABNAME']);
					$Type = $row['TYPE'];
				?><option value="<?php echo $Name; ?>" <?php if ($Sel_Table == $Name) { ?>selected<?php } ?>><?php echo $Name ?></option><?php } 	?>
			</select>
		</div>

		<div class="formelement" id="divConfig">
			<label id="labelConfig" class="labelFiltro">CONFIG</label>
			<select class="selectpicker" data-live-search="true" class="FieldMand selectSearch" name="selectConfig" id="selectConfig" onchange="verificaConfigurazione()">
				<option value="">-- SELEZIONA CONFIGURAZIONE --</option>
				<?php
				foreach ($datiConfigurazione as $row) {
					$val = trim($row['ID_LOAD_ANAG']);
					$text = $row['FLUSSO'];
				?><option value="<?php echo $val; ?>" <?php if ($ID_LOAD_ANAG == $val) { ?>selected<?php } ?>><?php echo $text; ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="formelement LabeladdConfig">
			<button id="modConfig" onclick="ModificaConfigurazione();return false;" class="btn"><i class="fa-solid fa-pen-to-square"></i> Modifica</button>
			<?php if($ID_LOAD_ANAG){?>
			<button id="addCheck" onclick="check(<?php echo $ID_LOAD_ANAG; ?>);return false;" class="btn"><i class="fa-solid fa-plus-circle"> </i> Check </button>
			<?php } ?>
		</div>


		<div style="clear: both;" class="formelement " id="labelFile">
			<div class="input_container">
				<input style="display: inline;" type="file" name="FileInput" id="FileInput" onchange="OpenFoglio();return false;" />
			</div>
		</div>
		<div class="formelement" id="divFoglio">
			<label id="labelFoglio" class="labelFiltro">Foglio</label>
			<select data-done-button="true" title="-- SELEZIONA FOGLIO --" multiple class="selectpicker" data-live-search="true" data-icon-base="fas" data-tick-icon="fa-solid fa-check" class="selectpicker" data-live-search="true" class="FieldMand selectSearch" name="selectFOGLIO" id="selectFOGLIO">
				
			</select>
		</div>
		<div class="formelement" id="label_RIGA_INZ">
			<label class="labelFiltro">Riga Inzio</label>
			<input style="display: inline;" type="index" name="selectRIGA_INZ" id="selectRIGA_INZ" />
		</div>
		<div class="formelement LabeladdConfig">
			<button id="addConfig" onclick="CreaConfigurazione();return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-plus-circle"> </i> Crea Configurazione</button>
		</div>
	
	
	<?php if (!empty($DatiFile) && !empty($datiTableOutput)) { ?>	
		<div id="actionSave" class="actionSave">
			<div class="formelement">
				<label class="labelFiltro">Nome Flusso</label>
				<input type="text" name="FLUSSO" id="FLUSSO" value="<?php echo $FLUSSO; ?>" />
			</div>

			<div class="formelement">
				<label class="labelFiltro">Descrizione</label>
				<input type="text" name="DESCR" id="DESCR" value="<?php echo $DESCR ?>" />
			</div>
			<br/>
			<div class="formelement">
				<label class="labelFiltro">Opt Del</label>				
				<textarea id="OPT_DEL" name="OPT_DEL"  rows="2" cols="50"><?php echo $OPT_DEL; ?></textarea>
			</div>

			<div class="formelement">
				<label class="labelFiltro">MANIPOL</label>
				<input type="text" name="MANIPOL" id="MANIPOL" value="<?php echo $MANIPOL ?>" />
			</div>
			<div class="formelement LabeladdConfig">
				<button onclick="salvaConfigurazione();return false;" class="btn" style="display: inline-block; margin-bottom: 10px;"><i class="fa-solid fa-floppy-disk"></i> </i> Salva </button>
				</div>
			
		</div>
		<?php } ?>
		</aside>
		<div id="risultato"	>
		<?php if (!empty($DatiFile) && !empty($datiTableOutput)) { ?>	
		<div style="clear: both;" class="FileInput">


			<table id="idTabellaInput" class="display dataTable">
				<caption><?php echo $fileName ?> <strong>Foglio:</strong> <?php echo $FOGLIO ?> </caption>
				<thead class="headerStyle">
					<tr>
						<th>Nome Campo</th>
					</tr>
				</thead>
				<tbody>
					<?php

					foreach ($DatiFile as $ki => $row) {
						//console.log($row);
						$NAME = $row['COLONNA'];
						$TYPE = $row['TIPO'];
					?>
						<tr>
							<td><input readonly type="text" name="FileInput_NomeCampo[]" id="FileInput_NomeCampo_<?php echo $ki; ?>" value="<?php echo $NAME; ?>" /></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>


		</div>
		<div class="Target">

			<table id="idTabellaTarget" class="display dataTable">
				<caption><strong>Target:</strong> <?php echo $TAB_TARGET; ?> </caption>
				<thead class="headerStyle">
					<tr>
						<th style="width: 15%;">Nome Campo</th>
						<th style="width: 15%;">Type</th>
						<th style="width: 60%;">Formula</th>
					</tr>
				</thead>
				<tbody>
					<?php

					foreach ($datiTableOutput as $k =>  $row) {
						//console.log($row);

						$NAME = $row['COLONNA'];
						$TYPE = $row['TIPO'];
						$FORMULA = $row['FORMULA'];
						if ($NAME != 'ID_PROCESS') { ?>
							<tr>
								<td><input readonly type="text" name="Target_NAME[]" id="Target_NAME_<?php echo $k; ?>" value="<?php echo $NAME; ?>" /></td>
								<td><input readonly type="text" name="Target_TYPE[]" id="Target_TYPE_<?php echo $k; ?>" value="<?php echo $TYPE; ?>" /></td>
								<td><textarea class="formula" id="Target_FORMULA_<?php echo $k; ?>" name="Target_FORMULA[]" rows="1"><?php echo $FORMULA; ?></textarea>
								</td>

							</tr>
					<?php
						}
					}

					?>
				</tbody>
			</table>





		</div>
		<div style="clear: both; margin:10px;">
			<button onclick="salvaConfigurazione();return false;" class="btn" style="display: inline-block; margin-bottom: 10px;"><i class="fa-solid fa-floppy-disk"></i> Salva</button>

		</div>
		</div>
		<br />
		<br />
		<br />
	<?php } ?>

</form>