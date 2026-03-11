<?php

include '../GESTIONE/connection.php';
include '../GESTIONE/SettaVar.php'; 
?>
<script src="../JS/jquery-2.2.0.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="../CSS/bootstrap.min.css">
<style>
#ShowDataElab{ display:none; }
#Waiting{
	position: fixed;
	height: 75px;
	width: 240px;
	background-color: white;
	z-index: 999999;
	border: 2px solid black;
	top: 0;
	box-shadow: 0px 0px 60px 3px black !important;
	bottom: 0;
	left: 0;
	right: 0;
	margin:auto;
}

#WaitImg{
	text-align:center;
	float:left;
}

#WaitText{
	text-align: center;
	font-size: 1.0em;
	margin: 25px;
	float:left;
}
</style>
<div name="Waiting" id="Waiting" >
    <table width="100%">
	<tr>
	<td><div id="WaitImg"><img src="../images/Attendere.gif" height="40px"></div></td>
	<td><div id="WaitText">Attendere Prego..</div></td>
	</tr>
	</table>
</div>
<?php 
 
    $IDSQL=$_GET["IDSQL"];
 
    $sql="SELECT SCHEMA,PACKAGE,ROUTINE,START,END,STATUS,NOTES,AGENT_ID
	FROM WORK_ELAB.LOG_ROUTINES WHERE ID_RUN_SQL = $IDSQL ORDER BY START DESC";	
	$vTabella="LOG_ROUTINE";
	
	include './DB2OpenTab.php';
 
    $sql="SELECT LOCATION, TMS_INSERT, TYPE, ERROR_MESSAGE, NOTES, KEYS FROM WORK_ELAB.LOG_EVENTS WHERE ID_RUN_SQL = $IDSQL ORDER BY TYPE, TMS_INSERT DESC";	
	$vTabella="LOG_EVENTS";
	
	include './DB2OpenTab.php';
?>
<script> 
	$('#Waiting').hide();
</script>
