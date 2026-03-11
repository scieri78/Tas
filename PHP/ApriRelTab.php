
<?php
 
 include '../GESTIONE/connection.php';
 include '../GESTIONE/SettaVar.php'; 
 
$conn = db2_connect($db2_conn_string, '', '');
 ?> 
<script src="../JS/jquery-2.2.0.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="../CSS/bootstrap.min.css">
<link rel="stylesheet" href="../CSS/excel.css">
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
$InIdSh=$_GET['IDSH'];
$InIdRunSh=$_GET['IDRUNSH'];
$InIdRunSql=$_GET['IDRUNSQL'];

db2_close($db2_conn_string);

        
if ( "$InIdSh" != "" or "$InIdRunSql" != "" ){
    
    
    if ( "$InIdRunSql" == "" ){ 
      $sql="SELECT SHELL_PATH||'/'||SHELL FILE FROM WORK_CORE.CORE_SH_ANAG WHERE ID_SH = $InIdSh";
      $stmt=db2_prepare($conn, $sql);
      $result=db2_execute($stmt);
      while ($row = db2_fetch_assoc($stmt)) {
        $ListFile="'".$row['FILE']."',";
      } 
      $InFile=$ListFile;
      $sql="SELECT DISTINCT FILE_SQL FROM WORK_CORE.CORE_DB WHERE ID_RUN_SH = $InIdRunSh";
      $stmt=db2_prepare($conn, $sql);
      $result=db2_execute($stmt);
      while ($row = db2_fetch_assoc($stmt)) {
        $ListFile=$ListFile."'".$row['FILE_SQL']."',";
      } 
	  $ListFile=substr($ListFile, 0, -1);
    } else {
      $sql="SELECT FILE_SQL FROM WORK_CORE.CORE_DB WHERE ID_RUN_SQL = $InIdRunSql";
      $stmt=db2_prepare($conn, $sql);
      $result=db2_execute($stmt);
      while ($row = db2_fetch_assoc($stmt)) {
        $ListFile=$row['FILE_SQL'];
      } 
      $InFile=$ListFile;
	  $ListFile="'".$ListFile."'";
    }
    ?>
    <BR><BR>
    <div style="margin:auto;left:0;right:0;text-align:center;" ><B>TABELLE UTILIZZATE DAL FILE <BR><?php echo $InFile; ?></B></div>
    <BR>
    <table class="ExcelTable" style="width:300px;margin:auto;left:0;right:0;">
    <tr>
      <?php if ( "$InIdSh" != "" ) { ?>
      <th>PATH</th>
      <th>FILE</th>
      <?php } ?>
      <th>SCHEMA</th>
      <th>TABLE</th>
    </tr>
    <?php   
    $sql="SELECT DISTINCT PATH, FILE, TRIM(SCHEMA) SCHEMA, TABELLA  FROM WORK_RULES.TABLES_IN_FILE WHERE PATH||'/'||FILE IN( $ListFile ) ORDER BY 1,2";
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    while ($row = db2_fetch_assoc($stmt)) {
      $RPath=$row['PATH'];
      $RFile=$row['FILE'];
      $RSchema=$row['SCHEMA'];
      $Rtabella=$row['TABELLA'];
      ?>
      <tr>
        <?php if ( "$InIdSh" != "" ) { ?>
        <td><?php echo $RPath; ?></td>
        <td><?php echo $RFile; ?></td>
        <?php } ?>
        <td><?php echo $RSchema; ?></td>
        <td><?php echo $Rtabella; ?></td>
      </tr>
      <?php
    }
    if ( "$InIdRunSql" != "" ) {

        $sql="SELECT  DISTINCT ROUTINESCHEMA as PACKAGE_SCHEMA, 
            ROUTINEMODULENAME as PACKAGE, 
            BSCHEMA as TAB_SCHEMA, 
            BNAME as TAB_NAME
            FROM SYSCAT.ROUTINEDEP a
            where a.btype in ('N','T')
              AND (ROUTINESCHEMA,ROUTINEMODULENAME) IN ( SELECT DISTINCT SCHEMA,PACKAGE FROM WORK_ELAB.LOG_ROUTINES WHERE ID_RUN_SQL = $InIdRunSql  )
            order by 1, 2, 3, 4
        ";
		
	}else{

        $sql="SELECT  DISTINCT ROUTINESCHEMA as PACKAGE_SCHEMA, 
            ROUTINEMODULENAME as PACKAGE, 
            BSCHEMA as TAB_SCHEMA, 
            BNAME as TAB_NAME
            FROM SYSCAT.ROUTINEDEP a
            where a.btype in ('N','T')
              AND (ROUTINESCHEMA,ROUTINEMODULENAME) IN ( SELECT DISTINCT SCHEMA,PACKAGE FROM WORK_ELAB.LOG_ROUTINES WHERE ID_RUN_SQL IN
			  (SELECT ID_RUN_SQL FROM WORK_CORE.CORE_DB WHERE ID_RUN_SH = $InIdRunSh )
			  )
            order by 1, 2, 3, 4
        ";
		
    }
	$stmt=db2_prepare($conn, $sql);
	$result=db2_execute($stmt);
	while ($row = db2_fetch_assoc($stmt)) {
	  $PkgSchema=$row['PACKAGE_SCHEMA'];
	  $PkgName=$row['PACKAGE'];
	  $TabSchema=$row['TAB_SCHEMA'];
	  $TabName=$row['TAB_NAME'];
	  ?>
	  <tr>
		<td><?php echo $PkgSchema; ?></td>
		<td><?php echo $PkgName; ?></td>
		<td><?php echo $TabSchema; ?></td>
		<td><?php echo $TabName; ?></td>
	  </tr>
	  <?php
	}       
    ?></table><?php
} 

?>
<script> 
    $('#Waiting').hide();
</script>

