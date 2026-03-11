<center>
<button onclick="refreshLinkInterni();return false;" id="refresh" class="btn"><i class="fa-solid fa-refresh"> </i> REFRESH</button>	
</center>
<br>
<table  id='idTabella' class="display dataTable">
	<thead class="headerStyle">
<tr>
  <th class="SalvaConf" >CONSOLIDA</th>
  <th>IdFile</th>
  <th>Load</th>
  <th>Cons</th>
   <th>Mb</th>
   <th>Rows</th>
   <th>File</th>
</tr>
</thead>
	<tbody>
<?php 

foreach ($datiGiroRIASViva as $rowT) {
    $T_ID_FILE=$rowT['ID_FILE'];
	  $T_LOAD_DATE=$rowT['LOAD_DATE'];
    $T_CONS_DATE=$rowT['CONS_DATE'];
    $T_LAST_CONS=$rowT['LAST_CONS'];
    $T_SIZE_FILE=$rowT['SIZE_FILE'];
    $T_NUM_ROWS=$rowT['NUM_ROWS'];
    $T_INPUT_FILE=$rowT['INPUT_FILE'];
    $T_ARCHIVE=$rowT['ARCHIVE'];
    $T_NOTE=$rowT['NOTE'];
    $T_TAGS=$rowT['TAGS'];
	$cnt=$cnt+1; 
	if ( $T_LAST_CONS == "Y" ){ $T_SEL_CONS=$T_ID_FILE; }
    ?>
	<tr>
      <td class="SalvaConf" ><input type="radio" name="SelCons" class="SelCons" value="<?php echo $T_ID_FILE; ?>" <?php if ( "$T_LAST_CONS" == "Y" ){ ?>checked<?php }?> ></td>
      <td <?php if ( "$T_LAST_CONS" == "Y" ){ ?>class="FileCons"<?php }?> ><?php echo "$T_ID_FILE"; ?></td>
	  <td <?php if ( "$T_LAST_CONS" == "Y" ){ ?>class="FileCons"<?php }?> ><?php echo "$T_LOAD_DATE"; ?></td>
      <td <?php if ( "$T_LAST_CONS" == "Y" ){ ?>class="FileCons"<?php }?> ><?php echo "$T_CONS_DATE"; ?></td>
      <td <?php if ( "$T_LAST_CONS" == "Y" ){ ?>class="FileCons"<?php }?> ><?php echo round($T_SIZE_FILE/1024/1024,2); ?></td>
      <td <?php if ( "$T_LAST_CONS" == "Y" ){ ?>class="FileCons"<?php }?> ><?php echo "$T_NUM_ROWS"; ?></td>
      <td <?php if ( "$T_LAST_CONS" == "Y" ){ ?>class="FileCons"<?php }?> ><?php echo "$T_INPUT_FILE"; ?></td>
	</tr>
	<?php
}

?>
			</tbody>
      </table>
<?php if(!empty($datiGiroRIASViva)){ ?>
<br><br>
<center>
<button onclick="consolidaGiro();return false;" id="ConsFile" class="btn"><i class="fa-regular fa-floppy-disk"></i> Consolida File</button>	
<button class="SalvaConf btn" onclick="validalegame();return false;" id="validaGiro" class="btn"><i class="fa-regular fa-floppy-disk"></i> Valida Dati</button>	
</CENTER>
<?php } ?>
<script>

  
  function TestConsFile(){
 
	var vSelx = false;
  $('#validaGiro').hide();
	$('.SelCons').each(function(){      
	  if (  $(this).is(':checked') && 
	       $(this).val() == '<?php echo $T_SEL_CONS; ?>'  
	  ){
		vSelx=true;
	  }
	});
	
	if ( vSelx ){
	  $('#ConsFile').hide();
	  $('#validaGiro').show();
	}else{
	  $('#ConsFile').show();
    $('#validaGiro').hide();
	}
  };
  
  
  $('.SelCons').change(function(){ TestConsFile(); });
  
  TestConsFile();
   
	  $('#ConsFile').click(function(){
		  $('#Waiting').show();
	  });


</script>
<script src="./JS/datatable.config.js?p=<?php echo rand(1000,9999);?>"></script>