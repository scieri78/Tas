<script type="text/javascript" src="./view/ddlPackage/JS/index.js?p=<?php echo rand(1000,9999);?>"></script>

<form id="FormDdl" method="POST">
	<input type="hidden" name="sito" value="<?= $_GET['sito']?$_GET['sito']:$_POST['sito'] ?>"/>
	<BR>
	<aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note"> <p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>Tools | DDL Package</p> 
	<button onclick="$('#FormDdl').submit();return false;" id="refresh" class="btn"><i class="fa-solid fa-refresh"> </i> Refresh</button>		
	<label class="labelFiltro">SCHEMA</label>
					
					
		<select style="width: 234px;margin: 0 12px;"  class="selectSearch" name="Sel_PkgSchema" id="Sel_PkgSchema" onchange="$('#Sel_PkgName').val('');$('#FormDdl').submit();">
			<option value="" >..</option>
			<?php 
			
			foreach ($datiSchema as $row) {
			  $PkgSchema=$row['MODULESCHEMA'];
			  ?><option value="<?php echo $PkgSchema ?>" <?php if ( $Sel_PkgSchema == $PkgSchema){ ?>selected<?php } ?> ><?php echo $PkgSchema; ?></option><?php
			}
			?>
		</select>
		<label class="labelFiltro">PACKAGE</label>
					
					
		<select style="width: 234px;margin: 0 12px;"  class="selectSearch" name="Sel_PkgName" id="Sel_PkgName"  onchange="$('#FormDdl').submit();">
			<option value="" >..</option>
			<?php
				foreach ($datiPackage as $row) {
				  $PkgName=$row['MODULENAME'];
				  ?><option value="<?php echo $PkgName ?>" <?php if ( $Sel_PkgName == $PkgName){ ?>selected<?php } ?> ><?php echo $PkgName ?></option><?php
				}
			
			?>
		</select>
	
			
			<?php
			if ( $Sel_PkgSchema != "" and $Sel_PkgName != "" ){
				?>
				<button onclick="window.open('./DDL/<?php echo $filename; ?>');return false;" id="removeWorkFlow" class="btn" style="display: inline-block;"><i class="fa-solid fa-download"> </i> Download File</button></a>
				<button onclick="copy();return false;" id="removeWorkFlow" class="btn" style="display: inline-block;"><i class="fa-solid fa-copy"> </i> Copia Testo</button></a>
			<?php
			}
			?>
	</aside>
	<BR>
	<?php
	if ( $Sel_PkgSchema != "" and $Sel_PkgName != "" ){
		

		?>
		<textarea class="Pkg" readonly ><?php echo $TestoPkg; ?></textarea>
		<?php
	}

	?>
</form>

