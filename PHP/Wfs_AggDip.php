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
    $TLink=$_POST['TLink'];
    
    
    
    if ( "$conn" == "Resource id #4" ) {
      $conn = db2_connect($db2_conn_string, '', '');
    }
    
    ?><input type="hidden" Name="IdFlu" id="IdFlu" value="<?php echo $IdFlu; ?>" ><?php
        
    switch ( $Tipo ){
      case "":
        ?>
        <div id="FormAggFlusso" >
            
            <BR>
            <div><label>Select Tipology</label></div>
            <div>
            <button class="Plst Tipo" id="PulFluss" style="border-color:black;color:black;" ><img class="ImgIco" src="../images/Flusso.png">Flusso</button>
            <button class="Plst Tipo" id="PulCaric" style="border-color:black;color:black;" ><img class="ImgIco" src="../images/Carica.png">Caricamento</button>
            <button class="Plst Tipo" id="PulLinkExt" style="border-color:black;color:black;" ><img class="ImgIco" src="../images/Link.png">Link Ext</button>
            <button class="Plst Tipo" id="PulLinkInt" style="border-color:black;color:black;" ><img class="ImgIco" src="../images/Setting.png">Link Int</button>
            <button class="Plst Tipo" id="PulElabo" style="border-color:black;color:black;" ><img class="ImgIco" src="../images/Elaborazione.png">Elaborazione</button>
            <button class="Plst Tipo" id="PulValid" style="border-color:black;color:black;" ><img class="ImgIco" src="../images/Valida.png">Validazione</button>
            <script>
               $("#PulFluss").click(function(){
                   $("#InsDip").empty().load('../PHP/Wfs_AggDip.php', {
                           Tipo: 'F',
                           IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
                           IdFlu: '<?php echo $IdFlu; ?>'
                      });
               });
               $("#PulCaric").click(function(){
                   $("#InsDip").empty().load('../PHP/Wfs_AggDip.php', {
                           Tipo: 'C',
                           IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
                           IdFlu: '<?php echo $IdFlu; ?>'
                      });
               });
               $("#PulLinkExt").click(function(){
                   $("#InsDip").empty().load('../PHP/Wfs_AggDip.php', {
                           Tipo: 'L',
                           TLink: 'E',
                           IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
                           IdFlu: '<?php echo $IdFlu; ?>'
                      });
               });
               $("#PulLinkInt").click(function(){
                   $("#InsDip").empty().load('../PHP/Wfs_AggDip.php', {
                           Tipo: 'L',
                           TLink: 'I',
                           IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
                           IdFlu: '<?php echo $IdFlu; ?>'
                      });
               });                 
               $("#PulElabo").click(function(){
                   $("#InsDip").empty().load('../PHP/Wfs_AggDip.php', {
                           Tipo: 'E',
                           IdWorkFlow: '<?php echo $IdWorkFlow; ?>', 
                           IdFlu: '<?php echo $IdFlu; ?>'
                      });
               });
               $("#PulValid").click(function(){
                   $("#InsDip").empty().load('../PHP/Wfs_AggDip.php', {
                           Tipo: 'V',
                           IdWorkFlow: '<?php echo $IdWorkFlow; ?>', 
                           IdFlu: '<?php echo $IdFlu; ?>'
                      });
               });             
            </script>
            </div>
            <BR>
            <div><button id="ChiudiD" >Close</button></div>
            <script>
            $("#ChiudiD").click(function(){
                $("#InsDip").hide();
                $("#InsDip").empty();
            });
            </script>           
        </div>
        <?php
        break;
      case "F":
        ?><div id="FormAggFlusso" >
            <div class="Plst Tipo" id="PulFluss" style="border-color:black;color:black;text-align: center;width: 100%;" ><img class="ImgIco" src="../images/Flusso.png">FLUSSO</div>
            <table class="TabDip" >
            <tr>
                <td class="TdDip"><label style="width:200px;text-align:left;">Priorita'</label></td>
                <td class="TdDip">
                    <SELECT id="Priorita" Name="Priorita"   style="width: 100%;" >
                    <?php 
                    for($s=1;$s<=$TotLiv;$s++){
                        ?><option value="<?php echo $s; ?>" ><?php echo $s; ?></option><?php
                    }
                    ?>
                    </SELECT>
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Flusso</label></td>
                <td class="TdDip">
                    <SELECT id="SELFLUSSO" Name="SELFLUSSO"  style="width: 100%;"  class="AggiungiField" >
                        <option value="" >..</option>
                        <?php
                            $sql="SELECT ID_FLU, ID_WORKFLOW, FLUSSO FROM WFS.FLUSSI WHERE ID_FLU != $IdFlu AND ID_WORKFLOW = $IdWorkFlow ORDER BY FLUSSO";
                            $stmt=db2_prepare($conn, $sql);
                            $result=db2_execute($stmt);
                            while ($row = db2_fetch_assoc($stmt)) {
                                $IdFlusso=$row['ID_FLU'];
                                $SelFlusso=$row['FLUSSO'];
                                $SelIdWorkFlow=$row['ID_WORKFLOW'];
                                ?><option value="<?php echo $IdFlusso; ?>" ><?php echo $SelFlusso; ?></option><?php
                            }
                        ?>
                    </SELECT>            
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Mesi Validita'</label></td>
                <td class="TdDip">
                    <input type="text" id="SELVAL" Name="SELVAL"  style="width: 100%;"   >         
                </td>
            </tr>
            </table>
            <button id="PulBack" >Back</button>
            <button id="PulAggDip" hidden >Aggiungi</button>
            <script>    
            $("#PulAggDip").click(function(){   
                   $('#Azione').val('ADF');
                   $("#Waiting").show();
                   $('#FormMain').submit(); 
            });             
            </script>
        </div><?php
        break;    
      case "C":
        ?><div id="FormAggFlusso" >
            <div class="Plst Tipo" id="PulCaric" style="border-color:black;color:black;text-align: center;width: 100%;" ><img class="ImgIco" src="../images/Carica.png">CARICAMENTO</div>
            <table class="TabDip" >
            <tr>
                <td class="TdDip"><label style="width:200px;text-align:left;">Priorita'</label></td>
                <td class="TdDip">
                    <SELECT id="Priorita" Name="Priorita"  style="width: 100%;" >
                    <?php 
                    for($s=1;$s<=$TotLiv;$s++){
                        ?><option value="<?php echo $s; ?>" ><?php echo $s; ?></option><?php
                    }
                    ?>
                    </SELECT>
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Caricamento</label></td>
                <td class="TdDip">
                   
                            <SELECT id="SELCARICAMENTO" Name="SELCARICAMENTO" style="width: 100%;"  class="AggiungiField"  >
                                <option value="" >..</option>
                                <option value="WFS_TEST" >----- TEST ----</option>
                                <?php   
                                    $sql="SELECT DISTINCT NOME_FLUSSO 
                                    FROM WORK_RULES.LOAD_TRASCODIFICA 
                                    WHERE NOME_FLUSSO NOT IN ( SELECT NOME_INPUT FROM WFS.CARICAMENTI ) 
                                    ORDER BY NOME_FLUSSO";
                    
                                    $stmt=db2_prepare($conn, $sql);
                                    $result=db2_execute($stmt);
                                    while ($row = db2_fetch_assoc($stmt)) {
                                        $NomeFlusso=$row['NOME_FLUSSO'];
                                        ?><option value="<?php echo $NomeFlusso; ?>" ><?php echo $NomeFlusso; ?></option><?php
                                    }
                                ?>
                            </SELECT>            
                        
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Nome</label></td>
                <td class="TdDip"><input type="text" id="NomeCar" Name="NomeCar" style="width: 100%;" class="AggiungiField" /></td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Descrizione</label></td>
                <td class="TdDip"><textarea type="text" id="DescrCar" Name="DescrCar"  style="width: 100%;" /></td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Conferma Dato in Tabella</label></td>
                <td class="TdDip">
                    <SELECT id="ConfermaDato" Name="ConfermaDato" style="width: 100%;"   class="AggiungiField"  >
                        <option value="S" >Si</option>
                        <option value="N" >No (obbligatorio caricare File)</option>
                    </SELECT>            
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Mesi Validita'</label></td>
                <td class="TdDip">
                    <input type="text" id="SELVAL" Name="SELVAL"  style="width: 100%;"  >         
                </td>
            </tr>           
            </table>
            <button id="PulBack" >Back</button>
            <button id="PulAggDip" hidden >Aggiungi</button>
            <script>    
            $('#SELCARICAMENTO').change(function(){   
               $('#NomeCar').val($('#SELCARICAMENTO').val());
            });
            $("#PulAggDip").click(function(){   
                   $('#Azione').val('AC');                 
                   $("#Waiting").show();
                   $('#FormMain').submit();     
            });             
            </script> 
            <BR>            
            <BR>            
        </div><?php
        break;    
  case "L":

        $ArrListConfPhp=array();
        $SqlList="SELECT TARGET FROM WFS.LINKS WHERE ID_WORKFLOW = $IdWorkFlow AND TIPO = 'I' ";
        $stmt=db2_prepare($conn, $SqlList);
        $res=db2_execute($stmt);
        if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); } 
        while ($row = db2_fetch_assoc($stmt)) {
            $TargLink=$row['TARGET']; 
            array_push($ArrListConfPhp,$TargLink);
        }
  
        if ( "$TLink" == "E" ) {
          $ImgLink="Link";
        }else{
          $ImgLink="Setting";
        }
        ?><div id="FormAggFlusso" >
            <div class="Plst Tipo" id="PulCaric" style="border-color:black;color:black;text-align: center;width: 100%;" ><img class="ImgIco" src="../images/<?php echo $ImgLink; ?>.png">LINK</div>
            <table class="TabDip" >
            <tr>
                <td class="TdDip"><label style="width:200px;text-align:left;">Priorita'</label></td>
                <td class="TdDip">
                    <SELECT id="Priorita" Name="Priorita"  style="width: 100%;" >
                    <?php 
                    for($s=1;$s<=$TotLiv;$s++){
                        ?><option value="<?php echo $s; ?>" ><?php echo $s; ?></option><?php
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
                       <input type="text" id="Destinazione" Name="Destinazione" style="width: 100%;" class="AggiungiField" hidden />
                       <select id="ConfPhp" name="ConfPhp" style="width: 100%;" onchange="TestLinkTarget()" >
                             <option value="" >..</option>
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
                                    ?><option value="<?php echo $SelFile; ?>" ><?php echo substr($SelFile, 0, -4); ?></option><?php
                                  }
                                }
                              }
                            ?>
                        </select>  
                      <?php
                    }else{ 
                      ?>
                      <input type="text" id="Destinazione" Name="Destinazione" style="width: 100%;" class="AggiungiField" />
                      <?php 
                    }
                    ?>
                   
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Nome</label></td>
                <td class="TdDip"><input type="text" id="NomeLink" Name="NomeLink" style="width: 100%;" class="AggiungiField" /></td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Descrizione</label></td>
                <td class="TdDip"><textarea type="text" id="DescrLink" Name="DescrLink" style="width: 100%;"   /></td>
            </tr>
			<tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Possibilità di non eseguire</label></td>
                <td class="TdDip">
                    <SELECT id="SaltaElab" Name="SaltaElab" style="width: 100%;"   class="AggiungiField"  >
                        <option value="S" >Si</option>
                        <option value="N" >No (obbligatorio Eseguire)</option>
                    </SELECT>            
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Mesi Validita'</label></td>
                <td class="TdDip">
                    <input type="text" id="SELVAL" Name="SELVAL"  style="width: 100%;" >         
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Opzionale</label></td>
                <td class="TdDip">
  				    <select id="SELOPT" name="SELOPT" style="width: 100%;" class="AggiungiField" >
                      <option value="N" >No</option>
                      <option value="Y" >Yes</option>
                    </select> 
                </td>
            </tr>				
            </table>
            <button id="PulBack" >Back</button>
            <button id="PulAggDip" hidden >Aggiungi</button>
            <script>    
            $("#PulAggDip").click(function(){   
                   $('#Azione').val('AL');
                   $("#Waiting").show();
                   $('#FormMain').submit();     
            });  
            function TestLinkTarget(){
                $('#Destinazione').val('');
                var vPhp=$("#ConfPhp").val();
                $("#Destinazione").val(vPhp);
            }
            
            </script>          
        </div><?php
        break;          
      case "E":
        ?><div id="FormAggFlusso" >
            <div class="Plst Tipo" id="PulElabo" style="border-color:black;color:black;text-align: center;width: 100%;" ><img class="ImgIco" src="../images/Elaborazione.png">ELABORAZIONE</div>
            <table class="TabDip" >
            <tr>
                <td class="TdDip"><label style="width:200px;text-align:left;">Priorita'</label></td>
                <td class="TdDip">
                    <SELECT id="Priorita" Name="Priorita"   style="width: 100%;" >
                    <?php 
                    for($s=1;$s<=$TotLiv;$s++){
                        ?><option value="<?php echo $s; ?>" ><?php echo $s; ?></option><?php
                    }
                    ?>
                    </SELECT>
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Shell</label></td>
                <td class="TdDip">
                    
                            <SELECT id="SELELABORAZIONE" Name="SELELABORAZIONE" style="width: 100%;"   class="AggiungiField" >
                                <option value="" >..</option>
                                <option value="0" >----- TEST ----</option>
                                <?php   
                                $sql="SELECT ID_SH, SHELL 
                                FROM (
                                  SELECT DISTINCT ID_SH, SHELL
                                  FROM WORK_CORE.CORE_SH_ANAG 
                                  WHERE ID_SH NOT IN ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE SHELL != 'ConsolidaIdProcess.sh'  AND ID_WORKFLOW = $IdWorkFlow ) 
                                  AND WFS = 'Y'
                                  UNION ALL
                                  SELECT ID_SH, SHELL
                                  FROM WORK_CORE.CORE_SH_ANAG 
                                  WHERE ID_SH = ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE SHELL = 'ConsolidaIdProcess.sh' AND ID_WORKFLOW = $IdWorkFlow  ) 
								  AND ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE SHELL = 'ConsolidaIdProcess.sh' AND ID_WORKFLOW = $IdWorkFlow  ) NOT IN ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE SHELL != 'ConsolidaIdProcess.sh'  AND ID_WORKFLOW = $IdWorkFlow )
                                  AND WFS = 'Y'
                                )
                                ORDER BY SHELL";
                   echo $sql;
                                $stmt=db2_prepare($conn, $sql);
                                $result=db2_execute($stmt);
                                while ($row = db2_fetch_assoc($stmt)) {
                                    $IdSh=$row['ID_SH'];
                                    $ShellName=$row['SHELL'];
                                    ?><option value="<?php echo $IdSh; ?>" ><?php echo $ShellName; ?></option><?php
                                }

                                ?>
                            </SELECT>            
                        
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Nome</label></td>
                <td class="TdDip"><input type="text" id="NomeEla" Name="NomeEla" style="width: 100%;" class="AggiungiField" /></td>
            </tr>
            <tr hidden >
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Tags</label></td>
                <td class="TdDip"><input type="text" id="Tags" Name="Tags" style="width: 100%;" /></td>
            </tr>           
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Descrizione</label></td>
                <td class="TdDip"><textarea type="text" id="DescrEla" Name="DescrEla" style="width: 100%;"   /></td>
            </tr>       
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >ReadOnly</label></td>
                <td class="TdDip">
                    <select id="ReadOnly" name="ReadOnly" >
                      <option value="N" >No</option>
                      <option value="Y" >Si</option>
                    </select>
                </td>
            </tr>
			<tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Possibilità di non eseguire</label></td>
                <td class="TdDip">
                    <SELECT id="SaltaElab" Name="SaltaElab" style="width: 100%;"   class="ModificaField"  >
                        <option value="N" >No (obbligatorio Eseguire)</option>
                        <option value="S" >Si</option>
                    </SELECT>            
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Show Processing</label></td>
                <td class="TdDip">
                    <select id="ShowProc" name="ShowProc" >
                      <option value="N" >No</option>
                      <option value="Y" >Si</option>
                    </select>
                </td>
            </tr>			
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Mesi Validita'</label></td>
                <td class="TdDip">
                    <input type="text" id="SELVAL" Name="SELVAL"  style="width: 100%;" >         
                </td>
            </tr>
            </table>
            <button id="PulBack" >Back</button>
            <button id="PulAggDip" hidden >Aggiungi</button>
            <script>  
            
            $("#PulAggDip").click(function(){   
                   $('#Azione').val('AE');
                   $("#Waiting").show();
                   $('#FormMain').submit(); 
            });   

            $("#SELELABORAZIONE").change(function(){   
                   $('#NomeEla').val($('#SELELABORAZIONE option:selected').text());
            });   

			
            </script>          
        </div><?php
        break;          
      case "V":
        ?><div id="FormAggFlusso" >
            <div class="Plst Tipo" id="PulValid" style="border-color:black;color:black;text-align: center;width: 100%;" ><img class="ImgIco" src="../images/Valida.png">VALIDAZIONE</div>
            <table class="TabDip" >
            <tr>
                <td class="TdDip"><label style="width:200px;text-align:left;">Priorita'</label></td>
                <td class="TdDip">
                    <SELECT id="Priorita" Name="Priorita"   style="width: 100%;" >
                    <?php 
                    for($s=1;$s<=$TotLiv;$s++){
                        ?><option value="<?php echo $s; ?>" ><?php echo $s; ?></option><?php
                    }
                    ?>
                    </SELECT>
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Nome</label></td>
                <td class="TdDip"><input type="text" id="NomeVal" Name="NomeVal" style="width: 100%;" class="AggiungiField" /></td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Descrizione</label></td>
                <td class="TdDip"><textarea type="text" id="DescrVal" Name="DescrVal" style="width: 100%;"/></td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >External</label></td>
                <td class="TdDip">
                <select id="ExtVal" name="ExtVal" >
                  <option value="N" >No</option>
                  <option value="Y" >Yes</option>               
                </select>
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Mesi Validita'</label></td>
                <td class="TdDip">
                    <input type="text" id="SELVAL" Name="SELVAL"  style="width: 100%;"  >         
                </td>
            </tr>
            </table>
            <button id="PulBack" >Back</button>
            <button id="PulAggDip" hidden >Aggiungi</button>
            <script>    
            $("#PulAggDip").click(function(){   
                   $('#Azione').val('AV');
                   $("#Waiting").show();
                   $('#FormMain').submit(); 
            });             
            </script> 
        </div><?php
        break;      
    }
    ?>
    <script>
            $('#PulBack').click(function(){ 
                $("#InsDip").empty().load('../PHP/Wfs_AggDip.php', {
                           Tipo: '',
                           IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
                           IdFlu: <?php echo $IdFlu; ?>
                });
            });
            
            
            function TestAggiungi(){
                var vTest = true;
                $('.AggiungiField').each(function(){
                    if ( $(this).val() == '' ){ vTest = false;}
                });
                
                if (vTest){
                    $('#PulAggDip').show();
                } else {
                    $('#PulAggDip').hide();
                }
                $('#TopScroll').val($( window ).scrollTop());
                $('#BodyHeight').val($('body').height());
            }
            
            $('.AggiungiField').keyup(function(){
                TestAggiungi();
            });
            
            $('.AggiungiField').change(function(){
                TestAggiungi();
            });
            
            TestAggiungi();
    </script>
    <?php
}
?>

