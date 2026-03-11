<form id="FormAddCheck"> 
    <input type="hidden" name="ID_LOAD_ANAG" id="ID_LOAD_ANAG" value="<?php echo $ID_LOAD_ANAG; ?>" >
	<input type="hidden" name="ID_LOAD_CHECK" id="ID_LOAD_CHECK" value="<?php echo $ID_LOAD_CHECK; ?>" >
	
	
    <table class="TabDip">
		
            <tr>
                <td class="TdDip"><label style="width:200px;text-align:left;">LABEL</label></td>
                <td class="TdDip">
                <input type="text" id="LABEL" Name="LABEL" class="ModificaField" value="<?php echo $datiCheck[0]['LABEL']; ?>" > 
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >TEST</label></td>
                <td class="TdDip">                
                <textarea id="TEST" name="TEST" rows="2" cols="40" style="width: 100%;"><?php echo $datiCheck[0]['TEST']; ?></textarea>
                </td>
            </tr>
			
                     
			<tr>
                
               <td>
				<button id="PulMod" onclick="modCheck();return false;" class="btn AggiungiFlusso">
				<?php if($ID_LOAD_CHECK){ ?>
				<i class="fa-solid fa-pencil-square-o"> </i> Modifica</button>
				<?php }else{ ?>
				<i class="fa-solid fa-floppy-disk"> </i> Salva</button>
				<?php } ?>
				</td>
            </tr>
</table>
</div>
  </form>