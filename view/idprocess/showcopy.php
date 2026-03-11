<div class="rowIdProcess">
			<button id="refreshButtom" onclick="showDetTable();return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-refresh"> </i> Refresh</button>
</div >
     <div id="ShowTab" >
     <table id="idTabella" class="display dataTable">
     <thead class="headerStyle">
     <tr>
	   <th>FROM</th>
       <th>ID_PROCESS</th>
       <th>TABSCHEMA</th>
       <th>TABNAME</th>
       <th>PART</th>
       <th>DATA_FOUND</th>
	   <th>DIFF</th>
       <th>INSERT</th>
	   <th>START</th>
	   <th>END</th>
	   <th>ESITO</th>
     </tr>
	 </thead>
	 <tbody>
     <?php 

	 $TOTDIFF=0;
     foreach ($DatiTable as $row) {
        $FROM_ID_PROCESS=$row['FROM_ID_PROCESS'];
		$ID_PROCESS=$row['ID_PROCESS'];
		$TABSCHEMA=$row['TABSCHEMA'];
        $TABNAME=$row['TABNAME'];
        $FLG_PART=$row['FLG_PART'];
        $FLG_DATA_FOUND=$row['FLG_DATA_FOUND'];
        $TMS_INSERT=$row['TMS_INSERT'];
		$TMS_START_COPY=$row['TMS_START_COPY'];
		$TMS_END_COPY=$row['TMS_END_COPY'];
		$ESITO=$row['ESITO'];
		$DIFF=$row['DIFF'];
        $TOTDIFF=$TOTDIFF+$DIFF;
      ?>
		
      <tr>       
		<td><?php echo $FROM_ID_PROCESS; ?></td>          
        <td><?php echo $ID_PROCESS; ?></td>
        <td><?php echo $TABSCHEMA; ?></td>
        <td><?php echo $TABNAME; ?></td>
        <td><?php echo $FLG_PART; ?></td>
        <td><?php echo $FLG_DATA_FOUND; ?></td>
		<td><?php echo gmdate('H:i:s',$DIFF); ?></td>
        <td><?php echo $TMS_INSERT; ?></td>
		<td><?php echo $TMS_START_COPY; ?></td>
		<td><?php echo $TMS_END_COPY; ?></td>
		<td><?php echo $ESITO; ?></td>
      </tr>
      <?php
     }
     ?>
	  <tr>       
		<td><?php echo $FROM_ID_PROCESS; ?></td>          
        <td><?php echo $ID_PROCESS; ?></td>
        <td><?php echo $TABSCHEMA; ?></td>
        <td></td>
        <td></td>
        <td></td>
		<td><?php echo gmdate('H:i:s',$TOTDIFF); ?></td>
        <td></td>
		<td></td>
		<td></td>
		<td></td>
      </tr>
	  </tbody>
     </table>
     </div>
     


