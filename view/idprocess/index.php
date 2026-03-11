
<FORM id="FormMain" method="POST" >
	 <input type="hidden" id="mailPage" name="mailPage" value="<?php echo $mailPage;?>" />
	<input type="hidden" id="mailSearch" name="mailSearch" value="<?php echo $mailSearch;?>" />
	<input type="hidden" id="mailOrdern" name="mailOrdern" value="<?php echo $mailOrdern;?>" />
	<input type="hidden" id="mailOrdert" name="mailOrdert" value="<?php echo $mailOrdert;?>" />
	<input type="hidden" id="pageTable" name="pageTable" value="" />
	<input type="hidden" id="mailLength" name="mailLength" value="<?php echo $mailLength;?>" />

    <div id="LoadConfig" >	
		<aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note"> <p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>WFS Admin | IdProcess</p> 

			<div class="rowIdProcess">
				<button disabled id="refreshButtom" onclick="RefreshPage();return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-refresh"> </i> Refresh</button>
				<button id="addIdProcessButtom" onclick="viewAddIdProcess();return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-plus-circle"> </i> Idprocess</button>
				<button id="copyIdProcessButtom" onclick="viewCopyIdProcess();return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-copy"> </i> Copy</button>
				<button id="removeIdProcessButtom" onclick="viewRemoveIdProcess();return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-trash-can"> </i> Elimina</button>
			</div>
			<div class="rowIdProcess">
				<select class="noSearch" onchange="OpenTeam()" id="IdTeam" name="IdTeam"  style="width:150px;height:30px;">
					<option value="" <?php if ( $IdTeam == "" ){?>selected<?php } ?> >Selezione Team</option>
					<?php
					
					foreach ($DatiTeams as $rowLA) {
						$TabIdTeam=$rowLA['ID_TEAM'];
						$TabTeam=$rowLA['TEAM'];
						?><option value="<?php echo $TabIdTeam; ?>" <?php if ( $IdTeam == $TabIdTeam ){?>selected<?php } ?> ><?php echo $TabTeam; ?></option>
						<?php }?>
				</select>
				<?php if($IdTeam != ""){ ?>
						<script>
						setTimeout(function () {
						OpenTeam(<?php echo $IdWorkFlow; ?>);
						}, 500);										
						</script>
			    <?php } ?>
			
				<div id="divIdWorkFlow" style="display:inline">
					<select class="noSearch"  onchange="OpenWorkFlow()" id="IdWorkFlow" name="IdWorkFlow"  style="width:150px;height:30px;">
					</select>
				</div>
				
				<div id="newIdProcContainer" class="idProcessConteiner"></div>
			</div>
				
	</aside>    
	<div id="divAddIdProcess"></div>
	</div>
	
</FORM>

<script src="./view/idprocess/JS/index.js?p=<?php echo rand(1000,9999);?>"></script>