<?php

include './GESTIONE/connection.php';
include './GESTIONE/SettaVar.php'; 

	
 
    $sql="SELECT SCHEMA,PACKAGE,ROUTINE,START,END,STATUS,NOTES,AGENT_ID
	FROM WORK_ELAB.LOG_ROUTINES WHERE ID_RUN_SQL = $IDSQL ORDER BY START DESC";	
	$vTabella="LOG_ROUTINE";
	
	include './view/statoshell/DB2OpenTab.php';
 
    $sql="SELECT LOCATION, TMS_INSERT, TYPE, ERROR_MESSAGE, NOTES, KEYS FROM WORK_ELAB.LOG_EVENTS WHERE ID_RUN_SQL = $IDSQL ORDER BY TYPE, TMS_INSERT DESC";	
	$vTabella="LOG_EVENTS";
	
	include './view/statoshell/DB2OpenTab.php';
?>

