<?php

include './GESTIONE/connection.php';
include './GESTIONE/SettaVar.php';
?>
<style>
#ShowDataElab{ display:none; }

#ShowDownload{ /*position: fixed; */
float:right; }
.DivShow{ 
   // position: fixed;
	background-color: white;
	width: 700px;
	height: 700px;
	left: 0;
	right: 0;
	margin: auto;
	top: 50px;
}
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

<?php 

//$IDSH=$_GET["IDSH"];

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

	$ShName=str_replace('.sh', '', $ShName); 
    $Dt=date("YmdHis");
    shell_exec('rm -f '.${rootdir}.'/TMP/'.$ShName.'_*');
	$filename=$ShName.'_'.$Dt.'.sh';
	file_put_contents(${rootdir}.'/TMP/'.$filename, $TestoFile );
	shell_exec('chmod 774 '.${rootdir}.'/TMP/'.$ShName.'_*');

	?>	
	<div id="ShowDownload" ><a href="./TMP/<?php echo $filename; ?>" download><image src="./images/download.png" width="50px" /></a></div>
	<textarea class="DivShow" readonly ><?php echo $TestoFile; ?></textarea>

	<?php	
	
} else {
    echo "Errore nella lettura dei dati shell dall'Anagrafica: $IDSH";
}
?>

