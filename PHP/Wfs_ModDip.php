<style>
.TabDip{
    left:0;
    right:0;
    margin:auto;
    width:400px;
}
.TdDip{
    width:50%;
}

</style>
<?php

include '../GESTIONE/connection.php';
include '../GESTIONE/SettaVar.php';
include '../GESTIONE/login.php';
include '../GESTIONE/sicurezza.php';

if ( "$find" == "1" )  {

    $TotLiv=20;
    
    $IdWorkFlow=$_REQUEST['IdWorkFlow'];
    $IdFlu=$_POST['IdFlu'];
    $Tipo=$_POST['Tipo'];
    $IdDip=$_POST['IdDip'];
    $TLink=$_POST['TLink'];
    
    if ( "$conn" == "Resource id #4" ) {
      $conn = db2_connect($db2_conn_string, '', '');
    }
    
    ?><input type="hidden" Name="IdFlu" id="IdFlu" value="<?php echo $IdFlu; ?>" ><?php
        
    switch ( $Tipo ){
 case "FL":
      
        $sqlT="SELECT FLUSSO, DESCR, BLOCK_CONS FROM WFS.FLUSSI WHERE ID_FLU = $IdDip and ID_WORKFLOW = '$IdWorkFlow'";
        $stmtT=db2_prepare($conn, $sqlT);
        $resT=db2_execute($stmtT);
        while ($rowT = db2_fetch_assoc($stmtT)) {
            $TabNome=$rowT['FLUSSO'];
            $TabDesc=$rowT['DESCR'];
            $TabBlockCons=$rowT['BLOCK_CONS'];
        }
        $sqlT="SELECT DISTINCT LIV, (SELECT count(*) FROM WFS.FLUSSI_LIV_PERS WHERE ID_FLU = $IdDip and ID_WORKFLOW = '$IdWorkFlow' ) LIVPRES FROM WFS.LEGAME_FLUSSI WHERE ID_FLU = $IdDip and ID_WORKFLOW = '$IdWorkFlow'";
        $stmtT=db2_prepare($conn, $sqlT);
        $resT=db2_execute($stmtT);
        while ($rowT = db2_fetch_assoc($stmtT)) {
            $TabLiv=$rowT['LIV'];
            $LivPers=$rowT['LIVPRES'];
        }      
        ?><div id="FormModFlusso" >
            <div class="Plst Tipo" id="PulFluss" style="border-color:black;color:black;text-align: center;width: 100%;" ><img class="ImgIco" src="../images/Flusso.png" title="<?php echo $IdDip; ?>"  >Modifica Flusso: <?php echo $TabNome; ?></div>
            <table class="TabDip" >
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Livello</label></td>
                <td class="TdDip">
				<input name="LivPersAuto" id="LivPersAuto" type="checkbox" <?php if ( "$LivPers" == "0" ){ ?> checked <?php } ?> value="1" >Auto
				<input name="LivPersNum" id="LivPersNum" type="number"  value="<?php echo $TabLiv; ?>" hidden >
				<input name="TabPersNum" id="TabPersNum" type="hidden"  value="<?php echo $TabLiv; ?>" readonly >
				</td>
            </tr>			
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Nome</label></td>
                <td class="TdDip"><input type="text" id="NomeFlu" Name="NomeFlu" style="width: 100%;" class="ModificaField" value="<?php echo $TabNome; ?>" /></td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Descrizione</label></td>
                <td class="TdDip"><textarea type="text" id="DescrFlu" Name="DescrFlu"  style="width: 100%;" class="ModificaField" ><?php echo $TabDesc; ?></textarea></td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Comportamento con periodo Consolidato</label></td>
                <td class="TdDip">
				<select id="BlockConsFlu" Name="BlockCons"  class="ModificaField" >
				<option value="N" <?php if ( "$TabBlockCons" == "N" ){ ?> selected <?php } ?> > Non far Nulla </option>
				<option value="Y" <?php if ( "$TabBlockCons" == "Y" ){ ?> selected <?php } ?> > Blocca con periodo Consolidato </option>
				<option value="S" <?php if ( "$TabBlockCons" == "S" ){ ?> selected <?php } ?> > Abilita con periodo Consolidato </option>
				<option value="X" <?php if ( "$TabBlockCons" == "X" ){ ?> selected <?php } ?> > Disabilita svalida con periodo Consolidato </option>
				</select>
				</td>
            </tr>
            <tr>
                <td><div class="button centrale" onclick="PulChiudi()" >Chiudi</div></td>
                <td><div class="button centrale" id="PulMod" onclick="PulModDip('FL',<?php echo $IdDip; ?>)" hidden >Modifica</div></td>
            </tr>
            </table>
        </div>
        <script>
            function TestModifica(){
                var vTest = false;
                                
                if ( $('#NomeFlu').val() != '' && $('#NomeFlu').val() != '<?php echo $TabNome; ?>' ){ vTest = true;}
                if ( $('#DescrFlu').val() != '' && $('#DescrFlu').val() != '<?php echo $TabDesc; ?>' ){ vTest = true;}
				if ( $('#SELVAL'   ).val() != '<?php echo $TabVali; ?>' ){ vTest = true;}
                
                <?php if ( "$TabBlockCons" == "Y" ){ ?>
                if ( $('#BlockConsFlu').prop("checked") == false ){ vTest = true; }
                <?php } else { ?>
                if ( $('#BlockConsFlu').prop("checked") == true ){ vTest = true; }
                <?php } ?>              
                 
                if (vTest){
                    $('#PulMod').show();
                } else {
                    $('#PulMod').hide();
                }
            }
			$('#LivPersAuto').change(function(){
			  if ( $('#LivPersAuto').prop("checked") == true ){ $('#LivPersNum').hide(); }else{ $('#LivPersNum').show();}
			});
			if ( $('#LivPersAuto').prop("checked") == true ){ $('#LivPersNum').hide(); }else{ $('#LivPersNum').show();}
        </script>
        <?php
        break;          
      case "F":
      
        $sqlT="SELECT FLUSSO, DESCR,
        ( SELECT PRIORITA FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'F' AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') PRIO,
		( SELECT VALIDITA FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'F' AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') VALIDITA
        FROM WFS.FLUSSI WHERE ID_FLU = $IdDip and ID_WORKFLOW = '$IdWorkFlow'";
        $stmtT=db2_prepare($conn, $sqlT);
        $resT=db2_execute($stmtT);
        while ($rowT = db2_fetch_assoc($stmtT)) {
            $TabNome=$rowT['FLUSSO'];
            $TabDesc=$rowT['DESCR'];
            $TabPrio=$rowT['PRIO'];
			$TabVali=$rowT['VALIDITA'];
        }
      
      
        ?><div id="FormModFlusso" >
            <div class="Plst Tipo" id="PulFluss" style="border-color:black;color:black;text-align: center;width: 100%;" ><img class="ImgIco" src="../images/Flusso.png" title="<?php echo $IdDip; ?>"  >Modifica Flusso: <?php echo $TabNome; ?></div>
            <table class="TabDip" >
            <tr>
                <td class="TdDip"><label style="width:200px;text-align:left;">Priorita'</label></td>
                <td class="TdDip">
                    <SELECT id="Priorita" Name="Priorita" class="ModificaField"   style="width: 100%;" >
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
                    <SELECT id="SELFLUSSO" Name="SELFLUSSO"  style="width: 100%;"  class="ModificaField" >
                        <option value="" >..</option>
                        <?php
                            $sql="SELECT ID_FLU, ID_WORKFLOW, FLUSSO FROM WFS.FLUSSI WHERE ID_FLU != $IdFlu ORDER BY FLUSSO";
            
                            $stmt=db2_prepare($conn, $sql);
                            $result=db2_execute($stmt);
                            while ($row = db2_fetch_assoc($stmt)) {
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
                <td><div class="button centrale" onclick="PulChiudi()" >Chiudi</div></td>
                <td><div class="button centrale" id="PulMod" onclick="PulModDip('F',<?php echo $IdDip; ?>)" hidden >Modifica</div></td>
            </tr>
            </table>
        </div>
        <script>       
            
            function TestModifica(){
                var vTest = false;
                
                if ( $('#Priorita').val() != '' && $('#Priorita').val() != '<?php echo $TabPrio; ?>' ){ vTest = true;} 
				if ( $('#SELVAL'   ).val() != '<?php echo $TabVali; ?>' ){ vTest = true;}
                if ( $('#SELFLUSSO').val() != '' && $('#SELFLUSSO').val() != '<?php echo $IdDip; ?>' ){ vTest = true;}
                                
                if (vTest){
                    $('#PulMod').show();
                } else {
                    $('#PulMod').hide();
                }
            }
        </script>
        <?php
        break;    
      case "C":

        $sqlT="SELECT 
        NOME_INPUT, CARICAMENTO, DESCR, CONFERMA_DATO,      
        ( SELECT PRIORITA FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'C' AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') PRIO,
		( SELECT VALIDITA FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'C' AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') VALIDITA
        FROM WFS.CARICAMENTI WHERE ID_CAR = $IdDip and ID_WORKFLOW = '$IdWorkFlow'";
        $stmtT=db2_prepare($conn, $sqlT);
        $resT=db2_execute($stmtT);
        while ($rowT = db2_fetch_assoc($stmtT)) {
            $TabNome=$rowT['CARICAMENTO'];
            $TabDesc=$rowT['DESCR'];
            $TabInput=$rowT['NOME_INPUT'];
            $TabPrio=$rowT['PRIO'];
            $TabConfermaDato=$rowT['CONFERMA_DATO']; 
			$TabVali=$rowT['VALIDITA'];           
        }
        
        ?><div id="FormModFlusso" >
            <div class="Plst Tipo" id="PulCaric" style="border-color:black;color:black;text-align: center;width: 100%;" ><img class="ImgIco" src="../images/Carica.png" title="<?php echo $IdDip; ?>"  >Modifica Caricamento: <?php echo $TabNome; ?></div>
            <table class="TabDip" >
            <tr>
                <td class="TdDip"><label style="width:200px;text-align:left;">Priorita'</label></td>
                <td class="TdDip">
                    <SELECT id="Priorita" Name="Priorita" class="ModificaField"  style="width: 100%;" >
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
                            
                            <SELECT id="SELCARICAMENTO" Name="SELCARICAMENTO" style="width: 100%;"  class="ModificaField">
                                <option value="" >..</option>
                                <option value="WFS_TEST"<?php if ( "WFS_TEST" == "$TabInput" ) { ?>selected<?php } ?>  >----- TEST ----</option>
                                <?php   
                                    $sql="SELECT DISTINCT NOME_FLUSSO 
                                    FROM WORK_RULES.LOAD_TRASCODIFICA 
                                    WHERE NOME_FLUSSO NOT IN ( SELECT NOME_INPUT FROM WFS.CARICAMENTI  WHERE NOME_INPUT != '$TabInput'  ) 
                                    ORDER BY NOME_FLUSSO";
                    
                                    $stmt=db2_prepare($conn, $sql);
                                    $result=db2_execute($stmt);
                                    while ($row = db2_fetch_assoc($stmt)) {
                                        $NomeFlusso=$row['NOME_FLUSSO'];
                                        ?><option value="<?php echo $NomeFlusso; ?>" <?php if ( "$NomeFlusso" == "$TabInput" ) { ?>selected<?php } ?> ><?php echo $NomeFlusso; ?></option><?php
                                    }
                                ?>
                            </SELECT> 
                        
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Nome</label></td>
                <td class="TdDip"><input type="text" id="NomeCar" Name="NomeCar" style="width: 100%;" class="ModificaField" value="<?php echo $TabNome; ?>" /></td>
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
                <td><div class="button centrale" onclick="PulChiudi()" >Chiudi</div></td>
                <td><div class="button centrale" id="PulMod" onclick="PulModDip('C',<?php echo $IdDip; ?>)" hidden >Modifica</div></td>
            </tr>
            </table>
        </div>
        <script>
            function TestModifica(){
                var vTest = false;
                                
                if ( $('#Priorita').val() != '' && $('#Priorita').val() != '<?php echo $TabPrio; ?>' ){ vTest = true;}
                if ( $('#SELCARICAMENTO').val() != '' && $('#SELCARICAMENTO').val() != '<?php echo $TabInput; ?>' ){ vTest = true;}
                if ( $('#NomeCar').val() != '' && $('#NomeCar').val() != '<?php echo $TabNome; ?>' ){ vTest = true;}
                if ( $('#DescrCar').val() != '' && $('#DescrCar').val() != '<?php echo $TabDesc; ?>' ){ vTest = true;}
                if ( $('#ConfermaDato').val() != '<?php echo $TabConfermaDato; ?>' ){ vTest = true;}
				if ( $('#SELVAL'   ).val() != '<?php echo $TabVali; ?>'   ){ vTest = true;}
                                
                if (vTest){
                    $('#PulMod').show();
                } else {
                    $('#PulMod').hide();
                }
            }
        </script>
        <?php
        break;    
  case "L":
       $ImgTipo="Link";
       if ( "$TLink" == "I" ){ $ImgTipo="Setting"; }

        $sqlT="SELECT  TIPO,LINK,TARGET,DESCR,
        ( SELECT PRIORITA FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'L' AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') PRIO,
		( SELECT VALIDITA FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'L' AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') VALIDITA,
		( SELECT OPT FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'L' AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') OPT
        FROM WFS.LINKS WHERE ID_LINK = $IdDip and ID_WORKFLOW = '$IdWorkFlow'";
        $stmtT=db2_prepare($conn, $sqlT);
        $resT=db2_execute($stmtT);
        while ($rowT = db2_fetch_assoc($stmtT)) {
            $TabNome=$rowT['LINK'];
            $TabDesc=$rowT['DESCR'];
            $TabTipo=$rowT['TIPO'];
            $TabDest=$rowT['TARGET'];
            $TabPrio=$rowT['PRIO'];
			$TabVali=$rowT['VALIDITA'];
            $TabOpt=$rowT['OPT'];
        }

        $ArrListConfPhp=array();
        $SqlList="SELECT TARGET FROM WFS.LINKS WHERE ID_WORKFLOW = $IdWorkFlow AND TIPO = 'I' ";
        $stmt=db2_prepare($conn, $SqlList);
        $res=db2_execute($stmt);
        if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); } 
        while ($row = db2_fetch_assoc($stmt)) {
            $TargLink=$row['TARGET']; 
            array_push($ArrListConfPhp,$TargLink);
        }
        
        ?><div id="FormModFlusso" >
            <div class="Plst Tipo" id="PulCaric" style="border-color:black;color:black;text-align: center;width: 100%;" ><img class="ImgIco" src="../images/<?php echo $ImgTipo; ?>.png" title="<?php echo $IdDip; ?>" >Modifica Link: <?php echo $TabNome; ?></div>
            <table class="TabDip" >
            <tr>
                <td class="TdDip"><label style="width:200px;text-align:left;">Priorita'</label></td>
                <td class="TdDip">
                    <SELECT id="Priorita" Name="Priorita" class="ModificaField"  style="width: 100%;" >
                    <?php 
                    for($s=1;$s<=$TotLiv;$s++){
                        ?><option value="<?php echo $s; ?>" <?php if ( "$s" == "$TabPrio" ) { ?>selected<?php } ?> ><?php echo $s; ?></option><?php
                    }
                    ?>
                    </SELECT>
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label style="width:200px;text-align:left;">Tipo</label></td>
                <td class="TdDip">
                    <input type="hidden" "id="TipoLink" Name="TipoLink" value="<?php echo $TLink; ?>" >
                    <?php 
                    if ( "$TLink" == "I" ){ 
                      ?>Interno<?php
                    }else{ 
                      ?>Esterno<?php 
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Destinazione</label></td>
                <td class="TdDip">
                 <?php 
                    if ( "$TLink" == "I" ){ 
                      ?>
                       <input type="text" id="Destinazione" Name="Destinazione" style="width: 100%;" class="AggiungiField" value="<?php echo $TabDest; ?>" hidden />
                       <select id="ConfPhp" name="ConfPhp" style="width: 100%;" onchange="TestLinkTarget()" >
                             <option value="" >..</option>
                             <option value="<?php echo $TabDest; ?>" <?php if( "$TabDest" == "$TabDest" ){ ?>selected<?php } ?>><?php echo substr($TabDest, 0, -4); ?></option>
                             <?php
                              $ArrFilePhp=scandir($PREELAB);
                              foreach( $ArrFilePhp as $SelFile ){
                                if ( "$SelFile" != "." and "$SelFile" != ".."  ){
                                  $find=false;
                                  foreach( $ArrListConfPhp as $Censito ){
                                      if ( "$Censito" == "$SelFile" ){
                                         $find=true;                                  
                                      }
                                  }
                                  if ( ! $find ){
                                    ?><option value="<?php echo $SelFile; ?>" <?php if( "$SelFile" == "$TabDest" ){ ?>selected<?php } ?>><?php echo substr($SelFile, 0, -4); ?></option><?php
                                  }
                                }
                              }
                            ?>
                        </select>  
                      <?php
                    }else{ 
                      ?>
                      <input type="text" id="Destinazione" Name="Destinazione" style="width: 100%;" class="AggiungiField" value="<?php echo $TabDest; ?>" />
                      <?php 
                    }
                    ?>                
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Nome</label></td>
                <td class="TdDip"><input type="text" id="NomeLink" Name="NomeLink" style="width: 100%;" class="ModificaField" value="<?php echo $TabNome; ?>" /></td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Descrizione</label></td>
                <td class="TdDip"><textarea type="text" id="DescrLink" Name="DescrLink" style="width: 100%;"  class="ModificaField" ><?php echo $TabDesc; ?></textarea></td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Mesi Validita'</label></td>
                <td class="TdDip">
                    <input type="text" id="SELVAL" Name="SELVAL"  style="width: 100%;"  class="ModificaField" value="<?php echo $TabVali; ?>" >         
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Opzionale</label></td>
                <td class="TdDip">
  				    <select id="SELOPT" name="SELOPT" style="width: 100%;" onchange="TestLinkTarget()" >
                      <option value="N" <?php if( "$TabOpt" == "N" ){ ?>selected<?php } ?>>No</option>
                      <option value="Y" <?php if( "$TabOpt" == "Y" ){ ?>selected<?php } ?>>Yes</option>
                    </select> 
                </td>
            </tr>						
            <tr>
                <td><div class="button centrale" onclick="PulChiudi()" >Chiudi</div></td>
                <td><div class="button centrale" id="PulMod" onclick="PulModDip('L',<?php echo $IdDip; ?>)" hidden >Modifica</div></td>
            </tr>
            </table>
        </div>
        <script>
            function TestModifica(){
                var vTest = false;
                                
                if ( $('#Priorita').val() != '' && $('#Priorita').val() != '<?php echo $TabPrio; ?>' ){ vTest = true;}
                if ( $('#TipoLink').val() != '' && $('#TipoLink').val() != '<?php echo $TabTipo; ?>' ){ vTest = true;}
                if ( $('#Destinazione').val() != '' && $('#Destinazione').val() != '<?php echo $TabDest; ?>' ){ vTest = true;}
                if ( $('#NomeLink'    ).val() != '' && $('#NomeLink'    ).val() != '<?php echo $TabNome; ?>' ){ vTest = true;}
                if ( $('#DescrLink'   ).val() != '<?php echo $TabDesc; ?>' ){ vTest = true;}
				if ( $('#SELVAL'   ).val() != '<?php echo $TabVali; ?>'   ){ vTest = true;}
				if ( $('#SELOPT'   ).val() != '<?php echo $TabOpt; ?>'   ){ vTest = true;}
                                
                if (vTest){
                    $('#PulMod').show();
                } else {
                    $('#PulMod').hide();
                }
            }
            
            function TestLinkTarget(){
                $('#Destinazione').val('');
                var vPhp=$("#ConfPhp").val();
                $("#Destinazione").val(vPhp);
            }
            
        </script>
        <?php
        break;          
      case "E":

        $sqlT="SELECT  ID_SH,ELABORAZIONE,TAGS,DESCR,SALTA_ELAB,SHOWPROC,
        ( SELECT PRIORITA FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'E' AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') PRIO,
        READONLY,
		( SELECT VALIDITA FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'E' AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') VALIDITA
        FROM WFS.ELABORAZIONI WHERE ID_ELA = $IdDip and ID_WORKFLOW = '$IdWorkFlow'";
        $stmtT=db2_prepare($conn, $sqlT);
        $resT=db2_execute($stmtT);
        while ($rowT = db2_fetch_assoc($stmtT)) {
            $TabNome=$rowT['ELABORAZIONE'];
            $TabDesc=$rowT['DESCR'];
            $TabSh=$rowT['ID_SH'];
            $TabTags=$rowT['TAGS'];
            $TabPrio=$rowT['PRIO'];
            $TabROnly=$rowT['READONLY'];
			$TabVali=$rowT['VALIDITA'];
			$TabSalta=$rowT['SALTA_ELAB'];
			$TabShowProc=$rowT['SHOWPROC'];
        }
        
        ?><div id="FormModFlusso" >
            <div class="Plst Tipo" id="PulElabo" style="border-color:black;color:black;text-align: center;width: 100%;" ><img class="ImgIco" src="../images/Elaborazione.png" title="<?php echo $IdDip; ?>" >Modifica Elaborazione: <?php echo $TabNome; ?></div>
            <table class="TabDip" >
            <tr>
                <td class="TdDip"><label style="width:200px;text-align:left;">Priorita'</label></td>
                <td class="TdDip">
                    <SELECT id="Priorita" Name="Priorita"  class="ModificaField"  style="width: 100%;" >
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
                   
                            <SELECT id="SELELABORAZIONE" Name="SELELABORAZIONE" style="width: 100%;"  class="ModificaField"   >
                                <option value="" >..</option>
                                <option value="0" <?php if ( "0" == "$TabSh" ) { ?>selected<?php } ?> >----- TEST ----</option>
                                <?php  
                                $sql="SELECT ID_SH, SHELL 
                                FROM (
                                  SELECT DISTINCT ID_SH, SHELL
                                  FROM WORK_CORE.CORE_SH_ANAG 
                                  WHERE ID_SH NOT IN ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE SHELL != 'ConsolidaIdProcess.sh' AND ID_SH != $TabSh   AND ID_WORKFLOW = $IdWorkFlow ) 
                                  AND WFS = 'Y'
                                  UNION ALL
                                  SELECT ID_SH, SHELL
                                  FROM WORK_CORE.CORE_SH_ANAG 
                                  WHERE ID_SH = ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE SHELL = 'ConsolidaIdProcess.sh' AND ID_WORKFLOW = $IdWorkFlow  ) 
								  AND ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE SHELL = 'ConsolidaIdProcess.sh' AND ID_WORKFLOW = $IdWorkFlow  ) NOT IN ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE SHELL != 'ConsolidaIdProcess.sh'  AND ID_WORKFLOW = $IdWorkFlow )
                                  AND WFS = 'Y'
                                )
                                ORDER BY SHELL";		
                
                                $stmt=db2_prepare($conn, $sql);
                                $result=db2_execute($stmt);
                                while ($row = db2_fetch_assoc($stmt)) {
                                    $IdSh=$row['ID_SH'];
                                    $ShellName=$row['SHELL'];
                                    ?><option value="<?php echo $IdSh; ?>" <?php if ( "$IdSh" == "$TabSh" ){ ?>selected<?php } ?> ><?php echo $ShellName; ?></option><?php
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
                <td><div class="button centrale" onclick="PulChiudi()" >Chiudi</div></td>
                <td><div class="button centrale" id="PulMod" onclick="PulModDip('E',<?php echo $IdDip; ?>)" hidden >Modifica</div></td>
            </tr>
            </table>
        </div>
        <script>
            
            function TestModifica(){
                var vTest = false;
                                
                if ( $('#Priorita').val() != '' && $('#Priorita').val() != '<?php echo $TabPrio; ?>' ){ vTest = true;}
                if ( $('#SELELABORAZIONE').val() != '' && $('#SELELABORAZIONE').val() != '<?php echo $TabSh; ?>' ){ vTest = true;}
                if ( $('#NomeEla').val() != '' && $('#NomeEla'    ).val() != '<?php echo $TabNome; ?>' ){ vTest = true;}
                if ( $('#DescrEla').val() != '<?php echo $TabDesc; ?>' ){ vTest = true;}
                if ( $('#Tags').val() != '<?php echo $TabTags; ?>' ){ vTest = true;}
                if ( $('#ReadOnly').val() != '<?php echo $TabROnly; ?>' ){ vTest = true;}
				if ( $('#SELVAL'   ).val() != '<?php echo $TabVali; ?>'   ){ vTest = true;}
				if ( $('#SaltaElab'   ).val() != '<?php echo $SaltaElab; ?>'   ){ vTest = true;}
				
                                
                if (vTest){
                    $('#PulMod').show();
                } else {
                    $('#PulMod').hide();
                }
            }
            
        </script>
        <?php
        break;          
      case "V":

        $sqlT="SELECT VALIDAZIONE,DESCR,
        ( SELECT PRIORITA FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'V' AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') PRIO,
		EXTERNAL,
		( SELECT VALIDITA FROM WFS.LEGAME_FLUSSI WHERE TIPO = 'V' AND ID_DIP = $IdDip and ID_WORKFLOW = '$IdWorkFlow') VALIDITA
        FROM WFS.VALIDAZIONI WHERE ID_VAL = $IdDip and ID_WORKFLOW = '$IdWorkFlow'";
        $stmtT=db2_prepare($conn, $sqlT);
        $resT=db2_execute($stmtT);
        while ($rowT = db2_fetch_assoc($stmtT)) {
            $TabNome=$rowT['VALIDAZIONE'];
            $TabDesc=$rowT['DESCR'];
            $TabPrio=$rowT['PRIO'];
			$TabVali=$rowT['VALIDITA'];
			$TabExt=$rowT['EXTERNAL'];
        }     
      
        ?><div id="FormModFlusso" >
            <div class="Plst Tipo" id="PulValid" style="border-color:black;color:black;text-align: center;width: 100%;" ><img class="ImgIco" src="../images/Valida.png" title="<?php echo $IdDip; ?>" >Modifica Validazione: <?php echo $TabNome; ?></div>
            <table class="TabDip" >
            <tr>
                <td class="TdDip"><label style="width:200px;text-align:left;">Priorita'</label></td>
                <td class="TdDip">
                    <SELECT id="Priorita" Name="Priorita" class="ModificaField"   style="width: 100%;" >
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
                <td><div class="button centrale" onclick="PulChiudi()" >Chiudi</div></td>
                <td><div class="button centrale" id="PulMod" onclick="PulModDip('V',<?php echo $IdDip; ?>,<?php echo $IdFlu; ?>)" hidden >Modifica</div></td>
            </tr>
            </table>
        </div>
        <script>
            function TestModifica(){
                var vTest = false;
                                
                if ( $('#Priorita').val() != '' && $('#Priorita').val() != '<?php echo $TabPrio; ?>' ){ vTest = true;}
                if ( $('#NomeVal'    ).val() != '' && $('#NomeVal'    ).val() != '<?php echo $TabNome; ?>' ){ vTest = true;}
                if ( $('#DescrVal'   ).val() != '<?php echo $TabDesc; ?>' ){ vTest = true;}
				if ( $('#External'   ).val() != '<?php echo $TabExt; ?>' ){ vTest = true;}
				if ( $('#SELVAL'   ).val() != '<?php echo $TabVali; ?>'  ){ vTest = true;}
                                
                if (vTest){
                    $('#PulMod').show();
                } else {
                    $('#PulMod').hide();
                }
            }
        </script>
        <?php
        break;      
    }
    ?>
    <script>
            function PulChiudi(){
                $('#InsDip').empty().hide();
            }
            
            function PulModDip(vTipo,vIdDip){
                    $('#Azione').val('M'+vTipo);
                      
                    var input = $("<input>")
                    .attr("type", "hidden")
                    .attr("name", "IdDip")
                    .val(vIdDip);
                    $('#FormMain').append($(input));
            
			        $('#TopScroll').val($( window ).scrollTop());
                    $('#BodyHeight').val($('body').height());
                   
                   $("#Waiting").show();
                   $('#FormMain').submit(); 
            };                        
              
            
            $('.ModificaField').keyup(function(){
                TestModifica();
            });
            
            $('.ModificaField').change(function(){
                TestModifica();
            });
            
            TestModifica();
    </script>
    <?php
}
?>

