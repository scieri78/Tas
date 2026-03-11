<?php

include '../GESTIONE/connection.php';
include '../GESTIONE/SettaVar.php';
include '../GESTIONE/login.php';
include '../GESTIONE/sicurezza.php';

if ( "$find" == "1" )  {
$TServer="Jiak";
	$IdWorkFlow=$_REQUEST['IdWorkFlow'];
	$IdFlu=$_POST['IdFlu'];
	$Tipo=$_POST['Tipo'];
		

	if ( "$conn" == "Resource id #4" ) {
	  $conn = db2_connect($db2_conn_string, '', '');
	}
		
	?><table class="TabDett ExcelTable" >
		<tr class="borderbot" >
			 <th class="thMod" colspan=2 >
             <?php if ( "$TServer" != "PROD USER" ){  ?> 
             <div id="AggDipIN<?php echo $IdFlu; ?>" class="button" style="width:200px;" >
			   <img class="ImgIco" src="../images/Aggiungi.png" >Aggiungi Dipendenza
			 </div>
			 <script>
				$("#AggDipIN<?php echo $IdFlu; ?>").click(function(){
					$("#InsDip").load('../PHP/Wfs_AggDip.php', {
								   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
								   IdFlu: '<?php echo $IdFlu; ?>'
							  }).show();
				});
			 </script>  			
			 <?php } ?>
			 </th>
			 <th class="thLiv" >PRIORITA'</th>					 
			 <th class="thImgIco" >TIPO</th>
			 <th class="thDipendenza" >DIPENDENZA</th>
			 <th class="thDipendenza" >DETTAGLIO</th>
			 
		     <th ><input type="checkbox" name="ChkRilascia_<?php echo $IdFlu; ?>" value="1" >SEL PER RILASCIO</th>
		</tr>
	<?php

	$sqlLG="SELECT 
	     ID_LEGAME
        ,ID_WORKFLOW
        ,PRIORITA
        ,TIPO
		,ID_DIP
        ,CASE L.TIPO 
		WHEN 'F' THEN
		   ( SELECT FLUSSO FROM WFS.FLUSSI WHERE ID_FLU = L.ID_DIP )
		WHEN 'C' THEN
		  ( SELECT CARICAMENTO FROM WFS.CARICAMENTI WHERE ID_CAR = L.ID_DIP )
		WHEN 'L' THEN
		  ( SELECT LINK FROM WFS.LINKS WHERE ID_LINK = L.ID_DIP )
		WHEN 'E' THEN
		  ( SELECT ELABORAZIONE FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )
		WHEN 'V' THEN
		  ( SELECT VALIDAZIONE FROM WFS.VALIDAZIONI WHERE ID_VAL = L.ID_DIP )
		END AS DIPENDENZA
        ,CASE L.TIPO 
		WHEN 'F' THEN
		   ( SELECT DESCR FROM WFS.FLUSSI WHERE ID_FLU = L.ID_DIP )
		WHEN 'C' THEN
		  ( SELECT DESCR FROM WFS.CARICAMENTI WHERE ID_CAR = L.ID_DIP )
		WHEN 'L' THEN
		  ( SELECT DESCR FROM WFS.LINKS WHERE ID_LINK = L.ID_DIP )
		WHEN 'E' THEN
		  ( SELECT DESCR FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )
		WHEN 'V' THEN
		  ( SELECT DESCR FROM WFS.VALIDAZIONI WHERE ID_VAL = L.ID_DIP )
		END AS DESCR
		,CASE L.TIPO 
		WHEN 'F' THEN
		   null
		WHEN 'C' THEN
		  ( SELECT NOME_INPUT FROM WFS.CARICAMENTI WHERE ID_CAR = L.ID_DIP )
		  ||
		  ( SELECT ' - Conferma Dato: '||CONFERMA_DATO FROM WFS.CARICAMENTI WHERE ID_CAR = L.ID_DIP )
		WHEN 'L' THEN
		  ( SELECT TARGET FROM WFS.LINKS WHERE ID_LINK = L.ID_DIP )
		WHEN 'E' THEN
		( SELECT C.SHELL FROM WFS.ELABORAZIONI E , WORK_CORE.CORE_SH_ANAG C 
		  WHERE 1=1
		  AND E.ID_SH = C.ID_SH
		  AND E.ID_ELA = L.ID_DIP )
		WHEN 'V' THEN
		  null
		END AS DETT
		,CASE L.TIPO 
		WHEN 'L' THEN
		  ( SELECT LS.TIPO FROM WFS.LINKS LS WHERE ID_LINK = L.ID_DIP )
		ELSE
		  null
		END AS TLINK	
		,CASE L.TIPO 
		WHEN 'E' THEN
		  ( SELECT READONLY FROM WFS.ELABORAZIONI E WHERE ID_ELA = L.ID_DIP )
		ELSE
		  null
		END AS READONLY			
		,CASE L.TIPO 
		WHEN 'V' THEN
		  ( SELECT EXTERNAL FROM WFS.VALIDAZIONI E WHERE ID_VAL = L.ID_DIP )
		ELSE
		  null
		END AS EXTERNAL			
		,CASE L.TIPO 
		WHEN 'E' THEN
		  ( SELECT SALTA_ELAB FROM WFS.ELABORAZIONI E WHERE ID_ELA = L.ID_DIP )
		ELSE
		  null
		END AS SALTA			
		FROM 
			WFS.LEGAME_FLUSSI L
		WHERE 1=1
		AND ID_FLU = $IdFlu
		ORDER BY 3, 4, 5";
	$stmtLG=db2_prepare($conn, $sqlLG);
	$resLG=db2_execute($stmtLG);
	while ($rowLG = db2_fetch_assoc($stmtLG)) {
		$IdLegame=$rowLG['ID_LEGAME'];
		$Prio=$rowLG['PRIORITA'];
		$Tipo=$rowLG['TIPO'];
		$Dipendenza=$rowLG['DIPENDENZA'];
		$IdDip=$rowLG['ID_DIP'];
		$IdDesc=$rowLG['DESCR'];
		$Dett=$rowLG['DETT'];
		$TLink=$rowLG['TLINK'];
		$TROnly=$rowLG['READONLY'];
		$TExternal=$rowLG['EXTERNAL'];
		$TSalta=$rowLG['SALTA'];
		
		switch ( $Tipo ){
		   case "F":
			   $ImgTipo="Flusso";
			   $ShowDett=false;
			   break;
		   case "E":
			   if ( "$TSalta" == "N" ){
			     $ImgTipo="Elaborazione";
			   }else{
				 $ImgTipo="SaltaElab";  
			   }
			   $ShowDett=true;
			   break;
		   case "V":
			   $ImgTipo="Valida";
			   $ShowDett=true;
			   break;
		   case "C":
			   $ImgTipo="Carica";
			   $ShowDett=true;
			   break;
		   case "L":
			   $ImgTipo="Link";
			   if ( "$TLink" == "I" ){ $ImgTipo="Setting"; }
			   $ShowDett=true;
			   break;			   
		}								
		
		?>
		<tr>
		 <td class="thModImgIco" >
		   <?php if ( "$TServer" != "PROD USER" ){ ?>
		   <div id="DelDett<?php echo $IdLegame; ?>" class="Plst" onclick="DelDip(<?php echo $IdDip; ?>,'<?php echo $Tipo; ?>',<?php echo $IdLegame; ?>)" ><img class="ImgIco" src="../images/Cestino.png" ></div>
		   <?php } ?>
		 </td>
		 <td class="thModImgIco" >
		   <?php if ( "$TServer" != "PROD USER" ){ ?>
		   <div id="ModDett<?php echo $IdLegame; ?>" class="Plst" onclick="ModDip(<?php echo $IdFlu; ?>,<?php echo $IdDip; ?>,'<?php echo $Tipo; ?>','<?php echo $TLink; ?>')" ><img class="ImgIco" src="../images/Matita.png" ></div>
		   <?php } ?>
		 </td>			   
		 <td class="thLiv"><div  class="Liv" ><?php  echo $Prio;  ?></div></td>
		 <td class="thImgIco"  title="<?php echo $IdDip; ?>" ><img class="ImgIco" src="../images/<?php echo $ImgTipo; ?>.png" ></td>
		 <td class="thDipendenza"><div  class="Dipendenza" ><?php 
		 if ( ( "$Dett" == "" and "$Tipo" == "E" ) || ( substr($Dett,0,8) == "WFS_TEST" and  "$Tipo" == "C" ) ){ ?><img class="ImgIco" src="../images/Lab.png" title="Laboratorio" ><?php }
		 echo "$Dipendenza "; if  ( "$IdDesc" != "" ){ echo " ($IdDesc)"; } 
		 ?></div>
         </td>
		 <td colspan=2 ><?php echo $Dett; 
		 if ( "$TROnly" == "S" ){ ?><img class="ImgIco" src="../images/bandiera.png" ><?php }
		 if ( "$TExternal" == "Y" ){ ?><div class="ImgIco" style="float:left;color:RED;" >EXTERNAL</div><?php }
		 ?></td>
		 </tr>
		<?php
	}
	?>				 
	</table>
	<script>
	    function DelDip(vIdDip,vTipo,vIdLeg){
		   
		  var re = confirm('Do you want remove the Dependence?');
		  if ( re == true) {   
		    $('#Azione').val('R'+vTipo);
		    
		    var input = $("<input>")
		    .attr("type", "hidden")
		    .attr("name", "IdDip")
		    .val(vIdDip);
		    $('#FormMain').append($(input));
		    
		    var input = $("<input>")
		    .attr("type", "hidden")
		    .attr("name", "IdLeg")
		    .val(vIdLeg);
		    $('#FormMain').append($(input));


		    $("#Waiting").show();
		    $('#FormMain').submit();
		  }
		}
	
		function PulChiudi(){ 
			$('#InsDip').empty().hide();
		};
		
		  
	</script>  
	<?php
}
?>
