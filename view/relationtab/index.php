<form id="FormDdl" method="POST">
	<BR>
	<aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note">
		<p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>Tools | Relation Table </p>
		<button onclick="$('#FormDdl').submit();return false;" id="refresh" class="btn"><i class="fa-solid fa-refresh"> </i> Refresh</button>
		<label class="labelFiltro">SCHEMA</label>

		<select style="width: 234px;margin: 0 12px;" class="selectSearch" name="Sel_Schema" id="Sel_Schema" onchange="$('#Sel_Object').val('');$('#FormDdl').submit();">
			<option value="">..</option>
			<?php
			foreach ($datiSchema as $row) {
				$Schema = $row['TABSCHEMA'];
			?><option value="<?php echo $Schema ?>" <?php if ($Sel_Schema == $Schema) { ?>selected<?php } ?>><?php echo $Schema; ?></option><?php
																																				}
																																					?>
		</select>
		<label class="labelFiltro">TABLE</label>

		<select style="width: 234px;margin: 0 12px;" class="selectSearch" name="Sel_Object" id="Sel_Object" onchange="$('#FormDdl').submit();">
			<option value="">..</option>
			<?php
			foreach ($datiTable as $row) {
				$Name = $row['TABNAME'];
				$Type = $row['TYPE'];
			?><option value="<?php echo $Name . '|' . $Type ?>" <?php if ($Sel_Table == $Name) { ?>selected<?php } ?>><?php echo $Name ?></option><?php
																																						}
																																							?>
		</select>
	</aside>
	<BR>
</form>

<?php

if ($Sel_Schema != "" and $Sel_Table != "") {

?>
	<div><B>TABELLA UTILIZZATA DA:</B></div>
	<button onclick="downloadExport();return false;" id="downloadFile" class="btn"><i class="fa-solid fa-download"> </i> Export xlsx</button>
	<table id='idTabella' class="display dataTable">
		<thead class="headerStyle">
			<tr>
				<th>TIPO</th>
				<th>DOVE</th>
				<th>OGGETTO</th>
				<th>LAST RUN SQL</th>
				<th>SHELL</th>
				<th>LAST RUN SH</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($datiSqlTable as $row) {
				$RTipo = $row['TIPO'];
				$RPath = $row['PATH'];
				$RFile = $row['FILE'];
				$RIdSql = $row['ID_SQL'];
				$RIdSh = $row['ID_SH'];
				$RNameSh = $row['SHELL'];
				$IDSHELL = $row['IDSHELL'];
				$LAST_RUN_SQL = $row['LAST_RUN_SQL'];
				$LAST_RUN_SH = $row['LAST_RUN_SH'];
				$LAST_RUN_SH = $row['LAST_RUN_SH_FATHER'] ? $row['LAST_RUN_SH_FATHER'] : $row['LAST_RUN_SH'];
				if ($RIdSh != "" or $RIdSql != "") {
			?>
					<tr>
						<td><?php echo $RTipo; ?></td>
						<td><?php echo $RPath; ?></td>
						<td><?php
						      if ($RIdSql != "") {
																									
							$titoloSh = "SQL: ($RIdSql) " . $RPath ."/". $RFile;
							?> <div class="addMail" onclick="openSqlFile('<?php echo $RIdSql; ?>','<?php echo $titoloSh; ?>')" >
							<img src="./images/File.png" class="IconSh" style="width:25px;">
						<?php
							$Fd = 1;
							echo $RFile; 
							echo "</div>";
						}?>
																								
							</div>
						</td>
						<td><?php echo $LAST_RUN_SQL; ?> </td>
						<td>
							 <?php
								if ($RIdSh != "") {
								?> <div class="addMail" onclick="openDialog('<?php echo $RIdSh; ?>','<?php echo $RFile; ?>','apriFile')">
								<img src="./images/File.png" class="IconSh" style="width:25px;">
								<?php
									$Fd = 1;
									echo $RFile; 
									echo "</div>";
								}
							
							if ($RIdSql != "") {
							?><div class="addMail" onclick="openDialog('<?php echo $IDSHELL; ?>','<?php echo $RNameSh; ?>','apriFile')">
									<img src="./images/File.png" class="IconSh" style="width:25px;">
									<?php echo $RNameSh;
									?>
								</div><?php
									}
										?>
						</td>
						<td><?php echo $LAST_RUN_SH; ?> </td>
					</tr>
				<?php
				}
			}

			foreach ($datiPkgTable as $row) {
				$RSchema = $row['PACKAGE_SCHEMA'];
				$RPackage = $row['PACKAGE'];
				$LAST_RUN_SQL = $row['LAST_RUN_SQL'];
				$LAST_RUN_SH = $row['LAST_RUN_SH'];
				$LAST_RUN_SH = $row['LAST_RUN_SH_FATHER'] ? $row['LAST_RUN_SH_FATHER'] : $row['LAST_RUN_SH'];
				?>
				<tr>
					<td>PACKAGE</td>
					<td><?php echo $RSchema; ?></td>
					<td>
						<i class="fa-solid fa-cube"></i>
						<div id="DisDett1011" class="addMail" onclick="gotoDDLPackage('<?php echo trim($RSchema); ?>','<?php echo trim($RPackage); ?>')">
							<?php echo $RPackage; ?>
						</div>
					</td>
					<td></td>
					<td></td>
					<td><?php echo $LAST_RUN_SH; ?> </td>
				</tr>
			<?php
			}


			foreach ($datiViewTable as $row) {
				$VSchema = $row['VIEWSCHEMA'];
				$VName = $row['VIEWNAME'];
			?>
				<tr>
					<td>VIEW</td>
					<td><?php echo $VSchema; ?></td>
					<td><?php echo $VName; ?></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			<?php
			}

			?>
		</tbody>
	</table>
<?php
}
?>