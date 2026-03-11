<?php

include './GESTIONE/connection.php';
include './GESTIONE/SettaVar.php'; 

$IDSH=$_GET["IDSH"];

$sql = "SELECT LOG FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = $IDSH";
$conn = db2_connect($db2_conn_string, '', '');
$stmt=db2_prepare($conn, $sql);
$result=db2_execute($stmt);
db2_close($db2_conn_string);
while ($row = db2_fetch_assoc($stmt)) {
  $Log=$row['LOG'];
}
//echo "LOG: ($IDSH) $Log<BR>";

$TestoFile = shell_exec("ssh $SSHUSR@$SERVER \"more $Log\" ");
$NameLog = shell_exec("basename $Log");

$NameLog=substr($NameLog, 0, -1);
$NameLog2=substr($NameLog, 0, -22);
$Dt=date("YmdHis");
shell_exec('rm -f '.${rootdir}.'/TMP/'.$NameLog2.'*');
$filename=$NameLog;
file_put_contents(${rootdir}.'/TMP/'.$filename, $TestoFile );
shell_exec('chmod 774 '.${rootdir}.'/TMP/'.$NameLog2.'*');


?>
<textarea class="DivShow" readonly ><?php echo $TestoFile; ?></textarea>
<div id="ShowDownload" ><a href="./TMP/<?php echo $filename; ?>" download><image src="./images/download.png" width="50px" /></a></div>


