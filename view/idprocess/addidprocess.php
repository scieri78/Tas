<?php if($IdWorkFlow){
	?>
<table id="idTabella" class="display dataTable">
     <thead class="headerStyle">
    <tr>
       <th style="width: 40px;"><img src="./images/Cestino.png" style="width:20px;" ></th>
       <th style="width: 100px;">IdProcess</th>
       <th style="width: 100px;">Descrizione</th>
       <th style="width: 80px;">Anno Esame</th>
       <th style="width: 80px;">Mese Esame</th>
       <th style="width: 80px;">Tipologia</th>
       <th style="width: 80px;">Stato</th>
       <th style="width: 80px;">Apertura</th>
       <th style="width: 80px;">Inz. Val. Legami</th>      
      <!-- <th style="width: 80px;"></th>-->
       <th style="width: 80px;">ReadOnly</th>
        <th style="width: 80px;">Utente</th>

    </tr>
	</thead>
	<tbody>
    <?php 
       $ArrIdP=array();
       
       foreach ($DatiListaIdProc as $row) {
           $IdProc=$row['ID_PROCESS']; 
           $DescrProc=$row['DESCR']; 
           $EserEsameProc=$row['ESER_ESAME'];
           $MeseEsameProc=$row['MESE_ESAME'];  
           $EserMeseProc=$row['ESER_MESE']; 
           $FlagProc=$row['FLAG_CONSOLIDATO']; 
           $TipoProc=$row['TIPO']; 
           $ReadOnly=$row['READONLY'];
           $FlgStato=$row['FLAG_STATO'];
           $data_apertura=$row['DATA_APERTURA'];
           $utente=$row['UTENTE'];         
           $inizVal=$row['INZ_VAL'];         
      
           switch ($FlgStato) {
                case 'C': 
                       $Stato = 'Chiuso';          
                   break;
                     case 'A':  
                      $Stato = 'Aperto';              
                   break;
                   case 'S':  
                    $Stato = 'Salvato';               
                 break;
               
            }

		   
           array_push($ArrIdP,$IdProc);
		   $InSvec="";
		   $Svec=0;
		   $LabSvec="Add";
		   if ( in_array($IdProc,$ArrSvecIdP) ){
		     $InSvec="InSvec";
             $Svec=1;			 
			 $LabSvec="Rem";
		   }

       //    if ($FlgStato != "C" ){          
             ?>
             <tr class="<?php echo $InSvec; ?>" >
                 <td>
                 <?php if(!$FlagProc) :?> 
                 <input type="checkbox"  id="CheckSvec<?php echo $IdProc; ?>" <?php if ( $Svec == "1" ) { ?>checked<?php } ?> 
				              onclick="<?php echo $LabSvec; ?>SveccIdp(<?php echo $IdProc; ?>)" >
                  <?php endif;?>
              </td>   
                 <td style="width: 200px;"><?php echo $IdProc; ?><?php if($FlagProc) :?> <strong style="color:blue">CONS</strong> <?php endif;?> </td>
                 <td style="width: 200px;"><?php echo $DescrProc; ?></td>
                 <td style="width: 80px;"><?php echo $EserEsameProc; ?></td>
                 <td style="width: 80px;"><?php echo $MeseEsameProc; ?></td>
                 <td style="width: 100px;"><?=($TipoProc=="R")?"Restatement":"Closing";
              /*   switch ($TipoProc){
                  case 'R': echo "Restatement"; break;
				  default: echo "Closing"; break;
                 }*/
                 ?></td>
               <td style="width: 80px;"><?php echo $Stato; ?></td>  
               <td style="width: 80px;"><?php echo $data_apertura; ?></td>  
               <td style="width: 80px;">
                   <?php
                       $this->renderSelectBox($optiondate,'INZVAL_'.$IdProc,$inizVal,$IdProc);                        
                        ?>                
             <!--  <td style="width: 80px;">
                <?php if(!$FlagProc) :?> 
              <button id="crearilascio" onclick="AllineaDate(<?php echo $IdProc; ?>);return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-calendar-days"></i> Allinea</button>
                <?php endif;?>
               </td>  -->
              <td class="<?php echo $InSvec; ?>" style="width: 80px;">   
                <?php if(!$FlagProc) :?>          
              <?php
              if ( $ReadOnly == "Y" ){
                ?>
                <img title="Utente non abilitato a questa CHIUSURA" src="./images/ReadMode.png" onclick="removeReadonly(<?php echo $IdProc; ?>)" id="FlussoReadOnly" style="width: 36px;">
			<!--	<i class="fa-solid fa-glasses" onclick="removeReadonly(<?php echo $IdProc; ?>)" title="Remove ReadOnly" width="30px" style="cursor:pointer;"></i>
				<img onclick="removeReadonly(<?php echo $IdProc; ?>)" title="Remove ReadOnly" src="./images/ReadMode.png" width="30px" style="cursor:pointer;"> -->
				<?php
              } else {
                ?>
				<i title="Utente abilitato a questa CHIUSURA" class="fa-solid fa-pencil" onclick="AddReadOnly(<?php echo $IdProc; ?>);return false;"></i>
				<?php
              }
              ?>
              <?php endif;?>
              </td>
              <td style="width: 80px;"><?php echo $utente; ?></td>  
              </tr>
              <?php
         //  }
       }     
     ?>
</tbody>
</table>
</div>

<div id="copyIdProcContainer" class=""></div>

<?php  }  ?>







