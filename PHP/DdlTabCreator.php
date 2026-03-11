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
$Sel_Schema=$_POST['Sel_Schema'];
$Sel_Object=$_POST['Sel_Object'];
$arrs=explode('|',$Sel_Object);
$Sel_Table=$arrs[0];
$Sel_Type=$arrs[1];

if ( "$Sel_Schema" != "" and "$Sel_Table" != "" ){
	
	$Typelabel="t";
	$Optlabel="-noview ";
    switch($Sel_Type){
        case 'T': 
            $Typelabel="n";
            if ( "$DATABASE" == "USER" ){ 
                $TestoFile = shell_exec("ssh $SSHUSR@$SERVER \". /home/db2tsusr/sqllib/db2profile; db2look -d TASPCUSR -z $Sel_Schema -t $Sel_Table -e -r -nostatsclause -nofed -noview -noimplschema \" ");
            }else{
                $TestoFile = shell_exec("ssh $SSHUSR@$SERVER \". /home/db2tswrk/sqllib/db2profile; db2look -d TASPCWRK -z $Sel_Schema -t $Sel_Table -e -r -nostatsclause -nofed -noview -noimplschema \" ");
            }    
            break;        
        case 'N': 
            $TestoFile=" ---------- ! This is Nickname and i can't create DDL ! -------------- ";
            break;
        case 'V': 
            $sql = "SELECT TEXT FROM SYSCAT.VIEWS WHERE VIEWSCHEMA = '$Sel_Schema' AND VIEWNAME = '$Sel_Table'";
            $stmt=db2_prepare($conn, $sql);
            $result=db2_execute($stmt);
            while ($row = db2_fetch_assoc($stmt)) {
              $TestoFile=$row['TEXT'];
            }
            break;
    }       
   
	
    $Dt=date("YmdHis");
    shell_exec('rm -f '.${rootdir}.'/DDL/'.$Sel_Schema.'_'.$Sel_Table.'_*');
    $filename=$Sel_Schema.'.'.$Sel_Table.'_'.$Dt.'.sql';
	file_put_contents(${rootdir}.'/DDL/'.$filename, $TestoFile );
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
	<select name="Sel_Schema" id="Sel_Schema" onchange="$('#Sel_Object').val('');$('#FormDdl').submit();">
		<option value="" >..</option>
		<?php 
		$sql = "select DISTINCT TABSCHEMA from syscat.tables WHERE TABSCHEMA NOT LIKE 'SYS%' ORDER BY 1";
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
	<select name="Sel_Object" id="Sel_Object"  onchange="$('#FormDdl').submit();">
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
	<?php
	if ( "$Sel_Table" != "" and "$Sel_Schema" != "" ){
		?><div id="ShowDownload" ><a href="../DDL/<?php echo $filename; ?>" ><image src="../images/download.png" width="30px" /></a></div><?php
	}
	?>
</div>
<BR>
<?php
if ( "$Sel_Table" != "" and "$Sel_Schema" != "" ){
	

	?>
	<textarea class="DivShow" readonly ><?php echo $TestoFile; ?></textarea>
	<?php
}

?>
</form>
<script> 
	$('#Waiting').hide();
	
</script>

