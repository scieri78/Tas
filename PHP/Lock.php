
<style>
.AllRighe{display:none;}
.Intest{display:none;}
</style>
<?php

include '../GESTIONE/connection.php';
include '../GESTIONE/SettaVar.php'; 

$KillAgent=$_POST['KillAgent'];
if ( "$KillAgent" != "" ){
	echo "<CENTER>eseguo Kill: $KillAgent</CENTER>";
	shell_exec("sh $PRGDIR/AvviaKillAgent.sh '${DATABASE}' '${SSHUSR}' '${SERVER}' '$KillAgent'");
}

?>
<BR><BR>
<div style="width:300px;margin:auto;left:0;right:0;text-align:center;" ><B>LOCK DB</B></div>
<BR>
<form id="LockForm" method="post">
<center><input type="submit" value="REFRESH" ></center>
<input id="SelAgent" type="hidden" value="" >
</form>
<table class="ExcelTable" style="width:800px;margin:auto;left:0;right:0;">
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
	<?php	
	
	$sql="SELECT DB_NAME, APPL_NAME, AUTHID, AGENT_ID, TBSP_NAME, TABSCHEMA, TABNAME, LOCK_OBJECT_TYPE, LOCK_MODE, LOCK_STATUS, count(*) CNT
FROM SYSIBMADM.LOCKS_HELD
WHERE TABSCHEMA NOT LIKE 'IBM%'
--  AND TABNAME = 'SEG_DIN'
GROUP BY DB_NAME, APPL_NAME, AUTHID, AGENT_ID,TBSP_NAME, TABSCHEMA, TABNAME, LOCK_OBJECT_TYPE, LOCK_MODE, LOCK_STATUS
ORDER BY  TABSCHEMA, TABNAME, LOCK_OBJECT_TYPE, LOCK_MODE, LOCK_STATUS
	";
	$stmt=db2_prepare($conn, $sql);
	$result=db2_execute($stmt);
    if ( ! $result) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
	while ($row = db2_fetch_assoc($stmt)) {
		
		$DB_NAME                 =$row['DB_NAME'];
		$APPL_NAME               =$row['APPL_NAME'];
		$AUTHID                  =$row['AUTHID'];
		$AGENT_ID                =$row['AGENT_ID'];
		$TBSP_NAME               =$row['TBSP_NAME'];
		$TABSCHEMA               =$row['TABSCHEMA'];
		$TABNAME                 =$row['TABNAME'];
		$LOCK_OBJECT_TYPE        =$row['LOCK_OBJECT_TYPE'];
		$LOCK_MODE               =$row['LOCK_MODE'];
		$LOCK_STATUS             =$row['LOCK_STATUS'];
		$CNT                     =$row['CNT'];
		
	  ?>
	  <tr>
		<td><?php echo $DB_NAME; ?></td>           
		<td><?php echo $APPL_NAME; ?></td>         
		<td><?php echo $AUTHID; ?></td>            
		<td onclick="OpenAuthId(<?php echo $AGENT_ID; ?>)" style="cursor:pointer;color:blue;" ><?php echo $AGENT_ID; ?></td>          
		<td><?php echo $TBSP_NAME; ?></td>         
		<td><?php echo $TABSCHEMA; ?></td>         
		<td><?php echo $TABNAME; ?></td>           
		<td><?php echo $LOCK_OBJECT_TYPE; ?></td>  
		<td><?php echo $LOCK_MODE; ?></td>         
		<td><?php echo $LOCK_STATUS; ?></td>       
		<td><?php echo $CNT; ?></td> 
        <td><img src="../images/Remove.png" width="25px" onclick="KillAgent(<?php echo $AGENT_ID; ?>)" ></td>
	  </tr>
	  <?php
	}
	?></table>
	<script>
	function OpenAuthId(vAgent){
		$('.AllRighe').each(function(){$(this).hide()});
		$('.Intest').hide();
		if ( $('#SelAgent').val() != vAgent ){
		  $('#SelAgent').val(vAgent);	
		  $('.Riga'+vAgent).each(function(){$(this).show()});
		  $('.Intest').show();
		} else {
		  $('#SelAgent').val('');	
		}
		
	}
	function KillAgent(vAgent){
		var re = confirm('Are you sure you want kill this agent id?');
        if ( re == true) {
             var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "KillAgent")
            .val(vAgent);
            $('#LockForm').append($(input));     			
			$('#Waiting').show();
            $('#LockForm').submit();
       }
	}
	</script>
<BR>
<table class="ExcelTable" style="width:800px;margin:auto;left:0;right:0;">
<tr class="Intest" >
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
	<?php	
	
	$sql="select
     APP.AGENT_ID
     ,APP.APPL_NAME
     ,APP.AUTHID
     ,SUBSTR(APP.APPL_ID,1,INSTR(APP.APPL_ID,'.',1,4)-1) AS IP
     ,APP.APPL_STATUS
     ,APP.STATUS_CHANGE_TIME
     ,SUBSTR(APP.CLIENT_NNAME,6,LENGTH(TRIM(APP.CLIENT_NNAME))-6) AS USER_CODE
     ,DECODE(
         SUBSTR(APP.CLIENT_NNAME,6,LENGTH(TRIM(APP.CLIENT_NNAME))-6),
          'RU17688','PACORA',
          'RU17376','CREVATID',
          'RU18578','ORAZIC',
          APP.CLIENT_NNAME
     ) AS USER_NAME
    ,MSQL.ACTIVITY_STATE
     ,MSQL.ACTIVITY_TYPE
     ,MSQL.TOTAL_CPU_TIME
     ,MSQL.ROWS_READ
     ,MSQL.ROWS_RETURNED
     ,MSQL.QUERY_COST_ESTIMATE
     ,MSQL.STMT_TEXT
  FROM
     SYSIBMADM.APPLICATIONS APP
    ,SYSIBMADM.MON_CONNECTION_SUMMARY CON
    ,SYSIBMADM.MON_CURRENT_SQL MSQL
WHERE APP.AGENT_ID = MSQL.APPLICATION_HANDLE (+)
   AND APP.AGENT_ID = CON.APPLICATION_HANDLE (+)
ORDER BY
    APP.AGENT_ID
	";
	$stmt=db2_prepare($conn, $sql);
	$result=db2_execute($stmt);
    if ( ! $result) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
	while ($row = db2_fetch_assoc($stmt)) {
	  $AGENT_ID            =$row['AGENT_ID'];
      $APPL_NAME           =$row['APPL_NAME'];
      $AUTHID              =$row['AUTHID'];
      $IP                  =$row['IP'];
      $APPL_STATUS         =$row['APPL_STATUS'];
      $STATUS_CHANGE_TIME  =$row['STATUS_CHANGE_TIME'];
      $USER_CODE           =$row['USER_CODE'];
      $USER_NAME           =$row['USER_NAME'];
      $ACTIVITY_STATE      =$row['ACTIVITY_STATE'];
      $ACTIVITY_TYPE       =$row['ACTIVITY_TYPE'];
      $TOTAL_CPU_TIME      =$row['TOTAL_CPU_TIME'];
      $ROWS_READ           =$row['ROWS_READ'];
      $ROWS_RETURNED       =$row['ROWS_RETURNED'];
      $QUERY_COST_ESTIMATE =$row['QUERY_COST_ESTIMATE'];
      $STMT_TEXT           =$row['STMT_TEXT'];

	  ?>
	  <tr class="AllRighe Riga<?php echo $AGENT_ID; ?>">
		<td><?php echo $AGENT_ID; ?></td>           
		<td><?php echo $APPL_NAME; ?></td>          
		<td><?php echo $AUTHID; ?></td>             
		<td><?php echo $IP; ?></td>                 
		<td><?php echo $APPL_STATUS ; ?></td>       
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
	?></table><?php
 
