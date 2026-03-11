<FORM id="FormModificaWF" method="POST" >
	<div id="FormModWfs" >
            <input type="hidden" id="IdTeam" name="IdTeam" value="<?php echo $IdTeam; ?>" >
            <input type="hidden" id="IdWorkFlow" name="IdWorkFlow" value="<?php echo $IdWorkFlow; ?>" >
	<div id="iconModWfs" >	       
		   <?php
		    if ( $IdWorkFlow != "" ){
	          ?><i class="fa-solid fa-pen"></i><?php
	        }else{
			  ?><i class="fa-solid fa-plus"></i><?php
			}
	        ?>
	</div>
        	<div id="ShowAggiungiAmb" >
				<div><label>WorkFlow</label></div>
				<div><input type="text" id="InpWorkFlow" Name="InpWorkFlow" style="width:100%" value="<?php echo $TabWorkFlow; ?>"  class="ModificaField" <?php if ( $TServer == "PROD USER" ){ ?> readonly <?php } ?>/></div>
				<div><label for="InpDescr">Descrizione</label></div>
				<div><input type="text" id="InpDescr" Name="InpDescr"  style="width:100%" value="<?php echo $TabDescr; ?>"  class="ModificaField" <?php if ( $TServer == "PROD USER" ){ ?> readonly <?php } ?> /></div>
				<div><label for="InpFreq">Frequenza</label></div>
				<div>
				
				      <select data-live-search="true" id="InpFreq" name="InpFreq"  class="selectpicker ModificaField" >
                         <option value="M" <?php if ( $TabFreq == "M" ) { ?>selected<?php } ?> >Mensile</option>
                         <option value="Q" <?php if ( $TabFreq == "Q" ) { ?>selected<?php } ?> >Trimestrale</option>
                         <option value="A" <?php if ( $TabFreq == "A" ) { ?>selected<?php } ?> >Annuale</option>
				      </select>
				</div>
				<div><label for="InpMulti">Multi Processo</label></div>
				<div>
				      <select data-live-search="true" id="InpMulti" name="InpMulti"  class="selectpicker ModificaField" >
				         <option value="N" <?php if ( $TabMulti == "N" ) { ?>selected<?php } ?> >No</option>
				   	     <option value="S" <?php if ( $TabMulti == "S" ) { ?>selected<?php } ?> >Si</option>
				      </select>
				</div>
				<div>
				<?php
		       
				   ?>
				 <label for="InpReadOnly">ReadOnly</label></div>
				<div>
				    <select data-live-search="true" id="InpReadOnly" name="InpReadOnly" class="selectpicker ModificaField" >
				          <option value="N" <?php if ( $TabReadOnly == "N" ) { ?>selected<?php } ?> >No</option>
				    	  <option value="S" <?php if ( $TabReadOnly == "S" ) { ?>selected<?php } ?> >Si</option>
				    </select>
				</div>
	
				 <label for="InOpenAuto">Open Auto</label></div>
				<div>
				    <select data-live-search="true" id="InOpenAuto" name="InOpenAuto" class="selectpicker ModificaField" >
				     	 <option value="Y" <?php if ( $TabOpenAuto == "Y" ) { ?>selected<?php } ?> >Si</option>   
						 <option value="N" <?php if ( $TabOpenAuto == "N" ) { ?>selected<?php } ?> >No</option>				    	  
				    </select>
				</div>

				<label for="InOpenMese">Open Mese</label></div>
				<div>
				    <select data-live-search="true" id="InOpenMese" name="InOpenMese" class="selectpicker ModificaField" >
				          <option value=""  >..</option>
				          <option value="1" <?php if ( $TabOpenAuto == "Y"  || $TabOpenAuto == "" || $TabOpenMese == "1" ) { ?>selected<?php } ?> >1</option>
				    	  <option value="6" <?php if ( $TabOpenMese == "6" ) { ?>selected<?php } ?> >6</option>
				    	  <option value="12" <?php if ( $TabOpenMese == "12" ) { ?>selected<?php } ?> >12</option>
				    </select>
				</div>

				<label for="InOpenGiorno">Open Giorno</label></div>
				<div>
				    <select data-live-search="true" id="InOpenGiorno" name="InOpenGiorno" class="selectpicker ModificaField" >
				          <option value=""  >..</option>
				          <option value="1" <?php if ( $TabOpenAuto == "Y"  || $TabOpenAuto == "" || $TabOpenGiorno == "1" ) { ?>selected<?php } ?> >1</option>
				    	  <option value="2" <?php if ( $TabOpenGiorno == "2" ) { ?>selected<?php } ?> >2</option>
				    	  <option value="20" <?php if ( $TabOpenGiorno == "20" ) { ?>selected<?php } ?> >20</option>
				    </select>
				</div>				
				<br/>
				<div>
  				 <input type='hidden' name='TabWorkFlow' id='TabWorkFlow' value='<?php echo $TabWorkFlow; ?>' >
				  <input type='hidden' name='TabMulti' id='TabMulti' value='<?php echo $TabMulti; ?>' >
				  <input type='hidden' name='TabFreq' id='TabFreq' value='<?php echo $TabFreq; ?>' >				  
				  <input type='hidden' name='TabOpenAuto' id='TabOpenAuto' value='<?php echo $TabOpenAuto; ?>' >	
				  <input type='hidden' name='TabOpenMese' id='TabOpenMese' value='<?php echo $TabOpenMese; ?>' >	
				  <input type='hidden' name='TabOpenGiorno' id='TabOpenGiorno' value='<?php echo $TabOpenGiorno; ?>' >	
				 <input type='hidden' name='TabReadOnly' id='TabReadOnly' value='<?php echo $TabReadOnly; ?>' >		
				  <input type='hidden' name='TabDescr' id='TabDescr' value='<?php echo $TabDescr; ?>' >	
				<?php					
		        if ( $IdWorkFlow != "" ){
	              ?>				  
				  <button id="editworkflow" onclick="modificaWF('<?php echo $IdWorkFlow; ?>');return false;" class="btn">
				 <i class="fa-solid fa-pencil-square-o" aria-hidden="true"> </i> Modifica WF</button>
				  <input type='hidden' name='tipoAzione' id='tipoAzione' value='Modifica' >
				  <input type='hidden' name='Azione' id='Azione' value='Modifica' >					
				  <?php
				}else{
				  ?>
				  <button id="PulAggiungiWF" onclick="modificaWF('<?php echo $IdWorkFlow; ?>');return false;" class="btn">
					<i class="fa-solid fa-plus-circle"> </i> Aggiungi WF</button>
					  <input type='hidden' name='tipoAzione' id='tipoAzione' value='Aggiungi' >
				  	  <input type='hidden' name='Azione' id='Azione' value='Aggiungi' >
				  <?php
				}
				?>			
			</div>
	</div>
	</div>
	</FORM>
	
