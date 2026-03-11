<?php
if ($PkgSchema != "" and $PkgName != "") {?>


	<table class="display dataTable">
		<thead class="headerStyle">
			<tr>
				<th>SCHEMA</th>
				<th>TABLE</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($DatiPackageSql as $row) {
				$PkgSchema = $row['PACKAGE_SCHEMA'];
				$PkgName = $row['PACKAGE'];
				$TabSchema = $row['TAB_SCHEMA'];
				$TabName = $row['TAB_NAME'];
			?>
				<tr>
					<td><?php echo $TabSchema; ?></td>
					<td><?php echo $TabName; ?></td>
				</tr>
			<?php
			}
			?>
		</tbody>
	</table>
<?php } ?>