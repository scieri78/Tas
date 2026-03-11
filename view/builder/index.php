
<link rel="stylesheet" href="./CSS/WfsGest.css">

  <div class="breadcrumbs">WFS Admin |   
	<span>Workflow Builder</span>
  </div>

<FORM id="FormMain" method="POST" >

	<input type="hidden" Name="IdWorkFlow" id="IdWorkFlow" value="" >
	<input type="hidden" Name="Azione" id="Azione" value="" >

	<div id="LoadConfig">
		<label>Team</label><BR>
		<select id="IdTeam" name="IdTeam"  class="FieldMand selectSearch" style="width:150px;height:30px;">
			<option value=""     <?php if ( $IdTeam == "" ){?>selected<?php } ?> >..</option>
			<?php
			foreach ($datiTeam as $rowLA) {
				$TabIdTeam=$rowLA['ID_TEAM'];
				$TabTeam=$rowLA['TEAM'];
				?><option value="<?php echo $TabIdTeam; ?>" <?php if ( $IdTeam == $TabIdTeam ){?>selected<?php } ?> ><?php echo $TabTeam; ?></option><?php
			}
			?>
		</select>
		
		<?php
		if ( $IdTeam != "" ){
			?>
			<div><LABEL>Select WorkFlow</LABEL></div>
			<?php
			foreach ($datiWorkflow as $rowLA) {
				$TabIdWorkFlow=$rowLA['ID_WORKFLOW'];
				$WorkFlow=$rowLA['WORKFLOW'];
				$Descr=$rowLA['DESCR'];
				$Readonly=$rowLA['READONLY'];
				$NumDip=$rowLA['CONTA'];
				$Freq=$rowLA['FREQUENZA'];
				$Multi=$rowLA['MULTI'];
				?>
				<div class="Plst WorkFlow" >
				  <?php
				  if ( $NumDip == "0" ) {
					 ?><div class="Plst DelWfs" onclick="DeleteWorkFlow(<?php echo $TabIdWorkFlow; ?>)" ><img class="ImgIco" src="./images/Cestino.png"  ></div><?php
				  } 
				  ?>
				  <div class="Plst ModWfs" onclick="ModWorkFlow('<?php echo $TabIdWorkFlow; ?>', '<?php echo $IdTeam; ?>')" ><img class="ImgIco" src="./images/Matita.png"  ></div>
				  <div style="color: brown;font-size:15px;" onclick="OpenWorkFlow(<?php echo $TabIdWorkFlow; ?>)" title="<?php  if  ( $Descr != "" ){ echo $Descr; }  ?>" >
				  <?php 
				  if ( $Readonly == "S" ){
					?><img class="ImgIco" src="./images/ReadMode.png"  ><?php  
				  }
				  echo "$WorkFlow [$Freq]"; ?>
				  </div>
				</div>
				<?php
			}
			if ( $TServer != "PROD USER" ){
			  ?>	
			  <div class="Plst WorkFlow"  onclick="ModWorkFlow('<?php echo null; ?>', '<?php echo $IdTeam; ?>')" style="display: inline-table;box-shadow: 0px 0px 4px 0px red inset;">
				 <div id="PulCreateWF" style="color:red;" ><img class="ImgIco" src="./images/Aggiungi.png" >WORKFLOW</div>
			  </div>
				<?php
			}
			?>
			<div id="EditWfs" ></div>      
			<?php
			
		}
		?>
	</div> 
</FORM>


<script src="./view/builder/JS/builder.js?p=<?php echo rand(1000,9999);?>"></script>
