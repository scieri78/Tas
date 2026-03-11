<?php
 
 include '../GESTIONE/connection.php';
 include '../GESTIONE/SettaVar.php'; 
 ?>
<style>
#ShowDataElab{ display:none; }
</style>
<?php 
    $IDOBJ=$_GET["IDOBJ"];
	$TYPE=$_GET["TYPE"];
  
    $Label="LOG:";
    if ( "$TYPE" != "LSH"  ){
      $sql = "SELECT LOG_EXEC_MANAG AS FILE FROM WORK_CORE.EXEC_MANAG_OBJECTS WHERE ID_OBJECT = $IDOBJ";
	  
      IF ( "$TYPE" == "S"  ){
	    $sql = "SELECT '/area_staging_TAS/'||TARGET_DIR||'/'||TARGET AS FILE FROM WORK_CORE.EXEC_MANAG_OBJECTS WHERE ID_OBJECT = $IDOBJ";
	    $Label="FILE";
	  }
      $conn = db2_connect($db2_conn_string, '', '');
      $stmt=db2_prepare($conn, $sql);
      $result=db2_execute($stmt);
      db2_close($db2_conn_string);
      while ($row = db2_fetch_assoc($stmt)) {
        $File=$row['FILE'];
	  }
	} else {
	  $File="$IDOBJ";
	  $IDOBJ="";
	}
	echo "$Label : ($IDOBJ) $File<BR>";
	
    $TestoLog = shell_exec("ssh $SSHUSR@$SERVER \"more $File\" ");
    echo "-------------------------------------------------------------------------------------<BR>";
    echo preg_replace("/\r\n|\r|\n/",'<br/>',$TestoLog);
    echo "-------------------------------------------------------------------------------------";
?>
