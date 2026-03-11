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
#ShowDownload{ position: fixed; right: 10px; top: 5px; }

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

#CheckShowVar{
	position: fixed;
    top: 20px;
    left: 20px;
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

$IDSQL=$_GET["IDSQL"];
$ShowVar=$_POST['ShowVar'];

$sql = "SELECT FILE_SQL, ID_RUN_SH FROM WORK_CORE.CORE_DB WHERE ID_RUN_SQL = $IDSQL";
$conn = db2_connect($db2_conn_string, '', '');
$stmt=db2_prepare($conn, $sql);
$result=db2_execute($stmt);

if ( ! $result ){
    echo $sql;
    echo "ERROR DB2 2";
}    

while ($rowSQL = db2_fetch_assoc($stmt)) {
    $SqlName=$rowSQL['FILE_SQL'];
	$RunSh=$rowSQL['ID_RUN_SH'];
}

$sql = "SELECT ID_PROCESS, ESER_ESAME, MESE_ESAME, ESER_MESE FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = $RunSh";
$stmt=db2_prepare($conn, $sql);
$result=db2_execute($stmt);

if ( ! $result ){
    echo $sql;
    echo "ERROR DB2 2";
}    

while ($rowSQL = db2_fetch_assoc($stmt)) {
	$ID_PROCESS=$rowSQL['ID_PROCESS'];
	$ESER_ESAME=$rowSQL['ESER_ESAME'];
	$MESE_ESAME=$rowSQL['MESE_ESAME'];
	$ESER_MESE=$rowSQL['ESER_MESE'];
	$ESER_ESAME_PREC=$ESER_ESAME-1;
	$ESER_MESE_PREC=(($ESER_ESAME-1)*1000)+12;
}

db2_close($db2_conn_string);
echo "SQL: ($IDSQL) $SqlName<BR><BR><BR>";
if ( "$SqlName" != "" ){
    $TestoFile = shell_exec("ssh $SSHUSR@$SERVER \"cat $SqlName\" ");
    $FileSql = shell_exec("basename $SqlName");
    $FileSql =substr($FileSql,0,-5);
    
	$TestoFile=str_ireplace('SET WORK_CORE.','--SET WORK_CORE.',$TestoFile);
	
	if ( "$ShowVar" == "1" ){
	
       $TestoFile=str_ireplace('%ID_PROCESS%',$ID_PROCESS,$TestoFile);
	   $TestoFile=str_ireplace('%ESER_ESAME_PREC%',$ESER_ESAME_PREC,$TestoFile);
	   $TestoFile=str_ireplace('%ESER_ESAME%',$ESER_ESAME,$TestoFile);
	   $TestoFile=str_ireplace('%MESE_ESAME%',$MESE_ESAME,$TestoFile);
	   $TestoFile=str_ireplace('%ESER_MESE_PREC%',$ESER_MESE_PREC,$TestoFile);
	   $TestoFile=str_ireplace('%ESER_MESE%',$ESER_MESE,$TestoFile);
	   $TestoFile=str_ireplace('%QRT_ESAME%',CEIL($MESE_ESAME/3),$TestoFile);
	   $TestoFile=str_ireplace('%ESER_QRT%',$ESER_ESAME."0".CEIL($MESE_ESAME/3),$TestoFile);
	   
       $TestoFile=str_ireplace('WORK_CORE.VAR_ID_PROCESS',$ID_PROCESS,$TestoFile);
	   $TestoFile=str_ireplace('WORK_CORE.VAR_ESER_ESAME_PREC',$ESER_ESAME_PREC,$TestoFile);
	   $TestoFile=str_ireplace('WORK_CORE.VAR_ESER_ESAME',$ESER_ESAME,$TestoFile);
	   $TestoFile=str_ireplace('WORK_CORE.VAR_MESE_ESAME',$MESE_ESAME,$TestoFile);
	   $TestoFile=str_ireplace('WORK_CORE.VAR_ESER_MESE_PREC',$ESER_MESE_PREC,$TestoFile);
	   $TestoFile=str_ireplace('WORK_CORE.VAR_ESER_MESE',$ESER_MESE,$TestoFile);
	   $TestoFile=str_ireplace('WORK_CORE.VAR_QRT_ESAME',CEIL($MESE_ESAME/3),$TestoFile);
	   $TestoFile=str_ireplace('WORK_CORE.VAR_ESER_QRT',$ESER_ESAME."0".CEIL($MESE_ESAME/3),$TestoFile);
	
	}
	
    $Dt=date("YmdHis");
    shell_exec('rm -f '.${rootdir}.'/TMP/'.$FileSql.'*');
    $filename=$FileSql.'_'.$Dt.'.sql';
    file_put_contents(${rootdir}.'/TMP/'.$filename, $TestoFile );
    shell_exec('chmod 774 '.${rootdir}.'/TMP/'.$FileSql.'_*');
    

    ?>
	<form id="FormFile" method="POST">
	<div id="CheckShowVar"><input type="checkbox" id="ShowVar" name="ShowVar" value="1" <?php if ( "$ShowVar" == "1" ) { ?>checked<?php } ?> >Replace Var</div>
    <textarea class="DivShow" readonly ><?php echo $TestoFile; ?></textarea>
    <div id="ShowDownload" ><a href="../TMP/<?php echo $filename; ?>" download><image src="../images/download.png" width="50px" /></a></div>
	</form>
    <?php   
    
    
} else {
    echo "Errore nella lettura del file sql: $IDSQL";
}

?>
<script> 
    
    $('#Waiting').hide();
    
	$('#ShowVar').change(function(){
		$('#FormFile').submit();
	});
	
</script>
