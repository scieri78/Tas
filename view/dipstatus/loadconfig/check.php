	<FORM id="FormCheck" method="POST" >
	<input type="hidden" id="ID_LOAD_ANAG" name="ID_LOAD_ANAG" value="<?php echo $ID_LOAD_ANAG; ?>" />

<div id="azioni">
<button onclick="AggiungiCheck(<?php echo $ID_LOAD_ANAG; ?>);return false;" id="AggDipIN<?php echo $ID_LOAD_ANAG; ?>" class="AggiungiFlusso btn"><i class="fa-solid fa-plus-circle"> </i> Check</button>
</div>
</div>	 

      <table id='idTabedlla' class="display dataTable">
            <thead class="headerStyle">
              <tr>
				<th>LABEL</th>
                <th class="thModTest">TEST</th>
                <th>METODO</th>
                <th>ATTESO</th>
                <th class="thModImgIco">MODIFICA</th>
                <th class="thModImgIco">ELIMINA</th>				
              </tr>
            </thead>
	<tbody>
		<?php 
	//	print_r($datiFlusso);

		foreach ($datiCheck as $rowLG) {
			$LABEL=$rowLG['LABEL'];
			$TEST=$rowLG['TEST'];
			$METODO=$rowLG['METODO'];
			$ATTESO=$rowLG['ATTESO'];
			$ID_LOAD_CHECK=$rowLG['ID_LOAD_CHECK'];
		
		?>
		<tr>				   
		<td class="thLiv"><?php echo $LABEL;?></td>
		<td class="thLiv"><?php echo $TEST;?></td>
		<td class="thLiv"><?php echo $METODO;?></td>
		<td class="thLiv"><?php echo $ATTESO;?></td>		
		<td class="thLiv"><div id="ModDett908" class="Plst" onclick="AggiungiCheck(<?php echo $ID_LOAD_ANAG; ?>,<?php echo $ID_LOAD_CHECK;?>);return false;">
		<i class="fa-solid fa-pen-to-square"></i></div></td>		
		<td class="thLiv">
		<div id="DelDett908" class="Plst" onclick="delCheck(<?php echo $ID_LOAD_ANAG; ?>,<?php echo $ID_LOAD_CHECK;?>);return false;">
		<i class="fa-solid fa-trash-can"></i></div>
		</td>

		</tr>
		<?php } ?>
	</tbody>	
</table>
</form>


