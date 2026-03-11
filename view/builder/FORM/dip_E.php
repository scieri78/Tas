<div id="FormModFlusso" class="dip_E" >
<input type="hidden" name="Azione" id="Azione" value="<?php echo $Azione; ?>" >
<input type="hidden" name="TipoDip" id="Azione" value="Elaborazione" >
<input type="hidden" name="NomeDip" id="NomeDip" value="<?php echo $TabNome; ?>" >

            <table class="TabDip" >
			<tr>
			  <td  class="iconType"><img class="ImgIco" src="./images/Elaborazione.png" title="<?php echo $IdDip; ?>" />
			</td>
            <tr>
                <td class="TdDip"><label style="width:200px;text-align:left;">Priorita'</label></td>
                <td class="TdDip">
                    <SELECT id="Priorita" Name="Priorita"  class="selectSearch ModificaField"  style="width: 100%;" >
                    <?php 
                    for($s=1;$s<=$TotLiv;$s++){
                        ?><option value="<?php echo $s; ?>" <?php if ( "$s" == "$TabPrio" ) { ?>selected<?php } ?> ><?php echo $s; ?></option><?php
                    }
                    ?>
                    </SELECT>
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Shell</label></td>
                <td class="TdDip">
                   
                            <SELECT id="SELELABORAZIONE" Name="SELELABORAZIONE" style="width: 100%;"  class="selectSearch ModificaField">
                                <option value="" >..</option>
                                <option value="0" <?php if (isset($TabSh) && "0" == "$TabSh" ) { ?>selected<?php } ?> >----- TEST ----</option>
                                <?php                                 
                                foreach ($datiSelectE as $row) {
                                    $IdSh=$row['ID_SH'];
                                    $ShellName=$row['SHELL'];
                                    ?><option value="<?php echo $IdSh; ?>" <?php if ( $IdSh == $TabSh ){ ?>selected<?php } ?> ><?php echo $ShellName; ?></option><?php
									}
                                ?>
                            </SELECT>                                         
                </td>
            </tr> 
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Nome</label></td>
                <td class="TdDip"><input type="text" id="NomeEla" Name="NomeEla" style="width: 100%;" class="ModificaField" value="<?php echo $TabNome; ?>" /></td>
            </tr>
            <tr hidden >
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Tags</label></td>
                <td class="TdDip"><input type="text" id="Tags" Name="Tags" style="width: 100%;" class="ModificaField" value="<?php echo $TabTags; ?>" /></td>
            </tr>           
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Descrizione</label></td>
                <td class="TdDip"><textarea type="text" id="DescrEla" Name="DescrEla" style="width: 100%;"  class="ModificaField" ><?php echo $TabDesc; ?></textarea></td>
            </tr> 
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Parametri</label></td>
                <td class="TdDip">
                    <input type="text" id="Parametri" Name="Parametri"  style="width: 100%;"  class="ModificaField" value="<?php echo $TabParametri; ?>" >         
                </td>
            </tr>      
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >ReadOnly</label></td>
                <td class="TdDip">
                    <select id="ReadOnly" name="ReadOnly" class="ModificaField"  >
                      <option value="N" <?php if ( "$TabROnly" == "N" ){ ?>selected<?php } ?> >No</option>
                      <option value="S" <?php if ( "$TabROnly" == "S" ){ ?>selected<?php } ?> >Si</option>
                    </select>
                </td>
            </tr>
			<tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Possibilità di non eseguire</label></td>
                <td class="TdDip">
                    <SELECT id="SaltaElab" Name="SaltaElab" style="width: 100%;"   class="ModificaField"  >
                        <option value="N" <?php if ( "$TabSalta" == "N" ){ ?>selected<?php } ?>>No (obbligatorio Eseguire)</option>
                        <option value="S" <?php if ( "$TabSalta" == "S" ){ ?>selected<?php } ?>>Si</option>
                    </SELECT>            
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Show Processing</label></td>
                <td class="TdDip">
                    <select id="ShowProc" name="ShowProc" >
                      <option value="N" <?php if ( "$TabShowProc" == "N" ){ ?>selected<?php } ?>>No</option>
                      <option value="Y" <?php if ( "$TabShowProc" == "Y" ){ ?>selected<?php } ?>>Si</option>
                    </select>
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
		
