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

#tabSelObject{
	position:absolute;
	left: 10px;
	height: 30px;

}

th{
	padding:3px;
}

td{
	padding:3px;	
}

select {
	width:100%;
}

#DivSearch{
	position: absolute;
	background-color: white;
	width: 630px;
	height: 30px;
	left: 0;
	right: 0;
	margin: auto;
	top: 10px;
}
</style>
<script src="../JS/jquery-2.2.0.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="../CSS/bootstrap.min.css">
<link rel="stylesheet" href="../CSS/excel.css">
<?php
$Sel_Schema=$_POST['Sel_Schema'];
$Sel_Object=$_POST['Sel_Object'];
$arrs=explode('|',$Sel_Object);
$Sel_Table=$arrs[0];
$Sel_Type=$arrs[1];

if ( "$Sel_Schema" != "" and "$Sel_Table" != "" ){
	
	?>
	<BR><BR>
	<div style="width:300px;margin:auto;left:0;right:0;text-align:center;" ><B>TABELLA UTILIZZATA DA:</B></div>
	<table class="ExcelTable" style="width:90%;margin:auto;left:0;right:0;">
	<tr>
	  <th style="width:30px;" >TIPO</th>
	  <th>DOVE</th>
	  <th>OGGETTO</th>
	  <th>SHELL</th>
	</tr>
	<?php	
	
	$sql="select a.*, 
	CASE 
	WHEN a.ID_SQL != 0 
	THEN (SELECT SHELL FROM WORK_CORE.CORE_SH_ANAG WHERE ID_SH = (
	SELECT ID_SH FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH in
	( SELECT ID_RUN_SH FROM WORK_CORE.CORE_DB WHERE ID_RUN_SQL = a.ID_SQL )
	))
	ELSE null
	END SHELL,
	CASE 
	WHEN a.ID_SQL != 0 
	THEN (SELECT ID_SH FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH in
	( SELECT ID_RUN_SH FROM WORK_CORE.CORE_DB WHERE ID_RUN_SQL = a.ID_SQL )
	)
	ELSE null
	END IDSHELL	
	FROM(
	SELECT TIPO, PATH, FILE, 
	( SELECT MAX(ID_RUN_SQL) FROM WORK_CORE.CORE_DB WHERE FILE_SQL = TIF.PATH||'/'||TIF.FILE ) ID_SQL , 
	( SELECT ID_SH FROM WORK_CORE.CORE_SH_ANAG WHERE SHELL_PATH = TIF.PATH AND SHELL = TIF.FILE ) ID_SH
	FROM WORK_RULES.TABLES_IN_FILE TIF WHERE TRIM(SCHEMA) = '$Sel_Schema' AND TRIM(TABELLA) = '$Sel_Table' AND DATABASE = '$DATABASE' 
	) a
	ORDER BY FILE";
	$stmt=db2_prepare($conn, $sql);
	$result=db2_execute($stmt);
	while ($row = db2_fetch_assoc($stmt)) {
	  $RTipo=$row['TIPO'];
	  $RPath=$row['PATH'];
	  $RFile=$row['FILE'];
	  $RIdSql=$row['ID_SQL'];
	  $RIdSh=$row['ID_SH'];
	  $RNameSh=$row['SHELL'];
	  $RIdNameSh=$row['IDSHELL'];
	  
	  if ( "$RIdSh" != "" or "$RIdSql" != "" ){ 
	  ?>
	  <tr>
		<td><?php echo $RTipo; ?></td>
	    <td><?php echo $RPath; ?></td>
	    <td><div style="cursor:pointer;"<?php
		if ( "$RIdSh" != "" ){ 
		   ?> onclick="OpenFile('<?php echo $RIdSh; ?>')" ><?php
		   $Fd=1;   
		}
		if ( "$RIdSql" != "" ){ 
		   ?> onclick="OpenSqlFile('<?php echo $RIdSql; ?>')" ><?php 
		   $Fd=1;
		}
        echo $RFile; ?></div></td>
		<td>
		<?php
		if ( "$RIdSql" != "" ){ 
		 ?><div style="cursor:pointer;" onclick="OpenFile('<?php echo $RIdNameSh; ?>')" ><?php 
         echo $RNameSh;
		 ?></div><?php
		}
		?>
		</td><?php 		
		?>
	  </tr>
	  <?php
	  }
	}

	$sql="SELECT  DISTINCT 
	    ROUTINESCHEMA as PACKAGE_SCHEMA, 
        ROUTINEMODULENAME as PACKAGE
FROM SYSCAT.ROUTINEDEP a
where a.btype in ('N','T')
  AND TRIM(BSCHEMA)= '$Sel_Schema'
  AND TRIM(BNAME) = '$Sel_Table'  
  AND ROUTINEMODULENAME IS NOT NULL
order by 1, 2
	";
	$stmt=db2_prepare($conn, $sql);
	$result=db2_execute($stmt);
	while ($row = db2_fetch_assoc($stmt)) {
	  $RSchema=$row['PACKAGE_SCHEMA'];
	  $RPackage=$row['PACKAGE'];
	  ?>
	  <tr>
		<td>PACKAGE</td>
	    <td><?php echo $RSchema; ?></td>
	    <td><?php echo $RPackage; ?></td>
		<td></td>
	  </tr>
	  <?php
	}	

	$sql="SELECT  DISTINCT 
		VIEWSCHEMA, 
		VIEWNAME
		FROM SYSCAT.VIEWS a
		where TEXT like '%$Sel_Schema.$Sel_Table %'
		order by 1, 2
	";

	$stmt=db2_prepare($conn, $sql);
	$result=db2_execute($stmt);
	while ($row = db2_fetch_assoc($stmt)) {
	  $VSchema=$row['VIEWSCHEMA'];
	  $VName=$row['VIEWNAME'];
	  ?>
	  <tr>
		<td>VIEW</td>
		<td><?php echo $VSchema; ?></td>
		<td><?php echo $VName; ?></td>
		<td></td>
	  </tr>
	  <?php
	}    	
	
	
	?></table><?php	
}
?>
<form id="FormDdl" method="POST"  >
<BR>
<div id="DivSearch" >
	<table id="tabSelObject" style="width:500px;left:0;righe:0;margin:auto;">
	<tr>
	<th>SCHEMA</th>
	<td>
	<select name="Sel_Schema" id="Sel_Schema" onchange="$('#Sel_Object').val('');$('#FormDdl').submit();" style="min-width:200px;" >
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
	<select name="Sel_Object" id="Sel_Object"  onchange="$('#FormDdl').submit();" style="min-width:300px;" >
		<option value="" >..</option>
		<?php
		if ( "$Sel_Schema" != "" ){
			$sql = "select DISTINCT TABNAME, TYPE  from syscat.tables WHERE TYPE in ('T','V','N') AND TRIM(TABSCHEMA) = '$Sel_Schema' ORDER BY 1";
			$stmt=db2_prepare($conn, $sql);
			$result=db2_execute($stmt);
			while ($row = db2_fetch_assoc($stmt)) {
			  $Name=$row['TABNAME'];
			  $Type=$row['TYPE'];
			  ?><option value="<?php echo $Name.'|'.$Type ?>" <?php if ( "$Sel_Table" == "$Name" ){ ?>selected<?php } ?> ><?php echo $Name ?></option><?php
			}
		}
		?>
	</select>
	</td>
	</tr>
	</table>
</div>
<BR>
</form>
<script> 
	$('#Waiting').hide();
	
function OpenSqlFile(vIdSql){    
     window.open('../PHP/ApriSqlFile.php?IDSQL='+vIdSql);
}

function OpenFile(vIdSh){
    window.open('../PHP/ApriFile.php?IDSH='+vIdSh);
}

</script>

