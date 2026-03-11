
<div id="FormModFlusso" class="dip_F" >
<input type="hidden" name="Azione" id="Azione"  value="<?php echo $Azione; ?>" >
<input type="hidden" name="TipoDip" id="TipoDip" value="Flusso" >
<input type="hidden" name="NomeDip" id="NomeDip" value="<?php echo $TabNome; ?>" >
       
            <table class="TabDip" >
			<tr>
			  <td  class="iconType"><img class="ImgIco" src="./images/Flusso.png" title="<?php echo $IdDip; ?>" />
			</td>
            <tr>
            <tr>
                <td class="TdDip"><label style="width:200px;text-align:left;">Priorita'</label></td>
                <td class="TdDip">
                    <SELECT id="Priorita" Name="Priorita" class="selectSearch ModificaField"   style="width: 100%;" >
                    <?php 
                    for($s=1;$s<=$TotLiv;$s++){
                        ?><option value="<?php echo $s; ?>" <?php if ( "$s" == "$TabPrio" ) { ?>selected<?php } ?> ><?php echo $s; ?></option><?php
                    }
                    ?>
                    </SELECT>
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Flusso</label></td>
                <td class="TdDip">
                    <SELECT id="SELFLUSSO" Name="SELFLUSSO"  style="width: 100%;"  class="selectSearch ModificaField" >
                        <option value="" >..</option>
                        <?php                            
                            foreach ($datiSelectF as $row) {
                                $IdFlusso=$row['ID_FLU'];
                                $SelFlusso=$row['FLUSSO'];
                                $SelIdWorkFlow=$row['ID_WORKFLOW'];
                                ?><option value="<?php echo $IdFlusso; ?>" <?php if ( "$IdFlusso" == "$IdDip" ) { ?>selected<?php } ?> ><?php echo $SelFlusso; ?></option><?php
                            }
                        ?>
                    </SELECT>            
                </td>
            </tr>
			
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Mesi Validita'</label></td>
                <td class="TdDip">
                    <input type="text" id="SELVAL" Name="SELVAL"  style="width: 100%;"  class="ModificaField" value="<?php echo $TabVali; ?>" >         
                </td>
            </tr>
             <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Inizio AnnoMese Validita'</label></td>
                <td class="TdDip">
                    <?php                       
                        $this->renderSelectBox($optiondate,'SELINZVAL',$TabInzVali);                      
                        ?>    
                  </td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Fine AnnoMese Validita'</label></td>
                <td class="TdDip">
                    <?php                 
                        $this->renderSelectBox($optiondate,'SELFINVAL',$TabFinVali);                        
                        ?>
                      </td>
            </tr>
			<tr>
                
               <td>
				<button id="PulMod" onclick="PulModDip(<?php echo $ndialog; ?>);return false;" class="btn AggiungiFlusso">
				<?php if($IdDip){ ?>
				<i class="fa-solid fa-pencil-square-o"> </i> Modifica</button>
				<?php }else{ ?>
				<i class="fa-solid fa-plus-circle"> </i> Aggiungi</button>
				<?php } ?>
				</td>
            </tr>
            </table>
        </div>
       