    <?php 
if ($TabExt['EXTERNAL'] == 'Y'){
	$iconType ='<div style="float:left;">EXT.</div><img class="ImgIco" src="./images/Elaborazione.png">';
	$TipoDip = 'Elaborazione Esterna';
}else{
	$iconType ='<img class="ImgIco" src="./images/Valida.png">';
	$TipoDip = 'Validazione';
} 

 ?>


<div id="FormModFlusso" class="dip_V" >
<input type="hidden" name="Azione" id="Azione"  value="<?php echo $Azione; ?>" >
<input type="hidden" name="TipoDip" id="TipoDip" value="<?php echo $TipoDip; ?>" >   
<input type="hidden" name="NomeDip" id="NomeDip" value="<?php echo $TabNome; ?>" >    
            <table class="TabDip" >			
			 <tr> 			
		   <td class="iconType"> 
				<?php echo $iconType; ?>
		   </td>
		 
		 </tr>
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
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Nome</label></td>
                <td class="TdDip"><input type="text" id="NomeVal" Name="NomeVal" style="width: 100%;" class="ModificaField" value="<?php echo $TabNome; ?>" /></td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Descrizione</label></td>
                <td class="TdDip"><textarea type="text" id="DescrVal" Name="DescrVal" style="width: 100%;" class="ModificaField" ><?php echo $TabDesc; ?></textarea></td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >External</label></td>
                <td class="TdDip">
                    <select id="External" name="External" class="ModificaField"  >
                      <option value="N" <?php if ( "$TabExt" == "N" ){ ?>selected<?php } ?> >No</option>
                      <option value="Y" <?php if ( "$TabExt" == "Y" ){ ?>selected<?php } ?> >Si</option>
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
        