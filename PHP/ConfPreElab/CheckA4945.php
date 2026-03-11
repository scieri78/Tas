
<style>

.Titolo{
    position:relative;
    left:0;
    right:0;
    margin:auto;
    font-size:20px;
    width:100%;
}
#OpenLinkPage{
	height:500px;
}

</style>
<center><div class="Bottone" onclick="ReloadLink()" >REFRESH</div></center>
<BR>
<CENTER><B>Stato Elaborazioni:</B></CENTER>
<table class="ExcelTable" style="width:auto;margin:auto;left:0;right:0;">
	<tr>  
		 <th>STATO</th>     
		 <th>NUM PACCHETTI</th>     
		 <th>PERC</th>     
	</tr>
	<?php	
	
	$sql="
WITH W_STATI(priorita, stato, descr) AS (values
(0  ,'Inizio-OK', 'Stato iniziale')
)
SELECT w.STATO, DESCR, NVL(CNT,0) CNT, (SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3) TOT, DECIMAL(NVL(CNT,0)/(SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3),5,2)*100 PERC
FROM W_STATI w
LEFT OUTER JOIN (SELECT STATO, COUNT(*) CNT FROM TASPCWRK.WORK_ELAB.PILOTA3 GROUP BY STATO ) p ON w.STATO = p.STATO
WHERE RIGHT(W.STATO,3)<>'Err' OR (RIGHT(W.STATO,3)='Err' AND CNT >0 )
ORDER BY PRIORITA;";
	$stmt=db2_prepare($conn, $sql);
	$result=db2_execute($stmt);
    if ( ! $result) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
	while ($row = db2_fetch_assoc($stmt)) {
		$STATO =$row['STATO'];
		$DESCR =$row['DESCR'];
		$CNT   =$row['CNT'];
		$TOT  =$row['TOT'];
		$PERC  =$row['PERC'];
	  ?>
	  <tr>
		<td >Da Iniziare</td>  
		<td ><?php echo "$CNT/$TOT"; ?></td>    
		<td ><?php echo $PERC; ?> %</td>
	  </tr>
	  <?php
	}
	?>
<?php	
	
	$sql="
WITH W_STATI(priorita, stato, descr) AS (values
(2  ,'SIN-OK', 'P_POPOLA_SIN in elaborazione'),
(4  ,'MOV-OK', 'P_POPOLA_MOV_CONT in elaborazione'),
(6  ,'CPN-OK', 'P_POPOLA_CPN in elaborazione'),
(7  ,'AGG-SIN-OK', 'AGG_POST_SIN in elaborazione')
)
SELECT w.STATO, DESCR, NVL(CNT,0) CNT, (SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3) TOT, DECIMAL(NVL(CNT,0)/(SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3),5,2)*100 PERC
FROM W_STATI w
LEFT OUTER JOIN (SELECT STATO, COUNT(*) CNT FROM TASPCWRK.WORK_ELAB.PILOTA3 GROUP BY STATO ) p ON w.STATO = p.STATO
WHERE RIGHT(W.STATO,3)<>'Err' OR (RIGHT(W.STATO,3)='Err' AND CNT >0 )
ORDER BY PRIORITA;";
	$stmt=db2_prepare($conn, $sql);
	$result=db2_execute($stmt);
	$CNTTOT=0;
	$PERCTOT=0;	
    if ( ! $result) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
	while ($row = db2_fetch_assoc($stmt)) {
		$STATO =$row['STATO'];
		$DESCR =$row['DESCR'];
		$CNT   =$row['CNT'];
		$TOT  =$row['TOT'];
		$PERC  =$row['PERC'];
		$CNTTOT=$CNTTOT+$CNT;
		$PERCTOT=$PERCTOT+$PERC;
	}
	?>
	  <tr>
		<td >In Elaborazione</td>  
		<td ><?php echo "$CNTTOT"; ?></td>    
		<td ><?php echo $PERCTOT; ?> %</td>
	  </tr>
	<?php
	
	$sql="
WITH W_STATI(priorita, stato, descr) AS (values
(2  ,'SIN-Err', 'P_POPOLA_SIN in errore'),
(4  ,'MOV-Err', 'P_POPOLA_MOV_CONT in errore'),
(6  ,'CPN-Err', 'P_POPOLA_CPN in errore'),
(7  ,'AGG-SIN-Err', 'AGG_POST_SIN in errore')
)
SELECT w.STATO, DESCR, NVL(CNT,0) CNT, (SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3) TOT, DECIMAL(NVL(CNT,0)/(SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3),5,2)*100 PERC
FROM W_STATI w
LEFT OUTER JOIN (SELECT STATO, COUNT(*) CNT FROM TASPCWRK.WORK_ELAB.PILOTA3 GROUP BY STATO ) p ON w.STATO = p.STATO
WHERE RIGHT(W.STATO,3)<>'Err' OR (RIGHT(W.STATO,3)='Err' AND CNT >0 )
ORDER BY PRIORITA;";
	$stmt=db2_prepare($conn, $sql);
	$result=db2_execute($stmt);
	$CNTTOT=0;
	$PERCTOT=0;
    if ( ! $result) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
	while ($row = db2_fetch_assoc($stmt)) {
		$STATO =$row['STATO'];
		$DESCR =$row['DESCR'];
		$CNT   =$row['CNT'];
		$TOT  =$row['TOT'];
		$PERC  =$row['PERC'];
		$CNTTOT=$CNTTOT+$CNT;
		$PERCTOT=$PERCTOT+$PERC;
	}
	?>
	  <tr>	
		<td  <?php if ( strpos("$STATO", "Err")>0 and "$CNT">0 ) { ?>  style="color:Red;" <?php } ?> >In Errore<?php if ( strpos("$STATO", "Err")>0 and "$CNT">0 ) { ?>  <img  style="width:15px;" src="../images/Attention.png" > <?php } ?></td>  
		<td <?php if ( strpos("$STATO", "Err")>0 and "$CNT">0 ) { ?>  style="color:Red;" <?php } ?> ><?php echo $CNTTOT; ?></td>    
		<td <?php if ( strpos("$STATO", "Err")>0 and "$CNT">0 ) { ?>  style="color:Red;" <?php } ?> ><?php echo $PERCTOT; ?> %</td>	
	  </tr>
	<?php
	
	$sql="
WITH W_STATI(priorita, stato, descr) AS (values
(7  ,'AGG-MOV-OK', 'AGG_POST_SIN in elaboraziohne')
)
SELECT w.STATO, DESCR, NVL(CNT,0) CNT, (SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3) TOT, DECIMAL(NVL(CNT,0)/(SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3),5,2)*100 PERC
FROM W_STATI w
LEFT OUTER JOIN (SELECT STATO, COUNT(*) CNT FROM TASPCWRK.WORK_ELAB.PILOTA3 GROUP BY STATO ) p ON w.STATO = p.STATO
WHERE RIGHT(W.STATO,3)<>'Err' OR (RIGHT(W.STATO,3)='Err' AND CNT >0 )
ORDER BY PRIORITA;";
	$stmt=db2_prepare($conn, $sql);
	$result=db2_execute($stmt);
	$CNTTOT=0;
	$PERCTOT=0;	
    if ( ! $result) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
	while ($row = db2_fetch_assoc($stmt)) {
		$STATO =$row['STATO'];
		$DESCR =$row['DESCR'];
		$CNT   =$row['CNT'];
		$TOT  =$row['TOT'];
		$PERC  =$row['PERC'];
		$CNTTOT=$CNTTOT+$CNT;
		$PERCTOT=$PERCTOT+$PERC;
	}
	?>
	  <tr>  
		<td >Finiti</td>  
		<td ><?php echo "$CNTTOT/$TOT"; ?></td>    
		<td ><?php echo $PERCTOT; ?> %</td>
	  </tr>
	<?php
	?>
</table> 
<BR><BR>
<CENTER><B>Elaborazioni in corso:</B></CENTER>
<table class="ExcelTable" style="width:auto;margin:auto;left:0;right:0;">
	<tr>  
		 <th>STATO</th>   
		 <th>DESCRIZIONE</th>   
		 <th>NUM PACCHETTI</th>     
		 <th>PERC</th>     
	</tr>
	<?php	
	
	$sql="
WITH W_STATI(priorita, stato, descr) AS (values
(2  ,'SIN-OK', 'P_POPOLA_SIN in elaborazione'),
(4  ,'MOV-OK', 'P_POPOLA_MOV_CONT in elaborazione'),
(6  ,'CPN-OK', 'P_POPOLA_CPN in elaborazione'),
(7  ,'AGG-SIN-OK', 'AGG_POST_SIN in elaborazione')
)
SELECT w.STATO, DESCR, NVL(CNT,0) CNT, (SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3) TOT, DECIMAL(NVL(CNT,0)/(SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3),5,2)*100 PERC
FROM W_STATI w
LEFT OUTER JOIN (SELECT STATO, COUNT(*) CNT FROM TASPCWRK.WORK_ELAB.PILOTA3 GROUP BY STATO ) p ON w.STATO = p.STATO
WHERE RIGHT(W.STATO,3)<>'Err' OR (RIGHT(W.STATO,3)='Err' AND CNT >0 )
ORDER BY PRIORITA;";
	$stmt=db2_prepare($conn, $sql);
	$result=db2_execute($stmt);
    if ( ! $result) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
	while ($row = db2_fetch_assoc($stmt)) {
		$STATO =$row['STATO'];
		$DESCR =$row['DESCR'];
		$CNT   =$row['CNT'];
		$TOT  =$row['TOT'];
		$PERC  =$row['PERC'];
	  ?>
	  <tr>
		<td <?php if ( strpos("$STATO", "Err")>0 and "$CNT">0 ) { ?>  style="color:Red;" <?php } ?> ><?php echo $STATO; ?></td>  
		<td <?php if ( strpos("$STATO", "Err")>0 and "$CNT">0 ) { ?>  style="color:Red;" <?php } ?> ><?php echo $DESCR; ?></td>  
		<td <?php if ( strpos("$STATO", "Err")>0 and "$CNT">0 ) { ?>  style="color:Red;" <?php } ?> ><?php echo $CNT;
if("$STATO"=="AGG-MOV-OK" Or "$STATO"=="Inizio-OK") { echo "/$TOT";} ?></td>    
		<td <?php if ( strpos("$STATO", "Err")>0 and "$CNT">0 ) { ?>  style="color:Red;" <?php } ?> ><?php echo $PERC; ?> %</td>
	  </tr>
	  <?php
	}
	?>
</table> 

<BR><BR>
<CENTER><B>Elaborazioni in ERRORE:</B></CENTER>
<table class="ExcelTable" style="width:auto;margin:auto;left:0;right:0;">
	<tr>
		 <th>STATO</th>   
		 <th>DESCRIZIONE</th>   
		 <th>NUM PACCHETTI</th>     
		 <th>PERC</th>     
	</tr>
	<?php	
	
	$sql="
WITH W_STATI(priorita, stato, descr) AS (values
(2  ,'SIN-Err', 'P_POPOLA_SIN in errore'),
(4  ,'MOV-Err', 'P_POPOLA_MOV_CONT in errore'),
(6  ,'CPN-Err', 'P_POPOLA_CPN in errore'),
(8  ,'AGG-SIN-Err', 'AGG_POST_SIN in errore'),
(9  ,'AGG-MOV-Err', 'Stato finale in errore')
)
SELECT w.STATO, DESCR, NVL(CNT,0) CNT, (SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3) TOT, DECIMAL(NVL(CNT,0)/(SELECT COUNT(1) FROM TASPCWRK.WORK_ELAB.PILOTA3),5,2)*100 PERC
FROM W_STATI w
LEFT OUTER JOIN (SELECT STATO, COUNT(*) CNT FROM TASPCWRK.WORK_ELAB.PILOTA3 GROUP BY STATO ) p ON w.STATO = p.STATO
WHERE RIGHT(W.STATO,3)<>'Err' OR (RIGHT(W.STATO,3)='Err' AND CNT >0 )
ORDER BY PRIORITA;";
	$stmt=db2_prepare($conn, $sql);
	$result=db2_execute($stmt);
    if ( ! $result) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
	while ($row = db2_fetch_assoc($stmt)) {
		$STATO =$row['STATO'];
		$DESCR =$row['DESCR'];
		$CNT   =$row['CNT'];
		$TOT  =$row['TOT'];
		$PERC  =$row['PERC'];
	  ?>
	  <tr>
		<td style="color:Red;"><?php echo $STATO; ?></td>  
		<td style="color:Red;"><?php echo $DESCR; ?></td>  
		<td style="color:Red;"><?php echo $CNT;  ?></td>    
		<td style="color:Red;"><?php echo $PERC; ?> %</td>
	  </tr>
	  <?php
	}
	?>
</table> 
<BR>