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

$sql = "SELECT SHELL_PATH, SHELL FROM WORK_CORE.CORE_SH_ANAG WHERE ID_SH = $IDSH";
$conn = db2_connect($db2_conn_string, '', '');
$stmt=db2_prepare($conn, $sql);
$result=db2_execute($stmt);

if ( ! $result ){
    echo $sql;
    echo "ERROR DB2 2";
}    

while ($rowSH = db2_fetch_assoc($stmt)) {
    $ShName=$rowSH['SHELL'];
    $ShPath=$rowSH['SHELL_PATH'];
}
db2_close($db2_conn_string);
if ( "$ShPath" != "" ){
    echo "SHELL: ($IDSH) $ShPath/$ShName<BR>";
    $TestoFile = shell_exec("ssh $SSHUSR@$SERVER \"cat $ShPath/$ShName\" ");
    //echo preg_replace("/\r\n|\r|\n/",'<br/>',$TestoFile);
	shell_exec('find '.${rootdir}.'/TMP/* -mtime +30 |xargs rm');

	$ShName=str_replace('.sh', '', $ShName); 
    $Dt=date("YmdHis");
    shell_exec('rm -f '.${rootdir}.'/TMP/'.$ShName.'_*');
	$filename=$ShName.'_'.$Dt.'.sh';
	file_put_contents(${rootdir}.'/TMP/'.$filename, $TestoFile );
	shell_exec('chmod 774 '.${rootdir}.'/TMP/'.$ShName.'_*');

	?>
	<textarea class="DivShow" readonly ><?php echo $TestoFile; ?></textarea>
	<div id="ShowDownload" ><a href="../TMP/<?php echo $filename; ?>" download><image src="../images/download.png" width="50px" /></a></div>
	<?php	
	
} else {
    echo "Errore nella lettura dei dati shell dall'Anagrafica: $IDSH";
}
?>
<script> 
	$('#Waiting').hide();
</script>
