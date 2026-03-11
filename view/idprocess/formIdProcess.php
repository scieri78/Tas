<?php if($IdWorkFlow){
	?>
<div id="newIdProcess">
<div class="rowIdProcess">
<div class="gestionerow" id="gestioneEsercizio">
 <select onchange="setDecrizione()" id="Esercizio" name="Esercizio"  class="FieldMand">
        <option value="" >Seleziona Esercizio</option>
        <?php 
            $Anno=date("Y");
            $Mese=date("m");
            
            if ( $IdFreq == "M" ){
              $dateVal = date("Y-m-t");
			}
			if ( $IdFreq == "Q" ){
              $dateM = date("m");
			  if ( $dateM == 1 or $dateM == 2 or $dateM == 3 ){
			    $dateVal = date("Y-m-t", strtotime($Anno.'-03-01')); 
			  }
			  if ( $dateM == 4 or $dateM == 5 or $dateM == 6 ){
			    $dateVal = date("Y-m-t", strtotime($Anno.'-06-01')); 
			  }
			  if ( $dateM == 7 or $dateM == 8 or $dateM == 9 ){
			    $dateVal = date("Y-m-t", strtotime($Anno.'-09-01')); 
			  }
			  if ( $dateM == 10 or $dateM == 11 or $dateM == 12 ){
			    $dateVal = date("Y-m-t", strtotime($Anno.'-12-01')); 
			  }
			}		
			if ( $IdFreq == "A" ){
              $dateVal = date("Y-m-t", strtotime($Anno.'-12-01')); 
			}
            date_add($dateVal, date_interval_create_from_date_string("1 months"));  
    
            ?><option value="<?php echo $dateVal; ?>" <?php if ( $Sel_New_Process_Period == $dateVal) { ?> selected <?php } ?> ><?php echo $dateVal; ?></option><?php
            
            for( $m=$Mese-1; $m>=1 ;$m--){
                  $mm=str_pad($m,2,0,STR_PAD_LEFT);
				  if ( $IdFreq == "A" ){
					if ( $mm == "12" ){
					  $dd=date("Y-m-t", strtotime($Anno.'-'.$mm.'-01'));  
					  ?><option value="<?php echo $dd; ?>" <?php if ( $Sel_New_Process_Period == $dd ) { ?> selected <?php } ?> ><?php echo $dd; ?></option><?php
					}
				  }else{
					if ( $IdFreq == "Q" ){
				      if ( $mm == "3" or $mm == "6" or $mm == "9" or $mm == "12" ){
					    $dd=date("Y-m-t", strtotime($Anno.'-'.$mm.'-01'));  
					    ?><option value="<?php echo $dd; ?>" <?php if ( $Sel_New_Process_Period == $dd ) { ?> selected <?php } ?> ><?php echo $dd; ?></option><?php
					  } 
				    } else{
					  $dd=date("Y-m-t", strtotime($Anno.'-'.$mm.'-01'));
                      ?><option value="<?php echo $dd; ?>" <?php if ( $Sel_New_Process_Period == $dd ) { ?> selected <?php } ?> ><?php echo $dd; ?></option><?php
				    }
				  }
                  
            }
            
            $Gap=$Anno-2015;
            for( $a=$Anno-1; $a>$Anno-$Gap ; $a-- ){
                for( $m=12; $m>=1 ;$m--){
                  $mm=str_pad($m,2,0,STR_PAD_LEFT);
                   if ( $IdFreq == "A" ){
					if ( $mm == "12" ){
					  $dd=date("Y-m-t", strtotime($a.'-'.$mm.'-01'));  
					  ?><option value="<?php echo $dd; ?>" <?php if ( $Sel_New_Process_Period == $dd ) { ?> selected <?php } ?> ><?php echo $dd; ?></option><?php
					}
				  }else{
					if ( $IdFreq == "Q" ){
				      if ( $mm == "3" or $mm == "6" or $mm == "9" or $mm == "12" ){
					    $dd=date("Y-m-t", strtotime($a.'-'.$mm.'-01'));  
					    ?><option value="<?php echo $dd; ?>" <?php if ( $Sel_New_Process_Period == $dd ) { ?> selected <?php } ?> ><?php echo $dd; ?></option><?php
					  } 
				    } else{
					  $dd=date("Y-m-t", strtotime($a.'-'.$mm.'-01'));
                      ?><option value="<?php echo $dd; ?>" <?php if ( $Sel_New_Process_Period == $dd ) { ?> selected <?php } ?> ><?php echo $dd; ?></option><?php
				    }
				  }
              }
            }
        ?>
    </select>
	
	<input placeholder="Descrizione" type="text" name="Descr" id="Descr" class="FieldMand">


	<button style="display:none" id="SaveIdProcess" onclick="xSaveIdProcess();return false;" class="btn"><i class="fa-solid fa-plus-circle"> </i> Salva</button>
	</div>
	<div class="gestionerow" id="gestioneCopy">
	<select id="FromId" name="FromId" onchange="TestsaveCopy(this.value)" class="FieldMandCopy" style="width:150px;height:30px;">
        <option value="">Copy From</option>
        <?php
            foreach ( $ArrIdP as $IdP ){
                  ?><option value="<?php echo $IdP; ?>" <?php if ( $SelFromId == $IdP ) { ?> selected <?php } ?> ><?php echo $IdP; ?></option><?php
            }
        ?>
    </select>
	<select id="ToId" name="ToId" onchange="TestsaveCopy(this.value)"  class="FieldMandCopy" style="width:150px;height:30px;">
        <option value="" >Copy To</option>
        <?php
            foreach ( $ArrIdP as $IdP ){
                  ?><option value="<?php echo $IdP; ?>" <?php if ( $SelToId == $IdP ) { ?> selected <?php } ?> ><?php echo $IdP; ?></option><?php
            }
        ?>
    </select>
	
<button style="display:none" id="ShowCopy"  onclick="showDetTable();return false;" class="btn"><i class="fa-solid fa-eye"></i> Mostra</button>
<button style="display:none" id="CopyIdProcess" onclick="copyFormIdProcess();return false;" class="btn"><i class="fa-solid fa-copy"></i> Copia</button>
</div>
	<div class="gestionerow" id="gestioneCancella">
<select id="SvuotaId" onchange="SvuotaIdProcess()" name="SvuotaId" class="FieldMandRem" style="width:150px;height:30px;">
        <option value="" >Seleziona Svuota</option>
        <?php
            foreach ( $ArrIdP as $IdP ){
                  ?><option value="<?php echo $IdP; ?>" <?php if ( $SvuotaId == $IdP ) { ?> selected <?php } ?> ><?php echo $IdP; ?></option><?php
            }
        ?>
    </select>

<select id="CancellaId" onchange="CancellaIdProcess()" name="CancellaId" class="FieldMandRem" style="width:150px;height:30px;">
        <option value="" >Seleziona Cancella</option>
        <?php
            foreach ( $ArrIdP as $IdP ){
                  ?><option value="<?php echo $IdP; ?>" <?php if ( $CancellaId == $IdP ) { ?> selected <?php } ?> ><?php echo $IdP; ?></option><?php
            }
        ?>
    </select>
	<button style="display:none" id="svuotaIdProcess" onclick="eliminaIdProcess();return false;" class="btn"><i class="fa-regular fa-trash-can"></i> Svuota</button>
	<button style="display:none" id="cancellaIdProcess" onclick="eliminaIdProcess();return false;" class="btn"><i class="fa-solid fa-trash"></i> Cancella</button>
</div>
</div>

<div class="rowIdProcess">



</div>
<?php  }  ?>
