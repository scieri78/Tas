
<form id="FormAutoPlay" >
<input type="hidden" name="id_AutoPlay" id="id_AutoPlay"  value="<?php echo $id_AutoPlay; ?>" >
<input type="hidden" name="IdProcess" id="IdProcess" value="<?php echo $IdProcess; ?>" >       
            <table class="TabDip" >
			 <tr>       
                <td class="TdDip"><label  style="width:100px;text-align:left;" >ATTIVO</label></td>
                <td class="TdDip">
                    <SELECT id="ATTIVO" Name="ATTIVO" class="selectSearch" style="width:100%;" >
                        <option <?php if ( $ATTIVO == 1 ) { ?>selected<?php } ?> value="1" >SI</option>
                        <option <?php if ( $ATTIVO== 0 ) { ?>selected<?php } ?> value="0" >NO</option>
                        
                    </SELECT>            
                </td>
            </tr>
			
             <tr><td colspan=2 style="text-align:center;" ><B>Disabilita al</B></td></tr>
            <tr>       
                <td class="TdDip"><label  style="width:100px;text-align:left;" >FLUSSO</label></td>
                <td class="TdDip">
                    <SELECT onchange="selectDipendenza(this.value,'<?php echo $datiAutorun[0]['ID_LEGAME'];?>')" id="SELFLUSSO" Name="SELFLUSSO"  style="width: 100%;"  class="selectSearch" >
                        <option value="" >..</option>
                        <?php                            
                            foreach ($datiFlussi as $row) {
                                $IdFlusso=$row['ID_FLU'];
                                $SelFlusso=$row['FLUSSO'];
                                $SelIdWorkFlow=$row['ID_WORKFLOW'];
                                ?><option value="<?php echo $IdFlusso; ?>" <?php if ($IdFlusso == $datiAutorun[0]['ID_FLU'] ) { ?>selected<?php } ?> ><?php echo $SelFlusso; ?></option><?php
                            }
                        ?>
                    </SELECT>            
                </td>
            </tr>

               <tr>       
                <td class="TdDip"><label  style="width:100px;text-align:left;" >PASSO</label></td>
                <td class="TdDip">
                    <SELECT id="ID_LEGAME" Name="ID_LEGAME"  class="selectSearch" style="width:100%;" >
                        <option value="" >..</option>
                        
                    </SELECT>            
                </td>
            </tr>
         
                
               <td>
				<button id="PulMod" onclick="ModAutoPlay();return false;" class="btn AggiungiFlusso">				
				<i class="fa-solid fa-save"> </i> Salva</button>
				
				</td>
            </tr>
            </table>
</form>
       <script>
        selectDipendenza('<?php echo $datiAutorun[0]['ID_FLU'];?>','<?php echo $datiAutorun[0]['ID_LEGAME'];?>');
 $.ui.dialog.prototype._allowInteraction = function (e) {
    return true;
};
	$(".selectSearch").select2();
       </script>