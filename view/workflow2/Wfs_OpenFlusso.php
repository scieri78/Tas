	<FORM id="FormDip" method="POST">
		<input type="hidden" id="TopScroll" name="TopScroll" value="<?php echo $TopScroll; ?>" />
		<input type="hidden" id="BodyHeight" name="BodyHeight" value="<?php echo $BodyHeight; ?>" />
		<input type="hidden" Name="IdWorkFlow" id="IdWorkFlow" value="<?php echo $IdWorkFlow; ?>">
		<input type="hidden" id="IdTeam" name="IdTeam" value="<?php echo $IdTeam; ?>" class="FieldMand">
		<input type="hidden" Name="Azione" id="AzioneD" value="">
		<input type="hidden" Name="IdLegame" id="IdLegame" value="">
		<input type="hidden" Name="IdFlu" id="IdFlu" value="<?php echo $IdFlu; ?>">
		<input type="hidden" Name="ndialog" id="ndialog" value="<?php echo $ndialog; ?>">
		<div id="azioni">
			<button id="refresh" onclick="refreshOpen(<?php echo $ndialog; ?>);return false;" class="btn AggiungiFlusso"><i class="fa-solid fa-refresh"> </i> Refresh</button>
			<button id="editworkflow" onclick="ModDip('<?php echo $IdWorkFlow; ?>','<?php echo $Flusso; ?>','<?php echo $IdFlu; ?>','<?php echo $IdFlu; ?>','FL','',<?php echo $ndialog; ?>,'');return false;" class="btn AggiungiFlusso">
				<i class="fa-solid fa-pencil-square-o" aria-hidden="true"> </i> Modifica Flusso</button>

			<button onclick="aggiungiDipendenza(<?php echo $IdWorkFlow; ?>,<?php echo $IdFlu; ?>,'',<?php echo $ndialog; ?>);return false;" id="AggDipIN<?php echo $IdFlu; ?>" class="AggiungiFlusso btn"><i class="fa-solid fa-plus-circle"> </i> Dipendenza</button>


			<button <?php if (count($datiFlusso) > 0) { ?> style="display: none;" <?php } ?> id="RemoveFlu" onclick="DeleteFlu();return false;" class="btn AggiungiFlusso">
				<i class="fa-solid fa-trash-can" aria-hidden="true"> </i> Flusso</button>
		</div>
		</div>

		<table id='idTabedlla' class="display dataTable">
			<thead class="headerStyle">
				<tr>
					<th>TIPO</th>
					<th>PRIORITA'</th>
					<th>DIPENDENZA</th>
					<th>DETTAGLIO</th>
					<th>NASCONDI</th>
					<th>MODIFICA</th>
					<th>ELIMINA</th>


				</tr>
			</thead>
			<tbody>
				<?php				

				foreach ($datiFlusso as $rowLG) {
					$IdLegame = $rowLG['ID_LEGAME'];
					$Prio = $rowLG['PRIORITA'];
					$Tipo = $rowLG['TIPO'];
					$Dipendenza = $rowLG['DIPENDENZA'];
					$Parametri = $rowLG['PARAMETRI'];
					$IdDip = $rowLG['ID_DIP'];
					$IdDesc = $rowLG['DESCR'];
					$Dett = $rowLG['DETT'];
					$TLink = $rowLG['TLINK'];
					$TROnly = $rowLG['READONLY'];
					$TExternal = $rowLG['EXTERNAL'];
					$TSalta = $rowLG['SALTA'];
					$TInsert = $rowLG['TMS_INSERT'];
					$TInzValid = $rowLG['INZ_VALID'];
					$TFinValid = $rowLG['FIN_VALID'];

					switch ($Tipo) {
						case "F":
							$ImgTipo = "Flusso";
							$ShowDett = false;
							break;
						case "E":
							if ($TSalta == "N") {
								$ImgTipo = "Elaborazione";
							} else {
								$ImgTipo = "SaltaElab";
							}
							$ShowDett = true;
							break;
						case "V":
							$ImgTipo = "Valida";
							if ("$TExternal" == "Y") {
								$ImgTipo = "Elaborazione";
							}
							$ShowDett = true;
							break;
						case "C":
							$ImgTipo = "Carica";
							$ShowDett = true;
							break;
						case "L":
							$ImgTipo = "Link";
							if ($TLink == "I") {
								$ImgTipo = "Setting";
							}
							$ShowDett = true;
							break;
					}

					$BckGr = "";
					$hiddenDip = "";
					if ($TFinValid < date("Ym")) {
						$BckGr = "hiddenDip";
						$hiddenDip = "hiddenDip";
						$Dett .= " Fine Valid: $TFinValid";
					}

					if ($TInzValid > date("Ym")) {
						$hiddenDip = "FurureDip";
						$BckGr = "style=\"background:yellow;\"";
						$Dett .= " Inz Valid: $TInzValid";
					}
				?>
					<tr>

						<td style="width:35px;text-align: right;" class="<?php echo $hiddenDip; ?>">
							<?php if ("$TExternal" == "Y") { ?> <div style="float:left;">EXT.</div><?php } ?><img class="ImgIco" src="./images/<?php echo $ImgTipo; ?>.png">
						</td>

						<td class="thLiv <?php echo $hiddenDip; ?>">
							<div class="Liv"><?php echo $Prio;  ?></div>
						</td>

						<td class="thDipendenza <?php echo $hiddenDip; ?>">
							<div class="Dipendenza"><?php
													if (("$Dett" == "" and "$Tipo" == "E") || (substr($Dett, 0, 8) == "WFS_TEST" and  "$Tipo" == "C")) { ?>

									<i class="fa-regular fa-flask"></i>
								<?php }
													echo "$Dipendenza ";
													if ("$IdDesc" != "") {
														echo " ($IdDesc)";
													}
								?>
							</div>
						</td>
						<td class="<?php echo $hiddenDip; ?> dettDip">
							<?php if ($TROnly == "S") { ?><i class="fa-solid fa-flag"></i><?php } ?><?php echo $Dett; ?> 
							<?php if($Parametri):?> - <?php echo $Parametri; endif;?></td>

						<td class="thModImgIco <?php echo $hiddenDip; ?>">
							<?php if ($BckGr == "") { ?>
								<div id="DisDett<?php echo $IdLegame; ?>" class="Plst" onclick="DisDip(<?php echo $IdLegame; ?>,<?php echo $ndialog; ?>)">
									<i class="fa-solid fa-eye-slash"></i>
								</div>
							<?php } ?>
						</td>
						<td class="thModImgIco <?php echo $hiddenDip; ?>">
							<?php if (1 || (
								$TServer != "PROD USER"
								and ($TInsert >= date("Ym") and $BckGr != "")
								or  ($TInsert <= date("Ym") and $BckGr == "")
							)) { ?>
								<div id="ModDett<?php echo $IdLegame; ?>" class="Plst" onclick="ModDip('<?php echo $IdWorkFlow; ?>','<?php echo $Dipendenza; ?>','<?php echo $IdFlu; ?>','<?php echo $IdDip; ?>','<?php echo $Tipo; ?>','<?php echo $TLink; ?>',<?php echo $ndialog; ?>,<?php echo $IdLegame; ?>)">
									<i class="fa-solid fa-pen-to-square"></i>
								</div>
							<?php } ?>
						</td>
						<td class="thModImgIco <?php echo $hiddenDip; ?>">
							<?php if ($TServer != "PROD USER") { ?>
								<div id="DelDett<?php echo $IdLegame; ?>" class="Plst" onclick="DelDip(<?php echo $IdDip; ?>,'<?php echo $Tipo; ?>',<?php echo $IdLegame; ?>,<?php echo $ndialog; ?>);return false;">
									<i class="fa-solid fa-trash-can"></i>
								</div>
							<?php } ?>
						</td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</form>


	<script>
		$(document).ready(function() {
			$('#Waiting').hide();


			$(".selectSearch").select2();
			$('.selectNoSearch').select2({
				minimumResultsForSearch: -1
			});

			
		});






		$('#idTabella').DataTable({
			"paging": false,
			"searching": false,

			language: {
				"url": "./JSON/italian.json"
			},
		
		});



		function PulChiudi() {
			$('#InsDip').empty().hide();
		};



	</script>