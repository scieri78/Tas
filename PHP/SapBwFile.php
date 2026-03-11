
<style>

.Titolo{
    position:relative;
    left:0;
    right:0;
    margin:auto;
    font-size:20px;
    width:100%;
}


.Title{
  margin:4px;
  font-size:20px;
  left:0;
  right:0;
  margin:auto;
  width: max-content;
  color: blue;
}

.Button{
  width:145px;
  height:30px;
  border: 1px solid black;
  bachground:white;
  text-align: center;
  margin:5px;
  cursor:pointer;
}
</style>
<?php
include '../GESTIONE/sicurezza.php';

if ( $find == 1 ){
    
    function alert($msg) {
        echo "<script type='text/javascript'>alert('$msg');</script>";
    }
	
	$SelM=6;
    ?>
<form id="FormSelectRete" method="POST">

<center><div class="Titolo" >SAPBW FILE [Last <?php echo $SelM; ?> Month]</div><BR>
<input type="submit" value="REFRESH" class="Bottone" style="background:yellow;" ></center>
<BR>
<CENTER>
<B>STRATI CONSOLIDATI SULLA DA_SAPBW</B>
<table width="100%" class="ExcelTable" >
    <tr>
         <th>ESER_ESAME</th>
         <th>MESE_ESAME</th>
         <th>TAGS</th>
         <th>ID_FILE</th> 
         <th>INPUT_FILE</th>
         <th>START_TIME</th>
         <th>END_TIME</th>
		 <th>TIME</th>
    </tr>
    <?php    
    $sql="SELECT ESER_ESAME, 
        MESE_ESAME, 
        TAGS, 
        START_TIME, 
        END_TIME, 
		timestampdiff(2,NVL(END_TIME,CURRENT_TIMESTAMP)-START_TIME) DIFF,
        VARIABLES, 
        ID_FILE, 
        INPUT_FILE 
FROM LOSS_RESERVING.V_SAPBW_FILE
WHERE ESER_ESAME||LPAD(MESE_ESAME,2,0) >= YEAR(DATE( CURRENT_TIMESTAMP - $SelM MONTH))||LPAD(MONTH(DATE( CURRENT_TIMESTAMP - $SelM MONTH)),2,0)
ORDER BY ESER_ESAME desc, MESE_ESAME desc, TAGS DESC;
    ";
    #echo $sql;
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
	$OldPer="1";
    if ( ! $result) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
    while ($row = db2_fetch_assoc($stmt)) {
        $ESER_ESAME =$row['ESER_ESAME'];
        $MESE_ESAME =$row['MESE_ESAME'];
        $TAGS       =$row['TAGS'];
        $START_TIME =$row['START_TIME'];
        $END_TIME   =$row['END_TIME'];
		$DIFF   =$row['DIFF'];
        $VARIABLES  =$row['VARIABLES'];
        $ID_FILE    =$row['ID_FILE'];
        $INPUT_FILE =$row['INPUT_FILE'];
	
	if ( "$OldPer" != "$ESER_ESAME$MESE_ESAME" ){
	  if ( "$OldPer" != "1" ){
	    ?>
        <tr style="height:2px;">
          <th style="height: 2px !important;" ></th>
          <th style="height: 2px !important;" ></th>
          <th style="height: 2px !important;" ></th>
          <th style="height: 2px !important;" ></th>
          <th style="height: 2px !important;" ></th>
		  <th style="height: 2px !important;" ></th>
          <th style="height: 2px !important;" ></th>
          <th style="height: 2px !important;" ></th>
        </tr>
        <?php    	
	  }
	  $OldPer="$ESER_ESAME$MESE_ESAME";
	}
	
    ?>
      <tr >
        <td><?php echo $ESER_ESAME; ?></td>    
        <td><?php echo $MESE_ESAME; ?></td>    
        <td><?php echo $TAGS; ?></td>    
        <td><?php echo $ID_FILE; ?></td>    
        <td><?php echo $INPUT_FILE; ?></td>   
        <td><?php echo $START_TIME; ?></td>    
        <td><?php echo $END_TIME; ?></td>     
		<td><?php echo gmdate('i:s',$DIFF); ?></td>   
      </tr>
    <?php
	}
  ?>
</table>
<BR>
<B>STATO LIBRERIA MULTI FILE</B>
<table width="100%" class="ExcelTable" >
    <tr>
         <th>ESER_ESAME</th>
         <th>MESE_ESAME</th>
         <th>TIPO</th>
         <th>LAST_CONS</th>
         <th>ID_FILE</th>
         <th>INPUT_FILE</th> 
         <th>LOAD_DATE</th>
		 <th>NUM_ROWS</th>
    </tr>
    <?php  
  $sql="SELECT * FROM (
  SELECT 
    (SELECT ESER_ESAME FROM TASPCWRK.WORK_CORE.CORE_SH c WHERE c.ID_RUN_SH = I.ID_RUN_SH) ESER_ESAME
   ,(SELECT MESE_ESAME FROM TASPCWRK.WORK_CORE.CORE_SH c WHERE c.ID_RUN_SH = I.ID_RUN_SH) MESE_ESAME
   ,ID_RUN_SQL ID_FILE
   ,LOAD_DATE
   ,LAST_CONS
   ,NUM_ROWS
   ,INPUT_FILE
   ,NOTE
FROM  
TASPCWRK.WORK_RULES.INPUT_FILES I
WHERE ID_SH = (SELECT ID_SH FROM TASPCWRK.WORK_CORE.CORE_SH_ANAG WHERE SHELL = 'SAPBW_Carica.sh')
)
WHERE ESER_ESAME||LPAD(MESE_ESAME,2,0) >= YEAR(DATE( CURRENT_TIMESTAMP - $SelM MONTH))||LPAD(MONTH(DATE( CURRENT_TIMESTAMP - $SelM MONTH)),2,0)
ORDER BY LOAD_DATE DESC
;
    ";
    #echo $sql;
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
	$OldPer="1";
    if ( ! $result) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
    while ($row = db2_fetch_assoc($stmt)) {
        $ESER_ESAME =$row['ESER_ESAME'];
        $MESE_ESAME =$row['MESE_ESAME'];
        $ID_FILE =$row['ID_FILE'];
        $LOAD_DATE =$row['LOAD_DATE'];
        $LAST_CONS =$row['LAST_CONS'];
        $NUM_ROWS =$row['NUM_ROWS'];
        $INPUT_FILE =$row['INPUT_FILE'];
        $NOTE =$row['NOTE'];
	if ( "$OldPer" != "$ESER_ESAME$MESE_ESAME" ){
	  if ( "$OldPer" != "1" ){
	    ?>
        <tr style="height:2px;">
          <th style="height: 2px !important;" ></th>
          <th style="height: 2px !important;" ></th>
          <th style="height: 2px !important;" ></th>
          <th style="height: 2px !important;" ></th>
          <th style="height: 2px !important;" ></th>
		  <th style="height: 2px !important;" ></th>
          <th style="height: 2px !important;" ></th>
          <th style="height: 2px !important;" ></th>
        </tr>
        <?php    	
	  }
	  $OldPer="$ESER_ESAME$MESE_ESAME";
	}
	
    ?>
      <tr >
        <td><?php echo $ESER_ESAME; ?></td>    
        <td><?php echo $MESE_ESAME; ?></td>  
		<td><?php echo $NOTE; ?></td>     
        <td><?php echo $LAST_CONS; ?></td>  
        <td><?php echo $ID_FILE; ?></td>    
        <td><?php echo $INPUT_FILE; ?></td> 
        <td><?php echo $LOAD_DATE; ?></td>      
		<td><?php echo $NUM_ROWS; ?></td>      
      </tr>
    <?php
	}
    ?>
</table>
</CENTER>
</form>
<SCRIPT>
</SCRIPT>
<?php
    }
    ?>