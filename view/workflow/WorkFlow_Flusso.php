<style>
  .PreSel{
     box-shadow:0px 0px 5px 1px blue !important	;
  }
  .PostSel{
	 box-shadow:0px 0px 5px 1px red !important ;
  }
  .ImgPeriodCons{
    position: relative;
    left: 22px;
    width: 30px;
    top: 42px;
	z-index:9999;
  }  
  .LockMode{
	position: absolute;
    width: 17px !important;
    right: 5px;
  }
  
</style>
<?php
session_cache_limiter(‘private_no_expire’);
session_start() ;

include './GESTIONE/connection.php';
include './GESTIONE/SettaVar.php';
//include './GESTIONE/login.php';
//include './GESTIONE/sicurezza.php';

if ( 1 )  {
    $Uk = $_SESSION['CodUk'];
    $IdWorkFlow=$_REQUEST['IdWorkFlow'];   
    $IdProcess=$_POST['IdProcess']; 
    $ProcMeseEsame=$_POST['ProcMeseEsame'];
	
	
    if ( "$IdWorkFlow" == "" or "$IdProcess" == ""  ) { exit; }

    $IdFlusso=$_POST['IdFlusso'];  
 
    

       //LEGAMI
       $ArrLegami=array();
       $SqlList="SELECT ID_LEGAME,LIV,ID_FLU,PRIORITA,TIPO,ID_DIP,
	   ( SELECT FLUSSO FROM WFS.FLUSSI WHERE ID_FLU = L.ID_FLU ) NOME_FLUSSO
	   FROM WFS.LEGAME_FLUSSI L
	   WHERE ID_WORKFLOW = $IdWorkFlow 
	   AND DECODE(VALIDITA,null,' $ProcMeseEsame ',' '||VALIDITA||' ') like '% $ProcMeseEsame %'
	   AND INZ_VALID <= INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
	   AND FIN_VALID >= INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
	   AND ( ID_FLU = $IdFlusso OR ( TIPO = 'F' AND ID_DIP = $IdFlusso ) )
	   ORDER BY LIV, NOME_FLUSSO, TIPO, ID_DIP";
       $stmt=db2_prepare($conn, $SqlList);
	   $res=db2_execute($stmt);
	   if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); } 
       while ($row = db2_fetch_assoc($stmt)) {
           $Id=$row['ID_LEGAME']; 
           $Liv=$row['LIV']; 
           $IdFlu=$row['ID_FLU']; 
           $Prio=$row['PRIORITA']; 
           $Tipo=$row['TIPO']; 
           $IdDip=$row['ID_DIP']; 
               
           array_push($ArrLegami,array($Id,$Liv,$IdFlu,$Prio,$Tipo,$IdDip));
       }
	   
	//DIP FLUSSI
	$ArrPreFlussi=array();       
	foreach( $ArrLegami as $Legame ){
		//ID_LEGAME,LIV,ID_FLU,PRIORITA,TIPO,ID_DIP
		$IdFlu=$Legame[2];
		$Tipo=$Legame[4];
		$IdDip=$Legame[5];
		if ( "$Tipo" == "F" and "$IdFlu" == "$IdFlusso" ){
		   array_push($ArrPreFlussi,$IdDip);
		}
	}
	
	$ArrSucFlussi=array();
	   foreach( $ArrLegami as $Legame ){
		//ID_LEGAME,LIV,ID_FLU,PRIORITA,TIPO,ID_DIP
		$IdFlu=$Legame[2];
		$Tipo=$Legame[4];
		$IdDip=$Legame[5];
		if ( "$Tipo" == "F" and "$IdDip" == "$IdFlusso" ){
		   array_push($ArrSucFlussi,$IdFlu);
		}
	}

 
    $SqlList="
	WITH W_ABILITATE AS(
	  SELECT ID_DIP , TIPO FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = $IdWorkFlow AND OPT NOT IN ('Y','M') AND DECODE(VALIDITA,null,' $ProcMeseEsame ',' '||VALIDITA||' ') like '% $ProcMeseEsame %'
	  AND INZ_VALID <= INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
	  AND FIN_VALID >= INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
	)
	SELECT ID_FLU,FLUSSO,DESCR, DECODE(BLOCK_CONS,'X','S',BLOCK_CONS) BLOCK_CONS
	,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = F.ID_WORKFLOW AND NVL(ID_PROCESS,0) = NVL('$IdProcess',0) AND ID_FLU = F.ID_FLU AND ESITO = 'E' AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) KO
	,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = F.ID_WORKFLOW AND NVL(ID_PROCESS,0) = NVL('$IdProcess',0) AND ID_FLU = F.ID_FLU AND ESITO IN ( 'F', 'W' ) AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE ) ) OK
	,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = F.ID_WORKFLOW AND NVL(ID_PROCESS,0) = NVL('$IdProcess',0) AND ID_FLU = F.ID_FLU AND ESITO IN ( 'W' ) AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE ) ) WAR
	,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = F.ID_WORKFLOW AND NVL(ID_PROCESS,0) = NVL('$IdProcess',0) AND ID_FLU = F.ID_FLU AND ESITO = 'I' AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE ) ) IZ
	,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = F.ID_WORKFLOW AND NVL(ID_PROCESS,0) = NVL('$IdProcess',0) AND ID_FLU = F.ID_FLU AND ESITO = 'N' AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE ) ) TD
	,( SELECT count(*) FROM WFS.LEGAME_FLUSSI  WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_FLU = F.ID_FLU AND TIPO NOT IN ( 'F', 'W' ) AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) TT
	,( SELECT count(*) FROM WFS.LEGAME_FLUSSI  WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_FLU = F.ID_FLU AND TIPO IN( 'F', 'W' ) AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE ) ) TTF
	,( SELECT count(*) FROM WFS.CODA_RICHIESTE WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_FLU = F.ID_FLU AND ID_PROCESS = $IdProcess   ) CODA
	,( SELECT count(*) FROM WFS.AUTORIZZAZIONI WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_FLUSSO = F.ID_FLU 
	     AND ID_GRUPPO IN ( SELECT ID_GRUPPO FROM WFS.ASS_GRUPPO WHERE ID_UK = $Uk AND ID_WORKFLOW = $IdWorkFlow  )
         AND ID_GRUPPO = ( SELECT ID_GRUPPO FROM WFS.GRUPPI WHERE ID_WORKFLOW = F.ID_WORKFLOW AND GRUPPO = 'READ')		 
	) RDONLY
	,( SELECT count(*) FROM WFS.AUTORIZZAZIONI WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_FLUSSO = F.ID_FLU 
	     AND ID_GRUPPO IN ( SELECT ID_GRUPPO FROM WFS.ASS_GRUPPO WHERE ID_UK = $Uk AND ID_WORKFLOW = $IdWorkFlow )	 
	) PERMESSO
	,( SELECT count(*) FROM WORK_CORE.ID_PROCESS WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_PROCESS = $IdProcess AND READONLY = 'Y' ) WFSRDONLY
	,( SELECT count(*) 
	FROM WFS.LEGAME_FLUSSI L JOIN WFS.ULTIMO_STATO U ON L.ID_WORKFLOW = U.ID_WORKFLOW AND L.ID_FLU = U.ID_FLU AND L.TIPO = U.TIPO AND L.ID_DIP = U.ID_DIP
	WHERE U.ID_WORKFLOW = F.ID_WORKFLOW AND U.ID_FLU = F.ID_FLU AND U.TIPO = 'C' AND  U.NOTE = 'Confermo il dato in tabella' AND U.ID_PROCESS = $IdProcess
	) TCD
	,( SELECT count(*) FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_FLU = F.ID_FLU AND (
	  ( TIPO = 'E' AND ID_DIP IN ( SELECT ID_ELA FROM WFS.ELABORAZIONI WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_SH = 0 ) )
	  OR
	  ( TIPO = 'C' AND ID_DIP IN ( SELECT ID_CAR ID_DIP FROM WFS.CARICAMENTI  WHERE ID_WORKFLOW  = $IdWorkFlow AND NOME_INPUT = 'WFS_TEST' ))
	)
	) CNTLAB
	,( SELECT count(*) FROM WORK_CORE.ID_PROCESS WHERE (ESER_ESAME,MESE_ESAME) IN ( SELECT ESER_ESAME,MESE_ESAME FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess ) AND FLAG_CONSOLIDATO = 1 AND ID_TEAM = ( SELECT ID_TEAM FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess ) AND ID_WORKFLOW = $IdWorkFlow ) CONS
	,( SELECT SUM(U.WARNING)
	FROM WFS.LEGAME_FLUSSI L 
	JOIN WFS.ULTIMO_STATO U 
	ON    L.ID_WORKFLOW = U.ID_WORKFLOW
	  AND L.ID_FLU = U.ID_FLU
	  AND L.TIPO = U.TIPO
	  AND L.ID_DIP = U.ID_DIP
	WHERE 1=1
	AND U.ID_WORKFLOW = F.ID_WORKFLOW 
	AND U.ID_FLU = F.ID_FLU 
	AND U.ID_PROCESS = $IdProcess
	AND U.WARNING > 0
	) WARNING
	,( SELECT count(*)
	FROM WFS.LEGAME_FLUSSI L 
	JOIN WFS.ULTIMO_STATO U 
	ON    L.ID_WORKFLOW = U.ID_WORKFLOW
	  AND L.ID_FLU = U.ID_FLU
	  AND L.TIPO = U.TIPO
	  AND L.ID_DIP = U.ID_DIP
	WHERE 1=1
	AND U.ID_WORKFLOW = F.ID_WORKFLOW 
	AND U.ID_FLU = F.ID_FLU 
	AND U.ID_PROCESS = $IdProcess
	AND U.WARNING < 0
	) ALERT
	,( SELECT count(*)
	FROM WFS.LEGAME_FLUSSI L
	WHERE 1=1
	AND L.ID_WORKFLOW = F.ID_WORKFLOW 
	AND L.ID_FLU = F.ID_FLU 
	AND L.OPT = 'M'
	AND 0 = ( 
	  SELECT count(*) 
	  FROM WFS.ULTIMO_STATO U 
	  WHERE 1=1
	  AND U.ID_PROCESS = $IdProcess
      AND L.ID_WORKFLOW = U.ID_WORKFLOW
	  AND L.ID_FLU = U.ID_FLU
	  AND U.ESITO IN ('W','F')
	  AND (U.TIPO,U.ID_DIP) IN ( SELECT T.TIPO,T.ID_DIP
	                             FROM WFS.LEGAME_FLUSSI T
	                             WHERE 1=1
	                             AND T.ID_WORKFLOW = U.ID_WORKFLOW 
	                             AND T.ID_FLU = U.ID_FLU 
	                             AND T.OPT = 'M' 
								 )
	  )
	) MANUAL
	,( SELECT count(*) FROM WFS.REGIME_LEGAME_FLUSSI WHERE ID_FLU = F.ID_FLU AND ID_REGIME IN (SELECT ID_REGIME FROM WFS.REGIME_FLUSSI WHERE ID_WORKFLOW = $IdWorkFlow AND REGIME = 'A RICHIESTA')) ARICH
	FROM WFS.FLUSSI F 
	WHERE ID_FLU = $IdFlusso AND ID_WORKFLOW = $IdWorkFlow 
	ORDER BY FLUSSO";
	//echo $SqlList;
    $stmt=db2_prepare($conn, $SqlList);
    $res=db2_execute($stmt);
    if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); } 
	while ($row = db2_fetch_assoc($stmt)) {
	   $NomeFlusso=$row['FLUSSO']; 
	   $DescFlusso=$row['DESCR'];  
	   $BlockCons=$row['BLOCK_CONS'];
	   $PeriodCons=$row['CONS'];
	   
	   $CntKO=$row['KO'];  
	   $CntOK=$row['OK'];  
	   $CntIZ=$row['IZ'];  
	   $CntTD=$row['TD'];  
	   $CntTT=$row['TT'];  
	   $CntTTF=$row['TTF'];
	   $CntWar=$row['WAR'];
	   
	   $CntCD=$row['CODA'];
	   $RdOnly=$row['RDONLY'];
	   $Permesso=$row['PERMESSO'];
	   $WfsRdOnly=$row['WFSRDONLY'];
	   $ARich=$row['ARICH'];
	   
	   
	   $Alert=$row['ALERT'];
	   if ( "$Alert" != 0 ){ $Warning="-1"; }else{ $Warning=$row['WARNING']; }
	   
	   $StManual=$row['MANUAL'];
	   $CntTCD=$row['TCD'];
	   $CntLab=$row['CNTLAB'];	   
	}		
	
	$Stato='F';
	if ( $CntTTF != 0 ){
		            
           $Errore=0;
		   $Note="";
		   $Stato='N';
		   
					
           $CallPlSql='CALL WFS.K_WFS.TestSottoFlussi(?, ?, ?, ?, ?, ? )';
           $stmt = db2_prepare($conn, $CallPlSql);
           db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
           db2_bind_param($stmt, 2, "IdProcess"   , DB2_PARAM_IN);
           db2_bind_param($stmt, 3, "IdFlusso"    , DB2_PARAM_IN);
		   db2_bind_param($stmt, 4, "Stato"       , DB2_PARAM_OUT);
           db2_bind_param($stmt, 5, "Errore"      , DB2_PARAM_OUT);
           db2_bind_param($stmt, 6, "Note"        , DB2_PARAM_OUT);
           $res=db2_execute($stmt);
           
           if ( ! $res) {
             echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg();
           }
           
           if ( $Errore != 0 ) {
             echo "PLSQL Procedure Calling Error $Errore: ".$Note;
           } 
		   /*
           if ( "$Stato" == "S" ){
			   if (  $CntOK == 0 and $CntIZ == 0 ) { $CntOK=$CntOK+1; $CntTT=$CntTT+1; }
		   } else {
		      if (  $CntOK == $CntTT and $CntTT != 0 ) {$CntOK=$CntOK+1; $CntTT=$CntTT+1; } 		   
		   }
		   */
	}
	
	if (  $CntKO != 0 ) { 
	   $Esito="E";
	} else {
	   if (  $CntOK == $CntTT and $CntTT != 0 and ( "$Stato" == "S" or "$Stato" == "F" ) ){ 	
	      $Esito='F';
	   } else {
		 if (  $CntOK == 0 and $CntIZ == 0  and ( "$Stato" == "N" or "$Stato" == "F" or "$Stato" == "S" ) ) { 	  
		   $Esito='N';
		 }else{
		   if (  $CntIZ != 0  ) {
		      $Esito='I';
		   } else {
			  $Esito='C';
		   }
		 }
	   }
	}
	
	if ( "$NomeFlusso" == "ZUTILITY" ){
	  $NomeFlusso="UTILITY";
	  $Img="Optional";
	  $classFlusso="FlussoUtility";   
	  $ColTit="black";  
    }else{
	  $Img="DaEseguire";
	  $classFlusso="FlussoDaEseguire";   
	  $ColTit="black";
	  switch ( $Esito ){
	  case 'E' :
	  	$Img="Terminato";
	  	$classFlusso="FlussoTerminato";
	  	$ColTit="darkred";
	  	$Mostra="";
	  	break;
	  case 'I':
	  	$Img="InEsecuzione";
	  	$classFlusso="FlussoInEsecuzione"; 
	  	$ColTit="orange";
	  	$Mostra="";
	  	break;
	  case 'F':
	  	$classFlusso="FlussoEseguito";
	  	$Img="Eseguito"; 
	  	$ColTit="darkgreen";
	  	break;
	  case 'C':
	  	$classFlusso="FlussoIncompleto"; 
	  	$Img="Incompleto";
          $ColTit="darkslateblue";		
	  	break;
	  }								
	}
	
		  
	?>
	<div id="Flu<?php echo $IdFlusso; ?>" class="Flusso <?php echo $classFlusso; ?>" onclick="OpenDettFlusso(<?php echo $IdWorkFlow; ?>,<?php echo $IdProcess; ?>,<?php echo $IdFlusso; ?>,'<?php echo $NomeFlusso; ?>','<?php echo $DescFlusso; ?>')" >
		<img src="./images/PreStep.png"   id="DipP<?php echo $IdFlusso; ?>" class="LinkDipP" hidden>
		<img src="./images/PostStep.png" id="DipS<?php echo $IdFlusso; ?>" class="LinkDipS" hidden>	
		<div class="TitFlu <?php echo "Tit".$classFlusso; ?>" title="<?php echo $IdFlusso; if ( "$DescFlusso" != "" ){ echo " - $DescFlusso"; }	 ?>" ><B style="color:<?php echo $ColTit; ?>" ><?php echo $NomeFlusso; ?></B></div>		
        <img src="./images/<?php echo $Img; ?>.png" class="ImageEsito">
		<div id="StatusFlusso<?php echo $IdFlusso; ?>" onclick="OpenStatusFlusso(<?php echo $IdWorkFlow; ?>,<?php echo $IdProcess; ?>,<?php echo $IdFlusso; ?>)"></div>
		<?php
		
		if ( "$CntLab" != "0" ){
		  ?><img src="./images/Lab.png" title="Laboratorio"  class="ImgSveglia" ><?php
		}
		$BlkCons=false; 
		$NotBlkCons=false;
		if ( "${BlockCons}" == "Y" and "${PeriodCons}" != "0" ){ $BlkCons=true; }
		if ( $Permesso == 0 and "${PeriodCons}" != "0"  ){ $BlkCons=true; }
	    if ( "${BlockCons}" == "S" and "${PeriodCons}" != "0" and $RdOnly == 0 ){ $NotBlkCons=true; }
		if ( $ARich != 0 ){
		  ?><img src="./images/Arichiesta.png" title="A Richiesta"   class="ImgSveglia" ><?php
		}
        if ( "$NomeFlusso" != "UTILITY" ){		
          if ( $BlkCons ){
		   ?><img src="./images/Lock.png"    class="LockMode" ><?php
		  } else {			   
		       if ( $RdOnly != 0 or $Permesso == 0 or $WfsRdOnly != 0 or $BlkCons ){
		        if ( ! $NotBlkCons ){
		  		if ( "${PeriodCons}" != "0" ){ 
		            ?><img src="./images/Lock.png"        id="FlussoReadOnly" class="LockMode" ><?php
		  		}else{
		  		  ?><img src="./images/ReadMode.png"    id="FlussoReadOnly" class="FlussoReadMode" ><?php
		  		}
		  	  }
		       }	
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
		
		if ( "$Warning" == "-1" ){ ?><label class="ImgSveglia" style="width:25px;color:red;" ><img class="ImgIco" src="./images/Alert.png" title="Strato Rimpiazzato"></label><?php }
		if ( "$Warning" > "0" ){ ?><label class="ImgSveglia" style="width:25px;color:red;" ><img class="ImgAttention" src="./images/Attention.png" title="<?php if ( "$Warning" > "1" ){ echo $Warning; }  ?>" ></label><?php }
		?>
		
	</div>
	<?php
       
}
?>
<script>
    var vSel = $('#SelFlusso').val();
    //$('#Flu'+vSel).css('border','1px solid white').addClass('ingrandFlu');
	$('#Flu'+vSel).addClass('ingrandFlu');
	
	  $('#Flu<?php echo $IdFlusso; ?>').mouseenter(function(){ <?php
			foreach( $ArrPreFlussi as $IdFlu ){
			  ?>$('#DipP<?php echo $IdFlu; ?>').show();
			  $('#Flu<?php echo $IdFlu; ?>').addClass('PreSel');
			  <?php
			}
			
			foreach( $ArrSucFlussi as $IdFlu ){
			  ?>$('#DipS<?php echo $IdFlu; ?>').show();
			  $('#Flu<?php echo $IdFlu; ?>').addClass('PostSel');
			  <?php
			}
		?>
	  });

	  $('#Flu<?php echo $IdFlusso; ?>').mouseleave(function(){ <?php
			foreach( $ArrPreFlussi as $IdFlu ){
			  ?>$('#DipP<?php echo $IdFlu; ?>').hide();
			  $('#Flu<?php echo $IdFlu; ?>').removeClass('PreSel');
			  <?php
			}
			
			foreach( $ArrSucFlussi as $IdFlu ){
			  ?>$('#DipS<?php echo $IdFlu; ?>').hide();
			  $('#Flu<?php echo $IdFlu; ?>').removeClass('PostSel');
			  <?php
			}
		?>
	 });
     function ForzaScodatore(){
                   var input = $("<input>")
                  .attr("type", "hidden")
                  .attr("name", "ForzaScodatore")
                  .val(1);
                  $('#MainForm').append($(input));
                  
                //   $("#Waiting").show();
                   $('#MainForm').submit();    		 
	 }
</script>
