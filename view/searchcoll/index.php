<form id="Formsearchcoll" method="POST">
	<BR>
	<aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note">
		<p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>Tools | Ricerca Colonne </p>
		<button onclick="$('#FormDdl').submit();return false;" id="refresh" class="btn"><i class="fa-solid fa-refresh"> </i> Refresh</button>
		<label class="labelFiltro">COLONNA</label>
		<input onkeydown="searchcollInvia(event)" onblur="searchcoll()" class="inputFilter" id="COLNAME" name="COLNAME" value="<?php echo $COLNAME; ?>">

	</aside>
	<BR>
</form>

<?php

if ($COLNAME != "") {
?>
<button  onclick="downloadExport();return false;" id="downloadFile" class="btn"><i class="fa-solid fa-download"> </i> Export xlsx</button>
	<div><B>TABELLA UTILIZZATA DA:</B></div>
	<table id='idTabella' class="display dataTable">
		<thead class="headerStyle">
			<tr>
				<th>TABSCHEMA</th>
				<th>TABNAME</th>
				<th>COLNAME</th>
				<th class="modColl">RELATION TAB</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($datiTable as $row) {
				$TABSCHEMA = $row['TABSCHEMA'];
				$TABNAME = $row['TABNAME'];
				$COLNAME = $row['COLNAME'];


			?>
				<tr>
					<td><?php echo $TABSCHEMA; ?></td>
					<td><?php echo $TABNAME; ?></td>
					<td><?php echo $COLNAME; ?></td>
					<td class="modColl"><div id="DisDett1011" class="Plst" onclick="gotorelationtab('<?php echo trim($TABSCHEMA); ?>','<?php echo trim($TABNAME); ?>')">
						<i class="fa-solid fa-eye"></i>
					</div></td>
				</tr>

			<?php

			}


			?>

		</tbody>
	</table>

	<script>
		$('#idTabella').DataTable({
			language: {
				"url": "./JSON/italian.json"
			},
			"lengthMenu": [
				[50, 100, -1],
				[50, 100, 'All']
			]

		});



		function PulChiudi() {
			$('#InsDip').empty().hide();
		};
	</script><?php

			}


				?>