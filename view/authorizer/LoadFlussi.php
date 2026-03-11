<table class="ExcelTable">
    <tr>
      <th><img class="ImgIco" src="./images/Flusso.png">
		<div style="float:right">
	 <select onchange="loadFlussi('<?php echo $IdWorkFlow; ?>','<?php echo $WorkFlow; ?>',this.value)" id="selectFlusso" name="selectFlusso"  class="selectSearch  FieldMand" style="width:150px;height:30px;">
	 <?php $selected = ($selectFlusso=="")?"selected":false; ?>	
			<option value="" <?php echo $selected; ?>>Filtra Flussi...</option>
		<?php	   foreach ($DatiSelectFlussi as $rowFlusso) { 
		 $IdFlusso=$rowFlusso["ID_FLU"];
      $Flusso=$rowFlusso["FLUSSO"]; 
		$selected=($selectFlusso==$IdFlusso)?"selected":"";
		?>
			<option value="<?php echo $IdFlusso; ?>" <?php echo $selected; ?>><?php echo $Flusso; ?></option>	
		<?php } ?>	
			</select>
	  </div>
	  </th>
      
    </tr><?php
    foreach ($DatiFlussi as $rowFlusso) { 
      $IdFlusso=$rowFlusso["ID_FLU"];
      $Flusso=$rowFlusso["FLUSSO"];     
      ?>
      <tr class="bordertop" > 
         
		  
		  
        <td>
		<?php echo "<b>$Flusso</b>"; ?>
           <div id="ShowFlu_<?php echo $IdWorkFlow.$IdFlusso; ?>" ></div>
		   <?php
		  
		   foreach ($DatiAutorizzazioni[$IdFlusso] as $rwFL) {      
				  $IdAut=$rwFL['ID_AUT'];
				  $Gruppo=$rwFL['GRUPPO'];
				  ?>
				  <div class="Plst Mattone deleteFlusso" onclick="deleteFlusso('<?php echo $IdWorkFlow; ?>',
											   '<?php echo $WorkFlow; ?>',
											   '<?php echo $IdFlusso; ?>',
											   '<?php echo $Flusso; ?>',
											   '<?php echo $IdAut; ?>'
											   ,'RAUT')" id="PulRemGroup<?php echo $IdAut; ?>" class="Plst Mattone" >
					<i class="fa-solid fa-trash-can"></i>					
					
					 <?php echo $Gruppo; ?>
				  </div>
				  
		  <?php } ?>
		   
		   <div id="PulRemUser" class="PulRemUser Plst Mattone" >
		   <i onclick="newGroup('<?php echo $IdWorkFlow; ?>',
                    '<?php echo $WorkFlow; ?>',
                    '<?php echo $IdFlusso; ?>',
                    '<?php echo $Flusso; ?>')" id="PulAddGrIn<?php echo $IdWorkFlow.$IdFlusso; ?>" style="float: left;" class="fa-regular fa-user-group"></i>
		 
             <div class="Plst" style="float: left;" onclick="ModAuthDipFlu('<?php echo $IdWorkFlow; ?>','<?php echo $WorkFlow; ?>','<?php echo $Flusso; ?>','<?php echo $IdFlusso; ?>')" >
				<i class="fa-solid fa-pen-to-square ImgIco" title="<?php echo $IdFlusso; ?>"></i>
			</div>
            
          </div>		   
           
        </td>
      </tr>
      <tr id="InsAuthDip<?php echo $IdWorkFlow.$IdFlusso; ?>" hidden ><td colspan=2 ><div id="LoadAutDip<?php echo $IdWorkFlow.$IdFlusso; ?>" ></div>
      </td></tr>
      <?php
    }
    ?>
   
    </table>
	
	<script>
	$(".selectSearch").select2();
	</script>
    

