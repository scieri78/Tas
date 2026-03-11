<div id="FormModFlusso" class="dip_C" >
<input type="hidden" name="Azione" id="Azione"  value="<?php echo $Azione; ?>" >
<input type="hidden" name="TipoDip" id="TipoDip" value="Caricamento" >
<input type="hidden" name="NomeDip" id="NomeDip" value="<?php echo $TabNome; ?>" >

            <table class="TabDip" >
			<tr>
			  <td  class="iconType"><img class="ImgIco" src="./images/Carica.png" title="<?php echo $IdDip; ?>" />
			</td>
            <tr>
            <tr>
                <td class="TdDip"><label style="width:200px;text-align:left;">Priorita'</label></td>
                <td class="TdDip">
                    <SELECT id="Priorita" Name="Priorita" class="selectSearch ModificaField"  style="width: 100%;" >
                    <?php 
                    for($s=1;$s<=$TotLiv;$s++){
                        ?><option value="<?php echo $s; ?>" <?php if ( "$s" == "$TabPrio" ) { ?>selected<?php } ?> ><?php echo $s; ?></option><?php
                    }
                    ?>
                    </SELECT>
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Caricamento</label></td>
                <td class="TdDip">
                            
                            <SELECT  id="SELCARICAMENTO" Name="SELCARICAMENTO" style="width: 100%;"  class="selectSearch ModificaField">
                                <option value="" >..</option>
                                <option <?php if ( $TabInput != "") { ?>selected<?php } ?> value="CarFile" >Caricamento File</option>
                                <option value="WFS_TEST"<?php if ( "WFS_TEST" == $TabInput ) { ?>selected<?php } ?>  >----- TEST ----</option>
                                <?php   
                                  
                                    foreach ($datiSelectC as $row) {
                                        $NomeFlusso=$row['NOME_FLUSSO'];
                                        ?><option value="<?php echo $NomeFlusso; ?>" <?php if ( $NomeFlusso == $TabInput ) { ?>selected<?php } ?> ><?php echo $NomeFlusso; ?></option><?php
                                    }
                                ?>
                                
                            </SELECT> 
                        
                </td>
            </tr>
            <tr class="CarFile" >
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Nome Caricamento File</label></td>
                <td class="TdDip">
                            <input onblur="SpacesToUnderscore(this)" type="text" name="NOMCARICAMENTO" id="NOMCARICAMENTO" style="width: 100%;" value="<?php echo $TabInput; ?>"  >  
                </td>
            </tr>
			<tr class="CarFile" >
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Cartella Dest</label></td>
                <td class="TdDip">
                            <input onblur="SpacesToUnderscore(this)" type="text" name="TXTCARICAMENTO" id="TXTCARICAMENTO" style="width: 100%;" value="<?php echo $TabTDir; ?>"  >  
                </td>
            </tr>
            <tr class="CarFile" >
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Shell Target</label></td>
                <td class="TdDip">
                            <input onblur="SpacesToUnderscore(this)" type="text" name="SHELL_TARGET" id="SHELL_TARGET" style="width: 100%;" value="<?php echo $TabShellTarget; ?>"  >  
                </td>
            </tr>
            <tr class="CarFile" >
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Shell Var</label></td>
                <td class="TdDip">
                            <input type="text" name="SHELL_VAR" id="SHELL_VAR" style="width: 100%;" value="<?php echo $TabShellVar; ?>"  >  
                </td>
            </tr>
            <tr class="CarFile" >
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Target Tab</label></td>
                <td class="TdDip">
                            <input onblur="SpacesToUnderscore(this)" type="text" name="TARGET_TAB" id="TARGET_TAB" style="width: 100%;" value="<?php echo $TabTargetTab; ?>"  >  
                </td>
            </tr>            
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Nome</label></td>
                <td class="TdDip"><input onblur="SpacesToUnderscore(this)"  type="text" id="NomeCar" Name="NomeCar" style="width: 100%;" class="ModificaField" value="<?php echo $TabNome; ?>" /></td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Descrizione</label></td>
                <td class="TdDip"><textarea type="text" id="DescrCar" Name="DescrCar"  style="width: 100%;" class="ModificaField" ><?php echo $TabDesc; ?></textarea></td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Conferma Dato in Tabella</label></td>
                <td class="TdDip">
                    <SELECT id="ConfermaDato" Name="ConfermaDato" style="width: 100%;"   class="ModificaField"  >
                        <option value="S" <?php if ( "$TabConfermaDato" == "S" ){ ?>selected<?php } ?>>Si</option>
                        <option value="C" <?php if ( "$TabConfermaDato" == "C" ){ ?>selected<?php } ?>>Si con Carica Strato Prec</option>
                        <option value="N" <?php if ( "$TabConfermaDato" == "N" ){ ?>selected<?php } ?>>No (obbligatorio caricare File)</option>
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
       
	 		
<script>  
            
           /* if($('#SELCARICAMENTO').val() == 'CarFile'){
                 $('.CarFile').show(); 
                }else{ 
                    $('.CarFile').hide();  
                    $('#NOMCARICAMENTO').val() ='';
                    $('#TXTCARICAMENTO').val() ='';                     
                }*/

            $("#SELCARICAMENTO").change(function(){   
               if ( $('#SELCARICAMENTO').val() == 'CarFile' ){ $('.CarFile').show(); }else{
                 $('.CarFile').hide(); 
                 $('#NOMCARICAMENTO').val("") ;
                $('#TXTCARICAMENTO').val("") ; 
            } 
            });   

			
            </script> 