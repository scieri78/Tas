<?php
 include '../GESTIONE/connection.php';
 include '../GESTIONE/SettaVar.php'; 
 

$IdSh=$_GET['IDSH'];
if ( "$IdSh" == "" ){  exit; }


$SqlList="SELECT NAME FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = $IdSh";
$stmt=db2_prepare($conn, $SqlList);
$res=db2_execute($stmt);
if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); } 
while ($row = db2_fetch_assoc($stmt)) {
   $STEP=$row['NAME']; 
}

?>
<img style="top:10px;position:absolute;left:0;right:0;margin:auto;" src="./Grafici/graph_step.php?STEP=<?php echo $STEP; ?>&TAGS=<?php echo $TAGS; ?>" />
