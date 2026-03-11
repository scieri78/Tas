<?php
include './GESTIONE/connection.php';

$sqlTabRead = "SELECT 
    ID_OBJECT, 
    DESCR_OBJECT, 
    TARGET, 
    TARGET_DIR, 
    VARIABLES, 
    ENABLE, 
    VALID_FROM, 
    VALID_TO, 
    SET_MONTH, 
    SET_DAY, 
    SET_HOUR, 
    SET_MINUTE, 
    FREQUENCY, 
    BG, 
    STATUS, 
    START_TIME, 
    END_TIME, 
    LOG_EXEC_MANAG, 
    ( SELECT NAME_GROUP FROM WORK_CORE.EXEC_MANAG_GROUPS WHERE ID_EM_GROUP = H.ID_EM_GROUP ) NAME_GROUP,
	( SELECT NVL((SELECT NOMINATIVO USERNAME FROM WEB.TAS_UTENTI WHERE USERNAME = A.USERNAME),A.USERNAME) USERNAME 
	FROM WORK_CORE.EXEC_MANAG_AUDIT A
	WHERE ID_OBJECT = H.ID_OBJECT 
	AND TMS_INSERT = ( SELECT MAX(TMS_INSERT) FROM WORK_CORE.EXEC_MANAG_AUDIT WHERE ID_OBJECT = H.ID_OBJECT AND TMS_INSERT <= H.START_TIME)
	) USERNAME
FROM 
    WORK_CORE.EXEC_MANAG_HISTORY H
WHERE START_TIME is not null
AND START_TIME >= (SELECT CURRENT_timestamp - 60 DAYS FROM DUAL)	
ORDER BY START_TIME DESC	
";

$stmtTabRead = db2_prepare($conn, $sqlTabRead);
$resultTabRead = db2_execute($stmtTabRead); 
if ( ! $resultTabRead) { echo "Stmt Exec Error ".db2_stmt_errormsg(); } 
?>

<table class="ExcelTable" >
<tr>
    <th>GROUP</th>
	<th>TARGET_DIR</th>
	<th>TARGET</th>
	<th>DESCR_OBJECT</th>
    <th>VARIABLES</th>
    <th>START_TIME</th>
    <th>END_TIME</th>
	<th>ESITO</th>
	<th>USER</th>
<tr>
<?php
while ($rowTabRead = db2_fetch_assoc($stmtTabRead)) {
    
	$ID_OBJECT        =$rowTabRead['ID_OBJECT'];
    $DESCR_OBJECT     =$rowTabRead['DESCR_OBJECT']; 
    $TARGET           =$rowTabRead['TARGET']; 
    $TARGET_DIR       =$rowTabRead['TARGET_DIR']; 
    $VARIABLES        =$rowTabRead['VARIABLES']; 
    $ENABLE           =$rowTabRead['ENABLE']; 
    $VALID_FROM       =$rowTabRead['VALID_FROM']; 
    $VALID_TO         =$rowTabRead['VALID_TO']; 
    $SET_MONTH        =$rowTabRead['SET_MONTH']; 
    $SET_DAY          =$rowTabRead['SET_DAY']; 
    $SET_HOUR         =$rowTabRead['SET_HOUR']; 
    $SET_MINUTE       =$rowTabRead['SET_MINUTE']; 
    $FREQUENCY        =$rowTabRead['FREQUENCY']; 
    $BG               =$rowTabRead['BG']; 
    $STATUS           =$rowTabRead['STATUS']; 
    $START_TIME       =$rowTabRead['START_TIME']; 
    $END_TIME         =$rowTabRead['END_TIME']; 
    $LOG_EXEC_MANAG   =$rowTabRead['LOG_EXEC_MANAG']; 
    $USERNAME   =$rowTabRead['USERNAME']; 
	$NAME_GROUP   =$rowTabRead['NAME_GROUP']; 
	
	
	$ImgDip="";
	switch ($STATUS) {
           case "X": 
             $ImgDip='./images/KO.png';
             break;
           case "F": 
             $ImgDip='./images/OK.png'; 
             break;
           case "C":               
             $ImgDip='./images/Loading.gif';
             break;  
           case "W":               
             $ImgDip='./images/Warning.png';
             break;                      
           default:
              $ImgDip='./images/OK.png'; 
         }      
	
    ?>
    <tr>
        <td><?php echo $NAME_GROUP; ?></td>
	    <td><?php echo $TARGET_DIR; ?></td>
	    <td><?php echo $TARGET; ?></td>
	    <td><?php echo $DESCR_OBJECT; ?></td>
        <td><?php echo $VARIABLES; ?></td>
        <td><?php echo $START_TIME; ?></td>
        <td><?php echo $END_TIME; ?></td>
		<td><img style="width:25px;" src="<?php echo $ImgDip; ?>" title="<?php echo $ID_OBJECT; ?>" ></td>
        <td><?php echo $USERNAME; ?></td>
    <tr>                       
    <?php
}
?></table>
<?php
db2_close($conn); 
?>
