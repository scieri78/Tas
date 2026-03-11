<div id="FormModFlusso" class="dip_FL" >
<input type="hidden" name="Azione" id="Azione"  value="MFL" >
<input type="hidden" name="TipoDip" id="TipoDip" value="Flusso" >
<input type="hidden" name="NomeDip" id="NomeDip" value="<?php echo $TabNome; ?>" >
             <table class="TabDip" >
			<tr>
			  <td  class="iconType"><img class="ImgIco" src="./images/Flusso.png" title="<?php echo $IdDip; ?>" />
			</td>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Livello</label></td>
                <td class="TdDip">
				<input name="LivPersAuto" id="LivPersAuto" type="checkbox" <?php if (!isset($LivPers) || $LivPers == "0" ){ ?> checked <?php } ?> value="1" >Auto
				<input name="LivPersNum" id="LivPersNum" type="number"  value="<?php echo $TabLiv; ?>" hidden >
				<input name="TabPersNum" id="TabPersNum" type="hidden"  value="<?php echo $TabLiv; ?>" readonly >
				</td>
            </tr>			
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Nome</label></td>
                <td class="TdDip"><input onblur="SpacesToUnderscore(this)" type="text" id="NomeFlu" Name="NomeFlu" style="width: 100%;" class="ModificaField" value="<?php echo $TabNome; ?>" /></td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Descrizione</label></td>
                <td class="TdDip"><textarea type="text" id="DescrFlu" Name="DescrFlu"  style="width: 100%;" class="ModificaField" ><?php echo $TabDesc; ?></textarea></td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Comportamento con periodo Consolidato</label></td>
                <td class="TdDip">
				<select id="BlockConsFlu" Name="BlockCons"  class="ModificaField" >
				<option value="N" <?php if ( "$TabBlockCons" == "N" ){ ?> selected <?php } ?> > Disabilitato con periodo Consolidato </option>
				<option value="S" <?php if ( "$TabBlockCons" == "S" ){ ?> selected <?php } ?> > Abilitato con periodo Consolidato </option>
				<option value="X" <?php if ( "$TabBlockCons" == "X" ){ ?> selected <?php } ?> > Abilitato senza svalidazione con periodo Consolidato </option>
				<option value="O" <?php if ( "$TabBlockCons" == "O" ){ ?> selected <?php } ?> > Sempre Abilitato </option>
				</select>
				</td>
            </tr>
			
            <tr>
              
              <td>
				<button id="PulMod" onclick="PulModDip(<?php echo $ndialog; ?>);return false;" class="btn AggiungiFlusso">
				<i class="fa-solid fa-pencil-square-o"> </i> Modifica</button>
				</td>
            </tr>
            </table>
        </div>
       <script>
           
			$('#LivPersAuto').change(function(){
			  if ( $('#LivPersAuto').prop("checked") == true ){ $('#LivPersNum').hide(); }else{ $('#LivPersNum').show();}
			});
			if ( $('#LivPersAuto').prop("checked") == true ){ $('#LivPersNum').hide(); }else{ $('#LivPersNum').show();}
        </script>