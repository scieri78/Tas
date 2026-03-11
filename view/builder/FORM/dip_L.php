    <?php
	
	$ImgTipo="Link";
       if ( $TLink == "I" ){ $ImgTipo="Setting"; }
        ?>
		
		<div id="FormModFlusso" class="dip_L" >
		<input type="hidden" name="Azione" id="Azione"  value="<?php echo $Azione; ?>" >
		<input type="hidden" name="TipoDip" id="TipoDip" value="Link" >
		<input type="hidden" name="NomeDip" id="NomeDip" value="<?php echo $TabNome; ?>" >
        <table class="TabDip" >
			<tr>
			  <td  class="iconType"><img class="ImgIco" src="./images/<?php echo $ImgTipo; ?>.png" title="<?php echo $IdDip; ?>" />
			</td>
            <tr>
                <td class="TdDip"><label style="width:200px;text-align:left;">Priorita'</label></td>
                <td class="TdDip">
                    <SELECT id="Priorita" Name="Priorita" class="selectSearch ModificaField"  style="width: 100%;" >
                    <?php 
                    for($s=1;$s<=$TotLiv;$s++){
                        ?><option value="<?php echo $s; ?>" <?php if ($s == $TabPrio) { ?>selected<?php } ?> ><?php echo $s; ?></option><?php
                    }
                    ?>
                    </SELECT>
                </td>
            </tr>
            <tr>
                <td class="TdDip"><label style="width:200px;text-align:left;">Tipo</label></td>
                <td class="TdDip">
                    <input type="hidden" id="TipoLink" Name="TipoLink" value="<?php echo $TLink; ?>" >
                    <?php 
                    if ( $TLink == "I" ){ 
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
                    if ( $TLink == "I" ){ 
                      ?>
                       <input type="text" id="Destinazione" Name="Destinazione" style="width: 100%;" class="AggiungiField" value="<?php echo $TabDest; ?>" hidden />
                       <select class="selectSearch ModificaField" id="ConfPhp" name="ConfPhp" style="width: 100%;" onchange="TestLinkTarget()" >
                             <option value="" >..</option>
							 <?php if($IdDip){ ?>
                             <option value="<?php echo $TabDest; ?>" <?php if($TabDest == $TabDest){ ?>selected<?php } ?>><?php echo substr($TabDest, 0, -4); ?></option>
                             <?php
							 }
                          //   $ArrFilePhp=scandir($_SESSION['PREELAB']);
                             $ArrFilePhp=scandir("controller");
                              foreach( $ArrFilePhp as $SelFile ){
                                if ( $SelFile != "." and $SelFile != ".."  ){
                                  $find=false;
                                  foreach( $ArrListConfPhp as $Censito ){
                                      if ( $Censito == $SelFile ){
                                         $find=true;                                  
                                      }
                                  }
                                  if ( ! $find && (strpos($SelFile, 'openLink_') !== false)  ){
                                    $SelFile = str_replace("openLink_","",$SelFile); 
                                    ?><option value="<?php echo $SelFile; ?>" <?php if($SelFile == $TabDest){ ?>selected<?php } ?>><?php echo substr($SelFile, 0, -4); ?></option><?php
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
              <tr  class="parametri" <?php if($TabDest!="parametri.php"):?>style="display:none"<?php endif;?>>
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Gruppo</label></td>
                <td class="TdDip">
                 
                  
                <select class="selectSearch ModificaField" id="ID_PAR_GRUPPO" name="ID_PAR_GRUPPO" style="width: 100%;" >
                             <option value="" >..</option>
							 <?php							 
                              foreach( $DatiGruppo as $row ){?>
                              <option value="<?php echo $row['ID_PAR_GRUPPO']; ?>" <?php if( $TabIdParGruppo == $row['ID_PAR_GRUPPO']){ ?>selected<?php } ?>><?php echo $row['LABEL']; ?></option><?php
                               }?>
                        </select>  
						
                                   
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
                <td class="TdDip"><label  style="width:200px;text-align:left;" >Opzionale</label></td>
                <td class="TdDip">
  				    <select id="SELOPT" name="SELOPT" style="width: 100%;" onchange="TestLinkTarget()" >
                      <option value="N" <?php if($TabOpt=="N"){ ?>selected<?php } ?>>No</option>
                      <option value="Y" <?php if($TabOpt=="Y"){ ?>selected<?php } ?>>Yes</option>
                    </select> 
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
            
            function TestLinkTarget(){
                $('#Destinazione').val('');
                var vPhp=$("#ConfPhp").val();
                $("#Destinazione").val(vPhp);
            }
            
          
         

            $("#ConfPhp").change(function(){   
               if ( $('#ConfPhp').val() == 'parametri.php' ){ $('.parametri').show(); }else{
                 $('.parametri').hide(); 
                 $('#ID_PAR_GRUPPO').val("");               
            } 
            });   

			
            </script> 