<FORM id="FormAuthorizer" method="POST">
	<aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note">
		<p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>WFS Admin | Authorizer</p>
		<div class="asideContent">
			<input type="hidden" name="IdTeam" id="IdTeam" value="<?php echo $IdTeam; ?>" />
			<div id="divSelIdTeam" class="divSelIdTeam">
				<button id="refresh" onclick="refreshHome();return false;" class="btn"><i class="fa-solid fa-refresh"> </i> Refresh</button>
				<select class="FieldMand selectSearch" onchange="OpenTeam()" id="selIdTeam" name="selIdTeam">
					<option value="" <?php if ($IdTeam == "") { ?>selected<?php } ?>>Seleziona Team</option>
					<?php
					foreach ($DatiTeams as $rowLA) {
						$TabIdTeam = $rowLA['ID_TEAM'];
						$TabTeam = $rowLA['TEAM'];
						$sel = ($TabIdTeam == $IdTeam) ? "selected" : "";
					?><option <?php echo $sel; ?> value="<?php echo $TabIdTeam; ?>"><?php echo $TabTeam; ?></option>
					<?php } ?>
				</select>
			</div>
			<div id="divSelIdWorkFlow" class="divSelIdTeam">
				<select onchange="LoadWf()" id="selIdWorkFlow" name="selIdWorkFlow" class="FieldMand selectSearch">
					<option value="" <?php if ($IdTeam == "") { ?>selected<?php } ?>><i class='fa fa-glasses'></i>Seleziona WorkFlow</option>
					<?php
					foreach ($DatiWF as $rowLA) {
						$TabIdWF = $rowLA['ID_WORKFLOW'];
						$TabWF = $rowLA['WORKFLOW'];
						$sel = ($TabIdWF == $IdWorkFlow) ? "selected" : "";
					?><option <?php echo $sel; ?> value="<?php echo $TabIdWF; ?>"><?php echo $TabWF; ?></option>
					<?php } ?>
				</select>
				<?php
					if ($IdWorkFlow != "") {
						?>
						<script>LoadWf()</script>
					<?php }	?>
				

			</div>
		</div>
	</aside> 
	<br/>
	<div id="LoadWf"></div>
	<div id="ADDDiv"></div>
</form>