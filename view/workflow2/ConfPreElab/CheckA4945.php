<center>
<button onclick="refreshLinkInterni();return false;" id="refresh" class="btn"><i class="fa-solid fa-refresh"> </i> REFRESH</button>	
</center>
<BR>
<CENTER><B>Stato Elaborazioni:</B></CENTER>
<table class="ExcelTable" style="width:auto;margin:auto;left:0;right:0;">
	<tr>  
		 <th>STATO</th>     
		 <th>NUM PACCHETTI</th>     
		 <th>PERC</th>     
	</tr>
	<?php		
	foreach ($datiStatoElaborazioniDaIniziare as $row ) {
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
	foreach ($datiStatoElaborazioniInElaborazione as $row) {
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
	foreach ($datiStatoElaborazioniInErrore as $row) {
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
		<td  <?php if ( strpos("$STATO", "Err")>0 and "$CNT">0 ) { ?>  style="color:Red;" <?php } ?> >In Errore<?php if ( strpos("$STATO", "Err")>0 and "$CNT">0 ) { ?>  <img  style="width:15px;" src="./images/Attention.png" > <?php } ?></td>  
		<td <?php if ( strpos("$STATO", "Err")>0 and "$CNT">0 ) { ?>  style="color:Red;" <?php } ?> ><?php echo $CNTTOT; ?></td>    
		<td <?php if ( strpos("$STATO", "Err")>0 and "$CNT">0 ) { ?>  style="color:Red;" <?php } ?> ><?php echo $PERCTOT; ?> %</td>	
	  </tr>
	<?php
	foreach ($datiElaborazioniInCorso as $row) {
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
	
	foreach ($datiElaborazioniInCorso as $row) {
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
	
	foreach ($datiElaborazioniInErrore as $row) {
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