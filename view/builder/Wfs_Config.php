<FORM id="FormMain" method="POST">
	<input type="hidden" id="TopScroll" name="TopScroll" value="<?php echo $TopScroll; ?>" />
	<input type="hidden" id="BodyHeight" name="BodyHeight" value="<?php echo $BodyHeight; ?>" />
	<input type="hidden" Name="IdWorkFlow" id="IdWorkFlow" value="<?php echo $IdWorkFlow; ?>">
	<input type="hidden" id="IdTeam" name="IdTeam" value="<?php echo $IdTeam; ?>" class="FieldMand">
	<input type="hidden" id="TabFreq" name="TabFreq" value="<?php echo $TabFreq; ?>" class="FieldMand">
	<input type="hidden" Name="Azione" id="Azione" value="">
	<aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note">
		<p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>WFS Admin | Builder </p>

		<table id="TabFlussi">
			<tr>
				<th>
					<div id="divSelIdTeam" class="divSelIdTeam">
						<button id="refresh" onclick="refreshHome();return false;" class="btn"><i class="fa-solid fa-refresh"> </i> Refresh</button>
						<select class="selectpicker" data-live-search="true" onchange="OpenTeam(this.value)" id="selIdTeam" name="selIdTeam">
							<option value="" <?php if ($IdTeam == "") { ?>selected<?php } ?>>Seleziona Team</option>
							<?php
							foreach ($datiTeam as $rowLA) {
								$TabIdTeam = $rowLA['ID_TEAM'];
								$TabTeam = $rowLA['TEAM'];
								$SEL = ($IdTeam  == $TabIdTeam) ? 'selected' : '';?>
								<option <?php echo $SEL;?> value="<?php echo $TabIdTeam; ?>"><?php echo $TabTeam; ?></option>
								<?php } 
								if($IdTeam != ""){ ?>
									<script>
									setTimeout(function () {
									OpenTeam('<?php echo $IdTeam; ?>');
									}, 500);
										setTimeout(function () {
											$('#selIdWorkFlow').val(<?php echo $selIdWorkFlow; ?>).selectpicker('refresh');
									OpenWorkFlow(<?php echo $selIdWorkFlow; ?>);
									}, 1000);						
									
									</script>
								<?php }	?>
						</select>
					</div>
					<div id="divSelIdWorkFlow" class="divSelIdTeam">
						<select class="selectpicker" data-live-search="true" onchange="OpenWorkFlow(this.value)" id="selIdWorkFlow" name="selIdWorkFlow" class="FieldMand selectSearch" style="width:180px;height:30px;">
							<option value="" <?php if ($IdTeam == "") { ?>selected<?php } ?>><i class='fa fa-glasses'></i>Seleziona WorkFlow</option>
								
						</select>
					</div>
					<div id="divSelectFlusso" class="divSelIdTeam">
						<select class="selectpicker" data-live-search="true" onchange="OpenWorkFlowFlussi($('#IdWorkFlow').val())" id="selectFlusso" name="selectFlusso" class="selectSearch  FieldMand" style="width:190px;height:30px;"><?php $selected = ($selectFlusso == "") ? "selected" : false; ?>
							<option value="" <?php echo $selected; ?>>Filtra Flussi/Tutti</option>
						</select>
					</div>
				</th>
			<tr>
				<th id="BuilderFilter">
					
					<?php if ($TServer != "PROD USER") {  ?>
						<button id="addworkflow" onclick="addWorkFlow();return false;" class="btn"><i class="fa-solid fa-plus-circle"> </i> WORKFLOW</button>
						<button id="removeWorkFlow" onclick="DeleteWorkFlow();return false;" class="btn"><i class="fa-solid fa-trash-can"> </i> WORKFLOW</button>

					<?php }  ?>
					<button id="editworkflow" onclick="modWorkFlow();return false;" class="btn">
						<i class="fa-solid fa-pencil-square-o" aria-hidden="true"> </i> Modifica WF</button>
					<?php if ($TServer != "PROD USER") { ?>
						<button id="addflusso" onclick="ShowAggiungiFlusso();return false;" class="btn"><i class="fa-solid fa-plus-circle"> </i> Flusso</button>
						<button id="selrilascio" onclick="selezionaRilascio();return false;" class="btn"><i class="fa-regular fa-square-check"></i><span id="selectAll"> Seleziona Tutto<span></button>
						<button id="crearilascio" onclick="CreaRilascio();return false;" class="btn"><i class="fa-solid fa-rocket"> </i> Crea Rilascio</button>
					<?php }  ?>
				</th>
			</tr>
		</table>
	</aside>
	<div id="AreaPreFlussi">

	</div>
	<?php if ($TServer != "PROD USER") { ?>
		<div id="AggFlusso" hidden>
			<B>Nuovo Flusso</B>
			<BR>
			<label>Nome Flusso</label>
			<input type="text" id="NomeFlusso" Name="NomeFlusso" />
			<BR>
			<label>Descr. Flusso</label>
			<input type="text" id="DescFlusso" Name="DescFlusso" />
			<BR>
			<label>Comportamento con periodo Consolidato</label><BR>
			<select id="BlockConsFlu" Name="BlockCons">
				<option value="N"> Non far Nulla </option>
				<option value="Y"> Blocca con periodo Consolidato </option>
				<option value="S"> Abilita con periodo Consolidato </option>
			</select>
			<table>
				<tr>
					<td>
						<div class="button" id="AggiungiF">Aggiungi</div>
					</td>
					<td>
						<div class="button" id="ChiudiF">Close</div>
					</td>
				</tr>
			</table>
		</div>
	<?php } ?>
	<div id="InsDip"></div>
</FORM>

<script src="./view/builder/JS/Wfs_Config.js?p=<?php echo rand(1000, 9999); ?>"></script>