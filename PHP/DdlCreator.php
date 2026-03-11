<style>
#contenitore {
    height: 80%;
}
#ShowDataElab{ display:none; }
.Pkg{ 
    position: absolute;
	background-color: white;
	width: 98%;
	height: 90%;
	left: 0;
	right: 0;
	margin: auto;
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
#ShowDownload{
	position:absolute;
	right:10px;
}

#tabSelObject{
	position:absolute;
	left: 10px;
}

th{
	padding:3px;
	min-width:100px
}

td{
	padding:3px;
	min-width:400px
	
}

select {
	width:100%;
}

#DivSearch{
	position: absolute;
	background-color: white;
	width: 98%;
	height: 30px;
	left: 0;
	right: 0;
	margin: auto;
	top: 10px;
}
</style>
<?php
$Sel_PkgName=$_POST['Sel_PkgName'];
$Sel_PkgSchema=$_POST['Sel_PkgSchema'];

if ( "$Sel_PkgSchema" != "" and "$Sel_PkgName" != "" ){
	

	$sql="select SOURCEHEADER, SOURCEBODY from SYSIBM.SYSMODULES WHERE  MODULESCHEMA = '$Sel_PkgSchema' AND  MODULENAME = '$Sel_PkgName'";
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
	
    $Dt=date("YmdHis");
	$filename=$Sel_PkgSchema.".".$Sel_PkgName.".sql";
    shell_exec('rm -f '.${rootdir}.'/DDL/'.$Sel_PkgSchema.".".$Sel_PkgName.'*');
	$filename=$Sel_PkgSchema.".".$Sel_PkgName."_".$Dt.".sql";
	file_put_contents(${rootdir}.'/DDL/'.$filename, $TestoPkg );
	shell_exec('chmod 774 '.${rootdir}.'/DDL/'.$filename);
		
}
?>
<form id="FormDdl" method="POST"  >
<BR>
<div id="DivSearch" >
	<table id="tabSelObject" >
	<tr>
	<th>SCHEMA</th>
	<td>
	<select name="Sel_PkgSchema" id="Sel_PkgSchema" onchange="$('#Sel_PkgName').val('');$('#FormDdl').submit();">
		<option value="" >..</option>
		<?php 
		$sql = "select DISTINCT TRIM(MODULESCHEMA) MODULESCHEMA from SYSIBM.SYSMODULES";
		$stmt=db2_prepare($conn, $sql);
		$result=db2_execute($stmt);
		while ($row = db2_fetch_assoc($stmt)) {
		  $PkgSchema=$row['MODULESCHEMA'];
		  ?><option value="<?php echo $PkgSchema ?>" <?php if ( "$Sel_PkgSchema" == "$PkgSchema"){ ?>selected<?php } ?> ><?php echo $PkgSchema; ?></option><?php
		}
		?>
	</select>
	</td>
	<th>PACKAGE</th>
	<td>
	<select name="Sel_PkgName" id="Sel_PkgName"  onchange="$('#FormDdl').submit();">
		<option value="" >..</option>
		<?php
		if ( "$Sel_PkgSchema" != "" ){
			$sql = "select DISTINCT TRIM(MODULENAME) MODULENAME from SYSIBM.SYSMODULES WHERE TRIM(MODULESCHEMA) = '$Sel_PkgSchema' ";
			$stmt=db2_prepare($conn, $sql);
			$result=db2_execute($stmt);
			while ($row = db2_fetch_assoc($stmt)) {
			  $PkgName=$row['MODULENAME'];
			  ?><option value="<?php echo $PkgName ?>" <?php if ( "$Sel_PkgName" == "$PkgName"){ ?>selected<?php } ?> ><?php echo $PkgName ?></option><?php
			}
		}
		?>
	</select>
	</td>
	</tr>
	</table>
	<?php
	if ( "$Sel_PkgSchema" != "" and "$Sel_PkgName" != "" ){
		?><div id="ShowDownload" ><a href="../DDL/<?php echo $filename; ?>" ><image src="../images/download.png" width="30px" /></a></div><?php
	}
	?>
</div>
<BR>
<?php
if ( "$Sel_PkgSchema" != "" and "$Sel_PkgName" != "" ){
	

	?>
	<textarea class="Pkg" readonly ><?php echo $TestoPkg; ?></textarea>
	<?php
}

?>
</form>
<script> 
	$('#Waiting').hide();
	
</script>

