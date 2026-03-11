<style>
#contenitore {
    height: 80%;
}
#ShowDataElab{ display:none; }
.{ 
    position: absolute;
	background-color: white;
	width: 98%;
	height: 90%;
	left: 0;
	right: 0;
	margin: auto;
}
.DivShow{ 
	position: fixed;
	background-color: white;
	width: 98%;
	height: 70%;
	left: 0;
	right: 0;
	margin: auto;
	top: 170px;
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
#ShowDownload{
	position:absolute;
	right:10px;
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
	height: 125px;
	left: 0;
	right: 0;
	margin: auto;
	top: 10px;
}
</style>
<script src="../JS/jquery-2.2.0.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="../CSS/bootstrap.min.css">
<link rel="stylesheet" href="../CSS/excel.css">

<form id="FormDdl" method="POST"  >
<BR>
<div id="DivSearch" >
<CENTER style="font-size:20px;" >RIMUOVI TABLE PENDING</CENTER><BR>
<?php
$Sel_Schema=$_POST['Sel_Schema'];
$Sel_Table=$_POST['Sel_Table'];
$Leva=$_POST['Leva'];

$SelTab="${Sel_Schema}.${Sel_Table}";
if ( "$Sel_Tab" != "." and "$Leva" == "Rimuovi Pending"  ){
	echo "<CENTER>eseguo Rimozione pending: $SelTab</CENTER>";
	shell_exec("sh $PRGDIR/AvviaPendServer.sh '${DATABASE}' '${SSHUSR}' '${SERVER}' '$SelTab'");
}
?>
	<CENTER>
	<table id="tabSelObject" >
    <th>SCHEMA</th>
	<td>
	<select name="Sel_Schema" id="Sel_Schema" onchange="$('#Sel_Table').val('');$('#FormDdl').submit();">
		<option value="" >..</option>
		<?php 
		$sql = "select DISTINCT TRIM(TABSCHEMA) TABSCHEMA from syscat.tables WHERE TABSCHEMA NOT LIKE 'SYS%' ORDER BY 1";
		$stmt=db2_prepare($conn, $sql);
		$result=db2_execute($stmt);
		while ($row = db2_fetch_assoc($stmt)) {
		  $Schema=$row['TABSCHEMA'];
		  ?><option value="<?php echo $Schema ?>" <?php if ( "$Sel_Schema" == "$Schema"){ ?>selected<?php } ?> ><?php echo $Schema; ?></option><?php
		}
		?>
	</select>
	</td>
	<th>TABLE</th>
	<td>
	<select name="Sel_Table" id="Sel_Table" >
		<option value="" >..</option>
		<?php
		if ( "$Sel_Schema" != "" ){
			$sql = "select DISTINCT TRIM(TABNAME) TABNAME  from syscat.tables WHERE TYPE = 'T' AND TRIM(TABSCHEMA) = '$Sel_Schema' ORDER BY 1";
			$stmt=db2_prepare($conn, $sql);
			$result=db2_execute($stmt);
			while ($row = db2_fetch_assoc($stmt)) {
			  $Name=$row['TABNAME'];
			  ?><option value="<?php echo $Name ?>" <?php if ( "$Sel_Table" == "$Name" ){ ?>selected<?php } ?> ><?php echo $Name ?></option><?php
			}
		}
		?>
	</select>
	</td>
	</tr>
	</table>
	<BR>
	<input type="submit" name="Leva" value="Rimuovi Pending" ></CENTER>
</div>
<BR>
</form>
<script> 
	$('#Waiting').hide();
	
</script>

