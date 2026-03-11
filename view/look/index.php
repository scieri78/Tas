<div class="breadcrumbs">Tool |
	<span>Lock Db</span>
</div>


<form id="LockForm" method="post">
	<button id="refresh" class="btn AggiungiFlusso"><i class="fa-solid fa-refresh"> </i> Refresh</button>
	<br />
	<br />
	<input id="SelAgent" name="SelAgent" type="hidden" value="<?php echo $SelAgent; ?>">
</form>
<table class="display dataTable">
	<thead class="headerStyle">
		<tr>
			<th>DB_NAME</th>
			<th>APPL_NAME</th>
			<th>AUTHID</th>
			<th>AGENT_ID</th>
			<th>TBSP_NAME</th>
			<th>TABSCHEMA</th>
			<th>TABNAME</th>
			<th>LOCK_OBJECT_TYPE</th>
			<th>LOCK_MODE</th>
			<th>LOCK_STATUS</th>
			<th>CNT</th>
			<th>KILL</th>
		</tr>
	</thead>
	<tbody>

		<?php
		foreach ($datiLookDb as $row) {

			$DB_NAME                 = $row['DB_NAME'];
			$APPL_NAME               = $row['APPL_NAME'];
			$AUTHID                  = $row['AUTHID'];
			$AGENT_ID                = $row['AGENT_ID'];
			$TBSP_NAME               = $row['TBSP_NAME'];
			$TABSCHEMA               = $row['TABSCHEMA'];
			$TABNAME                 = $row['TABNAME'];
			$LOCK_OBJECT_TYPE        = $row['LOCK_OBJECT_TYPE'];
			$LOCK_MODE               = $row['LOCK_MODE'];
			$LOCK_STATUS             = $row['LOCK_STATUS'];
			$CNT                     = $row['CNT'];

		?>
			<tr style="vertical-align: baseline;">
				<td><?php echo $DB_NAME; ?></td>
				<td><?php echo $APPL_NAME; ?></td>
				<td><?php echo $AUTHID; ?></td>
				<td onclick="OpenAuthId(<?php echo $AGENT_ID; ?>)" style="cursor:pointer;color:blue;"><?php echo $AGENT_ID; ?></td>
				<td><?php echo $TBSP_NAME; ?></td>
				<td><?php echo $TABSCHEMA; ?></td>
				<td><?php echo $TABNAME; ?></td>
				<td><?php echo $LOCK_OBJECT_TYPE; ?></td>
				<td><?php echo $LOCK_MODE; ?></td>
				<td><?php echo $LOCK_STATUS; ?></td>
				<td><?php echo $CNT; ?></td>
				<td>
					<div id="idkill_<?php echo $AGENT_ID; ?>" class="Plst" onclick="KillAgent(<?php echo $AGENT_ID; ?>);return false;">
						<i class="fa-solid fa-trash-can"></i>
					</div>
				</td>
			</tr>
		<?php
		}
		?>
	</tbody>
</table>

<BR>

<table class="display dataTable">
	<thead class="headerStyle">
		<tr class="Intest">
			<th>AGENT_ID</th>
			<th>APPL_NAME</th>
			<th>AUTHID</th>
			<th>IP</th>
			<th>APPL_STATUS</th>
			<th>STATUS_CHANGE_TIME</th>
			<th>USER_CODE</th>
			<th>USER_NAME</th>
			<th>ACTIVITY_STATE</th>
			<th>ACTIVITY_TYPE</th>
			<th>TOTAL_CPU_TIME</th>
			<th>ROWS_READ</th>
			<th>ROWS_RETURNED</th>
			<th>QUERY_COST_ESTIMATE</th>
			<th>STMT_TEXT</th>
		</tr>
	</thead>

	<tbody>
		<?php

		foreach ($datiLookAgent as $row) {
			$AGENT_ID            = $row['AGENT_ID'];
			$APPL_NAME           = $row['APPL_NAME'];
			$AUTHID              = $row['AUTHID'];
			$IP                  = $row['IP'];
			$APPL_STATUS         = $row['APPL_STATUS'];
			$STATUS_CHANGE_TIME  = $row['STATUS_CHANGE_TIME'];
			$USER_CODE           = $row['USER_CODE'];
			$USER_NAME           = $row['USER_NAME'];
			$ACTIVITY_STATE      = $row['ACTIVITY_STATE'];
			$ACTIVITY_TYPE       = $row['ACTIVITY_TYPE'];
			$TOTAL_CPU_TIME      = $row['TOTAL_CPU_TIME'];
			$ROWS_READ           = $row['ROWS_READ'];
			$ROWS_RETURNED       = $row['ROWS_RETURNED'];
			$QUERY_COST_ESTIMATE = $row['QUERY_COST_ESTIMATE'];
			$STMT_TEXT           = $row['STMT_TEXT'];

		?>
			<tr class="AllRighe Riga<?php echo $AGENT_ID; ?>" style="vertical-align: baseline;">
				<td><?php echo $AGENT_ID; ?></td>
				<td><?php echo $APPL_NAME; ?></td>
				<td><?php echo $AUTHID; ?></td>
				<td><?php echo $IP; ?></td>
				<td><?php echo $APPL_STATUS; ?></td>
				<td><?php echo $STATUS_CHANGE_TIME; ?></td>
				<td><?php echo $USER_CODE; ?></td>
				<td><?php echo $USER_NAME; ?></td>
				<td><?php echo $ACTIVITY_STATE; ?></td>
				<td><?php echo $ACTIVITY_TYPE; ?></td>
				<td><?php echo $TOTAL_CPU_TIME; ?></td>
				<td><?php echo $ROWS_READ; ?></td>
				<td><?php echo $ROWS_RETURNED; ?></td>
				<td><?php echo $QUERY_COST_ESTIMATE; ?></td>
				<td><?php echo $STMT_TEXT; ?></td>
			</tr>
		<?php
		}
		?>
	</tbody>
</table>

<script>
	OpenAuthId($('#SelAgent').val());
</script>