
	<div id="Flu<?php echo $IdFlusso; ?>" 
	onmouseenter="selezionaFlusso( <?php echo json_encode($ArrPreFlussi); ?>, <?php echo json_encode($ArrSucFlussi); ?>)" 
	onmouseleave="deSelezionaFlusso( <?php echo json_encode($ArrPreFlussi); ?>, <?php echo json_encode($ArrSucFlussi); ?> )" "
	class="Flusso <?php echo $classFlusso; ?>"
	 onclick="OpenDettFlusso(<?php echo $IdWorkFlow; ?>,
							<?php echo $IdProcess; ?>,
							<?php echo $IdFlusso; ?>,
							'<?php echo $NomeFlusso; ?>',
							'<?php echo $DescFlusso; ?>',
							'<?php echo $ProcMeseEsame; ?>')" >
		<div class="TitFlu <?php echo "Tit".$classFlusso; ?>" title="<?php echo $IdFlusso; if ( "$DescFlusso" != "" ){ echo " - $DescFlusso"; }	 ?>" >
			<B><?php echo $NomeFlusso; ?></B>
			<input type="hidden" id="NomeFlu<?php echo $IdFlusso; ?>" name="NomeFlu<?php echo $IdFlusso; ?>" value="<?php echo $NomeFlusso; ?>">
		</div>		
        <img src="./images/<?php echo $Img; ?>.png" class="ImageEsito">
		<div id="StatusFlusso<?php echo $IdFlusso; ?>" onclick="MostraFlusso(<?php echo $IdWorkFlow; ?>,<?php echo $IdProcess; ?>,<?php echo $IdFlusso; ?>)"></div>
		<?php
		
		if ( "$CntLab" != "0" ){		
		echo '<i class="fa-regular fa-flask"></i>';

		}

		if($isAutorun)
		{
			echo '<img src="./images/stop.png" id="FlussoReadOnly" class="LockModeStop" >';
		}
				
		if ( $ARich != 0 ){
		  ?><img src="./images/Arichiesta.png" title="A Richiesta"   class="ImgSveglia" ><?php
		}
		
		if ( ${BlockCons} == "X" ){
		  ?><img src="./images/OneShot.png" title="OneShot"  style="width:17px;float:left;" ><?php
		}
        if ( "$NomeFlusso" != "UTILITY" ){	
		  //BlockCons
	      //"N"> Disabilitato con periodo Consolidato
	      //"S"> Abilitato con periodo Consolidato
	      //"X"> Abilitato senza svalidazione con periodo Consolidato
	      //"O"> Sempre Abilitato
		  $BlkCons=false;
		  $BlkRead=false;
 		  $NoRead=false;
		  if ( ${BlockCons} == "N" and ${PeriodCons} == "1" ){ $BlkCons=true; }
		  if ( ${BlockCons} == "S" and ${PeriodCons} == "0" ){ $BlkCons=true; }
		  if ( ${BlockCons} == "X" and ${PeriodCons} == "0" ){ $BlkCons=true; }
		  if ( ${BlockCons} == "O" and ${PeriodCons} == "1" ){ $NoRead=true; }
		  if ( ${BlockCons} == "S" and ${PeriodCons} == "1" ){ $NoRead=true; }
		  if ( ${BlockCons} == "X" and ${PeriodCons} == "1" ){ $NoRead=true; }
		  if ( ${Permesso}  == 0   and ${PeriodCons} == "1" ){ $BlkCons=true; }
		  if ( ${Permesso}  == 0   and ${PeriodCons} == "0" ){ $BlkRead=true; }
          if ( ${RdOnly}  != 0   ){ $BlkRead=true; }
		  if ( ( $WfsRdOnly != 0 or $BlkRead ) and ! $BlkCons and ! $NoRead ){
			?><img src="./images/ReadMode.png" id="FlussoReadOnly" style="position: absolute;width: 31px;right: 2px;bottom: 2px;" ><?php
		  } else {
            if ( $BlkCons ){
		     ?><img src="./images/Lock.png" style="position: absolute;width: 15px;right: 0px;bottom: 0px;" ><?php
		    } 
		  }
		}else{
				if ( ${RdOnly}  != 0 ){ $BlkRead=true; }
			//	if ( ${Permesso}  == 0 ){ $BlkRead=true; }
				if ( $WfsRdOnly != 0 || $BlkRead ){
				?><img src="./images/ReadMode.png" id="FlussoReadOnly" style="position: absolute;width: 31px;right: 2px;bottom: 2px;" ><?php
			}
			}
		

		if ( $StManual != 0  ){
		 ?><img src="./images/hand.png" title="Manual" class="ImgSveglia" ><?php
		}
		if ( $CntTCD != 0  ){
		 ?><img src="./images/ConfermoDato.png" class="ImgSveglia" ><?php
		}
		
		if ( $CntWar != 0  ){
		 ?><img src="./images/Warning.png" title="Warning" class="ImgSveglia" ><?php
		}
		
		if ( $CntCD != 0 ){
		 
		  if ( $CntIZ != 0 ){
		    ?><img id="IcoRun<?php echo $IdFlusso ?>" class="ImgSveglia" src="./images/Loading.gif" ><?php
		  } else {
			?><img id="IcoSveg<?php echo $IdFlusso ?>" class="ImgSveglia" src="./images/Sveglia.png" onclick="ForzaScodatore()" style="coursor:pointer;" ><?php  
		  }
		  
		  ?><img id="IcoRefresh<?php echo $IdFlusso ?>" class="ImgSveglia" src="./images/refresh.png" hidden ><?php
		}
		
		if ( "$Warning" == "-1" ){ ?><label class="ImgSveglia" style="width:25px;color:red;" ><img class="ImgAlert" src="./images/Alert.png" title="Strato Rimpiazzato"></label><?php }
		if ( "$Warning" > "0" ){ ?><label class="ImgSveglia" style="width:25px;color:red;" ><img class="ImgAttention" src="./images/Attention.png" title="<?php if ( "$Warning" > "1" ){ echo $Warning; }  ?>" ></label><?php }
		?>
		
	</div>
