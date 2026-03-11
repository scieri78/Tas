<?php

include '../GESTIONE/connection.php';
include '../GESTIONE/SettaVar.php'; 
?>
<script src="../JS/jquery-2.2.0.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="../CSS/bootstrap.min.css">
<style>
#ShowDataElab{ display:none; }
.DivShow{ 
    position: fixed;
	background-color: white;
	width: 98%;
	height: 90%;
	left: 0;
	right: 0;
	margin: auto;
	top: 50px;
}
#ShowDownload{ position: fixed; right: 10px; top: 5px; }

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
    $IDSH=$_GET["IDSH"];
  
    $sql = "SELECT LOG FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = $IDSH";
    $conn = db2_connect($db2_conn_string, '', '');
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    db2_close($db2_conn_string);
    while ($row = db2_fetch_assoc($stmt)) {
      $Log=$row['LOG'];
	}
	echo "LOG: ($IDSH) $Log<BR>";
	
    $TestoFile = shell_exec("ssh $SSHUSR@$SERVER \"more $Log\" ");
	$NameLog = shell_exec("basename $Log");
	shell_exec('find '.${rootdir}.'/TMP/* -mtime +30 |xargs rm');

    $NameLog=substr($NameLog, 0, -1);
    $NameLog2=substr($NameLog, 0, -22);
    $Dt=date("YmdHis");
    shell_exec('rm -f '.${rootdir}.'/TMP/'.$NameLog2.'*');
    $filename=$NameLog;
	file_put_contents(${rootdir}.'/TMP/'.$filename, $TestoFile );
	shell_exec('chmod 774 '.${rootdir}.'/TMP/'.$NameLog2.'*');
	

	?>
	<textarea class="DivShow" readonly ><?php echo $TestoFile; ?></textarea>
    <div id="ShowDownload" ><a href="../TMP/<?php echo $filename; ?>" download><image src="../images/download.png" width="50px" /></a></div>
	<?php	
		
  
?>
<script> 
	$('#Waiting').hide();
</script>
