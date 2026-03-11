
<?php
 
 include '../GESTIONE/connection.php';
 include '../GESTIONE/SettaVar.php'; 
 

$IDSQL=$_GET["IDSQL"];

$conn = db2_connect($db2_conn_string, '', '');
 ?> 
<script src="../JS/jquery-2.2.0.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="../CSS/bootstrap.min.css">
<style>
#ShowDataElab{ display:none; }
.Pkg{ 
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
$PkgSchema=$_GET['SCHEMA'];
$PkgName=$_GET['PACKAGE'];

db2_close($db2_conn_string);

if ( "$PkgSchema" != "" and "$PkgName" != "" ){
	
	?><B>DDL <?php echo $PkgSchema.".".$PkgName; ?></B><?php
	
	$sql="select SOURCEHEADER, SOURCEBODY 
	from SYSIBM.SYSMODULES
	WHERE
	MODULESCHEMA = '$PkgSchema' AND MODULENAME = '$PkgName'
	";
	$stmt=db2_prepare($conn, $sql);
	$result=db2_execute($stmt);
	while ($row = db2_fetch_assoc($stmt)) {
	  $PkgHead=$row['SOURCEHEADER'];
	  $PkgBody=$row['SOURCEBODY'];
	}
	$TestoPkg="$PkgHead
/
$PkgBody
/
";
	shell_exec('find '.${rootdir}.'/DLL/* -mtime +30 |xargs rm');

    $Dt=date("YmdHis");
	echo $Dt;
	$filename=$PkgSchema.".".$PkgName.".sql";
    shell_exec('rm -f '.${rootdir}.'/DDL/'.$PkgSchema.".".$PkgName.'*');
	$filename=$PkgSchema.".".$PkgName."_".$Dt.".sql";
	file_put_contents(${rootdir}.'/DDL/'.$filename, $TestoPkg );
	shell_exec('chmod 774 '.${rootdir}.'/DDL/'.$filename);
	

	?>
	<textarea class="Pkg" readonly ><?php echo $TestoPkg; ?></textarea>
	<div id="ShowDownload" ><a href="../DDL/<?php echo $filename; ?>" ><image src="../images/download.png" width="50px" /></a></div>
	<?php
} 

?>
<script> 
	$('#Waiting').hide();
</script>

