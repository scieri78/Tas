<form id="formUpdateArgo" >


            <table class="TabDip" >
			
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >ID_PROCESS</label></td>
                <td class="TdDip"><input readonly type="text" id="ID_PROCESS" Name="ID_PROCESS" style="width: 100%;" class="ModificaField" value="<?php echo $ID_PROCESS; ?>" /></td>
            </tr>
            
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >COMPAGNIA</label></td>
                <td class="TdDip"><input readonly type="text" id="COMPAGNIA" Name="COMPAGNIA" style="width: 100%;" class="ModificaField" value="<?php echo $COMPAGNIA; ?>" /></td>
            </tr>
            
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >ID_REMOTO</label></td>
                <td class="TdDip"><input type="text" <?php if ($legameValido){echo "readonly";}?> id="ID_REMOTO" Name="ID_REMOTO" style="width: 100%;" class="ModificaField" value="<?php echo $ID_REMOTO; ?>" /></td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >NOME</label></td>
                <td class="TdDip"><input <?php if ($NAME == "CHIUSURA TAS") {echo "readonly";}?> type="text" id="NAME" Name="NAME" style="width: 100%;" class="ModificaField" value="<?php echo $NAME; ?>" /></td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >DESCR</label></td>
                <td class="TdDip"><input type="text" id="DESCR" Name="DESCR" style="width: 100%;" class="ModificaField" value="<?php echo $DESCR; ?>" /></td>
            </tr>
           
            <tr>
               <td>
				<button id="PulMod" onclick="ModIdArgo();return false;" class="btn AggiungiFlusso">
				
				<i class="fa-solid fa-pencil-square-o"> </i> Modifica</button>
				
				</td>
            </tr>
            </table>
                </form>
       
	 		
