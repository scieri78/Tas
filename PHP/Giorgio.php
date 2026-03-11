
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
    ?>
<form id="FormSelectRete" method="POST">

<center><div class="Titolo" >SAPBW FILE</div></center>
<table width="100%" class="ExcelTable" >    
<tr><td style="width: 30px;" ><input type="submit" value="REFRESH" class="Bottone" style="background:yellow;" ></td>
</table>
<table width="100%" class="ExcelTable" >
    <tr>
         <th>ESER_ESAME</th>
         <th>MESE_ESAME</th>
         <th>TAGS</th>
         <th>START_TIME</th>
         <th>END_TIME</th>
         <th>VARIABLES</th>  
         <th>ID_FILE</th> 
         <th>INPUT_FILE</th>
    </tr>
    <?php    
    $sql="SELECT ESER_ESAME, 
        MESE_ESAME, 
        TAGS, 
        START_TIME, 
        END_TIME, 
        VARIABLES, 
        ID_FILE, 
        INPUT_FILE 
FROM LOSS_RESERVING.V_SAPBW_FILE
ORDER BY ESER_ESAME desc, MESE_ESAME desc, TAGS desc;
    ";
    #echo $sql;
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    if ( ! $result) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
    while ($row = db2_fetch_assoc($stmt)) {
        $ESER_ESAME =$row['ESER_ESAME'];
        $MESE_ESAME =$row['MESE_ESAME'];
        $TAGS       =$row['TAGS'];
        $START_TIME =$row['START_TIME'];
        $END_TIME   =$row['END_TIME'];
        $VARIABLES  =$row['VARIABLES'];
        $ID_FILE    =$row['ID_FILE'];
        $INPUT_FILE =$row['INPUT_FILE'];
    ?>
      <tr>
        <td><?php echo $ESER_ESAME; ?></td>    
        <td><?php echo $MESE_ESAME; ?></td>    
        <td><?php echo $TAGS; ?></td>    
        <td><?php echo $START_TIME; ?></td>    
        <td><?php echo $END_TIME; ?></td>    
        <td><?php echo $VARIABLES; ?></td>    
        <td><?php echo $ID_FILE; ?></td>    
        <td><?php echo $INPUT_FILE; ?></td>    
      </tr>
    <?php
	}
    ?>
</table>
</form>
<SCRIPT>
</SCRIPT>
<?php
    }
    ?>